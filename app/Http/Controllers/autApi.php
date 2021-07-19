<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DB;
use File;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use AfricasTalking\SDK\AfricasTalking;
use App\Http\Controllers\SendSMSController;
use Illuminate\Support\Facades\Mail;
use \App\Mail\SendRegistrationEmail;
use App\Http\Controllers\pushNotification;

class autApi extends Controller
{



    function registerUser(Request $request){
    	$phone=$request->input("phone");
    	$email=$request->input("email");
    	$userexists=\App\User::whereEmail($email)->first();

 list($msisdn, $network) = $this->get_msisdn_network($request->phone);

        if (!$msisdn){
            return Array("response"=>"Please enter a valid phone number!","error"=>true);
        }else{
            $valid_phone = $msisdn;

        }


    	$phoneexists=\App\Customers::wherePhone($valid_phone)->first();
    	if ($userexists==null) {
    		# code...

if ($phoneexists==null) {
	# code...


$user = new \App\User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->platform="mobile";
        $user->password = Hash::make($request->input('phone'));
        $user->save();

        $user_id = DB::getPdo()->lastInsertId();

        $customer = new \App\Customers();
        $customer->user_id = $user_id;
        $customer->phone  = $valid_phone;
        $customer->save();
          $phone=\App\Customers::whereUser_id($user_id)->first()->phone;
            return Array("response"=>$user,"error"=>false,"phone"=>$valid_phone);
}
else{
return Array("response"=>"Phone Number Already taken","error"=>true);

}

    	}
    	else{
    		return Array("response"=>"Email Already taken","error"=>true);
    	}




    }


    function ifPhoneExists(Request $request){
$phone=$request->input("phone");
    	$phoneExists=\App\Customers::wherePhone($phone)->first();
    	if ($phoneExists!=null) {
    		# code...
    		return Array("response"=>"Phone Exists","error"=>false);
    	}
    	else{
return Array("response"=>"no records exists","error"=>true);
    	}

    }

    function resetPassword(Request $request){
    	$phone=$request->input("phone");
    	$phoneExists=\App\Customers::wherePhone($phone)->first();

    	$user_id=$phoneExists->user_id;
    	$response=\App\User::whereId($user_id)->update(["password"=>Hash::make($request->input('password'))]);

    		return Array("response"=>"Password Updated Successfully","error"=>false);

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lipaNaMpesaPassword($lipa_time)
    {

        $passkey = env('STK_PASSKEY');
        $BusinessShortCode = env('MPESA_SHORT_CODE');
        $timestamp =$lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode.$passkey.$timestamp);
        return $lipa_na_mpesa_password;
    }


        private function get_msisdn_network($msisdn){
            $regex =  [
                 'airtel' =>'/^\+?(254|0|)7(?:[38]\d{7}|5[0-6]\d{6})\b/',
                 'equitel' => '/^\+?(254|0|)76[0-7]\d{6}\b/',
                 'safaricom' => '/^\+?(254|0|)(?:7[01249]\d{7}|1[01234]\d{7}|75[789]\d{6}|76[89]\d{6})\b/',
                 'telkom' => '/^\+?(254|0|)7[7]\d{7}\b/',
             ];

             foreach ($regex as $operator => $re ) {
                 if (preg_match($re, $msisdn)) {
                     return [preg_replace('/^\+?(254|0)/', "254", $msisdn), $operator];
                 }
             }
             return [false, false];
         }

 public function stk_push($amount,$msisdn,$booking_ref){

        $consumer_key =  env('CONSUMER_KEY');
        $consume_secret = env('CONSUMER_SECRET');
        $headers = ['Content-Type:application/json','Charset=utf8'];
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_USERPWD,$consumer_key.':'.$consume_secret);

        $curl_response = curl_exec($curl);
        $result = json_decode($curl_response);

        $token = $result->access_token;

        $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        Log::info("Generated access token " . $token);

        $timestamp = date("YmdHis");

        $BusinessShortCode = env('MPESA_SHORT_CODE');

