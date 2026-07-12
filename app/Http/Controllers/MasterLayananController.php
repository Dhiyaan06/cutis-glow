<?php

namespace App\Http\Controllers;

use App\Models\MasterLayanan;
use Illuminate\Http\Request;

class MasterLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // 1. Ambil inputan dari request search & filter
        $search = $request->input('search');
        $filterDiskon = $request->input('filter_diskon');

        // 2. Query dasar mengambil data layanan
        $query = MasterLayanan::query();

        // Logika Search: Jika ada input search, cari berdasarkan nama layanan
        if ($search) {
            $query->where('nama_layanan', 'like', '%' . $search . '%');
        }

        // Logika Filter: Filter berdasarkan status diskon
        if ($filterDiskon === 'ada_diskon') {
            $query->where('diskon', '>', 0);
        } elseif ($filterDiskon === 'tanpa_diskon') {
            $query->where('diskon', '=', 0);
        }

        // 3. Jalankan pagination (10 data per halaman) sambil mempertahankan parameter query di URL
        $layanan = $query->paginate(10)->withQueryString();

        // 4. Lempar data ke view
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
