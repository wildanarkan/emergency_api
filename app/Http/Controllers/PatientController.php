<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role == 2) {
            $hospital = Hospital::where('user_id', $user->id)->first();
            $patient = Patient::where('hospital_id', $hospital->id)->orderBy('status', 'ASC')->orderBy('arrival', 'ASC')->get();
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
            'status' => 'required|integer|in:1,2,3',
            'time_incident' => 'required|date',
            'mechanism' => 'required|string',
            'injury' => 'required|string',
            'photo_injury' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Changed to handle image upload
            'treatment' => 'required|string'
        ];

        $user = auth()->user();
        $data = $request->all();

        // Set user_id
        $data['user_id'] = $user->id;

        // Format dates
        $data['time_incident'] = date('Y-m-d H:i:s', strtotime($request->time_incident));

        // Handle hospital_id for role 2
        if ($user->role == 2) {
            $hospital = Hospital::where('user_id', $user->id)->first();
            if ($hospital) {
                $data['hospital_id'] = $hospital->id;
            }
        }

        // Handle file upload
        if ($request->hasFile('photo_injury')) {
            $file = $request->file('photo_injury');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/injuries', $filename);
            $data['photo_injury'] = 'storage/injuries/' . $filename;
        }

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $patient = Patient::create($data);
            return response()->json([
                'success' => true,
                'data' => $patient,
            ], 201);
        }

        $request->validate($rules);
        Patient::create($data);
        return redirect()->route('patient.index')->with('success', 'Patient created successfully');
    }

    public function show(Request $request, $id)
    {
        $user = auth()->user();
        $patient = Patient::with(['hospital', 'user'])->find($id);

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
        $rules = [
            'name' => 'required|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|integer|in:1,2',
            'case' => 'required|integer|in:1,2',
            'desc' => 'required|string',
            'arrival' => 'required|date',
            'hospital_id' => 'nullable|exists:hospital,id',
            'status' => 'required|integer|in:1,2,3',
            'time_incident' => 'required|date',
            'mechanism' => 'required|string',
            'injury' => 'required|string',
            'photo_injury' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'treatment' => 'required|string'
        ];
    
        $patient = Patient::findOrFail($id);
        $user = auth()->user();
        $data = $request->all();
    
        // Set user_id
        $data['user_id'] = $user->id;
    
        // Format dates
        $data['time_incident'] = date('Y-m-d H:i:s', strtotime($request->time_incident));
    
        // Handle hospital_id for role 2
        if ($user->role == 2) {
            $hospital = Hospital::where('user_id', $user->id)->first();
            if ($hospital) {
                $data['hospital_id'] = $hospital->id;
            }
        }
    
        // Handle file upload
        if ($request->hasFile('photo_injury')) {
            $file = $request->file('photo_injury');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/injuries', $filename);
            $data['photo_injury'] = 'storage/injuries/' . $filename;
    
            // Delete old photo if exists
            if ($patient->photo_injury && file_exists(public_path($patient->photo_injury))) {
                unlink(public_path($patient->photo_injury));
            }
        }
    
        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
    
            $patient->update($data);
            return response()->json([
                'success' => true,
                'data' => $patient,
            ], 200);
        }
    
        $request->validate($rules);
        $patient->update($data);
    
        return redirect()->route('patient.index')->with('success', 'Patient updated successfully');
    }    


    public function destroy(Request $request, $id)
    {
        $user = auth()->user();
        $patient = Patient::find($id);

        // Cek apakah pasien ditemukan
        if (!$patient) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found'
                ], 404);
            }
            return abort(404);
        }

        // Cek apakah user memiliki izin untuk menghapus data
        // if ($user->role == 2 && $user->user_id != $patient->hospital_id) {
        //     if ($request->expectsJson()) {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Unauthorized (tidak ada izin)'
        //         ], 403);
        //     }
        //     return abort(403);
        // }

        // Hapus file foto jika ada
        if ($patient->photo_injury) {
            $filePath = storage_path('app/public/injuries/' . basename($patient->photo_injury));
            if (file_exists($filePath)) {
                unlink($filePath); // Hapus file
            } else {
                Log::error('File not found: ' . $filePath); // Debugging jika file tidak ditemukan
            }
        }

        // Hapus data pasien
        $patient->delete();

        // Response untuk JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Patient deleted successfully'
            ]);
        }

        // Redirect untuk request biasa
        return redirect()->route('patient.index')->with('success', 'Patient deleted successfully');
    }


    public function updateStatus($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->status = 2;
        $patient->save();

        return redirect()->route('patient.index')->with('success', 'Status pasien berhasil diperbarui.');
    }
}
