<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Detection;


class RouteController extends Controller
{
    public function index()
    {
        return Detection::distinct('plate_number')->paginate(10);
    }

    public function show($plate_number)
    {
        $detections = Detection::where('plate_number', $plate_number)->with('camera')->get();
        return $detections;
    }
}
