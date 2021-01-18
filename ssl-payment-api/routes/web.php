<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\SslcommerzController;

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
// Route::get('/api/items', 'App\Http\Controllers\SslcommerzController@index');
Route::resource('/api/items', 'App\Http\Controllers\SslCommerzPaymentController');
// Route::get('/api/items', 'App\Http\Controllers\SslCommerzPaymentController@index');

// Route::resource('api/items', 'SslcommerzController');


// // SSLCOMMERZ Start
Route::get('/example1', 'App\Http\Controllers\SslCommerzPaymentController@exampleEasyCheckout');
Route::get('/example2', 'App\Http\Controllers\SslCommerzPaymentController@exampleHostedCheckout');

Route::post('/pay', 'App\Http\Controllers\SslCommerzPaymentController@index');
Route::post('/pay-via-ajax', 'App\Http\Controllers\SslCommerzPaymentController@payViaAjax');

// Route::post('/success', 'App\Http\Controllers\SslCommerzPaymentController@success');
Route::post('/success', 'App\Http\Controllers\SslCommerzPaymentController@success2');
Route::post('/fail', 'App\Http\Controllers\SslCommerzPaymentController@fail');
Route::post('/cancel', 'App\Http\Controllers\SslCommerzPaymentController@cancel');

Route::post('/ipn', 'App\Http\Controllers\SslCommerzPaymentController@ipn');
// //SSLCOMMERZ END

Route::get('/my-api-call', 'App\Http\Controllers\SslCommerzPaymentController@apicall');