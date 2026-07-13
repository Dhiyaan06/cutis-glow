<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManajemenDokter;

class DokterApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dokter = ManajemenDokter::all();
        return response()->json([
            'status' => 'success',
            'data' => $dokter
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_dokter' => 'required|string',
            'spesialis' => 'required|string',
            'jadwal_praktek' => 'required|string',
        ]);

        $dokter = ManajemenDokter::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Dokter berhasil ditambahkan',
            'data' => $dokter
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $dokter = ManajemenDokter::find($id);
        if (!$dokter) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $dokter], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $dokter = ManajemenDokter::find($id);
        if (!$dokter) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_dokter' => 'sometimes|required|string',
            'spesialis' => 'sometimes|required|string',
            'jadwal_praktek' => 'sometimes|required|string',
        ]);

        $dokter->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Dokter berhasil diperbarui',
            'data' => $dokter
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $dokter = ManajemenDokter::find($id);
        if (!$dokter) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }

        $dokter->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Dokter berhasil dihapus'
        ], 200);
    }
}
