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
use \App\Mail\SendPaymentEmail;
use App\Mail\SendPaymentMailToAdmin;
use App\Mail\SendBookingMail;

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

$balance=0;
//$existingUser->balance;

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
        $booking->amount_paid = $total_cost;
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
    if ($request->setreminder!= null) {
        $booking->setdate= $request->setdate;
    $booking->setreminder= $request->setreminder;
        # code...
    }
    else{
        $booking->setdate='2021-09-09';
    $booking->setreminder= 0;
    }

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

        // $booking_reference = 'MM'.rand(10000,99999);
           for($i=0;$i<1000000;$i++){
            $booking_reference = 'MM'.rand(10000,99999);
            $res=\App\Bookings::whereBooking_reference($booking_reference)->first();
            if ($res==null) {
                # code...
break;
            }

        }

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

        // $booking_reference = 'MM'.rand(10000,99999);
               for($i=0;$i<1000000;$i++){
            $booking_reference = 'MM'.rand(10000,99999);
            $res=\App\Bookings::whereBooking_reference($booking_reference)->first();
            if ($res==null) {
                # code...
break;
            }

        }

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
            if ($request->setreminder!= null) {
          $booking->setdate= $request->setdate;
        $booking->setreminder= $request->setreminder;
          # code...
      }
      else{
           $booking->setdate='2021-09-09';
        $booking->setreminder= 0;
      }
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

function MakeWalletPayment(Request $request){
          $msisdn=$request->input("phone");
        $amount=$request->input('amount');
        $booking_ref=$request->input("bookingref");
$bill_ref_no=$request->input("bookingref");
 //$message =  $this->stk_push($amount,$msisdn,$booking_ref);

            $travelPattern = "/t/i";
    
            $travelTrue = preg_match($travelPattern,$booking_ref);

            if ($travelTrue) {

 //return Array("response"=>"Booking does not exist","success"=>false,"error"=>true);

                # code...
                return $this->TravelWalletPayments($bill_ref_no,$amount,$phone,'','','',0);
            }









 $date_paid = Carbon::today()->toDateString();

        $booking = \App\Bookings::with('product','payments','payments.mpesapayment','customer','customer.user','county','location')->where('booking_reference','=',$booking_ref)->first();


        if($booking == null){
            return Array("response"=>"Booking does not exist","success"=>false,"error"=>true);
        }

 //return $message;
 $user=\App\User::whereId($booking->customer->user_id);
$obj=$user->first();
if ($obj->balance<$amount) {
    # code...
          return Array("response"=>"you have insufficient balance to complete this transaction.","success"=>false,"error"=>true);
}


        if($booking->status == 'pending'){

           if($booking->vendor_code !== null){
                $vendor = \App\Vendor::where('vendor_code','=',$booking->vendor_code)->first();
                if($vendor == null){

                   }else {

                    $recipients = $vendor->phone;

                    $details = [
                        'customer'=> $booking->customer->user->name,
                        'booking_reference'=>$booking->booking_reference

                    ];

                    Mail::to($vendor->user->email)->send(new SendBookingMail($details));

                 }
            }
        }

        $payment = new \App\Payments();
        $payment->booking_id = $booking->id;
        $payment->customer_id = $booking->customer_id;
        $payment->product_id  = $booking->product_id;
        $payment->transaction_amount =$request->amount;
        $payment->booking_status = 'active';
        $payment->date_paid = now();
        $payment->save();

        $payment_id = DB::getPdo()->lastInsertId();

        $amount_paid = $booking->amount_paid +$request->amount;

        $balance = $booking->total_cost - $amount_paid;
\App\commission_records::create(Array("amount"=>$amount_paid,"booking_reference"=>$booking_ref,"transaction_origin"=>"mobile","booking_id"=>$booking->id));
        if($balance<1){

            DB::table('bookings')
            ->where('booking_reference','=',$booking_ref)
            ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'complete','updated_at'=>now()]);

            $recipients = $booking->customer->phone;

            if($booking->location_type == 'store_pickup'){

                if($booking->vendor_code !== null){

                    if($vendor == null){

                    }else {
                        $location = " Your will pick your product at ".$vendor->location;
                    }

                }elseif($booking->agent_code !== null){

                    $agent = \App\Agents::where('agent_code','=',$booking->agent_code)->first();
                    if($agent == null){

                    }else {
                     $location = " Your will pick your product at ".$agent->location;
                    }

                }

            }


            $message = "Congratulations, You have completed Payment of ".$booking->product->product_name.$location.", You will be contacted for more information.";

            SendSMSController::sendMessage($recipients,$message,$type="booking_completed_notification");

            $product = \App\Products::with('subcategory')->where('id','=',$booking->product_id)->first();

            if($booking->vendor_code !== null){
                $vendor = \App\Vendor::where('vendor_code','=',$booking->vendor_code)->first();
                if($vendor == null){

                   }else {

                    $commission_rate=$vendor->commission_rate;
                    $commision_cap=$vendor->commission_cap;
                    $admin_commission=floatval($product->product_price)*($commission_rate/100);
                    if ($admin_commission>=$commision_cap) {
                    $admin_commission=$commision_cap;
                    # code...
                    }
                    $vendor_commission=floatval($product->product_price)-$admin_commission;

                    // $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                    // $vendor_commission = $product->product_price * ((100-$product->subcategory->commision)/100);

                    $recipients = $vendor->phone;

                    $message = $booking->customer->user->name . " has completed payment of booking ref ".$booking->booking_reference;

                    SendSMSController::sendMessage($recipients,$message,$type="booking_completed_notification");

                    DB::table('commissions')->insert([
                        'product_id' => $product->id,
                        'booking_id' => $booking->id,
                        'vendor_id' =>  $vendor->id,
                        'admin_commission' =>$admin_commission,
                        'other_party_commission' => $vendor_commission,
                        'created_at'=>now(),
                        'updated_at'=>now(),
                        ]);

                   }
            }

        }else{

            DB::table('bookings')
            ->where('booking_reference','=',$booking_ref)
            ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'active']);
        }
        $user=\App\User::whereId($booking->customer->user_id);
