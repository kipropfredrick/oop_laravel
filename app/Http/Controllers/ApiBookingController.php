<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApiBookingController extends Controller
{
    //

    function index(Request $request){
    	return Array("status"=>true,"data"=>$request->all());
    }
}
