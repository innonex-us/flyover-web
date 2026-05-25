<x-app-layout
    title="Hotels | FlyoverBD"
    meta_description="Browse curated hotels across Bangladesh's top destinations. Book your perfect stay at Cox's Bazar, Dhaka, Sylhet and more."
>

    {{-- Hero --}}
    <section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="px-4 py-14 text-center">
        {{-- Search Widget --}}
        <div class="mx-auto w-full max-w-3xl"
             x-data="{
                 query: '{{ addslashes(request('search', '')) }}',
                 checkIn: {{ request('check_in') ? "new Date('" . e(request('check_in')) . "')" : 'null' }},
                 checkOut: {{ request('check_out') ? "new Date('" . e(request('check_out')) . "')" : 'null' }},
                 persons: {{ (int) request('persons', 1) }},
                 suggestions: [], show: false, loading: false, timer: null,
                 calOpen: '',
                 calYear: new Date().getFullYear(), calMonth: new Date().getMonth(),
                 months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
                 days: ['Su','Mo','Tu','We','Th','Fr','Sa'],
                 today: new Date(new Date().toDateString()),
                 get calLabel(){ return this.months[this.calMonth] + ' ' + this.calYear; },
                 calPrev(){ if(this.calMonth===0){this.calMonth=11;this.calYear--;}else{this.calMonth--;} },
                 calNext(){ if(this.calMonth===11){this.calMonth=0;this.calYear++;}else{this.calMonth++;} },
                 calDays(){
                     let d=[], first=new Date(this.calYear,this.calMonth,1).getDay(), daysIn=new Date(this.calYear,this.calMonth+1,0).getDate();
                     for(let i=0;i<first;i++) d.push(null);
                     for(let i=1;i<=daysIn;i++) d.push(new Date(this.calYear,this.calMonth,i));
                     return d;
                 },
                 fmtDateShort(d){ if(!d) return ''; return d.toLocaleDateString('en-US',{month:'short',day:'numeric'}); },
                 fmtISO(d){ if(!d) return ''; let m=(d.getMonth()+1).toString().padStart(2,'0'),dd=d.getDate().toString().padStart(2,'0'); return d.getFullYear()+'-'+m+'-'+dd; },
                 pickDate(d){
                     if(!d || d < this.today) return;
                     if(this.calOpen==='checkIn'){ this.checkIn=d; this.calOpen=''; }
                     else if(this.calOpen==='checkOut'){
                         if(this.checkIn && d<=this.checkIn){ this.checkIn=d; }
                         else{ this.checkOut=d; this.calOpen=''; }
                     }
                 },
                 isSelected(d,field){ return d && this[field] && this[field].toDateString()===d.toDateString(); },
                 isInRange(d){ return d && this.checkIn && this.checkOut && d>this.checkIn && d<this.checkOut; },
                 isPast(d){ return d && d < this.today; },
                 openCal(field){ this.calOpen=this.calOpen===field?'':field; },
                 fetchSuggestions(){
                     this.loading=true; clearTimeout(this.timer);
                     this.timer=setTimeout(()=>{
                         window.fetch(`{{ route('search.suggestions') }}?type=hotels&query=${encodeURIComponent(this.query)}`)
                             .then(r=>r.json()).then(d=>{ this.suggestions=d; this.show=d.length>0; this.loading=false; })
                             .catch(()=>this.loading=false);
                     }, 220);
                 },
                 go(url){ window.location.href=url; },
                 search(){
                     let url='{{ route('hotels.index') }}?search='+encodeURIComponent(this.query);
                     if(this.checkIn) url+='&check_in='+this.fmtISO(this.checkIn);
                     if(this.checkOut) url+='&check_out='+this.fmtISO(this.checkOut);
                     url+='&persons='+this.persons;
                     window.location.href=url;
                 }
             }"
             @keydown.escape.window="show=false; calOpen=''"
             @click.outside="calOpen=''; show=false">

            <div class="bg-white rounded-3xl shadow-2xl shadow-black/10 p-3 sm:p-4 text-left relative">

                {{-- Destination --}}
                <div class="flex items-center px-3 py-3 mb-3 bg-gray-50 border border-gray-200 rounded-2xl focus-within:border-red-400 focus-within:ring-2 focus-within:ring-red-100 transition-all">
                    <svg class="w-4 h-4 text-red-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <input type="text" x-model="query" @input="fetchSuggestions()" @focus="fetchSuggestions()"
                           placeholder="Where do you want to stay?"
                           class="w-full text-gray-800 text-sm border-0 p-0 focus:ring-0 outline-none placeholder-gray-400 bg-transparent"
                           autocomplete="off">
                    <div x-show="loading" class="w-3.5 h-3.5 border-2 border-red-500 border-t-transparent rounded-full animate-spin ml-2 flex-shrink-0"></div>
                </div>

                {{-- Date + Guests row --}}
                <div class="grid grid-cols-3 sm:grid-cols-3 gap-2 mb-3">
                    {{-- Check-in --}}
                    <button type="button" @click.stop="openCal('checkIn')"
                            class="flex items-center gap-1 px-2 sm:px-3 py-2.5 sm:py-3 bg-gray-50 border rounded-xl transition-all text-left"
                            :class="calOpen==='checkIn' ? 'border-red-400 bg-red-50' : 'border-gray-200'">
                        <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide whitespace-nowrap">Check-in</p>
                            <p class="text-sm font-semibold text-gray-700 truncate" x-text="checkIn ? fmtDateShort(checkIn) : 'Select'"></p>
                        </div>
                    </button>
                    {{-- Check-out --}}
                    <button type="button" @click.stop="openCal('checkOut')"
                            class="flex items-center gap-1 px-2 sm:px-3 py-2.5 sm:py-3 bg-gray-50 border rounded-xl transition-all text-left"
                            :class="calOpen==='checkOut' ? 'border-red-400 bg-red-50' : 'border-gray-200'">
                        <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide whitespace-nowrap">Check-out</p>
                            <p class="text-sm font-semibold text-gray-700 truncate" x-text="checkOut ? fmtDateShort(checkOut) : 'Select'"></p>
                        </div>
                    </button>
                    {{-- Guests --}}
                    <div class="flex items-center gap-1 px-2 sm:px-3 py-2.5 sm:py-3 bg-gray-50 border border-gray-200 rounded-xl">
                        <svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Guests</p>
                            <div class="flex items-center gap-1">
                                <button type="button" @click.stop="persons=Math.max(1,persons-1)" class="w-5 h-5 flex items-center justify-center text-gray-500 hover:text-red-600 font-bold text-base leading-none">−</button>
                                <span class="text-sm font-bold text-gray-800 w-4 text-center" x-text="persons"></span>
                                <button type="button" @click.stop="persons=Math.min(20,persons+1)" class="w-5 h-5 flex items-center justify-center text-gray-500 hover:text-red-600 font-bold text-base leading-none">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Inline Calendar --}}
                <div x-show="calOpen==='checkIn' || calOpen==='checkOut'"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     style="display:none;"
                     class="mb-3 bg-white border border-gray-200 rounded-2xl shadow-sm p-3">
                    <div class="flex items-center justify-between mb-2">
                        <button type="button" @click.stop="calPrev()" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 transition text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <span class="text-sm font-bold text-gray-800" x-text="calLabel"></span>
                        <button type="button" @click.stop="calNext()" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 transition text-gray-500">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                    <div class="grid grid-cols-7 mb-1">
                        <template x-for="d in days" :key="d">
                            <div class="text-center text-xs font-semibold text-gray-400 py-0.5" x-text="d"></div>
                        </template>
                    </div>
                    <div class="grid grid-cols-7">
                        <template x-for="(d,i) in calDays()" :key="i">
                            <div class="flex items-center justify-center py-0.5">
                                <button x-show="d!==null" type="button" @click.stop="pickDate(d)" :disabled="isPast(d)"
                                        :class="{
                                            'bg-red-600 text-white font-bold': (calOpen==='checkIn'&&isSelected(d,'checkIn'))||(calOpen==='checkOut'&&isSelected(d,'checkOut')),
                                            'bg-red-100 text-red-600 font-medium': isInRange(d),
                                            'text-gray-300 cursor-not-allowed': isPast(d),
                                            'hover:bg-red-50 hover:text-red-600 text-gray-700': !isPast(d)&&!isSelected(d,'checkIn')&&!isSelected(d,'checkOut'),
                                            'ring-2 ring-red-400 font-bold text-red-600': d&&d.toDateString()===today.toDateString()&&!isSelected(d,'checkIn')&&!isSelected(d,'checkOut')
                                        }"
                                        class="w-8 h-8 text-xs rounded-full transition-all"
                                        x-text="d?d.getDate():''"></button>
                            </div>
                        </template>
                    </div>
                    <div x-show="checkIn||checkOut" class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-xs text-gray-500">
                            <span class="font-semibold text-gray-800" x-text="checkIn?fmtDateShort(checkIn):'-'"></span>
                            <span class="mx-1 text-gray-300">→</span>
                            <span class="font-semibold text-gray-800" x-text="checkOut?fmtDateShort(checkOut):'-'"></span>
                        </span>
                        <button type="button" @click.stop="checkIn=null;checkOut=null" class="text-xs text-red-500 font-semibold hover:text-red-700">Clear</button>
                    </div>
                </div>

                {{-- Search button --}}
                <button @click="search()"
                        class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 rounded-2xl text-sm transition-all shadow-lg shadow-red-600/20 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Search Hotels
                </button>

                {{-- Autocomplete suggestions --}}
                <div x-show="show && suggestions.length > 0 && calOpen === ''"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 translate-y-2"
                     x-transition:enter-end="opacity-100 translate-y-0"
                     @click.outside="show=false"
                     class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl shadow-black/15 border border-gray-100 overflow-hidden z-50"
                     style="display:none;">
                    <div class="flex items-center justify-between px-4 py-2.5 bg-gray-50 border-b border-gray-100">
                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="query ? 'Results for &quot;'+query+'&quot;' : 'Popular hotels'"></span>
                        <div x-show="loading" class="w-3 h-3 border-2 border-red-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    <ul class="max-h-64 overflow-y-auto">
                        <template x-for="item in suggestions" :key="item.url">
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

        </div>

        {{-- Active filter pills --}}
        @if(request()->hasAny(['check_in','check_out','persons']))
        <div class="flex flex-wrap justify-center gap-2 mt-4">
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
                {{ request('persons') }} Guests
            </span>
            @endif
            <a href="{{ route('hotels.index') }}" class="inline-flex items-center gap-1 text-xs font-semibold bg-gray-100 text-gray-500 hover:text-gray-700 rounded-full px-3 py-1 transition">Clear filters</a>
        </div>
        @endif

        {{-- Location chips --}}
        <div class="flex flex-wrap justify-center gap-2 mt-4">
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
                        @php
                            $thumbnail = Str::startsWith($hotel->thumbnail, 'http')
                                ? preg_replace('/&w=\d+/', '&w=600', preg_replace('/\?w=\d+/', '?w=600', $hotel->thumbnail))
                                : Storage::url($hotel->thumbnail);
                        @endphp
                        <img src="{{ $thumbnail }}" alt="{{ $hotel->name }}" loading="lazy" decoding="async"
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
                    <p class="text-xl font-extrabold text-red-600 mb-4"
                       data-price-bdt="{{ $minPrice }}"
                    ><span data-currency-display>৳{{ number_format($minPrice) }}</span><span class="text-xs text-gray-400 font-normal">/night</span></p>
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
        <div class="mt-10 flex justify-center">{{ $hotels->appends(request()->query())->links() }}</div>
        @endif
        @else
        <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <p class="text-xl font-bold text-gray-400 mb-2">No hotels found</p>
            <p class="text-sm text-gray-400 mb-5">Try a different location or clear your filters.</p>
            <a href="{{ route('hotels.index') }}" class="btn-primary">Browse All Hotels</a>
        </div>
        @endif
    </div>
    </section>

