<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Buat Booking Konsultasi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">
                <h3 class="text-lg font-bold text-gray-700 mb-6">Form Booking Baru</h3>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md mb-6 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('booking-konsultasi.store') }}" method="POST" class="space-y-4">
                    @csrf

                    @role('admin')
                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Pilih Pasien</label>
                        <select name="id_pasien" required
                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                            <option value="">-- Pilih Pasien --</option>
                            @foreach($pasienList as $pas)
                                <option value="{{ $pas->id_pasien }}" {{ old('id_pasien') == $pas->id_pasien ? 'selected' : '' }}>{{ $pas->user->name }} ({{ $pas->no_hp }})</option>
                            @endforeach
                        </select>
                    </div>
                    @else
                    @php
                        $currentPasien = \App\Models\Pasien::where('id_pengguna', auth()->user()->id_pengguna)->first();
                    @endphp
                    @if($currentPasien)
                        <input type="hidden" name="id_pasien" value="{{ $currentPasien->id_pasien }}">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Pasien</label>
                            <input type="text" readonly value="{{ auth()->user()->name }}" class="w-full mt-1 bg-gray-50 border-pink-200 rounded-md text-sm text-gray-600 focus:ring-0 focus:border-pink-200">
                        </div>
                    @endif
                    @endrole

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Pilih Dokter</label>
                        <select name="id_dokter" required
                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                            <option value="">-- Pilih Dokter --</option>
                            @foreach($dokterList as $doc)
                                <option value="{{ $doc->id_dokter }}" {{ old('id_dokter') == $doc->id_dokter ? 'selected' : '' }}>{{ $doc->user->name }} - {{ $doc->spesialis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Tanggal Booking</label>
                            <input type="date" name="tanggal_booking" value="{{ old('tanggal_booking', date('Y-m-d')) }}" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Jam Booking</label>
                            <input type="time" name="jam_booking" value="{{ old('jam_booking') }}" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Catatan / Keluhan Pasien</label>
                        <textarea name="catatan" rows="3" placeholder="Ingin mencerahkan noda hitam / facial reguler"
                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">{{ old('catatan') }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('booking-konsultasi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-md font-semibold text-sm transition shadow-sm">
                            Buat Booking
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
