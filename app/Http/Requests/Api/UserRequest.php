<?php

namespace App\Http\Requests\Api;


class UserRequest extends FormRequest
{

    public function rules()
    {
        return [
            'name' => 'required|between:3,25|unique:users,name',
            'password' => 'required|string|min:6',
            'captcha_key' => 'required|string',
            'captcha_code' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'captcha_key' => '验证码 key',
            'captcha_code' => '验证码',
        ];
    }
}
