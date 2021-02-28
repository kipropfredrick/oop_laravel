<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use DB;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\Log;
use AfricasTalking\SDK\AfricasTalking;

class FrontPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $categories = \App\Categories::with('subcategories')->get();
       $lcategories = \App\Categories::with('subcategories')->take(10)->get();
       
       $products = \App\Products::with('category','subcategory')->where('status','=','approved')
                    ->where(function($q){    
                        $q->where('vendor_id' , '!=', null)
                        ->orWhere('agent_id' , '!=', null);
                        })
                    ->where('quantity','>',0)->latest()->inRandomOrder()->take(20)->get();

        $trendingProducts = \App\Products::with('category','subcategory')->where('status','=','approved')
                    ->where(function($q){    
                        $q->where('vendor_id' , '!=', null)
                        ->orWhere('agent_id' , '!=', null);
                        })
                    ->where('quantity','>',0)->orderBy('clicks','DESC')->inRandomOrder()->take(20)->get();

        $bookings = \App\Bookings::orderBy('id','DESC')->take(20)->get();

        $product_ids = [];

        foreach($bookings as $booking){
            array_push($product_ids,$booking->product_id);
        }

        $bestSellers = \App\Products::with('category','subcategory')->where('status','=','approved')
                        ->where(function($q){    
                            $q->where('vendor_id' , '!=', null)
                            ->orWhere('agent_id' , '!=', null);
                            })
                        ->where('quantity','>',0)->whereIn('id',$product_ids)->inRandomOrder()->take(20)->get();


       foreach($products as $product){
            $date = Carbon::parse($product->created_at);
            $now = Carbon::now();
            $days = $date->diffInDays($now);
            $product['days'] = $days;

       }

       return view('welcome',compact('products','categories','lcategories','trendingProducts','bestSellers'));
    }

    public function terms(){
        $categories = \App\Categories::with('subcategories')->get();
        return view('terms',compact('categories'));
    }

    public function search(Request $request){

        $categories = \App\Categories::all();

        $search =  $request->search;

        $category = \App\Categories::find($request->category_id);

        $products= \App\Products::with('influencer')->where ( 'product_name', 'LIKE', '%' . $search . '%' )->where('status','=','approved')
                                ->where(function($q){    
                                    $q->where('vendor_id' , '!=', null)
                                    ->orWhere('agent_id' , '!=', null)
                                    ->orWhere('influencer_id' , '!=', null);
                                })
                                ->where('quantity','>',0)->orderBy('id','DESC')->inRandomOrder()->paginate(8);

        return view('front.show_category',compact('products','categories','category'));
       

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $categories = \App\Categories::all();
        $product = \App\Products::with('category','brand','subcategory','gallery','vendor.user','agent.user')->where('slug','=',$slug)->first();
        $clicks = $product->clicks + 1;
        \App\Products::where('slug','=',$slug)->update(['clicks'=>$clicks]);
        return view('front.product',compact('product','categories'));
    }

    public function displayImage($filename)
        {
            $path = 'storage/images/'.$filename;
            if (!File::exists($path)) {
                abort(404);
            }
        $file = File::get($path);
        $type = File::mimeType($path);
        $response = Response::make($file, 200);
        $response->header("Content-Type", $type);
        return $response;
    }

    public function category($slug){

        $categories = \App\Categories::all();

        $category = \App\Categories::where('slug','=',$slug)->first();

        $products =   \App\Products::with('category','subcategory','gallery')->where('category_id','=',$category->id)
                                    ->where(function($q){    
                                        $q->where('vendor_id' , '!=', null)
                                        ->orWhere('agent_id' , '!=', null)
                                        ->orWhere('influencer_id' , '!=', null);
                                    })
                                    ->where('quantity','>',0)->where('status','=','approved')->orderBy('id','DESC')->inRandomOrder()->paginate(8);

        $trendingProducts = \App\Products::with('category','subcategory')->where('status','=','approved')
                                    ->where(function($q){    
                                        $q->where('vendor_id' , '!=', null)
                                        ->orWhere('agent_id' , '!=', null);
                                        })
                                    ->where('quantity','>',0)
                                    ->orderBy('clicks','DESC')
                                    ->where('category_id',$category->id)
                                    ->inRandomOrder()->take(10)->get();

        return view('front.show_category',compact('products','categories','category','trendingProducts'));

    }

    public function shop($id){

        $categories = \App\Categories::all();

        $influencer = \App\Influencer::where('id','=',$id)->first();

        $products =   \App\Products::with('category','subcategory','gallery')->where('influencer_id','=',$id)
                                    ->where(function($q){    
                                        $q->where('vendor_id' , '!=', null)
                                        ->orWhere('agent_id' , '!=', null)
                                        ->orWhere('influencer_id' , '!=', null);
                                    })
                                    ->where('quantity','>',0)->where('status','=','approved')->orderBy('id','DESC')->inRandomOrder()->paginate(8);

        return view('front.shop',compact('products','categories','influencer'));

    }

    public function subcategory($id){

        $categories = \App\Categories::all();

        $subcategory = \App\SubCategories::where('id','=',$id
        )->first();

        $category = \App\Categories::where('id','=',$subcategory->category_id)->first();

        $products = \App\Products::with('category','subcategory','gallery')->where('subcategory_id','=',$id)
                        ->where('vendor_id' , '!=', null)
                        ->where('quantity','>',0)->where('status','=','approved')->orderBy('id','DESC')->paginate(10);

        $trendingProducts = \App\Products::with('category','subcategory')->where('status','=','approved')
                        ->where(function($q){    
                            $q->where('vendor_id' , '!=', null)
                            ->orWhere('agent_id' , '!=', null);
                            })
                        ->where('subcategory_id',$subcategory->id)
                        ->where('quantity','>',0)->orderBy('clicks','DESC')->inRandomOrder()->take(10)->get();

        return view('front.show_subcategory',compact('products','trendingProducts','categories','category','subcategory'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request, $slug)
    {
        $categories = \App\Categories::all();
        $product_quantity = "1";

        $product = \App\Products::with('category','subcategory','gallery')->where('slug','=',$slug)->first();

        if($product->product_price < 5000){
            $minDeposit = 0.2*$product->product_price;
        }else {
            $minDeposit = 0.1 *$product->product_price;
        }

        //    return($product_quantity);
        return view('front.checkout',compact('product','product_quantity','categories','minDeposit'));
    }

    public function checkout_bonga(Request $request, $slug)
    {

        $categories = \App\Categories::all();
        $product_quantity = "1";

        $product = \App\Products::with('category','subcategory','gallery')->where('slug','=',$slug)->first();

        if($product->product_price < 5000){
            $minDeposit = 0.2*$product->product_price;
        }else {
            $minDeposit = 0.1 *$product->product_price;
        }

        //    return($product_quantity);
        return view('front.checkout_bonga',compact('product','product_quantity','categories','minDeposit'));
    }

    public function checkout_existing(Request $request, $slug)
    {

        $categories = \App\Categories::all();
        $product_quantity = "1";

        $product = \App\Products::with('category','subcategory','gallery')->where('slug','=',$slug)->first();

        if($product->product_price < 5000){
            $minDeposit = 0.2*$product->product_price;
        }else {
            $minDeposit = 0.1 *$product->product_price;
        }

        //    return($product_quantity);
        return view('front.checkoutAccount',compact('product','product_quantity','categories','minDeposit'));
    }

    public function checkout_bonga_existing(Request $request, $slug){

        $categories = \App\Categories::all();
        $product_quantity = "1";

        $product = \App\Products::with('category','subcategory','gallery')->where('slug','=',$slug)->first();

        if($product->product_price < 5000){
            $minDeposit = 0.2*$product->product_price;
        }else {
            $minDeposit = 0.1 *$product->product_price;
        }

        //    return($product_quantity);
        return view('front.checkoutAccountBonga',compact('product','product_quantity','categories','minDeposit'));

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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function make_booking_account(Request $request)
    {

        $zone_id = null;

        $valid_phone = '254'.ltrim($request->input('phone'), '0');

        $existingCustomer = \App\Customers::where('phone','=',$valid_phone)->first();
        
        $booking_reference = 'BKG'.rand(10,1000000);

        $booking_date = now();

        $$booking_date = strtotime($booking_date);

        $product = \App\Products::find($request->product_id);

        // return $product;

        $due_date = date('Y-m-d', strtotime($booking_date. ' + 3 months'));


        if($existingCustomer === null){
            return redirect()->back()->with('error',"You have no account");
        }else{
        $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$request->product_id)->first();

        if($product->product_price < 5000){
            $minDeposit = 0.2*$product->product_price;
        }else {
            $minDeposit = 0.1 *$product->product_price;
        }
        if($request->initial_deposit<200){
          return redirect()->back()->with('error',"The Minimum deposit for this product is : KES ".number_format(200,0));
        }

        $booking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->where('status','=','active')->first();


        if($booking){
            return back()->with('error','You have an ongoing booking. You cannot make another booking until you finish the existing one.');
            $pendingbooking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->where('status','=','pending')->first();
            if($pendingbooking){
                return back()->with('error','You have an ongoing booking. You cannot make another booking until you finish the existing one.');
            }
        }


        if((10000 <= $product->product_price) && ($product->product_price <= 20000)){
            $discount = 200;
        }elseif($product->price >20000) {
            $discount = 500;
        }else {
            $discount = 0;
        }

        if($discount>0){
            $showDiscount = 1;
        }else {
            $showDiscount = 0;
        }


        if($product->weight != 0){
            $weight_array = preg_split('#(?<=\d)(?=[a-z])#i', $product->weight);
        }else{
            $weight_array = (['0','g']);
        }

       
        
        if(!empty($request->county_id)){
            // For Other counties
            $county = \App\Counties::find($request->county_id);

            $product_weight = $weight_array;

            if($product_weight[1] == 'g'){
                $shipping_cost = 300;
            }elseif($product_weight[1] == 'kg' && $product_weight[0]<=5){
                $shipping_cost = 300;
            }elseif($product_weight[1] == 'kg' && $product_weight[0]>5){
            $extra_kg = $product_weight[0] - 5;
            $extra_cost = (30 * $extra_kg);
            $vat = 0.16*$extra_cost;
            $shipping_cost = 300 + $extra_cost + $vat;
            }

            $location_type = 'outside_nairobi';

        }else if(!empty($request->dropoff)) {
            // For Nairobi county

            $dropoff = \App\NairobiDropOffs::find($request->dropoff);

            $zone = \App\NairobiZones::find($dropoff->zone_id);

            $zone_id = $zone->id;

            $product_weight = $weight_array;

            if($product_weight[1] == 'g'){
                $shipping_cost =$zone->price_one_way;
            }elseif($product_weight[1] == 'kg' && $product_weight[0]<=5){
                $shipping_cost = $zone->price_one_way;
            }elseif($product_weight[1] == 'kg' && $product_weight[0]>5){
            $extra_kg = $product_weight[0] - 5;
            $extra_cost = (20 * $extra_kg);
            $shipping_cost = $zone->price_one_way + $extra_cost;

            }

            $location_type = 'within_nairobi';

        }


        $vendor_code = null;

        $agent_code = null;

        $influencer_code = null;


        if($product->agent_id !=null){

        $agent = \App\Agents::where('id','=',$product->agent_id)->first();

        $agent_code = $agent->agent_code;

        }elseif($product->vendor_id !=null){

         $vendor = \App\Vendor::where('id','=',$product->vendor_id)->first();

         $vendor_code = $vendor->vendor_code;

        }elseif($product->influencer_id !=null){
    
            $influencer = \App\Influencer::where('id','=',$product->influencer_id)->first();
        
            $influencer_code = $influencer->code;
        
           }

        

        $total_cost = ($product->product_price + $shipping_cost) - $discount;

        
        $booking = new \App\Bookings();
        $booking->customer_id = $existingCustomer->id; 
        $booking->product_id  = $request->product_id;
        $booking->county_id = $request->county_id;
        $booking->location_id = $request->location_id;
        $booking->zone_id = $zone_id;
        $booking->dropoff_id = $request->dropoff;
        $booking->booking_reference = $booking_reference;
        $booking->quantity  = '1';
        $booking->amount_paid = "0";
        $booking->balance = $total_cost;
        $booking->payment_mode  = 'Mpesa';
        $booking->date_started  = $booking_date;
        $booking->due_date = $due_date;
        $booking->status = "pending";
        $booking->location_type = $location_type;
        $booking->delivery_location = $request->delivery_location;
        $booking->discount = $discount;
        $booking->shipping_cost = $shipping_cost;
        $booking->total_cost =  $total_cost;
        $booking->vendor_code  = $vendor_code;
        $booking->agent_code  = $agent_code;
        $booking->influencer_code = $influencer_code;
        $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

        $booking_reference = 'BKG'.$booking_id;

        \App\Bookings::where('id',$booking_id)->update(['booking_reference'=>$booking_reference]);

        $reciepient = $valid_phone;

        if($showDiscount == 1){
            
            $message = "Booking of : ".$product->product_name." was successful. "."You recieved a discount of ".number_format($discount,2).", Product Price is ".$product->product_price.", Shipping Cost ".$shipping_cost.", Total Price is KES ".number_format(( $total_cost),2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4029165) and Account Number : ".$booking_reference.", Enter Amount : KES ".number_format($request->initial_deposit,2) ." Terms & Conditions Apply";
            $this->sendMessage($message,$reciepient);
        
        }else{
            $message = "Booking of : ".$product->product_name." was successful, Product price ".$product->product_price.", Shipping Cost ".$shipping_cost.", Total Price is KES ".number_format($total_cost,2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4029165) and Account Number : ".$booking_reference.", Enter Amount Enter Amount : KES ".number_format($minDeposit,2);
            $this->sendMessage($message,$reciepient);
        }

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;
        
         $message =  $this->stk_push($amount,$msisdn,$booking_ref);
        
        }

        $categories = \App\Categories::with('subcategories')->get();
        
        return view('front.thanks',compact('product','customer','booking_reference','categories','message','amount'));
        

    }

    public function make_booking(Request $request){

        $dropoff = $request->dropoff;
        $county_id = $request->county_id;
        $location_id = $request->location_id;


        if(is_null($dropoff) && is_null($location_id)){
            return back()->withInput()->with('error','Please Pick your preferred delivery location!');
        }

        // return $request->all();

        $zone_id = null;

        $categories = \App\Categories::all();

        $valid_phone = preg_match("/^(?:\+?254|0)?(7\d{8})/", $request->phone,$p_matches);
        //Valid email
        $valid_email = preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/", $request->email, $e_matches);
        //preg_match() returns 1 if the pattern matches given subject, 0 if it does not, or FALSE if an error occurred. 

        $valid_phone = preg_match("/^(?:\+?254|0)?(7\d{8})/", $request->phone, $p_matches);

        $valid_phone = $valid_phone != 1 ? $request->get('phone') : '254' . $p_matches[1];

        $user = $request->isMethod('put') ? \App\User::findOrFail($request->user_id) : new \App\User;
        $existingUser = \App\User::where('email',  $request->input('email'))->first();

        if($existingUser)
        {

        // return ($existingUser);

        $existingCustomer = \App\Customers::where('user_id','=',$existingUser->id)->first();


        $booking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->where('status','=','active')->first();


        if($booking){
            return back()->with('error','You have an ongoing booking. You cannot make another booking until you finish the existing one.');
            $pendingbooking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->where('status','=','pending')->first();
            if($pendingbooking){
                return back()->with('error','You have an ongoing booking. You cannot make another booking until you finish the existing one.');
            }
        }


        $booking_reference = 'BKG'.rand(10,1000000);

        $booking_date = now();

        $product = \App\Products::find($request->product_id);

        if((10000 <= $product->product_price) && ($product->product_price <= 20000)){
            $discount = 200;
        }elseif($product->price >20000) {
            $discount = 500;
        }else {
            $discount = 0;
        }

        if($discount>0){
            $showDiscount = 1;
        }else {
            $showDiscount = 0;
        }

        $due_date = date('Y-m-d', strtotime($booking_date. ' + 3 months'));


        $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$request->product_id)->first();

        if($product->product_price < 5000){
            $minDeposit = 0.2*$product->product_price;
        }else {
            $minDeposit = 0.1 *$product->product_price;
        }


        if($request->initial_deposit<200){

          return redirect()->back()->with('error',"The Minimum deposit for this product is : KES ".number_format(200,0));
         
        }


        if($product->weight != 0){
            $weight_array = preg_split('#(?<=\d)(?=[a-z])#i', $product->weight);
        }else{
            $weight_array = (['0','g']);
        }

       
        
        if(!empty($request->county_id)){
            // For Other counties
            $county = \App\Counties::find($request->county_id);

            $product_weight = $weight_array;

            if($product_weight[1] == 'g'){
                $shipping_cost = 300;
            }elseif($product_weight[1] == 'kg' && $product_weight[0]<=5){
                $shipping_cost = 300;
            }elseif($product_weight[1] == 'kg' && $product_weight[0]>5){
            $extra_kg = $product_weight[0] - 5;
            $extra_cost = (30 * $extra_kg);
            $vat = 0.16*$extra_cost;
            $shipping_cost = 300 + $extra_cost + $vat;
            }

            $location_type = 'outside_nairobi';

        }else if(!empty($request->dropoff)) {
            // For Nairobi county

            $dropoff = \App\NairobiDropOffs::find($request->dropoff);

            $zone = \App\NairobiZones::find($dropoff->zone_id);

            $zone_id = $zone->id;

            $product_weight = $weight_array;

            if($product_weight[1] == 'g'){
                $shipping_cost =$zone->price_one_way;
            }elseif($product_weight[1] == 'kg' && $product_weight[0]<=5){
                $shipping_cost = $zone->price_one_way;
            }elseif($product_weight[1] == 'kg' && $product_weight[0]>5){
            $extra_kg = $product_weight[0] - 5;
            $extra_cost = (20 * $extra_kg);
            $shipping_cost = $zone->price_one_way + $extra_cost;

            }

            $location_type = 'within_nairobi';

        }

        $customer = $existingCustomer;

        $total_cost = $product->product_price + $shipping_cost;

        $booking = new \App\Bookings();
        $booking->customer_id = $existingCustomer->id; 
        $booking->product_id  = $request->product_id;
        $booking->booking_reference = $booking_reference;
        $booking->quantity  = '1';
        $booking->amount_paid = "0";
        $booking->balance =   $total_cost - $discount;
        $booking->payment_mode  = 'Mpesa';
        $booking->date_started  = $booking_date;
        $booking->due_date = $due_date;
        $booking->status = "pending";
        $booking->location_type = $location_type;
        $booking->delivery_location = $request->delivery_location;
        $booking->shipping_cost = $shipping_cost;
        $booking->county_id = $request->county_id;
        $booking->location_id = $request->location_id;
        $booking->zone_id = $zone_id;
        $booking->dropoff_id = $request->dropoff;
        $booking->total_cost =  $total_cost  - $discount;

        // return $booking;

        $booking->save();


        $booking_id = DB::getPdo()->lastInsertId();

        $booking_reference = 'BKG'.$booking_id;

        \App\Bookings::where('id',$booking_id)->update(['booking_reference'=>$booking_reference]);


        $reciepient = $valid_phone;

         if($shipping_cost>0){
            $shipping_cost = number_format($shipping_cost,2);
        }else{
            $shipping_cost = ' will be comunicated on booking completion';
        };

        if($showDiscount == 1){
            
            $message = "Booking of : ".$product->product_name." was successful. "."You recieved a discount of ".number_format($discount,2).", Product Price is ".$product->product_price.", Shipping Cost ".$shipping_cost.", Total Price is KES ".number_format(( $total_cost - $discount),2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4029165) and Account Number : ".$booking_reference.", Enter Amount : KES ".number_format($request->initial_deposit,2) ." Terms & Conditions Apply";
            $this->sendMessage($message,$reciepient);
        
        }elseif($showDiscount == 0){

            $message = "Booking of : ".$product->product_name." was successful".", Product Price is ".$product->product_price.", Shipping Cost ".$shipping_cost.", Total Price is KES ".number_format($total_cost,2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4029165) and Account Number : ".$booking_reference.", Enter Amount : KES ".number_format($request->initial_deposit,2)." Terms & Conditions Apply";
            $this->sendMessage($message,$reciepient);

        }

        

        $booking_id = DB::getPdo()->lastInsertId();

        $product = \App\Products::find($request->product_id);

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        return view('front.thanks',compact('product','customer','product','booking_reference','categories','message','amount'));
            
        }

        
        $existingCustomer = \App\Customers::where('phone','=',$valid_phone)->first();

        if($existingCustomer)
        {

        $booking_reference = 'BKG'.rand(10,1000000);

        $booking_date = now();

        $$booking_date = strtotime($booking_date);

        $product = \App\Products::find($request->product_id);

        $due_date = date('Y-m-d', strtotime($booking_date. ' + 3 months'));


        $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$request->product_id)->first();

        if($product->product_price < 5000){
            $minDeposit = 0.2*$product->product_price;
        }else {
            $minDeposit = 0.1 *$product->product_price;
        }


        if($request->initial_deposit<200){

          return redirect()->back()->with('error',"The Minimum deposit for this product is : KES ".number_format(200,0));
         
        }

        $booking = new \App\Bookings();
        $booking->customer_id = $existingCustomer->id; 
        $booking->product_id  = $request->product_id;
        $booking->county_id = $request->county_id;
        $booking->location_id = $request->location_id;
        $booking->zone_id = $zone_id;
        $booking->dropoff_id = $request->dropoff;
        $booking->booking_reference = $booking_reference;
        $booking->quantity  = "1";
        $booking->amount_paid = "0";
        $booking->balance = $product->product_price;
        $booking->payment_mode  = 'Mpesa';
        $booking->date_started  = $booking_date;
        $booking->due_date = $due_date;
        $booking->status = "pending";
        $booking->total_cost = $product->product_price;
        $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

        $booking_reference = 'BKG'.$booking_id;

        \App\Bookings::where('id',$booking_id)->update(['booking_reference'=>$booking_reference]);

        $reciepient = $valid_phone;
        $message = "Booking of : ".$product->product_name." was successful. Total Price is KES ".number_format($product->product_price,2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4029165) and Account Number : ".$booking_reference.",Enter Amount : KES ".number_format($minDeposit,2);
        $this->sendMessage($message,$reciepient);

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $product = \App\Products::find($request->product_id);

        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        return view('front.thanks',compact('product','customer','booking_reference','categories','message','amount'));
            
        }

        $user = new \App\User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('phone'));
        $user->save();

        $user_id = DB::getPdo()->lastInsertId();

        $customer = new \App\Customers();
        $customer->user_id = $user_id; 
        $customer->phone  = '254'.ltrim($request->input('phone'), '0');
        $customer->save();

        $customer_id = DB::getPdo()->lastInsertId();

        $booking_date = now();

        $booking_date = strtotime($booking_date);

        $product = \App\Products::find($request->product_id);

        $due_date = date('Y-m-d', strtotime($booking_date. ' + 3 months'));

        $booking_reference = 'BKG'.rand(10,1000000);


        $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$request->product_id)->first();

        if($product->product_price < 5000){
            $minDeposit = 0.2*$product->product_price;
        }else {
            $minDeposit = 0.1 *$product->product_price;
        }


        if($request->initial_deposit<200){

          return redirect()->back()->with('error',"The Minimum deposit for this product is : KES ".number_format(200,0));
         
        }

        
        if((10000 <= $product->product_price) && ($product->product_price <= 20000)){
            $discount = 200;
        }elseif($product->price >20000) {
            $discount = 500;
        }else {
            $discount = 0;
        }

        if($discount>0){
            $showDiscount = 1;
        }else {
            $showDiscount = 0;
        }

        $booking = new \App\Bookings();
        $booking->customer_id = $customer_id; 
        $booking->product_id  = $request->product_id;
        $booking->county_id = $request->county_id;
        $booking->location_id = $request->location_id;
        $booking->zone_id = $zone_id;
        $booking->dropoff_id = $request->dropoff;
        $booking->booking_reference = $booking_reference;
        $booking->quantity  = "1";
        $booking->status = "pending";
        $booking->balance = $product->product_price - $discount;
        $booking->amount_paid = "0";
        $booking->payment_mode  = 'Mpesa';
        $booking->date_started  = $booking_date;
        $booking->due_date = $due_date;
        $booking->discount = $discount;
        $booking->total_cost = $product->product_price - $discount;
        $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

        $booking_reference = 'BKG'.$booking_id;

        \App\Bookings::where('id',$booking_id)->update(['booking_reference'=>$booking_reference]);

        $reciepient = $valid_phone;

        if($showDiscount == 1){
            
            $message = "Booking of : ".$product->product_name." was successful. "."You recieved a discount of ".number_format($discount,2)." Total Price is KES ".number_format(($product->product_price - $discount),2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4029165) and Account Number : ".$booking_reference.", Enter Amount : KES ".number_format($request->initial_deposit,2) ." Terms & Conditions Apply";
            $this->sendMessage($message,$reciepient);
        
        }elseif($showDiscount == 0){

            $message = "Booking of : ".$product->product_name." was successful. Total Price is KES ".number_format($product->product_price,2)." And the Payment period is 90 Days"." Incase direct payment fails Go to your MPESA, Select Paybill Enter : (4029165) and Account Number : ".$booking_reference.", Enter Amount : KES ".number_format($request->initial_deposit,2)." Terms & Conditions Apply";
            $this->sendMessage($message,$reciepient);

        }

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $product = \App\Products::find($request->product_id);

        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        return view('front.thanks',compact('product','customer','booking_reference','categories','message','amount'));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lipaNaMpesaPassword($lipa_time)
    {
       
        $passkey = "e16ba1623f2708b2ef89970fa0aa822ec95bf16fe1e4d36a57fc53d6840883b5";
        $BusinessShortCode = '4029165';
        $timestamp =$lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode.$passkey.$timestamp);
        return $lipa_na_mpesa_password;
    }

    public function sendMessage($message,$reciepient){
        $username   = "Combinesms";
        $apiKey     = "cf56a93a37982301267fd00af0554c068a4efeb005213e568278c9492152ca28";

        // Initialize the SDK
        $AT  = new AfricasTalking($username, $apiKey);

        // Get the SMS service
        $sms        = $AT->sms();

        // Set the numbers you want to send to in international format
        $reciepients = $reciepient;

        // Set your message
        $message    = $message;

        // Set your shortCode or senderId
        $from = "Mosmos";

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

            'BusinessShortCode' => '4029165',
            'Password'          => $apiPassword,
            'Timestamp'         => $lipa_time,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $msisdn,
            'PartyB'            =>'4029165',
            'PhoneNumber'       => $msisdn,
            'CallBackURL'       => 'https://combine.co.ke/confirmation-url-Q7NMii654AqcdCNmVgE',
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

        \Log::info('STK DATA => '.print_r(json_encode($responseArray),1));

        if(array_key_exists("errorCode", $responseArray)){
            $message = "Automatic payment failed. Go to your MPESA, Select Paybill Enter : (4029165) and Account Number : ".$booking_ref."Enter Amount : ".number_format($amount,2)." Thank you.";
        }else{
            $message = "A payment prompt has been sent to your phone.Enter MPesa PIN if prompted.";
        }

        return $message;
    }

    public function update_bookings_agent_or_vendor(){

        $bookings = \App\Bookings::with('product')->where('status','=','complete')->get();

        foreach($bookings as $booking){

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
                }

            
        }

        $out = ['Success'=>'True',
             "Bookings"=> $bookings,];

         return response()->json( $out);

            
    }

    

}
