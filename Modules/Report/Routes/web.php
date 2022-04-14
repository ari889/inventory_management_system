<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['auth']], function(){
    Route::get('summary-report', 'SummaryReportController@index')->name('summary.report');
    Route::post('summary-report/details', 'SummaryReportController@details')->name('summary.report.details');

    Route::match(['get', 'post'], 'product-report', 'ProductReportController@index');

    Route::get('daily-sale', 'SaleReportController@dailySale');
    Route::post('daily-sale', 'SaleReportController@dailySaleReport')->name('daily.sale.report');
    
    Route::get('monthly-sale', 'SaleReportController@monthlySale');
    Route::post('monthly-sale', 'SaleReportController@monthlySaleReport')->name('monthly.sale.report');
});