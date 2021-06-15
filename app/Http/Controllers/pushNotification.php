<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class pushNotification extends Controller
{
    //

    function testFcm(Request $request){
    	 //API URL of FCM
    $url = 'https://fcm.googleapis.com/fcm/send';

    /*api_key available in:
    Firebase Console -> Project Settings -> CLOUD MESSAGING -> Server key*/  
    $token="fAXoLwhYTPGowm9zgi_IOq:APA91bEe7VkO1SqjwF5wWJRWivf89UpL5riCh3DIFBfDYfpll6LT9aNi2PCjAXwnYG8NYOlXmo5NT3FWpglnIyJYPb7OsCQjpa6GlbWP8D7t9DblX-6IVSm47ehE52Ho4iTIBdbDQH9b";  
    $api_key = 'AAAABjRMKq4:APA91bF7kJTVRuQzw_9H8rbYSUDhqDAa6Jtm15undJXta74F20BCUdyLjPIXw338GHL3nFqlmhNcPhqwva9YFMGvS0fs0q3yJdkTz6yXxZCJ70vJJeNl6_v3LWCizNta6d9zFFScM9UB';
    // $ret= Array("message"=>Array("token"=>$token,"notification"=>Array("title"=>"hq","body"=>"hq1","image"=>""),"android"=>Array("notification"=>Array("click_action"=>"FLUTTER_NOTIFICATION_CLICK")),"data"=>$datamess));
                
    $fields = array (
        'registration_ids' => array (
               $token
        ),
        "notification"=>Array("title"=>"Discover Business Near You.","body"=> "Check out this test message","image"=>''),

        "android"=>Array("click_action"=>"FLUTTER_NOTIFICATION_CLICK"),
        'data' => Array("name"=>"brian"),

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

    if (($result->failure)>0) {
      # code...
      $error=$result->results[0]->error;
       return Array("data"=>$error,"message"=>"Unable to Send Push Notification","response"=>"Success");
    }
    else{

  return Array("data"=>$result,"message"=>"Push Notification Send Successfully","response"=>"Success");
    }
    }
}
