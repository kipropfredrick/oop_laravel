<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use App\Customers;
use App\topups;

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
$balance=intval($balance->balance)+$request->amount;
\App\User::whereId($sender)->update(["balance"=>$balance]);

 $credentials=Array("amount"=>$request->amount,"balance"=>$balance,"transid"=>"xxxxxxxx","sender"=>$sender);
\App\topups::create($credentials);
return Array("data"=>Array("response"=>"Payment Completed Successfully"),"error"=>false);
}

function purchaseAirtime(Request $request){
	
}

}
