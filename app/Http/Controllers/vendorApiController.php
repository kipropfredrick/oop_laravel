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

	function index(Request $request){
		$vendor_code=$request->vendor_code;
		$activeBookingAmount = \App\Bookings::where('status','=','active')->where('vendor_code',$vendor_code)->sum('total_cost');
        $activeBookingCount = \App\Bookings::where('status','=','active')->where('vendor_code',$vendor_code)->count();

        $revokedBookingAmount = \App\Bookings::where('status','=','revoked')->where('vendor_code',$vendor_code)->sum('total_cost');
        $revokedBookingCount = \App\Bookings::where('status','=','revoked')->where('vendor_code',$vendor_code)->count();
        $completeBookingAmount = \App\Bookings::where('status','=','complete')->where('vendor_code',$vendor_code)->sum('total_cost');
        $completeBookingCount = \App\Bookings::where('status','=','complete')->where('vendor_code',$vendor_code)->count();
        $pendingBookingAmount = \App\Bookings::where('status','=','pending')->where('vendor_code',$vendor_code)->sum('total_cost');
        $pendingBookingCount = \App\Bookings::where('status','=','pending')->where('vendor_code',$vendor_code)->count();

        $array=Array("completebookingamount"=>$completeBookingAmount,"completebookingcount"=>$completeBookingCount,"activebookingamount"=>$activeBookingAmount,"activebookingcount"=>$activeBookingCount,"revokedbookingamount"=>$revokedBookingAmount,"revokedbookingcount"=>$revokedBookingCount,"pendingbookingamount"=>$pendingBookingAmount,"pendingbookingcount"=>$pendingBookingCount);

        return $array;



	}
}
