<?php
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['namespace' => 'Admin'], function()
{
    // 模拟后台登录路由
    Route::post('manage/login', 'manageController@login');
    Route::get('manage/info', 'manageController@info');
    Route::post('manage/logout', 'manageController@logout');

    // 用户接口
    Route::get('user/list', 'userController@list');
    
    // 节点接口
    Route::get('node/list', 'nodeController@list');
    Route::post('node/create', 'nodeController@create');
    Route::post('node/edit', 'nodeController@edit');
    Route::post('node/del', 'nodeController@del');
    

});
