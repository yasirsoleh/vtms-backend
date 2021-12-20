<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use App\Models\Camera;
use Illuminate\Http\Request;
use App\Events\NewDetections;

class DetectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Detection::all();
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
        $request->validate([
            'camera_id' => 'exists:App\Models\Camera,id',
            'plate_number' => 'required|string',
        ]);

        $detection = Detection::create($request->all());

        event(new NewDetections($detection));

        return $detection;
    }

    public function store_from_mqtt($mqtt_topic, $data)
    {
        $camera = Camera::firstWhere('mqtt_topic', $mqtt_topic);
        $detection = Detection::create([
            'camera_id' => $camera->id,
            'plate_number' => $data->plate_number,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Detection::find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Detection::destroy($id);
    }

    /**
     * Search for plate number.
     *
     * @param  str  $name
     * @return \Illuminate\Http\Response
     */
    public function search_plate_number($plate_number)
    {
        return Detection::where('plate_number','like', '%'.$plate_number.'%')->get();
    }
}
