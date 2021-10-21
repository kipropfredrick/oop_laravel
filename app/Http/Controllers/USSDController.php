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
                $response .= "1. New product booking \n";
                $response .= "2. New direct booking \n";
                $response .= "3. Check balance \n";
                $response .= "4. Exchange order \n";
                $response .= "5.  Make payment \n";
               
           
        }
        else if ($ussd_string_exploded[0] == 3 && $level==1) {
                
     if ($level==1) {
         # code...
           $response = "CON Enter Booking Reference.";
     }

    }else if ($ussd_string_exploded[0] == 3  && $level == 2) {

        $booking_reference = $ussd_string_exploded[1];

        $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->first();

        if($booking === null){
            $response = "END Booking Reference Entered does not exist.";
        }else{
 

  $response = "END Balance for booking reference ".$ussd_string_exploded[1]. " is ".$booking->balance ;

        }

    }
     else if ($ussd_string_exploded[0] == 2 && !$isvendor) {
                
                $valid_phone = ltrim($phoneNumber, '+');

                $customer = \App\Customers::where('phone','=',$valid_phone)->first();

                if($customer == null){
                    $response = "END You  have no account.";
                   }else{

                $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending'])->first();
                
                if($booking == null){
                 $response = "END You  have no active booking.";
                }else{
                  $product = \App\Products::where('id','=',$booking->product_id)->first();
                  $booking_reference = $booking->booking_reference;
                  $response = "CON Active booking : ".$booking_reference." Amount paid : KES ".number_format($booking->amount_paid,2)." Balance : KES ".number_format($booking->balance,2)." \n"."Enter Amount to Pay";
                }

             }

            }

        else if ($ussd_string_exploded[0] == 5 && $isvendor) {
                    $response = "CON Enter customer phone number.";
                
               if ($level==2) {
                  list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);

        if (!$msisdn){
$message="END Please enter a valid phone number provided!";
        }else{
            $valid_phone = $msisdn;
        }


Log::info("test 1");
                $customer = \App\Customers::where('phone','=',$valid_phone)->first();

                if($customer == null){
                    Log::info("test 2");
                    $response = "END You  have no account.";
                   }else{
Log::info("test 3");
                $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending'])->first();
                
                if($booking == null){
                 $response = "END You  have no active booking.";
                }else{
                  $product = \App\Products::where('id','=',$booking->product_id)->first();
                  $booking_reference = $booking->booking_reference;
                  $response = "CON Active booking : ".$booking_reference." Amount paid : KES ".number_format($booking->amount_paid,2)." Balance : KES ".number_format($booking->balance,2)." \n"."Enter Amount to Pay";
                }

             }
                   # code...
               }
               if ($level==3) {
                   # code...
                 $response  = "CON Choose payment method \n";
                $response .= "1. Mpesa \n";
                $response .= "2. Airtel \n";
        
            
               }

               if ($level==4) {
                   # code...
                                  $amount = $ussd_string_exploded[2];
                  $paymentfrom= $ussd_string_exploded[3];


if ($paymentfrom==1) {
    # code...
       Log::info('AMOUNT : '.print_r($amount,true));

              
        list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);

        if (!$msisdn){
$message="END Please enter a valid phone number provided!";
        }else{
            $valid_phone = $msisdn;
        }

                $customer = \App\Customers::where('phone','=',$msisdn)->first();

                $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending'])->first();

               
                $booking_ref = $booking->booking_reference;

                $message = $this->stk_push($amount,$msisdn,$booking_ref);
                
                $response = $message;
}
else{
   $response="END platform implementation is pending come back later"; 
}
               }

            }



            else if ($ussd_string_exploded[0] == 2  && $level == 2 && !$isvendor) {

              
                 $response  = "CON Choose payment method \n";
                $response .= "1. Mpesa \n";
                $response .= "2. Airtel \n";
        
            }
            else if($ussd_string_exploded[0] == 2  && $level == 3 && !$isvendor){

                  $amount = $ussd_string_exploded[1];
                  $paymentfrom= $ussd_string_exploded[2];

if ($paymentfrom==1) {
    # code...
       Log::info('AMOUNT : '.print_r($amount,true));

                $msisdn = ltrim($phoneNumber, '+');

                $customer = \App\Customers::where('phone','=',$msisdn)->first();

                $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending'])->first();

               
                $booking_ref = $booking->booking_reference;

                $message = $this->stk_push($amount,$msisdn,$booking_ref);
                
                $response = $message;
}
else{
   $response="END platform implementation is pending come back later"; 
}
             

            }

             else if ($ussd_string_exploded[0]==1 && !$isvendor){
if ($level==1) {
    # code...
        $response  = "CON Enter Product code \n";
}
               
if ($ussd_string_exploded[0]==1 && $level==2) {
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
                              $response  = "CON  You are making a booking for ".$product->product_name."\nEnter Initial depoist amount \n";

            }
//check booking
    
                else{
 $response = "END You Already have an ongoing booking. You can't make another booking."; 

                }





            }
        }

          else if($level==3){

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
                    $request->county_id=1;
                    $request->exact_location='';
                    $request->phone=$phoneNumber;
                    $request->initial_deposit=$ussd_string_exploded[2];
                    $request->product_id=$product->id;
                    $request->vendor_code=$vendor->vendor_code;

 $response = $this->make_booking($request); 
Log::info("executed 1");
            }
                 else{
               Log::info("executed 2");     
 $response = "END You Already have an ongoing booking. You can't make another booking."; 

                }


}

    }


    else if ($ussd_string_exploded[0]==2 && $isvendor){

   if($level==1){

     $response = "CON Enter customer phone number.";


         } 

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
                              $response  = "CON  You are making a booking for ".$product->product_name."\nEnter Initial depoist amount \n";

            }
//check booking
    
                else{
 $response = "END You Already have an ongoing booking. You can't make another booking."; 

                }





            }
        }
       

          else if($level==4){
list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[1]);
                $customer = \App\Customers::where('phone','=',$msisdn)->first();
            
                $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereIn('status',['active','pending','unserviced','overdue'])->first();
