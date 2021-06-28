<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use app\User;
use App\Http\Controllers\pushNotification;



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
$users=User::whereIn("id",$users)->get();

foreach ($users as $key => $value) {

$token=$value->token;
    if ($token==null) {
        # code...
 
    }
    else{
        $count=$count+1;
    $data=Array("name"=>"home","value"=>"home");
    $message = str_replace('{customerName}',$value->name, $message);
    $obj->exceuteSendNotification($token,$message,$title,$data);
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
   $message = str_replace('{customerName}',$value->name, $message);
    $obj->exceuteSendNotification($token,$message,$title,$data);
        }
  
}


  }
    if (in_array("pending", $category))
  {
  $customers=\App\Bookings::pluck('customer_id')->toArray();
    $users = \App\Customers::whereNotIn("id",$customers)->pluck('user_id')->toArray();
$users=User::whereIn("id",$users)->get();

foreach ($users as $key => $value) {

$token=$value->token;
    if ($token==null) {
        # code...
 
    }
    else{
        $count=$count+1;
    $data=Array("name"=>"home","value"=>"home");
    // $obj->exceuteSendNotification($token,$message,$title,$data);
       $message = str_replace('{customerName}',$value->name, $message);
    $obj->exceuteSendNotification($token,$message,$title,$data);
        }
  
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

      
        return Back()->with('success','notification send successfully ('. $count .' recipients)'  );
    }
}
