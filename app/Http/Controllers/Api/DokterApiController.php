<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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

    /**
     * [Dokter] Ambil profil dokter milik user yang sedang login.
     * Dipakai supaya dokter bisa lihat (jadwal dari) profilnya sendiri
     * tanpa perlu tahu id_dokter miliknya.
     */
    public function myProfile(Request $request)
    {
        $dokter = $request->user()->dokter;

        if (!$dokter) {
            return response()->json([
                'status' => 'error',
                'message' => 'Profil dokter tidak ditemukan untuk akun ini.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $dokter->load('user'),
        ]);
    }

    /**
     * [Admin] Detail 1 dokter.
     */
    public function show(string $id)
    {
        $dokter = Dokter::with('user')->find($id);
        if (!$dokter) {
            return response()->json(['status' => 'error', 'message' => 'Dokter tidak ditemukan'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $dokter]);
    }

    /**
     * [Admin] Tambah dokter baru -> otomatis bikin akun User (role dokter) + profil Dokter.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'spesialis' => 'required|string|max:255',
            'no_str' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'status_aktif' => 'nullable|in:aktif,nonaktif',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'role' => 'dokter',
            'status_aktif' => $request->status_aktif ?? 'aktif',
        ]);
        $user->assignRole(Role::findOrCreate('dokter'));

        $dokter = Dokter::create([
            'user_id' => $user->id_pengguna,
            'nama' => $user->name,
            'spesialis' => $request->spesialis,
            'no_str' => $request->no_str,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            // jadwal_praktek (kolom lama) tidak dipakai lagi oleh mobile app,
            // jadwal detail dikelola lewat tabel jadwal_dokter
            'jadwal_praktek' => '-',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Dokter berhasil ditambahkan',
            'data' => $dokter->load('user'),
        ], 201);
    }

    /**
     * [Admin] Update data dokter + akun user-nya.
     */
    public function update(Request $request, string $id)
    {
        $dokter = Dokter::find($id);
        if (!$dokter) {
            return response()->json(['status' => 'error', 'message' => 'Dokter tidak ditemukan'], 404);
        }
        $user = User::findOrFail($dokter->user_id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id_pengguna . ',id_pengguna',
            'password' => 'nullable|string|min:8',
            'spesialis' => 'required|string|max:255',
            'no_str' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'status_aktif' => 'nullable|in:aktif,nonaktif',
        ]);

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
        ];
        if ($request->filled('status_aktif')) {
            $userData['status_aktif'] = $request->status_aktif;
        }
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }
        $user->update($userData);

        $dokter->update([
            'nama' => $request->name,
            'spesialis' => $request->spesialis,
            'no_str' => $request->no_str,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data dokter berhasil diubah',
            'data' => $dokter->fresh('user'),
        ]);
    }

    /**
     * [Admin] Hapus dokter + akun user-nya sekaligus.
     */
    public function destroy(string $id)
    {
        $dokter = Dokter::find($id);
        if (!$dokter) {
            return response()->json(['status' => 'error', 'message' => 'Dokter tidak ditemukan'], 404);
        }
        $userId = $dokter->user_id;
        $dokter->delete();
        User::where('id_pengguna', $userId)->delete();

        return response()->json(['status' => 'success', 'message' => 'Dokter berhasil dihapus']);
    }
}
