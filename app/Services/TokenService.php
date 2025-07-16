<?php

namespace App\Services;

use App\Models\TokenMetric; 
use App\Models\TokenEvent; 
use App\Models\transactions;
use App\Models\price_histories;
use Illuminate\Support\Facades\DB;
use \Carbon\Carbon; 

class TokenService
{
    protected TokenMetric $metric;
    protected int $precision = 4;
    // Base price per MPMO in TRX (initial)
    protected float $basePrice = 1.0;
    
    public function __construct()
    {
        // Ensure token_metrics has a 'price' column
        $this->metric = TokenMetric::firstOrCreate(
            ['symbol' => 'MPMO'],
            ['max_supply' => 1000000.0000, 'total_supply' => 1000000.0000, 'price' => $this->basePrice]
        );
    }

    public function generateUniqueCode()
    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $charactersNumber = strlen($characters);
        $codeLength = 64;

        $code = '';

        while (strlen($code) < $codeLength) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (transactions::where('txnhash', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;

    }

    public function getMetric(): TokenMetric
    {
        return $this->metric;
    }

    public function convert($user, float $trxAmount)
    {
        $rate    = 3.0;
        $feeRate = 0.05;
        $prec    = $this->precision;

        $gross   = round($trxAmount * $rate, $prec);
        $fee     = round($gross * $feeRate, $prec);
        $net     = round($gross - $fee, $prec);
        $metric  = $this->metric;

        DB::transaction(function() use($user, $trxAmount, $gross, $fee, $net, $metric) {
            // update user balances
            $user->decrement('trx_balance', $trxAmount);
            $user->increment('mpmo_balance', $net);
            if ($wallet = $user->wallet) {
                $wallet->decrement('trx_balance', $trxAmount);
                $wallet->increment('mpmo_balance', $net);
            }

            // update supply and treasury
            $metric->increment('total_supply', $gross);
            $metric->increment('treasury_balance', $fee);

            // record conversion event
            TokenEvent::create([
                'userid'          => $user->userid,
                'token_metric_id' => $metric->id,
                'type'            => 'conversion',
                'amount'          => $net,
                'fee'             => $fee,
                'meta'            => ['trx' => $trxAmount],
            ]);

            // Transaction log
            transactions::create([
                'tokenid'=>0,
                'tokenname'=>'MPMO',
                'txnhash'=>$this->generateUniqueCode(),
                'txntype'=>'conversion',
                'cwid'=>auth()->user()->wallets[0]->cwid,
                'timerecorded'=>Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s'),
                'addresssend'=>auth()->user()->wallets[0]->cwaddress,
                'addressreceive'=>auth()->user()->wallets[0]->cwaddress,
                'created_by'=>$user->userid,
                'mod' => 0,
                'status'=>'Success',
                'userid'=>$user->userid,
                'trx_amount'=>$trxAmount,
                'mpmo_gross'=>$gross,
                'mpmo_fee'=>$fee,
                'mpmo_net'=>$net,
                'type'=>'conversion',
                'meta'=>[]]
            );
            $this->updatePrice();
        });
    }

    public function redeem($user, float $mpmoAmount)
    {
         $rate    = 3.0;
        $feeRate = 0.10;
        $prec    = $this->precision;

        $grossTrxRaw = $mpmoAmount / $rate;
        $grossTrx    = round($grossTrxRaw, $prec);
        $feeMpmo     = round($mpmoAmount * $feeRate, $prec);
        $netMpmo     = round($mpmoAmount - $feeMpmo, $prec);
        $netTrxRaw   = $netMpmo / $rate;
        $netTrx      = round($netTrxRaw, $prec);
        $metric      = $this->metric;

        DB::transaction(function() use($user, $mpmoAmount, $grossTrx, $feeMpmo, $netMpmo, $netTrx, $metric) {
            // update user balances
            $user->decrement('mpmo_balance', $mpmoAmount);
            $user->increment('trx_balance', $netTrx);
            if ($wallet = $user->wallet) {
                $wallet->decrement('mpmo_balance', $mpmoAmount);
                $wallet->increment('trx_balance', $netTrx);
            }

            // update supply and treasury
            $metric->decrement('total_supply', $netMpmo);
            $metric->increment('treasury_balance', $feeMpmo);

            // record burn event
            TokenEvent::create([
                'userid'          => $user->userid,
                'token_metric_id' => $metric->id,
                'type'            => 'burn',
                'amount'          => $feeMpmo,
                'meta'            => ['from_redeem' => true],
            ]);

            // record redeem event
            TokenEvent::create([
                'userid'          => $user->userid,
                'token_metric_id' => $metric->id,
                'type'            => 'redeem',
                'amount'          => $netTrx,
                'fee'             => $feeMpmo,
                'meta'            => ['mpmo' => $mpmoAmount],
            ]);

            // log transaction record
            transactions::create([
                'tokenid'       =>0,
                'tokenname'     =>'TRX',
                'txnhash'       =>$this->generateUniqueCode(),
                'txntype'       =>'redeem',
                'cwid'          =>auth()->user()->wallets[0]->cwid,
                'timerecorded'  =>Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s'),
                'addresssend'   =>auth()->user()->wallets[0]->cwaddress,
                'addressreceive'=>auth()->user()->wallets[0]->cwaddress,
                'created_by'    =>$user->userid,
                'mod'           => 0,
                'status'        =>'Success',
                'userid'        => $user->userid,
                'trx_amount'    => $netTrx,
                'mpmo_gross'    => $mpmoAmount,
                'mpmo_fee'      => $feeMpmo,
                'mpmo_net'      => $netMpmo,
                'type'          => 'redeem',
                'meta'          => [],
            ]);
            $this->updatePrice();
        });
    }

    protected function updatePrice()
    {
        $circ    = $this->metric->circulating_supply;
        $max     = $this->metric->max_supply;
        $prec    = $this->precision;

        // simple bonding curve: price increases as circulating supply grows
        $ratio   = $circ / $max;
        $price   = round($this->basePrice * (1 + $ratio), $prec);

        // update metric price
        $this->metric->update(['price' => $price]);

        // record history
        price_histories::create([
            'token_metric_id' => $this->metric->id,
            'price'           => $price,
        ]);
    }
}
?>