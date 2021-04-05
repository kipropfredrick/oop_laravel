<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application backoffice.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalBookingAmount = \App\Bookings::sum('total_cost');
        $activeBookingAmount = \App\Bookings::where('status','=','active')->sum('total_cost');
        $overdueBookingAmount = \App\Bookings::where('status','=','overdue')->sum('total_cost');
        $completeBookingAmount = \App\Bookings::where('status','=','complete')->sum('total_cost');
        $pendingBookingAmount = \App\Bookings::where('status','=','pending')->sum('total_cost');

        $customersCount = \App\Customers::count();
        
        return view('backoffice.index',compact('totalBookingAmount','activeBookingAmount','pendingBookingAmount','overdueBookingAmount','completeBookingAmount','customersCount'));
    }

    public function edit_profile(){
        $profile = auth()->user();
        return view('profile',compact('profile'));
    }

    public function update_profile(Request $request){
        $data = $request->except('_token','password');
        $password = $request->password;


        if($password!=null){
            $data['password'] = Hash::make($request->password);
        }

        \App\User::where('id',auth()->user()->id)->update($data);

        return back()->with('success','Profile Updated!');

    }
}
