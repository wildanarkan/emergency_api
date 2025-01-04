<?php

namespace App\Http\Controllers;

use App\Helper\ResponseFormatter;
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
            'name' => 'required|string',
            'email' => 'required|string|email|unique:user',
            'password' => 'required|string|min:6',
            'role' => 'required|integer|in:1,2,3',
            'hospital_admin_id' => 'required_if:role,2|exists:hospital,id|nullable'
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return ResponseFormatter::error('Validation failed', $validator->errors(), 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone ?? '',
            'hospital_admin_id' => $request->hospital_admin_id,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        if ($request->expectsJson()) {
            return ResponseFormatter::success('Registration successful', [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ], 201);
        }

        return redirect()->route('login')
            ->with('success', 'Registration successful! Please login.');
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
                return ResponseFormatter::error('Validation failed', $validator->errors(), 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            if ($request->expectsJson()) {
                return ResponseFormatter::error('Unauthorized', null, 401);
            }
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->withInput();
        }

        $user = User::where('email', $request->email)->firstOrFail();

        // Check role, and log out if role is not allowed
        if ($user->role == 3) {
            Auth::logout(); // Logout the user
            if ($request->expectsJson()) {
                return ResponseFormatter::error('Nurse cannot login', null, 403);
            }
            return back()
                ->withErrors(['role' => 'Nurses are not allowed to login through this interface.'])
                ->withInput();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        if ($request->expectsJson()) {
            return ResponseFormatter::success('Login successful', [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Login successful!');
    }


    public function loginApp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return ResponseFormatter::error('Validation failed', $validator->errors(), 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        if (!Auth::attempt($request->only('email', 'password'))) {
            if ($request->expectsJson()) {
                return ResponseFormatter::error('Unauthorized', null, 401);
            }
            return back()
                ->withErrors(['email' => 'The provided credentials do not match our records.'])
                ->withInput();
        }

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->role == 1 || $user->role == 2) {
            if ($request->expectsJson()) {
                return ResponseFormatter::error('User cannot login', null, 403);
            }
            return back()
                ->withErrors(['role' => 'Users are not allowed to login through this interface.'])
                ->withInput();
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        if ($request->expectsJson()) {
            return ResponseFormatter::success('Login successful', [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
            ]);
        }

        return redirect()->intended(route('dashboard'))
            ->with('success', 'Login successful!');
    }

    // Logout method
    public function logout(Request $request)
    {
        $user = request()->user();

        $user->tokens()->delete();
        // Revoke all tokens for the authenticated user
        $request->user()->tokens()->delete();

        // For web sessions, logout using Auth facade
        Auth::guard('web')->logout();

        // // Clear the session
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return ResponseFormatter::success('Successfully logged out');
        }

        return redirect()->route('login')
            ->with('success', 'Successfully logged out!');
    }
}
