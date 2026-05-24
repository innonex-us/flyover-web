<x-app-layout>

{{-- ═══════════════════════════════════════
     HERO  — image slider + search card
═══════════════════════════════════════ --}}
<div class="relative" style="height:580px;"
     x-data="{ s:0, init(){ setInterval(()=>this.s=(this.s+1)%3, 5500) } }">

    {{-- Slides --}}
    <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000"
         style="background-image:url('{{ asset('banner/hero-banner-1.png') }}')"
         :class="s===0?'opacity-100':'opacity-0'"></div>
    <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000"
         style="background-image:url('{{ asset('banner/helo-banner-2.png') }}')"
         :class="s===1?'opacity-100':'opacity-0'"></div>
    <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000"
         style="background-image:url('{{ asset('banner/hero-banner-3.png') }}')"
         :class="s===2?'opacity-100':'opacity-0'"></div>

    {{-- Gradient overlay --}}
    <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(0,0,0,.45) 0%,rgba(0,0,0,.3) 50%,rgba(0,0,0,.65) 100%);"></div>

    {{-- Content --}}
    <div class="relative z-10 h-full flex flex-col items-center justify-center px-4 text-center">
        <h1 class="text-white font-extrabold text-4xl md:text-6xl leading-tight mb-3 drop-shadow-xl"
            style="font-family:'Merriweather',Georgia,serif;">
            Discover <span class="text-red-400"> Beyond</span>
        </h1>
        <p class="text-white/80 text-lg mb-10 max-w-xl">Tours · Visa · Hotels · Airport Transfers. All in one trusted place.</p>

        {{-- ── Search Widget ── --}}
        <div class="w-full max-w-2xl"
             x-data="{
                 tab:'tours', query:'', suggestions:[], show:false, loading:false, timer:null,
                 fetch() {
                     this.loading=true; clearTimeout(this.timer);
                     this.timer=setTimeout(()=>{
                         window.fetch(`{{ route('search.suggestions') }}?type=${this.tab}&query=${encodeURIComponent(this.query)}`)
                             .then(r=>r.json()).then(d=>{ this.suggestions=d; this.show=true; this.loading=false; })
                             .catch(()=>this.loading=false);
                     },280);
                 },
                 go(url){ window.location.href=url; },
                 reset(t){ this.tab=t; this.query=''; this.suggestions=[]; this.show=false; }
             }"
             @click.away="show=false">

            {{-- Tab row --}}
            <div class="flex justify-center gap-2 mb-3">
                <button @click="reset('tours')"
                        class="px-5 py-2 rounded-full text-sm font-semibold transition-all"
                        :class="tab==='tours'?'bg-red-600 text-white shadow-lg':'bg-white/20 text-white hover:bg-white/30 backdrop-blur-sm'">
                    ✈ Tour Packages
                </button>
                <button @click="reset('visas')"
                        class="px-5 py-2 rounded-full text-sm font-semibold transition-all"
                        :class="tab==='visas'?'bg-red-600 text-white shadow-lg':'bg-white/20 text-white hover:bg-white/30 backdrop-blur-sm'">
                    🛂 Visa Services
                </button>
                <button @click="reset('hotels')"
                        class="px-5 py-2 rounded-full text-sm font-semibold transition-all"
                        :class="tab==='hotels'?'bg-red-600 text-white shadow-lg':'bg-white/20 text-white hover:bg-white/30 backdrop-blur-sm'">
                    🏨 Hotels
                </button>
            </div>

            {{-- Search box --}}
            <div class="bg-white rounded-2xl shadow-2xl p-2 flex gap-2 items-center relative">
                {{-- Tours --}}
                <template x-if="tab==='tours'">
                    <form action="{{ route('packages.index') }}" method="GET" class="flex gap-2 w-full">
                        <div class="flex-1 relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" name="search" x-model="query" @input="fetch()" @focus="fetch()"
                                   placeholder="Search tour packages, destinations…"
                                   class="w-full pl-11 pr-4 py-3.5 text-gray-800 text-sm outline-none rounded-xl"
                                   autocomplete="off">
                        </div>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition whitespace-nowrap">
                            Search
                        </button>
                    </form>
                </template>
                {{-- Visas --}}
                <template x-if="tab==='visas'">
                    <form action="{{ route('visas.index') }}" method="GET" class="flex gap-2 w-full">
                        <div class="flex-1 relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <input type="text" name="search" x-model="query" @input="fetch()" @focus="fetch()"
                                   placeholder="Malaysia, Thailand, Schengen…"
                                   class="w-full pl-11 pr-4 py-3.5 text-gray-800 text-sm outline-none rounded-xl"
                                   autocomplete="off">
                        </div>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition whitespace-nowrap">
                            Find Visa
                        </button>
                    </form>
                </template>
                {{-- Hotels --}}
                <template x-if="tab==='hotels'">
                    <form action="{{ route('hotels.index') }}" method="GET" class="flex gap-2 w-full">
                        <div class="flex-1 relative">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            <input type="text" name="search" x-model="query" @input="fetch()" @focus="fetch()"
                                   placeholder="Cox's Bazar, Dhaka, Sylhet…"
                                   class="w-full pl-11 pr-4 py-3.5 text-gray-800 text-sm outline-none rounded-xl"
                                   autocomplete="off">
                        </div>
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-6 py-3.5 rounded-xl text-sm transition whitespace-nowrap">
                            Find Hotel
                        </button>
                    </form>
                </template>

                {{-- Autocomplete dropdown (outside form so it overlays) --}}
                <div x-show="show && suggestions.length > 0"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50"
                     style="display:none;">
                    <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 border-b border-gray-100">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="query?'Search results':'Popular'"></span>
                        <div x-show="loading" class="w-3 h-3 border-2 border-red-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <ul class="max-h-72 overflow-y-auto">
                        <template x-for="item in suggestions" :key="item.slug">
                            <li @click="go(item.url)" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 cursor-pointer transition group">
                                <img :src="item.image" alt="" class="w-11 h-11 object-cover rounded-xl flex-shrink-0 bg-gray-100">
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-gray-800 truncate group-hover:text-red-600" x-text="item.text"></p>
                                    <p class="text-xs text-gray-400 truncate" x-text="item.subtext"></p>
                                </div>
                                <svg class="w-4 h-4 text-gray-300 group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="flex flex-wrap justify-center gap-2 mt-4">
                @foreach(["Cox's Bazar",'Maldives','Thailand','Malaysia','Schengen Visa','Dubai Visa'] as $q)
                <a href="{{ route('packages.index', ['search'=>$q]) }}"
                   class="text-xs text-white/80 hover:text-white bg-white/10 hover:bg-white/20 backdrop-blur-sm px-3 py-1 rounded-full transition">
                    {{ $q }}
                </a>
                @endforeach
            </div>
        </div>
    </div>

    {{-- Slide dots --}}
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
        @foreach([0,1,2] as $i)
        <button @click="s={{ $i }}" class="w-2 h-2 rounded-full transition-all" :class="s==={{ $i }}?'bg-white w-6':'bg-white/40'"></button>
        @endforeach
    </div>
