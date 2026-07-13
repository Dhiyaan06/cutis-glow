<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm sm:rounded-lg p-6">

                @if ($errors->any())

                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">

                        <ul class="list-disc ml-5">

                            @foreach ($errors->all() as $error)

                                <li>{{ $error }}</li>

                            @endforeach

                        </ul>

                    </div>

                @endif

                <form action="{{ route('booking.update', $booking->id_booking) }}" method="POST">

                    @csrf
                    @method('PUT')

                    <!-- ID Pasien -->
                    <div class="mb-5">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ID Pasien
                        </label>

                        <input
                            type="number"
                            name="id_pasien"
                            value="{{ old('id_pasien', $booking->id_pasien) }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>

                    </div>

                    <!-- ID Dokter -->
                    <div class="mb-5">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            ID Dokter
                        </label>

                        <input
                            type="number"
                            name="id_dokter"
                            value="{{ old('id_dokter', $booking->id_dokter) }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>

                    </div>

                    <!-- Jadwal -->
                    <div class="mb-5">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jadwal Konsultasi
                        </label>

                        <input
                            type="datetime-local"
                            name="jadwal_konsultasi"
                            value="{{ date('Y-m-d\TH:i', strtotime($booking->jadwal_konsultasi)) }}"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>

                    </div>

                    <!-- Status -->
                    <div class="mb-5">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status Booking
                        </label>

                        <select
                            name="status_booking"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500">

                            <option value="pending"
                                {{ $booking->status_booking == 'pending' ? 'selected' : '' }}>
                                Pending
                            </option>

                            <option value="dikonfirmasi"
                                {{ $booking->status_booking == 'dikonfirmasi' ? 'selected' : '' }}>
                                Dikonfirmasi
                            </option>

                            <option value="selesai"
                                {{ $booking->status_booking == 'selesai' ? 'selected' : '' }}>
                                Selesai
                            </option>

                            <option value="dibatalkan"
                                {{ $booking->status_booking == 'dibatalkan' ? 'selected' : '' }}>
                                Dibatalkan
                            </option>

                        </select>

                    </div>

                    <!-- Keluhan -->
                    <div class="mb-6">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Keluhan
                        </label>

                        <textarea
                            name="keluhan"
                            rows="4"
                            class="w-full rounded-md border-gray-300 shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            required>{{ old('keluhan', $booking->keluhan) }}</textarea>

                    </div>

                    <!-- Tombol -->
                    <div class="flex justify-end gap-3">

                        <a href="{{ route('booking.index') }}"
                           class="px-5 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">

                            Kembali

                        </a>

                        <button
                            type="submit"
                            class="px-5 py-2 bg-yellow-500 text-white rounded hover:bg-yellow-600">

                            Update Booking

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
