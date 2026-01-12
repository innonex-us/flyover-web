<x-app-layout>
    <div class="bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-8">All Tour Packages</h1>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($packages as $package)
                     <x-package-card :package="$package" />
                @endforeach
            </div>

            <div class="mt-8">
                {{ $packages->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
