<?php

namespace App\Http\Controllers;

use App\Models\ManajemenDokter;
use Illuminate\Http\Request;

class ManajemenDokterController extends Controller
{
    public function index(Request $request)
    {
        $query = ManajemenDokter::query();

        // Search nama dokter
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        // Filter spesialis
        if ($request->filled('spesialis')) {
            $query->where('spesialis', $request->spesialis);
        }

        $dokter = $query->paginate(10)->withQueryString();

        $spesialis = ManajemenDokter::select('spesialis')
            ->distinct()
            ->orderBy('spesialis')
            ->get();

        return view('manajemen-dokter.index', compact('dokter', 'spesialis'));
    }

    public function create()
    {
        return view('manajemen-dokter.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'spesialis' => 'required',
            'jadwal_praktek' => 'required',
            'no_str' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        ManajemenDokter::create([
            'nama' => $request->nama,
            'spesialis' => $request->spesialis,
            'jadwal_praktek' => $request->jadwal_praktek,
            'no_str' => $request->no_str,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('dokter.index')
            ->with('success', 'Dokter berhasil ditambahkan.');
    }

    public function show($id)
    {
        $dokter = ManajemenDokter::findOrFail($id);

        return view('manajemen-dokter.show', compact('dokter'));
    }

    public function edit($id)
    {
        $dokter = ManajemenDokter::findOrFail($id);

        return view('manajemen-dokter.edit', compact('dokter'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'spesialis' => 'required',
            'jadwal_praktek' => 'required',
            'no_str' => 'required',
            'no_hp' => 'required',
            'alamat' => 'required',
        ]);

        $dokter = ManajemenDokter::findOrFail($id);

        $dokter->update([
            'nama' => $request->nama,
            'spesialis' => $request->spesialis,
            'jadwal_praktek' => $request->jadwal_praktek,
            'no_str' => $request->no_str,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
        ]);

        return redirect()->route('dokter.index')
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $dokter = ManajemenDokter::findOrFail($id);

        $dokter->delete();

        return redirect()->route('dokter.index')
            ->with('success', 'Data dokter berhasil dihapus.');
    }
}
