<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use DB;
use AfricasTalking\SDK\AfricasTalking;
use App\Customers;

class CustomerController extends Controller
{
    public function index()
    {
      return view('backoffice.index');
    }
public function redeem(Request $request){
$user = Auth::user();
$main=DB::table('users')->whereEmail(Auth::user()->email);
$balance=$main->first()->balance;
$amount=intval($request->input("amount"))-(intval($request->input("amount")))*0.1;

if ($amount>intval($balance)) {
    # code...

    return back()->with("error","You Have Insufficient Balance in your wallet account");
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
    return back()->with("error","mobile number format not supported");
}

  $username = env('AFRIUSERNAME'); // use 'sandbox' for development in the test environment
$apiKey   =env('AFRIAPIKEY');

$AT       = new AfricasTalking($username, $apiKey);

$airtime = $AT->airtime();
$array=Array("recipients"=>[Array('phoneNumber' => $mobilerec,
'currencyCode' => "KES",
'amount' => $amount)]);

$result   = $airtime->send($array);
\Log::info(json_encode($result));
// return back()->with("error","An Error Occured, check details and Try Again");

if ($result['data']->errorMessage=="None") {
    $main->update(["balance"=>intval($balance)-$amount]);

    
    return Back()->with("success","Payment Completed Successfully");
}
else{

    return back()->with("error",$result['data']->responses[0]->errorMessage);
}












}
    public function pending_bookings(){
        $user = Auth::user();
        $customer = Customers::where('user_id','=',$user->id)->first();
        $bookings = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','pending')->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.customer.pending',compact('bookings')); 
    }

    public function complete_bookings(){
        $user = Auth::user();
        $customer = Customers::where('user_id','=',$user->id)->first();
        $bookings = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','complete')->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.customer.complete',compact('bookings')); 
    }

    public function active_bookings(){
        $user = Auth::user();
        $customer = Customers::where('user_id','=',$user->id)->first();
        $bookings = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','active')->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.customer.active',compact('bookings')); 
    }

    public function revoked_bookings(){
        $user = Auth::user();
        $customer = Customers::where('user_id','=',$user->id)->first();
        $bookings = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','revoked')->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.customer.revoked',compact('bookings')); 
    }

}
