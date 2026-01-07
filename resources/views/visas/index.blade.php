<x-app-layout>
    <div class="bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">Visa Processing Services</h1>
            
            <div class="bg-white rounded-lg shadow p-6 mb-8">
                <form action="{{ route('visas.index') }}" method="GET" class="flex gap-4">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by country (e.g., Thailand, Dubai)" class="flex-grow border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                    <button type="submit" class="bg-red-600 text-white font-bold py-2 px-6 rounded-md hover:bg-red-700 transition">Search</button>
                    @if(request('search'))
                        <a href="{{ route('visas.index') }}" class="flex items-center text-gray-500 hover:text-gray-700">Clear</a>
                    @endif
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($visas as $visa)
                     <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition">
                        <img src="{{ Str::startsWith($visa->thumbnail, 'http') ? $visa->thumbnail : Storage::url($visa->thumbnail) }}" alt="{{ $visa->country }}" class="w-full h-40 object-cover">
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-900 mb-1">{{ $visa->country }}</h3>
                            <p class="text-xs text-red-600 uppercase font-semibold mb-2">{{ $visa->type }} Visa</p>
                            
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $visa->processing_time }} Processing
                            </div>

                            <div class="flex justify-between items-center mt-4">
                                <div class="font-bold text-gray-900">
                                    ৳{{ number_format($visa->price) }}
                                </div>
                                <a href="{{ route('visas.show', $visa->slug) }}" class="text-xs bg-red-100 text-red-700 px-3 py-1 rounded-full hover:bg-red-200 transition">Details</a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-gray-500 text-lg">No visa services found matching your criteria.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $visas->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
