<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user(); // Ambil data user yang sedang login

        // Default nilai awal untuk semua variabel
        // All Hospital
        $hospitalAllCount = Hospital::all()->count();
        $patientAllCount = Patient::all()->count();
        $userAllCount = User::all()->count();
        $hospitalAll = Hospital::all();
        $patientAll = Patient::all();
        $userAll = User::all();
        // $hospitalAllCount = 0;
        // $patientAllCount = 0;
        // $userAllCount = 0;

        $hospitalCount = 0;
        $patientCount = 0;
        $userCount = 0;
        $recentPatients = collect(); // Collection kosong untuk pasien terbaru
        $hospitals = collect(); // Collection kosong untuk rumah sakit
        $patients = collect(); // Collection kosong untuk semua pasien
        $users = collect(); // Collection kosong untuk semua pengguna

        // Switch case berdasarkan role pengguna
        switch ($user->role) {
            case 1: // Operator System

                $hospitalCount = Hospital::count();
                $patientCount = Patient::count();
                $userCount = User::count();
                $recentPatients = Patient::with('hospital')
                    ->latest()
                    ->take(5)
                    ->get();
                $hospitals = Hospital::all(); // Ambil semua data rumah sakit
                $patients = Patient::all(); // Ambil semua pasien
                $users = User::all(); // Ambil semua pengguna
                break;
            case 2:
                return redirect()->route('patient.index');
            default:
                abort(403, 'Unauthorized (belom di redirect)'); // Role tidak dikenali
        }

        // Kirim data ke view dashboard
        return view('dashboard', compact(
            'hospitalAllCount',
            'patientAllCount',
            'userAllCount',

            'hospitalCount',
            'patientCount',
            'userCount',
            'recentPatients',
            'hospitals', // Data rumah sakit
            'patients', // Semua pasien
            'users' // Semua pengguna
        ));
    }
}
