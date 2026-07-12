<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Judul dan Tombol Tambah -->
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-gray-700">
                        Data Dokter Cutis Glow
                    </h3>

                    <a href="{{ route('dokter.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow transition">
                        + Tambah Dokter
                    </a>
                </div>

                <!-- Notifikasi -->
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 p-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <!-- Tabel -->
                <div class="overflow-x-auto">

                    <table class="w-full border-collapse border border-gray-200">

                        <thead>
                            <tr class="bg-gray-100 text-gray-700">
                                <th class="border border-gray-200 p-3 text-left">Nama Dokter</th>
                                <th class="border border-gray-200 p-3 text-left">Spesialis</th>
                                <th class="border border-gray-200 p-3 text-left">Jadwal Praktek</th>
                                <th class="border border-gray-200 p-3 text-left">Nomor STR</th>
                                <th class="border border-gray-200 p-3 text-left">Nomor HP</th>
                                <th class="border border-gray-200 p-3 text-left">Alamat</th>
                                <th class="border border-gray-200 p-3 text-center">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($dokter as $item)

                                <tr class="hover:bg-gray-50 text-gray-700">

                                    <td class="border border-gray-200 p-3 font-medium">
                                        {{ $item->pengguna->name }}
                                    </td>

                                    <td class="border border-gray-200 p-3">
                                        {{ $item->spesialis }}
                                    </td>

                                    <td class="border border-gray-200 p-3">
                                        {{ $item->jadwal_praktek }}
                                    </td>

                                    <td class="border border-gray-200 p-3">
                                        {{ $item->no_str }}
                                    </td>

                                    <td class="border border-gray-200 p-3">
                                        {{ $item->no_hp }}
                                    </td>

                                    <td class="border border-gray-200 p-3">
                                        {{ $item->alamat }}
                                    </td>

                                    <td class="border border-gray-200 p-3 text-center">

                                        <a href="{{ route('dokter.show', $item->id_dokter) }}"
                                            class="text-blue-600 hover:underline mr-3">
                                            Detail
                                        </a>

                                        <a href="{{ route('dokter.edit', $item->id_dokter) }}"
                                            class="text-yellow-600 hover:underline mr-3">
                                            Edit
                                        </a>

                                        <form action="{{ route('dokter.destroy', $item->id_dokter) }}"
                                            method="POST"
                                            class="inline">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                class="text-red-600 hover:underline"
                                                onclick="return confirm('Yakin ingin menghapus data dokter ini?')">
                                                Hapus
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7"
                                        class="border border-gray-200 p-6 text-center text-gray-500 italic">
                                        Belum ada data dokter.
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $dokter->links() }}
                    </div>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
