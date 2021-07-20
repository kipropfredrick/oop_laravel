<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categories;
use App\SubCategories;
use App\Products;
use App\Gallery;
use App\User;
use App\Customers;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\ThirdLevelCategory;
class ProductsApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

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

     function productCategories(Request $request){
$result=Categories::get();
$finalResult=[];

for ($i=0; $i <count($result) ; $i++) { 
    # code...
    $res=Array();
    //cat for category
    $cat=Array();
    $cat['name']=$result[$i]->category_name;
    $cat['id']=$result[$i]->id;
    $cat['icon']=$result[$i]->category_icon;
    $cat['slug']=$result[$i]->slug;
    $res['category']=$cat;
    $subcategories=SubCategories::whereCategory_id($result[$i]->id)->get();

    $midres=Array();
    $midresult=[];

    for ($j=0; $j < count($subcategories) ; $j++) { 
        # code...
        //subcat defines sub category

        $subcat=Array();
        $subcat['id']=$subcategories[$j]->id;
        $subcat['name']=$subcategories[$j]->subcategory_name;
        $subcat['slug']=$subcategories[$j]->slug;
        $subcat['subcategory']=$subcat;
        $thirdlevelcategory=ThirdLevelCategory::whereSubcategory_id($subcategories[$j]->id)->get();
        $data=[];
  foreach($thirdlevelcategory as $thirdcategory){
    $ispresent=Products::with('gallery')->whereThird_level_category_id ($thirdcategory->id)->first();
    if ($ispresent) {
        # code...
         $thirdcategory['icon'] = $ispresent['product_image']?Products::with('gallery')->whereThird_level_category_id ($thirdcategory->id)->latest()->first()['product_image']:"download.jpg";
         array_push($data, $thirdcategory);
    }
         
           
        }

        $subcat['thirdlevelcategory']=$data;
        //$products=Products::with('gallery')->whereSubcategory_id($subcategories[$j]->id)->limit(4)->get();



array_push($midresult, $subcat);


    }

    $res['data']=$midresult;
    array_push($finalResult,$res);


}
return $finalResult;
     }
function categories(Request $request){
return Categories::inRandomOrder()->get();
}
     function weeklybestsellers(Request $request){
$bestSellers = \App\Products::with('category','subcategory')
                        ->where('status','=','approved')
                        ->where('quantity','>',0)->inRandomOrder()->take(6)->get();
   foreach ($bestSellers as $Products) {
                                # code...
    $Products['description']="";
    $Products['highlights']="";

                            }
    return $bestSellers;

     }

     function  trendingProducts(Request $request){
         $trendingProducts = \App\Products::with('category','subcategory')
                            ->where('status','=','approved')
                            ->where('quantity','>',0)->inRandomOrder()->take(6)->get();

 foreach ($trendingProducts as $Products) {
                                # code...
    $Products['description']="";
    $Products['highlights']="";

                            }

        return $trendingProducts;
     }

     function subcategories(Request $request){
$result=SubCategories::whereCategory_id($request->id)->get();
return $result;
       

     }

     function subcategoriesProducts(Request $request){
       $result=Products::whereSubcategory_id($request->id)->limit(6)->get();
return $result; 
     }
function getProduct(Request $request){
    $id=$request->input("id");
     $result=Products::with("gallery")->whereId($id)->first();
     $counties=\App\Counties::get();
     $result['counties']=$counties;
return $result; 
}


