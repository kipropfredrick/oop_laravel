<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use Storage;
use Carbon\Carbon;
use Hash;
use Image;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\SendSMSController;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOrderTransferedMail;
use App\Http\Controllers\FrontPageController;
use File;
use Exception;
use AfricasTalking\SDK\AfricasTalking;
use \App\Mail\SendRegistrationEmail;
use DataTables;
use App\Vendor;
use App\BranchUser;

class BranchVendorController extends Controller
{

       /**
        * Show the form for creating a new resource.
        *
        * @return \Illuminate\Http\Response
        */
       public function complete_bookings(){
    $branch_user = BranchUser::where('user_id','=',Auth::id())->first();
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','complete')->where('branch_id','=',$branch_user->branch_id)->orderBy('id', 'DESC')->get();
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
               $payment = \App\Payments::where('booking_id','=',$booking->id)->latest()->first();
               $booking['date_completed'] = $payment->date_paid;
           }
           return view('backoffice.bookings.completeold',compact('bookings'));  
       }

       public function pending_bookings(){
   
        $branch_user = BranchUser::where('user_id','=',Auth::id())->first();
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','pending')->where('branch_id','=',$branch_user->branch_id)->orderBy('id', 'DESC')->get();
     
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }

        return view('backoffice.bookings.pendingold',compact('bookings'));  
     }

     public function transfer_order(){

        $branch_user = BranchUser::where('user_id','=',Auth::id())->first();

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('branch_id',$branch_user->branch_id)->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }

        // $bookings = [];

        return view('backoffice.bookings.transferold',compact('bookings')); 
        
       

    }   
    public function transfer_orderID(Request $request, $id){

        $booking = \App\Bookings::where('id','=',$id)->first();

        $product = \App\Products::find($booking->product_id);



        if($product->product_code == $request->product_code){
            return back()->with('error','You cannot exchange with the same item');
        }

        $newProduct = \App\Products::where('product_code',$request->product_code)->where('status','=','approved')->first();

$user_id=Auth()->user()->id;
$vendor_id=\App\Vendor::whereUser_id($user_id)->first()->id;
if ($vendor_id!=$newProduct->vendor_id) {
    # code...
    return back()->with('error','You are not authorized to make this order transfer');
}






        if($newProduct == null){
            return back()->with('error','Sorry Product Code does not exist.');
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

        Mail::to($booking->customer->user->email)->send(new SendOrderTransferedMail($details));

        return back()->with('success', "Product exchanged successfully to ".$newProduct->product_name.". New Balance is KES ".number_format($balance,2).".");


    }
       
        public function image_delete($id){

            $image = \App\Gallery::where('id','=',$id)->first();
            $image->delete();
    
            return back()->with('success','Image Deleted.');
    
        }

    
       /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
        */
       public function overdue_bookings(){
      
         $branch_user = BranchUser::where('user_id','=',Auth::id())->first();
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','overdue')->where('branch_id','=',$branch_user->branch_id)->orderBy('id', 'DESC')->get();
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }
           return view('backoffice.bookings.overdueold',compact('bookings'));  
       }

       public function delivered_bookings(){
           
        $vendor = Vendor::where('user_id','=',Auth::id())->first();

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','delivered')->where('vendor_code','=',$vendor->vendor_code)->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.bookings.delivered',compact('bookings'));  
    }

    public function add_product(){

     $vendor = Vendor::where('user_id','=',Auth::id())->first();

     $categories = DB::table('categories')->get();

     $subcategories = DB::table('sub_categories')->get();

     return view('backoffice.products.add',compact('categories','subcategories'));
    }

    public function save_product(Request $request){

        $vendor = Vendor::where('user_id','=',Auth::id())->first();

        $data = $request->except('_token','image_path');

        $weight = $data['weight'].$data['unit'];

        $data['weight'] = $weight;

        unset($data['unit']);

        $image = $request->file('product_image');

        if(!Storage::disk('public')->exists('thumbnail')){
            Storage::disk('public')->makeDirectory('thumbnail');
        }

        if(!Storage::disk('public')->exists('images')){
            Storage::disk('public')->makeDirectory('images');
        }

        $time = now();

        $time = str_replace(":", "-", $time);

        $time = str_replace(" ", "-", $time);

        if ($files = $request->file('product_image')) {
            $fileNameToStore = Image::make($files);
            $originalPath = 'storage/images/';
            $fileNameToStore->save($originalPath.$time.$files->getClientOriginalName());
            $thumbnailPath = 'storage/thumbnail/';
            $fileNameToStore->resize(250, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
            $fileNameToStore = $fileNameToStore->save($thumbnailPath.$time.$files->getClientOriginalName());

            $image = $time.$files->getClientOriginalName();
        }else{
            $image = 'noimage.jpg';
        }

        $rand = rand(100,999);

        $slug = $rand."-".$request->product_name;
        
        $slug =  str_replace(' ', '-', $slug);

        $slug =  str_replace('/','-',$slug);

         $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

        $data['product_code'] = 'P'.rand(10,1000000);
        $data['product_image'] = $image;
        $data['slug'] = $slug;
        $data['status']="approved";
        $data['vendor_id'] = $vendor->id;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('products')->insert($data);

        $product_id = DB::getPdo()->lastInsertId();

        if(!Storage::disk('public')->exists('gallery/thumbnail')){
            Storage::disk('public')->makeDirectory('gallery/thumbnail');
        }

        if(!Storage::disk('public')->exists('gallery/images')){
            Storage::disk('public')->makeDirectory('gallery/images');
        }

        foreach($request->file('image_path') as $image){

            $fileNameToStore = Image::make($image);
            $originalPath = 'storage/gallery/images/';
            $fileNameToStore->save($originalPath. str_replace(' ', '-',$time.$image->getClientOriginalName()));
            $thumbnailPath = 'storage/gallery/thumbnail/';
            $fileNameToStore->resize(250, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
            $fileNameToStore = $fileNameToStore->save($thumbnailPath. str_replace(' ', '-',$time.$image->getClientOriginalName()));

            $image = str_replace(' ', '-',$time.$image->getClientOriginalName());

            DB::table('galleries')->insert( [
                'product_id' => $product_id,
                'image_path' => $image
            ]);

        }

        return Back()->with('success','Product Added.');

    }
   
       public function revoked_bookings(){
         $branch_user = BranchUser::where('user_id','=',Auth::id())->first();
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','revoked')->where('branch_id','=',$branch_user->branch_id)->orderBy('id', 'DESC')->get();
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }
           return view('backoffice.bookings.revokedold',compact('bookings'));  
       }
   
       public function unserviced_bookings(){

    $branch_user = BranchUser::where('user_id','=',Auth::id())->first();
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','unserviced')->where('branch_id','=',$branch_user->branch_id)->orderBy('id', 'DESC')->get();

   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }
           return view('backoffice.bookings.unservicedold',compact('bookings'));  
       }
   
       public function active_bookings(){
   
            $branch_user = BranchUser::where('user_id','=',Auth::id())->first();
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','active')->where('branch_id','=',$branch_user->branch_id)->orderBy('id', 'DESC')->get();

          
 
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }

      
           return view('backoffice.bookings.activeold',compact('bookings'));  
       }

       public function profile()
       {
        $user = \App\User::where('id','=',Auth::id())->first();
        $vendor = Vendor::where('user_id','=',Auth::id())->first();
        return view('backoffice.profiles.vendor',compact('user','vendor'));
       }

       public function update_profile(Request $request){
           $password = $request->password;
           $passwordConf = $request->password_confirm;

           if(strlen($password)<6){
            return back()->with('error','Password must have a minimum of 6 characters.'); 
           }

           if($password == $passwordConf){
            \App\User::where('id','=',$request->user_id)->update(['password'=> Hash::make($password)]);
            return back()->with('success','Password Updated');
           }else {
               return back()->with('error','Passwords entered do not match');
           }

       }

       function create_bookings(Request $request){
        $categories=\App\Categories::get();
        return view('backoffice.bookings.newbooking',compact('categories'));
       }

 

       function make_booking(Request $request){
          $county_id = $request->county_id;
        $exact_location = $request->exact_location;
        $vendor_code = $request->vendor_code;
        $obj=new FrontPageController();

        $categories = \App\Categories::all();
        
        list($msisdn, $network) = $obj->get_msisdn_network($request->phone);

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

        $total_cost = $obj->roundToTheNearestAnything($total_cost, 5);
        
        $existingUser = \App\User::where('email',  $request->input('email'))->first();

        if($existingUser!=null)
        {

        $user = $existingUser;

        $existingCustomer = \App\Customers::where('user_id','=',$existingUser->id)->first();


        $booking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->whereNotIn('status', ['complete','revoked'])->first();

        if($booking!=null){
            return Back()->with("error","Customer has already existing booking");
        }

        //\Auth::login($user);

        $booking_reference = $obj->get_booking_reference();

        $booking_date = now();

        $due_date = Carbon::now()->addMonths(3);

        
        $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$request->product_id)->first();

        
        if($request->initial_deposit<100){

          return redirect()->back()->with('error',"The Minimum deposit for this product is : KES ".number_format(100,0));
         
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
                $booking->amount_paid = $total_cost;
                $booking->balance="0";

                $message =  "Ksh ".$balance." from your mosmos wallet has been used fully pay your placed order";
            }
            else{

                \App\User::where('email',  $request->input('email'))->update(["balance"=>0]);
                $booking->balance =   $total_cost-(intval($balance)); 
                $booking->amount_paid = $balance;
                $booking->status = "active";
                $message =  "Ksh ".$balance." from your mosmos wallet has been used to pay for ordered item partially remaining amount is Ksh.".number_format($total_cost-(intval($balance)));
            }
                
            SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");
        }

        
        $booking->customer_id = $existingCustomer->id; 
        $booking->product_id  = $request->product_id;
        $booking->booking_reference = $obj->get_booking_reference();
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
       // $booking->booking_reference = $this->get_booking_reference();

        $booking->save();
        
        
        $booking_id = DB::getPdo()->lastInsertId();

        $recipients = $valid_phone;
      
        $booking_id = DB::getPdo()->lastInsertId();

        $product = \App\Products::find($request->product_id);

        $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking_reference." and amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

        SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;
        
        $message = $obj->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";
               $details = [
        'email' => $request->email,
        'name'=>$request->name,
            'total_cost'=>$total_cost,
        'productname'=>$product->product_name,
        'booking_reference'=>$booking_reference,
        'initial_deposit'=>number_format($request->initial_deposit),
        'password'=>$request->input('phone'),
        "url" => env('baseurl').encrypt($booking->booking_reference, "mosmos#$#@!89&^")."/invoice"
        ];

        Mail::to($request->email)->send(new SendRegistrationEmail($details));
