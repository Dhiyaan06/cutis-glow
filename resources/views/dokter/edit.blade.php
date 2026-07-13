<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-pink-600 leading-tight">
            {{ __('Edit Data Dokter') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-t-4 border-pink-400">
                <h3 class="text-lg font-bold text-gray-700 mb-6">Edit Profil Dokter: {{ $dokter->user->name }}</h3>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 p-3 rounded-md mb-6 text-sm">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('dokter.update', $dokter->id_dokter) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Nama Lengkap Dokter</label>
                        <input type="text" name="name" value="{{ old('name', $dokter->user->name) }}" required
                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Email (Untuk Login)</label>
                            <input type="email" name="email" value="{{ old('email', $dokter->user->email) }}" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Password Baru (Biarkan kosong jika tidak diganti)</label>
                            <input type="password" name="password" placeholder="Minimal 8 karakter"
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Spesialis</label>
                            <input type="text" name="spesialis" value="{{ old('spesialis', $dokter->spesialis) }}" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Nomor STR</label>
                            <input type="text" name="no_str" value="{{ old('no_str', $dokter->no_str) }}" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Nomor HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $dokter->no_hp) }}" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700">Status Aktif</label>
                            <select name="status_aktif" required
                                class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                <option value="aktif" {{ old('status_aktif', $dokter->user->status_aktif) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                <option value="nonaktif" {{ old('status_aktif', $dokter->user->status_aktif) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                        </div>
                    </div>

                    <!-- BAGIAN REPEATER JADWAL -->
                    <div class="border border-pink-100 p-4 rounded-md bg-pink-50/30">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jadwal Praktek Dokter</label>

                        <div id="jadwal-container" class="space-y-3">
                            @foreach($jadwalList as $index => $jadwal)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end jadwal-row">
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Hari</label>
                                    <input type="text" name="hari[]" value="{{ old('hari.'.$index, $jadwal['hari']) }}" required placeholder="e.g. Senin"
                                        class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Jam Mulai</label>
                                    <input type="time" name="jam_mulai[]" value="{{ old('jam_mulai.'.$index, $jadwal['jam_mulai']) }}" required
                                        class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                </div>
                                <div>
                                    <label class="block text-xs font-medium text-gray-500">Jam Selesai</label>
                                    <div class="flex gap-2">
                                        <input type="time" name="jam_selesai[]" value="{{ old('jam_selesai.'.$index, $jadwal['jam_selesai']) }}" required
                                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">
                                        <!-- Tombol Hapus hanya tampil jika bukan baris pertama -->
                                        <button type="button" class="bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded-md text-sm btn-hapus-jadwal {{ $loop->first ? 'hidden' : '' }}">
                                            Hapus
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>

                        <button type="button" id="btn-tambah-jadwal" class="mt-3 text-xs bg-pink-100 text-pink-700 px-3 py-1.5 rounded font-semibold hover:bg-pink-200 transition">
                            + Tambah Hari/Jam Lain
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700">Alamat Tempat Tinggal / Ruangan</label>
                        <textarea name="alamat" rows="3" required
                            class="w-full mt-1 border-pink-200 rounded-md focus:ring-pink-500 focus:border-pink-500 text-sm">{{ old('alamat', $dokter->alamat) }}</textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-4">
                        <a href="{{ route('dokter.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-md font-medium text-sm transition">
                            Batal
                        </a>
                        <button type="submit" class="bg-pink-600 hover:bg-pink-700 text-white px-5 py-2 rounded-md font-semibold text-sm transition shadow-sm">
                            Perbarui Dokter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('btn-tambah-jadwal').addEventListener('click', function() {
            const container = document.getElementById('jadwal-container');
            const newRow = container.children[0].cloneNode(true);

            // Reset nilai input
            newRow.querySelectorAll('input').forEach(input => input.value = '');

            // Tampilkan tombol hapus
            newRow.querySelector('.btn-hapus-jadwal').classList.remove('hidden');

            container.appendChild(newRow);
        });

        document.getElementById('jadwal-container').addEventListener('click', function(e) {
            if (e.target.classList.contains('btn-hapus-jadwal')) {
                e.target.closest('.jadwal-row').remove();
            }
        });
    </script>
</x-app-layout>
