<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>{{ $title ?? config('app.name', 'FlyoverBD') }}</title>
    <meta name="description" content="{{ $meta_description ?? 'FlyoverBD is your trusted partner for visa processing, tour packages, and travel consulting in Bangladesh.' }}">
    <meta name="keywords" content="{{ $meta_keywords ?? 'visa processing, tour packages, travel agency bangladesh, flyoverbd, tourist visa, business visa' }}">
    <meta name="robots" content="index, follow">
    <meta name="author" content="FlyoverBD">
    <link rel="canonical" href="{{ $canonical_url ?? url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="{{ $og_type ?? 'website' }}">
    <meta property="og:url" content="{{ $canonical_url ?? url()->current() }}">
    <meta property="og:title" content="{{ $title ?? config('app.name', 'FlyoverBD') }}">
    <meta property="og:description" content="{{ $meta_description ?? 'Your trusted partner for hassle-free visa processing and unforgettable tour packages.' }}">
    <meta property="og:image" content="{{ $meta_image ?? asset('logo.png') }}">
    <meta property="og:image:secure_url" content="{{ $meta_image ?? asset('logo.png') }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="{{ $title ?? config('app.name', 'FlyoverBD') }}">
    <meta property="og:site_name" content="{{ config('app.name', 'FlyoverBD') }}">
    <meta property="og:locale" content="en_BD">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@flyoverbd">
    <meta name="twitter:creator" content="@flyoverbd">
    <meta name="twitter:url" content="{{ $canonical_url ?? url()->current() }}">
    <meta name="twitter:title" content="{{ $title ?? config('app.name', 'FlyoverBD') }}">
    <meta name="twitter:description" content="{{ $meta_description ?? 'Your trusted partner for hassle-free visa processing and unforgettable tour packages.' }}">
    <meta name="twitter:image" content="{{ $meta_image ?? asset('logo.png') }}">
    <meta name="twitter:image:alt" content="{{ $title ?? config('app.name', 'FlyoverBD') }}">

    <!-- Additional SEO -->
    <meta property="fb:app_id" content="{{ env('FACEBOOK_APP_ID', '') }}">

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Analytics Script -->
    <script src="{{ asset('js/analytics.js') }}" defer></script>
    
    @stack('meta')
</head>

