<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiBookingController extends Controller
{
    //

    function index(Request $request){
    	return Array("status"=>true,"data"=>$request->all(),"message"=>"You already have an existing booking test response.");
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
}
