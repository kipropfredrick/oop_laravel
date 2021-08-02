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
  	return Array("data"=>Array("response"=>"An error occured try again."),"error"=>true);
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

//   $username = env('AFRIUSERNAME'); // use 'sandbox' for development in the test environment
// $apiKey   =env('AFRIAPIKEY');

// $AT       = new AfricasTalking($username, $apiKey);

// $airtime = $AT->airtime();
// $array=Array("recipients"=>[Array('phoneNumber' => $mobilerec,
// 'currencyCode' => "KES",
// 'amount' => $sendamount)]);
   
$response= json_decode($this->phonelookup(substr($mobilerec,4, 3)));

if (isset($response->data)) {
  # code...
  $operator= $response->data->operator;
}
else{

  return Array("data"=>Array("response"=>"Mobile Operator Not Supported".$mobilerec),"error"=>true);

}
//   $response= json_decode($this->createTransaction("0".substr($mobilerec, 4),10,$operator,$phone));

// if (isset($response->data)) {
//   # code...
//   $operator= $response->data->operator;
// }
// else{

//   return Array("data"=>Array("response"=>"Mobile Operator Not Supported"),"error"=>true);

// }
  $response= json_decode($this->createTransaction(substr($mobilerec, 1),$amount,$operator,$phone));
// $result   = $airtime->send($array);
\Log::info(json_encode($response));
// return back()->with("error","An Error Occured, check details and Try Again");




