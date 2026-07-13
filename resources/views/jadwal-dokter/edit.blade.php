<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Edit Jadwal Praktek Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">
                <h3 class="text-lg font-bold text-gray-700 mb-6">Edit Jadwal Praktek #JD-{{ $jadwal->id_jadwal }}</h3>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md mb-6 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('jadwal-dokter.update', $jadwal->id_jadwal) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Pilih Dokter</label>
                        <select name="id_dokter" required
                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                            @foreach($dokterList as $doc)
                                <option value="{{ $doc->id_dokter }}" {{ old('id_dokter', $jadwal->id_dokter) == $doc->id_dokter ? 'selected' : '' }}>{{ $doc->user->name }} - {{ $doc->spesialis }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Hari Praktek</label>
                            <select name="hari" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                @foreach(['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'] as $h)
                                    <option value="{{ $h }}" {{ old('hari', $jadwal->hari) == $h ? 'selected' : '' }}>{{ $h }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Status Operasional</label>
                            <select name="status" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="aktif" {{ old('status', $jadwal->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status', $jadwal->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Jam Mulai</label>
                            <input type="time" name="jam_mulai" value="{{ old('jam_mulai', date('H:i', strtotime($jadwal->jam_mulai))) }}" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Jam Selesai</label>
                            <input type="time" name="jam_selesai" value="{{ old('jam_selesai', date('H:i', strtotime($jadwal->jam_selesai))) }}" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('jadwal-dokter.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-md font-semibold text-sm transition shadow-sm">
                            Perbarui Jadwal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
