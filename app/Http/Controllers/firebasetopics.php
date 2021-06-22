<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;



class firebasetopics extends Controller
{
    //

    function index(Request $request){

    	$topics=DB::table("firebasetopics")->get();
return view("backoffice.topics.index",compact('topics'));

    }

    function addtopic(Request $request){
$checkifexists=DB::table("firebasetopics")->whereName($request->input('topic'))->first();
if ($checkifexists!=null) {
	# code...
	return Back()->with("error","Topic Already Exists");
//return Array("status"=>"error","Topic Already Exists");
}
else{
	$topics=DB::table("firebasetopics")->insert(["name"=>$request->input('topic')]);
return Back()->with("success","information updated");
    
}

    	
    }

    function removetopic(Request $request){
    		$topics=DB::table("firebasetopics")->whereId($request->id)->delete();
    		
return Back()->with("success","information updated");
    }
}
