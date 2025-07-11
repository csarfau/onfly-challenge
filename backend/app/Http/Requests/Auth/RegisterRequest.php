<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      =>  ['required', 'string', 'max:255'],
            'email'     =>  ['required', 'email', 'max:255', 'unique:users,email'],
            'password'  =>  ['required', 'string', 'max:255', 'confirmed', Password::min(6)->mixedCase()],
        ];
    }
}
