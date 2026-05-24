<x-app-layout>

{{-- ═══════════════════════════════════════════════
     HERO
═══════════════════════════════════════════════ --}}
<section style="background:#18130E;min-height:88vh;" class="relative overflow-hidden flex flex-col justify-center">

    {{-- Background image collage (decorative, right side) --}}
    <div class="absolute inset-0 pointer-events-none select-none overflow-hidden">
        {{-- Left gradient fade --}}
        <div class="absolute inset-y-0 left-0 w-1/2 z-10" style="background:linear-gradient(to right,#18130E 40%,transparent 100%);"></div>
        {{-- Image tiles --}}
        <div class="absolute right-0 top-0 bottom-0 w-1/2 md:w-7/12 flex gap-3 p-6 items-start">
            <div class="flex flex-col gap-3 mt-12 flex-1">
                <div class="rounded-2xl overflow-hidden" style="height:220px;background:linear-gradient(135deg,#C8102E,#6B0D18);">
                    <img src="{{ asset('banner/hero-banner-1.png') }}" alt="" class="w-full h-full object-cover opacity-70 mix-blend-luminosity" onerror="this.style.display='none'">
                </div>
                <div class="rounded-2xl overflow-hidden" style="height:150px;background:linear-gradient(135deg,#3A332B,#18130E);">
                    <img src="{{ asset('banner/hero-banner-3.png') }}" alt="" class="w-full h-full object-cover opacity-60 mix-blend-luminosity" onerror="this.style.display='none'">
                </div>
            </div>
            <div class="flex flex-col gap-3 flex-1">
                <div class="rounded-2xl overflow-hidden" style="height:160px;background:linear-gradient(135deg,#2d2520,#C8102E55);">
                    <img src="{{ asset('banner/helo-banner-2.png') }}" alt="" class="w-full h-full object-cover opacity-60 mix-blend-luminosity" onerror="this.style.display='none'">
                </div>
                <div class="rounded-2xl overflow-hidden" style="height:210px;background:linear-gradient(135deg,#C8102E22,#3A332B);">
                    <img src="{{ asset('banner/hero-banner-1.png') }}" alt="" class="w-full h-full object-cover opacity-50 mix-blend-luminosity" onerror="this.style.display='none'">
                </div>
            </div>
        </div>
        {{-- Flash sale badge --}}
        <div class="absolute top-10 right-[45%] z-20 hidden md:flex items-center gap-2 px-3 py-1.5 rounded-full text-xs font-bold" style="background:#C8102E;color:#FAF6EE;transform:rotate(-3deg);">
            <span class="animate-pulse w-1.5 h-1.5 rounded-full bg-white inline-block"></span>
            FLASH SALE — Up to 20% off
        </div>
        {{-- "Just booked" badge --}}
        <div class="absolute bottom-32 right-[42%] z-20 hidden md:flex items-center gap-2.5 px-4 py-2.5 rounded-xl text-xs" style="background:#FAF6EE;color:#18130E;box-shadow:0 4px 24px rgba(0,0,0,.4);">
            <div class="w-7 h-7 rounded-full flex items-center justify-center text-white text-[10px] font-bold" style="background:#C8102E;">AR</div>
            <div>
                <p class="font-semibold text-[11px]">Arif just booked</p>
                <p class="text-[10px]" style="color:#7A7166;">Cox's Bazar · 4 nights</p>
            </div>
        </div>
    </div>

    {{-- Hero content --}}
    <div class="relative z-20 max-w-6xl mx-auto w-full px-5 py-20 md:py-28">
        <p class="fb-eyebrow mb-5" style="color:#C8102E;">Bangladesh's trusted travel partner since 2015</p>
        <h1 class="fb-serif text-5xl md:text-7xl leading-[1.03] mb-6 max-w-xl" style="color:#FAF6EE;">
            The world<br>starts <em style="color:#C8102E;">here.</em>
        </h1>
        <p class="text-base md:text-lg max-w-md mb-10" style="color:#7A7166;">Tours, visas, hotels, and pick &amp; drop — handled end-to-end so you just show up.</p>
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('packages.index') }}" class="ota-btn-primary">Browse Packages</a>
            <a href="{{ route('visas.index') }}" class="ota-btn-ghost" style="border-color:#FAF6EE33;color:#FAF6EE;">Visa Services</a>
        </div>

        {{-- Trust strip --}}
        <div class="flex flex-wrap gap-6 mt-12 text-sm" style="color:#7A7166;">
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4" style="color:#C8102E;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                1.2M+ travellers served
            </span>
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4" style="color:#C8102E;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                62 destinations
            </span>
            <span class="flex items-center gap-2">
                <svg class="w-4 h-4" style="color:#C8102E;" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                94.2% visa approval rate
            </span>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     SEARCH WIDGET
