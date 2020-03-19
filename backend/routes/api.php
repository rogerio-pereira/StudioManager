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

Route::get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', 'ApiAuthController@login');
Route::post('/logout', 'ApiAuthController@logout');
Route::get('/auth-user', 'ApiAuthController@AuthUser');

Route::group(['middleware' => 'auth:api'], function () {
    Route::resource('customers', 'CustomerController');
    Route::resource('supliers', 'SuplierController');
    Route::resource('team', 'TeamController');
    Route::resource('products', 'ProductsController');
    Route::resource('events', 'EventsController');

    Route::get('/schedule', 'ScheduleController@index');
    Route::get('/schedule/{start_date}', 'ScheduleController@index');
    Route::get('/schedule/{start_date}/{end_date}', 'ScheduleController@index');
    
    Route::resource('sales', 'SalesController');
});
Route::get('payments/today', 'PaymentController@paymentsToday')->name('payments.today');
Route::get('payments/{id}', 'PaymentController@index')->name('payments.index');
Route::put('/payment/{id}/pay', 'PaymentController@pay')->name('payments.pay');