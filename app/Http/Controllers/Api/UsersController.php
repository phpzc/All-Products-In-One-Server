<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserLoginRequest;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use App\Models\User;

class UsersController extends Controller
{
    //注册用户
    public function store(UserRequest $request)
    {
        $captchaData = \Cache::get($request->captcha_key);

        if (!$captchaData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($captchaData['code'], $request->captcha_code)) {
            // 返回401
            return $this->response->errorUnauthorized('验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'password' => bcrypt($request->password),
        ]);

        // 清除验证码缓存
        \Cache::forget($request->captcha_key);

        return $this->respondWithToken($user)->setStatusCode(201);
    }


    public function login(UserLoginRequest $request)
    {
        $name = $request->input('name');

        $user = User::query()->where(['name'=>$name])->first();

        if(!$user){
            return $this->errorResponse('用户不存在');
        }

        if( \Auth::guard('api')->attempt(['name'=>$name,'password'=>$request->password]))
        {
            return $this->respondWithToken($user);
        }else{
            return $this->errorResponse('密码不正确');
        }

    }


    /**
     * 登录 注册 返回用户信息与jwt access_token
     * @param User $user
     * @return \Dingo\Api\Http\Response
     */
    protected function respondWithToken(User $user)
    {
        $token = \Auth::guard('api')->fromUser($user);

        return $this->response->item($user, new UserTransformer())->setMeta(
            [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60
            ]
        );

    }
}
