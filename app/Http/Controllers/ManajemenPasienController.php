<?php

namespace App\Http\Controllers;

use App\Models\ManajemenPasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ManajemenPasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = ManajemenPasien::query();

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('jenis_kelamin')) {
            $query->where('jenis_kelamin', $request->jenis_kelamin);
        }

        $pasien = $query->paginate(10);

        return view('manajemen_pasien.index', compact('pasien'));
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
        $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:pasien,email',
            'password'         => 'required|min:6',
            'no_hp'            => 'required',
            'jenis_kelamin'    => 'required|in:L,P',
            'tanggal_lahir'    => 'required|date',
            'alamat'           => 'required',
        ]);

        ManajemenPasien::create([
            'nama'             => $request->name,
            'email'            => $request->email,
            'password'         => Hash::make($request->password),
            'no_hp'            => $request->no_hp,
            'jenis_kelamin'    => $request->jenis_kelamin,
            'tanggal_lahir'    => $request->tanggal_lahir,
            'alamat'           => $request->alamat,
        ]);

        return redirect()
            ->route('pasien.index')
            ->with('success', 'Data pasien berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $pasien = ManajemenPasien::findOrFail($id);

        return view('manajemen_pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pasien = ManajemenPasien::findOrFail($id);

        return view('manajemen_pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name'             => 'required|string|max:100',
            'email'            => 'required|email|unique:pasien,email,' . $id . ',id_pasien',
            'no_hp'            => 'required',
            'jenis_kelamin'    => 'required|in:L,P',
            'tanggal_lahir'    => 'required|date',
            'alamat'           => 'required',
        ]);

        $pasien = ManajemenPasien::findOrFail($id);

        $pasien->update([
            'nama'             => $request->name,
            'email'            => $request->email,
            'no_hp'            => $request->no_hp,
            'jenis_kelamin'    => $request->jenis_kelamin,
            'tanggal_lahir'    => $request->tanggal_lahir,
            'alamat'           => $request->alamat,
        ]);

        return redirect()
            ->route('pasien.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pasien = ManajemenPasien::findOrFail($id);

        $pasien->delete();

        return redirect()
            ->route('pasien.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }
}
