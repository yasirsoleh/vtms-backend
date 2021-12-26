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
        return Camera::paginate();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!$request->user()->admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        $request->validate([
            'name' => ['required', Rule::unique('cameras'), 'max:255'],
            'traffic_direction' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $camera = Camera::create($request->all());

        $token = $camera->createToken('camera-access-token', ['camera'])->plainTextToken;
        $camera->plain_text_token = $token;
        $camera->save();

        return response($camera);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $camera = Camera::find($id);
        return response($camera);
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
        if (!$request->user()->admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        $camera = Camera::find($id);

        $request->validate([
            'name' => ['required', Rule::unique('cameras')->ignore($camera->id), 'max:255'],
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
    public function destroy(Request $request, $id)
    {
        if (!$request->user()->admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        return Camera::destroy($id);
    }

    public function search_name($name)
    {
        return Camera::where('name','like', '%'.$name.'%')->orWhere('mqtt_topic','like','%'.$name.'%')->get();
    }
}
