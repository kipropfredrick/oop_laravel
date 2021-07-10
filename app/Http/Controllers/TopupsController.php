<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use App\Customers;
use App\topups;
use AfricasTalking\SDK\AfricasTalking;
use App\Http\Controllers\autApi;

class TopupsController extends Controller
{
    //

public function balances(Request $request){
	$phone=$request->phone;
  $customers=Customers::wherePhone($phone)->first();

if($customers==null){
return Array("data"=>Array("response"=>"Account Association Failed.contact support"),"error"=>true);
}

$user_id=$customers->user_id;
$transactions=topups::get();
foreach ($transactions as $key => $value) {
	# code...
	$value->date=$value->created_at->toDateTimeString();
}
$balance=intval(DB::table("users")->whereId($customers->user_id)->first()->balance);


return Array("error"=>false,"data"=>Array("response"=>"Account linked successfully.","balance"=>$balance,"transactions"=>$transactions));

}


function maketopups(Request $request){

           $phone=$request->phone;
   $customers=Customers::wherePhone($phone)->first();
  if ($customers==null) {
  	return Array("data"=>Array("response"=>"Cannot make topups.contact support"),"error"=>true);
  	# code...
  }
$sender=$customers->user_id;
$balance=\App\User::whereId($sender)->first();

if ($balance->mosmosid==null) {
	  	return Array("data"=>Array("response"=>"Your Account Was Not linked properly. contact administrator"),"error"=>true);
	# code...
}
// $balance=intval($balance->balance)+$request->amount;
// \App\User::whereId($sender)->update(["balance"=>$balance]);

// $credentials=Array("amount"=>$request->amount,"balance"=>$balance,"transid"=>"xxxxxxxx","sender"=>$sender);
// \App\topups::create($credentials);

$obj=new autapi();
$result=$obj->stk_push($request->amount,"p"+$phone,$balance->mosmosid);
if($result->success){
return Array("data"=>Array("response"=>"Payment Request Send. Enter your mpesa pin to complete"),"error"=>false);	
}
else{
return Array("data"=>Array("response"=>"An error Occured processing your request, try again later"),"error"=>true);	
}

}

public function redeem(Request $request){
 $phone=$request->phone;
  $customers=Customers::wherePhone($phone)->first();
  if ($customers==null) {
  	return Array("data"=>Array("response"=>"Cannot make Redemtion.contact support"),"error"=>true);
  	# code...
  }
$main=DB::table('users')->whereId($customers->user_id);
$balance=$main->first()->balance;
$sender=$customers->user_id;
$sendamount=intval($request->input("amount"));
$amount=intval($request->input("amount"));
if ($amount>intval($balance)) {
    # code...
	return Array("data"=>Array("response"=>"You Have Insufficient Balance in your wallet account"),"error"=>true);
    
}
//return $result;




$airtime=false;
$mobileInput=$request->input('phone');
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

if (!$airtime) {
    # code...
    return Array("data"=>Array("response"=>"mobile number format not supported"),"error"=>true);
   
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

  return Array("data"=>Array("response"=>"Airtime redemption was successul"),"error"=>false);

}
else{
   return Array("data"=>Array("response"=>$result['data']),"error"=>true);
    
}


}

}
