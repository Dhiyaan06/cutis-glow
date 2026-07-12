<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Data Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-bold text-gray-700 mb-6">
                    Form Tambah Data Dokter
                </h3>

                {{-- Menampilkan Error Validasi --}}
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-4">
                        <ul class="list-disc ml-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dokter.store') }}" method="POST">
                    @csrf

                    <!-- Nama Dokter -->
                    <div class="mb-4">
                        <label for="id_pengguna" class="block text-gray-700 font-medium mb-2">
                            Nama Dokter
                        </label>

                        <select
                            name="id_pengguna"
                            id="id_pengguna"
                            class="w-full border-gray-300 rounded-lg shadow-sm"
                            required>

                            <option value="">-- Pilih Dokter --</option>

                            @foreach ($users as $user)
                                <option value="{{ $user->id_pengguna }}"
                                    {{ old('id_pengguna') == $user->id_pengguna ? 'selected' : '' }}>
                                    {{ $user->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <!-- Spesialis -->
                    <div class="mb-4">
                        <label for="spesialis" class="block text-gray-700 font-medium mb-2">
                            Spesialis
                        </label>

                        <input
                            type="text"
                            name="spesialis"
                            id="spesialis"
                            class="w-full border-gray-300 rounded-lg shadow-sm"
                            value="{{ old('spesialis') }}"
                            placeholder="Contoh: Dokter Kulit"
                            required>
                    </div>

                    <!-- Nomor STR -->
                    <div class="mb-4">
                        <label for="no_str" class="block text-gray-700 font-medium mb-2">
                            Nomor STR
                        </label>

                        <input
                            type="text"
                            name="no_str"
                            id="no_str"
                            class="w-full border-gray-300 rounded-lg shadow-sm"
                            value="{{ old('no_str') }}"
                            placeholder="Masukkan Nomor STR"
                            required>
                    </div>

                    <!-- Nomor HP -->
                    <div class="mb-4">
                        <label for="no_hp" class="block text-gray-700 font-medium mb-2">
                            Nomor HP
                        </label>

                        <input
                            type="text"
                            name="no_hp"
                            id="no_hp"
                            class="w-full border-gray-300 rounded-lg shadow-sm"
                            value="{{ old('no_hp') }}"
                            placeholder="08xxxxxxxxxx"
                            required>
                    </div>

                    <!-- Jadwal Praktek -->
                    <div class="mb-4">
                        <label for="jadwal_praktek" class="block text-gray-700 font-medium mb-2">
                            Jadwal Praktek
                        </label>

                        <input
                            type="text"
                            name="jadwal_praktek"
                            id="jadwal_praktek"
                            class="w-full border-gray-300 rounded-lg shadow-sm"
                            value="{{ old('jadwal_praktek') }}"
                            placeholder="Contoh: Senin - Jumat"
                            required>
                    </div>

                    <!-- Alamat -->
                    <div class="mb-6">
                        <label for="alamat" class="block text-gray-700 font-medium mb-2">
                            Alamat
                        </label>

                        <textarea
                            name="alamat"
                            id="alamat"
                            rows="4"
                            class="w-full border-gray-300 rounded-lg shadow-sm"
                            placeholder="Masukkan alamat dokter"
                            required>{{ old('alamat') }}</textarea>
                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end">

                        <a href="{{ route('dokter.index') }}"
                            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded mr-3">
                            Kembali
                        </a>

                        <button
                            type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                            Simpan
                        </button>

                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
