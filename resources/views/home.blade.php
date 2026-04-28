<x-app-layout>
    <!-- Hero Section -->
    <div 
        x-data="{
            activeSlide: 0,
            slides: [0, 1, 2],
            init() {
                setInterval(() => {
                    this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                }, 5000);
            }
        }"
        class="relative bg-white overflow-hidden h-[600px] flex items-center justify-center"
    >
        <!-- Background Images (Slider) - Hardcoded for Instant Loading -->
        <div 
            class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out"
            style="background-image: url('{{ asset('banner/hero-banner-1.png') }}')"
            :class="activeSlide === 0 ? 'opacity-100' : 'opacity-0'"
        ></div>
        
        <div 
            class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out"
            style="background-image: url('{{ asset('banner/helo-banner-2.png') }}')"
            :class="activeSlide === 1 ? 'opacity-100' : 'opacity-0'"
        ></div>

        <div 
            class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out"
            style="background-image: url('{{ asset('banner/hero-banner-3.png') }}')"
            :class="activeSlide === 2 ? 'opacity-100' : 'opacity-0'"
        ></div>
        
        <div class="absolute inset-0 bg-black/40"></div> <!-- Overlay for contrast -->

        <div class="relative z-10 text-left w-full max-w-4xl px-4">
            <h1 class="text-2xl md:text-4xl font-serif font-bold text-white mb-6 [text-shadow:0_2px_12px_rgba(0,0,0,0.55)]">
                Welcome to AamarTrip!
            </h1>
            <!-- Search Widget -->
            <div class="w-full max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl ring-1 ring-gray-900/10 p-5 md:p-8 text-gray-900" 
                x-data="{ 
                    activeTab: 'tours',
                    query: '',
                    flightFrom: 'DAC',
                    flightTo: '',
                    hotelCity: '',
                    suggestions: [],
                    showSuggestions: false,
                    loading: false,
                    fetchTimer: null,
                    
                    fetchSuggestions() {
                        this.loading = true;
                        clearTimeout(this.fetchTimer);
                        this.fetchTimer = setTimeout(() => {
                            fetch(`{{ route('search.suggestions') }}?type=${this.activeTab}&query=${this.query}`)
                                .then(res => res.json())
                                .then(data => {
                                    this.suggestions = data;
                                    this.showSuggestions = true;
                                    this.loading = false;
                                })
                                .catch(() => {
                                    this.loading = false;
                                });
                        }, 300);
                    },
                    selectSuggestion(url) {
                        window.location.href = url;
                    },
                    switchTab(tab) {
                        this.activeTab = tab;
                        this.query = '';
                        this.suggestions = [];
                        this.showSuggestions = false;
                    },
                    swapFlightEnds() {
                        const a = this.flightFrom;
                        this.flightFrom = this.flightTo;
                        this.flightTo = a;
                    },
                    searchFlight() {
                        const from = (this.flightFrom || '').trim().toUpperCase();
                        const to = (this.flightTo || '').trim().toUpperCase();
                        const iataRule = /^[A-Z]{3}$/;

                        if (!iataRule.test(from) || !iataRule.test(to)) {
                            alert('Use valid 3-letter airport codes. Example: DAC to DXB.');
                            return;
                        }

                        if (from === to) {
                            alert('Origin and destination must be different.');
                            return;
                        }

                        this.flightFrom = from;
                        this.flightTo = to;

                        const q = encodeURIComponent(`Flights to ${to} from ${from}`);
                        window.location.href = `https://www.google.com/travel/flights?q=${q}&hl=en&gl=GB&curr=GBP`;
                    },
                    searchHotel() {
                        const city = (this.hotelCity || '').trim();
                        if (!city) {
                            alert('Enter a city, area, or hotel name to search.');
                            return;
                        }
                        this.hotelCity = city;
                        const q = encodeURIComponent(`Hotels in ${city}`);
                        window.location.href = `https://www.google.com/travel/hotels?q=${q}&hl=en&gl=GB`;
                    }
                }"
                @click.away="showSuggestions = false"
            >
                
                <!-- Tabs -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-2 md:gap-2 bg-gray-100 rounded-xl p-1.5 mb-6">
                    <button 
                        type="button"
                        @click="switchTab('tours')" 
                        :class="activeTab === 'tours' ? 'bg-white text-red-700 shadow-sm ring-1 ring-gray-300' : 'text-gray-800 hover:text-red-700 hover:bg-white/80'"
                        class="flex items-center justify-center gap-2 rounded-lg py-2.5 px-2 sm:px-3 font-semibold transition text-sm md:text-base"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span class="truncate">Tours</span>
                    </button>
                    <button 
                        type="button"
                        @click="switchTab('visas')" 
                        :class="activeTab === 'visas' ? 'bg-white text-red-700 shadow-sm ring-1 ring-gray-300' : 'text-gray-800 hover:text-red-700 hover:bg-white/80'"
                        class="flex items-center justify-center gap-2 rounded-lg py-2.5 px-2 sm:px-3 font-semibold transition text-sm md:text-base"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                        <span class="truncate">Visas</span>
                    </button>
                    <button 
                        type="button"
                        @click="switchTab('flights')" 
                        :class="activeTab === 'flights' ? 'bg-white text-red-700 shadow-sm ring-1 ring-gray-300' : 'text-gray-800 hover:text-red-700 hover:bg-white/80'"
                        class="flex items-center justify-center gap-2 rounded-lg py-2.5 px-2 sm:px-3 font-semibold transition text-sm md:text-base"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        <span class="truncate">Flights</span>
                    </button>
                    <button 
                        type="button"
                        @click="switchTab('hotels')" 
                        :class="activeTab === 'hotels' ? 'bg-white text-red-700 shadow-sm ring-1 ring-gray-300' : 'text-gray-800 hover:text-red-700 hover:bg-white/80'"
                        class="flex items-center justify-center gap-2 rounded-lg py-2.5 px-2 sm:px-3 font-semibold transition text-sm md:text-base"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 10V8a2 2 0 012-2h10a2 2 0 012 2v2M5 10v10a1 1 0 001 1h2a1 1 0 001-1v-4h6v4a1 1 0 001 1h2a1 1 0 001-1V10M9 6h6v4H9V6z"/></svg>
                        <span class="truncate">Hotels</span>
                    </button>
                </div>

                <!-- Tours Search Form -->
                <div x-show="activeTab === 'tours'" class="animate-fade-in-up relative">
                    <form action="{{ route('packages.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-grow">
                            <label class="block text-left text-sm font-bold text-gray-800 uppercase tracking-wide mb-1.5">Destination</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="search" 
                                    x-model="query"
                                    @input="fetchSuggestions()"
                                    @focus="fetchSuggestions()"
                                    placeholder="Where do you want to go?" 
                                    class="pl-10 w-full border border-gray-300 rounded-xl bg-white shadow-sm focus:border-red-600 focus:ring-2 focus:ring-red-100 py-3 text-base text-gray-900 placeholder:text-gray-600"
                                    autocomplete="off"
                                >
                            </div>
                                                       <!-- Suggestions Dropdown -->
                            <div 
                                x-show="showSuggestions && activeTab === 'tours'" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute top-full left-0 w-full mt-2 bg-white rounded-xl shadow-2xl z-50 text-left overflow-hidden border border-gray-100"
                                style="display: none;"
                            >
                                <div class="p-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wide" x-text="query ? 'Search Results' : 'Latest Packages'"></span>
                                    <div x-show="loading" class="animate-spin rounded-full h-3 w-3 border-b-2 border-red-600"></div>
                                </div>
                                <ul class="divide-y divide-gray-50 max-h-[400px] overflow-y-auto scrollbar-hide">
                                    <template x-for="item in suggestions" :key="item.slug">
                                        <li @click="selectSuggestion(item.url)" class="px-4 py-3 hover:bg-red-50/50 cursor-pointer transition-colors duration-200 flex items-center group">
                                            <div class="relative w-12 h-12 shrink-0 mr-4 rounded-lg overflow-hidden shadow-sm">
                                                <img :src="item.image" class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110" alt="">
                                                <div class="absolute inset-0 bg-black/5"></div>
                                            </div>
                                            <div class="flex-grow min-w-0">
                                                <div class="text-sm font-bold text-gray-900 truncate group-hover:text-red-600 transition-colors" x-text="item.text"></div>
                                                <div class="text-xs text-gray-700 flex items-center mt-0.5">
                                                    <svg class="w-3.5 h-3.5 mr-1 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    <span class="truncate" x-text="item.subtext"></span>
                                                </div>
                                            </div>
                                            <div class="shrink-0 ml-2 opacity-0 group-hover:opacity-100 transition-opacity translate-x-1 group-hover:translate-x-0">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </li>
                                    </template>
                                    <li x-show="suggestions.length === 0 && !loading" class="px-4 py-8 text-center">
                                        <div class="text-gray-500 mb-2">
                                            <svg class="w-9 h-9 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">No matching tours found</p>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <div class="w-full md:w-auto flex items-end">
                            <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-red-600 text-white font-bold text-base py-3 px-8 md:px-10 rounded-xl hover:bg-red-700 transition shadow-md hover:shadow-lg">
                                <svg class="h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Search Tours
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Visa Search Form -->
                <div x-show="activeTab === 'visas'" class="animate-fade-in-up relative" style="display: none;">
                    <form action="{{ route('visas.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                         <div class="flex-grow">
                            <label class="block text-left text-sm font-bold text-gray-800 uppercase tracking-wide mb-1.5">Country</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="search" 
                                    x-model="query"
                                    @input="fetchSuggestions()"
                                    @focus="fetchSuggestions()"
                                    placeholder="Which country visa do you need?" 
                                    class="pl-10 w-full border border-gray-300 rounded-xl bg-white shadow-sm focus:border-red-600 focus:ring-2 focus:ring-red-100 py-3 text-base text-gray-900 placeholder:text-gray-600"
                                    autocomplete="off"
                                >
                            </div>

                             <!-- Suggestions Dropdown (Visas) -->
                             <div 
                                x-show="showSuggestions && activeTab === 'visas'" 
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                class="absolute top-full left-0 w-full mt-2 bg-white rounded-xl shadow-2xl z-50 text-left overflow-hidden border border-gray-100"
                                style="display: none;"
                            >
                                <div class="p-3 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
                                    <span class="text-xs font-bold text-gray-700 uppercase tracking-wide" x-text="query ? 'Search Results' : 'Latest Visas'"></span>
                                    <div x-show="loading" class="animate-spin rounded-full h-3 w-3 border-b-2 border-red-600"></div>
                                </div>
                                <ul class="divide-y divide-gray-50 max-h-[400px] overflow-y-auto scrollbar-hide">
                                    <template x-for="item in suggestions" :key="item.slug">
                                        <li @click="selectSuggestion(item.url)" class="px-4 py-3 hover:bg-red-50/50 cursor-pointer transition-colors duration-200 flex items-center group">
                                            <div class="relative w-12 h-12 shrink-0 mr-4 rounded-lg overflow-hidden bg-red-50 flex items-center justify-center">
                                                <img x-show="item.image" :src="item.image" class="w-full h-full object-cover" alt="">
                                                <span x-show="!item.image" class="text-xl">🌍</span>
                                                <div class="absolute inset-0 bg-black/5"></div>
                                            </div>
                                            <div class="flex-grow min-w-0">
                                                <div class="text-sm font-bold text-gray-900 truncate group-hover:text-red-600 transition-colors" x-text="item.text"></div>
                                                <div class="text-xs text-gray-700 mt-0.5" x-text="item.subtext"></div>
                                            </div>
                                            <div class="shrink-0 ml-2 opacity-0 group-hover:opacity-100 transition-opacity translate-x-1 group-hover:translate-x-0">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </li>
                                    </template>
                                    <li x-show="suggestions.length === 0 && !loading" class="px-4 py-8 text-center">
                                        <div class="text-gray-500 mb-2">
                                            <svg class="w-9 h-9 mx-auto" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <p class="text-sm font-medium text-gray-700">No matching visas found</p>
                                    </li>
                                </ul>
                            </div>

                        </div>
                         <div class="w-full md:w-auto flex items-end">
                            <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-red-600 text-white font-bold text-base py-3 px-8 md:px-10 rounded-xl hover:bg-red-700 transition shadow-md hover:shadow-lg">
                                <svg class="h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Search Visa
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Flight Search Form -->
                <div x-show="activeTab === 'flights'" class="animate-fade-in-up relative" style="display: none;">
                    <form @submit.prevent="searchFlight()" class="flex flex-col gap-4">
                        <div class="flex flex-col md:flex-row md:items-end gap-3 md:gap-4">
                            <div class="flex-1 min-w-0">
                                <label class="block text-left text-sm font-bold text-gray-800 uppercase tracking-wide mb-1.5">From</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-600" aria-hidden="true">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    </span>
                                    <input 
                                        type="text" 
                                        x-model="flightFrom"
                                        placeholder="DAC"
                                        inputmode="text"
                                        autocomplete="off"
                                        aria-label="Departure airport IATA code"
                                        class="pl-10 w-full border border-gray-300 rounded-xl bg-white shadow-sm focus:border-red-600 focus:ring-2 focus:ring-red-100 py-3 text-base uppercase tracking-widest font-semibold text-gray-900 placeholder:font-normal placeholder:tracking-normal placeholder:text-gray-600"
                                        maxlength="3"
                                        @input="flightFrom = flightFrom.replace(/[^a-zA-Z]/g, '').toUpperCase()"
                                    >
                                </div>
                                <p class="mt-1.5 text-xs font-medium text-gray-700">3-letter airport code (IATA)</p>
                            </div>
                            <div class="flex justify-center md:pb-2 shrink-0">
                                <button type="button" @click="swapFlightEnds()" class="rounded-full border border-gray-300 bg-white p-2.5 text-gray-700 shadow-sm hover:border-red-300 hover:text-red-700 hover:bg-red-50 transition" title="Swap origin and destination" aria-label="Swap origin and destination">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/></svg>
                                </button>
                            </div>
                            <div class="flex-1 min-w-0">
                                <label class="block text-left text-sm font-bold text-gray-800 uppercase tracking-wide mb-1.5">To</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-600" aria-hidden="true">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </span>
                                    <input 
                                        type="text" 
                                        x-model="flightTo"
                                        placeholder="DXB"
                                        inputmode="text"
                                        autocomplete="off"
                                        aria-label="Arrival airport IATA code"
                                        class="pl-10 w-full border border-gray-300 rounded-xl bg-white shadow-sm focus:border-red-600 focus:ring-2 focus:ring-red-100 py-3 text-base uppercase tracking-widest font-semibold text-gray-900 placeholder:font-normal placeholder:tracking-normal placeholder:text-gray-600"
                                        maxlength="3"
                                        @input="flightTo = flightTo.replace(/[^a-zA-Z]/g, '').toUpperCase()"
                                    >
                                </div>
                                <p class="mt-1.5 text-xs font-medium text-gray-700">3-letter airport code (IATA)</p>
                            </div>
                            <div class="w-full md:w-auto md:shrink-0 flex items-end">
                                <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-red-600 text-white font-bold text-base py-3 px-8 md:px-10 rounded-xl hover:bg-red-700 transition shadow-md hover:shadow-lg">
                                    <svg class="h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                    Search Flights
                                </button>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 leading-relaxed">Opens Google Flights for your route (United Kingdom locale, GBP).</p>
                    </form>
                </div>

                <!-- Hotel Search Form -->
                <div x-show="activeTab === 'hotels'" class="animate-fade-in-up relative" style="display: none;">
                    <form @submit.prevent="searchHotel()" class="flex flex-col md:flex-row gap-4 md:items-end">
                        <div class="flex-grow min-w-0">
                            <label class="block text-left text-sm font-bold text-gray-800 uppercase tracking-wide mb-1.5">Destination</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-600" aria-hidden="true">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 10V8a2 2 0 012-2h10a2 2 0 012 2v2M5 10v10a1 1 0 001 1h2a1 1 0 001-1v-4h6v4a1 1 0 001 1h2a1 1 0 001-1V10M9 6h6v4H9V6z"/></svg>
                                </span>
                                <input 
                                    type="text" 
                                    x-model="hotelCity"
                                    placeholder="e.g. Dubai, Cox's Bazar, Bangkok"
                                    autocomplete="off"
                                    aria-label="City or hotel search"
                                    class="pl-10 w-full border border-gray-300 rounded-xl bg-white shadow-sm focus:border-red-600 focus:ring-2 focus:ring-red-100 py-3 text-base text-gray-900 placeholder:text-gray-600"
                                >
                            </div>
                        </div>
                        <div class="w-full md:w-auto shrink-0 flex items-end">
                            <button type="submit" class="w-full md:w-auto inline-flex items-center justify-center gap-2 bg-red-600 text-white font-bold text-base py-3 px-8 md:px-10 rounded-xl hover:bg-red-700 transition shadow-md hover:shadow-lg">
                                <svg class="h-5 w-5 shrink-0 text-white" fill="none" stroke="currentColor" stroke-width="2.25" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M5 10V8a2 2 0 012-2h10a2 2 0 012 2v2M5 10v10a1 1 0 001 1h2a1 1 0 001-1v-4h6v4a1 1 0 001 1h2a1 1 0 001-1V10M9 6h6v4H9V6z"/></svg>
                                Search Hotels
                            </button>
                        </div>
                    </form>
                    <p class="mt-3 text-sm text-gray-700 leading-relaxed">Opens Google Hotels for your search (United Kingdom locale).</p>
                </div>

            </div>
        </div>
    </div>

    <!-- Featured Tours Section -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Featured Tour Packages</h2>
            
            @if($packages->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($packages as $package)
                        <x-package-card :package="$package" />
                    @endforeach
                </div>
                <div class="mt-12 text-center">
                    <a href="{{ route('packages.index') }}" class="inline-block border-2 border-red-600 text-red-600 font-bold py-3 px-8 rounded-md hover:bg-red-600 hover:text-white transition">
                        View All Packages
                    </a>
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <p class="text-gray-500">No tour packages available at the moment.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Visa Services Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Visa Processing Services</h2>
            
            @if(isset($visas) && $visas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @foreach($visas as $visa)
                        <x-visa-card :visa="$visa" />
                    @endforeach
                </div>
                <div class="mt-12 text-center">
                    <a href="{{ route('visas.index') }}" class="inline-block border-2 border-red-600 text-red-600 font-bold py-3 px-8 rounded-md hover:bg-red-600 hover:text-white transition">
                        View All Visa Services
                    </a>
                </div>
            @else
                <div class="text-center py-12 bg-white rounded-lg border border-dashed border-gray-300">
                    <p class="text-gray-500">Visa services coming soon.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Latest Blog Posts -->
    <div class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Latest Travel Insights</h2>

            @if(isset($recentPosts) && $recentPosts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach($recentPosts as $post)
                        <article class="flex flex-col bg-white rounded-2xl shadow-lg hover:shadow-xl transition overflow-hidden border border-gray-100 group">
                            <a href="{{ route('blog.show', $post->slug) }}" class="overflow-hidden h-56 relative">
                                <img src="{{ $post->image ? Storage::url($post->image) : 'https://via.placeholder.com/800x600?text=AamarTrip+Blog' }}" 
                                     alt="{{ $post->title }}" 
                                     class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500 ease-in-out">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </a>
                            <div class="flex-1 p-6 flex flex-col">
                                <div class="flex items-center text-sm text-gray-500 mb-3 space-x-4">
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}
                                    </span>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-red-600 transition">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>
                                <p class="text-gray-600 mb-4 flex-1 line-clamp-3">
                                    {{ $post->seo_description ?? Str::limit(strip_tags($post->content), 100) }}
                                </p>
                                <div class="mt-auto">
                                    <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center text-red-600 font-semibold hover:text-red-700">
                                        Read Article 
                                        <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
                <div class="mt-12 text-center">
                    <a href="{{ route('blog.index') }}" class="inline-block border-2 border-red-600 text-red-600 font-bold py-3 px-8 rounded-md hover:bg-red-600 hover:text-white transition">
                        Read More Articles
                    </a>
                </div>
            @else
                <div class="text-center py-12 bg-gray-50 rounded-lg">
                    <p class="text-gray-500">No blog posts available at the moment.</p>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
