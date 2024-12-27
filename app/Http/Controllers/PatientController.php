<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->usertype == 2) { // Operator Hospital
            $patients = Patient::where('hospital_id', $user->hospital_id)->get();
        } else {
            $patients = Patient::all();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $patients
            ]);
        }
        return view('patients.index', compact('patients'));
    }

    public function create(Request $request)
    {
        $patient = null;
        $hospitals = Hospital::all();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Show create form'
            ]);
        }
        return view('patients.form', compact('patient', 'hospitals'));
    }

    public function store(Request $request)
    {
        $rules = [
            'hospital_id' => 'required|exists:hospitals,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients',
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10'
        ];

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
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

        $request->validate($rules);
        Patient::create($request->all());
        return redirect()->route('patients.index')->with('success', 'Patient created successfully');
    }

    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $patient = Patient::find($id);

        if (!$patient) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found'
                ], 404);
            }
            return abort(404);
        }

        if ($user->usertype == 2 && $user->hospital_id != $patient->hospital_id) {
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
                'data' => $patient
            ]);
        }
        return view('patients.show', compact('patient'));
    }

    public function edit(Request $request, Patient $patient)
    {
        $user = auth()->user();
        
        if ($user->usertype == 2 && $user->hospital_id != $patient->hospital_id) {
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
                'data' => $patient
            ]);
        }
        return view('patients.form', compact('patient', 'hospitals'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $patient = Patient::find($id);

        if (!$patient) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found'
                ], 404);
            }
            return abort(404);
        }

        if ($user->usertype == 2 && $user->hospital_id != $patient->hospital_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            return abort(403);
        }

        $rules = [
            'hospital_id' => 'required|exists:hospitals,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:patients,email,' . $id,
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|max:10'
        ];

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
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

        $request->validate($rules);
        $patient->update($request->all());
        return redirect()->route('patients.index')->with('success', 'Patient updated successfully');
    }

    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        $patient = Patient::find($id);

        if (!$patient) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found'
                ], 404);
            }
            return abort(404);
        }

        if ($user->usertype == 2 && $user->hospital_id != $patient->hospital_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            return abort(403);
        }

        $patient->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient deleted successfully'
            ]);
        }
        return redirect()->route('patients.index')->with('success', 'Patient deleted successfully');
    }
}