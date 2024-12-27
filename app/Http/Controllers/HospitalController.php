<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HospitalController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->usertype == 2) { // Operator Hospital
            $hospitals = Hospital::where('id', $user->hospital_id)->get();
        } else {
            $hospitals = Hospital::all();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $hospitals
            ]);
        }
        return view('hospitals.index', compact('hospitals'));
    }

    public function create(Request $request)
    {
        $hospital = null; // Set to null for create form

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Show create form'
            ]);
        }
        return view('hospitals.form', compact('hospital'));
    }


    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:15'
        ];

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $hospital = Hospital::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $hospital
            ], 201);
        }

        $request->validate($rules);
        Hospital::create($request->all());
        return redirect()->route('hospitals.index')->with('success', 'Hospital created successfully');
    }

    public function show(Request $request, $id)
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

        if ($user->usertype == 2 && $user->hospital_id != $id) {
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
        return view('hospitals.show', compact('hospital'));
    }

    public function edit(Hospital $hospital)
    {
        return view('hospitals.form', compact('hospital'));
    }

    public function update(Request $request, $id)
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

        if ($user->usertype == 2 && $user->hospital_id != $id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            return abort(403);
        }

        $rules = [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:15'
        ];

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $hospital->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $hospital
            ]);
        }

        $request->validate($rules);
        $hospital->update($request->all());
        return redirect()->route('hospitals.index')->with('success', 'Hospital updated successfully');
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

        if ($user->usertype == 2 && $user->hospital_id != $id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            return abort(403);
        }

        $hospital->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Hospital deleted successfully'
            ]);
        }
        return redirect()->route('hospitals.index')->with('success', 'Hospital deleted successfully');
    }

    public function showUsers(Request $request)
    {
        $user = auth()->user();

        if ($user->usertype == 2) {
            $hospital_id = $user->hospital_id;
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

            $users = User::where('hospital_id', $hospital_id)
                ->select('id', 'username', 'email', 'usertype', 'hospital_id')
                ->get();
        }
        // Jika Operator System, bisa lihat semua
        else if ($user->usertype == 3) {
            $users = User::all();
        }
        // Untuk operator ambulance atau tipe lain
        else {
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
                    'users' => $users
                ]
            ]);
        }
        return view('hospitals.users', compact('users'));
    }
}
