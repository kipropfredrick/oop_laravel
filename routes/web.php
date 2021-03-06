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

Route::get('/email', function () {
    return view('emails.sendInvoice');
});

Route::get('/faqs', function () {
    return view('faqs');
  });

Route::get('/how-to', function () {
return view('howto');
});

Route::get('/terms', function () {
    return view('terms');
});

Route::get('/privacy-policy', function () {
    return view('privacy');
});
Route::get('/cardsuccess/{details}', 'autApi@cardsuccess')->name('cardsuccess');
Route::get('/ipayfailed', function () {
    return view('front.ipayfailed');
});

Route::get('/register-email', function (){
 return view('emails.registrationmail_test');
});

Route::any('/capturepayment', 'autApi@capturepayment')->name('admin.capturepayment');
Route::any('/TravelCardTransaction', 'autApi@TravelCardTransaction')->name('admin.TravelCardTransaction');
Route::any('/simulatetransaction', 'autApi@TravelMpesaTransaction')->name('admin.TravelMpesaTransaction');
Route::prefix('payments')->group(function () {
  Route::get('/{ordernumber}/invoice','InvoicePaymentController@index');
  Route::get('/{ordernumber}/pay','InvoicePaymentController@pay');
    Route::get('keysettings','InvoicePaymentController@keySettings');
  

});




Route::get('/sendpromonotification', 'firebasetopics@promonotification')->name('admin.sendpromonotification');
Route::get('/updateAccountNumbers', 'autApi@updateAccountNumbers')->name('admin.updateAccountNumbers');

Route::get('/sendpromoreminder', 'firebasetopics@sendpromoreminder')->name('admin.sendpromoreminder');
Route::get('/scheduletasks','AdminController@scheduletasks');
Route::get('/scheduletasks1','AdminController@scheduletask1');
Route::get('/scheduletasks2','AdminController@scheduletask2');
Route::get('/scheduletasks3','AdminController@scheduletask3');
Route::get('/updateunservicedoverdue','AdminController@updateunservicedoverdue');
Route::get('/updatevendorslugs','AdminController@updatevendorslugs');




Route::get('/testSendSMS','FrontPageController@testSendSMS');

Route::get('/update_bookings_agent_or_vendor','FrontPageController@update_bookings_agent_or_vendor');

Route::post('/checkpayment','MpesaPaymentController@checkpayment');

Route::get('/vendors/{slug}','FrontPageController@vendor');
Route::post('/vendors/{slug}','FrontPageController@vendor_load_more');

Route::prefix('c2b')->group(function () {
    Route::post('/confirm-7CavgY5gFFwzktQH6XjcS2','MpesaPaymentController@mpesapayment');
    Route::post('/validate-UjQerTLb4EM78rHBSmYgCG','MpesaPaymentController@validation_url');
});

Route::get('/zohoverify/verifyforzoho.html', function () {
    return view('verifyforzoho');
  })->name('verifyforzoho');

Route::get('/update_booking','MpesaPaymentController@update_booking');

Route::get('/update_product_bookings','AdminController@update_product_bookings');



Route::get('/test-SMS','AdminController@testSMS');

Route::post('/register_vendor','AuthController@register')->name('register-vendor');

Route::get('/register_url','MpesaPaymentController@register_url');

Route::post('/test-accesstoken','MpesaPaymentController@generate_access_token');

Route::post('/simulate_payment','MpesaPaymentController@simulate_payment');

Route::post('/stkPush','MpesaPaymentController@stkPush');

Route::post('/USSD-Q7NMAqYVcdCNmVgE','USSDController@sessions');
Route::get('/AirtelussdPush','USSDController@AirtelussdPush');


Route::post('/USSD-test-Q7NMAqY34','USSDController@test_combine');

Route::post('/ussd-Q7NMAqcdCNmVgE','USSDController@test');

Route::get('/', 'FrontPageController@index')->name('welcome');

Route::get('/', 'FrontPageController@index');

Route::get('/update_categories','FrontPageController@update_categories');

