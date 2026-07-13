<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;

class DokterApiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $spesialis = $request->input('spesialis');

        $query = Dokter::with(['user', 'jadwal' => function ($q) {
            $q->where('status', 'aktif');
        }]);

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('spesialis', 'like', '%' . $search . '%');
        }

        if ($spesialis) {
            $query->where('spesialis', $spesialis);
        }

        $dokter = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $dokter
        ]);
    }

    public function schedules(Request $request)
    {
        $dokterId = $request->input('id_dokter');

        $query = JadwalDokter::with('dokter.user')->where('status', 'aktif');

        if ($dokterId) {
            $query->where('id_dokter', $dokterId);
        }

        $schedules = $query->get();

        return response()->json([
            'status' => 'success',
            'data' => $schedules
        ]);
    }
}
