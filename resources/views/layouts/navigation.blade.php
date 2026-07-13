<nav x-data="{ open: false }" class="bg-white border-b border-pink-100 shadow-sm">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                        @if(\App\Models\Setting::get('clinic_logo'))
                            <img src="{{ asset(\App\Models\Setting::get('clinic_logo')) }}" alt="Cutis Glow Logo" class="block h-10 w-auto rounded-full border-2 border-pink-200 shadow-sm object-cover">
                        @else
                            <img src="/logo.png" alt="Cutis Glow Logo" class="block h-10 w-auto rounded-full border-2 border-pink-200 shadow-sm">
                        @endif
                        <span class="font-bold text-pink-600 text-lg tracking-wide hidden sm:block">{{ \App\Models\Setting::get('clinic_name', 'Cutis Glow') }}</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-6 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-pink-600 focus:text-pink-700">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    @hasanyrole('admin|dokter|pasien')
                    <x-nav-link :href="route('layanan.index')" :active="request()->routeIs('layanan.*')" class="text-pink-600 focus:text-pink-700">
                        {{ __('Layanan') }}
                    </x-nav-link>
                    <x-nav-link :href="route('dokter.index')" :active="request()->routeIs('dokter.*')" class="text-pink-600 focus:text-pink-700">
                        {{ __('Dokter') }}
                    </x-nav-link>
                    @endhasanyrole

                    @role('admin')
                    <x-nav-link :href="route('pasien.index')" :active="request()->routeIs('pasien.*')" class="text-pink-600 focus:text-pink-700">
                        {{ __('Pasien') }}
                    </x-nav-link>
                    @endrole

                    @hasanyrole('admin|dokter|pasien')
                    <x-nav-link :href="route('booking-konsultasi.index')" :active="request()->routeIs('booking-konsultasi.*')" class="text-pink-600 focus:text-pink-700">
                        {{ __('Booking') }}
                    </x-nav-link>
                    @endhasanyrole

                    @role('admin')
                    <x-nav-link :href="route('jadwal-dokter.index')" :active="request()->routeIs('jadwal-dokter.*')" class="text-pink-600 focus:text-pink-700">
                        {{ __('Jadwal') }}
                    </x-nav-link>
                    @endrole

                    @hasanyrole('admin|dokter|pasien')
                    <x-nav-link :href="route('riwayat-layanan.index')" :active="request()->routeIs('riwayat-layanan.*')" class="text-pink-600 focus:text-pink-700">
                        {{ __('Riwayat') }}
                    </x-nav-link>
                    @endhasanyrole
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-pink-200 text-sm leading-4 font-medium rounded-md text-pink-700 bg-pink-50 hover:bg-pink-100 hover:text-pink-900 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4 text-pink-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-pink-50 text-pink-700">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();" class="hover:bg-pink-50 text-pink-700">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-pink-400 hover:text-pink-600 hover:bg-pink-50 focus:outline-none focus:bg-pink-50 focus:text-pink-600 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-pink-100">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-pink-600 border-pink-500 bg-pink-50">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @hasanyrole('admin|dokter|pasien')
            <x-responsive-nav-link :href="route('layanan.index')" :active="request()->routeIs('layanan.*')" class="text-pink-600">
                {{ __('Layanan') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dokter.index')" :active="request()->routeIs('dokter.*')" class="text-pink-600">
                {{ __('Dokter') }}
            </x-responsive-nav-link>
            @endhasanyrole

            @role('admin')
            <x-responsive-nav-link :href="route('pasien.index')" :active="request()->routeIs('pasien.*')" class="text-pink-600">
                {{ __('Pasien') }}
            </x-responsive-nav-link>
            @endrole

            @hasanyrole('admin|dokter|pasien')
            <x-responsive-nav-link :href="route('booking-konsultasi.index')" :active="request()->routeIs('booking-konsultasi.*')" class="text-pink-600">
                {{ __('Booking') }}
            </x-responsive-nav-link>
            @endhasanyrole

            @role('admin')
            <x-responsive-nav-link :href="route('jadwal-dokter.index')" :active="request()->routeIs('jadwal-dokter.*')" class="text-pink-600">
                {{ __('Jadwal') }}
            </x-responsive-nav-link>
            @endrole

            @hasanyrole('admin|dokter|pasien')
            <x-responsive-nav-link :href="route('riwayat-layanan.index')" :active="request()->routeIs('riwayat-layanan.*')" class="text-pink-600">
                {{ __('Riwayat') }}
            </x-responsive-nav-link>
            @endhasanyrole
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-pink-100">
            <div class="px-4">
                <div class="font-medium text-base text-pink-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-pink-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="text-pink-600">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" class="text-pink-600">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
