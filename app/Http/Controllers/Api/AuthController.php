<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // public function __construct() {
    //     $this->middleware('auth:api', ['except' => ['login', 'register']]);
    // }

    public function register(Request $request) {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8', 'confirmed']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed',
                'error' => $validator->errors(),
                'status' => 0,
            ], 400);
        } else {
            $validateData = $validator->Validated();
            $user = User::create(array_merge(
                $validateData,
                ['password' => bcrypt($request->password)],
            ));
            $access_token = $user->createToken('auth-token')->accessToken;

            return response()->json([
                'access_token' => $access_token,
                'user' => $user,
                'message' => 'Registered Successfuly',
                'status' => 1,
            ], 200);
        }
    }

    public function login(Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation Failed',
                'error' => $validator->errors(),
                'status' => 0,
            ], 400);
        } else {
            $validateData = $validator->Validated();
            $user = User::where(['email' => $validateData['email']])->first();
            if ($user && Hash::check($validateData['password'], $user->password)) {
                $access_token = $user->createToken('auth-token')->accessToken;
                return response()->json([
                    'access_token' => $access_token,
                    'user' => $user,
                    'message' => 'Logged In Successfuly',
                    'status' => 1,
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Invalid email or password',
                    'status' => 0,
                ], 401);
            }
        }
    }

    public function logout() {
        $user = auth()->user();
        if($user) {
            $user->token()->revoke();
            return response()->json([
                'message' => 'User logged out successfuly',
                'status' => 1
            ], 200);
        } else {
            return response()->json([
                'message' => 'User not authenticated',
                'status' => 0
            ], 401);
        }
    }

    public function refresh() {
        $this->createNewToken(auth()->refresh());
    }

    public function userProfile() {
        return response()->json(auth()->user());
    }

    public function createNewToken($token) {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'beater',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user(),
        ]);
    }
}
