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
  foreach($thirdlevelcategory as $thirdcategory){
            return Products::with('gallery')->whereThird_level_category_id ($thirdcategory->id)->first();
            $thirdlevelcategory['icon'] = Products::with('gallery')->whereThird_level_category_id ($thirdcategory->id)->first()['gallery']->image_path;
        }

        $subcat['thirdlevelcategory']=$thirdlevelcategory;
        //$products=Products::with('gallery')->whereSubcategory_id($subcategories[$j]->id)->limit(4)->get();



array_push($midresult, $subcat);


    }

    $res['data']=$midresult;
    array_push($finalResult,$res);


}
return $finalResult;
     }

     function weeklybestsellers(Request $request){
$bestSellers = \App\Products::with('category','subcategory')
                        ->where('status','=','approved')
                        ->where('quantity','>',0)->inRandomOrder()->take(6)->get();

    return $bestSellers;

     }

     function  trendingProducts(Request $request){
         $trendingProducts = \App\Products::with('category','subcategory')
                            ->where('status','=','approved')
                            ->where('quantity','>',0)->inRandomOrder()->take(6)->get();


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
function getGallery(Request $request){
     $result=Gallery::whereProduct_id($request->id)->get();
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
        $customers=DB::table('customers')->where('id','=',$customer_id)->first();
        $balance=intval(DB::table("users")->whereId($customers->user_id)->first()->balance);


$hasbooking=false;
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
          # code...
      }
      else{
        $amountPaid=0;
        $bookingbalance=0;
      }

      return Array("totalBookingAmount"=>$totalBookingAmount,"totalBookingAmount"=>$totalBookingAmount,"activeBookingAmount"=>$activeBookingAmount,"activeBookingsCount"=>$activeBookingsCount,"revokedBookingAmount"=>$revokedBookingAmount,"revokedBookingCount"=>$revokedBookingCount,"completeBookingAmount"=>$completeBookingAmount,"completeBookingCount"=>$completeBookingCount,"pendingBookingAmount"=>$pendingBookingAmount,"pendingBookingCount"=>$pendingBookingCount,"balance"=>$balance,"hasbooking"=>$hasbooking,"amountPaid"=>$amountPaid,"bookingbalance"=>$bookingbalance);
                
        
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

          $phone="254".substr($username, 1);
        $existingCustomer = \App\Customers::where('phone','=',$phone)->first();
          $email= \App\User::whereId( $existingCustomer->user_id)->first()->email;

        }
        elseif($valid_email == 1){
           $email=$username;
        }

        else{
            return Array("response"=>"Invalid Email Or Phone Number","error"=>true);
        }

         if (Auth::attempt(["email"=>$email,"password"=>$password])) {
            // Authentication passed...
            $phone=\App\Customers::whereUser_id(Auth()->user()->id)->first()->phone;
            return Array("response"=>Auth()->user(),"error"=>false,"phone"=>$phone);
            
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
    $array=Array("product_name"=>$payments[$i]['product']->product_name,"payment_ref"=>$payments[$i]['mpesapayment']->transac_code,"booking_reference"=>$payments[$i]['booking']->booking_reference,"transaction_amount"=>$payments[$i]->transaction_amount,"date"=>$payments[$i]->date_paid);
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
}
