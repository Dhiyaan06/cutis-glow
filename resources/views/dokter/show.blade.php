<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Detail Profil Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">
                <div class="flex items-center space-x-4 mb-6">
                    <img src="/logo.png" alt="Logo" class="h-16 w-16 rounded-full border-2 border-pink-200 shadow-sm">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $dokter->user->name }}</h3>
                        <p class="text-sm text-pink-600 font-medium">{{ $dokter->spesialis }}</p>
                    </div>
                </div>

                <div class="border-t border-pink-100 py-4 space-y-3">
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Email Login</span>
                        <span class="text-sm text-gray-800 col-span-2">{{ $dokter->user->email }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Nomor STR</span>
                        <span class="text-sm text-gray-800 col-span-2 font-mono text-xs">{{ $dokter->no_str }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Nomor HP</span>
                        <span class="text-sm text-gray-800 col-span-2">{{ $dokter->no_hp }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Alamat Praktek</span>
                        <span class="text-sm text-gray-800 col-span-2">{{ $dokter->alamat }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Jadwal Praktek</span>
                        <span class="text-sm text-gray-800 col-span-2">{{ $dokter->jadwal_praktek }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Status Aktif</span>
                        <span class="col-span-2">
                            @if(($dokter->user->status_aktif ?? 'aktif') === 'aktif')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Nonaktif</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-6 border-t border-pink-50">
                    <a href="{{ route('dokter.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium text-sm transition">
                        Kembali
                    </a>
                    @role('admin')
                    <a href="{{ route('dokter.edit', $dokter->id_dokter) }}" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-md font-semibold text-sm transition shadow-sm">
                        Edit Profil
                    </a>
                    @endrole
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
