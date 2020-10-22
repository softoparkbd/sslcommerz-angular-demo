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

// Route::resource('api/items', 'SslcommerzController');


// // SSLCOMMERZ Start
// Route::get('/example1', 'SslCommerzPaymentController@exampleEasyCheckout');
// Route::get('/example2', 'SslCommerzPaymentController@exampleHostedCheckout');

// Route::post('/pay', 'SslCommerzPaymentController@index');
// Route::post('/pay-via-ajax', 'SslCommerzPaymentController@payViaAjax');

// Route::post('/success', 'SslCommerzPaymentController@success');
// Route::post('/fail', 'SslCommerzPaymentController@fail');
// Route::post('/cancel', 'SslCommerzPaymentController@cancel');

// Route::post('/ipn', 'SslCommerzPaymentController@ipn');
// //SSLCOMMERZ END