<?php

namespace App\Http\Controllers;

use App\Models\ManajemenPasien;
use App\Models\User;
use Illuminate\Http\Request;


class ManajemenPasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasien = ManajemenPasien::with('user')->get();

        return view('manajemen_pasien.index', compact('pasiens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manajemen_pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $user = User::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    ManajemenPasien::create([
        'user_id'         => $user->id_pengguna,
        'jenis_kelamin'   => $request->jenis_kelamin,
        'tanggal_lahir'   => $request->tanggal_lahir,
        'alamat'          => $request->alamat,
    ]);

    return redirect()->back();
}



    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pasien = ManajemenPasien::with([
            'user',
            'bookingKonsultasi',
            'RiwayatLayanan'
        ])->findOrFail($id);

        return view('manajemen_pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pasien = ManajemenPasien::with('user')->findOrFail($id);

        return view('admin.manajemen_pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(Request $request, $id)
    {
        $pasien = ManajemenPasien::findOrFail($id);

        $request->validate([
            'nama'            => 'required',
            'email'           => 'required|email',
            'jenis_kelamin'   => 'required',
            'tanggal_lahir'   => 'required',
            'alamat'          => 'required',
        ]);


        $pasien->user->update([
            'nama'      => $request->nama,
            'email'     => $request->email,
        ]);

        $pasien->update([
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat'        => $request->alamat,
        ]);

        return redirect()->route('manajemen-pasien.index')
                ->with('success', 'Data pasien berhasil diperbarui.');
        }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pasien = ManajemenPasien::findOrFail($id);

        // Jika ingin benar-benar menghapus
        $pasien->user->delete();
        $pasien->delete();

        return redirect()->route('manajemen-pasien.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }
}
