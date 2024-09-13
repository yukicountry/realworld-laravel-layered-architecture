<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListArticlesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'tag'       => ['nullable', 'string', 'max:20'],
            'author'    => ['nullable', 'string', 'max:50'],
            'favorited' => ['nullable', 'string', 'max:50'],
            'limit'     => ['nullable', 'int', 'between:0,50'],
            'offset'    => ['nullable', 'int', 'min:0'],
        ];
    }
}
