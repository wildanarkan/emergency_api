<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Periksa apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // Periksa apakah role pengguna diizinkan
        if (!in_array(Auth::user()->role, $roles)) {
            // Jika role tidak sesuai, redirect ke halaman patient
            return redirect()->route('patient.index');
        }

        return $next($request);
    }
}