<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-bold text-gray-800 mb-6">
                    Informasi Booking
                </h3>

                <table class="w-full border border-gray-200">

                    <tbody>

                        <tr>
                            <th class="border px-4 py-3 bg-gray-100 text-left w-64">
                                ID Booking
                            </th>
                            <td class="border px-4 py-3">
                                {{ $booking->id_booking }}
                            </td>
                        </tr>

                        <tr>
                            <th class="border px-4 py-3 bg-gray-100 text-left">
                                ID Pasien
                            </th>
                            <td class="border px-4 py-3">
                                {{ $booking->id_pasien }}
                            </td>
                        </tr>

                        <tr>
                            <th class="border px-4 py-3 bg-gray-100 text-left">
                                ID Dokter
                            </th>
                            <td class="border px-4 py-3">
                                {{ $booking->id_dokter }}
                            </td>
                        </tr>

                        <tr>
                            <th class="border px-4 py-3 bg-gray-100 text-left">
                                Jadwal Konsultasi
                            </th>
                            <td class="border px-4 py-3">
                                {{ $booking->jadwal_konsultasi }}
                            </td>
                        </tr>

                        <tr>
                            <th class="border px-4 py-3 bg-gray-100 text-left">
                                Status Booking
                            </th>
                            <td class="border px-4 py-3">

                                @if($booking->status_booking == 'pending')

                                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                        Pending
                                    </span>

                                @elseif($booking->status_booking == 'dikonfirmasi')

                                    <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        Dikonfirmasi
                                    </span>

                                @elseif($booking->status_booking == 'selesai')

                                    <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                        Selesai
                                    </span>

                                @else

                                    <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                        Dibatalkan
                                    </span>

                                @endif

                            </td>
                        </tr>

                        <tr>
                            <th class="border px-4 py-3 bg-gray-100 text-left">
                                Keluhan
                            </th>
                            <td class="border px-4 py-3">
                                {{ $booking->keluhan }}
                            </td>
                        </tr>

                    </tbody>

                </table>

                <div class="flex justify-end mt-6">

                    <a href="{{ route('booking.index') }}"
                       class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded">

                        Kembali

                    </a>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
