<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use File;
use DB;
use Carbon\Carbon;
use Hash;
use Exception;
use Illuminate\Support\Facades\Log;
use AfricasTalking\SDK\AfricasTalking;
use App\Http\Controllers\SendSMSController;
use Illuminate\Support\Facades\Mail;
use \App\Mail\SendRegistrationEmail;

class ApiBookingController extends Controller
{

    function index(Request $request){
Log::info(json_encode($request));
    	 $decrypted = decrypt($request->secret_key, "mosmos#$#@!89&^");
$request['vendor_code']=$decrypted;
    	$vendor_code=Vendor::whereVendor_code($request->vendor_code)->first();

    	if ($vendor_code==null) {
    		# code...
    		return Array("status"=>true,"data"=>$request->all(),"message"=>"An error occured processing your request. contact support for help making a booking.");

    	}

    	return $this->make_booking($request,'api');
    	return Array("status"=>false,"data"=>$request->all(),"message"=>json_encode($request->all()));
    }



        function vendorbooking(Request $request){

    	$vendor_code=Vendor::whereVendor_code($request->vendor_code)->first();

    	if ($vendor_code==null) {
    		# code...

    		return Back()->with("error","An error occured processing your request. contact support for help making a booking.");

    	}

    	$data= $this->make_booking($request,'direct');
    	if ($data['status']) {
    		# code...
    		return back()->with("success",$data['message']);
    	}
    	else{
    			return back()->with("error",$data['message']);
    	}
    	
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



        public function get_msisdn_network($msisdn){
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




    public function get_booking_reference(){


for($i=0;$i<1000000;$i++){
$booking_reference = 'MM'.rand(10000,99999);
$res=\App\Bookings::whereBooking_reference($booking_reference)->first();
if ($res==null) {
# code...
break;
}

}

        return $booking_reference;

    }


    function addProduct($request){

    	 $vendor = Vendor::whereVendor_code($request->vendor_code)->first();

        //$data = $request->except('_token','image_path');
$data=Array();
        $weight = $request->weight."kg";

        $data['weight'] = $weight;
        $data['subcategory_id']=0;
        $data['category_id']=0;
  $data['brand_id']=0;
    $data['vendor_id']=$vendor->id;
    $data['product_name']=$request->product_name;
    $data['product_price']=$request->productPrice;
    $data['quantity']=1;

    $data['highlights']="";
    $data['description']="";
        $time = now();

        $time = str_replace(":", "-", $time);

        $time = str_replace(" ", "-", $time);

            $image = 'noimage.jpg';
        

        $rand = rand(100,999);
   
        $slug = $rand."-".$request->product_name;
        
        $slug =  str_replace(' ', '-', $slug);

        $slug =  str_replace('/','-',$slug);

         $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

        $data['product_code'] = 'PM'.rand(10,1000000);
        $data['product_image'] = $image;
        $data['slug'] = $slug;
        $data['vendor_id'] = $vendor->id;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('products')->insert($data);

        $product_id = DB::getPdo()->lastInsertId();
        return $product_id;
    }

    public function make_booking(Request $request,$source){


 $product_id=$this->addProduct($request);

        $county_id = $request->county_id;
        $exact_location = $request->exact_location;
        $vendor_code = $request->vendor_code;

        $categories = \App\Categories::all();
        
        list($msisdn, $network) = $this->get_msisdn_network($request->phone);

        if (!$msisdn){

             	return Array("status"=>false,"data"=>"","message"=>"The phone number format is not supported please check and try again.");
        }else{

             	
            $valid_phone = $msisdn;
        }
        //Valid email

        $valid_email = preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/", $request->email, $e_matches);
        
        $product = \App\Products::find($product_id);

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
         	return Array("status"=>false,"data"=>"","message"=>"You already have an existing booking with Lipa Mos Mos.");
        }

       // \Auth::login($user);

        $booking_reference = $this->get_booking_reference();

        $booking_date = now();

        $due_date = Carbon::now()->addMonths(3);

        
        $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$product_id)->first();

        
        if($request->initial_deposit<100){

          //return redirect()->back()->with('error',);

          return Array("status"=>false,"data"=>"","message"=>"The Minimum deposit for this product is : KES ".number_format(100,0));
         
        }

        $balance=0;
        //$existingUser->balance;

        $booking = new \App\Bookings();
        $recipients = $valid_phone;
        if (intval($balance)==0) {
        $booking->balance =   $total_cost; 
        $booking->amount_paid = "0";
        $booking->status = "pending";
    $booking->platform=$source;
       
       
        }
        else{

            if (intval($total_cost)<intval($balance)) {
                # code...
                \App\User::where('email',  $request->input('email'))->update(["balance"=>intval($balance)-intval($total_cost)]);
                $booking->status = "complete";
                $booking->amount_paid = $total_cost;
                $booking->balance="0";
 $booking->platform="api";
                $message =  "Ksh ".$balance." from your mosmos wallet has been used fully pay your placed order";
            }
            else{

                \App\User::where('email',  $request->input('email'))->update(["balance"=>0]);
                $booking->balance =   $total_cost-(intval($balance)); 
                $booking->amount_paid = $balance;
                $booking->status = "active";
                $message =  "Ksh ".$balance." from your mosmos wallet has been used to pay for ordered item partially remaining amount is Ksh.".number_format($total_cost-(intval($balance)));
               $booking->platform=$source;
            }
                
            SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");
        }

        
        $booking->customer_id = $existingCustomer->id; 
        $booking->product_id  = $product_id;
        $booking->booking_reference = $booking_reference;
        $booking->quantity  = '1';
       
        $booking->item_cost = $product->product_price;
        
        $booking->payment_mode  = 'Mpesa';
        $booking->date_started  = now();
        $booking->due_date = $due_date;
       $booking->platform=$source;
       
        $booking->vendor_code = $vendor_code;
        $booking->location_type = "Exact Location";
        $booking->item_cost = $product->product_price;
        $booking->shipping_cost = $shipping_cost;
        $booking->county_id = $request->county_id;
        $booking->exact_location = $request->exact_location;
        $booking->total_cost =  $total_cost;
       // $booking->booking_reference = $this->get_booking_reference();

        $booking->save();
        
        
        $booking_id = DB::getPdo()->lastInsertId();

        $recipients = $valid_phone;
      
        $booking_id = DB::getPdo()->lastInsertId();

        $product = \App\Products::find($product_id);

        $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking_reference." and amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;
        
        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";

         	return Array("status"=>true,"data"=>'',"message"=>$stkMessage,"booking_reference"=>$booking_reference);
            
        }

        
        $existingCustomer = \App\Customers::where('phone','=',$valid_phone)->first();