        $passkey = env('STK_PASSKEY');

        $lipa_time = Carbon::rawParse('now')->format('YmdHms');

        $apiPassword = $this->lipaNaMpesaPassword($lipa_time);

        Log::info("Generated Password " . $apiPassword);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token)); //setting custom header

        $curl_post_data = array(

            'BusinessShortCode' => env('MPESA_SHORT_CODE'),
            'Password'          => $apiPassword,
            'Timestamp'         => $lipa_time,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $msisdn,
            'PartyB'            =>env('MPESA_SHORT_CODE'),
            'PhoneNumber'       => $msisdn,
            'CallBackURL'       => 'https://mosmos.co.ke/api/stk-callback',
            'AccountReference'  => $booking_ref,
            'TransactionDesc'   => 'Mosmos Product Payment'
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        $responseArray = json_decode($curl_response, true);
        $status = 200;
        $success = true;
        $message = "STK Request Success";
        $httpCode = 200;

        \Log::info('STK DATA => '.print_r(json_encode($responseArray),1));

        if(array_key_exists("errorCode", $responseArray)){
            $message = "Automatic payment failed. Go to your MPESA, Select Paybill Enter : env('MPESA_SHORT_CODE') and Account Number : ".$booking_ref."Enter Amount : ".number_format($amount,2)." Thank you.";

            return Array("response"=>$message,"success"=>false,"error"=>false);
        }else{
            $message = "A payment prompt has been sent to your phone.Enter MPesa PIN if prompted.";
           return Array("response"=>$message,"success"=>true,"error"=>false);
        }



        return $message;
    }

      public function MakePayment(Request $request){

        $msisdn=$request->input("phone");
        $amount=$request->input('amount');
        $booking_ref=$request->input("bookingref");

 $message =  $this->stk_push($amount,$msisdn,$booking_ref);


 return $message;
    }



