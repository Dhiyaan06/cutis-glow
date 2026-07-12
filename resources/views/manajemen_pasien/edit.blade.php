<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Data Pasien') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    @if ($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc ml-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pasien.update', $pasien->id_pasien) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="block font-medium mb-2">Nama</label>
                            <input type="text" name="name" value="{{ old('name', $pasien->nama) }}"
                                class="w-full border rounded px-3 py-2" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-2">Email</label>
                            <input type="email" name="email" value="{{ old('email', $pasien->email) }}"
                                class="w-full border rounded px-3 py-2" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-2">No HP</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $pasien->no_hp) }}"
                                class="w-full border rounded px-3 py-2" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-2">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="w-full border rounded px-3 py-2" required>
                                <option value="L" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>
                                <option value="P" {{ old('jenis_kelamin', $pasien->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                    Perempuan
                                </option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-2">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir"
                                value="{{ old('tanggal_lahir', $pasien->tanggal_lahir?->format('Y-m-d')) }}"
                                class="w-full border rounded px-3 py-2" required>
                        </div>

                        <div class="mb-4">
                            <label class="block font-medium mb-2">Alamat</label>
                            <textarea name="alamat" rows="4" class="w-full border rounded px-3 py-2"
                                required>{{ old('alamat', $pasien->alamat) }}</textarea>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-2 rounded">
                                Update
                            </button>
                            <a href="{{ route('pasien.index') }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded">
                                Kembali
                            </a>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
