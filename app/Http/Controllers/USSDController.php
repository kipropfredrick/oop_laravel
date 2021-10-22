<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use Storage;
use Carbon\Carbon;
use Hash;
use AfricasTalking\SDK\AfricasTalking;

use File;
use Exception;
use App\Http\Controllers\SendSMSController;
use Illuminate\Support\Facades\Mail;
use \App\Mail\SendRegistrationEmail;
use App\Http\Controllers\pushNotification;
use \App\Mail\SendPaymentEmail;
use App\Mail\SendPaymentMailToAdmin;
use App\Mail\SendBookingMail;
use Illuminate\Support\Str;


class USSDController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    private function roundToTheNearestAnything($value, $roundTo)
    {
        $mod = $value%$roundTo;
        return $value+($mod<($roundTo/2)?-$mod:$roundTo-$mod);
    }


   
    public function sessions(){
        
        // Log::info("CALLBACK FROM AFRICASTALKING");

            $sessionId   = $_POST["sessionId"];
            $serviceCode = $_POST["serviceCode"];
            $phoneNumber = $_POST["phoneNumber"];
            $text        = $_POST["text"];
             Log::info($text);

            $ussd_string_exploded = explode("*", $text);
            
            $level = count($ussd_string_exploded);

            $valid_phone = ltrim($phoneNumber, '+');

            $existingVendor = \App\Vendor::where('phone','=',$valid_phone)->first();

            $isvendor=false;
            if (\App\Vendor::where('phone','=',$valid_phone)->count() > 0) {
$isvendor=true;
                # code...
            }

           
            if ($text == "" && $existingVendor === null ) {
                   
                    Log::info('Not Agent');
                    
                    $response  = "CON Welcome to Lipa Mos Mos \n";
                    $response .= "1. Place an order \n";
                    $response .= "2. Make a payment \n";
                    $response .= "3. Check balance";
                    
                }
        elseif($text == "" && $isvendor){

                $existingVendor = \App\Vendor::where('phone','=',$valid_phone)->first();

                Log::info('Is Vendor : '.$text);

                $response  = "CON Welcome to Lipa Mos Mos \n";
                $response .= "1. New direct booking\n";
                $response .= "2. New product booking \n";
                 $response .= "3.  Make payment \n";
                $response .= "4. Exchange order \n";
                $response .= "5. Check balance \n";
               
               
           
        }
        else if ($ussd_string_exploded[0] == 3 && $level==1 && !$isvendor) {
                
     if ($level==1) {
         # code...
           $response = "CON Enter Booking Reference.";
     }

    }else if ($ussd_string_exploded[0] == 3  && $level == 2 && !$isvendor) {

        $booking_reference = $ussd_string_exploded[1];

        $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->first();

        if($booking === null){
            $response = "END Booking Reference Entered does not exist.";
        }else{
 

  $response = "END Balance for booking reference ".$ussd_string_exploded[1]. " is KSh.".number_format($booking->balance) ;

        }

    }

     else if ($ussd_string_exploded[0] == 5 && $level==1 && $isvendor) {
                
     if ($level==1) {
         # code...
           $response = "CON Enter Booking Reference.";
     }

    }else if ($ussd_string_exploded[0] == 5  && $level == 2 && $isvendor) {

        $booking_reference = $ussd_string_exploded[1];

        $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->first();

        if($booking === null){
            $response = "END Booking Reference Entered does not exist.";
        }else{
 

  $response = "END Balance for booking reference ".$ussd_string_exploded[1]. " is KSh.".number_format($booking->balance) ;

        }

    }




  else if ($ussd_string_exploded[0] == 2 && !$isvendor && $level==1) {
   list($msisdn, $network) = $this->get_msisdn_network($phoneNumber);
$customer=\App\Customers::wherePhone($msisdn)->first();
if ($customer==null) {
    # code...
      $response = "END You have not registered with lipa mosmos."; 
}
else{


        $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','revoked','unserviced','pending'])->first();

        if($booking === null){
            $response = "END You do not have existing active booking.";
        }else{
 

  $response = "CON Enter Phone Number (making payment)" ;

        }


    }

     

            }


  else if ($ussd_string_exploded[0] == 2 && !$isvendor && $level==2) {

        list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);

        if (!$msisdn){
$message="END Please enter a valid phone number provided!";
        }else{
            $valid_phone = $msisdn;
  list($msisdn, $network) = $this->get_msisdn_network($phoneNumber);
$customer=\App\Customers::wherePhone($msisdn)->first();

        $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','revoked','unserviced','pending'])->first();
                
                if($booking == null){
                 $response = "END You  have no active booking.";
                }else{
                  $product = \App\Products::where('id','=',$booking->product_id)->first();
                  $booking_reference = $booking->booking_reference;
                  $response = "CON Booking ref ".$booking_reference.".  You have paid KSh.".number_format($booking->amount_paid,2).", your balance is KSh. ".number_format($booking->balance,2)."\nEnter Amount to pay.";
                }

             
              }

            }


  else if ($ussd_string_exploded[0] == 2  && $level == 3 && !$isvendor) {

              
                 $response  = "CON Choose payment option \n";
                $response .= "1. M-Pesa \n";
                $response .= "2. Airtel Money \n";
        
            }
            else if($ussd_string_exploded[0] == 2  && $level == 4 && !$isvendor){

                  $amount = $ussd_string_exploded[2];
                  $paymentfrom= $ussd_string_exploded[3];
    Log::info('AMOUNT : '.print_r($amount,true));

      list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);
      $customer=\App\Customers::wherePhone($msisdn)->first();

        $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','revoked','unserviced','pending'])->first();

        $booking_ref=$booking->booking_reference;
             
