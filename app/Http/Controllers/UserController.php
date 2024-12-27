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

        if ($user->usertype == 2) {
            $users = User::where('hospital_id', $user->hospital_id)->get();
        } else {
            $users = User::with('hospital')->get();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $users
            ]);
        }
        return view('users.index', compact('users'));
    }

    public function create(Request $request)
    {
        $user = null;
        $hospitals = Hospital::all();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Show create form'
            ]);
        }
        return view('users.form', compact('user', 'hospitals'));
    }

    public function store(Request $request)
    {
        $authUser = auth()->user();

        // Set default values for hospital operators
        if ($authUser->usertype == 2) {
            $request->merge([
                'usertype' => 2,
                'hospital_id' => $authUser->hospital_id
            ]);
        }

        $rules = [
            'username' => 'required|string|unique:users',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'usertype' => 'required|integer|in:1,2,3',
            'hospital_id' => 'required_if:usertype,2|exists:hospitals,id|nullable'
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

        if ($userData['usertype'] != 2) {
            $userData['hospital_id'] = null;
        }

        $user = User::create($userData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user
            ], 201);
        }
        return redirect()->route('users.index')->with('success', 'User created successfully');
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

        if ($authUser->usertype == 2 && $authUser->hospital_id != $user->hospital_id) {
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
        return view('users.show', compact('user'));
    }

    public function edit(Request $request, User $user)
    {
        $authUser = auth()->user();

        if ($authUser->usertype == 2 && $authUser->hospital_id != $user->hospital_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            return abort(403);
        }

        $hospitals = Hospital::all();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        }
        return view('users.form', compact('user', 'hospitals'));
    }

    public function update(Request $request, $id)
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

        if ($authUser->usertype == 2 && $authUser->hospital_id != $user->hospital_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            return abort(403);
        }

        $rules = [
            'username' => 'required|string|unique:users,username,' . $id,
            'email' => 'required|string|email|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
            'usertype' => 'required|integer|in:1,2,3',
            'hospital_id' => 'required_if:usertype,2|exists:hospitals,id|nullable'
        ];

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
        } else {
            $request->validate($rules);
        }

        $userData = $request->except('password');
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        if ($userData['usertype'] != 2) {
            $userData['hospital_id'] = null;
        }

        $user->update($userData);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        }
        return redirect()->route('users.index')->with('success', 'User updated successfully');
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

        if ($authUser->usertype == 2 && $authUser->hospital_id != $user->hospital_id) {
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
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
