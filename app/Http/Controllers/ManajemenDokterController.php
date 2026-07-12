<?php

namespace App\Http\Controllers;

use App\Models\ManajemenDokter;
use App\Models\User;
use Illuminate\Http\Request;

class ManajemenDokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokter = ManajemenDokter::with('pengguna')->paginate(10);
        return view('manajemen-dokter.index', compact('dokter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::where('role', 'dokter')->get();
        return view('manajemen-dokter.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pengguna' => 'required',
            'spesialis' => 'required',
            'jadwal_praktek' => 'required',
            'no_str' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        ManajemenDokter::create([
            'id_pengguna' => $request->id_pengguna,
            'spesialis' => $request->spesialis,
            'jadwal_praktek' => $request->jadwal_praktek,
            'no_str' => $request->no_str,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('manajemen-dokter.index')
            ->with('success', 'Dokter berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $dokter = ManajemenDokter::with('pengguna')->findOrFail($id);
        return view('manajemen-dokter.show', compact('dokter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dokter = ManajemenDokter::findOrFail($id);
        $user = User::where('role', 'dokter')->get();
        return view('manajemen-dokter.edit', compact('dokter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pengguna' => 'required',
            'spesialis' => 'required',
            'jadwal_praktek' => 'required',
            'no_str' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        $dokter = ManajemenDokter::findOrFail($id);
        $dokter->update([
            'id_pengguna' => $request->id_pengguna,
            'spesialis' => $request->spesialis,
            'jadwal_praktek' => $request->jadwal_praktek,
            'no_str' => $request->no_str,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('manajemen-dokter.index')
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $dokter = ManajemenDokter::findOrFail($id);
        $dokter->delete();

        return redirect()->route('manajemen-dokter.index')
            ->with('success', 'Data dokter berhasil dihapus.');
    }
}
