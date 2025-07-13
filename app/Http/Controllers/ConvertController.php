<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ConvertController extends Controller
{
    public function show() { 
        return view('convert'); 
    }

    public function execute(Request $request, TokenService $svc)
    {
        $data = $request->validate(['trx_amount'=>'required|numeric|max:'.auth()->user()->trx_balance]);
        $svc->convert(auth()->user(), $data['trx_amount']);
        $user = auth()->user()->refresh();
        return response()->json(['message'=>'Converted!',
                                'trx_balance'=>$user->trx_balance,
                                'mpmo_balance'=>$user->mpmo_balance]);
    }
}
