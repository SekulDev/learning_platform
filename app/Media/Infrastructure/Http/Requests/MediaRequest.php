<?php

namespace App\Media\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:jpg,png,gif|max:8192',
        ];
    }
}
