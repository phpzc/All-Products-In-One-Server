<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //

        //dingo 接口路由上 未找到绑定的模型时 优化显示的错误信息  不报出具体的模型名称
        \API::error(function  (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException  $exception)  {
            throw new \Symfony\Component\HttpKernel\Exception\HttpException(404,  '404 Data Not Found');
        });

        //接口权限报错时  统一使用403状态码
        \API::error(function (\Illuminate\Auth\Access\AuthorizationException $exception) {
            abort(403, $exception->getMessage());//服务器理解请求 但是拒绝执行
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
