<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'short_name' => ['nullable', 'string', 'max:20'],
            'mobile_no' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'designation' => ['nullable','string', 'max:255'],
            'password' => $this->passwordRules(),
        ])->validate();

        dd('testing the user creaion ');
        return User::create([
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'short_name' => $input['short_name'],
            'mobile_no' => $input['mobile_no'],
            'email' => $input['email'],
            'designation' => $input['designation'] ?? null,
            'password' => Hash::make($input['password']),
        ]);
    }
}