if ($paymentfrom==1) {
    # code...
   

                $message = $this->stk_push($amount,$msisdn,$booking_ref);
                
                $response = $message;
}
else{
   
                $message = $this->AirtelussdPush($amount,$msisdn,$booking_ref);
                
                $response = $message;
}
             

            }






        else if ($ussd_string_exploded[0] == 3 && $isvendor) {
            if ($level==1) {
                 $response = "CON Enter Booking reference.";
                # code...
            }
                   if ($level==2) {
                       # code...
$booking = \App\Bookings::where('booking_reference','=',$ussd_string_exploded[1])->whereIn('status',['active','pending','revoked','unservices'])->first();
if ($booking==null) {
    # code...
    $response="END Booking reference does not exist";
}
else{
    $response = "CON Enter customer Phone Number.";
}
                     
                   }
                
               if ($level==3) {
                  list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[2]);

        if (!$msisdn){
$message="END Please enter a valid phone number provided!";
        }else{
            $valid_phone = $msisdn;
        }


Log::info("test 1");
                $customer = \App\Customers::where('phone','=',$valid_phone)->first();
               $booking = \App\Bookings::where('booking_reference','=',$ussd_string_exploded[1])->whereIn('status',['active','pending','revoked','unservices'])->first();
                
                if($booking == null){
                 $response = "END You  have no active booking.";
                }else{
                  $product = \App\Products::where('id','=',$booking->product_id)->first();
                  $booking_reference = $booking->booking_reference;
                  $response = "CON Booking ref ".$booking_reference.".  You have paid KSh.".number_format($booking->amount_paid,2).", your balance is KSh. ".number_format($booking->balance,2)."\nEnter Amount to pay.";                }

             
                   # code...
               }
               if ($level==4) {
                   # code...
                 $response  = "CON Choose payment option \n";
                $response .= "1. M-Pesa \n";
                $response .= "2. Airtel Money \n";
        
            
               }

               if ($level==5) {
                   # code...
                                  $amount = $ussd_string_exploded[3];
                  $paymentfrom= $ussd_string_exploded[4];

         
        list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[2]);

        if (!$msisdn){
$message="END Phone number entered is invalid!";
        }else{
            $valid_phone = $msisdn;
        }

               $booking = \App\Bookings::where('booking_reference','=',$ussd_string_exploded[1])->whereIn('status',['active','pending','revoked','unservices'])->first();
                $booking_ref = $booking->booking_reference;

if ($paymentfrom==1) {
    # code...
       Log::info('AMOUNT : '.print_r($amount,true));

     

                $message = $this->stk_push($amount,$msisdn,$booking_ref);
                
                $response = $message;
}
else{
  
                $message = $this->AirtelussdPush($amount,$msisdn,$booking_ref);
                
                $response = $message;
}
               }

            }



          
             else if ($ussd_string_exploded[0]==1 && !$isvendor){
if ($level==1) {
    # code...
        $response  = "CON Enter Product code \n";
}
               
if ($ussd_string_exploded[0]==1 && $level==2 && !$isvendor) {
    # code...
    //check if product exists
$product_code=$ussd_string_exploded[1];
       $product = \App\Products::where('product_code','=',$product_code)->first();

            if($product === null){
            $response = "END Product Code Entered does not exist.";
            }
            else{
                $phone = ltrim($phoneNumber, '0');
                $phone=substr($phone, 1);

                $customer = \App\Customers::where('phone','=',$phone)->first();
  $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending','unserviced','overdue'])->first();
                if($booking == null){
                              $response  = "CON  You are making a booking for ".$product->product_name."\nEnter Initial Deposit (Minimum KSh.100) \n";

            }
//check booking
    
                else{
 $response = "END You already have an existing booking. You can't place another order."; 

                }





            }
        }
          else if($ussd_string_exploded[0]==1 && $level==3 && !$isvendor){
             $response  = "CON Choose payment option \n";
                $response .= "1. M-Pesa \n";
                $response .= "2. Airtel Money \n";
}
          else if($ussd_string_exploded[0]==1 && $level==4 && !$isvendor){
   
          # code...
          $phone = ltrim($phoneNumber, '0');
                $phone=substr($phone, 1);

                $customer = \App\Customers::where('phone','=',$phone)->first();
                Log::info(json_encode($customer));
                 Log::info(json_encode($phone));
                $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending','unserviced','overdue'])->first();
$product_code=$ussd_string_exploded[1];
       $product = \App\Products::where('product_code','=',$product_code)->first();
       $vendor=\App\Vendor::whereId($product->vendor_id)->first();
                if($booking == null){
                    $request=(object) Array();
                    $request->county_id=2;
                    $request->exact_location='';
                    $request->phone=$phoneNumber;
                    $request->initial_deposit=$ussd_string_exploded[2];
                    $request->product_id=$product->id;
                    $request->vendor_code=$vendor->vendor_code;
   if ($ussd_string_exploded[3]==1) {
 $response = $this->make_booking($request); 
}
else{
    $response = $this->make_booking($request,$payment_type="airtel");  
}
Log::info("executed 1");
      }
      else{
         $response = "END You already have an existing booking. You can't place another order."; 

      }

              
          


}

    }


    else if ($ussd_string_exploded[0]==2 && $isvendor){

   if($level==1){

     $response = "CON Enter customer Phone Number.";


         } 


list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);
$customer = \App\Customers::where('phone','=',$msisdn)->first();

