<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostArticleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'article.title'       => ['required', 'string', 'max:200'],
            'article.description' => ['required', 'string', 'max:500'],
            'article.body'        => ['required', 'string', 'max:2000'],
            'article.tagList'     => ['required', 'array'],
            'article.tagList.*'   => ['required', 'string', 'max:20'],
        ];
    }
}
