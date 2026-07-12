<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if($user->hasRole('admin')){
            return view('dashboard.admin');
        }
         if ($user->hasRole('dokter')) {
            return view('dashboard.dokter');
        }
        if ($user->hasRole('pasien')) {
            return view('dashboard.pasien');
        }
        abort(403, 'Role tidak dikenali.');
    }
}
