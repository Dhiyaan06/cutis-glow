<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\BookingKonsultasi;
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if($user->hasRole('admin')){
            return view('dashboard.admin');
        }
         if ($user->hasRole('dokter')) {
              $stats = [
                'total_booking' => BookingKonsultasi::count(),

                'pending_booking' => BookingKonsultasi::where(
                    'status_booking',
                    'pending'
                )->count(),

                'confirmed_booking' => BookingKonsultasi::where(
                    'status_booking',
                    'confirmed'
                )->count(),

                'completed_booking' => BookingKonsultasi::where(
                    'status_booking',
                    'completed'
                )->count(),
            ];


            $mybooking = BookingKonsultasi::with([
                'pasien.user'
            ])
            ->latest()
            ->paginate(10);

            return view('dashboard.dokter', compact('stats', 'mybooking'));
        }

        if ($user->hasRole('pasien')) {
            return view('dashboard.pasien');
        }
        abort(403, 'Role tidak dikenali.');
    }
}
