<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight flex items-center space-x-2">
            <img src="/logo.png" alt="Logo" class="h-8 w-8 rounded-full">
            <span>{{ __('Dashboard Pasien Cutis Glow') }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-pink-400 to-pink-500 rounded-2xl shadow-sm p-6 text-white flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">Halo, {{ Auth::user()->name }}!</h3>
                    <p class="text-pink-50 mt-1">Pantau riwayat treatment kulit dan jadwal konsultasi kecantikan Anda di Cutis Glow.</p>
                </div>
                <div class="mt-4 md:mt-0 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl text-sm border border-white/30">
                    <span class="font-semibold">Peran Anda:</span> Pasien Cutis Glow
                </div>
            </div>

            <!-- Cards Widget -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white border-b-4 border-pink-500 rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Booking Konsultasi Aktif</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $bookingAktif }}</p>
                    </div>
                    <div class="p-3 bg-pink-100 rounded-full text-pink-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white border-b-4 border-emerald-500 rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Kunjungan Treatment</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalTreatment }} kali</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-full text-emerald-650">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white border-b-4 border-yellow-500 rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Pengeluaran</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full text-yellow-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M12 14a2 2 0 110-4h4"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Bookings Table -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Jadwal Booking Konsultasi Anda</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-pink-50/50 text-pink-700 border-b border-pink-100">
                                    <th class="p-3 text-sm font-semibold">Dokter</th>
                                    <th class="p-3 text-sm font-semibold">Tanggal</th>
                                    <th class="p-3 text-sm font-semibold">Jam</th>
                                    <th class="p-3 text-sm font-semibold">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $b)
                                    <tr class="border-b border-pink-50/30 hover:bg-pink-50/10">
                                        <td class="p-3 text-sm font-medium text-gray-800">{{ $b->dokter->user->name ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($b->tanggal_booking)->translatedFormat('d F Y') }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ date('H:i', strtotime($b->jam_booking)) }} WIB</td>
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
                                        <td colspan="4" class="p-6 text-center text-gray-400 italic">Belum ada riwayat booking konsultasi.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Treatment History Table -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Riwayat Perawatan & Treatment</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-pink-50/50 text-pink-700 border-b border-pink-100">
                                    <th class="p-3 text-sm font-semibold">Treatment</th>
                                    <th class="p-3 text-sm font-semibold">Dokter</th>
                                    <th class="p-3 text-sm font-semibold">Tanggal</th>
                                    <th class="p-3 text-sm font-semibold">Catatan / Resep</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentTreatments as $t)
                                    <tr class="border-b border-pink-50/30 hover:bg-pink-50/10">
                                        <td class="p-3 text-sm font-medium text-gray-800">{{ $t->layanan->nama_layanan ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ $t->dokter->user->name ?? '-' }}</td>
                                        <td class="p-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($t->tanggal_treatment)->translatedFormat('d F Y') }}</td>
                                        <td class="p-3 text-xs text-gray-500 italic max-w-xs truncate" title="{{ $t->catatan }}">
                                            {{ $t->catatan ?? '-' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-6 text-center text-gray-400 italic">Belum ada riwayat perawatan medis.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
