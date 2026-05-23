<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Dashboard' }} — FlyoverBD Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="antialiased" style="background:#F4F5F7;color:#0F1419;font-family:'Geist',system-ui,sans-serif;">

<div class="flex min-h-screen">

    {{-- ── SIDEBAR ─────────────────────────────────────────────────── --}}
    <aside class="w-60 flex flex-col shrink-0" style="background:#fff;border-right:1px solid #E6E8EC;">

        {{-- Brand --}}
        <div class="flex items-center justify-between px-5 py-4" style="border-bottom:1px solid #E6E8EC;">
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <svg width="26" height="26" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="15.2" stroke="#C8102E" stroke-width="1.6"/><path d="M9 21 L16 8 L23 21 L20 21 L18 17 L14 17 L12 21 Z" fill="#C8102E"/><path d="M14.6 15.4 L17.4 15.4 L16 12.7 Z" fill="#FAF6EE"/></svg>
                <div class="flex flex-col leading-none">
                    <span class="font-serif italic text-base" style="color:#18130E;letter-spacing:-0.02em;">Flyover</span>
                    <span class="font-mono text-[8px] tracking-widest mt-0.5" style="color:#C8102E;">BANGLADESH</span>
                </div>
            </a>
            <span class="px-2 py-0.5 text-[9px] font-bold rounded" style="background:#FFE9EC;color:#C8102E;letter-spacing:0.06em;">ADMIN</span>
        </div>

        {{-- Nav --}}
        <nav class="flex-1 px-3 py-3 overflow-y-auto">
            @php
                $groups = [
                    ['Overview', [
                        ['Dashboard',    route('admin.dashboard'),             'admin.dashboard',           '📊', null],
                    ]],
                    ['Catalogue', [
                        ['Packages',     route('admin.packages.index'),        'admin.packages.*',          '🧳', null],
                        ['Visas',        route('admin.visas.index'),           'admin.visas.*',             '📘', null],
                        ['Blog',         route('admin.blog.index'),            'admin.blog.*',              '✍️', null],
                    ]],
                    ['Operations', [
                        ['Bookings',     route('admin.bookings.index'),        'admin.bookings.*',          '🗂',  '142'],
                        ['Custom Req.',  route('admin.customizations.index'),  'admin.customizations.*',   '⚙',  null],
                        ['Messages',     route('admin.contact-messages.index'),'admin.contact-messages.*','✉',  null],
                    ]],
                ];
            @endphp

            @foreach($groups as [$groupName, $items])
                <div class="mb-4">
                    <p class="px-2.5 mb-1 text-[10px] font-bold tracking-widest uppercase" style="color:#9CA3AF;">{{ $groupName }}</p>
                    @foreach($items as [$label, $href, $itemRoute, $emo, $badge])
                        <a href="{{ $href }}" class="flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium mb-0.5 transition-all
                            {{ request()->routeIs($itemRoute) ? 'text-white' : 'text-[#374151] hover:bg-gray-50' }}"
                           style="{{ request()->routeIs($itemRoute) ? 'background:#18130E;' : '' }}">
                            <span class="text-sm w-4">{{ $emo }}</span>
                            <span class="flex-1">{{ $label }}</span>
                            @if($badge)
                                <span class="px-1.5 py-0.5 text-[10px] font-bold rounded"
                                      style="{{ request()->routeIs($itemRoute) ? 'background:rgba(255,255,255,0.15);color:#fff;' : 'background:#F3F4F6;color:#6B7280;' }}">{{ $badge }}</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>

        {{-- User --}}
        <div class="px-3 pb-3 pt-3" style="border-top:1px solid #E6E8EC;">
            <div class="flex items-center gap-2.5 p-2.5 rounded-lg" style="background:#F9FAFB;">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0" style="background:#C8102E;">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-semibold truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px]" style="color:#6B7280;">Super Admin</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" title="Logout" class="text-sm transition-colors" style="color:#9CA3AF;" onmouseover="this.style.color='#C8102E'" onmouseout="this.style.color='#9CA3AF'">
                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M5.5 12H3a1 1 0 01-1-1V3a1 1 0 011-1h2.5M9 9.5l3-2.5-3-2.5M12 7H5.5"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ── MAIN ────────────────────────────────────────────────────── --}}
    <div class="flex-1 flex flex-col min-w-0">

        {{-- Top bar --}}
        <header class="flex items-center justify-between px-7 py-3.5" style="background:#fff;border-bottom:1px solid #E6E8EC;">
            <div>
                <div class="flex items-center gap-1.5 text-xs" style="color:#6B7280;">
                    <span>Flyover</span><span>›</span>
                    <span>{{ $pageTitle ?? 'Dashboard' }}</span>
                </div>
                <div class="flex items-baseline gap-3 mt-0.5">
                    <h1 class="text-2xl font-bold" style="letter-spacing:-0.015em;">{{ $pageTitle ?? 'Dashboard' }}</h1>
                    @isset($pageSubtitle)
                        <span class="text-sm" style="color:#6B7280;">{{ $pageSubtitle }}</span>
                    @endisset
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm w-64" style="background:#F4F5F7;">
                    <span>🔍</span>
                    <span style="color:#9CA3AF;">Search bookings, customers…</span>
                    <span class="ml-auto text-[10px] font-mono px-1.5 py-0.5 rounded border" style="color:#6B7280;background:#fff;border-color:#E6E8EC;">⌘K</span>
                </div>
                <button class="relative p-2 rounded-lg text-lg" style="background:#fff;border:1px solid #E6E8EC;">
                    🔔
                    <span class="absolute top-1 right-1 w-2 h-2 rounded-full" style="background:#C8102E;"></span>
                </button>
                @isset($headerActions){{ $headerActions }}@endisset
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 p-7">
            {{ $slot }}
        </main>
    </div>
</div>

{{-- Toast --}}
<div x-data="{
        show: false, message: '', type: 'success',
        init() {
            @if(session('success')) this.notify('{{ addslashes(session('success')) }}', 'success'); @endif
            @if(session('error'))   this.notify('{{ addslashes(session('error')) }}', 'error'); @endif
        },
        notify(msg, t) { this.message = msg; this.type = t; this.show = true; setTimeout(() => this.show = false, 3500); }
     }"
     x-show="show" x-cloak
     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200" x-transition:leave-end="opacity-0 translate-y-2"
     class="fixed bottom-5 right-5 z-50 px-5 py-3 rounded-xl shadow-xl text-white text-sm font-semibold flex items-center gap-2"
     :class="type === 'success' ? 'bg-green-600' : 'bg-red-600'">
    <span x-show="type==='success'">✓</span>
    <span x-show="type==='error'">✕</span>
    <span x-text="message"></span>
</div>

@stack('scripts')
</body>
</html>
