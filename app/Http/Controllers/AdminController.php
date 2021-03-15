<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Carbon\Carbon;
use Hash;
use Image;
use AfricasTalking\SDK\AfricasTalking;
use \App\Mail\SendNotificationMail;
use \App\Mail\SendPaymentEmail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\SendSMSController;


class AdminController extends Controller
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


    public function index()
    {
        $totalBookingAmount = \App\Bookings::sum('total_cost');
        $activeBookingAmount = \App\Bookings::where('status','=','active')->sum('total_cost');
        $overdueBookingAmount = \App\Bookings::where('status','=','overdue')->sum('total_cost');
        $completeBookingAmount = \App\Bookings::where('status','=','complete')->sum('total_cost');
        $customersCount = \App\Customers::count();
        $pendingBookingAmount = \App\Bookings::where('status','=','pending')->sum('total_cost');

      return view('backoffice.index',compact('totalBookingAmount','pendingBookingAmount','activeBookingAmount','overdueBookingAmount','completeBookingAmount','customersCount'));
    }

    public function dashboard(){

        $totalBookingAmount = \App\Bookings::sum('total_cost');
        $activeBookingAmount = \App\Bookings::where('status','=','active')->sum('total_cost');
        $overdueBookingAmount = \App\Bookings::where('status','=','overdue')->sum('total_cost');
        $completeBookingAmount = \App\Bookings::where('status','=','complete')->sum('total_cost');
        $pendingBookingAmount = \App\Bookings::where('status','=','pending')->sum('total_cost');

        $customersCount = \App\Customers::count();

      return view('backoffice.index',compact('totalBookingAmount','activeBookingAmount','pendingBookingAmount','overdueBookingAmount','completeBookingAmount','customersCount'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    
     public function products(){

        $products = \App\Products::with('category')->orderBy('id', 'DESC')->get();

        return view('backoffice.products.index',compact('products'));

     }

     public function influencer_products(){

        $products = \App\Products::with('category')->where('influencer_id','!=',null)->orderBy('id', 'DESC')->get();

        $title = "Influencer";

        return view('backoffice.products.index',compact('products','title'));

     }


     public function influencer_logs(){

        $logs = \App\InfluencerPaymentlog::with('influencer.user')->orderBy('id', 'DESC')->get();
	// return $logs;
	 \Log::info('Influncer log => '.print_r($logs,1));
        return view('backoffice.influencers.logs',compact('logs'));

     }

      

     public function vendor_product_approve($id){

        $products = \App\Products::where('id','=',$id)->update(['status'=>'approved']);

        return back()->with('success','Product Approved');
     }

     public function vendor_product_reject($id){
         
        $products = \App\Products::where('id','=',$id)->update(['status'=>'rejected']);

        return back()->with('success','Product Rejected');
     }

     public function vendor_products(){

        $products = \App\Products::with('category','vendor','vendor.user')->where('vendor_id','!=', null)->orderBy('id', 'DESC')->get();

        return view('backoffice.products.vendor',compact('products'));

     }

     public function vendor_pending_products(){
        $status = "Pending";
       $products = \App\Products::with('category','vendor','vendor.user')->where('vendor_id','!=', null)->where('status','=',"pending")->orderBy('id', 'DESC')->get();
       return view('backoffice.products.vendor',compact('products','status')); 
     }

     public function vendor_approved_products(){
        $status = "Approved";
        $products = \App\Products::with('category','vendor','vendor.user')->where('vendor_id','!=', null)->where('status','=',"approved")->orderBy('id', 'DESC')->get();
        return view('backoffice.products.vendor',compact('products','status')); 
      }

      public function agent_approved_products(){
        $products = \App\Products::with('category','agent','agent.user')->where('agent_id','!=', null)->where('status','=',"approved")->orderBy('id', 'DESC')->get();
        return view('backoffice.products.agent',compact('products')); 
      }

      public function agent_pending_products(){
        $products = \App\Products::with('category','agent','agent.user')->where('agent_id','!=', null)->where('status','=',"pending")->orderBy('id', 'DESC')->get();
        return view('backoffice.products.agent',compact('products')); 
      }

      public function agent_rejected_products(){
        $products = \App\Products::with('category','agent','agent.user')->where('agent_id','!=', null)->where('status','=',"rejected")->orderBy('id', 'DESC')->get();
        return view('backoffice.products.agent',compact('products')); 
      }

      public function vendor_rejected_products(){
        $status = "Rejected";
        $products = \App\Products::with('category','vendor','vendor.user')->where('vendor_id','!=', null)->where('status','=',"rejected")->orderBy('id', 'DESC')->get();
        return view('backoffice.products.vendor',compact('products','status')); 
      }

     public function approve_product($id){

        $products = \App\Products::where('id','=',$id)->update(['status'=>'approved']);

        return back()->with('success','Product Approved');

     }

     public function approve_vendor_product($id){

        $products = \App\Products::where('id','=',$id)->update(['status'=>'approved']);

        return back()->with('success','Product Approved');

     }


     public function view_vendor($id){
         $vendor = \App\Vendor::with('user','city')->where('id','=',$id)->first();

         $products = \App\Products::where('vendor_id','=',$vendor->id)->get();

         return view('backoffice.vendors.view-vendor',compact('vendor','products'));
         
     }

     public function view_influencer($id){
        $influencer = \App\Influencer::with('user','commission_totals')->where('id','=',$id)->first();

        $products = \App\Products::where('influencer_id','=',$id)->get();

        return view('backoffice.influencers.view',compact('influencer','products'));
        
    }

     public function vendor_product_view($id){

        $product = \App\Products::with('category','subcategory','third_level_category')->where('id','=',$id)->first();

        return view('backoffice.products.view',compact('product'));

     }

     public function view_agent($id){
        $agent = \App\Agents::with('user','city')->where('id','=',$id)->first();
        
        return view('backoffice.agents.view-agent',compact('agent'));
        
    }

     public function reject_product($id){

        $products = \App\Products::where('id','=',$id)->update(['status'=>'rejected']);
        
        return back()->with('success','Product rejected');
        
     }

     public function reject_vendor_product($id){
        $products = \App\Products::where('id','=',$id)->update(['status'=>'rejected']);
        
        return back()->with('success','Product rejected');
     }

     public function reject_agent_product($id){
        $products = \App\Products::where('id','=',$id)->update(['status'=>'rejected']);
        
        return back()->with('success','Product rejected');
     }

    public function categories()
    {
        $categories = DB::table('categories')->get();

        return view('backoffice.products.categories',compact('categories'));
    }

    public function save_category(Request $request)
    {
        $slug =  str_replace(' ', '-', $request->category_name);

        $slug =  str_replace('/','-',$slug);

        $image = $request->file('category_icon');
    
            if(!Storage::disk('public')->exists('thumbnail')){
                Storage::disk('public')->makeDirectory('thumbnail');
            }
    
            if(!Storage::disk('public')->exists('images')){
                Storage::disk('public')->makeDirectory('images');
            }
    
            $time = time();
    
            if ($files = $request->file('category_icon')) {
                $fileNameToStore = Image::make($files);
                $originalPath = 'storage/images/';
                $fileNameToStore->save($originalPath.$time.$files->getClientOriginalName());
                $thumbnailPath = 'storage/thumbnail/';
                $fileNameToStore->resize(250, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                $fileNameToStore = $fileNameToStore->save($thumbnailPath.$time.$files->getClientOriginalName());
    
                $category_icon = $time.$files->getClientOriginalName();
            }else{
                $category_icon = 'noimage.jpg';
            }

        DB::table('categories')->insert(['category_name'=>$request->category_name,'slug'=>$slug,'category_icon'=>$category_icon]);
        
        return redirect('/admin/product-categories')->with('success','Category Added.');
    }

    public function save_brand(Request $request)
    {
        $slug =  str_replace(' ', '-', $request->brand_name);

        $slug =  str_replace('/','-',$slug);

        $image = $request->file('brand_icon');
    
            if(!Storage::disk('public')->exists('thumbnail')){
                Storage::disk('public')->makeDirectory('thumbnail');
            }
    
            if(!Storage::disk('public')->exists('images')){
                Storage::disk('public')->makeDirectory('images');
            }
    
            $time = time();
    
            if ($files = $request->file('brand_icon')) {
                $fileNameToStore = Image::make($files);
                $originalPath = 'storage/images/';
                $fileNameToStore->save($originalPath.$time.$files->getClientOriginalName());
                $thumbnailPath = 'storage/thumbnail/';
                $fileNameToStore->resize(250, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                $fileNameToStore = $fileNameToStore->save($thumbnailPath.$time.$files->getClientOriginalName());
    
                $brand_icon = $time.$files->getClientOriginalName();
            }else{
                $brand_icon = 'noimage.jpg';
            }

        DB::table('brands')->insert(['brand_name'=>$request->brand_name,'slug'=>$slug,'brand_icon'=>$brand_icon]);
        
        return redirect('/admin/product-brands')->with('success','Brand Added.');
    }

    public function update_brand(Request $request,\App\Brand $brand)
    {
            $slug =  str_replace(' ', '-', $request->brand_name);

            $slug =  str_replace('/','-',$slug);

           $image = $request->file('brand_icon');
    
            if($image){
                if(!Storage::disk('public')->exists('thumbnail')){
                Storage::disk('public')->makeDirectory('thumbnail');
            }
    
            if(!Storage::disk('public')->exists('images')){
                Storage::disk('public')->makeDirectory('images');
            }
    
            $time = time();
    
            if ($files = $request->file('brand_icon')) {
                $fileNameToStore = Image::make($files);
                $originalPath = 'storage/images/';
                $fileNameToStore->save($originalPath.$time.$files->getClientOriginalName());
                $thumbnailPath = 'storage/thumbnail/';
                $fileNameToStore->resize(250, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                $fileNameToStore = $fileNameToStore->save($thumbnailPath.$time.$files->getClientOriginalName());
    
                $brand_icon = $time.$files->getClientOriginalName();
            }else{
                $brand_icon = 'noimage.jpg';
            }
            }else{
                $brand_icon = $brand->brand_icon;
            }

        DB::table('brands')->where('id',$brand->id)->update(['brand_name'=>$request->brand_name,'slug'=>$slug,'brand_icon'=>$brand_icon]);
        
        return redirect('/admin/product-brands')->with('success','Brand Added.');
    }

    public function edit_category($id){

        $category = DB::table('categories')->where('id','=',$id)->first();

        return view('backoffice.products.editcategory',compact('category'));

    }

    public function update_category(Request $request, $id){

        $slug =  str_replace(' ', '-', $request->category_name);

        $slug =  str_replace('/','-',$slug);

        $data['slug'] = $slug;

        $image = $request->file('category_icon');

        if($image !=null){
            
            if(!Storage::disk('public')->exists('thumbnail')){
                Storage::disk('public')->makeDirectory('thumbnail');
            }
    
            if(!Storage::disk('public')->exists('images')){
                Storage::disk('public')->makeDirectory('images');
            }
    
            $time = time();
    
            if ($files = $request->file('category_icon')) {
                $fileNameToStore = Image::make($files);
                $originalPath = 'storage/images/';
                $fileNameToStore->save($originalPath.$time.$files->getClientOriginalName());
                $thumbnailPath = 'storage/thumbnail/';
                $fileNameToStore->resize(250, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                $fileNameToStore = $fileNameToStore->save($thumbnailPath.$time.$files->getClientOriginalName());
    
                $category_icon = $time.$files->getClientOriginalName();
            }else{
                $category_icon = 'noimage.jpg';
            } 

            $data = ['category_name'=>$request->category_name,'slug'=>$slug,'category_icon'=>$category_icon];
        }else{
            $data = ['category_name'=>$request->category_name,'slug'=>$slug];
        }

        DB::table('categories')->where('id','=',$id)->update($data);

        return redirect('/admin/product-categories')->with('success','Category Updated.');
    }

    public function product_brands(){
        $brands = \App\Brand::all();
        return view('backoffice.products.brands',compact('brands'));
    }

    public function add_product()
    {
        $categories = DB::table('categories')->orderBy('id', 'DESC')->get();

        $subcategories = DB::table('sub_categories')->orderBy('id', 'DESC')->get();

        $third_level_categories = DB::table('third_level_categories')->orderBy('id', 'DESC')->get();

        return view('backoffice.products.add',compact('categories','subcategories','third_level_categories'));
    }

    function fetch_sub_categories(Request $request)
    {
        $category_id = $request->get('category_id');

        $first = [
                "id"=>'0',
                "category_id"=>'',
                "subcategory_name"=>"Select Subcategory",
                "commision"=>'',
                "created_at"=>null,
                "updated_at"=>null
                ];

        $arr = [];

        array_push($arr,$first);

        $subcategories = DB::table('sub_categories')
                        ->where('category_id', $category_id)
                        ->get();
        foreach($subcategories as $subcategory){
            array_push($arr,$subcategory);
        }

        $subcategories = $arr;
        
        return $subcategories;
    }

    public function get_third_categories(Request $request){

        $subcategory_id = $request->subcategory_id;

        \Log::info("subcategory_id => ".$subcategory_id);

        $tsubcategories = DB::table('third_level_categories')
                        ->where('subcategory_id', $subcategory_id)
                        ->get();
        return $tsubcategories;

    }

    public function cities(){
        $cities = \App\City::all();
        return view('backoffice.cities',compact('cities'));   
    }

    public function update_city(Request $request,$id){
        $data = $request->except('_token');
        DB::table('cities')->where('id','=',$id)->update($data);

        return back()->with('success','City updated.');
    }

    public function save_city(Request $request){

        DB::table('cities')->insert(['city_name'=>$request->city_name]);
        
        return back()->with('success','City Added.');

    }

    public function save_product(Request $request)
    {

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
        $data['vendor_id'] = 1;
        $data['quantity'] = 10;
        $data['status'] = 'approved';
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

        return redirect('/admin/products')->with('success','Product Added.');
    }

    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function banners()
    {
       $banners = DB::table('banners')->orderBy('id', 'DESC')->get();
        return view('backoffice.banners.index',compact('banners'));
    }

    public function add_banner(){
        return view('backoffice.banners.add'); 
    }

    public function save_banner(Request $request){

        // dd($request->description);
        
        $data = $request->except('_token','image');

        if(!Storage::disk('public')->exists('thumbnail')){
            Storage::disk('public')->makeDirectory('thumbnail');
        }

        if(!Storage::disk('public')->exists('banners')){
            Storage::disk('public')->makeDirectory('banners');
        }

        $time = time();

        if ($files = $request->file('image')) {
            $fileNameToStore = Image::make($files);
            $originalPath = 'storage/banners/';
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
        
        $data['image'] = $image;
        $data['description'] = $request->description;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('banners')->insert($data);

        return redirect('/admin/banners')->with('success','Banner added.');


    }

    public function banner_delete($id){

      $banner = \App\Banners::find($id);

      $image_path = public_path().'/storage/banners/'.$banner->image;
        
      unlink($image_path);

      $banner->delete();
      
      return redirect('/admin/banners')->with('success','Banner Deleted');
    }


    public function banner_view($id){

        $banner = \App\Banners::find($id);

        // return array($banner);

        return view('backoffice.banners.edit',compact('banner'));
      }

      public  function banner_edit($id){
       
        $banner = \App\Banners::find($id);

        return view('backoffice.banners.edit',compact('banner'));
      }

      public function update_banner(Request $request, $id){
        
        $data = $request->except('_token','image');

        $banner = \App\Banners::find($id);

        $image = $request->file('image');

        if($image!== null){
          $image_path = public_path().'/storage/banners/'.$banner->image;
       
            unlink($image_path);
      
        $time = time();
        if ($files = $request->file('image')) {
            $fileNameToStore = Image::make($files);
            $originalPath = 'storage/banners/';
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

        $data['image'] = $image;

      }

        DB::table('banners')->where('id','=',$id)->update($data);
        return redirect('/admin/banners')->with('success','Banner Updated');
      }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function product_edit($id)
    {
        $product = \App\Products::with('category','gallery')->find($id);

        if($product->weight != 0){
            $weight_array = preg_split('#(?<=\d)(?=[a-z])#i', $product->weight);
        }else{
            $weight_array = (['0','g']);
        }

        $product['weight'] = $weight_array;

        // return $product;

        $categories = DB::table('categories')->get();

        $subcategories = DB::table('sub_categories')->get();

        return view('backoffice.products.edit',compact('product','categories','subcategories'));
        
    }

    public function add_vendor(){

        return view('backoffice.vendors.add');

    }


    public function save_vendor(Request $request){

    if(\App\User::where('email',$request->email)->exists()){
        return back()->with('error','Email Exists');
    }elseif(\App\Vendor::where('phone','254'.ltrim($request->input('phone'), '0'))->exists()){
        return back()->with('error','Phone Exists');
    }

    $user = new \App\User();
    $user->email = $request->input('email');
    $user->name = $request->input('name');
    $user->role ='vendor';
    $user->email_verified_at = now();
    $user->password = Hash::make($request->input('password'));
    $user->save();

    $user_id = DB::getPdo()->lastInsertId();

    $vendor = new \App\Vendor();
    $vendor->user_id = $user_id; 
    $vendor->business_name = $request->business_name; 
    $vendor->status = "approved";
    $vendor->phone  = '254'.ltrim($request->input('phone'), '0');
    $vendor->location  = $request->input('location');
    $vendor->city_id  = $request->input('city_id');
    $vendor->country  = $request->input('country');
    $vendor->save();

    return redirect('/admin/vendors')->with('success','Vendor Saved');

    }

    public function view_vendor_product($id)
    {
        $product = \App\Products::with('category','subcategory','brand','third_level_category')->where('id','=',$id)->first();

        $categories = DB::table('categories')->get();

        $subcategories = DB::table('sub_categories')->get();

        return view('backoffice.products.view',compact('product','categories','subcategories'));
        
    }

    public function view_agent_product($id)
    {
        $product = \App\Products::with('category','gallery')->find($id);

        $categories = DB::table('categories')->get();

        $subcategories = DB::table('sub_categories')->get();

        return view('backoffice.products.view',compact('product','categories','subcategories'));
        
    }

    public function assign_product($id){

        $product = \App\Products::with('category')->find($id);

        $categories = DB::table('categories')->get();

        $agents = DB::table('agents')->join('users','users.id','agents.user_id')->select('agents.id as agent_id','agents.*','users.*')->orderBy('agents.id', 'DESC')->get();

        // return($agents);

        return view('backoffice.products.assign',compact('product','categories','agents'));

    }

    public function assign_save_product(Request $request, $id){

     $product = \App\Products::find($id);

     if($request->quantity>$product->quantity){
         return back()->withInput()->with('error', 'The quantity entered is higher than the availble');
     }else{
         $data = ['product_id'=>$id,
                   'agent_id'=>$request->agent_id,
                    'quantity'=>$request->quantity,
                    'created_at'=>now(),
                    'updated_at'=>now(),
                 ];

                 $newQuantity =  $product->quantity - $request->quantity;

                 DB::table('product_assignments')->insert($data);

                 DB::table('products')->where('id','=',$id)->update(['quantity'=>$newQuantity]);

                 return redirect('/admin/products')->with('success','Product assigned to agent.');

         }

    }

    public function view_category($id){
      $category = \App\Categories::with('subcategories')->find($id);
      $subcategories = \App\SubCategories::where('category_id','=',$category->id)->get();

      return view('backoffice.products.subcategories',compact('category','subcategories'));

    }

    public function view_subcategory($id){
        $subcategory = \App\SubCategories::with('thirdlevelcategories')->find($id);
  
        return view('backoffice.products.tsubcategories',compact('subcategory'));
  
      }

    public function save_subcategory(Request $request){

        $data = $request->except('_token');

        $slug =  str_replace(' ', '-', $request->subcategory_name);

        $slug =  str_replace('/','-',$slug);

        $data['slug'] = $slug;

        DB::table('sub_categories')->insert($data);

        return back()->with('success','Subcategory added');

    }

    public function save_tsubcategory(Request $request){

        $data = $request->except('_token');

        $data['created_at'] = now();

        $data['updated_at'] = now();

        DB::table('third_level_categories')->insert($data);

        return back()->with('success','Category added');  
    }


    public function update_subcategory(Request $request,$id){

        $data = $request->except('_token');

        $slug =  str_replace(' ', '-', $request->subcategory_name);

        $slug =  str_replace('/','-',$slug);

        $data['slug'] = $slug;

        DB::table('third_level_categories')->where('id','=',$id)->update($data);

        return back()->with('success','Category updated');

    }

    public function update_tsubcategory(Request $request,$id){

        $data = $request->except('_token');

        DB::table('third_level_categories')->where('id','=',$id)->update($data);

        return back()->with('success','Subcategory updated');

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

        return redirect('/admin/products')->with('success','Product updated.');
    }

    public function active_bookings(){

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','active')->orderBy('id', 'DESC')->get();

        // return $bookings;

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            $payment = \App\Payments::where('booking_id','=',$booking->id)->latest()->first();

            \Log::info('Payment Array =>'.json_encode($payment));

            if($payment !=null){
                $booking['date_completed'] = $payment->date_paid;
            }else{
                $booking['date_completed'] = "NULL";
            }

            if($booking->agent_code !== null){
                $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
               
                if(isset($agent->user)){
                    $agent = $agent->user->name.' (Agent)';
                }elseif(isset($influencer->user)){
                    $agent = $influencer->user->name.' (Influencer)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }


            }elseif($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }
            }elseif($booking->influencer_code !== null){
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
                if($influencer == null){
                    $agent = "Lipa Mos Mos (Admin)";
                   }else {
                      if(isset($influencer->user)){
                        $agent = $influencer->user->name.' (Influencer)';
                      }
                   }
            }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
               $agent = "Lipa Mos Mos (Admin)";
            }


            $booking['agent'] = $agent;

        }

        // return($bookings);

        return view('backoffice.bookings.active',compact('bookings'));  
    }

    public function influencer_active_bookings(){

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','!=', null)->where('status','=','active')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();

            $agent = "Influencer (".$influencer->user->name.")";

            $booking['agent'] = $agent;

        }

        // return($bookings);

        return view('backoffice.bookings.active',compact('bookings'));  
    }

    public function revoke_booking($id){
        DB::table('bookings')->where('id','=',$id)->delete();
        return back()->with('success','Booking revoked.');
    }

    public function complete_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','complete')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            $payment = \App\Payments::where('booking_id','=',$booking->id)->latest()->first();

            \Log::info('Payment Array =>'.json_encode($payment));

            if($payment !=null){
                $booking['date_completed'] = $payment->date_paid;
            }else{
                $booking['date_completed'] = "NULL";
            }

            if($booking->agent_code !== null){
                $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
               
                if(isset($agent->user)){
                    $agent = $agent->user->name.' (Agent)';
                }elseif(isset($influencer->user)){
                    $agent = $influencer->user->name.' (Influencer)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }


            }elseif($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }
            }elseif($booking->influencer_code !== null){
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
                if($influencer == null){
                    $agent = "Lipa Mos Mos (Admin)";
                   }else {
                      if(isset($influencer->user)){
                        $agent = $influencer->user->name.' (Influencer)';
                      }
                   }
            }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
               $agent = "Lipa Mos Mos (Admin)";
            }


            $booking['agent'] = $agent;

        }

        // return($bookings);

        return view('backoffice.bookings.complete',compact('bookings'));  
    }

    public function influencer_complete_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','!=', null)->where('status','=','complete')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            $payment = \App\Payments::where('booking_id','=',$booking->id)->latest()->first();

            \Log::info('Payment Array =>'.json_encode($payment));

            if($payment !=null){
                $booking['date_completed'] = $payment->date_paid;
            }else{
                $booking['date_completed'] = "NULL";
            }

            $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();

            $agent = "Influencer (".$influencer->user->name.")";


            $booking['agent'] = $agent;

        }

        // return($bookings);

        return view('backoffice.bookings.complete',compact('bookings'));  
    }


    public function delivered_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','agent-delivered')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){

            if($booking->agent_code !== null){
                $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
               
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
               
                if(isset($agent->user)){
                    $agent = $agent->user->name.' (Agent)';
                }elseif(isset($influencer->user)){
                    $agent = $influencer->user->name.' (Influencer)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }


            }elseif($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }
            }elseif($booking->influencer_code !== null){
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
                if($influencer == null){
                    $agent = "Lipa Mos Mos (Admin)";
                   }else {
                      if(isset($influencer->user)){
                        $agent = $influencer->user->name.' (Influencer)';
                      }
                   }
            }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
               $agent = "Lipa Mos Mos (Admin)";
            }


            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.delivered',compact('bookings'));  
    }

    public function influencer_delivered_bookings (){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','!=', null)->where('status','=','agent-delivered')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){

            $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
            $agent = "Influencer (".$influencer->user->name.")";
            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.delivered',compact('bookings'));  
    }

    public function influencer_pending_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','!=', null)->where('status','=','pending')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){

            $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
            $agent = "Influencer (".$influencer->user->name.")";
            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.pending',compact('bookings'));  
    }

    public function influencer_unserviced_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','!=', null)->where('status','=','unserviced')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){

            $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
            $agent = "Influencer (".$influencer->user->name.")";
            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.unserviced',compact('bookings'));  
    }


    public function confirmed_deliveries(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','delivered')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){

            if($booking->agent_code !== null){
                $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
               
                if(isset($agent->user)){
                    $agent = $agent->user->name.' (Agent)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }

            }elseif($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }
            }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
               $agent = "Lipa Mos Mos (Admin)";
            }


            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.confirmed_deliveries',compact('bookings'));  
    }

    public function approve_delivery($id){
      \App\Bookings::where('id','=',$id)->update(['status'=>"delivered"]);

      return redirect('/admin/delivered_bookings')->with('success','Delivery Validated');

    }

    public function overdue_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','overdue')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->agent_code !== null){
                $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
               
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
               
                if(isset($agent->user)){
                    $agent = $agent->user->name.' (Agent)';
                }elseif(isset($influencer->user)){
                    $agent = $influencer->user->name.' (Influencer)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }


            }elseif($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }
            }elseif($booking->influencer_code !== null){
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
                if($influencer == null){
                    $agent = "Lipa Mos Mos (Admin)";
                   }else {
                      if(isset($influencer->user)){
                        $agent = $influencer->user->name.' (Influencer)';
                      }
                   }
            }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
               $agent = "Lipa Mos Mos (Admin)";
            }


            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.overdue',compact('bookings'));  
    }

    public function influencer_overdue_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','!=', null)->where('status','=','overdue')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
            $agent = "Influencer (".$influencer->user->name.")";

            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.overdue',compact('bookings'));  
    }

    public function influencer_revoked_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('influencer_code','!=', null)->where('status','=','revoked')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
            $agent = "Influencer (".$influencer->user->name.")";

            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.revoked',compact('bookings'));  
    }

    public function revoked_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','revoked')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->agent_code !== null){
                $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
               
                if(isset($agent->user)){
                    $agent = $agent->user->name.' (Agent)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }

            }elseif($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }
            }elseif($booking->influencer_code !== null){
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
                if($influencer == null){
                    $agent = "Lipa Mos Mos (Admin)";
                   }else {
                      if(isset($influencer->user)){
                        $agent = $influencer->user->name.' (Influencer)';
                      }
                   }
            }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
               $agent = "Lipa Mos Mos (Admin)";
            }


            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.revoked',compact('bookings'));  
    }

    public function transfer_order(){

        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }

        // $bookings = [];

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

        $booking = \App\Bookings::where('product_id','=',$id)->first();

        $balance = $newProduct->product_price - $booking->amount_paid;

        \App\Bookings::where('id','=',$booking->id)->update([
                        "product_id"=>$newProduct->id,
                        "balance"=>$balance,
                        "total_cost"=>$newProduct->product_price
                        ]);

        return back()->with('success', "Product exchanged successfully to ".$newProduct->product_name.". New Balance is KES ".number_format($balance,2).".");

        

    }

    public function unserviced_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','unserviced')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->agent_code !== null){
                $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
               
                if(isset($agent->user)){
                    $agent = $agent->user->name.' (Agent)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }

            }elseif($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }
            }elseif($booking->influencer_code !== null){
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
                if($influencer == null){
                    $agent = "Lipa Mos Mos (Admin)";
                   }else {
                      if(isset($influencer->user)){
                        $agent = $influencer->user->name.' (Influencer)';
                      }
                   }
            }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
               $agent = "Lipa Mos Mos (Admin)";
            }


            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.unserviced',compact('bookings'));  
    }

    public function pending_bookings(){
        $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','pending')->orderBy('id', 'DESC')->get();

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->agent_code !== null){
                $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
                Log::info("AGENT =>".print_r($agent,1));
                if($agent == null){
                 $agent = "Lipa Mos Mos (Admin)";
                }else {
                    $agent = $agent->user->name.' (Agent)';
                }
            }elseif($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
                if($vendor == null){
                    $agent = "Lipa Mos Mos (Admin)";
                   }else {
                       $agent = $vendor->user->name.' (Vendor)';
                   }
            }elseif($booking->influencer_code !== null){
                $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
                if($influencer == null){
                    $agent = "Lipa Mos Mos (Admin)";
                   }else {
                      if(isset($influencer->user)){
                        $agent = $influencer->user->name.' (Influencer)';
                      }
                   }
            }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
               $agent = "Lipa Mos Mos (Admin)";
            }

            $booking['agent'] = $agent;

        }

        return view('backoffice.bookings.pending',compact('bookings'));  
    }

    public function payments(){
        // $payments =  DB::table('payments')->get();

        $payments = \App\Payments::with('customer','mpesapayment','customer.user','product')->orderBy('id', 'DESC')->get();

        // return($payments);

        return view('backoffice.payments.index',compact('payments'));
    }

    public function payments_callbacks(){

        $payments = \App\PaymentLog::orderBy('id', 'DESC')->get();

        return view('backoffice.payments.logs',compact('payments'));

    }

    public function customers(){
        $customers  = DB::table('customers')
                        ->select('customers.*','customers.id AS customer_id','users.*')
                        ->join('users', 'customers.user_id', '=', 'users.id')
                        ->orderBy('customers.id', 'DESC')
                        ->get();
        foreach($customers as $customer){
            
            $bookingsCount = \App\Bookings::where('customer_id',$customer->customer_id)->where('status','!=','revoked')->count();

            $customer->bookingsCount = $bookingsCount;

        }

        return view('backoffice.customers.index',compact('customers'));
    }

    public function delete_customer($id){

        \App\Bookings::where('customer_id',$id)->where('status','revoked')->delete();

        $customer  = DB::table('customers')
                        ->where('id',$id)
                        ->first();

        \App\User::where('id',$customer->user_id)->delete();


        $customers  = DB::table('customers')
                        ->where('id',$id)
                        ->delete();

        return back()->with('success', 'Customer as been deleted!');

    }

    public function agents(){
        $agents =  \App\Agents::with('user')->get();
        // return($agents);
        return view('backoffice.agents.index',compact('agents'));
    }

    public function influencers(){
        $influencers =  \App\Influencer::with('user')->get();
        // return($agents);
        return view('backoffice.influencers.index',compact('influencers'));
    }

    public function vendors(){
        $vendors =  \App\Vendor::with('user')->orderBy('id', 'DESC')->get();
        return view('backoffice.vendors.index',compact('vendors'));
    }

    public function add_influencer(){
        return view('backoffice.influencers.add');  
    }

    public function add_agent(){
        return view('backoffice.agents.add');
    }

    public function agent_save(Request $request){

        $valid_phone = preg_match("/^(?:\+?254|0)?(7\d{8})/", $request->phone,$p_matches);
        //Valid email
        $valid_email = preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/", $request->email, $e_matches);
        //preg_match() returns 1 if the pattern matches given subject, 0 if it does not, or FALSE if an error occurred. 
        if ($valid_phone != 1 ) {

            return back()->withInput()->with('error', 'Please enter a valid  Phone Number!');
            

        }elseif($valid_email != 1){
            return back()->withInput()->with('error', 'Please enter a valid  email address!');
        }

        $valid_phone = preg_match("/^(?:\+?254|0)?(7\d{8})/", $request->phone, $p_matches);

        $valid_phone = $valid_phone != 1 ? $request->get('phone') : '254' . $p_matches[1];

        $user = $request->isMethod('put') ? \App\User::findOrFail($request->user_id) : new \App\User;
        $existingUser = \App\User::where('email',  $request->input('email'))->first();
        if($existingUser)
        {
            return back()->withInput()->with('error', 'Email is taken!');
        }
        $existingUser = \App\Agents::where('phone','=',$valid_phone)->first();
        if($existingUser)
        {
            return back()->withInput()->with('error', 'Contact is taken!');
        }
        $user = new \App\User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->role ='agent';
        $user->password = Hash::make($request->input('phone'));
        $user->save();

        $user_id = DB::getPdo()->lastInsertId();

        $agent = new \App\Agents();
        $agent->user_id = $user_id; 
        $agent->agent_code ='AG'.$user_id; 
        $agent->phone  = '254'.ltrim($request->input('phone'), '0');
        $agent->location  = $request->input('location');
        $agent->city_id  = $request->input('city_id');
        $agent->country  = $request->input('country');
        $agent->business_name  = $request->input('business_name');
        $agent->save();

        return redirect('admin/agents')->with('success','Agent Added');

    }

    public function influencer_save(Request $request){

        list($msisdn, $network) = $this->get_msisdn_network($request->phone);
        
        $valid_phone = $msisdn;
        //Valid email
        $valid_email = preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/", $request->email, $e_matches);
        //preg_match() returns 1 if the pattern matches given subject, 0 if it does not, or FALSE if an error occurred. 
        if (!$msisdn) {
        
            return back()->withInput()->with('error', 'Please enter a valid  Phone Number!');
            
        
        }elseif($valid_email != 1){
            return back()->withInput()->with('error', 'Please enter a valid  email address!');
        }
        
        $existingUser = \App\User::where('email',  $request->input('email'))->first();
        if($existingUser)
        {
            return back()->withInput()->with('error', 'Email is taken!');
        }
        $existingUser = \App\Influencer::where('phone','=',$valid_phone)->first();
        if($existingUser)
        {
            return back()->withInput()->with('error', 'Contact is taken!');
        }
        $user = new \App\User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->role ='influencer';
        $user->password = Hash::make($request->input('phone'));
        $user->save();
        
        $user_id = DB::getPdo()->lastInsertId();
        
        $influencer= new \App\Influencer();
        $influencer->user_id = $user_id; 
        $influencer->code ='INF'.$user_id; 
        $influencer->phone  = $msisdn;
        $influencer->commission = $request->commission;
        $influencer->store_name  = $request->input('store_name');
        $influencer->save();

        $influencer_id = DB::getPdo()->lastInsertId();

        $influencer_t = new \App\InfluencerCommissionTotal();
        $influencer_t->influencer_id = $influencer_id;
        $influencer_t->total_commission = '0.00';
        $influencer_t->commission_paid = '0.00';
        $influencer_t->pending_payment = '0.00';
        $influencer_t->save();

        return redirect('admin/influencers')->with('success','Influencer Added');
        
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
        
        return redirect('/admin/products')->with('success','Product Deleted');

    }

    public function image_delete($id){

        $image = \App\Gallery::where('id','=',$id)->first();
        $image->delete();

        return back()->with('success','Image Deleted.');

    }

    public function approve_vendor($id){

        \App\Vendor::where('id','=',$id)->update(['status'=>'approved']);

        return redirect('admin/vendors')->with('success','Vendor Activated');

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function commissions()
    {

      $commissions =   \App\Commission::with('booking','product','vendor','vendor.user','agent','agent.user')->get();

    //   return response()->json($commissions);

      return view('backoffice.commissions.index',compact('commissions'));
        
    }

    public function influencer_commissions(){
      $commissions =   \App\InfluencerCommission::with('booking','product','influencer','influencer.user')->get();
      \Log::info('Influncer Commissions => '.print_r($commissions,1));
      return view('backoffice.commissions.influencers',compact('commissions'));
    }

    public function influencer_pay(Request $request){

        $logData = $request->except('_token');
        
        \App\InfluencerPaymentlog::create($logData);

        $influencer_t = \App\InfluencerCommissionTotal::where('influencer_id',$request->influencer_id)->first();

        $data = [];
        $data['commission_paid'] = $influencer_t->commission_paid + $request->amount_paid;
        $data['pending_payment'] = $influencer_t->pending_payment -  $request->amount_paid;
        \App\InfluencerCommissionTotal::where('influencer_id',$request->influencer_id)->update($data);

        return redirect()->back()->with('success','Payment Recorded');
    }

    public function vendor_delete_account($id){

        $vendor = \App\Vendor::where('id','=',$id)->first();

         $products = \App\Products::where('vendor_id','=',$vendor->id)->get();

         $bookings = \App\Bookings::where('vendor_code','=',$vendor->vendor_code)->get();

         foreach($products as $product){
            \App\Products::where('id','=',$product->id)->update(['vendor_id'=>NULL,'updated_at'=>now()]);
         }

         foreach($bookings as $booking){
            \App\Bookings::where('id','=',$booking->id)->update(['agent_code'=>NULL,'updated_at'=>now()]);
         }

         \App\User::where('id',$vendor->user_id)->delete();

         $vendor->delete();

         return back()->with('success','Vendor deleted');

    }

    public function influencer_delete_account($id){

        $influencer = \App\Influencer::where('id','=',$id)->first();
        
         $products = \App\Products::where('influencer_id','=',$influencer->id)->get();
        
         $bookings = \App\Bookings::where('influencer_code','=',$influencer->influencer_code)->get();
        
         foreach($products as $product){
            \App\Products::where('id','=',$product->id)->update(['influencer_id'=>NULL,'updated_at'=>now()]);
         }
        
         foreach($bookings as $booking){
            \App\Bookings::where('id','=',$booking->id)->update(['agent_code'=>NULL,'updated_at'=>now()]);
         }
        
         \App\User::where('id',$influencer->user_id)->delete();
        
         $influencer->delete();
        
         return back()->with('success','influencer deleted');
        
    }

    public function agent_delete_account($id){

        $agent = \App\Agents::where('id','=',$id)->first();
        
         $products = \App\Products::where('agent_id','=',$agent->id)->get();
        
         $bookings = \App\Bookings::where('agent_code','=',$agent->agent_code)->get();
        
         foreach($products as $product){
            \App\Products::where('id','=',$product->id)->update(['agent_id'=>NULL,'updated_at'=>now()]);
         }
        
         foreach($bookings as $booking){
            \App\Bookings::where('id','=',$booking->id)->update(['agent_code'=>NULL,'updated_at'=>now()]);
         }
        
         \App\User::where('id',$agent->user_id)->delete();
        
         $agent->delete();
        
         return back()->with('success','agent deleted');
        
    }

    public function sms_log(){

        $logs = DB::table('s_m_s_logs')->orderBy('id','DESC')->get();

        return view('backoffice.sms.index',compact('logs'));

    }

    public function send_sms(){

      return view('backoffice.sms.send');
        
    }

    public function send_sms_save(Request $request){
        
      $recipients = $request->receiver;
      $message = $request->message;

      SendSMSController::sendMessage($recipients,$message,$type = 'composed_message');

      return back()->with('success','Message has been sent!');
          
    }

    public function update_product_bookings(){
        
        $bookings = \App\Bookings::with('product')->where('agent_code',NULL)->orWhere('vendor_code',NULL)->get();

        foreach($bookings as $booking){

            $product = \App\Products::find( $booking->product_id);

            if($product !=null){
                if($product->agent_id !=null){

                    $agent = \App\Agents::where('id','=',$product->agent_id)->first();
            
                    $agent_code = $agent->agent_code;
    
                    \App\Bookings::where('id',$booking->id)->update(['agent_code'=>$agent_code]);
            
                }elseif($product->vendor_id !=null){
        
                    $vendor = \App\Vendor::where('id','=',$product->vendor_id)->first();
        
                    $vendor_code = $vendor->vendor_code;
    
                    \App\Bookings::where('id',$booking->id)->update(['vendor_code'=>$vendor_code]);
        
                }elseif($product->influencer_id !=null){
    
                    $influencer = \App\Influencer::where('id','=',$product->influencer_id)->first();

                    $influencer_code = $influencer->code;
    
                    \App\Bookings::where('id',$booking->id)->update(['influencer_code'=>$influencer_code]);
                    
                    }
        
            }

            $now = now();

            $startTimeStamp = strtotime($booking->created_at);
            $endTimeStamp = strtotime($now);

            $timeDiff = abs($endTimeStamp - $startTimeStamp);

            $numberDays = $timeDiff/86400;  // 86400 seconds in one day

            // and you might want to convert to integer
            $numberDays = intval($numberDays);

            $latestPayment = \App\Payments::where('booking_id',$booking->id)->latest()->first();

            $latestPaymentTime = strtotime($booking->created_at);

            $now = strtotime($now);

            $timeDiff = abs($now - $latestPaymentTime);

            $numberDaysP = $timeDiff/86400;

            if($numberDays>= 90 && $booking->status != "complete"){
                Log::info('More than or equal to 90 days');
                \App\Bookings::where('id','=',$booking->id)->update(['status'=>'overdue']);

            }elseif($latestPayment !=null && $numberDaysP>= 30 && $numberDaysP < 90 && $booking->status != "complete"){
                Log::info('More than or equal to 90 days');
                \App\Bookings::where('id','=',$booking->id)->update(['status'=>'unserviced']);
            }else{
                Log::info('Less than 90 days');
            }

            // if($booking->influencer_code !== null && $booking->status == 'complete'){
            //     \Log::info('Complete booking =>'.print_r($booking,1));
            //     $influencer = \App\Influencer::where('code','=',$booking->influencer_code)->first();
            //     if($influencer == null){
                    
            //        }else {
            //         $influencer_commission = ($product->product_price - $product->buying_price) * ($influencer->commission/100);

            //         DB::table('influencer_commissions')->insert([
            //             'product_id' => $product->id,
            //             'booking_id' => $booking->id,
            //             'influencer_id' =>  $influencer->id,
            //             'commission' =>$influencer_commission,
            //             'created_at'=>now(),
            //             'updated_at'=>now(),
            //             ]);

            //         $influencer_t = \App\InfluencerCommissionTotal::where('influencer_id',$influencer->id)->first();

            //         $data = [];
            //         $data['total_commission'] = $influencer_t->total_commission + $influencer_commission;
            //         $data['pending_payment'] = $influencer_t->pending_payment + $influencer_commission;
            //         \App\InfluencerCommissionTotal::where('influencer_id',$influencer->id)->update($data);
            //        }
            // }

        }

        $message = "Success";

        return $message;
    }

}
