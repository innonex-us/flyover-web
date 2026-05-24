<x-app-layout>

{{-- ════════════════════════════════════════════
     HERO — full-bleed banner + search widget
════════════════════════════════════════════ --}}
<section class="relative overflow-hidden" style="min-height:520px;"
    x-data="{ s:0, init(){ setInterval(()=>{ this.s=(this.s+1)%3 },5000) } }">

    {{-- Slides --}}
    <div class="absolute inset-0 transition-opacity duration-1000" :class="s===0?'opacity-100':'opacity-0'"
         style="background:url('{{ asset('banner/hero-banner-1.png') }}') center/cover no-repeat;"></div>
    <div class="absolute inset-0 transition-opacity duration-1000" :class="s===1?'opacity-100':'opacity-0'"
         style="background:url('{{ asset('banner/helo-banner-2.png') }}') center/cover no-repeat;"></div>
    <div class="absolute inset-0 transition-opacity duration-1000" :class="s===2?'opacity-100':'opacity-0'"
         style="background:url('{{ asset('banner/hero-banner-3.png') }}') center/cover no-repeat;"></div>
    <div class="absolute inset-0" style="background:linear-gradient(to bottom, rgba(0,0,0,.52) 0%, rgba(0,0,0,.35) 60%, rgba(0,0,0,.6) 100%);"></div>

    {{-- Hero text + search --}}
    <div class="relative z-10 flex flex-col items-center justify-center text-center px-4 pt-20 pb-10" style="min-height:520px;">
        <p class="text-xs font-semibold tracking-widest uppercase text-white/70 mb-3">Bangladesh's #1 Travel Agency</p>
        <h1 class="text-4xl md:text-6xl font-bold text-white mb-3 leading-tight drop-shadow-lg" style="font-family:'Instrument Serif',serif;">
            Explore the World<br>with <span style="color:#ff6b6b;">FlyoverBD</span>
        </h1>
        <p class="text-base text-white/80 mb-10 max-w-lg">Tours, visa services, hotels &amp; airport pick &amp; drop — all in one place.</p>

        {{-- ── Search Widget ── --}}
        <div class="w-full max-w-3xl bg-white rounded-2xl shadow-2xl overflow-hidden"
             x-data="{
                 activeTab: 'tours',
                 query: '', suggestions: [], showSuggestions: false, loading: false, fetchTimer: null,
                 fetchSuggestions() {
                     this.loading = true;
                     clearTimeout(this.fetchTimer);
                     this.fetchTimer = setTimeout(() => {
                         fetch(`{{ route('search.suggestions') }}?type=${this.activeTab}&query=${encodeURIComponent(this.query)}`)
                             .then(r=>r.json()).then(d=>{ this.suggestions=d; this.showSuggestions=true; this.loading=false; })
                             .catch(()=>{ this.loading=false; });
                     }, 280);
                 },
                 selectSuggestion(url){ window.location.href=url; },
                 switchTab(tab){ this.activeTab=tab; this.query=''; this.suggestions=[]; this.showSuggestions=false; }
             }"
             @click.away="showSuggestions=false">

            {{-- Tabs --}}
            <div class="flex border-b border-gray-100">
                <button @click="switchTab('tours')"
                        class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition border-b-2"
                        :class="activeTab==='tours' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tour Packages
                </button>
                <button @click="switchTab('visas')"
                        class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition border-b-2"
                        :class="activeTab==='visas' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    Visa Services
                </button>
                <button @click="switchTab('hotels')"
                        class="flex items-center gap-2 px-6 py-4 text-sm font-semibold transition border-b-2"
                        :class="activeTab==='hotels' ? 'border-red-600 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Hotels
                </button>
            </div>

            {{-- Tour search --}}
            <div x-show="activeTab==='tours'" class="p-5 relative">
                <form action="{{ route('packages.index') }}" method="GET" class="flex gap-3">
                    <div class="flex-1 relative">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Destination / Tour</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" x-model="query"
                                   @input="fetchSuggestions()" @focus="fetchSuggestions()"
                                   placeholder="Where do you want to go?"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition"
                                   autocomplete="off">
                        </div>
                        {{-- Suggestions dropdown --}}
                        <div x-show="showSuggestions && suggestions.length > 0"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute top-full left-0 right-0 mt-1 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden"
                             style="display:none;">
                            <div class="px-4 py-2 bg-gray-50 border-b border-gray-100 flex items-center justify-between">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="query ? 'Results' : 'Popular Tours'"></span>
                                <div x-show="loading" class="w-3 h-3 border-2 border-red-500 border-t-transparent rounded-full animate-spin"></div>
                            </div>
                            <ul class="max-h-64 overflow-y-auto">
                                <template x-for="item in suggestions" :key="item.slug">
                                    <li @click="selectSuggestion(item.url)"
                                        class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-red-50 transition group">
                                        <img :src="item.image" class="w-10 h-10 object-cover rounded-lg flex-shrink-0" alt="">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 truncate group-hover:text-red-600" x-text="item.text"></p>
                                            <p class="text-xs text-gray-400 truncate" x-text="item.subtext"></p>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition text-sm whitespace-nowrap shadow-sm">
                            Search Tours
                        </button>
                    </div>
                </form>
            </div>

            {{-- Visa search --}}
            <div x-show="activeTab==='visas'" class="p-5 relative" style="display:none;">
                <form action="{{ route('visas.index') }}" method="GET" class="flex gap-3">
                    <div class="flex-1 relative">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Destination Country</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <input type="text" name="search" x-model="query"
                                   @input="fetchSuggestions()" @focus="fetchSuggestions()"
                                   placeholder="Which country visa do you need?"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition"
                                   autocomplete="off">
                        </div>
                        <div x-show="showSuggestions && suggestions.length > 0"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 -translate-y-1"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="absolute top-full left-0 right-0 mt-1 bg-white rounded-xl shadow-2xl border border-gray-100 z-50 overflow-hidden"
                             style="display:none;">
                            <ul class="max-h-64 overflow-y-auto">
                                <template x-for="item in suggestions" :key="item.slug">
                                    <li @click="selectSuggestion(item.url)"
                                        class="flex items-center gap-3 px-4 py-3 cursor-pointer hover:bg-red-50 transition group">
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center text-xl flex-shrink-0">
                                            <img x-show="item.image" :src="item.image" class="w-full h-full object-cover rounded-lg" alt="">
                                            <span x-show="!item.image">🌍</span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-800 truncate group-hover:text-red-600" x-text="item.text"></p>
                                            <p class="text-xs text-gray-400 truncate" x-text="item.subtext"></p>
                                        </div>
                                    </li>
                                </template>
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition text-sm shadow-sm">
                            Find Visa
                        </button>
                    </div>
                </form>
            </div>

            {{-- Hotels --}}
            <div x-show="activeTab==='hotels'" class="p-5" style="display:none;">
                <div class="flex gap-3">
                    <div class="flex-1">
                        <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">City / Hotel</label>
                        <div class="relative">
                            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <input type="text" placeholder="Where are you going?" class="w-full pl-10 pr-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100 transition">
                        </div>
                    </div>
                    <div class="flex items-end">
                        <a href="{{ route('packages.index') }}" class="px-8 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition text-sm shadow-sm">
                            Search Hotels
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Trust indicators --}}
        <div class="flex flex-wrap justify-center gap-6 mt-8 text-white/70 text-xs">
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                1.2M+ Happy Travellers
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                62 Destinations
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                94.2% Visa Approval
            </span>
            <span class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 text-green-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                24/7 Support
            </span>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     PROMO STRIP
