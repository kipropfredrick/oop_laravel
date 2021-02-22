<?php

namespace App\Http\Controllers;

use App\NairobiZones;
use Illuminate\Http\Request;
use App\NairobiDropOffs;

class NairobiZonesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $zones = NairobiZones::all();
        return view('backoffice.nairobizones.index',compact('zones'));
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
        $data = $request->except('_token');

        NairobiZones::create($data);

        return back()->with('success','Zone Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\NairobiZones  $zone
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $zone = NairobiZones::with('dropoffs')->where('id','=',$id)->first();
        $zones = NairobiZones::all();
        return view('backoffice.nairobizones.view',compact('zone','zones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\NairobiZones  $zone
     * @return \Illuminate\Http\Response
     */
    public function edit(NairobiZones $zone)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\NairobiZones  $zone
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->except('_token');
        NairobiZones::where('id','=',$id)->update($data);
        return back()->with('success','Zone Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\NairobiZones  $zone
     * @return \Illuminate\Http\Response
     */
    public function destroy(NairobiZones $zone)
    {
        $zone->update(['status'=>'active']);
        return back()->with('success','Zone Deleted');
    }
}
