<x-app-layout>
    <x-slot name="title">{{ $hotel->name }} — FlyoverBD</x-slot>

    {{-- Hotel Header --}}
    <section class="bg-white border-b border-gray-100">
        @if($hotel->thumbnail)
        <div class="w-full h-72 md:h-96 overflow-hidden">
            <img src="{{ Storage::url($hotel->thumbnail) }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
        </div>
        @endif
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-xl text-amber-400 font-bold">
                            @for($i = 1; $i <= 5; $i++){{ $i <= $hotel->star_rating ? '★' : '☆' }}@endfor
                        </span>
                        <span class="text-sm text-gray-400 font-medium">{{ number_format($hotel->star_rating, 1) }}-star hotel</span>
                    </div>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2">{{ $hotel->name }}</h1>
                    <div class="flex items-center gap-2 text-gray-500 text-sm">
                        <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $hotel->location }}
                    </div>
                </div>
                @if($hotel->rooms->count())
                @php $minPrice = $hotel->rooms->min('price_per_night'); @endphp
                <div class="flex-shrink-0 text-right">
                    <p class="text-sm text-gray-400">from</p>
                    <p class="text-3xl font-extrabold text-red-600">৳{{ number_format($minPrice) }}</p>
                    <p class="text-xs text-gray-400">per night</p>
                </div>
                @endif
            </div>
        </div>
    </section>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-10 space-y-12">

        {{-- Description --}}
        @if($hotel->description)
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-4">About This Hotel</h2>
            <p class="text-gray-600 leading-relaxed">{{ $hotel->description }}</p>
        </section>
        @endif

        {{-- Amenities --}}
        @if($hotel->amenities && count($hotel->amenities))
        <section>
            <h2 class="text-xl font-bold text-gray-900 mb-4">Amenities</h2>
            <div class="flex flex-wrap gap-2">
                @foreach($hotel->amenities as $amenity)
                <span class="inline-flex items-center px-3 py-1.5 bg-red-50 text-red-700 text-sm font-semibold rounded-full border border-red-100">
                    {{ trim($amenity) }}
                </span>
                @endforeach
            </div>
        </section>
        @endif

        {{-- Rooms --}}
        @if($hotel->rooms->count())
        <section x-data="{
            showBooking: false,
            selectedRoom: null,
            checkIn: '',
            checkOut: '',
            guests: 1,
            get nights() {
                if (!this.checkIn || !this.checkOut) return 0;
                const d = (new Date(this.checkOut) - new Date(this.checkIn)) / 86400000;
                return d > 0 ? d : 0;
            },
            get total() {
                if (!this.selectedRoom) return 0;
                return this.nights * this.selectedRoom.price;
            },
            openBooking(room) {
                this.selectedRoom = room;
                this.guests = 1;
                this.showBooking = true;
            }
        }">
            <h2 class="text-xl font-bold text-gray-900 mb-6">Available Rooms</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($hotel->rooms as $room)
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition">
                    @if($room->image)
                        <img src="{{ Storage::url($room->image) }}" alt="{{ $room->name }}" class="w-full h-44 object-cover">
                    @endif
                    <div class="p-5">
                        <div class="flex items-start justify-between mb-2">
                            <h3 class="font-bold text-gray-900 text-base">{{ $room->name }}</h3>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600 capitalize ml-2 flex-shrink-0">{{ $room->room_type }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-sm text-gray-500 mb-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                Up to {{ $room->capacity }} guests
                            </span>
                        </div>
                        @if($room->description)
                        <p class="text-sm text-gray-500 mb-4">{{ Str::limit($room->description, 80) }}</p>
                        @endif
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl font-extrabold text-red-600">৳{{ number_format($room->price_per_night) }}</p>
                                <p class="text-xs text-gray-400">per night</p>
                            </div>
                            <button type="button"
                                @click="openBooking({ id: {{ $room->id }}, name: '{{ addslashes($room->name) }}', price: {{ $room->price_per_night }}, capacity: {{ $room->capacity }} })"
                                class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-xl transition text-sm">
                                Book
                            </button>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Booking Modal --}}
            <div x-show="showBooking"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 style="display:none;"
                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                <div @click.outside="showBooking = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="bg-white rounded-2xl shadow-2xl w-full max-w-md max-h-[90vh] overflow-y-auto p-6">

                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900" x-text="selectedRoom ? selectedRoom.name : ''"></h3>
                            <p class="text-sm text-gray-500">Complete your booking</p>
                        </div>
                        <button type="button" @click="showBooking = false" class="p-2 rounded-lg hover:bg-gray-100 transition">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    <form action="{{ route('hotels.book') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="room_id" :value="selectedRoom ? selectedRoom.id : ''">

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Check-in <span class="text-red-500">*</span></label>
                                <input type="date" name="check_in" x-model="checkIn"
                                    :min="new Date(Date.now() + 86400000).toISOString().split('T')[0]"
                                    required class="w-full py-2.5 border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-red-200">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1.5">Check-out <span class="text-red-500">*</span></label>
                                <input type="date" name="check_out" x-model="checkOut"
                                    :min="checkIn || new Date(Date.now() + 86400000 * 2).toISOString().split('T')[0]"
                                    required class="w-full py-2.5 border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-red-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Guests <span class="text-red-500">*</span></label>
                            <input type="number" name="guests" x-model="guests" min="1"
                                :max="selectedRoom ? selectedRoom.capacity : 10"
                                required class="w-full py-2.5 border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-red-200">
                        </div>

                        @guest
                        <div class="border-t pt-4 space-y-3">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-wide">Your Information</p>
                            <input type="text" name="guest_name" placeholder="Full Name *" required
                                class="w-full py-2.5 border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-red-200">
                            <input type="email" name="guest_email" placeholder="Email Address *" required
                                class="w-full py-2.5 border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-red-200">
                            <input type="tel" name="guest_phone" placeholder="Phone Number *" required
                                class="w-full py-2.5 border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-red-200">
                        </div>
                        @endguest

                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1.5">Special Request</label>
                            <textarea name="special_request" rows="2" placeholder="Any special requirements..."
                                class="w-full py-2.5 border-gray-300 rounded-lg text-sm focus:border-red-500 focus:ring-red-200"></textarea>
                        </div>

                        {{-- Dynamic total --}}
                        <div x-show="nights > 0" class="bg-red-50 rounded-xl p-4 border border-red-100">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="text-gray-600" x-text="`${nights} night${nights > 1 ? 's' : ''} × ৳${selectedRoom ? selectedRoom.price.toLocaleString() : 0}`"></span>
                            </div>
                            <div class="flex justify-between font-bold">
                                <span class="text-gray-700">Total</span>
                                <span class="text-red-600 text-lg" x-text="`৳${total.toLocaleString()}`"></span>
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition shadow-sm">
                            Confirm Booking
                        </button>
                    </form>
                </div>
            </div>
        </section>
        @else
        <div class="text-center py-12">
            <p class="text-gray-400">No rooms available at this time.</p>
        </div>
        @endif
    </div>

</x-app-layout>
