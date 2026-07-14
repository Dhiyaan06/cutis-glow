<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RiwayatLayanan;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\MasterLayanan;
use App\Models\BookingKonsultasi;

class RiwayatLayananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $dokterId = $request->input('id_dokter');

        $query = RiwayatLayanan::with(['pasien.user', 'dokter.user', 'layanan']);

        $user = auth()->user();
        if ($user->hasRole('pasien')) {
            $pasien = Pasien::where('id_pengguna', $user->id_pengguna)->first();
            $query->where('id_pasien', $pasien ? $pasien->id_pasien : 0);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('pasien.user', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('dokter.user', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%');
                })->orWhereHas('layanan', function ($sub) use ($search) {
                    $sub->where('nama_layanan', 'like', '%' . $search . '%');
                });
            });
        }

        if ($dokterId) {
            $query->where('id_dokter', $dokterId);
        }

        $riwayat = $query->orderBy('tanggal_treatment', 'desc')->paginate(10)->withQueryString();
        $dokterList = Dokter::with('user')->get();

        return view('riwayat.index', compact('riwayat', 'dokterList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $bookingId = $request->input('booking_id');
        $booking = null;
        if ($bookingId) {
            $booking = BookingKonsultasi::with(['pasien', 'dokter'])->find($bookingId);
        }

        $pasienList = Pasien::with('user')->get();

        $user = auth()->user();
        if ($user->hasRole('dokter')) {
            $dokterList = Dokter::with('user')->where('user_id', $user->id_pengguna)->get();
        } else {
            $dokterList = Dokter::with('user')->get();
        }

        $layananList = MasterLayanan::all();

        return view('riwayat.create', compact('booking', 'pasienList', 'dokterList', 'layananList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->hasRole('dokter')) {
            $dokterObj = Dokter::where('user_id', $user->id_pengguna)->first();
            $request->merge(['id_dokter' => $dokterObj ? $dokterObj->id_dokter : null]);
        }

        $request->validate([
            'id_pasien' => 'required|exists:pasien,id_pasien',
            'id_dokter' => 'required|exists:dokter,id_dokter',
            'id_layanan' => 'required|exists:master_layanan,id_layanan',
            'tanggal_treatment' => 'required|date',
            'status' => 'required|in:selesai,batal',
            'catatan' => 'nullable|string',
            'qty' => 'required|integer|min:1',
            'id_booking' => 'nullable|exists:booking_konsultasi,id_booking'
        ]);

        $layanan = MasterLayanan::findOrFail($request->id_layanan);

        // hitung harga dengan diskon
        $hargaAkhir = $layanan->harga - ($layanan->harga * $layanan->diskon / 100);

        RiwayatLayanan::create([
            'id_booking' => $request->id_booking,
            'id_pasien' => $request->id_pasien,
            'id_dokter' => $request->id_dokter,
            'id_layanan' => $request->id_layanan,
            'tanggal_treatment' => $request->tanggal_treatment,
            'status' => $request->status,
            'catatan' => $request->catatan,
            'harga' => $hargaAkhir,
            'qty' => $request->qty
        ]);

        // Jika ada booking terkait, set status booking menjadi selesai
        if ($request->id_booking) {
            $booking = BookingKonsultasi::findOrFail($request->id_booking);
            $booking->status = 'selesai';
            $booking->save();
        }

        return redirect()->route('riwayat-layanan.index')->with('success', 'Riwayat treatment berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
   //public function show(string $id)
    //{
        //
    //}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $riwayat = RiwayatLayanan::findOrFail($id);
        $pasienList = Pasien::with('user')->get();
        $dokterList = Dokter::with('user')->get();
        $layananList = MasterLayanan::all();
        return view('riwayat.edit', compact('riwayat', 'pasienList', 'dokterList', 'layananList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $riwayat = RiwayatLayanan::findOrFail($id);

        $request->validate([
            'id_pasien' => 'required|exists:pasien,id_pasien',
            'id_dokter' => 'required|exists:dokter,id_dokter',
            'id_layanan' => 'required|exists:master_layanan,id_layanan',
            'tanggal_treatment' => 'required|date',
            'status' => 'required|in:selesai,batal',
            'catatan' => 'nullable|string',
            'qty' => 'required|integer|min:1',
        ]);

        $layanan = MasterLayanan::findOrFail($request->id_layanan);
        $hargaAkhir = $layanan->harga - ($layanan->harga * $layanan->diskon / 100);

        $riwayat->update([
            'id_pasien' => $request->id_pasien,
            'id_dokter' => $request->id_dokter,
            'id_layanan' => $request->id_layanan,
            'tanggal_treatment' => $request->tanggal_treatment,
            'status' => $request->status,
            'catatan' => $request->catatan,
            'harga' => $hargaAkhir,
            'qty' => $request->qty
        ]);

        return redirect()->route('riwayat-layanan.index')->with('success', 'Riwayat treatment berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $riwayat = RiwayatLayanan::findOrFail($id);
        $riwayat->delete();

        return redirect()->route('riwayat-layanan.index')->with('success', 'Riwayat treatment berhasil dihapus!');
    }
}
