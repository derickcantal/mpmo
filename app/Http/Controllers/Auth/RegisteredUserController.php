<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\temp_users;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use \Carbon\Carbon;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $request->validate([
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class, 'unique:'.temp_users::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class, 'unique:'.temp_users::class],
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'birthdate' => ['required', 'string', 'max:255'],
            'mobile' => ['required', 'string', 'max:255'],
        ]);

        $temp_users = temp_users::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'avatar' => 'avatars/avatar-default.jpg',
            'username' => $request->username,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'birthdate' => $request->birthdate,
            'mobile_primary' => $request->mobile,
            'accesstype' => 'Temporary',
            'timerecorded' => $timenow,
            'created_by' => 'Self Registration',
            'trxbal' => 0,
            'usdtbal' => 0,
            'totalbal' => 0,
            'mod' => 0,
            'copied' => 'N',
            'status' => 'Inactive',
        ]);

        if($temp_users){
            return redirect()->route('register')
                        ->with('success','User creation success. Please wait for Admin Approval');
        }else{
            return redirect()->route('register')
                        ->with('failed','User creation failed. Please Contact Administrator');
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
