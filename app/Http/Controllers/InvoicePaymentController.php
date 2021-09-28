<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bookings;

class InvoicePaymentController extends Controller
{
    //
    function index(Request $request,$ordernumber){

    	$booking_code=decrypt($ordernumber, "mosmos#$#@!89&^");
$booking=Bookings::with('product','customer.user','payments')->whereBooking_reference($booking_code)->first();
//removed customer county ,'customer.county'
if ($booking==null) {
	# code...
	return "Booking does not exist";
}

    	return view('invoice.booking',compact('booking','ordernumber'));
    }

    function pay(Request $request,$ordernumber){
    		$booking_code=decrypt($ordernumber, "mosmos#$#@!89&^");
    		$booking=Bookings::with('customer.user','product')->whereBooking_reference($booking_code)->first();

if ($booking==null) {
	# code...
	return "Booking does not exist";
}

    	return view('invoice.pay',compact('booking'));
    }
   
    function keySettings(Request $request){
        $string="MM45300";

 $encrypted = encrypt($string, "mosmos#$#@!89&^");


return $encrypted;

    }


/**
 * Returns an encrypted & utf8-encoded
 */
function encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}

/**
 * Returns decrypted original string
 */
function decrypt($encrypted_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypted_string = mcrypt_decrypt(MCRYPT_BLOWFISH, $encryption_key, $encrypted_string, MCRYPT_MODE_ECB, $iv);
    return $decrypted_string;
}
}
