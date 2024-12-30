<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role == 2) { // Hospital Admin
            $hospital = Hospital::where('user_id', $user->id)->first();
            if ($hospital) {
                $users = User::whereHas('patients', function ($query) use ($hospital) {
                    $query->where('hospital_id', $hospital->id);
                })->get();
            } else {
                $users = collect();
            }
            $hospitals = collect([$hospital]); // Wrap single hospital in collection
        } else {
            $users = User::all();
            $hospitals = Hospital::all();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        }
        return view('user.index', compact('users', 'hospitals'));
    }

    public function create(Request $request)
    {
        $user = null;
        $hospital = Hospital::all();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Show create form'
            ]);
        }
        return view('user.form', compact('user', 'hospital'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        $rules = [
            'email' => 'required|string|email|unique:user',
            'password' => 'required|string|min:6',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'role' => 'required|string|max:15',
        ];

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
        } else {
            $request->validate($rules);
        }

        $userData = $request->all();
        $userData['password'] = Hash::make($request->password);

        $user = User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'phone' => $request->phone,
            'role' => 3,
        ]);
        

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user
            ], 201);
        }
        return redirect()->route('user.index')->with('success', 'User created successfully');
    }

    public function show(Request $request, $id)
    {
        $authUser = auth()->user();
        $user = User::find($id);

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            return abort(404);
        }

        if ($authUser->role == 2 && $authUser->user_id != $user->user_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            return abort(403);
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        }
        return view('user.show', compact('user'));
    }

    public function edit(Request $request, $id)
    {
        $authUser = auth()->user();
        $user = User::find($id);

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            return abort(404);
        }

        if ($authUser->role == 2 && $authUser->user_id != $user->user_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            return abort(403);
        }

        $hospital = Hospital::all();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        }
        return view('user.form', compact('user', 'hospital'));
    }


    public function update(Request $request, $id)
    {
        $authUser = auth()->user();
        $user = User::findOrFail($id);

        // Validasi input
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:user,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'password' => 'nullable|string|min:6',
        ];

        // Tambahkan validasi role hanya untuk System Admin
        if ($authUser->role == 1) {
            $rules['role'] = 'required|integer|in:1,2,3';
            $rules['user_id'] = 'nullable|exists:hospital,admin_id';
        } else {
            $request->merge(['role' => $user->role]); // Tetapkan role saat ini
            $request->merge(['user_id' => $user->user_id]); // Tetapkan hospital saat ini
        }

        $validatedData = $request->validate($rules);

        // Update data pengguna
        $userData = $request->except('password');

        // Perbarui password jika diisi
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('user.index')->with('success', 'User updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $authUser = auth()->user();
        $user = User::find($id);

        if (!$user) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }
            return abort(404);
        }

        if ($authUser->role == 2 && $authUser->user_id != $user->user_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            return abort(403);
        }

        $user->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        }
        return redirect()->route('user.index')->with('success', 'User deleted successfully');
    }
}