Route::get('/terms', 'FrontPageController@terms');
Route::get('/product/{slug}','FrontPageController@show');
Route::get('/cat/{slug}','FrontPageController@category');
Route::get('/tlc/{subcategory}/{slug}','FrontPageController@thirdlevelcategory');
Route::post('/tlc/{subcategory}/{slug}','FrontPageController@thirdlevelcategory_load_more');
Route::post('/cat/{slug}','FrontPageController@category_load_more');
Route::get('/brand/{slug}','FrontPageController@brand');
Route::post('/brand/search','FrontPageController@search_brand');
Route::post('/brand/{slug}','FrontPageController@brand_load_more');
Route::get('/shop/{id}','FrontPageController@shop');
Route::get('/sub/{slug}','FrontPageController@subcategory')->name('view-subcategory');
Route::post('/sub/{slug}','FrontPageController@subcategory_load_more');
Route::get('/checkout/{slug}','FrontPageController@checkout');
Route::get('/checkout/bonga/{slug}','FrontPageController@checkout_bonga');
Route::get('/checkout-with-existing/{slug}','FrontPageController@checkout_existing');
Route::get('/checkout-bonga-with-existing/{slug}','FrontPageController@checkout_bonga_existing');
Route::post('/make-booking','FrontPageController@make_booking');
Route::post('/make-booking/bonga','FrontPageController@make_booking_bonga');
Route::post('/make-booking-account','FrontPageController@make_booking_account');
Route::post('/make-booking-bonga-account','FrontPageController@make_booking_bonga_account');
Route::get('image/{filename}', 'FrontPageController@displayImage')->name('image.displayImage');


Auth::routes();

Route::get('/search', 'FrontPageController@search')->name('search');

Route::post('/search', 'FrontPageController@search_load_more');

Route::get('/home', 'HomeController@index')->name('home');



  Route::group(['middleware' => ['auth']], function (){
    Route::prefix('dashboard')->group(function () {
        Route::get('/home', 'AdminController@dashboard')->name('dashboard');
    });
    Route::get('/edit-profile', 'HomeController@edit_profile');
    Route::post('/update-profile/{role}', 'HomeController@update_profile');
 });

Route::prefix('customer')->group(function () {
    Route::group(['middleware' => ['auth','customer']], function (){
        Route::get('/dashboard', 'CustomerController@index')->name('customer.dashboard');
        Route::get('/pending-bookings','CustomerController@pending_bookings');
        Route::get('/complete-bookings','CustomerController@complete_bookings');
        Route::get('/active-bookings','CustomerController@active_bookings');
        Route::get('/revoked-bookings','CustomerController@revoked_bookings');
        Route::post('/customer/redeem','CustomerController@redeem')->name('customer.redeem');
        Route::get('/payments', 'FrontPageController@payments')->name('customer.payments');

    });
});

Route::prefix('agent')->group(function () {
    Route::group(['middleware' => ['auth','agent']], function (){
        Route::get('/assigned-products', 'AgentsController@assigned_products')->name('agent.assigned.products');
        Route::get('/product-view/{id}','AgentsController@view_product');
        Route::get('/complete-bookings','AgentsController@complete_bookings');
        Route::get('/active-bookings','AgentsController@active_bookings');
        Route::get('/revoked-bookings','AgentsController@revoked_bookings');
        Route::get('/add-product', 'AgentsController@add_product');
        Route::post('/update-product/{id}', 'AgentsController@update_product');
        Route::post('/save-product', 'AgentsController@save_product');
        Route::get('/pending-products','AgentsController@pending_products');
        Route::get('/approved-products','AgentsController@approved_products');
        Route::get('/rejected-products','AgentsController@rejected_products');
        Route::get('/overdue-bookings','AgentsController@overdue_bookings');
        Route::get('/profile','AgentsController@profile');
        Route::post('/update-profile', 'AgentsController@update_profile');
        Route::get('/revoked-bookings','AgentsController@revoked_bookings');
        Route::get('/delivered-bookings','AgentsController@delivered_bookings');
        Route::get('/unserviced-bookings','AgentsController@unserviced_bookings');
        Route::get('/assigned-products', 'AgentsController@assigned_products');
        Route::get('/product-view/{id}','AgentsController@view_product');
        Route::get('/complete-bookings','AgentsController@complete_bookings');
        Route::get('/active-bookings','AgentsController@active_bookings');
        Route::get('/pending-bookings','AgentsController@pending_bookings');
        Route::get('/transfer-order','AgentsController@transfer_order');
        Route::post('/transfer-order/{id}','AgentsController@transfer_orderID');
        Route::get('/revoked-bookings','AgentsController@revoked_bookings');
        Route::get('/unserviced-bookings','AgentsController@unserviced_bookings');
        Route::get('/deliver-booking/{id}','AgentsController@deliver_booking');
        Route::get('/delivered-bookings','AgentsController@delivered_bookings');
        Route::get('/confirmed_deliveries','AgentsController@confirmed_deliveries');
        Route::get('/product-edit/{id}', 'AgentsController@product_edit');
        Route::get('/product-delete/{id}', 'AgentsController@product_delete');
    });
});

