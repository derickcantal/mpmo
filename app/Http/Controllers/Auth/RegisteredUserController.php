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
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use \Carbon\Carbon;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;

class RegisteredUserController extends Controller
{
    public function generateUniqueCode()
    {

        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersNumber = strlen($characters);
        $codeLength = 6;

        $code = '';

        while (strlen($code) < 6) {
            $position = rand(0, $charactersNumber - 1);
            $character = $characters[$position];
            $code = $code.$character;
        }

        if (temp_users::where('rfid', $code)->exists()) {
            $this->generateUniqueCode();
        }

        return $code;

    }

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
            'fullname' => ['required', 'string', 'max:255'],
            'refcode' => ['required', 'string', 'max:255', Rule::exists('users', 'refid'),],
        ]);

        $temp_users = temp_users::create([
            'refid' => $request->generateUniqueCode(),
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
            'mod' => 0,
            'copied' => 'N',
            'walletstatus' => 'Inactive',
            'status' => 'Inactive',
        ]);

        if($temp_users){
            // generate your verification URL however you like...
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id]
            );

            // send the welcome email
            Mail::to($user->email)
                ->send(new WelcomeMail($user, $verificationUrl));

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