function customerOrders(Request $request){
$username=$request->input("username");
$customer=Customers::wherePhone($username)->first();

 
        $customer_id = $customer->id;;
$phone=$customer->phone;
        $totalBookingAmount = \App\Bookings::where('amount_paid','>',0)->where('customer_id',$customer_id)->sum('total_cost');
        $totalBookingCount = \App\Bookings::where('amount_paid','>',0)->where('customer_id',$customer_id)->count();
        $activeBookingAmount = \App\Bookings::where('status','=','active')->where('customer_id',$customer_id)->sum('total_cost');
        $activeBookingsCount = \App\Bookings::where('status','=','active')->where('customer_id',$customer_id)->count();

        $revokedBookingAmount = \App\Bookings::where('status','=','revoked')->where('customer_id',$customer_id)->sum('total_cost');
        $revokedBookingCount = \App\Bookings::where('status','=','revoked')->where('customer_id',$customer_id)->count();
        $completeBookingAmount = \App\Bookings::where('status','=','complete')->where('customer_id',$customer_id)->sum('total_cost');
        $completeBookingCount = \App\Bookings::where('status','=','complete')->where('customer_id',$customer_id)->count();
        $pendingBookingAmount = \App\Bookings::where('status','=','pending')->where('customer_id',$customer_id)->sum('total_cost');
        $pendingBookingCount = \App\Bookings::where('status','=','pending')->where('customer_id',$customer_id)->count();

         $completionDate = \App\Bookings::where('status','=','active')->where('customer_id',$customer_id)->first()->setdate;
         $createdat = \App\Bookings::where('status','=','active')->where('customer_id',$customer_id)->first()->created_at;
        $customers=DB::table('customers')->where('id','=',$customer_id)->first();
        $balance=intval(DB::table("users")->whereId($customers->user_id)->first()->balance);


$hasbooking=false;
$progresspercentage=0;
$daystogo=0;
$dailytarget=0;
$progressmessage="on track";

        if($customer!=null)
        {

        


        $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereNotIn('status', ['complete','revoked'])->first();

        if ($booking!=null) {
          # code...
          $hasbooking=true;
        }
      }


      if ($hasbooking) {
$amountPaid=$booking->amount_paid;
$bookingbalance=intval($booking->balance);
$progresspercentage=intval($amountPaid/$totalBookingAmount)*100;

$date = Carbon::parse($completionDate);
$now = Carbon::now();

$daystogo =( $date->diffInDays($now))." Days";

$cdate = Carbon::parse($completionDate);
$createddate = Carbon::parse($createdat);

$days=intval(($cdate->diffInDays($createddate)));

$dailytarget=intval($totalBookingAmount/$days);
$dayspassed=intval(($createddate->diffInDays($now)));
$amountsbepaid=intval($dayspassed*$dailytarget);
$paymentbalance=$amountsbepaid-$amountPaid;
if ($paymentbalance<0) {
  # code...
  $progressmessage="On Track";

}
else{
   $daysdue=intval($paymentbalance/$dailytarget);
   $progressmessage=$daysdue." behind ".$paymentbalance;
}
          # code...
      }
      else{
        $amountPaid=0;
        $bookingbalance=0;
      }

      return Array("totalBookingAmount"=>$totalBookingAmount,"totalBookingAmount"=>$totalBookingAmount,"activeBookingAmount"=>$activeBookingAmount,"activeBookingsCount"=>$activeBookingsCount,"revokedBookingAmount"=>$revokedBookingAmount,"revokedBookingCount"=>$revokedBookingCount,"completeBookingAmount"=>$completeBookingAmount,"completeBookingCount"=>$completeBookingCount,"pendingBookingAmount"=>$pendingBookingAmount,"pendingBookingCount"=>$pendingBookingCount,"balance"=>$balance,"hasbooking"=>$hasbooking,"amountPaid"=>$amountPaid,"bookingbalance"=>$bookingbalance,"progressmessage"=>$progressmessage,"dailytarget"=>$dailytarget,"daystogo"=>$daystogo,"progresspercentage"=>$progresspercentage);
                
        
}