return Back()->with("success",$stkMessage);
            
        }

        
        $existingCustomer = \App\Customers::where('phone','=',$valid_phone)->first();

        if($existingCustomer)
        {
            
        $booking_date = now();

        $$booking_date = strtotime($booking_date);

        $product = \App\Products::find($request->product_id);

       $due_date = Carbon::now()->addMonths(3);

        if($request->initial_deposit<100){

          return redirect()->back()->with('error',"The Minimum deposit for this product is : KES ".number_format(100));
         
        }

        $booking = new \App\Bookings();
        $booking->customer_id = $existingCustomer->id; 
        $booking->product_id  = $request->product_id;
        $booking->county_id = $request->county_id;
        $booking->exact_location = $exact_location;
        $booking->booking_reference = $obj->get_booking_reference();
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

        $message = $obj->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";
               $details = [
        'email' => $request->email,
        'name'=>$request->name,
        'productname'=>$product->product_name,
        'total_cost'=>$total_cost,
        'booking_reference'=>$booking_reference,
        'initial_deposit'=>number_format($request->initial_deposit),
        'password'=>$request->input('phone'),
        "url" => env('baseurl').encrypt($booking->booking_reference, "mosmos#$#@!89&^")."/invoice"
        ];

        Mail::to($request->email)->send(new SendRegistrationEmail($details));

      return Back()->with("success",$stkMessage);
            
        }

        $user = new \App\User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->password = Hash::make($request->input('phone'));
        $user->save();

        $user_id = DB::getPdo()->lastInsertId();

        $customer = new \App\Customers();
        $customer->user_id = $user_id; 
        $customer->phone  = $valid_phone;
        $customer->save();

        $customer_id = DB::getPdo()->lastInsertId();

        $booking_date = now();

        $booking_date = strtotime($booking_date);

        $product = \App\Products::find($request->product_id);

       $due_date = Carbon::now()->addMonths(3);

       $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$request->product_id)->first();


        $booking = new \App\Bookings();
        $booking->customer_id = $customer_id; 
        $booking->product_id  = $request->product_id;
        $booking->county_id = $request->county_id;
        $booking->exact_location = $exact_location;
        $booking->booking_reference = $obj->get_booking_reference();
        $booking->quantity  = "1";
        $booking->status = "pending";
        $booking->vendor_code = $vendor_code;
        $booking->item_cost = $product->product_price;
        $booking->balance = $total_cost;
        $booking->shipping_cost = $shipping_cost;
        $booking->amount_paid = "0";
        $booking->payment_mode  = 'Mpesa';
        $booking->date_started  = now();
        $booking->due_date = $due_date;
        $booking->total_cost = $total_cost;
        $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

       $recipients = $valid_phone;

       $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking->booking_reference." And amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

       SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

       // $details = [
       //  'email' => $request->email,
       //  'name'=>$request->name,
       //  'booking_reference'=>$booking_reference,
       //  'initial_deposit'=>number_format($request->initial_deposit),
       //  'password'=>$request->input('phone'),
       //  'url'=>encrypt($booking->booking_reference, "mosmos#$#@!89&^");
       //  ];

       //  Mail::to($request->email)->send(new SendRegistrationEmail($details));

       $details = [
        'email' => $request->email,
        'name'=>$request->name,
        'productname'=>$product->product_name,
        'booking_reference'=>$booking_reference,
            'total_cost'=>$total_cost,
        'initial_deposit'=>number_format($request->initial_deposit),
        'password'=>$request->input('phone'),
        "url" => env('baseurl').encrypt($booking->booking_reference, "mosmos#$#@!89&^")."/invoice"
        ];

        Mail::to($request->email)->send(new SendRegistrationEmail($details));


        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        $product = \App\Products::find($request->product_id);

        $message = $obj->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";
