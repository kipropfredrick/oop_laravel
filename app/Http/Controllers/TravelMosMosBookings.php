<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Customers;
use App\Http\Controllers\autApi;
class TravelMosMosBookings extends Controller
{
    //

public function checktestconnection(){


$bookings = \DB::connection('mysql2')->table('bookings')->get();

return $bookings;
}

function bookings(Request $request){
	$connection=\DB::connection('mysql2');
    $username=$request->input("username");
$customer=Customers::wherePhone($username)->first();
$customerId=$customer->id;
$hasbooking=false;
$totalactive=0;
$totalpaid=0;
$balance=0;
$dailyTarget=0;
$daytogo="0";
$progress="0";
$hastarget=0;
$progresspercentage=0;
$booking_reference="";
$progressmessage="Track";
$dailytarget=0;
$daystogo=0;
$setdate="";
$setreminder=0;

$targettype="Daily";
$bookings = $connection->table('bookings')->whereCustomer_id($customerId)->whereIn('status',['active','pending'])->first();
if ($bookings!=null) {
    # code...
   $hasbooking=true; 
   $booking_reference=$bookings->booking_reference;

}

$bookings = $connection->table('bookings')->whereCustomer_id($customerId)->whereStatus('active')->first();
if ($bookings!=null) {
	# code...
	$hasbooking=true;
	$totalactive=$bookings->total_cost;
	$totalpaid=$bookings->amount_paid;
	$balance=$bookings->balance;

    $booking_reference=$bookings->booking_reference;

  $completionDate = $connection->table('bookings')->where('status','=','active')->where('customer_id',$customerId)->first()->setdate;
         $createdat = $connection->table('bookings')->where('status','=','active')->where('customer_id',$customerId)->first()->created_at;
    $setreminder=intval($connection->table('bookings')->where('status','=','active')->where('customer_id',$customerId)->first()->setreminder);
  $setdate=$connection->table('bookings')->where('status','=','active')->where('customer_id',$customerId)->first()->setdate;

$bookingbalances=intval($bookings->balance);
$totalBookingAmounts=$bookings->total_cost;
$progresspercentage=intval(($amountPaids/$totalBookingAmounts)*100);


$date = Carbon::parse($completionDate);
$now = Carbon::now();

$daystogo =( $date->diffInDays($now))." Days";

$cdate = Carbon::parse($completionDate);
$createddate = Carbon::parse($createdat);
$hastarget=$setreminder;
$days=intval(($cdate->diffInDays($createddate)));

if($days>0){
    if ($setreminder==1) {
        # code...
            $dailytarget=intval($totalBookingAmounts/$days);
            $targettype="Daily target";
    }
    else if ($setreminder==2) {
        # code...
            $dailytarget=intval($totalBookingAmounts/$days) * 7;
             $targettype="Weekly target";
    }
    else if ($setreminder==3) {
        # code...
          $dailytarget=intval($totalBookingAmounts/$days) * 30;
           $targettype="Monthly target";
    }



}
$dayspassed=intval(($createddate->diffInDays($now)));
$amountsbepaid=intval($dayspassed*$dailytarget);
$paymentbalance=$amountsbepaid-$amountPaids;
if ($paymentbalance<0) {
  # code...
  $progressmessage="On Track";

}
else{
  if($dailytarget>0){
     $daysdue=intval($paymentbalance/$dailytarget);
  }
  else{
    $daysdue=0;
  }
   $progressmessage=$daysdue."-Days behind Ksh. ".number_format($paymentbalance);
}



}




$array=Array("hasbooking"=>$hasbooking,"totalactive"=>$totalactive,"totalpaid"=>$totalpaid,"balance"=>$balance,"dailyTarget"=>$dailyTarget,"daytogo"=>$daytogo,"progress"=>$progress,"booking_reference"=>$booking_reference,"progressmessage"=>$progressmessage,"dailytarget"=>$dailytarget,"daystogo"=>$daystogo,"progresspercentage"=>$progresspercentage,"hastarget"=>$hastarget,"setdate"=>$setdate,"setreminder"=>$setreminder,"bookingreference"=>$booking_reference,"targettype"=>$targettype);



return $array;


}

function makePayment(Request $request){

	$amount=$request->amount;

	$phone=$request->phone;
	$booking_ref=$request->input("bookingref");

  list($msisdn, $network) = $this->get_msisdn_network($phone);

        if (!$msisdn){

              return Array("data"=>Array("response"=>"Invalid Phone Number"),"error"=>true);
        }else{
            $mobilerec = "0".substr($msisdn, 3);
            
            $valid_phone=$msisdn;
         
        }




        
$connection=\DB::connection('mysql2');
//$customer->id
        $booking =$connection->table('bookings')->whereBooking_reference($booking_ref)->first();
if ($booking==null) {

return Array("response"=>"Booking data not found.","error"=>true);  
}

$travel_agent=$connection->table('travel_agents')->whereId($booking->agent_id)->first();
if ($travel_agent==null) {
  # code...
  return Array("response"=>"An error occured processing your request.","error"=>true);   
}

if ($travel_agent->mpesa_approved=="approved") {
  # code...
  $consumer_key=$travel_agent->CONSUMER_KEY;
  $consume_secret=$travel_agent->CONSUMER_SECRET;
  $MPESA_SHORT_CODE=$travel_agent->MPESA_SHORT_CODE;
  $STK_PASSKEY=$travel_agent->STK_PASSKEY;


  return $this->stk_push($amount,$msisdn,$booking_ref,$consumer_key,$consume_secret,$MPESA_SHORT_CODE,$STK_PASSKEY);
}
else{
$authobj=new autApi();

return $authobj->stk_push($amount,$msisdn,$booking_ref);

}



}
 function travelcheckBooking(request $request){
        $username=$request->input("username");
$customer=Customers::wherePhone($username)->first();
        $customer_id = $customer->id;
$phone=$customer->phone;

   if($customer!=null)
        {

        
$connection=\DB::connection('mysql2');
//$customer->id
        $booking =$connection->table('bookings')->where('customer_id','=',$customer_id)->whereIn('status', ['active','pending'])->first();

        if ($booking!=null) {
          # code...
          $hasbooking=true;
          return Array("response"=>$booking,"error"=>false);
        }
        else{
            return Array("response"=>"No Active Or Pending Orders in the list","error"=>true);  
        }
      }

      else{

        return Array("response"=>"Account Data Not Found ","error"=>true);
      }
}