$obj=$user->first();
if($obj!=null){
    $mosmosbalance=$obj->balance;
$mosmosbalance=$mosmosbalance-$amount;
$user->update(["balance"=>$mosmosbalance]);

        for($i=0;$i<1000000;$i++){
            $transid = 'TB'.rand(10000,99999)."M";
            $res=\App\topups::whereTransid($transid)->first();
            if ($res==null) {             # code...
break;  }
          
        }

$credentials=Array("amount"=>$amount,"balance"=>$mosmosbalance,"transid"=>$transid,"sender"=>$obj->id,"type"=>$booking_ref);
\App\topups::create($credentials);

  $obj = new pushNotification();
    //$data=Array("name"=>"home","value"=>"home");
    // $obj->exceuteSendNotification($user->first()->token,"Buy Airtime and pay utility bills at KSh.0 transaction cost.","Booking payment successful!",$data);
    $data=Array("name"=>"payment","value"=>"Payments");
    $obj->exceuteSendNotification($user->first()->token,"Your payment of KSh.".$request->amount ." for Order Ref ".$booking_ref." has been received.","Payment Received",$data);


}

        //DB::table('payment_logs')->where('id',$)->update(['status'=>'valid']);

        DB::table('mpesapayments')
            ->insert([
                      'payment_id'=>$payment_id,
                      'amount_paid'=>$request->amount,
                      'phone'=>$msisdn,
                      'transac_code'=>$transid,
                      'date_paid'=>$date_paid,
                      'created_at'=>now(),
                      'updated_at'=>now()
                      ]);

        $message = 'Success';

        $recipients = $recipients = $booking->customer->phone;

       $request->amount = number_format($request->amount,2);
        $balance =number_format($balance,2);

        $payment_count = \App\PaymentLog::where('BillRefNumber',$booking->booking_reference)->count();


        if($payment_count<2){
                    $shipping_cost = $booking->shipping_cost;
                    //$message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}. Incl delivery cost of KES .{$shipping_cost}.Download our app to easily track your payments - http://bit.ly/MosMosApp.";

                    $message="Payment of KSh.{$request->amount} for {$bill_ref_no} received. Txn. {$transid}. Bal is KSh.{$balance} incl delivery cost. Download our app to easily track your payments - http://bit.ly/MosMosApp";


        }else{

            $message    ="Payment of KES. {$request->amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$transid}. Balance KES. {$balance}.Download our app to easily track your payments - http://bit.ly/MosMosApp." ;

        }


        SendSMSController::sendMessage($recipients,$message,$type="payment_notification");

        $data['receiver'] = $recipients;
        $data['type'] = 'payment_notification';
        $data['message'] = $message;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        //DB::table('s_m_s_logs')->insert($data);

        $details = [
            'customer'=> $booking->customer->user->name,
            'booking_reference'=>$booking->booking_reference,
            'amount_paid'=>$request->amount,
            'product'=>$booking->product->product_name,
            'mpesa_ref'=>$transid,
            'balance'=> $booking->balance

        ];

        Mail::to('order@mosmos.co.ke')->send(new SendPaymentMailToAdmin($details));


        $latestPayment = \App\Payments::with('mpesapayment')->where('booking_id',$booking->id)->latest()->first();

        $details  = [
            'customer_name'=>$booking->customer->user->name,
            'product_name'=>$booking->product->product_name,
            'booking_reference'=>$booking->booking_reference,
            'total_cost'=>number_format($booking->total_cost,2),
            'amount_paid'=>number_format($booking->amount_paid),
            'balance'=>$balance,
            'date_paid'=>$date_paid,
            'product_price'=>number_format($booking->product->product_price),
            'payments'=>$booking->payments,
            'latestPayment'=>$latestPayment
        ];

        Mail::to($booking->customer->user->email)->send(new SendPaymentEmail($details));

        return Array("response"=>"Payment successful","success"=>true,"error"=>false);
}

