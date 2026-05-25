<x-app-layout
    :title="$title"
    meta_description="Book hassle-free airport transfers and pick & drop services across Bangladesh. Fixed pricing, preset routes, or custom locations."
>

    {{-- Hero --}}
    <section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="px-4 py-14 text-center">

        {{-- Search widget --}}
        <div class="mx-auto w-full max-w-3xl mb-8"
             x-data="{
                 pickup: '{{ e(request('pickup')) }}',
                 drop: '{{ e(request('drop')) }}',
                 travelDate: {{ request('travel_date') ? "new Date('" . e(request('travel_date')) . "')" : 'null' }},
                 passengers: {{ (int) request('passengers', 1) }},
                 calOpen: '', calYear: new Date().getFullYear(), calMonth: new Date().getMonth(),
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
                 pickDate(d){ if(!d || d < this.today) return; this.travelDate=d; this.calOpen=''; },
                 isSelected(d){ return d && this.travelDate && this.travelDate.toDateString()===d.toDateString(); },
                 isPast(d){ return d && d < this.today; },
                 openCal(){ this.calOpen=this.calOpen===''?'travelDate':''; this.calYear=new Date().getFullYear(); this.calMonth=new Date().getMonth(); },
                 search(){
                     let url='{{ route('transfers.index') }}?pickup='+encodeURIComponent(this.pickup)+'&drop='+encodeURIComponent(this.drop)+'&travel_date='+this.fmtISO(this.travelDate)+'&passengers='+this.passengers;
                     window.location.href=url;
                 }
             }"
             @keydown.escape.window="calOpen=''"
             @click.outside="calOpen=''">
            <div class="bg-white rounded-3xl shadow-2xl shadow-black/10 p-4 text-left relative">

                {{-- Pickup (full width) --}}
                <div class="flex items-center px-3 py-3 mb-3 bg-gray-50 border border-gray-200 rounded-2xl focus-within:border-red-400 focus-within:ring-2 focus-within:ring-red-100 transition-all">
                    <svg class="w-4 h-4 text-green-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    <input type="text" x-model="pickup" placeholder="Pickup location — Dhaka Airport, Cox's Bazar…"
                           class="w-full text-gray-800 text-sm border-0 p-0 focus:ring-0 outline-none placeholder-gray-400 bg-transparent" autocomplete="off">
                </div>

                {{-- Drop-off + Date + Passengers (grid) --}}
                <div class="grid grid-cols-3 gap-2 mb-3">
                    {{-- Drop-off --}}
                    <div class="flex items-center gap-1.5 px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl focus-within:border-red-400 focus-within:ring-2 focus-within:ring-red-100 transition-all">
                        <svg class="w-3.5 h-3.5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide whitespace-nowrap">Drop-off</p>
                            <input type="text" x-model="drop" placeholder="Destination…"
                                   class="w-full text-gray-700 text-sm border-0 p-0 focus:ring-0 outline-none placeholder-gray-400 bg-transparent" autocomplete="off">
                        </div>
                    </div>
                    {{-- Date --}}
                    <button type="button" @click.stop="openCal()"
                            class="flex items-center gap-1.5 px-3 py-3 bg-gray-50 border rounded-xl transition-all text-left"
                            :class="calOpen==='travelDate' ? 'border-red-400 bg-red-50' : 'border-gray-200'">
                        <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <div class="min-w-0">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide whitespace-nowrap">Date</p>
                            <p class="text-sm font-semibold text-gray-700 truncate" x-text="travelDate ? fmtDateShort(travelDate) : 'Select'"></p>
                        </div>
                    </button>
                    {{-- Passengers --}}
                    <div class="flex items-center gap-1.5 px-3 py-3 bg-gray-50 border border-gray-200 rounded-xl">
                        <svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <div class="flex-1 min-w-0">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Persons</p>
                            <div class="flex items-center gap-1">
                                <button type="button" @click.stop="passengers=Math.max(1,passengers-1)" class="w-5 h-5 flex items-center justify-center text-gray-500 hover:text-red-600 font-bold text-base leading-none">−</button>
                                <span class="text-sm font-bold text-gray-800 w-4 text-center" x-text="passengers"></span>
                                <button type="button" @click.stop="passengers=Math.min(50,passengers+1)" class="w-5 h-5 flex items-center justify-center text-gray-500 hover:text-red-600 font-bold text-base leading-none">+</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Inline calendar --}}
                <div x-show="calOpen==='travelDate'"
                     x-transition:enter="transition ease-out duration-150"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     style="display:none;"
                     class="mb-3 bg-white border border-gray-200 rounded-2xl shadow-sm p-3">
                    <div class="flex items-center justify-between mb-2">
                        <button type="button" @click.stop="calPrev()" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 transition text-gray-500"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg></button>
                        <span class="text-sm font-bold text-gray-800" x-text="calLabel"></span>
                        <button type="button" @click.stop="calNext()" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 transition text-gray-500"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></button>
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
                                            'bg-red-600 text-white font-bold': isSelected(d),
                                            'text-gray-300 cursor-not-allowed': isPast(d),
                                            'hover:bg-red-50 hover:text-red-600 text-gray-700': !isPast(d)&&!isSelected(d),
                                            'ring-2 ring-red-400 font-bold text-red-600': d&&d.toDateString()===today.toDateString()&&!isSelected(d)
                                        }"
                                        class="w-8 h-8 text-xs rounded-full transition-all"
                                        x-text="d?d.getDate():''"></button>
                            </div>
                        </template>
                    </div>
                </div>

                {{-- Search button --}}
                <button @click="search()"
                        class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-red-600/30 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    Find Pick &amp; Drop
                </button>
            </div>

            {{-- Active search context pills --}}
            @if(request()->hasAny(['pickup','drop','travel_date','passengers']))
            <div class="flex flex-wrap justify-center gap-2 mt-3">
                @if(request('pickup'))
                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-green-50 text-green-700 border border-green-200 rounded-full px-3 py-1">
                    From: {{ request('pickup') }}
                </span>
                @endif
                @if(request('drop'))
                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-red-50 text-red-700 border border-red-200 rounded-full px-3 py-1">
                    To: {{ request('drop') }}
                </span>
                @endif
                @if(request('travel_date'))
                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200 rounded-full px-3 py-1">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    {{ \Carbon\Carbon::parse(request('travel_date'))->format('d M Y') }}
                </span>
                @endif
                @if(request('passengers') && request('passengers') > 1)
                <span class="inline-flex items-center gap-1 text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200 rounded-full px-3 py-1">
                    {{ request('passengers') }} Passengers
                </span>
                @endif
            </div>
            @endif
        </div>

        {{-- Quick-jump chips --}}
        <div class="flex flex-wrap justify-center gap-2">
            <a href="#booking-form" onclick="document.getElementById('booking-form').scrollIntoView({behavior:'smooth'}); return false;"
               class="text-xs font-semibold px-3.5 py-1.5 rounded-full bg-red-600 text-white transition hover:bg-red-700">
                Book Now
            </a>
            @foreach(['Dhaka Airport', "Cox's Bazar", 'Chittagong', 'Sylhet Airport', 'Custom Route'] as $loc)
            <a href="{{ $loc === 'Custom Route' ? '#booking-form' : '#booking-form' }}"
               onclick="document.getElementById('booking-form').scrollIntoView({behavior:'smooth'}); {{ $loc === 'Custom Route' ? "document.querySelector('[x-data]').dispatchEvent(new CustomEvent('set-custom'))" : '' }} return false;"
               class="text-xs font-semibold px-3.5 py-1.5 rounded-full bg-white border border-gray-200 text-gray-600 hover:border-red-300 hover:text-red-600 transition">
                {{ $loc }}
            </a>
            @endforeach
        </div>
    </section>

    {{-- Preset Routes --}}
    @if($routes->count())
    <section class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-1" style="font-family:'Merriweather',Georgia,serif;">Available Routes</h2>
            <p class="text-gray-500">Choose from preset transfer routes with fixed pricing.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($routes as $route)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition overflow-hidden">
                @if($route->thumbnail)
                    <img src="{{ Storage::url($route->thumbnail) }}" alt="{{ $route->name }}" class="w-full h-40 object-cover">
                @endif
                <div class="p-6">
                    <h3 class="font-bold text-gray-900 text-lg mb-2">{{ $route->name }}</h3>
                    <div class="flex items-center gap-2 text-sm text-gray-600 mb-1">
                        <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        <span class="font-medium">{{ $route->pickup_location }}</span>
                        <span class="text-gray-400">→</span>
                        <span class="font-medium">{{ $route->drop_location }}</span>
                    </div>
                    @if($route->description)
                        <p class="text-sm text-gray-500 mt-2 mb-4">{{ Str::limit($route->description, 80) }}</p>
                    @else
                        <div class="mt-3 mb-4"></div>
                    @endif
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs text-gray-400 font-semibold uppercase tracking-wide">Price per person</p>
                            <p class="text-2xl font-extrabold text-red-600">৳{{ number_format($route->price_per_person) }}</p>
                        </div>
                        <a href="#booking-form" onclick="document.getElementById('route_id_input').value = '{{ $route->id }}'; document.getElementById('booking-form').scrollIntoView({behavior:'smooth'}); return false;"
                            class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-xl transition text-sm">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    </section>
    @endif

    {{-- Booking Form --}}
    <section id="booking-form" style="background:#F9F6EF;border-top:1px solid #E4DCC9;" class="py-16 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2" style="font-family:'Merriweather',Georgia,serif;">Book a Pick &amp; Drop</h2>
                <p class="text-gray-500">Fill in your details and we'll arrange your pick &amp; drop.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8" x-data="{
                isCustom: {{ (request('pickup') || request('drop')) ? 'true' : 'false' }},
                routeId: ''
            }">
                @if($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <ul class="text-sm text-red-600 space-y-1">
                        @foreach($errors->all() as $error)
                        <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form id="transfer_form" action="{{ route('transfers.store') }}" method="POST">
                    @csrf

                    {{-- Route or Custom Toggle --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Service Type</label>
                        <div class="flex gap-3">
                            <button type="button" @click="isCustom = false"
                                :class="!isCustom ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                class="flex-1 py-2.5 px-4 rounded-xl text-sm font-semibold transition">
                                Preset Route
                            </button>
                            <button type="button" @click="isCustom = true"
                                :class="isCustom ? 'bg-red-600 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                class="flex-1 py-2.5 px-4 rounded-xl text-sm font-semibold transition">
                                Custom Transfer
                            </button>
                        </div>
                    </div>

                    {{-- Preset Route Selector --}}
                    <div x-show="!isCustom" class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Select Route</label>
                        <select name="route_id" id="route_id_input" x-model="routeId"
                            class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                            <option value="">Choose a route...</option>
                            @foreach($routes as $route)
                            <option value="{{ $route->id }}">{{ $route->name }} - ৳{{ number_format($route->price_per_person) }}/person</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Custom Locations --}}
                    <div x-show="isCustom" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6" style="display:none;">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pickup Location</label>
                            <input type="text" name="pickup_location" id="form_pickup"
                                value="{{ old('pickup_location', request('pickup')) }}"
                                placeholder="e.g. Dhaka Airport"
                                class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Drop Location</label>
                            <input type="text" name="drop_location" id="form_drop"
                                value="{{ old('drop_location', request('drop')) }}"
                                placeholder="e.g. Hotel Name, Cox's Bazar"
                                class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                        </div>
                    </div>

                    {{-- Trip Details --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Passengers <span class="text-red-500">*</span></label>
                            <input type="number" name="passenger_count" id="form_passengers"
                                value="{{ old('passenger_count', request('passengers', 1)) }}" min="1" required
                                class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                            @error('passenger_count') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Travel Date <span class="text-red-500">*</span></label>
                            <input type="date" name="travel_date" id="form_travel_date"
                                value="{{ old('travel_date', request('travel_date')) }}" required
                                min="{{ now()->addDay()->format('Y-m-d') }}"
                                class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                            @error('travel_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Pickup Time</label>
                            <input type="time" name="pickup_time" value="{{ old('pickup_time') }}"
                                class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                        </div>
                    </div>

                    {{-- Guest Info (if not logged in) --}}
                    @guest
                    <div class="space-y-4 mb-6 border-t pt-6">
                        <h3 class="text-sm font-bold text-gray-700 uppercase tracking-wide">Your Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Full Name <span class="text-red-500">*</span></label>
                                <input type="text" name="guest_name" value="{{ old('guest_name') }}" required
                                    class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                                @error('guest_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="guest_email" value="{{ old('guest_email') }}" required
                                    class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                                @error('guest_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Phone <span class="text-red-500">*</span></label>
                                <input type="tel" name="guest_phone" value="{{ old('guest_phone') }}" required
                                    class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                                @error('guest_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </div>
                    @endguest

                    {{-- Special Request --}}
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Special Request <span class="text-gray-400 font-normal">(optional)</span></label>
                        <textarea name="special_request" rows="3"
                            placeholder="Any special requirements..."
                            class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">{{ old('special_request') }}</textarea>
                    </div>

                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-4 rounded-xl shadow-lg hover:shadow-xl transition text-base">
                        Submit Booking Request
                    </button>
                </form>
            </div>
        </div>
    </section>

{{-- ── How it works ─────────────────────── --}}
<section class="bg-white py-14">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-10">
            <p class="section-eyebrow mb-2">Simple process</p>
            <h2 class="font-extrabold text-3xl text-gray-900">How Pick &amp; Drop Works</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 text-center">
            @php $steps = [
                ['bg-blue-50','text-blue-600','M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z','1. Set Route','Enter your pickup and drop-off locations, or choose a preset route.'],
                ['bg-green-50','text-green-600','M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z','2. Pick a Date','Select your travel date and preferred pickup time.'],
                ['bg-yellow-50','text-yellow-600','M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','3. Book Online','Fill in your details and submit your booking request instantly.'],
                ['bg-red-50','text-red-600','M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z','4. Ride Confirmed!','Our driver will be at your location on time. Sit back and relax.'],
            ]; @endphp
            @foreach($steps as [$bg,$color,$icon,$title,$desc])
            <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 shadow-sm">
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
    <p class="section-eyebrow mb-3" style="color:#C8102E;">Need a ride?</p>
    <h2 class="font-extrabold text-3xl mb-3" style="font-family:'Merriweather',Georgia,serif;">We'll get you there, on time.</h2>
    <p class="text-sm max-w-sm mx-auto mb-6" style="color:#A09890;">Airport transfers, city rides, or custom routes — book via WhatsApp or online.</p>
    <div class="flex flex-wrap justify-center gap-3">
        <a href="https://wa.me/8801335111370" target="_blank"
           class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-3.5 rounded-xl transition text-sm">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            WhatsApp Us
        </a>
        <a href="{{ route('contact') }}" style="border-color:#FAF6EE;color:#FAF6EE;" class="ota-btn-ghost">Contact Us</a>
    </div>
</section>

</x-app-layout>
