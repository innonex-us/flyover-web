<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? config('app.name', 'FlyoverBD') }}</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" style="background:#F9F6EF;">
        <div class="min-h-screen flex flex-col">

            {{-- Top bar --}}
            <div class="py-6 text-center border-b border-[#E4DCC9]" style="background:#F9F6EF;">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2">
                    <span class="font-extrabold text-2xl tracking-tight" style="color:#C8102E;font-family:'Merriweather',Georgia,serif;">FlyoverBD</span>
                </a>
            </div>

            {{-- Card --}}
            <div class="flex-1 flex items-start justify-center px-4 pt-10 pb-16">
                <div class="w-full max-w-md">
                    {{ $slot }}
                </div>
            </div>

            {{-- Footer strip --}}
            <div class="py-4 text-center text-xs text-gray-400 border-t border-[#E4DCC9]">
                <a href="{{ route('privacy') }}" class="hover:text-red-600 transition">Privacy Policy</a>
                <span class="mx-2">·</span>
                <a href="{{ route('contact') }}" class="hover:text-red-600 transition">Contact</a>
                <span class="mx-2">·</span>
                <span>&copy; {{ date('Y') }} FlyoverBD</span>
            </div>
        </div>
    </body>
</html>