if (!isset($response->error)) {
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
   //return Array("data"=>Array("response"=>$result['data']),"error"=>true);
   return Array("data"=>Array("response"=>is_array($response->error)?$response->error[0]->text:$response->error->error),"error"=>true);
    
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


function BillsPayment(Request $request){

$type=$request->paymenttype;
$amount=intval($request->input("amount"));;
//
$phone=$request->phone;
$biller_name=$request->biller_name;
// $payfor=$request->payfor;
$account=$request->accountno;


if ($amount<5) {
  # code...
  return Array("data"=>Array("response"=>"Minimum top-up is KSh.5"),"error"=>true);
}

// if ($payfor=="airtime") {
//   # code...
//   $ismobileformatted=false;
// $account=$request->input('sendmobile');
//     $pattern = "/^(0)\d{9}$/";
// $pattern1 = "/^(254)\d{9}$/";
// $pattern2 = "/^(\+254)\d{9}$/";
// if (preg_match($pattern, $account)) {
//   # code...
//     $ismobileformatted=true;
//   $accountno=$account;
// }
// else if(preg_match($pattern2, $account)){
//     $ismobileformatted=true;
// $accountno="0".substr($account, 4);
// }
// else if(preg_match($pattern1, $account)){
//     $ismobileformatted=true;
// $accountno="0".substr($account, 3);
// }
// else{
//     return Array("data"=>Array("response"=>"Phone number not supported"),"error"=>true);
// }


// }


//check if user exist
   $customers=Customers::wherePhone($phone)->first();
  if ($customers==null) {
    return Array("data"=>Array("response"=>"Error purchasing airtime contact our support team"),"error"=>true);
    # code...
  }
  // 





  if ($type=="mpesa") {
    # code...
    $obj=new autApi();
if ($biller_name=="kplc_prepaid") {
  # code...
  $accountno="PP".$account;
}
else if ($biller_name=="kplc_postpaid") {
  # code...
    $accountno="PS".$account;
}
else if ($biller_name=="zuku") {
  # code...
    $accountno="ZU".$account;
}
else if ($biller_name=="startimes") {
  # code...
    $accountno="ST".$account;
}
else if ($biller_name=="gotv") {
  # code...
    $accountno="GO".$account;
}
else if ($biller_name=="dstv") {
  # code...
      $accountno="DS".$account;
}
else if ($biller_name=="nairobi_water") {
  # code...
        $accountno="NW".$account;
}
else{
  return Array("data"=>Array("response"=>"Biller Not Supported ."),"error"=>true); 
}

$result=$obj->stk_push($request->amount,$phone,$accountno);

    


if($result['success']){
return Array("data"=>Array("response"=>"Payment request sent. Enter your M-pesa PIN to complete the transaction."),"error"=>false); 
}
else{
return Array("data"=>Array("response"=>"An error occurred processing your request, try again later."),"error"=>true); 
}
return Array("data"=>Array("response"=>"M-pesa top-up was successful. Thank you."),"error"=>false);

  }

  $main=DB::table('users')->whereId($customers->user_id);
$bal=$main->first()->balance;
if ($bal<$amount) {
  # code...
   return Array("data"=>Array("response"=>"Your account balance is issuficient"),"error"=>true);
}
  
   $response= json_decode($this->createTransaction($account,$amount,$biller_name,$phone)); 



//return response()->json($response);
if (isset($response->error)) {
  # code...
  //top up customer account and send an sms and push notification
    return Array("data"=>Array("response"=>is_array($response->error)?$response->error[0]->text:$response->error->error),"error"=>true);
}
else{
  //update the account
$balance=$main->first()->balance;
$sender=$customers->user_id;
$sendamount=intval($request->input("amount"));
$amount=intval($request->input("amount"));
//


      $main->update(["balance"=>intval($balance)-$amount]);

$balance=\App\User::whereId($sender)->first();
$balance=intval($balance->balance);


        for($i=0;$i<1000000;$i++){
            $transid = 'TB'.rand(10000,99999)."M";
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

//return $this->phonelookup($prefix);
//return $this->getAccountBalance();


}

public function createTransaction($account,$amount,$biller_name,$phone){

$hashkey = env('IpayKey');
$IpayId=env('IpayId');
$datastring = "account=".$account."&amount=".$amount."&biller_name=".$biller_name."&phone=".$phone."&vid=".$IpayId ;
$hashid = hash_hmac("sha256", $datastring, $hashkey);
$url="https://apis.ipayafrica.com/ipay-billing/transaction/create";  

$fields=Array("vid"=>$IpayId,"hash"=>$hashid,"account"=>$account,"biller_name"=>$biller_name,"phone"=>$phone,"amount"=>$amount);



// $fields=Array("hash"=>$hashid,"vid"=>"nelmasoft");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    $result = curl_exec($ch);
return $result;
}

function validateAccount($account,$account_type){
$hashkey = env('IpayKey');
$IpayId=env('IpayId');
$datastring = "account=".$account."&account_type=".$account_type."&vid=".$IpayId ;
$hashid = hash_hmac("sha256", $datastring, $hashkey);
$url="https://apis.ipayafrica.com/ipay-billing/billing/validate/account";  

$fields=Array("vid"=>$IpayId,"hash"=>$hashid,"account"=>$account,"account_type"=>$account_type);



// $fields=Array("hash"=>$hashid,"vid"=>"nelmasoft");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    $result = curl_exec($ch);
return $result;

}

public function phonelookup($prefix){

  $hashkey = env('IpayKey');
$IpayId=env('IpayId');
$datastring = "prefix=".$prefix."&vid=".$IpayId ;
$hashid = hash_hmac("sha256", $datastring, $hashkey);
$url="https://apis.ipayafrica.com/ipay-billing/billing/phone/lookup";  

$fields=Array("vid"=>$IpayId,"hash"=>$hashid,"prefix"=>$prefix);



// $fields=Array("hash"=>$hashid,"vid"=>"nelmasoft");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    $result = curl_exec($ch);
return $result;

}

public function getAccountBalance(){

  $hashkey = env('IpayKey');
$IpayId=env('IpayId');
$datastring = "vid=".$IpayId ;
$hashid = hash_hmac("sha256", $datastring, $hashkey);
$url="https://apis.ipayafrica.com/ipay-billing/billing/account/balance?vid=".$IpayId."&hash=".$hashid;  

$fields=Array("vid"=>$IpayId);



// $fields=Array("hash"=>$hashid,"vid"=>"nelmasoft");
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    //curl_setopt($ch, CURLOPT_POST, 1);
    // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    $result = curl_exec($ch);
return $result;

}



}



