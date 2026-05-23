<x-app-layout>
    @php
        $defaultImage = null;
        $mainImage = $package->thumbnail
            ? (\Illuminate\Support\Str::startsWith($package->thumbnail, 'http') ? $package->thumbnail : Storage::url($package->thumbnail))
            : null;

        $galleryImages = [];
        $pushGallery = function (string $url) use (&$galleryImages) {
            if ($url !== '' && !in_array($url, $galleryImages, true)) {
                $galleryImages[] = $url;
            }
        };
        if ($mainImage) $pushGallery($mainImage);
        if (!empty($package->images) && is_array($package->images)) {
            foreach ($package->images as $img) {
                $resolved = \Illuminate\Support\Str::startsWith($img, 'http') ? $img : Storage::url($img);
                $pushGallery($resolved);
            }
        }

        $hasGallery = count($galleryImages) > 0;

        $gradients = [
            'linear-gradient(135deg,#1a3a5c 0%,#2d6a9f 50%,#5ba3d9 100%)',
            'linear-gradient(135deg,#2A2520 0%,#6B4F3A 50%,#C09B7A 100%)',
            'linear-gradient(135deg,#1a4a2a 0%,#2d8a4a 50%,#5bc47a 100%)',
            'linear-gradient(135deg,#3a1a4a 0%,#7a3a9a 50%,#ba7ac4 100%)',
        ];
    @endphp

    @if($mainImage)
        @push('meta')
            <link rel="preload" href="{{ $mainImage }}" as="image">
        @endpush
    @endif

    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
        <script>
            grecaptcha.ready(function() {
                var form = document.querySelector('form[action="{{ route('bookings.store') }}"]');
                if (!form) return;
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'submit'}).then(function(token) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'g-recaptcha-response';
                        input.value = token;
                        form.appendChild(input);
                        form.submit();
                    });
                });
            });
        </script>
    @endpush

    <div style="background:var(--cream); min-height:100vh;" x-data="{
        openInquiryModal: false,
        activeSection: 'overview',
        activeGallery: 0,
        inquiryForm: {
            destinations: [{ country: '', nights: '', cities: [{ name: '', nights: '' }] }],
            adults: 1, children: 0, infants: 0,
            hotel_type: '', travel_date: '', message: '', name: '', email: '', phone: ''
        },
        addDestination() { this.inquiryForm.destinations.push({ country: '', nights: '', cities: [{ name: '', nights: '' }] }); },
        removeDestination(i) { this.inquiryForm.destinations.splice(i, 1); },
        addCity(d) { this.inquiryForm.destinations[d].cities.push({ name: '', nights: '' }); },
        removeCity(d, c) { this.inquiryForm.destinations[d].cities.splice(c, 1); }
    }">

        {{-- ── Breadcrumb ────────────────────────────────────────────── --}}
        <div style="background:#fff; border-bottom:1px solid var(--rule);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <nav class="flex items-center gap-2 text-xs font-medium" style="color:var(--mute);" aria-label="Breadcrumb">
                    <a href="{{ route('packages.index') }}" class="fb-mono hover:text-red-600 transition-colors">TOURS</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="fb-mono" style="color:var(--mute);">{{ strtoupper($package->location ?? $package->destination ?? 'Tour') }}</span>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span class="truncate max-w-xs" style="color:var(--ink);">{{ $package->title }}</span>
                </nav>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            @if(session('success'))
                <div class="mb-6 flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-medium"
                    style="background:#E8F5EC; border:1px solid #B8E0C4; color:#1F6E3D;">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            @endif

            {{-- ── Title + Meta ──────────────────────────────────────── --}}
            <div class="mb-6">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    <span class="ota-tag-soft">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ $package->location ?? 'International' }}
                    </span>
                    @if($package->duration_days)
                        <span class="ota-tag-soft">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            {{ $package->duration_days }}D / {{ max(1,$package->duration_days-1) }}N
                        </span>
                    @endif
                    @if($package->group_size)
                        <span class="ota-tag-soft">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $package->group_size }}
                        </span>
                    @endif
                    <span class="rating-badge">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        4.8 · 96 reviews
                    </span>
                </div>
                <h1 class="fb-serif text-3xl md:text-4xl lg:text-5xl" style="color:var(--ink); line-height:1.05;">
                    <em>{{ $package->title }}</em>
                </h1>
            </div>

            {{-- ── Gallery: 1 big + 4 small ──────────────────────────── --}}
            <div class="mb-8 grid gap-2 rounded-2xl overflow-hidden" style="grid-template-columns: 1fr 1fr; grid-template-rows: 200px 200px;">
                {{-- Main big slot --}}
                <div class="row-span-2 relative overflow-hidden">
                    @if($hasGallery && isset($galleryImages[0]))
                        <img src="{{ $galleryImages[0] }}" alt="{{ $package->title }}"
                            class="w-full h-full object-cover"
                            id="gallery-main"
                            fetchpriority="high" decoding="async">
                    @else
                        <div class="w-full h-full img-placeholder" style="background:{{ $gradients[0] }};"></div>
                    @endif
                    {{-- Photo count badge --}}
                    @if($hasGallery && count($galleryImages) > 1)
                        <button onclick="document.getElementById('gallery-modal').classList.remove('hidden')"
                            class="absolute bottom-4 right-4 flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold"
                            style="background:rgba(24,19,14,0.7); color:#fff; backdrop-filter:blur(4px);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            +{{ count($galleryImages) }} photos
                        </button>
                    @endif
                </div>

                {{-- 4 small slots --}}
                @for($s = 1; $s <= 4; $s++)
                    <div class="relative overflow-hidden">
                        @if($hasGallery && isset($galleryImages[$s]))
                            <img src="{{ $galleryImages[$s] }}" alt="Tour photo {{ $s }}"
                                class="w-full h-full object-cover cursor-pointer hover:opacity-90 transition-opacity"
                                loading="lazy" decoding="async">
                        @else
                            <div class="w-full h-full img-placeholder" style="background:{{ $gradients[$s % count($gradients)] }};"></div>
                        @endif
                    </div>
                @endfor
            </div>

            {{-- ── 2-col layout ─────────────────────────────────────── --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- ── LEFT: Main Content ───────────────────────────── --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Overview --}}
                    <div class="ota-card p-6 md:p-8">
                        <p class="fb-eyebrow mb-3">Overview</p>
                        <div class="text-base leading-relaxed whitespace-pre-line" style="color:var(--ink2);">
                            {{ $package->description }}
                        </div>
                    </div>

                    {{-- Inclusions strip --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        @php
                            $strips = [
                                ['icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'label'=>'Visa', 'sub'=>'Assistance incl.', 'color'=>'#E8F5EC','text'=>'#1F6E3D'],
                                ['icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'label'=>'Stay', 'sub'=>'Hotel incl.', 'color'=>'#EEF2FF','text'=>'#4338CA'],
                                ['icon'=>'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4', 'label'=>'Transfers', 'sub'=>'Airport to hotel', 'color'=>'#FFF4D6','text'=>'#8A5A00'],
                                ['icon'=>'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'label'=>'Guide', 'sub'=>'Expert local guide', 'color'=>'#FFE9EC','text'=>'var(--red)'],
                            ];
                        @endphp
                        @foreach($strips as $strip)
                            <div class="ota-card p-4 text-center">
                                <div class="w-10 h-10 mx-auto mb-2 rounded-xl flex items-center justify-center"
                                    style="background:{{ $strip['color'] }};">
                                    <svg class="w-5 h-5" fill="none" stroke="{{ $strip['text'] }}" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $strip['icon'] }}"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-bold mb-0.5" style="color:var(--ink);">{{ $strip['label'] }}</p>
                                <p class="text-xs" style="color:var(--mute);">{{ $strip['sub'] }}</p>
                            </div>
                        @endforeach
                    </div>

                    {{-- Itinerary --}}
                    <div class="ota-card overflow-hidden">
                        <div class="px-6 md:px-8 pt-6 pb-4 flex items-center justify-between" style="border-bottom:1px solid var(--rule);">
                            <p class="fb-eyebrow">Day-by-day Itinerary</p>
                            @if(!empty($package->itinerary) && is_array($package->itinerary))
                                <span class="fb-mono">{{ count($package->itinerary) }} days</span>
                            @endif
                        </div>

                        <div class="p-6 md:p-8 space-y-0">
                            @if(!empty($package->itinerary) && is_array($package->itinerary))
                                @foreach($package->itinerary as $idx => $day)
                                    @php $isLast = $loop->last; @endphp
                                    <div class="relative flex gap-4 {{ $isLast ? '' : 'pb-6' }}">
                                        {{-- Timeline line --}}
                                        @if(!$isLast)
                                            <div class="absolute left-4 top-8 bottom-0 w-px" style="background:var(--rule);"></div>
                                        @endif
                                        {{-- Day number circle --}}
                                        <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold z-10"
                                            style="background:var(--red); color:#fff; margin-top:2px;">
                                            {{ $day['day'] ?? ($idx + 1) }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold mb-1" style="color:var(--ink);">{{ $day['title'] ?? 'Day ' . ($idx+1) }}</h3>
                                            @if(!empty($day['activities']))
                                                <ul class="space-y-1">
                                                    @foreach($day['activities'] as $act)
                                                        <li class="flex items-start gap-2 text-sm" style="color:var(--ink2);">
                                                            <svg class="w-3.5 h-3.5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--red);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                            {{ $act }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @elseif(!empty($day['description']))
                                                <p class="text-sm" style="color:var(--ink2);">{{ $day['description'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                {{-- Placeholder itinerary --}}
                                @php
                                    $placeholderDays = [
                                        ['title'=>'Arrival & City Orientation','desc'=>'Welcome to your destination. Airport transfer to hotel, check-in and evening orientation walk.'],
                                        ['title'=>'Main Sightseeing Day','desc'=>'Full-day guided tour of top landmarks. Breakfast included at hotel.'],
                                        ['title'=>'Cultural Immersion','desc'=>'Local market visit, hands-on cultural experience, and optional cooking class.'],
                                        ['title'=>'Nature Excursion','desc'=>'Day trip to a natural highlight. Transfers and lunch included.'],
                                        ['title'=>'Leisure & Departure','desc'=>'Free morning for shopping or relaxation. Airport transfer and departure.'],
                                    ];
                                    $days = array_slice($placeholderDays, 0, $package->duration_days ?? 5);
                                @endphp
                                @foreach($days as $di => $pd)
                                    @php $isLast = $di === count($days)-1; @endphp
                                    <div class="relative flex gap-4 {{ $isLast ? '' : 'pb-6' }}">
                                        @if(!$isLast)
                                            <div class="absolute left-4 top-8 bottom-0 w-px" style="background:var(--rule);"></div>
                                        @endif
                                        <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold z-10"
                                            style="background:var(--red); color:#fff; margin-top:2px;">
                                            {{ $di + 1 }}
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-semibold mb-1" style="color:var(--ink);">Day {{ $di+1 }} · {{ $pd['title'] }}</h3>
                                            <p class="text-sm" style="color:var(--mute);">{{ $pd['desc'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- Included / Not Included --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Included --}}
                        <div class="ota-card p-6">
                            <p class="fb-eyebrow mb-4" style="color:#1F6E3D;">What's included</p>
                            <ul class="space-y-2">
                                @forelse($package->inclusions ?? [] as $item)
                                    <li class="flex items-start gap-2.5 text-sm" style="color:var(--ink2);">
                                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#1F6E3D;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        {{ $item }}
                                    </li>
                                @empty
                                    @foreach(['Return flights from Dhaka','Hotel accommodation (twin-share)','Daily breakfast','All transfers & airport pickup','Licensed English-speaking guide','Entry fees to all listed sites','Travel insurance'] as $item)
                                        <li class="flex items-start gap-2.5 text-sm" style="color:var(--ink2);">
                                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#1F6E3D;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                @endforelse
                            </ul>
                        </div>

                        {{-- Not Included --}}
                        <div class="ota-card p-6">
                            <p class="fb-eyebrow mb-4">Not included</p>
                            <ul class="space-y-2">
                                @forelse($package->exclusions ?? [] as $item)
                                    <li class="flex items-start gap-2.5 text-sm" style="color:var(--ink2);">
                                        <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C8102E;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        {{ $item }}
                                    </li>
                                @empty
                                    @foreach(['Personal expenses & shopping','Visa fees (unless stated)','Meals not in itinerary','Optional excursion upgrades','Tips & gratuities'] as $item)
                                        <li class="flex items-start gap-2.5 text-sm" style="color:var(--ink2);">
                                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#C8102E;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                @endforelse
                            </ul>
                        </div>
                    </div>

                    {{-- Additional accordion sections --}}
                    @php
                        $accordions = [
                            ['id'=>'hotel',    'title'=>'Hotel Details',           'icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'content'=>$package->hotel_details],
                            ['id'=>'req',      'title'=>'Requirements',            'icon'=>'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2', 'content'=>$package->requirements],
                            ['id'=>'tips',     'title'=>'Travel Tips',             'icon'=>'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'content'=>$package->travel_tips],
                            ['id'=>'policy',   'title'=>'Cancellation Policy',    'icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'content'=>$package->policy],
                            ['id'=>'addl',     'title'=>'Additional Information',  'icon'=>'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'content'=>$package->additional_info],
                        ];
                    @endphp
                    <div class="space-y-3" x-data="{ openSec: '' }">
                        @foreach($accordions as $ac)
                            @if(!empty($ac['content']))
                                <div class="ota-card overflow-hidden">
                                    <button @click="openSec = (openSec === '{{ $ac['id'] }}' ? '' : '{{ $ac['id'] }}')"
                                        class="w-full flex items-center justify-between px-6 py-4 text-left">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-xl flex items-center justify-center"
                                                style="background:#FFE9EC;">
                                                <svg class="w-4.5 h-4.5" fill="none" stroke="var(--red)" viewBox="0 0 24 24" style="width:18px;height:18px;">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $ac['icon'] }}"/>
                                                </svg>
                                            </div>
                                            <span class="font-semibold text-sm" style="color:var(--ink);">{{ $ac['title'] }}</span>
                                        </div>
                                        <svg class="w-4 h-4 transition-transform duration-200"
                                            :class="openSec === '{{ $ac['id'] }}' ? 'rotate-180' : ''"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--mute);">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                    <div x-show="openSec === '{{ $ac['id'] }}'" x-collapse
                                        class="px-6 pb-6 pt-0">
                                        <hr style="border-color:var(--rule); margin-bottom:1rem;">
                                        <div class="text-sm leading-relaxed whitespace-pre-line" style="color:var(--ink2);">
                                            @if(is_array($ac['content']))
                                                <ul class="space-y-1">
                                                    @foreach($ac['content'] as $ci)
                                                        <li class="flex items-start gap-2">
                                                            <span style="color:var(--red);">·</span> {{ $ci }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                {{ $ac['content'] }}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    {{-- WhatsApp CTA --}}
                    <div class="ota-card p-6 flex flex-col sm:flex-row items-center gap-5"
                        style="background:linear-gradient(135deg,#E8F5EC,#D4EDD9); border-color:#B8E0C4;">
                        <div class="shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center"
                            style="background:#25D366;">
                            <svg class="w-7 h-7" fill="#fff" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="font-bold mb-1" style="color:var(--ink);">Chat on WhatsApp</h3>
                            <p class="text-sm" style="color:var(--mute);">Get instant answers from our travel experts. Usually reply in under 5 minutes.</p>
                        </div>
                        <a href="https://wa.me/8809611677989?text=Hi!+I'm+interested+in+{{ urlencode($package->title) }}"
                            target="_blank" rel="noopener"
                            class="shrink-0 flex items-center gap-2 font-semibold px-5 py-3 rounded-xl text-sm transition-all"
                            style="background:#25D366; color:#fff; text-decoration:none;"
                            onmouseover="this.style.background='#128C7E'" onmouseout="this.style.background='#25D366'">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Chat now
                        </a>
                    </div>

                    {{-- Custom plan CTA --}}
                    <div class="ota-card p-8 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
                        <div class="absolute -right-12 -top-12 w-40 h-40 rounded-full opacity-5" style="background:var(--red);"></div>
                        <div class="relative">
                            <h3 class="fb-serif text-2xl mb-1" style="color:var(--ink);"><em>Need a personalised plan?</em></h3>
                            <p class="text-sm" style="color:var(--mute);">Our experts craft custom itineraries around your schedule and budget.</p>
                        </div>
                        <button @click="openInquiryModal = true"
                            class="ota-btn-primary shrink-0 px-7 py-3.5">
                            Get custom plan
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ── RIGHT: Sticky Booking Card ───────────────────── --}}
                <div class="lg:col-span-1">
                    <div class="ota-card p-6 md:p-8" style="position:sticky; top:88px;">

                        {{-- Price --}}
                        <div class="mb-6 pb-6" style="border-bottom:1px solid var(--rule);">
                            <p class="fb-mono mb-1">From</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold" style="color:var(--red);">৳{{ number_format($package->price) }}</span>
                                <span class="text-sm" style="color:var(--mute);">/ person</span>
                            </div>
                            @if($package->start_date)
                                <div class="mt-3 flex items-center gap-2 text-xs font-semibold px-3 py-2 rounded-lg" style="background:#FFE9EC; color:var(--red);">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    Next departure: {{ $package->start_date->format('d M Y') }}
                                </div>
                            @endif
                        </div>

                        {{-- Booking form --}}
                        <form action="{{ route('bookings.store') }}" method="POST"
                            x-data="{
                                adults: 1, children: 0, infants: 0,
                                price: {{ $package->price }},
                                get qty() { return parseInt(this.adults)+parseInt(this.children)+parseInt(this.infants); },
                                get subtotal() { return this.qty * this.price; },
                                get deposit() { return Math.ceil(this.subtotal * 0.25); }
                            }">
                            @csrf
                            <input type="hidden" name="payable_type" value="package">
                            <input type="hidden" name="payable_id" value="{{ $package->id }}">
                            <input type="hidden" name="quantity" :value="qty">

                            @if($errors->any())
                                <div class="mb-4 p-4 rounded-xl text-sm" style="background:#FFE9EC; color:var(--red); border:1px solid #FFD0D8;">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="space-y-4">
                                {{-- Departure date --}}
                                <div class="fb-field">
                                    <label class="fb-field-label">Departure date</label>
                                    <input type="date" name="booking_date" required min="{{ date('Y-m-d') }}"
                                        class="fb-input"
                                        style="font-size:14px; color:var(--ink);">
                                </div>

                                {{-- Travellers --}}
                                <div class="fb-field">
                                    <label class="fb-field-label">Travellers</label>
                                    <div class="grid grid-cols-3 gap-3 mt-1">
                                        @foreach([['Adults','adults',1],['Children','children',0],['Infants','infants',0]] as [$lbl,$model,$min])
                                            <div class="text-center">
                                                <p class="text-xs mb-1.5" style="color:var(--mute);">{{ $lbl }}</p>
                                                <input type="number" name="details[{{ $model }}]" x-model="{{ $model }}" min="{{ $min }}"
                                                    class="w-full text-center text-sm font-semibold rounded-lg py-2 border focus:outline-none"
                                                    style="border-color:var(--rule); color:var(--ink); background:#fff;">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                @guest
                                    <div class="fb-field">
                                        <label class="fb-field-label">Your name</label>
                                        <input type="text" name="guest_name" required value="{{ old('guest_name') }}"
                                            class="fb-input" placeholder="Full name">
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                        <div class="fb-field">
                                            <label class="fb-field-label">Email</label>
                                            <input type="email" name="guest_email" required value="{{ old('guest_email') }}"
                                                class="fb-input" placeholder="you@email.com">
                                        </div>
                                        <div class="fb-field">
                                            <label class="fb-field-label">Phone</label>
                                            <input type="text" name="guest_phone" required value="{{ old('guest_phone') }}"
                                                class="fb-input" placeholder="+8801...">
                                        </div>
                                    </div>
                                @endguest

                                <div class="fb-field">
                                    <label class="fb-field-label">Special requests</label>
                                    <textarea name="notes" rows="2" class="fb-input resize-none" placeholder="Any dietary needs, accessibility, etc."></textarea>
                                </div>
                            </div>

                            {{-- Price breakdown --}}
                            <div class="mt-5 rounded-2xl p-4 space-y-2" style="background:var(--cream); border:1px solid var(--rule);">
                                <div class="flex justify-between text-sm" style="color:var(--ink2);">
                                    <span>৳{{ number_format($package->price) }} × <span x-text="qty"></span> person<span x-show="qty>1">s</span></span>
                                    <span class="font-semibold" x-text="'৳' + subtotal.toLocaleString('en-IN')"></span>
                                </div>
                                <div class="flex justify-between text-xs" style="color:var(--mute);">
                                    <span>25% deposit due now</span>
                                    <span x-text="'৳' + deposit.toLocaleString('en-IN')"></span>
                                </div>
                                <hr style="border-color:var(--rule);">
                                <div class="flex justify-between font-bold">
                                    <span style="color:var(--ink);">Total</span>
                                    <span style="color:var(--red);" x-text="'৳' + subtotal.toLocaleString('en-IN')"></span>
                                </div>
                            </div>

                            <button type="submit"
                                class="ota-btn-primary w-full justify-center mt-4 py-4 text-base font-bold">
                                Reserve · 25% deposit
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>

                            <a href="{{ route('contact') }}"
                                class="ota-btn-ghost w-full justify-center mt-3 py-3 text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Ask a question
                            </a>
                        </form>

                        {{-- Trust signals --}}
                        <div class="mt-5 pt-5 grid grid-cols-3 gap-3 text-center" style="border-top:1px solid var(--rule);">
                            @foreach([['Free cancellation','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],['Best price','M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],['24/7 support','M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z']] as [$label, $icon])
                                <div>
                                    <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--red);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/></svg>
                                    <p class="text-xs" style="color:var(--mute);">{{ $label }}</p>
                                </div>
                            @endforeach
                        </div>

                        <p class="text-center text-xs mt-4" style="color:var(--mute);">
                            <svg class="w-3.5 h-3.5 inline-block mr-1 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            Secure booking · Instant confirmation
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Related Packages ─────────────────────────────────── --}}
            @if(isset($relatedPackages) && count($relatedPackages) > 0)
                <div class="mt-16">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="fb-serif text-3xl" style="color:var(--ink);"><em>You might also like</em></h2>
                        <a href="{{ route('packages.index') }}" class="ota-btn-ghost text-sm px-4 py-2">
                            View all
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                        @foreach($relatedPackages as $rel)
                            <a href="{{ route('packages.show', $rel->slug) }}"
                                class="ota-card group flex flex-col overflow-hidden no-underline"
                                style="text-decoration:none;">
                                <div class="overflow-hidden" style="height:160px;">
                                    @if($rel->thumbnail)
                                        <img src="{{ Str::startsWith($rel->thumbnail,'http') ? $rel->thumbnail : Storage::url($rel->thumbnail) }}"
                                            alt="{{ $rel->title }}" class="w-full h-full object-cover transition-transform duration-400 group-hover:scale-105" loading="lazy">
                                    @else
                                        <div class="w-full h-full img-placeholder" style="background:{{ $gradients[$loop->index % count($gradients)] }};"></div>
                                    @endif
                                </div>
                                <div class="p-4 flex flex-col flex-1">
                                    <p class="fb-eyebrow text-xs mb-1">{{ $rel->location ?? '' }}</p>
                                    <h3 class="font-semibold text-sm mb-auto truncate" style="color:var(--ink);">{{ $rel->title }}</h3>
                                    <div class="flex items-center justify-between mt-3 pt-3" style="border-top:1px solid var(--rule);">
                                        <span class="font-bold" style="color:var(--red);">৳{{ number_format($rel->price) }}</span>
                                        <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--mute);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        {{-- ── Inquiry Modal ────────────────────────────────────────── --}}
        <div x-show="openInquiryModal" x-cloak style="display:none;"
            class="fixed inset-0 z-[100] overflow-y-auto flex items-center justify-center p-4" role="dialog" aria-modal="true">
            <div class="fixed inset-0" style="background:rgba(24,19,14,0.5); backdrop-filter:blur(4px);"
                @click="openInquiryModal = false"></div>

            <div class="relative bg-white rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100">

                <div class="flex items-center justify-between px-8 py-6" style="border-bottom:1px solid var(--rule);">
                    <div>
                        <p class="fb-eyebrow mb-0.5">Customise this tour</p>
                        <h3 class="font-bold text-lg" style="color:var(--ink);">Request a personalised plan</h3>
                    </div>
                    <button @click="openInquiryModal = false"
                        class="w-9 h-9 flex items-center justify-center rounded-full transition-colors"
                        style="border:1px solid var(--rule);"
                        onmouseover="this.style.background='var(--cream)'" onmouseout="this.style.background='transparent'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>

                <div class="overflow-y-auto max-h-[70vh] px-8 py-6">
                    <form action="{{ route('packages.customize', $package->id) }}" method="POST" class="space-y-5">
                        @csrf

                        <template x-for="(dest, di) in inquiryForm.destinations" :key="di">
                            <div class="p-4 rounded-2xl space-y-4 relative" style="background:var(--cream); border:1px solid var(--rule);">
                                <button type="button" @click="removeDestination(di)"
                                    x-show="inquiryForm.destinations.length > 1"
                                    class="absolute top-3 right-3 w-6 h-6 flex items-center justify-center rounded-full text-white"
                                    style="background:var(--red);">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                <div class="grid grid-cols-2 gap-3">
                                    <div class="fb-field">
                                        <label class="fb-field-label">Country</label>
                                        <input type="text" :name="'destinations['+di+'][country]'" x-model="dest.country" required
                                            class="fb-input" placeholder="e.g. Thailand">
                                    </div>
                                    <div class="fb-field">
                                        <label class="fb-field-label">Nights</label>
                                        <input type="number" :name="'destinations['+di+'][nights]'" x-model="dest.nights" required
                                            class="fb-input" placeholder="5">
                                    </div>
                                </div>
                                <template x-for="(city, ci) in dest.cities" :key="ci">
                                    <div class="flex gap-3 items-end pl-4" style="border-left:2px solid var(--rule);">
                                        <div class="flex-1 fb-field">
                                            <label class="fb-field-label">City</label>
                                            <input type="text" :name="'destinations['+di+'][cities]['+ci+'][name]'" x-model="city.name"
                                                class="fb-input" placeholder="e.g. Bangkok">
                                        </div>
                                        <div class="w-24 fb-field">
                                            <label class="fb-field-label">Nights</label>
                                            <input type="number" :name="'destinations['+di+'][cities]['+ci+'][nights]'" x-model="city.nights"
                                                class="fb-input">
                                        </div>
                                        <button type="button" @click="removeCity(di, ci)" x-show="dest.cities.length > 1"
                                            class="mb-1 w-7 h-7 flex items-center justify-center rounded-full shrink-0"
                                            style="border:1px solid var(--rule);">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>
                                <button type="button" @click="addCity(di)"
                                    class="text-xs font-semibold flex items-center gap-1 transition-colors"
                                    style="color:var(--red);">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                    Add city
                                </button>
                            </div>
                        </template>

                        <button type="button" @click="addDestination()"
                            class="w-full py-3 rounded-2xl text-sm font-semibold transition-colors"
                            style="border:2px dashed var(--rule); color:var(--mute);"
                            onmouseover="this.style.borderColor='var(--red)';this.style.color='var(--red)'"
                            onmouseout="this.style.borderColor='var(--rule)';this.style.color='var(--mute)'">
                            + Add destination
                        </button>

                        <div class="grid grid-cols-3 gap-3">
                            @foreach([['Adults','adults',1],['Children','children',0],['Infants','infants',0]] as [$lbl,$m,$mn])
                                <div class="fb-field">
                                    <label class="fb-field-label">{{ $lbl }}</label>
                                    <input type="number" name="{{ $m }}" x-model="inquiryForm.{{ $m }}" min="{{ $mn }}" class="fb-input">
                                </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div class="fb-field">
                                <label class="fb-field-label">Hotel category</label>
                                <select name="hotel_type" x-model="inquiryForm.hotel_type" class="fb-input">
                                    <option value="">Select</option>
                                    <option>3 Star</option>
                                    <option>4 Star</option>
                                    <option>5 Star</option>
                                    <option>Budget</option>
                                </select>
                            </div>
                            <div class="fb-field">
                                <label class="fb-field-label">Travel date</label>
                                <input type="date" name="travel_date" x-model="inquiryForm.travel_date" class="fb-input">
                            </div>
                        </div>

                        <div class="fb-field">
                            <label class="fb-field-label">Notes / requirements</label>
                            <textarea name="message" x-model="inquiryForm.message" rows="3" class="fb-input resize-none" placeholder="Special requests, budget, preferences..."></textarea>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-3" style="border-top:1px solid var(--rule);">
                            <div class="fb-field">
                                <label class="fb-field-label">Name</label>
                                <input type="text" name="name" x-model="inquiryForm.name" required class="fb-input" placeholder="Full name">
                            </div>
                            <div class="fb-field">
                                <label class="fb-field-label">Phone</label>
                                <input type="text" name="phone" x-model="inquiryForm.phone" required class="fb-input" placeholder="+8801...">
                            </div>
                            <div class="fb-field">
                                <label class="fb-field-label">Email</label>
                                <input type="email" name="email" x-model="inquiryForm.email" required class="fb-input" placeholder="you@email.com">
                            </div>
                        </div>

                        <button type="submit" class="ota-btn-primary w-full justify-center py-4 text-base">
                            Submit request
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
