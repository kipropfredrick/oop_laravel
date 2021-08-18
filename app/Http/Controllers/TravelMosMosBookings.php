<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Customers;
class TravelMosMosBookings extends Controller
{
    //

public function checktestconnection(){


$bookings = \DB::connection('mysql2')->table('bookings')->get();

return $bookings;
}

function bookings(Request $request){
	$connection=\DB::connection('mysql2');
$customerId=1;
$hasbooking=false;
$totalactive=0;
$totalpaid=0;
$balance=0;
$dailyTarget=0;
$daytogo="0";
$progress="0";
$hastarget=0;


$bookings = $connection->table('bookings')->whereCustomer_id($customerId)->first();
if ($bookings!=null) {
	# code...
	$hasbooking=true;
	$totalactive=$bookings->total_cost;
	$totalpaid=$bookings->amount_paid;
	$balance=$bookings->balance;
    $hastarget=1;



}
$array=Array("hasbooking"=>$hasbooking,"totalactive"=>$totalactive,"totalpaid"=>$totalpaid,"balance"=>$balance,"dailyTarget"=>$dailyTarget,"daytogo"=>$daytogo,"progress"=>$progress,"hastarget"=>$hastarget,"booking_reference"=>$bookings->booking_reference);



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


return $this->stk_push($amount,$msisdn,$booking_ref);

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
        $booking =$connection->table('bookings')->where('customer_id','=',2)->whereIn('status', ['active','pending'])->first();

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
        $bookings = $connection->table('bookings')->where('customer_id','=',2)->where('status','=',$status)->latest()->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking->progress = $progress;
         
        }

       return $bookings;
    }


public function stk_push($amount,$msisdn,$booking_ref){

        $CONSUMER_KEY1 =  env('CONSUMER_KEY1');
        $consume_secret = env('CONSUMER_SECRET1');
        $headers = ['Content-Type:application/json','Charset=utf8'];
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_USERPWD,$CONSUMER_KEY1.':'.$consume_secret);

        $curl_response = curl_exec($curl);
        $result = json_decode($curl_response);

        $token = $result->access_token;

        $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        Log::info("Generated access token " . $token);

        $timestamp = date("YmdHis");

        $BusinessShortCode = env('MPESA_SHORT_CODE1');

        $passkey = env('STK_PASSKEY');

        $lipa_time = Carbon::rawParse('now')->format('YmdHms');

        $apiPassword = $this->lipaNaMpesaPassword($lipa_time);

        Log::info("Generated Password " . $apiPassword);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token)); //setting custom header

        $curl_post_data = array(

            'BusinessShortCode' => env('MPESA_SHORT_CODE1'),
            'Password'          => $apiPassword,
            'Timestamp'         => $lipa_time,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $msisdn,
            'PartyB'            =>env('MPESA_SHORT_CODE1'),
            'PhoneNumber'       => $msisdn,
            'CallBackURL'       => 'https://travelmosmos.co.ke/stk-callback',
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
            $message = "Automatic payment failed. Go to your MPESA, Select Paybill Enter : env('MPESA_SHORT_CODE1') and Account Number : ".$booking_ref."Enter Amount : ".number_format($amount,2)." Thank you.";

            return Array("response"=>$message,"success"=>false,"error"=>false);
        }else{
            $message = "A payment prompt has been sent to your phone.Enter MPesa PIN if prompted.";
           return Array("response"=>$message,"success"=>true,"error"=>false);
        }



        return $message;
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

           /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lipaNaMpesaPassword($lipa_time)
    {

        $passkey = env('STK_PASSKEY1');
        $BusinessShortCode = env('MPESA_SHORT_CODE1');
        $timestamp =$lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode.$passkey.$timestamp);
        return $lipa_na_mpesa_password;
    }

}
