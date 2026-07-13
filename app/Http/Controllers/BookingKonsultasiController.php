<?php

namespace App\Http\Controllers;

use App\Models\BookingKonsultasi;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingKonsultasiController extends Controller
{
    // Helper untuk konversi hari Inggris ke Indonesia
    private function getIndoDayName($dateString)
    {
        $dayOfWeek = date('N', strtotime($dateString));
        $days = [
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
            7 => 'Minggu'
        ];
        return $days[$dayOfWeek] ?? '';
    }

    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $dokterId = $request->input('id_dokter');

        $query = BookingKonsultasi::with(['pasien.user', 'dokter.user']);

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
                });
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($dokterId) {
            $query->where('id_dokter', $dokterId);
        }

        $booking = $query->orderBy('tanggal_booking', 'desc')
            ->orderBy('jam_booking', 'desc')
            ->paginate(10)
            ->withQueryString();

        $dokterList = Dokter::with('user')->get();

        return view('booking.index', compact('booking', 'dokterList'));
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->hasRole('pasien')) {
            $pasienList = Pasien::with('user')->where('id_pengguna', $user->id_pengguna)->get();
        } else {
            $pasienList = Pasien::with('user')->get();
        }
        $dokterList = Dokter::with('user')->get();
        return view('booking.create', compact('pasienList', 'dokterList'));
    }

     public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->hasRole('pasien')) {
            $pasien = Pasien::where('id_pengguna', $user->id_pengguna)->first();
            $request->merge(['id_pasien' => $pasien ? $pasien->id_pasien : null]);
        }

        $request->validate([
            'id_pasien' => 'required|exists:pasien,id_pasien',
            'id_dokter' => 'required|exists:dokter,id_dokter',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_booking' => 'required',
            'catatan' => 'nullable|string',
        ]);

        $id_dokter = $request->id_dokter;
        $tanggal_booking = $request->tanggal_booking;
        $jam_booking = $request->jam_booking;

        // 1. VALIDASI: Harus sesuai jadwal dokter
        $hariIndo = $this->getIndoDayName($tanggal_booking);
        $schedules = JadwalDokter::where('id_dokter', $id_dokter)
            ->where('hari', $hariIndo)
            ->where('status', 'aktif')
            ->get();

        if ($schedules->isEmpty()) {
            return redirect()->back()->withInput()->withErrors([
                'tanggal_booking' => "Dokter tidak memiliki jadwal praktek pada hari $hariIndo."
            ]);
        }

        $isValidTime = false;
        $formattedJamInput = date('H:i:s', strtotime($jam_booking));
        foreach ($schedules as $sched) {
            $start = date('H:i:s', strtotime($sched->jam_mulai));
            $end = date('H:i:s', strtotime($sched->jam_selesai));
            if ($formattedJamInput >= $start && $formattedJamInput <= $end) {
                $isValidTime = true;
                break;
            }
        }

        if (!$isValidTime) {
            return redirect()->back()->withInput()->withErrors([
                'jam_booking' => "Jam booking ($jam_booking) tidak masuk dalam shift praktek dokter pada hari $hariIndo."
            ]);
        }

        // 2. VALIDASI: Cek double booking
        $exists = BookingKonsultasi::where('id_dokter', $id_dokter)
            ->where('tanggal_booking', $tanggal_booking)
            ->where('jam_booking', $formattedJamInput)
            ->where('status', '!=', 'dibatalkan')
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->withErrors([
                'jam_booking' => "Jadwal dokter tersebut pada tanggal $tanggal_booking jam $jam_booking sudah di-booking oleh pasien lain."
            ]);
        }

        // 3. Simpan Booking
        $booking = BookingKonsultasi::create([
            'id_pasien' => $request->id_pasien,
            'id_dokter' => $request->id_dokter,
            'tanggal_booking' => $tanggal_booking,
            'jam_booking' => $formattedJamInput,
            'status' => 'pending',
            'catatan' => $request->catatan,
        ]);

        // Buat notifikasi ke dokter dan pasien
        $pasien = Pasien::with('user')->find($request->id_pasien);
        $dokter = Dokter::with('user')->find($request->id_dokter);

        Notifikasi::create([
            'id_pengguna' => $pasien->user->id_pengguna,
            'judul' => 'Booking Baru Berhasil',
            'pesan' => "Booking Anda dengan {$dokter->user->name} pada {$tanggal_booking} jam {$jam_booking} berhasil dibuat dan menunggu konfirmasi.",
            'tipe' => 'booking'
        ]);

        Notifikasi::create([
            'id_pengguna' => $dokter->user->id_pengguna,
            'judul' => 'Reservasi Baru Masuk',
            'pesan' => "Pasien {$pasien->user->name} telah memesan konsultasi pada {$tanggal_booking} jam {$jam_booking}.",
            'tipe' => 'booking'
        ]);

        return redirect()->route('booking-konsultasi.index')->with('success', 'Booking konsultasi berhasil dibuat!');
    }

    public function show($id)
    {
        $booking = BookingKonsultasi::with(['pasien.user', 'dokter.user', 'riwayat'])->findOrFail($id);

        $user = auth()->user();
        if ($user->hasRole('pasien')) {
            $pasien = Pasien::where('id_pengguna', $user->id_pengguna)->first();
            if (!$pasien || $booking->id_pasien !== $pasien->id_pasien) {
                abort(403, 'Unauthorized action.');
            }
        }

        return view('booking.show', compact('booking'));
    }

    public function edit($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);
        $pasienList = Pasien::with('user')->get();
        $dokterList = Dokter::with('user')->get();
        return view('booking.edit', compact('booking', 'pasienList', 'dokterList'));
    }

    public function update(Request $request, $id)
    {
        $booking = BookingKonsultasi::findOrFail($id);

        $request->validate([
            'id_pasien' => 'required|exists:pasien,id_pasien',
            'id_dokter' => 'required|exists:dokter,id_dokter',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_booking' => 'required',
            'status' => 'required|in:pending,dikonfirmasi,selesai,dibatalkan',
            'catatan' => 'nullable|string',
        ]);

        $id_dokter = $request->id_dokter;
        $tanggal_booking = $request->tanggal_booking;
        $jam_booking = $request->jam_booking;

        // Validasi Jadwal
        $hariIndo = $this->getIndoDayName($tanggal_booking);
        $schedules = JadwalDokter::where('id_dokter', $id_dokter)
            ->where('hari', $hariIndo)
            ->where('status', 'aktif')
            ->get();

        if ($schedules->isEmpty()) {
            return redirect()->back()->withInput()->withErrors([
                'tanggal_booking' => "Dokter tidak memiliki jadwal praktek pada hari $hariIndo."
            ]);
        }

        $isValidTime = false;
        $formattedJamInput = date('H:i:s', strtotime($jam_booking));
        foreach ($schedules as $sched) {
            $start = date('H:i:s', strtotime($sched->jam_mulai));
            $end = date('H:i:s', strtotime($sched->jam_selesai));
            if ($formattedJamInput >= $start && $formattedJamInput <= $end) {
                $isValidTime = true;
                break;
            }
        }

        if (!$isValidTime) {
            return redirect()->back()->withInput()->withErrors([
                'jam_booking' => "Jam booking ($jam_booking) tidak masuk dalam shift praktek dokter pada hari $hariIndo."
            ]);
        }

        // Cek double booking (kecuali untuk record booking itu sendiri)
        $exists = BookingKonsultasi::where('id_dokter', $id_dokter)
            ->where('tanggal_booking', $tanggal_booking)
            ->where('jam_booking', $formattedJamInput)
            ->where('id_booking', '!=', $id)
            ->where('status', '!=', 'dibatalkan')
            ->exists();

        if ($exists) {
            return redirect()->back()->withInput()->withErrors([
                'jam_booking' => "Jadwal dokter tersebut pada tanggal $tanggal_booking jam $jam_booking sudah di-booking oleh pasien lain."
            ]);
        }

        $oldStatus = $booking->status;
        $booking->update([
            'id_pasien' => $request->id_pasien,
            'id_dokter' => $request->id_dokter,
            'tanggal_booking' => $tanggal_booking,
            'jam_booking' => $formattedJamInput,
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        // Kirim notifikasi status perubahan
        if ($oldStatus != $request->status) {
            $pasien = Pasien::with('user')->find($booking->id_pasien);
            Notifikasi::create([
                'id_pengguna' => $pasien->user->id_pengguna,
                'judul' => 'Status Booking Diperbarui',
                'pesan' => "Status booking Anda untuk tanggal {$tanggal_booking} jam {$jam_booking} telah diubah menjadi: " . strtoupper($request->status),
                'tipe' => 'booking'
            ]);
        }

        return redirect()->route('booking-konsultasi.index')->with('success', 'Booking berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);
        $booking->delete();

        return redirect()->route('booking-konsultasi.index')->with('success', 'Booking berhasil dihapus!');
    }

    // Aksi cepat Konfirmasi
    public function konfirmasi($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);
        $booking->status = 'dikonfirmasi';
        $booking->save();

        $pasien = Pasien::with('user')->find($booking->id_pasien);
        Notifikasi::create([
            'id_pengguna' => $pasien->user->id_pengguna,
            'judul' => 'Booking Dikonfirmasi',
            'pesan' => "Booking Anda pada tanggal {$booking->tanggal_booking} jam {$booking->jam_booking} telah dikonfirmasi oleh Admin.",
            'tipe' => 'booking'
        ]);

        return redirect()->back()->with('success', 'Booking berhasil dikonfirmasi!');
    }

    // Aksi cepat Selesai
    public function selesai($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);
        $booking->status = 'selesai';
        $booking->save();

        return redirect()->route('riwayat-layanan.create', ['booking_id' => $booking->id_booking])
            ->with('success', 'Booking selesai! Silakan tambahkan layanan/treatment yang diberikan.');
    }

    // Aksi cepat Batal
    public function batal($id)
    {
        $booking = BookingKonsultasi::findOrFail($id);
        $booking->status = 'dibatalkan';
        $booking->save();

        $pasien = Pasien::with('user')->find($booking->id_pasien);
        Notifikasi::create([
            'id_pengguna' => $pasien->user->id_pengguna,
            'judul' => 'Booking Dibatalkan',
            'pesan' => "Booking Anda pada tanggal {$booking->tanggal_booking} jam {$booking->jam_booking} telah dibatalkan.",
            'tipe' => 'booking'
        ]);

        return redirect()->back()->with('success', 'Booking berhasil dibatalkan.');
    }
}
