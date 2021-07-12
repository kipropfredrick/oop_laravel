<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;
use AfricasTalking\SDK\AfricasTalking;
use \App\Mail\SendNotificationMail;
use \App\Mail\SendPaymentEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendBookingMail;
use App\Mail\SendPaymentMailToAdmin;
use App\Http\Controllers\SendSMSController;
use App\Http\Controllers\pushNotification;

class MpesaPaymentController extends Controller
{
    public function generate_access_token(){;

        $consumer_key = env('CONSUMER_KEY');
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
        $access_token = $result->access_token;

        echo $access_token;

        curl_close($curl);
    }

    public function update_booking(){

        $payments =  \App\PaymentLog::where('status','unverified')->get();

        
        foreach($payments as $payment){
            $booking = \App\Bookings::where('booking_reference','=',$payment->BillRefNumber)->first();

            $amount_paid = $booking->amount_paid + $payment->TransAmount;

            $transaction_amount = $payment->TransAmount;

            $msisdn = $payment->MSISDN;

            $code = $payment->TransID;

            $bill_ref_no = $payment->BillRefNumber;

            $balance = $booking->total_cost - $amount_paid;

            $time = now();

            \App\PaymentLog::where('id',$payment->id)->update(['status'=>"valid"]);

            if($balance<1){

                DB::table('bookings')
                ->where('booking_reference','=',$bill_ref_no)
                ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'complete']);

                $product = \App\Products::with('subcategory')->where('id','=',$booking->product_id)->first();


                if($booking->agent_code !== null){
                    $agent = \App\Agents::where('agent_code','=',$booking->agent_code)->first();
                    if($agent == null){
                    }else {
                        $agent_commission = $product->product_price * ($product->subcategory->commision/100);
                        $admin_commission = $product->product_price - ($product->product_price * ($product->subcategory->commision)/100);

                        DB::table('commissions')->insert([
                            'product_id' => $product->id,
                            'booking_id' => $booking->id,
                            'agent_id' =>  $agent->id,
                            'admin_commission' =>$admin_commission,
                            'other_party_commission' => $agent_commission,
                            'created_at'=>now(),
                            'updated_at'=>now(),
                            ]);
                    }
                }elseif($booking->vendor_code !== null){
                    $vendor = \App\Vendor::where('vendor_code','=',$booking->vendor_code)->first();
                    if($vendor == null){
                        
                       }else {
                        $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                        $vendor_commission = $product->product_price - ($product->product_price * ($product->subcategory->commision/100));

                        DB::table('commissions')->insert([
                            'product_id' => $product->id,
                            'booking_id' => $booking->id,
                            'vendor_id' =>  $vendor->id,
                            'admin_commission' =>$admin_commission,
                            'other_party_commission' => $vendor_commission,
                            'created_at'=>now(),
                            'updated_at'=>now(),
                            ]);

                       }
                }elseif($booking->influencer_code !== null){
                    $influencer = \App\Influencer::where('code','=',$booking->influencer_code)->first();
                    if($influencer == null){
                        
                       }else {
                        $influencer_commission = ($product->product_price - $product->buying_price) * ($influencer->commission/100);

                        DB::table('influencer_commissions')->insert([
                            'product_id' => $product->id,
                            'booking_id' => $booking->id,
                            'influencer_id' =>  $influencer->id,
                            'commission' =>$influencer_commission,
                            'created_at'=>now(),
                            'updated_at'=>now(),
                            ]);

                        $influencer_t = \App\InfluencerCommissionTotal::where('influencer_id',$influencer->id)->first();

                        $data = [];
                        $data['total_commission'] = $influencer_t->total_commission + $influencer_commission;
                        $data['pending_payment'] = $influencer_t->pending_payment + $influencer_commission;
                        \App\InfluencerCommissionTotal::where('influencer_id',$influencer->id)->update($data);
                       }
                }


               

            }else{

                DB::table('bookings')
                ->where('booking_reference','=',$bill_ref_no)
                ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'active']);
            }
                $payment = new \App\Payments();
                $payment->booking_id = $booking->id;
                $payment->customer_id = $booking->customer_id; 
                $payment->product_id  = $booking->product_id;
                $payment->transaction_amount = $transaction_amount;
                $payment->booking_status = 'active';
                $payment->date_paid = $time;
                $payment->save();

                $payment_id = DB::getPdo()->lastInsertId();

            DB::table('mpesapayments')
                ->insert([
                         'payment_id'=>$payment_id,
                         'phone'=>$msisdn,
                          'amount_paid'=>$transaction_amount,
                          'phone'=>$msisdn,
                          'transac_code'=>$code,
                          'created_at'=>$time,
                          'date_paid'=>$time,
                          'updated_at'=>$time
                          ]);

            $message = "Success";
            

            // Set the numbers you want to send to in international format
            $recipients =$msisdn;

            // Set your message
            $transaction_amount = number_format($transaction_amount,2);
            $balance =number_format($balance,2);

            // Set your message
            $message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}.Download our app to easily track your payments - http://bit.ly/MosMosApp.";

            // Set your shortCode or senderId
            $from       = "Mosmos";

            try {
                // Thats it, hit send and we'll take care of the rest
                $result = $sms->send([
                    'to'      => $recipients,
                    'from'=>$from,
                    'message' => $message,
                ]);

            } catch (Exception $e) {
                echo "Error: ".$e->getMessage();
            }

            


        }

    }

    private function getClientIP() {

        if (isset($_SERVER)) {
    
            if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
                return $_SERVER["HTTP_X_FORWARDED_FOR"];
    
            if (isset($_SERVER["HTTP_CLIENT_IP"]))
                return $_SERVER["HTTP_CLIENT_IP"];
    
            return $_SERVER["REMOTE_ADDR"];
        }
    
        if (getenv('HTTP_X_FORWARDED_FOR'))
            return getenv('HTTP_X_FORWARDED_FOR');
    
        if (getenv('HTTP_CLIENT_IP'))
            return getenv('HTTP_CLIENT_IP');
    
        return getenv('REMOTE_ADDR');
    }

    

    public function mpesapayment(Request $request){
        
        $mpesaResponse = file_get_contents('php://input');

        \Log::info('mpesaResponse =>'.print_r($mpesaResponse,1));

        $decoded = json_decode($mpesaResponse);

        $valid_ips = [
                    '196.201.212.127',
                    '196.201.212.129',
                    '196.201.212.136',
                    '196.201.212.138',
                    '196.201.214.200',
                    '196.201.214.206',
                    '196.201.214.208'
                    ];


        $serve_ip = $this->getClientIP();

        \Log::info('IP Adress =>'.$serve_ip);


     if( $mpesaResponse != null){

            $transaction_type = isset($decoded->TransactionType) ? $decoded->TransactionType : "Pay Bill";;
            $transaction_id = $decoded->TransID;
            $transaction_time = $decoded->TransTime;
            $transaction_amount = $decoded->TransAmount;
            $business_code = $decoded->BusinessShortCode;
            $bill_ref_no = $decoded->BillRefNumber;
            $invoice_number = $decoded->InvoiceNumber;
            $org_account_balance = $decoded->OrgAccountBalance;
            $third_party_trans_id = $decoded->ThirdPartyTransID;
            $msisdn = $decoded->MSISDN;
            $first_name = $decoded->FirstName;
            $middle_name = $decoded->MiddleName;
            $last_name = $decoded->LastName;
            $code = $decoded->TransID;

            $paymentLog = (array) $decoded;

            
            $travelPattern = "/tt/i";
    
            $travelTrue = preg_match($travelPattern,$bill_ref_no);

            if($travelTrue ==1){

                $existingLog = \DB::connection('mysql2')->table('payment_logs')->where('TransID',$transaction_id)->first();

                if($existingLog!=null){
    
                    return "Duplicate Transaction";
    
                }

                $paymentLog['created_at'] = now();
                $paymentLog['updated_at'] = now();

                \DB::connection('mysql2')->table('payment_logs')->insert( $paymentLog);

                $log_id = DB::connection('mysql2')->getPdo()->lastInsertId();

                $message = $this->validateTravelPayments($bill_ref_no,$transaction_amount,$msisdn,$first_name,$middle_name,$last_name,$code,$log_id);

                return $message;

            }
               $mosmosaccountpattern="/MID/i";
  $mosmosTrue = preg_match($mosmosaccountpattern,$bill_ref_no);

            if($mosmosTrue ==1){
$user=\App\User::whereMosmosid($bill_ref_no);
$obj=$user->first();
if($obj!=null){
    $balance=$obj->balance;
$balance=$balance+$transaction_amount;
$user->update(["balance"=>$balance]);

        for($i=0;$i<1000000;$i++){
            $transid = 'TT'.rand(10000,99999)."M";
            $res=\App\topups::whereTransid($transid)->first();
            if ($res==null) {             # code...
break;  }
          
        }

$credentials=Array("amount"=>$transaction_amount,"balance"=>$balance,"transid"=>$transid,"sender"=>$obj->id);
\App\topups::create($credentials);

  $obj = new pushNotification();
    $data=Array("name"=>"home","value"=>"home");
    $obj->exceuteSendNotification($token,"Buy Airtime and pay utility bills at KSh.0 transaction cost."," Wallet top-up successful!",$data);

}

return "true";
            }

            $existingLog = \App\PaymentLog::where('TransID',$transaction_id)->first();

            if($existingLog!=null){

                return "Duplicate Transaction";

            }

            \App\PaymentLog::insert($paymentLog);

            $log_id = DB::getPdo()->lastInsertId();


          \App\PaymentLog::where('id',$log_id)->update(['status'=>"valid"]);

        //   Log::info(print_r($mpesaResponse,true));

            $booking = \App\Bookings::with('product','payments','customer','customer.user','county','location')->where('booking_reference','=',$bill_ref_no)->first();

            if($booking == null){
                return "Booking Does not exist!";
            }
       

            if($booking->status == 'pending'){
                if($booking->agent_code !== null){
                    $agent = \App\Agents::where('agent_code','=',$booking->agent_code)->first();
                    if($agent == null){

                    }else { 

                        $recipients = $agent->phone;

                        $details = [
                            'customer'=> $booking->customer->user->name,
                            'booking_reference'=>$booking->booking_reference

                        ];

                        Mail::to($vendor->user->email)->send(new SendBookingMail($details));

                    }
                }elseif($booking->vendor_code !== null){
                    $vendor = \App\Vendor::where('vendor_code','=',$booking->vendor_code)->first();
                    if($vendor == null){
                        
                       }else {

                        $recipients = $vendor->phone;

                        $details = [
                            'customer'=> $booking->customer->user->name,
                            'booking_reference'=>$booking->booking_reference

                        ];

                        Mail::to($vendor->user->email)->send(new SendBookingMail($details));

                     }
                }
            }

            $payment = new \App\Payments();
            $payment->booking_id = $booking->id;
            $payment->customer_id = $booking->customer_id; 
            $payment->product_id  = $booking->product_id;
            $payment->transaction_amount = $transaction_amount;
            $payment->booking_status = 'active';
            $payment->date_paid = now();
            $payment->save();

            $payment_id = DB::getPdo()->lastInsertId();

            $amount_paid = $booking->amount_paid + $transaction_amount;

            $balance = $booking->total_cost - $amount_paid;

            if($balance<1){

                DB::table('bookings')
                ->where('booking_reference','=',$bill_ref_no)
                ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'complete','updated_at'=>now()]);

                $recipients = $booking->customer->phone;

                if($booking->location_type = 'store_pickup'){

                    if($booking->vendor_code !== null){

                        if($vendor == null){
                        
                        }else {
                            $location = " Your will pick your product at ".$vendor->location;
                        }

                    }elseif($booking->agent_code !== null){

                        $agent = \App\Agents::where('agent_code','=',$booking->agent_code)->first();
                        if($agent == null){

                        }else {
                         $location = " Your will pick your product at ".$agent->location;
                        }

                    }

                }

                if($booking->location_type = 'outside_nairobi'){

                    $location = " Your product will be delivered at ". $booking->location. ($booking->county." County ");

                }

                if($booking->location_type = 'home_or_office'){
                    $location = " Your product will be delivered at ". $booking->delivery_location;
                }

                $message = "Congratulations, You have completed Payment for ".$booking->product->product_name.", You will be contacted to finalise your delivery.";

                SendSMSController::sendMessage($recipients,$message,$type="booking_completed_notification");


  $token=\App\User::whereId($booking->customer->user->id)->first()->token;
    if ($token==null) {
        # code...
 
    }
    else{
    $obj = new pushNotification();
    $data=Array("name"=>"complete","value"=>"View Orders");
    $obj->exceuteSendNotification($token,"You have completed payment for ".$booking->product->product_name,"Congratulations",$data);
        }

                $product = \App\Products::with('subcategory')->where('id','=',$booking->product_id)->first();


                if($booking->agent_code !== null){
                    $agent = \App\Agents::where('agent_code','=',$booking->agent_code)->first();
                    if($agent == null){

                    }else {
                        $agent_commission = $product->product_price * ($product->subcategory->commision/100);
                        $admin_commission = $product->product_price * ((100-$product->subcategory->commision)/100);

                        $recipients = $agent->phone;

                        $message = $booking->customer->user->name . " has completed payment of booking ref ".$booking->booking_reference;

                        SendSMSController::sendMessage($recipients,$message,$type="booking_completed_notification");

                        DB::table('commissions')->insert([
                            'product_id' => $product->id,
                            'booking_id' => $booking->id,
                            'agent_id' =>  $agent->id,
                            'admin_commission' =>$admin_commission,
                            'other_party_commission' => $agent_commission
                            ]);
                    }
                }elseif($booking->vendor_code !== null){
                    $vendor = \App\Vendor::where('vendor_code','=',$booking->vendor_code)->first();
                    if($vendor == null){
                        
                       }else {
                        $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                        $vendor_commission = $product->product_price * ((100-$product->subcategory->commision)/100);

                        $recipients = $vendor->phone;

                        $message = $booking->customer->user->name . " has completed payment of booking ref ".$booking->booking_reference;

                        SendSMSController::sendMessage($recipients,$message,$type="booking_completed_notification");

                        DB::table('commissions')->insert([
                            'product_id' => $product->id,
                            'booking_id' => $booking->id,
                            'vendor_id' =>  $vendor->id,
                            'admin_commission' =>$admin_commission,
                            'other_party_commission' => $vendor_commission,
                            'created_at'=>now(),
                            'updated_at'=>now(),
                            ]);

                       }
                }
                
            }else{

                DB::table('bookings')
                ->where('booking_reference','=',$bill_ref_no)
                ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'active']);
            }
            

            DB::table('mpesapayments')
                ->insert([
                          'payment_id'=>$payment_id,
                          'phone'=>$msisdn,
                          'amount_paid'=>$transaction_amount,
                          'phone'=>$msisdn,
                          'transac_code'=>$code,
                          'date_paid'=>$transaction_time,
                          'created_at'=>now(),
                          'updated_at'=>now()
                          ]);

            $message = 'Success';

            $recipients = $recipients = $booking->customer->phone;

            $transaction_amount = number_format($transaction_amount,2);
            $balance =number_format($balance,2);

            $payment_count = \App\PaymentLog::where('BillRefNumber',$bill_ref_no)->count();

            if($payment_count<2){
                $shipping_cost = $booking->shipping_cost;
                //$message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}. Incl delivery cost of KES .{$shipping_cost}.Download our app to easily track your payments - http://bit.ly/MosMosApp.";

                $message="Payment of KSh.{$transaction_amount} for {$bill_ref_no} received. Txn. {$code}. Bal is KSh.{$balance} incl delivery cost. Download our app to easily track your payments - http://bit.ly/MosMosApp";

                $result=DB::table("monitorpay")->get();
                if (count($result)==0) {
                    DB::table("monitorpay")->insert(["total"=>1,"mobile"=>0]);
                }
                else{
                    $total=intval($result[0]->total)+1;
                    DB::table("monitorpay")->update(["total"=>$total]);
                }



            }else{

                // $message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}.Download our app to easily track your payments - http://bit.ly/MosMosApp." ;
                $message="Payment of KES. {$transaction_amount} for {$bill_ref_no} received. Txn.{$code}. Bal is KSh. {$balance}.Download our app to easily track your payments - http://bit.ly/MosMosApp";
                   $result=DB::table("monitorpay")->get();
                if (count($result)==0) {
                    DB::table("monitorpay")->insert(["total"=>1,"mobile"=>0]);
                }
                else{
                    $total=intval($result[0]->total)+1;
                    DB::table("monitorpay")->update(["total"=>$total]);
                }

            }   

            SendSMSController::sendMessage($recipients,$message,$type="payment_notification");

            $details = [
                'customer'=> $booking->customer->user->name,
                'booking_reference'=>$booking->booking_reference,
                'amount_paid'=>$transaction_amount,
                'product'=>$booking->product->product_name,
                'mpesa_ref'=>$transaction_id,
                'balance'=> $balance

            ];

            Mail::to('order@mosmos.co.ke')->send(new SendPaymentMailToAdmin($details));

            $booking = \App\Bookings::with('product','payments','payments.mpesapayment','customer','customer.user','county','location')->where('booking_reference','=',$bill_ref_no)->first();

            $latestPayment = \App\Payments::with('mpesapayment')->where('booking_id',$booking->id)->latest()->first();

            $details  = [
                'customer_name'=>$booking->customer->user->name,
                'product_name'=>$booking->product->product_name,
                'booking_reference'=>$booking->booking_reference,
                'total_cost'=>number_format($booking->total_cost,2),
                'amount_paid'=>number_format($booking->amount_paid),
                'balance'=>$balance,
                'product_price'=>number_format($booking->product->product_price),
                'payments'=>$booking->payments,
                'latestPayment'=>$latestPayment
            ];

            Mail::to($booking->customer->user->email)->send(new SendPaymentEmail($details));
          // $user_id=\App\Customers::whereId($booking->customer_id)->first()->user_id;

   $token=\App\User::whereId($booking->customer->user->id)->first()->token;
    if ($token==null) {
        # code...
    
    }
    else{
    $obj = new pushNotification();
    $data=Array("name"=>"payment","value"=>"Payments");
    $obj->exceuteSendNotification($token,"Your payment of KSh.".$transaction_amount ." for Order Ref ".$bill_ref_no." has been received.","Payment Received",$data);

            
        $message = "Success!";
}

    }else{
        $message = "No Data from Safaricom";

    }
    return response()->json($message);
    
    }

    public function validation_url(){

        $header = ('Content-Type:application/json');

        $response ='{
            "ResultCode":0,
            "ResultDesc":"Confirmation Recieved:
        }';

        $mpesaResponse = file_get_contents('php://input');

        Log::info("Got mpesa from Safaricom (validation_url)".$mpesaResponse);

        Log::info(print_r($mpesaResponse,true));

        if( $mpesaResponse != null){

            $decoded = json_decode($mpesaResponse);

            $transaction_type = $decoded->TransactionType;
            $transaction_id = $decoded->TransID;
            $transaction_time = $decoded->TransTime;
            $transaction_amount = $decoded->TransAmount;
            $business_code = $decoded->BusinessShortCode;
            $bill_ref_no = $decoded->BillRefNumber;
            $invoice_number = $decoded->InvoiceNumber;
            $org_account_balance = $decoded->OrgAccountBalance;
            $third_party_trans_id = $decoded->ThirdPartyTransID;
            $msisdn = $decoded->MSISDN;
            $first_name = $decoded->FirstName;
            $middle_name = $decoded->MiddleName;
            $last_name = $decoded->LastName;
            $code = $decoded->TransID;

            $existingLog = \App\PaymentLog::where('TransID',$code)->first();

            if($existingLog!=null){

                return "Duplicate Transaction";

            }


            $booking = \App\Bookings::where('booking_reference','=',$bill_ref_no)->first();

            $amount_paid = $booking->amount_paid + $transaction_amount;

            $balance = $booking->total_cost - $amount_paid;

            $time = now();


            if($balance<1){

                DB::table('bookings')
                ->where('booking_reference','=',$bill_ref_no)
                ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'complete']);

                $product = \App\Products::with('subcategory')->where('id','=',$booking->product_id)->first();


                if($booking->agent_code !== null){
                    $agent = \App\Agents::where('agent_code','=',$booking->agent_code)->first();
                    if($agent == null){
                    }else {
                        $agent_commission = $product->product_price * ($product->subcategory->commision/100);
                        $admin_commission = $product->product_price - ($product->product_price * ($product->subcategory->commision)/100);

                        DB::table('commissions')->insert([
                            'product_id' => $product->id,
                            'booking_id' => $booking->id,
                            'agent_id' =>  $agent->id,
                            'admin_commission' =>$admin_commission,
                            'other_party_commission' => $agent_commission,
                            'created_at'=>now(),
                            'updated_at'=>now(),
                            ]);
                    }
                }elseif($booking->vendor_code !== null){
                    $vendor = \App\Vendor::where('vendor_code','=',$booking->vendor_code)->first();
                    if($vendor == null){
                        
                       }else {
                        $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                        $vendor_commission = $product->product_price - ($product->product_price * ($product->subcategory->commision/100));

                        DB::table('commissions')->insert([
                            'product_id' => $product->id,
                            'booking_id' => $booking->id,
                            'vendor_id' =>  $vendor->id,
                            'admin_commission' =>$admin_commission,
                            'other_party_commission' => $vendor_commission,
                            'created_at'=>now(),
                            'updated_at'=>now(),
                            ]);

                       }
                }elseif($booking->influencer_code !== null){
                    $influencer = \App\Influencer::where('code','=',$booking->influencer_code)->first();
                    if($influencer == null){
                        
                       }else {
                        $influencer_commission = ($product->product_price - $product->buying_price) * ($influencer->commission/100);

                        DB::table('influencer_commissions')->insert([
                            'product_id' => $product->id,
                            'booking_id' => $booking->id,
                            'influencer_id' =>  $influencer->id,
                            'commission' =>$influencer_commission,
                            'created_at'=>now(),
                            'updated_at'=>now(),
                            ]);

                        $influencer_t = \App\InfluencerCommissionTotal::where('influencer_id',$influencer->id)->first();

                        $data = [];
                        $data['total_commission'] = $influencer_t->total_commission + $influencer_commission;
                        $data['pending_payment'] = $influencer_t->pending_payment + $influencer_commission;
                        \App\InfluencerCommissionTotal::where('influencer_id',$influencer->id)->update($data);
                       }
                }


               

            }else{

                DB::table('bookings')
                ->where('booking_reference','=',$bill_ref_no)
                ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'active']);
            }
                $payment = new \App\Payments();
                $payment->booking_id = $booking->id;
                $payment->customer_id = $booking->customer_id; 
                $payment->product_id  = $booking->product_id;
                $payment->transaction_amount = $transaction_amount;
                $payment->booking_status = 'active';
                $payment->date_paid = $time;
                $payment->save();

                $payment_id = DB::getPdo()->lastInsertId();

            DB::table('mpesapayments')
                ->insert([
                         'payment_id'=>$payment_id,
                         'phone'=>$msisdn,
                          'amount_paid'=>$transaction_amount,
                          'phone'=>$msisdn,
                          'transac_code'=>$code,
                          'created_at'=>$time,
                          'date_paid'=>$time,
                          'updated_at'=>$time
                          ]);

            $message = "Success";
            
            $recipients =$msisdn;
            
            $transaction_amount = number_format($transaction_amount,2);
            $balance =number_format($balance,2);
            
            $message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}.Download our app to easily track your payments - http://bit.ly/MosMosApp." ;
           
            SendSMSController::sendMessage($recipients,$message,$type="payment_notification");

        }else{
            $message = "No Data from Safaricom";
            return response()->json($message);
        }

        
        
        // return $json_response;
    }

    public function register_url(){

        $consumer_key = env('CONSUMER_KEY');
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

        // return array($result);

        $access_token = $result->access_token;

        \Log::info('Access token response =>'.json_encode($result));

        curl_close($curl);

        $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';
  
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header
        
        $curl_post_data = array(
            //Fill in the request parameters with valid values
            'ShortCode' => env('MPESA_SHORT_CODE'),
            'ResponseType' => 'Completed',
            'ConfirmationURL' => 'https://mosmos.co.ke/c2b/confirm-7CavgY5gFFwzktQH6XjcS2',
            'ValidationURL' => 'https://mosmos.co.ke/c2b/validate-UjQerTLb4EM78rHBSmYgCG'
        );

        \Log::info('Post data =>'.json_encode($curl_post_data));
        
        $data_string = json_encode($curl_post_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
        
        $curl_response = curl_exec($curl);
        echo $curl_response;
    }

    public function checkpayment(Request $request){

        $payment = \DB::table('payment_logs')->where('BillRefNumber',$request->payment_ref)->first();

        $out = [];


        if($payment !=null){

            $booking = \App\Bookings::with('product')->where('booking_reference',$request->payment_ref)->first();

            $message = "Hello ".$payment->FirstName."We have received your payment of of Ksh ".number_format($payment->TransAmount,2)." for product ".$booking->product->product_name.', Mpesa reference code is '.$payment->TransID.".";
            
            $out['status'] = 1;
            $out['amount'] = number_format($payment->TransAmount,2);
            $out['name'] = $payment->FirstName;
            $out['product'] = $booking->product->product_name;
            $out['mpesa_ref'] = $payment->TransID;
            $out['message'] = $message;
        }else{
            $out['status'] = 0;
            $out['amount'] = null;
        }
        return $out;

    }

    public function simulate_payment(Request $request){

        $consumer_key = env('CONSUMER_KEY');
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

        $access_token = $result->access_token;

        // return array($access_token);

        $url = 'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate';
  
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json','Authorization:Bearer '.$access_token)); //setting custom header
    
    
        $curl_post_data = array(
                //Fill in the request parameters with valid values
            'ShortCode' => env('MPESA_SHORT_CODE'),
            'CommandID' => 'CustomerPayBillOnline',
            'Amount' => $request->amount,
            'Msisdn' => $request->msisdn,
            'BillRefNumber' => $request->booking_ref,
        );
    
        $data_string = json_encode($curl_post_data);
    
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);
    
        $curl_response = curl_exec($curl);
        print_r($curl_response);
    
        // echo $curl_response;

        return response()->json($curl_response);

        }

        public function lipaNaMpesaPassword($lipa_time)
        {
           
            $passkey = env('STK_PASSKEY');
            $BusinessShortCode = env('MPESA_SHORT_CODE');
            $timestamp =$lipa_time;
            $lipa_na_mpesa_password = base64_encode($BusinessShortCode.$passkey.$timestamp);
            return $lipa_na_mpesa_password;
        }

        public function stkPush(Request $request)
        {

        Log::info("Logging the request");
        Log::info($request->all());
        $amount = $request->amount;
        $msisdn = $request->msisdn;
        $booking_ref = $request->booking_ref;
 
        $consumer_key = env('CONSUMER_KEY');
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

        // $apiPassword = $BusinessShortCode.$passkey.$timestamp;
        $lipa_time = Carbon::rawParse('now')->format('YmdHms');

        $apiPassword = $this->lipaNaMpesaPassword($lipa_time);

        // return response()->json($apiPassword);

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
            'CallBackURL'       => 'https://mosmos.co.ke/confirmation-url',
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

    public function validateTravelPayments($bill_ref_no,$transaction_amount,$msisdn,$first_name,$middle_name,$last_name,$code,$log_id){

       $booking = DB::connection('mysql2')->table('bookings')->where('booking_reference','=',$bill_ref_no)->first();
       
       if($booking == null){
           return "Booking Does not exist!";
       }else{
           
        
          $customer = DB::connection('mysql2')->table('customers')->where('id',$booking->customer_id)->first();

          $recipients = $customer->phone;

          $payment_data = [
                            'payment_log_id'=>$log_id,
                            'customer_id'=>$customer->id,
                            'booking_id'=>$booking->id,
                            'amount'=>$transaction_amount,
                            'created_at'=>now(),
                            'updated_at'=>now()
                          ];

          DB::connection('mysql2')->table('payments')->insert($payment_data);

          $package = DB::connection('mysql2')->table('travel_packages')->where('id',$booking->package_id)->first();

          $amount_paid = $booking->amount_paid + $transaction_amount;

          $balance = $booking->balance - $transaction_amount;

          $data = ['amount_paid'=>$amount_paid,'balance'=>$balance,'status'=>'active'];

          $message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}.Download our app to easily track your payments - http://bit.ly/MosMosApp.";
         
          SendSMSController::sendMessage($recipients,$message,$type="payment_notification");

          if($balance<1){

            $message = "Congratulations, You have completed Payment for ".$package->package_name.".";

            SendSMSController::sendMessage($recipients,$message,$type="payment_completion_notification");

            $data['status'] = 'complete';

          }

          DB::connection('mysql2')->table('bookings')->where('booking_reference','=',$bill_ref_no)->update($data);

          return "Success";

       }

    }

}