public function createCardTransaction(Request $request){

$hashkey = env('IpayKey');
$ivid=env('IpayId');
$msisdn=$request->input("phone");
$amount=$request->input('amount');
$booking_ref=$request->input("bookingref");

$customer=\App\Customers::wherePhone($msisdn)->first();
if ($customer==null) {
    # code...
    return Array("successs"=>false,"an error occured try again later");
}
$user=\App\User::whereId($customer->user_id)->first();
// $hashkey = '89siusx8siys8sus7';
// $ivid='rdfyne';

// $hashkey =  env('IpayKey');
// $ivid=env('IpayId');
$bytes = random_bytes(20);

$trans_amount=$amount;
$rate=0.035;

$ilive             = "1";
$icbk              = "https://mosmos.co.ke/capturepayment";
$iamount           = round(0.035*$trans_amount)+$trans_amount;
$p1               = $amount;
$p2               = "xx";
$p3               = "xx";
$p4               = "xx";
$curr             = "KES"; //or USD
$itel     = $msisdn;
$ieml            = $user->email;

$icst = "1";
$icrl = "0";
$icurr = $curr;
$oid='K'.rand(10000,99999);
$iinv=$booking_ref;



$data_string = $ilive.$ioid .$iinv.$iamount.$itel.$ieml.$ivid.$icurr.$p1.$p2.$p3.$p4.$icbk.$icst.$icrl;

// $data_string = "live=".$live."&oid=".$ioid."&inv".$iinv."&amount=".$amount."&tel=".$itel."&email=".$ieml."&vid=".$ivid."&curr=".$icurr."&p1=".$p1."&p2=".$p2."&p3=".$p3."&p4=".$p4."&cbk=".$icbk."&cst=".$icst."&crl".$icrl;
$hashed = hash_hmac("sha1", $data_string, $hashkey);
// $datastring = "account=".$account."&amount=".$amount."&biller_name=".$biller_name."&phone=".$phone."&vid=".$IpayId ;
$url="https://payments.ipayafrica.com/v3/ke?live=".$ilive."&mm=1&mb=1&dc=1&cc=1&mer=ipay"."&mpesa=0&airtel=0&equity=0&creditcard=1&elipa=0&debitcard=1"."&oid=".$ioid."&inv=".$iinv."&ttl=".$iamount."&tel=".$itel."&eml=".$ieml."&vid=".$ivid."&p1=".$p1."&p2=".$p2."&p3=".$p3."&p4=".$p4."&crl=".$icrl."&cbk=".$icbk."&cst=".$icst."&curr=".$icurr."&hsh=".$hashed;


return Array("url"=>$url,"fee"=>(round(0.035*$trans_amount)),"trans_amount"=>(round(0.035*$trans_amount)+$trans_amount ),"success"=>true);


}

