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
return $result;
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
       $result=Products::whereSubcategory_id($request->id)->get();
return $result; 
     }


  
}
