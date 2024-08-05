<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try {
            $validation = Validator::make($request->all(), [
                'name' => 'required|min:6|max:30|unique:users',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'image' => 'file|image|nullable',
                'password_confirmation' => 'same:password'
            ]);

            if ($validation->fails()) {
                return response()->json(['errors' => $validation->errors()], 422);
            }

            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $fileName = date('YmdHis') . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $path = 'images/vendors';
                $image->move(public_path($path), $fileName);
                $user->image = url('/') . '/' . $path . '/' . $fileName;
            }

            $user->save();

            return $user ;
        } catch (\Exception $e) {
            return response()->json([
                'erorr' => $e->getMessage()
            ], 400);
        }
    }


    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::where('email', $request->email)->firstOrFail();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }


            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'data' => $user,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function logout(Request $request)
    {
        try {
            if (Auth::check()) {
                $user = Auth::user();
                $request->user()->tokens()->delete();
                return response()->json(['message' => 'Logged out successfully'], 200);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }
}
