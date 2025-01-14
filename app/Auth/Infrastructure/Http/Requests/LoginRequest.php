<?php

namespace App\Auth\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:250',
            'password' => 'required|string|min:6|max:250'
        ];
    }
}
