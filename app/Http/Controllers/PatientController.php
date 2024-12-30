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

        if ($user->role == 2) { // Hospital Admin
            $hospital = Hospital::where('user_id', $user->id)->first();

            // Filter patient berdasarkan hospital_id
            $patient = Patient::where('hospital_id', $hospital->id)->get();
        } else {
            $patient = Patient::all();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $patient
            ]);
        }
        return view('patient.index', compact('patient'));
    }

    public function create(Request $request)
    {
        $patient = null;
        $hospital = Hospital::all();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Show create form'
            ]);
        }
        return view('patient.form', compact('patient', 'hospital'));
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|integer|in:1,2',
            'case' => 'required|integer|in:1,2',
            'desc' => 'required|string',
            'arrival' => 'required|date',
            'hospital_id' => 'nullable|exists:hospital,id',
            'status' => 'required|integer|in:1,2,3', // 1:menuju lokasi / 2:rujukan / 3:selesai
        ];

        $request->merge(['user_id' => auth()->id()]);

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = auth()->user();
            if ($user->role == 2) {
                $hospital = Hospital::where('user_id', $user->id)->first();
                if ($request->filled('hospital_id') && $hospital && $hospital->id != $request->hospital_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized: Cannot create patient for another hospital',
                    ], 403);
                }
            }

            $patient = Patient::create($request->all());
            return response()->json([
                'success' => true,
                'data' => $patient,
            ], 201);
        }

        $request->validate($rules);
        Patient::create($request->all());
        return redirect()->route('patient.index')->with('success', 'Patient created successfully');
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

        if ($user->role == 2 && $user->user_id != $patient->hospital_id) {
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
        return view('patient.show', compact('patient'));
    }

    public function edit(Request $request, Patient $patient)
    {
        $user = auth()->user();

        if ($user->role == 2 && $user->hospital_id != $patient->hospital_id) {
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
                'data' => $patient
            ]);
        }
        return view('patient.form', compact('patient', 'hospital'));
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $patient = Patient::find($id);

        // 1. Check if patient exists
        if (!$patient) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found'
                ], 404);
            }
            return abort(404);
        }

        // 2. Authorization check for role 2
        if ($user->role == 2 && $user->user_id != $patient->hospital_id) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 403);
            }
            return abort(403);
        }

        // 3. Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|integer|in:1,2',
            'case' => 'required|integer|in:1,2',
            'desc' => 'required|string',
            'arrival' => 'required|date',
            'hospital_id' => 'nullable|exists:hospital,id',
            'status' => 'required|integer'
        ];

        // 4. Handle JSON request
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // Update arrival time to correct format
            $request->merge(['arrival' => date('Y-m-d H:i:s', strtotime($request->arrival))]);

            // Update patient
            $patient->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $patient
            ]);
        }

        // 5. Handle form request
        $validatedData = $request->validate($rules);

        // Update arrival time to correct format
        $validatedData['arrival'] = date('Y-m-d H:i:s', strtotime($validatedData['arrival']));

        // Update patient
        $patient->update($validatedData);

        return redirect()->route('patient.index')->with('success', 'Patient updated successfully');
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

        if ($user->role == 2 && $user->user_id != $patient->hospital_id) {
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
        return redirect()->route('patient.index')->with('success', 'Patient deleted successfully');
    }

    public function updateStatus($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->status = 3; // Ubah status menjadi 3 (Selesai)
        $patient->save();

        return redirect()->route('patient.index')->with('success', 'Status pasien berhasil diperbarui.');
    }
}
