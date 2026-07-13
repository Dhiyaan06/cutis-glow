<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PasienController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $gender = $request->input('jenis_kelamin');

        $query = Pasien::with('user');

        if ($search) {
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            })->orWhere('no_hp', 'like', '%' . $search . '%')
              ->orWhere('alamat', 'like', '%' . $search . '%');
        }

        if ($gender) {
            $query->where('jenis_kelamin', $gender);
        }

        $pasien = $query->paginate(10)->withQueryString();

        return view('pasien.index', compact('pasien'));
    }

    public function create()
    {
        return view('pasien.create');
    }

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
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        // Create User
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'role' => 'pasien',
            'status_aktif' => $request->status_aktif,
        ]);

        // Assign Spatie Role
        $pasienRole = Role::findOrCreate('pasien');
        $user->assignRole($pasienRole);

        // Create Pasien profile
        Pasien::create([
            'id_pengguna' => $user->id_pengguna,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil ditambahkan!');
    }

    public function show($id)
    {
        $pasien = Pasien::with('user')->findOrFail($id);
        return view('pasien.show', compact('pasien'));
    }

    public function edit($id)
    {
        $pasien = Pasien::with('user')->findOrFail($id);
        return view('pasien.edit', compact('pasien'));
    }

    public function update(Request $request, $id)
    {
        $pasien = Pasien::findOrFail($id);
        $user = User::findOrFail($pasien->id_pengguna);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id_pengguna . ',id_pengguna',
            'password' => 'nullable|string|min:8',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:L,P',
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

        // Update Pasien
        $pasien->update([
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
        ]);

        return redirect()->route('pasien.index')->with('success', 'Data pasien berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $pasien = Pasien::findOrFail($id);
        $user = User::findOrFail($pasien->id_pengguna);

        $pasien->delete();
        $user->delete();

        return redirect()->route('pasien.index')->with('success', 'Pasien berhasil dihapus!');
    }
}
