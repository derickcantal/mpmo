<?php

namespace App\Http\Controllers;
use App\Models\TokenMetric;
use App\Services\TokenService;

use Illuminate\Http\Request;

class ConvertController extends Controller
{
    protected TokenService $tokenService;

    public function __construct(TokenService $tokenService)
    {
        $this->tokenService = $tokenService;
    }

    public function show() { 
        $metric = $this->tokenService->getMetric();
        return view('token.index',compact('metric')); 
    }

    public function execute(Request $request, TokenService $svc)
    {
        $user     = $request->user();
        $maxTrx   = $user->trx_balance;

        $data = $request->validate([
            'trx_amount' => [
                'required',
                'numeric',
                'gt:0',                       // must be > 0
                'max:'.$maxTrx,               // not more than they have
            ],
        ], [
            'trx_amount.max' => "You only have {$maxTrx} TRX available.",
        ]);

        $this->tokenService->convert($user, (float) $data['trx_amount']);

        return redirect()
            ->route('convert')
            ->with('success', 'TRX successfully converted to MPMO');

        $data = $request->validate(['trx_amount'=>'required|numeric|max:'.auth()->user()->trx_balance]);
        $svc->convert(auth()->user(), $data['trx_amount']);
        $user = auth()->user()->refresh();
        return response()->json(['message'=>'Converted!',
                                'trx_balance'=>$user->trx_balance,
                                'mpmo_balance'=>$user->mpmo_balance]);
    }
}
