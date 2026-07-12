<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white shadow-sm rounded-lg p-6">

                <h1 class="text-2xl font-bold text-blue-600">
                    Selamat Datang Admin
                </h1>

                <p class="mt-3 text-gray-600">
                    Halo,
                    <strong>{{ Auth::user()->name }}</strong>.
                </p>

                <hr class="my-5">

                <h3 class="text-lg font-semibold mb-3">
                    Menu Admin
                </h3>

                <ul class="list-disc ml-6 text-gray-700">
                    <li>Kelola Data Dokter</li>
                    <li>Kelola Data Pasien</li>
                    <li>Kelola Jadwal Dokter</li>
                    <li>Kelola Booking Treatment</li>
                    <li>Lihat Laporan</li>
                </ul>

            </div>

        </div>
    </div>
</x-app-layout>
