<?php

namespace App\Http\Controllers;

use App\Models\Detection;
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
        return Detection::orderByDesc('created_at')->cursorPaginate(15);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $camera = $request->user();
        $request->validate([
            //'camera_id' => 'exists:App\Models\Camera,id',
            'plate_number' => 'required|string',
        ]);

        $detection = Detection::create([
            'camera_id' => $camera->id,
            'plate_number' => $request->plate_number,
        ]);

        event(new NewDetections($detection));

        return $detection;
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