</div>

{{-- ═══════════════════════════════════════
     TRUST BAR
═══════════════════════════════════════ --}}
<div class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-5xl mx-auto px-4 py-4 flex flex-wrap items-center justify-center gap-8 text-sm">
        <div class="flex items-center gap-2.5 text-gray-600">
            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <span><strong class="text-gray-900">1.2M+</strong> Happy Travellers</span>
        </div>
        <div class="flex items-center gap-2.5 text-gray-600">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span><strong class="text-gray-900">62</strong> Destinations</span>
        </div>
        <div class="flex items-center gap-2.5 text-gray-600">
            <div class="w-8 h-8 rounded-full bg-yellow-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
            <span><strong class="text-gray-900">4.8★</strong> Rating</span>
        </div>
        <div class="flex items-center gap-2.5 text-gray-600">
            <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <span><strong class="text-gray-900">94.2%</strong> Visa Approval</span>
        </div>
        <div class="flex items-center gap-2.5 text-gray-600">
            <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <span><strong class="text-gray-900">24/7</strong> Support</span>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════
     FEATURED PACKAGES
═══════════════════════════════════════ --}}
<section class="bg-gray-50 py-14">
    <div class="max-w-6xl mx-auto px-4">

        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="section-eyebrow mb-1.5">Handpicked for you</p>
                <h2 class="text-3xl font-extrabold text-gray-900">Featured Tour Packages</h2>
            </div>
            <a href="{{ route('packages.index') }}" class="hidden md:flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-700">
                View all <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($packages->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach($packages as $package)
            <a href="{{ route('packages.show', $package->slug) }}" class="travel-card flex flex-col group">
                {{-- Image --}}
                <div class="relative overflow-hidden" style="height:200px;">
                    @if($package->thumbnail)
                        <img src="{{ Str::startsWith($package->thumbnail,'http') ? $package->thumbnail : Storage::url($package->thumbnail) }}"
                             alt="{{ $package->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        {{-- Styled gradient placeholder --}}
                        @php $colors = [['#667eea','#764ba2'],['#f093fb','#f5576c'],['#4facfe','#00f2fe'],['#43e97b','#38f9d7'],['#fa709a','#fee140'],['#a18cd1','#fbc2eb']]; $c=$colors[$loop->index % count($colors)]; @endphp
                        <div class="w-full h-full flex flex-col items-center justify-center" style="background:linear-gradient(135deg,{{ $c[0] }},{{ $c[1] }});">
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" class="opacity-40 mb-2"><path d="M12 34 L24 12 L36 34 L31 34 L28 28 L20 28 L17 34 Z" fill="white"/></svg>
                            <span class="text-white/60 text-xs font-medium">{{ $package->destination ?? $package->location ?? 'Destination' }}</span>
                        </div>
                    @endif
                    <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,.6) 0%,transparent 50%);"></div>
                    @if($package->is_active)
                    <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">POPULAR</span>
                    @endif
                    @if($package->duration_days)
                    <span class="absolute bottom-3 right-3 bg-black/50 text-white text-[11px] font-semibold px-2 py-0.5 rounded-lg backdrop-blur-sm">
                        {{ $package->duration_days }}D / {{ $package->duration_days - 1 }}N
                    </span>
                    @endif
                </div>
                {{-- Info --}}
                <div class="p-4 flex flex-col flex-1">
                    <div class="flex items-center gap-1 text-xs text-gray-400 font-medium mb-1">
                        <svg class="w-3.5 h-3.5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        {{ $package->location ?? $package->destination ?? 'International' }}
                    </div>
                    <h3 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2 mb-3 group-hover:text-red-600 transition flex-1">
                        {{ $package->title }}
                    </h3>
                    <div class="flex items-center justify-between border-t border-gray-50 pt-3">
                        <div>
                            <span class="text-[10px] text-gray-400 block">Starting from</span>
                            <span class="text-lg font-extrabold text-red-600">৳{{ number_format($package->price) }}</span>
                            <span class="text-[10px] text-gray-400 block">per person</span>
                        </div>
                        <span class="bg-gray-900 text-white text-xs font-semibold px-3 py-2 rounded-lg group-hover:bg-red-600 transition">
                            Book Now
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-8 md:hidden">
            <a href="{{ route('packages.index') }}" class="btn-primary">View All Packages</a>
        </div>

        @else
        <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-gray-400 font-medium">Tour packages coming soon</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════
     POPULAR DESTINATIONS
