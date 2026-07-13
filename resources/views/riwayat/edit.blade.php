<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Edit Riwayat Treatment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">
                <h3 class="text-lg font-bold text-gray-700 mb-6">Edit Catatan Treatment #TRT-{{ $riwayat->id_riwayat }}</h3>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md mb-6 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('riwayat-layanan.update', $riwayat->id_riwayat) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Pasien</label>
                            <select name="id_pasien" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                @foreach($pasienList as $pas)
                                    <option value="{{ $pas->id_pasien }}" {{ old('id_pasien', $riwayat->id_pasien) == $pas->id_pasien ? 'selected' : '' }}>{{ $pas->user->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Dokter Pelaksana</label>
                            <select name="id_dokter" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                @foreach($dokterList as $doc)
                                    <option value="{{ $doc->id_dokter }}" {{ old('id_dokter', $riwayat->id_dokter) == $doc->id_dokter ? 'selected' : '' }}>{{ $doc->user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Layanan / Treatment</label>
                            <select name="id_layanan" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                @foreach($layananList as $lay)
                                    <option value="{{ $lay->id_layanan }}" {{ old('id_layanan', $riwayat->id_layanan) == $lay->id_layanan ? 'selected' : '' }}>{{ $lay->nama_layanan }} (Rp {{ number_format($lay->harga, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Kuantitas (Qty)</label>
                            <input type="number" name="qty" value="{{ old('qty', $riwayat->qty) }}" min="1" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Treatment</label>
                            <input type="date" name="tanggal_treatment" value="{{ old('tanggal_treatment', $riwayat->tanggal_treatment) }}" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Status Tindakan</label>
                            <select name="status" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="selesai" {{ old('status', $riwayat->status) == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                <option value="batal" {{ old('status', $riwayat->status) == 'batal' ? 'selected' : '' }}>Batal</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Catatan Medis / Rekomendasi Dokter</label>
                        <textarea name="catatan" rows="3"
                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">{{ old('catatan', $riwayat->catatan) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('riwayat-layanan.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-md font-semibold text-sm transition shadow-sm">
                            Perbarui Riwayat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
