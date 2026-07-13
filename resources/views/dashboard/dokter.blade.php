<!-- ROLE: dokter DASHBOARD -->
@role('dokter')

<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">

    <!-- Total Pasien -->
    <div class="bg-white border rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Total Pasien</p>
        <h3 class="text-3xl font-bold text-gray-800">
            {{ $stats['total_booking'] }}
        </h3>
    </div>

    <!-- Booking Pending -->
    <div class="bg-white border rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Booking Pending</p>
        <h3 class="text-3xl font-bold text-yellow-600">
            {{ $stats['pending_booking'] }}
        </h3>
    </div>

    <!-- Booking Diterima -->
    <div class="bg-white border rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Booking Diterima</p>
        <h3 class="text-3xl font-bold text-blue-600">
            {{ $stats['confirmed_booking'] }}
        </h3>
    </div>

    <!-- Booking Selesai -->
    <div class="bg-white border rounded-xl p-5 shadow-sm">
        <p class="text-sm text-gray-500">Booking Selesai</p>
        <h3 class="text-3xl font-bold text-green-600">
            {{ $stats['completed_booking'] }}
        </h3>
    </div>

</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    <!-- Daftar Booking -->
    <div class="bg-white border rounded-xl p-6 shadow-sm lg:col-span-2">

        <div class="flex justify-between items-center mb-5">
            <h3 class="text-lg font-semibold">
                Daftar Booking
            </h3>

            <a href="{{ route('booking.index') }}"
               class="text-pink-500 hover:underline">
                Lihat Semua
            </a>
        </div>

        <div class="space-y-4">

            @forelse($mybooking as $booking)

                <div class="border rounded-lg p-4">

                    <div class="flex justify-between">

                        <div>

                            <h4 class="font-semibold">
                                {{ $booking->pasien->user->name ?? '-' }}
                            </h4>

                            <p class="text-sm text-gray-500">
                                Tanggal :
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d-m-Y') }}
                            </p>

                            <p class="text-sm text-gray-500">
                                Jam :
                                {{ $booking->booking_time }}
                            </p>

                            @if($booking->notes)
                                <p class="text-sm mt-2">
                                    Keterangan :
                                    {{ $booking->notes }}
                                </p>
                            @endif

                        </div>

                        <div class="text-right">

                            <span class="inline-block px-3 py-1 rounded-full text-xs bg-gray-100">
                                {{ ucfirst($booking->status) }}
                            </span>

                            <div class="mt-3">

                                @if($booking->status == 'pending')

                                <form action="{{ route('booking.status',$booking) }}"
                                      method="POST">

                                    @csrf
                                    @method('PATCH')

                                    <input type="hidden"
                                           name="status"
                                           value="confirmed">

                                    <button
                                        class="bg-pink-500 hover:bg-pink-600 text-white px-3 py-1 rounded">
                                        Konfirmasi
                                    </button>

                                </form>

                                @endif

                                <a href="{{ route('booking.show',$booking) }}"
                                   class="text-blue-500 hover:underline block mt-2">
                                    Detail
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="text-center text-gray-500 py-6">
                    Belum ada data booking.
                </div>

            @endforelse

        </div>

        <div class="mt-4">
            {{ $mybooking->links() }}
        </div>

    </div>

    <!-- Jadwal Praktik -->
    <div class="bg-white border rounded-xl p-6 shadow-sm">

        <div class="flex justify-between items-center mb-5">

            <h3 class="text-lg font-semibold">
                Jadwal Praktik
            </h3>

            <a href="{{ route('schedules.index') }}"
               class="text-pink-500 hover:underline">
                Kelola
            </a>

        </div>

        <div class="space-y-3">

            @forelse($mySchedules as $schedule)

                <div class="border rounded-lg p-3 flex justify-between">

                    <div>

                        <p class="font-medium">
                            {{ $schedule->day }}
                        </p>

                        <p class="text-sm text-gray-500">
                            {{ $schedule->start_time }}
                            -
                            {{ $schedule->end_time }}
                        </p>

                    </div>

                    <div>

                        @if($schedule->status == 'available')
                            <span class="text-green-600">
                                Tersedia
                            </span>
                        @else
                            <span class="text-red-600">
                                Tidak Tersedia
                            </span>
                        @endif

                    </div>

                </div>

            @empty

                <div class="text-center text-gray-500 py-6">
                    Belum ada jadwal praktik.
                </div>

            @endforelse

        </div>

        <a href="{{ route('schedules.create') }}"
           class="block mt-6 text-center bg-pink-500 hover:bg-pink-600 text-white py-2 rounded-lg">
            Tambah Jadwal
        </a>

    </div>

</div>

@endrole
