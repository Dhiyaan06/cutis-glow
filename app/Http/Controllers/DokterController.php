<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dokter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DokterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $spesialis = $request->input('spesialis');

        $query = Dokter::with('user');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            })->orWhere('spesialis', 'like', '%' . $search . '%');
        }

        if ($spesialis) {
            $query->where('spesialis', $spesialis);
        }

        $dokter = $query->paginate(10)->withQueryString();

        // Get unique specialties for filter dropdown
        $specialties = Dokter::distinct()->pluck('spesialis');

        return view('dokter.index', compact('dokter', 'specialties'));
    }

    public function create()
    {
        return view('dokter.create');
    }

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
            'jadwal_praktek' => 'required|string',
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        // 1. Buat User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'role' => 'dokter',
            'status_aktif' => $request->status_aktif,
        ]);

        // Assign Spatie Role
        $dokterRole = Role::findOrCreate('dokter');
        $user->assignRole($dokterRole);

        // 2. Buat Dokter
        Dokter::create([
            'id_pengguna' => $user->id_pengguna,
            'spesialis' => $request->spesialis,
            'no_str' => $request->no_str,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'jadwal_praktek' => $request->jadwal_praktek,
        ]);

        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil ditambahkan!');
    }

    public function show($id)
    {
        $dokter = Dokter::with('user')->findOrFail($id);
        return view('dokter.show', compact('dokter'));
    }

    public function edit($id)
    {
        $dokter = Dokter::with('user')->findOrFail($id);
        return view('dokter.edit', compact('dokter'));
    }

    public function update(Request $request, $id)
    {
        $dokter = Dokter::findOrFail($id);
        $user = User::findOrFail($dokter->id_pengguna);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id_pengguna . ',id_pengguna',
            'password' => 'nullable|string|min:8',
            'spesialis' => 'required|string|max:255',
            'no_str' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'jadwal_praktek' => 'required|string',
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        // Update User
        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'status_aktif' => $request->status_aktif,
        ];

        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        // Update Dokter
        $dokter->update([
            'spesialis' => $request->spesialis,
            'no_str' => $request->no_str,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'jadwal_praktek' => $request->jadwal_praktek,
        ]);

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);
        $user = User::findOrFail($dokter->id_pengguna);

        // Hapus dokter dan user terkait (akan terhapus cascade di db tapi hapus user manual agar bersih)
        $dokter->delete();
        $user->delete();

        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil dihapus!');
    }
}
