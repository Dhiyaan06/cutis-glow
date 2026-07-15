<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileApiController extends Controller
{
    /**
     * Update profil akun sendiri (semua role: admin/dokter/pasien).
     * Password bersifat opsional -- isi hanya kalau mau diganti.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'no_hp' => $request->no_hp,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // Sinkronkan juga ke tabel profil terkait (dokter/pasien) kalau ada,
        // supaya nama/no HP yang tampil di list admin ikut ter-update.
        if ($user->role === 'dokter' && $user->dokter) {
            $user->dokter->update(['nama' => $request->name, 'no_hp' => $request->no_hp]);
        }
        if ($user->role === 'pasien' && $user->pasien) {
            $user->pasien->update(['no_hp' => $request->no_hp]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Profil berhasil diperbarui',
            'data' => [
                'id_pengguna' => $user->id_pengguna,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'no_hp' => $user->no_hp,
                'status_aktif' => $user->status_aktif,
            ],
        ]);
    }
}
