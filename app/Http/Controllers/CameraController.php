<?php

namespace App\Http\Controllers;

use App\Http\Resources\CameraResource;
use Illuminate\Http\Request;
use App\Models\Camera;
use Illuminate\Validation\Rule;

class CameraController extends Controller
{
    public function index()
    {
        return CameraResource::collection(Camera::paginate());
    }

    public function store(Request $request)
    {
        if (!$request->user()->is_admin) {
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

        return response([
            'message' => 'Camera Created',
            'camera' => $camera
        ]);
    }

    public function show(Request $request, $id)
    {
        $camera = Camera::find($id);
        if (!$camera) {
            return response([
                'message' => 'Not Found'
            ], 404);
        }

        if (!$request->user()->is_admin) {
            return response([
                'camera' => $camera
            ]);
        }

        return response([
            'camera' => [
                'id' => $camera->id,
                'name' => $camera->name,
                'plain_text_token' => $camera->plain_text_token,
                'traffic_direction' => $camera->traffic_direction,
                'latitude' => $camera->latitude,
                'longitude' => $camera->longitude,
                'created_at' => $camera->created_at,
                'updated_at' => $camera->updated_at
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        $camera = Camera::find($id);

        if (!$camera) {
            return response([
                'message' => 'Not Found'
            ], 404);
        }

        $request->validate([
            'name' => ['required', Rule::unique('cameras')->ignore($camera->id), 'max:255'],
            'traffic_direction' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $camera->update($request->all());

        return response([
            'message' => 'Camera Updated',
            'camera' => $camera
        ]);
    }

    public function destroy(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        if (Camera::destroy($id)) {
            return response([
                'message' => 'Camera Deleted'
            ]);
        }

        return response([
            'message' => 'Not Found'
        ], 404);
    }

    public function search_name($name)
    {
        return Camera::where('name','like', '%'.$name.'%')->get();
    }
}
