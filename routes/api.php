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
Route::get('/products','ProductsApiController@getProduct');
Route::post('/updateBookingTarget','ProductsApiController@updateBookingTarget');

Route::get('/myaccount','ProductsApiController@customerOrders');
Route::get('/login','ProductsApiController@login');

Route::get('/payments','ProductsApiController@payments');
Route::get('/bookings','ProductsApiController@bookings');
Route::get('/getProducts','ProductsApiController@getProducts');
Route::get('/getSubcategoryProducts','ProductsApiController@getSubcategoryProducts');
Route::get('/categories','ProductsApiController@categories');
Route::post('/register','autApi@registerUser');
Route::get('/phoneExists','autApi@ifPhoneExists');
Route::get('/resetpassword','autApi@resetPassword');
Route::get('/search','ProductsApiController@search');
Route::post('/makepayment','autApi@MakePayment');
Route::get('/checkbooking','ProductsApiController@checkBooking');
Route::post('/makebooking','autApi@make_booking');
Route::post('/hasBooking','autApi@hasBooking');
Route::get('/getUserDetails','autApi@getUserDetails');
Route::post('/updateaccount','autApi@updateaccount');

Route::get('/check-booking-exists/{booking_reference}','AdminController@check_booking_exists');





Route::get('/pushnotification','pushNotification@testFcm');
Route::get('/firebasetopics','autApi@firebasetopics');
Route::get('/sendtotopics','pushNotification@sendtotopics');


Route::get('/insertToken','firebaseToken@insertToken');
Route::get('/updateToken','firebaseToken@updateToken');
Route::post('stk-callback', 'autApi@callBack');


//account topups
Route::get('/balances','TopupsController@balances');
Route::post('/maketopup','TopupsController@maketopups');
Route::any('/redeem','TopupsController@redeem');

Route::post('/refreshpayment','TopupsController@refreshpayment');
Route::any('/BillsPayment','TopupsController@BillsPayment');
Route::any('/travelbookings','TravelMosMosBookings@bookings');
Route::any('/travelmakingpayment','TravelMosMosBookings@makePayment');
Route::any('/customertravelbookings','TravelMosMosBookings@customertravelbookings');
Route::any('/travelcheckbooking','TravelMosMosBookings@travelcheckBooking');
Route::any('/maketravelpayment','TravelMosMosBookings@makePayment');
Route::any('/travelpayments','TravelMosMosBookings@travelpayments');























