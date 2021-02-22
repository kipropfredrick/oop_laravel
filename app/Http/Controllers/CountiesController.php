<?php

namespace App\Http\Controllers;

use App\Counties;
use Illuminate\Http\Request;

class CountiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counties = Counties::all();
        return view('backoffice.counties.index',compact('counties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Counties::create(['county_name'=>$request->county_name]);

        return back()->with('success','County Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Counties  $counties
     * @return \Illuminate\Http\Response
     */
    public function show(Counties $county)
    {
        $pickuplocations = \App\PickupLocation::where('county_id','=',$county->id)->get();
        return view('backoffice.counties.view',compact('county','pickuplocations'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Counties  $counties
     * @return \Illuminate\Http\Response
     */
    public function edit(Counties $counties)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Counties  $counties
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Counties $county)
    {
        $data = $request->except('_token');
        $county->update($data);
        return back()->with('success','County Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Counties  $counties
     * @return \Illuminate\Http\Response
     */
    public function destroy(Counties $counties)
    {
        //
    }
}
