<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/get-categories','AdminController@fetch_sub_categories');
Route::post('/get-third-categories','AdminController@get_third_categories');

Route::get('/productcategory','ProductsApiController@productCategories');

Route::get('/weeklybestsellers','ProductsApiController@weeklybestsellers');

Route::get('/trendingProducts','ProductsApiController@trendingProducts');
Route::get('/subcategories','ProductsApiController@subcategories');
Route::get('/subcategoriesProducts','ProductsApiController@subcategoriesProducts');
Route::get('/gallery','ProductsApiController@getGallery');

Route::get('/myaccount','ProductsApiController@customerOrders');
Route::get('/login','ProductsApiController@login');

Route::get('/payments','ProductsApiController@payments');
Route::get('/bookings','ProductsApiController@bookings');
Route::get('/getProducts','ProductsApiController@getProducts');

