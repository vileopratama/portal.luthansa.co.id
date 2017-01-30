<?php

/*
|--------------------------------------------------------------------------
| Module Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for the module.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::group(['prefix' => 'report-order-payment','middleware' => ['web','permission','access_read:report-order-payment']], function() {
    Route::get('/', ['uses' => 'ReportOrderPaymentController@index','middleware' => ['access_read:report-order-payment']]);
    Route::get('/export/excel', ['uses' => 'ReportOrderPaymentController@export_excel','middleware' => ['access_create:report-order-payment']]);
});