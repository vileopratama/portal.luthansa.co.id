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

Route::group(['prefix' => 'report-sales-summary','middleware' => ['web','permission','access_read:report-sales-summary']], function() {
	Route::get('/', ['uses' => 'ReportSalesSummaryController@index','middleware' => ['access_read:report-sales-summary']]);
	Route::get('/export/excel', ['uses' => 'ReportSalesSummaryController@export_excel','middleware' => ['access_create:report-sales-summary']]);
});