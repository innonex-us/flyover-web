<x-app-layout
    :title="$title"
    :meta_description="$meta_description"
    :meta_image="$meta_image"
>

    {{-- Hotel Hero --}}
    @if($hotel->thumbnail)
    <div class="w-full h-72 md:h-[420px] overflow-hidden relative">
        <img src="{{ Str::startsWith($hotel->thumbnail, 'http') ? $hotel->thumbnail : Storage::url($hotel->thumbnail) }}" alt="{{ $hotel->name }}" class="w-full h-full object-cover">
        <div class="absolute inset-0" style="background:linear-gradient(to top, rgba(0,0,0,0.55) 0%, transparent 60%);"></div>
        <div class="absolute bottom-0 left-0 right-0 px-4 sm:px-6 lg:px-8 pb-8 max-w-5xl mx-auto">
            <a href="{{ route('hotels.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-white/80 hover:text-white mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                All Hotels
            </a>
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <span class="text-lg text-amber-400 font-bold block mb-1">@for($i = 1; $i <= 5; $i++){{ $i <= $hotel->star_rating ? '★' : '☆' }}@endfor</span>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-white mb-2" style="font-family:'Merriweather',Georgia,serif;">{{ $hotel->name }}</h1>
                    <div class="flex items-center gap-2 text-white/80 text-sm">
                        <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $hotel->location }}
                    </div>
                </div>
                @if($hotel->rooms->count())
                @php $minPrice = $hotel->rooms->min('price_per_night'); @endphp
                <div class="flex-shrink-0 bg-white/10 backdrop-blur-sm rounded-2xl px-5 py-3 text-white border border-white/20">
                    <p class="text-xs text-white/60 font-semibold uppercase tracking-wide">Starting from</p>
                    <p class="text-3xl font-extrabold">৳{{ number_format($minPrice) }}</p>
                    <p class="text-xs text-white/60">per night</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="px-4 py-12">
        <div class="max-w-5xl mx-auto">
            <a href="{{ route('hotels.index') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-gray-500 hover:text-gray-800 mb-4 transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                All Hotels
            </a>
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <span class="text-xl text-amber-400 font-bold block mb-1">@for($i = 1; $i <= 5; $i++){{ $i <= $hotel->star_rating ? '★' : '☆' }}@endfor</span>
                    <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2" style="font-family:'Merriweather',Georgia,serif;">{{ $hotel->name }}</h1>
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
    @endif

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            {{-- ── Left Column: Gallery & Main Content ── --}}
            <div class="lg:col-span-2 space-y-8">
                
                {{-- Hotel gallery --}}
                @php
                    $hotelDefault = asset('banner/hero-banner-1.png');
                    $hotelGallery = [];
                    if ($hotel->thumbnail) {
                        $hotelGallery[] = \Illuminate\Support\Str::startsWith($hotel->thumbnail, 'http') ? $hotel->thumbnail : Storage::url($hotel->thumbnail);
                    }
                    if (!empty($hotel->images) && is_array($hotel->images)) {
                        foreach ($hotel->images as $img) {
                            $hotelGallery[] = \Illuminate\Support\Str::startsWith($img, 'http') ? $img : Storage::url($img);
                        }
                    }
                    if ($hotelGallery === []) $hotelGallery = [$hotelDefault];
                @endphp

                @include('components.photo-gallery', ['images' => $hotelGallery, 'alt' => $hotel->name])

                {{-- Description --}}
                @if($hotel->description)
                <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                    <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-5 rounded-full" style="background:#C8102E;"></span>
                        About This Hotel
                    </h2>
                    <div class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
                        {{ $hotel->description }}
                    </div>
                </div>
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
                    },
                    submitting: false,
                    async submitBooking(e) {
                        this.submitting = true;
                        const form = e.target;
                        const data = new FormData(form);

                        try {
                            const res = await fetch('{{ route('hotels.book') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Accept': 'application/json',
                                },
                                body: data,
                            });

                            const json = await res.json();

                            if (res.ok && json.redirect_url) {
                                // Trigger toast notification
                                window.dispatchEvent(new CustomEvent('notify', {
                                    detail: { type: 'success', message: json.message || 'Booking confirmed! Redirecting to payment...' }
                                }));
                                this.showBooking = false;
                                // Redirect to payment after a short delay
                                setTimeout(() => { window.location.href = json.redirect_url; }, 1500);
                            } else {
                                window.dispatchEvent(new CustomEvent('notify', {
                                    detail: { type: 'error', message: json.message || 'Booking failed. Please try again.' }
                                }));
                            }
                        } catch (err) {
                            window.dispatchEvent(new CustomEvent('notify', {
                                detail: { type: 'error', message: 'Something went wrong. Please try again.' }
                            }));
                        } finally {
                            this.submitting = false;
                        }
                    }
                }">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 rounded-full" style="background:#C8102E;"></span>
                        Available Rooms
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($hotel->rooms as $room)
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden hover:shadow-md transition">
                            @if($room->image)
                                <img src="{{ $room->image ? (Str::startsWith($room->image, 'http') ? $room->image : Storage::url($room->image)) : $hotelDefault }}" alt="{{ $room->name }}" class="w-full h-44 object-cover">
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

                            <form @submit.prevent="submitBooking" class="space-y-4">
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

                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-xl transition shadow-sm"
                                    x-text="submitting ? 'Processing...' : 'Confirm Booking'"
                                    :disabled="submitting">
                                </button>
                            </form>
                        </div>
                    </div>
                </section>
                @else
                <div class="text-center py-12 bg-white rounded-2xl border border-gray-100">
                    <p class="text-gray-400">No rooms available at this time.</p>
                </div>
                @endif
            </div>

            {{-- ── Right Column: Sidebar ── --}}
            <div class="lg:col-span-1 space-y-6">
                
                {{-- Quick Info Card --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sticky top-24">
                    <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                        <span class="w-1 h-4 rounded-full" style="background:#C8102E;"></span>
                        Hotel Overview
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-red-50 text-red-600 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Location</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $hotel->location }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <div class="w-10 h-10 bg-amber-50 text-amber-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Rating</p>
                                <p class="text-sm font-semibold text-gray-800">{{ $hotel->star_rating }} Star Hotel</p>
                            </div>
                        </div>
                    </div>

                    {{-- Amenities --}}
                    @if($hotel->amenities && count($hotel->amenities))
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <h4 class="text-xs font-bold text-gray-900 uppercase tracking-wider mb-3">Amenities</h4>
                        <div class="flex flex-wrap gap-2">
                            @foreach($hotel->amenities as $amenity)
                            <span class="inline-flex items-center px-2.5 py-1 bg-gray-50 text-gray-600 text-xs font-medium rounded-lg border border-gray-100">
                                {{ trim($amenity) }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Share --}}
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <x-share-buttons :title="$hotel->name" />
                    </div>
                </div>

            </div>
        </div>
    </div>

</x-app-layout>
