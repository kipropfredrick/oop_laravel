<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use DB;
use Carbon\Carbon;
use AfricasTalking\SDK\AfricasTalking;
use \App\Mail\SendNotificationMail;
use \App\Mail\SendPaymentEmail;
use \App\Mail\SendTravelPaymentEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendBookingMail;
use App\Mail\SendPaymentMailToAdmin;
use App\Http\Controllers\SendSMSController;
use App\Http\Controllers\pushNotification;

use App\User;
use App\Customers;
use App\topups;
use App\Http\Controllers\autApi;
use App\Http\Controllers\TopupsController;
use App\Http\Controllers\paybills;
use App\Http\Controllers\AES;

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
                    if ($vendor->category=="1") {
    # code...
        $fixed_cost_subcategories=$vendor->fixed_cost_subcategories;

                 $array=json_decode($fixed_cost_subcategories,true);
        
                 $i=0;
                 $checked=false;
foreach ($array as $key => $value) {
    # code...

    if ($value['id']==$request->subcategory) {
        # code...
      $commission_rate=$value['commission_rate'];
                    $commision_cap=$value['commission_cap'];
$checked=true;

    }

}

if (!$checked) {
    $commission_rate=0;
    $commision_cap=0;
}

}
else{

                    $commission_rate=$vendor->commission_rate;
                    $commision_cap=$vendor->commission_cap;
                   
}
                    $admin_commission=floatval($product->product_price)*($commission_rate/100);
                    if ($admin_commission>=$commision_cap) {
                    $admin_commission=$commision_cap;
                    # code...
                    }
                    $vendor_commission=floatval($product->product_price)-$admin_commission;

                    // $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                    // $vendor_commission = $product->product_price * ((100-$product->subcategory->commision)/100);

                        // $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                        // $vendor_commission = $product->product_price - ($product->product_price * ($product->subcategory->commision/100));

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
            $message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}. Download our app to easily track your payments - http://bit.ly/MosMosApp.";

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


            $sms_credit_payment = \DB::connection('mysql2')->table('travel_agents')->where('code',$bill_ref_no)->first();

            $invoice_payment = \DB::connection('mysql2')->table('invoices')->where('ref',$bill_ref_no)->first();

            $travelPattern = "/t/i";
    
            $travelTrue = preg_match($travelPattern,$bill_ref_no);

            if($travelTrue ==1 || !empty($sms_credit_payment) || !empty($invoice_payment)){

                $existingLog = \DB::connection('mysql2')->table('payment_logs')->where('TransID',$transaction_id)->first();

                if($existingLog!=null){
    
                    return "Duplicate Transaction";
    
                }

                if(!empty($sms_credit_payment)){

                    $paymentLog['TransactionType'] = "SMS Credit Topup";
      
                  }

                $paymentLog['created_at'] = now();
                $paymentLog['updated_at'] = now();

                \DB::connection('mysql2')->table('payment_logs')->insert( $paymentLog);

                $log_id = DB::connection('mysql2')->getPdo()->lastInsertId();
Log::info("checkpoint0");
                $message = $this->validateTravelPayments($bill_ref_no,$transaction_amount,$msisdn,$first_name,$middle_name,$last_name,$code,$log_id,$transaction_id);

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
    $obj->exceuteSendNotification($user->first()->token,"Buy Airtime and pay utility bills at KSh.0 transaction cost."," Wallet top-up successful!",$data);

}

return "true";
            }

$ismobiletopup="/254/i";
$mob='/^(0)\d{9}$/';
$mob1='/^(\+254)\d{9}$/';
$saf="/SAF/i";
$tel="/TEL/i";
$air="/AIR/i";

