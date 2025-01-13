<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Hospital;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Ambil daftar rumah sakit dan user untuk dropdown filter
        $hospitals = Hospital::all();
        $users = User::where('role', 3)->get();

        // Query awal berdasarkan role
        if ($user->role == 2) {
            $hospital = Hospital::where('user_id', $user->id)->first();
            $query = Patient::where('hospital_id', $hospital->id);
        } else {
            $query = Patient::query();
        }

        // Filter berdasarkan hospital_id jika tersedia
        if ($request->has('hospital_id') && $request->hospital_id != '') {
            $query->where('hospital_id', $request->hospital_id);
        }

        // Filter berdasarkan user_id jika tersedia
        if ($request->has('user_id') && $request->user_id != '') {
            $query->where('user_id', $request->user_id);
        }

        // Filter berdasarkan case_type jika tersedia
        if ($request->has('case_type') && $request->case_type != '') {
            $query->where('case', $request->case_type); // Pastikan 'case' adalah kolom yang benar
        }

        // Filter berdasarkan case_type jika tersedia
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status); // Pastikan 'case' adalah kolom yang benar
        }

        // Ambil data pasien sesuai filter
        $patients = $query->orderBy('status', 'ASC')->orderBy('arrival', 'ASC')->with('hospital', 'user')->get();

        // Jika request JSON, kembalikan sebagai API
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $patients
            ]);
        }

        // Kembalikan ke view dengan data yang difilter
        return view('patient.index', compact('patients', 'hospitals', 'users'));
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
            'name' => 'nullable|string|max:255',
            'age' => 'required|integer',
            'gender' => 'required|integer|in:1,2',
            'case' => 'required|integer|in:1,2',
            'time_incident' => 'required|date',
            'mechanism' => 'required|string',
            'injury' => 'required|string',
            'photo_injury' => 'required|image|mimes:jpeg,png,jpg,webp',
            'symptom' => 'required|string',
            'treatment' => 'required|string',
            'arrival' => 'required|date',
            'hospital_id' => 'nullable|exists:hospital,id',
            'request' => 'required|string',
            'status' => 'required|integer|in:1,2,3',
        ];

        $user = auth()->user();
        Log::info('Authenticated user:', ['user_id' => $user->id]);

        $data = $request->all();
        Log::info('Request data received:', $data);

        // Set user_id
        $data['user_id'] = $user->id;

        // Format dates
        $data['time_incident'] = date('Y-m-d H:i:s', strtotime($request->time_incident));
        Log::info('Formatted time_incident:', ['time_incident' => $data['time_incident']]);

        // Handle hospital_id for role 2
        if ($user->role == 2) {
            $hospital = Hospital::where('user_id', $user->id)->first();
            if ($hospital) {
                $data['hospital_id'] = $hospital->id;
            }
            Log::info('Hospital ID for user:', ['hospital_id' => $data['hospital_id'] ?? 'none']);
        }

        // Handle file upload
        if ($request->hasFile('photo_injury')) {
            $file = $request->file('photo_injury');
            Log::info('Uploaded file MIME type:', ['mime' => $file->getMimeType()]);
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('public/injuries', $filename);
            $data['photo_injury'] = 'storage/injuries/' . $filename;

            Log::info('File uploaded successfully:', ['path' => $data['photo_injury']]);
        }

        if ($request->expectsJson()) {
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                Log::error('Validation failed:', $validator->errors()->toArray());
                return response()->json($validator->errors(), 422);
            }

            $patient = Patient::create($data);
            Log::info('Patient created successfully (JSON response):', $patient->toArray());
            return response()->json([
                'success' => true,
                'data' => $patient,
            ], 201);
        }

        $request->validate($rules);
        $patient = Patient::create($data);
        Log::info('Patient created successfully (Redirect response):', $patient->toArray());

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
            'photo_injury' => 'nullable|image|mimes:jpeg,png,jpg',
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

        if (!$patient) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found'
                ], 404);
            }
            return abort(404);
        }

        if ($patient->photo_injury) {
            $filePath = storage_path('app/public/injuries/' . basename($patient->photo_injury));
            if (file_exists($filePath)) {
                unlink($filePath); // Hapus file
            } else {
                Log::error('File not found: ' . $filePath); // Debugging jika file tidak ditemukan
            }
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
        $patient->status = 2;
        $patient->save();

        return redirect()->route('patient.index')->with('success', 'Status pasien berhasil diperbarui.');
    }

    public function downloadPDF($id)
    {
        $patient = Patient::findOrFail($id);

        $pdf = Pdf::loadView('patient.pdf', [
            'patient' => $patient
        ]);

        $pdf->setPaper('A4', 'portrait');

        // Enable images in PDF
        $pdf->setOption('enable-local-file-access', true);

        return $pdf->download('patient-' . $patient->name . '.pdf');
    }
}
