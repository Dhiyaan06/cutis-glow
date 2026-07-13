<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Manajemen Dokter Klinik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-700">Data Dokter Cutis Glow</h3>
                    @role('admin')
                    <a href="{{ route('dokter.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm transition font-medium text-sm">
                        + Tambah Dokter
                    </a>
                    @endrole
                </div>

                @if(session('success'))
                    <div class="bg-pink-50 border border-pink-200 text-pink-700 p-3 rounded-md mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Search & Filter Form -->
                <form action="{{ route('dokter.index') }}" method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-1 w-full gap-2">
                        <!-- Input Search -->
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau spesialis..."
                                class="w-full pl-3 pr-4 py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>

                        <!-- Dropdown Filter Spesialis -->
                        <div class="w-48">
                            <select name="spesialis" class="w-full py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="">Semua Spesialis</option>
                                @foreach($specialties as $spec)
                                    <option value="{{ $spec }}" {{ request('spesialis') == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-2 w-full md:w-auto justify-end">
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm text-sm transition font-medium">
                            Cari & Filter
                        </button>
                        @if(request('search') || request('spesialis'))
                            <a href="{{ route('dokter.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm transition font-medium">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                @role('admin')
                <!-- Tabel -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-pink-100">
                        <thead>
                            <tr class="bg-pink-50 text-pink-800 border-b border-pink-100">
                                <th class="p-3 text-left text-sm font-semibold">Nama</th>
                                <th class="p-3 text-left text-sm font-semibold">Spesialis</th>
                                <th class="p-3 text-left text-sm font-semibold">No STR</th>
                                <th class="p-3 text-left text-sm font-semibold">No HP</th>
                                <th class="p-3 text-center text-sm font-semibold">Status</th>
                                <th class="p-3 text-center text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($dokter as $item)
                                <tr class="hover:bg-pink-50/10 text-gray-600 border-b border-pink-50/30">
                                    <td class="p-3 text-sm font-semibold text-gray-800">
                                        <a href="{{ route('dokter.show', $item->id_dokter) }}" class="text-pink-600 hover:underline">
                                            {{ $item->user->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td class="p-3 text-sm">{{ $item->spesialis }}</td>
                                    <td class="p-3 text-sm font-mono text-xs">{{ $item->no_str }}</td>
                                    <td class="p-3 text-sm">{{ $item->no_hp }}</td>
                                    <td class="p-3 text-center">
                                        @if(($item->user->status_aktif ?? 'aktif') === 'aktif')
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                                        @else
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Nonaktif</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center text-sm">
                                        <a href="{{ route('dokter.edit', $item->id_dokter) }}" class="text-yellow-600 hover:underline mr-3">Edit</a>
                                        <form action="{{ route('dokter.destroy', $item->id_dokter) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus dokter ini? Akun login terkait juga akan terhapus.')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-400 italic">
                                        Belum ada data dokter yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @else
                <!-- Grid Card Cantik Dokter untuk Pasien -->
                <div x-data="{ activeDokter: null }">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($dokter as $item)
                            <div @click="activeDokter = {{ json_encode($item->load('user')) }}"
                                class="bg-white border-2 border-pink-100 hover:border-pink-300 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 cursor-pointer flex flex-col justify-between items-center text-center transform hover:-translate-y-1">
                                <div class="flex flex-col items-center w-full">
                                    <div class="w-20 h-20 bg-pink-50 rounded-full flex items-center justify-center border-2 border-pink-100 mb-4 shadow-inner">
                                        <svg class="w-10 h-10 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <h4 class="font-bold text-lg text-gray-800">{{ $item->user->name ?? '-' }}</h4>
                                    <p class="text-pink-600 font-bold text-xs mt-1">{{ $item->spesialis }}</p>

                                    <div class="mt-4 px-3 py-1.5 bg-gray-50 rounded-xl inline-flex items-center space-x-1.5 text-xs text-gray-500">
                                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <span>{{ $item->jadwal_praktek }}</span>
                                    </div>
                                </div>
                                <button class="mt-6 w-full py-2.5 bg-pink-50 hover:bg-pink-100 text-pink-650 font-bold text-sm rounded-xl transition">
                                    Lihat Detail
                                </button>
                            </div>
                        @empty
                            <div class="col-span-3 bg-white border border-pink-100 rounded-2xl p-8 text-center text-gray-400 italic">
                                Belum ada data dokter yang tersedia.
                            </div>
                        @endforelse
                    </div>

                    <!-- Alpine.js Modal Detail Dokter -->
                    <div x-show="activeDokter" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                            <!-- Background overlay -->
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="activeDokter = null">
                                <div class="absolute inset-0 bg-gray-500 opacity-60"></div>
                            </div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <!-- Modal Content Box -->
                            <template x-if="activeDokter">
                            <div x-show="activeDokter"
                                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-t-8 border-pink-500">

                                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
                                    <div class="flex items-center space-x-4 mb-6">
                                        <div class="w-16 h-16 bg-pink-50 rounded-full flex items-center justify-center border border-pink-200 shadow-sm shrink-0">
                                            <svg class="w-8 h-8 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-800" x-text="activeDokter.user ? activeDokter.user.name : ''"></h3>
                                            <p class="text-sm font-semibold text-pink-650" x-text="activeDokter.spesialis"></p>
                                        </div>
                                    </div>

                                    <div class="border-t border-pink-100 py-4 space-y-3">
                                        <div class="grid grid-cols-3 gap-2">
                                            <span class="text-sm font-semibold text-gray-500">Nomor STR</span>
                                            <span class="text-sm text-gray-800 col-span-2 font-mono text-xs" x-text="activeDokter.no_str"></span>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <span class="text-sm font-semibold text-gray-500">Nomor HP</span>
                                            <span class="text-sm text-gray-800 col-span-2" x-text="activeDokter.no_hp"></span>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <span class="text-sm font-semibold text-gray-500">Alamat Klinik</span>
                                            <span class="text-sm text-gray-800 col-span-2" x-text="activeDokter.alamat"></span>
                                        </div>
                                        <div class="grid grid-cols-3 gap-2">
                                            <span class="text-sm font-semibold text-gray-500">Jadwal Praktek</span>
                                            <span class="text-sm text-gray-800 col-span-2 font-semibold text-pink-600" x-text="activeDokter.jadwal_praktek"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-pink-50/40 px-6 py-4 sm:px-8 flex justify-end space-x-2">
                                    <button @click="activeDokter = null" class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">Tutup</button>
                                    <a href="{{ route('booking-konsultasi.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm transition">Booking Konsultasi</a>
                                </div>
                            </div>
                            </template>
                        </div>
                    </div>
                </div>
                @endrole

                <div class="mt-4">
                    {{ $dokter->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
