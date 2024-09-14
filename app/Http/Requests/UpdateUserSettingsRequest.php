<?php declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserSettingsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user.username' => ['sometimes', 'required', 'string', 'max:200'],
            'user.email'    => ['sometimes', 'required', 'max:200', 'email'],
            'user.password' => ['sometimes', 'required', 'string', 'between:4,200'],
            'user.bio'      => ['nullable', 'string', 'max:200'],
            'user.image'    => ['nullable', 'string', 'max:200'],
        ];
    }
}
