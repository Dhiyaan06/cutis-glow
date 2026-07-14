<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pasien;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PasienApiController extends Controller
{
    /**
     * [Admin] Daftar semua pasien.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $gender = $request->input('jenis_kelamin');

        $query = Pasien::with('user');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }
        if ($gender) {
            $query->where('jenis_kelamin', $gender);
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->get(),
        ]);
    }

    public function show(string $id)
    {
        $pasien = Pasien::with('user')->find($id);
        if (!$pasien) {
            return response()->json(['status' => 'error', 'message' => 'Pasien tidak ditemukan'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $pasien]);
    }

    /**
     * [Admin] Tambah pasien baru -> otomatis bikin akun User (role pasien) + profil Pasien.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
            'status_aktif' => 'nullable|in:aktif,nonaktif',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'role' => 'pasien',
            'status_aktif' => $request->status_aktif ?? 'aktif',
        ]);
        $user->assignRole(Role::findOrCreate('pasien'));

        $pasien = Pasien::create([
            'id_pengguna' => $user->id_pengguna,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Pasien berhasil ditambahkan',
            'data' => $pasien->load('user'),
        ], 201);
    }

    /**
     * [Admin] Update data pasien + akun user-nya.
     */
    public function update(Request $request, string $id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return response()->json(['status' => 'error', 'message' => 'Pasien tidak ditemukan'], 404);
        }
        $user = User::findOrFail($pasien->id_pengguna);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id_pengguna . ',id_pengguna',
            'password' => 'nullable|string|min:8',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
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

        $pasien->update([
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Data pasien berhasil diubah',
            'data' => $pasien->fresh('user'),
        ]);
    }

    /**
     * [Admin] Hapus pasien + akun user-nya sekaligus.
     */
    public function destroy(string $id)
    {
        $pasien = Pasien::find($id);
        if (!$pasien) {
            return response()->json(['status' => 'error', 'message' => 'Pasien tidak ditemukan'], 404);
        }
        $userId = $pasien->id_pengguna;
        $pasien->delete();
        User::where('id_pengguna', $userId)->delete();

        return response()->json(['status' => 'success', 'message' => 'Pasien berhasil dihapus']);
    }
}
