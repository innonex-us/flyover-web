<x-app-layout>
    @push('scripts')
        <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
        <script>
            grecaptcha.ready(function() {
                var form = document.querySelector('form[action="{{ route('bookings.store') }}"]');
                if (!form) return;
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'submit'}).then(function(token) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'g-recaptcha-response';
                        input.value = token;
                        form.appendChild(input);
                        form.submit();
                    });
                });
            });
        </script>
    @endpush

    <div style="background:var(--cream); min-height:100vh;">

        {{-- ── Breadcrumb ────────────────────────────────────────────── --}}
        <div style="background:#fff; border-bottom:1px solid var(--rule);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <nav class="flex items-center gap-2 text-xs font-medium" style="color:var(--mute);" aria-label="Breadcrumb">
                    <a href="{{ route('visas.index') }}" class="fb-mono hover:text-red-600 transition-colors">VISA SERVICES</a>
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    <span style="color:var(--ink);">{{ $visa->country }}</span>
                </nav>
            </div>
        </div>

        {{-- ── Hero Band ─────────────────────────────────────────────── --}}
        <div style="background:#fff; border-bottom:1px solid var(--rule);">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
                <div class="flex flex-col md:flex-row md:items-center gap-6">

                    {{-- Flag --}}
                    <div class="shrink-0 w-20 h-20 rounded-2xl flex items-center justify-center text-4xl overflow-hidden"
                        style="background:var(--cream); border:1px solid var(--rule);">
                        @if(!empty($visa->flag) && !Str::startsWith($visa->flag, 'http'))
                            {{ $visa->flag }}
                        @elseif(!empty($visa->flag))
                            <img src="{{ $visa->flag }}" alt="{{ $visa->country }}" class="w-full h-full object-cover">
                        @elseif(!empty($visa->thumbnail))
                            <img src="{{ Str::startsWith($visa->thumbnail,'http') ? $visa->thumbnail : Storage::url($visa->thumbnail) }}"
                                alt="{{ $visa->country }}" class="w-full h-full object-cover">
                        @else
                            🌍
                        @endif
                    </div>

                    {{-- Title block --}}
                    <div class="flex-1">
                        <p class="fb-eyebrow mb-2">{{ $visa->type ?? 'Embassy Visa' }}</p>
                        <h1 class="fb-serif text-3xl md:text-4xl mb-3" style="color:var(--ink);">
                            <em>{{ $visa->country }} Visa</em>
                        </h1>
                        <div class="flex flex-wrap gap-3">
                            @if($visa->processing_time)
                                <span class="fb-chip text-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $visa->processing_time }} processing
                                </span>
                            @endif
                            @if($visa->validity)
                                <span class="fb-chip text-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $visa->validity }} validity
                                </span>
                            @endif
                            @if($visa->maximum_stay)
                                <span class="fb-chip text-sm">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064"/></svg>
                                    {{ $visa->maximum_stay }} max stay
                                </span>
                            @endif
                            @if($visa->approval_rate)
                                <span class="text-sm font-semibold px-3 py-1.5 rounded-full" style="background:#E8F5EC; color:#1F6E3D;">
                                    ✓ {{ $visa->approval_rate }} approval
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Price callout --}}
                    <div class="shrink-0 ota-card px-6 py-4 text-center md:text-right">
                        <p class="fb-mono mb-1">Service fee</p>
                        <p class="text-3xl font-bold" style="color:var(--red);">৳{{ number_format($visa->price) }}</p>
                        <p class="text-xs mt-1" style="color:var(--mute);">per person</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Success message ──────────────────────────────────────── --}}
        @if(session('success'))
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-6">
                <div class="flex items-center gap-3 px-5 py-4 rounded-2xl text-sm font-medium"
                    style="background:#E8F5EC; border:1px solid #B8E0C4; color:#1F6E3D;">
                    <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        {{-- ── Main 2-col layout ────────────────────────────────────── --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- ── LEFT: Content ───────────────────────────────── --}}
                <div class="lg:col-span-2 space-y-6" x-data="{ activeTab: 'summary' }">

                    {{-- Tab nav --}}
                    <div class="ota-card overflow-hidden">
                        <div class="flex" style="border-bottom:1px solid var(--rule);">
                            @foreach([['summary','Summary'],['checklist','Documents'],['policy','Policy & Notes']] as [$tab,$label])
                                <button type="button"
                                    @click="activeTab = '{{ $tab }}'"
                                    class="flex-1 py-4 px-3 text-sm font-semibold transition-colors relative"
                                    :style="activeTab === '{{ $tab }}'
                                        ? 'color:var(--red);'
                                        : 'color:var(--mute);'"
                                    >
                                    {{ $label }}
                                    <span class="absolute bottom-0 left-0 right-0 h-0.5 transition-all"
                                        :style="activeTab === '{{ $tab }}' ? 'background:var(--red);' : 'background:transparent;'"></span>
                                </button>
                            @endforeach
                        </div>

                        {{-- Summary --}}
                        <div x-show="activeTab === 'summary'"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="p-6 md:p-8">
                            <p class="fb-eyebrow mb-3">About this visa</p>
                            <div class="text-base leading-relaxed whitespace-pre-line mb-8" style="color:var(--ink2);">
                                {{ $visa->description }}
                            </div>

                            {{-- Key info grid --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                                @foreach([
                                    ['Type', $visa->type ?? 'Embassy', 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                                    ['Processing', $visa->processing_time ?? 'N/A', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                                    ['Validity', $visa->validity ?? 'N/A', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                    ['Max Stay', $visa->maximum_stay ?? 'N/A', 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064'],
                                ] as [$label, $val, $icon])
                                    <div class="p-4 rounded-2xl text-center" style="background:var(--cream); border:1px solid var(--rule);">
                                        <svg class="w-5 h-5 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--red);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/></svg>
                                        <p class="fb-mono mb-1">{{ $label }}</p>
                                        <p class="font-bold text-sm" style="color:var(--ink);">{{ $val }}</p>
                                    </div>
                                @endforeach
                            </div>

                            {{-- Visit office --}}
                            <div class="rounded-2xl p-5" style="background:var(--cream); border:1px solid var(--rule);">
                                <div class="flex items-start gap-3">
                                    <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center" style="background:var(--red);">
                                        <svg class="w-5 h-5" fill="none" stroke="#fff" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold mb-1" style="color:var(--ink);">Visit our Experience Centre</h3>
                                        <p class="text-sm mb-3" style="color:var(--mute);">Free consultation with our visa experts. No appointment needed.</p>
                                        <p class="text-sm font-semibold mb-3" style="color:var(--ink);">House 45, Road 13, Block D, Banani, Dhaka-1213</p>
                                        <div class="flex flex-wrap gap-3">
                                            <a href="tel:09611677989" class="flex items-center gap-1.5 text-sm font-semibold transition-colors" style="color:var(--red);" onmouseover="this.style.color='var(--ink)'" onmouseout="this.style.color='var(--red)'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                09611-677989
                                            </a>
                                            <a href="https://maps.google.com" target="_blank" rel="noopener" class="flex items-center gap-1.5 text-sm font-semibold transition-colors" style="color:var(--mute);">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                                View on Google Maps
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Checklist --}}
                        <div x-show="activeTab === 'checklist'" style="display:none;"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="p-6 md:p-8">

                            <p class="fb-eyebrow mb-3">Required documents</p>
                            <h2 class="text-xl font-bold mb-6" style="color:var(--ink);">{{ $visa->country }} visa checklist</h2>

                            @php
                                $normalizedDocs = [];
                                if (!empty($visa->required_documents) && is_array($visa->required_documents)) {
                                    $rawDocs = $visa->required_documents;
                                    $firstElem = reset($rawDocs);
                                    if (is_array($firstElem) && isset($firstElem['section'])) {
                                        foreach ($rawDocs as $sec) {
                                            $normalizedDocs[] = ['title' => $sec['section'] ?? '', 'items' => $sec['documents'] ?? []];
                                        }
                                    } else {
                                        $hasStringKeys = count(array_filter(array_keys($rawDocs), 'is_string')) > 0;
                                        if ($hasStringKeys) {
                                            foreach ($rawDocs as $cat => $val) {
                                                $normalizedDocs[] = ['title' => $cat, 'items' => is_array($val) ? $val : [$val]];
                                            }
                                        } else {
                                            $normalizedDocs[] = ['title' => 'General Requirements', 'items' => $rawDocs];
                                        }
                                    }
                                }
                            @endphp

                            @if(count($normalizedDocs) > 0)
                                <div class="space-y-4" x-data="{ openSec: 0 }">
                                    @foreach($normalizedDocs as $si => $section)
                                        <div class="rounded-2xl overflow-hidden" style="border:1px solid var(--rule);">
                                            <button type="button" @click="openSec = (openSec === {{ $si }} ? -1 : {{ $si }})"
                                                class="w-full flex items-center justify-between px-5 py-4 text-left"
                                                style="background:var(--cream);">
                                                <span class="font-semibold text-sm" style="color:var(--ink);">{{ $section['title'] }}</span>
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full" style="background:var(--red);color:#fff;">{{ count($section['items']) }}</span>
                                                    <svg class="w-4 h-4 transition-transform" :class="openSec === {{ $si }} ? 'rotate-180' : ''"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--mute);">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                    </svg>
                                                </div>
                                            </button>
                                            <div x-show="openSec === {{ $si }}" x-collapse class="px-5 pb-4 bg-white">
                                                <ul class="mt-3 space-y-2">
                                                    @foreach($section['items'] as $doc)
                                                        @if(!empty(trim($doc)))
                                                            <li class="flex items-start gap-3 text-sm" style="color:var(--ink2);">
                                                                <span class="shrink-0 w-5 h-5 mt-0.5 rounded-full flex items-center justify-center" style="background:#E8F5EC;">
                                                                    <svg class="w-3 h-3" fill="none" stroke="#1F6E3D" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                                </span>
                                                                {{ $doc }}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @elseif($visa->requirements)
                                <div class="ota-card p-6">
                                    <div class="text-sm leading-relaxed whitespace-pre-line" style="color:var(--ink2);">{{ $visa->requirements }}</div>
                                </div>
                            @else
                                <div class="text-center py-10 rounded-2xl" style="background:var(--cream); border:1px dashed var(--rule);">
                                    <p class="text-sm" style="color:var(--mute);">Document checklist will be updated shortly.</p>
                                    <p class="text-sm mt-2" style="color:var(--mute);">Please <a href="{{ route('contact') }}" style="color:var(--red);">contact us</a> for the full requirements list.</p>
                                </div>
                            @endif

                            {{-- Download CTA --}}
                            <div class="mt-6 flex items-center gap-4 p-4 rounded-2xl" style="background:var(--cream); border:1px solid var(--rule);">
                                <svg class="w-8 h-8 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--red);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <div class="flex-1">
                                    <p class="font-semibold text-sm" style="color:var(--ink);">Download checklist PDF</p>
                                    <p class="text-xs" style="color:var(--mute);">Print-friendly version for your records</p>
                                </div>
                                <button type="button" class="ota-btn-ghost px-4 py-2 text-sm">Download</button>
                            </div>
                        </div>

                        {{-- Policy --}}
                        <div x-show="activeTab === 'policy'" style="display:none;"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            class="p-6 md:p-8">

                            <p class="fb-eyebrow mb-3">Important notes</p>
                            <h2 class="text-xl font-bold mb-6" style="color:var(--ink);">Policy & conditions</h2>

                            @if($visa->important_notes)
                                <div class="space-y-3 mb-6">
                                    @foreach(explode("\n", $visa->important_notes) as $note)
                                        @if(trim($note))
                                            <div class="flex items-start gap-3 p-4 rounded-xl" style="background:var(--cream); border:1px solid var(--rule);">
                                                <span class="shrink-0 w-2 h-2 rounded-full mt-2" style="background:var(--red);"></span>
                                                <p class="text-sm leading-relaxed" style="color:var(--ink2);">{{ trim($note) }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @endif

                            @if($visa->terms)
                                <div class="ota-card p-5">
                                    <h3 class="font-bold mb-3 text-sm" style="color:var(--ink);">Terms & Conditions</h3>
                                    <div class="text-sm leading-relaxed whitespace-pre-line" style="color:var(--mute);">{{ $visa->terms }}</div>
                                </div>
                            @endif

                            @if(!$visa->important_notes && !$visa->terms)
                                <p class="text-sm" style="color:var(--mute);">No specific policy notes. Please contact us for details.</p>
                            @endif
                        </div>
                    </div>

                    {{-- ── Process Steps ─────────────────────────────── --}}
                    <div class="ota-card p-6 md:p-8">
                        <p class="fb-eyebrow mb-3">How it works</p>
                        <h2 class="text-xl font-bold mb-6" style="color:var(--ink);">Your application journey</h2>

                        @php
                            $processSteps = [
                                ['Apply online', 'Fill out the application form and upload your documents securely.'],
                                ['Document review', 'Our visa experts verify every document for accuracy and completeness.'],
                                ['Embassy submission', 'We submit your application to the embassy on your behalf.'],
                                ['Status updates', 'Receive real-time SMS and email updates throughout the process.'],
                                ['Visa delivery', 'Your approved visa is sent digitally or by registered post.'],
                            ];
                        @endphp

                        <div class="space-y-0">
                            @foreach($processSteps as $si => $step)
                                @php $isLast = $si === count($processSteps)-1; @endphp
                                <div class="relative flex gap-4 {{ $isLast ? '' : 'pb-6' }}">
                                    @if(!$isLast)
                                        <div class="absolute left-4 top-8 bottom-0 w-px" style="background:var(--rule); z-index:0;"></div>
                                    @endif
                                    <div class="shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold z-10"
                                        style="background:var(--red); color:#fff; margin-top:2px;">
                                        {{ $si + 1 }}
                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-semibold text-sm mb-0.5" style="color:var(--ink);">{{ $step[0] }}</h3>
                                        <p class="text-sm" style="color:var(--mute);">{{ $step[1] }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <div class="ota-card p-6 flex flex-col sm:flex-row items-center gap-5"
                        style="background:linear-gradient(135deg,#E8F5EC,#D4EDD9); border-color:#B8E0C4;">
                        <div class="shrink-0 w-14 h-14 rounded-2xl flex items-center justify-center" style="background:#25D366;">
                            <svg class="w-7 h-7" fill="#fff" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </div>
                        <div class="flex-1 text-center sm:text-left">
                            <h3 class="font-bold mb-1" style="color:var(--ink);">Questions about your visa?</h3>
                            <p class="text-sm" style="color:var(--mute);">Chat directly with a visa specialist. Average response time: under 5 minutes.</p>
                        </div>
                        <a href="https://wa.me/8809611677989?text=Hi!+I+need+help+with+{{ urlencode($visa->country) }}+visa"
                            target="_blank" rel="noopener"
                            class="shrink-0 flex items-center gap-2 font-semibold px-5 py-3 rounded-xl text-sm transition-all"
                            style="background:#25D366; color:#fff; text-decoration:none;"
                            onmouseover="this.style.background='#128C7E'" onmouseout="this.style.background='#25D366'">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            Chat now
                        </a>
                    </div>
                </div>

                {{-- ── RIGHT: Booking Sidebar ───────────────────────── --}}
                <div class="lg:col-span-1">
                    <div class="ota-card p-6 md:p-8" style="position:sticky; top:88px;">

                        {{-- Price --}}
                        <div class="mb-6 pb-6" style="border-bottom:1px solid var(--rule);">
                            <p class="fb-mono mb-1">Service fee</p>
                            <div class="flex items-baseline gap-2">
                                <span class="text-3xl font-bold" style="color:var(--red);">৳{{ number_format($visa->price) }}</span>
                                <span class="text-sm" style="color:var(--mute);">/ person</span>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mt-4">
                                @if($visa->validity)
                                    <div>
                                        <p class="fb-mono mb-0.5">Validity</p>
                                        <p class="font-semibold text-sm" style="color:var(--ink);">{{ $visa->validity }}</p>
                                    </div>
                                @endif
                                @if($visa->maximum_stay)
                                    <div>
                                        <p class="fb-mono mb-0.5">Max Stay</p>
                                        <p class="font-semibold text-sm" style="color:var(--ink);">{{ $visa->maximum_stay }}</p>
                                    </div>
                                @endif
                                @if($visa->processing_time)
                                    <div>
                                        <p class="fb-mono mb-0.5">Processing</p>
                                        <p class="font-semibold text-sm" style="color:var(--ink);">{{ $visa->processing_time }}</p>
                                    </div>
                                @endif
                                @if($visa->approval_rate)
                                    <div>
                                        <p class="fb-mono mb-0.5">Approval</p>
                                        <p class="font-semibold text-sm" style="color:#1F6E3D;">{{ $visa->approval_rate }}</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Errors --}}
                        @if($errors->any())
                            <div class="mb-4 p-4 rounded-xl text-sm" style="background:#FFE9EC; color:var(--red); border:1px solid #FFD0D8;">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Booking Form --}}
                        <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
                            @csrf
                            <input type="hidden" name="payable_type" value="visa">
                            <input type="hidden" name="payable_id" value="{{ $visa->id }}">

                            <div class="fb-field">
                                <label class="fb-field-label">Expected travel date</label>
                                <input type="date" name="booking_date" value="{{ old('booking_date') }}" required
                                    min="{{ date('Y-m-d') }}" class="fb-input">
                            </div>

                            <div class="fb-field">
                                <label class="fb-field-label">Number of persons</label>
                                <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required
                                    class="fb-input">
                            </div>

                            @auth
                                <div class="fb-field" style="background:var(--cream);">
                                    <label class="fb-field-label">Full name</label>
                                    <input type="text" value="{{ Auth::user()->name }}" readonly class="fb-input cursor-not-allowed" style="color:var(--mute);">
                                </div>
                                <div class="fb-field" style="background:var(--cream);">
                                    <label class="fb-field-label">Email</label>
                                    <input type="email" value="{{ Auth::user()->email }}" readonly class="fb-input cursor-not-allowed" style="color:var(--mute);">
                                </div>
                                <div class="fb-field" style="background:var(--cream);">
                                    <label class="fb-field-label">Phone</label>
                                    <input type="text" value="{{ Auth::user()->phone ?? '' }}" readonly class="fb-input cursor-not-allowed" style="color:var(--mute);">
                                </div>
                            @else
                                <div class="fb-field">
                                    <label class="fb-field-label">Full name</label>
                                    <input type="text" name="guest_name" value="{{ old('guest_name') }}" required
                                        class="fb-input" placeholder="Your full name">
                                </div>
                                <div class="fb-field">
                                    <label class="fb-field-label">Email</label>
                                    <input type="email" name="guest_email" value="{{ old('guest_email') }}" required
                                        class="fb-input" placeholder="you@email.com">
                                </div>
                                <div class="fb-field">
                                    <label class="fb-field-label">Phone</label>
                                    <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" required
                                        class="fb-input" placeholder="+8801...">
                                </div>
                            @endauth

                            <button type="submit" class="ota-btn-primary w-full justify-center py-4 text-base font-bold">
                                Apply for visa
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </form>

                        {{-- Contact --}}
                        <div class="mt-6 pt-6 space-y-3" style="border-top:1px solid var(--rule);">
                            <p class="font-semibold text-sm mb-3" style="color:var(--ink);">Need help?</p>
                            <a href="tel:09611677989"
                                class="flex items-center gap-3 p-3 rounded-xl transition-colors"
                                style="background:var(--cream); border:1px solid var(--rule);"
                                onmouseover="this.style.borderColor='var(--ink)'" onmouseout="this.style.borderColor='var(--rule)'">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#fff; border:1px solid var(--rule);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#1F6E3D;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                </div>
                                <div>
                                    <p class="fb-mono">Call 24/7</p>
                                    <p class="font-bold text-sm" style="color:var(--ink);">09611-677989</p>
                                </div>
                            </a>
                            <a href="mailto:visa.flyoverbd@gmail.com"
                                class="flex items-center gap-3 p-3 rounded-xl transition-colors"
                                style="background:var(--cream); border:1px solid var(--rule);"
                                onmouseover="this.style.borderColor='var(--ink)'" onmouseout="this.style.borderColor='var(--rule)'">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0" style="background:#fff; border:1px solid var(--rule);">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--red);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="fb-mono">Email us</p>
                                    <p class="font-bold text-sm" style="color:var(--ink);">visa.flyoverbd@gmail.com</p>
                                </div>
                            </a>
                        </div>

                        {{-- Trust --}}
                        <div class="mt-5 grid grid-cols-3 gap-3 text-center pt-5" style="border-top:1px solid var(--rule);">
                            @foreach([['Secure','M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],['Verified','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],['Expert','M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z']] as [$lbl,$icon])
                                <div>
                                    <svg class="w-5 h-5 mx-auto mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--red);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="{{ $icon }}"/></svg>
                                    <p class="text-xs" style="color:var(--mute);">{{ $lbl }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Bottom CTA ────────────────────────────────────────────── --}}
        <div class="py-14" style="background:var(--ink);">
            <div class="max-w-3xl mx-auto text-center px-4">
                <p class="fb-eyebrow mb-4" style="color:rgba(250,246,238,0.6);">Other destinations</p>
                <h2 class="fb-serif text-3xl md:text-4xl mb-6" style="color:var(--cream);">
                    <em>Explore more visa services</em>
                </h2>
                <a href="{{ route('visas.index') }}" class="ota-btn-primary px-8 py-3.5 text-base">
                    Browse all 184 countries
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
            </div>
        </div>

    </div>
</x-app-layout>
