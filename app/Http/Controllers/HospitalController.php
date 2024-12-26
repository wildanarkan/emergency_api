<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HospitalController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->usertype == 2) { // Operator Hospital
            $hospitals = Hospital::where('id', $user->hospital_id)->get();
        } else {
            $hospitals = Hospital::all();
        }

        return response()->json([
            'success' => true,
            'data' => $hospitals
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:15'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $hospital = Hospital::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $hospital
        ], 201);
    }

    public function show($id)
    {
        $user = auth()->user();
        $hospital = Hospital::find($id);

        if (!$hospital) {
            return response()->json([
                'success' => false,
                'message' => 'Hospital not found'
            ], 404);
        }

        if ($user->usertype == 2 && $user->hospital_id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $hospital
        ]);
    }

    public function showUsers()
    {
        $user = auth()->user();

        if ($user->usertype == 2) {
            $hospital_id = $user->hospital_id;
            $hospital = Hospital::find($hospital_id);
            if (!$hospital) {
                return response()->json([
                    'success' => false,
                    'message' => 'Hospital not found'
                ], 404);
            }

            $users = User::where('hospital_id', $hospital_id)
                ->select('id', 'username', 'email', 'usertype', 'hospital_id')
                ->get();
        }
        // Jika system admin, bisa lihat semua
        else if ($user->usertype == 3) {
            $users = User::all();
        }
        // Untuk operator ambulance atau tipe lain
        else {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'users' => $users
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $hospital = Hospital::find($id);

        if (!$hospital) {
            return response()->json([
                'success' => false,
                'message' => 'Hospital not found'
            ], 404);
        }

        if ($user->usertype == 2 && $user->hospital_id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:15'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $hospital->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $hospital
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $hospital = Hospital::find($id);

        if (!$hospital) {
            return response()->json([
                'success' => false,
                'message' => 'Hospital not found'
            ], 404);
        }

        if ($user->usertype == 2 && $user->hospital_id != $id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $hospital->delete();

        return response()->json([
            'success' => true,
            'message' => 'Hospital deleted successfully'
        ]);
    }
}