$ismobiletopuptrue = preg_match($ismobiletopup,$bill_ref_no);
      if ($ismobiletopuptrue || preg_match($mob,$bill_ref_no) || preg_match($saf,$bill_ref_no) || preg_match($tel,$bill_ref_no)|| preg_match($air,$bill_ref_no) ||  preg_match($mob1,$bill_ref_no)) {

  $existingLog = \App\BillpaymentLogs::where('TransID',$transaction_id)->first();

            if($existingLog!=null){

                return "Duplicate Transaction";

            }

            \App\BillpaymentLogs::insert($paymentLog);

            $log_id = DB::getPdo()->lastInsertId();


        $productcode="";
        $recipient="";

  # code...i
        if ($ismobiletopuptrue || preg_match($mob,$bill_ref_no) || preg_match($mob1,$bill_ref_no)) {
            # code...

// if ($ismobiletopuptrue) {
//      Log::info("num 1");
//    $recipient="0".substr($bill_ref_no, 3);
// }
// else if (preg_match($mob1,$bill_ref_no)) {
//      Log::info("num 2");
//    $recipient="0".substr($bill_ref_no, 4);
// }
// else{
//      Log::info("num 3");
//     $recipient=$bill_ref_no;
// }

  list($msisdn1, $network) = $this->get_msisdn_network($bill_ref_no);

        if (!$msisdn1){
             Log::info("Invalid Phone Number");

              return Array("data"=>Array("response"=>"Invalid Phone Number"),"error"=>true);
        }else{
            $mobilerec = "0".substr($msisdn1, 3);
            
            $valid_phone=$msisdn1;
         
        }




// $obz=new TopupsController();
// $response= json_decode($obz->phonelookup(substr($recipient,1, 3)));

if ($msisdn1) {
  # code...
  $operator=$network;

    if ($operator=="safaricom") {
    # code...
    $productcode="SF01";
    Log::info("safaricom");

  }
  else if ($operator=="airtel") {
    $productcode="AP01";
      Log::info("airtel");

  }
  else if ($operator=="telkom") {
    # code...
      Log::info("telkom");
    $productcode="OP01";
  }
}
else{
 Log::info("no telco");
  return Array("data"=>Array("response"=>"Mobile Operator Not Supported".$mobilerec),"error"=>true);

}





        }
        else{
              if (preg_match($saf,$bill_ref_no)) {
    # code...
    $productcode="SF01";

  }
  else if ( preg_match($air,$bill_ref_no)) {
    $productcode="AP01";

  }
  else if ( preg_match($tel,$bill_ref_no)) {
    # code...
    $productcode="OP01";
  }
   Log::info("product code".$productcode);
        }



                # code...
                
//  $username = env('AFRIUSERNAME'); // use 'sandbox' for development in the test environment
// $apiKey   =env('AFRIAPIKEY');

// $AT       = new AfricasTalking($username, $apiKey);

// $airtime = $AT->airtime();
// $array=Array("recipients"=>[Array('phoneNumber' => "+".$bill_ref_no,
// 'currencyCode' => "KES",
// 'amount' => $transaction_amount)]);

// $result   = $airtime->send($array);
// \Log::info(json_encode($result));
// return back()->with("error","An Error Occured, check details and Try Again");

$paybillobj = new paybills();
$array=Array("PhoneNumber"=>$this->getphone($bill_ref_no),"Amount"=>$transaction_amount*100,"ProductCode"=>$productcode);

$res=$paybillobj->AirtimeTopUp($array);

  Log::info($res);
 $decdata=json_decode($res);
  Log::info("product code".$productcode);

if ($decdata==null) {
  # code...
     Log::info("returned null");
    //log the transaction into the database
  return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}
 Log::info("passsed");

$userid=$msisdn;
$customer=\App\Customers::wherePhone($msisdn)->first();
if ($customer!=null) {

    $user=\App\User::whereId($customer->user_id)->first();
    $userid=$user->id;
    # code...
}
if (($decdata->ResponseCode)=="000")  {
 $credentials=Array("amount"=>$transaction_amount,"balance"=>0,"transid"=>$transaction_id,"sender"=>$userid,"type"=>"airtime");
 \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
\App\topups::create($credentials);
  $obj = new pushNotification();
    $data=Array("name"=>"home","value"=>"home");
    $obj->exceuteSendNotification(\App\User::whereId($userid)->first()->token,"Thank you for topping up KSh. ".$transaction_amount." airtime with us.","Transaction successful. ",$data);

  return Array("data"=>Array("response"=>"Airtime top-up successs"),"error"=>false);

}
else{
$customer=\App\Customers::wherePhone($msisdn)->first();
if ($customer!=null) {

    $user=\App\User::whereId($customer->user_id)->first();
    $userid=$user->id;
    # code...
}

$user=\App\User::whereId($userid);
$obj=$user->first();
if($obj!=null){
    $balance=$obj->balance;
$balance=$balance+$transaction_amount;
$user->update(["balance"=>$balance]);
 \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"credited"]);

        for($i=0;$i<1000000;$i++){
            $transid = 'TT'.rand(10000,99999)."M";
            $res=\App\topups::whereTransid($transid)->first();
            if ($res==null) {             # code...
break;  }
          
        }

$credentials=Array("amount"=>$transaction_amount,"balance"=>$balance,"transid"=>$transid,"sender"=>$obj?$obj->id:$msisdn);
\App\topups::create($credentials);

  $obj = new pushNotification();
    $data=Array("name"=>"home","value"=>"home");
    $obj->exceuteSendNotification($user->first()->token,"Your airtime purchase request was not successful. The amount has been credited back to your Lipa Mos Mos wallet.","Airtime purchase failed",$data);

}

   return Array("data"=>Array("response"=>$result['data']),"error"=>true);
    
}


            }    

