<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\JadwalDokter;
use Illuminate\Http\Request;

class JadwalDokterApiController extends Controller
{
    /**
     * [Admin] Tambah 1 slot jadwal praktek untuk seorang dokter.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_dokter' => 'required|exists:dokter,id_dokter',
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        $jadwal = JadwalDokter::create([
            'id_dokter' => $request->id_dokter,
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => $request->status ?? 'aktif',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $jadwal,
        ], 201);
    }

    /**
     * [Admin] Update 1 slot jadwal.
     */
    public function update(Request $request, string $id)
    {
        $jadwal = JadwalDokter::find($id);
        if (!$jadwal) {
            return response()->json(['status' => 'error', 'message' => 'Jadwal tidak ditemukan'], 404);
        }

        $request->validate([
            'hari' => 'required|string|max:20',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'status' => 'nullable|in:aktif,nonaktif',
        ]);

        $jadwal->update([
            'hari' => $request->hari,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'status' => $request->status ?? $jadwal->status,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Jadwal berhasil diubah',
            'data' => $jadwal,
        ]);
    }

    /**
     * [Admin] Hapus 1 slot jadwal.
     */
    public function destroy(string $id)
    {
        $jadwal = JadwalDokter::find($id);
        if (!$jadwal) {
            return response()->json(['status' => 'error', 'message' => 'Jadwal tidak ditemukan'], 404);
        }
        $jadwal->delete();

        return response()->json(['status' => 'success', 'message' => 'Jadwal berhasil dihapus']);
    }
}
