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

Route::group(['prefix' => 'sales-confirm-payment','middleware' => ['web','permission']], function() {
	Route::get('/', ['uses' => 'SalesConfirmPaymentController@index','middleware' => ['permission','access_read:sales-confirm-payment']]);
	Route::get('/view/{slug}', ['uses' => 'SalesConfirmPaymentController@view','middleware' => ['permission','access_read:sales-confirm-payment']]);
	Route::post('/do-update', ['uses' => 'SalesConfirmPaymentController@do_update','middleware' => ['permission','access_update:sales-confirm-payment']]);
});