$pp="/PP/i";
$ps="/PS/i";
$zu="/ZU/i";
$st="/ST/i";
$go="/GO/i";
$ds="/DS/i";
$nw="/NW/i";
if (preg_match($pp,$bill_ref_no) || preg_match($ps,$bill_ref_no) || preg_match($zu,$bill_ref_no) ||
 preg_match($st,$bill_ref_no) || preg_match($go,$bill_ref_no) || preg_match($ds,$bill_ref_no) || preg_match($nw,$bill_ref_no)) {

  $existingLog = \App\BillpaymentLogs::where('TransID',$transaction_id)->first();

            if($existingLog!=null){

                return "Duplicate Transaction";

            }

            \App\BillpaymentLogs::insert($paymentLog);

            $log_id = DB::getPdo()->lastInsertId();




    $paybillobj = new paybills();

$objtopup=new TopupsController();



$biller_name="";
$account=substr($bill_ref_no, 2);
$otherbills=false;

    if (preg_match($pp,$bill_ref_no) ) {
        # code...
        $biller_name="kplc_prepaid";
    }
    else if (preg_match($ps,$bill_ref_no) ) {
        # code...
        $biller_name="kplc_postpaid";
    }
      else if (preg_match($zu,$bill_ref_no) ) {
        # code...
        $otherbills=true;
        $biller_name="ZUKU";
    }
      else if (preg_match($st,$bill_ref_no) ) {
        # code...
         $otherbills=true;
        $biller_name="STARTIMES";
    }
      else if (preg_match($go,$bill_ref_no) ) {
        # code...
         $otherbills=true;
        $biller_name="GOTV";
    }
      else if (preg_match($ds,$bill_ref_no) ) {
        # code...
         $otherbills=true;
        $biller_name="DSTV";
    }
      else if (preg_match($nw,$bill_ref_no) ) {
        # code...
         $otherbills=true;
        $biller_name="NWATER";
    }
    else{
        return 0;
    }


    //kplc
if ($biller_name=="kplc_prepaid") {
  # code...
  $array=Array("PhoneNumber"=>"0".substr($msisdn, 3),"CustomerName"=>"customer","MeterNumber"=>substr($bill_ref_no,2),"Amount"=>$transaction_amount*100);
$res=$paybillobj->kplcprepaid($array);

 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
    Log::info("returned null");
  return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
        Log::info("returned ok");
         \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
         $token=json_decode(json_decode($decdata->VoucherDetails,true)[0])->Token;
$ret=$this->paymentSuccess($msisdn,$transaction_amount,$transaction_id,$biller_name,$token);
   
return Array("data"=>Array("response"=>"Transaction success: tokenno: ".$token),"error"=>false);
  # code...
}
else{
        Log::info("returned error");
      $this->CustomTopUpAccount($msisdn,$transaction_amount,$log_id);
    return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}



}
else if ($biller_name=="kplc_postpaid") {
  # code...
  $array=Array("MobileNumber"=>"0".substr($msisdn, 3),"CustomerName"=>"customer","CustAccNum"=>substr($bill_ref_no, 2),"Amount"=>$transaction_amount*100);
$res=$paybillobj->kplcpostpaid($array);

 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
  return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
     \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
$ret=$this->paymentSuccess($msisdn,$transaction_amount,$transaction_id,$biller_name);
return Array("data"=>Array("response"=>"Post Paid success"),"error"=>false);
  # code...
}
else{
      $this->CustomTopUpAccount($msisdn,$transaction_amount,$log_id);
    return Array("data"=>Array("response"=>"An error occured processing your request.".$decdata->ResponseDescription),"error"=>true);
}



}
else{

    $array=Array("paymentType"=>$biller_name,"PhoneNumber"=>"0".substr($msisdn, 3),"AccountNumber"=>substr($bill_ref_no, 2),"AccountName"=>"customer","Amount"=>$transaction_amount*100);
  // $array=Array("MobileNumber"=>"0".substr($phone, 3),"CustomerName"=>"customer","CustAccNum"=>$account,"Amount"=>$amount*100);
$res=$paybillobj->otherpayments($array);

 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
  return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
     \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
$ret=$this->paymentSuccess($msisdn,$transaction_amount,$transaction_id,$biller_name);
return Array("data"=>Array("response"=>"Payment Successs"),"error"=>false);
  # code...
}
else{

    $this->CustomTopUpAccount($msisdn,$transaction_amount,$log_id);
    return Array("data"=>Array("response"=>"An error occured processing your request.".$decdata->ResponseDescription),"error"=>true);
}

}

