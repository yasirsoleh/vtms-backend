<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        return $user;
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
                'message' => 'Bad Credentials'
            ], 403);
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
        $user = $request->user();
        $request->validate([
            'name' => 'required|string',
            'username' => ['required', Rule::unique('users')->ignore($user->id),'max:255']
        ]);

        $user->update([
            'name' => $request->name,
            'username' => $request->username
        ]);

        return response([
            'message' => 'User Updated',
            'user' => $user
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
            'password' => ['required', 'confirmed', Password::min(8)]
        ]);

        $user = $request->user();
        $user->password = bcrypt($request->password);
        $user->save();

        return response([
            'message' => 'Password Changed'
        ]);
    }

    public function revoke_tokens(Request $request)
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

        return User::paginate();
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

        return response([
            'message' => 'User Created',
            'user' => $user
        ]);
    }

    public function admin_delete_user(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        if ($id == $request->user()->id) {
            return response([
                'message' => 'Cannot Delete Yourself'
            ], 403);
        }

        if (User::destroy($id)) {
            return response([
                'message' => 'User Deleted'
            ], 200);
        }

        return response([
            'message' => 'Not Found'
        ], 404);
    }

    public function admin_revoke_user_tokens(Request $request, $id)
    {
        if (!$request->user()->is_admin) {
            return response([
                'message' => 'Not Admin'
            ], 403);
        }

        $user = User::find($id);
        if (!$user) {
            return response([
                'message' => 'Not Found'
            ], 404);
        }

        if ($user->tokens()->delete()) {
            return response([
                'message' => 'Tokens Deleted'
            ]);
        }

        return response([
            'message' => 'No Tokens Found'
        ], 404);
    }
}
