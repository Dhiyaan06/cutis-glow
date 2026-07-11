<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Layanan Klinik') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Bagian Judul dan Tombol Tambah -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-700">Data Layanan Cutis Glow</h3>
                    <a href="{{ route('layanan.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                        + Tambah Layanan
                    </a>
                </div>

                <!-- Notifikasi Sukses Jika Ada -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabel Data Layanan -->
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border border-gray-200 p-3 text-left">Nama Layanan</th>
                                <th class="border border-gray-200 p-3 text-left">Deskripsi</th>
                                <th class="border border-gray-200 p-3 text-left">Harga</th>
                                <th class="border border-gray-200 p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($layanan as $item)
                                <tr class="hover:bg-gray-50 text-gray-600">
                                    <td class="border border-gray-200 p-3 font-medium text-gray-800">{{ $item->nama_layanan }}</td>
                                    <td class="border border-gray-200 p-3">{{ $item->deskripsi ?? '-' }}</td>
                                    <td class="border border-gray-200 p-3">Rp {{ number_format($item->harga, 0, ',', '.') }}</td>
                                    <td class="border border-gray-200 p-3 text-center">
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
                                    <td colspan="4" class="border border-gray-200 p-6 text-center text-gray-400 italic">
                                        Belum ada data layanan klinik yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
