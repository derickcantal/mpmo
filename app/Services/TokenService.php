<?php

namespace App\Services;

use App\Models\TokenMetric;
use App\Models\TokenEvent;
use App\Models\price_histories;
use App\Models\transactions;
use App\Models\User;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TokenService
{
    protected TokenMetric $metric;
    protected int $precision = 4;

    // Percentage fee for convert and redeem
    protected float $convertFeeRate = 0.05;
    protected float $redeemFeeRate  = 0.10;

    public function __construct()
    {
        // Initialize or seed the token metric, including price
        $this->metric = TokenMetric::firstOrCreate(
            ['symbol' => 'MPMO'],
            ['max_supply' => 1000000.0000, 'total_supply' => 1000000.0000, 'price' => 1.0]
        );

        // Backfill price if null
        if (is_null($this->metric->price)) {
            $this->metric->price = 1.0;
            $this->metric->save();
        }
    }

    public function getMetric(): TokenMetric
    {
        return $this->metric;
    }

    /**
     * Convert TRX to MPMO using a dynamic rate based on current price (TRX per MPMO)
     */
    public function convert($user, float $trxAmount)
    {
        $prec   = $this->precision;
        $feeRate = $this->convertFeeRate;

        // Current price is TRX per 1 MPMO; invert to get MPMO per 1 TRX
        $currentPrice     = $this->metric->price;
        $mpmoPerTrx       = 1 / $currentPrice;

        // compute gross MPMO, fee, and net
        $grossMpmo = round($trxAmount * $mpmoPerTrx, $prec);
        $feeMpmo   = round($grossMpmo * $feeRate, $prec);
        $netMpmo   = round($grossMpmo - $feeMpmo, $prec);

        $metric = $this->metric;

        DB::transaction(function() use($user, $trxAmount, $grossMpmo, $feeMpmo, $netMpmo, $metric) {
            // Update balances on wallet and user
            $wallet = $user->wallets()->firstOrFail();
            $wallet->decrement('trx_balance', $trxAmount);
            $wallet->increment('mpmo_balance', $netMpmo);
            $user->update([
                'trx_balance'  => $wallet->trx_balance,
                'mpmo_balance' => $wallet->mpmo_balance,
            ]);

            // Supply and treasury adjustments
            $metric->increment('total_supply', $grossMpmo);
            $metric->increment('treasury_balance', $feeMpmo);

            // Log events and tx
            TokenEvent::create([
                'userid'          => $user->userid,
                'token_metric_id' => $metric->id,
                'type'            => 'conversion',
                'amount'          => $netMpmo,
                'fee'             => $feeMpmo,
                'meta'            => ['trx' => $trxAmount],
            ]);

            transactions::create([
                'tokenid'        => 0,
                'tokenname'      => 'MPMO',
                'txnhash'        => $this->generateUniqueCode(),
                'txntype'        => 'conversion',
                'cwid'           => $wallet->cwid,
                'timerecorded'   => Carbon::now()->format('Y-m-d H:i:s'),
                'addresssend'    => $wallet->cwaddress,
                'addressreceive' => $wallet->cwaddress,
                'created_by'     => $user->userid,
                'mod'            => 0,
                'status'         => 'Success',
                'userid'         => $user->userid,
                'trx_amount'     => $trxAmount,
                'mpmo_gross'     => $grossMpmo,
                'mpmo_fee'       => $feeMpmo,
                'mpmo_net'       => $netMpmo,
                'type'           => 'conversion',
                'meta'           => []
            ]);

            // Recalculate dynamic price
            $this->updatePrice();
        });
    }

    /**
     * Redeem MPMO back to TRX using dynamic price
     */
    public function redeem($user, float $mpmoAmount)
    {
        $prec   = $this->precision;
        $feeRate = $this->redeemFeeRate;

        // current conversion: MPMO→TRX uses price per MPMO
        $currentPrice = $this->metric->price;

        // compute gross TRX, fee in MPMO, net MPMO, then net TRX
        $grossTrxRaw = $mpmoAmount * $currentPrice;
        $grossTrx    = round($grossTrxRaw, $prec);
        $feeMpmo     = round($mpmoAmount * $feeRate, $prec);
        $netMpmo     = round($mpmoAmount - $feeMpmo, $prec);
        $netTrxRaw   = $netMpmo * $currentPrice;
        $netTrx      = round($netTrxRaw, $prec);

        $metric = $this->metric;

        if ($netMpmo > $metric->total_supply) {
            throw new \Exception("Redeem amount {$netMpmo} exceeds current supply {$metric->total_supply}.");
        }

        DB::transaction(function() use($user, $mpmoAmount, $grossTrx, $feeMpmo, $netMpmo, $netTrx, $metric) {
            $user->decrement('mpmo_balance', $mpmoAmount);
            $user->increment('trx_balance', $netTrx);
            $wallet = $user->wallets()->firstOrFail();
            $wallet->decrement('mpmo_balance', $mpmoAmount);
            $wallet->increment('trx_balance', $netTrx);

            $metric->decrement('total_supply', $netMpmo);
            $metric->increment('treasury_balance', $feeMpmo);

            TokenEvent::create([
                'userid'          => $user->userid,
                'token_metric_id' => $metric->id,
                'type'            => 'burn',
                'amount'          => $feeMpmo,
                'meta'            => ['from_redeem' => true],
            ]);

            TokenEvent::create([
                'userid'          => $user->userid,
                'token_metric_id' => $metric->id,
                'type'            => 'redeem',
                'amount'          => $netTrx,
                'fee'             => $feeMpmo,
                'meta'            => ['mpmo' => $mpmoAmount],
            ]);

            transactions::create([
                'tokenid'        => 0,
                'tokenname'      => 'TRX',
                'txnhash'        => $this->generateUniqueCode(),
                'txntype'        => 'redeem',
                'cwid'           => $wallet->cwid,
                'timerecorded'   => Carbon::now()->format('Y-m-d H:i:s'),
                'addresssend'    => $wallet->cwaddress,
                'addressreceive' => $wallet->cwaddress,
                'created_by'     => $user->userid,
                'mod'            => 0,
                'status'         => 'Success',
                'userid'         => $user->userid,
                'trx_amount'     => $netTrx,
                'mpmo_gross'     => $mpmoAmount,
                'mpmo_fee'       => $feeMpmo,
                'mpmo_net'       => $netMpmo,
                'type'           => 'redeem',
                'meta'           => []
            ]);

            $this->updatePrice();
        });
    }

    /**
     * Recalculate and persist dynamic price and its history
     */
    protected function updatePrice()
    {
        $circ = $this->metric->circulating_supply;
        $max  = $this->metric->max_supply;
        $prec = $this->precision;

        // Bonding curve: price rises from 1 → 2 as supply goes 0 → max
        $ratio = $circ / $max;
        $newPrice = round(1.0 * (1 + $ratio), $prec);

        $this->metric->update(['price' => $newPrice]);

        price_histories::create([
            'token_metric_id' => $this->metric->id,
            'price'           => $newPrice,
        ]);
    }

    /**
     * Generate random unique code
     */
    public function generateUniqueCode()
    {
        $chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $code = '';
        while (strlen($code) < 64) {
            $code .= $chars[random_int(0, strlen($chars) - 1)];
        }
        return $code;
    }

    public function airdrop(array $allocations)
    {
        $metric = $this->metric;

        DB::transaction(function() use($allocations, $metric) {
            foreach ($allocations as $userId => $amount) {
                if ($metric->treasury_balance < $amount) {
                    throw new \Exception("Not enough tokens in treasury to airdrop {$amount} to user {$userId}");
                }

                // 1) debit treasury
                $metric->decrement('treasury_balance', $amount);

                // 2) credit user wallet
                $user   = User::findOrFail($userId);
                $wallet = $user->wallets()->firstOrFail();
                $wallet->increment('mpmo_balance', $amount);
                $user->increment('mpmo_balance', $amount);

                // 3) record event
                TokenEvent::create([
                    'userid'          => $userId,
                    'token_metric_id' => $metric->id,
                    'type'            => 'airdrop',
                    'amount'          => $amount,
                    'fee'             => 0,
                    'meta'            => ['reason'=>'monthly airdrop'],
                ]);
            }
        });
    }

    /**
     * Distribute staking rewards from treasury.
     * @param  array  $rewards  [ userId => rewardAmount, … ]
     */
    public function distributeStakingRewards(array $rewards)
    {
        $metric = $this->metric;

        DB::transaction(function() use($rewards, $metric) {
            foreach ($rewards as $userId => $amount) {
                if ($metric->treasury_balance < $amount) {
                    throw new \Exception("Not enough treasury for staking reward {$amount} to user {$userId}");
                }

                $metric->decrement('treasury_balance', $amount);

                $user   = User::findOrFail($userId);
                $wallet = $user->wallets()->firstOrFail();
                $wallet->increment('mpmo_balance', $amount);
                $user->increment('mpmo_balance', $amount);

                TokenEvent::create([
                    'userid'          => $userId,
                    'token_metric_id' => $metric->id,
                    'type'            => 'staking_reward',
                    'amount'          => $amount,
                    'fee'             => 0,
                    'meta'            => ['staked_at'=>now()->toDateTimeString()],
                ]);
            }
        });
    }

    /**
     * Incentivize liquidity provision by sending tokens from treasury.
     * @param  int    $poolId
     * @param  array  $allocations  [ userId => amount, … ]
     */
    public function incentivizeLiquidity(int $poolId, array $allocations)
    {
        $metric = $this->metric;

        DB::transaction(function() use($poolId, $allocations, $metric) {
            foreach ($allocations as $userId => $amount) {
                if ($metric->treasury_balance < $amount) {
                    throw new \Exception("Insufficient treasury for LP incentive");
                }

                $metric->decrement('treasury_balance', $amount);

                $user   = User::findOrFail($userId);
                $wallet = $user->wallets()->firstOrFail();
                $wallet->increment('mpmo_balance', $amount);
                $user->increment('mpmo_balance', $amount);

                TokenEvent::create([
                    'userid'          => $userId,
                    'token_metric_id' => $metric->id,
                    'type'            => 'liquidity_incentive',
                    'amount'          => $amount,
                    'fee'             => 0,
                    'meta'            => ['pool_id'=>$poolId],
                ]);
            }
        });
    }
}