return Back()->with("success",$stkMessage);

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

function manualBooking(Request $request){
    $product_quantity=1;
    return view('backoffice.branchvendors.manualBooking',compact('product_quantity'));
}

function bookingdetails(Request $request,$id){
    $booking=\App\Bookings::whereId($id)->first();
    $customer=\App\Customers::whereId($booking->customer_id)->first();
    $user=\App\User::whereId($customer->user_id)->first();
    $product=\App\Products::whereId($booking->product_id)->first();


return view('backoffice.bookings.bookingsdetails', compact('booking','customer','user','product'));
}
function bookingpayments(Request $request,$id){
  if($request->ajax()){ 


        $payments = \App\Payments::with('customer','mpesapayment','customer.user','product:id,product_name,product_code','booking')->where("payments.booking_id","=",$id)->orderBy('payments.id', 'DESC');
       
            

            return DataTables::of($payments)->make(true);

        }
}

function payments(Request $request){
    // $payments =  DB::table('payments')->get();
  $branch_id=Auth::user()->branch_user->branch_id;
        $payments=[];
        $validpaymentreferences=[];
        $validmpesa=[];
if ($request->validmpesa!=null) {
    # code...$vali
    $validmpesa=json_decode($request->validmpesa, true);
}

             if($request->ajax()){ 


        $payments = \App\Payments::with('customer','mpesapayment','customer.user','product:id,product_name,product_code','booking')->whereBranch_id($branch_id)->whereIn("payments.id",$validmpesa)->orderBy('payments.id', 'DESC');
       
            

            return DataTables::of($payments)->make(true);

        }
        else{
            $validpaymentreferences=\App\PaymentLog::select('payment_logs.*')->where("payment_logs.status","=","valid")->pluck('TransID')->toArray();
  $validmpesa=json_encode(\App\Mpesapayments::whereIn("transac_code",$validpaymentreferences)->pluck('payment_id')->toArray());
        }



        return view('backoffice.vendors.payments.index',compact('payments','validmpesa'));
}
function branchusers(Request $request){
  $id=Auth::user()->branch_user->branch_id;
  $branch_users=\App\BranchUser::with('user')->whereBranch_id($id)->get();
  return view('backoffice.branchvendors.branch_users',compact('branch_users'));
}

