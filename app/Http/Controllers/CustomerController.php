<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\Customers;

class CustomerController extends Controller
{
    public function index()
    {
      return view('backoffice.index');
    }

    public function pending_bookings(){
        $user = Auth::user();
        $customer = Customers::where('user_id','=',$user->id)->first();
        $bookings = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','pending')->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.customer.pending',compact('bookings')); 
    }

    public function complete_bookings(){
        $user = Auth::user();
        $customer = Customers::where('user_id','=',$user->id)->first();
        $bookings = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','complete')->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.customer.complete',compact('bookings')); 
    }

    public function active_bookings(){
        $user = Auth::user();
        $customer = Customers::where('user_id','=',$user->id)->first();
        $bookings = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','active')->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.customer.active',compact('bookings')); 
    }

    public function revoked_bookings(){
        $user = Auth::user();
        $customer = Customers::where('user_id','=',$user->id)->first();
        $bookings = \App\Bookings::where('customer_id','=',$customer->id)->where('status','=','revoked')->get();
        foreach($bookings as $booking){
            $progress = round(($booking->amount_paid/$booking->total_cost)*100);
            $booking['progress'] = $progress;
        }
        return view('backoffice.customer.revoked',compact('bookings')); 
    }

}
