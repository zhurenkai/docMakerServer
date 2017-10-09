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

Route::group(['middleware' => 'auth:api'], function () {
    // 资源路由
    Route::resource('key-statement', 'Apis\KeyStatementController');
    Route::resource('project-host', 'Host\ProjectHostController');
    Route::resource('user-project', 'Project\UserProjectController');
    Route::resource('apis', 'Apis\ApiCOntroller');
    //非资源路由
    Route::group([], function () {
        Route::get('user-info', 'Auth\UserController@userInfo');
    });
});


