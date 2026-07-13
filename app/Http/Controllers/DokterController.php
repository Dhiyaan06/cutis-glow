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
        'hari' => 'required|array',
        'hari.*' => 'required|string',
        'jam_mulai' => 'required|array',
        'jam_selesai' => 'required|array',
        'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'no_hp' => $request->no_hp,
            'role' => 'dokter',
            'status_aktif' => $request->status_aktif,
        ]);

        $dokterRole = Role::findOrCreate('dokter');
        $user->assignRole($dokterRole);

        // LOOPING UNTUK MENGGABUNGKAN BANYAK JADWAL
        $daftarJadwal = [];
        foreach ($request->hari as $index => $hari) {
            $daftarJadwal[] = $hari . ' (' . $request->jam_mulai[$index] . ' - ' . $request->jam_selesai[$index] . ')';
        }
        // Hasil gabungan berupa string dipisah tanda '|'
        // Contoh: "Senin (08:00 - 12:00) | Rabu (13:00 - 16:00)"
        $jadwalGabungan = implode(' | ', $daftarJadwal);

        Dokter::create([
            'id_pengguna' => $user->id_pengguna,
            'spesialis' => $request->spesialis,
            'no_str' => $request->no_str,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'jadwal_praktek' => $jadwalGabungan,
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
        $dokter = Dokter::findOrFail($id);
        $jadwalList = [];

        // Pecah string database berdasarkan pemisah ' | '
        $rawJadwal = explode(' | ', $dokter->jadwal_praktek);

        foreach ($rawJadwal as $j) {
            if (preg_match('/^(.*?)\s*\((.*?)\s*-\s*(.*?)\)$/', $j, $matches)) {
                $jadwalList[] = [
                    'hari' => trim($matches[1]),
                    'jam_mulai' => trim($matches[2]),
                    'jam_selesai' => trim($matches[3]),
                ];
            }
        }

        // Jika data kosong, beri 1 baris kosong sebagai default
        if (empty($jadwalList)) {
            $jadwalList[] = ['hari' => '', 'jam_mulai' => '', 'jam_selesai' => ''];
        }

        return view('dokter.edit', compact('dokter', 'jadwalList'));
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
            'hari' => 'required|array',
            'jam_mulai' => 'required|array',
            'jam_selesai' => 'required|array',
            'status_aktif' => 'required|in:aktif,nonaktif',
        ]);

        // Update User
        $userData = ['name' => $request->name, 'email' => $request->email, 'no_hp' => $request->no_hp, 'status_aktif' => $request->status_aktif];
        if ($request->filled('password')) { $userData['password'] = Hash::make($request->password); }
        $user->update($userData);

        // Gabungkan array jadwal baru
        $daftarJadwal = [];
        foreach ($request->hari as $index => $hari) {
            $daftarJadwal[] = $hari . ' (' . $request->jam_mulai[$index] . ' - ' . $request->jam_selesai[$index] . ')';
        }
        $jadwalGabungan = implode(' | ', $daftarJadwal);

        // Update Dokter
        $dokter->update([
            'spesialis' => $request->spesialis,
            'no_str' => $request->no_str,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'jadwal_praktek' => $jadwalGabungan,
        ]);

        return redirect()->route('dokter.index')->with('success', 'Data dokter berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $dokter = Dokter::findOrFail($id);
        $user = User::findOrFail($dokter->id_pengguna);

        $dokter->delete();
        $user->delete();

        return redirect()->route('dokter.index')->with('success', 'Dokter berhasil dihapus!');
    }
}
