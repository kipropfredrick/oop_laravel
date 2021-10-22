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
use App\Http\Controllers\SendSMSController;
use App\Mail\SendBookingMail;
use App\Mail\SendPaymentMailToAdmin;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOrderTransferedMail;
use \App\Bookings;
use App\Http\Controllers\pushNotification;
use DataTables;
use App\topups;
use App\Http\Controllers\TopupsController;
use App\Http\Controllers\MpesaPaymentController;


use App\User;
use App\Customers;
use App\Http\Controllers\autApi;
use App\Http\Controllers\paybills;
use App\Http\Controllers\AES;
use App\Vendor;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;




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


     private function upload_image($image,$folder){


        if(!Storage::disk('public')->exists('thumbnail')){
            Storage::disk('public')->makeDirectory('thumbnail');
        }

        if(!Storage::disk('public')->exists($folder)){
            Storage::disk('public')->makeDirectory($folder);
        }

        $time = time();

        if ($files = $image) {
            $fileNameToStore = Image::make($files);
            $originalPath = 'storage/'.$folder.'/';
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

        return $image;

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

 
//        $credentials = [
//       'email'    => 'test3@example.com',
//     'password' => 'foobar3',
//     'name'=>"test brian" 
// ];
// // Register a new user
//  $res=Sentinel::registerAndActivate($credentials);

//$res=Sentinel::authenticate($credentials);

// return $res;
        
//         $credentials = [
//     'email'    => 'admin@mosmos.co.ke',
//     'password' => '11111111',
// ];

// Sentinel::activate($credentials);
// $res=Sentinel::authenticate($credentials);
// $user = Sentinel::findById(1);

// $user=Sentinel::login($user);

// return $res;
// // $user = Sentinel::check();
// $user = Sentinel::findById($user->id);

// $role = Sentinel::findRoleByName('Admin');
// if (Sentinel::inRole('Admin')) {
//     # code...
//    $role->users()->detach($user);
// }
// 

// $role->users()->attach($user);


// if ($user->hasAccess([ 'repayments.view']))
// {
//   return "OK"; // Execute this code if the user has permission
// }
// else
// {
//     return "No";// Execute this code if the permission check failed
// }



        $totalBookingAmount = \App\Bookings::sum('total_cost');
        $activeBookingAmount = \App\Bookings::where('status','=','active')->sum('total_cost');
        $overdueBookingAmount = \App\Bookings::where('status','=','overdue')->sum('total_cost');
        $completeBookingAmount = \App\Bookings::where('status','=','complete')->sum('total_cost');
        $pendingBookingAmount = \App\Bookings::where('status','=','pending')->sum('total_cost');

        $customersCount = \App\Customers::count();

        //7 days ago - last week.
$lastWeek = date("Y-m-d", strtotime("-7 days"));

$date = new \DateTime($lastWeek);

$arrayDays=[];
$payments=[];
$ucus=[];
$airtimepayments=[];
$utilitypayments=[];
$uniqueairtimecustomers=[];
$uniquebillcustomers=[];
for ($i=1; $i <=7 ; $i++) { 
    $minus=-7+$i;
$lastWeek = date("Y-m-d", strtotime($minus." days"));

 $daypayment=\App\PaymentLog::select('payment_logs.*',DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d") as TransTime_f'))->whereDate(DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d")'),"=",$lastWeek)->where("payment_logs.status","=","valid")->sum('TransAmount');

 $dayairtime = topups::select('amount',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$lastWeek)->whereIn("type",['airtime'])->whereStatus('valid')->sum('amount');

  $dayutility = topups::select('amount',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$lastWeek)->whereIn("type",['Bills(GOTV)','Bills(kplc_postpaid)','Bills(kplc_prepaid)'])->sum('amount');

  // $uniquecustomers=\App\Payments::select('customer_id',DB::raw('Date(created_at) as date_paid'))->whereDate('date_paid',"=",$lastWeek)->distinct('customer_id')->count();

    $validpaymentreferences=\App\PaymentLog::select('payment_logs.*',DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d") as TransTime_f'))->whereDate(DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d")'),"=",$lastWeek)->where("payment_logs.status","=","valid")->pluck('TransID')->toArray();


  $validmpesa=\App\Mpesapayments::whereIn("transac_code",$validpaymentreferences)->pluck('payment_id')->toArray();


  $uniquecustomers=\App\Payments::select('customer_id',DB::raw('Date(created_at) as date_paid'))->whereIn('id',$validmpesa)->distinct('customer_id')->count(); 


  $uc=\App\topups::select('sender','type',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$lastWeek)->whereIn("type",['airtime'])->whereStatus('valid')->distinct('sender')->count();
  $ub=\App\topups::select('sender','type',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$lastWeek)->whereIn("type",['Bills(GOTV)','Bills(kplc_postpaid)','Bills(kplc_prepaid)'])->distinct('sender')->count();




array_push($payments, $daypayment);
$date = new \DateTime($lastWeek);
$day=$date->format("D");
array_push($arrayDays, $day);
array_push($ucus, $uniquecustomers);
array_push($airtimepayments, $dayairtime);
array_push($utilitypayments, $dayutility);
array_push($uniqueairtimecustomers, $uc);
array_push($uniquebillcustomers, $ub);


}



        $days=json_encode($arrayDays);
        $bookings=json_encode($payments);
        $ucustom=json_encode($ucus);
        $airtime=json_encode($airtimepayments);
        $utility=json_encode($utilitypayments);
        $airtimecustomers=json_encode($uniqueairtimecustomers);
        $billcustomers=json_encode($uniquebillcustomers);
        $obj=new TopupsController();
        $utiliybalance=$obj->getBalance();



      return view('backoffice.index',compact('totalBookingAmount','activeBookingAmount','pendingBookingAmount','overdueBookingAmount','completeBookingAmount','customersCount','days','bookings','ucustom','airtime','utility','airtimecustomers','billcustomers','utiliybalance'));

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

     public function vendor_product_delete($id){

        $products = \App\Products::where('id','=',$id)->update(['status'=>'deleted']);

        return back()->with('success','Product Dejected');
     }

     public function vendor_products(){

        $products = \App\Products::with('category','vendor','vendor.user')->where('vendor_id','!=', null)->orderBy('id', 'DESC')->get();

        return view('backoffice.products.vendor',compact('products'));

     }

     public function vendor_pending_products(Request $request){
       //  $status = "Pending";
       // $products = \App\Products::with('category','vendor','vendor.user')->where('vendor_id','!=', null)->where('status','=',"pending")->orderBy('id', 'DESC')->get();
       // return view('backoffice.products.vendor',compact('products','status'));

$products=[];

          $status = "Pending";

        if($request->ajax()){

        $products =\App\Products::with('category','vendor','vendor.user')->where('vendor_id','!=', null)->where('status','=',"pending")->orderBy('id', 'DESC');

 return DataTables::of($products)->make(true);


          }
  
        return view('backoffice.products.vendor',compact('products','status'));
     }

     public function vendor_approved_products(Request $request){



              $status = "Approved";
              $products=[];

        if($request->ajax()){

        $products = \App\Products::with('category','vendor','vendor.user')->where('vendor_id','!=', null)->where('status','=',"approved")->orderBy('id', 'DESC');

 return DataTables::of($products)->make(true);


          }
  
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

      public function vendor_rejected_products(Request $request){




              $status = "Rejected";
              $products=[];

        if($request->ajax()){

        $products = \App\Products::with('category','vendor','vendor.user')->where('vendor_id','!=', null)->where('status','=',"rejected")->orderBy('id', 'DESC');

 return DataTables::of($products)->make(true);


          }
        // $status = "Rejected";
        // $products = \App\Products::with('category','vendor','vendor.user')->where('vendor_id','!=', null)->where('status','=',"rejected")->orderBy('id', 'DESC')->get();
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

       public function edit_vendor($id){
         $vendor = \App\Vendor::with('user.customer','city')->where('id','=',$id)->first();


         return view('backoffice.vendors.edit-vendor',compact('vendor'));

     }

     public function view_influencer($id){
        $influencer = \App\Influencer::with('user','commission_totals')->where('id','=',$id)->first();

        $products = \App\Products::where('influencer_id','=',$id)->get();

        return view('backoffice.influencers.view',compact('influencer','products'));

    }

     public function vendor_product_view($id){

        $product = \App\Products::with('category','subcategory','third_level_category')->where('id','=',$id)->first();

        if($product->weight != 0){
            $weight_array = preg_split('#(?<=\d)(?=[a-z])#i', $product->weight);
        }else{
            $weight_array = (['0','g']);
        }

        $product['weight'] = $weight_array;

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

         $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

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

         $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

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

             $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

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

         $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

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
// $arr=[];
//         $commissions=json_decode($vendor->commission_rate_subcategories);
// foreach ($categories as $key => $category) {
//     # code...

// foreach ($commissions as $key1 => $value1) {
//   $cat=\App\Models\SubCategories::whereSub_category_id($value1->id)->first();
//   if ($cat!=null) {
//       # code...
//     $category_id=$cat->category_id;

//     if ($category->id==$category_id) {
//         # code...
// array_push($arr, $category);
//     }

//   }


// }
// }
// $categories=$arr;
  
// return 0;

        $subcategories = DB::table('sub_categories')->orderBy('id', 'DESC')->get();

        $third_level_categories = DB::table('third_level_categories')->orderBy('id', 'DESC')->get();

        return view('backoffice.products.add',compact('categories','subcategories','third_level_categories'));
    }

    function fetch_sub_categories(Request $request)
    {

        $category_id = $request->get('category_id');
         $vendor=\App\Vendor::with('user')->whereUser_id($request->user_id)->first();
    Log::info($vendor->commssionrate_enabled);
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
$commissions=json_decode($vendor->commission_rate_subcategories);
        foreach($subcategories as $subcategory){
   
if ($vendor->commssionrate_enabled==1) {
    # code...
    
 
$haskey=false;
foreach ($commissions as $key1 => $value1) {
    if ($value1->id==$subcategory->id) {
        # code...
        Log::info("enabled yes");
 array_push($arr,$subcategory);

break;
    }
    # code...
}



}
else{
     array_push($arr,$subcategory);
}


            // array_push($arr,$subcategory);
        }

        $subcategories = $arr;

        return $subcategories;
    }

    public function get_third_categories(Request $request){

        $subcategory_id = $request->subcategory_id;


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

         $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

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
                'image_path' => $image,
                'created_at' =>now(),
                'updated_at' =>now()
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

        $categories = DB::table('categories')->get();

        $subcategories = DB::table('sub_categories')->get();

        return view('backoffice.products.edit',compact('product','categories','subcategories'));

    }

    public function add_vendor(){


        return view('backoffice.vendors.add');

    }
    public function update_vendor(Request $request,$id){
  
        if ($request->type=='g_commissionrate') {
            # code...
                $details=Array("commission_rate"=>$request->commission_rate?$request->commission_rate:0,"commission_cap"=>$request->commission_cap?$request->commission_cap:0,"fixed_mobile_money"=>$request->fixed_mobile_money?$request->fixed_mobile_money:0,"fixed_bank"=>$request->fixed_bank?$request->fixed_bank:0);
                \App\Vendor::whereId($id)->update($details);

                return back()->with('success','data updated successfully');
        }


        if ($request->type=='g_sub_rate') {
              $vendor=\App\Vendor::whereId($id)->first();
        
           
            # code...
                $commission_rate_subcategories=$vendor->commission_rate_subcategories;
//return $commission_rate_subcategories;
                 $array=json_decode($commission_rate_subcategories,true);
        
                 $i=0;
foreach ($array as $key => $value) {
    # code...

    if ($value['id']==$request->subcategory) {
        # code...
        unset($array[$key]);


    }

}
//return $array;

    
            $details=Array("commission_rate"=>$request->commission_rate,"commission_cap"=>$request->commission_cap,"fixed_bank"=>0,"fixed_mobile_money"=>0,"id"=>$request->subcategory);
       
            array_push($array, $details);

 
                      $en_commission_rate_subcategories=json_encode($array);
                      // return $en_commission_rate_subcategories;
            \App\Vendor::whereId($id)->update(Array("commission_rate_subcategories"=>$en_commission_rate_subcategories));
          return back()->with('success','data updated successfully');
        }


        if ($request->type=='g_sub_fixed') {
              $vendor=\App\Vendor::whereId($id)->first();
        
          
            # code...
                $fixed_cost_subcategories=$vendor->fixed_cost_subcategories;

                 $array=json_decode($fixed_cost_subcategories,true);
        
                 $i=0;
foreach ($array as $key => $value) {
    # code...

    if ($value['id']==$request->subcategory) {
        # code...
        unset($array[$key]);


    }

}


    
            $details=Array("commission_rate"=>0,"commission_cap"=>0,"fixed_bank"=>$request->fixed_bank,"fixed_mobile_money"=>$request->fixed_mobile_money,"id"=>$request->subcategory);
       
            array_push($array, $details);
//return $array;
 
                      $en_fixed_cost_subcategories=json_encode($array);
                      // return $en_commission_rate_subcategories;
            \App\Vendor::whereId($id)->update(Array("fixed_cost_subcategories"=>$en_fixed_cost_subcategories));
          return back()->with('success','data updated successfully');
        }


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

    $slug =  str_replace(' ', '-', $request->business_name);

    $slug =  str_replace('/','-',$slug);

    $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

    $vendor = new \App\Vendor();
    $vendor->user_id = $user_id;
    $vendor->business_name = $request->business_name;
    $vendor->slug = $slug;
    $vendor->status = "approved";
    if (isset($request->add_product)) {
        # code...
        $vendor->add_product=1;
    }
    else{
        $vendor->add_product=0;
    }
    $vendor->phone  = '254'.ltrim($request->input('phone'), '0');
    $vendor->location  = $request->input('location');
    $vendor->city_id  = $request->input('city_id');
 
    $vendor->commssionrate_enabled= $request->input('commissionrate_enabled');
    $vendor->category= $request->input('category');
    $vendor->commission_rate_subcategories='[]';
    $vendor->fixed_cost_subcategories='[]';
    

    // if ($request->input('commissionrate_enabled')==1) {
    //     # code...
    //     $vendor->commission_rate  = $request->input('commission_rate');
    // $vendor->commission_cap  = $request->input('commission_cap');
    // }
    // else{
    //   $vendor->fixed_mobile_money= $request->input('fixed_mobile_money');
    // $vendor->fixed_bank= $request->input('fixed_bank');
    // }

    
    $vendor->country  = $request->input('country');

    $vendor->save();
    $id = DB::getPdo()->lastInsertId();
\App\Vendor::where("user_id",$user_id)->where("phone",'254'.ltrim($request->input('phone'), '0'))->update(["vendor_code"=>"VD".$id]);
    return redirect('/admin/vendors')->with('success','Vendor Saved');

    }

       public function update_vendors(Request $request, $id){

    // if(\App\User::where('email',$request->email)->exists()){
    //     return back()->with('error','Email Exists');
    // }elseif(\App\Vendor::where('phone','254'.ltrim($request->input('phone'), '0'))->exists()){
    //     return back()->with('error','Phone Exists');
    // }

    $user_details=Array("email"=> $request->input('email'),"name"=> $request->input('name') );
    // $user->email = $request->input('email');
    // $user->name = $request->input('name');
    // $user->role ='vendor';
    // $user->email_verified_at = now();
    // $user->password = Hash::make($request->input('password'));

        $vendor=Vendor::whereId($id);
       $user=User::whereId($vendor->first()->user_id)->update($user_details);


   

    $slug =  str_replace(' ', '-', $request->business_name);

    $slug =  str_replace('/','-',$slug);

    $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

    $vendor_details=Array("business_name"=>$request->business_name,"slug"=>$slug);
   // $vendor->user_id = $user_id;
    // $vendor->business_name = $request->business_name;
    // $vendor->slug = $slug;
    //$vendor->status = "approved";
    if (isset($request->add_product)) {
        # code...
        $vendor_details['add_product']=1;
    }
    else{
        $vendor_details['add_product']=0;
    }
    $vendor_details['phone']  = $request->input('phone');
    $vendor_details['location']  = $request->input('location');
    $vendor_details['city_id']  = $request->input('city_id');
 
    $vendor_details['commssionrate_enabled']= $request->input('commissionrate_enabled');
    $vendor_details['category']= $request->input('category');
    // $vendor->commission_rate_subcategories='[]';
    // $vendor->fixed_cost_subcategories='[]';
    

    // if ($request->input('commissionrate_enabled')==1) {
    //     # code...
    //     $vendor->commission_rate  = $request->input('commission_rate');
    // $vendor->commission_cap  = $request->input('commission_cap');
    // }
    // else{
    //   $vendor->fixed_mobile_money= $request->input('fixed_mobile_money');
    // $vendor->fixed_bank= $request->input('fixed_bank');
    // }

    
    $vendor_details['country']  = $request->input('country');

    $vendor->update($vendor_details);
  //  $id = DB::getPdo()->lastInsertId();
// \App\Vendor::where("user_id",$user_id)->where("phone",'254'.ltrim($request->input('phone'), '0'))->update(["vendor_code"=>"VD".$id]);
    return redirect('/admin/vendors')->with('success','Vendor Updated');

    }

    public function updatevendorslugs(){

        $vendors = \App\Vendor::all();

        foreach($vendors as $vendor){
            
            $slug =  str_replace(' ', '-', $vendor->business_name);

            $slug =  str_replace('/','-',$slug);

            $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

            \App\Vendor::where('id',$vendor->id)->update(['slug'=>$slug]);

        }

        return "Success";

    }

    public function view_vendor_product($id)
    {
        $product = \App\Products::with('category','subcategory','brand','third_level_category')->where('id','=',$id)->first();

        if($product->weight != 0){
            $weight_array = preg_split('#(?<=\d)(?=[a-z])#i', $product->weight);
        }else{
            $weight_array = (['0','g']);
        }

        $product['weight'] = $weight_array;

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

         $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

        $data['slug'] = $slug;

        DB::table('sub_categories')->insert($data);

        return back()->with('success','Subcategory added');

    }

    public function save_tsubcategory(Request $request){

        $data = $request->except('_token');

        $subcategory = \App\SubCategories::find($request->subcategory_id);

        $slug =  str_replace(' ', '-',$subcategory->slug.'-'.$request->name);

        $slug =  str_replace('/','-',$slug);

         $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

        $data['slug'] = $slug;

        $data['created_at'] = now();

        $data['updated_at'] = now();

        DB::table('third_level_categories')->insert($data);

        return back()->with('success','Category added');
    }


    public function update_subcategory(Request $request,$id){

        $data = $request->except('_token');

        $slug =  str_replace(' ', '-', $request->subcategory_name);

        $slug =  str_replace('/','-',$slug);

         $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

        $data['slug'] = $slug;

        DB::table('third_level_categories')->where('id','=',$id)->update($data);

        return back()->with('success','Category updated');

    }

    public function update_tsubcategory(Request $request,$id){

        $data = $request->except('_token');

        $subcategory = \App\SubCategories::find($request->subcategory_id);

        $slug =  str_replace(' ', '-',$subcategory->slug.'-'.$request->name);

        $slug =  str_replace('/','-',$slug);

         $slug = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $slug);

        $data['slug'] = $slug;

        DB::table('third_level_categories')->where('id','=',$id)->update($data);

        return back()->with('success','Subcategory updated');

    }

    public function update_product(Request $request,$id){

        $data = $request->except('_token','image_paths','product_image');

        $weight = $data['weight'].$data['unit'];

        $data['weight'] = $weight;

        unset($data['unit']);

        $product_image = $request->file('product_image');

        $image_paths = $request->file('image_paths');

        $time = now();

        if(!empty($product_image)){

         $data['product_image'] = $this->upload_image($image=$product_image,$folder="images");

        }

        

        if(!empty($image_paths)){

            foreach($request->file('image_paths') as $image_path){

                DB::table('galleries')->insert( [
                    'product_id' => $id,
                    'image_path' => $this->upload_image($image=$image_path,$folder="gallery/images"),
                    'created_at' =>now(),
                    'updated_at' =>now()
                ]);
            }

        }


        DB::table('products')->where('id','=',$id)->update($data);

        return redirect('/admin/products')->with('success','Product updated.');
    }

    public function active_bookings(Request $request){


        // $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff')->where('status','=','active')->where(DB::raw('DATEDIFF( DATE_ADD(created_at,INTERVAL 91 DAY), DATE(NOW()))'),">",0)->orderBy('id', 'DESC')->get();

        // foreach($bookings as $booking){
        //     $progress = round(($booking->amount_paid/$booking->total_cost)*100);
        //     $booking['progress'] = $progress;

        //     if($booking->vendor_code !== null){
        //         $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();

        //         if(isset($vendor->user)){
        //             $agent = $vendor->user->name.' (Vendor)';
        //         }else{
        //             $agent = "Lipa Mos Mos (Admin)";
        //         }

        //     }else{
        //        $agent = "Lipa Mos Mos (Admin)";
        //     }
        //     $booking['agent'] = $agent;

        // }

        // return view('backoffice.bookings.active',compact('bookings'));
$bookings=[];

 // $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff','vendor.user')->where('status','=','active')->where(DB::raw('DATEDIFF( DATE_ADD(created_at,INTERVAL 91 DAY), DATE(NOW()))'),">",0)->orderBy('id', 'DESC')->get();
 // return $bookings;
                if($request->ajax()){

              $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff','vendor.user')->where('bookings.status','=','active')->where(DB::raw('DATEDIFF( DATE_ADD(bookings.created_at,INTERVAL 91 DAY), DATE(NOW()))'),">",0);

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();

                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }

            }else{
               $agent = "Lipa Mos Mos (Admin)";
            }
            $booking['agent'] = $agent;

            //specify role
$myrole="";
               if(auth()->user()->role !== 'influencer'){

          $myrole=ucfirst($booking->customer->user->name);

                                }
                                    if(auth()->user()->role !== 'vendor'){
            $myrole=ucfirst($booking->agent);
                                   }

                                   $booking['myrole']=$myrole;


                                   //item cost

        $booking['item_cost']="Ksh ".number_format($booking->item_cost ?$booking->item_cost:$booking->product->product_price);

        }


            return DataTables::of($bookings)->make(true);

        }

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
        DB::table('bookings')->where('id','=',$id)->update(["status"=>"revoked"]);
        $result=DB::table('bookings')->where('id','=',$id)->first();
        $customers=DB::table('customers')->where('id','=',$result->customer_id)->first();

$customerbookings=DB::table('bookings')->where('customer_id','=',$result->customer_id)->where("status","=","active")->first();

if ($customerbookings!=null) {
    # code...

$amount_paid=DB::table('bookings')->find($customerbookings->id)->amount_paid;
$balance=DB::table('bookings')->find($customerbookings->id)->balance;

$newamount_paid=intval($amount_paid)+ intval(DB::table('bookings')->where('id','=',$id)->first()->amount_paid);
$newbalance=intval(DB::table('bookings')->find($customerbookings->id)->balance) -intval(DB::table('bookings')->where('id','=',$id)->first()->amount_paid);
DB::table('bookings')->whereId($customerbookings->id)->update(["balance"=>$newbalance,"amount_paid"=>$newamount_paid]);



}
else{
    $rbalance=intval(DB::table('bookings')->where('id','=',$id)->first()->amount_paid) * 0.7;
$balance=intval(DB::table("users")->whereId($customers->user_id)->first()->balance) +intval($rbalance);
     DB::table("users")->whereId($customers->user_id)->update(["balance"=>$balance]);
}





        return back()->with('success','Booking revoked.');
    }
    public function storepicking_booking($id){
 $result=DB::table('bookings')->where('id','=',$id)->first();

 if ($result==null) {
     # code...
    return Back()->with("error","booking reference failed");
 }
 $excessamount=(intval($result->amount_paid)-intval($result->item_cost));

     DB::table('bookings')->where('id','=',$id)->update(["status"=>"complete","balance"=>$excessamount*-1]);
$customers=DB::table('customers')->where('id','=',$result->customer_id)->first();
$user=\App\User::whereId($customers->user_id)->first();
$balance=intval($user->balance);
\App\User::whereId($customers->user_id)->update(["balance"=>$balance+$excessamount]);
$product=\App\Products::whereId($result->product_id)->first();
$message = "Congratulations, You have completed Payment for ".$product->product_name.", You will be contacted to finalise your delivery.";

                SendSMSController::sendMessage($customers->phone,$message,$type="booking_completed_notification");

    $obj = new pushNotification();
    $data=Array("name"=>"complete","value"=>"View Orders");
    $obj->exceuteSendNotification($user->token,"You have completed payment for ".$product->product_name,"Congratulations",$data);
        

        return back()->with('success','Item marked as complete.');



    }
      public function remove_booking($id){
        DB::table('bookings')->where('id','=',$id)->delete();

        return back()->with('success','Booking removed.');
    }

    public function complete_bookings(Request $request){
        // $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','complete')->orderBy('updated_at', 'DESC')->get();

        // foreach($bookings as $booking){
        //     $progress = round(($booking->amount_paid/$booking->total_cost)*100);
        //     $booking['progress'] = $progress;

        //     $payment = \App\Payments::where('booking_id','=',$booking->id)->latest()->first();

        //     \Log::info('Payment Array =>'.json_encode($payment));

        //     if($payment !=null){
        //         $booking['date_completed'] = $payment->date_paid;
        //     }else{
        //         $booking['date_completed'] = "NULL";
        //     }

        //     if($booking->agent_code !== null){
        //         $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
        //         $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();

        //         if(isset($agent->user)){
        //             $agent = $agent->user->name.' (Agent)';
        //         }elseif(isset($influencer->user)){
        //             $agent = $influencer->user->name.' (Influencer)';
        //         }else{
        //             $agent = "Lipa Mos Mos (Admin)";
        //         }


        //     }elseif($booking->vendor_code !== null){
        //         $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
        //         if(isset($vendor->user)){
        //             $agent = $vendor->user->name.' (Vendor)';
        //         }else{
        //             $agent = "Lipa Mos Mos (Admin)";
        //         }
        //     }elseif($booking->influencer_code !== null){
        //         $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
        //         if($influencer == null){
        //             $agent = "Lipa Mos Mos (Admin)";
        //            }else {
        //               if(isset($influencer->user)){
        //                 $agent = $influencer->user->name.' (Influencer)';
        //               }
        //            }
        //     }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
        //        $agent = "Lipa Mos Mos (Admin)";
        //     }


        //     $booking['agent'] = $agent;

        // }

        $bookings=[];

 // $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff','vendor.user')->where('status','=','active')->where(DB::raw('DATEDIFF( DATE_ADD(created_at,INTERVAL 91 DAY), DATE(NOW()))'),">",0)->orderBy('id', 'DESC')->get();
 // return $bookings;
                if($request->ajax()){

              $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff','vendor.user')->withCount('payments')->where('bookings.status','=','complete')->orderBy('bookings.updated_at', 'DESC');

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();

                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }

            }else{
               $agent = "Lipa Mos Mos (Admin)";
            }
            $booking['agent'] = $agent;

            //specify role
