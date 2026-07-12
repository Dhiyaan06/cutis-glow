<?php

namespace App\Http\Controllers;

use App\Models\ManajemenDokter;
use Illuminate\Http\Request;

class ManajemenDokterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokter = ManajemenDokter::all();
        return view('manajemen-dokter.index', compact('dokter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('manajemen-dokter.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'spesialis' => 'required',
            'jadwal_praktek' => 'required',
            'nomor_lisensi' => 'required',
            'alamat' => 'required',
        ]);

        ManajemenDokter::create([
            'nama' => $request->nama,
            'spesialis' => $request->spesialis,
            'jadwal_praktek' => $request->jadwal_praktek,
            'nomor_lisensi' => $request->nomor_lisensi,
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
        $dokter = ManajemenDokter::findOrFail($id);
        return view('manajemen-dokter.show', compact('dokter'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $dokter = ManajemenDokter::findOrFail($id);
        return view('dokter.edit', compact('dokter'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'spesialis' => 'required',
            'jadwal_praktek' => 'required',
            'nomor_lisensi' => 'required',
            'alamat' => 'required',
        ]);

        $dokter = ManajemenDokter::findOrFail($id);
        $dokter->update([
            'nama' => $request->nama,
            'spesialis' => $request->spesialis,
            'jadwal_praktek' => $request->jadwal_praktek,
            'nomor_lisensi' => $request->nomor_lisensi,
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
