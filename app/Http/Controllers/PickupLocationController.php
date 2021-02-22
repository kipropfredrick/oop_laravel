<?php

namespace App\Http\Controllers;

use App\PickupLocation;
use Illuminate\Http\Request;

class PickupLocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counties = \App\Counties::all();

        $locations = PickupLocation::with('county')->get();

        return view('backoffice.locations.view',compact('locations','counties'));
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
        $data =  $request->except('_token');

        PickupLocation::create($data);

        return back()->with('success','Pickup Location Saved');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PickupLocation  $pickupLocation
     * @return \Illuminate\Http\Response
     */
    public function show(PickupLocation $pickupLocation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PickupLocation  $pickupLocation
     * @return \Illuminate\Http\Response
     */
    public function edit(PickupLocation $pickupLocation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PickupLocation  $pickupLocation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PickupLocation $location)
    {
        $data = $request->except('_token','county_id');

        $location->update($data);

        return back()->with('success','Pickup Location Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PickupLocation  $pickupLocation
     * @return \Illuminate\Http\Response
     */
    public function destroy(PickupLocation $pickupLocation)
    {
        //
    }
}
