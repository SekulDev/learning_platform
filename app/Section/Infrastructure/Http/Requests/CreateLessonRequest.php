<?php

namespace App\Section\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateLessonRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => 'required|string|min:2|max:50',
        ];
    }
}
