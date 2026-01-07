<x-app-layout>
    <div class="bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">All Tour Packages</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($packages as $package)
                     <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <img src="{{ $package->thumbnail ?? 'https://via.placeholder.com/640x360?text=Tour+Package' }}" alt="{{ $package->title }}" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-gray-900 leading-tight">{{ $package->title }}</h3>
                            </div>
                            <div class="flex items-center text-sm text-gray-500 mb-2">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $package->duration_days }} Days
                            </div>
                            <p class="text-gray-600 text-xs mb-3 line-clamp-2">{{ $package->description }}</p>
                            <div class="flex justify-between items-center mt-auto">
                                <div class="text-red-600 font-bold">
                                    <span class="text-sm text-gray-500 font-normal">From</span>
                                    ৳{{ number_format($package->price) }}
                                </div>
                                <a href="{{ route('packages.show', $package->slug) }}" class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full hover:bg-red-200 transition">View</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8">
                {{ $packages->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
