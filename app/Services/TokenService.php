<?php

namespace App\Services;

use App\Models\TokenMetric; 
use App\Models\TokenEvent; 
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TokenService
{
    protected $metric;
    public function __construct()
    {
        $this->metric = TokenMetric::firstOrCreate(['symbol'=>'MPMO'], ['max_supply'=>1000000,'total_supply'=>1000000]);
    }

    public function convert($user, float $trxAmount)
    {
        $rate = 3;
        $feeRate = 0.02;
        $gross = (int) floor($trxAmount * $rate);
        $fee   = (int) floor($gross * $feeRate);
        $net   = $gross - $fee;

        DB::transaction(function() use($user, $trxAmount, $gross, $fee, $net) {
            // Update user and wallet
            $user->decrement('trx_balance', $trxAmount);
            $user->increment('mpmo_balance', $net);
            if ($wallet = $user->wallet) {
                $wallet->decrement('trx_balance', $trxAmount);
                $wallet->increment('mpmo_balance', $net);
            }

            // Treasury & events
            $this->metric->increment('treasury_balance', $fee);
            TokenEvent::create(['token_metric_id'=>$this->metric->id,'user_id'=>$user->id,'type'=>'conversion','amount'=>$net,'fee'=>$fee,'meta'=>['trx'=>$trxAmount]]);

            // Transaction log
            Transaction::create([
                'user_id'=>$user->id,
                'trx_amount'=>$trxAmount,
                'mpmo_gross'=>$gross,
                'mpmo_fee'=>$fee,
                'mpmo_net'=>$net,
                'type'=>'conversion',
                'meta'=>[]]
            );
        });
    }

    // burn() and buyBack() omitted for brevity
}
?>