<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? config('app.name', 'FlyoverBD') }}</title>
    <meta name="description" content="{{ $meta_description ?? 'FlyoverBD — tour packages, visa processing, hotel booking & pick-and-drop. Bangladesh\'s home-grown travel agency.' }}">
    <meta name="keywords" content="{{ $meta_keywords ?? 'visa processing, tour packages, hotel booking, pick and drop, travel agency bangladesh, flyoverbd' }}">
    <link rel="canonical" href="{{ url()->current() }}">

    <meta property="og:type"        content="{{ $og_type ?? 'website' }}">
    <meta property="og:url"         content="{{ url()->current() }}">
    <meta property="og:title"       content="{{ $title ?? config('app.name', 'FlyoverBD') }}">
    <meta property="og:description" content="{{ $meta_description ?? 'Tour packages, visa processing, hotel booking &amp; pick-and-drop.' }}">
    <meta property="og:image"       content="{{ $meta_image ?? asset('logo.png') }}">
    <meta property="og:site_name"   content="{{ config('app.name', 'FlyoverBD') }}">

    <meta name="twitter:card"        content="summary_large_image">
    <meta name="twitter:title"       content="{{ $title ?? config('app.name', 'FlyoverBD') }}">
    <meta name="twitter:description" content="{{ $meta_description ?? 'Tour packages, visa, hotels &amp; pick-and-drop.' }}">
    <meta name="twitter:image"       content="{{ $meta_image ?? asset('logo.png') }}">

    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/png">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('meta')
</head>
<body class="antialiased" style="background:#FAF6EE;color:#18130E;font-family:'Geist',system-ui,sans-serif;">

{{-- ── UTILITY BAR ───────────────────────────────────────────────────── --}}
<div style="background:#18130E;color:rgba(255,255,255,0.78);" class="py-2 px-4 lg:px-16 text-xs hidden md:flex justify-between items-center">
    <div class="flex gap-6 items-center">
        <span class="flex items-center gap-1.5">
            <span style="color:#C8102E;">●</span>
            24/7 helpline:
            <a href="tel:+8809678332211" class="font-semibold text-white ml-1">+880 9678 332211</a>
        </span>
        <a href="mailto:support@flyoverbd.net" class="hover:text-white transition-colors">support@flyoverbd.net</a>
    </div>
    <div class="flex gap-5 items-center">
        <span>🇧🇩 Bangladesh — BDT ৳</span>
        <span class="flex items-center gap-1.5">📱 Download the app</span>
        <a href="{{ route('bookings.confirmation', ['booking' => 'track']) ?? '#' }}" class="flex items-center gap-1.5 hover:text-white transition-colors">⚡ Track booking</a>
        @auth
            <a href="{{ route('dashboard') }}" class="font-semibold text-white hover:text-gray-200 transition-colors">My Account</a>
        @else
            <a href="{{ route('login') }}" class="font-semibold text-white hover:text-gray-200 transition-colors">Sign in</a>
            <a href="{{ route('register') }}" class="px-3 py-1 font-semibold text-white rounded-md transition-colors" style="background:#C8102E;">Sign up</a>
        @endauth
    </div>
</div>