$myrole="";
               if(auth()->user()->role !== 'influencer'){

          $myrole=ucfirst($booking->customer->user->name);

                                }
                                    if(auth()->user()->role !== 'vendor'){
            $myrole=ucfirst($booking->agent);
                                   }

                                   $booking['myrole']=$myrole;


                                   //item cost

        $booking['item_cost']="Ksh ".number_format($booking->item_cost ?$booking->item_cost:$booking->product->product_price);

        }


            return DataTables::of($bookings)->make(true);

        }




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

    public function overdue_bookings(Request $request){

       
        // $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','overdue')->orderBy('id', 'DESC')->get();

        // foreach($bookings as $booking){
        //     $progress = round(($booking->amount_paid/$booking->total_cost)*100);
        //     $booking['progress'] = $progress;

        //     if($booking->agent_code !== null){
        //         $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();

        //         $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();

        //         if(isset($agent->user)){
        //             $agent = $agent->user->name.' (Agent)';
        //         }elseif(isset($influencer->user)){
        //             $agent = $influencer->user->name.' (Influencer)';
        //         }else{
        //             $agent = "Lipa Mos Mos (Admin)";
        //         }


        //     }elseif($booking->vendor_code !== null){
        //         $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
        //         if(isset($vendor->user)){
        //             $agent = $vendor->user->name.' (Vendor)';
        //         }else{
        //             $agent = "Lipa Mos Mos (Admin)";
        //         }
        //     }elseif($booking->influencer_code !== null){
        //         $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
        //         if($influencer == null){
        //             $agent = "Lipa Mos Mos (Admin)";
        //            }else {
        //               if(isset($influencer->user)){
        //                 $agent = $influencer->user->name.' (Influencer)';
        //               }
        //            }
        //     }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
        //        $agent = "Lipa Mos Mos (Admin)";
        //     }


        //     $booking['agent'] = $agent;

        // }

          $bookings=[];
             if($request->ajax()){

              $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff','vendor.user')->where('bookings.status','=','overdue');

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();

                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }

            }else{
               $agent = "Lipa Mos Mos (Admin)";
            }
            $booking['agent'] = $agent;

            //specify role
