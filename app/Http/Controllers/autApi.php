<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use DB;

class autApi extends Controller
{
    //
    function registerUser(Request $request){
    	$phone=$request->input("phone");
    	$email=$request->input("email");
    	$phoneexists=\App\User::whereEmail($email)->first();
    	$userexists=\App\Customers::wherePhone($phone)->first();
    	if ($userexists!=null) {
    		# code...

if ($phoneexists!=null) {
	# code...


$user = new \App\User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('phone'));
        $user->save();

        $user_id = DB::getPdo()->lastInsertId();

        $customer = new \App\Customers();
        $customer->user_id = $user_id; 
        $customer->phone  = $valid_phone;
        $customer->save();

        return Array("response"=>"Account created successfully","error"=>true); 
}
else{
return Array("response"=>"Email Already taken","error"=>true);

}

    	}
    	else{
    		return Array("response"=>"Phone Number  Already taken","error"=>false);
    	}




    }
}
