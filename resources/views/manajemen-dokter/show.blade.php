<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Data Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                <h3 class="text-lg font-bold text-gray-700 mb-6">
                    Detail Data Dokter
                </h3>

                <table class="w-full border border-gray-200">

                    <tr>
                        <td class="border border-gray-200 p-3 font-semibold w-1/3">
                            Nama Dokter
                        </td>
                        <td class="border border-gray-200 p-3">
                            {{ $dokter->pengguna->name }}
                        </td>
                    </tr>

                    <tr>
                        <td class="border border-gray-200 p-3 font-semibold">
                            Spesialis
                        </td>
                        <td class="border border-gray-200 p-3">
                            {{ $dokter->spesialis }}
                        </td>
                    </tr>

                    <tr>
                        <td class="border border-gray-200 p-3 font-semibold">
                            Nomor STR
                        </td>
                        <td class="border border-gray-200 p-3">
                            {{ $dokter->no_str }}
                        </td>
                    </tr>

                    <tr>
                        <td class="border border-gray-200 p-3 font-semibold">
                            Nomor HP
                        </td>
                        <td class="border border-gray-200 p-3">
                            {{ $dokter->no_hp }}
                        </td>
                    </tr>

                    <tr>
                        <td class="border border-gray-200 p-3 font-semibold">
                            Jadwal Praktek
                        </td>
                        <td class="border border-gray-200 p-3">
                            {{ $dokter->jadwal_praktek }}
                        </td>
                    </tr>

                    <tr>
                        <td class="border border-gray-200 p-3 font-semibold">
                            Alamat
                        </td>
                        <td class="border border-gray-200 p-3">
                            {{ $dokter->alamat }}
                        </td>
                    </tr>

                </table>

                <div class="mt-6 flex justify-end">

                    <a href="{{ route('dokter.index') }}"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">
                        Kembali
                    </a>

                </div>

            </div>

        </div>
    </div>
</x-app-layout>
