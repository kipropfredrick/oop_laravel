<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pesalogs;
use AfricasTalking\SDK\AfricasTalking;


class paybills extends Controller
{
    //
     function kplcprepaid($array){

       

     $VoucherData=json_encode(Array("PhoneNumber"=>$array['PhoneNumber'],"MeterNumber"=>$array['MeterNumber'],"CustomerName"=>$array['CustomerName']));
     	
  


$s = substr(str_shuffle(str_repeat("123456789", 11)), 0, 11);
$encrypted_key="5085891621249755";
$obj = new AES($encrypted_key);


 for ($i=0; $i <1000000000000 ; $i++) { 
   # code...
  $s="8".substr(str_shuffle(str_repeat("123456789", 11)), 0, 11);
  $check=pesalogs::where("paymentreference",$s)->exists();
  if ($check) {
    # code...
  }
  else{
    break;
  }
 }
$mapobj = new mcry();
$sessionId=$mapobj->index();
  $data=json_encode(Array("function"=>"BillpayProductDetails","ProductCode"=>"14248","SystemServiceID"=>4,"SessionID"=>$sessionId,"RequestUniqueID"=>$s,"Amount"=>$array['Amount'],"MethodName"=>"VoucherFlexi","VoucherData"=>$VoucherData,"Quantity"=>1));
//$encdata = $obj->encrypt('{"function":"BillpayProductDetails","SessionID":"'.$sessionId.'","RequestUniqueID":'.$s.',"SystemServiceID":"_","MethodName":"BillpayProductDetails"}');
$encdata=$obj->encrypt($data);


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,            "http://rshost.pesapoint.co.ke/productrest/productrest" );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_POST,           1 );
curl_setopt($ch, CURLOPT_POSTFIELDS,     "TerminalNumber=18991324&TransactionKey=1157699726&Data=".$encdata ); 
curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

$result=curl_exec ($ch);



 $decdata = $obj->decrypt((json_decode($result))->Data);
\Log::info($array['MeterNumber']);
\Log::info(json_encode($decdata));
 $decdata=json_decode($decdata);

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];

    $token=json_decode(json_decode($decdata->VoucherDetails,true)[0])->Token;
   
   $ret= pesalogs::where("TransID",$array['TransID'])->update(["status"=>"credited","token"=>$token,"paymentreference"=>$s]);
 

	return "payment made successfully";
	# code...
}
else{

   $ret= pesalogs::where("TransID",$array['TransID'])->update(["reason"=>$decdata->ResponseDescription,"paymentreference"=>$s]);
        $username = "swiftpay"; // use 'sandbox' for development in the test environment
$apiKey   ="2ae24a2f2364955edefc7889b12823b2f81429283a5cda07b2700ae6665dc6ba";
$AT       = new AfricasTalking($username, $apiKey);
$sms      = $AT->sms();

//Use the service

$phone=pesalogs::where("TransID",$array['TransID'])->first()->MSISDN;
$result   = $sms->send([
    'to'      => "+".$phone,
    'message' => 'Hello. Your transaction could not be completed. Please contact support for assistance. Call  0720303095.'
]);
		return "An Error Occured Try Again";
}
 return $decdata;
    }

      function otherpayments($array){


  // this is payment for the following   DSTV, GOTV, Startimes, ZUKU, Nairobi Water (Bill Payment).

      	// $array=Array("paymentType"=>"DSTV","PhoneNumber"=>"0790535349","AccountNumber"=>"7030513969","AccountName"=>"James wanjohi","Amount"=>1000);
            $type=$array['paymentType'];

            if ($type=="DSTV") {
             	# code...
             	$payment="04302901";
             	$ProductCode="3029";
             } 
             else if ($type=="GOTV") {
             	# code...
             	$payment="04321901";
             	$ProductCode="3219";
             }
             else if ($type=="STARTIMES") {
             	# code...
             	$payment="043032226599";
             	$ProductCode="3032";
             }
             else if ($type=="ZUKU") {
             	# code...
             	$payment="04303001";
             	$ProductCode="3030";
             }
             else if ($type=="NWATER") {
             	# code...
             	$payment="04303301";
             	$ProductCode="3033";
             }
             else{

             }
            
//0703053277
//04302901

     	$infor=json_encode(Array("PhoneNumber"=>$array['PhoneNumber'],"PaymentCode"=>$payment,"AccountNumber"=>$array['AccountNumber'],"AccountName"=>$array['AccountName']));
     	
  


$s = substr(str_shuffle(str_repeat("123456789", 11)), 0, 11);
$encrypted_key="5085891621249755";
$obj = new AES($encrypted_key);
  for ($i=0; $i <100000000000000 ; $i++) { 
   # code...
  $s="9".substr(str_shuffle(str_repeat("123456789", 11)), 0, 11);
  $check=pesalogs::where("paymentreference",$s)->exists();
  if ($check) {
    # code...
  }
  else{
    break;
  }
 }
$mapobj = new mcry();
$sessionId=$mapobj->index();

  $data=json_encode(Array("function"=>"BillpayProductDetails","ProductCode"=>$ProductCode,"SystemServiceID"=>1024,"SessionID"=>$sessionId,"RequestUniqueID"=>$s,"Amount"=>$array['Amount'],"MethodName"=>"BillPay","Quantity"=>1,"FromANI"=>"","Email"=>"","BillPayData"=>$infor));
//$encdata = $obj->encrypt('{"function":"BillpayProductDetails","SessionID":"'.$sessionId.'","RequestUniqueID":'.$s.',"SystemServiceID":"_","MethodName":"BillpayProductDetails"}');
$encdata=$obj->encrypt($data);


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,            "http://rshost.pesapoint.co.ke/productrest/productrest" );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_POST,           1 );
curl_setopt($ch, CURLOPT_POSTFIELDS,     "TerminalNumber=18991324&TransactionKey=1157699726&Data=".$encdata ); 
curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