Route::prefix('influencer')->group(function () {
    Route::group(['middleware' => ['auth','influencer']], function (){
        Route::get('/product-view/{id}','InfluencerController@view_product');
        Route::get('/add-product', 'InfluencerController@add_product');
        Route::post('/update-product/{id}', 'InfluencerController@update_product');
        Route::post('/save-product', 'InfluencerController@save_product');
        Route::get('/complete-bookings','InfluencerController@complete_bookings');
        Route::get('/active-bookings','InfluencerController@active_bookings');
        Route::get('/pending-products','InfluencerController@pending_products');
        Route::get('/approved-products','InfluencerController@approved_products');
        Route::get('/rejected-products','InfluencerController@rejected_products');
        Route::get('/overdue-bookings','InfluencerController@overdue_bookings');
        Route::get('/profile','InfluencerController@profile');
        Route::post('/update-profile', 'InfluencerController@update_profile');
        Route::get('/revoked-bookings','InfluencerController@revoked_bookings');
        Route::get('/delivered-bookings','InfluencerController@delivered_bookings');
        Route::get('/transfer-order','InfluencerController@transfer_order');
        Route::post('/transfer-order/{id}','InfluencerController@transfer_orderID');
        Route::get('/unserviced-bookings','InfluencerController@unserviced_bookings');
        Route::get('/pending-bookings','InfluencerController@pending_bookings');
        Route::get('/assigned-products', 'InfluencerController@assigned_products');
        Route::get('/product-view/{id}','InfluencerController@view_product');
        Route::get('/complete-bookings','InfluencerController@complete_bookings');
        Route::get('/active-bookings','InfluencerController@active_bookings');
        Route::get('/revoked-bookings','InfluencerController@revoked_bookings');
        Route::get('/product-edit/{id}', 'InfluencerController@product_edit');
        Route::get('/logs','InfluencerController@logs');
    Route::get('/product-delete/{id}', 'InfluencerController@product_delete');
    });
});

Route::prefix('vendor')->group(function () {
    Route::group(['middleware' => ['auth']], function (){
        Route::get('/view-booking/{id}','VendorController@bookingdetails')->name('vendor.bookingdetails');

  Route::post('/view-booking/{id}/payments','VendorController@bookingpayments')->name('vendor.bookingpayments');
        
        Route::get('/key', 'VendorController@keySettings');
        
          Route::any('/payments', 'VendorController@payments');
        Route::get('/vendor-booking', 'VendorController@manualBooking');
        Route::post('/vendor-savebooking', 'ApiBookingController@vendorbooking');
          
        Route::get('/assigned-products', 'VendorController@assigned_products');
        Route::get('/product-view/{id}','VendorController@view_product');
        Route::get('/add-product', 'VendorController@add_product');
        Route::get('/image-delete/{id}', 'VendorController@image_delete');
        Route::post('/update-product/{id}', 'VendorController@update_product');
        Route::post('/save-product', 'VendorController@save_product');
        Route::get('/create-bookings','VendorController@create_bookings');
          Route::post('/make-booking','VendorController@make_booking');
        
         Route::get('/getproducts','VendorController@getproducts');


         Route::get('/active-bookings','VendorController@active_bookings');
        Route::get('/active-bookings','VendorController@active_bookings');
        Route::get('/pending-products','VendorController@pending_products');
        Route::get('/approved-products','VendorController@approved_products');
        Route::get('/rejected-products','VendorController@rejected_products');
        Route::get('/overdue-bookings','VendorController@overdue_bookings');
        Route::get('/profile','VendorController@profile');
        Route::post('/update-profile', 'VendorController@update_profile');
        Route::get('/revoked-bookings','VendorController@revoked_bookings');
        Route::get('/delivered-bookings','VendorController@delivered_bookings');
        Route::get('/transfer-order','VendorController@transfer_order');
        Route::post('/transfer-order/{id}','VendorController@transfer_orderID');
        Route::get('/unserviced-bookings','VendorController@unserviced_bookings');
        Route::get('/pending-bookings','VendorController@pending_bookings');
        Route::get('/assigned-products', 'VendorController@assigned_products');
        Route::get('/product-view/{id}','VendorController@view_product');
        Route::get('/complete-bookings','VendorController@complete_bookings');
        Route::get('/active-bookings','VendorController@active_bookings');
        Route::get('/revoked-bookings','VendorController@revoked_bookings');
        Route::get('/product-edit/{id}', 'VendorController@product_edit');
    Route::get('/product-delete/{id}', 'VendorController@product_delete');
       Route::get('/branches', 'VendorController@branches')->name('branchvendor.branches');
 Route::get('/add-branch', 'VendorController@add_vendor')->name('vendor.branchvendor.add');
  Route::get('/view-branch/{id}', 'VendorController@view_branch')->name('vendor.branchvendor.view');
    Route::post('/vendor-save', 'VendorController@save_vendor')->name('vendor.branchvendor.save');
    Route::get('/view-vendor/{id}', 'VendorController@view_vendor')->name('vendor.branchvendor.view');




 Route::prefix('branch')->group(function () {


        Route::get('/delete-account/{id}','VendorController@vendor_delete_account');
        Route::get('/products-report/{id}','ReportGenerator@vendor_products_report');
        Route::get('/active-bookings-report/{id}','ReportGenerator@vendor_active_bookings_report');
        Route::get('/pending-bookings-report/{id}','ReportGenerator@vendor_pending_bookings_report');
        Route::get('/rejected-bookings-report/{id}','ReportGenerator@vendor_rejected_bookings_report');
        Route::get('/unserviced-bookings-report/{id}','ReportGenerator@vendor_unserviced_bookings_report');
        Route::get('/complete-bookings-report/{id}','ReportGenerator@vendor_complete_bookings_report');
        Route::get('/overdue-bookings-report/{id}','ReportGenerator@vendor_overdue_bookings_report');
        Route::get('/delivered-bookings-report/{id}','ReportGenerator@vendor_delivered_bookings_report');
        Route::get('/rejected-products','AdminController@vendor_rejected_products');
       

      });


    });
});


