<?php

namespace App\Http\Requests\Api;


class UserPasswordRequest extends FormRequest
{

    public function rules()
    {
        return [
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|min:6',
        ];
    }

    public function attributes()
    {
        return [
            'old_password' => '旧密码',
            'password' => '新密码',
        ];
    }
}
