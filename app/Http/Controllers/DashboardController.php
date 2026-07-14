<?php

namespace App\Http\Controllers;

use App\Models\Dokter;
use App\Models\Pasien;
use App\Models\BookingKonsultasi;
use App\Models\MasterLayanan;
use App\Models\JadwalDokter;
use App\Models\RiwayatLayanan;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            $totalDokter = Dokter::count();
            $totalPasien = Pasien::count();
            $totalLayanan = MasterLayanan::count();
            $totalBooking = BookingKonsultasi::whereIn('status', ['pending', 'dikonfirmasi'])->count();

            // Booking terbaru (ambil 5 data)
            $recentBookings = BookingKonsultasi::with(['pasien.user', 'dokter.user'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            return view('dashboard.admin', compact(
                'totalDokter',
                'totalPasien',
                'totalLayanan',
                'totalBooking',
                'recentBookings'
            ));
        }

        if ($user->hasRole('dokter')) {
            $dokter = Dokter::where('user_id', $user->id_pengguna)->first();

            $totalBooking = $dokter ? BookingKonsultasi::where('id_dokter', $dokter->id_dokter)
                ->whereIn('status', ['pending', 'dikonfirmasi'])->count() : 0;

            $totalSelesai = $dokter ? BookingKonsultasi::where('id_dokter', $dokter->id_dokter)
                ->where('status', 'selesai')->count() : 0;

            $totalJadwal = $dokter ? JadwalDokter::where('id_dokter', $dokter->id_dokter)
                ->where('status', 'aktif')->count() : 0;

            $recentBookings = $dokter ? BookingKonsultasi::with('pasien.user')
                ->where('id_dokter', $dokter->id_dokter)
                ->orderBy('tanggal_booking', 'desc')
                ->orderBy('jam_booking', 'desc')
                ->limit(5)
                ->get() : collect();

            $mySchedules = $dokter ? JadwalDokter::where('id_dokter', $dokter->id_dokter)
                ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
                ->get() : collect();

            return view('dashboard.dokter', compact(
                'totalBooking',
                'totalSelesai',
                'totalJadwal',
                'recentBookings',
                'mySchedules'
            ));
        }

        if ($user->hasRole('pasien')) {
            $pasien = Pasien::where('id_pengguna', $user->id_pengguna)->first();

            $bookingAktif = $pasien ? BookingKonsultasi::where('id_pasien', $pasien->id_pasien)
                ->whereIn('status', ['pending', 'dikonfirmasi'])->count() : 0;

            $totalTreatment = $pasien ? RiwayatLayanan::where('id_pasien', $pasien->id_pasien)->count() : 0;

            $totalPengeluaran = $pasien ? RiwayatLayanan::where('id_pasien', $pasien->id_pasien)
                ->sum(DB::raw('harga * qty')) : 0;

            $recentBookings = $pasien ? BookingKonsultasi::with('dokter.user')
                ->where('id_pasien', $pasien->id_pasien)
                ->orderBy('tanggal_booking', 'desc')
                ->orderBy('jam_booking', 'desc')
                ->limit(5)
                ->get() : collect();

            $recentTreatments = $pasien ? RiwayatLayanan::with(['dokter.user', 'layanan'])
                ->where('id_pasien', $pasien->id_pasien)
                ->orderBy('tanggal_treatment', 'desc')
                ->limit(5)
                ->get() : collect();

            return view('dashboard.pasien', compact(
                'bookingAktif',
                'totalTreatment',
                'totalPengeluaran',
                'recentBookings',
                'recentTreatments'
            ));
        }

        // Fallback jika role tidak dikenali
        return redirect('/');
    }
}
