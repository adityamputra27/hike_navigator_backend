<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->where('role', 'HIKER')->first();

        if (!empty($user) && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            return response()->json([
                'status' => 200,
                'token' => $token,
                'user' => $user,
                'message' => 'Login success!',
            ]);
        } else if (empty($user)) {
            return response()->json([
                'status' => 500,
                'message' => 'Akun tidak ditemukan!',
            ]);
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Email atau Password salah!',
            ]);
        }
    }

    public function register(Request $request)
    {
        $exists = User::where('email', $request->email)->first();
        try {
            if (!empty($exists)) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Email already exists!',
                ]);
            } else {
                $user = new User;
                $user->username = '';
                $user->name = $request->name;
                $user->email = $request->email;
                $user->role = 'HIKER';
                $user->status = 'ACTIVE';
                $user->avatar = 'default.png';
                $user->password = Hash::make($request->password);
                $user->address = '';
                $user->phone = '';
                $user->save();

                return response()->json([
                    'status' => 200,
                    'message' => 'Register success!',
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e,
            ]);
        }
    }

    public function forgotPassword(Request $request)
    {

    }
}
