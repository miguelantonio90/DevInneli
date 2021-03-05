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
    Route::post('shop/data', 'ShopController@getShopData')->name('getShopData');
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
        Route::post('user/userLogin', 'UserController@userLogin')->name('users.userLogin');
        Route::post('user/read/notification/{id}', 'UserController@readNotification')->name('readNotification');

        Route::resource('access', 'AccessController');
        Route::resource('keys', 'KeyPositionsController');

        Route::resource('shop', 'ShopController');
        Route::post('shop/no_config', 'ShopController@getShopNoConfig')->name('getShopNoConfig');

        Route::resource('online', 'ConfigController');
        Route::resource('boxes', 'BoxController');
        Route::post('boxes/sendOpenCloseBox', 'BoxController@sendOpenClose')->name('boxes.sendOpenClose');
        Route::post('boxes/getOpenClose', 'BoxController@getOpenClose')->name('boxes.getOpenClose');

        Route::resource('company', 'CompanyController');
        Route::post('company/logo/{id}', 'CompanyController@updateLogo')->name('company.updateLogo');

        Route::resource('client', 'ClientController');

        Route::resource('category', 'CategoryController');
        Route::post('category/shops', 'CategoryController@getCategoriesShop')->name('getCategoriesShop');

        Route::resource('payment', 'PaymentController');
        Route::resource('refund', 'RefoundController');

        Route::resource('tax', 'TaxController');

        Route::resource('discount', 'DiscountController');

        Route::resource('supplier', 'SupplierController');
        Route::post('supplier/getClients', 'SupplierController@getSupplierClients')->name('getSupplierClients');

        Route::resource('article', 'ArticleController');
        Route::post('article/import', 'ArticleController@import')->name('articleImport');
        Route::post('article/byCategory', 'ArticleController@byCategory')->name('byCategory');
        Route::get('article/number/get', 'ArticleController@findArticleNumber')->name('numberArticle');

        Route::resource('inventory', 'InventoryController');
        Route::resource('supply', 'SupplyController');
        Route::post('supply/number/facture', 'SupplyController@findNumberFacture')->name('numberFacture');

        Route::resource('sale', 'SaleController');
        Route::post('sale/category', 'SaleController@saleCategory')->name('saleCategory');
        Route::post('sale/payment', 'SaleController@salePayment')->name('salePayment');
        Route::post('sale/product', 'SaleController@saleByProduct')->name('saleByProduct');
        Route::post('sale/employer', 'SaleController@saleByEmployer')->name('saleByEmployer');
        Route::get('sale/by_limit/{limit}', 'SaleController@findSalesByLimit')->name('saleByLimit');
        Route::get('sale/sales/static', 'SaleController@getTotalSalesStatic')->name('saleStatics');
        Route::get('sale/number/facture', 'SaleController@findNumberFacture')->name('numberFacture');

        Route::resource('assistance', 'AssistanceController');

        Route::resource('expense/category', 'ExpenseCategoryController');

        Route::resource('exchange/rate', 'ExchangeRateController');

        Route::resource('type/order', 'TypeOfOrderController');

        Route::put('type/order/edit/{order}', 'TypeOfOrderController@setPrincipal')->name('order.set.principal');

        Route::resource('modifiers', 'ModifiersController');

    });

});
