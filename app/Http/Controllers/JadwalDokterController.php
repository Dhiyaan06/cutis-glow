<?php

namespace App\Http\Controllers;

use App\Models\JadwalDokter;
use App\Models\Dokter;
use Illuminate\Http\Request;

class JadwalDokterController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dokterId = $request->input('id_dokter');
        $status = $request->input('status');

        $query = JadwalDokter::with('dokter.user');

        if ($search) {
            $query->whereHas('dokter.user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            })->orWhere('hari', 'like', '%' . $search . '%');
        }

        if ($dokterId) {
            $query->where('id_dokter', $dokterId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        $jadwal = $query->paginate(10)->withQueryString();
        $dokterList = Dokter::with('user')->get();

        return view('jadwal-dokter.index', compact('jadwal', 'dokterList'));
    }

    public function create()
    {
        $dokterList = Dokter::with('user')->get();
        return view('jadwal-dokter.create', compact('dokterList'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_dokter' => 'required|exists:dokter,id_dokter',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        JadwalDokter::create($request->all());

        return redirect()->route('jadwal-dokter.index')->with('success', 'Jadwal dokter berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);
        $dokterList = Dokter::with('user')->get();
        return view('jadwal-dokter.edit', compact('jadwal', 'dokterList'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = JadwalDokter::findOrFail($id);

        $request->validate([
            'id_dokter' => 'required|exists:dokter,id_dokter',
            'hari' => 'required|string',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'status' => 'required|in:aktif,nonaktif',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal-dokter.index')->with('success', 'Jadwal dokter berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jadwal = JadwalDokter::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal-dokter.index')->with('success', 'Jadwal dokter berhasil dihapus!');
    }
}
