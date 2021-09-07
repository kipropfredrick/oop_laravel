<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use Auth;

class vendorApiController extends Controller
{
    
	function login(Request $request){
	$username=$request->input('username');
	$password=$request->input('password');
$credentials=Array("email"=>$username,"password"=>$password);
	if (Auth::attempt($credentials)) {
	// Authentication passed...
		if (Auth::user()->role=='vendor') {
			# code...
			$vendor=Vendor::whereUser_id(Auth::user()->id)->first();
		return Array("response"=>Auth::user(),"error"=>false,"vendor"=>$vendor);
		}
		else{
				return Array("response"=>"Only available for vendors.","error"=>true);
		}

	}
	else{
	return Array("response"=>"Incorrect Username or password","error"=>true);
	}

	} 
}
