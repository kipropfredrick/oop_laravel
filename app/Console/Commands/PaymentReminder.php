<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\SendSMSController;

class PaymentReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sendPaymentReminders:sendCustomerPaymentReminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command checks for pending payments and sends the respective customers messages if they have not paid with 48 hours of booking placement.';

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

        foreach($bookings as $booking){
            $now = now();

            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $now);
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $booking->created_at);
            $diff_in_hours = $to->diffInHours($from);

            \Log::info('Hours => '.$diff_in_hours);

            if($diff_in_hours == 48){

                $recipients = $booking->customer->phone;
                $message = "Complete your booking for ".$booking->product->product_name.". Paybill 4040299 and account number [order-id]. Total amount is KSh.".$booking->booking_reference.". You can pay a minimum of KSh.500.";

                SendSMSController::sendMessage($recipients,$message);
            }


        }

        $activeBookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','active')->orderBy('id', 'DESC')->get();
        
        foreach($activeBookings as $booking){

            $latestpayment = \DB::table('payments')->where('booking_id',$booking->id)->latest()->first();

            if($latestpayment!=null){
                $now = now();

            $to = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $now);
            $from = \Carbon\Carbon::createFromFormat('Y-m-d H:s:i', $latestpayment->created_at);
            $diff_in_weeks = $to->diffInWeeks($from);

            \Log::info('weeks => '.$diff_in_weeks);

            if($diff_in_weeks == 3){
                $recipients = $booking->customer->phone;
                $message =  "Hello, it’s been a while. Keep paying for ".$booking->product->product_name.". Paybill 4040299 and account number ".$booking->booking_reference.". Amount is KSh.".number_format($booking->balance).".";
                SendSMSController::sendMessage($recipients,$message);
            }
            }

        }
        
    }
}
