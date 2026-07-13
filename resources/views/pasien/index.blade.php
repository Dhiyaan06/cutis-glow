<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Manajemen Pasien Klinik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">

                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-bold text-gray-700">Data Pasien Cutis Glow</h3>
                    <a href="{{ route('pasien.create') }}" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm transition font-medium text-sm">
                        + Tambah Pasien
                    </a>
                </div>

                @if(session('success'))
                    <div class="bg-pink-50 border border-pink-200 text-pink-700 p-3 rounded-md mb-6">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Search & Filter Form -->
                <form action="{{ route('pasien.index') }}" method="GET" class="mb-6 flex flex-col md:flex-row gap-4 items-center justify-between">
                    <div class="flex flex-1 w-full gap-2">
                        <!-- Input Search -->
                        <div class="relative w-full">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama, nomor hp, alamat..."
                                class="w-full pl-3 pr-4 py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>

                        <!-- Dropdown Filter Gender -->
                        <div class="w-48">
                            <select name="jenis_kelamin" class="w-full py-2 border border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="">Semua Gender</option>
                                <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki (L)</option>
                                <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan (P)</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex gap-2 w-full md:w-auto justify-end">
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-4 py-2 rounded-md shadow-sm text-sm transition font-medium">
                            Cari & Filter
                        </button>
                        @if(request('search') || request('jenis_kelamin'))
                            <a href="{{ route('pasien.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-sm transition font-medium">
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
                                <th class="p-3 text-left text-sm font-semibold">Nama</th>
                                <th class="p-3 text-left text-sm font-semibold">Email</th>
                                <th class="p-3 text-left text-sm font-semibold">No HP</th>
                                <th class="p-3 text-center text-sm font-semibold">Gender</th>
                                <th class="p-3 text-left text-sm font-semibold">Tanggal Lahir</th>
                                <th class="p-3 text-center text-sm font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($pasien as $item)
                                <tr class="hover:bg-pink-50/10 text-gray-600 border-b border-pink-50/30">
                                    <td class="p-3 text-sm font-semibold text-gray-800">
                                        <a href="{{ route('pasien.show', $item->id_pasien) }}" class="text-pink-600 hover:underline">
                                            {{ $item->user->name ?? '-' }}
                                        </a>
                                    </td>
                                    <td class="p-3 text-sm">{{ $item->user->email ?? '-' }}</td>
                                    <td class="p-3 text-sm">{{ $item->no_hp }}</td>
                                    <td class="p-3 text-center text-sm">
                                        <span class="px-2 py-0.5 rounded-md text-xs font-bold {{ $item->jenis_kelamin == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                            {{ $item->jenis_kelamin }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-sm">{{ \Carbon\Carbon::parse($item->tanggal_lahir)->translatedFormat('d M Y') }}</td>
                                    <td class="p-3 text-center text-sm">
                                        <a href="{{ route('pasien.edit', $item->id_pasien) }}" class="text-yellow-600 hover:underline mr-3">Edit</a>
                                        <form action="{{ route('pasien.destroy', $item->id_pasien) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Yakin ingin menghapus pasien ini? Data riwayat/booking terkait juga akan terhapus.')">
                                                Hapus
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-6 text-center text-gray-400 italic">
                                        Belum ada data pasien yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $pasien->links() }}
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
