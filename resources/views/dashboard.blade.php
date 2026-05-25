<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Dashboard — FlyoverBD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="font-sans antialiased" style="background:#F3F4F8;">

<div x-data="{ sidebarOpen: false, notificationOpen: false, userMenuOpen: false }" class="min-h-screen flex">

    {{-- Mobile Overlay --}}
    <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 z-20 bg-black/50 lg:hidden" style="display:none;"></div>

    {{-- Sidebar --}}
    <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           class="fixed inset-y-0 left-0 z-30 w-64 h-screen flex flex-col transition-transform duration-300 ease-in-out lg:translate-x-0 print:hidden"
           style="background:linear-gradient(180deg,#1a0a0a 0%,#18130E 60%,#0f0a07 100%);">

        {{-- Logo --}}
        <div class="flex items-center justify-between h-16 px-5 flex-shrink-0" style="border-bottom:1px solid rgba(255,255,255,0.07);">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <img src="{{ asset('logo.png') }}" alt="FlyoverBD" class="h-8 w-auto">
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden p-1 rounded text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- User Mini Profile --}}
        <div class="px-4 py-4 flex-shrink-0 mx-3 my-3 rounded-xl" style="background:rgba(255,255,255,0.05);">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover ring-2 ring-red-500/50">
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-400 rounded-full border-2 border-gray-900"></span>
                </div>
                <div class="min-w-0">
                    <p class="text-white font-semibold text-sm truncate">{{ $user->name }}</p>
                    <p class="text-gray-400 text-xs truncate">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto py-2 px-3 space-y-0.5">
            @php
                $navItems = [
                    ['label' => 'Dashboard', 'route' => 'dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['label' => 'My Bookings', 'route' => 'dashboard', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                    ['label' => 'Browse Tours', 'route' => 'packages.index', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
                    ['label' => 'Apply Visa', 'route' => 'visas.index', 'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0'],
                    ['label' => 'Book Hotels', 'route' => 'hotels.index', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-3h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                    ['label' => 'Pick & Drop', 'route' => 'transfers.index', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                    ['label' => 'Custom Plan', 'route' => 'customize.index', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                ];
            @endphp

            @foreach($navItems as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all {{ $isActive ? 'text-white shadow-sm' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                   @if($isActive) style="background:linear-gradient(135deg,#C8102E,#a00d24);" @endif>
                    <svg class="w-4.5 h-4.5 flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="pt-4 mt-3 border-t border-white/10">
                <p class="px-3 text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Account</p>
                <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:text-white hover:bg-white/5 transition-all">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profile Settings
                </a>
                <form method="POST" action="{{ route('logout') }}" class="block">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-400 hover:text-red-400 hover:bg-red-500/5 transition-all">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    {{-- Main Content --}}
    <div class="flex-1 flex flex-col lg:ml-64 min-h-screen">

        {{-- Header --}}
        <header class="h-16 bg-white/80 backdrop-blur border-b border-gray-200/70 flex items-center justify-between px-4 sm:px-6 lg:px-8 sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = true" class="lg:hidden p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div>
                    <h1 class="text-base font-bold text-gray-900 leading-tight">My Dashboard</h1>
                    <p class="text-xs text-gray-400 hidden sm:block">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                {{-- Notifications --}}
                <div class="relative">
                    <button @click="notificationOpen = !notificationOpen; userMenuOpen = false" class="relative p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                        @if($unreadCount > 0)
                        <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                        @endif
                    </button>
                    <div x-show="notificationOpen" @click.away="notificationOpen = false"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-gray-100 z-50"
                         style="display:none;">
                        <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 text-sm">Notifications</h3>
                            @if($unreadCount > 0)
                            <form method="POST" action="{{ route('user.notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="text-xs text-red-600 font-semibold hover:text-red-700">Mark all read</button>
                            </form>
                            @endif
                        </div>
                        <div class="max-h-72 overflow-y-auto">
                            @if($notifications->isEmpty())
                            <div class="p-6 text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5"/></svg>
                                <p class="text-sm text-gray-400">No notifications yet</p>
                            </div>
                            @else
                            @foreach($notifications as $notification)
                            <div class="p-3 border-b border-gray-50 hover:bg-gray-50 transition {{ $notification->read_at ? 'opacity-60' : '' }}">
                                <div class="flex gap-2">
                                    <div class="w-1.5 h-1.5 rounded-full bg-red-500 mt-2 flex-shrink-0 {{ $notification->read_at ? 'opacity-0' : '' }}"></div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $notification->data['title'] ?? 'Notification' }}</p>
                                        <p class="text-xs text-gray-500">{{ $notification->data['message'] ?? '' }}</p>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $notification->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            @endif
                        </div>
                    </div>
                </div>

                {{-- User Menu --}}
                <div class="relative">
                    <button @click="userMenuOpen = !userMenuOpen; notificationOpen = false" class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-gray-100 transition">
                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-7 h-7 rounded-full object-cover ring-2 ring-gray-200">
                        <span class="hidden sm:block text-sm font-semibold text-gray-700">{{ Str::limit($user->name, 14) }}</span>
                        <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="userMenuOpen" @click.away="userMenuOpen = false"
                         class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                         style="display:none;">
                        <div class="p-3 bg-gradient-to-r from-red-50 to-orange-50 border-b border-gray-100">
                            <p class="font-bold text-gray-900 text-sm">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $user->email }}</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile Settings
                        </a>
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Dashboard
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="border-t border-gray-100">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 space-y-6">

            {{-- Hero Welcome Banner --}}
            <div class="relative rounded-2xl overflow-hidden" style="background:linear-gradient(135deg,#C8102E 0%,#8B0000 50%,#18130E 100%);">
                <div class="absolute inset-0 opacity-10" style="background-image:url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'1\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
                <div class="relative px-6 py-7 sm:px-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="relative flex-shrink-0">
                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-2xl object-cover ring-4 ring-white/20 shadow-xl">
                        </div>
                        <div>
                            <p class="text-white/70 text-sm font-medium">Welcome back 👋</p>
                            <h2 class="text-2xl font-extrabold text-white leading-tight">{{ $user->name }}</h2>
                            <p class="text-white/60 text-xs mt-0.5">{{ $user->email }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        @if($stats['pending_bookings'] > 0)
                        <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2.5 text-center border border-white/10">
                            <p class="text-2xl font-extrabold text-white">{{ $stats['pending_bookings'] }}</p>
                            <p class="text-white/60 text-xs">Pending</p>
                        </div>
                        @endif
                        <div class="bg-white/10 backdrop-blur rounded-xl px-4 py-2.5 text-center border border-white/10">
                            <p class="text-2xl font-extrabold text-white">{{ $stats['total_tour_bookings'] + $stats['total_hotel_bookings'] + $stats['total_transfer_bookings'] }}</p>
                            <p class="text-white/60 text-xs">Total Trips</p>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="bg-white text-red-700 font-semibold text-sm px-4 py-2.5 rounded-xl hover:bg-red-50 transition shadow-sm hidden sm:block">Edit Profile</a>
                    </div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach([
                    ['Tour Bookings', $stats['total_tour_bookings'], 'packages.index', '#FEF2F2', '#C8102E', 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064', 'Tours Booked'],
                    ['Hotel Bookings', $stats['total_hotel_bookings'], 'hotels.index', '#EFF6FF', '#2563EB', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-3h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'Stays Booked'],
                    ['Pick & Drop', $stats['total_transfer_bookings'], 'transfers.index', '#F0FDF4', '#16A34A', 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'Transfers'],
                    ['Total Spent', '৳' . number_format($stats['total_spent'] ?? 0), '#', '#FFFBEB', '#D97706', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'Amount Paid'],
                ] as [$label, $value, $route, $bg, $color, $icon, $sublabel])
                <a href="{{ $route == '#' ? '#' : route($route) }}" class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 group">
                    <div class="flex items-center justify-between mb-3">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center" style="background:{{ $bg }};">
                            <svg class="w-5 h-5" fill="none" stroke="{{ $color }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-gray-500 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                    <p class="text-2xl font-extrabold text-gray-900">{{ $value }}</p>
                    <p class="text-xs font-medium text-gray-400 mt-0.5">{{ $sublabel }}</p>
                </a>
                @endforeach
            </div>

            {{-- Main Grid --}}
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

                {{-- Left Column --}}
                <div class="xl:col-span-2 space-y-6">

                    {{-- Bookings Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ activeTab: 'tour' }">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm">
                                <div class="w-7 h-7 bg-red-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                </div>
                                My Bookings
                            </h3>
                            @if($stats['pending_bookings'] > 0)
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-600 text-xs font-bold rounded-full border border-amber-200">{{ $stats['pending_bookings'] }} pending payment</span>
                            @endif
                        </div>

                        {{-- Tabs --}}
                        <div class="flex border-b border-gray-100 px-2 pt-1 gap-1 bg-gray-50/30">
                            @foreach([
                                ['tour', 'Tours & Visas', $tourBookings->count()],
                                ['hotel', 'Hotels', $hotelBookings->count()],
                                ['transfer', 'Transfers', $transferBookings->count()],
                            ] as [$key, $label, $count])
                            <button @click="activeTab = '{{ $key }}'"
                                    :class="activeTab === '{{ $key }}' ? 'bg-white text-red-600 border-b-2 border-red-500 shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                                    class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2.5 text-xs font-semibold rounded-t-lg transition-all">
                                {{ $label }}
                                @if($count > 0)
                                <span class="px-1.5 py-0.5 bg-red-100 text-red-600 text-[10px] font-bold rounded-md">{{ $count }}</span>
                                @endif
                            </button>
                            @endforeach
                        </div>

                        <div>
                            {{-- Tours --}}
                            <div x-show="activeTab === 'tour'">
                                @if($tourBookings->isEmpty())
                                <div class="p-10 text-center">
                                    <div class="w-14 h-14 bg-red-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-7 h-7 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                                    </div>
                                    <p class="text-gray-500 text-sm mb-4">No tour bookings yet</p>
                                    <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition shadow-sm">Browse Tours</a>
                                </div>
                                @else
                                <div class="divide-y divide-gray-50">
                                @foreach($tourBookings as $booking)
                                <div class="p-4 flex items-center justify-between hover:bg-gray-50/70 transition group">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $booking->payable?->title ?? 'Tour Booking' }}</p>
                                            <p class="text-xs text-gray-400">{{ $booking->booking_date?->format('M d, Y') }} · {{ $booking->quantity }} person(s)</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                            {{ $booking->payment_status === 'paid' ? 'bg-green-50 text-green-600 border border-green-200' : ($booking->payment_status === 'pending' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-gray-100 text-gray-500') }}">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                        <a href="{{ route('bookings.confirmation', $booking) }}" class="text-xs text-red-600 hover:text-red-700 font-semibold bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition">View</a>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                                @endif
                            </div>

                            {{-- Hotels --}}
                            <div x-show="activeTab === 'hotel'" style="display:none;">
                                @if($hotelBookings->isEmpty())
                                <div class="p-10 text-center">
                                    <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-7 h-7 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-3h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <p class="text-gray-500 text-sm mb-4">No hotel bookings yet</p>
                                    <a href="{{ route('hotels.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition shadow-sm">Browse Hotels</a>
                                </div>
                                @else
                                <div class="divide-y divide-gray-50">
                                @foreach($hotelBookings as $booking)
                                <div class="p-4 flex items-center justify-between hover:bg-gray-50/70 transition">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-3h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $booking->hotel?->name ?? 'Hotel' }}</p>
                                            <p class="text-xs text-gray-400">{{ $booking->check_in?->format('M d') }} – {{ $booking->check_out?->format('M d, Y') }} · {{ $booking->guests }} guest(s)</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                            {{ $booking->payment_status === 'paid' ? 'bg-green-50 text-green-600 border border-green-200' : ($booking->payment_status === 'pending' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-gray-100 text-gray-500') }}">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                        <a href="{{ route('hotel-bookings.show', $booking) }}" class="text-xs text-red-600 hover:text-red-700 font-semibold bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition">View</a>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                                @endif
                            </div>

                            {{-- Transfers --}}
                            <div x-show="activeTab === 'transfer'" style="display:none;">
                                @if($transferBookings->isEmpty())
                                <div class="p-10 text-center">
                                    <div class="w-14 h-14 bg-green-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                        <svg class="w-7 h-7 text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                    </div>
                                    <p class="text-gray-500 text-sm mb-4">No transfer bookings yet</p>
                                    <a href="{{ route('transfers.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white text-sm font-semibold rounded-xl hover:bg-red-700 transition shadow-sm">Book Transfer</a>
                                </div>
                                @else
                                <div class="divide-y divide-gray-50">
                                @foreach($transferBookings as $booking)
                                <div class="p-4 flex items-center justify-between hover:bg-gray-50/70 transition">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-semibold text-gray-900 text-sm truncate">{{ $booking->route?->pickup_location ?? 'Pickup' }} → {{ $booking->route?->dropoff_location ?? 'Dropoff' }}</p>
                                            <p class="text-xs text-gray-400">{{ $booking->transfer_date?->format('M d, Y') }} · {{ $booking->passengers }} passenger(s)</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                                        <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                            {{ $booking->payment_status === 'paid' ? 'bg-green-50 text-green-600 border border-green-200' : ($booking->payment_status === 'pending' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-gray-100 text-gray-500') }}">
                                            {{ ucfirst($booking->payment_status) }}
                                        </span>
                                        <a href="{{ route('transfer-bookings.show', $booking) }}" class="text-xs text-red-600 hover:text-red-700 font-semibold bg-red-50 hover:bg-red-100 px-2.5 py-1 rounded-lg transition">View</a>
                                    </div>
                                </div>
                                @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Payment History --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm">
                                <div class="w-7 h-7 bg-emerald-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                                </div>
                                Payment History
                            </h3>
                            <span class="text-xs text-gray-400">Last 5 transactions</span>
                        </div>
                        @if($payments->isEmpty())
                        <div class="p-10 text-center">
                            <div class="w-14 h-14 bg-emerald-50 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                <svg class="w-7 h-7 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <p class="text-gray-500 text-sm">No payment records yet</p>
                        </div>
                        @else
                        <div class="divide-y divide-gray-50">
                            @foreach($payments as $payment)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/70 transition">
                                <div class="flex items-center gap-3 min-w-0">
                                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0
                                        {{ $payment->status === 'completed' ? 'bg-emerald-50' : ($payment->status === 'pending' ? 'bg-amber-50' : 'bg-red-50') }}">
                                        @if($payment->status === 'completed')
                                        <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        @elseif($payment->status === 'pending')
                                        <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        @else
                                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-semibold text-gray-900 text-sm">{{ $payment->transaction_id ?? ('Payment #' . $payment->id) }}</p>
                                        <p class="text-xs text-gray-400">{{ $payment->created_at->format('M d, Y · g:i A') }} · {{ ucfirst($payment->method ?? 'bKash') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 flex-shrink-0 ml-3">
                                    <p class="font-bold text-gray-900 text-sm">৳{{ number_format($payment->amount) }}</p>
                                    <span class="px-2 py-0.5 text-xs font-semibold rounded-full
                                        {{ $payment->status === 'completed' ? 'bg-emerald-50 text-emerald-600 border border-emerald-200' : ($payment->status === 'pending' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-red-50 text-red-500 border border-red-200') }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Custom Requests --}}
                    @if($customizationRequests->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 flex items-center gap-2 text-sm">
                                <div class="w-7 h-7 bg-purple-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </div>
                                Custom Plan Requests
                            </h3>
                        </div>
                        <div class="divide-y divide-gray-50">
                            @foreach($customizationRequests as $request)
                            <div class="px-6 py-4 flex items-center justify-between hover:bg-gray-50/70 transition">
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">Custom Trip #{{ $request->id }}</p>
                                    <p class="text-xs text-gray-400">Submitted {{ $request->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                    {{ $request->status === 'approved' ? 'bg-green-50 text-green-600 border border-green-200' : ($request->status === 'pending' ? 'bg-amber-50 text-amber-600 border border-amber-200' : 'bg-gray-100 text-gray-500') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                </div>

                {{-- Right Column --}}
                <div class="space-y-5">

                    {{-- Profile Card --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="h-16 w-full" style="background:linear-gradient(135deg,#C8102E,#8B0000);"></div>
                        <div class="px-5 pb-5">
                            <div class="flex items-end gap-3 -mt-7 mb-4">
                                <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-14 h-14 rounded-2xl object-cover ring-4 ring-white shadow">
                                <div class="pb-1">
                                    <h3 class="font-bold text-gray-900 text-sm leading-tight">{{ $user->name }}</h3>
                                    @if($user->phone)
                                    <p class="text-xs text-gray-400">{{ $user->phone }}</p>
                                    @endif
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mb-4 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                {{ $user->email }}
                            </p>
                            @if($user->bio)
                            <p class="text-xs text-gray-500 mb-4 italic border-l-2 border-red-200 pl-3 bg-red-50/30 py-2 rounded-r">"{{ Str::limit($user->bio, 90) }}"</p>
                            @endif
                            <a href="{{ route('profile.edit') }}" class="block w-full bg-gray-900 hover:bg-black text-white text-center font-semibold py-2.5 rounded-xl transition text-sm">Edit Profile</a>
                        </div>
                    </div>

                    {{-- Quick Actions --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
                        <h3 class="font-bold text-gray-900 mb-3 text-sm">Explore Services</h3>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach([
                                ['packages.index', 'Book Tour', '#FEF2F2', '#C8102E', 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
                                ['visas.index', 'Apply Visa', '#EFF6FF', '#2563EB', 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0'],
                                ['hotels.index', 'Find Hotel', '#F0FDF4', '#16A34A', 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-3h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                                ['transfers.index', 'Pick & Drop', '#FFF7ED', '#EA580C', 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                            ] as [$routeName, $label, $bg, $color, $icon])
                            <a href="{{ route($routeName) }}" class="flex flex-col items-center gap-2 p-3 rounded-xl hover:-translate-y-0.5 hover:shadow-md transition-all duration-200 text-center" style="background:{{ $bg }};">
                                <svg class="w-6 h-6" fill="none" stroke="{{ $color }}" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                                <span class="text-xs font-semibold text-gray-700">{{ $label }}</span>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    {{-- Need Help --}}
                    <div class="relative rounded-2xl overflow-hidden p-5 text-white" style="background:linear-gradient(135deg,#C8102E,#8B0000);">
                        <div class="absolute top-0 right-0 w-20 h-20 opacity-20" style="background:radial-gradient(circle,white,transparent);border-radius:50%;transform:translate(20%,-20%);"></div>
                        <svg class="w-8 h-8 text-white/40 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        <h3 class="font-bold mb-1 text-sm">Need Help?</h3>
                        <p class="text-white/70 text-xs mb-4 leading-relaxed">Our support team is ready to assist you with any booking questions.</p>
                        <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-red-700 px-4 py-2 rounded-xl font-semibold text-xs hover:bg-red-50 transition shadow">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            Contact Support
                        </a>
                    </div>

                </div>
            </div>

        </main>
    </div>
</div>

</body>
</html>
