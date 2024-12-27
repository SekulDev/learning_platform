<?php

namespace App\Group\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMemberToGroupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|string|email|max:250',
        ];
    }
}
