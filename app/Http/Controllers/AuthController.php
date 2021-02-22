<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;
use Storage;
use Carbon\Carbon;
use Hash;
use Image;
use AfricasTalking\SDK\AfricasTalking;

class AuthController extends Controller
{
    
    public function register(Request $request){

        // dd($request->all());

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
        $existingUser = \App\Vendor::where('phone','=',$valid_phone)->first();
        if($existingUser)
        {
            return back()->withInput()->with('error', 'Contact is taken!');
        }
        $user = new \App\User();
        $user->email = $request->input('email');
        $user->name = $request->input('name');
        $user->role ='vendor';
        $user->password = Hash::make($request->input('password'));
        $user->save();

        $user_id = DB::getPdo()->lastInsertId();

        $image = $request->file('business_logo');

        if(!Storage::disk('public')->exists('thumbnail')){
            Storage::disk('public')->makeDirectory('thumbnail');
        }

        if(!Storage::disk('public')->exists('images')){
            Storage::disk('public')->makeDirectory('images');
        }

        $time = time();

        if ($files = $request->file('business_logo')) {
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
        

        $vendor = new \App\Vendor();
        $vendor->user_id = $user_id;  
        $vendor->phone  = '254'.ltrim($request->input('phone'), '0');
        $vendor->location  = $request->input('location');
        $vendor->city_id  = $request->input('city_id');
        $vendor->vendor_code = "VD".$user_id;
        $vendor->country  = $request->input('country');
        $vendor->logo  = $image;
        $vendor->business_description  = $request->input('business_description');
        $vendor->business_name  = $request->input('business_name');
        $vendor->save();


        $username   = "Combinesms";
        $apiKey     = "cf56a93a37982301267fd00af0554c068a4efeb005213e568278c9492152ca28";

        // Initialize the SDK
        $AT  = new AfricasTalking($username, $apiKey);

        // Get the SMS service
        $sms        = $AT->sms();

        // Set the numbers you want to send to in international format
        $recipients = '+254'.ltrim($request->input('phone'), '0');

        // Set your message
        $message    = "You have been registered as a vendor on combine.co.ke. Your Username/Email is : {$request->email}. Vendor Code : VD.$user_id"."Terms & Conditions Apply";

        // Set your shortCode or senderId
        $from = "COMBINE";

        try {
            // Thats it, hit send and we'll take care of the rest
            $result = $sms->send([
                'to'      => $recipients,
                'from'=>$from,
                'message' => $message,
            ]);

            // return array($result);

        } catch (Exception $e) {
            echo "Error: ".$e->getMessage();
        }

        return redirect('/login')->with('success','Account Created Wait for verification');
    }

}