════════════════════════════════════════════ --}}
<section class="bg-white border-b border-gray-100">
    <div class="max-w-6xl mx-auto px-4 py-5 grid grid-cols-2 md:grid-cols-4 divide-x divide-gray-100">
        @php $promos = [
            ['💳','bKash / Nagad','0% extra charge on mobile payments'],
            ['🗓','Easy EMI','Up to 12 months, 0% interest'],
            ['📱','App Exclusive','Download app for special deals'],
            ['🏖',"Cox's Bazar Deal",'Guaranteed lowest package price'],
        ]; @endphp
        @foreach($promos as [$icon,$title,$sub])
        <div class="flex items-center gap-3 px-5 py-3">
            <span class="text-2xl flex-shrink-0">{{ $icon }}</span>
            <div>
                <p class="text-sm font-semibold text-gray-800">{{ $title }}</p>
                <p class="text-xs text-gray-500 mt-0.5">{{ $sub }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- ════════════════════════════════════════════
     FEATURED PACKAGES
════════════════════════════════════════════ --}}
<section class="bg-gray-50 py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">Handpicked for you</p>
                <h2 class="text-3xl font-bold text-gray-900">Featured Tour Packages</h2>
            </div>
            <a href="{{ route('packages.index') }}" class="hidden md:inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-700 transition">
                View all packages
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($packages->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($packages as $package)
            <a href="{{ route('packages.show', $package->slug) }}"
               class="bg-white rounded-2xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-200 group flex flex-col">
                <div class="relative overflow-hidden" style="height:190px;">
                    @if($package->thumbnail)
                        <img src="{{ Str::startsWith($package->thumbnail,'http') ? $package->thumbnail : Storage::url($package->thumbnail) }}"
                             alt="{{ $package->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#1a1a2e 0%,#C8102E 100%);">
                            <svg width="40" height="40" viewBox="0 0 40 40" fill="none"><path d="M10 28 L20 10 L30 28 L26 28 L23 22 L17 22 L14 28 Z" fill="rgba(255,255,255,0.4)"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,.55) 0%, transparent 55%);"></div>
                    @if($package->is_active)
                    <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-2 py-0.5 rounded">POPULAR</span>
                    @endif
                    @if($package->duration_days)
                    <span class="absolute bottom-3 left-3 text-white text-xs font-medium">{{ $package->duration_days }} Days</span>
                    @endif
                </div>
                <div class="p-4 flex flex-col flex-1">
                    <p class="text-xs text-gray-400 font-medium mb-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $package->location ?? $package->destination ?? 'International' }}
                    </p>
                    <h3 class="text-sm font-bold text-gray-900 line-clamp-2 mb-3 group-hover:text-red-600 transition flex-1">{{ $package->title }}</h3>
                    <div class="flex items-end justify-between mt-auto pt-3 border-t border-gray-50">
                        <div>
                            <p class="text-[10px] text-gray-400 uppercase font-semibold">Starting from</p>
                            <p class="text-xl font-bold text-red-600">৳{{ number_format($package->price) }}</p>
                            <p class="text-[10px] text-gray-400">per person</p>
                        </div>
                        <span class="text-xs font-semibold text-white bg-gray-900 px-3 py-1.5 rounded-lg">Book Now</span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
        <div class="mt-8 text-center md:hidden">
            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white font-semibold rounded-xl text-sm">
                View All Packages
            </a>
        </div>
        @else
        <div class="text-center py-16 bg-white rounded-2xl border border-dashed border-gray-200">
            <p class="text-gray-400 text-lg">Tour packages coming soon.</p>
        </div>
        @endif
    </div>
