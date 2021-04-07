<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Log;
use \App\Mail\SendNotificationMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SendSMSController;

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
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','pending')->orderBy('id', 'DESC')->get();

        Log::info('Cron Job Running, Data');
        
        if($bookings !== null){
            
         $reciepientsArray = [];

        foreach($bookings as $booking){

        $recipients = $booking->customer->phone;

        $message = "Complete your booking for ".$booking->product->product_name.". Paybill 4040299 and account number ".$booking->booking_reference.". Total amount is KSh.".$booking->booking_reference.". You can pay a minimum of KSh.100.";

        SendSMSController::sendMessage($recipients,$message,$type="payment_reminder");
        
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
