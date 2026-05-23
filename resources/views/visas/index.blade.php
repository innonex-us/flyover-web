<x-app-layout>
    <div style="background:var(--cream); min-height:100vh;">

        {{-- ── Hero ──────────────────────────────────────────────────── --}}
        <div style="background:#fff; border-bottom:1px solid var(--rule);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-16 pb-12">
                <p class="fb-eyebrow mb-4">Visa Services · 184 countries covered</p>
                <h1 class="fb-serif text-4xl md:text-5xl lg:text-6xl mb-6"
                    style="color:var(--ink); line-height:1.04; max-width:700px; letter-spacing:-0.02em;">
                    The paperwork,<br><em>handled.</em>
                </h1>
                <p class="text-lg mb-8 max-w-xl" style="color:var(--mute); line-height:1.6;">
                    From tourist visas to business entry — our specialists process your application
                    with industry-leading approval rates. You travel, we take care of the rest.
                </p>
                <div class="flex flex-wrap gap-3">
                    <a href="#visa-grid" class="ota-btn-primary px-7 py-3.5 text-base">
                        Start an application
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                    <a href="#eligibility-check" class="ota-btn-ghost px-7 py-3.5 text-base">
                        Check eligibility
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Stats Bar ─────────────────────────────────────────────── --}}
        <div style="background:var(--ink);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-0 md:divide-x md:divide-white/10">
                    @foreach([
                        ['94.2%', 'Approval rate', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        ['12,800+', 'Applications processed', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                        ['184', 'Countries covered', 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['4.2 days', 'Average processing', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ] as [$stat, $label, $icon])
                        <div class="md:px-8 text-center md:text-left">
                            <div class="flex items-center justify-center md:justify-start gap-2 mb-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--red);">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/>
                                </svg>
                                <span class="text-2xl font-bold" style="color:var(--cream);">{{ $stat }}</span>
                            </div>
                            <p class="text-sm" style="color:rgba(250,246,238,0.5);">{{ $label }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Quick Eligibility Check ────────────────────────────────── --}}
        <div id="eligibility-check" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="ota-card p-6 md:p-8" x-data="{ purpose: 'tourism' }">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
                    <div class="md:col-span-3 mb-2">
                        <p class="fb-eyebrow mb-1">Quick eligibility check</p>
                        <h2 class="text-xl font-bold" style="color:var(--ink);">Where are you headed?</h2>
                    </div>

                    {{-- Passport --}}
                    <div class="fb-field">
                        <label class="fb-field-label">Passport</label>
                        <select class="fb-input">
                            <option>Bangladeshi (BD)</option>
                            <option>Indian (IN)</option>
                            <option>Pakistani (PK)</option>
                            <option>Other</option>
                        </select>
                    </div>

                    {{-- Destination --}}
                    <div class="fb-field">
                        <label class="fb-field-label">Destination country</label>
                        <input type="text" class="fb-input" placeholder="e.g. Thailand, UAE, UK…">
                    </div>

                    {{-- Purpose tabs --}}
                    <div>
                        <p class="fb-field-label mb-2">Purpose</p>
                        <div class="flex gap-2 flex-wrap">
                            @foreach(['tourism','business','medical','education'] as $p)
                                <button type="button" @click="purpose = '{{ $p }}'"
                                    class="px-3 py-1.5 rounded-full text-xs font-semibold border transition-all capitalize"
                                    :style="purpose === '{{ $p }}'
                                        ? 'background:var(--red);color:#fff;border-color:var(--red);'
                                        : 'background:#fff;color:var(--ink2);border-color:var(--rule);'">
                                    {{ ucfirst($p) }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <div class="md:col-span-3 flex gap-3 items-center pt-2" style="border-top:1px solid var(--rule);">
                        <button type="button" class="ota-btn-primary px-6">
                            Check requirements
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                        <p class="text-sm" style="color:var(--mute);">Instant results · No sign-up needed</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Visa Listing ──────────────────────────────────────────── --}}
        <div id="visa-grid" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">

            {{-- Search + filter row --}}
            <div class="flex flex-col sm:flex-row gap-4 mb-8 items-start sm:items-center justify-between">
                <div>
                    <p class="fb-eyebrow mb-1">Browse destinations</p>
                    <h2 class="text-2xl font-bold" style="color:var(--ink);">All visa services</h2>
                </div>
                <form action="{{ route('visas.index') }}" method="GET" class="flex gap-2 w-full sm:w-auto">
                    <div class="fb-field flex-row items-center gap-2 flex-1 sm:w-64 py-3">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--mute);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search country…"
                            class="fb-input flex-1" style="font-size:14px;">
                    </div>
                    <button type="submit" class="ota-btn-primary px-5">Search</button>
                    @if(request('search'))
                        <a href="{{ route('visas.index') }}" class="ota-btn-ghost px-5">Clear</a>
                    @endif
                </form>
            </div>

            {{-- Type filter chips --}}
            <div class="flex gap-2 flex-wrap mb-6">
                @foreach(['All','Embassy','E-Visa','On-Arrival','Visa-Free'] as $type)
                    <a href="{{ route('visas.index', array_merge(request()->query(), ['type' => $type === 'All' ? '' : Str::slug($type)])) }}"
                        class="fb-chip transition-all text-sm"
                        style="{{ (request('type','') === ($type === 'All' ? '' : Str::slug($type))) ? 'background:var(--ink);color:#fff;border-color:var(--ink);' : '' }}">
                        {{ $type }}
                    </a>
                @endforeach
            </div>

            {{-- Grid --}}
            @if($visas->isEmpty())
                <div class="text-center py-20 ota-card">
                    <p class="text-4xl mb-4">🔍</p>
                    <h3 class="font-bold text-lg mb-2" style="color:var(--ink);">No results for "{{ request('search') }}"</h3>
                    <p class="text-sm mb-6" style="color:var(--mute);">Try a different country name or browse all destinations.</p>
                    <a href="{{ route('visas.index') }}" class="ota-btn-primary">Browse all</a>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                    @foreach($visas as $visa)
                        @php
                            $typeColor = match(strtolower($visa->type ?? '')) {
                                'e-visa', 'evisa'  => ['bg'=>'#EEF2FF','text'=>'#4338CA'],
                                'on-arrival'       => ['bg'=>'#E8F5EC','text'=>'#1F6E3D'],
                                'visa-free'        => ['bg'=>'#FFF4D6','text'=>'#8A5A00'],
                                default            => ['bg'=>'#FFE9EC','text'=>'var(--red)'],
                            };
                            $approvalRate = $visa->approval_rate ?? '92%';
                        @endphp
                        <a href="{{ route('visas.show', $visa->slug) }}"
                            class="ota-card group flex flex-col overflow-hidden no-underline"
                            style="text-decoration:none;">
                            <div class="p-6 flex-1 flex flex-col">

                                {{-- Flag + country + type --}}
                                <div class="flex items-start justify-between gap-3 mb-4">
                                    <div class="flex items-center gap-3">
                                        {{-- Flag --}}
                                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-2xl shrink-0 overflow-hidden"
                                            style="background:var(--cream); border:1px solid var(--rule);">
                                            @if(!empty($visa->flag) && !Str::startsWith($visa->flag, 'http'))
                                                {{ $visa->flag }}
                                            @elseif(!empty($visa->flag))
                                                <img src="{{ $visa->flag }}" alt="" class="w-full h-full object-cover">
                                            @else
                                                🌍
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-base group-hover:text-red-700 transition-colors"
                                                style="color:var(--ink);">{{ $visa->country ?? $visa->name }}</h3>
                                            <span class="inline-block text-xs font-semibold px-2 py-0.5 rounded-md mt-0.5"
                                                style="background:{{ $typeColor['bg'] }};color:{{ $typeColor['text'] }};">
                                                {{ $visa->type }}
                                            </span>
                                        </div>
                                    </div>
                                    {{-- Approval rate --}}
                                    <div class="shrink-0 text-center">
                                        <div class="text-lg font-bold" style="color:#1F6E3D;">{{ $approvalRate }}</div>
                                        <p class="text-xs" style="color:var(--mute);">approval</p>
                                    </div>
                                </div>

                                {{-- Description snippet --}}
                                @if(!empty($visa->description))
                                    <p class="text-sm mb-4 line-clamp-2" style="color:var(--mute);">
                                        {{ Str::limit(strip_tags($visa->description), 90) }}
                                    </p>
                                @endif

                                {{-- Meta chips --}}
                                <div class="flex flex-wrap gap-2 mt-auto mb-4">
                                    @if($visa->processing_time)
                                        <span class="fb-chip text-xs">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            {{ $visa->processing_time }}
                                        </span>
                                    @endif
                                    @if($visa->validity)
                                        <span class="fb-chip text-xs">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                            {{ $visa->validity }}
                                        </span>
                                    @endif
                                </div>

                                {{-- Divider + price --}}
                                <div class="flex items-end justify-between pt-4" style="border-top:1px solid var(--rule);">
                                    <div>
                                        <p class="fb-mono mb-0.5">Fee</p>
                                        <p class="text-xl font-bold" style="color:var(--red);">
                                            ৳{{ number_format($visa->price ?? $visa->fee ?? 0) }}
                                        </p>
                                        <p class="text-xs" style="color:var(--mute);">per person</p>
                                    </div>
                                    <div class="ota-btn-ghost px-4 py-2 text-sm group-hover:border-red-600 group-hover:text-red-600 transition-colors flex items-center gap-1.5">
                                        Apply now
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>

                {{-- Pagination --}}
                @if($visas->hasPages())
                    <div class="mt-10 flex justify-center items-center gap-2">
                        @if($visas->onFirstPage())
                            <span class="px-4 py-2 rounded-xl text-sm font-medium cursor-not-allowed" style="color:var(--rule); border:1px solid var(--rule); background:#fff;">&larr;</span>
                        @else
                            <a href="{{ $visas->previousPageUrl() }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-colors" style="color:var(--ink2); border:1px solid var(--rule); background:#fff;">&larr;</a>
                        @endif

                        @foreach($visas->getUrlRange(max(1,$visas->currentPage()-2), min($visas->lastPage(),$visas->currentPage()+2)) as $page => $url)
                            @if($page === $visas->currentPage())
                                <span class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-bold" style="background:var(--red); color:#fff;">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl text-sm font-medium" style="color:var(--ink2); border:1px solid var(--rule); background:#fff;">{{ $page }}</a>
                            @endif
                        @endforeach

                        @if($visas->hasMorePages())
                            <a href="{{ $visas->nextPageUrl() }}" class="px-4 py-2 rounded-xl text-sm font-medium transition-colors" style="color:var(--ink2); border:1px solid var(--rule); background:#fff;">&rarr;</a>
                        @else
                            <span class="px-4 py-2 rounded-xl text-sm font-medium cursor-not-allowed" style="color:var(--rule); border:1px solid var(--rule); background:#fff;">&rarr;</span>
                        @endif
                    </div>
                @endif
            @endif
        </div>

        {{-- ── How It Works ──────────────────────────────────────────── --}}
        <div style="background:#fff; border-top:1px solid var(--rule);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="text-center mb-12">
                    <p class="fb-eyebrow mb-3">Simple process</p>
                    <h2 class="fb-serif text-3xl md:text-4xl" style="color:var(--ink);">
                        <em>From application to approval</em>
                    </h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-5 gap-6">
                    @php
                        $steps = [
                            ['num'=>'01', 'title'=>'Select destination', 'desc'=>'Choose your country and visa type from our 184-country database.'],
                            ['num'=>'02', 'title'=>'Submit documents',   'desc'=>'Upload required documents through our secure portal. We check everything.'],
                            ['num'=>'03', 'title'=>'We process',         'desc'=>'Our visa experts prepare and submit your application to the embassy.'],
                            ['num'=>'04', 'title'=>'Track status',       'desc'=>'Real-time updates via SMS and email at every stage of processing.'],
                            ['num'=>'05', 'title'=>'Receive visa',       'desc'=>'Visa delivered digitally or by courier. You\'re cleared to travel!'],
                        ];
                    @endphp
                    @foreach($steps as $si => $step)
                        <div class="relative text-center">
                            @if($si < count($steps)-1)
                                <div class="hidden lg:block absolute top-6 left-1/2 w-full h-px" style="background:var(--rule); z-index:0;"></div>
                            @endif
                            <div class="relative z-10 w-12 h-12 mx-auto mb-4 flex items-center justify-center rounded-full font-bold text-sm"
                                style="background:var(--red); color:#fff;">
                                {{ $step['num'] }}
                            </div>
                            <h3 class="font-bold text-sm mb-2" style="color:var(--ink);">{{ $step['title'] }}</h3>
                            <p class="text-xs leading-relaxed" style="color:var(--mute);">{{ $step['desc'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ── Document Checklist Sample ─────────────────────────────── --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-start">
                <div>
                    <p class="fb-eyebrow mb-3">Sample checklist</p>
                    <h2 class="fb-serif text-3xl mb-4" style="color:var(--ink);"><em>Schengen visa documents</em></h2>
                    <p class="text-base mb-6" style="color:var(--mute); line-height:1.7;">
                        Here is a typical document list for a Schengen tourist visa. Requirements vary by
                        country and embassy — your actual checklist is generated after you select a destination.
                    </p>
                    <a href="{{ route('visas.index') }}" class="ota-btn-ghost">
                        View all destinations
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>

                <div class="ota-card overflow-hidden">
                    <div class="px-6 py-4 flex items-center gap-3" style="background:var(--ink); color:var(--cream);">
                        <span class="text-2xl">🇩🇪</span>
                        <div>
                            <p class="font-bold">Schengen — Germany / France / Italy</p>
                            <p class="text-xs" style="color:rgba(250,246,238,0.6);">Embassy visa · 10–15 working days</p>
                        </div>
                    </div>
                    @php
                        $schengenDocs = [
                            ['cat'=>'Passport & Identity', 'docs'=>['Original passport (min 6 months validity, 2 blank pages)','National ID card (photocopy)','2 recent passport-sized photographs (35×45mm, white background)']],
                            ['cat'=>'Financial Documents', 'docs'=>['Bank statement (last 6 months, min BDT 3 lakh balance)','Bank solvency letter','Income tax return (last 2 years)','Salary slip / business ownership proof']],
                            ['cat'=>'Travel Documents', 'docs'=>['Flight reservation (not booked ticket)','Hotel bookings / accommodation proof','Travel insurance (min €30,000 coverage)','Detailed travel itinerary']],
                            ['cat'=>'Supporting', 'docs'=>['Cover letter explaining purpose of visit','NOC from employer / leave letter','Previous visas (if any)']],
                        ];
                    @endphp
                    <div class="divide-y" style="divide-color:var(--rule);">
                        @foreach($schengenDocs as $cat)
                            <div class="p-5" x-data="{ open: {{ $loop->first ? 'true' : 'false' }} }">
                                <button type="button" @click="open = !open"
                                    class="w-full flex items-center justify-between text-left">
                                    <span class="font-semibold text-sm" style="color:var(--ink);">{{ $cat['cat'] }}</span>
                                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--mute);">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <ul x-show="open" x-collapse class="mt-3 space-y-2">
                                    @foreach($cat['docs'] as $doc)
                                        <li class="flex items-start gap-2.5 text-sm" style="color:var(--ink2);">
                                            <svg class="w-4 h-4 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#1F6E3D;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            {{ $doc }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Bottom CTA ────────────────────────────────────────────── --}}
        <div class="py-14" style="background:var(--ink);">
            <div class="max-w-3xl mx-auto text-center px-4">
                <p class="fb-eyebrow mb-4" style="color:rgba(250,246,238,0.6);">Ready to apply?</p>
                <h2 class="fb-serif text-3xl md:text-4xl mb-6" style="color:var(--cream);">
                    <em>Start your visa application today</em>
                </h2>
                <div class="flex flex-wrap gap-3 justify-center">
                    <a href="#visa-grid" class="ota-btn-primary px-8 py-3.5 text-base">Browse all destinations</a>
                    <a href="{{ route('contact') }}" class="ota-btn-ghost px-8 py-3.5 text-base" style="background:transparent; color:var(--cream); border-color:rgba(250,246,238,0.3);">
                        Talk to an expert
                    </a>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
