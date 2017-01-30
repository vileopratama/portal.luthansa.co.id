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

Route::group(['prefix' => 'company','middleware' => ['web','permission','access_read:company']], function() {
	Route::get('/', ['uses' =>'CompanyController@index','middleware' => ['access_read:company']]);
	Route::get('/create', ['uses' =>'CompanyController@create','middleware' => ['access_create:company']]);
	Route::get('/view/{slug}', ['uses' =>'CompanyController@view','middleware' => ['access_read:company']]);
	Route::get('/edit/{slug}', ['uses' =>'CompanyController@edit','middleware' => ['access_update:company']]);
	Route::get('/do-publish/{slug}', ['uses' =>'CompanyController@do_publish','middleware' => ['access_update:company']]);
	Route::post('/do-update', ['uses' =>'CompanyController@do_update','middleware' => ['access_update:company']]);
	Route::post('/do-delete', ['uses' =>'CompanyController@do_delete','middleware' => ['access_delete:company']]);
});