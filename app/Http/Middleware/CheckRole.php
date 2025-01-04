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
        if (Auth::check() && Auth::user()->role == 3) {
            Auth::logout(); // Logout the user
            return redirect()->route('login')->withErrors([
                'role' => 'Access denied for nurses.',
            ]);
        }
        return $next($request);
    }
}
