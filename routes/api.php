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

Route::middleware('auth:api')->group(function () {
    Route::apiResource('products', 'ProductController')->only(['store', 'update', 'destroy']);
    Route::apiResource('categories', 'CategoryController')->only(['store', 'update', 'destroy']);
});

Route::apiResource('products', 'ProductController')->only(['index', 'show']);

Route::get('categories/{category}/products', 'CategoryController@products' );
Route::apiResource('categories', 'CategoryController')->only(['index', 'show']);

