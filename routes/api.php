<?php

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

Route::group(['namespace' => 'Api'], function() {
    Route::post('/register', 'AuthController@register');
    Route::post('/login', 'AuthController@login');
    Route::get('/verify/{code}', 'AuthController@verifyUser');

    Route::middleware('auth:api')->group(function (){
        Route::post('file/upload', 'FileController@uploadFile');
        Route::delete('file/remove/{fileId}', 'FileController@deleteFile');

        Route::apiResource('card', 'CardController');
        Route::apiResource('task', 'TasksController');
        Route::apiResource('user', 'UserController');
    });
});