═══════════════════════════════════════════════ --}}
<section style="background:#FAF6EE;border-bottom:1px solid #E4DCC9;" class="px-5 py-8">
    <div class="max-w-3xl mx-auto"
         x-data="{
             activeTab: 'tours',
             query: '',
             suggestions: [],
             showSuggestions: false,
             loading: false,
             fetchTimer: null,
             fetchSuggestions() {
                 this.loading = true;
                 clearTimeout(this.fetchTimer);
                 this.fetchTimer = setTimeout(() => {
                     fetch(`{{ route('search.suggestions') }}?type=${this.activeTab}&query=${this.query}`)
                         .then(r => r.json())
                         .then(d => { this.suggestions = d; this.showSuggestions = true; this.loading = false; })
                         .catch(() => { this.loading = false; });
                 }, 300);
             },
             selectSuggestion(url) { window.location.href = url; },
             switchTab(tab) { this.activeTab = tab; this.query = ''; this.suggestions = []; this.showSuggestions = false; }
         }"
         @click.away="showSuggestions = false">

        {{-- Tabs --}}
        <div class="flex gap-1 mb-5">
            <button @click="switchTab('tours')" class="px-5 py-2 rounded-full text-sm font-semibold transition"
                    :style="activeTab==='tours' ? 'background:#C8102E;color:#fff;' : 'background:#E4DCC9;color:#7A7166;'">
                ✈ Tour Packages
            </button>
            <button @click="switchTab('visas')" class="px-5 py-2 rounded-full text-sm font-semibold transition"
                    :style="activeTab==='visas' ? 'background:#C8102E;color:#fff;' : 'background:#E4DCC9;color:#7A7166;'">
                🛂 Visa Services
            </button>
        </div>

        {{-- Tours form --}}
        <div x-show="activeTab === 'tours'" class="relative">
            <form action="{{ route('packages.index') }}" method="GET" class="flex gap-2">
                <div class="flex-1 relative">
                    <label class="fb-field-label">Where to?</label>
                    <div class="relative mt-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color:#7A7166;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <input type="text" name="search" x-model="query" @input="fetchSuggestions()" @focus="fetchSuggestions()"
                               placeholder="Destination, tour name…"
                               class="fb-input pl-9 w-full" autocomplete="off">
                    </div>
                    {{-- Suggestions --}}
                    <div x-show="showSuggestions && activeTab === 'tours'"
                         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 w-full mt-1 rounded-xl overflow-hidden z-50 shadow-2xl"
                         style="background:#fff;border:1px solid #E4DCC9;display:none;">
                        <div class="px-4 py-2.5 flex justify-between items-center" style="border-bottom:1px solid #E4DCC9;background:#F9F6EF;">
                            <span class="text-[10px] font-mono tracking-widest uppercase" style="color:#7A7166;" x-text="query ? 'Results' : 'Popular Packages'"></span>
                            <div x-show="loading" class="animate-spin w-3 h-3 rounded-full border-2 border-[#C8102E] border-t-transparent"></div>
                        </div>
                        <ul class="max-h-80 overflow-y-auto divide-y" style="divide-color:#F4F0E8;">
                            <template x-for="item in suggestions" :key="item.slug">
                                <li @click="selectSuggestion(item.url)" class="flex items-center gap-3 px-4 py-3 cursor-pointer transition hover:bg-[#FAF6EE] group">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0" style="background:#E4DCC9;">
                                        <img :src="item.image" class="w-full h-full object-cover" alt="">
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold truncate group-hover:text-[#C8102E] transition" style="color:#18130E;" x-text="item.text"></p>
                                        <p class="text-xs truncate" style="color:#7A7166;" x-text="item.subtext"></p>
                                    </div>
                                    <svg class="w-4 h-4 flex-shrink-0 opacity-0 group-hover:opacity-100 transition" style="color:#C8102E;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </li>
                            </template>
                            <li x-show="suggestions.length === 0 && !loading" class="px-4 py-8 text-center text-sm" style="color:#7A7166;">No tours found</li>
                        </ul>
                    </div>
                </div>
                <button type="submit" class="self-end px-6 py-2.5 rounded-lg text-sm font-bold text-white transition hover:opacity-90 whitespace-nowrap" style="background:#C8102E;">
                    Search
                </button>
            </form>
        </div>

        {{-- Visa form --}}
        <div x-show="activeTab === 'visas'" class="relative" style="display:none;">
            <form action="{{ route('visas.index') }}" method="GET" class="flex gap-2">
                <div class="flex-1 relative">
                    <label class="fb-field-label">Which country?</label>
                    <div class="relative mt-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 pointer-events-none" style="color:#7A7166;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <input type="text" name="search" x-model="query" @input="fetchSuggestions()" @focus="fetchSuggestions()"
                               placeholder="e.g. Malaysia, Thailand…"
                               class="fb-input pl-9 w-full" autocomplete="off">
                    </div>
                    <div x-show="showSuggestions && activeTab === 'visas'"
                         x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                         class="absolute top-full left-0 w-full mt-1 rounded-xl overflow-hidden z-50 shadow-2xl"
                         style="background:#fff;border:1px solid #E4DCC9;display:none;">
                        <div class="px-4 py-2.5 flex justify-between items-center" style="border-bottom:1px solid #E4DCC9;background:#F9F6EF;">
                            <span class="text-[10px] font-mono tracking-widest uppercase" style="color:#7A7166;" x-text="query ? 'Results' : 'Popular Visas'"></span>
                            <div x-show="loading" class="animate-spin w-3 h-3 rounded-full border-2 border-[#C8102E] border-t-transparent"></div>
                        </div>
                        <ul class="max-h-80 overflow-y-auto divide-y" style="divide-color:#F4F0E8;">
                            <template x-for="item in suggestions" :key="item.slug">
                                <li @click="selectSuggestion(item.url)" class="flex items-center gap-3 px-4 py-3 cursor-pointer transition hover:bg-[#FAF6EE] group">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden flex items-center justify-center text-xl flex-shrink-0" style="background:#E4DCC9;">
                                        <img x-show="item.image" :src="item.image" class="w-full h-full object-cover" alt="">
                                        <span x-show="!item.image">🌍</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold truncate group-hover:text-[#C8102E] transition" style="color:#18130E;" x-text="item.text"></p>
                                        <p class="text-xs truncate" style="color:#7A7166;" x-text="item.subtext"></p>
                                    </div>
                                    <svg class="w-4 h-4 flex-shrink-0 opacity-0 group-hover:opacity-100 transition" style="color:#C8102E;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </li>
                            </template>
                            <li x-show="suggestions.length === 0 && !loading" class="px-4 py-8 text-center text-sm" style="color:#7A7166;">No visas found</li>
                        </ul>
                    </div>
                </div>
                <button type="submit" class="self-end px-6 py-2.5 rounded-lg text-sm font-bold text-white transition hover:opacity-90 whitespace-nowrap" style="background:#C8102E;">
                    Search
                </button>
            </form>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     PROMO STRIP
═══════════════════════════════════════════════ --}}
<section style="background:#fff;border-bottom:1px solid #E4DCC9;" class="px-5 py-6">
    <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
        $promos = [
            ['icon'=>'💳', 'title'=>'bKash & Nagad', 'sub'=>'0% charge on mobile payments'],
            ['icon'=>'🗓', 'title'=>'EMI Available', 'sub'=>'Up to 12 months, 0% interest'],
            ['icon'=>'📱', 'title'=>'Download App', 'sub'=>'Exclusive app-only deals'],
            ['icon'=>'🏖', 'title'=>"Cox's Bazar Special", 'sub'=>'Lowest package prices guaranteed'],
        ];
        @endphp
        @foreach($promos as $p)
        <div class="flex items-start gap-3 p-4 rounded-xl transition hover:bg-[#FAF6EE]" style="border:1px solid #E4DCC9;">
            <span class="text-2xl flex-shrink-0">{{ $p['icon'] }}</span>
            <div>
                <p class="text-sm font-bold" style="color:#18130E;">{{ $p['title'] }}</p>
                <p class="text-xs mt-0.5" style="color:#7A7166;">{{ $p['sub'] }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     FEATURED PACKAGES
═══════════════════════════════════════════════ --}}
<section style="background:#F9F6EF;" class="px-5 py-16">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="fb-eyebrow mb-2">Curated for you</p>
                <h2 class="fb-serif text-4xl md:text-5xl" style="color:#18130E;">Featured <em>packages.</em></h2>
            </div>
            <a href="{{ route('packages.index') }}" class="hidden md:flex items-center gap-1.5 text-sm font-bold transition hover:gap-3" style="color:#C8102E;">
                View all
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($packages->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($packages as $package)
            <a href="{{ route('packages.show', $package->slug) }}" class="ota-card flex flex-col group overflow-hidden" style="border-radius:14px;">
                <div class="relative overflow-hidden" style="height:200px;">
                    @if($package->thumbnail)
                        <img src="{{ Str::startsWith($package->thumbnail, 'http') ? $package->thumbnail : Storage::url($package->thumbnail) }}"
                             alt="{{ $package->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full" style="background:linear-gradient(135deg,#18130E 0%,#C8102E 100%);"></div>
                    @endif
                    <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(24,19,14,.5),transparent 50%);"></div>
                    @if($package->is_active)
                    <span class="absolute top-3 left-3 ota-tag-red text-[10px]">POPULAR</span>
                    @endif
                    <span class="absolute bottom-3 left-3 text-xs font-mono text-white/80">{{ $package->duration_days ?? '' }} Days</span>
                </div>
                <div class="flex flex-col flex-1 p-4">
                    <p class="text-[11px] font-mono tracking-widest uppercase mb-1" style="color:#7A7166;">{{ $package->location ?? $package->destination ?? '' }}</p>
                    <h3 class="fb-serif text-lg leading-snug mb-3 line-clamp-2 group-hover:text-[#C8102E] transition" style="color:#18130E;">{{ $package->title }}</h3>
                    <div class="mt-auto flex items-end justify-between">
                        <div>
                            <p class="text-[10px] font-bold tracking-widest uppercase" style="color:#7A7166;">From</p>
                            <p class="text-xl font-bold" style="color:#C8102E;">৳{{ number_format($package->price) }}</p>
                            <p class="text-[10px]" style="color:#9CA3AF;">per person</p>
                        </div>
                        <span class="flex items-center gap-1 text-xs font-bold px-3 py-1.5 rounded-lg" style="background:#18130E;color:#FAF6EE;">
                            Book
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('packages.index') }}" class="ota-btn-dark">View All Packages</a>
        </div>
        @else
        <div class="text-center py-12 rounded-2xl" style="background:#FAF6EE;border:1px dashed #E4DCC9;">
            <p class="fb-serif text-xl" style="color:#7A7166;">Packages coming soon.</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     CATEGORY STRIP
