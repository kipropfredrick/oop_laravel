<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use DB;
use App\Customers;

class TopupsController extends Controller
{
    //

public function balances(Request $request){
	$phone=$request->phone;
  $customers=Customers::wherePhone($phone)->first();

if($customers==null){
return Array("data"=>Array("response"=>"Account Association Failed.contact support"),"error"=>true);
}
$balance=intval(DB::table("users")->whereId($customers->user_id)->first()->balance);
wss

return Array("error"=>false,"data"=>Array("response"=>"Account linked successfully.","balance"=>$balance));
}


}
