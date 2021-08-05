<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AES;
use App\trasactsession;
use Carbon\Carbon;

class mcry extends Controller
{
    //
function index(){
// $terminalnumber = '10821082';
// $padding_zero   = '00000000';
// $encrypted_key = $terminalnumber.$padding_zero;

	$plaintext = Array("function"=>"BscGenerateSessionID","RequestUniqueID"=>"67864747647","MethodName"=>"DstGenerateSessionID");

$plaintext=json_encode($plaintext);
 $s = substr(str_shuffle(str_repeat("123456789", 8)), 0, 8);
$encrypted_key="1903360117933878";
$obj = new AES($encrypted_key);
$encrypted_terminalnumber = $obj->encrypt('{"function":"DstGenerateSessionID","RequestUniqueID":"'.$s.'","MethodName":"DstGenerateSessionID"}');

	
 if ((count(trasactsession::get()))>0) {
 	# code...
$res= trasactsession::latest('created_at')->first();
$startTime = Carbon::parse($res->created_at);
    $endTime = Carbon::now();
    $totalDuration =  $startTime->diff($endTime)->format('%I');
     $hrs =  $startTime->diff($endTime)->format('%H');
    $hr= intval($hrs)*60 + intval($totalDuration);
 \Log::info($hr);
  //return Array("totalDuration"=> $totalDuration,"startTime"=>$startTime,"endTime"=>$endTime);
    if ($hr>30) {
    	# code...
    		$ch = curl_init();


curl_setopt($ch, CURLOPT_URL,"http://rsadmin.pesapoint.co.ke/distributormobilerest/distributormobilerest/" );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_POST,           1 );
curl_setopt($ch, CURLOPT_POSTFIELDS,     "TerminalNumber=96956906&Data=".$encrypted_terminalnumber ); 
curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

$result=curl_exec ($ch);

 $decrypted_terminalnumber = $obj->decrypt((json_decode($result))->Data);
 if (json_decode($decrypted_terminalnumber)->ResponseCode == "000") {
  	# code...
  	 $SessionID=json_decode($decrypted_terminalnumber)->SessionID;
  	 	trasactsession::create(Array("requestId"=>$s,"sessionId"=>$SessionID));

  }
  else{
  	return 0;
  }


    }
 }
 else{
 $ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://rsadmin.pesapoint.co.ke/distributormobilerest/distributormobilerest/" );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
curl_setopt($ch, CURLOPT_POST,           1 );
curl_setopt($ch, CURLOPT_POSTFIELDS,     "TerminalNumber=96956906&Data=".$encrypted_terminalnumber ); 
curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain')); 

$result=curl_exec ($ch);

 $decrypted_terminalnumber = $obj->decrypt((json_decode($result))->Data);

 if (json_decode($decrypted_terminalnumber)->ResponseCode == "000") {
  	# code...
  	 $SessionID=json_decode($decrypted_terminalnumber)->SessionID;
  	 	trasactsession::create(Array("requestId"=>$s,"sessionId"=>$SessionID));

  }
  else{
  	return 0;
  }
 
 }
$res= trasactsession::latest('created_at')->first();
return $res->sessionId;
}

// echo "<b>Terminal Number :</b>".$terminalnumber = '10821082';
// echo "<br/><b>Encryption of Terminal number (".$terminalnumber.") :</b> " . urlencode($encrypted_terminalnumber);

// $decrypted_terminalnumber = $obj->decrypt($encrypted_terminalnumber);
// echo "<br/><b>Decryption :</b>" . $decrypted_terminalnumber ."</br>";
}
