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

Route::group(['prefix' => 'sales-order','middleware' => ['web']], function() {
	Route::get('/', ['uses' => 'SalesOrderController@index','middleware' => ['permission','access_read:sales-order']]);
	Route::get('/create', ['uses' => 'SalesOrderController@create','middleware' => ['permission','access_create:sales-order']]);
	Route::get('/view/{slug}', ['uses' => 'SalesOrderController@view','middleware' => ['permission','access_read:sales-order']]);
	Route::get('/edit/{slug}', ['uses' => 'SalesOrderController@edit','middleware' => ['permission','access_update:sales-order']]);
	Route::get('/preview/{slug}', ['uses' => 'SalesOrderController@preview','middleware' => ['permission','access_read:sales-order']]);
	Route::post('/do-update/item', ['uses' => 'SalesOrderController@do_update_item','middleware' => ['access_update:sales-order']]);
	Route::post('/do-update/last_item', ['uses' => 'SalesOrderController@do_update_last_item','middleware' => ['permission','access_update:sales-order']]);
	Route::post('/do-delete/item', ['uses' => 'SalesOrderController@do_delete_item','middleware' => ['permission','access_delete:sales-order']]);
	Route::post('/do-update/other-cost', ['uses' => 'SalesOrderController@do_update_other_cost','middleware' => ['permission','access_update:sales-order']]);
	Route::post('/do-delete/other-cost', ['uses' => 'SalesOrderController@do_delete_other_cost','middleware' => ['permission','access_delete:sales-order']]);
	Route::post('/do-update', ['uses' => 'SalesOrderController@do_update','middleware' => ['permission','access_update:sales-order']]);
	Route::post('/set-invoice', ['uses' => 'SalesOrderController@set_invoice','middleware' => ['permission','access_update:sales-order']]);
	Route::post('/sent-email', ['uses' => 'SalesOrderController@sent_email','middleware' => ['permission','access_create:sales-order']]);
	Route::post('/do-delete', ['uses' => 'SalesOrderController@do_delete','middleware' => ['permission','access_delete:sales-order']]);
	Route::get('/feed/invoice/{slug}', ['uses' => 'SalesOrderController@feed_invoice']);
});