public function TravelCardTransaction(Request $request){

// $hashkey = env('IpayKey');
// $IpayId=env('IpayId');
$msisdn=$request->input("phone");
$amount=$request->input('amount');
$booking_ref=$request->input("bookingref");

$customer=\App\Customers::wherePhone($msisdn)->first();
if ($customer==null) {
    # code...
    return Array("successs"=>false,"an error occured try again later");
}
$user=\App\User::whereId($customer->user_id)->first();
// $hashkey = '89siusx8siys8sus7';
// $ivid='rdfyne';
$hashkey = env('IpayKey');
$ivid=env('IpayId');
// $hashkey =  env('IpayKey');
// $ivid=env('IpayId');
$trans_amount=$amount;
$rate=0.035;


$ilive             = "1";
$icbk              = "https://mosmos.co.ke/capturepayment";
$iamount           = round(0.035*$trans_amount)+$trans_amount;
$p1               = $amount;
$p2               = "xx";
$p3               = "xx";
$p4               = "xx";
$curr             = "KES"; //or USD
$itel     = $msisdn;
$ieml            = $user->email;

$icst = "1";
$icrl = "0";
$icurr = $curr;
$oid='K'.rand(10000,99999);
$iinv=$booking_ref;

$data_string = $ilive.$ioid .$iinv.$iamount.$itel.$ieml.$ivid.$icurr.$p1.$p2.$p3.$p4.$icbk.$icst.$icrl;

// $data_string = "live=".$live."&oid=".$ioid."&inv".$iinv."&amount=".$amount."&tel=".$itel."&email=".$ieml."&vid=".$ivid."&curr=".$icurr."&p1=".$p1."&p2=".$p2."&p3=".$p3."&p4=".$p4."&cbk=".$icbk."&cst=".$icst."&crl".$icrl;
$hashed = hash_hmac("sha1", $data_string, $hashkey);
// $datastring = "account=".$account."&amount=".$amount."&biller_name=".$biller_name."&phone=".$phone."&vid=".$IpayId ;
$url="https://payments.ipayafrica.com/v3/ke?live=".$ilive."&mm=1&mb=1&dc=1&cc=1&mer=ipay"."&mpesa=0&airtel=0&equity=0&creditcard=1&elipa=0&debitcard=1"."&oid=".$ioid."&inv=".$iinv."&ttl=".$iamount."&tel=".$itel."&eml=".$ieml."&vid=".$ivid."&p1=".$p1."&p2=".$p2."&p3=".$p3."&p4=".$p4."&crl=".$icrl."&cbk=".$icbk."&cst=".$icst."&curr=".$icurr."&hsh=".$hashed;


return redirect($url);


}


      public function TravelMpesaTransaction(Request $request){

        $msisdn=$request->input("phone");
        $amount=$request->input('amount');
        $booking_ref=$request->input("bookingref");
          $kk=$request->input("bookingref");
        
 list($msisdn, $network) = $this->get_msisdn_network($request->phone);

        if (!$msisdn){
            return Array("response"=>"Please enter a valid phone number!","error"=>true);
        }else{
            $valid_phone = $msisdn;

        }
 $message =  $this->stk_push($amount,$valid_phone,$booking_ref);



 return back();
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




function capturepayment(Request $request){



if ($request->status=="aei7p7yrx4ae34") {
  # code...
     $travelPattern = "/t/i";
    
    $travelTrue = preg_match($travelPattern,$request->ivm);

    if ($travelTrue) {
        # code...
        $details=Array('txncd'=>$request->txncd,"uyt"=>$request->uyt,"agt"=>$request->agt,"qwh"=>$request->qwh,"ifd"=>$request->ifd,"poi"=>$request->poi,"oid"=>$request->ivm,"amount"=>$request->p1,"total_amount"=>$request->mc,"channel"=>$request->channel);

        //$bill_ref_no,$transaction_amount,$msisdn,$first_name,$middle_name,$last_name,$log_id,$type='topup'

 DB::connection('mysql2')->table('cardpayments')->insert($details);

 $log_id=DB::getPdo()->lastInsertId();

 $res=$this->TravelWalletPayments($request->txncd,$request->ivm,$request->p1,$request->msisdn_idnum,"","","",$log_id,"card");

 if ($res['success']) {
     # code...
    return redirect("http://travel.test/cardsuccess/".$request->ivm);
 }
 else {
    return redirect("/failed");
 }


    }



$details=Array('txncd'=>$request->txncd,"uyt"=>$request->uyt,"agt"=>$request->agt,"qwh"=>$request->qwh,"ifd"=>$request->ifd,"poi"=>$request->poi,"oid"=>$request->ivm,"amount"=>$request->p1,"total_amount"=>$request->mc,"channel"=>$request->channel);



    $carddetails=\App\Cardpayments::create($details);
    $request->phone=$request->mc;
   $request->amount=$request->p1;
    $request->bookingref=$request->ivm;


 $msisdn=$request->msisdn_idnum;
        $amount=$request->amount;
        $booking_ref=$request->ivm;
$bill_ref_no=$request->ivm;
 //$message =  $this->stk_push($amount,$msisdn,$booking_ref);









 $date_paid = Carbon::today()->toDateString();

Log::info(json_encode($booking_ref));
        $booking = \App\Bookings::with('product','payments','payments.mpesapayment','customer','customer.user','county','location')->where('booking_reference','=',$booking_ref)->first();


 //return $message;
 $user=\App\User::whereId($booking->customer->user_id);
$obj=$user->first();



        if($booking->status == 'pending'){

           if($booking->vendor_code !== null){
                $vendor = \App\Vendor::where('vendor_code','=',$booking->vendor_code)->first();
                if($vendor == null){

                   }else {

                    $recipients = $vendor->phone;

                    $details = [
                        'customer'=> $booking->customer->user->name,
                        'booking_reference'=>$booking->booking_reference

                    ];

                    Mail::to($vendor->user->email)->send(new SendBookingMail($details));

                 }
            }
        }


        $payment = new \App\Payments();
        $payment->booking_id = $booking->id;
        $payment->customer_id = $booking->customer_id;
        $payment->product_id  = $booking->product_id;
        $payment->transaction_amount =$request->amount;
        $payment->booking_status = 'active';
        $payment->date_paid = now();
        $payment->save();

        $payment_id = DB::getPdo()->lastInsertId();

        $amount_paid = $booking->amount_paid +$request->amount;

        $balance = $booking->total_cost - $amount_paid;

        if($balance<1){

            DB::table('bookings')
            ->where('booking_reference','=',$booking_ref)
            ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'complete','updated_at'=>now()]);

            $recipients = $booking->customer->phone;

            if($booking->location_type == 'store_pickup'){

                if($booking->vendor_code !== null){

                    if($vendor == null){

                    }else {
                        $location = " Your will pick your product at ".$vendor->location;
                    }

                }elseif($booking->agent_code !== null){

                    $agent = \App\Agents::where('agent_code','=',$booking->agent_code)->first();
                    if($agent == null){

                    }else {
                     $location = " Your will pick your product at ".$agent->location;
                    }

                }

            }


            $message = "Congratulations, You have completed Payment of ".$booking->product->product_name.$location.", You will be contacted for more information.";

            SendSMSController::sendMessage($recipients,$message,$type="booking_completed_notification");

            $product = \App\Products::with('subcategory')->where('id','=',$booking->product_id)->first();

            if($booking->vendor_code !== null){
                $vendor = \App\Vendor::where('vendor_code','=',$booking->vendor_code)->first();
                if($vendor == null){

                   }else {

                    $commission_rate=$vendor->commission_rate;
                    $commision_cap=$vendor->commission_cap;
                    $admin_commission=floatval($product->product_price)*($commission_rate/100);
                    if ($admin_commission>=$commision_cap) {
                    $admin_commission=$commision_cap;
                    # code...
                    }
                    $vendor_commission=floatval($product->product_price)-$admin_commission;

                    // $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                    // $vendor_commission = $product->product_price * ((100-$product->subcategory->commision)/100);

                    $recipients = $vendor->phone;

                    $message = $booking->customer->user->name . " has completed payment of booking ref ".$booking->booking_reference;

                    SendSMSController::sendMessage($recipients,$message,$type="booking_completed_notification");

                    DB::table('commissions')->insert([
                        'product_id' => $product->id,
                        'booking_id' => $booking->id,
                        'vendor_id' =>  $vendor->id,
                        'admin_commission' =>$admin_commission,
                        'other_party_commission' => $vendor_commission,
                        'created_at'=>now(),
                        'updated_at'=>now(),
                        ]);

                   }
            }

        }else{

            DB::table('bookings')
            ->where('booking_reference','=',$booking_ref)
            ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'active']);
        }
        $user=\App\User::whereId($booking->customer->user_id);
