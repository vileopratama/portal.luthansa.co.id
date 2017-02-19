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

Route::group(['prefix' => 'sales-invoice','middleware' => ['web']], function() {
	Route::get('/', ['uses' => 'SalesInvoiceController@index','middleware' => ['permission','access_read:sales-invoice']]);
	Route::get('/view/{slug}', ['uses' => 'SalesInvoiceController@view','middleware' => ['permission','access_read:sales-invoice']]);
	Route::post('/view-armada', ['uses' => 'SalesInvoiceController@view_armada','middleware' => ['permission','access_read:sales-invoice']]);
	Route::get('/edit/{slug}', ['uses' => 'SalesInvoiceController@edit','middleware' => ['permission','access_update:sales-invoice']]);
	Route::get('/preview/{slug}', ['uses' => 'SalesInvoiceController@preview','middleware' => ['permission','access_read:sales-invoice']]);
	Route::get('/print/payment/{slug}', ['uses' => 'SalesInvoiceController@print_payment','middleware' => ['permission','access_read:sales-invoice']]);
	Route::post('/do-update/item', ['uses' => 'SalesInvoiceController@do_update_item','middleware' => ['permission','access_update:sales-invoice']]);
	Route::post('/do-update/last_item', ['uses' => 'SalesInvoiceController@do_update_last_item','middleware' => ['permission','access_update:sales-invoice']]);
	Route::post('/do-delete/item', ['uses' => 'SalesInvoiceController@do_delete_item','middleware' => ['permission','access_delete:sales-invoice']]);
	Route::post('/do-update/other-cost', ['uses' => 'SalesInvoiceController@do_update_other_cost','middleware' => ['permission','access_update:sales-invoice']]);
	Route::post('/do-delete/other-cost', ['uses' => 'SalesInvoiceController@do_delete_other_cost','middleware' => ['permission','access_delete:sales-invoice']]);
	Route::post('/do-update/expense', ['uses' => 'SalesInvoiceController@do_update_expense','middleware' => ['permission','access_update:sales-invoice']]);
	Route::post('/do-delete/expense', ['uses' => 'SalesInvoiceController@do_delete_expense','middleware' => ['permission','access_delete:sales-invoice']]);
	Route::post('/do-update/armada', ['uses' => 'SalesInvoiceController@do_update_armada','middleware' => ['permission','access_update:sales-invoice']]);
	Route::post('/do-delete/armada', ['uses' => 'SalesInvoiceController@do_delete_armada','middleware' => ['permission','access_delete:sales-invoice']]);
	Route::post('/do-update/payment', ['uses' => 'SalesInvoiceController@do_update_payment','middleware' => ['permission','access_update:sales-invoice']]);
	Route::post('/do-delete/payment', ['uses' => 'SalesInvoiceController@do_delete_payment','middleware' => ['permission','access_delete:sales-invoice']]);
	Route::post('/set-cancel-invoice', ['uses' => 'SalesInvoiceController@set_cancel_invoice','middleware' => ['permission','access_update:sales-invoice']]);
	Route::post('/sent-email', ['uses' => 'SalesInvoiceController@sent_email','middleware' => ['permission','access_create:sales-invoice']]);
	Route::post('/do-update', ['uses' => 'SalesInvoiceController@do_update','middleware' => ['permission','access_update:sales-invoice']]);
	Route::get('/feed/invoice/{slug}', ['uses' => 'SalesInvoiceController@feed_invoice']);
});