<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'article.title'       => ['sometimes', 'required', 'string', 'max:200'],
            'article.description' => ['sometimes', 'required', 'string', 'max:500'],
            'article.body'        => ['sometimes', 'required', 'string', 'max:2000'],
            'article.tagList'     => ['sometimes', 'required', 'array'],
            'article.tagList.*'   => ['sometimes', 'required', 'string', 'max:20'],
        ];
    }
}
