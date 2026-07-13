<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Riwayat Treatment Pasien') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-700">Catatan Riwayat Treatment & Layanan</h3>
                    @hasanyrole('admin|dokter')
                    <a href="{{ route('riwayat-layanan.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm transition font-medium text-sm">
                        + Catat Treatment Baru
                    </a>
                    @endhasanyrole
                </div>

                @if(session('success'))
                    <div class="bg-pink-50 border border-pink-200 text-pink-700 p-3 rounded-md mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Search & Filter Form -->
                <form action="{{ route('riwayat-layanan.index') }}" method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-1 w-full gap-2">
                        <!-- Input Search -->
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama pasien, dokter, atau jenis layanan..."
                                class="w-full pl-3 pr-4 py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
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
                        @if(request('search') || request('id_dokter'))
                            <a href="{{ route('riwayat-layanan.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm transition font-medium">
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
                                <th class="p-3 text-left text-sm font-semibold">Tanggal</th>
                                <th class="p-3 text-left text-sm font-semibold">Pasien</th>
                                <th class="p-3 text-left text-sm font-semibold">Dokter</th>
                                <th class="p-3 text-left text-sm font-semibold">Layanan</th>
                                <th class="p-3 text-left text-sm font-semibold">Harga</th>
                                <th class="p-3 text-center text-sm font-semibold">Qty</th>
                                <th class="p-3 text-left text-sm font-semibold">Catatan Medis</th>
                                @role('admin')
                                <th class="p-3 text-center text-sm font-semibold">Aksi</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($riwayat as $item)
                                <tr class="hover:bg-pink-50/10 text-gray-600 border-b border-pink-50/30">
                                    <td class="p-3 text-sm">{{ \Carbon\Carbon::parse($item->tanggal_treatment)->translatedFormat('d M Y') }}</td>
                                    <td class="p-3 text-sm font-semibold text-gray-800">{{ $item->pasien->user->name ?? '-' }}</td>
                                    <td class="p-3 text-sm">{{ $item->dokter->user->name ?? '-' }}</td>
                                    <td class="p-3 text-sm font-semibold text-pink-600">{{ $item->layanan->nama_layanan ?? '-' }}</td>
                                    <td class="p-3 text-sm font-semibold text-emerald-600">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="p-3 text-center text-sm">{{ $item->qty }}</td>
                                    <td class="p-3 text-sm max-w-xs truncate italic">{{ $item->catatan ?? '-' }}</td>
                                    @role('admin')
                                    <td class="p-3 text-center text-sm">
                                        <a href="{{ route('riwayat-layanan.edit', $item->id_riwayat) }}" class="text-yellow-600 hover:underline mr-3">Edit</a>
                                        <form action="{{ route('riwayat-layanan.destroy', $item->id_riwayat) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus catatan riwayat treatment ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                    @endrole
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-6 text-center text-gray-400 italic">
                                        Belum ada catatan riwayat treatment yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $riwayat->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