{{-- ── MAIN NAV ──────────────────────────────────────────────────────── --}}
<nav style="background:#fff;border-bottom:1px solid #E4DCC9;" class="sticky top-0 z-50"
     x-data="{ open: false }">
    <div class="px-4 lg:px-16">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 shrink-0">
                <svg width="32" height="32" viewBox="0 0 32 32" fill="none">
                    <circle cx="16" cy="16" r="15.2" stroke="#C8102E" stroke-width="1.6"/>
                    <path d="M9 21 L16 8 L23 21 L20 21 L18 17 L14 17 L12 21 Z" fill="#C8102E"/>
                    <path d="M14.6 15.4 L17.4 15.4 L16 12.7 Z" fill="#FAF6EE"/>
                </svg>
                <div class="flex flex-col leading-none">
                    <span class="font-serif italic text-xl" style="color:#18130E;letter-spacing:-0.02em;">Flyover</span>
                    <span class="font-mono text-[9px] tracking-widest mt-0.5" style="color:#C8102E;">BANGLADESH</span>
                </div>
            </a>

            {{-- Desktop nav --}}
            <nav class="hidden lg:flex items-center gap-1">
                @php
                    $navItems = [
                        ['Home',         route('home'),            'home'],
                        ['Tours',        route('packages.index'),  'packages.*'],
                        ['Visa',         route('visas.index'),     'visas.*'],
                        ['Hotels',       route('hotels.index'),    'hotels.*'],
                        ['Pick & Drop',  route('pickdrop.index'),  'pickdrop.*'],
                        ['About',        route('about'),           'about'],
                    ];
                @endphp
                @foreach($navItems as [$label, $href, $route])
                    <a href="{{ $href }}" class="px-3 py-2 rounded-lg text-sm font-semibold transition-all duration-150
                        {{ request()->routeIs($route) ? 'text-[#C8102E] bg-[#FFE9EC]' : 'text-[#3A332B] hover:text-[#18130E] hover:bg-[#FAF6EE]' }}">
                        @if(in_array($label, ['Hotels','Pick & Drop']))
                            {{ $label }}
                            <span class="ml-1 text-[9px] font-bold bg-[#C8102E] text-white px-1 py-0.5 rounded align-middle">NEW</span>
                        @else
                            {{ $label }}
                        @endif
                    </a>
                @endforeach
            </nav>

            {{-- Right actions --}}
            <div class="hidden lg:flex items-center gap-3">
                <span class="flex items-center gap-1.5 text-sm text-[#3A332B]">
                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" stroke="currentColor" stroke-width="1.4"><path d="M11.5 12.3l-1.3-1.3a4.8 4.8 0 1 0-1.2 1.2l1.3 1.3z"/></svg>
                    Search
                </span>
                <a href="{{ route('contact') }}" class="ota-btn-primary text-sm px-4 py-2">Book a Call →</a>
            </div>

            {{-- Mobile hamburger --}}
            <button @click="open=!open" class="lg:hidden p-2 rounded-md text-[#3A332B]">
                <svg x-show="!open" width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M3 5h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2zm0 4h14a1 1 0 010 2H3a1 1 0 010-2z" clip-rule="evenodd"/></svg>
                <svg x-show="open" x-cloak width="20" height="20" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile menu --}}
    <div x-show="open" x-cloak x-transition style="background:#fff;border-top:1px solid #E4DCC9;" class="lg:hidden px-4 py-3">
        @foreach($navItems as [$label, $href, $route])
            <a href="{{ $href }}" class="block px-3 py-2.5 rounded-lg text-sm font-semibold mb-1
                {{ request()->routeIs($route) ? 'text-[#C8102E] bg-[#FFE9EC]' : 'text-[#3A332B]' }}">
                {{ $label }}
                @if(in_array($label, ['Hotels','Pick & Drop']))
                    <span class="ml-1 text-[9px] font-bold bg-[#C8102E] text-white px-1 py-0.5 rounded">NEW</span>
                @endif
            </a>
        @endforeach
        <div class="pt-3 border-t border-[#E4DCC9] mt-2 flex gap-3">
            @auth
                <a href="{{ route('dashboard') }}" class="ota-btn-ghost flex-1 text-center text-sm py-2">My Account</a>
            @else
                <a href="{{ route('login') }}" class="ota-btn-ghost flex-1 text-center text-sm py-2">Sign in</a>
                <a href="{{ route('register') }}" class="ota-btn-primary flex-1 text-center text-sm py-2">Sign up</a>
            @endauth
        </div>
    </div>
</nav>

{{-- ── PAGE CONTENT ──────────────────────────────────────────────────── --}}
<main>
    {{ $slot }}
</main>

