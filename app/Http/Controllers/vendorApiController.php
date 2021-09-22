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
		$vendor_id=\App\Vendor::whereVendor_code($vendor_code)->first()->id;
		$activeBookingAmount = \App\Bookings::where('status','=','active')->where('vendor_code',$vendor_code)->sum('total_cost');
        $activeBookingCount = \App\Bookings::where('status','=','active')->where('vendor_code',$vendor_code)->count();

        $revokedBookingAmount = \App\Bookings::where('status','=','revoked')->where('vendor_code',$vendor_code)->sum('total_cost');
        $revokedBookingCount = \App\Bookings::where('status','=','revoked')->where('vendor_code',$vendor_code)->count();
        $completeBookingAmount = \App\Bookings::where('status','=','complete')->where('vendor_code',$vendor_code)->sum('total_cost');
        $completeBookingCount = \App\Bookings::where('status','=','complete')->where('vendor_code',$vendor_code)->count();
        $pendingBookingAmount = \App\Bookings::where('status','=','pending')->where('vendor_code',$vendor_code)->sum('total_cost');
        $pendingBookingCount = \App\Bookings::where('status','=','pending')->where('vendor_code',$vendor_code)->count();
        $products=\App\Products::whereVendor_id($vendor_id)->count();

        $array=Array("completebookingamount"=>$completeBookingAmount,"completebookingcount"=>$completeBookingCount,"activebookingamount"=>$activeBookingAmount,"activebookingcount"=>$activeBookingCount,"revokedbookingamount"=>$revokedBookingAmount,"revokedbookingcount"=>$revokedBookingCount,"pendingbookingamount"=>$pendingBookingAmount,"pendingbookingcount"=>$pendingBookingCount,'totalproducts'=>$products);

        return $array;



	}

	      public function bookings(Request $request){
        $username=$request->input("vendor_code");
        $status=$request->input("type");
        $bookings = \App\Bookings::where('vendor_code','=',$username)->where('status','=',$status)->latest()->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
            $booking['product_name']=\App\Products::whereId($booking->product_id)->first()->product_name;
              $booking['product_code']=\App\Products::whereId($booking->product_id)->first()->product_code;
            $booking['customer_name']=\App\User::whereId(\App\Customers::whereId($booking->customer_id)->first()->user_id)->first()->name;
        }

       return $bookings;
    }

      function getProducts(Request $request){
 
       $products= Products::select('id','product_name','product_price','product_image','status')->where('status','=','approved')->inRandomOrder()->paginate(20);

       return $products->items();


    }

}
