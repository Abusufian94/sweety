<?php

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

Route::get('/', function () {
    return view('login');
});

Route::get('admin/dashboard', 'webController@profile');
Route::get('retail/dashboard', 'webController@profile2');
Route::get('warehouse/dashboard', 'webController@profile3');
Route::get('logout', 'webController@logout');
Route::get('/warehouses','WarehouseController@index')->name('warehouse.home');
Route::get('/warehouses/create','WarehouseController@create')->name('warehouse.create');
Route::get('/warehouse/edit/','WarehouseController@edit')->name('warehouse.edit');

Route::get('/stock','StockController@index')->name('stock.home');
Route::get('/stock/create','StockController@create')->name('stock.create');
Route::get('/stock/edit/','StockController@edit')->name('stock.edit');

Route::get('/stock-log','StockController@indexLog')->name('stocklog.home');
Route::get('/consumption-list','StockController@consumption')->name('consumption.home');
Route::get('/consumption/create','StockController@consumptionCreate')->name('consumption.create');


Route::get('/product','ProductController@index')->name('product.home');
Route::get('/product/create','ProductController@create')->name('product.create');
Route::get('/product/edit/','ProductController@edit')->name('product.edit');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin/retail-user-list', 'webController@retalUserList');


Route::get('admin/retail-user-list', 'retailController@index')->name('retail-user-list');
Route::get('/admin/retail-user-create','retailController@create')->name('retail_user.create');

Route::get('retail/product/list','RetailController@retailProductList')->name('retail.product.list');
Route::get('retail/product/assign/list','RetailController@retailAssignProductList')->name('retail.assign.product.list');

//Route::group(['middleware' => 'Warehouse'], function()
//{
   /** were house  */
    Route::get('warehouse/product/list','ProductController@warehouseproductlist')->name('warehouse.product.list');
    Route::get('warehouse/edit/product','ProductController@warehouseproductedit')->name('warehouse.product.edit');

    Route::get('warehouse/retail/list','ProductController@warehouseretaillist')->name('warehouse.retail.list');
    Route::get('warehouse/edit/retail','ProductController@warehouseretailedit')->name('warehouse.retail.edit');
    Route::get('warehouse/retail/create','ProductController@warehouseretailcreate')->name('warehouse.retail.create');
    Route::get('retail/product/list','ProductController@retailProducts')->name('retail.product.list');
    Route::get('retail/product/billings','retailController@billings')->name('retail.product.billings');

//});