$obj=$user->first();
if($obj!=null){
    $mosmosbalance=$obj->balance;
$mosmosbalance=$mosmosbalance-$amount;
$user->update(["balance"=>$mosmosbalance]);

        for($i=0;$i<1000000;$i++){
            $transid = 'TB'.rand(10000,99999)."M";
            $res=\App\topups::whereTransid($transid)->first();
            if ($res==null) {             # code...
break;  }
          
        }

$credentials=Array("amount"=>$amount,"balance"=>$mosmosbalance,"transid"=>$transid,"sender"=>$obj->id,"type"=>$booking_ref);
\App\topups::create($credentials);

  $obj = new pushNotification();
    //$data=Array("name"=>"home","value"=>"home");
    // $obj->exceuteSendNotification($user->first()->token,"Buy Airtime and pay utility bills at KSh.0 transaction cost.","Booking payment successful!",$data);
    $data=Array("name"=>"payment","value"=>"Payments");
    $obj->exceuteSendNotification($user->first()->token,"Your payment of KSh.".$request->amount ." for Order Ref ".$booking_ref." has been received.","Payment Received",$data);


}

        //DB::table('payment_logs')->where('id',$)->update(['status'=>'valid']);

        DB::table('mpesapayments')
            ->insert([
                      'payment_id'=>$payment_id,
                      'amount_paid'=>$request->amount,
                      'phone'=>$msisdn,
                      'transac_code'=>$transid,
                      'date_paid'=>$date_paid,
                      'created_at'=>now(),
                      'updated_at'=>now()
                      ]);

        $message = 'Success';

        $recipients = $recipients = $booking->customer->phone;

       $request->amount = number_format($request->amount,2);
        $balance =number_format($balance,2);

        $payment_count = \App\PaymentLog::where('BillRefNumber',$booking->booking_reference)->count();


        if($payment_count<2){
                    $shipping_cost = $booking->shipping_cost;
                    //$message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}. Incl delivery cost of KES .{$shipping_cost}.Download our app to easily track your payments - http://bit.ly/MosMosApp.";

                    $message="Payment of KSh.{$request->amount} for {$bill_ref_no} received. Txn. {$transid}. Bal is KSh.{$balance} incl delivery cost. Download our app to easily track your payments - http://bit.ly/MosMosApp";


        }else{

            $message    ="Payment of KES. {$request->amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$transid}. Balance KES. {$balance}.Download our app to easily track your payments - http://bit.ly/MosMosApp." ;

        }


        SendSMSController::sendMessage($recipients,$message,$type="payment_notification");

        $data['receiver'] = $recipients;
        $data['type'] = 'payment_notification';
        $data['message'] = $message;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        //DB::table('s_m_s_logs')->insert($data);

        $details = [
            'customer'=> $booking->customer->user->name,
            'booking_reference'=>$booking->booking_reference,
            'amount_paid'=>$request->amount,
            'product'=>$booking->product->product_name,
            'mpesa_ref'=>$transid,
            'balance'=> $booking->balance

        ];

        Mail::to('order@mosmos.co.ke')->send(new SendPaymentMailToAdmin($details));


        $latestPayment = \App\Payments::with('mpesapayment')->where('booking_id',$booking->id)->latest()->first();

        $details  = [
            'customer_name'=>$booking->customer->user->name,
            'product_name'=>$booking->product->product_name,
            'booking_reference'=>$booking->booking_reference,
            'total_cost'=>number_format($booking->total_cost,2),
            'amount_paid'=>number_format($booking->amount_paid),
            'balance'=>$balance,
            'date_paid'=>$date_paid,
            'product_price'=>number_format($booking->product->product_price),
            'payments'=>$booking->payments,
            'latestPayment'=>$latestPayment
        ];



  return redirect(route('cardsuccess',["details"=>$booking->booking_reference]));

}
else{
    return redirect('/ipayfailed');
}

         


       }


       public static function TravelWalletPayments($transid='xx',$bill_ref_no,$transaction_amount,$msisdn,$first_name,$middle_name,$last_name,$log_id,$type='topup'){
       $booking = DB::connection('mysql2')->table('bookings')->where('booking_reference','=',$bill_ref_no)->first();
       
       if($booking == null){
           return Array('response'=>"Booking Does not exist!","success"=>false);
       }else{



$customer=\App\Customers::whereId($booking->customer_id)->first();
 $user=\App\User::whereId($customer->user_id);
$obj=$user->first();
if ($type=='topup') {
    # code...

if ($obj->balance<$transaction_amount) {
    # code...
          return Array("response"=>"you have insufficient balance to complete this transaction.".json_encode($user),"success"=>false,"error"=>true);
}
}

           
            $customer = DB::connection('mysql2')->table('customers')->where('id',$booking->customer_id)->first();

            $user_customer = DB::connection('mysql2')->table('users')->where('id',$customer->user_id)->first();

            $recipients = $customer->phone;

            $agent = DB::connection('mysql2')->table('travel_agents')->where('id',$booking->agent_id)->first();

            $a_user = DB::connection('mysql2')->table('users')->where('id',$agent->user_id)->first();


            $current_card_payments = $agent->card_payments;
            //$current_offline_payments = $agent->offline_payments;
            $current_total_payments = $agent->total_payments;

            $new_card_payments = $current_card_payments + $transaction_amount;
            $new_total_payments = $agent->total_payments + $transaction_amount;

            $admin_commission = $agent->system_payment_cost;

            $payment_balance = $transaction_amount - $admin_commission;

            DB::connection('mysql2')->table('travel_agents')
                                    ->where('id',$booking->agent_id)
                                    ->update([
                                            'card_payments'=>$new_card_payments,
                                            'usd_wallet_balance'=>$agent->usd_wallet_balance + $payment_balance,
                                           'cardtotal_payments'=>$new_total_payments
                                            ]);

            $admin_wallet = DB::connection('mysql2')->table('admin_wallets')->first();
            
            if(empty($admin_wallet)){
                DB::connection('mysql2')->table('admin_wallets')->insert(['previous_balance'=>0,'current_balance'=>$admin_commission]);
            }else{
                DB::connection('mysql2')->table('admin_wallets')->update(['previous_balance'=>$admin_wallet->previous_balance,'current_balance'=>($admin_wallet->current_balance + $admin_commission)]);
            }

           
           $current_date = date('Y-m-d');

           $date_from = date('Y-m-01', strtotime($current_date));

           $date_to = date("Y-m-t", strtotime($date_from));

           $period = date('M jS'.', '.'Y', strtotime($date_from))." to ".date('M jS'.', '.'Y', strtotime($date_to));

           $commission = \DB::connection('mysql2')->table('system_commissions')->where('agent_id',$booking->agent_id)->where('period',$period)->first();

           if(!empty($commission)){

            $amount = $commission->transaction_amount + $admin_commission;

            $transactions = $commission->transactions + 1;
            $amount = $commission->transaction_amount + $admin_commission;

            \DB::connection('mysql2')->table('system_commissions')->where('id',$commission->id)
                         ->update([
                                    'transactions'=>$transactions,
                                    'transaction_amount'=>$commission->transaction_amount + $transaction_amount,
                                    'commission_paid'=>$commission->commission_paid + $admin_commission,
                                    'unit'=>$agent->system_payment_cost,
                                ]);

           }else{

            $commission = [];
            $commission['agent_id'] = $booking->agent_id;
            $commission['period'] = $period;
            $commission['unit'] = $agent->system_payment_cost;
            $commission['transaction_amount'] = $transaction_amount;
            $commission['commission_paid'] = $admin_commission;
            $commission['date_from'] = $date_from;
            $commission['date_to'] = $date_to;
            $commission['transactions'] = 1;
            $commission['created_at'] = now();
            $commission['updated_at'] = now();

            \DB::connection('mysql2')->table('system_commissions')->insert($commission);

           }

            $payment_data = [
                            'payment_log_id'=>$log_id,
                            'customer_id'=>$customer->id,
                            'agent_id'=>$agent->id,
                            'booking_id'=>$booking->id,
                            'transaction_type'=>"Pay Bill",
                            'amount'=>$transaction_amount,
                            'admin_commission'=>$admin_commission,
                            'balance'=>$payment_balance,
                            'created_at'=>now(),
                            'updated_at'=>now()
                            ];

            DB::connection('mysql2')->table('payments')->insert($payment_data);

            $amount_paid = $booking->amount_paid + $transaction_amount;

            $balance = $booking->balance - $transaction_amount; 

            $f_balance = number_format($balance,2);
            $f_transaction_amount =  number_format($transaction_amount);

            $data = ['amount_paid'=>$amount_paid,'balance'=>$balance,'status'=>'active'];

         

            if($balance<1){

            $message = "Congratulations, You have completed Payment for ".$booking->package_name.".";

            SendSMSController::sendMessage($recipients,$message,$type="payment_completion_notification");

            $user = \DB::connection('mysql2')->table('users')->where('id',$customer->user_id)->first();

            $message = $user->name." has completed Payment for ".$booking->package_name.".";

            SendSMSController::sendMessage($recipients = $agent->phone,$message,$type="travel_payment_completion_notification");

            $data['status'] = 'complete';

            }

            DB::connection('mysql2')->table('bookings')->where('booking_reference','=',$bill_ref_no)->update($data);
 $customer=\App\Customers::whereId($booking->customer_id)->first();
 $user=\App\User::whereId($customer->user_id);
$obj=$user->first();
if ($type=='topup') {
    # code...


if($obj!=null){
    $mosmosbalance=$obj->balance;
$mosmosbalance=$mosmosbalance-$transaction_amount;
$user->update(["balance"=>$mosmosbalance]);

        for($i=0;$i<1000000;$i++){
            $transid = 'TB'.rand(10000,99999)."M";
            $res=\App\topups::whereTransid($transid)->first();
            if ($res==null) {             # code...
break;  }
          
        }


           $message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$transid}. Balance KES. {$f_balance}. Download our app to easily track your payments - http://bit.ly/MosMosApp.";

            SendSMSController::sendMessage($recipients,$message,$type="payment_notification");

$credentials=Array("amount"=>$transaction_amount,"balance"=>$mosmosbalance,"transid"=>$transid,"sender"=>$obj->id,"type"=>$bill_ref_no);
\App\topups::create($credentials);

  $obj = new pushNotification();
    //$data=Array("name"=>"home","value"=>"home");
    // $obj->exceuteSendNotification($user->first()->token,"Buy Airtime and pay utility bills at KSh.0 transaction cost.","Booking payment successful!",$data);
    $data=Array("name"=>"payment","value"=>"Payments");
 $obj->exceuteSendNotification(\App\User::whereId($customer->user_id)->first()->token,"Your payment of KSh.".$transaction_amount ." for Order Ref ".$bill_ref_no." has been received.","Payment Received",$data);


 return Array('response'=>"success","success"=>true);

       }

}
else{

      $message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$transid}. Balance KES. {$f_balance}. Download our app to easily track your payments - http://bit.ly/MosMosApp.";

            SendSMSController::sendMessage($recipients,$message,$type="payment_notification");

  $obj = new pushNotification();
  
    $data=Array("name"=>"payment","value"=>"Payments");
 $obj->exceuteSendNotification(\App\User::whereId($customer->user_id)->first()->token,"Your payment of KSh.".$transaction_amount ." for Order Ref ".$bill_ref_no." has been received.","Payment Received",$data);


 return Array('response'=>"success","success"=>true);

    }
}
}

function cardsuccess(Request $request, $details){

      $booking = \App\Bookings::with('product','payments','payments.mpesapayment','customer','customer.user','county','location')->where('booking_reference','=',$details)->first();
        $details  = [
            'customer_name'=>$booking->customer->user->name,
            'product_name'=>$booking->product->product_name,
            'booking_reference'=>$booking->booking_reference,
            'total_cost'=>number_format($booking->total_cost,2),
            'amount_paid'=>number_format($booking->amount_paid),
            'balance'=>$booking->balance,
            "url" => env('baseurl').encrypt($booking->booking_reference, "mosmos#$#@!89&^")."/invoice"
            
        ];
    return view('front.cardconfirmation',["details"=>$details]);
}





}
