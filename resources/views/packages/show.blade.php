<x-app-layout>
    @php
        $defaultImage = 'https://via.placeholder.com/1200x600?text=Tour+Package';
        $mainImage = $package->thumbnail 
            ? (\Illuminate\Support\Str::startsWith($package->thumbnail, 'http') ? $package->thumbnail : Storage::url($package->thumbnail))
            : $defaultImage;
        
        $galleryImages = [$mainImage];
        if (!empty($package->images) && is_array($package->images)) {
            foreach($package->images as $img) {
                $galleryImages[] = \Illuminate\Support\Str::startsWith($img, 'http') ? $img : Storage::url($img);
            }
        }
    @endphp

    <div class="bg-gray-50/50 min-h-screen pb-12" x-data="{ 
        openInquiryModal: false,
        activeSection: 'itinerary'
    }">
        <!-- Breadcrumbs -->
        <div class="bg-white border-b border-gray-100/80">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <nav class="flex text-xs font-medium text-gray-500" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li><a href="{{ route('home') }}" class="hover:text-red-500 transition">Home</a></li>
                        <li><svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li><a href="{{ route('packages.index') }}" class="hover:text-red-500 transition">Tours</a></li>
                        <li><svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-gray-900 font-medium truncate">{{ $package->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
            
            @if(session('success'))
                <div class="bg-green-50 border border-green-200/50 text-green-800 px-5 py-3 rounded-xl relative mb-6 flex items-center shadow-sm" role="alert">
                    <svg class="w-4 h-4 mr-3 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span class="block sm:inline font-medium text-sm">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column: Primary Content -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Main Thumbnail -->
                    <div class="relative rounded-2xl overflow-hidden shadow-md aspect-video bg-gray-100 border border-gray-200/50">
                        <img src="{{ $mainImage }}" alt="{{ $package->title }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Title & Basic Info -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-5 leading-tight">{{ $package->title }}</h1>
                        
                        <div class="flex flex-wrap items-center gap-3 text-gray-600">
                            <div class="flex items-center px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs font-semibold">
                                <svg class="w-4 h-4 mr-1.5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $package->location }}
                            </div>
                            <div class="flex items-center px-3 py-1.5 bg-gray-50 border border-gray-100 rounded-lg text-xs font-semibold">
                                <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $package->duration_days }} Days / {{ $package->duration_days - 1 }} Nights
                            </div>
                        </div>
                    </div>

                    <!-- Overview Section -->
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <span class="w-1 h-5 bg-red-500 rounded-full mr-3"></span>
                            Overview
                        </h2>
                        <div class="text-gray-600 text-sm md:text-base leading-relaxed">
                            {{ $package->description }}
                        </div>
                    </div>

                    <!-- Accordion Sections -->
                    <div class="space-y-3">
                        
                        <!-- Itinerary -->
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <button @click="activeSection = (activeSection === 'itinerary' ? '' : 'itinerary')" class="w-full flex items-center justify-between p-5 text-left focus:outline-none group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-red-50 text-red-600 rounded-lg flex items-center justify-center mr-4 group-hover:bg-red-500 group-hover:text-white transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 uppercase tracking-wide">Itinerary</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="activeSection === 'itinerary' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="activeSection === 'itinerary'" x-collapse class="p-5 pt-0 ml-14">
                                <div class="space-y-6 pt-4 border-t border-gray-50">
                                    @if(!empty($package->itinerary) && is_array($package->itinerary))
                                        @foreach($package->itinerary as $index => $day)
                                            <div class="relative pl-6 border-l border-gray-100 last:border-0 pb-6 last:pb-0">
                                                <div class="absolute -left-1.5 top-1 w-3 h-3 bg-red-500 rounded-full border-2 border-white"></div>
                                                <div class="mb-2">
                                                    <span class="text-[10px] font-bold text-red-500 uppercase tracking-widest">Day {{ $day['day'] ?? ($index + 1) }}</span>
                                                    <h3 class="text-base font-bold text-gray-900">{{ $day['title'] ?? 'Overview' }}</h3>
                                                </div>
                                                <ul class="space-y-1.5 mt-2">
                                                    @foreach($day['activities'] ?? [] as $activity)
                                                        <li class="flex items-start text-xs text-gray-600">
                                                            <svg class="w-3.5 h-3.5 mr-2 mt-0.5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            {{ $activity }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endforeach
                                    @else
                                        <p class="text-xs text-gray-500 italic">Itinerary details coming soon.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Basic Accordion Template -->
                        @php
                            $sections = [
                                ['id' => 'hotel', 'title' => 'Hotel Details', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'blue', 'content' => $package->hotel_details ?: 'Details shared upon booking.'],
                                ['id' => 'included', 'title' => 'Included', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'green', 'is_list' => true, 'content' => $package->inclusions ?: []],
                                ['id' => 'excluded', 'title' => 'Excluded', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'orange', 'is_list' => true, 'content' => $package->exclusions ?: []],
                                ['id' => 'additional', 'title' => 'Additional Information', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'color' => 'purple', 'content' => $package->additional_info],
                                ['id' => 'policy', 'title' => 'Policy & Condition', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'color' => 'indigo', 'content' => $package->policy],
                                ['id' => 'tips', 'title' => 'Travel Tips', 'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z', 'color' => 'yellow', 'content' => $package->travel_tips],
                                ['id' => 'pickup', 'title' => 'Pickup Note', 'icon' => 'M12 21l9-9-9-9-9 9 9 9z', 'color' => 'cyan', 'content' => $package->pickup_note],
                            ];
                        @endphp

                        @foreach($sections as $sec)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <button @click="activeSection = (activeSection === '{{ $sec['id'] }}' ? '' : '{{ $sec['id'] }}')" class="w-full flex items-center justify-between p-5 text-left focus:outline-none group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-{{ $sec['color'] }}-50 text-{{ $sec['color'] }}-600 rounded-lg flex items-center justify-center mr-4 group-hover:bg-{{ $sec['color'] }}-500 group-hover:text-white transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sec['icon'] }}"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 uppercase tracking-wide">{{ $sec['title'] }}</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="activeSection === '{{ $sec['id'] }}' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </button>
                            <div x-show="activeSection === '{{ $sec['id'] }}'" x-collapse class="p-5 pt-0 ml-14">
                                <div class="pt-4 border-t border-gray-50 text-xs md:text-sm text-gray-600 leading-relaxed whitespace-pre-line">
                                    @if(isset($sec['is_list']) && is_array($sec['content']))
                                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                            @forelse($sec['content'] as $item)
                                                <li class="flex items-start text-xs text-gray-700 bg-{{ $sec['color'] }}-50/30 p-2 rounded-lg border border-{{ $sec['color'] }}-100/50">
                                                    <svg class="w-3.5 h-3.5 mr-2 mt-0.5 text-{{ $sec['color'] }}-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    {{ $item }}
                                                </li>
                                            @empty
                                                <li class="col-span-2 italic text-gray-400">Not specified.</li>
                                            @endforelse
                                        </ul>
                                    @else
                                        {{ $sec['content'] ?: 'No information provided.' }}
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Right Column: Sidebar -->
                <div class="lg:col-span-1">
                    <div class="sticky top-20 space-y-6">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8">
                            <div class="text-center mb-8 border-b border-gray-50 pb-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-1">Plan Your Holiday</h3>
                                <p class="text-xs text-gray-500 mb-4">Customize your travel experience</p>
                                <div class="flex items-center justify-center space-x-2">
                                    <span class="text-2xl font-black text-red-600">৳{{ number_format($package->price) }}</span>
                                    <span class="text-[10px] font-bold text-gray-400 uppercase">Per person</span>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <button @click="openInquiryModal = true" class="w-full flex items-center justify-center py-3.5 px-6 bg-red-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-red-700 transition-all duration-200 active:scale-95">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    Request Quotation
                                </button>
                                
                                <p class="text-[10px] font-bold text-gray-400 text-center uppercase tracking-widest">Get a response within 24h</p>

                                <a href="tel:+880123456789" class="w-full flex items-center justify-center py-3.5 px-6 bg-white border border-gray-200 text-gray-700 rounded-xl font-bold text-sm hover:border-red-600 hover:text-red-600 transition-all duration-200 active:scale-95 group">
                                    <svg class="w-5 h-5 mr-2 group-hover:rotate-6 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    Speak to Experts
                                </a>
                            </div>

                            <div class="mt-8 pt-6 border-t border-gray-50 flex justify-around">
                                <div class="text-center group">
                                    <div class="w-8 h-8 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-1.5 transition-colors group-hover:bg-green-50 group-hover:text-green-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                    </div>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter">Verified</span>
                                </div>
                                <div class="text-center group">
                                    <div class="w-8 h-8 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-1.5 transition-colors group-hover:bg-blue-50 group-hover:text-blue-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                    </div>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter">Fast</span>
                                </div>
                                <div class="text-center group">
                                    <div class="w-8 h-8 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-1.5 transition-colors group-hover:bg-yellow-50 group-hover:text-yellow-500">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <span class="text-[8px] font-bold text-gray-400 uppercase tracking-tighter">Best Price</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customized Tour Banner -->
            <div class="mt-12 bg-white rounded-2xl p-8 border border-gray-100 flex flex-col md:flex-row items-center justify-between shadow-sm relative overflow-hidden group">
                <div class="absolute -right-20 -top-20 w-60 h-60 bg-red-50 rounded-full group-hover:scale-110 transition-transform duration-500"></div>
                <div class="relative z-10 text-center md:text-left mb-6 md:mb-0">
                    <h2 class="text-xl font-bold text-gray-900 mb-2">Need a Personalized Plan?</h2>
                    <p class="text-sm text-gray-500 max-w-md">Our experts can craft a custom itinerary tailored to your specific needs and budget.</p>
                </div>
                <div class="relative z-10 flex gap-3">
                    <button @click="openInquiryModal = true" class="px-6 py-3 bg-red-600 text-white font-bold text-sm rounded-xl shadow-sm hover:bg-red-700 transition">
                        Get Custom Plan
                    </button>
                </div>
            </div>

            <!-- Related Packages -->
            @if(isset($relatedPackages) && count($relatedPackages) > 0)
                <div class="mt-16">
                    <div class="flex items-center justify-between mb-8">
                        <h2 class="text-xl font-bold text-gray-900">Recommended Tours</h2>
                        <a href="{{ route('packages.index') }}" class="text-xs font-bold text-red-600 hover:underline">View All</a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        @foreach($relatedPackages as $rel)
                            <a href="{{ route('packages.show', $rel->slug) }}" class="bg-white rounded-xl overflow-hidden shadow-sm border border-gray-50 group hover:-translate-y-1 transition duration-300">
                                <div class="aspect-[4/3] overflow-hidden bg-gray-100 border-b border-gray-50">
                                    <img src="{{ $rel->thumbnail ? (\Illuminate\Support\Str::startsWith($rel->thumbnail, 'http') ? $rel->thumbnail : Storage::url($rel->thumbnail)) : $defaultImage }}" class="w-full h-full object-cover">
                                </div>
                                <div class="p-4">
                                    <div class="text-[10px] font-bold text-red-500 uppercase tracking-widest mb-1">{{ $rel->location }}</div>
                                    <h3 class="font-bold text-gray-900 text-sm mb-3 truncate">{{ $rel->title }}</h3>
                                    <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                        <p class="text-sm font-bold text-red-600">৳{{ number_format($rel->price) }}</p>
                                        <div class="w-7 h-7 bg-gray-50 text-gray-400 rounded-full flex items-center justify-center group-hover:bg-red-500 group-hover:text-white transition-colors">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Inquiry Modal -->
        <div x-show="openInquiryModal" style="display: none;" class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="openInquiryModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm transition-opacity" @click="openInquiryModal = false"></div>

                <div x-show="openInquiryModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" class="relative bg-white rounded-2xl shadow-xl max-w-md w-full overflow-hidden p-8">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-red-50 text-red-600 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-100">
                             <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900">Enquiry Form</h3>
                        <p class="text-xs text-gray-500 mt-1">Let us know your requirements for <br><span class="font-bold text-red-600">{{ $package->title }}</span></p>
                    </div>

                    <form action="{{ route('packages.customize', $package->id) }}" method="POST" class="space-y-4">
                        @csrf
                        <div class="space-y-3">
                            <input type="text" name="name" required class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-sm focus:bg-white focus:ring-1 focus:ring-red-500 transition-all shadow-sm" placeholder="Full Name">
                            <div class="grid grid-cols-2 gap-3">
                                <input type="email" name="email" required class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-sm focus:bg-white focus:ring-1 focus:ring-red-500 transition-all shadow-sm" placeholder="Email">
                                <input type="text" name="phone" required class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-sm focus:bg-white focus:ring-1 focus:ring-red-500 transition-all shadow-sm" placeholder="Phone">
                            </div>
                            <textarea name="message" rows="3" required class="w-full bg-gray-50 border-gray-100 rounded-xl py-3 px-4 text-sm focus:bg-white focus:ring-1 focus:ring-red-500 transition-all shadow-sm" placeholder="Message / Custom Requirements"></textarea>
                        </div>
                        <button type="submit" class="w-full py-4 bg-red-600 text-white rounded-xl font-bold text-sm shadow-md hover:bg-red-700 transition active:scale-95 mt-4">
                            Submit Enquiry
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
