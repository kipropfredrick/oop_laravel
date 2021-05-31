<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categories;
use App\SubCategories;
use App\Products;
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

//$result=SubCategories::whereCategory_id($request->id)->get();
//$result=Products::whereSubcategory_id($request->id)->limit(6)->get();

for ($i=0; $i <count($result) ; $i++) { 
    # code...
    $res=Array();
    //cat for category
    $cat=Array();
    $cat['name']=$result[$i]->category_name;
    $cat['id']=$result[$i]->id;
    $cat['icon'=$result[$i]->category_icon;
    $cat['slug']=$result[$i]->slug;
    $res['category']=$cat;

    $subcategories=SubCategories::whereCategory_id($request->id)->get();

    $midres=Array();
    $midresult=[];

    for ($j=0; $j < count($subcategories) ; $j++) { 
        # code...
        //subcat defines sub category

        $subcat=Array();
        $subcat['id']=$subcategories[$i]->id;
        $subcat['name']=$subcategories[$i]->subcategory_name;
        $subcat['slug']=$subcategories[$i]->slug;
        $subcat['subcategory']=$subcat;

        $subcat['products']=Products::whereSubcategory_id($subcategories[$i]->id)->limit(6)->get();
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


  
}