$product_code=$ussd_string_exploded[2];
       $product = \App\Products::where('product_code','=',$product_code)->first();
       $vendor=\App\Vendor::whereId($product->vendor_id)->first();
                if($booking == null){
                    $request=(object) Array();
                    $request->county_id=1;
                    $request->exact_location='';
                    $request->phone=$phoneNumber;
                    $request->initial_deposit=$ussd_string_exploded[3];
                    $request->product_id=$product->id;
                    $request->vendor_code=$vendor->vendor_code;

 $response = $this->make_booking($request); 
Log::info("executed 1");
            }
                 else{
               Log::info("executed 2");     
 $response = "END You Already have an ongoing booking. You can't make another booking."; 

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

$categories=\App\Categories::get();
if ($level==1) {
    # code...
    $response="CON Select category\n";
    $index=1;
 foreach ($categories as $key => $value) {
 
     $response .= "{$index}. {$value->category_name} \n";
$index=$index+1;
 }


}
if ($level==2) {
    # code...
    $value1=$ussd_string_exploded[1]-1;
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

if ($level==3) {

  $value1=$ussd_string_exploded[1]-1;
    $category_id=0;
foreach ($categories as $key => $value) {
    # code...
    if ($key==$value1) {
        # code...
        $category_id=$value->id;
    }

}
$subcategories=\App\SubCategories::whereCategory_id($category_id)->get();
  $value2=$ussd_string_exploded[2]-1;
    $subcategory_id=0;
foreach ($subcategories as $key => $value) {
    # code...
    if ($key==$value2) {
        # code...
        $subcategory_id=$value->id;
    }

}

$tlc=\App\ThirdLevelCategory::whereSubcategory_id($subcategory_id)->get();


$response="CON Select third level category\n";
       
$index=1;

foreach ($tlc as $key => $value) {
    # code...

     $response .= "{$index}. {$value->name} \n";
$index=$index+1;
 

}

    # code...
}

if ($level==4) {
    # code...
  $value1=$ussd_string_exploded[1]-1;
    $category_id=0;
foreach ($categories as $key => $value) {
    # code...
    if ($key==$value1) {
        # code...
        $category_id=$value->id;
    }

}
$subcategories=\App\SubCategories::whereCategory_id($category_id)->get();
  $value2=$ussd_string_exploded[2]-1;
    $subcategory_id=0;
foreach ($subcategories as $key => $value) {
    # code...
    if ($key==$value2) {
        # code...
        $subcategory_id=$value->id;
    }

}

$tlc=\App\ThirdLevelCategory::whereSubcategory_id($subcategory_id)->get();
$value3=$ussd_string_exploded[3]-1;
    $tlc_id=0;
foreach ($tlc as $key => $value) {
    # code...
    if ($key==$value3) {
        # code...
        $tlc_id=$value->id;
    }

}
$products=\App\Products::whereThird_level_category_id($tlc_id)->get();
$response="CON Select Product\n";
       
$index=1;

foreach ($products as $key => $value) {
    # code...

     $response .= "{$index}. {$value->product_name} \n";
$index=$index+1;
 

}

}

if ($level==5) {
    # code...
$response="CON Enter customer phone number";


}
if ($level>5) {
    # code...
    list($msisdn, $network) = $this->get_msisdn_network($ussd_string_exploded[5]);
$customer = \App\Customers::where('phone','=',$msisdn)->first();
if ($customer==null) {
    $response="END proceed to register customer";
    # code...
}
else{

    $response="CON Enter initial deposit";

if ($level==7) {
        $response  = "CON Choose payment method \n";
                $response .= "1. Mpesa \n";
                $response .= "2. Airtel \n";
}
if ($level==8) {
    # code...
if ($ussd_string_exploded[7]==1) {
    # code...
 $value1=$ussd_string_exploded[1]-1;
    $category_id=0;
foreach ($categories as $key => $value) {
    # code...
    if ($key==$value1) {
        # code...
        $category_id=$value->id;
    }

}
$subcategories=\App\SubCategories::whereCategory_id($category_id)->get();
  $value2=$ussd_string_exploded[2]-1;
    $subcategory_id=0;
foreach ($subcategories as $key => $value) {
    # code...
    if ($key==$value2) {
        # code...
        $subcategory_id=$value->id;
    }

}

$tlc=\App\ThirdLevelCategory::whereSubcategory_id($subcategory_id)->get();
$value3=$ussd_string_exploded[3]-1;
    $tlc_id=0;
foreach ($tlc as $key => $value) {
    # code...
    if ($key==$value3) {
        # code...
        $tlc_id=$value->id;
    }

}
$products=\App\Products::whereThird_level_category_id($tlc_id)->get();

$value4=$ussd_string_exploded[4]-1;
    $product_id=0;
foreach ($products as $key => $value) {
    # code...
    if ($key==$value4) {
        # code...
        $tlc_id=$value->id;
    }

}

$vendor_id=\App\Products::whereId($product_id)->first()->vendor_id;
$vendor=\App\Vendors::whereId($vendor_id)->first();


        $request=(object) Array();
                    $request->county_id=1;
                    $request->exact_location='';
                    $request->phone=$msisdn;
                    $request->initial_deposit=$ussd_string_exploded[6];
                    $request->product_id=$product_id;
                    $request->vendor_code=$vendor->vendor_code;

 $response = $this->make_booking($request); 

}
else{
$response="END payment platform not supported";

}

}


    
}

}

}


          
             




     //        else if ($ussd_string_exploded[0] == 2  && $level == 3) {


     //            $amount = $ussd_string_exploded[2];
     //            // $msisdn = $phoneNumber;
     //            $msisdn = ltrim($phoneNumber, '+');
     //            $booking_ref = $ussd_string_exploded[1];

     //            Log::info('Phone : '.$phoneNumber);

     //            $message = $this->stk_push($amount,$msisdn,$booking_ref);
                
     //            $response = $message;

            
     //        }else if($text == "1*2") { 
               
     //            $response = "CON Enter your Full Name \n";

     //         }elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 3) {

     //                $response = "CON Please enter your email address";


     //         }elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 4){


     //                $response = "CON Please enter Agent/Vendor Code.";

                
     //        } elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 5){

     //            $response = "CON Please enter product Code";

        
     //      } elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 6){

     //        $product_code = $ussd_string_exploded[5];

     //        $product = \App\Products::where('product_code','=',$product_code)->first();

     //        if($product === null){
     //        $response = "END Product Code Entered does not exist.";
     //        }else{

     //        if($product->product_price < 5000){
     //            $minDeposit = 0.2*$product->product_price;
     //        }else {
     //            $minDeposit = 0.1 *$product->product_price;
     //        }

     //        $response = "CON  Minimum Deposit Amount for this Product is : KES ".number_format($minDeposit,2)."\n"." Please enter Initial deposit.";

     //    }

    
     // }

        // elseif(empty($ussd_string_exploded[1])){
        //     $response  = "END Sorry we do not accept blank values";
        //  }elseif(empty($ussd_string_exploded[2])){
        //     $response  = "END Sorry we do not accept blank values";
        //  }elseif(empty($ussd_string_exploded[3])){
        // $response  = "END Sorry we do not accept blank values";
        // }elseif(empty($ussd_string_exploded[4])){
        //     $response  = "END Sorry we do not accept blank values";
        // }elseif(empty($ussd_string_exploded[5])){
        //     $response  = "END Sorry we do not accept blank values";
        //  }elseif(empty($ussd_string_exploded[6])){
        //     $response  = "END Sorry we do not accept blank values";
        //  }
//          else if ($text == "4") {

//                 Log::info('Make booking');

//                 $response  = "CON Please Select One \n";
//                 $response .= "1. Customer has an account \n";
//                 $response .= "2.  Customer does not have an account";
                    
//             }else if ($ussd_string_exploded[0] == "" && \App\Agents::where('phone','=',$valid_phone)->count() > 0){

//                     $existingAgent = \App\Agents::where('phone','=',$valid_phone)->first();


//                     Log::info('Is Agent : '.$text);

//                     $text = '';

//                     $ussd_string_exploded = [];
    
//                     $response  = "CON Invalid Choice,Please Enter a right value) \n";
//                     $response .= "4. Make booking \n";
//                     $response .= "6. Exchange an Item \n";
//                     $response .= "7. Confirm Delivery";
                    
//             }else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 1 && $level == 2) {

//                 $response = "CON Please Enter Customer's Phone No \n";
                    
//             }else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 1 && $level == 3) {

//                 $phone = $ussd_string_exploded[2];

//                 $phone = "254".ltrim($phone, '0');

//                 $customer = \App\Customers::where('phone','=',$phone)->first();

//                 // AGENT EXISTING USER

//                 if($customer === null){

//                     $response = "END Customer has no account \n";  
//                 }else{

//                     $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','active')->first();

//                     if($booking === null){
//                         $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','pending')->first();
//                         if($booking === null){
//                             $response = "CON Please Enter Product Code \n";
//                         }else{
//                         $response = "END Customer has an existing booking. Advise them to Complete the current booking before making another one.";
//                     }
//                 }
//              }

//             }else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 1 && $level == 4) {

//                 $product_code = $ussd_string_exploded[3];

//                 $product = \App\Products::where('product_code','=',$product_code)->first();
    
//                 if($product === null){

//                   $response = "END Product Code Entered does not exist.Please Enter a valid product code";
//                 }else{
    
//                     // if($product->product_price < 5000){
//                     //     $minDeposit = 0.2*$product->product_price;
//                     // }else {
//                     //     $minDeposit = 0.1 *$product->product_price;
//                     // }
        
//                     $response = "CON  Minimum Deposit Amount for this Product is : KES ".number_format(200,2)."\n"." Please enter Initial deposit.";
    
//             }      
//             }else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 1 && $level == 5) {

//                 $deposit = $ussd_string_exploded[4];

//                 if($deposit < 1){

//                     $response = "END The minimum deposit for this item is KES 200.00";
//                   }else{

//                 $booking_date = now();

//                 $booking_date = strtotime($booking_date);

//                 $product_code = $ussd_string_exploded[3];

//                 $product = \App\Products::where('product_code','=',$product_code)->first();

//                 Log::info('PRODUCT : '.print_r($product,1));

//                 $due_date = Carbon::createFromTimestamp($booking_date)->addMonths(3);

//                 $booking_reference = 'BKG'.rand(10,1000000);

//                 $valid_phone = ltrim($phoneNumber, '+');

//                 $agent = \App\Agents::where('phone','=',$valid_phone)->first();

//                 $phone = "254".ltrim($ussd_string_exploded[2], '0');

//                 $customer = \App\Customers::where('phone','=',$phone)->first();

//                 $booking = new \App\Bookings();
//                 $booking->customer_id = $customer->id; 
//                 $booking->product_id  = $product->id;
//                 $booking->booking_reference = $booking_reference;
//                 $booking->quantity  = "1";
//                 $booking->agent_code = $agent->agent_code;
//                 $booking->balance = $product->product_price;
//                 $booking->amount_paid = '0';
//                 $booking->payment_mode  = 'Mpesa';
//                 $booking->status = "pending";
//                 $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//                 $booking->due_date = $due_date;
//                 $booking->total_cost = $product->product_price;
//                 $booking->save();

//                 $amount = $deposit;

//                 $msisdn = $phone;
                
//                 $booking_ref = $booking->booking_reference;
                
//                 $message = $this->stk_push($amount,$msisdn,$booking_ref);
        
//                 $response = $message;

//                  }

                
//             }//AGENT NEW USER
//             else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 2 && $level == 2) {

//                 $response = "CON Please Enter Customer's Full Name \n";
                    
//             }else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 2 && $level == 3) {

//                 $response = "CON Please Enter Customer's Phone Number \n";
                    
//             }else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 2 && $level == 4) {

//                 $response = "CON Please Enter Customer's Email Adress \n";
                    
//             }else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 2 && $level == 5) {

//                 $response = "CON Please Enter Product Code \n";

                    
//             }else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 2 && $level == 6) {

//                 $product_code = $ussd_string_exploded[5];
                
//                 $product = \App\Products::where('product_code','=',$product_code)->first();
                
//                 if($product === null){
                
//                   $response = "END Product Code Entered does not exist.Please Enter a valid product code";
//                 }else{
//                     $response = "CON Please Enter Customer's Initial Deposit \n";
//                 }
                    
//             }else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 2 && $level == 7) {

//                 $name =$ussd_string_exploded[2];
//                 $phone = '254'.ltrim($ussd_string_exploded[3], '0');
//                 $email = $ussd_string_exploded[4];
//                 $product_code = $ussd_string_exploded[5];
//                 $deposit = $ussd_string_exploded[6];

//                 $existingCustomer = \App\Customers::where('phone','=',$phone)->first();


//                 if($existingCustomer == null){

//                     //here

//                     // $user = new \App\User();
//                     // $user->email = $email;
//                     // $user->name = $name;
//                     // $user->password = Hash::make($phone);
//                     // $user->save();

//                     $existingUser = \App\User::where('email','=',$email)->first();

//                     if($existingUser === null){
//                         $user = new \App\User();
//                         $user->email = $email;
//                         $user->name = $name;
//                         $user->password = Hash::make($phone);
//                         $user->save();

//                         $user_id = DB::getPdo()->lastInsertId();

//                         Log::info('USER DOES NOT EXIST');
                        
//                     }else{
//                         $user_id =  $existingUser->id;
//                         Log::info('USER EXISTS : '.print_r($existingUser,1));
//                     }

//                     // $user_id = DB::getPdo()->lastInsertId();

//                     $customer = new \App\Customers();
//                     $customer->user_id = $user_id; 
//                     $customer->phone  = $phone;
//                     $customer->save();

//                     $customer_id = DB::getPdo()->lastInsertId();

//                     $booking_date = now();

//                     $booking_date = strtotime($booking_date);

//                     $product = \App\Products::where('product_code','=',$product_code)->first();


//                     if((10000 <= $product->product_price) && ($product->product_price <= 20000)){
//                         $discount = 200;
//                     }elseif($product->price >20000) {
//                         $discount = 500;
//                     }else {
//                         $discount = 0;
//                     }

//                     if($discount>0){
//                         $showDiscount = 1;
//                     }else {
//                         $showDiscount = 0;
//                     }


//                     $due_date = Carbon::createFromTimestamp($booking_date)->addMonths(3);
        
//                     $booking_reference = 'BKG'.rand(10,1000000);

//                     $valid_phone = ltrim($phoneNumber, '+');

//                     $agent = \App\Agents::where('phone','=',$valid_phone)->first();

//                     $booking = new \App\Bookings();
//                     $booking->customer_id = $customer_id; 
//                     $booking->product_id  = $product->id;
//                     $booking->booking_reference = $booking_reference;
//                     $booking->quantity  = "1";
//                     $booking->agent_code = $agent->agent_code;
//                     $booking->balance = $product->product_price - $discount;
//                     $booking->amount_paid = '0';
//                     $booking->payment_mode  = 'Mpesa';
//                     $booking->status = "pending";
//                     $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//                     $booking->due_date = $due_date;
//                     $booking->total_cost = $product->product_price - $discount;
//                     $booking->discount = $discount;
//                     $booking->save();

//                     $amount = $deposit;
                    
//                     $msisdn = $phone;
                    
//                     $booking_ref = $booking->booking_reference;

//                     $reciepient = $msisdn;


//                     if($showDiscount == 1){
            
//                         $AFmessage = "Booking of : ".$product->product_name." was successful. "."You recieved a discount of ".number_format($discount,2)." Total Price is KES ".number_format(($product->product_price - $discount),2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4040299) and Account Number : ".$booking_reference.", Enter Amount : KES ".number_format($deposit,2);
//                         $this->sendMessage($AFmessage,$reciepient);
                    
//                     }elseif($showDiscount == 0){
            
//                         $AFmessage = "Booking of : ".$product->product_name." was successful. Total Price is KES ".number_format($product->product_price,2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4040299) and Account Number : ".$booking_reference.", Enter Amount : KES ".number_format($deposit,2);
//                         $this->sendMessage($AFmessage,$reciepient);
            
//                     }


//                     $message = $this->stk_push($amount,$msisdn,$booking_ref);
                    
//                     $response = $message;

//                 }else{

//                 //  IF CUSTOMER  EXISTS

//                 $customer = $existingCustomer;

//                 $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','active')->first();

//                 if($booking == null){

//                 $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','pending')->first();

//                 if($booking == null){

//                 Log::info('Phone Number : '.$phone);

//                 $booking_reference = 'BKG'.rand(10,1000000);

//                 $booking_date = now();
                
//                 $booking_date = strtotime($booking_date);

//                 $product = \App\Products::where('product_code','=',$product_code)->first();

//                 $due_date = Carbon::createFromTimestamp($booking_date)->addMonths(3);

//                 if($deposit<200){
//                     $response = "END Minimum Deposit for this product is  KES".number_format(200,2);
//                 }else {

//                $valid_phone = ltrim($phoneNumber, '+');

//                $agent = \App\Agents::where('phone','=',$valid_phone)->first();

//                 $booking = new \App\Bookings();
//                 $booking->customer_id = $existingCustomer->id; 
//                 $booking->product_id  = $product->id;
//                 $booking->booking_reference = $booking_reference;
//                 $booking->quantity  = '1';
//                 $booking->agent_code = $agent->agent_code;
//                 $booking->amount_paid = '0';
//                 $booking->balance = $product->product_price;
//                 $booking->payment_mode  = 'Mpesa';
//                 $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//                 $booking->due_date = $due_date;
//                 $booking->status = "pending";
//                 $booking->total_cost = $product->product_price;
//                 $booking->save();

//                 $amount = $deposit;
//                 $msisdn = $valid_phone;
//                 $booking_ref = $booking_reference;

//                 $message = $this->stk_push($amount,$msisdn,$booking_ref);
                    
//                 $response = $message;

//                     }
                        
//                     }else{
//                         $response = "END Customer has an existing booking. Advise them to Complete the current booking before making another one.";
//                     }
//                 }


//                 }

                    
//             }

            
//             //VENDOR EXISTING USER
//             else if ($text == "8") {

//                 Log::info('Make booking');
                
//                 $response  = "CON Please Select One \n";
//                 $response .= "1. Customer has an account \n";
//                 $response .= "2.  Customer does not have an account";
                    
//                 }else if ($ussd_string_exploded[0] == 8  && $ussd_string_exploded[1] == 1 && $level == 2) {
                
//                 $response = "CON Please Enter Customer's Phone No \n";
                    
//                 }else if ($ussd_string_exploded[0] == 8  && $ussd_string_exploded[1] == 1 && $level == 3) {
                
//                 $phone = $ussd_string_exploded[2];
                
//                 $phone = "254".ltrim($phone, '0');
                
//                 $customer = \App\Customers::where('phone','=',$phone)->first();
                
//                 if($customer === null){
                
//                     $response = "END Customer has no account \n";  
//                 }else{
                
//                     $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','active')->first();
                
//                     if($booking === null){
//                         $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','pending')->first();
//                         if($booking === null){
//                             $response = "CON Please Enter Product Code \n";
//                         }else{
//                         $response = "END Customer has an existing booking. Advise them to Complete the current booking before making another one.";
//                     }
//                 }
//                 }
                
//                 }else if ($ussd_string_exploded[0] == 8  && $ussd_string_exploded[1] == 1 && $level == 4) {
                
//                 $product_code = $ussd_string_exploded[3];
                
//                 $product = \App\Products::where('product_code','=',$product_code)->first();
                
//                 if($product === null){
                
//                   $response = "END Product Code Entered does not exist.Please Enter a valid product code";
//                 }else{
//                     $response = "CON  Minimum Deposit Amount for this Product is : KES ".number_format(200,2)."\n"." Please enter Initial deposit.";
                
//                 }      
//                 }else if ($ussd_string_exploded[0] == 8  && $ussd_string_exploded[1] == 1 && $level == 5) {
                
//                 $deposit = $ussd_string_exploded[4];
                
//                 if($deposit < 1){
                
//                     $response = "END The minimum deposit for this item is KES 200.00";
//                   }else{
                
//                 $booking_date = now();
                
//                 $booking_date = strtotime($booking_date);
                
//                 $product_code = $ussd_string_exploded[3];
                
//                 $product = \App\Products::where('product_code','=',$product_code)->first();
                
//                 $due_date = Carbon::createFromTimestamp($booking_date)->addMonths(3);
                
//                 $booking_reference = 'BKG'.rand(10,1000000);
                
//                 $valid_phone = ltrim($phoneNumber, '+');
                
//                 $vendor = \App\Vendor::where('phone','=',$valid_phone)->first();
                
//                 $phone = "254".ltrim($ussd_string_exploded[2], '0');
                
//                 $customer = \App\Customers::where('phone','=',$phone)->first();
                
//                 $booking = new \App\Bookings();
//                 $booking->customer_id = $customer->id; 
//                 $booking->product_id  = $product->id;
//                 $booking->booking_reference = $booking_reference;
//                 $booking->quantity  = "1";
//                 $booking->vendor_code = $vendor->vendor_code;
//                 $booking->balance = $product->product_price;
//                 $booking->amount_paid = '0';
//                 $booking->payment_mode  = 'Mpesa';
//                 $booking->status = "pending";
//                 $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//                 $booking->due_date = $due_date;
//                 $booking->total_cost = $product->product_price;
//                 $booking->save();
                
//                 $amount = $deposit;
                
//                 $msisdn = $phone;
                
//                 $booking_ref = $booking->booking_reference;
                
//                 $message = $this->stk_push($amount,$msisdn,$booking_ref);
                    
//                 $response = $message;
                
//                  }
                
                
//                 }
//                 else if ($ussd_string_exploded[0] == 4  && $ussd_string_exploded[1] == 1 && $level == 4) {

//                 $response = "END A payment prompt has been sent to the customer. Inform them to enter MPesa PIN if prompted.";
                    
//             }//VENDOR NEW USER
//             else if ($ussd_string_exploded[0] == 8  && $ussd_string_exploded[1] == 2 && $level == 2) {

//                 $response = "CON Please Enter Customer's Full Name \n";
                    
//             }else if ($ussd_string_exploded[0] == 8  && $ussd_string_exploded[1] == 2 && $level == 3) {

//                 $response = "CON Please Enter Customer's Phone Number \n";
                    
//             }else if ($ussd_string_exploded[0] == 8  && $ussd_string_exploded[1] == 2 && $level == 4) {

//                 $response = "CON Please Enter Customer's Email Adress \n";
                    
//             }else if ($ussd_string_exploded[0] == 8  && $ussd_string_exploded[1] == 2 && $level == 5) {

//                 $response = "CON Please Enter Product Code \n";
                    
//             }else if ($ussd_string_exploded[0] == 8  && $ussd_string_exploded[1] == 2 && $level == 6) {

//                 $response = "CON Please Enter Customer's Initial Deposit \n";
                    
//             }else if ($ussd_string_exploded[0] == 8  && $ussd_string_exploded[1] == 2 && $level == 7) {

//                 // $response = "END A payment prompt has been sent to the customer. Inform them to enter MPesa PIN if prompted.";
//                 //here
//                 $name =$ussd_string_exploded[2];
//                 $phone = '254'.ltrim($ussd_string_exploded[3], '0');
//                 $email = $ussd_string_exploded[4];
//                 $product_code = $ussd_string_exploded[5];
//                 $deposit = $ussd_string_exploded[6];

//                 $existingCustomer = \App\Customers::where('phone','=',$phone)->first();


//                 if($existingCustomer == null){

//                     // $user = new \App\User();
//                     // $user->email = $email;
//                     // $user->name = $name;
//                     // $user->password = Hash::make($phone);
//                     // $user->save();

//                     // $user_id = DB::getPdo()->lastInsertId();


//                     if($existingUser === null){
//                         $user = new \App\User();
//                         $user->email = $email;
//                         $user->name = $name;
//                         $user->password = Hash::make($phone);
//                         $user->save();
    
//                         $user_id = DB::getPdo()->lastInsertId();
    
//                         Log::info('USER DOES NOT EXIST');
                        
//                     }else{
//                         $user_id=  $existingUser->id;
//                         Log::info('USER EXISTS : '.print_r($existingUser));
//                     }

//                     $customer = new \App\Customers();
//                     $customer->user_id = $user_id; 
//                     $customer->phone  = $phone;
//                     $customer->save();

//                     $customer_id = DB::getPdo()->lastInsertId();

//                     $booking_date = now();

//                     $booking_date = strtotime($booking_date);

//                     $product = \App\Products::where('product_code','=',$product_code)->first();


//                     $due_date = Carbon::createFromTimestamp($booking_date)->addMonths(3);
        
//                     $booking_reference = 'BKG'.rand(10,1000000);

//                     $valid_phone = ltrim($phoneNumber, '+');

//                     $vendor = \App\Vendor::where('phone','=',$valid_phone)->first();

//                     if((10000 <= $product->product_price) && ($product->product_price <= 20000)){
//                         $discount = 200;
//                     }elseif($product->price >20000) {
//                         $discount = 500;
//                     }else {
//                         $discount = 0;
//                     }

//                     if($discount>0){
//                         $showDiscount = 1;
//                     }else {
//                         $showDiscount = 0;
//                     }

//                     $booking = new \App\Bookings();
//                     $booking->customer_id = $customer_id; 
//                     $booking->product_id  = $product->id;
//                     $booking->booking_reference = $booking_reference;
//                     $booking->quantity  = "1";
//                     $booking->vendor_code = $vendor->vendor_code;
//                     $booking->balance = $product->product_price - $discount;
//                     $booking->amount_paid = '0';
//                     $booking->payment_mode  = 'Mpesa';
//                     $booking->status = "pending";
//                     $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//                     $booking->due_date = $due_date;
//                     $booking->total_cost = $product->product_price - $discount;
//                     $booking->discount = $discount;
//                     $booking->save();

//                     $amount = $deposit;
                    
//                     $msisdn = $phone;
                    
//                     $booking_ref = $booking->booking_reference;


//                     if($showDiscount == 1){
            
//                         $message = "Booking of : ".$product->product_name." was successful. "."You recieved a discount of ".number_format($discount,2)." Total Price is KES ".number_format(($product->product_price - $discount),2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4040299) and Account Number : ".$booking_reference.", Enter Amount : KES ".number_format($minDeposit,2);
//                         $this->sendMessage($AFmessage,$reciepient);
                    
//                     }elseif($showDiscount == 0){
            
//                         $message = "Booking of : ".$product->product_name." was successful. Total Price is KES ".number_format($product->product_price,2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4040299) and Account Number : ".$booking_reference.", Enter Amount : KES ".number_format($minDeposit,2);
//                         $this->sendMessage($AFmessage,$reciepient);
            
//                     }
                    
//                     $message = $this->stk_push($amount,$msisdn,$booking_ref);
                    
//                     $response = $message;

//                 }else{

//                 //  IFF CUSTOMER  EXISTS

//                 $customer = $existingCustomer;

//                 $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','active')->first();

//                 if($booking == null){

//                 $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','pending')->first();

//                 if($booking == null){

//                 Log::info('Phone Number : '.$phone);

//                 $booking_reference = 'BKG'.rand(10,1000000);

//                 $booking_date = now();
                
//                 $booking_date = strtotime($booking_date);

//                 $product = \App\Products::where('product_code','=',$product_id)->first();

//                 $due_date = Carbon::createFromTimestamp($booking_date)->addMonths(3);


//                 if($deposit<200){
//                     $response = "END Minimum Deposit for this product is  KES".number_format(200,2);
//                 }else {

//                 $booking = new \App\Bookings();
//                 $booking->customer_id = $existingCustomer->id; 
//                 $booking->product_id  = $product->id;
//                 $booking->booking_reference = $booking_reference;
//                 $booking->quantity  = '1';
//                 $booking->vendor_code = $vendor_code;
//                 $booking->amount_paid = '0';
//                 $booking->balance = $product->product_price;
//                 $booking->payment_mode  = 'Mpesa';
//                 $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//                 $booking->due_date = $due_date;
//                 $booking->status = "pending";
//                 $booking->total_cost = $product->product_price;
//                 $booking->save();

//                 $amount = $deposit;
//                 $msisdn = $valid_phone;
//                 $booking_ref = $booking_reference;

//                 $message = $this->stk_push($amount,$msisdn,$booking_ref);
                    
//                 $response = $message;
//             }
                
//             }else{
//                 $response = "END Customer has an existing booking. Advise them to Complete the current booking before making another one.";
//                 }
//             }


//             }

                    
//             } else if ($text == "1") {

//                 // $valid_phone = ltrim($phoneNumber, '+');

//                 // $customer = \App\Customers::where('phone','=',$valid_phone)->first();

//                 // if($customer == null){

//                 // }else{

//                 // }
//                 $response = "CON Do you have an account? \n";
//                 $response .= "1. Yes \n";
//                 $response .= "2. No ";

//             }
// elseif ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 2 && $level == 7){

//         $email = $ussd_string_exploded[3];
//         $name = $ussd_string_exploded[2];
//         $agent_code = $ussd_string_exploded[4];
//         $product_id = $ussd_string_exploded[5];
//         $deposit = $ussd_string_exploded[6];

//         $pattern = "/ag/i";

//         $agentTrue = preg_match($pattern,$agent_code);

//         $vpattern = "/vd/i";

//         $vendorTrue = preg_match($vpattern,$agent_code);

//         $valid_phone = ltrim($phoneNumber, '+');

//         $phone = "0".ltrim($phoneNumber, '+254');

//         $existingCustomer = \App\Customers::where('phone','=',$valid_phone)->first();
        
//         // If Customer doesnt exist

//         if($existingCustomer === null){

//                 $existingUser = \App\User::where('email','=',$email)->first();

//                 if($existingUser === null){
//                     $user = new \App\User();
//                     $user->email = $email;
//                     $user->name = $name;
//                     $user->password = Hash::make($phone);
//                     $user->save();

//                     $user_id = DB::getPdo()->lastInsertId();

//                     Log::info('USER DOES NOT EXIST');
                    
//                 }else{
//                     $user_id=  $existingUser->id;
//                     Log::info('USER EXISTS : '.print_r($existingUser));
//                 }

//                 $customer = new \App\Customers();
//                 $customer->user_id = $user_id; 
//                 $customer->phone  = '254'.ltrim($phone, '0');
//                 $customer->save();

//                 $customer_id = DB::getPdo()->lastInsertId();

//                 $booking_date = now();

//                 $booking_date = strtotime($booking_date);

//                 $product = \App\Products::where('product_code','=',$product_id)->first();


//                 if($product->product_price < 5000){
//                     $minDeposit = 0.2*$product->product_price;
//                 }else {
//                     $minDeposit = 0.1 *$product->product_price;
//                 }

//                 if($deposit<200){
//                     $response = "END Minimum Deposit for this product is  KES".number_format(200,2);
//                 }else {
//                     if((10000 <= $product->product_price) && ($product->product_price <= 20000)){
//                         $discount = 200;
//                     }elseif($product->price >20000) {
//                         $discount = 500;
//                     }else {
//                         $discount = 0;
//                     }

//                     if($discount>0){
//                         $showDiscount = 1;
//                     }else {
//                         $showDiscount = 0;
//                     }
    
//                     $due_date = Carbon::createFromTimestamp($booking_date)->addMonths(3);
    
//                     $booking_reference = 'BKG'.rand(10,1000000);

//                     if($agentTrue == 1){
    
//                     $booking = new \App\Bookings();
//                     $booking->customer_id = $customer_id; 
//                     $booking->product_id  = $product->id;
//                     $booking->booking_reference = $booking_reference;
//                     $booking->quantity  = "1";
//                     $booking->agent_code = $agent_code;
//                     $booking->balance = $product->product_price - $discount;
//                     $booking->amount_paid = '0';
//                     $booking->payment_mode  = 'Mpesa';
//                     $booking->status = "pending";
//                     $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//                     $booking->due_date = $due_date;
//                     $booking->total_cost = $product->product_price - $discount;
//                     $booking->discount = $discount;
//                     $booking->save();

//                     }elseif($vendorTrue == 1){

//                     $booking = new \App\Bookings();
//                     $booking->customer_id = $customer_id; 
//                     $booking->product_id  = $product->id;
//                     $booking->booking_reference = $booking_reference;
//                     $booking->quantity  = "1";
//                     $booking->vendor_code = $agent_code;
//                     $booking->balance = $product->product_price - $discount;
//                     $booking->amount_paid = '0';
//                     $booking->payment_mode  = 'Mpesa';
//                     $booking->status = "pending";
//                     $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//                     $booking->due_date = $due_date;
//                     $booking->total_cost = $product->product_price - $discount;
//                     $booking->discount = $discount;
//                     $booking->save();

//                     }
    
//                     if($showDiscount == 1){

//                         $reciepient = $phoneNumber;
//                         $AFmessage = "Booking of : ".$product->product_name." was successful. "."You recieved a discount of ".number_format($discount,2)." Total Price is KES ".number_format(($product->product_price - $discount),2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, select Paybill Enter : (4040299) and Account Number : ".$booking_reference.", Enter Amount you wish to pay. Thank you. Terms & Conditions Apply";
//                         $this->sendMessage($AFmessage,$reciepient);

//                     }else {
//                         $reciepient = $phoneNumber;
//                         $AFmessage = "Booking of : ".$product->product_name." was successful. Total Price is KES ".number_format($product->product_price,2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, select Paybill Enter : (4040299) and Account Number : ".$booking_reference.", Enter Amount you wish to pay. Thank you. Terms & Conditions Apply";
//                         $this->sendMessage($AFmessage,$reciepient);
//                    }
    
//                     $amount = $deposit;
//                     $msisdn = $valid_phone;
//                     $booking_ref = $booking_reference;

//                     $message = $this->stk_push($amount,$msisdn,$booking_ref);
                
//                     $response = $message;
//                 }
//         // If Customer  exists

//         }else{ 

//             $msisdn = ltrim($phoneNumber, '+');

//             $customer = \App\Customers::where('phone','=',$msisdn)->first();

//             $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','active')->first();

//             if($booking == null){

//             $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','pending')->first();

//             if($booking == null){

//             Log::info('Phone Number : '.$phone);

//             $booking_reference = 'BKG'.rand(10,1000000);

//             $booking_date = now();
            
//             $booking_date = strtotime($booking_date);

//             $product = \App\Products::where('product_code','=',$product_id)->first();

//             $due_date = Carbon::createFromTimestamp($booking_date)->addMonths(3);

//             if($deposit<200){
//                 $response = "END Minimum Deposit for this product is  KES".number_format(200,2);
//             }else {


//             if($agentTrue == 1){
//             $booking = new \App\Bookings();
//             $booking->customer_id = $existingCustomer->id; 
//             $booking->product_id  = $product->id;
//             $booking->booking_reference = $booking_reference;
//             $booking->quantity  = '1';
//             $booking->agent_code = $agent_code;
//             $booking->amount_paid = '0';
//             $booking->balance = $product->product_price;
//             $booking->payment_mode  = 'Mpesa';
//             $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//             $booking->due_date = $due_date;
//             $booking->status = "pending";
//             $booking->total_cost = $product->product_price;
//             $booking->save();
//             }

//             if($vendorTrue == 1){

//             $booking = new \App\Bookings();
//             $booking->customer_id = $existingCustomer->id; 
//             $booking->product_id  = $product->id;
//             $booking->booking_reference = $booking_reference;
//             $booking->quantity  = '1';
//             $booking->vendor_code = $agent_code;
//             $booking->amount_paid = '0';
//             $booking->balance = $product->product_price;
//             $booking->payment_mode  = 'Mpesa';
//             $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//             $booking->due_date = $due_date;
//             $booking->status = "pending";
//             $booking->total_cost = $product->product_price;
//             $booking->save();

//             }

//             $amount = $deposit;
//             $msisdn = $valid_phone;
//             $booking_ref = $booking_reference;

//             $message = $this->stk_push($amount,$msisdn,$booking_ref);
                
//             $response = $message;
//           }
            
//         }else{
//             $response = "END You Already have an ongoing booking. You can't make another booking."; 
//         }
//      }
//     }

        

//     } else if($text == "1*1") { 

//         $msisdn = ltrim($phoneNumber, '+');

//         $customer = \App\Customers::where('phone','=',$msisdn)->first();

//        if($customer == null){
//          $response = "END Sorry you don't have an account. Please Use Option 2."; 
//        }else{
//         $booking = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','active')->first();

//         if($booking == null){
//          $response = "CON Enter Agent/Vendor Code. \n";
//         }else{
//             $response = "END You Already have an ongoing booking. You can't make another booking."; 
//         }
//     }

//     }else if ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 1 && $level == 3) {

//         $agent_code = $ussd_string_exploded[2];

//         $pattern = "/ag/i";

//         $agentTrue = preg_match($pattern,$agent_code);
    
//         $vpattern = "/vd/i";
    
//         $vendorTrue = preg_match($vpattern,$agent_code);

//                 if($agentTrue == 1){
//                     $existingAgent = \App\Agents::where('agent_code','=',$agent_code)->first();
                
//                     if($existingAgent === null){

//                         $response = "END Agent Code entered does not exist.";
                    
//                     }else{

//                         $response = "CON Enter Product Code. \n";
//                     }
//                 }

//                 if($vendorTrue == 1){

//                     $existingVendor = \App\Vendor::where('vendor_code','=',$agent_code)->first();
                
//                     if($existingAgent === null){
        
//                         $response = "END Vendor Code entered does not exist.";
                    
//                     }else{
        
//                         $response = "CON Enter Product Code. \n";
//                     }
//                 }
    
//         }else if ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 1 && $level == 4) {

//             $product_code = $ussd_string_exploded[3];

//             $product = \App\Products::where('product_code','=',$product_code)->first();

//         if($product === null){
//           $response = "END Product Code Entered does not exist.";
//         }else{

//             if($product->product_price < 5000){
//                 $minDeposit = 0.2*$product->product_price;
//             }else {
//                 $minDeposit = 0.1 *$product->product_price;
//             }

//             $response = "CON  Minimum Deposit Amount for this Product is : KES ".number_format($minDeposit,2)."\n"." Please enter Initial deposit.";
//         }

//     }else if ($ussd_string_exploded[0] == 1 && $ussd_string_exploded[1] == 1 && $level == 5) {


//     $booking_reference = 'BKG'.rand(10,1000000);

//     $deposit = $ussd_string_exploded[4];

//     $booking_date = now();

//     $phone =  $phoneNumber;

//     $agent_code = $ussd_string_exploded[2];

//     $pattern = "/ag/i";

//     $agentTrue = preg_match($pattern,$agent_code);

//     $vpattern = "/vd/i";

//     $vendorTrue = preg_match($vpattern,$agent_code);

//     $booking_date = strtotime($booking_date);

//     $product_code = $ussd_string_exploded[3];

//     $product = \App\Products::where('product_code','=',$product_code)->first();

//     $due_date = Carbon::createFromTimestamp($booking_date)->addMonths(3);


//     $valid_phone = ltrim($phoneNumber, '+');


//     $customer = \App\Customers::where('phone','=',$valid_phone)->first();

//     if($product->product_price < 5000){
//         $minDeposit = 0.2*$product->product_price;
//     }else {
//         $minDeposit = 0.1 *$product->product_price;
//     }

//     if($deposit<200){
//         $response = "END Minimum Deposit for this product is  KES".number_format(200,2);
//     }else {

//     if($agentTrue == 1){
//         $booking = new \App\Bookings();
//         $booking->customer_id = $customer->id; 
//         $booking->product_id  = $product->id;
//         $booking->booking_reference = $booking_reference;
//         $booking->quantity  = '1';
//         $booking->agent_code = $agent_code;
//         $booking->amount_paid = '0';
//         $booking->balance = $product->product_price;
//         $booking->payment_mode  = 'Mpesa';
//         $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//         $booking->due_date = $due_date;
//         $booking->status = "pending";
//         $booking->total_cost = $product->product_price;

//         $booking->save();
//     }

//     if($vendorTrue == 1){
//         $booking = new \App\Bookings();
//         $booking->customer_id = $customer->id; 
//         $booking->product_id  = $product->id;
//         $booking->booking_reference = $booking_reference;
//         $booking->quantity  = '1';
//         $booking->vendor_code = $agent_code;
//         $booking->amount_paid = '0';
//         $booking->balance = $product->product_price;
//         $booking->payment_mode  = 'Mpesa';
//         $booking->date_started  = Carbon::createFromTimestamp($booking_date)->format('Y-m-d H:i:s');
//         $booking->due_date = $due_date;
//         $booking->status = "pending";
//         $booking->total_cost = $product->product_price;

//         $booking->save();
//     }

//     $reciepient = $phoneNumber;
//     $AFmessage = "Booking of : ".$product->product_name." was successful. Total Price is KES ".number_format($product->product_price,2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, select Paybill Enter : (4040299) and Account Number : ".$booking_reference.", Enter Amount you wish to pay. Thank you. Terms & Conditions Apply";
//     $this->sendMessage($AFmessage,$reciepient);

//     $amount = $deposit;
//     $msisdn = $valid_phone;
//     $booking_ref = $booking_reference;

//     $message = $this->stk_push($amount,$msisdn,$booking_ref);
            
//     $response = $message;
     
//      }


//     } else if ($text == "7") {
                
//         $response = "CON Enter Booking Reference of an item you have delivered.";

//     }else if ($ussd_string_exploded[0] == 7  && $level == 2) {

//         $booking_reference = $ussd_string_exploded[1];

//         $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->where('status','=','completed')->first();

//         if($booking === null){
//           $response = "END Booking with provided code does not exist or is not complete.";
//         }else{
//             \App\Bookings::where('booking_reference','=',$booking_reference)
//                     ->update(["status"=>"agent-delivered"]);
        
//         $response = "END Delivery Submitted successfully, Please wait for admin to approve. Thanks";
//         }

//      }else if ($text == "10") {
                
//         $response = "CON Enter Booking Reference of an item you have delivered.";

//     }else if ($ussd_string_exploded[0] == 10  && $level == 2) {

//         $booking_reference = $ussd_string_exploded[1];

//         $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->where('status','=','completed')->first();

//         if($booking === null){
//           $response = "END Booking with provided code does not exist or is not complete.";
//         }else{
//             \App\Bookings::where('booking_reference','=',$booking_reference)
//                     ->update(["status"=>"agent-delivered"]);
        
//         $response = "END Delivery Submitted successfully, Please wait for admin to approve. Thanks";
//         }

//      } else if ($text == "6") {
                
//         $response = "CON Enter Booking Reference.";

//     }else if ($ussd_string_exploded[0] == 6  && $level == 2) {

//         $booking_reference = $ussd_string_exploded[1];

//         $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->first();

//         if($booking === null){
//             $response = "END Booking Reference Entered does not exist.";
//         }else{

//             $product = \App\Products::where('id','=',$booking->product_id)->first();

//                 $response  = "CON The product that had booked is ".$product->product_name.". Amount paid is KES ".number_format($booking->amount_paid,2).". \n";
//                 $response .= "Enter Product Code of Item you wish to exchange with.";

//         }

//     }else if ($ussd_string_exploded[0] == 6  && $level == 3) {

//         $booking_reference = $ussd_string_exploded[1];

//         $product_code = $ussd_string_exploded[2];

//         $product = \App\Products::where('product_code','=',$product_code)->first();

//         if($product === null){
//           $response = "END Product Code Entered does not exist.";
//         }else{
//             $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->first();

//         $balance = $product->product_price - $booking->amount_paid;

//         $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->update([
//                         "product_id"=>$product->id,
//                         "balance"=>$balance,
//                         "total_cost"=>$product->product_price
//                         ]);

//         $response = "END Product exchanged successfully to ".$product->product_name.". New Balance is KES ".number_format($balance,2).".";
//         }
//     }else if ($text == "9") {
                
//         $response = "CON Enter Booking Reference.";

//     }else if ($ussd_string_exploded[0] == 9  && $level == 2) {

//         $booking_reference = $ussd_string_exploded[1];

//         $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->first();

//         if($booking === null){
//             $response = "END Booking Reference Entered does not exist.";
//         }else{

//             $product = \App\Products::where('id','=',$booking->product_id)->first();

//                 $response  = "CON The product that had booked is ".$product->product_name.". Amount paid is KES ".number_format($booking->amount_paid,2).". \n";
//                 $response .= "Enter Product Code of Item you wish to exchange with.";

//         }

//     }else if ($ussd_string_exploded[0] == 9  && $level == 3) {

//         $booking_reference = $ussd_string_exploded[1];

//         $product_code = $ussd_string_exploded[2];

//         $product = \App\Products::where('product_code','=',$product_code)->first();

//         if($product === null){
//           $response = "END Product Code Entered does not exist.";
//         }else{
//             $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->first();

//         $balance = $product->product_price - $booking->amount_paid;

//         $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->update([
//                         "product_id"=>$product->id,
//                         "balance"=>$balance,
//                         "total_cost"=>$product->product_price
//                         ]);

//         $response = "END Product exchanged successfully to ".$product->product_name.". New Balance is KES ".number_format($balance,2).".";
//         }
//     }else if ($ussd_string_exploded[0] == 3  && $level == 3) {

//         $booking_reference = $ussd_string_exploded[1];

//         $product_code = $ussd_string_exploded[2];

//         $product = \App\Products::where('product_code','=',$product_code)->first();

//         if($product === null){
//           $response = "END Product Code Entered does not exist.";
//         }else{
//         $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->first();

//         $balance = $product->product_price - $booking->amount_paid;

//         $booking = \App\Bookings::where('booking_reference','=',$booking_reference)->update([
//                         "product_id"=>$product->id,
//                         "balance"=>$balance,
//                         "total_cost"=>$product->product_price
//                         ]);

//         $response = "END Product exchanged successfully to ".$product->product_name.". New Balance is KES ".number_format($balance,2).".";
//         }

//     }
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



      public function make_booking($request){
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

        if ($customer!=null) {
            # code...
            $userid=$customerdata->user_id;
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
                $message = "END A Payment Prompt has been sent to the provided Phone No. Enter MPesa PIN if Prompted.";
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



    


}
