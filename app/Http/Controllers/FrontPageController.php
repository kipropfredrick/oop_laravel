<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use DB;
use Carbon\Carbon;
use Hash;
use Exception;
use Illuminate\Support\Facades\Log;
use AfricasTalking\SDK\AfricasTalking;
use App\Http\Controllers\SendSMSController;
use Illuminate\Support\Facades\Mail;
use \App\Mail\SendRegistrationEmail;

class FrontPageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function roundToTheNearestAnything($value, $roundTo)
    {
        $mod = $value%$roundTo;
        return $value+($mod<($roundTo/2)?-$mod:$roundTo-$mod);
    }

     
    public function update_categories(){
        
        $subs = DB::table('sub_categories')->get();

        foreach($subs as $sub){
            $slug =  str_replace(' ', '-', $sub->subcategory_name);
            $slug =  str_replace('/','-',$slug);
            $slug = strtolower($slug);

            DB::table('sub_categories')->where('id',$sub->id)->update(['slug'=>$slug]);
        };


        return "Success";
       

    }

    public function index()
    {

       $third_level_categories = \App\ThirdLevelCategory::all();

       foreach($third_level_categories as $category){

        $slug =  str_replace(' ', '-', $category->name);

        $slug =  str_replace('/','-',$slug);

        // if(!isset($category->slug)){
            \App\ThirdLevelCategory::where('id',$category->id)->update(['slug'=>$slug]);
        // }

       }

       $categories = \App\Categories::with('subcategories.thirdlevelcategories')->get();
       $lcategories = \App\Categories::with('subcategories.thirdlevelcategories')->take(10)->get();

       $products = \App\Products::with('category','subcategory')
                    ->where('status','=','approved')
                    ->where('quantity','>',0)->inRandomOrder()->take(20)->get();

        $trendingProducts = \App\Products::with('category','subcategory')
                            ->where('status','=','approved')
                            ->where('quantity','>',0)->inRandomOrder()->take(20)->get();

        $bookings = \App\Bookings::orderBy('id','DESC')->take(20)->get();

        $product_ids = [];

        foreach($bookings as $booking){
            array_push($product_ids,$booking->product_id);
        }

        $bestSellers = \App\Products::with('category','subcategory')
                        ->where('status','=','approved')
                        ->where('quantity','>',0)->inRandomOrder()->take(20)->get();


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

        $products= \App\Products::where('product_name', 'LIKE', '%' . $search . '%' )
                                ->where('status','=','approved')
                                ->where('quantity','>',0)
                                ->orderBy('id','DESC')
                                // ->inRandomOrder()
                                ->orderBy('id','DESC')
                                ->paginate(20);

            $sort_by = $request->sort_by;

            if($sort_by !=null){
    
                if($sort_by == "price-asc"){
                    $p = "product_price";
                    $o = "ASC";
                }elseif($sort_by == "price-desc"){
                    $p = "product_price";
                    $o = "DESC";
                }elseif($sort_by == "id"){
                    $p = "id";
                    $o = "DESC";
                }elseif($sort_by == "best-sellers"){
    
                    $bookings = \App\Bookings::orderBy('id','DESC')->take(20)->get();
    
                    $product_ids = [];
            
                    foreach($bookings as $booking){
                        array_push($product_ids,$booking->product_id);
                    }

                    $products =   \App\Products::with('category','subcategory','gallery')->where ( 'product_name', 'LIKE', '%' . $search . '%' )
                                    ->where('quantity','>',0)->where('status','=','approved')->inRandomOrder()->paginate(20);
            
    
                        return view('front.search_results',compact('products','categories','search','sort_by'));
                }
    
            }else{
                $sort_by = "id";
                $p = "id";
                $o = "DESC";
            }

            $products =   \App\Products::with('category','subcategory','gallery')->where ( 'product_name', 'LIKE', '%' . $search . '%' )
                            ->where('quantity','>',0)->where('status','=','approved')->orderBy($p,$o)->paginate(20);
                        

        return view('front.search_results',compact('products','categories','search','sort_by'));
       

    }

    public function search_load_more(Request $request){

        $sort_by = $request->sort_by;
        
        $search =  $request->search;

        if($request->ajax()){

            $skip=$request->skip;
            $take=12;

            if($sort_by == "price-asc"){
                $p = "product_price";
                $o = "ASC";
            }elseif($sort_by == "price-desc"){
                $p = "product_price";
                $o = "DESC";
            }else{
                $p = "id";
                $o = "DESC";
            }


        $products =   \App\Products::with('category','subcategory','gallery')
                                        ->where('product_name', 'LIKE', '%' . $search . '%' )
                                        ->where('quantity','>',0)
                                        ->where('status','=','approved')
                                        ->orderBy($p,$o)
                                        ->skip($skip)
                                        ->take($take)
                                        ->get();

            foreach($products as $product){

                $product->product_price = number_format($product->product_price);
                
            }

            return response()->json($products);
        }else{
            return response()->json('Direct Access Not Allowed!!');
        }
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
        $product = \App\Products::with('category','brand','subcategory','gallery','vendor.user','agent.user')
                                ->where('slug','=',$slug)
                                ->orWhere('slug', 'like', $slug . '%')
                                ->first();
            if($product->status!='approved'){
                return redirect('/sub/'.$product->subcategory->slug);
            }
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

    public function category(Request $request,$slug){

        $sort_by = $request->sort_by;
        
        $brand_slug = $request->brand;

        $brand  = [];

        $categories = \App\Categories::all();

        $category = \App\Categories::with('subcategories')->where('slug','=',$slug)->first();

        $brand_ids = \App\Products::where('status','=','approved')
                ->distinct('brand_id')
                ->where('category_id',$category->id)
                ->where('quantity','>',0)
                ->whereNotNull('brand_id')
                ->pluck('brand_id')
                ->toArray();
        

        $brands  = DB::table('brands')
                                ->whereIn("id",$brand_ids)
                                ->orderBy('id', 'DESC')
                                ->get();

        $brand = \App\Brand::where('slug',$brand_slug)->first();

        $current_b = $brand;

        $trendingProducts = \App\Products::with('category','subcategory')
                                            ->where('status','=','approved')
                                            ->where('quantity','>',0)
                                            ->where('category_id',$category->id)
                                            ->where(function($query) use ($brand)
                                            {
                                                if (!empty($brand)) {
                                                    $query->where('brand_id', $brand->id);
                                                }
                                            })
                                            ->inRandomOrder()
                                            ->take(20)->get();;

        if($sort_by !=null){

            if($sort_by == "price-asc"){
                $p = "product_price";
                $o = "ASC";
            }elseif($sort_by == "price-desc"){
                $p = "product_price";
                $o = "DESC";
            }elseif($sort_by == "id"){
                $p = "id";
                $o = "DESC";
            }elseif($sort_by == "best-sellers"){

                $bookings = \App\Bookings::orderBy('id','DESC')->take(20)->get();

                $product_ids = [];
        
                foreach($bookings as $booking){
                    array_push($product_ids,$booking->product_id);
                }
        
                $products = \App\Products::with('category','subcategory')->where('status','=','approved')
                                            ->where('category_id','=',$category->id)
                                            ->where('quantity','>',0)
                                            ->where(function($query) use ($brand)
                                            {
                                                if (!empty($brand)) {
                                                    $query->where('brand_id', $brand->id);
                                                }
                                            })
                                            ->inRandomOrder()
                                            ->paginate(20);

                $productsCount = \App\Products::with('category','subcategory')
                                            ->where('status','=','approved')
                                            ->where('category_id','=',$category->id)
                                            ->where('quantity','>',0)
                                            ->where(function($query) use ($brand)
                                            {
                                                if (!empty($brand)) {
                                                    $query->where('brand_id', $brand->id);
                                                }
                                            })
                                            ->count();

                return view('front.show_category',compact('products','productsCount','sort_by','current_b','categories','category','trendingProducts','brands'));
            }

        }else{
            $sort_by = "id";
            $products =   \App\Products::with('category','subcategory','gallery')
                                        ->where('category_id','=',$category->id)
                                        ->where('quantity','>',0)
                                        ->where(function($query) use ($brand)
                                        {
                                            if (!empty($brand)) {
                                                $query->where('brand_id', $brand->id);
                                            }
                                        })
                                        ->where('status','=','approved')
                                        ->inRandomOrder()
                                        ->paginate(20);

            $productsCount =  \App\Products::with('category','subcategory','gallery')
                                        ->where('category_id','=',$category->id)
                                        ->where('quantity','>',0)
                                        ->where(function($query) use ($brand)
                                        {
                                            if (!empty($brand)) {
                                                $query->where('brand_id', $brand->id);
                                            }
                                        })
                                        ->where('status','=','approved')
                                        ->count();

            return view('front.show_category',compact('products','productsCount','current_b','sort_by','categories','category','trendingProducts','brands'));
        }


        $products = \App\Products::with('category','subcategory')->where('status','=','approved')
                            ->where('category_id','=',$category->id)
                            ->where('quantity','>',0)
                            ->where(function($query) use ($brand)
                            {
                                if (!empty($brand)) {
                                    $query->where('brand_id', $brand->id);
                                }
                            })
                            ->orderBy($p,$o)
                            ->paginate(20);

        $productsCount = \App\Products::with('category','subcategory')->where('status','=','approved')
                            ->where('category_id','=',$category->id)
                            ->where('quantity','>',0)
                            ->where(function($query) use ($brand)
                            {
                                if (!empty($brand)) {
                                    $query->where('brand_id', $brand->id);
                                }
                            })
                            ->orderBy($p,$o)
                            ->count();


        return view('front.show_category',compact('products','productsCount','current_b','sort_by','categories','category','trendingProducts','brands'));

    }


    public function category_load_more(Request $request,$slug){

        $sort_by = $request->sort_by;

        $categories = \App\Categories::all();

        $category = \App\Categories::where('slug','=',$slug)->first();

        $brand_slug = $request->brand;

        $brand = \App\Brand::where('slug',$brand_slug)->first();

        if($request->ajax()){

            $skip=$request->skip;
            $take=12;

            if($sort_by == "price-asc"){
                $p = "product_price";
                $o = "ASC";
            }elseif($sort_by == "price-desc"){
                $p = "product_price";
                $o = "DESC";
            }else{
                $p = "id";
                $o = "DESC";
            }


            $products =   \App\Products::with('category','subcategory','gallery')
                                        ->where('category_id','=',$category->id)
                                        ->where('quantity','>',0)
                                        ->where('status','=','approved')
                                        ->where(function($query) use ($brand)
                                        {
                                            if (!empty($brand)) {
                                                $query->where('brand_id', $brand->id);
                                            }
                                        })
                                        ->orderBy($p,$o)
                                        ->skip($skip)
                                        ->take($take)
                                        ->get();

            foreach($products as $product){

                $product->product_price = number_format($product->product_price);
                
            }

            return response()->json($products);
        }else{
            return response()->json('Direct Access Not Allowed!!');
        }
    }

    public function thirdlevelcategory(Request $request,$subcategory,$slug){

        $sort_by = $request->sort_by;
        
        $categories = \App\Categories::all();

       
        $thirdlevel_category = \App\ThirdLevelCategory::with('subcategory')->where('slug','=',$slug)->first();


        $subcategory = \App\SubCategories::where('slug','=',$subcategory)->first();


        $category = \App\Categories::where('id','=',$subcategory->category_id)->first();

        $brand_slug = $request->brand;

        $brand = [];

        $brand = \App\Brand::where('slug',$brand_slug)->first();

        $current_b = $brand;

        $brand_ids = \App\Products::where('status','=','approved')
                                    ->distinct('brand_id')
                                    ->where('third_level_category_id',$thirdlevel_category->id)
                                    ->where('quantity','>',0)
                                    ->where(function($query) use ($brand)
                                        {
                                            if (!empty($brand)) {
                                                $query->where('brand_id', $brand->id);
                                            }
                                    })
                                    ->whereNotNull('brand_id')
                                    ->pluck('brand_id')
                                    ->toArray();
        

        $brands  = DB::table('brands')
                                ->whereIn("id",$brand_ids)
                                ->orderBy('id', 'DESC')
                                ->get();
        
        
        $trendingProducts = \App\Products::with('category','subcategory')
                                            ->where('status','=','approved')
                                            ->where('quantity','>',0)
                                            ->where('subcategory_id',$subcategory->id)
                                            ->where('third_level_category_id',$thirdlevel_category->id)
                                            ->inRandomOrder()
                                            ->take(10)->get();
        
        
        if($sort_by !=null){
        
            if($sort_by == "price-asc"){
                $p = "product_price";
                $o = "ASC";
            }elseif($sort_by == "price-desc"){
                $p = "product_price";
                $o = "DESC";
            }elseif($sort_by == "id"){
                $p = "id";
                $o = "DESC";
            }elseif($sort_by == "best-sellers"){
        
                $bookings = \App\Bookings::orderBy('id','DESC')->take(20)->get();
        
                $product_ids = [];
        
                foreach($bookings as $booking){
                    array_push($product_ids,$booking->product_id);
                }
        
                $products = \App\Products::with('category','subcategory')->where('status','=','approved')
                                            ->where('subcategory_id',$subcategory->id)
                                            ->where('third_level_category_id','=',$thirdlevel_category->id)
                                            ->where(function($query) use ($brand)
                                                {
                                                    if (!empty($brand)) {
                                                        $query->where('brand_id', $brand->id);
                                                    }
                                            })
                                            ->where('quantity','>',0)
                                            ->inRandomOrder()
                                            ->paginate(20);
        
                return view('front.show_third_category',compact('products','brands','current_b','thirdlevel_category','subcategory','sort_by','categories','category','trendingProducts'));
            }
        
        }else{
            $sort_by = "id";
            $products =   \App\Products::with('category','subcategory','gallery')
                                            ->where('subcategory_id',$subcategory->id)
                                            ->where('third_level_category_id','=',$thirdlevel_category->id)
                                            ->where(function($query) use ($brand)
                                                {
                                                    if (!empty($brand)) {
                                                        $query->where('brand_id', $brand->id);
                                                    }
                                            })
                                        ->where('quantity','>',0)->where('status','=','approved')
                                        ->paginate(20);
            return view('front.show_third_category',compact('products','brands','current_b','thirdlevel_category','subcategory','sort_by','categories','category','trendingProducts'));
        }
        
        
        $products = \App\Products::with('category','subcategory')->where('status','=','approved')
                                    ->where('subcategory_id',$subcategory->id)
                                    ->where('third_level_category_id','=',$thirdlevel_category->id)
                                    ->where(function($query) use ($brand)
                                        {
                                            if (!empty($brand)) {
                                                $query->where('brand_id', $brand->id);
                                            }
                                    })
                                    ->where('quantity','>',0)
                                    ->orderBy($p,$o)
                                    ->paginate(20);
        
        return view('front.show_third_category',compact('products','brands','current_b','thirdlevel_category','subcategory','sort_by','categories','category','trendingProducts'));
        
        }

    public function brand(Request $request, $slug){

        $categories = \App\Categories::all();

        $sub_slug = $request->sub;

        $current_sub = \App\SubCategories::where('slug',$sub_slug)->first();
        
        $brand = \App\Brand::where('slug','=',$slug)->first();

        $current_b = $brand;

        $brands = \App\Brand::where('id','!=',$brand->id)->get();

        $cat_ids = DB::table('products')->where('brand_id',$brand->id)->distinct('category_id')->pluck('category_id')->toArray();

        $b_categories = \App\Categories::with('subcategories')->whereIn('id',$cat_ids)->get();

        $trendingProducts = \App\Products::with('category','subcategory')->where('status','=','approved')
                                    ->where('quantity','>',0)
                                    ->where('brand_id',$brand->id)
                                    ->inRandomOrder()
                                    ->take(10)->get();

        $sort_by = $request->sort_by;

        if($sort_by !=null){

            if($sort_by == "price-asc"){
                $p = "product_price";
                $o = "ASC";
            }elseif($sort_by == "price-desc"){
                $p = "product_price";
                $o = "DESC";
            }elseif($sort_by == "id"){
                $p = "id";
                $o = "DESC";
            }elseif($sort_by == "best-sellers"){

                $bookings = \App\Bookings::orderBy('id','DESC')->take(20)->get();

                $product_ids = [];
        
                foreach($bookings as $booking){
                    array_push($product_ids,$booking->product_id);
                }
        
                $products = \App\Products::with('category','subcategory')->where('status','=','approved')
                                            ->where('brand_id','=',$brand->id)
                                            ->where('quantity','>',0)
                                            ->where(function($query) use ($current_sub)
                                            {
                                                    if (!empty($current_sub)) {
                                                        $query->where('subcategory_id', $current_sub->id);
                                                    }
                                            })
                                            ->inRandomOrder()
                                            ->paginate(20);
                                            

                return view('front.show_brand',compact('products','current_sub','current_b','brands','b_categories','sort_by','sort_by','categories','brand','trendingProducts'));
            }

            $products =   \App\Products::with('category','subcategory','gallery')
                                        ->where('brand_id','=',$brand->id)
                                        ->where('quantity','>',0)
                                        ->where('status','=','approved')
                                        ->where(function($query) use ($current_sub)
                                            {
                                                    if (!empty($current_sub)) {
                                                        $query->where('subcategory_id', $current_sub->id);
                                                    }
                                        })
                                        ->orderBy($p,$o)
                                        ->paginate(20);

        }else{
            $sort_by = "id";
            $products =   \App\Products::with('category','subcategory','gallery')
                                        ->where('brand_id','=',$brand->id)
                                        ->where('quantity','>',0)
                                        ->where('status','=','approved')
                                        ->where(function($query) use ($current_sub)
                                            {
                                                    if (!empty($current_sub)) {
                                                        $query->where('subcategory_id', $current_sub->id);
                                                    }
                                         })
                                        ->inRandomOrder()
                                        ->paginate(20);
        }

        
        
        return view('front.show_brand',compact('products','current_sub','current_b','brands','b_categories','sort_by','categories','brand','trendingProducts'));
        
    }

    public function search_brand(Request $request){

        $searchTerm = $request->searchTerm;

        $brands = \App\Brand::where('brand_name', 'LIKE', "%{$searchTerm}%")->get();

        return $brands;

    }

    public function brand_load_more(Request $request,$slug){

        $sort_by = $request->sort_by;

        $categories = \App\Categories::all();
        
        $sub_slug = $request->sub;

        $current_sub = \App\SubCategories::where('slug',$sub_slug)->first();

        $brand = \App\Brand::where('slug','=',$slug)->first();

        if($request->ajax()){

            $skip=$request->skip;
            $take=12;

            if($sort_by == "price-asc"){
                $p = "product_price";
                $o = "ASC";
            }elseif($sort_by == "price-desc"){
                $p = "product_price";
                $o = "DESC";
            }else{
                $p = "id";
                $o = "DESC";
            }


            $products =   \App\Products::with('category','subcategory','gallery')
                                        ->where('brand_id','=',$brand->id)
                                        ->where('quantity','>',0)
                                        ->where('status','=','approved')
                                        ->where(function($query) use ($current_sub)
                                            {
                                                    if (!empty($current_sub)) {
                                                        $query->where('subcategory_id', $current_sub->id);
                                                    }
                                        })
                                        ->orderBy($p,$o)
                                        ->skip($skip)
                                        ->take($take)
                                        ->get();

            foreach($products as $product){

                $product->product_price = number_format($product->product_price);
                
            }

            return response()->json($products);
        }else{
            return response()->json('Direct Access Not Allowed!!');
        }
    }

    public function shop($id){

        $categories = \App\Categories::all();

        $influencer = \App\Influencer::where('id','=',$id)->first();

        $products =   \App\Products::with('category','subcategory','gallery')->where('influencer_id','=',$id)
                                    ->where('quantity','>',0)->where('status','=','approved')->orderBy('id','DESC')->inRandomOrder()->paginate(20);

        return view('front.shop',compact('products','categories','influencer'));

    }

    public function thirdlevelcategory_load_more(Request $request,$subcategory,$slug){

        $sort_by = $request->sort_by;

        $categories = \App\Categories::all();
            
            $thirdlevel_category = \App\ThirdLevelCategory::with('subcategory')->where('slug','=',$slug)->first();
        
            $subcategory = \App\SubCategories::where('id','=',$thirdlevel_category->subcategory_id)->first();
            
        
            $category = \App\Categories::where('id','=',$subcategory->category_id)->first();
        
                if($request->ajax()){
        
                    $skip=$request->skip;
                    $take=12;
        
                    if($sort_by == "price-asc"){
                        $p = "product_price";
                        $o = "ASC";
                    }elseif($sort_by == "price-desc"){
                        $p = "product_price";
                        $o = "DESC";
                    }else{
                        $p = "id";
                        $o = "DESC";
                    }
        
        
                    $products =   \App\Products::with('category','subcategory','gallery')
                                                ->where('subcategory_id',$subcategory->id)
                                                ->where('third_level_category_id','=',$thirdlevel_category->id)
                                                ->where('quantity','>',0)
                                                ->where('status','=','approved')
                                                ->orderBy($p,$o)
                                                ->skip($skip)
                                                ->take($take)
                                                ->get();
        
                    foreach($products as $product){
        
                        $product->product_price = number_format($product->product_price);
                        
                    }
        
                    return response()->json($products);
                }else{
                    return response()->json('Direct Access Not Allowed!!');
                } 
        
    }

    public function subcategory(Request $request, $slug){

        $categories = \App\Categories::all();

        $subcategory = \App\SubCategories::where('slug','=',$slug)->first();

        $category = \App\Categories::where('id','=',$subcategory->category_id)->first();

        $brand_slug = $request->brand;

        $brand = [];

        $brand = \App\Brand::where('slug',$brand_slug)->first();

        $current_b = $brand;

        $brand_ids = \App\Products::where('status','=','approved')
                                    ->distinct('brand_id')
                                    ->where('subcategory_id',$subcategory->id)
                                    ->where('quantity','>',0)
                                    ->whereNotNull('brand_id')
                                    ->pluck('brand_id')
                                    ->toArray();
        

        $brands  = DB::table('brands')
                                ->whereIn("id",$brand_ids)
                                ->orderBy('id', 'DESC')
                                ->get();

        $trendingProducts = \App\Products::with('category','subcategory')
                                          ->where('status','=','approved')
                                          ->where('subcategory_id',$subcategory->id)
                                          ->where('quantity','>',0)
                                          ->orderBy('clicks','DESC')
                                          ->inRandomOrder()
                                          ->take(10)
                                          ->get();

        $sort_by = $request->sort_by;

        if($sort_by !=null){

            if($sort_by == "price-asc"){
                $p = "product_price";
                $o = "ASC";
            }elseif($sort_by == "price-desc"){
                $p = "product_price";
                $o = "DESC";
            }elseif($sort_by == "id"){
                $p = "id";
                $o = "DESC";
            }elseif($sort_by == "best-sellers"){

                $bookings = \App\Bookings::orderBy('id','DESC')->take(20)->get();

                $product_ids = [];
        
                foreach($bookings as $booking){
                    array_push($product_ids,$booking->product_id);
                }
        
                $products = \App\Products::with('category','subcategory')
                                            ->where('status','=','approved')
                                            ->where('subcategory_id',$subcategory->id)
                                            ->where('quantity','>',0)
                                            ->whereIn('id',$product_ids)
                                            ->where(function($query) use ($brand)
                                            {
                                                if (!empty($brand)) {
                                                    $query->where('brand_id', $brand->id);
                                                }
                                            })
                                            ->orderBy('id','DESC')
                                            ->paginate(20);

                $productsCount = \App\Products::where('status','=','approved')
                                                ->where('subcategory_id',$subcategory->id)
                                                ->where('quantity','>',0)
                                                ->where(function($query) use ($brand)
                                                    {
                                                        if (!empty($brand)) {
                                                            $query->where('brand_id', $brand->id);
                                                        }
                                                    })
                                                ->count();

                return view('front.show_subcategory',compact('products','productsCount','current_b','brands','sort_by','trendingProducts','categories','category','subcategory'));
            }

            $products = \App\Products::with('category','subcategory','gallery')
                                        ->where('subcategory_id','=',$subcategory->id)
                                        ->where('vendor_id' , '!=', null)
                                        ->where('quantity','>',0)
                                        ->where('status','=','approved')
                                        ->where(function($query) use ($brand)
                                            {
                                                if (!empty($brand)) {
                                                    $query->where('brand_id', $brand->id);
                                                }
                                            })
                                        ->orderBy($p,$o)
                                        ->paginate(20);

            $productsCount = \App\Products::where('status','=','approved')
                                        ->where('subcategory_id',$subcategory->id)
                                        ->where('quantity','>',0)
                                        ->where(function($query) use ($brand)
                                            {
                                                if (!empty($brand)) {
                                                    $query->where('brand_id', $brand->id);
                                                }
                                            })
                                        ->count();

        }else{
            $sort_by = "id";
            $products =   \App\Products::with('category','subcategory','gallery')
                                         ->where('subcategory_id','=',$subcategory->id)
                                         ->where('quantity','>',0)
                                         ->where('status','=','approved')
                                         ->where(function($query) use ($brand)
                                            {
                                                if (!empty($brand)) {
                                                    $query->where('brand_id', $brand->id);
                                                }
                                            })
                                        ->orderBy('id','DESC')
                                         ->paginate(20);

            $productsCount = \App\Products::where('status','=','approved')
                                        ->where('subcategory_id',$subcategory->id)
                                        ->where('quantity','>',0)
                                        ->where(function($query) use ($brand)
                                            {
                                                if (!empty($brand)) {
                                                    $query->where('brand_id', $brand->id);
                                                }
                                            })
                                        ->count();
        }

       

        return view('front.show_subcategory',compact('products','productsCount','current_b','brands','sort_by','trendingProducts','categories','category','subcategory'));

    }

    public function subcategory_load_more(Request $request,$slug){

            $sort_by = $request->sort_by;

            $categories = \App\Categories::all();

            $subcategory = \App\SubCategories::where('slug','=',$slug)->first();

            $brand_slug = $request->brand;

            $brand = [];
    
            $brand = \App\Brand::where('slug',$brand_slug)->first();

            $skip=$request->skip;

            $take=12;

            if($request->ajax()){

                        if($sort_by == "price-asc"){
                            $p = "product_price";
                            $o = "ASC";
                        }elseif($sort_by == "price-desc"){
                            $p = "product_price";
                            $o = "DESC";
                        }else{
                            $p = "id";
                            $o = "DESC";
                        }


                    $products =   \App\Products::with('category','subcategory','gallery')
                                                    ->where('subcategory_id','=',$subcategory->id)
                                                    ->where('quantity','>',0)
                                                    ->where('status','=','approved')
                                                    ->where(function($query) use ($brand)
                                                        {
                                                            if (!empty($brand)) {
                                                                $query->where('brand_id', $brand->id);
                                                            }
                                                        })
                                                    ->orderBy($p,$o)
                                                    ->skip($skip)
                                                    ->take($take)
                                                    ->get();

                    foreach($products as $product){

                        $product->product_price = number_format($product->product_price);
                        
                    }

                    return response()->json($products);
                }else{
                    return response()->json('Direct Access Not Allowed!!');
                }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request, $slug)
    {

if ((auth()->user())!=null) {
    # code...
     $existingUser = \App\User::where('email',  auth()->user()->email)->first();
}
else
{
    $existingUser=null;
}


    # code...
    if($existingUser!=null)
        {
if ($existingUser->role == "user" ) {

        $user = $existingUser;

        $existingCustomer = \App\Customers::where('user_id','=',$existingUser->id)->first();


        $booking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->whereNotIn('status', ['complete','revoked'])->first();

        if($booking!=null){
            return view('front.exists',compact('booking'));
        }
    }
}
        

        $categories = \App\Categories::all();
        $product_quantity = "1";

        $product = \App\Products::with('category','subcategory','gallery')
                                  ->where('slug','=',$slug)
                                  ->orWhere('slug', 'like', $slug . '%')
                                  ->first();

        if($product->status!='approved'){
            return redirect('/sub/'.$product->subcategory->slug);
        }

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

        $product = \App\Products::with('category','subcategory','gallery')
                                  ->where('slug','=',$slug)
                                  ->orWhere('slug', 'like', $slug . '%')
                                  ->first();

        if($product->status!='approved'){
            return redirect('/sub/'.$product->subcategory->slug);
        }

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

        $product = \App\Products::with('category','subcategory','gallery')
                                  ->where('slug','=',$slug)
                                  ->orWhere('slug', 'like', $slug . '%')
                                  ->first();

        if($product->status!='approved'){
            return redirect('/sub/'.$product->subcategory->slug);
        }

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

        $product = \App\Products::with('category','subcategory','gallery')
                                  ->where('slug','=',$slug)
                                  ->orWhere('slug', 'like', $slug . '%')
                                  ->first();

        if($product->product_price < 5000){
            $minDeposit = 0.2*$product->product_price;
        }else {
            $minDeposit = 0.1 *$product->product_price;
        }

        //    return($product_quantity);
        return view('front.checkoutAccountBonga',compact('product','product_quantity','categories','minDeposit'));

    }

    


        public function get_msisdn_network($msisdn){
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

        $vendor_code = $request->vendor_code;

        list($msisdn, $network) = $this->get_msisdn_network($request->phone);

        if (!$msisdn){

            return redirect()->back()->with('error',"Please enter a valid phone number!");
        }else{
            $valid_phone = $msisdn;
        }
        
        $existingCustomer = \App\Customers::where('phone','=',$valid_phone)->first();

        if($existingCustomer === null){
            return redirect()->back()->with('error',"You have no account");
        }else{

        $user = \App\User::find($existingCustomer->user_id);
        
        $booking_date = now();

        $$booking_date = strtotime($booking_date);
        
       $due_date = Carbon::now()->addMonths(3);

        
       $product = \App\Products::find($request->product_id);


        $booking = \App\Bookings::where('customer_id','=',$existingCustomer->id)->whereNotIn('status', ['complete','revoked'])->first();


        if($booking!=null){
            return view('front.exists',compact('booking'));
        }



        if($product->weight != 0){
            $weight_array = preg_split('#(?<=\d)(?=[a-z])#i', $product->weight);
        }else{
            $weight_array = (['0','g']);
        }

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

           $total_cost = ($product->product_price + $shipping_cost);

           $total_cost = $this->roundToTheNearestAnything($total_cost, 5);

            $booking = new \App\Bookings();
            $booking->customer_id = $existingCustomer->id; 
            $booking->product_id  = $request->product_id;
            $booking->county_id = $request->county_id;
            $booking->exact_location = $request->exact_location;
            $booking->booking_reference =  $this->get_booking_reference();
            $booking->quantity  = '1';
            $booking->amount_paid = "0";
            $booking->item_cost = $product->product_price;
            $booking->balance = $total_cost;
            $booking->payment_mode  = 'Mpesa';
            $booking->date_started  = now();
            $booking->due_date = $due_date;
            $booking->status = "pending";
            $booking->vendor_code = $vendor_code;
            $booking->location_type = "Exact Location";
            $booking->shipping_cost = $shipping_cost;
            $booking->total_cost =  $total_cost;
            $booking->save();

        $booking_id = DB::getPdo()->lastInsertId();

        $recipients = $valid_phone;
       
        $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking_reference." And amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";
        
        $amount = $request->initial_deposit;
        $msisdn = $valid_phone;
        $booking_ref = $booking_reference;

        SendSMSController::sendMessage($recipients,$message,$type="on_booking_notification");

         $message =  $this->stk_push($amount,$msisdn,$booking_ref);

         \Auth::login($user);
        
        }

        $categories = \App\Categories::with('subcategories')->get();
        
        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";

        return view('front.processing',compact('product','customer','stkMessage','booking_reference','categories','message','amount'));
        

    }

    public function get_booking_reference(){


for($i=0;$i<1000000;$i++){
$booking_reference = 'MM'.rand(10000,99999);
$res=\App\Bookings::whereBooking_reference($booking_reference)->first();
if ($res==null) {
# code...
break;
}

}

        return $booking_reference;

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
            return view('front.exists',compact('booking'));
        }

        \Auth::login($user);

        $booking_reference = $this->get_booking_reference();

        $booking_date = now();

        $due_date = Carbon::now()->addMonths(3);

        
        $product = \App\Products::with('category','subcategory','gallery')->where('id','=',$request->product_id)->first();

        
        if($request->initial_deposit<100){

          return redirect()->back()->with('error',"The Minimum deposit for this product is : KES ".number_format(100,0));
         
        }

        $balance=0;
        //$existingUser->balance;

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
        
        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";
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

        return view('front.processing',compact('product','customer','stkMessage','booking_reference','categories','message','amount'));
            
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

        return view('front.processing',compact('product','customer','stkMessage','booking_reference','categories','message','amount'));
            
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
        $booking->booking_reference = $booking_reference;
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

       $message =  "Please Complete your booking. Use Paybill 4040299, account number ".$booking_reference." And amount Ksh.".number_format($request->initial_deposit).". For inquiries, Call/App 0113980270";

       SendSMSController::sendMessage($recipients,$message,$type="after_booking_notification");

       // $details = [
       //  'email' => $request->email,
       //  'name'=>$request->name,
       //  'booking_reference'=>$booking_reference,
       //  'initial_deposit'=>number_format($request->initial_deposit),
       //  'password'=>$request->input('phone')
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

        $message = $this->stk_push($amount,$msisdn,$booking_ref);

        $stkMessage = "Go to your MPESA, Select Paybill Enter : 4040299 and Account Number : ".$booking_reference.", Enter Amount : ".number_format($amount,2).", Thank you.";

        return view('front.processing',compact('product','customer','stkMessage','booking_reference','categories','message','amount'));

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


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function lipaNaMpesaPassword($lipa_time)
    {
      
        $passkey = env('STK_PASSKEY');  
        $BusinessShortCode = env('MPESA_SHORT_CODE');
        $timestamp =$lipa_time;
        $lipa_na_mpesa_password = base64_encode($BusinessShortCode.$passkey.$timestamp);
        return $lipa_na_mpesa_password;
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

            'BusinessShortCode' => env('MPESA_SHORT_CODE'),
            'Password'          => $apiPassword,
            'Timestamp'         => $lipa_time,
            'TransactionType'   => 'CustomerPayBillOnline',
            'Amount'            => $amount,
            'PartyA'            => $msisdn,
            'PartyB'            =>env('MPESA_SHORT_CODE'),
            'PhoneNumber'       => $msisdn,
            'CallBackURL'       => 'https://mosmos.co.ke/stk-callback',
            'AccountReference'  => $booking_ref,
            'TransactionDesc'   => 'Mosmos Product Payment'
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
            $message = "Automatic payment failed. Go to your MPESA, Select Paybill Enter : env('MPESA_SHORT_CODE') and Account Number : ".$booking_ref."Enter Amount : ".number_format($amount,2)." Thank you.";
        }else{
            $message = "A payment prompt has been sent to your phone.Enter MPesa PIN if prompted.";
        }

        return $message;
    }

    public function update_bookings_agent_or_vendor(){

        $bookings = \App\Bookings::all();

        $vendors = \App\Vendor::all();

        foreach($vendors as $vendor){

            $vendor_code = "VD".$vendor->id;

            DB::table('vendors')->where('id',$vendor->id)->update(['vendor_code'=>$vendor_code]);

        }

        foreach($bookings as $booking){

                $product = \App\Products::with('subcategory')->where('id','=',$booking->product_id)->first();

                 $vendor_code = null;

                if($product->vendor_id!=null){

                    $vendor = \App\Vendor::where('id','=',$product->vendor_id)->first();
        
                    $vendor_code = $vendor->vendor_code;
        
                }

                DB::table('bookings')->where('id',$booking->id)->update(['vendor_code'=>$vendor_code]);
            
        }

        $out = ['Success'=>'True'];

         return response()->json( $out);

            
    }


    public function testSendSMS(){

        $message =  "Please Complete your booking. Use Paybill 4040299, account number BKG5126 And amount Ksh. 500";
        
       $recipients = "254725569054";

       if(SendSMSController::sendMessage($recipients,$message,$type="Test")){
           return redirect('/');
       }else{
           return "Error";
       }

    }

    public function payments(){
        // $payments =  DB::table('payments')->get();
        if ((auth()->user())==null) {
            # code...
            return back();
        }
$id=auth()->user()->id;

$customer_id=DB::table("customers")->whereUser_id($id)->first()->id;
        $payments = \App\Payments::with('customer','mpesapayment','customer.user','product')->whereCustomer_id($customer_id)->orderBy('id', 'DESC')->get();

         

        return view('backoffice.payments.indexold',compact('payments'));
    }

    public function MakePayment(Request $request){

        $msisdn=$request->input("phone");
        $amount=$request->input('amount');
        $booking_ref=$request->input("bookingref");

 $message =  $this->stk_push($amount,$msisdn,$booking_ref);

 return $message;
    }

    

}
