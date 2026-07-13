<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight flex items-center space-x-2">
            <img src="/logo.png" alt="Logo" class="h-8 w-8 rounded-full">
            <span>{{ __('Dashboard Admin Cutis Glow') }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Cards Widget -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Card 1 -->
                <div class="bg-white border-b-4 border-pink-500 rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Layanan</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalLayanan }}</p>
                    </div>
                    <div class="p-3 bg-pink-100 rounded-full text-pink-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white border-b-4 border-pink-500 rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Dokter</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalDokter }}</p>
                    </div>
                    <div class="p-3 bg-pink-100 rounded-full text-pink-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white border-b-4 border-pink-500 rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Pasien</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalPasien }}</p>
                    </div>
                    <div class="p-3 bg-pink-100 rounded-full text-pink-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="bg-white border-b-4 border-pink-500 rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Booking Aktif</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBooking }}</p>
                    </div>
                    <div class="p-3 bg-pink-100 rounded-full text-pink-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Content Grid without Chart -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Recent Bookings Table (2/3 width) -->
                <div class="bg-white rounded-lg shadow-sm p-6 md:col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-700">Booking Konsultasi Terbaru</h3>
                        <a href="{{ route('booking-konsultasi.index') }}" class="text-pink-600 hover:text-pink-700 font-semibold text-sm">Lihat Semua &rarr;</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-pink-50/50 text-pink-700 border-b border-pink-100">
                                    <th class="p-3 text-sm font-semibold">Pasien</th>
                                    <th class="p-3 text-sm font-semibold">Dokter</th>
                                    <th class="p-3 text-sm font-semibold">Tanggal</th>
                                    <th class="p-3 text-sm font-semibold">Jam</th>
                                    <th class="p-3 text-sm font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $b)
                                    <tr class="border-b border-pink-50/30 hover:bg-pink-50/10">
                                        <td class="p-3 text-sm font-medium text-gray-800">{{ $b->pasien->user->name ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ $b->dokter->user->name ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($b->tanggal_booking)->translatedFormat('d F Y') }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ date('H:i', strtotime($b->jam_booking)) }}</td>
                                        <td class="p-3 text-sm">
                                            @if($b->status === 'pending')
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                                            @elseif($b->status === 'dikonfirmasi')
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Dikonfirmasi</span>
                                            @elseif($b->status === 'selesai')
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Selesai</span>
                                            @else
                                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Batal</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-gray-400 italic">Belum ada booking konsultasi masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Recent Activity / Info (1/3 width) -->
                <div class="bg-white rounded-lg shadow-sm p-6 md:col-span-1">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Aktivitas Terbaru</h3>
                    <div class="space-y-4">
                        <div class="border-l-4 border-pink-500 pl-3 py-1">
                            <p class="text-xs text-gray-400">Pemberitahuan Sistem</p>
                            <p class="text-sm text-gray-600 font-semibold">Semua sistem sinkronisasi API berjalan lancar.</p>
                        </div>
                        <div class="border-l-4 border-emerald-500 pl-3 py-1">
                            <p class="text-xs text-gray-400">Update Booking</p>
                            <p class="text-sm text-gray-600">Gunakan menu <strong>Booking</strong> untuk konfirmasi Reservasi Masuk.</p>
                        </div>
                        <div class="border-l-4 border-blue-500 pl-3 py-1">
                            <p class="text-xs text-gray-400">Jadwal Praktik</p>
                            <p class="text-sm text-gray-600">Pastikan dokter memiliki jadwal aktif untuk menerima booking.</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