function adduser(Request $request){
  return view('backoffice.branchvendors.add_user');
}
function usersave(Request $request){
 //$main_vendor_code= \App\Vendor::whereUser_id(Auth::user()->id)->first()->id;
    if(\App\User::where('email',$request->email)->exists()){
        return back()->with('error','Email Exists');
    }elseif(\App\BranchUser::where('phone','254'.ltrim($request->input('phone'), '0'))->exists()){
        return back()->with('error','Phone Exists');
    }

    $user = new \App\User();
    $user->email = $request->input('email');
    $user->name = $request->input('name');
    $user->role ='branch_vendor';
    $user->email_verified_at = now();
    $user->password = Hash::make($request->input('password'));
    $user->save();

    $user_id = DB::getPdo()->lastInsertId();

    // $slug =  str_replace(' ', '-', $request->branch_name);

    // $slug =  str_replace('/','-',$slug);

    // $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

    // $branch = new \App\Branch();
    // $branch->name=$request->branch_name;
    // $branch->slug=$slug;
    // $branch->vendor_id=$main_vendor_code;
    // $branch->save();
$branch = Auth::user()->branch_user;
$branch_id=$branch->branch_id;

 $branch_user = new \App\BranchUser();
 $branch_user->user_id = $user_id;
    $branch_user->branch_id = $branch_id;
    $branch_user->status = "approved";
    $branch_user->phone  = '254'.ltrim($request->input('phone'), '0');
    $branch_user->location  = $branch->location;
    $branch_user->city  = $branch->city;
    $branch_user->role='user';
    $branch_user->country  = $branch->country;

    $branch_user->save();

  return Back()->with("success","user created");
}
}
