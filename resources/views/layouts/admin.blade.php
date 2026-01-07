<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-slate-800 text-white min-h-screen flex-shrink-0">
            <div class="p-6 border-b border-slate-700">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold tracking-tight text-white">
                    FlyoverBD <span class="text-red-500 text-base">Admin</span>
                </a>
            </div>
            
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">
                    Dashboard
                </a>
                <a href="{{ route('admin.packages.index') }}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.packages.*') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">
                    Manage Packages
                </a>
                <a href="{{ route('admin.visas.index') }}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.visas.*') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">
                    Manage Visas
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="block px-4 py-3 rounded-lg {{ request()->routeIs('admin.bookings.*') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition">
                    Bookings
                </a>
            </nav>

            <div class="p-4 border-t border-slate-700 mt-auto">
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-8">
            {{ $slot }}
        </main>
    </div>
</body>
</html>
