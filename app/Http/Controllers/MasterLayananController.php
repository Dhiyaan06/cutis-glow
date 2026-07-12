<?php

namespace App\Http\Controllers;

use App\Models\MasterLayanan;
use Illuminate\Http\Request;

class MasterLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1. Pagination agar tampil 10 data per halaman
        $layanan = MasterLayanan::paginate(10);

        // 2. Lempar datanya ke halaman view 'layanan.index'
        return view('layanan.index', compact('layanan'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Inputan User
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'harga'        => 'required|numeric|min:0',
            'diskon'       => 'nullable|numeric|min:0|max:100',
        ]);

        // 2. Simpan ke Database
        \App\Models\MasterLayanan::create([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $request->harga,
            'diskon'       => $request->diskon ?? 0,
        ]);

        // 3. Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('layanan.index')->with('success', 'Layanan baru berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    #public function show(id)
    #{
        //
    #}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Cari data layanan berdasarkan ID, jika tidak ada otomatis memunculkan halaman 404
        $layanan = \App\Models\MasterLayanan::findOrFail($id);
        return view('layanan.edit', compact('layanan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 1. Validasi Inputan
        $request->validate([
            'nama_layanan' => 'required|string|max:255',
            'deskripsi'    => 'nullable|string',
            'harga'        => 'required|numeric|min:0',
            'diskon'       => 'nullable|numeric|min:0|max:100',
        ]);

        // 2. Cari data dan Update
        $layanan = \App\Models\MasterLayanan::findOrFail($id);
        $layanan->update([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi'    => $request->deskripsi,
            'harga'        => $request->harga,
            'diskon'       => $request->diskon ?? 0,
        ]);

        // 3. Kembalikan ke index dengan notifikasi sukses
        return redirect()->route('layanan.index')->with('success', 'Data layanan berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Cari data dan Hapus
        $layanan = \App\Models\MasterLayanan::findOrFail($id);
        $layanan->delete();

        // Kembalikan dengan notifikasi sukses
        return redirect()->route('layanan.index')->with('success', 'Layanan berhasil dihapus!');
    }
}