{{-- ── How it works ─────────────────────── --}}
<section style="background:#F9F6EF;border-top:1px solid #E4DCC9;" class="py-14">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-10">
            <p class="section-eyebrow mb-2">Simple process</p>
            <h2 class="font-extrabold text-3xl text-gray-900">How Hotel Booking Works</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 sm:gap-6 text-center">
            @php $steps = [
                ['bg-blue-50','text-blue-600','M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z','1. Search','Enter your destination, dates and number of guests to see available hotels.'],
                ['bg-green-50','text-green-600','M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6','2. Pick a Hotel','Browse curated hotels with real photos, star ratings and pricing.'],
                ['bg-yellow-50','text-yellow-600','M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','3. Reserve a Room','Select your room type and fill in your booking details securely.'],
                ['bg-red-50','text-red-600','M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z','4. Confirmed!','Get instant confirmation via email and enjoy a stress-free stay.'],
            ]; @endphp
            @foreach($steps as [$bg,$color,$icon,$title,$desc])
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="w-12 h-12 {{ $bg }} rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 text-sm mb-1">{{ $title }}</h3>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ───────────────────────────────── --}}
<section class="py-14 text-center" style="background:#18130E;color:#FAF6EE;">
    <p class="section-eyebrow mb-3" style="color:#C8102E;">Need help?</p>
    <h2 class="font-extrabold text-3xl mb-3" style="font-family:'Merriweather',Georgia,serif;">Talk to a travel expert — free.</h2>
    <p class="text-sm max-w-sm mx-auto mb-6" style="color:#A09890;">Real people, not bots. Available 24/7 on WhatsApp and phone.</p>
    <div class="flex flex-wrap justify-center gap-3">
        <a href="https://wa.me/{{ $site['whatsapp'] }}" target="_blank"
           class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-3.5 rounded-xl transition text-sm">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            WhatsApp Us
        </a>
        <a href="{{ route('contact') }}" style="border-color:#FAF6EE;color:#FAF6EE;" class="ota-btn-ghost">Contact Us</a>
    </div>
</section>

</x-app-layout>