if ($customer==null) {
    # code...
if ($level==2) {
    # code...
      $response = "CON Enter Enter customer Full Name.";
}

if ($level==3) {
    # code...
        $response  = "CON Enter Product code \n";
}
               
if ($ussd_string_exploded[0]==2 && $level==4) {
    # code...
    //check if product exists
$product_code=$ussd_string_exploded[3];
       $product = \App\Products::where('product_code','=',$product_code)->first();

            if($product === null){
            $response = "END Product Code Entered does not exist.";
            }
            else{
            list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);

                $customer = \App\Customers::where('phone','=',$msisdn)->first();
  $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending','unserviced','overdue'])->first();
                if($booking == null){
                              $response  = "CON  You are making a booking for ".$product->product_name."\nEnter Initial Deposit (Minimum KSh.100) \n";

            }
//check booking
    
                else{
 $response = "END Customer has already have an existing booking. You can't place another order."; 

                }





            }
        }
       
       else if($level==5){
       $response  = "CON Choose payment option \n";
                $response .= "1. M-Pesa \n";
                $response .= "2. Airtel Money \n";
}

          else if($level==6){
if ($ussd_string_exploded[5]==1) {
    # code...
    list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);
                $customer = \App\Customers::where('phone','=',$msisdn)->first();
            
                $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending','unserviced','overdue'])->first();
$product_code=$ussd_string_exploded[3];
       $product = \App\Products::where('product_code','=',$product_code)->first();
       $vendor=\App\Vendor::whereId($product->vendor_id)->first();
                if($booking == null){
                    $request=(object) Array();
                    $request->county_id=2;
                    $request->exact_location='';
                    $request->phone=$msisdn;
                    $request->initial_deposit=$ussd_string_exploded[4];
                    $request->name=$ussd_string_exploded[2];
                    $request->product_id=$product->id;
                    $request->vendor_code=$vendor->vendor_code;

 $response = $this->make_booking($request); 
Log::info("executed 1");
            }
                 else{
               Log::info("executed 2");     
 $response = "END Customer has already have an existing booking. You can't place another order."; 

                }
}
else{
$response="END payment method not yet supported";

}


}



}
else{



if ($level==2) {
    # code...
        $response  = "CON Enter Product code \n";
}
               
if ($ussd_string_exploded[0]==2 && $level==3) {
    # code...
    //check if product exists
$product_code=$ussd_string_exploded[2];
       $product = \App\Products::where('product_code','=',$product_code)->first();

            if($product === null){
            $response = "END Product Code Entered does not exist.";
            }
            else{
            list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);

                $customer = \App\Customers::where('phone','=',$msisdn)->first();
  $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending','unserviced','overdue'])->first();
                if($booking == null){
                              $response  = "CON  You are making a booking for ".$product->product_name."\nEnter Initial Deposit (Minimum KSh.100) \n";

            }
//check booking
    
                else{
 $response = "END Cujstomer has already have an existing booking. You can't place another order."; 

                }





            }
        }
       
else if($level==4){
       $response  = "CON Choose payment option \n";
                $response .= "1. M-Pesa \n";
                $response .= "2. Airtel Money \n";
}
          else if($level==5){

            if ($ussd_string_exploded[4]==1) {
                # code...
                list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);
                $customer = \App\Customers::where('phone','=',$msisdn)->first();
            
                $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending','unserviced','overdue'])->first();
$product_code=$ussd_string_exploded[2];
       $product = \App\Products::where('product_code','=',$product_code)->first();
       $vendor=\App\Vendor::whereId($product->vendor_id)->first();
                if($booking == null){
                    $request=(object) Array();
                    $request->county_id=2;
                    $request->exact_location='';
                    $request->phone=$msisdn;
                    $request->initial_deposit=$ussd_string_exploded[3];
                    $request->product_id=$product->id;
                    $request->vendor_code=$vendor->vendor_code;

 $response = $this->make_booking($request); 
Log::info("executed 1");
            }
                 else{
               Log::info("executed 2");     
 $response = "END Customer has already have an existing booking. You can't place another order."; 

                }
            }
            else{
                $response="END payment method not yet supported";
            }



}
}

    }

else if($ussd_string_exploded[0]==4 && $isvendor){
$response="CON Enter booking reference";

if ($level==2) {
    $booking = \App\Bookings::whereBooking_reference($ussd_string_exploded[1])->whereIn('status',['active','pending','unserviced','overdue'])->first();

    if ($booking==null) {
        # code...
        $response="END no booking reference found";
    }

    else{


        $response="CON Enter new product code";

    }

}

if ($level==3) {
    $booking = \App\Bookings::whereBooking_reference($ussd_string_exploded[1])->whereIn('status',['active','pending','unserviced','overdue'])->first();
  $product_name = \App\Products::where('id','=',$booking->product_id)->first()->product_name;

  $product= \App\Products::where('product_code','=',$ussd_string_exploded[2])->first();
  if ($product==null) {
      # code...
    $response="END Product Code Entered does not exist.";
  }
  else{
$response="CON Confirm to exchange product from ".$product_name." to ".$product->product_name." \n";
     $response .= "1. Confirm \n";
    $response .= "2. Cancel \n";

  }


    # code...
}
if ($level==4) {
    # code...
    if ($ussd_string_exploded[3]==2) {
        # code...
        $response="END thank you for partnering with us";
    }
    else if($ussd_string_exploded[3]==1){
        $booking = \App\Bookings::whereBooking_reference($ussd_string_exploded[1])->whereIn('status',['active','pending','unserviced','overdue'])->first();

     $request=(object) Array();
     $request->product_code=$ussd_string_exploded[2];
     $request->amount=$booking->amount_paid;
   $response=$this->transfer_orderID($ussd_string_exploded[1],$request);
    }
    else{
        $response="END invalid choice try again";
    }
}




}
else if($isvendor && $ussd_string_exploded[0]==1){


    if ($level==1) {
        # code...
        $response="CON Enter Customer Phone Number";


    }

$categories=\App\Categories::get();
if ($level>1) {
   list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);
$customer=\App\Customers::wherePhone($msisdn)->first();
if ($customer==null) {
    # code...
   //register user
if ($level==3) {
    # code...
    $response="CON Enter Customer Full Name";
}

if ($level==4) {
           $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','revoked','unserviced','pending'])->first();

        if($booking != null){
            $response = "END Customer has an existing booking.";
        }else{
 

 

        }
         # code...
     }

     if ($level==5) {
         # code...
            # code...
    $response="CON Select category\n";
    $index=1;
 foreach ($categories as $key => $value) {
 
     $response .= "{$index}. {$value->category_name} \n";
$index=$index+1;
 }
     }

     if ($level==6) {
         # code...
         $value1=$ussd_string_exploded[5]-1;
    $category_id=0;
foreach ($categories as $key => $value) {
    # code...
    if ($key==$value1) {
        # code...
        $category_id=$value->id;
    }

}

$subcategories=\App\SubCategories::whereCategory_id($category_id)->get();

$response="CON Select sub category\n";
       
$index=1;

foreach ($subcategories as $key => $value) {
    # code...

     $response .= "{$index}. {$value->subcategory_name} \n";
$index=$index+1;
 

}

     }
     if ($level==7) {

        $response="CON Enter product name";
         # code...
     }
         if ($level==8) {
         $response="CON Enter Initial Deposit (Minimum KSh.100)";
    }

     if ($level==9) {
         # code...
          $response  = "CON Choose payment option \n";
                $response .= "1. M-Pesa \n";
                $response .= "2. Airtel Money \n";
     }

     if ($level=9) {
         # code...
if ($ussd_string_exploded[9]==1) {
    # code...
    $response="END pay with mpesa";
}
else{
        $response="END pay with airtel";
}

     }


}
else{


     if ($level==3) {
           $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','revoked','unserviced','pending'])->first();

        if($booking != null){
            $response = "END Customer has an existing booking.";
        }else{
 

 

        }
         # code...
     }

     if ($level==4) {
         # code...
            # code...
    $response="CON Select category\n";
    $index=1;
 foreach ($categories as $key => $value) {
 
     $response .= "{$index}. {$value->category_name} \n";
$index=$index+1;
 }
     }

     if ($level==5) {
         # code...
         $value1=$ussd_string_exploded[4]-1;
    $category_id=0;
foreach ($categories as $key => $value) {
    # code...
    if ($key==$value1) {
        # code...
        $category_id=$value->id;
    }

}

$subcategories=\App\SubCategories::whereCategory_id($category_id)->get();

$response="CON Select sub category\n";
       
$index=1;

foreach ($subcategories as $key => $value) {
    # code...

     $response .= "{$index}. {$value->subcategory_name} \n";
$index=$index+1;
 

}

     }
     if ($level==6) {

        $response="CON Enter product name";
         # code...
     }
         if ($level==7) {
         $response="CON Enter Initial Deposit (Minimum KSh.100)";
    }

     if ($level==8) {
         # code...
          $response  = "CON Choose payment option \n";
                $response .= "1. M-Pesa \n";
                $response .= "2. Airtel Money \n";
     }

     if ($level=9) {
         # code...
if ($ussd_string_exploded[8]==1) {
    # code...
    $response="END pay with mpesa";
}
else{
        $response="END pay with airtel";
}

     }


    }





}



