<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50">
    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav 
            x-data="{ 
                showNavLinks: {{ request()->routeIs('home') ? 'false' : 'true' }} 
            }"
            @if(request()->routeIs('home'))
                @scroll.window="showNavLinks = (window.scrollY > 500)"
            @endif
            class="bg-white/80 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-0 z-50 transition-all duration-300"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 flex items-center">
                            <a href="{{ route('home') }}">
                                <img src="{{ asset('logo.png') }}" alt="FlyoverBD" class="h-10 w-auto">
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex" x-cloak>
                            <a href="{{ route('home') }}"
                                class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('home') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                Home
                            </a>
                            
                            <a href="{{ route('packages.index') }}"
                                x-show="showNavLinks"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('packages.*') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAACWUlEQVR4nO2avW7UQBDHf6K4JDSYAgTp8gKhgAroQEI08Aa5VGmiVERECTUEXgB4CEiegAgIygctke6OD4lEuRR8BKGjXxTpb2kVGZ+9vvWai0cayTPeWc3P3t0ZS4YTIA3gCXAAmMDaBZaVU255XAEAc0wfuYB0FXyV8HLNejO5JX4KVRHjmk8N4klMDcIJB4mAaWAVaAN/pG35mhpTVj7kDRwFFoBfGerBIXBfMb7ycQocB7YTEn5lXa8l3N8CLlYFZBzY1divwEtdfwLGrHnG5DMaE8fsZoQxPkFGrTexqbX/UfadhHnu6roDnD0WOxISZEFjPgPngCvWUz6VMM+Rb0/2ZeA88EX2fCiQyNrYt+R7IPtpyjzPZS/Jvm0dAFEIkGndf2354v0xkzLPjOwXlm9dvmaBfJwDV3V/1vLtyHcjZZ6bsj9YvjnrECgdJN7Uk5bvp3wTKfNMyP5u+S7J1y6Qj3NgL0Phy6u9YQH5HQKkk7C0fuRYWt8SllarQD7OgSvDstmbuv9mAMfvO/mmCuTjHBipiNkFcUn2swwFcTGhIJ4JAYJa8UG1KPcGkE+hpnFL47bVCHZyNI3vZW+EbhpRC2635P9q40/rzcWbes9q/S/QX4xvkBhmM6EurPX5sNrICFEaCFoa89YBkKaH2hMjHvMpHBjpaF5RgetJW1pSU31Op8qA+BJTg1CDeBFTg1CDVB+koe9q41nXywBplQDy1jdISDE1CDWIFzE1CEMC0lXg0e8ToeW6ctl3CV4uoV7k1YcuIA3BxG8mpO4Lwuk3p/9K/gI5HHNwyRPgDAAAAABJRU5ErkJggg==" alt="Tour" class="w-5 h-5 mr-1 pt-1">
                                Tours
                            </a>
                            
                            <a href="{{ route('visas.index') }}"
                                x-show="showNavLinks"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform scale-95"
                                x-transition:enter-end="opacity-100 transform scale-100"
                                class="inline-flex items-center px-1 pt-1 border-b-2 {{ request()->routeIs('visas.*') ? 'border-red-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} text-sm font-medium leading-5 transition duration-150 ease-in-out">
                                <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAACWUlEQVR4nO2avW7UQBDHf6K4JDSYAgTp8gKhgAroQEI08Aa5VGmiVERECTUEXgB4CEiegAgIygctke6OD4lEuRR8BKGjXxTpb2kVGZ+9vvWai0cayTPeWc3P3t0ZS4YTIA3gCXAAmMDaBZaVU255XAEAc0wfuYB0FXyV8HLNejO5JX4KVRHjmk8N4klMDcIJB4mAaWAVaAN/pG35mhpTVj7kDRwFFoBfGerBIXBfMb7ycQocB7YTEn5lXa8l3N8CLlYFZBzY1divwEtdfwLGrHnG5DMaE8fsZoQxPkFGrTexqbX/UfadhHnu6roDnD0WOxISZEFjPgPngCvWUz6VMM+Rb0/2ZeA88EX2fCiQyNrYt+R7IPtpyjzPZS/Jvm0dAFEIkGndf2354v0xkzLPjOwXlm9dvmaBfJwDV3V/1vLtyHcjZZ6bsj9YvjnrECgdJN7Uk5bvp3wTKfNMyP5u+S7J1y6Qj3NgL0Phy6u9YQH5HQKkk7C0fuRYWt8SllarQD7OgSvDstmbuv9mAMfvO/mmCuTjHBipiNkFcUn2swwFcTGhIJ4JAYJa8UG1KPcGkE+hpnFL47bVCHZyNI3vZW+EbhpRC2635P9q40/rzcWbes9q/S/QX4xvkBhmM6EurPX5sNrICFEaCFoa89YBkKaH2hMjHvMpHBjpaF5RgetJW1pSU31Op8qA+BJTg1CDeBFTg1CDVB+koe9q41nXywBplQDy1jdISDE1CDWIFzE1CEMC0lXg0e8ToeW6ctl3CV4uoV7k1YcuIA3BxG8mpO4Lwuk3p/9K/gI5HHNwyRPgDAAAAABJRU5ErkJggg==" alt="Visa" class="w-5 h-5 mr-1 pt-1">
                                Visa
                            </a>
                        </div>
                    </div>

                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        @auth
                            <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                            <a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                    <div>
                        <h3 class="text-lg font-bold mb-4">FlyoverBD</h3>
                        <p class="text-gray-400 text-sm">Your trusted partner for travel and visa services.</p>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-4">Services</h3>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-white">Tour Packages</a></li>
                            <li><a href="#" class="hover:text-white">Visa Processing</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-4">Company</h3>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li><a href="#" class="hover:text-white">About Us</a></li>
                            <li><a href="#" class="hover:text-white">Contact</a></li>
                            <li><a href="#" class="hover:text-white">Privacy Policy</a></li>
                        </ul>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold mb-4">Contact</h3>
                        <ul class="space-y-2 text-gray-400 text-sm">
                            <li>Email: support@flyoverbd.com</li>
                            <li>Phone: +880 1234 567890</li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>