//     $userid="new";
// $customer=\App\Customers::wherePhone($msisdn)->first();
// if ($customer!=null) {

//     $user=\App\User::whereId($customer->user_id)->first();
//     $userid=$user->id;
//     # code...
// }

//     $obj= new TopupsController();
//    $response= json_decode($obj->createTransaction($account,$transaction_amount,$biller_name,$msisdn));

// Log::info(json_encode($response));
// if (isset($response->error)) {

// $customer=\App\Customers::wherePhone($msisdn)->first();
// if ($customer!=null) {

//     $user=\App\User::whereId($customer->user_id)->first();
//     $userid=$user->id;
//     # code...
// }else{
//     return 0;
// }

// $user=\App\User::whereId($userid);
// $obj=$user->first();
// if($obj!=null){
//     $balance=$obj->balance;
// $balance=$balance+$transaction_amount;
// $user->update(["balance"=>$balance]);

//         for($i=0;$i<1000000;$i++){
//             $transid = 'TT'.rand(10000,99999)."M";
//             $res=\App\topups::whereTransid($transid)->first();
//             if ($res==null) {             # code...
// break;  }
          
//         }

// $credentials=Array("amount"=>$transaction_amount,"balance"=>$balance,"transid"=>$transid,"sender"=>$obj->id);
// \App\topups::create($credentials);

//   $obj = new pushNotification();
//     $data=Array("name"=>"home","value"=>"home");
//     $obj->exceuteSendNotification($user->first()->token,"Your bill payment request was not successful. The amount has been credited back to your Lipa Mos Mos wallet.","Payment failed",$data);

// }

//    return 0;


// }

//     else{


//  $credentials=Array("amount"=>$transaction_amount,"balance"=>0,"transid"=>$transaction_id,"sender"=>$userid,"type"=>$biller_name);
// \App\topups::create($credentials);
//   $obj = new pushNotification();
//     $data=Array("name"=>"home","value"=>"home");
//     $obj->exceuteSendNotification(\App\User::whereId($sender)->first()->token,"paybil payment received. ".$sendamount."Thank you.","Transaction successful. ",$data);

//   return 0;

