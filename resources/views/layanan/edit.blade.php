<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Ubah Data Layanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">

                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">Edit Layanan: {{ $layanan->nama_layanan }}</h3>
                    <a href="{{ route('layanan.index') }}" class="text-gray-500 hover:text-pink-600 hover:underline text-sm font-semibold">
                        &larr; Kembali ke Daftar
                    </a>
                </div>

                <!-- Form Edit -->
                <form action="{{ route('layanan.update', $layanan->id_layanan) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Nama Layanan -->
                    <div>
                        <label for="nama_layanan" class="block text-gray-700 text-sm font-semibold">Nama Layanan *</label>
                        <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan) }}"
                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm" required>
                        @error('nama_layanan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div>
                        <label for="deskripsi" class="block text-gray-700 text-sm font-semibold">Deskripsi Layanan</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3"
                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga & Diskon -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="harga" class="block text-gray-700 text-sm font-semibold">Harga (Rp) *</label>
                            <input type="number" name="harga" id="harga" value="{{ old('harga', $layanan->harga) }}"
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm" required>
                            @error('harga')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="diskon" class="block text-gray-700 text-sm font-semibold">Diskon (%)</label>
                            <input type="number" name="diskon" id="diskon" min="0" max="100" value="{{ old('diskon', $layanan->diskon) }}"
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                            @error('diskon')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('layanan.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-md font-semibold text-sm transition shadow-sm">
                            Perbarui Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
