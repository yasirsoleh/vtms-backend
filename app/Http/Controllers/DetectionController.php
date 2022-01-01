<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use App\Events\NewDetections;

class DetectionController extends Controller
{
    public function index()
    {
        return Detection::orderByDesc('created_at')->cursorPaginate();
    }

    public function store(Request $request)
    {
        $camera = $request->user();

        if ($camera->tokenCan('camera')) {
            $request->validate([
                'plate_number' => 'required|string',
            ]);

            $detection = Detection::create([
                'camera_id' => $camera->id,
                'plate_number' => $request->plate_number,
            ]);

            event(new NewDetections($detection));
            return response();
        }

        return response([
            'message' => 'Invalid Token'
        ], 403);
    }

    public function show($id)
    {
        $detection = Detection::where('id', $id)->with('camera')->get();

        if ($detection->isEmpty()) {
            return response([
                'message' => 'Not Found'
            ], 404);
        }

        return response([
            'detection' => $detection->first()
        ]);
    }

    public function search_plate_numbers($plate_number)
    {
        return Detection::where('plate_number','like', '%'.$plate_number.'%')->cursorPaginate();
    }

    public function plate_numbers()
    {
        return Detection::distinct('plate_number')->cursorPaginate();
    }

    public function show_plate_numbers($plate_number)
    {
        return Detection::where('plate_number', $plate_number)->with('camera')->get();
    }
}