═══════════════════════════════════════ --}}
<section class="bg-white py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-extrabold text-gray-900">Popular Destinations</h2>
            <a href="{{ route('packages.index') }}" class="text-sm font-semibold text-red-600 hover:underline">Explore all</a>
        </div>
        <div class="grid grid-cols-3 sm:grid-cols-5 lg:grid-cols-10 gap-3">
            @php $dests = [
                ["Cox's Bazar",'BD','#3b82f6'],['Bandarban','BD','#10b981'],['Sylhet','BD','#8b5cf6'],
                ['Maldives','MV','#06b6d4'],['Bangkok','TH','#f59e0b'],['Bali','ID','#ef4444'],
                ['Singapore','SG','#64748b'],['Dubai','AE','#d97706'],['Kuala Lumpur','MY','#7c3aed'],['Japan','JP','#ec4899'],
            ]; @endphp
            @foreach($dests as [$name,$code,$color])
            <a href="{{ route('packages.index', ['search'=>$name]) }}"
               class="flex flex-col items-center gap-2 p-3 rounded-2xl hover:bg-gray-50 transition group text-center">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center font-bold text-white text-lg shadow-sm transition group-hover:scale-110"
                     style="background:{{ $color }};">
                    {{ $code }}
                </div>
                <span class="text-xs font-semibold text-gray-700 group-hover:text-red-600 transition leading-tight">{{ $name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     PAYMENT / OFFER BANNERS
═══════════════════════════════════════ --}}
<section class="bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm">bKash &amp; Nagad</p>
                <p class="text-xs text-gray-500 mt-0.5">0% charge on all mobile payments</p>
            </div>
        </div>
        <div class="flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm">Easy EMI</p>
                <p class="text-xs text-gray-500 mt-0.5">Up to 12 months, 0% interest rate</p>
            </div>
        </div>
        <div class="flex items-center gap-4 bg-gradient-to-r from-red-600 to-red-700 rounded-2xl p-5 shadow-sm text-white">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="font-bold text-sm">Download Our App</p>
                <p class="text-xs text-white/80 mt-0.5">Exclusive app-only deals &amp; discounts</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     VISA SERVICES
