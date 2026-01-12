@props(['package'])

@php
    $defaultImage = 'https://via.placeholder.com/640x360?text=Tour+Package';
    $thumbnail = $package->thumbnail 
        ? (\Illuminate\Support\Str::startsWith($package->thumbnail, 'http') ? $package->thumbnail : Storage::url($package->thumbnail)) 
        : $defaultImage;
@endphp

<a href="{{ route('packages.show', $package->slug) }}" class="group block bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
    <div class="relative h-64 overflow-hidden">
        <img src="{{ $thumbnail }}" 
             alt="{{ $package->title }}" 
             class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700 ease-out">
        
        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/60 via-transparent to-transparent opacity-60"></div>
        
        <!-- Badges -->
        <div class="absolute top-4 left-4 flex flex-col gap-2">
            <span class="bg-white/90 backdrop-blur-md text-gray-900 text-[10px] font-black uppercase tracking-widest px-3 py-1.5 rounded-full shadow-sm">
                 {{ $package->duration_days }} Days
            </span>
        </div>

        <!-- Location -->
        <div class="absolute bottom-4 left-4 flex items-center text-white">
            <svg class="w-3 h-3 mr-1 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" />
            </svg>
            <span class="text-[10px] font-bold uppercase tracking-widest">{{ $package->location }}</span>
        </div>
    </div>

    <div class="p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors line-clamp-1">
            {{ $package->title }}
        </h3>
        
        <p class="text-gray-500 text-xs mb-6 line-clamp-2 leading-relaxed">
            {{ $package->description }}
        </p>

        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
            <div>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Starting from</p>
                <p class="text-xl font-black text-red-600">৳{{ number_format($package->price) }}</p>
            </div>
            
            <div class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center group-hover:bg-red-600 group-hover:text-white transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </div>
        </div>
    </div>
</a>
