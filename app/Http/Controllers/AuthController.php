<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // Register method
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'usertype' => 'required|integer|in:1,2,3',
            'hospital_id' => 'required_if:usertype,2|exists:hospitals,id|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'usertype' => $request->usertype,
            'hospital_id' => $request->hospital_id,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Registration successful',
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
        ], 201);
    }


    // Login method
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json($validator->errors(), 422);
            } else {
                return back()->withErrors($validator)->withInput();
            }
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Unauthorized'], 401);
            } else {
                return back()->withErrors(['email' => 'The provided credentials do not match our records.'])->withInput();
            }
        }

        $user = User::where('email', $request->email)->firstOrFail();
        $token = $user->createToken('auth_token')->plainTextToken;

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Login success',
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        } else {
            return redirect()->intended(route('dashboard'))->with('message', 'Login successful');
        }
    }


    // Logout method
    public function logout(Request $request)
    {
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();

        // For web sessions, logout using Auth facade
        Auth::guard('web')->logout();

        // Clear the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        } else {
            return redirect()->route('login')->with('message', 'Successfully logged out');
        }
    }
}
