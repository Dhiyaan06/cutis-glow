<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Manajemen Booking Konsultasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-700">Data Booking Konsultasi</h3>
                    <a href="{{ route('booking-konsultasi.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm transition font-medium text-sm">
                        + Buat Booking
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-pink-50 border border-pink-200 text-pink-700 p-3 rounded-md mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Search & Filter Form -->
                <form action="{{ route('booking-konsultasi.index') }}" method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-1 w-full gap-2">
                        <!-- Input Search -->
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pasien atau dokter..."
                                class="w-full pl-3 pr-4 py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>

                        <!-- Dropdown Filter Status -->
                        <div class="w-44">
                            <select name="status" class="w-full py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="">Semua Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="dikonfirmasi" {{ request('status') == 'dikonfirmasi' ? 'selected' : '' }}>Dikonfirmasi</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </div>

                        <!-- Dropdown Filter Dokter -->
                        <div class="w-48">
                            <select name="id_dokter" class="w-full py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="">Semua Dokter</option>
                                @foreach($dokterList as $doc)
                                    <option value="{{ $doc->id_dokter }}" {{ request('id_dokter') == $doc->id_dokter ? 'selected' : '' }}>{{ $doc->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-2 w-full md:w-auto justify-end">
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm text-sm transition font-medium">
                            Cari & Filter
                        </button>
                        @if(request('search') || request('status') || request('id_dokter'))
                            <a href="{{ route('booking-konsultasi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm transition font-medium">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-pink-100">
                        <thead>
                            <tr class="bg-pink-50 text-pink-800 border-b border-pink-100">
                                <th class="p-3 text-left text-sm font-semibold">Pasien</th>
                                <th class="p-3 text-left text-sm font-semibold">Dokter</th>
                                <th class="p-3 text-left text-sm font-semibold">Tanggal Booking</th>
                                <th class="p-3 text-left text-sm font-semibold">Jam Booking</th>
                                <th class="p-3 text-center text-sm font-semibold">Status</th>
                                @hasanyrole('admin|dokter')
                                <th class="p-3 text-center text-sm font-semibold">Aksi Cepat</th>
                                @endhasanyrole
                                @role('admin')
                                <th class="p-3 text-center text-sm font-semibold">Pilihan</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($booking as $item)
                                <tr class="hover:bg-pink-50/10 text-gray-600 border-b border-pink-50/30">
                                    <td class="p-3 text-sm font-semibold text-gray-800">
                                        <a href="{{ route('booking-konsultasi.show', $item->id_booking) }}" class="text-pink-600 hover:underline">
                                            {{ $item->pasien->user->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td class="p-3 text-sm">{{ $item->dokter->user->name ?? '-' }}</td>
                                    <td class="p-3 text-sm">{{ \Carbon\Carbon::parse($item->tanggal_booking)->translatedFormat('d M Y') }}</td>
                                    <td class="p-3 text-sm">{{ date('H:i', strtotime($item->jam_booking)) }}</td>
                                    <td class="p-3 text-center text-sm">
                                        @if($item->status === 'pending')
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-800">Pending</span>
                                        @elseif($item->status === 'dikonfirmasi')
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">Dikonfirmasi</span>
                                        @elseif($item->status === 'selesai')
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Selesai</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Batal</span>
                                        @endif
                                    </td>
                                    @hasanyrole('admin|dokter')
                                    <td class="p-3 text-center space-x-1 space-y-1">
                                        @if($item->status === 'pending')
                                            <form action="{{ route('booking-konsultasi.konfirmasi', $item->id_booking) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs px-2.5 py-1 rounded shadow-sm">Konfirmasi</button>
                                            </form>
                                        @endif
                                        @if(in_array($item->status, ['pending', 'dikonfirmasi']))
                                            <form action="{{ route('booking-konsultasi.selesai', $item->id_booking) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 text-white text-xs px-2.5 py-1 rounded shadow-sm">Selesai</button>
                                            </form>
                                            <form action="{{ route('booking-konsultasi.batal', $item->id_booking) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-xs px-2.5 py-1 rounded shadow-sm">Batal</button>
                                            </form>
                                            <a href="{{ route('riwayat-layanan.create', ['booking_id' => $item->id_booking]) }}" class="bg-purple-500 hover:bg-purple-600 text-white text-xs px-2.5 py-1.5 rounded shadow-sm inline-block font-semibold">
                                                Catat Treatment
                                            </a>
                                        @endif
                                    </td>
                                    @endhasanyrole
                                    @role('admin')
                                    <td class="p-3 text-center text-sm">
                                        <a href="{{ route('booking-konsultasi.edit', $item->id_booking) }}" class="text-yellow-600 hover:underline mr-3">Edit</a>
                                        <form action="{{ route('booking-konsultasi.destroy', $item->id_booking) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus data booking ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                    @endrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="p-6 text-center text-gray-400 italic">
                                        Belum ada data booking konsultasi yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $booking->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
