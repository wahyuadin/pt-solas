<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthentifikasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwtAuth', ['except' => ['login','register']]);
    }

    public function login()
    {
        $credentials = request(['username', 'password']);

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'error' => true,
                'message' => 'Username or password is incorrect'
            ], 401);
        }

        return response()->json([
            'error' => false,
            'massage' => [
                'token' => $token,
                'user' => Auth::guard('api')->user(),
                'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            ], 200
        ]);
    }

    public function register() {
        $validator = Validator::make(request()->all(), [
            'nama'      => 'required',
            'email'     => 'required|email|unique:users',
            'username'  => 'required|unique:users',
            'password'  => 'required|confirmed',
        ]);

        $data = request()->except('_token','password_confirmation');
        $data['password'] = bcrypt($data['password']);
        if (!$validator->fails()) {
            User::create($data);
            return response()->json([
                'error' => false,
                'message' => "Data created successfully"], 201);
        }
        return response()->json(['error' => true, 'message' => $validator->messages()], 500);
    }

    public function me()
    {
        return response()->json(Auth::guard('api')->user());
    }

    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            'error' => false,
            'message' => 'Successfully logged out'
        ], 200);
    }

    public function refresh()
    {
        return $this->respondWithToken(Auth::guard('api')->refresh());
    }
}
