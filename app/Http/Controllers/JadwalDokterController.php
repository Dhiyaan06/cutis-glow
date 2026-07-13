<?php

namespace App\Http\Controllers;

use App\Models\ManajemenDokter;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalDokterController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $query = JadwalDokter::with('dokter.user');

        if ($user->hasRole('doctor')) { // Catatan: nama role di database tetap disesuaikan dengan konfigurasi auth Anda
            $dokter = ManajemenDokter::where('id_pengguna', $user->id)->first();
            $query->where('id_dokter', $dokter ? $dokter->id : 0);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('day', 'like', '%' . $search . '%')
                  ->orWhere('status', 'like', '%' . $search . '%')
                  ->orWhereHas('dokter.user', function($qu) use ($search) {
                      $qu->where('name', 'like', '%' . $search . '%');
                  });
            });
        }

        $schedules = $query->orderBy('day', 'asc')->paginate(5)->withQueryString();

        return view('schedules.index', compact('schedules', 'search'));
    }

    public function create()
    {
        $user = Auth::user();
        $dokters = [];

        if ($user->hasRole('admin')) {
            $dokters = ManajemenDokter::with('user')->get();
        }

        return view('schedules.create', compact('dokters'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'day' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required|in:available,unavailable',
        ];

        if ($user->hasRole('admin')) {
            $rules['id_dokter'] = 'required|exists:dokters,id';
        }

        $validated = $request->validate($rules);

        if ($user->hasRole('doctor')) {
            $dokter = ManajemenDokter::where('id_pengguna', $user->id)->first();
            if (!$dokter) {
                return redirect()->back()->with('error', 'Dokter tidak ditemukan.');
            }
            $validated['id_dokter'] = $dokter->id;
        }

        JadwalDokter::create($validated);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function edit(JadwalDokter $jadwalDokter)
    {
        $user = Auth::user();

        if ($user->hasRole('doctor')) {
            $dokter = ManajemenDokter::where('id_pengguna', $user->id)->first();
            if ($jadwalDokter->id_dokter !== ($dokter ? $dokter->id : null)) {
                abort(403, 'Akses ditolak.');
            }
        }

        $dokters = [];
        if ($user->hasRole('admin')) {
            $dokters = ManajemenDokter::with('user')->get();
        }

        return view('schedules.edit', compact('jadwalDokter', 'dokters'));
    }

    public function update(Request $request, JadwalDokter $jadwalDokter)
    {
        $user = Auth::user();

        if ($user->hasRole('doctor')) {
            $dokter = ManajemenDokter::where('id_pengguna', $user->id)->first();
            if ($jadwalDokter->id_dokter !== ($dokter ? $dokter->id : null)) {
                abort(403, 'Akses ditolak.');
            }
        }

        $rules = [
            'day' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu,Minggu',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'required|in:available,unavailable',
        ];

        if ($user->hasRole('admin')) {
            $rules['id_dokter'] = 'required|exists:dokters,id';
        }

        $validated = $request->validate($rules);

        $jadwalDokter->update($validated);

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(JadwalDokter $jadwalDokter)
    {
        $user = Auth::user();

        if ($user->hasRole('doctor')) {
            $dokter = ManajemenDokter::where('id_pengguna', $user->id)->first();
            if ($jadwalDokter->id_dokter !== ($dokter ? $dokter->id : null)) {
                abort(403, 'Akses ditolak.');
            }
        }

        $jadwalDokter->delete();

        return redirect()->route('schedules.index')->with('success', 'Jadwal berhasil dihapus.');
    }
}
