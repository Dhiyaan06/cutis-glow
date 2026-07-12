<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pasien') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    <h3 class="text-lg font-semibold mb-4">Biodata Pasien</h3>

                    <table class="min-w-full border border-gray-300 mb-8">
                        <tbody>
                            <tr>
                                <th class="border px-4 py-2 bg-gray-100 text-left w-1/4">Nama Pasien</th>
                                <td class="border px-4 py-2">{{ $pasien->nama ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="border px-4 py-2 bg-gray-100 text-left">Email</th>
                                <td class="border px-4 py-2">{{ $pasien->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="border px-4 py-2 bg-gray-100 text-left">No HP</th>
                                <td class="border px-4 py-2">{{ $pasien->no_hp ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="border px-4 py-2 bg-gray-100 text-left">Jenis Kelamin</th>
                                <td class="border px-4 py-2">
                                    @if($pasien->jenis_kelamin == 'L')
                                        Laki-laki
                                    @elseif($pasien->jenis_kelamin == 'P')
                                        Perempuan
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="border px-4 py-2 bg-gray-100 text-left">Tanggal Lahir</th>
                                <td class="border px-4 py-2">{{ $pasien->tanggal_lahir ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th class="border px-4 py-2 bg-gray-100 text-left">Alamat</th>
                                <td class="border px-4 py-2">{{ $pasien->alamat ?? '-' }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold mb-4">Riwayat Layanan</h3>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2">No</th>
                                    <th class="border px-4 py-2">Tanggal</th>
                                    <th class="border px-4 py-2">Layanan</th>
                                    <th class="border px-4 py-2">Dokter</th>
                                    <th class="border px-4 py-2">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pasien->riwayatLayanan ?? [] as $riwayat)
                                    <tr>
                                        <td class="border px-4 py-2 text-center">{{ $loop->iteration }}</td>
                                        <td class="border px-4 py-2">{{ $riwayat->created_at ?? '-' }}</td>
                                        <td class="border px-4 py-2">{{ $riwayat->layanan->nama_layanan ?? '-' }}</td>
                                        <td class="border px-4 py-2">{{ $riwayat->dokter->nama ?? '-' }}</td>
                                        <td class="border px-4 py-2">{{ $riwayat->status ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="border px-4 py-4 text-center">
                                            Belum ada riwayat layanan.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('pasien.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded">
                            Kembali
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
