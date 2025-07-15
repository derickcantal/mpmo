<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use App\Models\User;
use App\Models\temp_users;

class RegistrationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

     /**
     * Prepare the data for validation.
     * Lowercase the email automatically.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('email')) {
            $this->merge([
                'email' => strtolower($this->input('email')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email'    => [
                'required',
                'string',
                'email',
                'max:255',
                // Ensure unique across users and temp_users using the mysql connection
                Rule::unique(User::class, 'email'),
                Rule::unique(temp_users::class, 'email'),
            ],
            'password' => [
                'required',
                'confirmed',
                Password::defaults(),
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique(User::class, 'username'),
                Rule::unique(temp_users::class, 'username'),
            ],
            'fullname' => [
                'required',
                'string',
                'max:255',
            ],
            'refcode'  => [
                'required',
                'string',
                'max:255',
                Rule::exists(User::class, 'refid'),
            ],
        ];
    }
}
