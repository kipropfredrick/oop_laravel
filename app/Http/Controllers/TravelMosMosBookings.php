<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Customers;
use App\Http\Controllers\autApi;
class TravelMosMosBookings extends Controller
{
    //

public function checktestconnection(){


$bookings = \DB::connection('mysql2')->table('bookings')->get();

return $bookings;
}

function bookings(Request $request){
	$connection=\DB::connection('mysql2');
    $username=$request->input("username");
$customer=Customers::wherePhone($username)->first();
$customerId=$customer->id;
$hasbooking=false;
$totalactive=0;
$totalpaid=0;
$balance=0;
$dailyTarget=0;
$daytogo="0";
$progress="0";
$hastarget=0;
$progresspercentage=0;
$booking_reference="";
$progressmessage="Track";
$dailytarget=0;
$daystogo=0;
$setdate="";
$setreminder=0;

$targettype="Daily";
$bookings = $connection->table('bookings')->whereCustomer_id($customerId)->whereIn('status',['active','pending'])->first();
if ($bookings!=null) {
    # code...
   $hasbooking=true; 
   $booking_reference=$bookings->booking_reference;

}

$bookings = $connection->table('bookings')->whereCustomer_id($customerId)->whereStatus('active')->first();
if ($bookings!=null) {
	# code...
	$hasbooking=true;
	$totalactive=$bookings->total_cost;
	$totalpaid=$bookings->amount_paid;
	$balance=$bookings->balance;

    $booking_reference=$bookings->booking_reference;

  $completionDate = $connection->table('bookings')->where('status','=','active')->where('customer_id',$customerId)->first()->setdate;
         $createdat = $connection->table('bookings')->where('status','=','active')->where('customer_id',$customerId)->first()->created_at;
    $setreminder=intval($connection->table('bookings')->where('status','=','active')->where('customer_id',$customerId)->first()->setreminder);
  $setdate=$connection->table('bookings')->where('status','=','active')->where('customer_id',$customerId)->first()->setdate;

$bookingbalances=intval($bookings->balance);
$totalBookingAmounts=$bookings->total_cost;
$progresspercentage=intval(($amountPaids/$totalBookingAmounts)*100);


$date = Carbon::parse($completionDate);
$now = Carbon::now();

$daystogo =( $date->diffInDays($now))." Days";

$cdate = Carbon::parse($completionDate);
$createddate = Carbon::parse($createdat);
$hastarget=$setreminder;
$days=intval(($cdate->diffInDays($createddate)));

if($days>0){
    if ($setreminder==1) {
        # code...
            $dailytarget=intval($totalBookingAmounts/$days);
            $targettype="Daily target";
    }
    else if ($setreminder==2) {
        # code...
            $dailytarget=intval($totalBookingAmounts/$days) * 7;
             $targettype="Weekly target";
    }
    else if ($setreminder==3) {
        # code...
          $dailytarget=intval($totalBookingAmounts/$days) * 30;
           $targettype="Monthly target";
    }



}
$dayspassed=intval(($createddate->diffInDays($now)));
$amountsbepaid=intval($dayspassed*$dailytarget);
$paymentbalance=$amountsbepaid-$amountPaids;
if ($paymentbalance<0) {
  # code...
  $progressmessage="On Track";

}
else{
  if($dailytarget<=0){
     $daysdue=intval($paymentbalance/$dailytarget);
  }
  else{
    $daysdue=0;
  }
   $progressmessage=$daysdue."-Days behind Ksh. ".number_format($paymentbalance);
}



}




$array=Array("hasbooking"=>$hasbooking,"totalactive"=>$totalactive,"totalpaid"=>$totalpaid,"balance"=>$balance,"dailyTarget"=>$dailyTarget,"daytogo"=>$daytogo,"progress"=>$progress,"booking_reference"=>$booking_reference,"progressmessage"=>$progressmessage,"dailytarget"=>$dailytarget,"daystogo"=>$daystogo,"progresspercentage"=>$progresspercentage,"hastarget"=>$hastarget,"setdate"=>$setdate,"setreminder"=>$setreminder,"bookingreference"=>$booking_reference,"targettype"=>$targettype);



return $array;


}

function makePayment(Request $request){

	$amount=$request->amount;

	$phone=$request->phone;
	$booking_ref=$request->input("bookingref");

  list($msisdn, $network) = $this->get_msisdn_network($phone);

        if (!$msisdn){

              return Array("data"=>Array("response"=>"Invalid Phone Number"),"error"=>true);
        }else{
            $mobilerec = "0".substr($msisdn, 3);
            
            $valid_phone=$msisdn;
         
        }
$authobj=new autApi();

return $authobj->stk_push($amount,$msisdn,$booking_ref);

}
 function travelcheckBooking(request $request){
        $username=$request->input("username");
$customer=Customers::wherePhone($username)->first();
        $customer_id = $customer->id;
$phone=$customer->phone;

   if($customer!=null)
        {

        
$connection=\DB::connection('mysql2');
//$customer->id
        $booking =$connection->table('bookings')->where('customer_id','=',$customer_id)->whereIn('status', ['active','pending'])->first();

        if ($booking!=null) {
          # code...
          $hasbooking=true;
          return Array("response"=>$booking,"error"=>false);
        }
        else{
            return Array("response"=>"No Active Or Pending Orders in the list","error"=>true);  
        }
      }

      else{

        return Array("response"=>"Account Data Not Found ","error"=>true);
      }
}


    public function customertravelbookings(Request $request){
        $username=$request->input("username");
        $status=$request->input("status");
            $connection=\DB::connection('mysql2');

        $customer = Customers::wherePhone($username)->first();

        //$customer->id
        $bookings = $connection->table('bookings')->where('customer_id','=',$customer->id)->where('status','=',$status)->latest()->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking->progress = $progress;
         
        }

       return $bookings;
    }

    function travelpayments(Request $request){
         $connection=\DB::connection('mysql2');
        $customer_id=DB::table("customers")->wherePhone($request->input('username'))->first()->id;
        $bookings=$connection->table('bookings')->whereCustomer_id($customer_id)->pluck('booking_reference')->toArray();

        $payments = $connection->table('payment_logs')->whereIn("BillRefNumber",$bookings)->orderBy('id', 'DESC')->get();
        $allPayments=[];


  
for ($i=0; $i < count($payments); $i++) { 
    # code...
    $array=Array("product_name"=>'package 1',"payment_ref"=>$payments->TransID ,"booking_reference"=>$payments->BillRefNumber,"transaction_amount"=>$payments->TransAmount,"date"=>$payments[$i]->created_at);
    array_push($allPayments, $array);

}
         
return $allPayments;
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

           /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lipaNaMpesaPassword($lipa_time)
    {

        $passkey = env('STK_PASSKEY1');
        $BusinessShortCode = env('MPESA_SHORT_CODE1');
        $timestamp =$lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode.$passkey.$timestamp);
        return $lipa_na_mpesa_password;
    }

    function updateTraveTarget(Request $request){

$bookingreference=$request->bookingreference;
$setdate=$request->setdate;
$setreminder=$request->setreminder;
$connection=\DB::connection('mysql2');
$obj=$connection->table('bookings')->whereBooking_reference($bookingreference);
$booking=$obj->first();
if ($booking!=null) {
  # code...
$array=Array("setdate"=>$setdate,"setreminder"=>$setreminder);
$obj->update($array);
  return Array("data"=>Array("response"=>"Your payment target has been updated successfully."),"error"=>false);
}
else{
  return Array("data"=>Array("response"=>"No booking reference found."),"error"=>true);
}

    }

}
