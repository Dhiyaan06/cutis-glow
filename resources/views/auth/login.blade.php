<x-guest-layout>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="text-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Login</h2>
        <p class="text-sm text-gray-500">
            Silakan masuk untuk melanjutkan.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

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
                autofocus
                autocomplete="username"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-pink-500 focus:ring-pink-500">

            <x-input-error :messages="$errors->get('email')" class="mt-2" />
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
                autocomplete="current-password"
                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:border-pink-500 focus:ring-pink-500">

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember -->
        <div class="flex items-center justify-between mb-6">

            <label class="flex items-center">
                <input
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-pink-500">

                <span class="ml-2 text-sm text-gray-600">
                    Remember Me
                </span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                   class="text-sm text-pink-500 hover:underline">
                    Forgot Password?
                </a>
            @endif

        </div>

        <!-- Button -->
        <button
            type="submit"
            class="w-full bg-pink-500 hover:bg-pink-600 text-white py-2.5 rounded-lg font-medium transition">
            Login
        </button>

        <!-- Register -->
        <p class="mt-5 text-center text-sm text-gray-600">
            Belum punya akun?
            <a href="{{ route('register') }}"
               class="text-pink-500 hover:underline">
                Register
            </a>
        </p>

    </form>

</x-guest-layout>
