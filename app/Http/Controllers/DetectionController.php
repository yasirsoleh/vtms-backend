<?php

namespace App\Http\Controllers;

use App\Models\Detection;
use Illuminate\Http\Request;
use App\Events\NewDetections;
use Carbon\Carbon;

class DetectionController extends Controller
{
    public function index()
    {
        //return Detection::orderByDesc('created_at')->cursorPaginate();
        return Detection::orderByDesc('id')->cursorPaginate();
    }

    public function store(Request $request)
    {
        $camera = $request->user();

        if ($camera->tokenCan('camera')) {
            $request->validate([
                'plate_number' => 'required|string',
            ]);
            $stillNew = $camera->detections()->where('plate_number', $request->plate_number)->get()->last();
            if ($stillNew != null) {
                $stillNew = $stillNew['created_at'];
                $stillNew = Carbon::parse($stillNew);
                $stillNew = $stillNew->greaterThan(Carbon::now()->subSeconds(10));
            }
            if (!$stillNew) {
                $detection = Detection::create([
                    'camera_id' => $camera->id,
                    'plate_number' => $request->plate_number,
                ]);
                event(new NewDetections($detection));
                return response([
                    'message' => 'Detection Created',
                ]);
            }

            return response([
                'message' => 'Detection too soon',
            ], 403);
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

    public function search_detection($detection)
    {
        return Detection::where('plate_number','like', '%'.$detection.'%')->get();
    }

    public function search_plate_numbers($plate_number)
    {
        return Detection::distinct()->where('plate_number','like', '%'.$plate_number.'%')->pluck('plate_number');
    }

    public function plate_numbers()
    {
        return Detection::distinct()->orderByDesc('created_at')->pluck('plate_number');
    }

    public function show_plate_numbers($plate_number)
    {
        $detections = Detection::where('plate_number', $plate_number)->with('camera')->get();
        if (count($detections) == 0) {
            return response([
                'message' => 'Not Found'
            ], 404);
        }
        return $detections;
    }
}
