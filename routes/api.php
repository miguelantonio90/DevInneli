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

Route::group(['namespace' => 'Api', 'as' => 'api.', 'middleware' => ['respond.json']], function () {

    //Testing routes
    Route::get('ping', 'PingController@index')->name('ping');
    Route::get('sendmail', 'PingController@sendMsg')->name('sendmail');
    //End Testing routes

    Route::post('login', 'LoginController@login')->name('login');
    Route::post('login/pincode', 'LoginController@loginPincode')->name('login.pincode');

    Route::post('register', 'RegisterController@register')->name('register');

    Route::post('password/email/reset', 'ForgotPasswordController@sendPasswordResetLink')->name('password.reset');
    Route::post('password/reset/new/{hash}', 'ResetPasswordController@doReset')->name('password.reset.verify');

    Route::get('company/email/{email}', 'CompanyController@companyByEmail');

    Route::group(['middleware' => ['auth:api', 'respond.json']], function () {

        Route::get('email/verify/{hash}', 'VerificationController@verify')->name('verification.verify');

        Route::get('email/resend', 'VerificationController@resend')->name('verification.resend');

        Route::get('auth', 'AuthenticationController@user')->name('auth');
        Route::post('logout', 'LoginController@logout')->name('logout');

        Route::resource('user', 'UserController');
        Route::post('user/avatar/{id}', 'UserController@updateAvatar')->name('users.updateAvatar');

        Route::resource('access', 'AccessController');

        Route::resource('shop', 'ShopController');

        Route::resource('company', 'CompanyController');
        Route::post('company/logo/{id}', 'CompanyController@updateLogo')->name('company.updateLogo');

        Route::resource('client', 'ClientController');

        Route::resource('category', 'CategoryController');

        Route::resource('payment', 'PaymentController');

        Route::resource('tax', 'TaxController');

        Route::resource('discount', 'DiscountController');

        Route::resource('supplier', 'SupplierController');

        Route::resource('article', 'ArticleController');

        Route::resource('inventory', 'InventoryController');

        Route::resource('sale', 'SaleController');

        Route::resource('assistance', 'AssistanceController');

        Route::resource('expense/category', 'ExpenseCategoryController');

        Route::resource('exchange/rate', 'ExchangeRateController');

        Route::resource('type/order', 'TypeOfOrderController');

        Route::put('type/order/edit/{order}', 'TypeOfOrderController@setPrincipal')->name('order.set.principal');

    });

});
