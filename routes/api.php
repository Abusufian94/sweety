<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::auth();
Route::post('/register', 'API\UserController@register');
Route::post('/login', 'API\UserController@login');
Route::group(["prefix" => "/v1", "middleware" => ['auth:api'], "namespace" => '\App\Http\Controllers\API'], function () {
  Route::get('profile/', 'UserController@myprofile');
  Route::get('logout/', 'UserController@logout');
  Route::post('warehose/create', 'UserController@register');
  Route::patch('warehose/update', 'UserController@updatewarehouse');
  Route::delete('warehose/delete', 'UserController@deletewarehouse');
  Route::get('warehose/all', 'UserController@warehouselist')->name('warehouse.list');
  Route::get('warehouse/{id}', 'UserController@editwarehouse');


  // Raw Data Routes
  Route::post('raw/create', 'StockController@insert');
  Route::get('raw/details/{id}', 'StockController@getRawDetails');
  Route::post('raw/update', 'StockController@updatestock');
  Route::delete('raw/delete/{id}', 'StockController@deletestock');
  Route::get('raw/all', 'StockController@list')->name('raw.list');

  //stok log listen
  Route::get('log/all', 'StockController@log_list')->name('stocklog.list');

  //consumption listen
  Route::get('consumption/all', 'StockController@consumption_list')->name('consumption.list');
  Route::post('cn/add', 'StockController@consumptionCreate')->name('cn.add');



     // Product Routes
     Route::post('pro/create', 'StockController@insertProduct')->name('pinsert');
     Route::get('pro/details/{id}', 'StockController@getProductDetails');
     Route::post('pro/update', 'StockController@updateProduct');
     Route::delete('pro/delete/{id}', 'StockController@deleteProduct');
     Route::get('pro/all', 'StockController@productList')->name('product.list');
     Route::get('pro/rawitems/{id?}', 'StockController@rawlist')->name('rawstock.rawlist');
     Route::get('pro/consumption/{id}', 'StockController@productConsumption')->name('product.consumption');
     //ware house apis
     Route::post('product/update','Warehouseroducts@updatewarehouseproduct');


     /*retail store list*/
       Route::get('admin/retails', 'StockController@retailList');
       Route::post('admin/retail-user/create', 'UserController@retailUserCreate');
       Route::get('admin/retail-users', 'UserController@retailUsers');
      Route::delete('admin/retail_user/delete', 'UserController@retailUserDelete');

  Route::post('/retail-assign-log', 'Warehouseroducts@productRetailLogCreate')->name('retail.add');
  Route::get('/product-retail-list/{status?}', 'Warehouseroducts@productRetailList')->name('product.retail.list');
  Route::get('/product-retail-details/{id}', 'Warehouseroducts@getProductRetailDetails');
  Route::post('productretail/update', 'Warehouseroducts@productRetailLogUpdate');
  //  Route::get('/product-list', 'Warehouseroducts@productList')->name('productlist');

  Route::get('/retail-users', 'Warehouseroducts@retailUserList')->name('retailusers');
  Route::get('/retail-products', 'RetailProducts@RetailProducts')->name('retail.products');
  Route::post('/retail-products-approve', 'RetailProducts@approveProduct')->name('retail.approve');

  // admin/assigned-pending-total-stock

  Route::get('assigned-pending-total-stock', 'Warehouseroducts@assignedPendingTotalStock');
  Route::get('admin/suggestive-product','RetailerController@suggestiveproducts');

});