<body class="font-sans antialiased text-gray-900 bg-gray-50 pb-[72px] md:pb-0">

    @include('components.loader')

    <div class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav
            x-data="{
                showNavLinks: {{ request()->routeIs('home') ? 'false' : 'true' }},
                mobileOpen: false,
                userOpen: false
            }"
            {!! request()->routeIs('home') ? '@scroll.window="showNavLinks = (window.scrollY > 500)"' : '' !!}
            class="bg-white/90 backdrop-blur-md border-b border-gray-100 shadow-sm sticky top-0 z-50"
        >
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">

                    <!-- Logo -->
                    <a href="{{ route('home') }}" class="shrink-0">
                        <img src="{{ asset('logo.png') }}" alt="FlyoverBD" class="h-10 w-auto">
                    </a>

                    <!-- Desktop Nav Links -->
                    <div class="hidden md:flex items-center space-x-1" x-cloak>
                        @foreach([
                            ['Tours',      'packages.index',  'packages.*',  'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
                            ['Visa',       'visas.index',     'visas.*',     'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2'],
                            ['Hotels',     'hotels.index',    'hotels.*',    'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                            ['Transfers',  'transfers.index', 'transfers.*', 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                            ['Blog',       'blog.index',      'blog.*',      'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                            ['About',      'about',           'about',       'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ] as [$label, $route, $match, $icon])
                        <a href="{{ route($route) }}"
                            x-show="showNavLinks"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95"
                            x-transition:enter-end="opacity-100 scale-100"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg text-sm font-semibold transition
                                {{ request()->routeIs($match)
                                    ? 'text-red-600 bg-red-50'
                                    : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                            {{ $label }}
                        </a>
                        @endforeach
                    </div>

                    <!-- Right: Auth + Mobile Toggle -->
                    <div class="flex items-center gap-3">

                        {{-- Auth: Desktop --}}
                        <div class="hidden md:flex items-center gap-2">
                            @auth
                            {{-- Notification Bell (frontend) --}}
                            @php $userUnreadCount = auth()->user()->unreadNotifications()->count(); @endphp
                            <div class="relative" x-data="{ notifOpen: false }">
                                <button @click="notifOpen = !notifOpen" @keydown.escape.window="notifOpen = false"
                                        class="relative p-2 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @if($userUnreadCount > 0)
                                    <span class="absolute top-1 right-1 w-4 h-4 rounded-full text-[10px] font-bold text-white flex items-center justify-center" style="background:#C8102E;">
                                        {{ $userUnreadCount > 9 ? '9+' : $userUnreadCount }}
                                    </span>
                                    @endif
                                </button>
                                <div x-show="notifOpen" @click.outside="notifOpen = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     style="display:none;"
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 origin-top-right overflow-hidden">
                                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                        <p class="text-sm font-bold text-gray-900">My Notifications</p>
                                        @if($userUnreadCount > 0)
                                        <form method="POST" action="{{ route('user.notifications.read-all') }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold hover:underline" style="color:#C8102E;">Mark all read</button>
                                        </form>
                                        @endif
                                    </div>
                                    <div class="max-h-80 overflow-y-auto divide-y divide-gray-50">
                                        @forelse(auth()->user()->notifications()->latest()->take(8)->get() as $notif)
                                        @php
                                            $nd    = $notif->data;
                                            $nColor = match($nd['color'] ?? 'gray') {
                                                'green'  => ['bg' => 'bg-green-100',  'text' => 'text-green-600'],
                                                'red'    => ['bg' => 'bg-red-100',    'text' => 'text-red-600'],
                                                'blue'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600'],
                                                'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
                                                default  => ['bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
                                            };
                                        @endphp
                                        <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition {{ $notif->read_at ? 'opacity-60' : '' }}">
                                            <div class="w-8 h-8 rounded-xl {{ $nColor['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <svg class="w-3.5 h-3.5 {{ $nColor['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold text-gray-900">{{ $nd['title'] ?? 'Update' }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $nd['message'] ?? '' }}</p>
                                                <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                            </div>
                                            @if(!empty($nd['url']))
                                            <form method="POST" action="{{ route('user.notifications.read', $notif->id) }}" class="flex-shrink-0">
                                                @csrf
                                                <button type="submit" class="text-gray-300 hover:text-red-500 transition mt-1" title="View">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                        @empty
                                        <div class="px-4 py-8 text-center">
                                            <svg class="w-7 h-7 text-gray-200 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                            <p class="text-xs text-gray-400">No notifications yet</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <div class="relative" x-data="{ userOpen: false }">
                                <button @click="userOpen = !userOpen" @keydown.escape.window="userOpen = false"
                                        class="flex items-center gap-2 px-3 py-1.5 rounded-xl border border-gray-200 hover:border-red-200 hover:bg-red-50 transition text-sm font-semibold text-gray-700">
                                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold text-white flex-shrink-0"
                                          style="background:#C8102E;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    <span class="hidden lg:block max-w-[100px] truncate">{{ Auth::user()->name }}</span>
                                    <svg class="w-3.5 h-3.5 text-gray-400 transition-transform" :class="userOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="userOpen" @click.outside="userOpen = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-75"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     style="display:none;"
                                     class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-lg border border-gray-100 py-2 origin-top-right">
                                    <div class="px-4 py-2 border-b border-gray-100 mb-1">
                                        <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                                        <p class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                                    </div>
                                    @if(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        Admin Panel
                                    </a>
                                    <a href="{{ route('admin.analytics.index') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                        Analytics
                                    </a>
                                    @endif
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                        My Account
                                    </a>
                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Profile
                                    </a>
                                    <div class="border-t border-gray-100 mt-1 pt-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full items-center gap-2.5 px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                                Sign Out
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-gray-900 px-3 py-2 rounded-lg hover:bg-gray-50 transition">Sign In</a>
                            <a href="{{ route('register') }}" class="btn-primary text-sm py-2 px-4">Register</a>
                            @endauth
                        </div>

                        {{-- Mobile: show login/register icons only --}}
                        <div class="flex md:hidden items-center gap-1">
                            @auth
                            @php $userUnreadCountMobile = auth()->user()->unreadNotifications()->count(); @endphp
                            <div class="relative" x-data="{ notifOpenM: false }">
                                <button @click="notifOpenM = !notifOpenM" @keydown.escape.window="notifOpenM = false"
                                        class="relative p-2 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                    </svg>
                                    @if($userUnreadCountMobile > 0)
                                    <span class="absolute top-1 right-1 w-4 h-4 rounded-full text-[10px] font-bold text-white flex items-center justify-center" style="background:#C8102E;">
                                        {{ $userUnreadCountMobile > 9 ? '9+' : $userUnreadCountMobile }}
                                    </span>
                                    @endif
                                </button>
                                <div x-show="notifOpenM" @click.outside="notifOpenM = false"
                                     x-transition:enter="transition ease-out duration-100"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     style="display:none;"
                                     class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 origin-top-right overflow-hidden">
                                    <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                                        <p class="text-sm font-bold text-gray-900">My Notifications</p>
                                        @if($userUnreadCountMobile > 0)
                                        <form method="POST" action="{{ route('user.notifications.read-all') }}">
                                            @csrf
                                            <button type="submit" class="text-xs font-semibold hover:underline" style="color:#C8102E;">Mark all read</button>
                                        </form>
                                        @endif
                                    </div>
                                    <div class="max-h-72 overflow-y-auto divide-y divide-gray-50">
                                        @forelse(auth()->user()->notifications()->latest()->take(6)->get() as $notif)
                                        @php
                                            $nd = $notif->data;
                                            $nColor = match($nd['color'] ?? 'gray') {
                                                'green'  => ['bg' => 'bg-green-100',  'text' => 'text-green-600'],
                                                'red'    => ['bg' => 'bg-red-100',    'text' => 'text-red-600'],
                                                'blue'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600'],
                                                'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
                                                default  => ['bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
                                            };
                                        @endphp
                                        <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition {{ $notif->read_at ? 'opacity-60' : '' }}">
                                            <div class="w-8 h-8 rounded-xl {{ $nColor['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                                <svg class="w-3.5 h-3.5 {{ $nColor['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-bold text-gray-900">{{ $nd['title'] ?? 'Update' }}</p>
                                                <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $nd['message'] ?? '' }}</p>
                                                <p class="text-[10px] text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                            </div>
                                        </div>
                                        @empty
                                        <div class="px-4 py-6 text-center">
                                            <p class="text-xs text-gray-400">No notifications yet</p>
                                        </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            @else
                            <a href="{{ route('login') }}" class="text-xs font-semibold text-gray-600 px-3 py-1.5 rounded-lg border border-gray-200 hover:bg-gray-50 transition">Sign In</a>
                            @endauth
                        </div>

                    </div>
                </div>
            </div>

        </nav>

        <!-- Page Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer -->
        <footer style="background:#18130E;" class="text-white pt-8 sm:pt-14 pb-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-10 pb-8 sm:pb-10 border-b" style="border-color:#2E2720;">
                    <!-- Brand -->
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

                    <!-- Services -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest mb-5" style="color:#C8102E;">Services</h3>
                        <ul class="space-y-3 text-sm" style="color:#A09890;">
                            <li><a href="{{ route('packages.index') }}" class="hover:text-white transition">Tour Packages</a></li>
                            <li><a href="{{ route('visas.index') }}" class="hover:text-white transition">Visa Processing</a></li>
                            <li><a href="{{ route('customize.index') }}" class="hover:text-white transition">Custom Trip Planning</a></li>
                            <li><a href="{{ route('blog.index') }}" class="hover:text-white transition">Travel Blog</a></li>
                        </ul>
                    </div>

                    <!-- Company -->
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-widest mb-5" style="color:#C8102E;">Company</h3>
                        <ul class="space-y-3 text-sm" style="color:#A09890;">
                            <li><a href="{{ route('about') }}" class="hover:text-white transition">About Us</a></li>
                            <li><a href="{{ route('contact') }}" class="hover:text-white transition">Contact</a></li>
                            <li><a href="{{ route('privacy') }}" class="hover:text-white transition">Privacy Policy</a></li>
                        </ul>
                    </div>

                    <!-- Contact -->
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

                <!-- Bottom bar -->
                <div class="pt-6 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs" style="color:#6B6157;">
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <p>&copy; {{ date('Y') }} FlyoverBD. All rights reserved.</p>
                        <p>Licensed Travel Agency · ATAB · TOAB Member</p>
                    </div>
                    <p>
                        Developed by 
                        <a href="https://www.innonexus.net" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors" style="color:#9CA3AF;">
                            Innonexus
                        </a>
                    </p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')

    {{-- Browser Push Notification subscription --}}
    <script>
    (function(){
        if(!('serviceWorker' in navigator) || !('PushManager' in window)) return;

        const VAPID_PUBLIC = '{{ env('VAPID_PUBLIC_KEY') }}';

        function urlBase64ToUint8Array(base64String) {
            const padding = '='.repeat((4 - base64String.length % 4) % 4);
            const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
            const raw = window.atob(base64);
            return Uint8Array.from([...raw].map(c => c.charCodeAt(0)));
        }

        navigator.serviceWorker.register('/sw.js').then(function(reg) {
            return reg.pushManager.getSubscription().then(function(existing) {
                if (existing) return; // already subscribed

                // Ask after 3 s to avoid annoying on landing
                setTimeout(function() {
                    Notification.requestPermission().then(function(permission) {
                        if (permission !== 'granted') return;
                        reg.pushManager.subscribe({
                            userVisibleOnly: true,
                            applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC),
                        }).then(function(sub) {
                            const key  = sub.getKey('p256dh');
                            const auth = sub.getKey('auth');
                            fetch('{{ route('push.subscribe') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content ?? '',
                                },
                                body: JSON.stringify({
                                    endpoint:   sub.endpoint,
                                    public_key: btoa(String.fromCharCode(...new Uint8Array(key))),
                                    auth_token: btoa(String.fromCharCode(...new Uint8Array(auth))),
                                }),
                            });
                        });
                    });
                }, 3000);
            });
        });
    })();
    </script>

    <!-- ═══════════════════════════════════════
         MOBILE BOTTOM NAVIGATION BAR
    ═══════════════════════════════════════ -->
    <nav class="mobile-bottom-nav md:hidden fixed bottom-0 left-0 right-0 z-50 bg-white/95 backdrop-blur-xl border-t border-gray-100 shadow-2xl">
        <div class="flex items-stretch h-[68px]">

            {{-- Home --}}
            <a href="{{ route('home') }}"
               class="flex-1 flex flex-col items-center justify-center gap-1 relative transition-all duration-200 active:scale-95
                      {{ request()->routeIs('home') ? 'text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                @if(request()->routeIs('home'))
                <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-red-600 rounded-b-full"></span>
                @endif
                <div class="w-9 h-9 flex items-center justify-center rounded-2xl {{ request()->routeIs('home') ? 'bg-red-50' : '' }}">
                    <svg class="w-5 h-5" fill="{{ request()->routeIs('home') ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold leading-none">Home</span>
            </a>

            {{-- Tours --}}
            <a href="{{ route('packages.index') }}"
               class="flex-1 flex flex-col items-center justify-center gap-1 relative transition-all duration-200 active:scale-95
                      {{ request()->routeIs('packages.*') ? 'text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                @if(request()->routeIs('packages.*'))
                <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-red-600 rounded-b-full"></span>
                @endif
                <div class="w-9 h-9 flex items-center justify-center rounded-2xl {{ request()->routeIs('packages.*') ? 'bg-red-50' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold leading-none">Tours</span>
            </a>

            {{-- Visa --}}
            <a href="{{ route('visas.index') }}"
               class="flex-1 flex flex-col items-center justify-center gap-1 relative transition-all duration-200 active:scale-95
                      {{ request()->routeIs('visas.*') ? 'text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                @if(request()->routeIs('visas.*'))
                <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-red-600 rounded-b-full"></span>
                @endif
                <div class="w-9 h-9 flex items-center justify-center rounded-2xl {{ request()->routeIs('visas.*') ? 'bg-red-50' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold leading-none">Visa</span>
            </a>

            {{-- Hotels --}}
            <a href="{{ route('hotels.index') }}"
               class="flex-1 flex flex-col items-center justify-center gap-1 relative transition-all duration-200 active:scale-95
                      {{ request()->routeIs('hotels.*') ? 'text-red-600' : 'text-gray-400 hover:text-gray-600' }}">
                @if(request()->routeIs('hotels.*'))
                <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-red-600 rounded-b-full"></span>
                @endif
                <div class="w-9 h-9 flex items-center justify-center rounded-2xl {{ request()->routeIs('hotels.*') ? 'bg-red-50' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="text-[10px] font-bold leading-none">Hotels</span>
            </a>

            {{-- More (account/profile) --}}
            <div class="flex-1 flex flex-col items-center justify-center gap-1 relative" x-data="{ moreOpen: false }">
                <button @click="moreOpen = !moreOpen" @keydown.escape.window="moreOpen = false"
                        class="w-full flex flex-col items-center justify-center gap-1 h-full transition-all duration-200 active:scale-95
                               {{ request()->routeIs('dashboard') || request()->routeIs('profile.*') || request()->routeIs('blog.*') || request()->routeIs('about') || request()->routeIs('transfers.*') ? 'text-red-600' : 'text-gray-400' }}">
                    @if(request()->routeIs('dashboard') || request()->routeIs('profile.*') || request()->routeIs('blog.*') || request()->routeIs('about') || request()->routeIs('transfers.*'))
                    <span class="absolute top-0 left-1/2 -translate-x-1/2 w-8 h-0.5 bg-red-600 rounded-b-full"></span>
                    @endif
                    @auth
                    <div class="w-9 h-9 flex items-center justify-center rounded-2xl {{ request()->routeIs('dashboard') || request()->routeIs('profile.*') ? 'bg-red-50' : '' }}">
                        <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold text-white" style="background:#C8102E;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                    </div>
                    <span class="text-[10px] font-bold leading-none">Me</span>
                    @else
                    <div class="w-9 h-9 flex items-center justify-center rounded-2xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </div>
                    <span class="text-[10px] font-bold leading-none">More</span>
                    @endauth
                </button>

                {{-- More drawer --}}
                <div x-show="moreOpen" @click.outside="moreOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 translate-y-4"
                     style="display:none;"
                     class="fixed bottom-[72px] right-0 left-0 bg-white border-t border-gray-100 shadow-2xl rounded-t-3xl px-4 pt-5 pb-6 z-50">
                    <div class="w-10 h-1 bg-gray-200 rounded-full mx-auto mb-5"></div>
                    @auth
                    <div class="flex items-center gap-3 mb-4 p-3 bg-gray-50 rounded-2xl">
                        <span class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-extrabold text-white flex-shrink-0" style="background:#C8102E;">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                    @endauth
                    <div class="grid grid-cols-4 gap-3 mb-4">
                        <a href="{{ route('blog.index') }}" @click="moreOpen=false" class="flex flex-col items-center gap-1.5 p-3 rounded-2xl bg-gray-50 hover:bg-red-50 transition group">
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                            <span class="text-[10px] font-bold text-gray-600 group-hover:text-red-600">Blog</span>
                        </a>
                        <a href="{{ route('transfers.index') }}" @click="moreOpen=false" class="flex flex-col items-center gap-1.5 p-3 rounded-2xl bg-gray-50 hover:bg-red-50 transition group">
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            <span class="text-[10px] font-bold text-gray-600 group-hover:text-red-600">Transfers</span>
                        </a>
                        <a href="{{ route('about') }}" @click="moreOpen=false" class="flex flex-col items-center gap-1.5 p-3 rounded-2xl bg-gray-50 hover:bg-red-50 transition group">
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span class="text-[10px] font-bold text-gray-600 group-hover:text-red-600">About</span>
                        </a>
                        <a href="{{ route('contact') }}" @click="moreOpen=false" class="flex flex-col items-center gap-1.5 p-3 rounded-2xl bg-gray-50 hover:bg-red-50 transition group">
                            <svg class="w-5 h-5 text-gray-500 group-hover:text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-[10px] font-bold text-gray-600 group-hover:text-red-600">Contact</span>
                        </a>
                    </div>
                    @auth
                    <div class="space-y-1">
                        @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" @click="moreOpen=false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-red-50 hover:text-red-600 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            Admin Panel
                        </a>
                        @endif
                        <a href="{{ route('dashboard') }}" @click="moreOpen=false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            My Account
                        </a>
                        <a href="{{ route('profile.edit') }}" @click="moreOpen=false" class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-100 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex w-full items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold text-red-600 hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                    @else
                    <div class="flex gap-2">
                        <a href="{{ route('login') }}" @click="moreOpen=false" class="flex-1 py-3 text-center text-sm font-semibold text-gray-700 border border-gray-200 rounded-xl hover:bg-gray-50 transition">Sign In</a>
                        <a href="{{ route('register') }}" @click="moreOpen=false" class="flex-1 py-3 text-center text-sm font-semibold text-white rounded-xl transition" style="background:#C8102E;">Register</a>
                    </div>
                    @endauth
                </div>
            </div>

        </div>
    </nav>
    </div>
<!-- Cookie Consent -->
    <x-cookie-consent />
    
    <!-- Toast Notifications -->
    <x-toast-notifications />
</body>

</html>