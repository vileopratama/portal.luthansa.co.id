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

Route::group(['prefix' => 'department','middleware' => ['web','permission','access_read:department']], function() {
	Route::get('/', ['uses' => 'DepartmentController@index','middleware' => ['access_read:department']]);
	Route::get('/create', ['uses' => 'DepartmentController@create','middleware' => ['access_create:department']]);
	Route::get('/view/{slug}', ['uses' => 'DepartmentController@view','middleware' => ['access_read:department']]);
	Route::get('/edit/{slug}', ['uses' => 'DepartmentController@edit','middleware' => ['access_update:department']]);
	Route::get('/do-publish/{slug}', ['uses' => 'DepartmentController@do_publish','middleware' => ['access_update:department']]);
	Route::post('/do-update', ['uses' => 'DepartmentController@do_update','middleware' => ['access_update:department']]);
	Route::post('/do-delete', ['uses' => 'DepartmentController@do_delete','middleware' => ['access_delete:department']]);
});