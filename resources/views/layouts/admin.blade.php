<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle ?? 'Admin Panel' }} — FlyoverBD</title>
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
                $navItems = [
                    ['label' => 'Dashboard',       'route' => 'admin.dashboard',           'match' => 'admin.dashboard',          'icon' => 'M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z'],
                    ['label' => 'Packages',         'route' => 'admin.packages.index',      'match' => 'admin.packages.*',         'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
                    ['label' => 'Visa Services',    'route' => 'admin.visas.index',         'match' => 'admin.visas.*',            'icon' => 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0'],
                    ['label' => 'Bookings',         'route' => 'admin.bookings.index',      'match' => 'admin.bookings.*',         'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                    ['label' => 'Custom Requests',  'route' => 'admin.customizations.index','match' => 'admin.customizations.*',   'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z'],
                    ['label' => 'Messages',         'route' => 'admin.contact-messages.index','match' => 'admin.contact-messages.*','icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['label' => 'Blog',             'route' => 'admin.blog.index',          'match' => 'admin.blog.*',             'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
                    ['label' => 'Short Links',      'route' => 'admin.short-links.index',   'match' => 'admin.short-links.*',      'icon' => 'M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1'],
                    ['label' => 'Pick & Drop Routes','route' => 'admin.transfer-routes.index','match' => 'admin.transfer-routes.*', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                    ['label' => 'Pick & Drop Bookings','route' => 'admin.transfer-bookings.index','match' => 'admin.transfer-bookings.*','icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                    ['label' => 'Hotels',           'route' => 'admin.hotels.index',        'match' => 'admin.hotels.*',           'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                    ['label' => 'Hotel Bookings',   'route' => 'admin.hotel-bookings.index','match' => 'admin.hotel-bookings.*',   'icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z'],
                ];
            @endphp

            @foreach($navItems as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-semibold transition group
                   {{ $active ? 'text-white' : 'text-gray-400 hover:text-white hover:bg-white/5' }}"
               @if($active) style="background:#C8102E;" @endif>
                <svg class="w-4.5 h-4.5 w-[18px] h-[18px] shrink-0 {{ $active ? 'text-white' : 'text-gray-500 group-hover:text-gray-300' }}"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            {{-- Left: hamburger + page title --}}
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true"
                        class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                {{-- Breadcrumb-style title --}}
                <div class="flex items-center gap-2 text-sm">
                    <a href="{{ route('admin.dashboard') }}" class="text-gray-400 hover:text-gray-600 transition font-medium">Admin</a>
                    @if(isset($pageTitle) && $pageTitle)
                    <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="font-semibold text-gray-800">{{ $pageTitle }}</span>
                    @endif
                </div>
            </div>

            {{-- Right: view site + user --}}
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" target="_blank"
                   class="hidden sm:flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-800 px-3 py-1.5 rounded-lg hover:bg-gray-50 transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    View Site
                </a>

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
            @if($errors->any())
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
                if (typeof window._joditSync === 'function') {
                    window._joditSync();
                }
                if (typeof window._quillSync === 'function') {
                    window._quillSync();
                }
                if (typeof tinymce !== 'undefined') {
                    tinymce.triggerSave();
                }
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
                    if (xhr.status >= 200 && xhr.status < 300) {
                        if (xhr.responseURL && xhr.responseURL !== window.location.href) {
                            window.location.href = xhr.responseURL;
                        } else {
                            document.open();
                            document.write(xhr.responseText);
                            document.close();
                        }
                    } else {
                        document.open();
                        document.write(xhr.responseText);
                        document.close();
                    }
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

@stack('scripts')
</body>
</html>
