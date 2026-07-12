<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                @if ($errors->any())

                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">

                        <ul class="list-disc ml-5">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                <form action="{{ route('booking.store') }}" method="POST">

                    @csrf

                    <div class="mb-5">

                        <label class="block text-sm font-medium text-gray-700 mb-2">

                            ID Pasien

                        </label>

                        <select name="id_pasien" class="form-control" required>
                            <option value="">-- Pilih Pasien --</option>

                            @foreach($pasien as $p)
                                <option value="{{ $p->id_pasien }}">
                                    {{ $p->nama }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="mb-5">

                        <label class="block text-sm font-medium text-gray-700 mb-2">

                            ID Dokter

                        </label>

                        <select name="id_dokter" class="form-control" required>
                            <option value="">-- Pilih Dokter --</option>

                            @foreach($dokter as $d)
                                <option value="{{ $d->id_dokter }}">
                                    {{ $d->nama_dokter }}
                                </option>
                            @endforeach
                        </select>

                    </div>

                    <div class="mb-5">

                        <label class="block text-sm font-medium text-gray-700 mb-2">

                            Jadwal Konsultasi

                        </label>

                        <input
                            type="datetime-local"
                            name="jadwal_konsultasi"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            value="{{ old('jadwal_konsultasi') }}"
                            required>

                    </div>

                    <div class="mb-6">

                        <label class="block text-sm font-medium text-gray-700 mb-2">

                            Keluhan

                        </label>

                        <textarea
                            name="keluhan"
                            rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>{{ old('keluhan') }}</textarea>

                    </div>

                    <div class="flex justify-end gap-3">

                        <a
                            href="{{ route('booking.index') }}"
                            class="px-5 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">

                            Kembali

                        </a>

                        <button
                            type="submit"
                            class="px-5 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">

                            Simpan Booking

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
