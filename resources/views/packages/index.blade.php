<x-app-layout>
    <div style="background:var(--cream); min-height:100vh;">

        {{-- ── Page Header ─────────────────────────────────────────── --}}
        <div style="background:#fff; border-bottom:1px solid var(--rule);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
                <p class="fb-eyebrow mb-3">All journeys · {{ $packages->total() }} active</p>
                <h1 class="fb-serif text-4xl md:text-5xl lg:text-6xl" style="color:var(--ink); line-height:1.05; letter-spacing:-0.02em;">
                    Tour packages —<br><em>handpicked, never resold</em>
                </h1>
            </div>

            {{-- ── Filter Pill Bar ──────────────────────────────────── --}}
            <div style="border-top:1px solid var(--rule);">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <form method="GET" action="{{ route('packages.index') }}" class="flex items-center gap-2 overflow-x-auto py-3 scroll-snap-x hide-scrollbar">
                        @php
                            $filterPills = [
                                'region'     => ['label' => 'All regions',   'options' => ['Asia','Europe','Middle East','Africa','Americas']],
                                'duration'   => ['label' => 'Any duration',  'options' => ['1-3 days','4-7 days','8-14 days','15+ days']],
                                'month'      => ['label' => 'Any month',     'options' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']],
                                'budget'     => ['label' => 'Budget',        'options' => ['Under ৳30k','৳30k–60k','৳60k–1L','Above ৳1L']],
                                'group_size' => ['label' => 'Group size',    'options' => ['Solo','Couple','Family','Group (10+)']],
                                'style'      => ['label' => 'Style',         'options' => ['Adventure','Cultural','Luxury','Beach','Pilgrimage']],
                            ];
                        @endphp

                        @foreach($filterPills as $key => $pill)
                            <div class="relative shrink-0 scroll-snap-align-start" x-data="{ open: false }">
                                <button type="button" @click="open = !open"
                                    class="flex items-center gap-1.5 px-4 py-2 text-sm font-semibold rounded-full border transition-all whitespace-nowrap"
                                    style="{{ request($key) ? 'background:var(--ink);color:#fff;border-color:var(--ink);' : 'background:#fff;color:var(--ink2);border-color:var(--rule);' }}">
                                    {{ request($key) ? ucfirst(request($key)) : $pill['label'] }}
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div x-show="open" @click.away="open = false" x-cloak
                                    class="absolute top-full left-0 mt-2 z-30 bg-white rounded-2xl shadow-xl border py-2 min-w-[160px]"
                                    style="border-color:var(--rule);">
                                    @foreach($pill['options'] as $opt)
                                        <a href="{{ route('packages.index', array_merge(request()->query(), [$key => Str::slug($opt)])) }}"
                                            class="flex items-center gap-2 px-4 py-2 text-sm transition-colors"
                                            style="color:var(--ink2);"
                                            onmouseover="this.style.background='var(--cream)'" onmouseout="this.style.background='transparent'">
                                            @if(request($key) === Str::slug($opt))
                                                <span style="color:var(--red);">&#10003;</span>
                                            @endif
                                            {{ $opt }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach

                        @if(request()->hasAny(array_keys($filterPills)))
                            <a href="{{ route('packages.index') }}"
                                class="shrink-0 flex items-center gap-1 px-4 py-2 text-sm font-semibold rounded-full transition-colors"
                                style="color:var(--mute); border:1px solid var(--rule); background:#fff;">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                Clear
                            </a>
                        @endif
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Main Layout: Sidebar + Grid ─────────────────────────── --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex gap-8 items-start">

                {{-- ── Sidebar ──────────────────────────────────────── --}}
                <aside class="hidden lg:block shrink-0 w-64" style="position:sticky; top:88px;">
                    <form method="GET" action="{{ route('packages.index') }}">

                        {{-- Region --}}
                        <div class="mb-6">
                            <p class="fb-mono mb-3">Region</p>
                            @foreach(['Asia','Europe','Middle East','Africa','Americas','Oceania'] as $region)
                                <label class="flex items-center gap-2.5 py-1.5 cursor-pointer group">
                                    <input type="checkbox" name="regions[]" value="{{ Str::slug($region) }}"
                                        {{ in_array(Str::slug($region), (array) request('regions', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-red-600 focus:ring-0"
                                        style="accent-color:var(--red);">
                                    <span class="text-sm" style="color:var(--ink2);">{{ $region }}</span>
                                </label>
                            @endforeach
                        </div>

                        <hr style="border-color:var(--rule); margin-bottom:1.5rem;">

                        {{-- Trip Style --}}
                        <div class="mb-6">
                            <p class="fb-mono mb-3">Trip Style</p>
                            @foreach(['Adventure','Cultural','Luxury','Beach','Pilgrimage','Wildlife'] as $style)
                                <label class="flex items-center gap-2.5 py-1.5 cursor-pointer">
                                    <input type="checkbox" name="styles[]" value="{{ Str::slug($style) }}"
                                        {{ in_array(Str::slug($style), (array) request('styles', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300"
                                        style="accent-color:var(--red);">
                                    <span class="text-sm" style="color:var(--ink2);">{{ $style }}</span>
                                </label>
                            @endforeach
                        </div>

                        <hr style="border-color:var(--rule); margin-bottom:1.5rem;">

                        {{-- Duration --}}
                        <div class="mb-6">
                            <p class="fb-mono mb-3">Duration</p>
                            @foreach(['1-3 days','4-7 days','8-14 days','15+ days'] as $dur)
                                <label class="flex items-center gap-2.5 py-1.5 cursor-pointer">
                                    <input type="radio" name="duration" value="{{ Str::slug($dur) }}"
                                        {{ request('duration') === Str::slug($dur) ? 'checked' : '' }}
                                        style="accent-color:var(--red);">
                                    <span class="text-sm" style="color:var(--ink2);">{{ $dur }}</span>
                                </label>
                            @endforeach
                        </div>

                        <hr style="border-color:var(--rule); margin-bottom:1.5rem;">

                        {{-- Price Range (visual only) --}}
                        <div class="mb-6">
                            <p class="fb-mono mb-3">Price range</p>
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs font-semibold" style="color:var(--mute);">৳0</span>
                                <span class="text-xs font-semibold" style="color:var(--mute);">৳2,00,000</span>
                            </div>
                            <input type="range" name="max_price" min="0" max="200000" step="5000"
                                value="{{ request('max_price', 200000) }}"
                                class="w-full h-1.5 rounded-full appearance-none cursor-pointer"
                                style="accent-color:var(--red); background:var(--rule);">
                            <p class="text-xs mt-2" style="color:var(--mute);">
                                Up to ৳<span id="price-display">{{ number_format(request('max_price', 200000)) }}</span>
                            </p>
                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var r = document.querySelector('input[name="max_price"]');
                                    var d = document.getElementById('price-display');
                                    if (r && d) r.addEventListener('input', function () {
                                        d.textContent = parseInt(this.value).toLocaleString('en-IN');
                                    });
                                });
                            </script>
                        </div>

                        <button type="submit" class="ota-btn-primary w-full justify-center">
                            Apply Filters
                        </button>
                    </form>
                </aside>

                {{-- ── Package Grid ─────────────────────────────────── --}}
                <div class="flex-1 min-w-0">

                    {{-- Result count + sort --}}
                    <div class="flex items-center justify-between mb-6">
                        <p class="text-sm" style="color:var(--mute);">
                            Showing <span class="font-semibold" style="color:var(--ink);">{{ $packages->firstItem() }}–{{ $packages->lastItem() }}</span>
                            of <span class="font-semibold" style="color:var(--ink);">{{ $packages->total() }}</span> tours
                        </p>
                        <select name="sort" form="sort-form" onchange="document.getElementById('sort-form').submit()"
                            class="text-sm border rounded-xl px-3 py-2 focus:ring-0 focus:outline-none"
                            style="border-color:var(--rule); color:var(--ink2); background:#fff;">
                            <option value="popular" {{ request('sort','popular')==='popular' ? 'selected':'' }}>Most popular</option>
                            <option value="price_asc" {{ request('sort')==='price_asc' ? 'selected':'' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort')==='price_desc' ? 'selected':'' }}>Price: High to Low</option>
                            <option value="duration_asc" {{ request('sort')==='duration_asc' ? 'selected':'' }}>Shortest first</option>
                            <option value="newest" {{ request('sort')==='newest' ? 'selected':'' }}>Newest</option>
                        </select>
                        <form id="sort-form" method="GET" action="{{ route('packages.index') }}" class="hidden">
                            @foreach(request()->except('sort') as $k => $v)
                                <input type="hidden" name="{{ $k }}" value="{{ $v }}">
                            @endforeach
                        </form>
                    </div>

                    @if($packages->isEmpty())
                        <div class="text-center py-24 ota-card">
                            <svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--rule);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/></svg>
                            <h3 class="text-lg font-semibold mb-1" style="color:var(--ink);">No packages found</h3>
                            <p class="text-sm mb-6" style="color:var(--mute);">Try adjusting your filters or browse all tours.</p>
                            <a href="{{ route('packages.index') }}" class="ota-btn-primary">Browse all tours</a>
                        </div>
                    @else
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($packages as $pkg)
                                @php
                                    $gradients = [
                                        'linear-gradient(135deg,#1a3a5c 0%,#2d6a9f 50%,#5ba3d9 100%)',
                                        'linear-gradient(135deg,#2A2520 0%,#6B4F3A 50%,#C09B7A 100%)',
                                        'linear-gradient(135deg,#1a4a2a 0%,#2d8a4a 50%,#5bc47a 100%)',
                                        'linear-gradient(135deg,#3a1a4a 0%,#7a3a9a 50%,#ba7ac4 100%)',
                                        'linear-gradient(135deg,#4a2a1a 0%,#9a5a3a 50%,#d4926a 100%)',
                                    ];
                                    $grad = $gradients[$loop->index % count($gradients)];
                                    $hasImage = !empty($pkg->thumbnail);
                                    $imgUrl = $hasImage
                                        ? (Str::startsWith($pkg->thumbnail, 'http') ? $pkg->thumbnail : Storage::url($pkg->thumbnail))
                                        : null;
                                    $rating = round(4.2 + ($loop->index % 8) * 0.1, 1);
                                    $reviewCount = 40 + ($loop->index * 7) % 120;
                                @endphp

                                <a href="{{ route('packages.show', $pkg->slug) }}"
                                    class="ota-card group flex flex-col overflow-hidden no-underline"
                                    style="text-decoration:none;">

                                    {{-- Image / Placeholder --}}
                                    <div class="relative overflow-hidden" style="height:220px;">
                                        @if($hasImage)
                                            <img src="{{ $imgUrl }}" alt="{{ $pkg->title }}"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                                loading="lazy">
                                        @else
                                            <div class="w-full h-full img-placeholder transition-transform duration-500 group-hover:scale-105"
                                                style="background:{{ $grad }};"></div>
                                        @endif

                                        {{-- Overlay badges --}}
                                        <div class="absolute top-3 left-3 flex gap-2 flex-wrap">
                                            @if($loop->index < 3)
                                                <span class="ota-tag-red">POPULAR</span>
                                            @endif
                                            @if(!empty($pkg->duration_days))
                                                <span class="ota-tag-soft">{{ $pkg->duration_days }}D / {{ max(1,$pkg->duration_days-1) }}N</span>
                                            @endif
                                        </div>

                                        {{-- Rating badge --}}
                                        <div class="absolute top-3 right-3">
                                            <span class="rating-badge">
                                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                                {{ $rating }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Card Body --}}
                                    <div class="p-5 flex flex-col flex-1">

                                        {{-- Destination eyebrow --}}
                                        <p class="fb-eyebrow text-xs mb-2 flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            {{ $pkg->location ?? $pkg->destination ?? 'Bangladesh' }}
                                        </p>

                                        {{-- Title --}}
                                        <h2 class="fb-serif text-xl mb-2 leading-tight group-hover:text-red-700 transition-colors"
                                            style="color:var(--ink);">
                                            <em>{{ $pkg->title }}</em>
                                        </h2>

                                        {{-- Description snippet --}}
                                        @if(!empty($pkg->description))
                                            <p class="text-sm mb-4 line-clamp-2" style="color:var(--mute);">
                                                {{ Str::limit(strip_tags($pkg->description), 100) }}
                                            </p>
                                        @endif

                                        {{-- Chips --}}
                                        <div class="flex flex-wrap gap-2 mb-4">
                                            @if(!empty($pkg->group_size))
                                                <span class="fb-chip">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    {{ $pkg->group_size }}
                                                </span>
                                            @else
                                                <span class="fb-chip">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                    Small group
                                                </span>
                                            @endif
                                            <span class="fb-chip">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                Visa incl.
                                            </span>
                                            <span class="fb-chip">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                {{ ($pkg->duration_days ?? 5) }} days
                                            </span>
                                        </div>

                                        {{-- Divider + price --}}
                                        <div class="mt-auto pt-4 flex items-end justify-between" style="border-top:1px solid var(--rule);">
                                            <div>
                                                <p class="fb-mono" style="color:var(--mute);">From</p>
                                                <p class="text-2xl font-bold" style="color:var(--red);">
                                                    ৳{{ number_format($pkg->price) }}
                                                </p>
                                                <p class="text-xs" style="color:var(--mute);">per person</p>
                                            </div>
                                            <div class="flex items-center gap-1 ota-btn-ghost px-4 py-2 text-sm group-hover:border-red-600 group-hover:text-red-600 transition-colors">
                                                View tour
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        @if($packages->hasPages())
                            <div class="mt-10 flex justify-center">
                                <div class="flex items-center gap-1.5">
                                    {{-- Prev --}}
                                    @if($packages->onFirstPage())
                                        <span class="px-4 py-2 rounded-xl text-sm font-medium cursor-not-allowed" style="color:var(--rule); border:1px solid var(--rule); background:#fff;">
                                            &larr; Prev
                                        </span>
                                    @else
                                        <a href="{{ $packages->previousPageUrl() }}"
                                            class="px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                                            style="color:var(--ink2); border:1px solid var(--rule); background:#fff;"
                                            onmouseover="this.style.borderColor='var(--ink)'"
                                            onmouseout="this.style.borderColor='var(--rule)'">
                                            &larr; Prev
                                        </a>
                                    @endif

                                    {{-- Page numbers --}}
                                    @foreach($packages->getUrlRange(max(1,$packages->currentPage()-2), min($packages->lastPage(),$packages->currentPage()+2)) as $page => $url)
                                        @if($page === $packages->currentPage())
                                            <span class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-bold"
                                                style="background:var(--red); color:#fff;">{{ $page }}</span>
                                        @else
                                            <a href="{{ $url }}"
                                                class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-medium transition-colors"
                                                style="color:var(--ink2); border:1px solid var(--rule); background:#fff;">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach

                                    {{-- Next --}}
                                    @if($packages->hasMorePages())
                                        <a href="{{ $packages->nextPageUrl() }}"
                                            class="px-4 py-2 rounded-xl text-sm font-medium transition-colors"
                                            style="color:var(--ink2); border:1px solid var(--rule); background:#fff;"
                                            onmouseover="this.style.borderColor='var(--ink)'"
                                            onmouseout="this.style.borderColor='var(--rule)'">
                                            Next &rarr;
                                        </a>
                                    @else
                                        <span class="px-4 py-2 rounded-xl text-sm font-medium cursor-not-allowed" style="color:var(--rule); border:1px solid var(--rule); background:#fff;">
                                            Next &rarr;
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <p class="text-center text-xs mt-4" style="color:var(--mute);">
                                Page {{ $packages->currentPage() }} of {{ $packages->lastPage() }}
                            </p>
                        @endif
                    @endif
                </div>
            </div>
        </div>

        {{-- ── Bottom CTA band ─────────────────────────────────────── --}}
        <div class="mt-8 py-14" style="background:var(--ink);">
            <div class="max-w-3xl mx-auto text-center px-4">
                <p class="fb-eyebrow mb-4" style="color:rgba(250,246,238,0.6);">Can't find the right trip?</p>
                <h2 class="fb-serif text-3xl md:text-4xl mb-6" style="color:var(--cream);">
                    <em>Let us build a custom tour</em><br>around your schedule
                </h2>
                <a href="{{ route('contact') }}" class="ota-btn-primary px-8 py-3.5 text-base">
                    Request a custom itinerary
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

    </div>
</x-app-layout>
