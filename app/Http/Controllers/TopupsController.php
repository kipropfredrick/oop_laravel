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
use App\Http\Controllers\paybills;
use App\Http\Controllers\AES;
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
 $telco=$request->telco;

$airtime=false;
$mobileInput=$request->input('sendmobile');
    $pattern = "/^(0)\d{9}$/";
$pattern1 = "/^(254)\d{9}$/";
$pattern2 = "/^(\+254)\d{9}$/";
if (preg_match($pattern, $mobileInput)) {
  # code...
    $airtime=true;
  $mobilerec=$mobileInput;
}
else if(preg_match($pattern2, $mobileInput)){
    $airtime=true;
$mobilerec="0".substr($mobileInput, 4);
}
else if(preg_match($pattern1, $mobileInput)){
    $airtime=true;
$mobilerec="0".substr($mobileInput, 3);
}


if ($amount<5) {
  # code...
  return Array("data"=>Array("response"=>"Minimum top-up is KSh.5"),"error"=>true);
}


  if ($type=="mpesa") {
    # code...
    $obj=new autApi();

    $initial="";
     if ($telco=="safaricom") {
    # code...
    $initial="SAF";

  }
  else if ($telco=="airtel") {
    $initial="AIR";

  }
  else if ($telco=="telcom") {
    # code...
    $initial="TEL";
  }



$result=$obj->stk_push($request->amount,$phone,$initial.$mobilerec);
if($result['success']){
return Array("data"=>Array("response"=>"Payment request sent. Enter your M-pesa PIN to complete the transaction."),"error"=>false); 
}
else{
return Array("data"=>Array("response"=>"An error occurred processing your request, try again later."),"error"=>true); 
}


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
$productcode="";
if ($telco!=null) {
  # code...
  if ($telco=="safaricom") {
    # code...
    $productcode="SF01";

  }
  else if ($telco=="airtel") {
    $productcode="AP01";

  }
  else if ($telco=="telcom") {
    # code...
    $productcode="OP01";
  }


}
else{



$response= json_decode($this->phonelookup(substr($mobilerec,1, 3)));

if (isset($response->data)) {
  # code...
  $operator= $response->data->operator;

    if ($operator=="safaricom") {
    # code...
    $productcode="SF01";

  }
  else if ($operator=="airtel") {
    $productcode="AP01";

  }
  else if ($operator=="telcom") {
    # code...
    $productcode="OP01";
  }
}
else{

  return Array("data"=>Array("response"=>"Mobile Operator Not Supported".$mobilerec),"error"=>true);

}

}


$paybillobj = new paybills();
$array=Array("PhoneNumber"=>$mobilerec,"Amount"=>$amount*100,"ProductCode"=>$productcode);

$res=$paybillobj->AirtimeTopUp($array);


 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
  return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
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
  # code...
}
else{
    return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
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

// $paybillobj = new paybills();
// $array=Array("PhoneNumber"=>"0790535349","CustomerName"=>"Brian Mutiso","MeterNumber"=>"37182395980","Amount"=>45000);

//return $this->getBalance();
//return $this->createTransaction("254790535349",300,"safaricom","254790535349");
//return $this->checkstatus("KPLNEL3157C1628145907130601667");
if ($amount<5) {
  # code...
  return Array("data"=>Array("response"=>"Minimum top-up is KSh.5"),"error"=>true);
}



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





if ($biller_name=="kplc_prepaid") {
  # code...
  $array=Array("PhoneNumber"=>"0".substr($phone, 3),"CustomerName"=>"customer","MeterNumber"=>$account,"Amount"=>$amount*100);
$res=$paybillobj->kplcprepaid($array);

 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
  return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
   $token=json_decode(json_decode($decdata->VoucherDetails,true)[0])->Token;
return Array("data"=>Array("response"=>"Transaction success: tokenno: ".$token),"error"=>false);
  # code...
}
else{
    return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}



}
else if ($biller_name=="kplc_postpaid") {
  # code...
  $array=Array("MobileNumber"=>"0".substr($phone, 3),"CustomerName"=>"customer","CustAccNum"=>$account,"Amount"=>$amount*100);
$res=$paybillobj->kplcpostpaid($array);

 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
  return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];

