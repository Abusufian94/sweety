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
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin/retail-user-list', 'webController@retalUserList');