$result=curl_exec ($ch);

 $decdata = $obj->decrypt((json_decode($result))->Data);
\Log::info($decdata);
 $decdata=json_decode($decdata);
if (($decdata->ResponseCode)=="000") {
      pesalogs::where("TransID",$array['TransID'])->update(["status"=>"credited","paymentreference"=>$s]);
	return "payment made successfully";
	# code...
}
else{
 $ret= pesalogs::where("TransID",$array['TransID'])->update(["reason"=>$decdata->ResponseDescription,"paymentreference"=>$s]);
    
        $username = "swiftpay"; // use 'sandbox' for development in the test environment
$apiKey   ="2ae24a2f2364955edefc7889b12823b2f81429283a5cda07b2700ae6665dc6ba";
$AT       = new AfricasTalking($username, $apiKey);
$sms      = $AT->sms();

//Use the service
$phone=pesalogs::where("TransID",$array['TransID'])->first()->MSISDN;
$result   = $sms->send([
    'to'      => "+".$phone,
    'message' => 'Hello. Your transaction could not be completed. Please contact support for assistance. Call  0720303095.'
]);
    return "An Error Occured Try Again";

}

 return $decdata;
    }

         function kplcpostpaid($array){

   // ,"BillPayData":"{\"PaymentCode\":\"04302901\",\"PhoneNumber\":\"xxxx\",\"AccountNumber\":\"1021840961\",\"AccountName\":\"Name\"}}
// $array=Array("MobileNumber"=>"0790535349","CustAccNum"=>"10524304","CustomerName"=>"","OutStanding Balance"=>"000","Amount"=>"1000");

     	$infor=json_encode(Array("MobileNumber"=>$array['MobileNumber'],"CustAccNum"=>$array['CustAccNum'],"CustomerName"=>$array['CustomerName'],"OutStanding Balance"=>"000"));
  

$s = substr(str_shuffle(str_repeat("123456789", 11)), 0, 11);
$encrypted_key="5085891621249755";
$obj = new AES($encrypted_key);
  for ($i=0; $i <100000000000000 ; $i++) { 
   # code...
  $s="9".substr(str_shuffle(str_repeat("123456789", 11)), 0, 11);
  $check=pesalogs::where("paymentreference",$s)->exists();
  if ($check) {
    # code...
  }
  else{
    break;
  }
 }
$mapobj = new mcry();
$sessionId=$mapobj->index();

  $data=json_encode(Array("ProductCode"=>"14249","SystemServiceID"=>4,"SessionID"=>$sessionId,"RequestUniqueID"=>$s,"Amount"=>$array['Amount'],"MethodName"=>"BillPay","Quantity"=>1,"FromANI"=>"","Email"=>"","BillPayData"=>$infor));
//$encdata = $obj->encrypt('{"function":"BillpayProductDetails","SessionID":"'.$sessionId.'","RequestUniqueID":'.$s.',"SystemServiceID":"_","MethodName":"BillpayProductDetails"}');
$encdata=$obj->encrypt($data);


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,            "http://rshost.pesapoint.co.ke/productrest/productrest" );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_POST,           1 );
curl_setopt($ch, CURLOPT_POSTFIELDS,     "TerminalNumber=18991324&TransactionKey=1157699726&Data=".$encdata ); 
curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

$result=curl_exec ($ch);

 $decdata = $obj->decrypt((json_decode($result))->Data);
  $decdata=json_decode($decdata);