        if($existingCustomer)
        {
            
        $booking_date = now();

        $$booking_date = strtotime($booking_date);

        $product = \App\Products::find($product_id);

       $due_date = Carbon::now()->addMonths(3);

        if($request->initial_deposit<100){

     
          return Array("status"=>false,"data"=>"","message"=>"The Minimum deposit for this product is : KES ".number_format(100,0));
         
         
        }

        $booking = new \App\Bookings();
        $booking->customer_id = $existingCustomer->id; 
        $booking->product_id  = $product_id;
        $booking->county_id = $request->county_id;
        $booking->exact_location = $exact_location;
        $booking->booking_reference = $booking_reference;
        $booking->quantity  = "1";
        $booking->amount_paid = "0";
        $booking->balance = $total_cost;
        $booking->item_cost = $product->product_price;
        $booking->shipping_cost = $shipping_cost;
        $booking->payment_mode  = 'Mpesa';
        $booking->vendor_code = $vendor_code;
        $booking->date_started  = now();
        $booking->due_date = $due_date;
        $booking->status = "pending";
        $booking->total_cost = $total_cost;
      $booking->platform=$source;
        $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

        $recipients = $valid_phone;
       
        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $product = \App\Products::find($product_id);

        $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking_reference." And amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";


            
        }

        $user = new \App\User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('phone'));
        $user->save();

        $user_id = DB::getPdo()->lastInsertId();

        $customer = new \App\Customers();
        $customer->user_id = $user_id; 
        $customer->phone  = $valid_phone;
        $customer->save();

        $customer_id = DB::getPdo()->lastInsertId();

        $booking_date = now();

        $booking_date = strtotime($booking_date);

        $product = \App\Products::find($product_id);

       $due_date = Carbon::now()->addMonths(3);

       $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$product_id)->first();


        $booking = new \App\Bookings();
        $booking->customer_id = $customer_id; 
        $booking->product_id  = $product_id;
        $booking->county_id = $request->county_id;
        $booking->exact_location = $exact_location;
        $booking->booking_reference =$booking_reference;
        $booking->quantity  = "1";
        $booking->status = "pending";
        $booking->vendor_code = $vendor_code;
        $booking->item_cost = $product->product_price;
        $booking->balance = $total_cost;
        $booking->shipping_cost = $shipping_cost;
        $booking->amount_paid = "0";
        $booking->payment_mode  = 'Mpesa';
        $booking->date_started  = now();
        $booking->due_date = $due_date;
        $booking->total_cost = $total_cost;
      $booking->platform=$source;
        $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

       $recipients = $valid_phone;

       $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking->booking_reference ." And amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

       SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

       $details = [
        'email' => $request->email,
        'name'=>$request->name,
        'booking_reference'=>$booking_reference,
        'initial_deposit'=>number_format($request->initial_deposit),
        'password'=>$request->input('phone')
        ];

        Mail::to($request->email)->send(new SendRegistrationEmail($details));

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $product = \App\Products::find($product_id);

        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";
	return Array("status"=>true,"data"=>'',"message"=>$stkMessage,"booking_reference"=>$booking_reference);

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
            'CallBackURL'       => 'https://mosmos.co.ke/stk-callback',
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
        }else{
            $message = "A payment prompt has been sent to your phone.Enter MPesa PIN if prompted.";
        }

        return $message;
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
