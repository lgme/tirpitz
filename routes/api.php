<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix' => 'auth'], function () {
    Route::post('/login','AuthController@login');
    Route::post('/check','AuthController@check');
    Route::post('/register','AuthController@register');
    //Route::get('/activate/{token}','AuthController@activate');
    Route::post('/forgot-password','AuthController@password');
    Route::post('/reset-password','AuthController@reset');
    Route::post('/validate-password-reset','AuthController@validatePasswordReset');
});

Route::group(['middleware' => ['auth:api']], function () {
    Route::post('/auth/refresh', 'AuthController@refresh');
    Route::post('/auth/me', 'AuthController@me');
    Route::post('/auth/logout','AuthController@logout');

    Route::get('/users','UserController@index');
    Route::get('/users/{id}','UserController@show');
    Route::get('/users/{id}/devices','UserController@getDevices');
    Route::get('/users/{user_id}/devices/{device_id}','UserController@getDevice');
    Route::post('/users/{id}/devices','UserController@addDevice');
    Route::patch('/users/{id}','UserController@update');
    Route::delete('/users/{id}','UserController@destroy');
    Route::get('/users/{id}/projects','UserController@getProjects');
    Route::get('/users/{user_id}/projects/{project_id}','UserController@getProject');
    Route::get('/users/{id}/time-off','UserController@getTimeoffs');
    Route::get('/users/{id}/feedback','UserController@getFeedbacks');

    Route::get('/devices','DeviceController@index');
    Route::post('/devices','DeviceController@store');
    Route::get('/devices/{id}','DeviceController@show');
    Route::patch('/devices/{id}','DeviceController@update');
    Route::delete('/devices/{id}','DeviceController@destroy');

    Route::get('/projects','ProjectController@index');
    Route::post('/projects','ProjectController@store');
    Route::get('/projects/{id}','ProjectController@show');
    Route::patch('/projects/{id}','ProjectController@update');
    Route::delete('/projects/{id}','ProjectController@destroy');

    Route::get('/departments','DepartmentController@index');
    Route::post('/departments','DepartmentController@store');
    Route::get('/departments/{id}','DepartmentController@show');
    Route::patch('/departments/{id}','DepartmentController@update');
    Route::delete('/departments/{id}','DepartmentController@destroy');
});