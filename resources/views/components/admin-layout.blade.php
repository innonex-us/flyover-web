<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Admin Panel' }} - FlyoverBD</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">

<div
    x-data="{
        sidebarOpen: false,
        userOpen: false
    }"
    class="min-h-screen flex"
>

    {{-- ── Sidebar ── --}}
    <aside x-show="sidebarOpen" x-cloak
           class="fixed inset-y-0 left-0 z-50 w-64 bg-gradient-to-b from-red-900 to-red-800 text-white lg:static lg:inset-0 lg:flex lg:flex-col"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
           x-transition:enter="transition ease-in-out duration-300"
           x-transition:enter-start="-translate-x-full"
           x-transition:enter-end="translate-x-0"
           x-transition:leave="transition ease-in-out duration-300"
           x-transition:leave-start="translate-x-0"
           x-transition:leave-end="-translate-x-full"
    >
        {{-- Logo --}}
        <div class="flex items-center gap-3 px-6 py-5 border-b border-white/10">
            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                <span class="text-red-600 font-bold text-xl">F</span>
            </div>
            <div>
                <h1 class="font-bold text-lg">FlyoverBD</h1>
                <p class="text-xs text-red-200">Admin Panel</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-6 overflow-y-auto">

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
               @if(request()->routeIs('admin.dashboard')) style="background:#C8102E;" @endif>
                <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span class="flex-1 text-left">Dashboard</span>
            </a>

            {{-- Analytics --}}
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
               @if(request()->routeIs('admin.dashboard')) style="background:#C8102E;" @endif>
                <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span class="flex-1 text-left">Analytics</span>
            </a>

            {{-- Payments accordion --}}
            <div x-data="{ open: {{ request()->routeIs('admin.payments.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.payments.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if(request()->routeIs('admin.payments.*')) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.payments.*') ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="flex-1 text-left">Payments</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    <a href="{{ route('admin.payments.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.payments.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.payments.*')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.payments.*') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        All Payments
                    </a>
                    <a href="{{ route('admin.payments.refunds') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.payments.refunds') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.payments.refunds')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.payments.refunds') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Refunds
                    </a>
                </div>
            </div>

            {{-- Services accordion --}}
            <div x-data="{ open: {{ request()->routeIs('admin.packages.*') || request()->routeIs('admin.visas.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.packages.*') || request()->routeIs('admin.visas.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if(request()->routeIs('admin.packages.*') || request()->routeIs('admin.visas.*')) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.packages.*') || request()->routeIs('admin.visas.*') ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="flex-1 text-left">Services</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    <a href="{{ route('admin.packages.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.packages.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.packages.*')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.packages.*') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tour Packages
                    </a>
                    <a href="{{ route('admin.visas.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.visas.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.visas.*')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.visas.*') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        Visa Services
                    </a>
                </div>
            </div>

            {{-- Bookings accordion --}}
            <div x-data="{ open: {{ request()->routeIs('admin.bookings.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.bookings.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if(request()->routeIs('admin.bookings.*')) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.bookings.*') ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="flex-1 text-left">Bookings</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    <a href="{{ route('admin.bookings.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.bookings.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.bookings.*')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.bookings.*') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        All Bookings
                    </a>
                </div>
            </div>

            {{-- Content accordion --}}
            <div x-data="{ open: {{ request()->routeIs('admin.blog.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.blog.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if(request()->routeIs('admin.blog.*')) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.blog.*') ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <span class="flex-1 text-left">Content</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    <a href="{{ route('admin.blog.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.blog.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.blog.*')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.blog.*') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Blog Posts
                    </a>
                </div>
            </div>

            {{-- Messages accordion --}}
            <div x-data="{ open: {{ request()->routeIs('admin.messages.*') || request()->routeIs('admin.customization-requests.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.messages.*') || request()->routeIs('admin.customization-requests.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if(request()->routeIs('admin.messages.*') || request()->routeIs('admin.customization-requests.*')) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.messages.*') || request()->routeIs('admin.customization-requests.*') ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="flex-1 text-left">Messages</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    <a href="{{ route('admin.messages.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.messages.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.messages.*')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.messages.*') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        Contact Messages
                    </a>
                    <a href="{{ route('admin.customization-requests.index') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.customization-requests.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.customization-requests.*')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.customization-requests.*') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Customization Requests
                    </a>
                </div>
            </div>

            {{-- Reports accordion --}}
            <div x-data="{ open: {{ request()->routeIs('admin.reports.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.reports.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if(request()->routeIs('admin.reports.*')) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.reports.*') ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v1a3 3 0 003 3h0a3 3 0 003-3v-1m3-10V4a3 3 0 00-3-3h0a3 3 0 00-3 3v3m0 0h6m-6 0h6"/>
                    </svg>
                    <span class="flex-1 text-left">Reports</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    <a href="{{ route('admin.reports.sales') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.reports.sales') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.reports.sales')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.reports.sales') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 8v8m-4-5v5m-4-2v2m-2 4h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Sales Reports
                    </a>
                    <a href="{{ route('admin.reports.visitors') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.reports.visitors') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.reports.visitors')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.reports.visitors') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Visitor Reports
                    </a>
                </div>
            </div>

            {{-- Users --}}
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
               @if(request()->routeIs('admin.users.*')) style="background:#C8102E;" @endif>
                <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-white' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="flex-1 text-left">Users</span>
            </a>

            {{-- Settings accordion --}}
            <div x-data="{ open: {{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.settings.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if(request()->routeIs('admin.settings.*')) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.settings.*') ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="flex-1 text-left">Settings</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    <a href="{{ route('admin.settings.general') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.settings.general') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.settings.general')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.settings.general') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        General Settings
                    </a>
                    <a href="{{ route('admin.settings.email') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.settings.email') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.settings.email')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.settings.email') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Email Settings
                    </a>
                </div>
            </div>

            {{-- System accordion --}}
            <div x-data="{ open: {{ request()->routeIs('admin.system.*') ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ request()->routeIs('admin.system.*') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if(request()->routeIs('admin.system.*')) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ request()->routeIs('admin.system.*') ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span class="flex-1 text-left">System</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    <a href="{{ route('admin.system.logs') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.system.logs') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.system.logs')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.system.logs') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        System Logs
                    </a>
                    <a href="{{ route('admin.system.backup') }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ request()->routeIs('admin.system.backup') ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if(request()->routeIs('admin.system.backup')) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ request()->routeIs('admin.system.backup') ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/>
                        </svg>
                        Backup & Restore
                    </a>
                </div>
            </div>

        </nav>
    </aside>

    {{-- ── Main Content Area ── --}}
    <div class="flex-1 flex flex-col lg:pl-0">

        {{-- Top Header --}}
        <header class="bg-white border-b border-gray-200 lg:border-gray-200">
            <div class="flex items-center justify-between px-4 py-3 sm:px-6 lg:px-8">
                {{-- Mobile menu button --}}
                <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Right side --}}
                <div class="flex items-center gap-4">
                    {{-- Notifications --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-lg">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                            </svg>
                            @php
                                $unreadNotifications = auth()->user()->unreadNotifications()->count();
                            @endphp
                            @if($unreadNotifications > 0)
                            <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </button>
                    </div>

                    {{-- User dropdown --}}
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition">
                            <div class="w-8 h-8 bg-red-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-medium">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            </div>
                            <div class="hidden sm:block text-left">
                                <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                                <div class="text-xs text-gray-500">{{ auth()->user()->role }}</div>
                            </div>
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        {{-- Dropdown menu --}}
                        <div x-show="open" x-cloak
                             @click.away="open = false"
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                            <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 sm:p-8">
            {{ $slot }}
        </main>
    </div>
</div>

@stack('scripts')
<script>
    // Close mobile menu when clicking outside
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('aside');
        const menuButton = document.querySelector('[x-click*="sidebarOpen"]');
        
        if (sidebar && menuButton && !sidebar.contains(event.target) && !menuButton.contains(event.target)) {
            if (window.Alpine && window.Alpine.store) {
                window.Alpine.store('sidebarOpen', false);
            }
        }
    });
</script>
</body>
</html>
