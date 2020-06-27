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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResources(['user' => 'API\UserController']);
Route::apiResources(['role' => 'API\RoleController']);
Route::apiResources(['product' => 'API\ProductController']);
Route::apiResources(['orderdet' => 'API\OrderdetController']);
Route::apiResources(['order' => 'API\OrderController']);
Route::apiResources(['purch' => 'API\PurchController']);
Route::apiResources(['pdetail' => 'API\PurchdetController']);
Route::apiResources(['publish' => 'API\PublishController']);
Route::get('profile', 'API\UserController@profile');
Route::get('findUser', 'API\UserController@search');
Route::put('profile', 'API\UserController@updateProfile');
Route::get('findRole', 'API\RoleController@search');
Route::get('findProduct', 'API\ProductController@search');
Route::get('findOrder', 'API\OrderController@search');
Route::get('getusers', 'API\OrderController@getUsers');
Route::get('getusers2', 'API\OrderController@getUsers');
Route::get('update', 'API\ProductController@actualizar');

Route::get('information', 'API\DashboardController@index');

Route::get('firebase', 'API\TransferController@index');
Route::get('firebase/products', 'API\TransferController@products');