{{-- ── FOOTER ────────────────────────────────────────────────────────── --}}
<footer style="background:#18130E;color:#FAF6EE;" class="px-4 lg:px-16 pt-16 pb-6">

    {{-- App + newsletter row --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-12 pb-12 border-b border-white/10">
        <div>
            <p class="text-xs font-bold tracking-widest" style="color:#C8102E;">📱 GET THE APP</p>
            <h3 class="text-2xl font-bold mt-3 text-white">Book on the go.</h3>
            <p class="text-sm mt-2 leading-relaxed" style="color:rgba(255,255,255,0.65);max-width:340px;">Live prices, instant booking, paperless tickets. First booking gets ৳ 500 off.</p>
            <div class="flex gap-3 mt-5">
                <a href="#" class="flex items-center gap-2.5 bg-white text-[#18130E] px-4 py-2.5 rounded-lg text-sm font-semibold">
                    <span class="text-xl"></span>
                    <div><div class="text-[10px] text-[#7A7166]">Download on the</div><div class="font-semibold">App Store</div></div>
                </a>
                <a href="#" class="flex items-center gap-2.5 bg-white text-[#18130E] px-4 py-2.5 rounded-lg text-sm font-semibold">
                    <span class="text-xl"></span>
                    <div><div class="text-[10px] text-[#7A7166]">Get it on</div><div class="font-semibold">Google Play</div></div>
                </a>
            </div>
        </div>
        <div>
            <p class="text-xs font-bold tracking-widest" style="color:#C8102E;">📨 DEALS IN YOUR INBOX</p>
            <h3 class="text-2xl font-bold mt-3 text-white">Subscribe &amp; save 10%.</h3>
            <p class="text-sm mt-2 leading-relaxed" style="color:rgba(255,255,255,0.65);">Flash deals, festival offers, and the first peek at new destinations.</p>
            <div class="flex items-center mt-5 rounded-xl overflow-hidden border border-white/10" style="background:rgba(255,255,255,0.08);">
                <input type="email" placeholder="your@email.com" class="flex-1 bg-transparent border-none outline-none text-sm px-4 py-3 text-white placeholder-white/40">
                <button class="ota-btn-primary m-1 text-sm px-5 py-2 rounded-lg">Subscribe</button>
            </div>
            <p class="text-xs mt-3" style="color:rgba(255,255,255,0.4);">By subscribing you agree to our privacy policy. Unsubscribe anytime.</p>
        </div>
    </div>

    {{-- Link columns --}}
    <div class="grid grid-cols-2 md:grid-cols-5 gap-8 py-12">
        {{-- Brand --}}
        <div class="col-span-2 md:col-span-1">
            <a href="{{ route('home') }}" class="flex items-center gap-2.5">
                <svg width="28" height="28" viewBox="0 0 32 32" fill="none"><circle cx="16" cy="16" r="15.2" stroke="#C8102E" stroke-width="1.6"/><path d="M9 21 L16 8 L23 21 L20 21 L18 17 L14 17 L12 21 Z" fill="#C8102E"/><path d="M14.6 15.4 L17.4 15.4 L16 12.7 Z" fill="#18130E"/></svg>
                <div class="flex flex-col leading-none">
                    <span class="font-serif italic text-lg text-white" style="letter-spacing:-0.02em;">Flyover</span>
                    <span class="font-mono text-[9px] tracking-widest mt-0.5" style="color:#C8102E;">BANGLADESH</span>
                </div>
            </a>
            <p class="text-sm mt-4 leading-relaxed" style="color:rgba(255,255,255,0.62);max-width:240px;">Bangladesh's home-grown travel agency for international and domestic journeys.</p>
            <div class="flex gap-2.5 mt-5">
                @foreach(['Fb','Ig','Yt','Tw','In'] as $s)
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-semibold" style="background:rgba(255,255,255,0.08);">{{ $s }}</div>
                @endforeach
            </div>
        </div>

        @foreach([
            ['Services',      [['Flights','#'],['Hotels',route('hotels.index')],['Tour Packages',route('packages.index')],['Visa Processing',route('visas.index')],['Pick &amp; Drop',route('pickdrop.index')]]],
            ['Destinations',  [['Nepal','#'],['Bhutan','#'],['Maldives','#'],['Dubai','#'],['Thailand','#'],['Kashmir','#']]],
            ['Support',       [['Help center','#'],['Track booking','#'],['Cancellation','#'],['Refund policy','#'],['Contact us',route('contact')]]],
            ['Company',       [['About',route('about')],['Careers','#'],['Press','#'],['Privacy',route('privacy')],['Terms','#']]],
        ] as [$col, $links])
            <div>
                <p class="text-sm font-bold text-white mb-4">{{ $col }}</p>
                <ul class="space-y-3">
                    @foreach($links as [$lbl, $href])
                        <li><a href="{{ $href }}" class="text-sm transition-colors" style="color:rgba(255,255,255,0.65);" onmouseover="this.style.color='#fff'" onmouseout="this.style.color='rgba(255,255,255,0.65)'">{!! $lbl !!}</a></li>
                    @endforeach
                </ul>
            </div>
        @endforeach
    </div>

    {{-- Payment + certs --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 py-6 border-t border-b border-white/10">
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-bold tracking-widest" style="color:rgba(255,255,255,0.55);">PAY WITH</span>
            @foreach(['bKash','Nagad','Rocket','VISA','Mastercard','AMEX','EMI'] as $p)
                <span class="px-2.5 py-1 text-xs font-semibold text-white rounded-md" style="background:rgba(255,255,255,0.08);">{{ $p }}</span>
            @endforeach
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <span class="text-xs font-bold tracking-widest" style="color:rgba(255,255,255,0.55);">CERTIFIED</span>
            @foreach(['ATAB · 1842','IATA · 33-0 4567','TOAB Member','PCI-DSS'] as $c)
                <span class="text-xs" style="color:rgba(255,255,255,0.78);">{{ $c }}</span>
            @endforeach
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-2 pt-6 text-xs" style="color:rgba(255,255,255,0.45);">
        <div>© {{ date('Y') }} Flyover Bangladesh Ltd. · House 17/A, Road 4, Dhanmondi, Dhaka 1205</div>
        <div class="flex gap-5">
            <a href="{{ route('privacy') }}" class="hover:text-white transition-colors">Privacy</a>
            <a href="#" class="hover:text-white transition-colors">Terms</a>
            <a href="#" class="hover:text-white transition-colors">Cookies</a>
        </div>
    </div>
</footer>

{{-- WhatsApp float --}}
<a href="https://wa.me/8801335111370" target="_blank" rel="noopener"
   class="fixed bottom-6 right-6 z-50 flex items-center justify-center w-14 h-14 rounded-full shadow-lg transition-transform duration-300 hover:scale-110 group"
   style="background:#25D366;">
    <svg class="w-7 h-7 text-white fill-current" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
    <span class="absolute right-full mr-3 bg-gray-800 text-white text-xs px-2 py-1 rounded whitespace-nowrap opacity-0 group-hover:opacity-100 transition-opacity duration-300">Chat with us</span>
</a>

@stack('scripts')
</body>
</html>
