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

Route::namespace('Api')->group(function () {
    Route::group(['middleware' => 'auth:api'], function () {
        Route::post('/auth/delete', 'Auth\DeleteController@delete');
        Route::get('/auth/user', function (Request $request) {
            return $request->user();
        });
    });
});
