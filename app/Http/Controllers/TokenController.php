<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TokenMetric;

class TokenController extends Controller
{
    public function showTokenInfo()
    {
        $token = TokenMetric::firstOrCreate(
            ['symbol'      => 'MPMO'],
            ['max_supply'  => 1000000,   // adjust to your real cap
             'total_supply'=> 1000000]   // initial circulating supply
        );
        return view('token.info', compact('token'));
    }

     public function info() {
        $metric = TokenMetric::where('symbol','MPMO')->first();
        return view('token.info', compact('metric'));
    }
}
