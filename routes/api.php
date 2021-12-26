<?php

use App\Http\Controllers\CameraController;
use App\Http\Controllers\DetectionController;
use App\Http\Controllers\RouteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/users/login', [UserController::class, 'login']);

Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
    Route::get('/users/logout',[UserController::class, 'logout']);
    Route::get('/users',[UserController::class, 'index']);
    Route::put('/users',[UserController::class, 'update']);
    Route::get('/users/revoke_tokens',[UserController::class, 'revoke_tokens']);
    Route::post('/users/change_password', [UserController::class, 'change_password']);

    Route::get('/cameras', [CameraController::class, 'index']);
    Route::get('/cameras/{camera}', [CameraController::class, 'show']);
    Route::get('/cameras/search/{name}', [CameraController::class, 'search_name']);

    Route::get('/detections', [DetectionController::class, 'index']);
    Route::get('/detections/{detection}', [DetectionController::class, 'show']);
    Route::get('/detections/plate_numbers',[DetectionController::class, 'plate_numbers']);
    Route::get('/detections/plate_numbers/{plate_number}',[DetectionController::class, 'show_plate_numbers']);
    Route::get('/detections/plate_numbers/search/{plate_number}', [DetectionController::class, 'search_plate_numbers']);
});

Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function (){
    Route::get('/users/list', [UserController::class, 'admin_list_users']);
    Route::get('/users/{user}/revoke_token', [UserController::class, 'admin_revoke_users_token']);
    Route::delete('/users/{user}', [UserController::class, 'admin_delete_users']);
    Route::post('/users', [UserController::class, 'admin_create_users']);

    Route::post('/cameras', [CameraController::class, 'store']);
    Route::delete('/cameras/{camera}', [CameraController::class, 'destroy']);
    Route::put('/cameras/{camera}', [CameraController::class, 'update']);
});

Route::middleware(['auth:sanctum', 'abilities:camera'])->group(function () {
    Route::post('/detections', [DetectionController::class, 'store']);
});