Route::prefix('branch')->group(function () {
    Route::group(['middleware' => ['auth']], function (){
        Route::get('/view-booking/{id}','BranchVendorController@bookingdetails')->name('vendor.bookingdetails');

  Route::post('/view-booking/{id}/payments','BranchVendorController@bookingpayments')->name('vendor.bookingpayments');
     Route::get('/branch-booking', 'BranchVendorController@manualBooking');
        
          Route::any('/branchusers', 'BranchVendorController@branchusers');
        Route::any('/adduser', 'BranchVendorController@adduser');
    Route::any('/user-save', 'BranchVendorController@usersave');
          
          Route::any('/payments', 'BranchVendorController@payments');
        Route::get('/vendor-booking', 'BranchVendorController@manualBooking');
        Route::post('/vendor-savebooking', 'ApiBookingController@vendorbooking');
        Route::get('/image-delete/{id}', 'BranchVendorController@image_delete');
        Route::get('/create-bookings','BranchVendorController@create_bookings');
          Route::post('/make-booking','BranchVendorController@make_booking');
        
        
        Route::get('/active-bookings','BranchVendorController@active_bookings');
        Route::get('/rejected-products','BranchVendorController@rejected_products');
        Route::get('/overdue-bookings','BranchVendorController@overdue_bookings');
        Route::get('/profile','BranchVendorController@profile');
        Route::post('/update-profile', 'BranchVendorController@update_profile');
        Route::get('/revoked-bookings','BranchVendorController@revoked_bookings');
        Route::get('/delivered-bookings','BranchVendorController@delivered_bookings');
        Route::get('/transfer-order','BranchVendorController@transfer_order');
        Route::post('/transfer-order/{id}','BranchVendorController@transfer_orderID');
        Route::get('/unserviced-bookings','BranchVendorController@unserviced_bookings');
        Route::get('/pending-bookings','BranchVendorController@pending_bookings');
       
        Route::get('/complete-bookings','BranchVendorController@complete_bookings');
        Route::get('/active-bookings','BranchVendorController@active_bookings');
        Route::get('/revoked-bookings','BranchVendorController@revoked_bookings');

    

    });
});



