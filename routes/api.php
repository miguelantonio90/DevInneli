<?php

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

Route::group(['namespace' => 'Api', 'as' => 'api.'], function () {

    //Testing routes
    Route::get('ping', 'PingController@index')->name('ping');
    Route::get('sendmail', 'PingController@sendMsg')->name('sendmail');
    //End Testing routes

    Route::post('login', 'LoginController@login')->name('login');

    Route::post('register', 'RegisterController@register')->name('register');

    Route::post('password/reset', 'ForgotPasswordController@sendPasswordResetLink')->name('password.reset');
    Route::post('password/reset/new/{hash}', 'ResetPasswordController@doReset')->name('password.reset.verify');

    Route::group(['middleware' => ['auth:api']], function () {

        Route::get('email/verify/{hash}', 'VerificationController@verify')->name('verification.verify');

        Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');

        Route::get('auth', 'AuthenticationController@user')->name('auth');
        Route::post('logout', 'LoginController@logout')->name('logout');

        Route::resource('user', 'UserController');
        Route::post('user/avatar/{id}', 'UserController@updateAvatar')->name('users.updateAvatar');

    });

});
