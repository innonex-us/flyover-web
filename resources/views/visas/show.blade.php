@php
    $defaultImage = asset('banner/hero-banner-1.png');
    $visaImage = $visa->thumbnail
        ? (\Illuminate\Support\Str::startsWith($visa->thumbnail, 'http') ? $visa->thumbnail : Storage::url($visa->thumbnail))
        : $defaultImage;

    // Generate SEO meta description
    $visaDescription = $visa->description
        ? \Illuminate\Support\Str::limit(strip_tags($visa->description), 160)
        : 'Apply for ' . $visa->country . ' ' . $visa->type . ' visa with FlyoverBD. Price: ৳' . number_format($visa->price) . '. Fast processing, hassle-free documentation.';
@endphp

<x-app-layout
    :title="$visa->country . ' ' . $visa->type . ' Visa | Apply Online | FlyoverBD'"
    :meta_description="$visaDescription"
    :meta_image="$visaImage"
    :og_type="'product'"
>
    @push('meta')
    {{-- JSON-LD Structured Data for Visa Service --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "Service",
        "name": {{ Illuminate\Support\Js::from($visa->country . ' ' . $visa->type . ' Visa') }},
        "description": {{ Illuminate\Support\Js::from(strip_tags($visa->description)) }},
        "image": {{ Illuminate\Support\Js::from($visaImage) }},
        "url": {{ Illuminate\Support\Js::from(route('visas.show', $visa->slug)) }},
        "provider": {
            "@type": "TravelAgency",
            "name": "FlyoverBD",
            "url": {{ Illuminate\Support\Js::from(config('app.url')) }},
            "logo": {
                "@type": "ImageObject",
                "url": {{ Illuminate\Support\Js::from(asset('logo.png')) }}
            }
        },
        "offers": {
            "@type": "Offer",
            "price": "{{ $visa->price }}",
            "priceCurrency": "BDT"
        },
        "areaServed": {
            "@type": "Country",
            "name": {{ Illuminate\Support\Js::from($visa->country) }}
        }
    }
    </script>
    @endpush

    @push('scripts')
    <script src="https://www.google.com/recaptcha/api.js?render={{ env('RECAPTCHA_SITE_KEY') }}"></script>
    <script>
        grecaptcha.ready(function() {
            document.querySelector('form[action="{{ route('bookings.store') }}"]').addEventListener('submit', function(e) {
                e.preventDefault();
                grecaptcha.execute('{{ env('RECAPTCHA_SITE_KEY') }}', {action: 'submit'}).then(function(token) {
                    var input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'g-recaptcha-response';
                    input.value = token;
                    document.querySelector('form[action="{{ route('bookings.store') }}"]').appendChild(input);
                    document.querySelector('form[action="{{ route('bookings.store') }}"]').submit();
                });
            });
        });
    </script>
    @endpush

    {{-- ── Breadcrumb ───────────────────────── --}}
    <div class="bg-white border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex text-xs font-medium text-gray-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('home') }}" class="hover:text-red-500 transition">Home</a></li>
                    <li><svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                    <li><a href="{{ route('visas.index') }}" class="hover:text-red-500 transition">Visa Services</a></li>
                    <li><svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                    <li class="text-gray-900 font-semibold truncate">{{ $visa->country }}</li>
                </ol>
            </nav>
        </div>
    </div>

    {{-- ── Visa Hero ────────────────────────── --}}
    <section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center gap-6">
                {{-- Flag / thumbnail --}}
                @if($visa->thumbnail)
                <div class="w-24 h-16 rounded-xl overflow-hidden border border-gray-200 shadow-sm flex-shrink-0">
                    <img src="{{ Str::startsWith($visa->thumbnail, 'http') ? $visa->thumbnail : Storage::url($visa->thumbnail) }}"
                         alt="{{ $visa->country }}" class="w-full h-full object-cover">
                </div>
                @endif

                {{-- Title + badges --}}
                <div class="flex-1">
                    <div class="flex flex-wrap items-center gap-2 mb-2">
                        <span class="ota-tag-red">{{ $visa->type }}</span>
                        <span class="ota-tag-green">94.2% Approval Rate</span>
                    </div>
                    <h1 class="font-extrabold text-3xl md:text-4xl text-gray-900" style="font-family:'Merriweather',Georgia,serif;">
                        {{ $visa->country }} Visa
                    </h1>
                </div>

                {{-- Key stats strip --}}
                <div class="flex flex-wrap gap-5 md:gap-8 text-center md:text-right">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Fee</p>
                        <p class="text-2xl font-extrabold" style="color:#C8102E;">৳{{ number_format($visa->price) }}</p>
                        <p class="text-[10px] text-gray-400">per person</p>
                    </div>
                    @if($visa->processing_time)
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Processing</p>
                        <p class="text-lg font-bold text-gray-900">{{ $visa->processing_time }}</p>
                    </div>
                    @endif
                    @if($visa->validity)
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Validity</p>
                        <p class="text-lg font-bold text-gray-900">{{ $visa->validity }}</p>
                    </div>
                    @endif
                    @if($visa->maximum_stay)
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Max Stay</p>
                        <p class="text-lg font-bold text-gray-900">{{ $visa->maximum_stay }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- ── Share Options ─────────────────────── --}}
    {{-- Visa gallery (responsive thumbnails under main image on mobile) --}}
    @php
        $visaDefault = asset('banner/hero-banner-1.png');
        $visaGallery = [];
        if ($visa->thumbnail) {
            $visaGallery[] = \Illuminate\Support\Str::startsWith($visa->thumbnail, 'http') ? $visa->thumbnail : Storage::url($visa->thumbnail);
        }
        if (!empty($visa->images) && is_array($visa->images)) {
            foreach ($visa->images as $img) {
                $visaGallery[] = \Illuminate\Support\Str::startsWith($img, 'http') ? $img : Storage::url($img);
            }
        }
        if ($visaGallery === []) $visaGallery = [$visaDefault];
    @endphp



    {{-- ── Body ─────────────────────────────── --}}
    <div class="py-2" style="background:#F9F6EF;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">



            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- ── Left: Tabs ──────────────────── --}}
                <div class="lg:col-span-2 space-y-6" x-data="{ activeTab: 'summary' }">

                    {{-- Gallery --}}
                    @include('components.photo-gallery', ['images' => $visaGallery, 'alt' => $visa->country . ' Visa'])

                    {{-- Tab nav --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden mb-6">
                        <div class="flex border-b border-gray-100">
                            @foreach([['summary','Summary'],['checklist','Documents'],['policy','Policy']] as [$id,$label])
                            <button @click="activeTab = '{{ $id }}'"
                                    :class="activeTab === '{{ $id }}' ? 'border-b-2 text-gray-900 font-bold' : 'text-gray-500 hover:text-gray-700'"
                                    class="flex-1 py-4 text-sm font-semibold transition border-b-2 border-transparent"
                                    :style="activeTab === '{{ $id }}' ? 'border-color:#C8102E;color:#18130E;' : ''">
                                {{ $label }}
                            </button>
                            @endforeach
                        </div>

                        <div class="p-6 md:p-8">

                            {{-- Summary --}}
                            <div x-show="activeTab === 'summary'"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <h2 class="font-bold text-gray-900 text-lg mb-4">Visa Summary</h2>
                                <p class="text-gray-600 leading-relaxed mb-8 text-sm whitespace-pre-line">{{ $visa->description }}</p>

                                <div class="rounded-xl p-6 border" style="background:#F9F6EF;border-color:#E4DCC9;">
                                    <h3 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                        Visit Our Experience Center
                                    </h3>
                                    <p class="text-sm text-gray-600 mb-4">Visit us for a free visa consultation with our travel experts and get started on your application.</p>
                                    <div class="bg-white rounded-xl border border-gray-100 px-4 py-3 text-sm font-semibold text-gray-900 mb-4">
                                        House 45, Road 13, Block D, Banani, Dhaka
                                    </div>
                                    <div class="flex flex-wrap gap-4">
                                        <a href="tel:09611677989" class="flex items-center gap-2 text-sm font-semibold text-red-600 hover:text-red-700 transition">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            09611-677989
                                        </a>
                                        <a href="https://wa.me/8801335111370" target="_blank" class="flex items-center gap-2 text-sm font-semibold text-green-600 hover:text-green-700 transition">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                            WhatsApp
                                        </a>
                                    </div>
                                </div>
                            </div>

                            {{-- Documents --}}
                            <div x-show="activeTab === 'checklist'" style="display:none;"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <h2 class="font-bold text-gray-900 text-lg mb-6">Required Documents</h2>

                                @if(!empty($visa->required_documents) && is_array($visa->required_documents))
                                    @php
                                        $normalizedDocs = [];
                                        $rawDocs = $visa->required_documents;
                                        if (is_array($rawDocs) && count($rawDocs) > 0) {
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
                                    <div class="space-y-5">
                                        @foreach($normalizedDocs as $sectionData)
                                            @if(!empty($sectionData['title']) || !empty($sectionData['items']))
                                            <div class="bg-gray-50 rounded-xl border border-gray-100 overflow-hidden">
                                                @if(!empty($sectionData['title']))
                                                <div class="px-5 py-3 border-b border-gray-100">
                                                    <h3 class="font-bold text-gray-900 text-sm">{{ $sectionData['title'] }}</h3>
                                                </div>
                                                @endif
                                                @if(!empty($sectionData['items']))
                                                <ul class="p-5 space-y-2.5">
                                                    @foreach($sectionData['items'] as $docItem)
                                                        @if(!empty(trim($docItem)))
                                                        <li class="flex items-start gap-3 text-sm text-gray-700">
                                                            <span class="flex-shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center mt-0.5">
                                                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                                            </span>
                                                            {{ $docItem }}
                                                        </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                                @endif
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="bg-gray-50 rounded-xl border border-gray-100 p-6">
                                        <p class="text-sm text-gray-600 whitespace-pre-line">{{ $visa->requirements }}</p>
                                    </div>
                                @endif
                            </div>

                            {{-- Policy --}}
                            <div x-show="activeTab === 'policy'" style="display:none;"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 translate-y-1"
                                 x-transition:enter-end="opacity-100 translate-y-0">
                                <h2 class="font-bold text-gray-900 text-lg mb-6">Important Notes &amp; Policy</h2>

                                @if($visa->important_notes)
                                <div class="bg-gray-50 rounded-xl border border-gray-100 p-6 mb-5">
                                    <ul class="space-y-3">
                                        @foreach(explode("\n", $visa->important_notes) as $note)
                                            @if(trim($note))
                                            <li class="flex items-start gap-3 text-sm text-gray-600">
                                                <span class="w-1.5 h-1.5 rounded-full mt-2 flex-shrink-0" style="background:#C8102E;"></span>
                                                {{ trim($note) }}
                                            </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                @endif

                                @if($visa->terms)
                                <div class="text-sm text-gray-600 leading-relaxed whitespace-pre-line">{{ $visa->terms }}</div>
                                @endif

                                @if(!$visa->important_notes && !$visa->terms)
                                <p class="text-sm text-gray-400 italic">No policy information available for this visa.</p>
                                @endif
                            </div>

                        </div>
                    </div>

                    {{-- WhatsApp strip --}}
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex flex-col sm:flex-row items-center gap-4">
                        <div class="flex-1">
                            <p class="font-bold text-gray-900 text-sm">Need help with your application?</p>
                            <p class="text-xs text-gray-500 mt-0.5">Our visa experts are available 24/7 on WhatsApp.</p>
                        </div>
                        <a href="https://wa.me/8801335111370" target="_blank"
                           class="flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-bold px-5 py-3 rounded-xl transition text-sm flex-shrink-0">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                            Chat on WhatsApp
                        </a>
                    </div>

                    <div class="pt-2">
                        <x-share-buttons :title="$visa->country . ' Visa - ' . $visa->type" />
                    </div>
                </div>

                {{-- ── Right: Booking sidebar ───────── --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-20">
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

                            {{-- Price --}}
                            <div class="text-center pb-5 mb-5 border-b border-gray-100">
                                <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Visa Fee</p>
                                <p class="text-4xl font-extrabold" style="color:#C8102E;">৳{{ number_format($visa->price) }}</p>
                                <p class="text-xs text-gray-400 mt-1">per person</p>
                            </div>

                            {{-- Quick stats --}}
                            @if($visa->validity || $visa->maximum_stay)
                            <div class="grid grid-cols-2 gap-3 mb-5">
                                @if($visa->validity)
                                <div class="bg-gray-50 rounded-xl p-3 text-center">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Validity</p>
                                    <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $visa->validity }}</p>
                                </div>
                                @endif
                                @if($visa->maximum_stay)
                                <div class="bg-gray-50 rounded-xl p-3 text-center">
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">Max Stay</p>
                                    <p class="font-bold text-gray-900 text-sm mt-0.5">{{ $visa->maximum_stay }}</p>
                                </div>
                                @endif
                            </div>
                            @endif

                            <h3 class="font-bold text-gray-900 mb-4">Apply Now</h3>



                            <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="payable_type" value="visa">
                                <input type="hidden" name="payable_id" value="{{ $visa->id }}">

                                <div>
                                    <label class="fb-field-label mb-1.5">Expected Travel Date</label>
                                    <input type="date" name="booking_date" value="{{ old('booking_date') }}" required
                                           min="{{ date('Y-m-d') }}" class="fb-input">
                                </div>

                                <div>
                                    <label class="fb-field-label mb-1.5">Number of Persons</label>
                                    <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" required class="fb-input">
                                </div>

                                @auth
                                <div class="space-y-3">
                                    <div>
                                        <label class="fb-field-label mb-1.5">Full Name</label>
                                        <input type="text" value="{{ Auth::user()->name }}" readonly
                                               class="fb-input bg-gray-50 cursor-not-allowed text-gray-500">
                                    </div>
                                    <div>
                                        <label class="fb-field-label mb-1.5">Email Address</label>
                                        <input type="email" value="{{ Auth::user()->email }}" readonly
                                               class="fb-input bg-gray-50 cursor-not-allowed text-gray-500">
                                    </div>
                                    <div>
                                        <label class="fb-field-label mb-1.5">Phone</label>
                                        <input type="text" value="{{ Auth::user()->phone }}" readonly
                                               class="fb-input bg-gray-50 cursor-not-allowed text-gray-500">
                                    </div>
                                </div>
                                @else
                                <div class="space-y-3">
                                    <div>
                                        <label class="fb-field-label mb-1.5">Full Name</label>
                                        <input type="text" name="guest_name" value="{{ old('guest_name') }}" required
                                               placeholder="John Doe" class="fb-input">
                                    </div>
                                    <div>
                                        <label class="fb-field-label mb-1.5">Email Address</label>
                                        <input type="email" name="guest_email" value="{{ old('guest_email') }}" required
                                               placeholder="john@example.com" class="fb-input">
                                    </div>
                                    <div>
                                        <label class="fb-field-label mb-1.5">Phone</label>
                                        <input type="text" name="guest_phone" value="{{ old('guest_phone') }}" required
                                               placeholder="+8801..." class="fb-input">
                                    </div>
                                </div>
                                @endauth

                                <button type="submit" class="btn-primary w-full py-4 text-base">
                                    Apply for Visa
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                </button>

                                <p class="text-[10px] font-bold text-gray-400 text-center uppercase tracking-widest">Instant confirmation via email</p>
                            </form>

                            {{-- Trust icons --}}
                            <div class="mt-5 pt-5 border-t border-gray-100 flex justify-around">
                                @foreach([['bg-green-50','text-green-600','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','Verified'],['bg-blue-50','text-blue-600','M13 10V3L4 14h7v7l9-11h-7z','Fast'],['bg-yellow-50','text-yellow-600','M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','Best Price']] as [$bg,$color,$path,$label])
                                <div class="text-center">
                                    <div class="w-9 h-9 {{ $bg }} rounded-full flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-4 h-4 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/></svg>
                                    </div>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ $label }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