</section>

{{-- ════════════════════════════════════════════
     POPULAR DESTINATIONS STRIP
════════════════════════════════════════════ --}}
<section class="bg-white py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Popular Destinations</h2>
            <a href="{{ route('packages.index') }}" class="text-sm text-red-600 font-semibold hover:underline">Explore all</a>
        </div>
        <div class="flex gap-3 overflow-x-auto pb-2" style="scrollbar-width:none;">
            @php $destinations = [
                ['🏖','Cox\'s Bazar','Bangladesh','beach'],
                ['🏔','Bandarban','Bangladesh','mountain'],
                ['🌊','Sundarbans','Bangladesh','eco'],
                ['🕌','Dhaka','Bangladesh','culture'],
                ['🏝','Maldives','Indian Ocean','beach'],
                ['🌆','Bangkok','Thailand','city'],
                ['🏙','Singapore','Singapore','city'],
                ['🗼','Dubai','UAE','city'],
                ['🌿','Bali','Indonesia','nature'],
                ['🍜','Kuala Lumpur','Malaysia','city'],
            ]; @endphp
            @foreach($destinations as [$emoji,$name,$country,$type])
            <a href="{{ route('packages.index', ['search'=>$name]) }}"
               class="flex-shrink-0 text-center group cursor-pointer">
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center text-3xl mb-2 transition-all duration-200 group-hover:scale-105 group-hover:shadow-md"
                     style="background:linear-gradient(135deg,#FFF5F5,#FEE2E2);">
                    {{ $emoji }}
                </div>
                <p class="text-xs font-semibold text-gray-700 group-hover:text-red-600 transition">{{ $name }}</p>
                <p class="text-[10px] text-gray-400">{{ $country }}</p>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     VISA SERVICES
