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

    // Aksi cepat: Konfirmasi booking (admin/dokter)
    public function konfirmasi(Request $request, $id)
    {
        $user = $request->user();
        if (!in_array($user->role, ['admin', 'dokter'])) {
            return response()->json(['status' => 'error', 'message' => 'Tidak diizinkan.'], 403);
        }

        $booking = BookingKonsultasi::findOrFail($id);
        $booking->status = 'dikonfirmasi';
        $booking->save();

        $pasien = Pasien::with('user')->find($booking->id_pasien);
        if ($pasien) {
            Notifikasi::create([
                'id_pengguna' => $pasien->user->id_pengguna,
                'judul' => 'Booking Dikonfirmasi',
                'pesan' => "Booking Anda pada tanggal {$booking->tanggal_booking} jam {$booking->jam_booking} telah dikonfirmasi.",
                'tipe' => 'booking'
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Booking berhasil dikonfirmasi', 'data' => $booking]);
    }

    // Aksi cepat: Batalkan booking (admin/dokter)
    public function batal(Request $request, $id)
    {
        $user = $request->user();
        if (!in_array($user->role, ['admin', 'dokter'])) {
            return response()->json(['status' => 'error', 'message' => 'Tidak diizinkan.'], 403);
        }

        $booking = BookingKonsultasi::findOrFail($id);
        $booking->status = 'dibatalkan';
        $booking->save();

        $pasien = Pasien::with('user')->find($booking->id_pasien);
        if ($pasien) {
            Notifikasi::create([
                'id_pengguna' => $pasien->user->id_pengguna,
                'judul' => 'Booking Dibatalkan',
                'pesan' => "Booking Anda pada tanggal {$booking->tanggal_booking} jam {$booking->jam_booking} telah dibatalkan.",
                'tipe' => 'booking'
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Booking berhasil dibatalkan', 'data' => $booking]);
    }

    // Aksi cepat: Selesaikan booking + catat riwayat treatment (admin/dokter)
    public function selesai(Request $request, $id)
    {
        $user = $request->user();
        if (!in_array($user->role, ['admin', 'dokter'])) {
            return response()->json(['status' => 'error', 'message' => 'Tidak diizinkan.'], 403);
        }

        $request->validate([
            'id_layanan' => 'required|exists:master_layanan,id_layanan',
            'tanggal_treatment' => 'required|date',
            'qty' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $booking = BookingKonsultasi::findOrFail($id);
        $layanan = \App\Models\MasterLayanan::findOrFail($request->id_layanan);
        $hargaAkhir = $layanan->harga - ($layanan->harga * $layanan->diskon / 100);

        $riwayat = RiwayatLayanan::create([
            'id_booking' => $booking->id_booking,
            'id_pasien' => $booking->id_pasien,
            'id_dokter' => $booking->id_dokter,
            'id_layanan' => $request->id_layanan,
            'tanggal_treatment' => $request->tanggal_treatment,
            'status' => 'selesai',
            'catatan' => $request->catatan,
            'harga' => $hargaAkhir,
            'qty' => $request->qty,
        ]);

        $booking->status = 'selesai';
        $booking->save();

        $pasien = Pasien::with('user')->find($booking->id_pasien);
        if ($pasien) {
            Notifikasi::create([
                'id_pengguna' => $pasien->user->id_pengguna,
                'judul' => 'Treatment Selesai',
                'pesan' => "Booking Anda pada tanggal {$booking->tanggal_booking} jam {$booking->jam_booking} telah selesai.",
                'tipe' => 'booking'
            ]);
        }

        return response()->json(['status' => 'success', 'message' => 'Booking selesai & riwayat tercatat', 'data' => $riwayat]);
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
