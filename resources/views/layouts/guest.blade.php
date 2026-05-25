<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        @php
            $siteName = config('app.name', 'FlyoverBD');
            $routeName = request()->route()?->getName();
            $derivedTitle = null;

            if ($routeName) {
                $parts = array_values(array_filter(explode('.', $routeName), function ($part) {
                    return ! in_array($part, ['index', 'show', 'create', 'edit', 'store', 'update', 'destroy', 'dashboard'], true);
                }));

                if (! empty($parts)) {
                    $derivedTitle = collect($parts)->map(fn ($part) => \Illuminate\Support\Str::headline($part))->implode(' ');
                }
            }

            $resolvedTitle = $title ?? $derivedTitle ?? $siteName;
            $browserTitle = $resolvedTitle;
        @endphp

        <title>{{ $browserTitle }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    @php
        $authHeroImages = [
            asset('banner/hero-banner-1.png'),
            asset('banner/helo-banner-2.png'),
            asset('banner/hero-banner-3.png'),
            asset('banner/hero-banner-4.jpg'),
            asset('banner/hero-banner-5.jpg'),
        ];

        $authHeroImage = $authHeroImages[array_rand($authHeroImages)];
    @endphp
    <body class="font-sans antialiased text-gray-900 bg-gray-50 pb-[72px] md:pb-0">
        <div class="min-h-screen flex flex-col">
            <nav
                x-data="{
                    showNavLinks: true,
                    mobileOpen: false,
                    userOpen: false
                }"
                class="bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-0 z-50"
            >
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-16">

                        <a href="{{ route('home') }}" class="shrink-0">
                            <img src="{{ asset('logo.png') }}" alt="FlyoverBD" class="h-10 w-auto">
                        </a>

                        <div class="hidden md:flex items-center space-x-1" x-cloak>
                            @foreach([
                                ['Tours',      'packages.index',  'packages.*',  'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
                                ['Visa',       'visas.index',     'visas.*',     'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2'],
                                ['Hotels',     'hotels.index',    'hotels.*',    'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['Pik & Drop', 'transfers.index', 'transfers.*', 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                                ['Blog',       'blog.index',      'blog.*',      'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                                ['About',      'about',           'about',       'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            ] as [$label, $route, $match, $icon])
                            <a href="{{ route($route) }}"
                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-semibold transition
                                    {{ request()->routeIs($match)
                                        ? 'text-red-600 bg-red-50'
                                        : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                                {{ $label }}
                            </a>
                            @endforeach
                        </div>

                        <div class="flex items-center gap-3">
                            <div class="hidden md:flex items-center gap-2">
                                <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-50 transition">Sign In</a>
                                <a href="{{ route('register') }}" class="btn-primary text-sm py-2 px-4">Register</a>
                            </div>

                            <div class="flex md:hidden items-center gap-1">
                                <a href="{{ route('login') }}" class="text-xs font-semibold text-gray-600 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Sign In</a>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="flex-grow">
                <div class="min-h-[calc(100vh-64px)] flex items-center justify-center px-4 py-10 sm:px-6 lg:px-8">
                    <div class="w-full max-w-5xl overflow-hidden rounded-[2.5rem] border border-white/80 bg-white/90 shadow-[0_30px_100px_rgba(15,23,42,0.10)] lg:grid lg:grid-cols-[1.05fr_0.95fr]">
                        <div class="relative min-h-[230px] overflow-hidden lg:min-h-[620px]">
                            <div class="absolute inset-0 bg-cover bg-center scale-[1.02]" style="background-image:url('{{ $authHeroImage }}'); filter: blur(.55px) saturate(1.02) brightness(.95);"></div>
                            <div class="absolute inset-0 bg-[linear-gradient(180deg,rgba(11,16,32,0.10)_0%,rgba(11,16,32,0.48)_100%)]"></div>
                            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(255,255,255,0.26),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(200,16,46,0.16),transparent_35%)]"></div>
                            <div class="absolute inset-0 bg-[linear-gradient(135deg,rgba(255,255,255,0.05)_0%,rgba(255,255,255,0)_28%,rgba(0,0,0,0.12)_100%)]"></div>

                            <div class="relative z-10 flex h-full flex-col justify-between p-6 sm:p-8 lg:p-10 text-white">
                                <div class="inline-flex w-fit items-center gap-2 rounded-full border border-white/18 bg-white/12 px-4 py-2 text-[11px] font-bold uppercase tracking-[0.35em] backdrop-blur-sm shadow-[0_2px_10px_rgba(0,0,0,0.18)]">
                                    <span class="h-2 w-2 rounded-full bg-red-400"></span>
                                    FlyoverBD Travel
                                </div>

                                <div class="max-w-md">
                                    <p class="text-xs font-bold uppercase tracking-[0.35em] text-white/90 drop-shadow-[0_2px_8px_rgba(0,0,0,0.35)]">Discover Beyond</p>
                                    <h2 class="mt-3 font-display text-3xl leading-tight sm:text-4xl text-white drop-shadow-[0_3px_16px_rgba(0,0,0,0.45)]">Plan your next trip with the same calm, polished feel as the public site.</h2>
                                    <p class="mt-3 text-sm leading-6 text-white/90 drop-shadow-[0_2px_10px_rgba(0,0,0,0.35)]">Access your account, bookings, and travel updates from one secure place.</p>
                                </div>

                                <div class="flex flex-wrap gap-2 text-xs font-semibold text-white/90">
                                    <span class="rounded-full border border-white/18 bg-white/12 px-3 py-1.5 backdrop-blur-sm shadow-[0_2px_10px_rgba(0,0,0,0.12)]">Tours</span>
                                    <span class="rounded-full border border-white/18 bg-white/12 px-3 py-1.5 backdrop-blur-sm shadow-[0_2px_10px_rgba(0,0,0,0.12)]">Visa</span>
                                    <span class="rounded-full border border-white/18 bg-white/12 px-3 py-1.5 backdrop-blur-sm shadow-[0_2px_10px_rgba(0,0,0,0.12)]">Hotels</span>
                                    <span class="rounded-full border border-white/18 bg-white/12 px-3 py-1.5 backdrop-blur-sm shadow-[0_2px_10px_rgba(0,0,0,0.12)]">Pik & Drop</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-center p-6 sm:p-8 lg:p-10">
                            <div class="w-full max-w-md">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <footer style="background:#18130E;" class="text-white pt-8 sm:pt-14 pb-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-10 pb-8 sm:pb-10 border-b" style="border-color:#2E2720;">
                        <div class="col-span-2 md:col-span-1">
                            <a href="{{ route('home') }}" class="mb-4 inline-block">
                                <img src="{{ asset('logo.png') }}" alt="FlyoverBD" class="h-10 w-auto">
                            </a>
                            <p class="text-sm leading-relaxed mb-5" style="color:#A09890;">Bangladesh's trusted travel agency for tours, visa processing, and holiday packages.</p>
                            <a href="https://wa.me/8801335111370" target="_blank"
                               class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white text-xs font-bold px-4 py-2.5 rounded-xl transition">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Chat on WhatsApp
                            </a>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-widest mb-5" style="color:#C8102E;">Services</h3>
                            <ul class="space-y-3 text-sm" style="color:#A09890;">
                                <li><a href="{{ route('packages.index') }}" class="hover:text-white transition">Tour Packages</a></li>
                                <li><a href="{{ route('visas.index') }}" class="hover:text-white transition">Visa Processing</a></li>
                                <li><a href="{{ route('customize.index') }}" class="hover:text-white transition">Custom Trip Planning</a></li>
                                <li><a href="{{ route('blog.index') }}" class="hover:text-white transition">Travel Blog</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-widest mb-5" style="color:#C8102E;">Company</h3>
                            <ul class="space-y-3 text-sm" style="color:#A09890;">
                                <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                                <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                                <li><a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                            </ul>
                        </div>

                        <div>
                            <h3 class="text-xs font-bold uppercase tracking-widest mb-5" style="color:#C8102E;">Get in Touch</h3>
                            <ul class="space-y-3 text-sm" style="color:#A09890;">
                                <li class="flex items-start gap-2">
                                    <svg class="w-4 h-4 mt-0.5 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    House 45, Road 13, Block D, Banani, Dhaka
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    <a href="tel:09611677989" class="hover:text-white transition">09611-677989</a>
                                </li>
                                <li class="flex items-center gap-2">
                                    <svg class="w-4 h-4 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    <a href="mailto:info@flyoverbd.net" class="hover:text-white transition">info@flyoverbd.net</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs" style="color:#6B6157;">
                        <p>&copy; {{ date('Y') }} FlyoverBD. All rights reserved.</p>
                        <p>Licensed Travel Agency · ATAB · TOAB Member</p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