════════════════════════════════════════════ --}}
<section class="bg-gray-50 py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">184 countries</p>
                <h2 class="text-3xl font-bold text-gray-900">Visa Processing Services</h2>
            </div>
            <a href="{{ route('visas.index') }}" class="hidden md:inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-700 transition">
                All visa services
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if(isset($visas) && $visas->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
            @foreach($visas->take(12) as $visa)
            <a href="{{ route('visas.show', $visa->slug) }}"
               class="bg-white border border-gray-100 rounded-2xl p-4 text-center hover:border-red-200 hover:shadow-md transition-all group">
                <span class="text-3xl block mb-2">{{ $visa->flag_emoji ?? '🌍' }}</span>
                <p class="text-sm font-semibold text-gray-800 group-hover:text-red-600 transition leading-tight">{{ $visa->country }}</p>
                @if($visa->processing_time)
                <p class="text-[10px] text-gray-400 mt-1">{{ $visa->processing_time }}</p>
                @endif
                @if($visa->fee)
                <p class="text-xs font-bold text-red-600 mt-1.5">৳{{ number_format($visa->fee) }}</p>
                @endif
            </a>
            @endforeach
        </div>
        @else
        {{-- Fallback static visa grid --}}
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-4">
            @foreach([['🇲🇾','Malaysia','3-5 days'],['🇹🇭','Thailand','3-5 days'],['🇦🇪','Dubai','3-7 days'],['🇸🇬','Singapore','5-7 days'],['🇮🇩','Indonesia','3-5 days'],['🇯🇵','Japan','5-7 days'],['🇰🇷','South Korea','5-7 days'],['🇬🇧','UK','10-15 days'],['🇺🇸','USA','15-30 days'],['🇨🇦','Canada','20-30 days'],['🇮🇳','India','2-3 days'],['🌍','Schengen','7-15 days']] as [$flag,$name,$time])
            <div class="bg-white border border-gray-100 rounded-2xl p-4 text-center hover:border-red-200 hover:shadow-sm transition">
                <span class="text-3xl block mb-2">{{ $flag }}</span>
                <p class="text-xs font-semibold text-gray-800">{{ $name }}</p>
                <p class="text-[10px] text-gray-400 mt-1">{{ $time }}</p>
            </div>
            @endforeach
        </div>
        @endif

        <div class="mt-8 flex flex-wrap gap-3 items-center justify-between p-6 bg-white rounded-2xl border border-gray-100">
            <div>
                <p class="text-base font-bold text-gray-900">94.2% visa approval rate</p>
                <p class="text-sm text-gray-500">Documents verified twice. Submitted on time. Every time.</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('visas.index') }}" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl text-sm transition">Apply Now</a>
                <a href="{{ route('contact') }}" class="px-5 py-2.5 border border-gray-200 hover:border-red-300 text-gray-700 font-semibold rounded-xl text-sm transition">Free Consult</a>
            </div>
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     STATS BAND
════════════════════════════════════════════ --}}
<section class="bg-red-600 py-12">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-2 md:grid-cols-5 gap-6 text-center">
        @foreach([['1.2M+','Travellers Served'],['62','Destinations'],['4.8★','Avg. Rating'],['94.2%','Visa Approval'],['24/7','Support']] as [$num,$label])
        <div>
            <p class="text-3xl md:text-4xl font-bold text-white">{{ $num }}</p>
            <p class="text-xs text-red-200 font-medium uppercase tracking-widest mt-1">{{ $label }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- ════════════════════════════════════════════
     WHY CHOOSE US
════════════════════════════════════════════ --}}
<section class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-10">
            <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-2">Why FlyoverBD</p>
            <h2 class="text-3xl font-bold text-gray-900">Travel with confidence</h2>
            <p class="text-gray-500 mt-2 max-w-xl mx-auto">We handle every detail so your journey is smooth from start to finish.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php $features = [
                ['🛡','Best Price Guarantee','We match any lower price you find within 24 hours.'],
                ['📋','Expert Visa Support','97% of our visa applications are approved first time.'],
                ['🚗','Airport Transfers','On-time pick & drop to 20+ airports across Bangladesh.'],
                ['💬','24/7 Human Support','Real people on WhatsApp and phone — always.'],
            ]; @endphp
            @foreach($features as [$icon,$title,$desc])
            <div class="p-6 rounded-2xl border border-gray-100 hover:border-red-100 hover:shadow-sm transition text-center">
                <div class="text-3xl mb-4">{{ $icon }}</div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ════════════════════════════════════════════
     BLOG SECTION
