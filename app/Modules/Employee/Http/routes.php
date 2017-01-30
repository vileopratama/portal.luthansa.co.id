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

Route::group(['prefix' => 'employee','middleware' => ['web','permission','access_read:employee']], function() {
	Route::get('/', ['uses' => 'EmployeeController@index','middleware' => ['access_read:employee']]);
	Route::get('/create', ['uses' => 'EmployeeController@create','middleware' => ['access_create:employee']]);
	Route::get('/export/pdf/{slug}', ['uses' => 'EmployeeController@export_pdf','middleware' => ['access_create:employee']]);
	Route::get('/view/{slug}', ['uses' => 'EmployeeController@view','middleware' => ['access_read:employee']]);
	Route::get('/edit/{slug}', ['uses' => 'EmployeeController@edit','middleware' => ['access_update:employee']]);
	Route::get('/do-publish/{slug}', ['uses' => 'EmployeeController@do_publish','middleware' => ['access_update:employee']]);
	Route::post('/do-update', ['uses' => 'EmployeeController@do_update','middleware' => ['access_update:employee']]);
	Route::post('/do-delete', ['uses' => 'EmployeeController@do_delete','middleware' => ['access_delete:employee']]);
});