return Array("data"=>Array("response"=>"Post Paid success"),"error"=>false);
  # code...
}
else{
    return Array("data"=>Array("response"=>"An error occured processing your request.".$decdata->ResponseDescription),"error"=>true);
}



}

else  {
  # code...
  $payto="";
  if ($biller_name=="zuku") {
  # code...
    $payto="ZUKU";
}
else if ($biller_name=="startimes") {
  # code...
    $payto="STARTIMES";
}
else if ($biller_name=="gotv") {
  # code...
  $payto="GOTV";
}
else if ($biller_name=="dstv") {
  # code...
       $payto="DSTV";
}
else if ($biller_name=="nairobi_water") {
  # code...
        $payto="NWATER";
}
else{
    return Array("data"=>Array("response"=>"Biller payment not supported"),"error"=>true);
}
$array=Array("paymentType"=>$payto,"PhoneNumber"=>"0".substr($phone, 3),"AccountNumber"=>$account,"AccountName"=>"customer","Amount"=>$amount*100);
  // $array=Array("MobileNumber"=>"0".substr($phone, 3),"CustomerName"=>"customer","CustAccNum"=>$account,"Amount"=>$amount*100);
$res=$paybillobj->otherpayments($array);

 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
  return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];

return Array("data"=>Array("response"=>"Payment Successs"),"error"=>false);
  # code...
}
else{
    return Array("data"=>Array("response"=>"An error occured processing your request.".$decdata->ResponseDescription),"error"=>true);
}



}




  
//    $response= json_decode($this->createTransaction($account,$amount,$biller_name,$phone)); 




// //return response()->json($response);
// if (isset($response->error)) {
//   # code...
//   //top up customer account and send an sms and push notification
//     return Array("data"=>Array("response"=>is_array($response->error)?$response->error[0]->text:$response->error->error),"error"=>true);
// }
// else{
//   //update the account
// $balance=$main->first()->balance;
// $sender=$customers->user_id;
// $sendamount=intval($request->input("amount"));
// $amount=intval($request->input("amount"));
// //


//       $main->update(["balance"=>intval($balance)-$amount]);

// $balance=\App\User::whereId($sender)->first();
// $balance=intval($balance->balance);


//         for($i=0;$i<1000000;$i++){
//             $transid = 'TB'.rand(10000,99999)."M";
//             $res=\App\topups::whereTransid($transid)->first();
//             if ($res==null) {             # code...
// break;  }
          
//         }

//  $credentials=Array("amount"=>$request->amount,"balance"=>$balance,"transid"=>$transid,"sender"=>$sender,"type"=>"airtime");
// \App\topups::create($credentials);
//   $obj = new pushNotification();
//     $data=Array("name"=>"home","value"=>"home");
//     $obj->exceuteSendNotification(\App\User::whereId($sender)->first()->token,"Thank you for topping up KSh. ".$sendamount." airtime with us.","Transaction successful. ",$data);

//   return Array("data"=>Array("response"=>"Airtime top-up successs"),"error"=>false);

// }

//return $this->phonelookup($prefix);
//return $this->getAccountBalance();


}


function getBalance(){
$paybillobj = new paybills();

$result=$paybillobj->getBalance();
$encrypted_key="1903360117933878";
$obj = new AES($encrypted_key);

 $decdata = $obj->decrypt((json_decode($result))->Data);
 $decdata=json_decode($decdata);

if (($decdata->ResponseCode)=="000") {
     
    $bal= $decdata->Balance;
    # code...
}
else{
    
 $bal= 0;
    }
$balance="KES ". strval((intval($bal))/100);
return $balance;
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


}



