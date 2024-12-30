<?php

namespace App\Http\Middleware;

use App\Http\Controllers\HospitalController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!auth()->check()) {
            return redirect('login');
        }

        $userRole = auth()->user()->role;

        // Jika role = 2, hanya bisa akses patient.index
        // if ($userRole == 2) {
        //     // Izinkan akses ke patient.index
        //     if ($request->route()->getName() == 'patient.index') {
        //         return $next($request);
        //     }
        //     // Redirect ke patient.index untuk akses lainnya
        //     return redirect()->route('patient.index');
        // }

        // Untuk role lainnya, bisa akses semua
        return $next($request);
    }
}
