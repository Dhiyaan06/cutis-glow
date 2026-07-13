<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Daftar Layanan Klinik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">

                <!-- Bagian Judul dan Tombol Tambah -->
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-700">Data Layanan Cutis Glow</h3>
                    @role('admin')
                    <a href="{{ route('layanan.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm transition font-medium text-sm">
                        + Tambah Layanan
                    </a>
                    @endrole
                </div>

                <!-- Notifikasi Sukses Jika Ada -->
                @if(session('success'))
                    <div class="bg-pink-50 border border-pink-200 text-pink-700 p-3 rounded-md mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Form Search & Filter -->
                <form action="{{ route('layanan.index') }}" method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-1 w-full gap-2">
                        <!-- Input Search -->
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama layanan..."
                                class="w-full pl-3 pr-4 py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>

                        <!-- Dropdown Filter Diskon -->
                        <div class="w-48">
                            <select name="filter_diskon" class="w-full py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="">Semua Diskon</option>
                                <option value="ada_diskon" {{ request('filter_diskon') == 'ada_diskon' ? 'selected' : '' }}>Ada Diskon</option>
                                <option value="tanpa_diskon" {{ request('filter_diskon') == 'tanpa_diskon' ? 'selected' : '' }}>Tanpa Diskon</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-2 w-full md:w-auto justify-end">
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm text-sm transition font-medium">
                            Cari & Filter
                        </button>
                        @if(request('search') || request('filter_diskon'))
                            <a href="{{ route('layanan.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm transition font-medium">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>

                @role('admin')
                <!-- Tabel Data Layanan -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-pink-100">
                        <thead>
                            <tr class="bg-pink-50 text-pink-800 border-b border-pink-100">
                                <th class="p-3 text-left text-sm font-semibold">Nama Layanan</th>
                                <th class="p-3 text-left text-sm font-semibold">Deskripsi</th>
                                <th class="p-3 text-left text-sm font-semibold">Harga</th>
                                <th class="p-3 text-center text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($layanan as $item)
                                <tr class="hover:bg-pink-50/10 text-gray-600 border-b border-pink-50/30">
                                    <td class="p-3 text-sm font-semibold text-gray-800">{{ $item->nama_layanan }}</td>
                                    <td class="p-3 text-sm">{{ $item->deskripsi ?? '-' }}</td>
                                    <td class="p-3 text-sm">
                                        @if($item->diskon > 0)
                                            <!-- Menghitung harga setelah dipotong diskon -->
                                            @php
                                                $hargaDiskon = $item->harga - ($item->harga * $item->diskon / 100);
                                            @endphp
                                            <div class="flex flex-col">
                                                <!-- Harga Asli Coret & Label Persen -->
                                                <div class="flex items-center space-x-2">
                                                    <span class="line-through text-xs text-gray-400">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-semibold bg-red-100 text-red-700">
                                                        -{{ $item->diskon }}%
                                                    </span>
                                                </div>
                                                <!-- Harga Akhir -->
                                                <span class="font-bold text-emerald-600">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                            </div>
                                        @else
                                            <!-- Tampilan Normal Jika Tidak Ada Diskon -->
                                            <span class="font-medium text-gray-900 font-semibold">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                        @endif
                                    </td>
                                    <td class="p-3 text-center text-sm">
                                        <a href="{{ route('layanan.edit', $item->id_layanan) }}" class="text-yellow-600 hover:underline mr-3">Edit</a>
                                        <form action="{{ route('layanan.destroy', $item->id_layanan) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus layanan ini?')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-6 text-center text-gray-400 italic border border-pink-100">
                                        Belum ada data layanan klinik yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @else
                <!-- Tampilan Grid Card Cantik untuk Pasien -->
                <div x-data="{ activeLayanan: null }">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @forelse($layanan as $item)
                            <div @click="activeLayanan = {{ json_encode($item) }}"
                                class="bg-white border-2 border-pink-100 hover:border-pink-300 rounded-2xl p-6 shadow-sm hover:shadow-md transition duration-200 cursor-pointer flex flex-col justify-between transform hover:-translate-y-1">
                                <div>
                                    <div class="flex justify-between items-start gap-2">
                                        <h4 class="font-bold text-lg text-gray-800 line-clamp-1">{{ $item->nama_layanan }}</h4>
                                        @if($item->diskon > 0)
                                            <span class="bg-pink-100 text-pink-700 text-xs font-semibold px-2 py-0.5 rounded-full shrink-0">
                                                -{{ $item->diskon }}%
                                            </span>
                                        @endif
                                    </div>
                                    <p class="text-gray-500 text-sm mt-3 line-clamp-3">
                                        {{ $item->deskripsi ?? 'Pencet untuk melihat deskripsi selengkapnya.' }}
                                    </p>
                                </div>
                                <div class="mt-6 flex justify-between items-end border-t border-pink-50/50 pt-4">
                                    <div>
                                        <p class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Harga</p>
                                        @if($item->diskon > 0)
                                            @php
                                                $hargaDiskon = $item->harga - ($item->harga * $item->diskon / 100);
                                            @endphp
                                            <span class="line-through text-xs text-gray-400">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                            <p class="font-extrabold text-emerald-600 text-lg leading-none mt-0.5">Rp {{ number_format($hargaDiskon, 0, ',', '.') }}</p>
                                        @else
                                            <p class="font-extrabold text-gray-800 text-lg leading-none mt-1">Rp {{ number_format($item->harga, 0, ',', '.') }}</p>
                                        @endif
                                    </div>
                                    <span class="text-pink-600 hover:text-pink-700 font-bold text-sm flex items-center space-x-1">
                                        <span>Detail</span>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-3 bg-white border border-pink-100 rounded-2xl p-8 text-center text-gray-400 italic">
                                Belum ada data layanan klinik yang tersedia.
                            </div>
                        @endforelse
                    </div>

                    <!-- Alpine.js Modal Detail Layanan -->
                    <div x-show="activeLayanan" class="fixed inset-0 z-50 overflow-y-auto" style="display: none;"
                        x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">

                            <!-- Background overlay -->
                            <div class="fixed inset-0 transition-opacity" aria-hidden="true" @click="activeLayanan = null">
                                <div class="absolute inset-0 bg-gray-500 opacity-60"></div>
                            </div>

                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                            <!-- Modal Content Box -->
                            <template x-if="activeLayanan">
                            <div x-show="activeLayanan"
                                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-t-8 border-pink-500">

                                <div class="bg-white px-6 pt-6 pb-4 sm:p-8 sm:pb-6">
                                    <div class="flex justify-between items-start">
                                        <h3 class="text-2xl font-bold text-gray-800" x-text="activeLayanan.nama_layanan"></h3>
                                        <button @click="activeLayanan = null" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </div>
                                    <div class="mt-6">
                                        <p class="text-xs font-bold text-pink-600 uppercase tracking-widest">Deskripsi Perawatan</p>
                                        <p class="text-gray-600 text-sm mt-2 leading-relaxed" x-text="activeLayanan.deskripsi || 'Tidak ada deskripsi rinci untuk layanan ini.'"></p>
                                    </div>
                                    <div class="mt-8 border-t border-pink-50 pt-5 flex justify-between items-center">
                                        <div>
                                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Biaya Treatment</p>
                                            <div class="flex items-center space-x-2 mt-1">
                                                <template x-if="activeLayanan.diskon > 0">
                                                    <div class="flex items-center space-x-2">
                                                        <span class="line-through text-xs text-gray-400" x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(activeLayanan.harga)"></span>
                                                        <span class="bg-pink-100 text-pink-700 text-xs font-bold px-2 py-0.5 rounded-full" x-text="'-' + activeLayanan.diskon + '%'"></span>
                                                    </div>
                                                </template>
                                            </div>
                                            <p class="font-extrabold text-emerald-600 text-2xl mt-1"
                                                x-text="'Rp ' + new Intl.NumberFormat('id-ID').format(activeLayanan.harga - (activeLayanan.harga * activeLayanan.diskon / 100))"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-pink-50/40 px-6 py-4 sm:px-8 flex justify-end space-x-2">
                                    <button @click="activeLayanan = null" class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl text-sm font-medium transition">Tutup</button>
                                    <a href="{{ route('booking-konsultasi.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-6 py-2.5 rounded-xl text-sm font-bold shadow-sm transition">Booking Sekarang</a>
                                </div>
                            </div>
                            </template>
                        </div>
                    </div>
                </div>
                @endrole

                <div class="mt-4">
                    {{ $layanan->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
