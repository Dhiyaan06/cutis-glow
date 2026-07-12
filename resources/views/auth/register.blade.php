<x-guest-layout>

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Register</h2>
        <p class="text-sm text-gray-500">
            Silakan lengkapi data untuk membuat akun.
        </p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Nama -->
        <div class="mb-4">
            <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                Nama Lengkap
            </label>

            <input
                id="name"
                type="text"
                name="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-pink-500 focus:ring-pink-500">

            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Email -->
        <div class="mb-4">
            <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                Email
            </label>

            <input
                id="email"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autocomplete="username"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-pink-500 focus:ring-pink-500">

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Nomor HP -->
        <div class="mb-4">
            <label for="no_hp" class="block mb-2 text-sm font-medium text-gray-700">
                Nomor HP
            </label>

            <input
                id="no_hp"
                type="text"
                name="no_hp"
                value="{{ old('no_hp') }}"
                required
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-pink-500 focus:ring-pink-500">

            <x-input-error :messages="$errors->get('no_hp')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">
                Password
            </label>

            <input
                id="password"
                type="password"
                name="password"
                required
                autocomplete="new-password"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-pink-500 focus:ring-pink-500">

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-6">
            <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700">
                Konfirmasi Password
            </label>

            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                required
                autocomplete="new-password"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-pink-500 focus:ring-pink-500">

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Tombol Register -->
        <button
            type="submit"
            class="w-full bg-pink-500 hover:bg-pink-600 text-white py-2.5 rounded-lg font-medium transition">
            Register
        </button>

        <!-- Link Login -->
        <p class="mt-5 text-center text-sm text-gray-600">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-pink-500 hover:underline">
                Login
            </a>
        </p>

    </form>

</x-guest-layout>
