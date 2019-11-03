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
//系统创建的路由,无用
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//前端小程序拿到的地址：https://域名/api/v1/自己写的接口
Route::group(['prefix' => '/v1','namespace' => 'Api'], function () {
    // 授权登录
    Route::post('/user/login', 'UserController@weappLogin');
    // 获取节点
    Route::get('/nodes', 'NodeController@list');
    // 用户评论
    Route::post('/comment/submit', 'CommentController@post');

    //用户数据
        //个人主页数据
        Route::get('/userinfo/{id}', 'UserController@getUserIndex');
    
    
    // 话题
        Route::get('/topics', 'TopicController@list');
        Route::post('/topic/detail', 'TopicController@detail');
        Route::post('/topic/create', 'TopicController@create');
        Route::post('/topic/delete', 'TopicController@delete');
        Route::get('/topic/like/{id}', 'TopicController@like');
        Route::get('/topic/unlike/{id}', 'TopicController@unlike');
    // 心情
        Route::get('/moods', 'MoodController@list');
        Route::post('/mood/detail', 'MoodController@detail');
        Route::post('/mood/create', 'MoodController@create');
        Route::post('/mood/delete', 'MoodController@delete');
        Route::get('/mood/like/{id}', 'MoodController@like');
        Route::get('/mood/unlike/{id}', 'MoodController@unlike');
    // 工具
    Route::group(['namespace' => 'Tools'], function() {
        Route::post('files/upload', 'FilesController@upload');
        Route::post('tools/getcode', 'ToolsController@getcode');
    });
});
