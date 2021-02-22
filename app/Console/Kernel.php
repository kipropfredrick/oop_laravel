<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('sendReminders:sendMessage')->monthly();
        $schedule->command('checkdays:changestatus')->daily();

        $schedule->call(function () {

        \Log::info('Running booking update command');

        $bookings = \App\Bookings::with('product')->where('agent_code',NULL)->orWhere('vendor_code',NULL)->get();

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
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
