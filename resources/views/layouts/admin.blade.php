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

@php
    // Cache notification count for performance - avoid DB hit on every page load
    $unreadCount = cache()->remember('admin_notif_count_' . auth()->id(), 60, function () {
        return auth()->user()->unreadNotifications()->count();
    });
@endphp

<div
    x-data="{
        sidebarOpen: false,
        userOpen: false
    }"
    class="min-h-screen flex"
>

    {{-- ── Sidebar Overlay (mobile) ── --}}
    <div
        x-show="sidebarOpen"
        @click="sidebarOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        style="display:none;"
        class="fixed inset-0 z-20 bg-black/50 lg:hidden"
    ></div>

    {{-- ── Sidebar ── --}}
    <aside
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
        class="fixed inset-y-0 left-0 z-30 w-64 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 print:hidden"
        style="background:#18130E;"
    >
        {{-- Logo --}}
        <div class="flex items-center justify-between h-16 px-5 flex-shrink-0" style="border-bottom:1px solid #2E2720;">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <img src="{{ asset('logo.png') }}" alt="FlyoverBD" class="h-8 w-auto">
                <span class="text-xs font-bold uppercase tracking-widest px-2 py-0.5 rounded-md text-white" style="background:#C8102E;">Admin</span>
            </a>
            <button @click="sidebarOpen = false" class="lg:hidden p-1 rounded text-gray-400 hover:text-white transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-0.5">
            @php
                $servicesActive = request()->routeIs('admin.packages.*')
                    || request()->routeIs('admin.visas.*')
                    || request()->routeIs('admin.transfer-routes.*')
                    || request()->routeIs('admin.hotels.*')
                    || request()->routeIs('admin.customizations.*');

                $bookingsActive = request()->routeIs('admin.bookings.*')
                    || request()->routeIs('admin.transfer-bookings.*')
                    || request()->routeIs('admin.hotel-bookings.*');

                $contentActive = request()->routeIs('admin.blog.*')
                    || request()->routeIs('admin.short-links.*');

                $analyticsActive = request()->routeIs('admin.analytics.*')
                    || request()->routeIs('admin.reports.*')
                    || request()->routeIs('admin.push-notifications.*');

                $managementActive = request()->routeIs('admin.users.*')
                    || request()->routeIs('admin.contact-messages.*');

                $mainItems = [
                    ['label' => 'Dashboard', 'route' => 'admin.dashboard', 'match' => 'admin.dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ];

                $serviceItems = [
                    ['label' => 'Tour Packages',   'route' => 'admin.packages.index',        'match' => 'admin.packages.*',        'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
                    ['label' => 'Visa Services',   'route' => 'admin.visas.index',            'match' => 'admin.visas.*',           'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0'],
                    ['label' => 'Pick & Drop',     'route' => 'admin.transfer-routes.index', 'match' => 'admin.transfer-routes.*', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                    ['label' => 'Hotels',          'route' => 'admin.hotels.index',           'match' => 'admin.hotels.*',          'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
                    ['label' => 'Custom Requests', 'route' => 'admin.customizations.index',  'match' => 'admin.customizations.*',  'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                ];

                $bookingItems = [
                    ['label' => 'Tour & Visa Bookings', 'route' => 'admin.bookings.index',          'match' => 'admin.bookings.*',          'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['label' => 'Transfer Bookings',    'route' => 'admin.transfer-bookings.index', 'match' => 'admin.transfer-bookings.*', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                    ['label' => 'Hotel Bookings',       'route' => 'admin.hotel-bookings.index',    'match' => 'admin.hotel-bookings.*',    'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
                ];

                $contentItems = [
                    ['label' => 'Blog Posts',  'route' => 'admin.blog.index',        'match' => 'admin.blog.*',        'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                    ['label' => 'Short Links', 'route' => 'admin.short-links.index', 'match' => 'admin.short-links.*', 'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                ];

                $analyticsItems = [
                    ['label' => 'Analytics Dashboard', 'route' => 'admin.analytics.index',        'match' => 'admin.analytics.*',        'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ['label' => 'Business Reports',    'route' => 'admin.reports.index',          'match' => 'admin.reports.*',          'icon' => 'M9 17v1a3 3 0 003 3h0a3 3 0 003-3v-1m3-10V4a3 3 0 00-3-3h0a3 3 0 00-3 3v3m0 0h6m-6 0h6'],
                    ['label' => 'Push Notifications',  'route' => 'admin.push-notifications.index', 'match' => 'admin.push-notifications.*', 'icon' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9'],
                ];

                $managementItems = [
                    ['label' => 'Users',    'route' => 'admin.users.index',            'match' => 'admin.users.*',            'icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
                    ['label' => 'Messages', 'route' => 'admin.contact-messages.index', 'match' => 'admin.contact-messages.*', 'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ];

                $systemItems = [
                    ['label' => 'Settings', 'route' => 'admin.settings.general', 'match' => 'admin.settings.*', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                    ['label' => 'System',   'route' => 'admin.system.logs',       'match' => 'admin.system.*',   'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ];
            @endphp

            {{-- Dashboard --}}
            @foreach($mainItems as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ $active ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                   @if($active) style="background:#C8102E;" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ $active ? 'text-white' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <div class="border-t border-gray-700 my-2"></div>

            {{-- Service Management --}}
            <div x-data="{ open: {{ $servicesActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ $servicesActive ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if($servicesActive) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ $servicesActive ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span class="flex-1 text-left">Service Management</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    @foreach($serviceItems as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ $active ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if($active) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ $active ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Bookings Management --}}
            <div x-data="{ open: {{ $bookingsActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ $bookingsActive ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if($bookingsActive) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ $bookingsActive ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    <span class="flex-1 text-left">Bookings Management</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    @foreach($bookingItems as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ $active ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if($active) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ $active ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Content Management --}}
            <div x-data="{ open: {{ $contentActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ $contentActive ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if($contentActive) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ $contentActive ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span class="flex-1 text-left">Content Management</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    @foreach($contentItems as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ $active ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if($active) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ $active ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Analytics & Reports --}}
            <div x-data="{ open: {{ $analyticsActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ $analyticsActive ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if($analyticsActive) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ $analyticsActive ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="flex-1 text-left">Analytics & Reports</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    @foreach($analyticsItems as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ $active ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if($active) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ $active ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- User Management --}}
            <div x-data="{ open: {{ $managementActive ? 'true' : 'false' }} }">
                <button @click="open = !open"
                        class="w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ $managementActive ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                        @if($managementActive) style="background:rgba(200,16,46,0.25);" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ $managementActive ? 'text-red-400' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="flex-1 text-left">User Management</span>
                    <svg class="w-3.5 h-3.5 text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div x-show="open" x-cloak class="mt-0.5 ml-4 pl-3 space-y-0.5" style="border-left:1px solid rgba(255,255,255,0.08);">
                    @foreach($managementItems as $item)
                    @php $active = request()->routeIs($item['match']); @endphp
                    <a href="{{ route($item['route']) }}"
                       class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition group {{ $active ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                       @if($active) style="background:#C8102E;" @endif>
                        <svg class="w-[16px] h-[16px] shrink-0 {{ $active ? 'text-white' : 'text-gray-600 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                        </svg>
                        {{ $item['label'] }}
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="border-t border-gray-700 my-2"></div>

            {{-- System & Settings --}}
            @foreach($systemItems as $item)
                @php $active = request()->routeIs($item['match']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group {{ $active ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
                   @if($active) style="background:#C8102E;" @endif>
                    <svg class="w-[18px] h-[18px] shrink-0 {{ $active ? 'text-white' : 'text-gray-500 group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                    </svg>
                    {{ $item['label'] }}
                </a>
            @endforeach
        </nav>

        {{-- Bottom: Profile + Logout --}}
        <div class="flex-shrink-0 p-3" style="border-top:1px solid #2E2720;">
            <a href="{{ route('admin.profile.edit') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-400 hover:text-white hover:bg-white/5 transition mb-0.5">
                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold text-white flex-shrink-0" style="background:#C8102E;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <span class="truncate flex-1">{{ Auth::user()->name }}</span>
            </a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="flex w-full items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold text-gray-500 hover:text-red-400 hover:bg-white/5 transition">
                    <svg class="w-[18px] h-[18px] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Sign Out
                </button>
            </form>
        </div>
    </aside>

    {{-- ── Main Column ── --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Top Header --}}
        <header class="h-16 bg-white border-b border-gray-100 flex items-center justify-between px-4 sm:px-6 flex-shrink-0 print:hidden">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true"
                        class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div class="flex items-center gap-2 text-sm">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition font-medium">Admin</a>
                    @if(isset($pageTitle) && $pageTitle)
                    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="font-semibold text-gray-800">{{ $pageTitle }}</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank"
                   class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-800 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Site
                </a>

                {{-- Notification Bell (cached count, cached list) --}}
                @php
                    $notifications = cache()->remember('admin_notif_list_' . auth()->id(), 60, function () {
                        return auth()->user()->notifications()->latest()->take(10)->get();
                    });
                @endphp
                <div class="relative" x-data="{ notifOpen: false, notifCount: {{ $unreadCount }} }">
                    <button @click="notifOpen = !notifOpen" @keydown.escape.window="notifOpen = false"
                            class="relative p-2 rounded-lg text-gray-500 hover:text-gray-800 hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <span x-show="notifCount > 0" x-cloak
                              class="absolute top-1 right-1 w-4 h-4 rounded-full text-[10px] font-bold text-white flex items-center justify-center" style="background:#C8102E;">
                            <span x-text="notifCount > 9 ? '9+' : notifCount"></span>
                        </span>
                    </button>

                    <div x-show="notifOpen" @click.outside="notifOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         x-cloak
                         class="absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl border border-gray-100 z-50 origin-top-right overflow-hidden">
                        <div class="px-4 py-3 border-b border-gray-100 flex items-center justify-between">
                            <p class="text-sm font-bold text-gray-900">Notifications</p>
                            @if($unreadCount > 0)
                            <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                                @csrf
                                <button type="submit" class="text-xs font-semibold text-red-600 hover:text-red-700 transition">Mark all read</button>
                            </form>
                            @endif
                        </div>

                        <div class="max-h-96 overflow-y-auto divide-y divide-gray-50">
                            @forelse($notifications as $notification)
                            @php
                                $data  = $notification->data;
                                $color = match($data['color'] ?? 'gray') {
                                    'blue'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600'],
                                    'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                                    'green'  => ['bg' => 'bg-green-100',  'text' => 'text-green-600'],
                                    'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
                                    'red'    => ['bg' => 'bg-red-100',    'text' => 'text-red-600'],
                                    default  => ['bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
                                };
                                $iconPath = match($data['icon'] ?? 'bell') {
                                    'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                                    'car'      => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
                                    'building' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                                    'document' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                                    'mail'     => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                                    default    => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                                };
                            @endphp
                            <div class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 transition {{ $notification->read_at ? 'opacity-60' : '' }}">
                                <div class="w-9 h-9 rounded-xl {{ $color['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <svg class="w-4 h-4 {{ $color['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-gray-900">{{ $data['title'] ?? 'Notification' }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $data['message'] ?? '' }}</p>
                                    <p class="text-[10px] text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                                </div>
                                <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}" class="flex-shrink-0">
                                    @csrf
                                    <button type="submit" title="Mark as read" class="text-gray-300 hover:text-red-500 transition mt-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </form>
                            </div>
                            @empty
                            <div class="px-4 py-8 text-center">
                                <svg class="w-8 h-8 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                <p class="text-xs text-gray-400">No notifications</p>
                            </div>
                            @endforelse
                        </div>

                        <div class="border-t border-gray-100 px-4 py-2.5">
                            <a href="{{ route('admin.notifications.index') }}" class="text-xs font-semibold text-red-600 hover:text-red-700 transition">View all notifications &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="relative" x-data="{ userOpen: false }">
                    <button @click="userOpen = !userOpen" @keydown.escape.window="userOpen = false"
                            class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl border border-gray-200 hover:border-gray-300 hover:bg-gray-50 transition text-sm font-semibold text-gray-700">
                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-extrabold text-white" style="background:#C8102E;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:block max-w-[120px] truncate">{{ Auth::user()->name }}</span>
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
                         class="absolute right-0 mt-2 w-52 bg-white rounded-2xl shadow-lg border border-gray-100 py-2 z-50 origin-top-right">
                        <div class="px-4 py-2 border-b border-gray-100 mb-1">
                            <p class="text-xs font-bold text-gray-900 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-[11px] text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>
                        <a href="{{ route('admin.profile.edit') }}"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                            Profile & Settings
                        </a>
                        <a href="{{ route('home') }}" target="_blank"
                           class="flex items-center gap-2.5 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 hover:text-gray-900 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            View Site
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
            </div>
        </header>

        {{-- Page Content --}}
        <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 sm:p-8">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- ── Toast Notifications ── --}}
<div x-data="{
        show: false,
        message: '',
        type: 'success',
        init() {
            @if(session('success'))
                this.notify('{{ addslashes(session('success')) }}', 'success');
            @endif
            @if(session('error'))
                this.notify('{{ addslashes(session('error')) }}', 'error');
            @endif
            @if(isset($errors) && $errors->any())
                this.notify('Please check the form for errors.', 'error');
            @endif
        },
        notify(message, type) {
            this.message = message;
            this.type = type;
            this.show = true;
            setTimeout(() => this.show = false, 4000);
        }
    }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-2"
    style="display:none;"
    class="fixed bottom-5 right-5 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-xl text-white text-sm font-semibold max-w-sm"
    :class="type === 'success' ? 'bg-green-600' : 'bg-red-600'"
>
    <svg x-show="type === 'success'" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <svg x-show="type === 'error'" style="display:none;" class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    <span x-text="message"></span>
    <button @click="show = false" class="ml-2 opacity-70 hover:opacity-100 transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
    </button>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('formUploader', () => ({
            progress: 0,
            uploading: false,
            submitForm(event) {
                const form = event.target;
                if (typeof window._joditSync === 'function') window._joditSync();
                if (typeof window._quillSync === 'function') window._quillSync();
                if (typeof tinymce !== 'undefined') tinymce.triggerSave();

                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();
                this.uploading = true;
                this.progress = 0;

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        this.progress = Math.round((e.loaded / e.total) * 100);
                    }
                });

                xhr.addEventListener('load', () => {
                    this.uploading = false;
                    window.location.href = xhr.responseURL || window.location.href;
                });

                xhr.addEventListener('error', () => {
                    this.uploading = false;
                    alert('Upload failed. Please try again.');
                });

                xhr.open(form.method, form.action);
                const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                if (token) xhr.setRequestHeader('X-CSRF-TOKEN', token);
                xhr.setRequestHeader('Accept', 'text/html, application/xhtml+xml');
                xhr.send(formData);
            }
        }));

        Alpine.data('fileUploader', () => ({
            fileSize: null,
            fileName: null,
            handleFileChange(event) {
                const file = event.target.files[0];
                if (file) {
                    this.fileName = file.name;
                    this.fileSize = this.formatBytes(file.size);
                } else {
                    this.fileName = null;
                    this.fileSize = null;
                }
            },
            formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(decimals < 0 ? 0 : decimals)) + ' ' + sizes[i];
            }
        }));
    });

</script>

{{-- Browser Push Notification subscription --}}
<script>
(function(){
    if(!('serviceWorker' in navigator) || !('PushManager' in window)) return;

    const VAPID_PUBLIC = '{{ config('services.vapid.public_key') }}';

    if (!VAPID_PUBLIC || VAPID_PUBLIC.trim() === '') return;

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const raw = window.atob(base64);
        return Uint8Array.from([...raw].map(c => c.charCodeAt(0)));
    }

    navigator.serviceWorker.register('/sw.js').then(function(reg) {
        return reg.pushManager.getSubscription().then(function(existing) {
            if (existing) return;

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
                    }).catch(function(err) {
                        console.warn('Push subscription not available:', err.message);
                    });
                });
            }, 3000);
        });
    }).catch(function(err) {
        console.warn('Service worker registration failed:', err.message);
    });
})();
</script>

@stack('scripts')
</body>
</html>