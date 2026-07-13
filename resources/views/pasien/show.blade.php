<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Detail Profil Pasien') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">
                <div class="flex items-center space-x-4 mb-6">
                    <div class="h-16 w-16 rounded-full bg-pink-100 flex items-center justify-center text-pink-600 font-bold text-2xl">
                        {{ substr($pasien->user->name, 0, 1) }}
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">{{ $pasien->user->name }}</h3>
                        <p class="text-sm text-gray-500 font-medium">ID Pasien: #PSN-{{ $pasien->id_pasien }}</p>
                    </div>
                </div>

                <div class="border-t border-pink-100 py-4 space-y-3">
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Email Login</span>
                        <span class="text-sm text-gray-800 col-span-2">{{ $pasien->user->email }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Nomor HP</span>
                        <span class="text-sm text-gray-800 col-span-2">{{ $pasien->no_hp }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Jenis Kelamin</span>
                        <span class="text-sm text-gray-800 col-span-2">{{ $pasien->jenis_kelamin == 'L' ? 'Laki-laki (L)' : 'Perempuan (P)' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Tanggal Lahir</span>
                        <span class="text-sm text-gray-800 col-span-2">{{ \Carbon\Carbon::parse($pasien->tanggal_lahir)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Alamat Tempat Tinggal</span>
                        <span class="text-sm text-gray-800 col-span-2">{{ $pasien->alamat }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <span class="text-sm font-semibold text-gray-500">Status Aktif</span>
                        <span class="col-span-2">
                            @if(($pasien->user->status_aktif ?? 'aktif') === 'aktif')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-800">Nonaktif</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-6 border-t border-pink-50">
                    <a href="{{ route('pasien.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium text-sm transition">
                        Kembali
                    </a>
                    <a href="{{ route('pasien.edit', $pasien->id_pasien) }}" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-md font-semibold text-sm transition shadow-sm">
                        Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
