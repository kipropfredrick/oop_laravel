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
        return view('home');
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
