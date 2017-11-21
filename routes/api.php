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
    Route::group(['namespace'=>'Auth'], function () {
        Route::get('user-info', 'UserController@userInfo');
    });
    Route::group(['prefix'=>'doc','namespace'=>'Doc'],function(){
        Route::post('generate','DocController@generate');
    });
    Route::group(['prefix'=>'key','namespace'=>'Apis'],function(){
        Route::post('store-many','KeyStatementController@storeMany');
    });
});


