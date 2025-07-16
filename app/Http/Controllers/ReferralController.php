<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class ReferralController extends Controller
{
    public function handle($code)
    {
        // find the user who owns this code
        if ($referrer = User::where('referral_code', $code)->first()) {
        session([
            'referrer_id'   => $referrer->id,
            'referrer_code' => $referrer->referral_code,
        ]);
    }


        // send them to the standard registration page
        return redirect()->route('register');
    }
}
