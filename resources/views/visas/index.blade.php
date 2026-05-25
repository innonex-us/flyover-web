<x-app-layout
    title="Visa Processing Services | FlyoverBD"
    meta_description="94.2% visa approval rate. Get hassle-free visa processing for Malaysia, Thailand, UAE, Schengen, USA, UK and 180+ countries from Bangladesh."
>

{{-- ── Hero ─────────────────────────────── --}}
<section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="px-4 py-14 text-center">
    <p class="section-eyebrow mb-2">184 countries covered</p>
    <h1 class="font-extrabold text-4xl md:text-5xl text-gray-900 mb-3" style="font-family:'Merriweather',Georgia,serif;">
        Visa Processing Services
    </h1>
    <p class="text-gray-500 max-w-md mx-auto mb-2">94.2% approval rate · Hassle-free documentation · Expert guidance</p>

    {{-- Trust badges --}}
    <div class="flex flex-wrap justify-center gap-4 mb-8 mt-4">
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-green-700 bg-green-50 border border-green-200 rounded-full px-3 py-1">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            94.2% Approval Rate
        </span>
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-blue-700 bg-blue-50 border border-blue-200 rounded-full px-3 py-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            184+ Countries
        </span>
        <span class="inline-flex items-center gap-1.5 text-xs font-bold text-purple-700 bg-purple-50 border border-purple-200 rounded-full px-3 py-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            24/7 Support
        </span>
    </div>

    {{-- Real-time Search --}}
    <div class="max-w-xl mx-auto relative"
         x-data="{
             query: '{{ request('search') }}',
             suggestions: [],
             show: false,
             loading: false,
             timer: null,
             fetchSuggestions() {
                 if (this.query.length < 1) {
                     this.suggestions = [];
                     this.show = false;
                     return;
                 }
                 this.loading = true;
                 clearTimeout(this.timer);
                 this.timer = setTimeout(() => {
                     fetch(`{{ route('search.suggestions') }}?type=visas&query=${encodeURIComponent(this.query)}`)
                         .then(r => r.json())
                         .then(data => {
                             this.suggestions = data;
                             this.show = data.length > 0;
                             this.loading = false;
                         })
                         .catch(() => { this.loading = false; });
                 }, 180);
             },
             go(url) { window.location.href = url; },
             viewAll() {
                 window.location.href = '{{ route('visas.index') }}?search=' + encodeURIComponent(this.query);
             }
         }"
         @keydown.escape.window="show = false">

        <div class="flex gap-2">
            <div class="flex-1 relative">
                <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text"
                       x-model="query"
                       @input="fetchSuggestions()"
                       @focus="fetchSuggestions()"
                       placeholder="Search by country…"
                       class="search-field pl-11 py-3.5 w-full"
                       autocomplete="off">
                <div x-show="loading" class="absolute right-3 top-1/2 -translate-y-1/2">
                    <div class="w-4 h-4 border-2 border-red-500 border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
            <button @click="viewAll()" class="btn-primary px-7 py-3.5 rounded-xl">Search</button>
            @if(request('search'))
            <a href="{{ route('visas.index') }}" class="flex items-center px-4 py-3.5 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-500 hover:text-gray-700 transition">
                Clear
            </a>
            @endif
        </div>

        {{-- Search Dropdown --}}
        <div x-show="show && suggestions.length > 0"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-3 scale-[0.98]"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 translate-y-0 scale-100"
             x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]"
             @click.outside="show = false"
             class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl shadow-black/25 border border-gray-100 overflow-hidden z-50"
             style="display:none;">

            {{-- Header --}}
            <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-blue-50 to-white border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-xs font-semibold text-gray-600" x-text="query ? 'Visa results for &quot;' + query + '&quot;' : 'Popular visa services'"></span>
                </div>
                <div x-show="loading" class="w-4 h-4 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
            </div>

            {{-- Results --}}
            <ul class="max-h-[320px] overflow-y-auto py-2">
                <template x-for="(item, index) in suggestions" :key="item.url">
                    <li @click="go(item.url)"
                        class="group mx-2 rounded-xl cursor-pointer transition-all duration-200 border border-transparent hover:border-blue-100 hover:bg-blue-50/50 hover:shadow-sm"
                        :class="{'bg-blue-50/30': index === 0}">
                        <div class="flex items-center gap-3 px-3 py-2.5">
                            {{-- Image with visa badge --}}
                            <div class="relative flex-shrink-0">
                                <img :src="item.image || 'https://via.placeholder.com/120x80?text=Visa'"
                                     alt=""
                                     class="w-16 h-12 object-cover rounded-lg bg-gray-100 shadow-sm group-hover:scale-105 transition-transform duration-300">
                                <span class="absolute -bottom-1 -right-1 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-md bg-blue-500 text-white">Visa</span>
                            </div>
                            {{-- Content --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate group-hover:text-blue-600 transition-colors" x-text="item.text"></p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <p class="text-xs text-gray-500 truncate" x-text="item.subtext"></p>
                                </div>
                            </div>
                            {{-- Price or arrow --}}
                            <div class="flex-shrink-0 flex items-center gap-2">
                                <span x-show="item.price" class="text-sm font-bold text-blue-600" x-text="item.price ? '৳' + item.price.toLocaleString() : ''"></span>
                                <div class="w-7 h-7 rounded-full bg-gray-100 group-hover:bg-blue-500 flex items-center justify-center transition-colors">
                                    <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </div>
                            </div>
                        </div>
                    </li>
                </template>
            </ul>

            {{-- Footer --}}
            <div class="px-4 py-2 bg-gray-50 border-t border-gray-100">
                <button @click="viewAll()" class="w-full flex items-center justify-center gap-2 text-xs font-semibold text-gray-600 hover:text-blue-600 transition-colors py-1">
                    <span>View all visa results</span>
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Popular countries --}}
    <div class="flex flex-wrap justify-center gap-2 mt-5">
        @foreach(['Malaysia','Thailand','UAE','Singapore','India','Schengen','UK','USA','Canada','Japan'] as $c)
        <a href="{{ route('visas.index', ['search' => $c]) }}"
           class="text-xs font-semibold px-3.5 py-1.5 rounded-full transition {{ request('search') === $c ? 'bg-red-600 text-white' : 'bg-white border border-gray-200 text-gray-600 hover:border-red-300 hover:text-red-600' }}">
            {{ $c }}
        </a>
        @endforeach
    </div>
