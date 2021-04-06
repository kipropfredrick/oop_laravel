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

    public function update_profile(Request $request,$role){
        $data = $request->except('_token','password','phone');
        $password = $request->password;
        $phone = $request->phone;


        if($password!=null){
            $data['password'] = Hash::make($request->password);
        }

        \App\User::where('id',auth()->user()->id)->update($data);

        

         if($phone!=null){

                list($msisdn, $network) = $this->get_msisdn_network($phone);

                if (!$msisdn){

                    return redirect()->back()->with('error',"Please enter a valid phone number!");
                }else{
                    $valid_phone = $msisdn;
                }

                if($role === "user"){
                    \App\Customers::where('user_id',auth()->user()->id)->update(['phone'=>$msisdn]);
                }elseif($role === "vendor"){
                    \App\Vendor::where('user_id',auth()->user()->id)->update(['phone'=>$msisdn]);
                }

        }

        return back()->with('success','Profile Updated!');

    }
}
