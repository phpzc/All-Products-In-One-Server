<?php

namespace App\Http\Requests\Api;

class UserLoginRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|between:3,25',
            'password' => 'required|string|min:6',

        ];
    }

    public function attributes()
    {
        return [
            'name' => '用户名',
            'password' => '密码',
        ];
    }
}
