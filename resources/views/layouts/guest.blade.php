<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FlyoverBD') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased min-h-screen flex" style="background:#FAF6EE;">

    {{-- Left brand panel (hidden on mobile) --}}
    <div class="hidden lg:flex flex-col justify-between p-12 w-96 shrink-0" style="background:#18130E;">
        <a href="{{ route('home') }}" class="flex items-center gap-2.5">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="15.2" stroke="#C8102E" stroke-width="1.6"/><path d="M9 21 L16 8 L23 21 L20 21 L18 17 L14 17 L12 21 Z" fill="#C8102E"/><path d="M14.6 15.4 L17.4 15.4 L16 12.7 Z" fill="#FAF6EE"/></svg>
            <div class="flex flex-col leading-none">
                <span class="font-serif italic text-xl text-white" style="letter-spacing:-0.02em;">Flyover</span>
                <span class="font-mono text-[9px] tracking-widest mt-0.5" style="color:#C8102E;">BANGLADESH</span>
            </div>
        </a>

        <div>
            <h2 class="font-serif italic text-4xl leading-tight text-white" style="letter-spacing:-0.02em;">
                The world<br>
                starts <span style="color:#C8102E;">here.</span>
            </h2>
            <p class="text-sm mt-4 leading-relaxed" style="color:rgba(255,255,255,0.6);">
                Tours, visas, hotels, and transfers — built for travellers crossing in and out of Bangladesh.
            </p>
            <div class="flex items-center gap-3 mt-8">
                <div class="flex -space-x-2">
                    @foreach(['2A2520','6B4F40','36473A','574535'] as $c)
                        <div class="w-8 h-8 rounded-full border-2 border-[#18130E]" style="background:#{{ $c }};"></div>
                    @endforeach
                </div>
                <div>
                    <p class="text-xs font-semibold text-white">1.2M+ travellers trust us</p>
                    <p class="text-xs" style="color:rgba(255,255,255,0.5);">★★★★★ 4.8 / 5 rating</p>
                </div>
            </div>
        </div>

        <p class="text-xs" style="color:rgba(255,255,255,0.35);">© {{ date('Y') }} Flyover Bangladesh Ltd.</p>
    </div>

    {{-- Right form panel --}}
    <div class="flex-1 flex items-center justify-center p-6 lg:p-12">
        <div class="w-full max-w-md">
            {{-- Mobile logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 mb-8 lg:hidden">
                <svg width="28" height="28" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="15.2" stroke="#C8102E" stroke-width="1.6"/><path d="M9 21 L16 8 L23 21 L20 21 L18 17 L14 17 L12 21 Z" fill="#C8102E"/><path d="M14.6 15.4 L17.4 15.4 L16 12.7 Z" fill="#FAF6EE"/></svg>
                <span class="font-serif italic text-xl" style="color:#18130E;">Flyover</span>
            </a>
            {{ $slot }}
        </div>
    </div>
</body>
</html>
