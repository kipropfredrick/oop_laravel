<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\User;
use App\Http\Controllers\pushNotification;
use App\Payments;
use App\Http\Controllers\SendSMSController;




class firebasetopics extends Controller
{
    //

    function index(Request $request){

    	$topics=DB::table("firebasetopics")->get();
return view("backoffice.topics.index",compact('topics'));

    }

    function addtopic(Request $request){
$checkifexists=DB::table("firebasetopics")->whereName($request->input('topic'))->first();
if ($checkifexists!=null) {
	# code...
	return Back()->with("error","Topic Already Exists");
//return Array("status"=>"error","Topic Already Exists");
}
else{
	$topics=DB::table("firebasetopics")->insert(["name"=>$request->input('topic')]);
return Back()->with("success","information updated");

}


    }

    function removetopic(Request $request){
    		$topics=DB::table("firebasetopics")->whereId($request->id)->delete();

return Back()->with("success","information updated");
    }


    function customNotifications(Request $request){
        $users=User::get();
        return view("backoffice.topics.custom",compact('users'));

    }
    function customnotify(Request $request){

        $type=$request->input('seletedtext');
        $message=$request->message;
        $title=$request->title;
        $count=0;
            $obj = new pushNotification();
        if ($type=="group") {
            # code...
            $category=$request->category?$request->category:[];


if (in_array("active", $category))
  {

    $customers=\App\Bookings::where('status','=','active')->pluck('customer_id')->toArray();
    $users = \App\Customers::whereIn("id",$customers)->pluck('user_id')->toArray();
$user=User::whereIn("id",$users)->get();

foreach ($user as $key => $value) {

$token=$value->token;
    if ($token==null) {
        # code...

    }
    else{
        $count=$count+1;
    $data=Array("name"=>"home","value"=>"home");
    $messages = str_replace('{customerName}',$value->name, $message);
    $obj->exceuteSendNotification($token,$messages,$title,$data);
        }

}

  }
   if (in_array("complete", $category))
  {

   $customers=\App\Bookings::where('status','=','complete')->pluck('customer_id')->toArray();
    $users = \App\Customers::whereIn("id",$customers)->pluck('user_id')->toArray();
$users=User::whereIn("id",$users)->get();

foreach ($users as $key => $value) {

$token=$value->token;
    if ($token==null) {
        # code...

    }
    else{
        $count=$count+1;
    $data=Array("name"=>"home","value"=>"home");

   $messages = str_replace('{customerName}',$value->name, $message);
    $obj->exceuteSendNotification($token,$messages,$title,$data);
        }

}


  }
    if (in_array("pending", $category))
  {
  $customers=\App\Bookings::pluck('customer_id')->toArray();
    $users = \App\Customers::whereNotIn("id",$customers)->pluck('user_id')->toArray();
$users=User::whereIn("id",$users)->whereNotNull("token")->get();


$i=0;
foreach ($users as $key => $value) {
 if($i>1300){
          
$token=$value->token;
    if ($token==null) {
        # code...

    }
    else{
        $count=$count+1;
    $data=Array("name"=>"home","value"=>"home");
    // $obj->exceuteSendNotification($token,$message,$title,$data);

       $messages = str_replace('{customerName}',$value->name, $message);
    $obj->exceuteSendNotification($token,$messages,$title,$data);
        }
        if($i==1800){
            break;
        }

     

}
   $i=$i+1;
  }  

        }

        }
        else{


$sendto=$request->sendto;

if ($sendto=="000") {
      return Back()->with('error','no item selected');
    # code...
}
else{

$token=\App\User::whereId($sendto)->first()->token;

    if ($token==null) {
        # code...

    }
    else{
    $obj = new pushNotification();
    $data=Array("name"=>"home","value"=>"home");
       $message = str_replace('{customerName}',\App\User::whereId($sendto)->first()->name, $message);
    $obj->exceuteSendNotification($token,$message,$title,$data);
  //  $obj->exceuteSendNotification($token,$message,$title,$data);
      $count=$count+1;
        }


}


        }


        return Back()->with('success','notification sent successfully ('. $count .' recipients)'  );
    }