//     }

    # code...
}




            $existingLog = \App\PaymentLog::where('TransID',$transaction_id)->first();

            if($existingLog!=null){

                return "Duplicate Transaction";

            }

            \App\PaymentLog::insert($paymentLog);

            $log_id = DB::getPdo()->lastInsertId();

        //   Log::info(print_r($mpesaResponse,true));

            $booking = \App\Bookings::with('product','payments','customer','customer.user','county','location')->where('booking_reference','=',$bill_ref_no)->first();

            if($booking == null){
                return "Booking Does not exist!";
            }else{
               \App\PaymentLog::where('id',$log_id)->update(['status'=>"valid"]);
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
               $user=\App\Customers::whereId($booking->customer_id)->first();
             
                $phone=$user->phone;
                $res=$this->CustomTopUpExcessAmount($phone,$balance*-1);
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
                        
                       }
                       else {
                    // $commission_rate=$vendor->commission_rate;
                    // $commision_cap=$vendor->commission_cap;
                        if ($vendor->category=="1") {
    # code...
        $fixed_cost_subcategories=$vendor->fixed_cost_subcategories;

                 $array=json_decode($fixed_cost_subcategories,true);
        
                 $i=0;
                 $checked=false;
foreach ($array as $key => $value) {
    # code...

    if ($value['id']==$request->subcategory) {
        # code...
      $commission_rate=$value['commission_rate'];
                    $commision_cap=$value['commission_cap'];
$checked=true;

    }

}

if (!$checked) {
    $commission_rate=0;
    $commision_cap=0;
}

}
else{

                    $commission_rate=$vendor->commission_rate;
                    $commision_cap=$vendor->commission_cap;
                   
}
                    $admin_commission=floatval($product->product_price)*($commission_rate/100);
                    if ($admin_commission>=$commision_cap) {
                    $admin_commission=$commision_cap;
                    # code...
                    }
                
                    $vendor_commission=floatval($product->product_price)-$admin_commission;
                    // $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                    // $vendor_commission = $product->product_price * ((100-$product->subcategory->commision)/100);

                        // $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                        // $vendor_commission = $product->product_price * ((100-$product->subcategory->commision)/100);

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

                $status= DB::table('bookings')
                ->where('booking_reference','=',$bill_ref_no)->first()->status;
                if ($status=='pending') {
                    # code...
                    $newbalance=$balance-200;
                    $total_cost=($booking->total_cost)-200
    $recipients = $booking->customer->phone;
                DB::table('bookings')
                ->where('booking_reference','=',$bill_ref_no)
                ->update(['balance'=>$newbalance,'amount_paid'=>$amount_paid,'status'=>'active',"discount"=>200,"total_cost"=>$total_cost]);
                $message="Congratulations. You have received a KSh.200 discount on your Lipa Mos Mos order. Your new balance is KSh.{$newbalance}.";
SendSMSController::sendMessage($recipients,$message,$type="payment_notification");
  $token=\App\User::whereId($booking->customer->user_id)->first()->token;
    if ($token==null) {
        # code...
       return $message;
    }
       $data=Array("name"=>"bookingsuccess","value"=>"Bookings");
        $obj = new pushNotification();
    $obj->exceuteSendNotification($token,"You have received KSh.200 from us. Thanks for your order","Congratulations! ",$data);

                }
                else{


                DB::table('bookings')
                ->where('booking_reference','=',$bill_ref_no)
                ->update(['balance'=>$balance,'amount_paid'=>$amount_paid]);
                }

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
                //$message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}. Incl delivery cost of KES .{$shipping_cost}. Download our app to easily track your payments - http://bit.ly/MosMosApp.";

                // $message="Payment of KSh.{$transaction_amount} for {$bill_ref_no} received. Txn. {$code}. Bal is KSh.{$balance} incl delivery cost. Download our app to easily track your payments - http://bit.ly/MosMosApp";
                $message="{$code}. Payment of KES. {$transaction_amount} for {$bill_ref_no} received. Your Bal is KES. {$balance}. Buy Airtime bila stress. Paybill: 4040299 AC: Your Phone number.";

                $result=DB::table("monitorpay")->get();
                if (count($result)==0) {
                    DB::table("monitorpay")->insert(["total"=>1,"mobile"=>0]);
                }
                else{
                    $total=intval($result[0]->total)+1;
                    DB::table("monitorpay")->update(["total"=>$total]);
                }



            }else{

                // $message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}. Download our app to easily track your payments - http://bit.ly/MosMosApp." ;
                // $message="Payment of KES. {$transaction_amount} for {$bill_ref_no} received. Txn.{$code}. Bal is KSh. {$balance}. Download our app to easily track your payments - http://bit.ly/MosMosApp";
                     $message="{$code}. Payment of KES. {$transaction_amount} for {$bill_ref_no} received. Your Bal is KES. {$balance}. Buy Airtime bila stress. Paybill: 4040299 AC: Your Phone number.";
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
                  if ($vendor->category=="1") {
    # code...
        $fixed_cost_subcategories=$vendor->fixed_cost_subcategories;

                 $array=json_decode($fixed_cost_subcategories,true);
        
                 $i=0;
                 $checked=false;
foreach ($array as $key => $value) {
    # code...

    if ($value['id']==$request->subcategory) {
        # code...
      $commission_rate=$value['commission_rate'];
                    $commision_cap=$value['commission_cap'];
$checked=true;

    }

}

if (!$checked) {
    $commission_rate=0;
    $commision_cap=0;
}

}
else{

                    $commission_rate=$vendor->commission_rate;
                    $commision_cap=$vendor->commission_cap;
                   
}
                    $admin_commission=floatval($product->product_price)*($commission_rate/100);
                    if ($admin_commission>=$commision_cap) {
                    $admin_commission=$commision_cap;
                    # code...
                    }
                    $vendor_commission=floatval($product->product_price)-$admin_commission;

                    // $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                    // $vendor_commission = $product->product_price * ((100-$product->subcategory->commision)/100);

                        // $admin_commission = $product->product_price * ($product->subcategory->commision/100);
                        // $vendor_commission = $product->product_price - ($product->product_price * ($product->subcategory->commision/100));

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
            
            $message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}. Download our app to easily track your payments - http://bit.ly/MosMosApp." ;
           
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

    public static function validateTravelPayments($bill_ref_no,$transaction_amount,$msisdn,$first_name,$middle_name,$last_name,$code,$log_id,$TransID='none'){
Log::info("checkpoint1");
       $sms_credit_payment = \DB::connection('mysql2')->table('travel_agents')->where('code',$bill_ref_no)->first();
       $invoice_payment = \DB::connection('mysql2')->table('invoices')->where('ref',$bill_ref_no)->first();

       if(!empty($sms_credit_payment)){

            // s_m_s_top_ups

            $top_up_data = [
                            'payment_log_id'=>$log_id,
                            'agent_id'=>$sms_credit_payment->id,
                            'amount'=>$transaction_amount,
                            'channel'=>"Mpesa",
                            'created_at'=>now(),
                            'updated_at'=>now()
                            ];

            \DB::connection('mysql2')->table('s_m_s_top_ups')->insert($top_up_data);

            $c_sms_credits = $sms_credit_payment->sms_credits;
            $n_sms_credits = $c_sms_credits+$transaction_amount;

            \DB::connection('mysql2')->table('travel_agents')
                                     ->where('slug',$bill_ref_no)
                                     ->update([
                                          'sms_credits'=>$n_sms_credits, 
                                          'created_at'=>now(),
                                          'updated_at'=>now()
                                        ]);

            return "Success";

       }


       if(!empty($invoice_payment)){

            $amount_paid = $transaction_amount;
            $balance = $invoice_payment->amount - $amount_paid;

            if($balance<1){
                $status = "paid";
            }else{
                $status = "partially_paid";
            }

            \DB::connection('mysql2')->table('invoices')
                                    ->where('id',$invoice_payment->id)
                                    ->update([
                                        'balance'=>$balance,
                                        'amount_paid'=>$amount_paid,
                                        'status'=>$status
                                    ]);

            return "Success";

           
       }
Log::info("checkpoint2");
       $booking = DB::connection('mysql2')->table('bookings')->where('booking_reference','=',$bill_ref_no)->first();
       
       if($booking == null){
           return "Booking Does not exist!";
       }else{
           
            $customer = DB::connection('mysql2')->table('customers')->where('id',$booking->customer_id)->first();

            $user_customer = DB::connection('mysql2')->table('users')->where('id',$customer->user_id)->first();

            $recipients = $customer->phone;

            $agent = DB::connection('mysql2')->table('travel_agents')->where('id',$booking->agent_id)->first();

            $a_user = DB::connection('mysql2')->table('users')->where('id',$agent->user_id)->first();

            if($booking->status == "pending"){

                //   Booking made Notification
    
                // self::send_booking_made_mails($customer = $user_customer->name, $user_email = $user_customer->email,
                //                               $agent_email = $a_user->email,$package_name = $booking->package_name,
                //                               $booking_reference = $booking->booking_reference, $total_cost = $booking->total_cost
                //                             );
                $data = [];
                $data['customer'] = $user_customer->name;
                $data['user_email'] = $user_customer->email;
                $data['agent_email'] = $a_user->email;
                $data['package_name'] = $booking->package_name;
                $data['booking_reference'] = $booking->booking_reference;
                $data['total_cost'] = $booking->total_cost;
                

                $url = "127.0.0.1:8000/api/send-booking-made-email";

                $curl = curl_init($url);
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($curl);
                $response = json_decode($response);
                // return array($response);
                curl_close($curl);
    
            }

            $current_online_payments = $agent->online_payments;
            $current_offline_payments = $agent->offline_payments;
            $current_total_payments = $agent->total_payments;

            $new_online_payments = $current_online_payments + $transaction_amount;
            $new_total_payments = $agent->total_payments + $transaction_amount;

            $admin_commission = $agent->system_payment_cost;

            $payment_balance = $transaction_amount - $admin_commission;

            DB::connection('mysql2')->table('travel_agents')
                                    ->where('id',$booking->agent_id)
                                    ->update([
                                            'online_payments'=>$new_online_payments,
                                            'wallet_balance'=>$agent->wallet_balance + $payment_balance,
                                            'total_payments'=>$new_total_payments
                                            ]);

            $admin_wallet = DB::connection('mysql2')->table('admin_wallets')->first();
            
            if(empty($admin_wallet)){
                DB::connection('mysql2')->table('admin_wallets')->insert(['previous_balance'=>0,'current_balance'=>$admin_commission]);
            }else{
                DB::connection('mysql2')->table('admin_wallets')->update(['previous_balance'=>$admin_wallet->previous_balance,'current_balance'=>($admin_wallet->current_balance + $admin_commission)]);
            }

           
           $current_date = date('Y-m-d');

           $date_from = date('Y-m-01', strtotime($current_date));

           $date_to = date("Y-m-t", strtotime($date_from));

           $period = date('M jS'.', '.'Y', strtotime($date_from))." to ".date('M jS'.', '.'Y', strtotime($date_to));

           $commission = \DB::connection('mysql2')->table('system_commissions')->where('agent_id',$booking->agent_id)->where('period',$period)->first();

           if(!empty($commission)){

            $amount = $commission->transaction_amount + $admin_commission;

            $transactions = $commission->transactions + 1;
            $amount = $commission->transaction_amount + $admin_commission;

            \DB::connection('mysql2')->table('system_commissions')->where('id',$commission->id)
                         ->update([
                                    'transactions'=>$transactions,
                                    'transaction_amount'=>$commission->transaction_amount + $transaction_amount,
                                    'commission_paid'=>$commission->commission_paid + $admin_commission,
                                    'unit'=>$agent->system_payment_cost,
                                ]);

           }else{

            $commission = [];
            $commission['agent_id'] = $booking->agent_id;
            $commission['period'] = $period;
            $commission['unit'] = $agent->system_payment_cost;
            $commission['transaction_amount'] = $transaction_amount;
            $commission['commission_paid'] = $admin_commission;
            $commission['date_from'] = $date_from;
            $commission['date_to'] = $date_to;
            $commission['transactions'] = 1;
            $commission['created_at'] = now();
            $commission['updated_at'] = now();

            \DB::connection('mysql2')->table('system_commissions')->insert($commission);

           }
           Log::info("checkpoint3");

            $payment_data = [
                            'payment_log_id'=>$log_id,
                            'customer_id'=>$customer->id,
                            'agent_id'=>$agent->id,
                            'booking_id'=>$booking->id,
                            'transaction_type'=>"Pay Bill",
                            'amount'=>$transaction_amount,
                            'admin_commission'=>$admin_commission,
                            'balance'=>$payment_balance,
                            'created_at'=>now(),
                            'updated_at'=>now()
                            ];

            DB::connection('mysql2')->table('payments')->insert($payment_data);

            $amount_paid = $booking->amount_paid + $transaction_amount;

            $balance = $booking->balance - $transaction_amount; 

            $f_balance = number_format($balance,2);
            $f_transaction_amount =  number_format($transaction_amount);

            $data = ['amount_paid'=>$amount_paid,'balance'=>$balance,'status'=>'active'];

            $message    ="Payment of KES. {$f_transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$f_balance}. Download our app to easily track your payments - http://bit.ly/MosMosApp.";

            SendSMSController::sendMessage($recipients,$message,$type="payment_notification");


            // Send Invoice

            $payments = DB::connection('mysql2')->table('payments')
                            ->leftJoin('payment_logs','payments.payment_log_id','payment_logs.id','')
                            // ->where('booking_id',$booking->id)
                            ->select('payments.*','payment_logs.*')
                            ->orderBy('payments.id','DESC')
                            ->get();

            $latestPayment = DB::connection('mysql2')->table('payments')->where('booking_id',$booking->id)->latest()->first();

        $ch = curl_init();

        // set url
        curl_setopt($ch, CURLOPT_URL, 'https://travelmosmos.co.ke/invoiceurl?booking_reference='.$booking->booking_reference);

        //return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // $output contains the output string
        $output = curl_exec($ch);

        // close curl resource to free up system resources
        curl_close($ch); 


            $details  = [
                'customer'=>$user_customer,
                'customer_name'=>$user_customer->name,
                'agent'=>$agent,
                'payments'=>$payments,
                'product_name'=>$booking->package_name,
                'booking_reference'=>$booking->booking_reference,
                'total_cost'=>number_format($booking->total_cost,2),
                'amount_paid'=>number_format($booking->amount_paid),
                'balance'=>$balance,
                'booking'=>$booking,
                'latestPayment'=>$latestPayment,
                'date'=>Now(),
                'transcode'=>$TransID,
                    "url" => "https://travelmosmos.co.ke/payments/".$output."/invoice"
            ];
            Log::info(json_encode($details));
  

            Mail::to($user_customer->email)->send(new SendTravelPaymentEmail($details));


            // Send Invoice End
Log::info("checkpoint4");
Log::info($balance.".....".$bill_ref_no."...".json_encode($data));

            if($balance<1){

            $message = "Congratulations, You have completed Payment for ".$booking->package_name.".";

            SendSMSController::sendMessage($recipients,$message,$type="payment_completion_notification");

            $user = \DB::connection('mysql2')->table('users')->where('id',$customer->user_id)->first();

            $message = $user->name." has completed Payment for ".$booking->package_name.".";

            SendSMSController::sendMessage($recipients = $agent->phone,$message,$type="travel_payment_completion_notification");

            $data['status'] = 'complete';

            }

            DB::connection('mysql2')->table('bookings')->where('booking_reference','=',$bill_ref_no)->update($data);

            return "Success";

       }

    }
        /**
 * Returns an encrypted & utf8-encoded
 */
