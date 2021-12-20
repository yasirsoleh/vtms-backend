<?php

namespace App\Http\Controllers;

use App\Events\NewDetections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        if (!$request->user()->admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        $users = User::all();
        return response($users);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        event(new NewDetections($user));

        $token = $user->createToken('access-token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password) ) {
            return response([
                'message' => 'Bad credentials'
            ]);
        }
        $user->tokens()->delete();

        $token = $user->createToken('access-token')->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Logged Out'
        ]);
    }

    public function change_password(Request $request)
    {
        $request->validate([
            'password' => 'required|string|confirmed'
        ]);

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return response([
            'message' => 'Password Changed'
        ]);
    }
}
