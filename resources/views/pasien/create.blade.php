<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Pasien') }}
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



                    <form action="{{ route('pasien.store') }}" method="POST">

                        @csrf


                        <!-- Nama -->

                        <div class="mb-4">

                            <label class="block font-medium mb-2">
                                Nama Pasien
                            </label>


                            <input
                                type="text"
                                name="name"
                                value="{{ old('name') }}"
                                class="w-full border rounded px-3 py-2"
                                required>

                        </div>



                        <!-- Email -->

                        <div class="mb-4">

                            <label class="block font-medium mb-2">
                                Email
                            </label>


                            <input
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                class="w-full border rounded px-3 py-2"
                                required>

                        </div>




                        <!-- Password -->

                        <div class="mb-4">

                            <label class="block font-medium mb-2">
                                Password
                            </label>


                            <input
                                type="password"
                                name="password"
                                class="w-full border rounded px-3 py-2"
                                required>

                        </div>




                        <!-- No HP -->

                        <div class="mb-4">

                            <label class="block font-medium mb-2">
                                No HP
                            </label>


                            <input
                                type="text"
                                name="no_hp"
                                value="{{ old('no_hp') }}"
                                class="w-full border rounded px-3 py-2"
                                required>

                        </div>




                        <!-- Jenis Kelamin -->

                        <div class="mb-4">

                            <label class="block font-medium mb-2">
                                Jenis Kelamin
                            </label>


                            <select
                                name="jenis_kelamin"
                                class="w-full border rounded px-3 py-2"
                                required>


                                <option value="">
                                    -- Pilih Jenis Kelamin --
                                </option>


                                <option value="L"
                                    {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>
                                    Laki-laki
                                </option>


                                <option value="P"
                                    {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>
                                    Perempuan
                                </option>


                            </select>

                        </div>





                        <!-- Tanggal Lahir -->

                        <div class="mb-4">

                            <label class="block font-medium mb-2">
                                Tanggal Lahir
                            </label>


                            <input
                                type="date"
                                name="tanggal_lahir"
                                value="{{ old('tanggal_lahir') }}"
                                class="w-full border rounded px-3 py-2"
                                required>

                        </div>





                        <!-- Alamat -->

                        <div class="mb-4">

                            <label class="block font-medium mb-2">
                                Alamat
                            </label>


                            <textarea
                                name="alamat"
                                rows="4"
                                class="w-full border rounded px-3 py-2"
                                required>{{ old('alamat') }}</textarea>


                        </div>





                        <!-- Tombol -->

                        <div class="flex gap-3">


                            <button
                                type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded">

                                Simpan

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
