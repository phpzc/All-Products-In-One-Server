<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserEmailRequest;
use App\Http\Requests\Api\UserLoginRequest;
use App\Http\Requests\Api\UserPasswordRequest;
use App\Transformers\UserTransformer;
use Illuminate\Http\Request;
use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
     * 返回当前用户最新信息
     */
    public function me()
    {
        $user = $this->user();

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * 修改邮箱
     * @param UserEmailRequest $request
     */
    public function email(UserEmailRequest $request)
    {
        $user = $this->user();

        $email = $request->email;

        $user->email = $email;
        $user->save();

        return $this->response->item($user, new UserTransformer());
    }

    /**
     * 修改密码
     * @param UserPasswordRequest $request
     */
    public function update_password(UserPasswordRequest $request)
    {
        $user = $this->user();

        if( Hash::check($request->old_password, $user->password)) {

            $user->password = bcrypt($request->password);
            $user->save();

            return $this->response->noContent();
        }else{
            return $this->errorResponse('旧密码不正确',422);
        }


    }

    /**
     * 修改头像
     */
    public function avatar()
    {

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
