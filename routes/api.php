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
    Route::group(['namespace'=>'Api'],function (){
        Route::resource('key-statement', 'KeyStatementController');
    });
    Route::group(['namespace'=>'Host'],function (){
        Route::resource('project-host', 'ProjectHostController');
        Route::resource('host', 'HostController');
    });

    Route::resource('user-project', 'Project\UserProjectController');
    Route::resource('module', 'Module\ModuleController');
    Route::resource('api', 'Api\ApiController');
    Route::resource('document', 'Doc\DocController');

    //非资源路由
    Route::group(['namespace'=>'Auth'], function () {
        Route::get('user-info', 'UserController@userInfo');
    });
    Route::group(['prefix'=>'doc','namespace'=>'Doc'],function(){
        Route::post('generate','DocController@generate');
        Route::post('markdown','DocController@saveMarkDownDoc');
    });
    Route::group(['prefix'=>'key','namespace'=>'Api'],function(){
        Route::post('store-many','KeyStatementController@storeMany');
    });
});


