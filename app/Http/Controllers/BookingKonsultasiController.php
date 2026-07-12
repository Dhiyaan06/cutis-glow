<?php

namespace App\Http\Controllers;

use App\Models\BookingKonsultasi;
use Illuminate\Http\Request;

class BookingKonsultasiController extends Controller
{
    // Menampilkan semua booking
    public function index()
    {
        $booking = BookingKonsultasi::orderBy('jadwal_konsultasi', 'desc')->get();

        return view('booking.index', compact('booking'));
    }

     // Menampilkan form booking
    public function create()
    {
        return view('booking.create');
    }

     // Menyimpan data booking
    public function store(Request $request)
    {
        $request->validate([
            'id_pasien' => 'required',
            'id_dokter' => 'required',
            'jadwal_konsultasi' => 'required',
            'keluhan' => 'required'
        ]);

        BookingKonsultasi::create([
            'id_pasien' => $request->id_pasien,
            'id_dokter' => $request->id_dokter,
            'jadwal_konsultasi' => $request->jadwal_konsultasi,
            'keluhan' => $request->keluhan,
            'status_booking' => 'pending'
        ]);

        return redirect()->route('booking.index')
            ->with('success', 'Booking berhasil dibuat.');
    }

     // Menampilkan detail booking
    public function show($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);

        return view('booking.show', compact('booking'));
    }

    // Form edit booking
    public function edit($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);

        return view('booking.edit', compact('booking'));
    }

    // Update booking
    public function update(Request $request, $id)
    {
        $booking = BookingKonsultasi::findOrFail($id);

        $request->validate([
            'id_pasien' => 'required',
            'id_dokter' => 'required',
            'jadwal_konsultasi' => 'required',
            'keluhan' => 'required'
        ]);

        $booking->update([
            'id_pasien' => $request->id_pasien,
            'id_dokter' => $request->id_dokter,
            'jadwal_konsultasi' => $request->jadwal_konsultasi,
            'keluhan' => $request->keluhan
        ]);

        return redirect()->route('booking.index')
            ->with('success', 'Booking berhasil diperbarui.');
    }


    // Hapus booking
    public function destroy($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);

        $booking->delete();

        return redirect()->route('booking.index')
            ->with('success', 'Booking berhasil dihapus.');
    }


    // Riwayat Booking
    public function riwayat()
    {
        $booking = BookingKonsultasi::orderBy('jadwal_konsultasi', 'desc')->get();

        return view('booking.riwayat', compact('booking'));
    }

    // Mengubah status menjadi selesai
    public function selesai($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);

        $booking->status_booking = 'selesai';
        $booking->save();

        return redirect()->back()
            ->with('success', 'Status booking berhasil diubah menjadi selesai.');
    }
}
