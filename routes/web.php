<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------s
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/
/*Route::get('{vue_capture?}',function (){
    return view('app');
})->where('vue_capture','[\/\w.-]*');*/
Route::get('/{any}', 'SpaController@index')->where('any', '.*');
Auth::routes();
