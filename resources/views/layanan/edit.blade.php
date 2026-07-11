<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ubah Data Layanan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-lg font-bold text-gray-700">Edit Layanan: {{ $layanan->nama_layanan }}</h3>
                    <a href="{{ route('layanan.index') }}" class="text-gray-600 hover:underline text-sm">
                        &larr; Kembali ke Daftar
                    </a>
                </div>

                <!-- Form Edit -->
                <form action="{{ route('layanan.update', $layanan->id_layanan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Nama Layanan -->
                    <div class="mb-4">
                        <label for="nama_layanan" class="block text-gray-700 text-sm font-bold mb-2">Nama Layanan *</label>
                        <input type="text" name="nama_layanan" id="nama_layanan" value="{{ old('nama_layanan', $layanan->nama_layanan) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('nama_layanan')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Deskripsi -->
                    <div class="mb-4">
                        <label for="deskripsi" class="block text-gray-700 text-sm font-bold mb-2">Deskripsi Layanan</label>
                        <textarea name="deskripsi" id="deskripsi" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('deskripsi', $layanan->deskripsi) }}</textarea>
                        @error('deskripsi')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Harga -->
                    <div class="mb-6">
                        <label for="harga" class="block text-gray-700 text-sm font-bold mb-2">Harga (Rp) *</label>
                        <input type="number" name="harga" id="harga" value="{{ old('harga', $layanan->harga) }}" class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                        @error('harga')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tombol Aksi - PASTIKAN DI DALAM <form> -->
                    <div class="flex justify-end gap-2 p-4 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                        <a href="{{ route('layanan.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded hover:bg-gray-300 transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-black px-4 py-2 rounded shadow transition">
                            Perbarui Data
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
