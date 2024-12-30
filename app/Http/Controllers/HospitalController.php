<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role == 2) { // Hospital Admin
            // $hospital = Hospital::where('user_id', $user->id)->get();
            return redirect()->route('patient.index');
        } else {
            $hospital = Hospital::all();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $hospital
            ]);
        }
        return view('hospital.index', compact('hospital'));
    }

    public function create(Request $request)
    {
        $hospital = null;

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Show create form'
            ]);
        }

        if (auth()->user()->role == 2) {
            // return redirect('patient.index', compact('hospital'));
            return redirect()->route('patient.index');

        }
        return view('hospital.form', compact('hospital'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string',
            'address' => 'required|string',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|unique:user,email',
            'admin_phone' => 'required|string',
            'admin_password' => 'required|min:6'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($request->expectsJson() && $validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        DB::beginTransaction();
        try {
            // Create admin user
            $admin = User::create([
                'name' => $request->admin_name,
                'email' => $request->admin_email,
                'phone' => $request->admin_phone,
                'password' => Hash::make($request->admin_password),
                'role' => 2 // hospital admin role
            ]);

            // Create hospital
            $hospital = Hospital::create([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address,
                'user_id' => $admin->id
            ]);

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => $hospital
                ], 201);
            }

            return redirect()->route('hospital.index')->with('success', 'Hospital created successfully');
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create hospital. ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to create hospital. ' . $e->getMessage());
        }
    }


    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $hospital = Hospital::with('admin')->find($id);

        if (!$hospital) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hospital not found'
                ], 404);
            }
            return abort(404);
        }

        if ($user->role == 2 && $user->user_id != $id) {
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
                'data' => $hospital
            ]);
        }
        return view('hospital.show', compact('hospital'));
    }

    public function edit(Hospital $hospital)
    {
        $hospital->load('admin');
        return view('hospital.form', compact('hospital'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $hospital = Hospital::with('admin')->find($id);

        if (!$hospital) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hospital not found'
                ], 404);
            }
            return abort(404);
        }

        if ($user->role == 2 && $user->user_id != $id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized (cant change someone else hospital)'
                ], 403);
            }
            return abort(403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
        ];

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            DB::beginTransaction();
            try {
                $hospital->update([
                    'name' => $request->name,
                    'address' => $request->address,
                    'phone' => $request->phone
                ]);

                DB::commit();

                return response()->json([
                    'success' => true,
                    'data' => $hospital
                ]);
            } catch (\Exception $e) {
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update hospital. ' . $e->getMessage()
                ], 500);
            }
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $hospital->update([
                'name' => $request->name,
                'phone' => $request->phone,
                'address' => $request->address
            ]);

            DB::commit();
            return redirect()->route('hospital.index')->with('success', 'Hospital updated successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Failed to update hospital. ' . $e->getMessage());
        }
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        $hospital = Hospital::find($id);

        if (!$hospital) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hospital not found'
                ], 404);
            }
            return abort(404);
        }

        if ($user->role == 2 && $user->user_id != $id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized (cant delete someone else hospital)'
                ], 403);
            }
            return abort(403);
        }

        DB::beginTransaction();
        try {
            // Delete the admin user first
            $admin = User::find($hospital->admin_id);
            if ($admin) {
                $admin->delete();
            }

            // Delete the hospital
            $hospital->delete();

            DB::commit();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Hospital and admin deleted successfully'
                ]);
            }
            return redirect()->route('hospital.index')->with('success', 'Hospital and admin deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete hospital. ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to delete hospital. ' . $e->getMessage());
        }
    }

    public function showUsers(Request $request)
    {
        $user = auth()->user();

        if ($user->role == 2) {
            $hospital_id = $user->user_id;
            $hospital = Hospital::find($hospital_id);
            if (!$hospital) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Hospital not found'
                    ], 404);
                }
                return abort(404);
            }

            $users = User::where('user_id', $hospital_id)
                ->select('id', 'name', 'email', 'role', 'user_id')
                ->get();
        } else if ($user->role == 1) {
            $users = User::all();
        } else {
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
                'data' => [
                    'user' => $users
                ]
            ]);
        }
        return view('hospital.user', compact('users'));
    }
}