if (($decdata->ResponseCode)=="000") {
      pesalogs::where("TransID",$array['TransID'])->update(["status"=>"credited","paymentreference"=>$s]);
    return "payment made successfully";
    # code...
}
else{
   
     $ret= pesalogs::where("TransID",$array['TransID'])->update(["reason"=>$decdata->ResponseDescription,"paymentreference"=>$s]);
        $username = "swiftpay"; // use 'sandbox' for development in the test environment
$apiKey   ="2ae24a2f2364955edefc7889b12823b2f81429283a5cda07b2700ae6665dc6ba";
$AT       = new AfricasTalking($username, $apiKey);
$sms      = $AT->sms();

//Use the service
$phone=pesalogs::where("TransID",$array['TransID'])->first()->MSISDN;
$result   = $sms->send([
    'to'      => "+".$phone,
    'message' => 'Hello. Your transaction could not be completed. Please contact support for assistance. Call  0720303095.'
]);
    return "An Error Occured Try Again";
// return $decdata;
    }
}

function getBalance(){

  

$s = substr(str_shuffle(str_repeat("123456789", 13)), 0, 13);
$encrypted_key="5085891621249755";
$obj = new AES($encrypted_key);
 $s = substr(str_shuffle(str_repeat("123456789", 13)), 0, 13);
$mapobj = new mcry();
$sessionId=$mapobj->index();

  $data=json_encode(Array("function"=>"DstGetBalance","SessionID"=>$sessionId,"RequestUniqueID"=>$s,"MethodName"=>"DstGetBalance"));
//$encdata = $obj->encrypt('{"function":"BillpayProductDetails","SessionID":"'.$sessionId.'","RequestUniqueID":'.$s.',"SystemServiceID":"_","MethodName":"BillpayProductDetails"}');
$encdata=$obj->encrypt($data);

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,"http://rsadmin.pesapoint.co.ke/distributormobilerest/distributormobilerest/" );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_POST,           1 );
curl_setopt($ch, CURLOPT_POSTFIELDS,     "TerminalNumber=18991324&Data=".$encdata ); 
curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

$result=curl_exec ($ch);


 $decdata = $obj->decrypt((json_decode($result))->Data);
  $decdata=json_decode($decdata);

if (($decdata->ResponseCode)=="000") {
     
    return $decdata->Balance;
    # code...
}
else{
    
 return 0;
    }

}

      function totherpayments(){


  // this is payment for the following   DSTV, GOTV, Startimes, ZUKU, Nairobi Water (Bill Payment).

        $array=Array("paymentType"=>"NWATER","PhoneNumber"=>"0729926358","AccountNumber"=>"5014538","AccountName"=>"James wanjohi","Amount"=>10000);
            $type=$array['paymentType'];

            if ($type=="DSTV") {
              # code...
              $payment="04302901";
              $ProductCode="3029";
             } 
             else if ($type=="GOTV") {
              # code...
              $payment="04321901";
              $ProductCode="3219";
             }
             else if ($type=="STARTIMES") {
              # code...
              $payment="043032226599";
              $ProductCode="3032";
             }
             else if ($type=="ZUKU") {
              # code...
              $payment="04303001";
              $ProductCode="3030";
             }
             else if ($type=="NWATER") {
              # code...
              $payment="04303301";
              $ProductCode="3033";
             }
             else{

             }
            
//0703053277
//04302901

      $infor=json_encode(Array("PhoneNumber"=>$array['PhoneNumber'],"PaymentCode"=>$payment,"AccountNumber"=>$array['AccountNumber'],"AccountName"=>$array['AccountName']));
      
  


$s = substr(str_shuffle(str_repeat("123456789", 11)), 0, 11);
$encrypted_key="5085891621249755";
$obj = new AES($encrypted_key);
 $s = substr(str_shuffle(str_repeat("123456789", 11)), 0, 11);
$mapobj = new mcry();
$sessionId=$mapobj->index();
//return $sessionId;

  $data=json_encode(Array("function"=>"BillpayProductDetails","ProductCode"=>$ProductCode,"SystemServiceID"=>1024,"SessionID"=>$sessionId,"RequestUniqueID"=>$s,"Amount"=>$array['Amount'],"MethodName"=>"BillPay","Quantity"=>1,"FromANI"=>"","Email"=>"","BillPayData"=>$infor));
//$encdata = $obj->encrypt('{"function":"BillpayProductDetails","SessionID":"'.$sessionId.'","RequestUniqueID":'.$s.',"SystemServiceID":"_","MethodName":"BillpayProductDetails"}');
$encdata=$obj->encrypt($data);


$ch = curl_init();

curl_setopt($ch, CURLOPT_URL,            "http://rshost.pesapoint.co.ke/productrest/productrest" );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_POST,           1 );
curl_setopt($ch, CURLOPT_POSTFIELDS,     "TerminalNumber=18991324&TransactionKey=1157699726&Data=".$encdata ); 
curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

$result=curl_exec ($ch);

 $decdata = $obj->decrypt((json_decode($result))->Data);
\Log::info($decdata);
 $decdata=json_decode($decdata);

 return response()->json($decdata);
    }




}