═══════════════════════════════════════════════ --}}
<section style="background:#FAF6EE;border-top:1px solid #E4DCC9;border-bottom:1px solid #E4DCC9;" class="px-5 py-8">
    <div class="max-w-6xl mx-auto">
        <p class="fb-eyebrow mb-5">Explore by type</p>
        <div class="flex gap-3 overflow-x-auto pb-2 scrollbar-hide">
            @php
            $cats = [
                ['🏖', 'Beach & Island', 'beach'],
                ['🏔', 'Mountain & Trek', 'mountain'],
                ['🕌', 'Heritage & Culture', 'culture'],
                ['🌿', 'Eco & Wildlife', 'eco'],
                ['❄', 'Winter Special', 'winter'],
                ['💑', 'Honeymoon', 'honeymoon'],
                ['👨‍👩‍👧', 'Family Packages', 'family'],
                ['🎒', 'Budget Trips', 'budget'],
            ];
            @endphp
            @foreach($cats as [$emoji, $label, $type])
            <a href="{{ route('packages.index', ['type' => $type]) }}"
               class="flex-shrink-0 flex flex-col items-center gap-2 px-5 py-4 rounded-2xl text-center transition hover:-translate-y-1 hover:shadow-md"
               style="background:#fff;border:1px solid #E4DCC9;min-width:90px;">
                <span class="text-2xl">{{ $emoji }}</span>
                <span class="text-xs font-semibold leading-tight" style="color:#18130E;">{{ $label }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     VISA SECTION
═══════════════════════════════════════════════ --}}
<section style="background:#18130E;" class="px-5 py-16">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="fb-eyebrow mb-2" style="color:#C8102E;">184 countries covered</p>
                <h2 class="fb-serif text-4xl md:text-5xl" style="color:#FAF6EE;">Visa, <em>handled.</em></h2>
                <p class="mt-2 text-sm" style="color:#7A7166;">94.2% approval rate. Docs checked twice. Submitted on time.</p>
            </div>
            <a href="{{ route('visas.index') }}" class="hidden md:flex items-center gap-1.5 text-sm font-bold transition" style="color:#C8102E;">
                All visas
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if(isset($visas) && $visas->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3 mb-8">
            @foreach($visas->take(12) as $visa)
            <a href="{{ route('visas.show', $visa->slug) }}"
               class="flex flex-col items-center gap-2.5 p-4 rounded-xl text-center transition hover:bg-white/10 group"
               style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);">
                <span class="text-3xl">{{ $visa->flag_emoji ?? '🌍' }}</span>
                <span class="text-xs font-semibold leading-tight group-hover:text-[#C8102E] transition" style="color:#FAF6EE;">{{ $visa->country }}</span>
                @if($visa->processing_time)
                <span class="text-[10px] px-2 py-0.5 rounded-full" style="background:rgba(200,16,46,.2);color:#FFB3C1;">{{ $visa->processing_time }}</span>
                @endif
            </a>
            @endforeach
        </div>
        @else
        <div class="grid grid-cols-3 md:grid-cols-6 gap-3 mb-8">
            @foreach(['🇲🇾 Malaysia','🇹🇭 Thailand','🇦🇪 Dubai','🇸🇬 Singapore','🇮🇩 Bali','🇰🇷 South Korea','🇯🇵 Japan','🇮🇳 India','🇬🇧 UK','🇺🇸 USA','🇨🇦 Canada','🇸🇨 Schengen'] as $item)
            @php [$flag, $country] = explode(' ', $item, 2); @endphp
            <div class="flex flex-col items-center gap-2 p-4 rounded-xl text-center" style="background:rgba(255,255,255,.06);border:1px solid rgba(255,255,255,.08);">
                <span class="text-2xl">{{ $flag }}</span>
                <span class="text-xs font-semibold" style="color:#FAF6EE;">{{ $country }}</span>
            </div>
            @endforeach
        </div>
        @endif

        <div class="flex flex-wrap gap-3">
            <a href="{{ route('visas.index') }}" class="ota-btn-primary">Apply for Visa</a>
            <a href="{{ route('contact') }}" class="ota-btn-ghost" style="border-color:#FAF6EE33;color:#FAF6EE;">Free consultation</a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     STATS BAND
