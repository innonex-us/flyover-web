<x-app-layout
    title="Tour Packages | FlyoverBD"
    meta_description="Explore handpicked tour packages to Cox's Bazar, Thailand, Maldives, Malaysia and 60+ destinations. Book with Bangladesh's trusted travel agency."
>

{{-- ── Hero ─────────────────────────────── --}}
<section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="px-4 py-14 text-center">
    <p class="section-eyebrow mb-2">Handpicked for you</p>
    <h1 class="font-extrabold text-4xl md:text-5xl text-gray-900 mb-3" style="font-family:'Merriweather',Georgia,serif;">
        Tour Packages
    </h1>
    <p class="text-gray-500 max-w-md mx-auto mb-8">From quick weekend getaways to epic multi-country adventures — find and book the perfect trip.</p>

    {{-- Search --}}
    <form action="{{ route('packages.index') }}" method="GET" class="flex max-w-xl mx-auto gap-2">
        <div class="flex-1 relative">
            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search packages, destinations…"
                   class="search-field pl-11 py-3.5">
        </div>
        <button type="submit" class="btn-primary px-7 py-3.5 rounded-xl">Search</button>
        @if(request('search'))
        <a href="{{ route('packages.index') }}" class="flex items-center px-4 py-3.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 hover:text-gray-700 transition">
            Clear
        </a>
        @endif
    </form>

    {{-- Quick-search chips --}}
    <div class="flex flex-wrap justify-center gap-2 mt-5">
        @foreach(["Cox's Bazar",'Thailand','Maldives','Malaysia','Dubai','Singapore','Bali'] as $q)
        <a href="{{ route('packages.index', ['search' => $q]) }}"
           class="text-xs font-semibold px-3.5 py-1.5 rounded-full transition {{ request('search') === $q ? 'bg-red-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-red-300 hover:text-red-600' }}">
            {{ $q }}
        </a>
        @endforeach
    </div>
</section>

{{-- ── Results ──────────────────────────── --}}
<section class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4">

        {{-- Meta row --}}
        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-500">
                @if(request('search'))
                    Results for <strong class="text-gray-800">"{{ request('search') }}"</strong>
                    &nbsp;·&nbsp;
                @endif
                <strong class="text-gray-800">{{ $packages->total() }}</strong> packages found
            </p>
        </div>

        @if($packages->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($packages as $package)
                <x-package-card :package="$package" />
            @endforeach
        </div>

        @if($packages->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $packages->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xl font-bold text-gray-400 mb-2">No packages found</p>
            <p class="text-sm text-gray-400 mb-5">Try a different search term or browse all packages.</p>
            <a href="{{ route('packages.index') }}" class="btn-primary">View All Packages</a>
        </div>
        @endif
    </div>
</section>

{{-- ── CTA band ─────────────────────────── --}}
<section class="py-14 text-center" style="background:#18130E;color:#FAF6EE;">
    <p class="section-eyebrow mb-3" style="color:#C8102E;">Can't find your dream trip?</p>
    <h2 class="font-extrabold text-3xl mb-3" style="font-family:'Merriweather',Georgia,serif;">We'll build it for you.</h2>
    <p class="text-sm max-w-md mx-auto mb-6" style="color:#A09890;">Tell us your destination, dates, and budget — our travel experts craft a custom itinerary just for you.</p>
    <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ route('customize.index') }}" class="ota-btn-primary">Plan My Trip</a>
        <a href="{{ route('contact') }}" style="border-color:#FAF6EE;color:#FAF6EE;" class="ota-btn-ghost">Contact Us</a>
    </div>
</section>

</x-app-layout>
