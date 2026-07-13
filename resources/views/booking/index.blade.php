<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Booking') }}
        </h2>
    </x-slot>

    <div class="py-12">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Header --}}
                <div class="flex justify-between items-center mb-6">

                    <div>
                        <h3 class="text-lg font-bold text-gray-800">
                            Data Booking Klinik
                        </h3>

                        <p class="text-sm text-gray-500">
                            Daftar booking konsultasi pasien Cutis Glow.
                        </p>
                    </div>

                    <div class="flex gap-2">

                        <a href="{{ route('booking.riwayat') }}"
                           class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg shadow">
                            Riwayat Booking
                        </a>

                        <a href="{{ route('booking.create') }}"
                           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow">
                            + Tambah Booking
                        </a>

                    </div>

                </div>

                {{-- Alert --}}
                @if(session('success'))

                    <div class="mb-5 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded">

                        {{ session('success') }}

                    </div>

                @endif

                {{-- Table --}}
                <div class="overflow-x-auto">

                    <table class="min-w-full border border-gray-200">

                        <thead class="bg-gray-100">

                            <tr>

                                <th class="border px-4 py-3 text-center">No</th>

                                <th class="border px-4 py-3 text-left">
                                    ID Pasien
                                </th>

                                <th class="border px-4 py-3 text-left">
                                    ID Dokter
                                </th>

                                <th class="border px-4 py-3">
                                    Jadwal Konsultasi
                                </th>

                                <th class="border px-4 py-3">
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

                                        @elseif($item->status_booking=='selesai')

                                            <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                                Selesai
                                            </span>

                                        @elseif($item->status_booking=='dikonfirmasi')

                                            <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                                Dikonfirmasi
                                            </span>

                                        @else

                                            <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                                Dibatalkan
                                            </span>

                                        @endif

                                    </td>

                                    <td class="border px-4 py-3 text-center">

                                        <div class="flex justify-center gap-3">

                                            <a href="{{ route('booking.show',$item->id_booking) }}"
                                                class="text-green-600 hover:text-green-800 font-medium">

                                                Detail

                                            </a>

                                            <a href="{{ route('booking.edit',$item->id_booking) }}"
                                                class="text-yellow-600 hover:text-yellow-800 font-medium">

                                                Edit

                                            </a>

                                            <form
                                                action="{{ route('booking.destroy',$item->id_booking) }}"
                                                method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <button
                                                    onclick="return confirm('Yakin ingin menghapus booking ini?')"
                                                    class="text-red-600 hover:text-red-800 font-medium">

                                                    Hapus

                                                </button>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="6"
                                        class="border px-4 py-6 text-center text-gray-500">

                                        Belum ada data booking.

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                {{-- Pagination --}}
                <div class="mt-5">

                    {{ $booking->links() }}

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