═══════════════════════════════════════════════ --}}
<section style="background:#C8102E;" class="px-5 py-10">
    <div class="max-w-6xl mx-auto grid grid-cols-2 md:grid-cols-5 gap-6 text-center">
        @php
        $stats = [
            ['1.2M+', 'Travellers served'],
            ['62', 'Destinations'],
            ['4.8★', 'Average rating'],
            ['94.2%', 'Visa approval rate'],
            ['24/7', 'Customer support'],
        ];
        @endphp
        @foreach($stats as [$num, $label])
        <div>
            <p class="fb-serif text-3xl md:text-4xl" style="color:#FAF6EE;">{{ $num }}</p>
            <p class="text-xs font-mono tracking-widest uppercase mt-1" style="color:rgba(250,246,238,.6);">{{ $label }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- ═══════════════════════════════════════════════
     BLOG SECTION
═══════════════════════════════════════════════ --}}
@if(isset($recentPosts) && $recentPosts->count() > 0)
<section style="background:#F9F6EF;" class="px-5 py-16">
    <div class="max-w-6xl mx-auto">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="fb-eyebrow mb-2">From the journal</p>
                <h2 class="fb-serif text-4xl md:text-5xl" style="color:#18130E;">Travel <em>stories.</em></h2>
            </div>
            <a href="{{ route('blog.index') }}" class="hidden md:flex items-center gap-1.5 text-sm font-bold transition hover:gap-3" style="color:#C8102E;">
                All articles
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($recentPosts as $post)
            <article class="ota-card flex flex-col group overflow-hidden" style="border-radius:14px;">
                <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden relative" style="height:190px;">
                    @if($post->image)
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#18130E,#C8102E);">
                            <svg width="28" height="28" viewBox="0 0 32 32" fill="none"><path d="M9 22 L16 9 L23 22 L20 22 L18 18 L14 18 L12 22 Z" fill="#FAF6EE"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(24,19,14,.4),transparent);"></div>
                </a>
                <div class="flex flex-col flex-1 p-5">
                    <p class="text-[11px] font-mono tracking-widest uppercase mb-2" style="color:#7A7166;">
                        {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}
                    </p>
                    <h3 class="fb-serif text-lg leading-snug line-clamp-2 mb-3 group-hover:text-[#C8102E] transition" style="color:#18130E;">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h3>
                    <p class="text-sm line-clamp-2 flex-1" style="color:#7A7166;">
                        {{ $post->seo_description ?? Str::limit(strip_tags($post->content), 90) }}
                    </p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="flex items-center gap-1 mt-4 text-xs font-bold transition hover:gap-2" style="color:#C8102E;">
                        Read more <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('blog.index') }}" class="ota-btn-dark">Read More Articles</a>
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════════════
     CTA BAND
═══════════════════════════════════════════════ --}}
<section style="background:#FAF6EE;border-top:1px solid #E4DCC9;" class="px-5 py-14 text-center">
    <p class="fb-eyebrow mb-3">Start planning today</p>
    <h2 class="fb-serif text-4xl md:text-5xl mb-6" style="color:#18130E;">Your next trip is <em>one click away.</em></h2>
    <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ route('packages.index') }}" class="ota-btn-primary">Browse All Packages</a>
        <a href="https://wa.me/8801XXXXXXXXX" target="_blank" class="ota-btn-ghost">
            💬 WhatsApp Us
        </a>
    </div>
</section>

</x-app-layout>