//end 



}


    header('Content-type: text/plain');
    echo $response;

 }

    private function get_msisdn_network($msisdn){
        $regex =  [
                'airtel' =>'/^\+?(254|0|)7(?:[38]\d{7}|5[0-6]\d{6})\b/',
                'equitel' => '/^\+?(254|0|)76[0-7]\d{6}\b/',
                'safaricom' => '/^\+?(254|0|)(?:7[01249]\d{7}|1[01234]\d{7}|75[789]\d{6}|76[89]\d{6})\b/',
                'telkom' => '/^\+?(254|0|)7[7]\d{7}\b/',
            ];

            foreach ($regex as $operator => $re ) {
                if (preg_match($re, $msisdn)) {
                    return [preg_replace('/^\+?(254|0)/', "254", $msisdn), $operator];
                }
            }
            return [false, false];
        }



      public function make_booking($request, $payment_type="mpesa"){
        $county_id = $request->county_id;
        $exact_location = $request->exact_location;
        $vendor_code = $request->vendor_code;

        $vendor=\App\Vendor::whereId($vendor_code)->first();
        if ($vendor!=null) {
            $vendor_code=$vendor->vendor_code;
            # code...
        }
        else{
            $vendor="VD1";
        }

        $categories = \App\Categories::all();

        list($msisdn, $network) = $this->get_msisdn_network($request->phone);

        if (!$msisdn){
$message="END Please enter a valid phone number provided!";
        }else{
            $valid_phone = $msisdn;
        }
        //Valid email
        // $valid_email = preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/", $request->email, $e_matches);

        $product = \App\Products::find($request->product_id);

        if($product->weight != 0){
            $weight_array = preg_split('#(?<=\d)(?=[a-z])#i', $product->weight);
        }else{
            $weight_array = (['0','g']);
        }
        // For Other counties
        $county = \App\Counties::find($request->county_id);

        $product_weight = $weight_array;

        if($product_weight[1] == 'g'){
            $shipping_cost = 500;
        }elseif($product_weight[1] == 'kg' && $product_weight[0]<=5){
            $shipping_cost = 500;
        }elseif($product_weight[1] == 'kg' && $product_weight[0]>5){
            $extra_kg = $product_weight[0] - 5;
            $extra_cost = (50 * $extra_kg);
            $shipping_cost = 500 + $extra_cost;
        }

        $total_cost = $product->product_price + $shipping_cost;

        $total_cost = $this->roundToTheNearestAnything($total_cost, 5);
        $customerdata=\App\Customers::wherePhone($msisdn)->first();
        $userid=null;

        if ($customerdata!=null) {
            # code...
            $userid=$customerdata->user_id;
        }
        else{
            $user = new \App\User();
        // $user->email = $email;
        $user->name = $request->name;
        $user->password = Hash::make(substr($valid_phone, 3));
        $user->save();

        $user_id = DB::getPdo()->lastInsertId();

        $customer = new \App\Customers();
        $customer->user_id = $user_id; 
        $customer->phone  = $msisdn;
        $customer->save();
$userid=$user_id;
        }

        $existingUser = \App\User::where('id',  $userid)->first();

        if($existingUser!=null)
        {

        $user = $existingUser;

        $existingCustomer = \App\Customers::where('user_id','=',$existingUser->id)->first();


        $booking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->whereNotIn('status', ['complete','revoked'])->first();

        if($booking!=null){
            return "END you already have an existing bookings";
        }

        \Auth::login($user);


        for($i=0;$i<1000000;$i++){
            $booking_reference = 'MM'.rand(10000,99999);
            $res=\App\Bookings::whereBooking_reference($booking_reference)->first();
            if ($res==null) {
                # code...
break;
            }

        }

        $booking_date = now();


        $due_date = Carbon::now()->addMonths(3);


        $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$request->product_id)->first();


        if($request->initial_deposit<100){

        // return Array("error"=>true,"response"=>"");

      return "END minimum deposit is Ksh. 100";

        }

$balance=0;
//$existingUser->balance;

$booking = new \App\Bookings();
 $recipients = $valid_phone;
if (intval($balance)==0) {
   $booking->balance =   $total_cost;
$booking->amount_paid = "0";
$booking->status = "pending";
}
else{

    if (intval($total_cost)<intval($balance)) {
        # code...
          \App\User::where('id',  $userid)->update(["balance"=>intval($balance)-intval($total_cost)]);
        $booking->status = "complete";
        $booking->amount_paid = $total_cost;
        $booking->balance="0";

         $message =  "Ksh ".$balance." from your mosmos wallet has been used fully pay your placed order";
    }
    else{
\App\User::where('id',  $userid)->update(["balance"=>0]);
        $booking->balance =   $total_cost-(intval($balance));
$booking->amount_paid = $balance;
$booking->status = "active";
 $message =  "Ksh ".$balance." from your mosmos wallet has been used to pay for ordered item partially remaining amount is Ksh.".number_format($total_cost-(intval($balance)));
    }



        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");
}


        $booking->customer_id = $existingCustomer->id;
        $booking->product_id  = $request->product_id;
        $booking->booking_reference = $booking_reference;
        $booking->platform="mobile";
        $booking->quantity  = '1';
        $booking->discount  = 0;
    if ($request->setreminder!= null) {
        $booking->setdate= $request->setdate;
    $booking->setreminder= $request->setreminder;
        # code...
    }
    else{
        $booking->setdate='2021-09-09';
    $booking->setreminder= 0;
    }

        $booking->item_cost = $product->product_price;

        $booking->payment_mode  = 'Mpesa';
        $booking->date_started  = now();
        $booking->due_date = $due_date;

        $booking->vendor_code = $vendor_code;
        $booking->location_type = "Exact Location";
        $booking->item_cost = $product->product_price;
        $booking->shipping_cost = $shipping_cost;
        $booking->county_id = $request->county_id;
        $booking->exact_location = $request->exact_location;
        $booking->referal_code=$request->referal_code;
        $booking->total_cost =  $total_cost;

        $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

        // $booking_reference = 'MM'.rand(10000,99999);
           for($i=0;$i<1000000;$i++){
            $booking_reference = 'MM'.rand(10000,99999);
            $res=\App\Bookings::whereBooking_reference($booking_reference)->first();
            if ($res==null) {
                # code...
break;
            }

        }

        \App\Bookings::where('id',$booking_id)->update(['booking_reference'=>$booking_reference]);


       $recipients = $valid_phone;

        $booking_id = DB::getPdo()->lastInsertId();

        $product = \App\Products::find($request->product_id);

        if ($payment_type=="mpesa") {
            # code...

     $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking_reference." and amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";
    $token=\App\User::whereId($existingCustomer->user_id)->first()->token;
    if ($token==null) {
        # code...
     
      return $message;
    }
    $obj = new pushNotification();
    $data=Array("name"=>"bookingsuccess","value"=>"Bookings");
    $obj->exceuteSendNotification($token,"You have successfully booked ".$product->product_name,"Booking Successful",$data);

    // $data=Array("name"=>"bookingsuccess","value"=>"Bookings");
    // $obj->exceuteSendNotification($token,"You have received KSh.100 from us. Thanks for your order","Congratulations! ",$data);

      return $message;

        }else{


     $message =  "Please Complete your booking. Your account number is ".$booking_reference." and amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $message = $this->AirtelussdPush($amount,$msisdn,$booking_ref);

        
    $token=\App\User::whereId($existingCustomer->user_id)->first()->token;
    if ($token==null) {
        # code...
     
      return $message;
    }
    $obj = new pushNotification();
    $data=Array("name"=>"bookingsuccess","value"=>"Bookings");
    $obj->exceuteSendNotification($token,"You have successfully booked ".$product->product_name,"Booking Successful",$data);

    // $data=Array("name"=>"bookingsuccess","value"=>"Bookings");
    // $obj->exceuteSendNotification($token,"You have received KSh.100 from us. Thanks for your order","Congratulations! ",$data);

      return $message;


        }

   

        }


        $existingCustomer = \App\Customers::where('phone','=',$valid_phone)->first();

        if($existingCustomer)
        {

        // $booking_reference = 'MM'.rand(10000,99999);
               for($i=0;$i<1000000;$i++){
            $booking_reference = 'MM'.rand(10000,99999);
            $res=\App\Bookings::whereBooking_reference($booking_reference)->first();
            if ($res==null) {
                # code...
break;
            }

        }

        $booking_date = now();

        $$booking_date = strtotime($booking_date);

        $product = \App\Products::find($request->product_id);

       $due_date = Carbon::now()->addMonths(3);

        if($request->initial_deposit<100){

    return "END minimum deposit is Ksh. 100";

        }

        $booking = new \App\Bookings();
        $booking->customer_id = $existingCustomer->id;
        $booking->product_id  = $request->product_id;
        $booking->county_id = $request->county_id;
        $booking->exact_location = $exact_location;
        $booking->booking_reference = $booking_reference;
            if ($request->setreminder!= null) {
          $booking->setdate= $request->setdate;
        $booking->setreminder= $request->setreminder;
          # code...
      }
      else{
           $booking->setdate='2021-09-09';
        $booking->setreminder= 0;
      }
        $booking->quantity  = "1";
        $booking->amount_paid = "0";
        $booking->balance = intval($total_cost);
        $booking->item_cost = $product->product_price;
        $booking->shipping_cost = $shipping_cost;
        $booking->payment_mode  = 'Mpesa';
        $booking->vendor_code = $vendor_code;
        $booking->date_started  = now();
        $booking->due_date = $due_date;
        $booking->discount=0;
        $booking->status = "pending";
        $booking->referal_code=$request->referal_code;
        $booking->total_cost = intval($total_cost);
        $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

        $recipients = $valid_phone;

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $product = \App\Products::find($request->product_id);

        $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking_reference." And amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";


   $token=\App\User::whereId($existingCustomer->user_id)->first()->token;
    if ($token==null) {
        # code...
        return $message;
    }
    $obj = new pushNotification();
    $data=Array("name"=>"bookingsuccess","value"=>"Bookings");
    $obj->exceuteSendNotification($token,"You have successfully booked ".$product->product_name,"Booking Successful",$data);

        return $message;

        }


    }

      public function transfer_orderID($id,$request){

        $booking = \App\Bookings::whereBooking_reference($id)->first();

        $product = \App\Products::find($booking->product_id);



        if($product->product_code == $request->product_code){
            return 'END You cannot exchange with the same item';
        }

        $newProduct = \App\Products::where('product_code',$request->product_code)->where('status','=','approved')->first();

// $user_id=Auth()->user()->id;
// $vendor_id=\App\Vendor::whereUser_id($user_id)->first()->id;
// if ($vendor_id!=$newProduct->vendor_id) {
//     # code...
//     return back()->with('error','You are not authorized to make this order transfer');
// }






        if($newProduct == null){
            return 'END Sorry Product Code does not exist.';
        }

            if($newProduct->weight != 0){
                $weight_array = preg_split('#(?<=\d)(?=[a-z])#i', $newProduct->weight);
            }else{
                $weight_array = (['0','g']);
            }

            $product_weight = $weight_array;

            if($product_weight[1] == 'g'){
                $shipping_cost = 500;
            }elseif($product_weight[1] == 'kg' && $product_weight[0]<=5){
                $shipping_cost = 500;
            }elseif($product_weight[1] == 'kg' && $product_weight[0]>5){
            $extra_kg = $product_weight[0] - 5;
            $extra_cost = (30 * $extra_kg);
            $vat = 0.16*$extra_cost;
            $shipping_cost = 500 + $extra_cost + $vat;
            }

        $total_cost = ($newProduct->product_price + $shipping_cost);

        $balance = $total_cost - $booking->amount_paid;
          $customer = \App\Customers::where('id',$booking->customer_id)->first();

       if ($balance>0) {
           # code...
         \App\Bookings::where('id','=',$booking->id)->update([
                        "product_id"=>$newProduct->id,
                        "balance"=>$balance,
                        "shipping_cost"=>$shipping_cost,
                        "item_cost"=>$newProduct->product_price,
                        "total_cost"=>$total_cost
                        ]);
       }
       else{
 \App\Bookings::where('id','=',$booking->id)->update([
                        "product_id"=>$newProduct->id,
                        "balance"=>0,
                        "shipping_cost"=>$shipping_cost,
                        "item_cost"=>$newProduct->product_price,
                        'status'=>"complete",
                        "total_cost"=>$total_cost
                        ]);

$objuser=\App\User::whereId($customer->user_id);
$firstobjuser=$objuser->first();
$totalbal=intval($firstobjuser->balance)+ ($balance *-1);
$objuser->update(['balance'=>$totalbal]);
       }

      

        $message = "Product exchanged successfully to ".$newProduct->product_name.". New Balance is KES ".number_format($balance,2).". Use Paybill 4040299 and Account Number ".$booking->booking_reference.". Thank you.";

        $recipients = $customer->phone;

        SendSMSController::sendMessage($recipients,$message,$type="booking_transfered_notification");

        $details = [
            'customer'=> $booking->customer->user->name,
            'booking_reference'=>$booking->booking_reference,
            'amount_paid'=>$request->amount,
            'product'=>$newProduct->product_name,
            'balance'=> $balance

        ];

       // Mail::to($booking->customer->user->email)->send(new SendOrderTransferedMail($details));

       return "END Product exchanged successfully";


    }


        public function book($ussd_string_exploded){

            
           
        }


    public function register($ussd_string_exploded){


    }

    public function sign_up($email,$name,$phone,$product_id,$agent_code,$deposit){

        $user = new \App\User();
        $user->email = $email;
        $user->name = $name;
        $user->password = Hash::make($phone);
        $user->save();

        $user_id = DB::getPdo()->lastInsertId();

        $customer = new \App\Customers();
        $customer->user_id = $user_id; 
        $customer->phone  = '254'.ltrim($phone, '0');
        $customer->save();

        $customer_id = DB::getPdo()->lastInsertId();

        $booking_date = now();

        $booking_date = strtotime($booking_date);

        $product = \App\Products::where('product_code','=',$product_id)->first();

        $due_date = Carbon::createFromTimestamp($booking_date)->addMonths(3);

        $booking_reference = 'BKG'.rand(10,1000000);

        if($product->product_price < 5000){
            $minDeposit = 0.2*$product->product_price;
        }else {
            $minDeposit = 0.1 *$product->product_price;
        }

        if($deposit<200){
            $response = "END Minimum Deposit for this product is  KES".number_format(200,2);
        }else {

        $booking = new \App\Bookings();
        $booking->customer_id = $customer_id; 
        $booking->product_id  = $product->id;
        $booking->booking_reference = $booking_reference;
        $booking->quantity  = "1";
        $booking->agent_code = $agent_code;
        $booking->balance = $product->product_price;
        $booking->amount_paid = '0';
        $booking->payment_mode  = 'Mpesa';
        $booking->status = "pending";
        $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
        $booking->due_date = $due_date;
        $booking->total_cost = $product->product_price;
        $booking->save();

        $reciepient = $phone;
        $AFmessage = "Booking of : ".$product->product_name." was successful. Total Price is KES ".number_format($product->product_price,2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, select Paybill Enter : (4040299) and Account Number : ".$booking_reference.", Enter Amount you wish to pay. Thank you. Terms & Conditions Apply";
        $this->sendMessage($AFmessage,$reciepient);

        $amount = $deposit;
        $msisdn = $phone;
        $booking_ref = $booking_reference;
 
        $consumer_key = 'jm2Grv0ww5WnP72EgVxaSAmXu9yHeOWd';
        $consume_secret = 'T3AbvwSCjky7IFx8';
        $headers = ['Content-Type:application/json','Charset=utf8'];
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_USERPWD,$consumer_key.':'.$consume_secret);

        $curl_response = curl_exec($curl);
        $result = json_decode($curl_response);

        $token = $result->access_token;

        $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        Log::info("Generated access token " . $token);

        $timestamp = date("YmdHis");

        $BusinessShortCode = '4040299';

        $passkey = "e16ba1623f2708b2ef89970fa0aa822ec95bf16fe1e4d36a57fc53d6840883b5";

        $lipa_time = Carbon::rawParse('now')->format('YmdHms');

        $apiPassword = $this->lipaNaMpesaPassword($lipa_time);

        // return response()->json($apiPassword);

        Log::info("Generated Password " . $apiPassword);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token)); //setting custom header

        $curl_post_data = array(

            'BusinessShortCode' => '4040299',
            'Password'          => $apiPassword,
            'Timestamp'         => $lipa_time,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $msisdn,
            'PartyB'            =>'4040299',
            'PhoneNumber'       => $msisdn,
            'CallBackURL'       => 'https://combine.co.ke/confirmation-url',
            'AccountReference'  => $booking_ref,
            'TransactionDesc'   => 'Combine Product Payment'
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        $responseArray = json_decode($curl_response, true);
        $status = 200;
        $success = true;
        $message = "STK Request Success";
        $httpCode = 200;

       

        if(array_key_exists("errorCode", $responseArray)){

            $status = 400;
            $success = false;
            $message = $responseArray["errorMessage"];
        }

        $out = [
                'status'  => $status,
                'success' => $success,
                'message' => $message,
                'response'=>$responseArray,
                'amount'=>$amount,
                'token'=>$token
            ];   

        return response()->json($out);

        }
        
    }

    public function test(){
        
        Log::info("CALLBACK FROM AFRICASTALKING");

            $sessionId   = $_POST["sessionId"];
            $serviceCode = $_POST["serviceCode"];
            $phoneNumber = $_POST["phoneNumber"];
            $text        = $_POST["text"];

            $ussd_string_exploded = explode("*", $text);
            $level = count($ussd_string_exploded);

            if ($text == "") {
                $response  = "CON Welcome to Fuliza Fuel \n";
                $response .= "1. Create an account \n";
                $response .= "2. Fuliza Fuel";

            } else if ($text == "1") {
                $response = "CON Select Account Type \n";
                $response .= "1. Group \n";
                $response .= "2. User";

            } else if ($text == "2") {
                
                $response = "CON Enter Till No.";

            }else if ($ussd_string_exploded[0] == 2 && $level == 2) {
                
                $response = "CON Enter Amount to pay.";

            }else if ($ussd_string_exploded[0] == 2 && $level == 3) {
                
                $response = "END KES ".number_format($ussd_string_exploded[2],2)." will be paid to till No. (".$ussd_string_exploded[1].") shortly";

                }elseif($text == "1*1"){

                $response = "CON Enter Group Name.";

            } else if ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 1 && $level == 3) {

                $response = "CON Enter your location \n";
                
                }else if ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 1 && $level == 4) {
                
                $response = "CON Enter wallet deposit. \n";
                
                }else if ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 1 && $level == 5) {
                
                $response = "END Details Submitted successfully, You will recieve an SMS confirmation shortly.";
                }elseif($text == "1*2"){

                    $response = "CON Enter Group Ref No.";
    
                }else if ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 3) {
                    
                    $response = "CON Enter bike plate No. \n";
                    
                    }else if ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 4) {
                    
                        $response = "CON Enter your Name. \n";
                    }else if ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 5) {
                    
                    $response = "END Details Submitted successfully, You will recieve an SMS confirmation shortly.";
             }

    header('Content-type: text/plain');
    echo $response;

 }

        public function register_group(){

            $group = new \App\Group();
            $group->group_name = $ussd_string_exploded[2];
            $group->location = $ussd_string_exploded[3];
            $group->group_ref_no = 'GRP'.rand(10,1000000);;
            $group->save();

            $group_id = DB::getPdo()->lastInsertId();

            $wallet = new \App\Wallet();
            $wallet->group_id = $group_id; 
            $wallet->amount  = $ussd_string_exploded[4];
            $wallet->save(); 

        }

        public function register_rider(){

            $rider = new \App\Rider();
            $rider->group_id = $ussd_string_exploded[2];
            $rider->bike_plate_no = $ussd_string_exploded[3];
            $rider->name = $ussd_string_exploded[4];
            $rider->phone = $phoneNumber;
            $rider->save();

        }

  

        public function sendMessage($AFmessage,$reciepient){
            $username   = "Combinesms";
            $apiKey     = "cf56a93a37982301267fd00af0554c068a4efeb005213e568278c9492152ca28";
    
            // Initialize the SDK
            $AT  = new AfricasTalking($username, $apiKey);
    
            // Get the SMS service
            $sms        = $AT->sms();
    
            // Set the numbers you want to send to in international format
            $reciepients = $reciepient;
    
            // Set your message
            $message    = $AFmessage;
    
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
           /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lipaNaMpesaPassword($lipa_time)
    {

        $passkey = env('STK_PASSKEY');
        $BusinessShortCode = env('MPESA_SHORT_CODE');
        $timestamp =$lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode.$passkey.$timestamp);
        return $lipa_na_mpesa_password;
    }



        public function stk_push($amount,$msisdn,$booking_ref){

     
        $consumer_key =  env('CONSUMER_KEY');
        $consume_secret = env('CONSUMER_SECRET');
        $headers = ['Content-Type:application/json','Charset=utf8'];
        $url = 'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';

        $curl = curl_init($url);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl,CURLOPT_USERPWD,$consumer_key.':'.$consume_secret);

        $curl_response = curl_exec($curl);
        $result = json_decode($curl_response);

        $token = $result->access_token;

        $url = 'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

        Log::info("Generated access token " . $token);

        $timestamp = date("YmdHis");

        $BusinessShortCode = env('MPESA_SHORT_CODE');

        $passkey = env('STK_PASSKEY');

        $lipa_time = Carbon::rawParse('now')->format('YmdHms');

        $apiPassword = $this->lipaNaMpesaPassword($lipa_time);

        Log::info("Generated Password " . $apiPassword);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json', 'Authorization:Bearer ' . $token)); //setting custom header

        $curl_post_data = array(

            'BusinessShortCode' => env('MPESA_SHORT_CODE'),
            'Password'          => $apiPassword,
            'Timestamp'         => $lipa_time,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $msisdn,
            'PartyB'            =>env('MPESA_SHORT_CODE'),
            'PhoneNumber'       => $msisdn,
            'CallBackURL'       => 'https://mosmos.co.ke/api/stk-callback',
            'AccountReference'  => $booking_ref,
            'TransactionDesc'   => 'Mosmos Product Payment'
        );

        $data_string = json_encode($curl_post_data);

        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

        $curl_response = curl_exec($curl);

        $responseArray = json_decode($curl_response, true);
        $status = 200;
        $success = true;
        $message = "STK Request Success";
        $httpCode = 200;

        \Log::info('STK DATA => '.print_r(json_encode($responseArray),1));
            
            Log::info('RESPONSE ARRAY: '.print_r($responseArray,true));
            
            if(array_key_exists("errorCode", $responseArray)){
                $message = "END Automatic payment failed. Go to your MPESA, select Paybill Enter : (4040299) and Account Number : ".$booking_reference." Enter Amount : ".number_format($amount,2)." Thank you.";
            }else{
                $message = "END  A Payment Prompt has been sent to the provided Phone Number. Enter M-Pesa PIN if Prompted.";
            }

            return $message;

    }



    public function initialize_variables(){

        $sessionId   = $_POST["sessionId"];
        $serviceCode = $_POST["serviceCode"];
        $phoneNumber = $_POST["phoneNumber"];
        $text        = $_POST["text"];

        $ussd_string_exploded = explode("*", $text);

        $out = [
            'sessionId'=>$sessionId,
            'serviceCode'=>$serviceCode,
            'phoneNumber'=>$phoneNumber,
            'text'=>$text,
            'ussd_string_exploded'=>$ussd_string_exploded
        ];
        return($out);
    }

    public function test_combine(){

        $variables = $this->initialize_variables();

        Log::info("USSD DATA : "+print_r($variables,1));

        $sessionId = $variables['$sessionId'];
        $serviceCode = $variables['serviceCode'];
        $phoneNumber = $variables['phoneNumber'];
        $text = $variables['text'];
        $ussd_string_exploded = $variables['ussd_string_exploded'];

        $valid_phone = ltrim($phoneNumber, '+');

        $existingAgent = \App\Agents::where('phone','=',$valid_phone)->first();

        $existingVendor = \App\Vendor::where('phone','=',$valid_phone)->first();

        
        if ($text == "" && $existingAgent === null && $existingVendor === null ) {
                   
            Log::info('Not Agent');
            
            $response  = "CON Welcome to Combine \n";
            $response .= "1. Make booking \n";
            $response .= "2. Make Payment \n";
            $response .= "3. Exchange an Item";
            
        }elseif($text == "" && \App\Agents::where('phone','=',$valid_phone)->count() > 0){

            $existingAgent = \App\Agents::where('phone','=',$valid_phone)->first();

            Log::info('Is Agent : '.$text);

            $response  = "CON Welcome to Combine Agent (".$existingAgent->agent_code.") \n";
            $response .= "4. Make booking \n";
            $response .= "6. Exchange an Item \n";
            $response .= "7. Confirm Delivery";
       
         }

         header('Content-type: text/plain');
         echo $response;

    }



    


