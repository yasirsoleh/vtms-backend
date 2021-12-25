<?php

use App\Http\Controllers\CameraController;
use App\Http\Controllers\DetectionController;
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

//authenticated route for user
Route::middleware(['auth:sanctum', 'abilities:user'])->group(function () {
    //Cameras
    Route::get('/cameras', [CameraController::class, 'index']);
    Route::get('/cameras/{camera}', [CameraController::class, 'show']);
    Route::get('/cameras/search/{name}', [CameraController::class, 'search_name']);

    //Detections
    Route::get('/detections', [DetectionController::class, 'index']);
    Route::get('/detections/{detection}', [DetectionController::class, 'show']);
    Route::delete('/detections/{detection}', [DetectionController::class, 'destroy']);
    Route::get('/detections/search/{plate_number}', [DetectionController::class, 'search_plate_number']);

    //User
    Route::post('/user/change_password', [UserController::class, 'change_password']);
    Route::get('/user/logout',[UserController::class, 'logout']);
    Route::get('/user',[UserController::class, 'index']);

    //User manage token
    Route::get('/user/revoke_tokens',[UserController::class, 'user_revoke_all_tokens']);
});

Route::middleware(['auth:sanctum', 'abilities:admin'])->group(function (){
    //Admin Manage Cameras
    Route::post('/cameras', [CameraController::class, 'store']);
    Route::delete('/cameras/{camera}', [CameraController::class, 'destroy']);
    Route::put('/cameras/{camera}', [CameraController::class, 'update']);

    //Admin Manage Users
    Route::get('/admin/users/{user}/revoke_token', [UserController::class, 'admin_revoke_users_token']);
    Route::delete('/admin/users/{user}', [UserController::class, 'admin_delete_users']);
    Route::post('/admin/users/', [UserController::class, 'admin_create_users']);
});

//Only camera can poauthenticatedst detections
Route::middleware(['auth:sanctum', 'abilities:camera'])->group(function () {
    Route::post('/detections', [DetectionController::class, 'store']);
});

//User-not authenticated
Route::post('/user/login', [UserController::class, 'login']);
