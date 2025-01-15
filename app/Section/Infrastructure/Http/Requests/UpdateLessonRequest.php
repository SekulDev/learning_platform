<?php

namespace App\Section\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'string|min:2|max:50',
            'content' => 'array'
        ];
    }
}
