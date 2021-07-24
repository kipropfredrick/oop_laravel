<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use App\Customers;
use App\topups;
use AfricasTalking\SDK\AfricasTalking;
use App\Http\Controllers\autApi;
use App\Http\Controllers\pushNotification;
use Carbon\Carbon;
class TopupsController extends Controller
{
    //

public function balances(Request $request){
	$phone=$request->phone;
  $customers=Customers::wherePhone($phone)->first();

if($customers==null){
return Array("data"=>Array("response"=>"Account association failed. Contact support."),"error"=>true);
}

$user_id=$customers->user_id;

$transactions=topups::whereSender($user_id)->orderBy('created_at','desc')->get();

foreach ($transactions as $key => $value) {
	# code...
	$value->date=$value->created_at->toDateTimeString();
	$value->amount=intval($value->amount);
}
$balance=intval(DB::table("users")->whereId($customers->user_id)->first()->balance);


return Array("error"=>false,"data"=>Array("response"=>"Account Updated.","balance"=>$balance,"transactions"=>$transactions));

}


function maketopups(Request $request){

           $phone=$request->phone;
   $customers=Customers::wherePhone($phone)->first();


  if ($customers==null) {
  	return Array("data"=>Array("response"=>"We are unbale to process your request.contact support"),"error"=>true);
  	# code...
  }
$sender=$customers->user_id;
$balance=\App\User::whereId($sender)->first();

if ($balance->mosmosid==null) {
	  	return Array("data"=>Array("response"=>"Your account was not linked properly. Contact support."),"error"=>true);
	# code...
}
// $balance=intval($balance->balance)+$request->amount;
// \App\User::whereId($sender)->update(["balance"=>$balance]);

// $credentials=Array("amount"=>$request->amount,"balance"=>$balance,"transid"=>"xxxxxxxx","sender"=>$sender);
// \App\topups::create($credentials);

$obj=new autApi();
$result=$obj->stk_push($request->amount,$phone,$balance->mosmosid);
if($result['success']){
return Array("data"=>Array("response"=>"Payment request sent. Enter your M-pesa PIN to complete the transaction."),"error"=>false);	
}
else{
return Array("data"=>Array("response"=>"An error occurred processing your request, try again later."),"error"=>true);	
}

}

public function redeem(Request $request){

  $type=$request->type;
$amount=intval($request->input("amount"));
 $phone=$request->phone;

$airtime=false;
$mobileInput=$request->input('sendmobile');
    $pattern = "/^(0)\d{9}$/";
$pattern1 = "/^(254)\d{9}$/";
$pattern2 = "/^(\+254)\d{9}$/";
if (preg_match($pattern, $mobileInput)) {
  # code...
    $airtime=true;
  $mobilerec="+254".substr($mobileInput,1);
}
else if(preg_match($pattern2, $mobileInput)){
    $airtime=true;
$mobilerec=$mobileInput;
}
else if(preg_match($pattern1, $mobileInput)){
    $airtime=true;
$mobilerec="+".$mobileInput;
}


if ($amount<5) {
  # code...
  return Array("data"=>Array("response"=>"Minimum top-up is KSh.5"),"error"=>true);
}


  if ($type=="mpesa") {
    # code...
    $obj=new autApi();

$result=$obj->stk_push($request->amount,$phone,substr($mobilerec, 1));
if($result['success']){
return Array("data"=>Array("response"=>"Payment request sent. Enter your M-pesa PIN to complete the transaction."),"error"=>false); 
}
else{
return Array("data"=>Array("response"=>"An error occurred processing your request, try again later."),"error"=>true); 
}
return Array("data"=>Array("response"=>"M-pesa top-up was successful. Thank you."),"error"=>false);

  }else {

  $customers=Customers::wherePhone($phone)->first();
  if ($customers==null) {
  	return Array("data"=>Array("response"=>"Can not make redemption. Contact support."),"error"=>true);
  	# code...
  }
$main=DB::table('users')->whereId($customers->user_id);
$balance=$main->first()->balance;
$sender=$customers->user_id;
$sendamount=intval($request->input("amount"));
$amount=intval($request->input("amount"));
if ($amount>intval($balance)) {
    # code...
	return Array("data"=>Array("response"=>"You have insufficient balance in your Lipa Mos Mos wallet."),"error"=>true);
    
}
//return $result;






if (!$airtime) {
    # code...
    return Array("data"=>Array("response"=>"The phone number format is not supported. Please try again."),"error"=>true);
   
}

  $username = env('AFRIUSERNAME'); // use 'sandbox' for development in the test environment
$apiKey   =env('AFRIAPIKEY');

$AT       = new AfricasTalking($username, $apiKey);

$airtime = $AT->airtime();
$array=Array("recipients"=>[Array('phoneNumber' => $mobilerec,
'currencyCode' => "KES",
'amount' => $sendamount)]);

$result   = $airtime->send($array);
\Log::info(json_encode($result));
// return back()->with("error","An Error Occured, check details and Try Again");

if ($result['data']->errorMessage=="None") {
    $main->update(["balance"=>intval($balance)-$amount]);

$balance=\App\User::whereId($sender)->first();
$balance=intval($balance->balance);


        for($i=0;$i<1000000;$i++){
            $transid = 'TA'.rand(10000,99999)."M";
            $res=\App\topups::whereTransid($transid)->first();
            if ($res==null) {             # code...
break;  }
          
        }

 $credentials=Array("amount"=>$request->amount,"balance"=>$balance,"transid"=>$transid,"sender"=>$sender,"type"=>"airtime");
\App\topups::create($credentials);
  $obj = new pushNotification();
    $data=Array("name"=>"home","value"=>"home");
    $obj->exceuteSendNotification(\App\User::whereId($sender)->first()->token,"Thank you for topping up KSh. ".$sendamount." airtime with us.","Transaction successful. ",$data);

  return Array("data"=>Array("response"=>"Airtime top-up successs"),"error"=>false);

}
else{
   return Array("data"=>Array("response"=>$result['data']),"error"=>true);
    
}


}
}

function refreshpayment(Request $request){


$phone=$request->phone;
$customer=\App\Customers::wherePhone($phone)->first();

if ($customer==null) {

   return Array("data"=>Array("response"=>"An error occured processsing your payment"),"error"=>true);
  # code...
}
else{

$booking=\App\Bookings::whereCustomer_id($customer->id)->whereStatus('active')->first();
if ($booking==null) {
  # code...
   return Array("data"=>Array("response"=>"An error occured processsing your payment"),"error"=>true);
}
else{

  $payments=\App\Payments::whereBooking_id($booking->id)->latest()->first();

if ($payments==null) {
  # code...
   return Array("data"=>Array("response"=>"An error occured processsing your payment"),"error"=>true);
}

else{
    $start  = new Carbon($payments->created_at);
$end    = new Carbon(Now());

$hrs=intval($start->diff($end)->format('%H')) * 60;
$minutes=intval($start->diff($end)->format('%I'));
$total=$hrs+$minutes;


if ($total<3) {
  # code...
    return Array("data"=>Array("response"=>"PYour payment has been received."),"error"=>false);
}
else{
  return Array("data"=>Array("response"=>"No payment received. Please try again"),"error"=>true);
}

}

}

}


}

}
