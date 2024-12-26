<?php

namespace App\Auth\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2|max:250',
            'email' => 'required|string|email|max:250',
            'password' => 'required|string|min:6|max:250'
        ];
    }
}