/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    private function roundToTheNearestAnything($value, $roundTo)
    {
        $mod = $value%$roundTo;
        return $value+($mod<($roundTo/2)?-$mod:$roundTo-$mod);
    }


      public function make_booking(Request $request){
        $county_id = $request->county_id;
        $exact_location = $request->exact_location;
        $vendor_code = $request->vendor_code;

        $vendor=\App\Vendor::whereId($vendor_code)->first();
        if ($vendor!=null) {
            $vendor_code=$vendor->vendor_code;
            # code...
        }
        else{
            $vendor="VD1";
        }

        $categories = \App\Categories::all();

        list($msisdn, $network) = $this->get_msisdn_network($request->phone);

        if (!$msisdn){

            return redirect()->back()->with('error',"Please enter a valid phone number!");
        }else{
            $valid_phone = $msisdn;
        }
        //Valid email
        $valid_email = preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/", $request->email, $e_matches);

        $product = \App\Products::find($request->product_id);

        if($product->weight != 0){
            $weight_array = preg_split('#(?<=\d)(?=[a-z])#i', $product->weight);
        }else{
            $weight_array = (['0','g']);
        }
        // For Other counties
        $county = \App\Counties::find($request->county_id);

        $product_weight = $weight_array;

        if($product_weight[1] == 'g'){
            $shipping_cost = 500;
        }elseif($product_weight[1] == 'kg' && $product_weight[0]<=5){
            $shipping_cost = 500;
        }elseif($product_weight[1] == 'kg' && $product_weight[0]>5){
            $extra_kg = $product_weight[0] - 5;
            $extra_cost = (50 * $extra_kg);
            $shipping_cost = 500 + $extra_cost;
        }

        $total_cost = $product->product_price + $shipping_cost;

        $total_cost = $this->roundToTheNearestAnything($total_cost, 5);

        $existingUser = \App\User::where('email',  $request->input('email'))->first();

        if($existingUser!=null)
        {

        $user = $existingUser;

        $existingCustomer = \App\Customers::where('user_id','=',$existingUser->id)->first();


        $booking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->whereNotIn('status', ['complete','revoked'])->first();

        if($booking!=null){
            return Array("error"=>true,"response"=>"you already have an existing bookings");
        }

        \Auth::login($user);


        for($i=0;$i<1000000;$i++){
            $booking_reference = 'MM'.rand(10000,99999);
            $res=\App\Bookings::whereBooking_reference($booking_reference)->first();
            if ($res==null) {
                # code...
break;
            }

        }

        $booking_date = now();


        $due_date = Carbon::now()->addMonths(3);


        $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$request->product_id)->first();


        if($request->initial_deposit<100){

         return Array("error"=>true,"response"=>"minimum deposit is Ksh. 100");

        }

$balance=$existingUser->balance;

$booking = new \App\Bookings();
 $recipients = $valid_phone;
if (intval($balance)==0) {
   $booking->balance =   $total_cost-100;
$booking->amount_paid = "0";
$booking->status = "pending";
}
else{

    if (intval($total_cost)<intval($balance)) {
        # code...
        \App\User::where('email',  $request->input('email'))->update(["balance"=>intval($balance)-intval($total_cost)]);
        $booking->status = "complete";
        $booking->balance="0";

         $message =  "Ksh ".$balance." from your mosmos wallet has been used fully pay your placed order";
    }
    else{

         \App\User::where('email',  $request->input('email'))->update(["balance"=>0]);
        $booking->balance =   $total_cost-(intval($balance))-100;
$booking->amount_paid = $balance;
$booking->status = "active";
 $message =  "Ksh ".$balance." from your mosmos wallet has been used to pay for ordered item partially remaining amount is Ksh.".number_format($total_cost-(intval($balance))-100);
    }



        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");
}


        $booking->customer_id = $existingCustomer->id;
        $booking->product_id  = $request->product_id;
        $booking->booking_reference = $booking_reference;
        $booking->platform="mobile";
        $booking->quantity  = '1';
        $booking->discount  = 100;

        $booking->item_cost = $product->product_price;

        $booking->payment_mode  = 'Mpesa';
        $booking->date_started  = now();
        $booking->due_date = $due_date;

        $booking->vendor_code = $vendor_code;
        $booking->location_type = "Exact Location";
        $booking->item_cost = $product->product_price;
        $booking->shipping_cost = $shipping_cost;
        $booking->county_id = $request->county_id;
        $booking->exact_location = $request->exact_location;
        $booking->total_cost =  $total_cost-100;

        $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

        $booking_reference = 'MM'.rand(10000,99999);

        \App\Bookings::where('id',$booking_id)->update(['booking_reference'=>$booking_reference]);


       $recipients = $valid_phone;

        $booking_id = DB::getPdo()->lastInsertId();

        $product = \App\Products::find($request->product_id);

        $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking_reference." and amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";
    $token=\App\User::whereId($existingCustomer->user_id)->first()->token;
    if ($token==null) {
        # code...
       return $message;
    }
    $obj = new pushNotification();
    $data=Array("name"=>"bookingsuccess","value"=>"Bookings");
    $obj->exceuteSendNotification($token,"You have successfully booked ".$product->product_name,"Booking Successful",$data);

    $data=Array("name"=>"bookingsuccess","value"=>"Bookings");
    $obj->exceuteSendNotification($token,"You have received KSh.100 from us. Thanks for your order","Congratulations! ",$data);

      return $message;

        }


        $existingCustomer = \App\Customers::where('phone','=',$valid_phone)->first();

        if($existingCustomer)
        {

        $booking_reference = 'MM'.rand(10000,99999);

        $booking_date = now();

        $$booking_date = strtotime($booking_date);

        $product = \App\Products::find($request->product_id);

       $due_date = Carbon::now()->addMonths(3);

        if($request->initial_deposit<100){

    return Array("error"=>true,"response"=>"minimum deposit is Ksh. 100");

        }

        $booking = new \App\Bookings();
        $booking->customer_id = $existingCustomer->id;
        $booking->product_id  = $request->product_id;
        $booking->county_id = $request->county_id;
        $booking->exact_location = $exact_location;
        $booking->booking_reference = $booking_reference;
        $booking->quantity  = "1";
        $booking->amount_paid = "0";
        $booking->balance = intval($total_cost)-100;
        $booking->item_cost = $product->product_price;
        $booking->shipping_cost = $shipping_cost;
        $booking->payment_mode  = 'Mpesa';
        $booking->vendor_code = $vendor_code;
        $booking->date_started  = now();
        $booking->due_date = $due_date;
        $booking->discount=100;
        $booking->status = "pending";
        $booking->total_cost = intval($total_cost)-100;
        $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

        $recipients = $valid_phone;

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $product = \App\Products::find($request->product_id);

        $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking_reference." And amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";


   $token=\App\User::whereId($existingCustomer->user_id)->first()->token;
    if ($token==null) {
        # code...
       return $message;
    }
    $obj = new pushNotification();
    $data=Array("name"=>"bookingsuccess","value"=>"Bookings");
    $obj->exceuteSendNotification($token,"You have successfully booked ".$product->product_name,"Booking Successful",$data);

        return $message;

        }


    }

    function firebasetopics(Request $request){
$result=DB::table("firebasetopics")->get();

return $result;
    }

    function callBack(Request $request){
    $stkResponse = file_get_contents('php://input');

        Log::info("STK CALLBACKs => ".print_r($stkResponse,true));

         $body=($request->all())['Body']['stkCallback'];
         $MerchantRequestID=$body['MerchantRequestID'];
         $ResultCode=$body['ResultCode'];

          if ($ResultCode==0) {
  $result=DB::table("monitorpay")->get();
                if (count($result)==0) {
                    DB::table("monitorpay")->insert(["total"=>0,"mobile"=>1]);
                }
                else{
                    $total=intval($result[0]->mobile)+1;
                    DB::table("monitorpay")->update(["mobile"=>$total]);
                }
          }

        return 0;
    }

    function updateAccountNumbers(Request $request){


        $users=\App\User::get();
        foreach ($users as $key => $value) {
            # code...

        for($i=0;$i<1000000;$i++){
            $mid = 'MID'.rand(10000,99999);
            $res=\App\User::wheremosmosid($mid)->first();
            if ($res==null) {             # code...
break;  }

        }

\App\User::whereId($value->id)->update(["mosmosid"=>$mid]);

        }

    }

