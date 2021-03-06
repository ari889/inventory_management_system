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
    Route::get('brand', 'BrandController@index')->name('brand');
    Route::group(['prefix' => 'brand', 'as' => 'brand.'], function(){
        Route::post('datatable-data', 'BrandController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'BrandController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'BrandController@edit')->name('edit');
        Route::post('delete', 'BrandController@delete')->name('delete');
        Route::post('bulk-delete', 'BrandController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'BrandController@change_status')->name('change.status');
    });

    // tax route
    Route::get('tax', 'TaxController@index')->name('tax');
    Route::group(['prefix' => 'tax', 'as' => 'tax.'], function(){
        Route::post('datatable-data', 'TaxController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'TaxController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'TaxController@edit')->name('edit');
        Route::post('delete', 'TaxController@delete')->name('delete');
        Route::post('bulk-delete', 'TaxController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'TaxController@change_status')->name('change.status');
    });
    // warehouse route
    Route::get('warehouse', 'WarehouseController@index')->name('tax');
    Route::group(['prefix' => 'warehouse', 'as' => 'warehouse.'], function(){
        Route::post('datatable-data', 'WarehouseController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'WarehouseController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'WarehouseController@edit')->name('edit');
        Route::post('delete', 'WarehouseController@delete')->name('delete');
        Route::post('bulk-delete', 'WarehouseController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'WarehouseController@change_status')->name('change.status');
    });
    // customer group route
    Route::get('customer-group', 'CustomerGroupController@index')->name('tax');
    Route::group(['prefix' => 'customer-group', 'as' => 'customer.group.'], function(){
        Route::post('datatable-data', 'CustomerGroupController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'CustomerGroupController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'CustomerGroupController@edit')->name('edit');
        Route::post('delete', 'CustomerGroupController@delete')->name('delete');
        Route::post('bulk-delete', 'CustomerGroupController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'CustomerGroupController@change_status')->name('change.status');
    });
    // unit route
    Route::get('unit', 'UnitController@index')->name('tax');
    Route::group(['prefix' => 'unit', 'as' => 'unit.'], function(){
        Route::post('datatable-data', 'UnitController@get_datatable_data')->name('datatable.data');
        Route::post('store-or-update', 'UnitController@store_or_update_data')->name('store.or.update');
        Route::post('edit', 'UnitController@edit')->name('edit');
        Route::post('delete', 'UnitController@delete')->name('delete');
        Route::post('bulk-delete', 'UnitController@bulk_delete')->name('bulk.delete');
        Route::post('change-status', 'UnitController@change_status')->name('change.status');
        Route::post('base-unit', 'UnitController@base_unit')->name('base.unit');
    });

    // HRM setting routes
    Route::get('hrm-setting', 'HRMSettingController@index');
    Route::post('hrm-setting/store', 'HRMSettingController@store')->name('hrm.store');
});
