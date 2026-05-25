<x-app-layout
    title="Hotels | FlyoverBD"
    meta_description="Browse curated hotels across Bangladesh's top destinations. Book your perfect stay at Cox's Bazar, Dhaka, Sylhet and more."
>

    {{-- Hero --}}
    <section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="px-4 py-14 text-center">
        <p class="section-eyebrow mb-2">Curated accommodations</p>
        <h1 class="font-extrabold text-4xl md:text-5xl text-gray-900 mb-3" style="font-family:'Merriweather',Georgia,serif;">
            Hotel Bookings
        </h1>
        <p class="text-gray-500 max-w-md mx-auto mb-8">Handpicked hotels across Bangladesh's top destinations - book your perfect stay in minutes.</p>

        {{-- Search --}}
        <form action="{{ route('hotels.index') }}" method="GET"
              x-data="{
                  query: '{{ request('search') }}',
                  checkIn: '{{ request('check_in') }}',
                  checkOut: '{{ request('check_out') }}',
                  persons: {{ request('persons', 1) }},
                  suggestions: [], show: false, loading: false, timer: null,
                  fetch() {
                      this.loading = true; clearTimeout(this.timer);
                      this.timer = setTimeout(() => {
                          window.fetch(`{{ route('search.suggestions') }}?type=hotels&query=${encodeURIComponent(this.query)}`)
                              .then(r => r.json()).then(d => { this.suggestions = d; this.show = d.length > 0; this.loading = false; })
                              .catch(() => this.loading = false);
                      }, 280);
                  },
                  go(url) { window.location.href = url; }
              }"
              @click.away="show = false"
              class="max-w-4xl mx-auto mb-5">

            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-2 flex flex-wrap gap-2 items-center relative">
                {{-- Destination --}}
                <div class="flex-1 min-w-[180px] relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <input type="text" name="search" x-model="query"
                           @input="fetch()" @focus="fetch()"
                           placeholder="Search hotels or destinations…"
                           class="w-full pl-9 pr-3 py-3 text-gray-800 text-sm outline-none rounded-xl border border-gray-100 focus:border-red-300"
                           autocomplete="off">
                </div>

                {{-- Check-in --}}
                <div class="relative min-w-[140px]">
                    <label class="absolute -top-2 left-3 bg-white text-[10px] font-bold text-gray-400 px-1 uppercase tracking-wide">Check-in</label>
                    <input type="date" name="check_in" x-model="checkIn"
                           :min="new Date().toISOString().split('T')[0]"
                           class="w-full py-3 px-3 text-gray-700 text-sm outline-none rounded-xl border border-gray-100 focus:border-red-300">
                </div>

                {{-- Check-out --}}
                <div class="relative min-w-[140px]">
                    <label class="absolute -top-2 left-3 bg-white text-[10px] font-bold text-gray-400 px-1 uppercase tracking-wide">Check-out</label>
                    <input type="date" name="check_out" x-model="checkOut"
                           :min="checkIn || new Date().toISOString().split('T')[0]"
                           class="w-full py-3 px-3 text-gray-700 text-sm outline-none rounded-xl border border-gray-100 focus:border-red-300">
                </div>

                {{-- Persons --}}
                <div class="relative w-24">
                    <label class="absolute -top-2 left-3 bg-white text-[10px] font-bold text-gray-400 px-1 uppercase tracking-wide">Guests</label>
                    <input type="number" name="persons" x-model="persons" min="1" max="20"
                           class="w-full py-3 px-3 text-gray-700 text-sm outline-none rounded-xl border border-gray-100 focus:border-red-300 text-center">
                </div>

                <button type="submit" class="btn-primary px-6 py-3 rounded-xl whitespace-nowrap">Search Hotels</button>

                @if(request()->hasAny(['search','check_in','check_out','persons']))
                <a href="{{ route('hotels.index') }}" class="flex items-center px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 hover:text-gray-700 transition whitespace-nowrap">Clear</a>
                @endif

                {{-- Autocomplete dropdown --}}
                <div x-show="show && suggestions.length > 0"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-1"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="absolute top-full left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden z-50 text-left"
                     style="display:none;">
                    <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 border-b border-gray-100">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="query ? 'Search results' : 'Popular hotels'"></span>
                        <div x-show="loading" class="w-3 h-3 border-2 border-red-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <ul class="max-h-64 overflow-y-auto">
                        <template x-for="item in suggestions" :key="item.url">
                            <li @click="go(item.url)" class="flex items-center gap-3 px-4 py-3 hover:bg-red-50 cursor-pointer transition group">
                                <img :src="item.image || 'https://via.placeholder.com/80x80?text=Hotel'" alt="" class="w-11 h-11 object-cover rounded-xl flex-shrink-0 bg-gray-100">
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

            {{-- Active filter pills --}}
            @if(request()->hasAny(['check_in','check_out','persons']))
            <div class="flex flex-wrap justify-center gap-2 mt-3">
                @if(request('check_in'))
                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 text-red-700 border border-red-200 rounded-full px-3 py-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Check-in: {{ \Carbon\Carbon::parse(request('check_in'))->format('d M Y') }}
                </span>
                @endif
                @if(request('check_out'))
                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 text-red-700 border border-red-200 rounded-full px-3 py-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Check-out: {{ \Carbon\Carbon::parse(request('check_out'))->format('d M Y') }}
                </span>
                @endif
                @if(request('persons') && request('persons') > 1)
                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 rounded-full px-3 py-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    {{ request('persons') }} Guests
                </span>
                @endif
            </div>
            @endif
        </form>

        {{-- Location chips --}}
        <div class="flex flex-wrap justify-center gap-2">
            @foreach(["Cox's Bazar", "Dhaka", "Sylhet", "Sundarbans", "Bandarban", "Saint Martin"] as $loc)
            <a href="{{ route('hotels.index', array_merge(request()->only(['check_in','check_out','persons']), ['search' => $loc])) }}"
               class="text-xs font-semibold px-3.5 py-1.5 rounded-full transition {{ request('search') === $loc ? 'bg-red-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-red-300 hover:text-red-600' }}">
                {{ $loc }}
            </a>
            @endforeach
        </div>
    </section>

    {{-- Hotel Grid --}}
    <section class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($hotels->count())
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-500">
                @if(request('search'))
                    Results for <strong class="text-gray-800">"{{ request('search') }}"</strong> &nbsp;·&nbsp;
                @endif
                <strong class="text-gray-800">{{ $hotels->total() }}</strong> hotels found
                @if(request('check_in') && request('check_out'))
                    &nbsp;·&nbsp;
                    {{ \Carbon\Carbon::parse(request('check_in'))->format('d M') }} – {{ \Carbon\Carbon::parse(request('check_out'))->format('d M Y') }}
                    ({{ \Carbon\Carbon::parse(request('check_in'))->diffInDays(\Carbon\Carbon::parse(request('check_out'))) }} nights)
                @endif
                @if(request('persons') && request('persons') > 1)
                    &nbsp;·&nbsp; {{ request('persons') }} guests
                @endif
            </p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($hotels as $hotel)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden group">
                <div class="relative overflow-hidden">
                    @if($hotel->thumbnail)
                        <img src="{{ Storage::url($hotel->thumbnail) }}" alt="{{ $hotel->name }}"
                            class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        </div>
                    @endif
                    <div class="absolute top-3 left-3">
                        <span class="bg-white/90 backdrop-blur-sm text-amber-500 text-xs font-bold px-2 py-1 rounded-lg">
                            @for($i = 1; $i <= 5; $i++){{ $i <= $hotel->star_rating ? '★' : '☆' }}@endfor
                        </span>
                    </div>
                </div>
                <div class="p-5">
                    <h3 class="font-bold text-gray-900 text-base mb-1">{{ $hotel->name }}</h3>
                    <div class="flex items-center gap-1.5 text-sm text-gray-500 mb-3">
                        <svg class="w-3.5 h-3.5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $hotel->location }}
                    </div>
                    @php
                        $minPrice = $hotel->rooms->where('is_active', true)->min('price_per_night');
                    @endphp
                    @if($minPrice)
                    <p class="text-xs text-gray-400 mb-0.5">from</p>
                    <p class="text-xl font-extrabold text-red-600 mb-4">৳{{ number_format($minPrice) }}<span class="text-xs text-gray-400 font-normal">/night</span></p>
                    @endif
                    <a href="{{ route('hotels.show', array_merge(['hotel' => $hotel->slug], request()->only(['check_in','check_out','persons']))) }}"
                        class="block w-full text-center bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 rounded-xl transition text-sm">
                        View Hotel
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @if($hotels->hasPages())
        <div class="mt-10">{{ $hotels->links() }}</div>
        @endif
        @else
        <div class="text-center py-20">
            <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <h3 class="text-xl font-bold text-gray-700 mb-2">No Hotels Yet</h3>
            <p class="text-gray-400">We're adding hotels soon. Check back later!</p>
        </div>
        @endif
    </div>
    </section>

</x-app-layout>