════════════════════════════════════════════ --}}
@if(isset($recentPosts) && $recentPosts->count() > 0)
<section class="bg-gray-50 py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-xs font-bold text-red-600 uppercase tracking-widest mb-1">Travel journal</p>
                <h2 class="text-3xl font-bold text-gray-900">Latest Travel Insights</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="hidden md:inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-700 transition">
                Read all articles
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($recentPosts as $post)
            <article class="bg-white rounded-2xl overflow-hidden border border-gray-100 hover:shadow-lg hover:-translate-y-1 transition-all duration-200 group flex flex-col">
                <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden relative" style="height:195px;">
                    @if($post->image)
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition duration-500" loading="lazy">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#1a1a2e 0%,#C8102E 100%);">
                            <svg width="32" height="32" viewBox="0 0 40 40" fill="none"><path d="M10 28 L20 10 L30 28 L26 28 L23 22 L17 22 L14 28 Z" fill="rgba(255,255,255,0.4)"/></svg>
                        </div>
                    @endif
                    <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,.35),transparent);"></div>
                </a>
                <div class="p-5 flex flex-col flex-1">
                    <p class="text-xs text-gray-400 mb-2 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}
                    </p>
                    <h3 class="font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-red-600 transition flex-1 leading-snug">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h3>
                    <p class="text-sm text-gray-500 line-clamp-2 mb-4">
                        {{ $post->seo_description ?? Str::limit(strip_tags($post->content), 95) }}
                    </p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1 text-sm font-semibold text-red-600 hover:gap-2 transition-all">
                        Read article
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ════════════════════════════════════════════
     BOTTOM CTA
════════════════════════════════════════════ --}}
<section class="bg-gray-900 py-16 text-center">
    <div class="max-w-2xl mx-auto px-4">
        <p class="text-xs font-bold text-red-400 uppercase tracking-widest mb-3">Ready to travel?</p>
        <h2 class="text-4xl font-bold text-white mb-4" style="font-family:'Instrument Serif',serif;">
            Your next adventure<br>starts here.
        </h2>
        <p class="text-gray-400 mb-8">Talk to our travel experts — free consultation, no obligations.</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('packages.index') }}" class="px-7 py-3.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl transition text-sm">
                Browse Packages
            </a>
            <a href="https://wa.me/8801XXXXXXXXX" target="_blank"
               class="px-7 py-3.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl transition text-sm border border-white/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                WhatsApp Us
            </a>
        </div>
    </div>
</section>

</x-app-layout>
