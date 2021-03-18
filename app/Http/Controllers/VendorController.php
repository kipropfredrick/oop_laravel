<?php

namespace App\Http\Controllers;

use App\Vendor;
use Illuminate\Http\Request;
use Auth;
use DB;
use Storage;
use Carbon\Carbon;
use Hash;
use Image;
use Illuminate\Support\Facades\Log;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending_products(){

     $vendor = Vendor::where('user_id','=',Auth::id())->first();

     $products = \App\Products::with('category')->where('status','=','pending')->where('vendor_id','=',$vendor->id)->orderBy('id', 'DESC')->get();

     $status = "Pending";

     return view('backoffice.products.index',compact('products','status'));
    }

    public function approved_products(){

        $vendor = Vendor::where('user_id','=',Auth::id())->first();
        
        $products = \App\Products::with('category')->where('status','=','approved')->where('vendor_id','=',$vendor->id)->orderBy('id', 'DESC')->get();
        
        $status = "Approved";
        
        return view('backoffice.products.index',compact('products','status'));
        }


        public function rejected_products(){

            $vendor = Vendor::where('user_id','=',Auth::id())->first();
            
            $products = \App\Products::with('category')->where('status','=','rejected')->where('vendor_id','=',$vendor->id)->orderBy('id', 'DESC')->get();
            
            $status = "Pejected";
            
            return view('backoffice.products.index',compact('products','status'));
            }
        

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function assigned_products(){

        $vendor = Vendor::where('user_id','=',Auth::id())->first();
   
        $products = \App\Products::with('vendor')->where('vendor_id','=',$vendor->id)->orderBy('id', 'DESC')->get();
   
        return view('backoffice.vendors.assigned',compact('products'));
   
       }
   
       public function view_product($id){
   
        $product = \App\Products::with('category','subcategory')->find($id);
   
        return view('backoffice.vendors.viewproduct',compact('product'));
   
       }
       /**
        * Show the form for creating a new resource.
        *
        * @return \Illuminate\Http\Response
        */
       public function complete_bookings(){
   
           $vendor = Vendor::where('user_id','=',Auth::id())->first();
           
           $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','complete')->where('vendor_code','=',$vendor->vendor_code)->orderBy('id', 'DESC')->get();
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
               $payment = \App\Payments::where('booking_id','=',$booking->id)->latest()->first();
               $booking['date_completed'] = $payment->date_paid;
           }
           return view('backoffice.bookings.complete',compact('bookings'));  
       }

       public function pending_bookings(){
   
        $vendor = Vendor::where('user_id','=',Auth::id())->first();
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','pending')->where('vendor_code','=',$vendor->vendor_code)->orderBy('id', 'DESC')->get();
     
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }

        return view('backoffice.bookings.pending',compact('bookings'));  
     }

     public function transfer_order(){

        $vendor = Vendor::where('user_id','=',Auth::id())->first();

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('vendor_code',$vendor->vendor_code)->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }

        // $bookings = [];

        return view('backoffice.bookings.transfer',compact('bookings')); 
        
       

    }

     public function transfer_orderID(Request $request, $id){

        $booking = \App\Bookings::where('id','=',$id)->first();

        $product = \App\Products::find($booking->product_id);

        if($product->product_code == $request->product_code){
            return back()->with('error','You cannot exchange with the same item');
        }

        $newProduct = \App\Products::where('product_code',$request->product_code)->where('status','=','approved')->first();

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

        $booking = \App\Bookings::where('product_id','=',$id)->first();

        $balance = $total_cost - $booking->amount_paid;

        \App\Bookings::where('id','=',$booking->id)->update([
                        "product_id"=>$newProduct->id,
                        "balance"=>$balance,
                        "shipping_cost"=>$shipping_cost,
                        "total_cost"=>$newProduct->product_price
                        ]);

        return back()->with('success', "Product exchanged successfully to ".$newProduct->product_name.". New Balance is KES ".number_format($balance,2).".");

        

    }

       public function product_edit($id)
        {
            $product = \App\Products::with('category','gallery')->find($id);
            
            if($product->weight != 0){
                $weight_array = preg_split('#(?<=\d)(?=[a-z])#i', $product->weight);
            }else{
                $weight_array = (['0','g']);
            }
    
            $product['weight'] = $weight_array;

            $categories = DB::table('categories')->get();

            $subcategories = DB::table('sub_categories')->get();

            return view('backoffice.products.edit',compact('product','categories','subcategories'));
            
        }

        public function update_product(Request $request,$id){

            $data = $request->except('_token','image_path','product_image');

            $weight = $data['weight'].$data['unit'];

            $data['weight'] = $weight;

            unset($data['unit']);
    
            $product_image = $request->file('product_image');
    
            $image_path = $request->file('image_path');
    
            $time = now();

            $time = str_replace(":", "-", $time);

            $time = str_replace(" ", "-", $time);

    
            if($image_path == null){
    
            }else{
                $fileNameToStore = Image::make($image_path);
                $originalPath = 'storage/gallery/images/';
                $fileNameToStore->save($originalPath. str_replace(' ', '-',$time.$image_path->getClientOriginalName()));
                $thumbnailPath = 'storage/gallery/thumbnail/';
                $fileNameToStore->resize(250, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                $fileNameToStore = $fileNameToStore->save($thumbnailPath. str_replace(' ', '-',$time.$image_path->getClientOriginalName()));
    
                $image = str_replace(' ', '-',$time.$image_path->getClientOriginalName());
    
                DB::table('galleries')->insert( [
                    'product_id' => $id,
                    'image_path' => $image
                ]);
            }
    
            if($product_image == null){
    
            }else{
                $fileNameToStore = Image::make($product_image);
                $originalPath = 'storage/images/';
                $fileNameToStore->save($originalPath.$time.$product_image->getClientOriginalName());
                $thumbnailPath = 'storage/thumbnail/';
                $fileNameToStore->resize(250, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                $fileNameToStore = $fileNameToStore->save($thumbnailPath.$time.$product_image->getClientOriginalName());
    
                $image = $time.$product_image->getClientOriginalName();
    
                $data['product_image'] = $image;
            }
    
    
            DB::table('products')->where('id','=',$id)->update($data);
    
            return redirect('/vendor/pending-products')->with('success','Product updated.');
        }
        
        public function image_delete($id){

            $image = \App\Gallery::where('id','=',$id)->first();
            $image->delete();
    
            return back()->with('success','Image Deleted.');
    
        }

        public function product_delete($id){

            $product = \App\products::find($id);
    
            $image_path = public_path().'/storage/images/'.$product->product_image;
    
              
            unlink($image_path);
    
            $galleries = \App\Gallery::where('product_id','=',$product->id)->get();
    
            foreach($galleries as $gallery){
    
                $image_path = public_path().'/storage/gallery/images/'.$gallery->image_path;
                
                unlink($image_path);
    
            }
      
            $product->delete();
            
            return back()->with('success','Product Deleted');
    
        }
   
       /**
        * Store a newly created resource in storage.
        *
        * @param  \Illuminate\Http\Request  $request
        * @return \Illuminate\Http\Response
        */
       public function overdue_bookings(){
           
           $vendor = Vendor::where('user_id','=',Auth::id())->first();
           
           $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','overdue')->where('vendor_code','=',$vendor->vendor_code)->orderBy('id', 'DESC')->get();
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }
           return view('backoffice.bookings.overdue',compact('bookings'));  
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

        $data['product_code'] = 'P'.rand(10,1000000);
        $data['product_image'] = $image;
        $data['slug'] = $slug;
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

        return redirect('/vendor/pending-products')->with('success','Product Added.');

    }
   
       public function revoked_bookings(){
           
           $vendor = Vendor::where('user_id','=',Auth::id())->first();
           
           $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','revoked')->where('vendor_code','=',$vendor->vendor_code)->orderBy('id', 'DESC')->get();
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }
           return view('backoffice.bookings.revoked',compact('bookings'));  
       }
   
       public function unserviced_bookings(){

           $vendor = Vendor::where('user_id','=',Auth::id())->first();
           
           $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','unserviced')->where('vendor_code','=',$vendor->vendor_code)->orderBy('id', 'DESC')->get();
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }
           return view('backoffice.bookings.unserviced',compact('bookings'));  
       }
   
       public function active_bookings(){
   
           $vendor = Vendor::where('user_id','=',Auth::id())->first();

           Log::info('VENDOR CODE : '.$vendor->vendor_code);
   
           $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','active')->where('vendor_code','=',$vendor->vendor_code)->orderBy('id', 'DESC')->get();
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }
           return view('backoffice.bookings.active',compact('bookings'));  
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
}