$myrole="";
               if(auth()->user()->role !== 'influencer'){

          $myrole=ucfirst($booking->customer->user->name);

                                }
                                    if(auth()->user()->role !== 'vendor'){
            $myrole=ucfirst($booking->agent);
                                   }

                                   $booking['myrole']=$myrole;


                                   //item cost

        $booking['item_cost']="Ksh ".number_format($booking->item_cost ?$booking->item_cost:$booking->product->product_price);

        }


            return DataTables::of($bookings)->make(true);

        }
        else{
            // $this->updateunservicedoverdue();
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

    public function revoked_bookings(Request $request){
        // $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','revoked')->orderBy('id', 'DESC')->get();

        // foreach($bookings as $booking){
        //     $progress = round(($booking->amount_paid/$booking->total_cost)*100);
        //     $booking['progress'] = $progress;

        //     if($booking->agent_code !== null){
        //         $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();

        //         if(isset($agent->user)){
        //             $agent = $agent->user->name.' (Agent)';
        //         }else{
        //             $agent = "Lipa Mos Mos (Admin)";
        //         }

        //     }elseif($booking->vendor_code !== null){
        //         $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
        //         if(isset($vendor->user)){
        //             $agent = $vendor->user->name.' (Vendor)';
        //         }else{
        //             $agent = "Lipa Mos Mos (Admin)";
        //         }
        //     }elseif($booking->influencer_code !== null){
        //         $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
        //         if($influencer == null){
        //             $agent = "Lipa Mos Mos (Admin)";
        //            }else {
        //               if(isset($influencer->user)){
        //                 $agent = $influencer->user->name.' (Influencer)';
        //               }
        //            }
        //     }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
        //        $agent = "Lipa Mos Mos (Admin)";
        //     }


        //     $booking['agent'] = $agent;

        // }
        $bookings=[];

           if($request->ajax()){

              $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff','vendor.user')->where('bookings.status','=','revoked');

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();

                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }

            }else{
               $agent = "Lipa Mos Mos (Admin)";
            }
            $booking['agent'] = $agent;

            //specify role
$myrole="";
               if(auth()->user()->role !== 'influencer'){

          $myrole=ucfirst($booking->customer->user->name);

                                }
                                    if(auth()->user()->role !== 'vendor'){
            $myrole=ucfirst($booking->agent);
                                   }

                                   $booking['myrole']=$myrole;


                                   //item cost

        $booking['item_cost']="Ksh ".number_format($booking->item_cost ?$booking->item_cost:$booking->product->product_price);

        }


            return DataTables::of($bookings)->make(true);

        }

        return view('backoffice.bookings.revoked',compact('bookings'));
    }

    public function transfer_order(Request $request){

        // $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')
        //                             ->where('status','!=','complete')
        //                             ->orderBy('id', 'DESC')->get();

        // foreach($bookings as $booking){
        //     $progress = round(($booking->amount_paid/$booking->total_cost)*100);
        //     $booking['progress'] = $progress;
        // }

         $bookings=[];

             if($request->ajax()){

              $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff','vendor.user')->where('bookings.status','!=','complete');

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();

                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }

            }else{
               $agent = "Lipa Mos Mos (Admin)";
            }
            $booking['agent'] = $agent;

            //specify role
