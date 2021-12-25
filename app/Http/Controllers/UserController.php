<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Password;

class UserController extends Controller
{
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $user = $request->user();
        return response($user);
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|max:255',
            'password' => 'required|string'
        ]);

        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password) ) {
            return response([
                'message' => 'Bad credentials'
            ]);
        }
        $user->tokens()->delete();

        $token = $user->is_admin ? $user->createToken('access-token', ['user', 'admin'])->plainTextToken : $user->createToken('access-token', ['user'])->plainTextToken;

        return response([
            'user' => $user,
            'token' => $token
        ]);
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $request->validate([
            'name' => 'required|string',
            'username' => ['required', Rule::unique('cameras')->ignore($user->id),'max:255'],
            'password' => 'required|string|confirmed'
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

    public function user_revoke_all_tokens(Request $request)
    {
        $request->user()->tokens()->delete();

        return response([
            'message' => 'Tokens Deleted'
        ]);
    }

    public function admin_list_users(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        $users = User::all();
        return response($users);
    }

    public function admin_create_users(Request $request)
    {
        if (!$request->user()->is_admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        $request->validate([
            'name' => 'required|string',
            'username' => 'required|max:255|unique:users',
            'password' => ['required', 'string', 'confirmed', Password::min(8)]
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);

        return response($user);
    }

    public function admin_delete_users(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        if ($id == $request->user()->id) {
            return response([
                'message' => 'Cannot delete yourself'
            ], 403);
        }

        return User::destroy($id);
    }

    public function admin_revoke_users_token(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        $user = User::find($id);
        return $user->tokens()->delete();
    }

}
