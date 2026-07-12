<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Riwayat Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <!-- Header -->
                <div class="flex justify-between items-center mb-6">

                    <div>

                        <h3 class="text-lg font-bold text-gray-800">
                            Riwayat Booking Klinik
                        </h3>

                        <p class="text-sm text-gray-500">
                            Daftar seluruh booking pasien.
                        </p>

                    </div>

                    <a href="{{ route('booking.index') }}"
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded shadow">

                        Kembali

                    </a>

                </div>

                @if(session('success'))

                    <div class="mb-5 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded">

                        {{ session('success') }}

                    </div>

                @endif

                <div class="overflow-x-auto">

                    <table class="min-w-full border border-gray-200">

                        <thead class="bg-gray-100">

                            <tr>

                                <th class="border px-4 py-3 text-center">
                                    No
                                </th>

                                <th class="border px-4 py-3">
                                    ID Pasien
                                </th>

                                <th class="border px-4 py-3">
                                    ID Dokter
                                </th>

                                <th class="border px-4 py-3">
                                    Jadwal Konsultasi
                                </th>

                                <th class="border px-4 py-3 text-center">
                                    Status
                                </th>

                                <th class="border px-4 py-3 text-center">
                                    Aksi
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($booking as $item)

                                <tr class="hover:bg-gray-50">

                                    <td class="border px-4 py-3 text-center">

                                        {{ $loop->iteration + ($booking->currentPage()-1)*$booking->perPage() }}

                                    </td>

                                    <td class="border px-4 py-3">

                                        {{ $item->id_pasien }}

                                    </td>

                                    <td class="border px-4 py-3">

                                        {{ $item->id_dokter }}

                                    </td>

                                    <td class="border px-4 py-3">

                                        {{ $item->jadwal_konsultasi }}

                                    </td>

                                    <td class="border px-4 py-3 text-center">

                                        @if($item->status_booking=='pending')

                                            <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                                Pending
                                            </span>

                                        @elseif($item->status_booking=='dikonfirmasi')

                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                                Dikonfirmasi
                                            </span>

                                        @elseif($item->status_booking=='selesai')

                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                Selesai
                                            </span>

                                        @else

                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                                Dibatalkan
                                            </span>

                                        @endif

                                    </td>

                                    <td class="border px-4 py-3 text-center">

                                        @if($item->status_booking != 'selesai')

                                            <form action="{{ route('booking.selesai', $item->id_booking) }}"
                                                  method="POST">

                                                @csrf
                                                @method('PUT')

                                                <button
                                                    type="submit"
                                                    onclick="return confirm('Ubah status booking menjadi selesai?')"
                                                    class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded">

                                                    Selesai

                                                </button>

                                            </form>

                                        @else

                                            <span class="text-green-600 font-semibold">

                                                ✔ Selesai

                                            </span>

                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6"
                                        class="border px-4 py-6 text-center text-gray-500">

                                        Belum ada riwayat booking.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="mt-6">

                    {{ $booking->links() }}

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
