<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Log;
use \App\Mail\SendNotificationMail;
use Illuminate\Support\Facades\Mail;

class sendReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendReminders:sendMessage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will send payment reminders to users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $username   = "Combinesms";
        $apiKey     = "cf56a93a37982301267fd00af0554c068a4efeb005213e568278c9492152ca28";

        $AT  = new AfricasTalking($username, $apiKey);

        // Get the SMS service
        $sms        = $AT->sms();

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','pending')->orderBy('id', 'DESC')->get();

        Log::info('Cron Job Running, Data');
        
        if($bookings !== null){
            
         $reciepientsArray = [];

        foreach($bookings as $booking){

        $reciepients = $booking->customer->phone;

        // Set your message

        $message    = "You had attempted to make a booking of ".$booking->product->product_name." on combine.co.ke, The Product Price is  : KES ".number_format($booking->total_cost,2)." If you are still intrested, Go to Mpesa , Select Paybill Enter : 4029165 and Account Number : ".$booking->booking_reference.", Enter any amount you wish to pay. Terms & Conditions Apply";

        Log::info('Message : ' .print_r($message,true));

        // Set your shortCode or senderId
        $from = "COMBINE";


        try {
            // Thats it, hit send and we'll take care of the rest
            $result = $sms->send([
                'to'      => $reciepients,
                'from'=>$from,
                'message' => $message,
            ]);

            // return array($result);

        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
         }

        }


        $details  = [
            'customer_name'=>$booking->customer->user->name,
            'product_name'=>$booking->product->product_name,
            'booking_reference'=>$booking->booking_reference,
            'total_cost'=>number_format($booking->total_cost,2)
        ];

        Mail::to($booking->customer->user->email)->send(new SendNotificationMail($details));

        }
    }
}
