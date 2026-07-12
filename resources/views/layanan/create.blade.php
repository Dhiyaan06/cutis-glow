<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Layanan Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">Form Input Layanan Cutis Glow</h3>
                    <a href="{{ route('layanan.index') }}" class="text-gray-600 hover:underline text-sm">
                        &larr; Kembali ke Daftar
                    </a>
                </div>

                <!-- Form Input -->
                <form action="{{ route('layanan.store') }}" method="POST">
                    @csrf

                    <!-- Nama Layanan -->
                    <div class="mb-4">
                        <label for="nama_layanan" class="block text-gray-700 text-sm font-bold mb-2">Nama Layanan *</label>
                        <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('nama_layanan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Layanan</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('deskripsi') }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga -->
                    <div class="mb-6">
                        <label for="harga" class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp) *</label>
                        <input type="number" name="harga" id="harga" value="{{ old('harga') }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                        @error('harga')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="diskon" class="block text-sm font-medium text-gray-700">Diskon (%)</label>
                        <input type="number" name="diskon" id="diskon" min="0" max="100" value="{{ old('diskon', $layanan->diskon ?? 0) }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>

                    <!-- Tombol Aksi -->
                    <div class="flex justify-end gap-2">
                        <button type="reset" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">
                            Reset
                        </button>
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 shadow transition">
                            Simpan Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
