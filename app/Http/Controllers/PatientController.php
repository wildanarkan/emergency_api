<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        if ($user->usertype == 2) { // Operator Hospital
            $patients = Patient::where('hospital_id', $user->hospital_id)->get();
        } else {
            $patients = Patient::all();
        }
        
        return response()->json([
            'success' => true,
            'data' => $patients
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hospital_id' => 'required|exists:hospitals,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients',
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = auth()->user();
        if ($user->usertype == 2 && $user->hospital_id != $request->hospital_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $patient = Patient::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $patient
        ], 201);
    }

    public function show($id)
    {
        $user = auth()->user();
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        if ($user->usertype == 2 && $user->hospital_id != $patient->hospital_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $patient
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        if ($user->usertype == 2 && $user->hospital_id != $patient->hospital_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'hospital_id' => 'required|exists:hospitals,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        if ($user->usertype == 2 && $user->hospital_id != $request->hospital_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $patient->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $patient
        ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        $patient = Patient::find($id);

        if (!$patient) {
            return response()->json([
                'success' => false,
                'message' => 'Patient not found'
            ], 404);
        }

        if ($user->usertype == 2 && $user->hospital_id != $patient->hospital_id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $patient->delete();

        return response()->json([
            'success' => true,
            'message' => 'Patient deleted successfully'
        ]);
    }
}