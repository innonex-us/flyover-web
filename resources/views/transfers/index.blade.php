<x-app-layout
    title="Pick & Drop Transfer Service | FlyoverBD"
    meta_description="Book hassle-free airport transfers and pick & drop services across Bangladesh. Fixed pricing, preset routes, or custom locations."
>

    {{-- Hero --}}
    <section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="px-4 py-14 text-center">
        <p class="section-eyebrow mb-2">Airport & City Transfers</p>
        <h1 class="font-extrabold text-4xl md:text-5xl text-gray-900 mb-3" style="font-family:'Merriweather',Georgia,serif;">
            Pick &amp; Drop Service
        </h1>
        <p class="text-gray-500 max-w-md mx-auto mb-6">Comfortable transfers between airports, hotels, and destinations — fixed pricing, no surprises.</p>

        {{-- Quick-search bar --}}
        <div class="max-w-4xl mx-auto mb-8"
             x-data="{
                 pickup: '{{ request('pickup') }}',
                 drop: '{{ request('drop') }}',
                 travelDate: '{{ request('travel_date') }}',
                 passengers: {{ request('passengers', 1) }}
             }">
            <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-2 flex flex-wrap gap-2 items-center">
                {{-- Pickup --}}
                <div class="flex-1 min-w-[150px] relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-green-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                    <input type="text" x-model="pickup" id="hero_pickup"
                           placeholder="Pickup location…"
                           class="w-full pl-9 pr-3 py-3 text-gray-800 text-sm outline-none rounded-xl border border-gray-100 focus:border-red-300"
                           autocomplete="off">
                </div>
                {{-- Drop --}}
                <div class="flex-1 min-w-[150px] relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-red-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21l1.9-5.7a8.5 8.5 0 1 1 3.8 3.8z"/></svg>
                    <input type="text" x-model="drop" id="hero_drop"
                           placeholder="Drop location…"
                           class="w-full pl-9 pr-3 py-3 text-gray-800 text-sm outline-none rounded-xl border border-gray-100 focus:border-red-300"
                           autocomplete="off">
                </div>
                {{-- Travel date --}}
                <div class="relative min-w-[140px]">
                    <label class="absolute -top-2 left-3 bg-white text-[10px] font-bold text-gray-400 px-1 uppercase tracking-wide">Date</label>
                    <input type="date" x-model="travelDate" id="hero_date"
                           :min="new Date(Date.now()+86400000).toISOString().split('T')[0]"
                           class="w-full py-3 px-3 text-gray-700 text-sm outline-none rounded-xl border border-gray-100 focus:border-red-300">
                </div>
                {{-- Passengers --}}
                <div class="relative w-24">
                    <label class="absolute -top-2 left-3 bg-white text-[10px] font-bold text-gray-400 px-1 uppercase tracking-wide">Persons</label>
                    <input type="number" x-model="passengers" id="hero_passengers" min="1" max="50"
                           class="w-full py-3 px-3 text-gray-700 text-sm outline-none rounded-xl border border-gray-100 focus:border-red-300 text-center">
                </div>
                <button type="button"
                        @click="
                            var fp = document.getElementById('form_pickup');
                            var fd = document.getElementById('form_drop');
                            var fdate = document.getElementById('form_travel_date');
                            var fpax = document.getElementById('form_passengers');
                            if(fp) fp.value = pickup;
                            if(fd) fd.value = drop;
                            if(fdate && travelDate) fdate.value = travelDate;
                            if(fpax && passengers) fpax.value = passengers;
                            document.getElementById('booking-form').scrollIntoView({behavior:'smooth'});
                        "
                        class="bg-red-600 hover:bg-red-700 text-white font-bold px-5 py-3 rounded-xl text-sm transition whitespace-nowrap">
                    Find Transfer
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

        {{-- Trust badges --}}
        <div class="flex flex-wrap justify-center gap-4 mb-6">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-700 bg-green-50 border border-green-200 rounded-full px-3 py-1">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Fixed Pricing
            </span>
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-full px-3 py-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Any Group Size
            </span>
            <span class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200 rounded-full px-3 py-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Custom Routes
            </span>
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
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2" style="font-family:'Merriweather',Georgia,serif;">Book a Transfer</h2>
                <p class="text-gray-500">Fill in your details and we'll arrange your transfer.</p>
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
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Transfer Type</label>
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
                            <option value="{{ $route->id }}">{{ $route->name }} — ৳{{ number_format($route->price_per_person) }}/person</option>
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

</x-app-layout>
