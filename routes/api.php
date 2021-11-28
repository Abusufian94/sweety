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
Route::post('register', 'API\UserController@register');
Route::post('login', 'API\UserController@login');
Route::group(["prefix"=>"/v1","middleware"=>['auth:api'],"namespace"=>'\App\Http\Controllers\API'],function () {
    Route::get('profile/', 'UserController@myprofile');
    Route::get('logout/','UserController@logout');
    Route::post('warehose/create','UserController@register');
    Route::patch('warehose/update','UserController@updatewarehouse');
    Route::delete('warehose/delete','UserController@deletewarehouse');
    Route::get('warehose/all','UserController@warehouselist');
});


