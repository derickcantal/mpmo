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
use App\Http\Requests\RegistrationRequest;
use Illuminate\Support\Facades\URL;


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
    public function store(RegistrationRequest $request): RedirectResponse
    {
        $timenow = Carbon::now()->timezone('Asia/Manila')->format('Y-m-d H:i:s');

        $data = $request->validated();

        $tempUser = temp_users::create([
            'refid'        => $data['refcode'],
            'email'        => $data['email'],
            'password'     => Hash::make($data['password']),
            'avatar'       => 'avatars/avatar-default.jpg',
            'username'     => $data['username'],
            'fullname'     => $data['fullname'],
            'accesstype'   => 'Temporary',
            'role'         => 'Member',
            'timerecorded' => now(),
            'created_by'   => 'Online',
            'mod'          => 0,
            'copied'       => 'N',
            'walletstatus' => 'Inactive',
            'status'       => 'Inactive',
        ]);

        if ($tempUser) {
            
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                [
                    'id'   => $tempUser->getKey(),
                    'hash' => sha1($tempUser->getEmailForVerification()),
                ]
            );

            Mail::to($tempUser->email)
                ->send(new WelcomeMail($tempUser, $verificationUrl));

            return redirect()->route('register')
                            ->with('success','User creation success. Please wait for Admin Approval');
        }

        return redirect()->route('register')
                        ->with('failed','User creation failed. Please Contact Administrator');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}
