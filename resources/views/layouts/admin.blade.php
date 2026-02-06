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
        <aside class="w-64 bg-slate-800 text-white min-h-screen flex-shrink-0 print:hidden">
            <div class="p-6 border-b border-slate-700">
                <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold tracking-tight text-white">
                    FlyoverBD <span class="text-red-500 text-base">Admin</span>
                </a>
            </div>
            
            <nav class="p-4 space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    Dashboard
                </a>
                <a href="{{ route('admin.packages.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.packages.*') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.packages.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Manage Packages
                </a>
                <a href="{{ route('admin.visas.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.visas.*') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.visas.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                    Manage Visas
                </a>
            <a href="{{ route('admin.bookings.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.bookings.*') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.bookings.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                    Bookings
                </a>
                <a href="{{ route('admin.customizations.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.customizations.*') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.customizations.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Custom Requests
                </a>
                <a href="{{ route('admin.contact-messages.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.contact-messages.*') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.contact-messages.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Messages
                </a>
                <a href="{{ route('admin.blog.index') }}" class="flex items-center px-4 py-3 rounded-lg {{ request()->routeIs('admin.blog.*') ? 'bg-red-600 text-white shadow-lg' : 'text-slate-300 hover:bg-slate-700 hover:text-white' }} transition group">
                    <svg class="w-5 h-5 mr-3 {{ request()->routeIs('admin.blog.*') ? 'text-white' : 'text-slate-400 group-hover:text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
                    Blog System
                </a>
            </nav>

            <div class="p-4 border-t border-slate-700 mt-auto">
                 <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-slate-300 hover:text-white hover:bg-slate-700 rounded-lg transition group">
                        <svg class="w-5 h-5 mr-3 text-slate-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
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

    <!-- Toast Notifications -->
    <div x-data="{ 
            show: false, 
            message: '', 
            type: 'success',
            init() {
                @if(session('success'))
                    this.notify('{{ session('success') }}', 'success');
                @endif
                @if(session('error'))
                    this.notify('{{ session('error') }}', 'error');
                @endif
                @if($errors->any())
                    this.notify('Check the form for errors.', 'error');
                @endif
            },
            notify(message, type) {
                this.message = message;
                this.type = type;
                this.show = true;
                setTimeout(() => this.show = false, 3000);
            }
        }"
        x-show="show" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-5 right-5 z-50 px-6 py-4 rounded-lg shadow-lg text-white"
        :class="type === 'success' ? 'bg-green-600' : 'bg-red-600'"
        style="display: none;"
    >
        <div class="flex items-center">
            <svg x-show="type === 'success'" class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <svg x-show="type === 'error'" class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            <span x-text="message" class="font-medium"></span>
        </div>
    </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('formUploader', () => ({
                progress: 0,
                uploading: false,
                submitForm(event) {
                    const form = event.target;
                    // Sync TinyMCE (and other rich editors) content into form fields before submit
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
                            // If successful redirect or content content, usually we can just reload or follow redirect
                            // But since we want to mimic standard form submission, let's see what the response is.
                            // If it's a redirect, the browser won't automatically follow it with XHR unless we handle it.
                            // However, Laravel returns a redirect response. XHR follows redirects automatically for GET, but 
                            // for POST it might return the content of the redirected page if it follows.
                            // The easiest way to handle the "UI" update is to replace the document content 
                            // with the response if it's HTML, or follow the redirect if it's a JSON redirect.
                            
                            if (xhr.responseURL && xhr.responseURL !== window.location.href) {
                                window.location.href = xhr.responseURL;
                            } else {
                                // If the response is the same page (validation errors), replace the body
                                document.open();
                                document.write(xhr.responseText);
                                document.close();
                            }
                        } else {
                            // Handle errors (like 422 validation error returning HTML)
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
                    
                    // Add X-CSRF-TOKEN
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    if (token) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', token);
                    }
                    
                    // We need to request HTML response to render validation errors correctly if any
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
                    const dm = decimals < 0 ? 0 : decimals;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
                }
            }));
        });
    </script>
    @stack('scripts')
</body>
</html>
