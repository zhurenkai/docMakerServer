<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Response;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Response::macro('success', function ($data=true) {
            return Response::json(['code' => 0, 'data' => $data]);
        });

        Response::macro('error', function ($error_code, $error_message = null, $status = 200, $sprintf = null) {
            $error_message = $error_message ? $error_message : (config('errors.'.$error_code) ? config('errors.'.$error_code) : '未设置错误信息');

            if ($sprintf) {
                $error_message = sprintf($error_message, $sprintf);
            }
            return Response::json(['code' => $error_code, 'error' => $error_message], $status);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
