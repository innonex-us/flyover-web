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
        <p class="text-gray-500 max-w-md mx-auto mb-8">Handpicked hotels across Bangladesh's top destinations — book your perfect stay in minutes.</p>

        {{-- Search --}}
        <form action="{{ route('hotels.index') }}" method="GET" class="flex max-w-xl mx-auto gap-2 mb-5">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Search hotels or locations…"
                       class="search-field pl-11 py-3.5">
            </div>
            <button type="submit" class="btn-primary px-7 py-3.5 rounded-xl">Search</button>
            @if(request('search'))
            <a href="{{ route('hotels.index') }}" class="flex items-center px-4 py-3.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 hover:text-gray-700 transition">Clear</a>
            @endif
        </form>

        {{-- Location chips --}}
        <div class="flex flex-wrap justify-center gap-2">
            @foreach(["Cox's Bazar", "Dhaka", "Sylhet", "Sundarbans", "Bandarban", "Saint Martin"] as $loc)
            <a href="{{ route('hotels.index', ['search' => $loc]) }}"
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
                    <a href="{{ route('hotels.show', $hotel) }}"
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