Route::prefix('admin')->group(function () {
Route::group(['middleware' => ['auth','admin']], function (){
    Route::get('/dashboard', 'AdminController@index')->name('admin.dashboard');
    Route::get('/promotions', 'AdminController@promotions')->name('admin.promotions');
    Route::get('/profile', 'AdminController@profile')->name('admin.profile');
    Route::get('/update-profile', 'AdminController@update_profile')->name('admin.update-profile');
    Route::get('/products', 'AdminController@products')->name('admin.products');
    Route::get('/product-brands', 'AdminController@product_brands');
    Route::get('/product-edit/{id}', 'AdminController@product_edit')->name('admin.product.edit');
    Route::get('/product-delete/{id}', 'AdminController@product_delete')->name('admin.product.delete');
    Route::get('/image-delete/{id}', 'AdminController@image_delete')->name('admin.image.delete');
    Route::get('/add-product', 'AdminController@add_product')->name('admin.product.add');
    Route::get('/product-assign/{id}', 'AdminController@assign_product')->name('admin.product.assign');
    Route::post('/save-product', 'AdminController@save_product')->name('admin.save_product');
    Route::post('/save-brand', 'AdminController@save_brand')->name('admin.save_brand');
    Route::post('/update-brand/{brand}', 'AdminController@update_brand')->name('admin.update_brand');
    Route::get('/view-category/{id}', 'AdminController@view_category')->name('admin.category.view');
    Route::get('/view-subcategory/{id}', 'AdminController@view_subcategory')->name('admin.subcategory.view');
    Route::get('/edit-vendor/{id}', 'AdminController@edit_vendor')->name('admin.vendor.edit');
      Route::get('/view-vendor/{id}', 'AdminController@view_vendor')->name('admin.vendor.view');
      Route::get('/setcommissions/{id}', 'AdminController@setcommissions')->name('setcommissions');
    Route::get('/view-influencer/{id}', 'AdminController@view_influencer')->name('admin.influencer.view');
    Route::get('/view-agent/{id}', 'AdminController@view_agent')->name('admin.agent.view');
    Route::get('/edit-category/{id}', 'AdminController@edit_category')->name('admin.category.edit');
    Route::post('/update-product/{id}', 'AdminController@update_product')->name('admin.update_product');
    Route::post('/assign-product/{id}', 'AdminController@assign_save_product')->name('admin.assign_product');
    Route::get('/transfer-order','AdminController@transfer_order');
    Route::post('/transfer-order/{id}','AdminController@transfer_orderID');
    Route::post('/record-payment/{id}','AdminController@record_payment');
    Route::post('/recordbill-payment/{id}','AdminController@recordbillpayment');
      Route::post('/recordCreditedbill-payment/{id}','AdminController@recordCreditedbillpayment');
    

    Route::get('/product-categories', 'AdminController@categories')->name('admin.categories');
    Route::post('/save-category', 'AdminController@save_category')->name('admin.save_category');
    Route::post('/update-category/{id}', 'AdminController@update_category')->name('admin.update_category');
    Route::post('/save-subcategory', 'AdminController@save_subcategory')->name('admin.save_subcategory');
    Route::post('/save-tsubcategory', 'AdminController@save_tsubcategory')->name('admin.save_tsubcategory');
    Route::post('/update-subcategory/{id}', 'AdminController@update_subcategory')->name('admin.update_subcategory');
    Route::post('/update-tsubcategory/{id}', 'AdminController@update_tsubcategory')->name('admin.update_tsubcategory');
    Route::get('/active_bookings', 'AdminController@active_bookings')->name('admin.active_bookings');
    Route::get('/revoke-booking/{id}', 'AdminController@revoke_booking')->name('admin.revoke-booking');

    Route::get('/storepicking-booking/{id}','AdminController@storepicking_booking')->name('admin.storepicking-booking');
    Route::get('/view-booking/{id}','AdminController@bookingdetails')->name('admin.bookingdetails');

  Route::post('/view-booking/{id}/payments','AdminController@bookingpayments')->name('admin.bookingpayments');
    Route::get('/remove-booking/{id}', 'AdminController@remove_booking')->name('admin.remove-booking');
    Route::get('/complete_bookings', 'AdminController@complete_bookings')->name('admin.complete_bookings');
    Route::get('/delivered_bookings','AdminController@delivered_bookings');
    Route::get('/confirmed_deliveries','AdminController@confirmed_deliveries');
    Route::get('/approve-delivery/{id}','AdminController@approve_delivery');
    Route::get('/product-approve/{id}','AdminController@approve_product');
    Route::get('/product-reject/{id}','AdminController@reject_product');
    Route::get('/approve-vendor/{id}','AdminController@approve_vendor');
    Route::get('/overdue_bookings', 'AdminController@overdue_bookings')->name('admin.overdue_bookings');
    Route::get('/revoked_bookings', 'AdminController@revoked_bookings')->name('admin.revoked_bookings');
    Route::get('/unserviced_bookings', 'AdminController@unserviced_bookings')->name('admin.unserviced_bookings');
    Route::get('/pending_bookings', 'AdminController@pending_bookings')->name('admin.pending_bookings');
    Route::post('/check-booking-exists','AdminController@check_booking_exists');
    Route::get('/update-callback','AdminController@update_callback');
    Route::post('/record-payment/{id}','AdminController@record_payment');
    Route::any('/payments', 'AdminController@payments')->name('admin.payments');
    Route::get('/payment-callbacks', 'AdminController@payments_callbacks');
    Route::get('/bill-payment-callbacks', 'AdminController@billpayments_callbacks');
    Route::any('/customers/{type}', 'AdminController@customers')->name('admin.customers');
    Route::get('/delete-customer/{id}', 'AdminController@delete_customer');
    Route::get('/agents', 'AdminController@agents')->name('admin.agents');
    Route::get('/vendors', 'AdminController@vendors')->name('admin.vendors');
    Route::get('/influencers', 'AdminController@influencers')->name('admin.influencers');
    Route::get('/add_agent', 'AdminController@add_agent')->name('admin.add-agent');
    Route::get('/add-influencer', 'AdminController@add_influencer')->name('admin.add-influencer');
    Route::post('/influencer_save', 'AdminController@influencer_save')->name('admin.influencer_save');
    Route::get('/vendor-products', 'AdminController@vendor_products')->name('admin.vendor-products');
    Route::get('/vendor-product-approve/{id}', 'AdminController@vendor_product_approve');
    Route::get('/vendor-product-reject/{id}', 'AdminController@vendor_product_reject');
    Route::get('/vendor-product-delete/{id}', 'AdminController@vendor_product_delete');
    Route::get('/cities', 'AdminController@cities')->name('admin.cities');
    Route::post('/agent_save', 'AdminController@agent_save')->name('admin.agent_save');
    Route::post('/save-city', 'AdminController@save_city')->name('admin.save-city');
    Route::post('/update-city/{id}', 'AdminController@update_city')->name('admin.update_city');
    Route::get('/commissions','AdminController@commissions')->name('commissions');
    Route::get('/fixed-payout','AdminController@fixedPayout')->name('fixed-payout');
    Route::get('/show-commissions/{id}','AdminController@showCommissions')->name('showcommissions');
    
    Route::get('/influencer-commissions','AdminController@influencer_commissions')->name('influencer_commissions');
    Route::get('/influencer-products','AdminController@influencer_products');
    Route::get('/influencer-logs','AdminController@influencer_logs');
    Route::post('record-influencer-payment','AdminController@influencer_pay');

    Route::get('/add-vendor', 'AdminController@add_vendor')->name('admin.vendor.add');
    Route::post('/vendor-save', 'AdminController@save_vendor')->name('admin.vendor.save');
    Route::post('/vendor-update/{id}', 'AdminController@update_vendor')->name('admin.vendor.update_vendor');
    
    Route::post('/update_vendor/{id}', 'AdminController@update_vendors')->name('admin.vendor.update');
    Route::get('/banners', 'AdminController@banners')->name('admin.banners');
    Route::get('/add_banner','AdminController@add_banner')->name('admin.adder-banner');
    Route::post('/banner_save','AdminController@save_banner')->name('admin.save-banner');
    Route::get('/delete-banner/{id}', 'AdminController@banner_delete')->name('admin.banner.delete');
    Route::get('/view-banner/{id}', 'AdminController@banner_view')->name('admin.banner.view');
    Route::get('/edit-banner/{id}', 'AdminController@banner_edit')->name('admin.banner.edit');
    Route::post('/banner_update/{id}','AdminController@update_banner')->name('admin.updateer-banner');

    Route::get('/sms-log', 'AdminController@sms_log')->name('admin.sms-log');
    Route::get('/send-sms','AdminController@send_sms')->name('admin.send-sms');
    Route::post('/send-sms-save','AdminController@send_sms_save')->name('admin.send-sms-save');

    //send prmo notifications

    Route::get('/notifications', 'firebasetopics@index')->name('admin.notifications');
    Route::get('/custom', 'firebasetopics@customNotifications')->name('admin.customnotifications');
    Route::post('/addtopic', 'firebasetopics@addtopic')->name('admin.addtopic');
    Route::post('/customnotify', 'firebasetopics@customnotify')->name('admin.customnotify');
Route::get('/removetopic', 'firebasetopics@removetopic')->name('admin.removetopic');
Route::post('/sendtotopics','pushNotification@sendtotopics')->name('admin.firebasetopics');

//payment monitoring
Route::get('/monitorPayments','AdminController@monitorPayments')->name('admin.monitorPayments');



//add toups 

Route::get('/topups','AdminController@topups')->name('admin.topups');
Route::get('/purchases','AdminController@purchases')->name('admin.purchases');
Route::get('/utilities','AdminController@utilities')->name('admin.utilities');

Route::get('/ag-all','AdminController@agall')->name('admin.agall');
Route::get('/ag-bookings','AdminController@agbookings')->name('admin.agbookings');
Route::get('/ag-airtime','AdminController@agairtime')->name('admin.agairtime');
Route::get('/ag-utility','AdminController@agutility')->name('admin.agutility');

//users
Route::get('/user/data','UserController@index')->name('admin.user.index');
Route::get('/user/{id}/show','UserController@show')->name('admin.user.show');
Route::get('/user/create','UserController@create')->name('admin.user.create');
Route::post('/user/store','UserController@store')->name('admin.user.store');
Route::get('/user/{id}/delete','UserController@delete')->name('admin.user.delete');
Route::get('/user/{id}/edit','UserController@edit')->name('admin.user.edit');
Route::post('/user/{id}/update','UserController@update')->name('admin.user.update');


//manage user roles..
Route::get('/user/role/data','UserController@indexRole')->name('admin.role.data');
Route::get('/user/role/create','UserController@createRole')->name('admin.role.create');
Route::get('/user/role/store','UserController@storeRole')->name('admin.role.store');
Route::get('/user/role/{id}/delete','UserController@deleteRole')->name('admin.role.delete');
Route::get('/user/role/{id}/edit','UserController@editRole')->name('admin.role.edit');
Route::post('/user/role/{id}/update','UserController@updateRole')->name('admin.role.update');
//permissions
Route::get('/user/permission/data','UserController@indexPermission')->name('admin.permission.data');
Route::get('/user/permission/create','UserController@createPermission')->name('admin.permission.create');
Route::post('/user/permission/store','UserController@storePermission')->name('admin.permission.store');
Route::get('/user/permission/{id}/delete','UserController@deletePermission')->name('admin.permission.delete');
Route::get('/user/permission/{id}/edit','UserController@editPermission')->name('admin.permission.edit');
Route::post('/user/permission/{id}/update','UserController@updatePermission')->name('admin.permission.update');





    Route::prefix('influencer')->group(function () {

        Route::get('/active-bookings','AdminController@influencer_active_bookings');
        Route::get('/pending-bookings','AdminController@influencer_pending_bookings');
        Route::get('/rejected-bookings','AdminController@influencer_rejected_bookings');
        Route::get('/unserviced-bookings','AdminController@influencer_unserviced_bookings');
        Route::get('/complete-bookings','AdminController@influencer_complete_bookings');
        Route::get('/overdue-bookings','AdminController@influencer_overdue_bookings');
        Route::get('/delivered-bookings','AdminController@influencer_delivered_bookings');
        Route::get('/confirmed-deliveries','AdminController@influencer_confirmed_deliveries');
        Route::get('/revoked-bookings','AdminController@influencer_revoked_bookings');

    });

    Route::prefix('counties')->group(function () {
        Route::get('/view-all','CountiesController@index')->name('admin.counties');
        Route::post('/save','CountiesController@store')->name('admin.counties-save');
        Route::post('/update/{county}','CountiesController@update')->name('admin.counties-update');
        Route::get('/view/{county}','CountiesController@show')->name('admin.counties.show');

    Route::prefix('locations')->group(function () {

        Route::get('/all','PickupLocationController@index')->name('admin.locations.all');
        Route::post('/save','PickupLocationController@store')->name('admin.locations.save');
        Route::post('/update/{location}','PickupLocationController@update')->name('admin.locations.update');

        });
    });

    Route::prefix('zones')->group(function () {
        Route::get('/view-all','NairobiZonesController@index')->name('admin.zones');
        Route::post('/save','NairobiZonesController@store')->name('admin.zones-save');
        Route::post('/update/{county}','NairobiZonesController@update')->name('admin.zones-update');
        Route::get('/view/{county}','NairobiZonesController@show')->name('admin.zones.show');

    Route::prefix('dropoffs')->group(function () {

        Route::get('/all','NairobiDropOffsController@index')->name('admin.dropoffs.all');
        Route::post('/save','NairobiDropOffsController@store')->name('admin.dropoffs.save');
        Route::post('/update/{location}','NairobiDropOffsController@update')->name('admin.dropoffs.update');

        });
    });

    Route::prefix('vendor')->group(function () {


        Route::get('/delete-account/{id}','AdminController@vendor_delete_account');
        Route::get('/pending-products','AdminController@vendor_pending_products');
        Route::get('/approved-products','AdminController@vendor_approved_products');
        Route::get('/product-view','AdminController@vendor_product_view');
        Route::get('/products-report/{id}','ReportGenerator@vendor_products_report');
        Route::get('/active-bookings-report/{id}','ReportGenerator@vendor_active_bookings_report');
        Route::get('/pending-bookings-report/{id}','ReportGenerator@vendor_pending_bookings_report');
        Route::get('/rejected-bookings-report/{id}','ReportGenerator@vendor_rejected_bookings_report');
        Route::get('/unserviced-bookings-report/{id}','ReportGenerator@vendor_unserviced_bookings_report');
        Route::get('/complete-bookings-report/{id}','ReportGenerator@vendor_complete_bookings_report');
        Route::get('/overdue-bookings-report/{id}','ReportGenerator@vendor_overdue_bookings_report');
        Route::get('/delivered-bookings-report/{id}','ReportGenerator@vendor_delivered_bookings_report');
        Route::get('/confirmed-deliveries-report/{id}','ReportGenerator@vendor_confirmed_deliveries_report');
        Route::get('/rejected-products','AdminController@vendor_rejected_products');
        Route::get('/product-reject/{id}','AdminController@reject_vendor_product');
        Route::get('/product-view/{id}','AdminController@view_vendor_product');

      });
      Route::prefix('influencer')->group(function () {
        Route::get('/delete-account/{id}','AdminController@influencer_delete_account');
        Route::get('/pending-products','AdminController@influencer_pending_products');
        Route::get('/approved-products','AdminController@influencer_approved_products');
        Route::get('/product-view','AdminController@influencer_product_view');
        Route::get('/products-report/{id}','ReportGenerator@influencer_products_report');
        Route::get('/active-bookings-report/{id}','ReportGenerator@influencer_active_bookings_report');
        Route::get('/pending-bookings-report/{id}','ReportGenerator@influencer_pending_bookings_report');
        Route::get('/rejected-bookings-report/{id}','ReportGenerator@influencer_rejected_bookings_report');
        Route::get('/unserviced-bookings-report/{id}','ReportGenerator@influencer_unserviced_bookings_report');
        Route::get('/complete-bookings-report/{id}','ReportGenerator@influencer_complete_bookings_report');
        Route::get('/overdue-bookings-report/{id}','ReportGenerator@influencer_overdue_bookings_report');
        Route::get('/delivered-bookings-report/{id}','ReportGenerator@influencer_delivered_bookings_report');
        Route::get('/confirmed-deliveries-report/{id}','ReportGenerator@influencer_confirmed_deliveries_report');
        Route::get('/rejected-products','AdminController@influencer_rejected_products');
        Route::get('/product-reject/{id}','AdminController@reject_influencer_product');
        Route::get('/product-view/{id}','AdminController@view_influencer_product');
      });
    Route::prefix('agent')->group(function () {
        Route::get('/delete-account/{id}','AdminController@agent_delete_account');
        Route::get('/pending-products','AdminController@agent_pending_products');
        Route::get('/approved-products','AdminController@agent_approved_products');
        Route::get('/rejected-products','AdminController@agent_rejected_products');
        Route::get('/product-reject/{id}','AdminController@reject_agent_product');
        Route::get('/product-view/{id}','AdminController@view_agent_product');
        Route::get('/product-approve/{id}','AdminController@approve_product');
        Route::get('/products-report/{id}','ReportGenerator@agent_products_report');
        Route::get('/active-bookings-report/{id}','ReportGenerator@agent_active_bookings_report');
        Route::get('/pending-bookings-report/{id}','ReportGenerator@agent_pending_bookings_report');
        Route::get('/rejected-bookings-report/{id}','ReportGenerator@agent_rejected_bookings_report');
        Route::get('/unserviced-bookings-report/{id}','ReportGenerator@agent_unserviced_bookings_report');
        Route::get('/complete-bookings-report/{id}','ReportGenerator@agent_complete_bookings_report');
        Route::get('/overdue-bookings-report/{id}','ReportGenerator@agent_overdue_bookings_report');
        Route::get('/delivered-bookings-report/{id}','ReportGenerator@agent_delivered_bookings_report');
        Route::get('/confirmed-deliveries-report/{id}','ReportGenerator@agent_confirmed_deliveries_report');
    });
    });
});
