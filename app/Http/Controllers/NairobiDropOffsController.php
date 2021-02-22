<?php

namespace App\Http\Controllers;

use App\NairobiDropOffs;
use Illuminate\Http\Request;

class NairobiDropOffsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $zones = \App\NairobiZones::all();

        $dropoffs = NairobiDropOffs::with('zone')->get();

        return view('backoffice.dropoffs.view',compact('dropoffs','zones'));

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
       
        $data =  $request->except('_token');

        NairobiDropOffs::create($data);

        return back()->with('success','Drop off Location Saved');
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NairobiDropOffs  $dropoff
     * @return \Illuminate\Http\Response
     */
    public function show(NairobiDropOffs $dropoff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NairobiDropOffs  $dropoff
     * @return \Illuminate\Http\Response
     */
    public function edit(NairobiDropOffs $dropoff)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NairobiDropOffs  $dropoff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('_token','zone_id');

        NairobiDropOffs::where('id',$id)->update($data);

        return back()->with('success','Dropoff Location Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NairobiDropOffs  $dropoff
     * @return \Illuminate\Http\Response
     */
    public function destroy(NairobiDropOffs $dropoff)
    {
        $dropoff->update(['status'=>'active']);
        return back()->with('success','Dropoff Deleted');
    }
}
