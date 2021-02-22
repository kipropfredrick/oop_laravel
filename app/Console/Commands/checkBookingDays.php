<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use Illuminate\Support\Facades\Log;

class checkBookingDays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'checkdays:changestatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks Number of days since a booking was made and updates status';

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

        Log::info('Check Days Cron running');

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','pending')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){

            $product = \App\Products::find( $booking->product_id);

            if($product !=null){
                if($product->agent_id !=null){

                    $agent = \App\Agents::where('id','=',$product->agent_id)->first();
            
                    $agent_code = $agent->agent_code;
    
                    \App\Bookings::where('id',$booking->id)->update(['agent_code'=>$agent_code]);
            
                }elseif($product->vendor_id !=null){
        
                    $vendor = \App\Vendor::where('id','=',$product->vendor_id)->first();
        
                    $vendor_code = $vendor->vendor_code;
    
                    \App\Bookings::where('id',$booking->id)->update(['vendor_code'=>$vendor_code]);
        
                }elseif($product->influencer_id !=null){
    
                    $influencer = \App\Influencer::where('id','=',$product->influencer_id)->first();

                    $influencer_code = $influencer->code;
    
                    \App\Bookings::where('id',$booking->id)->update(['influencer_code'=>$influencer_code]);
                    
                    }
        
            }

            $now = now();

            $startTimeStamp = strtotime($booking->created_at);
            $endTimeStamp = strtotime($now);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff/86400;  // 86400 seconds in one day

            // and you might want to convert to integer
            $numberDays = intval($numberDays);

            $latestPayment = \App\Payments::where('booking_id',$booking->id)->latest()->first();

            $latestPaymentTime = strtotime($booking->created_at);

            $now = strtotime($now);

            $timeDiff = abs($now - $latestPaymentTime);

            $numberDaysP = $timeDiff/86400;

            if($numberDays>= 90 && $booking->status != "complete"){
                Log::info('More than or equal to 90 days');
                \App\Bookings::where('id','=',$booking->id)->update(['status'=>'overdue']);

            }elseif($latestPayment !=null && $numberDaysP>= 30 && $numberDaysP < 90 && $booking->status != "complete"){
                Log::info('More than or equal to 90 days');
                \App\Bookings::where('id','=',$booking->id)->update(['status'=>'unserviced']);
            }else{
                Log::info('Less than 90 days');
            }
        }
    }
}
