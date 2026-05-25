@php
$banners = \App\Models\Setting::get('banners', []);
$enabled = ($banners['enabled'] ?? '0') === '1';
$type = $banners['type'] ?? 'promo';
$title = $banners['title'] ?? 'Special Offer!';
$message = $banners['message'] ?? '';
$button = $banners['button_text'] ?? 'Book Now';
$link = $banners['link'] ?? '/tours';
$image = $banners['image'] ?? '';
$primaryColor = $banners['primary_color'] ?? 'red';
$delay = (float)($banners['delay'] ?? '1');
$frequency = $banners['frequency'] ?? 'once';
$pages = $banners['pages'] ?? 'home';

// Color maps
$colorGradients = [
    'red' => 'from-red-600 to-red-700',
    'blue' => 'from-blue-600 to-blue-700',
    'green' => 'from-emerald-600 to-emerald-700',
    'orange' => 'from-amber-500 to-orange-600',
    'purple' => 'from-purple-600 to-purple-700',
    'pink' => 'from-pink-500 to-pink-600',
    'indigo' => 'from-indigo-600 to-indigo-700',
    'teal' => 'from-teal-600 to-teal-700',
    'amber' => 'from-amber-500 to-amber-600',
    'slate' => 'from-gray-800 to-gray-900',
];

$colorButtons = [
    'red' => 'bg-red-600 hover:bg-red-700',
    'blue' => 'bg-blue-600 hover:bg-blue-700',
    'green' => 'bg-emerald-600 hover:bg-emerald-700',
    'orange' => 'bg-amber-500 hover:bg-orange-600',
    'purple' => 'bg-purple-600 hover:bg-purple-700',
    'pink' => 'bg-pink-500 hover:bg-pink-600',
    'indigo' => 'bg-indigo-600 hover:bg-indigo-700',
    'teal' => 'bg-teal-600 hover:bg-teal-700',
    'amber' => 'bg-amber-500 hover:bg-amber-600',
    'slate' => 'bg-white hover:bg-gray-100',
];

$selectedGradient = $colorGradients[$primaryColor] ?? $colorGradients['red'];
$selectedButton = $colorButtons[$primaryColor] ?? $colorButtons['red'];

// Check page restrictions
$currentRoute = request()->route()?->getName();
if ($pages === 'home' && $currentRoute !== 'home') return;
if ($pages === 'tours' && !str_starts_with($currentRoute ?? '', 'tours')) return;

if (!$enabled || !$message) return;

