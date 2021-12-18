<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Camera;
use Illuminate\Validation\Rule;

class CameraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Camera::all();
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
            'name' => 'required|string|max:255',
            'mqtt_topic' => 'required|unique:cameras|max:30',
            'traffic_direction' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $camera = Camera::create($request->all());

        return $camera;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Camera::find($id);
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
        $camera = Camera::find($id);

        $request->validate([
            'name' => ['required', Rule::unique('cameras')->ignore($camera->id),'max:255'],
            'mqtt_topic' => ['required', Rule::unique('cameras')->ignore($camera->id),'max:30'],
            'traffic_direction' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $camera->update($request->all());
        return $camera;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Camera::destroy($id);
    }

    public function search_name($name)
    {
        return Camera::where('name','like', '%'.$name.'%')->orWhere('mqtt_topic','like','%'.$name.'%')->get();
    }
}