    public function customertravelbookings(Request $request){
        $username=$request->input("username");
        $status=$request->input("status");
            $connection=\DB::connection('mysql2');

        $customer = Customers::wherePhone($username)->first();

        //$customer->id
        $bookings = $connection->table('bookings')->where('customer_id','=',$customer->id)->where('status','=',$status)->latest()->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking->progress = $progress;
         
        }

       return $bookings;
    }

    function travelpayments(Request $request){
         $connection=\DB::connection('mysql2');
        $customer_id=DB::table("customers")->wherePhone($request->input('username'))->first()->id;
        $bookings=$connection->table('bookings')->whereCustomer_id($customer_id)->pluck('booking_reference')->toArray();

        $payments = $connection->table('payment_logs')->whereIn("BillRefNumber",$bookings)->orderBy('id', 'DESC')->get();
        $allPayments=[];


  
for ($i=0; $i < count($payments); $i++) { 
    # code...
    $array=Array("product_name"=>'package 1',"payment_ref"=>$payments[$i]->TransID ,"booking_reference"=>$payments[$i]->BillRefNumber,"transaction_amount"=>intval($payments[$i]->TransAmount),"date"=>$payments[$i]->created_at);
    array_push($allPayments, $array);

}
         
return $allPayments;
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



    function updateTraveTarget(Request $request){

$bookingreference=$request->bookingreference;
$setdate=$request->setdate;
$setreminder=$request->setreminder;
$connection=\DB::connection('mysql2');
$obj=$connection->table('bookings')->whereBooking_reference($bookingreference);
$booking=$obj->first();
if ($booking!=null) {
  # code...
$array=Array("setdate"=>$setdate,"setreminder"=>$setreminder);
$obj->update($array);
  return Array("data"=>Array("response"=>"Your payment target has been updated successfully."),"error"=>false);
}
else{
  return Array("data"=>Array("response"=>"No booking reference found."),"error"=>true);
}

    }



    public function stk_push($amount,$msisdn,$booking_ref,$consumer_key,$consume_secret,$MPESA_SHORT_CODE,$STK_PASSKEY){

        // $consumer_key =  env('CONSUMER_KEY');
        // $consume_secret = env('CONSUMER_SECRET');
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

        $BusinessShortCode = $MPESA_SHORT_CODE;

        $passkey = $STK_PASSKEY;

        $lipa_time = Carbon::rawParse('now')->format('YmdHms');

        $apiPassword = $this->lipaNaMpesaPassword($STK_PASSKEY,$MPESA_SHORT_CODE,$lipa_time);

        Log::info("Generated Password " . $apiPassword);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token)); //setting custom header

        $curl_post_data = array(

            'BusinessShortCode' =>$MPESA_SHORT_CODE,
            'Password'          => $apiPassword,
            'Timestamp'         => $lipa_time,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $msisdn,
            'PartyB'            =>$MPESA_SHORT_CODE,
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

      /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lipaNaMpesaPassword($passkey,$BusinessShortCode,$lipa_time)
    {

        // $passkey = env('STK_PASSKEY');
        // $BusinessShortCode = env('MPESA_SHORT_CODE');
        $timestamp =$lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode.$passkey.$timestamp);
        return $lipa_na_mpesa_password;
    }

}