public function getAirAccToken(){
        $headers = array(
            'Content-Type' => 'application/json',
        );
        $client = new \GuzzleHttp\Client();
        // Define array of request body.
        $request_body = array("client_id"=>"deb82d7f-4b9d-427a-a45c-015fe68b0c8f",
        "client_secret"=>"3fcf15c8-9de4-480b-b129-daca54d865b3",
        "grant_type"=>"client_credentials");
            $response = $client->request('POST','https://openapiuat.airtel.africa/auth/oauth2/token', array(
                'headers' => $headers,
                'json' => $request_body,
               )
            );
            $to=json_decode($response->getBody());
            return 'Bearer '.$to->access_token;//json_encode([$to->token_type,$to->access_token]);
      }
      public function AirtelussdPush($amount,$msisdn,$booking_reference){
        $headers = array(
            'Content-Type' => 'application/json',
            'X-Country' => 'KE',
             'X-Currency' => 'KES',
            'Authorization'  =>  $this->getAirAccToken(),
        );
        $client = new \GuzzleHttp\Client();
        // Define array of request body.
        $request_body = collect([
            "reference"=>$booking_reference,
            "subscriber"=>[
                "country"=>"KE",
                "currency"=>"KES",
                "msisdn"=>substr($msisdn, 3)
            ],
            "transaction"=>[
                "amount"=>$amount,
                "country"=>"KE",
                "currency"=>"KES",
                "id"=>Str::random(10)
            ]
        ]);
        //return $request_body;
            $response = $client->request('POST','https://openapiuat.airtel.africa/merchant/v1/payments/', array(
                'headers' => $headers,
                'json' => $request_body,
               )
            );
            $result=json_decode($response->getBody());
            if ($result->status->success) {
                return "END A Payment Prompt has been sent to the provided Phone Number. Enter Airtel PIN if Prompted.";
                # code...
            }
            else{
                      return "END Automatic payment failed contact support for more details.";

            }

      }


}