    public function promonotification(Request $request){
        $bookings_customers=\App\Bookings::where('status','=','active')->orWhere('status','=','pending')->pluck('customer_id')->toArray();
        $booking_references=\App\Bookings::where('status','=','active')->pluck('booking_reference')->toArray();
        $customersCount = \App\Customers::whereIn("id",$bookings_customers)->count();
$today =date("Y-m-d", strtotime("+1 days"));
// date('Y-m-d');
$lastWeek = date("Y-m-d", strtotime("-7 days"));
$filtertotalpid=[];
$bookings=[];
$payments=Payments::whereBetween('created_at', [$lastWeek, $today])->select([DB::raw("SUM(transaction_amount) as total_paid"),"booking_id"])
->groupBy('booking_id')->get();

foreach($payments as $payment){
if (intval($payment->total_paid)>=1400) {
    array_push($filtertotalpid,$payment);
    array_push($bookings,$payment->booking_id);
}
}
$alreadySendTo=\App\promotions::pluck('booking_reference')->toArray();
$booking_reference=\App\Bookings::where('status','=','active')->whereIn("id",$bookings)->whereNotIn('booking_reference',$alreadySendTo)->pluck('booking_reference')->toArray();
$winner=$booking_reference[array_rand($booking_reference)];
$totalpaid=0;
$bookingId=\App\Bookings::whereBooking_reference($winner)->first()->id;
foreach ($filtertotalpid as $key => $value) {
    # code...
    if($bookingId==$value->booking_id){
$totalpaid=$value->total_paid;
    }
}

// return $winner;
 //$winner="MM48222";

//

$customer=\App\Customers::with('user')->whereId(\App\Bookings::whereBooking_reference($winner)->first()->customer_id)->first();


if($customer!=null){
    $customerName=$customer->user->name;
//logusertodb
//update database
$booking=\App\Bookings::whereBooking_reference($winner);
$bookingobject=$booking->first();
$orderbalance=intval($bookingobject->balance)-150;
$discount=intval($bookingobject->discount)+150;
$details=Array("balance"=>$orderbalance,"discount"=>$discount);
$booking->update($details);
$balance=\App\Bookings::whereCustomer_id($customer->id)->where('status','=','active')->first()->balance;
$message="Hello {$customerName}, you have received a KSh.150 discount on your order. Your balance is KSh.{$balance}. Thank you.";
$credentials=Array("customers_id"=>$customer->id,"booking_reference"=>$winner,"totalpaid"=>$totalpaid,"discount"=>150,"discounted_at"=>Now());
\App\promotions::create($credentials);
//sendsms
    SendSMSController::sendMessage("+".$customer->phone,$message,$type="payment_notification");

    //send push notification

$token=$customer->user->token;
$obj = new pushNotification();
if ($token==null) {
    # code...

}
else{
$data=Array("name"=>"home","value"=>"home",'priority' => "high");
$message="Hello {$customerName}, you have received a KSh.150 discount on your order. Your balance is KSh.{$balance}. Thank you.
";
$title="Congratulations! You have received a KSh.150 discount.";
$obj->exceuteSendNotification($token,$message,$title,$data);
    }


}
return $customer;

    }

function sendpromoreminder(Request $request){
    $customers=\App\Bookings::whereIn('status',["active","pending"])->pluck('customer_id')->toArray();

    $users = \App\Customers::whereIn("id",$customers)->pluck('user_id')->toArray();
$users=User::whereIn("id",$users)->get();
$obj = new pushNotification();
foreach ($users as $key => $value) {

$token=$value->token;
    if ($token==null) {
        # code...

    }
    else{

    $data=Array("name"=>"payment","value"=>"payment",'priority' => "high");
$message="Stand a chance to get up to KSh.1000 discount on your order this week if you pay for at least 5 days straight.";
$title="Congratulations! We want to reward you.";
$obj->exceuteSendNotification($token,$message,$title,$data);
        }

}
}
}
