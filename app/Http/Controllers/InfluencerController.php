<?php

namespace App\Http\Controllers;

use App\Influencer;
use Illuminate\Http\Request;
use Auth;
use DB;
use Storage;
use Carbon\Carbon;
use Hash;
use Image;
use Log;
class InfluencerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function pending_products(){

        $influencer = Influencer::where('user_id','=',Auth::id())->first();
   
        $products = \App\Products::with('category')->where('status','=','pending')->where('influencer_id','=',$influencer->id)->orderBy('id', 'DESC')->get();
   
        $status = "Pending";
   
        return view('backoffice.products.index',compact('products','status','influencer'));
       }
   
       public function approved_products(){
   
           $influencer = Influencer::where('user_id','=',Auth::id())->first();
           
           $products = \App\Products::with('category')->where('status','=','approved')->where('influencer_id','=',$influencer->id)->orderBy('id', 'DESC')->get();
           
           $status = "Approved";
           
           return view('backoffice.products.index',compact('products','status','influencer'));
           }
   
   
           public function rejected_products(){
   
               $influencer = Influencer::where('user_id','=',Auth::id())->first();
               
               $products = \App\Products::with('category')->where('status','=','rejected')->where('influencer_id','=',$influencer->id)->orderBy('id', 'DESC')->get();
               
               $status = "Pejected";
               
               return view('backoffice.products.index',compact('products','status','influencer'));
               }
           
   
       /**
        * Show the form for creating a new resource.
        *
        * @return \Illuminate\Http\Response
        */
       public function assigned_products(){
   
           $influencer = Influencer::where('user_id','=',Auth::id())->first();
      
           $products = \App\Products::with('influencer')->where('influencer_id','=',$influencer->id)->orderBy('id', 'DESC')->get();
      
           return view('backoffice.influencers.assigned',compact('products','influencer'));
      
          }
      
          public function view_product($id){
      
           $product = \App\Products::with('category','subcategory')->find($id);
      
           return view('backoffice.influencers.viewproduct',compact('product'));
      
          }
          /**
           * Show the form for creating a new resource.
           *
           * @return \Illuminate\Http\Response
           */
          public function complete_bookings(){
      
              $influencer = Influencer::where('user_id','=',Auth::id())->first();
              
              $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','complete')->where('influencer_code','=',$influencer->code)->orderBy('id', 'DESC')->get();
      
              foreach($bookings as $booking){
                  $progress = round(($booking->amount_paid/$booking->total_cost)*100);
                  $booking['progress'] = $progress;
                  $payment = \App\Payments::where('booking_id','=',$booking->id)->latest()->first();
                  if($payment !=null){
                    $booking['date_completed'] = $payment->date_paid;
                  }else{
                    $booking['date_completed'] = "No payment";
                  }
              }
              return view('backoffice.bookings.complete',compact('bookings','influencer'));  
          }
   
          public function pending_bookings(){
      
           $influencer = Influencer::where('user_id','=',Auth::id())->first();
           
           $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','pending')->where('influencer_code','=',$influencer->code)->orderBy('id', 'DESC')->get();
        
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }
   
           return view('backoffice.bookings.pending',compact('bookings','influencer'));  
        }
   
        public function transfer_order(){
   
           $influencer = Influencer::where('user_id','=',Auth::id())->first();
   
           $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code',$influencer->code)->orderBy('id', 'DESC')->get();
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }
   
           // $bookings = [];
   
           return view('backoffice.bookings.transfer',compact('bookings','influencer')); 
           
          
   
       }
   
        public function transfer_orderID(Request $request, $id){
   
           $product = \App\Products::find($id);
   
           if($product->product_code == $request->product_code){
               return back()->with('error','You cannot exchange with the same item');
           }
   
           $newProduct = \App\Products::where('product_code',$request->product_code)->where('status','=','approved')->first();
   
           if($newProduct == null){
               return back()->with('error','Sorry Product Code does not exist.');
           }
   
           $booking = \App\Bookings::where('product_id','=',$id)->first();
   
           $balance = $newProduct->product_price - $booking->amount_paid;
   
           \App\Bookings::where('id','=',$booking->id)->update([
                           "product_id"=>$newProduct->id,
                           "balance"=>$balance,
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
       
               // if($image_path == null){
       
               // }else{
               //     $fileNameToStore = Image::make($image_path);
               //     $originalPath = 'storage/gallery/images/';
               //     $fileNameToStore->save($originalPath. str_replace(' ', '-',$time.$image_path->getClientOriginalName()));
               //     $thumbnailPath = 'storage/gallery/thumbnail/';
               //     $fileNameToStore->resize(250, null, function ($constraint) {
               //                         $constraint->aspectRatio();
               //                     });
               //     $fileNameToStore = $fileNameToStore->save($thumbnailPath. str_replace(' ', '-',$time.$image_path->getClientOriginalName()));
       
               //     $image = str_replace(' ', '-',$time.$image_path->getClientOriginalName());
       
               //     DB::table('galleries')->insert( [
               //         'product_id' => $id,
               //         'image_path' => $image
               //     ]);
               // }
       
               // if($product_image == null){
       
               // }else{
               //     $fileNameToStore = Image::make($product_image);
               //     $originalPath = 'storage/images/';
               //     $fileNameToStore->save($originalPath.$time.$product_image->getClientOriginalName());
               //     $thumbnailPath = 'storage/thumbnail/';
               //     $fileNameToStore->resize(250, null, function ($constraint) {
               //                         $constraint->aspectRatio();
               //                     });
               //     $fileNameToStore = $fileNameToStore->save($thumbnailPath.$time.$product_image->getClientOriginalName());
       
               //     $image = $time.$product_image->getClientOriginalName();
       
               //     $data['product_image'] = $image;
               // }
       
       
               DB::table('products')->where('id','=',$id)->update($data);
       
               return redirect('/influencer/pending-products')->with('success','Product updated.');
           }
           
   
           public function product_delete($id){
   
               $product = \App\Products::find($id);
       
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
              
              $influencer = Influencer::where('user_id','=',Auth::id())->first();
              
              $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','overdue')->where('influencer_code','=',$influencer->code)->orderBy('id', 'DESC')->get();
      
              foreach($bookings as $booking){
                  $progress = round(($booking->amount_paid/$booking->total_cost)*100);
                  $booking['progress'] = $progress;
              }
              return view('backoffice.bookings.overdue',compact('bookings','influencer'));  
          }
   
          public function delivered_bookings(){
              
           $influencer = Influencer::where('user_id','=',Auth::id())->first();
   
           $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','delivered')->where('influencer_code','=',$influencer->code)->orderBy('id', 'DESC')->get();
   
           foreach($bookings as $booking){
               $progress = round(($booking->amount_paid/$booking->total_cost)*100);
               $booking['progress'] = $progress;
           }
           return view('backoffice.bookings.delivered',compact('bookings','influencer'));  
       }
   
       public function add_product(){
   
        $influencer = Influencer::where('user_id','=',Auth::id())->first();
   
        $categories = DB::table('categories')->get();
   
        $subcategories = DB::table('sub_categories')->get();
   
        return view('backoffice.products.add',compact('categories','subcategories'));
       }

       public function logs(){
        $influencer = Influencer::where('user_id','=',Auth::id())->first();
        $logs = \App\InfluencerPaymentlog::with('influencer.user')->where('influencer_id',$influencer->id)->orderBy('id', 'DESC')->get();
        // return $logs;
        return view('backoffice.influencers.selflogs',compact('logs'));
       }
   
       public function save_product(Request $request){
   
           $influencer = Influencer::where('user_id','=',Auth::id())->first();
   
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
   
           $time = time();
   
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
   
           $slug = $time."-".$request->product_name;
           
           $slug =  str_replace(' ', '-', $slug);
   
           $slug =  str_replace('/','-',$slug);
   
           $data['product_code'] = 'P'.rand(10,1000000);
           $data['product_image'] = $image;
           $data['status'] = 'approved';
           $data['slug'] = $slug;
           $data['influencer_id'] = $influencer->id;
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
   
           return redirect('/influencer/pending-products')->with('success','Product Added.');
   
       }
      
          public function revoked_bookings(){
              
              $influencer = Influencer::where('user_id','=',Auth::id())->first();
              
              $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','revoked')->where('influencer_code','=',$influencer->code)->orderBy('id', 'DESC')->get();
      
              foreach($bookings as $booking){
                  $progress = round(($booking->amount_paid/$booking->total_cost)*100);
                  $booking['progress'] = $progress;
              }
              return view('backoffice.bookings.revoked',compact('bookings','influencer'));  
          }
      
          public function unserviced_bookings(){
   
              $influencer = Influencer::where('user_id','=',Auth::id())->first();
              
              $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','unserviced')->where('influencer_code','=',$influencer->code)->orderBy('id', 'DESC')->get();
      
              foreach($bookings as $booking){
                  $progress = round(($booking->amount_paid/$booking->total_cost)*100);
                  $booking['progress'] = $progress;
              }
              return view('backoffice.bookings.unserviced',compact('bookings','influencer'));  
          }
      
          public function active_bookings(){
      
              $influencer = Influencer::where('user_id','=',Auth::id())->first();
   
              Log::info('influencer CODE : '.$influencer->code);
      
              $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','active')->where('influencer_code','=',$influencer->code)->orderBy('id', 'DESC')->get();
      
              foreach($bookings as $booking){
                  $progress = round(($booking->amount_paid/$booking->total_cost)*100);
                  $booking['progress'] = $progress;
              }
              return view('backoffice.bookings.active',compact('bookings','influencer'));  
          }
   
          public function profile()
          {
           $user = \App\User::where('id','=',Auth::id())->first();
           $influencer = Influencer::where('user_id','=',Auth::id())->first();
           return view('backoffice.profiles.influencer',compact('user','influencer'));
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