function myAccount(Request $request){

}
  

 function login(Request $request){
    $username=$request->input('username');
    $password=$request->input('password');
     $valid_phone = preg_match("/^(?:\+?254|0)?(7\d{8})/", $username,$p_matches);
        //Valid email
        $valid_email = preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/", $username, $e_matches);
       
        if ($valid_phone == 1 ) {
$items=str_split($username);

if($items[0]=="0"){
    $phone="254".substr($username, 1);
}
else{
    $phone=$username;
}

        
        $existingCustomer = \App\Customers::where('phone','=',$phone)->first();
          if($existingCustomer==null){
 return Array("response"=>"No records","error"=>true);
          }
          else{
$email= \App\User::whereId( $existingCustomer->user_id)->first()->email;

          }

        }
        elseif($valid_email == 1){
           $email=$username;
        }

        else{
            return Array("response"=>"Invalid Email Or Phone Number","error"=>true);
        }

        $users=\App\User::whereEmail($email)->first();

         if ($users!=null) {
            // Authentication passed...
            $phone=\App\Customers::whereUser_id($users->id)->first()->phone;

          $customer=\App\Customers::wherePhone($phone)->first();
         $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereNotIn('status', ['complete','revoked'])->first();

        if ($booking!=null) {
          # code...
          $hasbooking=true;
        }
        else{
          $hasbooking=false;
        }

            return Array("response"=>$users,"error"=>false,"phone"=>$phone,"hasbooking"=>$hasbooking);
            
        }
        else{
return Array("response"=>"Incorrect Username or password","error"=>true);
        }

 } 

    public function payments(Request $request){


$customer_id=DB::table("customers")->wherePhone($request->input('username'))->first()->id;
        $payments = \App\Payments::with('customer','mpesapayment','customer.user','product','booking')->whereCustomer_id($customer_id)->orderBy('id', 'DESC')->get();
        $allPayments=[];


  
for ($i=0; $i < count($payments); $i++) { 
    # code...
    $array=Array("product_name"=>$payments[$i]['product']->product_name,"payment_ref"=>$payments[$i]['mpesapayment']?$payments[$i]['mpesapayment']->transac_code:"","booking_reference"=>$payments[$i]['booking']?$payments[$i]['booking']->booking_reference:"","transaction_amount"=>$payments[$i]->transaction_amount,"date"=>$payments[$i]->date_paid);
    array_push($allPayments, $array);

}
         
return $allPayments;
    }

      public function bookings(Request $request){
        $username=$request->input("username");
        $status=$request->input("status");
        $customer = Customers::wherePhone($username)->first();
        $bookings = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=',$status)->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
            $booking['name']=\App\Products::whereId($booking->product_id)->first()->product_name;
        }

       return $bookings;
    }

    function getProducts(Request $request){
        $category_id=$request->input("id");
       $products= Products::select('id','product_name','product_price','product_image')->whereThird_level_category_id ($category_id)->inRandomOrder()->paginate(20);

       return $products->items();


    }
    function getSubcategoryProducts(Request $request){
         $id=$request->input("id");
       $products= Products::select('id','product_name','product_price','product_image')->whereSubcategory_id ($id)->inRandomOrder()->paginate(20);

       return $products->items();
    }

       public function search(Request $request){

$search=$request->input('search');
     

            $products =   \App\Products::select('id','product_name','product_price','product_image','status')->where ( 'product_name', 'LIKE', '%' . $search . '%' )->where('status','=','approved')->paginate(20);
                        

return $products->items();
       

    }

    function checkBooking(request $request){
        $username=$request->input("username");
$customer=Customers::wherePhone($username)->first();
        $customer_id = $customer->id;;
$phone=$customer->phone;

   if($customer!=null)
        {

        


        $booking = \App\Bookings::with('product')->where('customer_id','=',$customer->id)->whereNotIn('status', ['complete','revoked'])->first();

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


      public function make_booking(Request $request){
        $county_id = $request->county_id;
        $exact_location = $request->exact_location;
        $vendor_code = $request->vendor_code;

        $categories = \App\Categories::all();

        list($msisdn, $network) = $this->get_msisdn_network($request->phone);

        if (!$msisdn){

            return redirect()->back()->with('error',"Please enter a valid phone number!");
        }else{
            $valid_phone = $msisdn;
        }
        //Valid email
        $valid_email = preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/", $request->email, $e_matches);
        
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
        
        $existingUser = \App\User::where('email',  $request->input('email'))->first();

        if($existingUser!=null)
        {

        $user = $existingUser;

        $existingCustomer = \App\Customers::where('user_id','=',$existingUser->id)->first();


        $booking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->whereNotIn('status', ['complete','revoked'])->first();

        if($booking!=null){
            return Array("error"=>true,"response"=>"you already have an existing bookings");
        }

        \Auth::login($user);


        $booking_reference = 'MM'.rand(10000,99999);

        $booking_date = now();


        $due_date = Carbon::now()->addMonths(3);

        
        $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$request->product_id)->first();

        
        if($request->initial_deposit<100){

         return Array("error"=>true,"response"=>"minimum deposit is Ksh. 100");
         
        }

$balance=$existingUser->balance;

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
        \App\User::where('email',  $request->input('email'))->update(["balance"=>intval($balance)-intval($total_cost)]);
        $booking->status = "complete";
        $booking->balance="0";

         $message =  "Ksh ".$balance." from your mosmos wallet has been used fully pay your placed order";
    }
    else{

         \App\User::where('email',  $request->input('email'))->update(["balance"=>0]);
        $booking->balance =   $total_cost-(intval($balance)); 
$booking->amount_paid = $balance;
$booking->status = "active";
 $message =  "Ksh ".$balance." from your mosmos wallet has been used to pay for ordered item partially remaining amount is ".number_format($total_cost-(intval($balance)));
    }



        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");
}

        
        $booking->customer_id = $existingCustomer->id; 
        $booking->product_id  = $request->product_id;
        $booking->booking_reference = $booking_reference;
        $booking->quantity  = '1';
       
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
        $booking->total_cost =  $total_cost;

        $booking->save();
        
        $booking_id = DB::getPdo()->lastInsertId();

        $booking_reference = 'MM'.rand(10000,99999);

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

         

      return $message;
            
        }

        
        $existingCustomer = \App\Customers::where('phone','=',$valid_phone)->first();

        if($existingCustomer)
        {

        $booking_reference = 'MM'.rand(10000,99999);

        $booking_date = now();

        $$booking_date = strtotime($booking_date);

        $product = \App\Products::find($request->product_id);

       $due_date = Carbon::now()->addMonths(3);

        if($request->initial_deposit<100){

    return Array("error"=>true,"response"=>"minimum deposit is Ksh. 100");
         
        }

        $booking = new \App\Bookings();
        $booking->customer_id = $existingCustomer->id; 
        $booking->product_id  = $request->product_id;
        $booking->county_id = $request->county_id;
        $booking->exact_location = $exact_location;
        $booking->booking_reference = $booking_reference;
        $booking->quantity  = "1";
        $booking->amount_paid = "0";
        $booking->balance = $total_cost;
        $booking->item_cost = $product->product_price;
        $booking->shipping_cost = $shipping_cost;
        $booking->payment_mode  = 'Mpesa';
        $booking->vendor_code = $vendor_code;
        $booking->date_started  = now();
        $booking->due_date = $due_date;
        $booking->status = "pending";
        $booking->total_cost = $total_cost;
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

        return $message;
            
        }

  
    }

    function hasBooking(Request $request){
      $customer=\App\Customers::wherePhone($request->input("phone"))->first();
         $booking = \App\Bookings::where('customer_id','=',$customer->id)->whereNotIn('status', ['complete','revoked'])->first();

        if ($booking!=null) {
          # code...
          $hasbooking=true;
        }
        else{
          $hasbooking=false;
        }

        return Array("hasbooking"=>$hasbooking);
    }
}
