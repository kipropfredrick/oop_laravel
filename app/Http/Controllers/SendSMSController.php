<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use AfricasTalking\SDK\AfricasTalking;
use Exception;

class SendSMSController extends Controller
{
    public static function sendMessage($recipients,$message){

        $username   = "Bukuswift";
        $apiKey     = env('AT_API_KEY');

        // Initialize the SDK
        $AT  = new AfricasTalking($username, $apiKey);

        // Get the SMS service
        $sms        = $AT->sms();
   
    //    $from       = "Mosmos";

        try {
            // Thats it, hit send and we'll take care of the rest
            $result = $sms->send([
                'to'      => $recipients,
                // 'from'=>$from,
                'message' => $message,
            ]);

            // return array($result);

        } catch (Exception $e) {
            \Log::info('SMS ERROR =>'.print_r($e->getMessage(),1));
        }
    }
}
