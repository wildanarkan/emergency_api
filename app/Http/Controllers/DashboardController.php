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
        $user = auth()->user();

        if ($user->usertype == 3) { // Operator System
            $hospitalsCount = Hospital::count();
            $patientsCount = Patient::count();
            $usersCount = User::count();
            $recentPatients = Patient::with('hospital')
                ->latest()
                ->take(5)
                ->get();
        } else if ($user->usertype == 2) { // Hospital Operator
            $hospitalsCount = 1;
            $patientsCount = Patient::where('hospital_id', $user->hospital_id)->count();
            $usersCount = User::where('hospital_id', $user->hospital_id)->count();
            $recentPatients = Patient::with('hospital')
                ->where('hospital_id', $user->hospital_id)
                ->latest()
                ->take(5)
                ->get();
        } else { // Ambulance Operator
            $hospitalsCount = Hospital::count();
            $patientsCount = 0;
            $usersCount = 0;
            $recentPatients = collect();
        }

        return view('dashboard', compact(
            'hospitalsCount',
            'patientsCount',
            'usersCount',
            'recentPatients'
        ));
    }
}