// 5 Banner Types Configuration with customizable colors
$bannerTypes = [
    'promo' => [
        'name' => 'Special Promotion',
        'overlay' => 'bg-black/60',
        'modal' => 'bg-white',
        'header' => 'bg-gradient-to-r ' . $selectedGradient,
        'title' => 'text-white',
        'message' => 'text-gray-600',
        'button' => $selectedButton . ' text-white',
        'close' => 'text-white/80 hover:text-white',
        'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    ],
    'newsletter' => [
        'name' => 'Newsletter Signup',
        'overlay' => 'bg-black/60',
        'modal' => 'bg-white',
        'header' => 'bg-gradient-to-r ' . $selectedGradient,
        'title' => 'text-white',
        'message' => 'text-gray-600',
        'button' => $selectedButton . ' text-white',
        'close' => 'text-white/80 hover:text-white',
        'icon' => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
        'has_email' => true,
    ],
    'offer' => [
        'name' => 'Limited Offer',
        'overlay' => 'bg-black/60',
        'modal' => 'bg-white',
        'header' => 'bg-gradient-to-r ' . $selectedGradient,
        'title' => 'text-white',
        'message' => 'text-gray-600',
        'button' => $selectedButton . ' text-white',
        'close' => 'text-white/80 hover:text-white',
        'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    ],
    'discount' => [
        'name' => 'Big Discount',
        'overlay' => 'bg-black/60',
        'modal' => 'bg-white',
        'header' => 'bg-gradient-to-r ' . $selectedGradient,
        'title' => 'text-white',
        'message' => 'text-gray-600',
        'button' => $selectedButton . ' text-white',
        'close' => 'text-white/80 hover:text-white',
        'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z',
    ],
    'announcement' => [
        'name' => 'Announcement',
        'overlay' => 'bg-black/70',
        'modal' => $primaryColor === 'slate' ? 'bg-gray-900 border border-gray-700' : 'bg-white',
        'header' => 'bg-gradient-to-r ' . $selectedGradient,
        'title' => $primaryColor === 'slate' ? 'text-white' : 'text-white',
        'message' => $primaryColor === 'slate' ? 'text-gray-300' : 'text-gray-600',
        'button' => $primaryColor === 'slate' ? 'bg-white hover:bg-gray-100 text-gray-900' : $selectedButton . ' text-white',
        'close' => $primaryColor === 'slate' ? 'text-gray-400 hover:text-white' : 'text-white/80 hover:text-white',
        'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z',
    ],
];

$style = $bannerTypes[$type] ?? $bannerTypes['promo'];
$hasEmail = $style['has_email'] ?? false;
@endphp

<div x-data="{
    show: false,
    email: '',
    subscribed: false,
    init() {
        // Check frequency setting
        const freq = '{{ $frequency }}';
        const seen = sessionStorage.getItem('banner_seen');
        const dailySeen = localStorage.getItem('banner_seen_date');
        const today = new Date().toDateString();
        
        if (freq === 'once' && seen) return;
        if (freq === 'daily' && dailySeen === today) return;
        
        // Show after delay
        setTimeout(() => { this.show = true; }, {{ $delay * 1000 }});
    },
    close() {
        this.show = false;
        sessionStorage.setItem('banner_seen', '1');
        localStorage.setItem('banner_seen_date', new Date().toDateString());
    },
    goToLink() {
        sessionStorage.setItem('banner_seen', '1');
        localStorage.setItem('banner_seen_date', new Date().toDateString());
        window.location.href = '{{ $link }}';
    },
    subscribe() {
        if (!this.email || !this.email.includes('@')) {
            alert('Please enter a valid email address');
            return;
        }
        // Here you would typically make an AJAX call to subscribe
        this.subscribed = true;
        sessionStorage.setItem('banner_seen', '1');
        setTimeout(() => this.close(), 2000);
    }
}" x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-[9999] {{ $style['overlay'] }} flex items-center justify-center p-4" style="display: none;" @click.self="close()">
    
    <div x-show="show" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="relative w-full max-w-lg {{ $style['modal'] }} rounded-2xl shadow-2xl overflow-hidden" @click.stop>
        
        @if($image)
        <div class="absolute inset-0 z-0">
            <img src="{{ $image }}" alt="" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/50"></div>
        </div>
        @endif

        @if($hasEmail)
        {{-- Newsletter Layout --}}
        <div class="relative z-10">
            <div class="h-32 {{ $style['header'] }} flex items-center justify-center relative">
                <button @click="close()" class="absolute top-3 right-3 {{ $style['close'] }} transition p-1 rounded-lg hover:bg-white/10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <svg class="w-16 h-16 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $style['icon'] }}"/>
                </svg>
            </div>
            <div class="p-6">
                <h3 class="text-xl font-bold text-gray-900 mb-2 text-center">{{ $title }}</h3>
                <p class="text-gray-600 mb-4 text-center">{!! nl2br(e($message)) !!}</p>
                
                <template x-if="!subscribed">
                    <div class="flex gap-2">
                        <input type="email" x-model="email" placeholder="Enter your email" class="flex-1 px-4 py-3 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
                        <button @click="subscribe()" class="px-6 py-3 {{ $style['button'] }} rounded-xl font-semibold whitespace-nowrap">
                            {{ $button }}
                        </button>
                    </div>
                </template>
                <template x-if="subscribed">
                    <div class="text-center py-2">
                        <svg class="w-12 h-12 text-green-500 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        <p class="text-green-600 font-semibold">Thanks for subscribing!</p>
                    </div>
                </template>
                
                <button @click="close()" class="w-full mt-3 text-gray-400 hover:text-gray-600 text-sm font-medium transition">
                    No thanks, maybe later
                </button>
            </div>
        </div>
        @else
        {{-- Standard Layout --}}
        <div class="relative z-10 {{ $style['header'] }} px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $style['icon'] }}"/>
                </svg>
                <h3 class="text-lg font-bold {{ $style['title'] }}">{{ $title }}</h3>
            </div>
            <button @click="close()" class="{{ $style['close'] }} transition p-1 rounded-lg hover:bg-white/10">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="relative z-10 p-6">
            <p class="{{ $style['message'] }} text-base leading-relaxed mb-6">{!! nl2br(e($message)) !!}</p>
            
            <div class="flex gap-3">
                <button @click="goToLink()" class="flex-1 {{ $style['button'] }} font-semibold px-6 py-3 rounded-xl transition transform hover:scale-[1.02] active:scale-[0.98]">
                    {{ $button }}
                </button>
                <button @click="close()" class="px-4 py-3 text-gray-500 hover:text-gray-700 font-medium transition">
                    Maybe later
                </button>
            </div>
        </div>
        @endif

        {{-- Decorative elements for promo type --}}
        @if($type === 'promo')
        <div class="absolute top-0 right-0 -mt-2 -mr-2 w-16 h-16 bg-yellow-400 rounded-full opacity-20 blur-xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -mb-2 -ml-2 w-20 h-20 bg-red-400 rounded-full opacity-20 blur-xl pointer-events-none"></div>
        @endif
    </div>
</div>
