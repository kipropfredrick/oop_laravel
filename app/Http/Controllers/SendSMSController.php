<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;
use Exception;

class SendSMSController extends Controller
{
    public static function sendMessage($recipients,$message,$type){

        $username   = "Bukuswift";
        $apiKey     = env('AT_API_KEY');

        $data = [];

        // Initialize the SDK
        $AT  = new AfricasTalking($username, $apiKey);

        // Get the SMS service
        $sms        = $AT->sms();
   
       $from       = "RDFYNE";

        try {
            // Thats it, hit send and we'll take care of the rest
            $result = $sms->send([
                'to'      => $recipients,
                'from'=>$from,
                'message' => $message,
            ]);

            $data['receiver'] = $recipients;
            $data['message'] = $message;
            $data['type'] = $type;
            $data['cost'] = $result['data']->SMSMessageData->Recipients[0]->cost;
            $data['status'] =$result['status'];
            $data['created_at'] = now();
            $data['updated_at'] = now();

            \DB::table('s_m_s_logs')->insert($data);

        } catch (Exception $e) {
            \Log::info('SMS ERROR =>'.print_r($e->getMessage(),1));
        }

        


    }
}
