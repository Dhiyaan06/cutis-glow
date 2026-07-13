<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notifikasi;
use App\Models\BookingKonsultasi;
use Symfony\Component\HttpFoundation\StreamedResponse;

class RealtimeApiController extends Controller
{
    public function stream(Request $request)
    {
        $user = $request->user();

        $response = new StreamedResponse(function () use ($user) {
            // Catat waktu mulai streaming
            $lastChecked = now()->subSeconds(2);

            // Set php timeout limit
            set_time_limit(0);

            while (true) {
                // Periksa apakah ada notifikasi baru untuk user ini
                $newNotifs = Notifikasi::where('id_pengguna', $user->id_pengguna)
                    ->where('created_at', '>=', $lastChecked)
                    ->get();

                if ($newNotifs->isNotEmpty()) {
                    echo "event: notification\n";
                    echo "data: " . json_encode($newNotifs) . "\n\n";
                    $lastChecked = now();
                }

                // Cek status booking ter-update
                $newBookingUpdates = collect();
                if ($user->role === 'pasien') {
                    $pasien = \App\Models\Pasien::where('id_pengguna', $user->id_pengguna)->first();
                    if ($pasien) {
                        $newBookingUpdates = BookingKonsultasi::with('dokter.user')
                            ->where('id_pasien', $pasien->id_pasien)
                            ->where('updated_at', '>=', $lastChecked->subSeconds(3))
                            ->get();
                    }
                } elseif ($user->role === 'dokter') {
                    $dokter = \App\Models\Dokter::where('id_pengguna', $user->id_pengguna)->first();
                    if ($dokter) {
                        $newBookingUpdates = BookingKonsultasi::with('pasien.user')
                            ->where('id_dokter', $dokter->id_dokter)
                            ->where('updated_at', '>=', $lastChecked->subSeconds(3))
                            ->get();
                    }
                }

                if ($newBookingUpdates->isNotEmpty()) {
                    echo "event: booking_update\n";
                    echo "data: " . json_encode($newBookingUpdates) . "\n\n";
                }

                // Kirim detak jantung (heartbeat) untuk mencegah timeout koneksi
                echo "event: heartbeat\n";
                echo "data: {\"status\":\"keep-alive\"}\n\n";

                if (ob_get_level() > 0) {
                    ob_flush();
                }
                flush();

                // Cek apakah koneksi diputus oleh client
                if (connection_aborted()) {
                    break;
                }

                sleep(3);
            }
        });

        $response->headers->set('Content-Type', 'text/event-stream');
        $response->headers->set('Cache-Control', 'no-cache');
        $response->headers->set('Connection', 'keep-alive');
        $response->headers->set('X-Accel-Buffering', 'no'); // Menonaktifkan buffering Nginx

        return $response;
    }
}
