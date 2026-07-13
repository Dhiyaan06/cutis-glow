<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MasterLayanan;

class LayananApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layanan = MasterLayanan::all();
        return response()->json([
            'status' => 'success',
            'data' => $layanan
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_layanan' => 'required|string',
            'harga' => 'required|numeric',
        ]);

        $layanan = MasterLayanan::create($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil ditambahkan',
            'data' => $layanan
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $layanan = MasterLayanan::find($id);
        if (!$layanan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        }
        return response()->json(['status' => 'success', 'data' => $layanan], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $layanan = MasterLayanan::find($id);
        if (!$layanan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 44);
        }

        $layanan->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil diubah',
            'data' => $layanan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $layanan = MasterLayanan::find($id);
        if (!$layanan) {
            return response()->json(['message' => 'Data tidak ditemukan'], 44);
        }

        $layanan->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Layanan berhasil dihapus'
        ], 200);
    }
}
