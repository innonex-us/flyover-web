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

        <div class="relative z-10 text-left w-full max-w-2xl px-4">
            <h1 class="text-2xl md:text-3xl font-serif font-bold text-white mb-6 drop-shadow-md">
                Welcome to FLYOVER!
            </h1>
            <!-- Search Widget -->
            <div class="w-full max-w-2xl mx-auto bg-white/95 backdrop-blur-sm rounded-xl shadow-2xl p-8" 
                x-data="{ 
                    activeTab: 'tours',
                    query: '',
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
                    }
                }"
                @click.away="showSuggestions = false"
            >
                
                <!-- Tabs -->
                <div class="flex space-x-8 border-b border-gray-200 pb-4 mb-6 justify-center">
                    <button 
                        @click="switchTab('tours')" 
                        :class="activeTab === 'tours' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-red-600'"
                        class="flex items-center pb-2 font-bold transition duration-300 text-lg"
                    >
                        <!-- Flight/Plane Icon -->
                         <img src="data:image/png;base64,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" alt="around-the-globe"  alt="Tour" class="w-6 h-6 mr-2">
                        Tour Packages
                    </button>
                    
                    <button 
                        @click="switchTab('visas')" 
                        :class="activeTab === 'visas' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-500 hover:text-red-600'"
                        class="flex items-center pb-2 font-bold transition duration-300 text-lg"
                    >
                        <!-- Passport/Document Icon -->
                        <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAACWUlEQVR4nO2avW7UQBDHf6K4JDSYAgTp8gKhgAroQEI08Aa5VGmiVERECTUEXgB4CEiegAgIygctke6OD4lEuRR8BKGjXxTpb2kVGZ+9vvWai0cayTPeWc3P3t0ZS4YTIA3gCXAAmMDaBZaVU255XAEAc0wfuYB0FXyV8HLNejO5JX4KVRHjmk8N4klMDcIJB4mAaWAVaAN/pG35mhpTVj7kDRwFFoBfGerBIXBfMb7ycQocB7YTEn5lXa8l3N8CLlYFZBzY1divwEtdfwLGrHnG5DMaE8fsZoQxPkFGrTexqbX/UfadhHnu6roDnD0WOxISZEFjPgPngCvWUz6VMM+Rb0/2ZeA88EX2fCiQyNrYt+R7IPtpyjzPZS/Jvm0dAFEIkGndf2354v0xkzLPjOwXlm9dvmaBfJwDV3V/1vLtyHcjZZ6bsj9YvjnrECgdJN7Uk5bvp3wTKfNMyP5u+S7J1y6Qj3NgL0Phy6u9YQH5HQKkk7C0fuRYWt8SllarQD7OgSvDstmbuv9mAMfvO/mmCuTjHBipiNkFcUn2swwFcTGhIJ4JAYJa8UG1KPcGkE+hpnFL47bVCHZyNI3vZW+EbhpRC2635P9q40/rzcWbes9q/S/QX4xvkBhmM6EurPX5sNrICFEaCFoa89YBkKaH2hMjHvMpHBjpaF5RgetJW1pSU31Op8qA+BJTg1CDeBFTg1CDVB+koe9q41nXywBplQDy1jdISDE1CDWIFzE1CEMC0lXg0e8ToeW6ctl3CV4uoV7k1YcuIA3BxG8mpO4Lwuk3p/9K/gI5HHNwyRPgDAAAAABJRU5ErkJggg==" alt="passport" class="h-6 w-6 mr-2">
                        Visa Service
                    </button>
                </div>

                <!-- Tours Search Form -->
                <div x-show="activeTab === 'tours'" class="animate-fade-in-up relative">
                    <form action="{{ route('packages.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                        <div class="flex-grow">
                            <label class="block text-left text-xs font-bold text-gray-500 uppercase mb-1">Destination</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="search" 
                                    x-model="query"
                                    @input="fetchSuggestions()"
                                    @focus="fetchSuggestions()"
                                    placeholder="Where do you want to go?" 
                                    class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 py-3"
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
                                <div class="p-3 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="query ? 'Search Results' : 'Latest Packages'"></span>
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
                                                <div class="text-[11px] text-gray-500 flex items-center mt-0.5">
                                                    <svg class="w-3 h-3 mr-1 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                                    <span class="truncate" x-text="item.subtext"></span>
                                                </div>
                                            </div>
                                            <div class="shrink-0 ml-2 opacity-0 group-hover:opacity-100 transition-opacity translate-x-1 group-hover:translate-x-0">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </li>
                                    </template>
                                    <li x-show="suggestions.length === 0 && !loading" class="px-4 py-8 text-center">
                                        <div class="text-gray-400 mb-2">
                                            <svg class="w-8 h-8 mx-auto opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <p class="text-sm text-gray-500">No matching tours found</p>
                                    </li>
                                </ul>
                            </div>

                        </div>
                        <div class="w-full md:w-auto flex items-end">
                            <button type="submit" class="w-full md:w-auto bg-red-600 text-white font-bold py-3 px-10 rounded-lg hover:bg-red-700 transition shadow-lg transform hover:-translate-y-0.5">
                                Search Tours
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Visa Search Form -->
                <div x-show="activeTab === 'visas'" class="animate-fade-in-up relative" style="display: none;">
                    <form action="{{ route('visas.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                         <div class="flex-grow">
                            <label class="block text-left text-xs font-bold text-gray-500 uppercase mb-1">Country</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <input 
                                    type="text" 
                                    name="search" 
                                    x-model="query"
                                    @input="fetchSuggestions()"
                                    @focus="fetchSuggestions()"
                                    placeholder="Which country visa do you need?" 
                                    class="pl-10 w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50 py-3"
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
                                <div class="p-3 border-b border-gray-50 bg-gray-50/50 flex justify-between items-center">
                                    <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest" x-text="query ? 'Search Results' : 'Latest Visas'"></span>
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
                                                <div class="text-[11px] text-gray-500 mt-0.5" x-text="item.subtext"></div>
                                            </div>
                                            <div class="shrink-0 ml-2 opacity-0 group-hover:opacity-100 transition-opacity translate-x-1 group-hover:translate-x-0">
                                                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                            </div>
                                        </li>
                                    </template>
                                    <li x-show="suggestions.length === 0 && !loading" class="px-4 py-8 text-center">
                                        <div class="text-gray-400 mb-2">
                                            <svg class="w-8 h-8 mx-auto opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                        </div>
                                        <p class="text-sm text-gray-500">No matching visas found</p>
                                    </li>
                                </ul>
                            </div>

                        </div>
                         <div class="w-full md:w-auto flex items-end">
                            <button type="submit" class="w-full md:w-auto bg-red-600 text-white font-bold py-3 px-10 rounded-lg hover:bg-red-700 transition shadow-lg transform hover:-translate-y-0.5">
                                Search Visa
                            </button>
                        </div>
                    </form>
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
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                            <!-- Image Slider -->
                            <div class="relative h-48 overflow-hidden group"
                                x-data="{
                                    activeSlide: 0,
                                    slides: [
                                        '{{ $package->thumbnail ? Storage::url($package->thumbnail) : 'https://via.placeholder.com/640x360?text=Tour+Package' }}',
                                        @if(!empty($package->images) && is_array($package->images))
                                            @foreach($package->images as $img)
                                                '{{ Storage::url($img) }}',
                                            @endforeach
                                        @endif
                                    ],
                                    init() {
                                        if (this.slides.length > 1) {
                                            setInterval(() => {
                                                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                                            }, 3000 + (Math.random() * 2000)); // Random offset to prevent synchronized sliding
                                        }
                                    }
                                }"
                            >
                                <a href="{{ route('packages.show', $package->slug) }}" class="block w-full h-full">
                                    <template x-for="(slide, index) in slides" :key="index">
                                        <div 
                                            class="absolute inset-0 bg-cover bg-center transition-opacity duration-700 ease-in-out"
                                            :style="`background-image: url('${slide}')`"
                                            :class="activeSlide === index ? 'opacity-100' : 'opacity-0'"
                                        ></div>
                                    </template>
                                    
                                    <!-- Static First Image (LCP) -->
                                    <div 
                                        class="absolute inset-0 bg-cover bg-center opacity-100 x-cloak-hidden"
                                        style="background-image: url('{{ $package->thumbnail ? Storage::url($package->thumbnail) : 'https://via.placeholder.com/640x360?text=Tour+Package' }}'); z-index: 10;"
                                        x-show="false"
                                    ></div>
                                </a>
                            </div>
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $package->title }}</h3>
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $package->duration_days }} Days</span>
                                </div>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $package->description }}</p>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-red-600">৳{{ number_format($package->price) }}</span>
                                    <a href="{{ route('packages.show', $package->slug) }}" class="text-red-600 font-semibold hover:text-red-700">View Details &rarr;</a>
                                </div>
                            </div>
                        </div>
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
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition flex flex-col h-full border border-gray-100">
                             <a href="{{ route('visas.show', $visa->slug) }}" class="block relative h-48 overflow-hidden group">
                                <div 
                                    class="absolute inset-0 bg-cover bg-center transition-transform duration-700 hover:scale-105"
                                    style="background-image: url('{{ Str::startsWith($visa->thumbnail, 'http') ? $visa->thumbnail : Storage::url($visa->thumbnail) }}');"
                                ></div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                                <div class="absolute bottom-4 left-4 right-4">
                                     <h3 class="text-xl font-bold text-white shadow-sm">{{ $visa->country }}</h3>
                                </div>
                            </a>
                            <div class="p-6 flex-grow flex flex-col">
                                <div class="flex items-center space-x-2 mb-3">
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-blue-100 text-blue-800">{{ $visa->visa_type }}</span>
                                    <span class="px-2 py-1 text-xs font-semibold rounded bg-green-100 text-green-800">{{ $visa->processing_time }}</span>
                                </div>
                                <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-100">
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase">Starting from</p>
                                        <span class="text-xl font-bold text-red-600">৳{{ number_format($visa->price) }}</span>
                                    </div>
                                    <a href="{{ route('visas.show', $visa->slug) }}" class="text-red-600 font-semibold hover:text-red-700 text-sm">Details &rarr;</a>
                                </div>
                            </div>
                        </div>
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
                                <img src="{{ $post->image ? Storage::url($post->image) : 'https://via.placeholder.com/800x600?text=FlyoverBD+Blog' }}" 
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
