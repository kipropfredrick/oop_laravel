<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class firebaseToken extends Controller
{
    //
    function insertToken(Request $request){
    	$phone=$request->input('username');
    	$token=$request->input("token");
    	$existingCustomer = \App\Customers::where('phone','=',$phone)->first();
        $userid= $existingCustomer->userid;
\App\User::whereId( $userid)->update(['token'=>$token]);

    }
 function updateToken(Request $request){
    	$phone=$request->input('username');
    	$token=$request->input("token");
    	$existingCustomer = \App\Customers::where('phone','=',$phone)->first();
        $userid= $existingCustomer->userid;
\App\User::whereId( $user_id)->update(['token'=>$token]);

    }

}
