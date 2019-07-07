<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middle' =>'serializer:array',
], function($api) {

    //节流处理  登录相关
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function($api) {

    });

    //节流处理 访问相关
    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function($api) {
        //图片验证码
        $api->get('/captchas','CaptchasController@store');

    });
});

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:OPTIONS,GET,POST,DELETE,PUT'); // 允许option，get，post请求
header('Access-Control-Allow-Headers:x-requested-with');
