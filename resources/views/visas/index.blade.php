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
                     <x-visa-card :visa="$visa" />
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
