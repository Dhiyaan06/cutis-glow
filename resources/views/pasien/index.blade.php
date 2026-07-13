<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manajemen Pasien') }}
            </h2>

            <a href="{{ route('pasien.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Tambah Pasien
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white shadow rounded-lg">
                <div class="p-6">

                    <form action="{{ route('pasien.index') }}" method="GET" class="flex gap-3 mb-5">

                        <input
                            type="text"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Cari nama atau email..."
                            class="border rounded px-3 py-2 w-72">

                        <select name="jenis_kelamin" class="border rounded px-3 py-2">
                            <option value="">Semua Jenis Kelamin</option>
                            <option value="L" {{ request('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ request('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>

                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Cari
                        </button>

                        <a href="{{ route('pasien.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                            Reset
                        </a>

                    </form>

                    <div class="overflow-x-auto">
                        <table class="min-w-full border border-gray-300">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="border px-4 py-2">No</th>
                                    <th class="border px-4 py-2">Nama</th>
                                    <th class="border px-4 py-2">Email</th>
                                    <th class="border px-4 py-2">No HP</th>
                                    <th class="border px-4 py-2">Jenis Kelamin</th>
                                    <th class="border px-4 py-2">Tanggal Lahir</th>
                                    <th class="border px-4 py-2">Alamat</th>
                                    <th class="border px-4 py-2 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pasien as $item)
                                    <tr>
                                        <td class="border px-4 py-2 text-center">
                                            {{ $pasien->firstItem() + $loop->index }}
                                        </td>
                                        <td class="border px-4 py-2">{{ $item->nama ?? '-' }}</td>
                                        <td class="border px-4 py-2">{{ $item->email ?? '-' }}</td>
                                        <td class="border px-4 py-2">{{ $item->no_hp }}</td>
                                        <td class="border px-4 py-2">
                                            {{ $item->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        </td>
                                        <td class="border px-4 py-2">{{ $item->tanggal_lahir }}</td>
                                        <td class="border px-4 py-2">{{ $item->alamat }}</td>
                                        <td class="border px-4 py-2">
                                            <div class="flex justify-center gap-2">
                                                <a href="{{ route('pasien.show',$item->id_pasien) }}"
                                                    class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700">
                                                    Detail
                                                </a>
                                                <a href="{{ route('pasien.edit',$item->id_pasien) }}"
                                                    class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600">
                                                    Edit
                                                </a>
                                                <form action="{{ route('pasien.destroy',$item->id_pasien) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Yakin ingin menghapus data pasien?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="border px-4 py-4 text-center text-gray-500">
                                            Data pasien belum tersedia.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-5">
                        {{ $pasien->withQueryString()->links() }}
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
