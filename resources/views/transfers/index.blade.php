<x-app-layout>
    <x-slot name="title">Pick & Drop Transfer Service — FlyoverBD</x-slot>

    {{-- Hero --}}
    <section class="relative bg-gradient-to-br from-gray-900 via-gray-800 to-red-900 text-white py-20 px-4">
        <div class="max-w-4xl mx-auto text-center">
            <div class="inline-flex items-center gap-2 bg-red-600/20 border border-red-500/30 text-red-300 text-xs font-bold uppercase tracking-widest px-4 py-2 rounded-full mb-6">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                Pick & Drop Transfer
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">Comfortable <span class="text-red-400">Transfers</span></h1>
            <p class="text-lg text-gray-300 max-w-xl mx-auto">Book hassle-free transfers between airports, hotels, and tourist destinations across Bangladesh.</p>
        </div>
    </section>

    {{-- Preset Routes --}}
    @if($routes->count())
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="mb-10">
            <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Available Routes</h2>
            <p class="text-gray-500">Choose from our preset transfer routes with fixed pricing.</p>
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
    </section>
    @endif

    {{-- Booking Form --}}
    <section id="booking-form" class="bg-gray-50 py-16 px-4">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-2xl font-extrabold text-gray-900 mb-2">Book a Transfer</h2>
                <p class="text-gray-500">Fill in your details and we'll arrange your transfer.</p>
            </div>

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8" x-data="{
                isCustom: false,
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

                <form action="{{ route('transfers.store') }}" method="POST">
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
                            <input type="text" name="pickup_location" value="{{ old('pickup_location') }}"
                                placeholder="e.g. Dhaka Airport"
                                class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Drop Location</label>
                            <input type="text" name="drop_location" value="{{ old('drop_location') }}"
                                placeholder="e.g. Hotel Name, Cox's Bazar"
                                class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                        </div>
                    </div>

                    {{-- Trip Details --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Passengers <span class="text-red-500">*</span></label>
                            <input type="number" name="passenger_count" value="{{ old('passenger_count', 1) }}" min="1" required
                                class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                            @error('passenger_count') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Travel Date <span class="text-red-500">*</span></label>
                            <input type="date" name="travel_date" value="{{ old('travel_date') }}" required
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
