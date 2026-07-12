<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Cutis Glow - Klinik Kecantikan</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-pink-50 text-gray-800">

    <!-- ================= Navbar ================= -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <a href="/" class="text-2xl font-bold text-pink-500">
                Cutis Glow
            </a>

            @if (Route::has('login'))
                <div class="flex items-center gap-3">

                    @auth

                        <a href="{{ url('/dashboard') }}"
                            class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg transition">
                            Dashboard
                        </a>

                    @else

                        <a href="{{ route('login') }}"
                            class="text-gray-600 hover:text-pink-500 transition">
                            Login
                        </a>

                        @if(Route::has('register'))

                            <a href="{{ route('register') }}"
                                class="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded-lg transition">
                                Register
                            </a>

                        @endif

                    @endauth

                </div>
            @endif

        </div>
    </header>

    <!-- ================= Main ================= -->
    <main class="flex-1">

        <div class="max-w-7xl mx-auto px-6 py-20">

            <div class="grid lg:grid-cols-2 gap-12 items-center">

                <!-- Hero -->
                <div>

                    <span class="inline-block bg-pink-100 text-pink-600 px-4 py-1 rounded-full text-sm font-medium">
                        Sistem Klinik Kecantikan
                    </span>

                    <h1 class="text-5xl font-bold mt-6 leading-tight">
                        Solusi Mudah Untuk
                        <span class="text-pink-500">
                            Booking Perawatan
                        </span>
                    </h1>

                    <p class="mt-6 text-gray-600 leading-8">
                        Cutis Glow menyediakan layanan konsultasi dokter,
                        booking treatment, serta informasi jadwal praktik
                        yang dapat diakses secara online dengan mudah.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">

                        @auth

                            <a href="{{ route('booking.index') }}"
                                class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-lg transition">
                                Booking Sekarang
                            </a>

                        @else

                            <a href="{{ route('register') }}"
                                class="bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-lg transition">
                                Daftar
                            </a>

                            <a href="{{ route('login') }}"
                                class="border border-pink-500 text-pink-500 hover:bg-pink-100 px-6 py-3 rounded-lg transition">
                                Login
                            </a>

                        @endauth

                    </div>

                </div>

                <!-- Card -->
                <div>

                    <div class="bg-white rounded-xl shadow-md p-8">

                        <h2 class="text-2xl font-semibold mb-6">
                            Layanan Utama
                        </h2>

                        <div class="space-y-5">

                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold text-lg">
                                    Konsultasi Dokter
                                </h3>

                                <p class="text-gray-500 text-sm mt-1">
                                    Konsultasi dengan dokter sesuai jadwal praktik.
                                </p>
                            </div>

                            <div class="border rounded-lg p-4">
                                <h3 class="font-semibold text-lg">
                                    Booking Online
                                </h3>

                                <p class="text-gray-500 text-sm mt-1">
                                    Melakukan pemesanan layanan secara cepat dan mudah.
                                </p>
                            </div>

                        
                        </div>

                    </div>

                </div>

            </div>

        </div>

    </main>

    <!-- ================= Footer ================= -->
    <footer class="bg-white border-t">
        <div class="max-w-7xl mx-auto px-6 py-5 text-center text-sm text-gray-500">
            © {{ date('Y') }} Cutis Glow. All Rights Reserved.
        </div>
    </footer>

</body>
</html>
