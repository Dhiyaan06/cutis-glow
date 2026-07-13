<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight flex items-center space-x-2">
            <img src="/logo.png" alt="Logo" class="h-8 w-8 rounded-full">
            <span>{{ __('Dashboard Dokter Cutis Glow') }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-pink-500 to-rose-400 rounded-2xl shadow-sm p-6 text-white flex flex-col md:flex-row justify-between items-center">
                <div>
                    <h3 class="text-2xl font-bold">Selamat Datang Kembali, {{ Auth::user()->name }}!</h3>
                    <p class="text-pink-100 mt-1">Kelola jadwal praktik dan layani pasien konsultasi Anda dengan mudah.</p>
                </div>
                <div class="mt-4 md:mt-0 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-xl text-sm border border-white/30">
                    <span class="font-semibold">Peran Anda:</span> Dokter Spesialis
                </div>
            </div>

            <!-- Cards Widget -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white border-b-4 border-pink-500 rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Booking Konsultasi Aktif</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalBooking }}</p>
                    </div>
                    <div class="p-3 bg-pink-100 rounded-full text-pink-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="bg-white border-b-4 border-emerald-500 rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Treatment Selesai</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalSelesai }}</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-full text-emerald-650">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="bg-white border-b-4 border-blue-500 rounded-lg shadow-sm p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Jadwal Praktik Aktif</p>
                        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $totalJadwal }} sesi</p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full text-blue-600">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                </div>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Bookings assigned to Doctor (2/3 width) -->
                <div class="bg-white rounded-lg shadow-sm p-6 md:col-span-2">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Reservasi Konsultasi Pasien Anda</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-pink-50/50 text-pink-700 border-b border-pink-100">
                                    <th class="p-3 text-sm font-semibold">Nama Pasien</th>
                                    <th class="p-3 text-sm font-semibold">Tanggal</th>
                                    <th class="p-3 text-sm font-semibold">Jam</th>
                                    <th class="p-3 text-sm font-semibold">Status</th>
                                    <th class="p-3 text-sm font-semibold">Catatan Pasien</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentBookings as $b)
                                    <tr class="border-b border-pink-50/30 hover:bg-pink-50/10">
                                        <td class="p-3 text-sm font-medium text-gray-800">{{ $b->pasien->user->name ?? '-' }}</td>
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
                                        <td class="p-3 text-xs text-gray-500 italic max-w-xs truncate">{{ $b->catatan ?? 'Tidak ada catatan' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-gray-400 italic">Belum ada reservasi konsultasi yang ditugaskan ke Anda.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Doctor Schedules (1/3 width) -->
                <div class="bg-white rounded-lg shadow-sm p-6 md:col-span-1">
                    <h3 class="text-lg font-bold text-gray-700 mb-4">Jadwal Praktik Anda</h3>
                    <div class="space-y-3">
                        @forelse($mySchedules as $s)
                            <div class="flex items-center justify-between p-3 rounded-lg bg-pink-50/50 border border-pink-100/50">
                                <div>
                                    <p class="text-sm font-semibold text-gray-800">{{ $s->hari }}</p>
                                    <p class="text-xs text-gray-500">{{ date('H:i', strtotime($s->jam_mulai)) }} - {{ date('H:i', strtotime($s->jam_selesai)) }}</p>
                                </div>
                                <div>
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $s->status === 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-gray-100 text-gray-800' }}">
                                        {{ ucfirst($s->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-400 italic text-center py-4">Belum ada jadwal praktik yang terdaftar.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