═══════════════════════════════════════ --}}
<section class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="section-eyebrow mb-1.5">184 countries covered</p>
                <h2 class="text-3xl font-extrabold text-gray-900">Visa Processing Services</h2>
                <p class="text-gray-500 mt-1 text-sm">94.2% approval rate · Hassle-free documentation</p>
            </div>
            <a href="{{ route('visas.index') }}" class="hidden md:flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-700">
                All visa services <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if(isset($visas) && $visas->count() > 0)
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
            @foreach($visas->take(12) as $visa)
            <a href="{{ route('visas.show', $visa->slug) }}" class="visa-card group">
                <div class="text-3xl mb-2 block">{{ $visa->flag_emoji ?? '🌍' }}</div>
                <p class="text-xs font-bold text-gray-800 group-hover:text-red-600 transition leading-tight">{{ $visa->country }}</p>
                @if($visa->processing_time)
                <p class="text-[10px] text-gray-400 mt-1">{{ $visa->processing_time }}</p>
                @endif
                @if($visa->fee)
                <p class="text-xs font-bold text-red-600 mt-1">৳{{ number_format($visa->fee) }}</p>
                @endif
            </a>
            @endforeach
        </div>
        @else
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
            @foreach([['🇲🇾','Malaysia','3-5 days'],['🇹🇭','Thailand','3-5 days'],['🇦🇪','UAE','3-7 days'],['🇸🇬','Singapore','5-7 days'],['🇮🇩','Indonesia','3-5 days'],['🇯🇵','Japan','5-7 days'],['🇰🇷','S. Korea','5-7 days'],['🇬🇧','UK','10-15 days'],['🇺🇸','USA','15-30 days'],['🇨🇦','Canada','20-30 days'],['🇮🇳','India','2-3 days'],['🌍','Schengen','7-15 days']] as [$f,$n,$t])
            <div class="visa-card">
                <div class="text-3xl mb-2">{{ $f }}</div>
                <p class="text-xs font-bold text-gray-800 leading-tight">{{ $n }}</p>
                <p class="text-[10px] text-gray-400 mt-1">{{ $t }}</p>
            </div>
            @endforeach
        </div>
        @endif

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('visas.index') }}" class="btn-primary">Apply for Visa</a>
            <a href="{{ route('contact') }}" class="btn-outline">Free Consultation</a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     WHY CHOOSE US
═══════════════════════════════════════ --}}
<section class="bg-gray-50 py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-10">
            <p class="section-eyebrow mb-2">Why travellers trust us</p>
            <h2 class="text-3xl font-extrabold text-gray-900">The FlyoverBD Difference</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php $features = [
                ['bg-red-50','text-red-600','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','Best Price Guarantee','We match any lower price you find within 24 hours of booking.'],
                ['bg-blue-50','text-blue-600','M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','Visa Experts','Our visa team has a 94.2% approval rate across 184 destinations.'],
                ['bg-green-50','text-green-600','M12 19l9 2-9-18-9 18 9-2zm0 0v-8','On-time Transfers','Airport pick &amp; drop to 20+ cities — your driver is always on time.'],
                ['bg-purple-50','text-purple-600','M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z','24/7 Human Support','Real people on WhatsApp, not bots. Always available, always helpful.'],
            ]; @endphp
            @foreach($features as [$bg,$color,$icon,$title,$desc])
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="w-12 h-12 {{ $bg }} rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $title }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{!! $desc !!}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     BLOG
═══════════════════════════════════════ --}}
@if(isset($recentPosts) && $recentPosts->count() > 0)
<section class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="section-eyebrow mb-1.5">Travel insights</p>
                <h2 class="text-3xl font-extrabold text-gray-900">Latest from our Blog</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="hidden md:flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-700">
                All articles <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @foreach($recentPosts as $post)
            <article class="travel-card group flex flex-col">
                <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden" style="height:185px;">
                    @if($post->image)
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full" style="background:linear-gradient(135deg,#667eea,#764ba2);"></div>
                    @endif
                </a>
                <div class="p-5 flex flex-col flex-1">
                    <p class="text-xs text-gray-400 mb-2">{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}</p>
                    <h3 class="font-bold text-gray-900 text-sm leading-snug line-clamp-2 mb-2 group-hover:text-red-600 transition flex-1">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h3>
                    <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 mt-2">
                        Read more <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ═══════════════════════════════════════
     CTA BANNER
═══════════════════════════════════════ --}}
<section class="py-16" style="background:linear-gradient(135deg,#1a1a2e 0%,#C8102E 100%);">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h2 class="text-4xl font-extrabold text-white mb-4" style="font-family:'Merriweather',Georgia,serif;">
            Ready for your next adventure?
        </h2>
        <p class="text-white/75 text-lg mb-8">Talk to our travel experts for free. No obligations, no hidden fees.</p>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('packages.index') }}" class="bg-white text-red-600 hover:bg-gray-50 font-bold px-8 py-4 rounded-xl transition shadow-lg text-sm">
                Browse Packages
            </a>
            <a href="https://wa.me/8801335111370" target="_blank"
               class="bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-4 rounded-xl transition shadow-lg text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                Chat on WhatsApp
            </a>
        </div>
    </div>
</section>

</x-app-layout>
