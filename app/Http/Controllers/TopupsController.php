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
return Array("data"=>Array("response"=>"An Error Occured Processing Your Request"),"error"=>true);
}
$balance=intval(DB::table("users")->whereId($customers->user_id)->first()->balance);


return Array("balance"=>$balance,"error"=>false);
}


}
