<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Detail Booking Konsultasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-700">Kode Booking: #BK-{{ $booking->id_booking }}</h3>
                    <div>
                        @if($booking->status === 'pending')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                        @elseif($booking->status === 'dikonfirmasi')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Dikonfirmasi</span>
                        @elseif($booking->status === 'selesai')
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Selesai</span>
                        @else
                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Dibatalkan</span>
                        @endif
                    </div>
                </div>

                <div class="border-t border-pink-100 py-4 space-y-4">
                    <!-- Pasien -->
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Nama Pasien</span>
                        <span class="text-sm text-gray-800 col-span-2 font-medium">
                            <a href="{{ route('pasien.show', $booking->id_pasien) }}" class="text-pink-600 hover:underline">
                                {{ $booking->pasien->user->name ?? '-' }}
                            </a>
                        </span>
                    </div>
                    <!-- Dokter -->
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Nama Dokter</span>
                        <span class="text-sm text-gray-800 col-span-2 font-medium">
                            <a href="{{ route('dokter.show', $booking->id_dokter) }}" class="text-pink-600 hover:underline">
                                {{ $booking->dokter->user->name ?? '-' }}
                            </a>
                        </span>
                    </div>
                    <!-- Waktu -->
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Jadwal Rencana</span>
                        <span class="text-sm text-gray-800 col-span-2">
                            {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d F Y') }} pukul {{ date('H:i', strtotime($booking->jam_booking)) }} WIB
                        </span>
                    </div>
                    <!-- Catatan -->
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Catatan / Keluhan</span>
                        <span class="text-sm text-gray-800 col-span-2 bg-pink-50/20 p-3 rounded border border-pink-100 italic">{{ $booking->catatan ?? '-' }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-6 border-t border-pink-50">
                    <div class="space-x-1">
                        @if($booking->status === 'pending')
                            <form action="{{ route('booking-konsultasi.konfirmasi', $booking->id_booking) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-3 py-1.5 rounded shadow transition">Konfirmasi</button>
                            </form>
                        @endif
                        @if(in_array($booking->status, ['pending', 'dikonfirmasi']))
                            <form action="{{ route('booking-konsultasi.selesai', $booking->id_booking) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs px-3 py-1.5 rounded shadow transition">Selesai</button>
                            </form>
                            <form action="{{ route('booking-konsultasi.batal', $booking->id_booking) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-3 py-1.5 rounded shadow transition">Batalkan</button>
                            </form>
                        @endif
                    </div>

                    <div class="space-x-1">
                        <a href="{{ route('booking-konsultasi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium text-sm transition">
                            Kembali
                        </a>
                        <a href="{{ route('booking-konsultasi.edit', $booking->id_booking) }}" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-md font-semibold text-sm transition shadow shadow-sm">
                            Edit Booking
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
