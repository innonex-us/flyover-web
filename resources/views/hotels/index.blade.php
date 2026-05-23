<x-app-layout>

{{-- ── HOTEL HERO ────────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pt-12 pb-16" style="background:#FAF6EE;">
    <div class="max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

        {{-- Left copy --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <span class="ota-tag-red text-[10px] tracking-widest font-bold">NEW</span>
                <span class="fb-eyebrow">Service / 03 · Hotel Booking</span>
            </div>
            <h1 class="fb-serif mt-2 leading-[0.95]" style="font-size:clamp(48px,6.5vw,82px);color:#18130E;">
                Rooms we'd actually<br>stay in.
            </h1>
            <p class="mt-5 text-base leading-relaxed max-w-md" style="color:#3A332B;">
                8,400 properties across 62 cities — from heritage havelis in Old Dhaka to overwater villas in the Maldives. We only list what clears our 47-point comfort check.
            </p>

            {{-- Stats row --}}
            <div class="flex flex-wrap gap-6 mt-8">
                @foreach([['8,400','properties'],['62','cities'],['24 HR','free cancel']] as [$num,$label])
                <div>
                    <p class="text-2xl font-bold leading-none" style="color:#18130E;">{{ $num }}</p>
                    <p class="fb-mono mt-1">{{ $label }}</p>
                </div>
                @endforeach
            </div>

            <div class="flex flex-wrap gap-3 mt-8">
                <a href="#search-widget" class="ota-btn-primary px-6 py-3 text-base">Search hotels</a>
                <a href="#hotel-results" class="ota-btn-ghost px-6 py-3 text-base">Browse all →</a>
            </div>
        </div>

        {{-- Right: image placeholder card --}}
        <div class="relative hidden lg:flex items-center justify-center">
            {{-- Main image card --}}
            <div class="relative w-full max-w-sm rounded-2xl overflow-hidden shadow-2xl" style="height:380px;background:linear-gradient(135deg,#1A2026 0%,#2E3C4A 50%,#697D8E 100%);">
                <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                <div class="absolute inset-0" style="background:radial-gradient(circle at 30% 40%,rgba(255,255,255,0.06),transparent 50%);"></div>
                {{-- Negotiated rate badge --}}
                <div class="absolute top-4 left-4 bg-white rounded-xl px-4 py-2.5 shadow-xl flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white" style="background:#1F6E3D;">%</span>
                    <div>
                        <p class="text-[10px] font-semibold" style="color:#7A7166;">NEGOTIATED RATE</p>
                        <p class="text-sm font-bold" style="color:#18130E;">Up to 40% off</p>
                    </div>
                </div>
                {{-- Hotel name overlay --}}
                <div class="absolute bottom-0 left-0 right-0 p-5" style="background:linear-gradient(to top,rgba(0,0,0,0.75),transparent);">
                    <div class="flex items-end justify-between">
                        <div>
                            <p class="text-white font-bold text-lg leading-tight">The Peninsula Dhaka</p>
                            <p class="text-white/70 text-xs mt-0.5">Gulshan · 5-star</p>
                        </div>
                        <div class="rating-badge">★ 4.9</div>
                    </div>
                </div>
            </div>
            {{-- Float card: just booked --}}
            <div class="absolute -bottom-4 -left-6 bg-white rounded-xl px-4 py-3 shadow-xl flex items-center gap-3 border border-[#E4DCC9]">
                <span class="text-xl">🛎️</span>
                <div>
                    <p class="text-xs font-bold" style="color:#18130E;">Just booked</p>
                    <p class="text-xs" style="color:#7A7166;">Deluxe Suite · 3 nights</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── SEARCH WIDGET ─────────────────────────────────────────────────── --}}
<section id="search-widget" class="px-4 lg:px-16 -mt-6 relative z-10 max-w-screen-xl mx-auto pb-6">
    <div class="bg-white rounded-2xl shadow-xl border border-[#E4DCC9] p-5 lg:p-6">
        <form action="{{ route('hotels.index') }}" method="GET">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
                {{-- City / Hotel --}}
                <div class="fb-field lg:col-span-2">
                    <label class="fb-field-label">City or Hotel</label>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Dhaka, Cox's Bazar, Sylhet…" class="fb-input text-sm">
                </div>
                {{-- Check-in --}}
                <div class="fb-field">
                    <label class="fb-field-label">Check-in</label>
                    <input type="date" name="check_in" value="{{ request('check_in') }}" class="fb-input text-sm">
                </div>
                {{-- Check-out --}}
                <div class="fb-field">
                    <label class="fb-field-label">Check-out</label>
                    <input type="date" name="check_out" value="{{ request('check_out') }}" class="fb-input text-sm">
                </div>
                {{-- Guests --}}
                <div class="fb-field">
                    <label class="fb-field-label">Guests</label>
                    <select name="guests" class="fb-input text-sm bg-transparent">
                        @foreach(['1 adult','2 adults','2 adults, 1 child','2 adults, 2 children','3 adults','4 adults'] as $opt)
                            <option {{ request('guests') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-4 flex justify-end">
                <button type="submit" class="ota-btn-primary px-8 py-3 text-base">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Search Hotels
                </button>
            </div>
        </form>
    </div>
</section>

{{-- ── CATEGORIES STRIP ──────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-6 max-w-screen-xl mx-auto">
    <div class="flex gap-3 overflow-x-auto scroll-snap-x pb-2 -mx-1 px-1" style="scrollbar-width:none;">
        @foreach([
            ['🏖️','Beach & Resort'],
            ['⛰️','Mountain Lodges'],
            ['🏛️','Heritage Hotels'],
            ['🏙️','City Hotels'],
            ['🌿','Boutique'],
            ['🥂','All-inclusive'],
            ['🌊','Overwater Villas'],
            ['💑','Honeymoon'],
        ] as [$icon, $label])
        <button class="flex items-center gap-2 whitespace-nowrap px-4 py-2.5 rounded-full border text-sm font-semibold shrink-0 transition-colors duration-150
            {{ request('category') === $label ? 'text-white border-[#C8102E]' : 'border-[#E4DCC9] text-[#3A332B] bg-white hover:border-[#18130E]' }}"
            style="{{ request('category') === $label ? 'background:#C8102E;' : '' }}">
            <span>{{ $icon }}</span>{{ $label }}
        </button>
        @endforeach
    </div>
</section>

{{-- ── RESULTS: SIDEBAR + HOTEL LIST ────────────────────────────────── --}}
<section id="hotel-results" class="px-4 lg:px-16 pb-20 max-w-screen-xl mx-auto">
    <div class="flex gap-8 items-start">

        {{-- Sidebar filters --}}
        <aside class="hidden lg:block shrink-0" style="width:240px;" x-data="{open:true}">
            <div class="ota-card p-5 sticky top-6">
                <h3 class="font-bold text-sm mb-5" style="color:#18130E;">Filter results</h3>

                {{-- Price per night --}}
                <div class="mb-6">
                    <p class="fb-eyebrow mb-3">Price per night</p>
                    <div class="space-y-2">
                        @foreach([['Under ৳3,000','lt3k'],['৳3,000 – ৳7,000','3k7k'],['৳7,000 – ৳15,000','7k15k'],['৳15,000+','gt15k']] as [$label,$val])
                        <label class="flex items-center gap-2.5 text-sm cursor-pointer" style="color:#3A332B;">
                            <input type="checkbox" name="price[]" value="{{ $val }}" class="rounded border-[#E4DCC9] text-[#C8102E]" {{ in_array($val, (array)request('price')) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Star rating --}}
                <div class="mb-6">
                    <p class="fb-eyebrow mb-3">Star rating</p>
                    <div class="space-y-2">
                        @foreach([5,4,3,2] as $star)
                        <label class="flex items-center gap-2.5 text-sm cursor-pointer" style="color:#3A332B;">
                            <input type="checkbox" name="stars[]" value="{{ $star }}" class="rounded border-[#E4DCC9] text-[#C8102E]" {{ in_array($star, (array)request('stars')) ? 'checked' : '' }}>
                            <span class="text-amber-400 text-sm">{{ str_repeat('★',$star) }}{{ str_repeat('☆',5-$star) }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Amenities --}}
                <div class="mb-6">
                    <p class="fb-eyebrow mb-3">Amenities</p>
                    <div class="space-y-2">
                        @foreach([['Free WiFi','wifi'],['Pool','pool'],['Gym','gym'],['Breakfast','breakfast'],['Airport shuttle','shuttle'],['Parking','parking']] as [$label,$val])
                        <label class="flex items-center gap-2.5 text-sm cursor-pointer" style="color:#3A332B;">
                            <input type="checkbox" name="amenities[]" value="{{ $val }}" class="rounded border-[#E4DCC9] text-[#C8102E]" {{ in_array($val, (array)request('amenities')) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                {{-- Property type --}}
                <div class="mb-4">
                    <p class="fb-eyebrow mb-3">Property type</p>
                    <div class="space-y-2">
                        @foreach([['Hotel','hotel'],['Resort','resort'],['Boutique','boutique'],['Villa','villa'],['Hostel','hostel']] as [$label,$val])
                        <label class="flex items-center gap-2.5 text-sm cursor-pointer" style="color:#3A332B;">
                            <input type="checkbox" name="type[]" value="{{ $val }}" class="rounded border-[#E4DCC9] text-[#C8102E]" {{ in_array($val, (array)request('type')) ? 'checked' : '' }}>
                            {{ $label }}
                        </label>
                        @endforeach
                    </div>
                </div>

                <button type="button" class="w-full ota-btn-primary py-2.5 text-sm mt-2">Apply filters</button>
            </div>
        </aside>

        {{-- Hotel results list --}}
        <div class="flex-1 min-w-0">
            {{-- Sort bar --}}
            <div class="flex items-center justify-between mb-5">
                <p class="text-sm" style="color:#7A7166;">
                    Showing <strong style="color:#18130E;">{{ isset($hotels) ? $hotels->count() : 6 }}</strong> properties
                    @if(request('q')) for "<strong style="color:#18130E;">{{ request('q') }}</strong>" @endif
                </p>
                <select class="text-sm border border-[#E4DCC9] rounded-lg px-3 py-2 bg-white" style="color:#18130E;">
                    <option>Our picks</option>
                    <option>Price: low to high</option>
                    <option>Price: high to low</option>
                    <option>Star rating</option>
                    <option>Guest rating</option>
                </select>
            </div>

            {{-- Hotel cards --}}
            <div class="space-y-4">
                @php
                $sampleHotels = [
                    ['The Peninsula Dhaka','Gulshan, Dhaka','5','4.9','242','12,500',['Free WiFi','Pool','Spa','Gym'],['1A2026','2E3C4A','697D8E']],
                    ['Six Seasons Hotel','Banani, Dhaka','5','4.8','189','9,800',['Free WiFi','Pool','Restaurant','Bar'],['1E2A22','36473A','7C8E80']],
                    ['Cox\'s Bazar Beach Resort','Cox\'s Bazar','4','4.6','312','6,200',['Beach access','Free WiFi','Pool','Breakfast'],['2B2117','574535','A89178']],
                    ['Sylhet Tea Garden Retreat','Sylhet','4','4.5','97','5,500',['Tea garden view','Free WiFi','Spa','Restaurant'],['1C2417','354A2E','7A9466']],
                    ['Rangamati Lake View','Rangamati','3','4.3','64','2,800',['Lake view','Free WiFi','Boat service','Restaurant'],['25211C','4A3E35','8C7D6C']],
                    ['Bhuiyan Heritage Haveli','Old Dhaka','4','4.7','28','4,200',['Heritage tours','Free WiFi','Rooftop terrace','Restaurant'],['261E14','4F3C24','96784A']],
                ];
                @endphp

                @isset($hotels)
                    @forelse($hotels as $hotel)
                    <div class="ota-card overflow-hidden">
                        <div class="flex flex-col sm:flex-row">
                            <div class="shrink-0 sm:w-64 h-48 sm:h-auto relative" style="background:linear-gradient(135deg,#1A2026 0%,#2E3C4A 50%,#697D8E 100%);min-height:180px;">
                                <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                                <div class="absolute top-3 left-3">
                                    <span class="rating-badge">★ {{ $hotel->rating ?? '4.5' }}</span>
                                </div>
                            </div>
                            <div class="flex-1 p-5 flex flex-col sm:flex-row gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-amber-400 text-sm">{{ str_repeat('★', $hotel->stars ?? 4) }}</span>
                                        <span class="fb-mono">{{ $hotel->location ?? 'Bangladesh' }}</span>
                                    </div>
                                    <h3 class="font-bold text-lg leading-tight mb-2" style="color:#18130E;">{{ $hotel->name }}</h3>
                                    <p class="text-sm leading-relaxed mb-3" style="color:#7A7166;">{{ Str::limit($hotel->description ?? 'A premium property with exceptional service and comfort.', 100) }}</p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach(explode(',', $hotel->amenities ?? 'Free WiFi,Pool,Restaurant') as $amenity)
                                        <span class="fb-chip">{{ trim($amenity) }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="shrink-0 flex flex-col items-end justify-between sm:min-w-[140px]">
                                    <div class="text-right">
                                        <p class="text-xs" style="color:#7A7166;">per night from</p>
                                        <p class="text-2xl font-bold mt-0.5" style="color:#18130E;">৳{{ number_format($hotel->price_per_night ?? 5000) }}</p>
                                        <p class="text-xs mt-0.5" style="color:#1F6E3D;">Free cancellation</p>
                                    </div>
                                    <a href="#" class="ota-btn-primary mt-3 text-sm px-5 py-2.5">See rooms</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-16">
                        <p class="text-lg font-semibold" style="color:#18130E;">No hotels found</p>
                        <p class="text-sm mt-2" style="color:#7A7166;">Try adjusting your filters or search term.</p>
                    </div>
                    @endforelse
                @else
                    {{-- Sample data when no controller data passed --}}
                    @foreach($sampleHotels as [$name,$loc,$stars,$rating,$reviews,$price,$amenities,$colors])
                    <div class="ota-card overflow-hidden">
                        <div class="flex flex-col sm:flex-row">
                            {{-- Image placeholder --}}
                            <div class="shrink-0 sm:w-64 relative" style="min-height:180px;background:linear-gradient(135deg,#{{ $colors[0] }} 0%,#{{ $colors[1] }} 50%,#{{ $colors[2] }} 100%);">
                                <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                                <div class="absolute inset-0" style="background:radial-gradient(circle at 20% 30%,rgba(255,255,255,0.06),transparent 40%);"></div>
                                <div class="absolute top-3 left-3">
                                    <span class="rating-badge">★ {{ $rating }}</span>
                                </div>
                                <div class="absolute bottom-3 left-3">
                                    <span class="text-white/60 text-[10px] font-semibold tracking-widest uppercase">{{ $reviews }} reviews</span>
                                </div>
                            </div>
                            {{-- Info --}}
                            <div class="flex-1 p-5 flex flex-col sm:flex-row gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-1.5">
                                        <span class="text-amber-400 text-sm">{{ str_repeat('★', (int)$stars) }}{{ str_repeat('☆', 5-(int)$stars) }}</span>
                                        <span class="fb-mono">{{ $loc }}</span>
                                    </div>
                                    <h3 class="font-bold text-lg leading-tight mb-2" style="color:#18130E;">{{ $name }}</h3>
                                    <p class="text-sm leading-relaxed mb-3" style="color:#7A7166;">
                                        A handpicked property that meets our 47-point comfort standard — clean, well-located, and genuinely good value.
                                    </p>
                                    <div class="flex flex-wrap gap-1.5">
                                        @foreach($amenities as $amenity)
                                        <span class="fb-chip">{{ $amenity }}</span>
                                        @endforeach
                                    </div>
                                </div>
                                {{-- Price & CTA --}}
                                <div class="shrink-0 flex flex-col items-end justify-between sm:min-w-[140px]">
                                    <div class="text-right">
                                        <p class="text-xs" style="color:#7A7166;">per night from</p>
                                        <p class="text-2xl font-bold mt-0.5" style="color:#18130E;">৳{{ number_format((int)str_replace(',','',$price)) }}</p>
                                        <p class="text-xs mt-0.5 font-semibold" style="color:#1F6E3D;">Free cancellation</p>
                                    </div>
                                    <a href="{{ route('contact') }}" class="ota-btn-primary mt-3 text-sm px-5 py-2.5">See rooms</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endisset
            </div>

            {{-- Pagination --}}
            @isset($hotels)
            <div class="mt-8">{{ $hotels->links() }}</div>
            @endisset
        </div>
    </div>
</section>

{{-- ── WHY US: DARK SECTION ─────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-20" style="background:#18130E;">
    <div class="max-w-screen-xl mx-auto">
        <div class="max-w-xl mb-12">
            <span class="fb-eyebrow" style="color:#E4DCC9;opacity:0.6;">Our promise</span>
            <h2 class="fb-serif mt-3 text-white leading-tight" style="font-size:clamp(32px,4vw,52px);">
                We hold the room.<br>You pay on arrival.
            </h2>
            <p class="mt-4 text-base leading-relaxed" style="color:#7A7166;">
                No surprise charges. No lost bookings. If the room isn't as described, we fix it — or we move you.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach([
                ['🔒','Price lock guarantee','We match or beat any rate you find elsewhere within 24 hours of booking.'],
                ['✓','Room quality verified','Every listed property has been assessed by our team or a trusted partner on the ground.'],
                ['📞','24/7 support','Call us at any hour. Real people, not bots — fluent in Bangla and English.'],
                ['↩','Free cancellation','Cancel up to 24 hours before check-in on most rooms. No questions asked.'],
            ] as [$icon,$title,$desc])
            <div class="p-6 rounded-2xl border" style="border-color:#3A332B;background:#23201C;">
                <div class="text-3xl mb-4">{{ $icon }}</div>
                <h3 class="font-bold text-white mb-2">{{ $title }}</h3>
                <p class="text-sm leading-relaxed" style="color:#7A7166;">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

</x-app-layout>
