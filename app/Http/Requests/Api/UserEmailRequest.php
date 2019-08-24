<?php

namespace App\Http\Requests\Api;


class UserEmailRequest extends FormRequest
{

    public function rules()
    {
        return [
            'email' => 'required|email|unique:users,email',
        ];
    }

    public function attributes()
    {
        return [
            'email' => '邮箱',
        ];
    }
}