$myrole="";
               if(auth()->user()->role !== 'influencer'){

          $myrole=ucfirst($booking->customer->user->name);

                                }
                                    if(auth()->user()->role !== 'vendor'){
            $myrole=ucfirst($booking->agent);
                                   }

                                   $booking['myrole']=$myrole;


                                   //item cost

        $booking['item_cost']="Ksh ".number_format($booking->item_cost ?$booking->item_cost:$booking->product->product_price);

        }


            return DataTables::of($bookings)->make(true);

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
           $vendor_code=\App\Vendor::whereId($newProduct->vendor_id)->first()->vendor_code;

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
                        "total_cost"=>$total_cost,
                        "vendor_code"=>$vendor_code
                        ]);
       }
       else{
 \App\Bookings::where('id','=',$booking->id)->update([
                        "product_id"=>$newProduct->id,
                        "balance"=>0,
                        "shipping_cost"=>$shipping_cost,
                        "item_cost"=>$newProduct->product_price,
                        'status'=>"complete",
                        "total_cost"=>$total_cost,
                        "vendor_code"=>$vendor_code
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
    function recordbillpayment(Request $request,$id){

$result=\App\BillpaymentLogs::where('id',$id)->first();
$mpesa=new MpesaPaymentController();

if ($result==null) {
    # code...
    return Back()->with("error","Transaction verification failed");
}

if ($result->status!="unverified" ) {
    # code...
    return Back()->with("error","Transaction already verified");
}
$bill_ref_no=$request->reference;
$transaction_amount=$result->TransAmount;
$msisdn=$result->MSISDN;
$transaction_id=$result->TransID;

$ismobiletopup="/254/i";
$mob='/^(0)\d{9}$/';
$mob1='/^(\+254)\d{9}$/';
$saf="/SAF/i";
$tel="/TEL/i";
$air="/AIR/i";

$ismobiletopuptrue = preg_match($ismobiletopup,$bill_ref_no);
      if ($ismobiletopuptrue || preg_match($mob,$bill_ref_no) || preg_match($saf,$bill_ref_no) || preg_match($tel,$bill_ref_no)|| preg_match($air,$bill_ref_no) ||  preg_match($mob1,$bill_ref_no)) {

 
            $log_id =$result->id;


        $productcode="";
        $recipient="";

  # code...i
        if ($ismobiletopuptrue || preg_match($mob,$bill_ref_no) || preg_match($mob1,$bill_ref_no)) {
            # code...


  list($msisdn, $network) = $this->get_msisdn_network($bill_ref_no);

        if (!$msisdn){
             Log::info("Invalid Phone Number");

                return Back()->with("error","Invalid phone number");
        }else{
            $mobilerec = "0".substr($msisdn, 3);
            
            $valid_phone=$msisdn;
         
        }




// $obz=new TopupsController();
// $response= json_decode($obz->phonelookup(substr($recipient,1, 3)));

if ($msisdn) {
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
   return Back()->with("error","Mobile Operator Not Supported");


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



$paybillobj = new paybills();
$array=Array("PhoneNumber"=>$mpesa->getphone($bill_ref_no),"Amount"=>$transaction_amount*100,"ProductCode"=>$productcode);

$res=$paybillobj->AirtimeTopUp($array);

  Log::info($res);
 $decdata=json_decode($res);
  Log::info("product code".$productcode);

if ($decdata==null) {
  # code...
     Log::info("returned null");
    //log the transaction into the database

  return Back()->with('error',"An error occured processing your request.");

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
  return Back()->with('success',"Airtime top-up successs.");


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

   return Back()->with('error',"Airtime TOPUP was not successful. Amount has been credited to mosmos account.");
    
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

  // $existingLog = \App\BillpaymentLogs::where('TransID',$transaction_id)->first();

  //           if($existingLog!=null){

  //               return "Duplicate Transaction";

  //           }

  //           \App\BillpaymentLogs::insert($paymentLog);

            $log_id = $result->id;




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
    return Back()->with('error',"An error occured processing your request.");
  // return Array("data"=>Array("response"=>""),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
        Log::info("returned ok");
         \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
$ret=$mpesa->paymentSuccess($msisdn,$transaction_amount,$transaction_id,$biller_name);
   $token=json_decode(json_decode($decdata->VoucherDetails,true)[0])->Token;
   return Back()->with('success',"Transaction success: tokenno: ".$token);
// return Array("data"=>Array("response"=>"Transaction success: tokenno: ".$token),"error"=>false);
  # code...
}
else{
        Log::info("returned error");
      $mpesa->CustomTopUpAccount($msisdn,$transaction_amount,$log_id);
       return Back()->with('error',"An error occured processing your request.Amount credited to mosmos account.");
    // return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}



}
else if ($biller_name=="kplc_postpaid") {
  # code...
  $array=Array("MobileNumber"=>"0".substr($msisdn, 3),"CustomerName"=>"customer","CustAccNum"=>substr($bill_ref_no, 2),"Amount"=>$transaction_amount*100);
$res=$paybillobj->kplcpostpaid($array);

 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
      return Back()->with('success',"An error occured processing your request.");
  // return Array("data"=>Array("response"=>""),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
     \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
$ret=$mpesa->paymentSuccess($msisdn,$transaction_amount,$transaction_id,$biller_name);
  return Back()->with('success',"Transaction success");
// return Array("data"=>Array("response"=>"Post Paid success"),"error"=>false);
  # code...
}
else{
      $mpesa->CustomTopUpAccount($msisdn,$transaction_amount,$log_id);
      return Back()->with('error',"An error occured processing your request.Amount credited to mosmos account.");
    // return Array("data"=>Array("response"=>"An error occured processing your request.".$decdata->ResponseDescription),"error"=>true);
}



}
else{

    $array=Array("paymentType"=>$biller_name,"PhoneNumber"=>"0".substr($msisdn, 3),"AccountNumber"=>substr($bill_ref_no, 2),"AccountName"=>"customer","Amount"=>$transaction_amount*100);
  // $array=Array("MobileNumber"=>"0".substr($phone, 3),"CustomerName"=>"customer","CustAccNum"=>$account,"Amount"=>$amount*100);
$res=$paybillobj->otherpayments($array);

 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
    return Back()->with('error',"An error occured processing your request.");
  // return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
     \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
$ret=$mpesa->paymentSuccess($msisdn,$transaction_amount,$transaction_id,$biller_name);
  return Back()->with('success',"Transaction success");
//return Array("data"=>Array("response"=>"Payment Successs"),"error"=>false);
  # code...
}
else{

    $mpesa->CustomTopUpAccount($msisdn,$transaction_amount,$log_id);
    return Back()->with('error',"An error occured processing your request.Amount credited to mosmos account.");
    // return Array("data"=>Array("response"=>"An error occured processing your request.".$decdata->ResponseDescription),"error"=>true);
}

}


return Back()->with("success","Transaction success");


       } 

       return $result;
    }


     function recordCreditedbillpayment(Request $request,$id){

$result=\App\BillpaymentLogs::where('id',$id)->first();

$userid=\App\Customers::wherePhone($result->MSISDN)->first()->user_id;
$balance=\App\User::whereId($userid)->first()->balance;
$userobj=\App\User::whereId($userid);
$remainingbalance=$balance-$request->amount_paid;
if ($request->amount>$balance) {
       return Back()->with("error","You have insufficient funds to complete transaction");
    # code...
}
$mpesa=new MpesaPaymentController();

if ($result==null) {
    # code...
    return Back()->with("error","Transaction verification failed");
}

if ($result->status!="credited" ) {
    # code...
    return Back()->with("error","Transaction not in credited status");
}
$bill_ref_no=$request->reference;
$transaction_amount=$request->amount;
$msisdn=$result->MSISDN;
$transaction_id=$result->TransID;

$ismobiletopup="/254/i";
$mob='/^(0)\d{9}$/';
$mob1='/^(\+254)\d{9}$/';
$saf="/SAF/i";
$tel="/TEL/i";
$air="/AIR/i";

$ismobiletopuptrue = preg_match($ismobiletopup,$bill_ref_no);
      if ($ismobiletopuptrue || preg_match($mob,$bill_ref_no) || preg_match($saf,$bill_ref_no) || preg_match($tel,$bill_ref_no)|| preg_match($air,$bill_ref_no) ||  preg_match($mob1,$bill_ref_no)) {

 
            $log_id =$result->id;


        $productcode="";
        $recipient="";

  # code...i
        if ($ismobiletopuptrue || preg_match($mob,$bill_ref_no) || preg_match($mob1,$bill_ref_no)) {
            # code...


  list($msisdn, $network) = $this->get_msisdn_network($bill_ref_no);

        if (!$msisdn){
             Log::info("Invalid Phone Number");

                return Back()->with("error","Invalid phone number");
        }else{
            $mobilerec = "0".substr($msisdn, 3);
            
            $valid_phone=$msisdn;
         
        }




// $obz=new TopupsController();
// $response= json_decode($obz->phonelookup(substr($recipient,1, 3)));

if ($msisdn) {
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
   return Back()->with("error","Mobile Operator Not Supported");


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



$paybillobj = new paybills();
$array=Array("PhoneNumber"=>$mpesa->getphone($bill_ref_no),"Amount"=>$transaction_amount*100,"ProductCode"=>$productcode);

$res=$paybillobj->AirtimeTopUp($array);

  Log::info($res);
 $decdata=json_decode($res);
  Log::info("product code".$productcode);

if ($decdata==null) {
  # code...
     Log::info("returned null");
    //log the transaction into the database

  return Back()->with('error',"An error occured processing your request.");

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
  $userobj->update(["balance"=>$remainingbalance]);
 \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
\App\topups::create($credentials);
  $obj = new pushNotification();
    $data=Array("name"=>"home","value"=>"home");
    $obj->exceuteSendNotification(\App\User::whereId($userid)->first()->token,"Thank you for topping up KSh. ".$transaction_amount." airtime with us.","Transaction successful. ",$data);
  return Back()->with('success',"Airtime top-up successs.");


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
// $user->update(["balance"=>$balance]);
 // \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"credited"]);

        for($i=0;$i<1000000;$i++){
            $transid = 'TT'.rand(10000,99999)."M";
            $res=\App\topups::whereTransid($transid)->first();
            if ($res==null) {             # code...
break;  }
          
        }

// $credentials=Array("amount"=>$transaction_amount,"balance"=>$balance,"transid"=>$transid,"sender"=>$obj?$obj->id:$msisdn);
// \App\topups::create($credentials);

  $obj = new pushNotification();
    $data=Array("name"=>"home","value"=>"home");
    $obj->exceuteSendNotification($user->first()->token,"Your airtime purchase request was not successful. The amount has been credited back to your Lipa Mos Mos wallet.","Airtime purchase failed",$data);

}

   return Back()->with('error',"Airtime TOPUP was not successful. Amount has been credited to mosmos account.");
    
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

  // $existingLog = \App\BillpaymentLogs::where('TransID',$transaction_id)->first();

  //           if($existingLog!=null){

  //               return "Duplicate Transaction";

  //           }

  //           \App\BillpaymentLogs::insert($paymentLog);

            $log_id = $result->id;




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
    return Back()->with('error',"An error occured processing your request.");
  // return Array("data"=>Array("response"=>""),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
      $userobj->update(["balance"=>$remainingbalance]);
        Log::info("returned ok");
         \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
$ret=$mpesa->paymentSuccess($msisdn,$transaction_amount,$transaction_id,$biller_name);
   $token=json_decode(json_decode($decdata->VoucherDetails,true)[0])->Token;
   return Back()->with('success',"Transaction success: tokenno: ".$token);
// return Array("data"=>Array("response"=>"Transaction success: tokenno: ".$token),"error"=>false);
  # code...
}
else{
        Log::info("returned error");
     // $mpesa->CustomTopUpAccount($msisdn,$transaction_amount,$log_id);
       return Back()->with('error',"An error occured processing your request.Amount credited to mosmos account.");
    // return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}



}
else if ($biller_name=="kplc_postpaid") {
  # code...
  $array=Array("MobileNumber"=>"0".substr($msisdn, 3),"CustomerName"=>"customer","CustAccNum"=>substr($bill_ref_no, 2),"Amount"=>$transaction_amount*100);
$res=$paybillobj->kplcpostpaid($array);

 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
      return Back()->with('success',"An error occured processing your request.");
  // return Array("data"=>Array("response"=>""),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
      $userobj->update(["balance"=>$remainingbalance]);
     \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
$ret=$mpesa->paymentSuccess($msisdn,$transaction_amount,$transaction_id,$biller_name);
  return Back()->with('success',"Transaction success");
// return Array("data"=>Array("response"=>"Post Paid success"),"error"=>false);
  # code...
}
else{
      //$mpesa->CustomTopUpAccount($msisdn,$transaction_amount,$log_id);
      return Back()->with('error',"An error occured processing your request.Amount credited to mosmos account.");
    // return Array("data"=>Array("response"=>"An error occured processing your request.".$decdata->ResponseDescription),"error"=>true);
}



}
else{

    $array=Array("paymentType"=>$biller_name,"PhoneNumber"=>"0".substr($msisdn, 3),"AccountNumber"=>substr($bill_ref_no, 2),"AccountName"=>"customer","Amount"=>$transaction_amount*100);
  // $array=Array("MobileNumber"=>"0".substr($phone, 3),"CustomerName"=>"customer","CustAccNum"=>$account,"Amount"=>$amount*100);
$res=$paybillobj->otherpayments($array);

 $decdata=json_decode($res);

if ($decdata==null) {
  # code...
    return Back()->with('error',"An error occured processing your request.");
  // return Array("data"=>Array("response"=>"An error occured processing your request."),"error"=>true);
}

 if (($decdata->ResponseCode)=="000") {
    //return $array['TransID'];
      $userobj->update(["balance"=>$remainingbalance]);
     \App\BillpaymentLogs::whereId($log_id)->update(["status"=>"valid"]);
$ret=$mpesa->paymentSuccess($msisdn,$transaction_amount,$transaction_id,$biller_name);
  return Back()->with('success',"Transaction success");
//return Array("data"=>Array("response"=>"Payment Successs"),"error"=>false);
  # code...
}
else{

   // $mpesa->CustomTopUpAccount($msisdn,$transaction_amount,$log_id);
    return Back()->with('error',"An error occured processing your request.Amount credited to mosmos account.");
    // return Array("data"=>Array("response"=>"An error occured processing your request.".$decdata->ResponseDescription),"error"=>true);
}

}


return Back()->with("success","Transaction success");


       } 

       return $result;
    }

    public function record_payment(Request $request,$id){

        $type = $request->type;

        $bill_ref_no = $request->booking_reference;

        if($type == "travel"){

            $log = \App\PaymentLog::where('id',$id)->first();

            $booking = \DB::connection('mysql2')->table('bookings')->where('booking_reference',$bill_ref_no)->first();
            $sms_credit_payment = \DB::connection('mysql2')->table('travel_agents')->where('code',$bill_ref_no)->first();
            $invoice_payment = \DB::connection('mysql2')->table('invoices')->where('ref',$bill_ref_no)->first();

            if($booking == null && $sms_credit_payment == null && $invoice_payment == null){

                return back()->with('error', 'Booking/Bill With that Ref does not exist!');

            }else{

            \App\PaymentLog::where('id',$id)->update(['status'=>'verified']);
                
            \DB::connection('mysql2')->table('payment_logs')->insert([
                                                'TransactionType' => $log->TransactionType,
                                                'TransID'=>$log->TransID,
                                                'TransTime'=>$log->TransTime,
                                                'TransAmount'=>$log->TransAmount	,
                                                'BusinessShortCode'=>$log->BusinessShortCode,
                                                'BillRefNumber'=>$log->BillRefNumber,
                                                'InvoiceNumber'=>$log->InvoiceNumber,
                                                'OrgAccountBalance'=>$log->OrgAccountBalance,
                                                'ThirdPartyTransID'=>$log->ThirdPartyTransID,
                                                'MSISDN'=>$log->MSISDN,
                                                'FirstName'=>$log->FirstName,
                                                'MiddleName'=>$log->MiddleName,
                                                'LastName'=>$log->LastName,
                                                'status'=>'valid',
                                                'date_recorded'=>$log->date_recorded,
                                                'comment'=>$log->comment,
                                                'created_at'=>now(),
                                                'updated_at'=>now(),
                                            ]);
            
            $log_id = DB::connection('mysql2')->getPdo()->lastInsertId();

            $customer = \DB::connection('mysql2')->table('customers')->where('id',$booking->customer_id)->first();

            $message =  MpesaPaymentController::validateTravelPayments($bill_ref_no,$transaction_amount = $log->TransAmount,$msisdn = $customer->phone,$first_name = $log->FirstName,$middle_name = $log->MiddleName,$last_name = $log->LastName,$code = $log->TransID,$log_id);

            return back()->with('success', 'Payment Updated! ');

            }

        }

        $date_paid = Carbon::today()->toDateString();

        $booking = \App\Bookings::with('product','payments','payments.mpesapayment','customer','customer.user','county','location')->where('booking_reference','=',$bill_ref_no)->first();


        if($booking == null){
            return back()->with('error', 'Booking Does not exist!');
        }

       $payment_log = \App\PaymentLog::find($id);

       $existingLogCount = \App\PaymentLog::where('TransID',$payment_log->TransID)->count();

        if($existingLogCount>1){
            return back()->with('error', 'Duplicate Transaction!');
        }

       \App\PaymentLog::where('id',$id)->update(['BillRefNumber'=>$bill_ref_no]);

        if($booking->status == 'pending'){

           if($booking->vendor_code !== null){
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
        $payment->transaction_amount = $request->amount;
        $payment->booking_status = 'active';
        $payment->date_paid = now();
        $payment->save();

        $payment_id = DB::getPdo()->lastInsertId();

        $amount_paid = $booking->amount_paid + $request->amount;

        $balance = $booking->total_cost - $amount_paid;

        if($balance<1){

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


            $message = "Congratulations, You have completed Payment of ".$booking->product->product_name.$location.", You will be contacted for more information.";

            SendSMSController::sendMessage($recipients,$message,$type="booking_completed_notification");

            $product = \App\Products::with('subcategory')->where('id','=',$booking->product_id)->first();

            if($booking->vendor_code !== null){
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

            DB::table('bookings')
            ->where('booking_reference','=',$bill_ref_no)
            ->update(['balance'=>$balance,'amount_paid'=>$amount_paid,'status'=>'active']);
        }

        DB::table('payment_logs')->where('id',$id)->update(['status'=>'valid']);

        DB::table('mpesapayments')
            ->insert([
                      'payment_id'=>$payment_id,
                      'amount_paid'=>$request->amount,
                      'phone'=>$payment_log->MSISDN,
                      'transac_code'=>$payment_log->TransID,
                      'date_paid'=>$date_paid,
                      'created_at'=>now(),
                      'updated_at'=>now()
                      ]);

        $message = 'Success';

        $recipients = $recipients = $booking->customer->phone;

        $request->amount = number_format($request->amount,2);
        $balance =number_format($balance,2);

        $payment_count = \App\PaymentLog::where('BillRefNumber',$bill_ref_no)->count();


        if($payment_count<2){
                    $shipping_cost = $booking->shipping_cost;
                    //$message    ="Payment of KES. {$transaction_amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$code}. Balance KES. {$balance}. Incl delivery cost of KES .{$shipping_cost}.Download our app to easily track your payments - http://bit.ly/MosMosApp.";

                    $message="Payment of KSh.{$request->amount} for {$bill_ref_no} received. Txn. {$payment_log->TransID}. Bal is KSh.{$balance} incl delivery cost. Download our app to easily track your payments - http://bit.ly/MosMosApp";


        }else{

            $message    ="Payment of KES. {$request->amount} received for Booking Ref. {$bill_ref_no}, Payment reference {$payment_log->TransID}. Balance KES. {$balance}.Download our app to easily track your payments - http://bit.ly/MosMosApp." ;

        }


        SendSMSController::sendMessage($recipients,$message,$type="payment_notification");

        $data['receiver'] = $recipients;
        $data['type'] = 'payment_notification';
        $data['message'] = $message;
        $data['created_at'] = now();
        $data['updated_at'] = now();

        DB::table('s_m_s_logs')->insert($data);

        $details = [
            'customer'=> $booking->customer->user->name,
            'booking_reference'=>$booking->booking_reference,
            'amount_paid'=>$request->amount,
            'product'=>$booking->product->product_name,
            'mpesa_ref'=>$payment_log->TransID,
            'balance'=> $booking->balance

        ];

        Mail::to('order@mosmos.co.ke')->send(new SendPaymentMailToAdmin($details));


        $latestPayment = \App\Payments::with('mpesapayment')->where('booking_id',$booking->id)->latest()->first();

        $details  = [
            'customer_name'=>$booking->customer->user->name,
            'product_name'=>$booking->product->product_name,
            'booking_reference'=>$booking->booking_reference,
            'total_cost'=>number_format($booking->total_cost,2),
            'amount_paid'=>number_format($booking->amount_paid),
            'balance'=>$balance,
            'date_paid'=>$date_paid,
            'product_price'=>number_format($booking->product->product_price),
            'payments'=>$booking->payments,
            'latestPayment'=>$latestPayment
        ];

        Mail::to($booking->customer->user->email)->send(new SendPaymentEmail($details));

        return back()->with('success', 'Payment Updated');

    }




    public function unserviced_bookings(Request $request){

            

            // $bookings = \App\Bookings::with('customer','customer.user','product','county','location','zone','dropoff')->where('status','=','unserviced')->orderBy('id', 'DESC')->get();

            // foreach($bookings as $booking){
            //     $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            //     $booking['progress'] = $progress;

            //     if($booking->agent_code !== null){
            //         $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();

            //         if(isset($agent->user)){
            //             $agent = $agent->user->name.' (Agent)';
            //         }else{
            //             $agent = "Lipa Mos Mos (Admin)";
            //         }

            //     }elseif($booking->vendor_code !== null){
            //         $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
            //         if(isset($vendor->user)){
            //             $agent = $vendor->user->name.' (Vendor)';
            //         }else{
            //             $agent = "Lipa Mos Mos (Admin)";
            //         }
            //     }elseif($booking->influencer_code !== null){
            //         $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
            //         if($influencer == null){
            //             $agent = "Lipa Mos Mos (Admin)";
            //         }else {
            //             if(isset($influencer->user)){
            //                 $agent = $influencer->user->name.' (Influencer)';
            //             }
            //         }
            //     }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
            //     $agent = "Lipa Mos Mos (Admin)";
            //     }


            //     $booking['agent'] = $agent;

            // }

            $bookings=[];

           if($request->ajax()){

              $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff','vendor.user')->where('bookings.status','=','unserviced');

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();

                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }

            }else{
               $agent = "Lipa Mos Mos (Admin)";
            }
            $booking['agent'] = $agent;

            //specify role
$myrole="";
               if(auth()->user()->role !== 'influencer'){

          $myrole=ucfirst($booking->customer->user->name);

                                }
                                    if(auth()->user()->role !== 'vendor'){
            $myrole=ucfirst($booking->agent);
                                   }

                                   $booking['myrole']=$myrole;


                                   //item cost

        $booking['item_cost']="Ksh ".number_format($booking->item_cost ?$booking->item_cost:$booking->product->product_price);

        }


            return DataTables::of($bookings)->make(true);

        }
        else{
            //$this->updateunservicedoverdue();
        }

            return view('backoffice.bookings.unserviced',compact('bookings'));
    }

    public function pending_bookings(Request $request){
        // $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff','vendor.use')->where('status','=','pending')->orderBy('id', 'DESC')->get();

        // foreach($bookings as $booking){
        //     $progress = round(($booking->amount_paid/$booking->total_cost)*100);
        //     $booking['progress'] = $progress;

        //     if($booking->agent_code !== null){
        //         $agent = \App\Agents::with('user')->where('agent_code','=',$booking->agent_code)->first();
        //         Log::info("AGENT =>".print_r($agent,1));
        //         if($agent == null){
        //          $agent = "Lipa Mos Mos (Admin)";
        //         }else {
        //             $agent = $agent->user->name.' (Agent)';
        //         }
        //     }elseif($booking->vendor_code !== null){
        //         $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();
        //         if($vendor == null){
        //             $agent = "Lipa Mos Mos (Admin)";
        //            }else {
        //                $agent = $vendor->user->name.' (Vendor)';
        //            }
        //     }elseif($booking->influencer_code !== null){
        //         $influencer = \App\Influencer::with('user')->where('code','=',$booking->influencer_code)->first();
        //         if($influencer == null){
        //             $agent = "Lipa Mos Mos (Admin)";
        //            }else {
        //               if(isset($influencer->user)){
        //                 $agent = $influencer->user->name.' (Influencer)';
        //               }
        //            }
        //     }elseif ($booking->vendor_code == null && $booking->agent_code == null) {
        //        $agent = "Lipa Mos Mos (Admin)";
        //     }

        //     $booking['agent'] = $agent;

        // }
        $bookings=[];

             if($request->ajax()){

              $bookings = \App\Bookings::with('customer','customer.user','product:id,product_name,product_code','county','location','zone','dropoff','vendor.user')->where('bookings.status','=','pending');

        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;

            if($booking->vendor_code !== null){
                $vendor = \App\Vendor::with('user')->where('vendor_code','=',$booking->vendor_code)->first();

                if(isset($vendor->user)){
                    $agent = $vendor->user->name.' (Vendor)';
                }else{
                    $agent = "Lipa Mos Mos (Admin)";
                }

            }else{
               $agent = "Lipa Mos Mos (Admin)";
            }
            $booking['agent'] = $agent;

            //specify role
$myrole="";
               if(auth()->user()->role !== 'influencer'){

          $myrole=ucfirst($booking->customer->user->name);

                                }
                                    if(auth()->user()->role !== 'vendor'){
            $myrole=ucfirst($booking->agent);
                                   }

                                   $booking['myrole']=$myrole;


                                   //item cost

        $booking['item_cost']="Ksh ".number_format($booking->item_cost ?$booking->item_cost:$booking->product->product_price);

        }


            return DataTables::of($bookings)->make(true);

        }

         return view('backoffice.bookings.pending',compact('bookings'));
    }

    public function payments(Request $request){
        // $payments =  DB::table('payments')->get();
        $payments=[];
        $validpaymentreferences=[];
        $validmpesa=[];
if ($request->validmpesa!=null) {
    # code...$vali
    $validmpesa=json_decode($request->validmpesa, true);
}

             if($request->ajax()){ 


        $payments = \App\Payments::with('customer','mpesapayment','customer.user','product:id,product_name,product_code','booking')->whereIn("payments.id",$validmpesa)->orderBy('payments.id', 'DESC');
       
            

            return DataTables::of($payments)->make(true);

        }
        else{
            $validpaymentreferences=\App\PaymentLog::select('payment_logs.*')->where("payment_logs.status","=","valid")->pluck('TransID')->toArray();
  $validmpesa=json_encode(\App\Mpesapayments::whereIn("transac_code",$validpaymentreferences)->pluck('payment_id')->toArray());
        }



        return view('backoffice.payments.index',compact('payments','validmpesa'));
    }

    public function payments_callbacks(Request $request){
        $payments=[];
        
        if($request->ajax()){

            $payments = DB::table('payment_logs')->select('payment_logs.*',DB::raw('DATE_FORMAT(TransTime, "%M %d %Y %H:%I %S") as TransTime_f'))->orderBy('id','DESC');

            return DataTables::of($payments)->make(true);

        }

        return view('backoffice.payments.logs',compact('payments'));

    }

    function billpayments_callbacks(Request $request){
        $payments=[];
        
        if($request->ajax()){

            $payments = DB::table('billpayment_logs')->select('billpayment_logs.*',DB::raw('DATE_FORMAT(TransTime, "%M %d %Y %H:%I %S") as TransTime_f'))->orderBy('id','DESC');

            return DataTables::of($payments)->make(true);

        }

        return view('backoffice.payments.billlogs',compact('payments'));  
    }

    public function update_callback(Request $request){
        

            $refs = DB::table('bookings')->pluck('booking_reference')->toArray();

            $invalid_payment_ids = DB::table('payment_logs')->whereNotIn('BillRefNumber', $refs)->pluck('id')->toArray();

            DB::table('payment_logs')->whereIn('id',$invalid_payment_ids)->update(['status'=>'unverified']);

            return "Success!";


    }

    public function check_booking_exists($booking_reference){
       $booking =  \App\Bookings::where('booking_reference','=',$booking_reference)->first();
       if($booking){
           return "1";
       }else{
           return "0";
       }
    }

    public function customers(Request $request,$type){

        $title="";
        $customers=[];
        $users=$request->users?$request->users:"[]";

     
       
        

              if($request->ajax()){

$users=json_decode($users,true);


        if ($type=="inactive") {
        // $customers  = DB::table('customers')
        //                 ->select('customers.*','users.*')
        //                 ->join('users', 'customers.user_id', '=', 'users.id')->
        //                 whereNotIn("customers.id",$users)
        //                 ->orderBy('customers.id', 'DESC')->get();

  // $customers=\App\Customers::with('user')->whereNotIn("customers.id",$users)->join('customers', 'customers.id' , '=', 'bookings.customer_id'
  //   ->selectRaw("year(date) as year, COUNT(*) as count") ->orderBy('customers.id', 'DESC');
        # code...

   // $customers=\App\Customers::with('user')->join('bookings', 'bookings.customer_id' , '=', 'customers.id'
   //  )->select('bookings.status','customers.*', DB::raw('(SELECT COUNT(*) FROM bookings WHERE bookings.customer_id = customers.id) as total'),DB::raw('DATE_FORMAT(customers.created_at, "%b, %d, %Y") as date'))->whereNotIn("customers.id",$users) ->orderBy('customers.id', 'DESC')->get();
 // $customers=\App\Customers::with('user')->leftJoin('bookings', 'bookings.customer_id' , '=', 'customers.id'
 //    )->select(DB::raw('(SELECT "" ) as status'),'customers.*', DB::raw('(SELECT COUNT(*) FROM bookings WHERE bookings.customer_id = customers.id) as total'),DB::raw('DATE_FORMAT(customers.created_at, "%b, %d, %Y") as date'))->whereIntegerNotInRaw("customers.id",$users) ->orderBy('customers.id', 'DESC');

         $customers=\App\Customers::with('user')->leftJoin('bookings', 'bookings.customer_id' , '=', 'customers.id'
    )->select(DB::raw('(SELECT "" ) as status'),'customers.*', DB::raw('(SELECT COUNT(*) FROM bookings WHERE bookings.customer_id = customers.id) as total'),DB::raw('DATE_FORMAT(customers.created_at, "%b, %d, %Y") as date'))->whereNull('bookings.customer_id') ->orderBy('customers.id', 'DESC');

        }
        else{
        // $customers  = DB::table('customers')
        //                 ->select('customers.*','users.*')
        //                 ->join('users', 'customers.user_id', '=', 'users.id')->
        //                 whereIn("customers.id",$users)
        //                 ->orderBy('customers.id', 'DESC')->get();
               $customers=\App\Customers::with('user')->join('bookings', 'bookings.customer_id' , '=', 'customers.id'
    )->select('bookings.status','customers.*', DB::raw('(SELECT COUNT(*) FROM bookings WHERE bookings.customer_id = customers.id) as total'),DB::raw('DATE_FORMAT(customers.created_at, "%b, %d, %Y") as date'))->whereIntegerInRaw("customers.id",$users) ->orderBy('customers.id', 'DESC');
        }



            return DataTables::of($customers)->make(true);

        }
        else{


           if ($type=="active") {

        $users=\App\Bookings::where('status','=','complete')->orWhere('status','=','active')->pluck('customer_id')->toArray();
        $title="Active/ Complete Bookings Customers";
        # code...
        }
        else if ($type=='complete') {
        # code...
        $users=\App\Bookings::where('status','=','complete')->pluck('customer_id')->toArray();
        $title="Complete Bookings Customers";

        }
        else if ($type=='active-bookings') {
        # code...
        $users=\App\Bookings::where('status','=','active')->pluck('customer_id')->toArray();
        $title="Active Bookings Customers";

        }

        else if ($type=='pending-bookings') {
        # code...
        $users=\App\Bookings::where('status','=','pending')->pluck('customer_id')->toArray();

        $title="Pending Bookings Customers";
        }
        else if ($type=='revoked-bookings') {
        # code...
        $users=\App\Bookings::where('status','=','revoked')->pluck('customer_id')->toArray();
        $title="Revoked Bookings Customers";

        }
        else if ($type=='inactive') {
        # code...
        $users=\App\Bookings::pluck('customer_id')->toArray();
        $title="Inactive Customers Customers";

        }

         else if ($type=='overdue') {
        # code...
       $users=\App\Bookings::where('status','=','overdue')->pluck('customer_id')->toArray();
        $title="Overdue Bookings Customers";

        }
        else if ($type=='unserviced') {
        # code...
       $users=\App\Bookings::where('status','=','unserviced')->pluck('customer_id')->toArray();
        $title="Unserviced Bookings Customers";

        }
        $users=json_encode($users);

        }

        



        // foreach($customers as $customer){

        //     $bookingsCount = \App\Bookings::where('customer_id',$customer->customer_id)->where('status','!=','revoked')->count();

        //     $booking = \App\Bookings::where('customer_id',$customer->customer_id)->latest()->first();

        //     if($booking!=null){
        //         $customer->booking_status = $booking->status;
        //     }else{
        //         $customer->booking_status = "NO BOOKING";
        //     }

        //     $customer->bookingsCount = $bookingsCount;

        // }

        return view('backoffice.customers.index',compact('customers','title','users','type'));
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

        list($payment_log->MSISDN, $network) = $this->get_msisdn_network($request->phone);

        $valid_phone = $payment_log->MSISDN;
        //Valid email
        $valid_email = preg_match("/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}/", $request->email, $e_matches);
        //preg_match() returns 1 if the pattern matches given subject, 0 if it does not, or FALSE if an error occurred.
        if (!$payment_log->MSISDN) {

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
        $influencer->phone  = $payment_log->MSISDN;
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


      $commision =   \App\Commission::with('booking','product','vendor','vendor.user','agent','agent.user')->orderBy('commissions.id','DESC')->get();
 
$result=[];

      foreach ($commision as $key => $value) {
          # code...

        if ($value->vendor!=null) {
            # code...
            if ($value->vendor->commssionrate_enabled==1) {
                # code...  return $value;

                if ($value->vendor->category==0) {
                    # code...
$value->commission_rate=$comm['commission_rate'];
        $value->commission_cap=$comm['commission_cap'];

                            array_push($result, $value);
                }else{
                     $commission_rate_subcategories=$value->vendor->commission_rate_subcategories;
//return $commission_rate_subcategories;
                 $array=json_decode($commission_rate_subcategories,true);

                 $i=0;
                 $valchanged=false;
foreach ($array as $key1 => $comm) {
    # code...

    if ($comm['id']==$value->product->subcategory_id) {
        # code...

        $value->commission_rate=$comm['commission_rate'];
        $value->commission_cap=$comm['commission_cap'];

   

    }
 

}

if (!$valchanged) {
    # code...
    $value->commission_rate=0;
        $value->commission_cap=0;

}
array_push($result, $value);



                }



        
            }
        }
      }
$commissions=$result;


    //   return response()->json($commissions);

      return view('backoffice.commissions.index',compact('commissions'));

    }
    function fixedPayout(Request $request){

        $commision =   \App\Commission::with('booking','product','vendor','vendor.user','agent','agent.user')->orderBy('commissions.id','DESC')->get();
$result=[];

      foreach ($commision as $key => $value) {
          # code...
        if ($value->vendor!=null) {
            # code...

            if ($value->vendor->commssionrate_enabled==0) {
                # code...      

      if ($value->vendor->category==0) {
                    # code...
$value->commission_rate=$comm['commission_rate'];
        $value->commission_cap=$comm['commission_cap'];

                $countbanktransfers=\App\commission_records::whereBooking_id($value->booking->id)->whereTransaction_origin('bank')->count();
                  $countmobiletransfers=\App\commission_records::whereBooking_id($value->booking->id)->whereTransaction_origin('mobile')->count();
                    $totalbanktransfers=$value->vendor->fixed_bank*($countbanktransfers);
                  $totalmobiletransfers=$value->vendor->fixed_mobile_money*($countmobiletransfers);
                  $vendor_payout=$value->booking->item_cost-($totalmobiletransfers+$totalbanktransfers);
$value->vendor_payout=$vendor_payout;
$value->fixed_mobile_money=$value->vendor->fixed_mobile_money;
$value->fixed_bank=$value->vendor->fixed_bank;
$value->countbanktransfers=$countbanktransfers;
$value->countmobiletransfers=$countmobiletransfers;
$value->totalbanktransfers=$totalbanktransfers;
$value->totalmobiletransfers=$totalmobiletransfers;
$value->commission=$totalbanktransfers+$totalmobiletransfers;
                   array_push($result, $value);
                }else{
                   $countbanktransfers=\App\commission_records::whereBooking_id($value->booking->id)->whereTransaction_origin('bank')->count();
                  $countmobiletransfers=\App\commission_records::whereBooking_id($value->booking->id)->whereTransaction_origin('mobile')->count();
                     $commission_rate_subcategories=$value->vendor->fixed_cost_subcategories;
//return $commission_rate_subcategories;
                 $array=json_decode($commission_rate_subcategories,true);
    
                 $i=0;
                 $valchanged=false;
foreach ($array as $key1 => $comm) {
    # code...

    if ($comm['id']==$value->product->subcategory_id) {
        # code...

           $totalbanktransfers=intval($comm['fixed_bank'])*($countbanktransfers);
                  $totalmobiletransfers=intval($comm['fixed_mobile_money'])*($countmobiletransfers);
                  $vendor_payout=$value->booking->item_cost-($totalmobiletransfers+$totalbanktransfers);
$value->vendor_payout=$vendor_payout;
$value->countbanktransfers=$countbanktransfers;
$value->countmobiletransfers=$countmobiletransfers;
$value->totalbanktransfers=$totalbanktransfers;
$value->totalmobiletransfers=$totalmobiletransfers;
$value->fixed_mobile_money=$comm['fixed_mobile_money'];
$value->fixed_bank=$comm['fixed_bank'];
$value->commission=$totalbanktransfers+$totalmobiletransfers;

   $valchanged=true;

    }
 

}

if (!$valchanged) {
    # code...
  $value->vendor_payout=$value->booking->amount_paid;
$value->countbanktransfers=$countbanktransfers;
$value->countmobiletransfers=$countmobiletransfers;
$value->totalbanktransfers=0;
$value->totalmobiletransfers=0;
$value->fixed_mobile_money=0;
$value->fixed_bank=0;
$value->commission=$totalbanktransfers+$totalmobiletransfers;

}


array_push($result, $value);
                }








            }
        }
      }
$commissions=$result;

    //   return response()->json($commissions);

      return view('backoffice.commissions.fixedpayout',compact('commissions'));

    }

    function showCommissions(Request $request,$id){
$bank=\App\commission_records::whereTransaction_origin('bank')->whereBooking_id($id)->get();
$mobile=\App\commission_records::whereTransaction_origin('mobile')->whereBooking_id($id)->get();

return view('backoffice.commissions.show',compact('bank','mobile'));
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

    public function sms_log(Request $request){

        if($request->ajax()){

            $logs = DB::table('s_m_s_logs')->select('s_m_s_logs.*',DB::raw('DATE_FORMAT(created_at, "%M %d %Y %H:%I %S") as created_at_'))->orderBy('id','DESC');

            return DataTables::of($logs)->make(true);

        }


        return view('backoffice.sms.index');

    }

    public function send_sms(){

      return view('backoffice.sms.send');

    }

    public function send_sms_save(Request $request){

      $recipients = $request->receiver;
      $type = $request->type;
      $group = $request->group;
      $message = $request->message;

      if($type === "single" && empty($recipients)){
        return back()->withInput()->with('error','Recipient field is required');
      }

      if($type === "group" && empty($group)){
        return back()->withInput()->with('error','Group field is required');
      }


      if($type === "single"){
        SendSMSController::sendMessage($recipients,$message,$type = 'composed_message');
        return back()->with('success','Message has been sent!');
      }elseif($type === "group"){

        if($group === "active_customers"){

          $customers=\App\Bookings::where('status','=','complete')->orWhere('status','=','active')->pluck('customer_id')->toArray();

        }elseif($group === "cb_customers"){

            $customers=\App\Bookings::where('status','=','complete')->pluck('customer_id')->toArray();

        }elseif($group === "ab_customers"){

            $customers=\App\Bookings::where('status','=','active')->pluck('customer_id')->toArray();

        }elseif($group === "pb_customers"){

            $customers=\App\Bookings::where('status','=','pending')->pluck('customer_id')->toArray();

        }elseif($group === "rb_customers"){

            $customers=\App\Bookings::where('status','=','revoked')->pluck('customer_id')->toArray();

        }elseif($group === "inactive_customers"){

            $customers=\App\Bookings::pluck('customer_id')->toArray();

        }elseif($group === "ob_customers"){
            $customers=\App\Bookings::where('status','=','overdue')->pluck('customer_id')->toArray();
        }elseif($group === "ub_customers"){
            $customers=\App\Bookings::where('status','=','unserviced')->pluck('customer_id')->toArray();
        }

        if ($type=="inactive_customers") {
            $recipients  = DB::table('customers')
                            ->whereNotIn("customers.id",$customers)
                            ->orderBy('customers.id', 'DESC')
                            ->pluck('phone')->toArray();
            # code...
        }
        else{
        $recipients  = DB::table('customers')
                        ->whereIn("customers.id",$customers)
                        ->orderBy('customers.id', 'DESC')
                        ->pluck('phone')->toArray();
        }

        $recipients = implode(",",$recipients);

      }


       try {
           return back()->with('success','Messages Queued Successfully!');
        }finally{
            SendSMSController::sendMessage($recipients,$message,$type = $group.'_composed_message');
        }


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


    function scheduletasks(Request $request){
        //Log::info("executed successfully");

        // use cron jobs for linux/ubuntu to schedule task update

   $result=Bookings::whereStatus("pending")->latest()->get();
 $today =  Carbon::now();
 $devices1=[];

   for ($i=0; $i <count($result) ; $i++) {
       # code...

$createdDate = Carbon::parse($result[$i]->created_at);
$hours=$today->diffInHours($createdDate);

if (intval($hours)>48) {
    Bookings::whereId($result[$i]->id)->delete();
    # code...
}
if (intval($hours)==24 && $result[$i]->scheduled=="0") {
    # code...
   // Log::info("Notify");
    $customer=\App\Customers::whereId($result[$i]->customer_id)->first();
   $token=\App\User::whereId($customer->user_id)->first()->token;
    if ($token==null) {
        # code...

    }
    else{
    array_push($devices1, $token);
}
    Bookings::whereId($result[$i]->id)->update(["scheduled"=>"1"]);

}
//Log::info($hours."     " .$createdDate ."  " .$today);



   }

    $obj = new pushNotification();
  $data=Array("name"=>"makepayment","value"=>"Make Payment");
   $title="Please make your payment.";
$messages="Start paying for your order.";
    $obj->exceuteSendNotificationGroup($devices1,$messages,$title,$data); 



  




  



        // $result=DB::table('bookings')->where('id','=',$id)->first();
        // $customers=DB::table('customers')->where('id','=',$result->customer_id)->first();
return "hello";
    }

function scheduletask1(Request $request){
     $result=Bookings::whereStatus("active")->get();
     $devices1=[];
     $coun=0;
 $today =  Carbon::now();
   for ($i=0; $i <count($result) ; $i++) {
       # code...

$createdDate = Carbon::parse($result[$i]->notified_at);
$hours=$today->diffInHours($createdDate);

if (intval($hours)>36) {

    # code...
       $customer=\App\Customers::whereId($result[$i]->customer_id)->first();
   $toke =\App\User::whereId($customer->user_id)->first()->token;
    if ($token==null) {
        # code...

    }
    else{
   
   array_push($devices1, $token);
    $coun=$coun+1;
       if ($coun==990) {
           # code...
        break;
       }
}
    Bookings::whereId($result[$i]->id)->update(["notified_at"=>$today]);
}





//Log::info($hours."     " .$createdDate ."  " .$today);



   }

 $obj = new pushNotification();
    $data=Array("name"=>"paymentreminder","value"=>"Make Payment");
   $title="You can pay any amount.";
$messages="Keep up with your payments to have your order delivered faster.";
    $obj->exceuteSendNotificationGroup($devices1,$messages,$title,$data); 

}

function scheduletask2(Request $request){
 //discounts pap

   $customers=\App\Bookings::pluck('customer_id')->toArray();
    $cus=\App\Customers::whereIntegerNotInRaw("id",$customers)->pluck('user_id')->toArray();
$result = \App\User::with('customer:user_id,phone')->select("token","id")->whereNotNull('token')->whereIntegerInRaw("id",$cus)->get();

$devices=[];
$devices1=[];
$insertnotify=[];
$phones=[];
$coun=0;
 $today =  Carbon::now();
   
 

   for ($i=0; $i <count($result) ; $i++) {
       # code...
$checkifexists=DB::table("discountnotification")->wherePhone($result[$i]->customer->phone)->first();

if ($checkifexists==null) {
     array_push($devices1,$result[$i]->token);
//    $token=\App\User::whereId($result[$i]->user_id)->first()->token;
//     if ($token==null) {
//         # code...

//     }
//     else{
//     // $obj = new pushNotification();
//     // $data=Array("name"=>"discount", "value"=>"Get discount");
//     // $obj->exceuteSendNotification($token,"Order today with the app and get KSh.100 welcome discount on your first order.","Claim your KSh.100 gift 🤑🎁",$data);
// //         array_push($devices1, $token);


// } 
     $coun=$coun+1;
       if ($coun==990) {
           # code...
        break;
       }

        $array=Array("phone"=>$result[$i]->customer->phone,"created_at"=>Now(),"updated_at"=>Now(),"notified_at"=>Now());
        array_push($insertnotify, $array);


    #code...
}
else{
     // array_push($devices,$result[$i]->token);

    $createdDate = Carbon::parse($checkifexists->notified_at);
$hours=$today->diffInHours($createdDate);

if (intval($hours)>24) {

//     # code...
//         $token=\App\User::whereId($result[$i]->user_id)->first()->token;
//     if ($token==null) {
//         # code...

//     }
//     else{
//    //  $obj = new pushNotification();
//    //  $data=Array("name"=>"discount","value"=>"Get discount");
//    // $obj->exceuteSendNotification($token,"Order today with the app and get KSh.100 welcome discount on your first order.","Get KSh.100 welcome discount",$data);
//         array_push($devices, $token);
   
// }
    array_push($phones, $result[$i]->customer->phone);
    array_push($devices1,$result[$i]->token);
     $coun=$coun+1;
       if ($coun==990) {
           # code...
        break;
       }

}



}
     






//Log::info($hours."     " .$createdDate ."  " .$today);



   }

   

   DB::table("discountnotification")->insert($insertnotify);

    DB::table("discountnotification")->whereIn('phone',$phones)->update(["notified_at"=>Now()]);

  
 $obj = new pushNotification();
    $data=Array("name"=>"discount", "value"=>"Get discount");
   $title="Claim your KSh.1000 gift 🤑🎁";
$messages="Order today with the app and get up to KSh.1,000 welcome discount on your first order.";
    $obj->exceuteSendNotificationGroup($devices1,$messages,$title,$data); 
return 0;
  // $data=Array("name"=>"home","value"=>"home");
  //   //$messages = str_replace('{customerName}',$value->name, $message);
  //    $messages= "Order today with the app and get KSh.100 welcome discount on your first order.";
  //    $title="Get KSh.100 welcome discount";
  //   $obj->exceuteSendNotificationGroup($devices,$messages,$title,$data);  



}
function scheduletask3(Request $request){
      $result=\App\User::whereNotNull('token')->get();
 $today =  Carbon::now();
 $devices1=[];
 $ids=[];
 $coun=0;
   for ($i=0; $i <count($result) ; $i++) {
       # code...

$createdDate = Carbon::parse($result[$i]->notified_at);
$hours=$today->diffInHours($createdDate);

if (intval($hours)>48) {

    # code...
       //$customer=\App\Customers::whereId($result[$i]->customer_id)->first();
   $token=$result[$i]->token;
//     if ($token==null) {
//         # code...

//     }
//     else{
//     // $obj = new pushNotification();
//     // $data=Array("name"=>"home","value"=>"home");
//     // $obj->exceuteSendNotification($token,"","",$data);
 

// }
       array_push($devices1, $token);
       $coun=$coun+1;
       if ($coun==990) {
           # code...
        break;
       }

array_push($ids, $result[$i]->id);
    
}





//Log::info($hours."     " .$createdDate ."  " .$today);



   }


   \App\User::whereIntegerInRaw('id',$ids)->update(["notified_at"=>$today]);


 $obj = new pushNotification();


  $data=Array("name"=>"home","value"=>"home");
    //$messages = str_replace('{customerName}',$value->name, $message);
     $messages= "Buy airtime and pay utility bills for yourself or your loved ones bila stress with the Lipa Mos Mos app.";
     $title="Buy Airtime & Pay Bills Instantly.";
    return $obj->exceuteSendNotificationGroup($devices1,$messages,$title,$data);  
    return  0;
}



    function updateunservicedoverdue(Request $request){
            $result=Bookings::whereIn("status",["active","unserviced"])->get();
            for ($i=0; $i <count($result) ; $i++) {
                # code..
                $res=DB::table("payments")->whereBooking_id($result[$i]->id)->count();
            if ($res==1 || $res==0) {
                # code...
                \App\Bookings::where(DB::raw('DATEDIFF( DATE_ADD(created_at,INTERVAL 91 DAY), DATE(NOW()))'),"<",0)->whereId($result[$i]->id)->update(["status"=>"unserviced"]);
            }
            else{
                \App\Bookings::where(DB::raw('DATEDIFF( DATE_ADD(created_at,INTERVAL 91 DAY), DATE(NOW()))'),"<",0)->whereId($result[$i]->id)->update(["status"=>"overdue"]);

            }

        }
    }

    function monitorPayments(Request $request){
        $result=DB::table("monitorpay")->get()[0];
        return  view('backoffice.payments.monitoring',compact('result'));
    }

    function promotions(Request $request){

$users=\App\promotions::get();
foreach ($users as $key => $value) {
    # code...
    $value->customer=\App\Customers::with('user')->whereId($value->customers_id)->first();
}

return view("backoffice.promotions.data",compact('users'));

    }
    function topups(Request $request){
        $topups= topups::whereType('topup')->latest()->get();
foreach ($topups as $key => $value) {
    # code...
    $value->user=\App\User::whereId($value->sender)->first();
    $value->customer=\App\Customers::whereUser_id($value->sender)->first();
}
$title="Wallet Top-Ups";

return view("backoffice.topups.topups",compact('topups','title'));

    }
      function purchases(Request $request){
              $topups= topups::whereIn("type",['airtime'])->latest()->get();
foreach ($topups as $key => $value) {
    # code...
    $value->user=\App\User::whereId($value->sender)->first();
    $value->customer=\App\Customers::whereUser_id($value->sender)->first();
}
 $title="Airtime Purchases";

return view("backoffice.topups.topups",compact('topups','title'));
    }
    function utilities(Request $request){
              $topups= topups::whereIn("type",['Bills(GOTV)','Bills(kplc_postpaid)','Bills(kplc_prepaid)'])->latest()->get();
foreach ($topups as $key => $value) {
    # code...
    $value->user=\App\User::whereId($value->sender)->first();
    $value->customer=\App\Customers::whereUser_id($value->sender)->first();
}
 $title="Utility Purchases";

return view("backoffice.topups.utilities",compact('topups','title'));
    }


function agall(Request $request){
    $payments=[];

    $now = new \DateTime('now');
   $month = $now->format('m');
   $year = $now->format('Y');
   if (isset($request->month)) {
       # code...
$month=$request->month;
$year=$request->year;
   }


$days=cal_days_in_month(CAL_GREGORIAN, intval($month), intval($year));

$firstday=$year."-".$month."-"."00";
for ($i=0; $i <$days ; $i++) { 
    $j=$i+1;
  $currentday=date('Y-m-d', strtotime($firstday. ' + '.$j.' days'));

  if (Now()<$currentday) {
      # code...
    break;
  }


 $daypayment=\App\PaymentLog::select('payment_logs.*',DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d") as TransTime_f'))->whereDate(DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d")'),"=",$currentday)->where("payment_logs.status","=","valid")->sum('TransAmount');



 $dayairtime = topups::select('amount',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$currentday)->whereIn("type",['airtime'])->whereStatus('valid')->sum('amount');


  $dayutility = topups::select('amount',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$currentday)->whereNotIn("type",['airtime','topup'])->sum('amount');

  $validpaymentreferences=\App\PaymentLog::select('payment_logs.*',DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d") as TransTime_f'))->whereDate(DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d")'),"=",$currentday)->where("payment_logs.status","=","valid")->pluck('TransID')->toArray();
  $validmpesa=\App\Mpesapayments::whereIn("transac_code",$validpaymentreferences)->pluck('payment_id')->toArray();


  $uniquecustomers=\App\Payments::select('customer_id',DB::raw('Date(created_at) as date_paid'))->whereDate('date_paid',"=",$currentday)->whereIn('id',$validmpesa)->distinct('customer_id')->count();

$array=Array("date"=>$currentday,"total"=>$daypayment+$dayairtime+$dayutility,"unique"=>$uniquecustomers);
 array_push($payments, $array);
}



$payments=array_reverse($payments, true);



    return view('backoffice.aggregate.all',compact('payments','year','month'));
}

function  agbookings(Request $request){

     $payments=[];

    $now = new \DateTime('now');
   $month = $now->format('m');
   $year = $now->format('Y');
   if (isset($request->month)) {
       # code...
$month=$request->month;
$year=$request->year;
   }


$days=cal_days_in_month(CAL_GREGORIAN, intval($month), intval($year));

$firstday=$year."-".$month."-"."00";
for ($i=0; $i <$days ; $i++) { 
    $j=$i+1;
  $currentday=date('Y-m-d', strtotime($firstday. ' + '.$j.' days'));

  if (Now()<$currentday) {
      # code...
    break;
  }


 $daypayment=\App\PaymentLog::select('payment_logs.*',DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d") as TransTime_f'))->whereDate(DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d")'),"=",$currentday)->where("payment_logs.status","=","valid")->sum('TransAmount');
  $newbookings = \App\Bookings::select(DB::raw('Date(created_at) as date_paid','amount_paid'))->whereDate('activated_at',"=",$currentday)->where("amount_paid",">",0)->whereStatus('active')->count();

  $pendingbookings = \App\Bookings::select(DB::raw('Date(created_at) as date_paid','amount_paid'))->whereDate('created_at',"=",$currentday)->whereStatus('pending')->count();
  // $uniquecustomers=\App\Payments::select('customer_id',DB::raw('Date(created_at) as date_paid'))->whereDate('date_paid',"=",$currentday)->distinct('customer_id')->count();

  $validpaymentreferences=\App\PaymentLog::select('payment_logs.*',DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d") as TransTime_f'))->whereDate(DB::raw('DATE_FORMAT(TransTime, "%Y-%m-%d")'),"=",$currentday)->where("payment_logs.status","=","valid")->pluck('TransID')->toArray();
  $validmpesa=\App\Mpesapayments::whereIn("transac_code",$validpaymentreferences)->pluck('payment_id')->toArray();


  $uniquecustomers=\App\Payments::select('customer_id',DB::raw('Date(created_at) as date_paid'))->whereDate('date_paid',"=",$currentday)->whereIn('id',$validmpesa)->distinct('customer_id')->count();

$array=Array("date"=>$currentday,"total"=>$daypayment,"unique"=>$uniquecustomers,"newbookings"=>$newbookings,"pendingbookings"=>$pendingbookings);
 array_push($payments, $array);
}

$payments=array_reverse($payments, true);




    return view('backoffice.aggregate.bookings',compact('payments','year','month'));

}

function agairtime(Request $request){

   $payments=[];

    $now = new \DateTime('now');
   $month = $now->format('m');
   $year = $now->format('Y');
   if (isset($request->month)) {
       # code...
$month=$request->month;
$year=$request->year;
   }


$days=cal_days_in_month(CAL_GREGORIAN, intval($month), intval($year));

$firstday=$year."-".$month."-"."00";
for ($i=0; $i <$days ; $i++) { 
    $j=$i+1;
  $currentday=date('Y-m-d', strtotime($firstday. ' + '.$j.' days'));

  if (Now()<$currentday) {
      # code...
    break;
  }


 // $daypayment=\App\Payments::select('transaction_amount',DB::raw('Date(created_at) as date_paid'))->whereDate('date_paid',"=",$currentday)->sum('transaction_amount');

 $dayairtime = topups::select('amount',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$currentday)->whereIn("type",['airtime'])->whereStatus('valid')->sum('amount');

  // $dayutility = topups::select('amount',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$currentday)->whereNotIn("type",['airtime'])->sum('amount');

  $uniquecustomers=\App\topups::select('sender','type',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$currentday)->whereIn("type",['airtime'])->whereStatus('valid')->distinct('sender')->count();

$array=Array("date"=>$currentday,"total"=>$dayairtime,"unique"=>$uniquecustomers);
 array_push($payments, $array);
}

$payments=array_reverse($payments, true);




    return view('backoffice.aggregate.airtime',compact('payments','year','month'));

}

function agutility(Request $request){


     $payments=[];

    $now = new \DateTime('now');
   $month = $now->format('m');
   $year = $now->format('Y');
   if (isset($request->month)) {
       # code...
$month=$request->month;
$year=$request->year;
   }


$days=cal_days_in_month(CAL_GREGORIAN, intval($month), intval($year));

$firstday=$year."-".$month."-"."00";
for ($i=0; $i <$days ; $i++) { 
    $j=$i+1;
  $currentday=date('Y-m-d', strtotime($firstday. ' + '.$j.' days'));

  if (Now()<$currentday) {
      # code...
    break;
  }


 // $daypayment=\App\Payments::select('transaction_amount',DB::raw('Date(created_at) as date_paid'))->whereDate('date_paid',"=",$currentday)->sum('transaction_amount');

 // $dayairtime = topups::select('amount',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$currentday)->whereNotIn("type",['topup','bill'])->sum('amount');

  $dayutility = topups::select('amount',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$currentday)->whereIn("type",['Bills(GOTV)','Bills(kplc_postpaid)','Bills(kplc_prepaid)'])->sum('amount');

  $uniquecustomers=\App\topups::select('sender','type',DB::raw('Date(created_at) as date_paid'))->whereDate('created_at',"=",$currentday)->whereIn("type",['Bills(GOTV)','Bills(kplc_postpaid)','Bills(kplc_prepaid)'])->distinct('sender')->count();

$array=Array("date"=>$currentday,"total"=>$dayutility,"unique"=>$uniquecustomers);
 array_push($payments, $array);
}



$payments=array_reverse($payments, true);


    return view('backoffice.aggregate.utility',compact('payments','year','month'));
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

function setcommissions(Request $request,$id){
    $vendor=\App\Vendor::with('user')->whereId($id)->first();
//$subcategories=\App\Products::distinct('subcategory_id')->pluck('subcategory_id')->toArray();
    $categories=\App\Categories::get();
$subcats=\App\SubCategories::get();
$commissions=json_decode($vendor->commission_rate_subcategories);


foreach ($subcats as $key => $value) {
 
$haskey=false;
foreach ($commissions as $key1 => $value1) {
    if ($value1->id==$value->id) {
        # code...

        $value->commission_rate=$value1->commission_rate;
$value->commission_cap=$value1->commission_cap;
 $value->fixed_bank=$value1->fixed_bank;
$value->fixed_mobile_money=$value1->fixed_mobile_money;

$haskey=true;
break;
    }
    # code...
}
if (!$haskey) {
    # code...

        $value->commission_rate=0;
    $value->commission_cap=0;
     $value->fixed_bank=0;
$value->fixed_mobile_money=0;
unset($subcats[$key]);
}


}

$fixedcommissions=json_decode($vendor->fixed_cost_subcategories);


foreach ($subcats as $key => $value) {
 
$haskey=false;
foreach ($fixedcommissions as $key1 => $value1) {
    if ($value1->id==$value->id) {
        # code...

//         $value->commission_rate=$value1->commission_rate;
// $value->commission_cap=$value1->commission_cap;
 $value->fixed_bank=$value1->fixed_bank;
$value->fixed_mobile_money=$value1->fixed_mobile_money;

$haskey=true;
break;
    }
    # code...
}
if (!$haskey) {
    # code...
    //     $value->commission_rate=0;
    // $value->commission_cap=0;
     $value->fixed_bank=0;
$value->fixed_mobile_money=0;
}


}





    return view('backoffice.vendors.setcommissions',compact('vendor','subcats','categories'));
}

}
