<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cutis Glow - Skin Care Clinic</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=playfair-display:600,700|plus-jakarta-sans:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .serif-title {
                font-family: 'Playfair Display', serif;
            }
        </style>
    </head>
    <body class="antialiased text-slate-800 bg-gradient-to-br from-pink-50 via-white to-pink-100 min-h-screen flex flex-col justify-between">

        <!-- Navbar -->
        <header class="w-full bg-white/70 backdrop-blur-md sticky top-0 z-50 border-b border-pink-100">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

                <!-- Logo -->
            <a href="/" class="flex items-center gap-2 group">
            <div class="w-10 h-10 bg-pink-100 rounded-full flex items-center justify-center border border-pink-200 shadow-sm shadow-pink-100 group-hover:scale-105 transition-transform duration-200">
            <svg class="w-5 h-5 text-pink-500" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L14.7 9.3L22 12L14.7 14.7L12 22L9.3 14.7L2 12L9.3 9.3L12 2Z"/>
            </svg>
            </div>

    <span class="serif-title text-xl font-bold text-pink-600 tracking-wide">
        Cutis Glow
    </span>
</a>

                <!-- Navigation Action -->
                <div>
                    @if (Route::has('login'))
                        <div class="flex items-center gap-3">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 bg-pink-500 hover:bg-pink-400 text-white rounded-full text-sm font-bold tracking-wide shadow-md transition duration-150">
                                    Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-pink-600 transition duration-150">
                                    Masuk
                                </a>
                                @if (Route::has('register'))
                                    <a href="{{ route('register') }}" class="px-5 py-2.5 bg-pink-500 hover:bg-pink-400 text-white rounded-full text-sm font-bold tracking-wide shadow-md transition duration-150">
                                        Daftar
                                    </a>
                                @endif
                            @endauth
                        </div>
                    @endif
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <main class="flex-grow flex items-center">
            <div class="max-w-7xl mx-auto px-6 py-12 md:py-20 w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Text Area -->
                <div class="space-y-6 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 bg-pink-100/60 border border-pink-200/50 rounded-full px-4 py-1.5 text-xs font-bold tracking-wider uppercase text-pink-600">
                        ✨ Klinik Kecantikan Terpercaya
                    </div>
                    <h1 class="serif-title text-4xl md:text-5xl lg:text-6xl font-extrabold text-slate-900 leading-tight">
                        Pancarkan Pesona Kulit <span class="text-pink-500 relative">Sehat & Glowing</span> Anda
                    </h1>
                    <p class="text-slate-600 text-base md:text-lg max-w-lg mx-auto lg:mx-0 leading-relaxed">
                        Nikmati layanan perawatan kulit profesional di Cutis Glow dengan dukungan dokter spesialis kulit berpengalaman dan teknologi modern.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2">
                        @auth
                            <a href="{{ route('booking-konsultasi.index') }}" class="px-8 py-3.5 bg-pink-500 hover:bg-pink-400 text-white font-bold rounded-full shadow-lg shadow-pink-200 tracking-wide transition duration-150 text-center">
                                Booking Konsultasi
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="px-8 py-3.5 bg-pink-500 hover:bg-pink-400 text-white font-bold rounded-full shadow-lg shadow-pink-200 tracking-wide transition duration-150 text-center">
                                Mulai Sekarang
                            </a>
                            <a href="{{ route('login') }}" class="px-8 py-3.5 bg-white hover:bg-pink-50/50 text-pink-600 border border-pink-200 font-bold rounded-full tracking-wide transition duration-150 text-center">
                                Jadwal Dokter
                            </a>
                        @endauth
                    </div>
                </div>

                <!-- Graphic / Visual Section -->
                <div class="relative flex justify-center items-center">
                    <div class="w-72 h-72 md:w-96 md:h-96 bg-pink-200/50 rounded-full absolute filter blur-3xl opacity-60"></div>
                    <div class="bg-white/80 border-4 border-white shadow-2xl p-8 rounded-[2rem] w-full max-w-md relative backdrop-blur-md space-y-6">
                        <div class="flex items-center gap-3">
                            <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-pink-50 p-4 rounded-2xl border border-pink-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-pink-200 rounded-xl flex items-center justify-center text-pink-600 font-bold">1</div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Konsultasi Dokter</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Sesi tatap muka dengan dokter spesialis</p>
                                </div>
                            </div>
                            <div class="bg-pink-50 p-4 rounded-2xl border border-pink-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-pink-200 rounded-xl flex items-center justify-center text-pink-600 font-bold">2</div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Treatment Modern</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Laser, facial, & perawatan jerawat</p>
                                </div>
                            </div>
                            <div class="bg-pink-50 p-4 rounded-2xl border border-pink-100 flex items-center gap-4">
                                <div class="w-10 h-10 bg-pink-200 rounded-xl flex items-center justify-center text-pink-600 font-bold">3</div>
                                <div>
                                    <h4 class="font-bold text-slate-800 text-sm">Produk Teruji</h4>
                                    <p class="text-xs text-slate-500 mt-0.5">Skincare aman dengan sertifikat medis</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="w-full bg-white border-t border-pink-100 py-6 text-center text-xs text-slate-500">
            <div class="max-w-7xl mx-auto px-6">
                &copy; 2026 Cutis Glow Clinic. Seluruh hak cipta dilindungi.
            </div>
        </footer>
    </body>
</html>
