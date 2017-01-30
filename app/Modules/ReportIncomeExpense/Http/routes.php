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

Route::group(['prefix' => 'report-income-expense','middleware' => ['web','permission','access_read:report-income-expense']], function() {
    Route::get('/', ['uses' => 'ReportIncomeExpenseController@index','middleware' => ['access_read:report-income-expense']]);
    Route::get('/export/excel', ['uses' => 'ReportIncomeExpenseController@export_excel','middleware' => ['access_create:report-income-expense']]);
});