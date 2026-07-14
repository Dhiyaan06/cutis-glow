<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BookingKonsultasi;
use App\Models\Pasien;
use App\Models\Dokter;
use App\Models\JadwalDokter;
use App\Models\RiwayatLayanan;
use App\Models\Notifikasi;
use Illuminate\Http\Request;

class BookingApiController extends Controller
{
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
        $user = $request->user();

        if ($user->role === 'pasien') {
            $pasien = Pasien::where('id_pengguna', $user->id_pengguna)->first();
            if (!$pasien) {
                return response()->json(['status' => 'success', 'data' => []]);
            }
            $bookings = BookingKonsultasi::with('dokter.user')
                ->where('id_pasien', $pasien->id_pasien)
                ->orderBy('tanggal_booking', 'desc')
                ->orderBy('jam_booking', 'desc')
                ->get();
        } elseif ($user->role === 'dokter') {
            $dokter = Dokter::where('user_id', $user->id_pengguna)->first();
            if (!$dokter) {
                return response()->json(['status' => 'success', 'data' => []]);
            }
            $bookings = BookingKonsultasi::with('pasien.user')
                ->where('id_dokter', $dokter->id_dokter)
                ->orderBy('tanggal_booking', 'desc')
                ->orderBy('jam_booking', 'desc')
                ->get();
        } else {
            $bookings = BookingKonsultasi::with(['pasien.user', 'dokter.user'])
                ->orderBy('tanggal_booking', 'desc')
                ->orderBy('jam_booking', 'desc')
                ->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $bookings
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $pasien = Pasien::where('id_pengguna', $user->id_pengguna)->first();

        if (!$pasien) {
            return response()->json([
                'status' => 'error',
                'message' => 'Hanya pengguna dengan profil Pasien yang bisa memesan jadwal.'
            ], 403);
        }

        $request->validate([
            'id_dokter' => 'required|exists:dokter,id_dokter',
            'tanggal_booking' => 'required|date|after_or_equal:today',
            'jam_booking' => 'required',
            'catatan' => 'nullable|string',
        ]);

        $id_dokter = $request->id_dokter;
        $tanggal_booking = $request->tanggal_booking;
        $jam_booking = $request->jam_booking;

        // 1. VALIDASI: Jadwal praktek dokter
        $hariIndo = $this->getIndoDayName($tanggal_booking);
        $schedules = JadwalDokter::where('id_dokter', $id_dokter)
            ->where('hari', $hariIndo)
            ->where('status', 'aktif')
            ->get();

        if ($schedules->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => "Dokter tidak memiliki jadwal praktek pada hari $hariIndo."
            ], 422);
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
            return response()->json([
                'status' => 'error',
                'message' => "Waktu booking ($jam_booking) di luar jam praktek dokter pada hari $hariIndo."
            ], 422);
        }

        // 2. VALIDASI: Mencegah double booking
        $exists = BookingKonsultasi::where('id_dokter', $id_dokter)
            ->where('tanggal_booking', $tanggal_booking)
            ->where('jam_booking', $formattedJamInput)
            ->where('status', '!=', 'dibatalkan')
            ->exists();

        if ($exists) {
            return response()->json([
                'status' => 'error',
                'message' => "Jadwal dokter pada tanggal $tanggal_booking jam $jam_booking sudah dipesan pasien lain."
            ], 422);
        }

        // 3. Simpan
        $booking = BookingKonsultasi::create([
            'id_pasien' => $pasien->id_pasien,
            'id_dokter' => $id_dokter,
            'tanggal_booking' => $tanggal_booking,
            'jam_booking' => $formattedJamInput,
            'status' => 'pending',
            'catatan' => $request->catatan,
        ]);

        // Tambah notifikasi ke user & dokter
        $dokter = Dokter::with('user')->find($id_dokter);
        Notifikasi::create([
            'id_pengguna' => $user->id_pengguna,
            'judul' => 'Booking Menunggu Konfirmasi',
            'pesan' => "Booking dengan {$dokter->user->name} pada {$tanggal_booking} jam {$jam_booking} telah dikirim.",
            'tipe' => 'booking'
        ]);

        Notifikasi::create([
            'id_pengguna' => $dokter->user->id_pengguna,
            'judul' => 'Reservasi Baru Masuk',
            'pesan' => "Pasien {$user->name} memesan konsultasi pada {$tanggal_booking} jam {$jam_booking}.",
            'tipe' => 'booking'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Booking berhasil dibuat',
            'data' => $booking
        ], 201);
    }

    public function riwayat(Request $request)
    {
        $user = $request->user();
        if ($user->role === 'pasien') {
            $pasien = Pasien::where('id_pengguna', $user->id_pengguna)->first();
            if (!$pasien) {
                return response()->json(['status' => 'success', 'data' => []]);
            }
            $riwayat = RiwayatLayanan::with(['dokter.user', 'layanan'])
                ->where('id_pasien', $pasien->id_pasien)
                ->orderBy('tanggal_treatment', 'desc')
                ->get();
        } else {
            $riwayat = RiwayatLayanan::with(['pasien.user', 'dokter.user', 'layanan'])
                ->orderBy('tanggal_treatment', 'desc')
                ->get();
        }

        return response()->json([
            'status' => 'success',
            'data' => $riwayat
        ]);
    }

    public function notifications(Request $request)
    {
        $notifications = Notifikasi::where('id_pengguna', $request->user()->id_pengguna)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $notifications
        ]);
    }

    public function markNotificationRead(Request $request, $id)
    {
        $notif = Notifikasi::where('id_pengguna', $request->user()->id_pengguna)->findOrFail($id);
        $notif->status_baca = 'dibaca';
        $notif->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Notifikasi ditandai dibaca'
        ]);
    }
}
