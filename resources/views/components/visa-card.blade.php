@props(['visa'])

@php
    $thumbnail = \Illuminate\Support\Str::startsWith($visa->thumbnail, 'http') 
        ? $visa->thumbnail 
        : Storage::url($visa->thumbnail);
@endphp

<a href="{{ route('visas.show', $visa->slug) }}" class="group block bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-500 h-full flex flex-col">
    <div class="relative h-56 overflow-hidden">
        <div class="absolute inset-0 bg-cover bg-center transform group-hover:scale-110 transition-transform duration-700 ease-out"
             style="background-image: url('{{ $thumbnail }}');">
        </div>
        
        <!-- Overlay Gradient -->
        <div class="absolute inset-0 bg-gradient-to-t from-gray-900/80 via-gray-900/20 to-transparent"></div>
        
        <!-- Country Name on Image -->
        <div class="absolute bottom-4 left-5 right-5">
            <h3 class="text-2xl font-black text-white leading-tight shadow-sm">{{ $visa->country }}</h3>
        </div>

        <!-- Visa Type Badge -->
        <div class="absolute top-4 left-5">
            <span class="bg-red-600 text-white text-[9px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">
                {{ $visa->type }}
            </span>
        </div>
    </div>

    <div class="p-6 flex-grow flex flex-col justify-between">
        <div class="flex items-center text-gray-500 mb-6 bg-gray-50 rounded-xl p-3">
            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span class="text-xs font-bold uppercase tracking-wider">{{ $visa->processing_time }} Processing</span>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-gray-50">
            <div>
                <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-0.5">Visa Fee</p>
                <p class="text-xl font-black text-red-600">৳{{ number_format($visa->price) }}</p>
            </div>
            
            <div class="text-xs font-bold text-gray-900 flex items-center group-hover:text-red-600 transition-colors">
                Details 
                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                </svg>
            </div>
        </div>
    </div>
</a>