</section>

{{-- ── Grid ─────────────────────────────── --}}
<section class="bg-white py-12">
    <div class="max-w-7xl mx-auto px-4">

        <div class="flex items-center justify-between mb-6">
            <p class="text-sm text-gray-500">
                @if(request('search'))
                    Results for <strong class="text-gray-800">"{{ request('search') }}"</strong>
                    &nbsp;·&nbsp;
                @endif
                <strong class="text-gray-800">{{ $visas->total() }}</strong> visa services available
            </p>
        </div>

        @if($visas->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($visas as $visa)
                <x-visa-card :visa="$visa" />
            @endforeach
        </div>

        @if($visas->hasPages())
        <div class="mt-10 flex justify-center">
            {{ $visas->appends(request()->query())->links() }}
        </div>
        @endif

        @else
        <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-xl font-bold text-gray-400 mb-2">No visa services found</p>
            <p class="text-sm text-gray-400 mb-5">Try a different country name or browse all services.</p>
            <a href="{{ route('visas.index') }}" class="btn-primary">Browse All</a>
        </div>
        @endif
    </div>
</section>

{{-- ── How it works ─────────────────────── --}}
<section style="background:#F9F6EF;border-top:1px solid #E4DCC9;" class="py-14">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-10">
            <p class="section-eyebrow mb-2">Simple process</p>
            <h2 class="font-extrabold text-3xl text-gray-900">How Visa Processing Works</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-4 gap-6 text-center">
            @php $steps = [
                ['bg-blue-50','text-blue-600','M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','1. Apply Online','Fill the booking form with your travel details and documents.'],
                ['bg-green-50','text-green-600','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','2. Document Review','Our visa experts verify your documents and guide you if anything is missing.'],
                ['bg-yellow-50','text-yellow-600','M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z','3. Submission','We submit the application on your behalf to the embassy or consulate.'],
                ['bg-red-50','text-red-600','M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z','4. Visa Granted','Receive your visa. Our 94.2% approval rate speaks for itself.'],
            ]; @endphp
            @foreach($steps as [$bg,$color,$icon,$title,$desc])
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm">
                <div class="w-12 h-12 {{ $bg }} rounded-xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 text-sm mb-1">{{ $title }}</h3>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $desc }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ───────────────────────────────── --}}
<section class="py-14 text-center" style="background:#18130E;color:#FAF6EE;">
    <p class="section-eyebrow mb-3" style="color:#C8102E;">Need help?</p>
    <h2 class="font-extrabold text-3xl mb-3" style="font-family:'Merriweather',Georgia,serif;">Talk to a visa expert - free.</h2>
    <p class="text-sm max-w-sm mx-auto mb-6" style="color:#A09890;">Real people, not bots. Available 24/7 on WhatsApp and phone.</p>
    <div class="flex flex-wrap justify-center gap-3">
        <a href="https://wa.me/8801335111370" target="_blank"
           class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold px-8 py-3.5 rounded-xl transition text-sm">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
            WhatsApp Us
        </a>
        <a href="{{ route('contact') }}" style="border-color:#FAF6EE;color:#FAF6EE;" class="ota-btn-ghost">Contact Us</a>
    </div>
</section>

</x-app-layout>
