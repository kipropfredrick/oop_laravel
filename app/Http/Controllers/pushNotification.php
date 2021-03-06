<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pushNotification extends Controller
{
    //

    function testFcm(Request $request){
    	 //API URL of FCM
            $token=\App\User::whereEmail('brianqmutiso@gmail.com')->first()->token;
    if ($token==null) {
        # code...
        return 0;
    }
    $data=Array("name"=>"test","value"=>"To Test Page");
   return  $this->exceuteSendNotification($token,"You have successfully booked (product-name)","Booking Successful",$data);

    }


    function exceuteSendNotification($token,$message,$title,$data){
$url = 'https://fcm.googleapis.com/fcm/send';


    $api_key = 'AAAABjRMKq4:APA91bF7kJTVRuQzw_9H8rbYSUDhqDAa6Jtm15undJXta74F20BCUdyLjPIXw338GHL3nFqlmhNcPhqwva9YFMGvS0fs0q3yJdkTz6yXxZCJ70vJJeNl6_v3LWCizNta6d9zFFScM9UB';

    $fields = array (
        'registration_ids' => array (
               $token
        ),
        "notification"=>Array("title"=>$title,"body"=> $message,"image"=>'','priority' => "high"),

        "android"=>Array("click_action"=>"FLUTTER_NOTIFICATION_CLICK","title"=>$title,"body"=> $message),
        'data' => $data,

    );

    //header includes Content type and api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
   // return $result;
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    $result=json_decode($result);

  //   if (($result->failure)>0) {
  //     # code...
  //     $error=$result->results[0]->error;
  //      return Array("data"=>$error,"message"=>"Unable to Send Push Notification","response"=>"Success");
  //   }
  //   else{

  // return Array("data"=>$result,"message"=>"Push Notification Send Successfully","response"=>"Success");
  //   }
    }


    function exceuteSendNotificationGroup($token,$message,$title,$data){
$url = 'https://fcm.googleapis.com/fcm/send';


    $api_key = 'AAAABjRMKq4:APA91bF7kJTVRuQzw_9H8rbYSUDhqDAa6Jtm15undJXta74F20BCUdyLjPIXw338GHL3nFqlmhNcPhqwva9YFMGvS0fs0q3yJdkTz6yXxZCJ70vJJeNl6_v3LWCizNta6d9zFFScM9UB';

    $fields = array (
        'registration_ids' => $token,
        "notification"=>Array("title"=>$title,"body"=> $message,"image"=>'','priority' => "high"),

        "android"=>Array("click_action"=>"FLUTTER_NOTIFICATION_CLICK","title"=>$title,"body"=> $message),
        'data' => $data,

    );

    //header includes Content type and api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);
   // return $result;
    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    return $result;
    $result=json_decode($result);

  //   if (($result->failure)>0) {
  //     # code...
  //     $error=$result->results[0]->error;
  //      return Array("data"=>$error,"message"=>"Unable to Send Push Notification","response"=>"Success");
  //   }
  //   else{

  // return Array("data"=>$result,"message"=>"Push Notification Send Successfully","response"=>"Success");
  //   }
    }


    function sendtotopics(Request $request){

$url = 'https://fcm.googleapis.com/fcm/send';


    $api_key = 'AAAABjRMKq4:APA91bF7kJTVRuQzw_9H8rbYSUDhqDAa6Jtm15undJXta74F20BCUdyLjPIXw338GHL3nFqlmhNcPhqwva9YFMGvS0fs0q3yJdkTz6yXxZCJ70vJJeNl6_v3LWCizNta6d9zFFScM9UB';
$data=Array("name"=>"Home","value"=>"Home");
    $fields = array (
        "to"=>"/topics/".$request->topic,
        "notification"=>Array("title"=>$request->title,"body"=> $request->description,"image"=>''),

        "android"=>Array("click_action"=>"FLUTTER_NOTIFICATION_CLICK"),
        'data' => $data,

    );

    //header includes Content type and api key
    $headers = array(
        'Content-Type:application/json',
        'Authorization:key='.$api_key
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);

    if ($result === FALSE) {
        die('FCM Send Error: ' . curl_error($ch));
    }
    curl_close($ch);
    $result=json_decode($result);
  return Back()->with("success","Push Notification Send Successfully");


    }
}
