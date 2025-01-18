<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notif;
use Illuminate\Support\Facades\Auth;

class NotifController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($user->role == 2) {
            return redirect()->route('patient.index');
        } else {
            $notif = Notif::all();
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'data' => $notif
            ]);
        }
        return view('notif.index', compact('notif'));
    }

    public function getNotifications(Request $request)
    {
        $user = Auth::user();

        if ($user->role == 2) {
            $hospitalId = $user->hospital->id;
            $notifications = Notif::where('hospital_id', $hospitalId)
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get();
        } else {
            $notifications = Notif::orderBy('created_at', 'desc')->take(10)->get();
        }

        $notifCount = $notifications->where('status', 1)->count();
        return response()->json([
            'notifications' => $notifications,
            'count' => $notifCount
        ]);
    }

    public function updateStatus($id)
    {
        $user = Auth::user();

        if ($user->role == 1) {
            return redirect()->route('notif.index');
        }
        $notif = Notif::find($id);
        $notif->status = 2;
        // $notif->is_read = true;
        $notif->save();

        return redirect()->route('patient.index');
    }
}
