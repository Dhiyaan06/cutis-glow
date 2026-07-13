<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Catat Treatment Pasien Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">
                <h3 class="text-lg font-bold text-gray-700 mb-6">
                    @if($booking)
                        Catat Hasil Konsultasi Booking #BK-{{ $booking->id_booking }}
                    @else
                        Form Catat Treatment Baru
                    @endif
                </h3>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md mb-6 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('riwayat-layanan.store') }}" method="POST" class="space-y-4">
                    @csrf

                    @if($booking)
                        <input type="hidden" name="id_booking" value="{{ $booking->id_booking }}">
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Pasien</label>
                            @if($booking)
                                <input type="hidden" name="id_pasien" value="{{ $booking->id_pasien }}">
                                <input type="text" value="{{ $booking->pasien->user->name }}" readonly
                                    class="w-full mt-1 border-pink-100 bg-pink-50/50 text-gray-600 rounded-md text-sm">
                            @else
                                <select name="id_pasien" required
                                    class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                    <option value="">-- Pilih Pasien --</option>
                                    @foreach($pasienList as $pas)
                                        <option value="{{ $pas->id_pasien }}">{{ $pas->user->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Dokter Pelaksana</label>
                            @if($booking)
                                <input type="hidden" name="id_dokter" value="{{ $booking->id_dokter }}">
                                <input type="text" value="{{ $booking->dokter->user->name }}" readonly
                                    class="w-full mt-1 border-pink-100 bg-pink-50/50 text-gray-600 rounded-md text-sm">
                            @elseif(auth()->user()->hasRole('dokter'))
                                @php
                                    $currentDokter = \App\Models\Dokter::where('id_pengguna', auth()->user()->id_pengguna)->first();
                                @endphp
                                @if($currentDokter)
                                    <input type="hidden" name="id_dokter" value="{{ $currentDokter->id_dokter }}">
                                    <input type="text" value="{{ auth()->user()->name }}" readonly
                                        class="w-full mt-1 border-pink-100 bg-pink-50/50 text-gray-600 rounded-md text-sm">
                                @endif
                            @else
                                <select name="id_dokter" required
                                    class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                    <option value="">-- Pilih Dokter --</option>
                                    @foreach($dokterList as $doc)
                                        <option value="{{ $doc->id_dokter }}">{{ $doc->user->name }}</option>
                                    @endforeach
                                </select>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700">Layanan / Treatment</label>
                            <select name="id_layanan" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="">-- Pilih Layanan --</option>
                                @foreach($layananList as $lay)
                                    <option value="{{ $lay->id_layanan }}">{{ $lay->nama_layanan }} (Rp {{ number_format($lay->harga, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Kuantitas (Qty)</label>
                            <input type="number" name="qty" value="1" min="1" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Treatment</label>
                            <input type="date" name="tanggal_treatment" value="{{ old('tanggal_treatment', date('Y-m-d')) }}" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Status Tindakan</label>
                            <select name="status" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="selesai">Selesai</option>
                                <option value="batal">Batal</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Catatan Medis / Rekomendasi Dokter</label>
                        <textarea name="catatan" rows="3" placeholder="Selesai dilakukan Brightening Glow Facial. Disarankan tidak memakai make up tebal selama 24 jam."
                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('riwayat-layanan.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-md font-semibold text-sm transition shadow-sm">
                            Simpan Riwayat
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
