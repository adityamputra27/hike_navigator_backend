<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->where('role', 'HIKER')->first();

        if (!empty($user) && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            $setting = Setting::first();

            return response()->json([
                'status' => 200,
                'message' => 'Login success!',
                'token' => $token,
                'user' => $user,
                'setting' => $setting,
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

    public function authenticationByGoogle(Request $request)
    {
        $user = User::where('email', $request->email)->where('role', 'HIKER')->first();
        if (!empty($user)) {
            $newUser = new User;
            $newUser->username = 'user'.date('YmdHis');
            $newUser->name = $request->name;
            $newUser->email = $request->email;
            $newUser->role = 'HIKER';
            $newUser->status = 'ACTIVE';
            $newUser->avatar = 'default.png';
            $newUser->password = '';
            $newUser->address = '';
            $newUser->phone = '';
            $newUser->register_type = 'FIREBASE';
            $newUser->save();

            $token = $newUser->createToken('Personal Access Token')->plainTextToken;
            $setting = Setting::first();

            return response()->json([
                'status' => 200,
                'message' => 'Login success!',
                'token' => $token,
                'user' => $user,
                'setting' => $setting,
            ]);
        } else {
            $token = $user->createToken('Personal Access Token')->plainTextToken;
            $setting = Setting::first();

            return response()->json([
                'status' => 200,
                'message' => 'Login success!',
                'token' => $token,
                'user' => $user,
                'setting' => $setting,
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
                $user->username = 'user'.date('YmdHis');
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

    public function check(Request $request)
    {
        if ($request->email == '') {
            return response()->json([
                'status' => 500,
                'message' => "email can't be null"
            ]);
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status' => 500,
                'message' => "email not valid"
            ]);
        }

        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'status' => 404,
                'message' => "email not found"
            ]);
        }

        return response()->json([
            'status' => 200,
            'message' => 'email found'
        ]);
    }

    public function forgotPassword(Request $request)
    {
        if ($request->email == '') {
            return response()->json([
                'status' => 500,
                'message' => "email can't be null"
            ]);
        }

        if ($request->password == '' || $request->confirmation_password == '') {
            return response()->json([
                'status' => 500,
                'message' => "password can't be null"
            ]);
        }

        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'status' => 500,
                'message' => "email not valid"
            ]);
        }

        if ($request->confirmation_password != $request->password) {
            return response()->json([
                'status' => 500,
                'message' => "confirmation password not match with password"
            ]);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $token = $user->createToken('Personal Access Token')->plainTextToken;
        $user->save();

        return response()->json([
            'status' => 200,
            'message' => 'successfully reset password',
            'token' => $token
        ]);
    }
}
