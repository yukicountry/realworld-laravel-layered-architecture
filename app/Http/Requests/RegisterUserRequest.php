<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user.username' => ['required', 'string', 'max:200'],
            'user.email'    => ['required', 'max:200', 'email'],
            'user.password' => ['required', 'string', 'min:4'],
        ];
    }
}