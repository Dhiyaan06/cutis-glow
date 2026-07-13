<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Manajemen Jadwal Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-700">Jadwal Operasional Dokter</h3>
                    <a href="{{ route('jadwal-dokter.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm transition font-medium text-sm">
                        + Tambah Jadwal
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-pink-50 border border-pink-200 text-pink-700 p-3 rounded-md mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Search & Filter Form -->
                <form action="{{ route('jadwal-dokter.index') }}" method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-1 w-full gap-2">
                        <!-- Input Search -->
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari hari atau nama dokter..."
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

                        <!-- Dropdown Filter Status -->
                        <div class="w-44">
                            <select name="status" class="w-full py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="">Semua Status</option>
                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-2 w-full md:w-auto justify-end">
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm text-sm transition font-medium">
                            Cari & Filter
                        </button>
                        @if(request('search') || request('id_dokter') || request('status'))
                            <a href="{{ route('jadwal-dokter.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm transition font-medium">
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
                                <th class="p-3 text-left text-sm font-semibold">Nama Dokter</th>
                                <th class="p-3 text-left text-sm font-semibold">Hari Praktek</th>
                                <th class="p-3 text-left text-sm font-semibold">Jam Mulai</th>
                                <th class="p-3 text-left text-sm font-semibold">Jam Selesai</th>
                                <th class="p-3 text-center text-sm font-semibold">Status</th>
                                <th class="p-3 text-center text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($jadwal as $item)
                                <tr class="hover:bg-pink-50/10 text-gray-600 border-b border-pink-50/30">
                                    <td class="p-3 text-sm font-semibold text-gray-800">{{ $item->dokter->user->name ?? '-' }}</td>
                                    <td class="p-3 text-sm font-semibold text-pink-600">{{ $item->hari }}</td>
                                    <td class="p-3 text-sm font-mono">{{ date('H:i', strtotime($item->jam_mulai)) }}</td>
                                    <td class="p-3 text-sm font-mono">{{ date('H:i', strtotime($item->jam_selesai)) }}</td>
                                    <td class="p-3 text-center text-sm">
                                        @if($item->status === 'aktif')
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                                        @else
                                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center text-sm">
                                        <a href="{{ route('jadwal-dokter.edit', $item->id_jadwal) }}" class="text-yellow-600 hover:underline mr-3">Edit</a>
                                        <form action="{{ route('jadwal-dokter.destroy', $item->id_jadwal) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus jadwal praktek ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-400 italic">
                                        Belum ada jadwal praktek dokter yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $jadwal->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
