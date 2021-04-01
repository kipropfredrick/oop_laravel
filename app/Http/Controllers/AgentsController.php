<?php

namespace App\Http\Controllers;

use App\Agents;
use Illuminate\Http\Request;
use Auth;
use DB;
use Storage;
use Carbon\Carbon;
use Hash;
use Image;

class AgentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function assigned_products(){

     $agent = \App\Agents::where('user_id','=',Auth::id())->first();

     $products = \App\ProductAssignments::with('product','agent')->where('agent_id','=',$agent->id)->orderBy('id', 'DESC')->get();

     return view('backoffice.agents.assigned',compact('products'));

    }

    public function view_product($id){

     $product = \App\Products::with('category','subcategory')->find($id);

     return view('backoffice.agents.viewproduct',compact('product'));

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
    
            return redirect('/agent/pending-products')->with('success','Product updated.');
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function complete_bookings(){

        $agent = \App\Agents::where('user_id','=',Auth::id())->first();
        
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','complete')->where('agent_code',$agent->agent_code)->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
            $payment = \App\Payments::where('booking_id','=',$booking->id)->latest()->first();
            $booking['date_completed'] = $payment->date_paid;
        }
        return view('backoffice.bookings.complete',compact('bookings'));  
    }

    public function approved_products(){

        $agent = Agents::where('user_id','=',Auth::id())->first();
        
        $products = \App\Products::with('category')->where('status','=','approved')->where('agent_id','=',$agent->id)->orderBy('id', 'DESC')->get();
        
        $status = "Approved";
        
        return view('backoffice.products.index',compact('products','status'));
     }

     

        public function pending_products(){

            $agent = Agents::where('user_id','=',Auth::id())->first();
            
            $products = \App\Products::with('category')->where('status','=','pending')->where('agent_id','=',$agent->id)->orderBy('id', 'DESC')->get();
            
            $status = "Pending";
            
            return view('backoffice.products.index',compact('products','status'));
        }

        public function add_product(){
       
            $categories = DB::table('categories')->get();
       
            $subcategories = DB::table('sub_categories')->get();
       
            return view('backoffice.products.add',compact('categories','subcategories'));
           }

        public function rejected_products(){

            $agent = Agents::where('user_id','=',Auth::id())->first();
            
            $products = \App\Products::with('category')->where('status','=','rejected')->where('agent_id','=',$agent->id)->orderBy('id', 'DESC')->get();
            
            $status = "rejected";
            
            return view('backoffice.products.index',compact('products','status'));
          }

          public function save_product(Request $request){

            $agent = Agents::where('user_id','=',Auth::id())->first();
    
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
            $data['slug'] = $slug;
            $data['agent_id'] = $agent->id;
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
    
            return redirect('/agent/pending-products')->with('success','Product Added.');
    
        }
       

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function overdue_bookings(){
        
        $agent = \App\Agents::where('user_id','=',Auth::id())->first();
        
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','overdue')->where('agent_code',$agent->agent_code)->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.bookings.overdue',compact('bookings'));  
    }

    public function pending_bookings(){
        
        $agent = \App\Agents::where('user_id','=',Auth::id())->first();
        
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','pending')->where('agent_code',$agent->agent_code)->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.bookings.pending',compact('bookings'));  
    }

    public function delivered_bookings(){

        $agent = \App\Agents::where('user_id','=',Auth::id())->first();

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=',$agent->agent_code)->where('status','=','agent-delivered')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){

            if($booking->agent_code !== null){
                $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
                $agent = $agent->user->name.' (Agent)';
            }elseif($booking->vendor_code !== null){
                $vendor = \App\Vendors::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
                $agent = $vendor->user->name.' (Vendor)';
            }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
               $agent = "Lipa Mos Mos(Admin)";
            }

            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.delivered',compact('bookings'));  
    }


    public function confirmed_deliveries(){
        $agent = \App\Agents::where('user_id','=',Auth::id())->first();

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code','=',$agent->agent_code)->where('status','=','delivered')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){

            if($booking->agent_code !== null){
                $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
                $agent = $agent->user->name.' (Agent)';
            }elseif($booking->vendor_code !== null){
                $vendor = \App\Vendors::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
                $agent = $vendor->user->name.' (Vendor)';
            }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
               $agent = "Lipa Mos Mos(Admin)";
            }

            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.confirmed_deliveries',compact('bookings'));  
    }

    public function revoked_bookings(){
        
        $agent = \App\Agents::where('user_id','=',Auth::id())->first();
        
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','revoked')->where('agent_code',$agent->agent_code)->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.bookings.revoked',compact('bookings'));  
    }

    public function unserviced_bookings(){
        // $bookings = \App\Bookings::with('customer','customer.user','product','booking')->where('status','=','unserviced')->get();

        $agent = \App\Agents::where('user_id','=',Auth::id())->first();
        
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','unserviced')->where('agent_code',$agent->agent_code)->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.bookings.unserviced',compact('bookings'));  
    }

    public function deliver_booking($id){

        \App\Bookings::where('id','=',$id)
        ->update(["status"=>"agent-delivered"]);

        return back()->with('success','Booking Delivered wait for confirmation from the admin.');

    }

    public function transfer_order(){

        $agent = \App\Agents::where('user_id','=',Auth::id())->first();
        
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('agent_code',$agent->agent_code)->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.bookings.transfer',compact('bookings'));  

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

        $booking = \App\Bookings::where('product_id','=',$id)->first();

        $total_cost = ($newProduct->product_price + $shipping_cost);

        $balance = $total_cost - $booking->amount_paid;

        \App\Bookings::where('id','=',$booking->id)->update([
                                    "product_id"=>$newProduct->id,
                                    "balance"=>$balance,
                                    "shipping_cost"=>$shipping_cost,
                                    "item_cost"=>$newProduct->product_price,
                                    "total_cost"=>$total_cost
                                    ]);

    return back()->with('success', "Product exchanged successfully to ".$newProduct->product_name.". New Balance is KES ".number_format($balance,2).".");

        

    }

    public function active_bookings(){
        // $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','active')->get();

        $agent = \App\Agents::where('user_id','=',Auth::id())->first();
        

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','active')->where('agent_code',$agent->agent_code)->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.bookings.active',compact('bookings'));  
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Agents  $agents
     * @return \Illuminate\Http\Response
     */
    public function show(Agents $agents)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Agents  $agents
     * @return \Illuminate\Http\Response
     */
    public function edit(Agents $agents)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Agents  $agents
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agents $agents)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Agents  $agents
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agents $agents)
    {
        //
    }
}