function encrypt($pure_string, $encryption_key) {
    $iv_size = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $encrypted_string = mcrypt_encrypt(MCRYPT_BLOWFISH, $encryption_key, utf8_encode($pure_string), MCRYPT_MODE_ECB, $iv);
    return $encrypted_string;
}


    function CustomTopUpAccount($msisdn,$transaction_amount,$log_id){
        $customer=\App\Customers::wherePhone($msisdn)->first();
if ($customer!=null) {

    $user=\App\User::whereId($customer->user_id)->first();
    $userid=$user->id;
    # code...
}else{
    return 0;
}

$user=\App\User::whereId($userid);
$obj=$user->first();
if($obj!=null){
    $balance=$obj->balance;
$balance=$balance+$transaction_amount;
$user->update(["balance"=>$balance]);
  \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"credited"]);

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
    $obj->exceuteSendNotification($user->first()->token,"Your bill payment request was not successful. The amount has been credited back to your Lipa Mos Mos wallet.","Payment failed",$data);

}

   return 0;
    }

        function CustomTopUpExcessAmount($msisdn,$transaction_amount){
        $customer=\App\Customers::wherePhone($msisdn)->first();
if ($customer!=null) {

    $user=\App\User::whereId($customer->user_id)->first();
    $userid=$user->id;
    # code...
}else{
    return 0;
}

$user=\App\User::whereId($userid);
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
    $obj->exceuteSendNotification($user->first()->token,"You have received, ksh.".$transaction_amount." on you mosmos wallet.","Payment failed",$data);

}

   return 0;
    }

    function getphone($bill_ref_no){

$ismobiletopup="/254/i";
$mob='/^(0)\d{9}$/';
$mob1='/^(\+254)\d{9}$/';
$air="/AIR/i";
$tel="/TEL/i";
$saf="/SAF/i";
$recipient="";
if (preg_match($air, $bill_ref_no) || preg_match($tel, $bill_ref_no) || preg_match($saf, $bill_ref_no) ) {
    # code...
    $bill_ref_no=substr($bill_ref_no, 3);
    list($msisdn, $network) = $this->get_msisdn_network($bill_ref_no);

        if (!$msisdn){
           
        }else{
            $recipient = "0".substr($msisdn, 3);
            
            $valid_phone=$msisdn;
         
}}
else{
      list($msisdn, $network) = $this->get_msisdn_network($bill_ref_no);

        if (!$msisdn){
           
        }else{
            $recipient = "0".substr($msisdn, 3);
            
            $valid_phone=$msisdn;
         
        }
}


 

return $recipient;
    }

    function paymentSuccess($msisdn,$transaction_amount,$transaction_id,$biller_name,$token=""){

        $userid=$msisdn;
$customer=\App\Customers::wherePhone($msisdn)->first();
if ($customer!=null) {

    $user=\App\User::whereId($customer->user_id)->first();
    $userid=$user->id;
    # code...
}
 $credentials=Array("amount"=>$transaction_amount,"balance"=>0,"transid"=>$transaction_id,"sender"=>$userid,"type"=>"Bills(".$biller_name.")","token"=>$token);
\App\topups::create($credentials);
  $obj = new pushNotification();
    $data=Array("name"=>"home","value"=>"home");
    $obj->exceuteSendNotification(\App\User::whereId($userid)->first()->token,"Your Payment of KSh. ".$transaction_amount." was successsful.","Transaction successful. ",$data);

  return Array("data"=>Array("response"=>"Airtime top-up successs"),"error"=>false);
}

      public function get_msisdn_network($msisdn){
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

         



}
