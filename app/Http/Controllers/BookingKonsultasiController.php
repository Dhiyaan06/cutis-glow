<?php

namespace App\Http\Controllers;

use App\Models\BookingKonsultasi;
use App\Models\ManajemenDokter;
use App\Models\ManajemenPasien;
use Illuminate\Http\Request;

class BookingKonsultasiController extends Controller
{
    /**
     * Menampilkan daftar booking.
     */
    public function index()
    {
        $booking = BookingKonsultasi::orderBy('jadwal_konsultasi', 'desc')
                    ->paginate(10);

        return view('booking.index', compact('booking'));
    }

    /**
     * Menampilkan form tambah booking.
     */
   public function create()
{
    $pasien = ManajemenPasien::all();
    $dokter = ManajemenDokter::all();

    return view('booking.create', compact('pasien', 'dokter'));
}

    /**
     * Menyimpan data booking.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pasien'           => 'required',
            'id_dokter'           => 'required',
            'jadwal_konsultasi'   => 'required',
            'keluhan'             => 'required',
        ]);

        BookingKonsultasi::create([
            'id_pasien'          => $request->id_pasien,
            'id_dokter'          => $request->id_dokter,
            'jadwal_konsultasi'  => $request->jadwal_konsultasi,
            'keluhan'            => $request->keluhan,
            'status_booking'     => 'pending',
        ]);

        return redirect()
            ->route('booking.index')
            ->with('success', 'Booking berhasil ditambahkan.');
    }

    /**
     * Detail booking.
     */
    public function show($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    /**
     * Form edit booking.
     */
    public function edit($id)
    {
    $booking = BookingKonsultasi::findOrFail($id);
    $pasien = ManajemenPasien::all();
    $dokter = ManajemenDokter::all();

    return view('booking.edit', compact('booking', 'pasien', 'dokter'));
    }

    /**
     * Update booking.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pasien'           => 'required',
            'id_dokter'           => 'required',
            'jadwal_konsultasi'   => 'required',
            'keluhan'             => 'required',
            'status_booking'      => 'required',
        ]);

        $booking = BookingKonsultasi::findOrFail($id);


        $booking->update([
            'id_pasien' => $request->id_pasien,
            'id_dokter' => $request->id_dokter,
            'jadwal_konsultasi' => $request->jadwal_konsultasi,
            'status_booking' => $request->status_booking,
            'keluhan' => $request->keluhan,
        ]);

        return redirect()
            ->route('booking.index')
            ->with('success', 'Booking berhasil diperbarui.');
    }

    /**
     * Hapus booking.
     */
    public function destroy($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);

        $booking->delete();

        return redirect()
            ->route('booking.index')
            ->with('success', 'Booking berhasil dihapus.');
    }

    /**
     * Riwayat booking.
     */
    public function riwayat()
    {
        $booking = BookingKonsultasi::orderBy('jadwal_konsultasi', 'desc')
                    ->paginate(10);

        return view('booking.riwayat', compact('booking'));
    }

    /**
     * Ubah status booking menjadi selesai.
     */
    public function selesai($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);

        $booking->update([
            'status_booking' => 'selesai'
        ]);

        return redirect()
            ->route('booking.riwayat')
            ->with('success', 'Status booking berhasil diubah menjadi selesai.');
    }
}