public function getUserDetails(Request $request){

        $phone=$request->input('phone');
        $customer=\App\Customers::wherePhone($phone)->first();
        if ($customer==null) {
return Array("response"=>"error fetching data","error"=>true);
        }
        else{
$user=\App\User::whereId($customer->user_id)->first();
$result=$user;
$result['customer']=$customer;
$bookings=\App\Bookings::whereCustomer_id($customer->id)->whereStatus('active')->first();
if ($bookings==null) {
    # code...
    $result['county_id']=0;
    $result['exact_location']="";
}
else{
    $result['county_id']=$bookings->county_id;
    $result['exact_location']=$bookings->exact_location;

}

        }
         $counties=\App\Counties::get();
         $result['counties']=$counties;
         $result['error']=false;
    return $result;

}

public function updateaccount(Request $request){

    $phone=$request->input('phone');
    $name=$request->input('name');
    $county_id=$request->input('county_id');
    $email=$request->input('email');
    $exactlocation=$request->input('exactlocation');

$customer=\App\Customers::wherePhone($phone)->first();

if ($customer==null) {
    # code...
        return Array("data"=>Array("response"=>"We are currently unable to update your account.contact administrator"),"error"=>true);
}

//update booking addresses
$array=Array("county_id"=>$county_id,"exact_location"=>$exactlocation);
\App\Bookings::whereCustomer_id($customer->id)->update($array);
$array=Array("name"=>$name,"email"=>$email);
\App\User::whereId($customer->user_id)->update($array);
   return Array("data"=>Array("response"=>"Account updated successfully"),"error"=>false,"datas"=>\App\User::whereId($customer->user_id)->first());

}



}
