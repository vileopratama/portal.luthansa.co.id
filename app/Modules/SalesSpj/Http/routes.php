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

Route::group(['prefix' => 'sales-spj','middleware' => ['web','permission','access_read:sales-spj']], function() {
	Route::get('/', ['uses' => 'SalesSPJController@index','middleware' => ['access_read:sales-spj']]);
	Route::get('/create', ['uses' => 'SalesSPJController@create','middleware' => ['access_create:sales-spj']]);
	Route::get('/export/pdf/{slug}', ['uses' => 'SalesSPJController@export_pdf','middleware' => ['access_create:sales-spj']]);
	Route::get('/view/{slug}', ['uses' => 'SalesSPJController@view','middleware' => ['access_read:sales-spj']]);
	Route::get('/edit/{slug}', ['uses' => 'SalesSPJController@edit','middleware' => ['access_update:sales-spj']]);
	Route::post('/do-update', ['uses' => 'SalesSPJController@do_update','middleware' => ['access_update:sales-spj']]);
	Route::post('/do-delete', ['uses' => 'SalesSPJController@do_delete','middleware' => ['access_delete:sales-spj']]);
	Route::get('/export/spj/{slug}', ['uses' => 'SalesSPJController@print_spj','middleware' => ['access_create:sales-invoice']]);
	Route::get('/export/blanko/{slug}', ['uses' => 'SalesSPJController@print_blanko','middleware' => ['access_create:sales-invoice']]);
	Route::get('/list/invoice', ['uses' => 'SalesSPJController@list_invoice','middleware' => ['access_read:sales-spj']]);
	Route::get('/get/invoice', ['uses' => 'SalesSPJController@get_invoice','middleware' => ['access_read:sales-spj']]);
});