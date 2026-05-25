@php
    $defaultImage = asset('banner/hero-banner-1.png');
    $mainImage = $package->thumbnail
        ? (\Illuminate\Support\Str::startsWith($package->thumbnail, 'http') ? $package->thumbnail : Storage::url($package->thumbnail))
        : $defaultImage;

    // Generate SEO meta description from package description
    $metaDescription = $package->description
        ? \Illuminate\Support\Str::limit(strip_tags($package->description), 160)
        : 'Book ' . $package->title . ' with FlyoverBD. ' . ($package->duration_days ? $package->duration_days . ' days tour package' : 'Tour package') . ' starting from ৳' . number_format($package->price) . ' per person.';

    $galleryImages = [];
    $pushGallery = function (string $url) use (&$galleryImages) {
        if ($url !== '' && !in_array($url, $galleryImages, true)) {
            $galleryImages[] = $url;
        }
    };
    $pushGallery($mainImage);
    if (!empty($package->images) && is_array($package->images)) {
        foreach ($package->images as $img) {
            $resolved = \Illuminate\Support\Str::startsWith($img, 'http') ? $img : Storage::url($img);
            $pushGallery($resolved);
        }
    }
    if ($galleryImages === []) {
        $galleryImages = [$defaultImage];
        $mainImage = $defaultImage;
    }
@endphp

<x-app-layout
    :title="$title"
    :meta_description="$metaDescription"
    :meta_image="$mainImage"
    :og_type="'product'"
>
    @push('meta')
    <link rel="preload" href="{{ $mainImage }}" as="image">

    {{-- JSON-LD Structured Data for Tour/Product --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "TouristAttraction",
        "name": {{ Illuminate\Support\Js::from($package->title) }},
        "description": {{ Illuminate\Support\Js::from(strip_tags($package->description)) }},
        "image": {{ Illuminate\Support\Js::from($mainImage) }},
        "url": {{ Illuminate\Support\Js::from(route('packages.show', $package->slug)) }},
        "touristType": ["Tourism", "Sightseeing"],
        "offers": {
            "@type": "Offer",
            "price": "{{ $package->price }}",
            "priceCurrency": "BDT",
            "availability": "https://schema.org/InStock"
        },
        "provider": {
            "@type": "TravelAgency",
            "name": "FlyoverBD",
            "url": {{ Illuminate\Support\Js::from(config('app.url')) }},
            "logo": {
                "@type": "ImageObject",
                "url": {{ Illuminate\Support\Js::from(asset('logo.png')) }}
            }
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

    <div class="min-h-screen pb-12" style="background:#F9F6EF;" x-data="{
        openInquiryModal: false,
        activeSection: 'itinerary',
        inquiryForm: {
            destinations: [{ country: '', nights: '', cities: [{ name: '', nights: '' }] }],
            adults: 1, children: 0, infants: 0,
            hotel_type: '', travel_date: '', message: '', name: '', email: '', phone: ''
        },
        addDestination() { this.inquiryForm.destinations.push({ country: '', nights: '', cities: [{ name: '', nights: '' }] }); },
        removeDestination(index) { this.inquiryForm.destinations.splice(index, 1); },
        addCity(dIndex) { this.inquiryForm.destinations[dIndex].cities.push({ name: '', nights: '' }); },
        removeCity(dIndex, cIndex) { this.inquiryForm.destinations[dIndex].cities.splice(cIndex, 1); }
    }">

        {{-- ── Breadcrumb ───────────────────────── --}}
        <div class="bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <nav class="flex text-xs font-medium text-gray-500" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li><a href="{{ route('home') }}" class="hover:text-red-500 transition">Home</a></li>
                        <li><svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                        <li><a href="{{ route('packages.index') }}" class="hover:text-red-500 transition">Tours</a></li>
                        <li><svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/></svg></li>
                        <li class="text-gray-900 font-semibold truncate">{{ $package->title }}</li>
                    </ol>
                </nav>
            </div>
        </div>

        {{-- ── Package Hero ─────────────────────── --}}
        <section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row md:items-start gap-5 justify-between">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-3">
                            <span class="ota-tag-red">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                                {{ $package->location }}
                            </span>
                            @if($package->duration_days)
                            <span class="ota-tag-soft">
                                {{ $package->duration_days }}D / {{ $package->duration_days - 1 }}N
                            </span>
                            @endif
                            @if($package->is_active)
                            <span class="ota-tag-green">Popular</span>
                            @endif
                        </div>
                        <h1 class="font-extrabold text-3xl md:text-4xl leading-tight text-gray-900 mb-4" style="font-family:'Merriweather',Georgia,serif;">
                            {{ $package->title }}
                        </h1>
                        <div class="flex flex-wrap gap-4 text-sm">
                            <div class="flex items-center gap-1.5 text-gray-600">
                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="font-semibold">
                                    @if($package->start_date)
                                        {{ $package->start_date->format('M j, Y') }}
                                    @else
                                        <span class="text-gray-400">Date TBA</span>
                                    @endif
                                </span>
                            </div>
                            @if(!empty($package->travel_data) && is_array($package->travel_data))
                                @foreach($package->travel_data as $item)
                                    @if(!empty($item['label']) || !empty($item['content']))
                                    <div class="flex items-center gap-1.5 text-gray-600">
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-gray-400">{{ $item['label'] ?? '' }}</span>
                                        <span class="font-semibold text-gray-800">{{ $item['content'] ?? '-' }}</span>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <!-- Price block removed from UI per request -->
                </div>
            </div>
        </section>

        {{-- Share options moved below the main post for better flow on mobile/desktops --}}

        {{-- ── Main Content ─────────────────────── --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-2">



            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- ── Left Col ─────────────────── --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Gallery --}}
                    @include('components.photo-gallery', ['images' => $galleryImages, 'alt' => $package->title])

                    {{-- Description --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
                        <h2 class="text-base font-bold text-gray-900 mb-4 flex items-center gap-2">
                            <span class="w-1 h-5 rounded-full" style="background:#C8102E;"></span>
                            Description
                        </h2>
                        <div class="text-gray-600 text-sm leading-relaxed whitespace-pre-line">
                            {{ $package->description }}
                        </div>
                    </div>

                    {{-- Accordions --}}
                    <div class="space-y-3">

                        {{-- Itinerary --}}
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                            <button @click="activeSection = (activeSection === 'itinerary' ? '' : 'itinerary')" class="w-full flex items-center justify-between p-5 text-left focus:outline-none group">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-red-50 text-red-600 rounded-lg flex items-center justify-center mr-4 group-hover:bg-red-500 group-hover:text-white transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 uppercase tracking-wide">Itinerary</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="activeSection === 'itinerary' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="activeSection === 'itinerary'" x-collapse class="p-5 pt-0 ml-14">
                                <div class="space-y-6 pt-4 border-t border-gray-50">
                                    @if(!empty($package->itinerary) && is_array($package->itinerary))
                                        @foreach($package->itinerary as $index => $day)
                                        <div class="relative pl-6 border-l border-gray-100 last:border-0 pb-6 last:pb-0">
                                            <div class="absolute -left-1.5 top-1 w-3 h-3 rounded-full border-2 border-white" style="background:#C8102E;"></div>
                                            <div class="mb-2">
                                                <span class="text-[10px] font-bold uppercase tracking-widest" style="color:#C8102E;">Day {{ $day['day'] ?? ($index + 1) }}</span>
                                                <h3 class="text-base font-bold text-gray-900">{{ $day['title'] ?? 'Overview' }}</h3>
                                            </div>
                                            <ul class="space-y-1.5 mt-2">
                                                @foreach($day['activities'] ?? [] as $activity)
                                                <li class="flex items-start text-xs text-gray-600">
                                                    <svg class="w-3.5 h-3.5 mr-2 mt-0.5 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                    {{ $activity }}
                                                </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                        @endforeach
                                    @else
                                        <p class="text-xs text-gray-400 italic">Itinerary details coming soon.</p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Other accordion sections --}}
                        @php
                            $sections = [
                                ['id' => 'hotel',        'title' => 'Hotel Details',           'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'color' => 'blue',   'content' => $package->hotel_details ?: 'Details shared upon booking.'],
                                ['id' => 'included',     'title' => 'Included',                'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',                                                                                              'color' => 'green',  'is_list' => true, 'list_style' => 'included', 'content' => $package->inclusions ?: []],
                                ['id' => 'excluded',     'title' => 'Excluded',                'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',                                                                       'color' => 'orange', 'is_list' => true, 'list_style' => 'excluded', 'content' => $package->exclusions ?: []],
                                ['id' => 'requirements', 'title' => 'Requirements',             'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'color' => 'amber',  'content' => $package->requirements],
                                ['id' => 'additional',   'title' => 'Additional Information',  'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',                                                                                  'color' => 'purple', 'content' => $package->additional_info],
                                ['id' => 'policy',       'title' => 'Policy & Conditions',     'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',                     'color' => 'indigo', 'content' => $package->policy],
                                ['id' => 'tips',         'title' => 'Travel Tips',             'icon' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',        'color' => 'yellow', 'content' => $package->travel_tips],
                                ['id' => 'pickup',       'title' => 'Pickup Note',             'icon' => 'M12 21l9-9-9-9-9 9 9 9z',                                                                                                                     'color' => 'cyan',   'content' => $package->pickup_note],
                            ];
                        @endphp

                        @foreach($sections as $sec)
                        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden {{ isset($sec['list_style']) && $sec['list_style'] === 'included' ? 'ring-1 ring-green-200/60' : '' }} {{ isset($sec['list_style']) && $sec['list_style'] === 'excluded' ? 'ring-1 ring-orange-200/60' : '' }}">
                            <button @click="activeSection = (activeSection === '{{ $sec['id'] }}' ? '' : '{{ $sec['id'] }}')"
                                    class="w-full flex items-center justify-between px-5 py-4 text-left focus:outline-none group {{ $sec['id'] === 'included' ? 'hover:bg-green-50/50' : '' }} {{ $sec['id'] === 'excluded' ? 'hover:bg-orange-50/50' : '' }}">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-{{ $sec['color'] }}-50 text-{{ $sec['color'] }}-600 rounded-lg flex items-center justify-center mr-4 group-hover:bg-{{ $sec['color'] }}-500 group-hover:text-white transition-all duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sec['icon'] }}"/></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 uppercase tracking-wide">{{ $sec['title'] }}</span>
                                </div>
                                <svg class="w-4 h-4 text-gray-400 transition-transform duration-300" :class="activeSection === '{{ $sec['id'] }}' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>
                            <div x-show="activeSection === '{{ $sec['id'] }}'" x-collapse class="px-5 pb-5 pt-0 ml-14">
                                <div class="pt-4 border-t border-gray-100 {{ in_array($sec['id'], ['included','excluded']) ? 'px-3 py-2 -mx-1 rounded-b-xl' : '' }} {{ $sec['id'] === 'included' ? 'bg-green-50/40' : '' }} {{ $sec['id'] === 'excluded' ? 'bg-orange-50/40' : '' }}">
                                    @if(isset($sec['is_list']) && is_array($sec['content']))
                                        @if(isset($sec['list_style']) && $sec['list_style'] === 'included')
                                        <ul class="list-none space-y-2">
                                            @forelse($sec['content'] as $item)
                                            <li class="flex items-start gap-3 text-sm text-gray-800 bg-white/80 rounded-lg px-4 py-2.5 shadow-sm border border-green-100/80">
                                                <span class="flex-shrink-0 w-5 h-5 rounded-full bg-green-500 flex items-center justify-center mt-0.5"><svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg></span>
                                                {{ $item }}
                                            </li>
                                            @empty
                                            <li class="italic text-gray-400 text-sm py-2">Not specified.</li>
                                            @endforelse
                                        </ul>
                                        @elseif(isset($sec['list_style']) && $sec['list_style'] === 'excluded')
                                        <ul class="list-none space-y-2">
                                            @forelse($sec['content'] as $item)
                                            <li class="flex items-start gap-3 text-sm text-gray-800 bg-white/80 rounded-lg px-4 py-2.5 shadow-sm border border-orange-100/80">
                                                <span class="flex-shrink-0 w-5 h-5 rounded-full bg-orange-400 flex items-center justify-center mt-0.5"><svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg></span>
                                                {{ $item }}
                                            </li>
                                            @empty
                                            <li class="italic text-gray-400 text-sm py-2">Not specified.</li>
                                            @endforelse
                                        </ul>
                                        @else
                                        <ul class="list-none space-y-0.5">
                                            @forelse($sec['content'] as $item)
                                            <li class="text-sm text-gray-700">{{ $item }}</li>
                                            @empty
                                            <li class="italic text-gray-400">Not specified.</li>
                                            @endforelse
                                        </ul>
                                        @endif
                                    @else
                                        @php $plainContent = $sec['content']; $isEmptyPlain = $plainContent === null || $plainContent === ''; @endphp
                                        <div class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                                            {{ $isEmptyPlain ? 'No information provided.' : $plainContent }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Custom Plan CTA --}}
                    <div class="rounded-2xl p-7 border flex flex-col sm:flex-row items-center justify-between gap-5" style="background:#F9F6EF;border-color:#E4DCC9;">
                        <div>
                            <p class="section-eyebrow mb-1">Tailor-made travel</p>
                            <h2 class="font-bold text-gray-900 text-lg">Need a Personalised Plan?</h2>
                            <p class="text-sm text-gray-500 mt-1">Our experts craft a custom itinerary to match your exact needs and budget.</p>
                        </div>
                        <button @click="openInquiryModal = true"
                                class="btn-primary flex-shrink-0">
                            Get Custom Plan
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- ── Right Col: Booking Sidebar ── --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-20 space-y-4">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6" x-data="{
                            adults: 1, children: 0, infants: 0, bookingDate: '',
                            price: {{ $package->price }},
                            get quantity() { return parseInt(this.adults) + parseInt(this.children) + parseInt(this.infants) },
                            get total() { return this.quantity * this.price }
                        }">
                            <div class="text-center mb-6 pb-5 border-b border-gray-100">
                                <h3 class="font-bold text-gray-900 mb-1">Book Your Holiday</h3>
                                <p class="text-xs text-gray-400 mb-3">Secure your spot today</p>
                                <p class="text-3xl font-extrabold" style="color:#C8102E;">৳{{ number_format($package->price) }}</p>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400">per person</p>
                            </div>

                            <form action="{{ route('bookings.store') }}" method="POST" class="space-y-4">
                                @csrf
                                <input type="hidden" name="payable_type" value="package">
                                <input type="hidden" name="payable_id" value="{{ $package->id }}">
                                <input type="hidden" name="quantity" :value="quantity">



                                <div>
                                    <label class="fb-field-label mb-1.5">Travel Date</label>
                                    <input type="date" name="booking_date" x-model="bookingDate" required min="{{ date('Y-m-d') }}"
                                           class="fb-input {{ $errors->has('booking_date') ? '!border-red-500' : '' }}">
                                </div>

                                <div>
                                    <label class="fb-field-label mb-1.5">Number of Persons</label>
                                    <div class="grid grid-cols-3 gap-2">
                                        <div>
                                            <span class="text-[9px] font-bold text-gray-400 uppercase block mb-1">Adults</span>
                                            <input type="number" name="details[adults]" x-model="adults" min="1"
                                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-2 text-xs text-center focus:bg-white focus:border-red-400 outline-none">
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-gray-400 uppercase block mb-1">Child</span>
                                            <input type="number" name="details[children]" x-model="children" min="0"
                                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-2 text-xs text-center focus:bg-white focus:border-red-400 outline-none">
                                        </div>
                                        <div>
                                            <span class="text-[9px] font-bold text-gray-400 uppercase block mb-1">Infant</span>
                                            <input type="number" name="details[infants]" x-model="infants" min="0"
                                                   class="w-full bg-gray-50 border border-gray-200 rounded-lg py-2 px-2 text-xs text-center focus:bg-white focus:border-red-400 outline-none">
                                        </div>
                                    </div>
                                </div>

                                @guest
                                <div class="space-y-2.5 pt-1">
                                    <p class="fb-field-label">Guest Info</p>
                                    <input type="text" name="guest_name" required value="{{ old('guest_name') }}"
                                           class="fb-input {{ $errors->has('guest_name') ? '!border-red-500' : '' }}" placeholder="Full Name">
                                    <div class="grid grid-cols-2 gap-2">
                                        <input type="email" name="guest_email" required value="{{ old('guest_email') }}"
                                               class="fb-input {{ $errors->has('guest_email') ? '!border-red-500' : '' }}" placeholder="Email">
                                        <input type="text" name="guest_phone" required value="{{ old('guest_phone') }}"
                                               class="fb-input {{ $errors->has('guest_phone') ? '!border-red-500' : '' }}" placeholder="Phone">
                                    </div>
                                </div>
                                @endguest

                                <div>
                                    <label class="fb-field-label mb-1.5">Special Requirements</label>
                                    <textarea name="notes" rows="2" class="fb-input resize-none" placeholder="Any requests?"></textarea>
                                </div>

                                {{-- Total --}}
                                <div class="rounded-xl p-4 flex justify-between items-center" style="background:#FFF1F2;border:1px solid #FECDD3;">
                                    <span class="text-sm font-bold" style="color:#9F1239;">Total</span>
                                    <span class="text-xl font-extrabold" style="color:#C8102E;" x-text="'৳' + total.toLocaleString()"></span>
                                </div>

                                <button type="submit" class="btn-primary w-full py-4 text-base">
                                    Confirm Booking
                                </button>
                                <p class="text-[9px] font-bold text-gray-400 text-center uppercase tracking-widest">Instant confirmation via email</p>
                            </form>

                            <div class="mt-5 pt-5 border-t border-gray-100 flex justify-around">
                                @foreach([['bg-green-50','text-green-600','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','Verified'],['bg-blue-50','text-blue-600','M13 10V3L4 14h7v7l9-11h-7z','Fast'],['bg-yellow-50','text-yellow-600','M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z','Best Price']] as [$bg,$tc,$path,$label])
                                <div class="text-center">
                                    <div class="w-9 h-9 {{ $bg }} rounded-full flex items-center justify-center mx-auto mb-1">
                                        <svg class="w-4 h-4 {{ $tc }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $path }}"/></svg>
                                    </div>
                                    <span class="text-[9px] font-bold text-gray-400 uppercase tracking-tighter">{{ $label }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- WhatsApp strip --}}
                        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 flex items-center gap-4">
                            <div class="flex-1">
                                <p class="text-sm font-bold text-gray-900">Need help booking?</p>
                                <p class="text-xs text-gray-400">Available 24/7 on WhatsApp</p>
                            </div>
                            <a href="https://wa.me/8801335111370" target="_blank"
                               class="flex items-center gap-1.5 bg-green-500 hover:bg-green-600 text-white font-bold px-4 py-2.5 rounded-xl transition text-xs flex-shrink-0">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                                Chat
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Related Packages --}}
            @if(isset($relatedPackages) && count($relatedPackages) > 0)
            <div class="mt-14">
                {{-- Moved share buttons here so they appear under the main post --}}
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 mb-6">
                    <x-share-buttons :title="$package->title" />
                </div>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-extrabold text-gray-900">Recommended Tours</h2>
                    <a href="{{ route('packages.index') }}" class="text-sm font-semibold text-red-600 hover:text-red-700 flex items-center gap-1">
                        View All <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach($relatedPackages as $rel)
                    <a href="{{ route('packages.show', $rel->slug) }}" class="travel-card flex flex-col group">
                        <div class="overflow-hidden" style="height:180px;">
                            <img src="{{ $rel->thumbnail ? (\Illuminate\Support\Str::startsWith($rel->thumbnail, 'http') ? $rel->thumbnail : Storage::url($rel->thumbnail)) : $defaultImage }}"
                                 alt="{{ $rel->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                        <div class="p-4 flex flex-col flex-1">
                            <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#C8102E;">{{ $rel->location }}</p>
                            <h3 class="font-bold text-gray-900 text-sm mb-3 line-clamp-2 flex-1 group-hover:text-red-600 transition">{{ $rel->title }}</h3>
                            <div class="flex items-center justify-between pt-3 border-t border-gray-50">
                                <p class="font-extrabold text-sm" style="color:#C8102E;">৳{{ number_format($rel->price) }}</p>
                                <span class="text-xs font-bold text-gray-500 group-hover:text-red-600 transition flex items-center gap-0.5">
                                    View <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </span>
                            </div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- ── Inquiry Modal ────────────────────── --}}
        <div x-show="openInquiryModal" style="display:none;" class="fixed inset-0 z-[100] overflow-y-auto" role="dialog" aria-modal="true">
            <div class="flex items-center justify-center min-h-screen p-4">
                <div x-show="openInquiryModal" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                     class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm" @click="openInquiryModal = false"></div>

                <div x-show="openInquiryModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     class="relative bg-white rounded-2xl shadow-xl max-w-2xl w-full overflow-hidden p-6 sm:p-10">
                    <button @click="openInquiryModal = false" class="absolute top-4 right-4 p-2 text-gray-400 hover:text-red-500 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="mb-7">
                        <p class="section-eyebrow mb-1">Custom Request</p>
                        <h3 class="font-extrabold text-xl text-gray-900">Build Your Itinerary</h3>
                    </div>

                    <form action="{{ route('packages.customize', $package->id) }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="space-y-4">
                            <template x-for="(dest, dIndex) in inquiryForm.destinations" :key="dIndex">
                                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100 space-y-4 relative group">
                                    <button type="button" @click="removeDestination(dIndex)" x-show="inquiryForm.destinations.length > 1"
                                            class="absolute -top-2 -right-2 bg-red-500 text-white p-1 rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                    <div class="grid grid-cols-2 gap-3">
                                        <div>
                                            <label class="fb-field-label mb-1">Country</label>
                                            <input type="text" :name="'destinations[' + dIndex + '][country]'" x-model="dest.country" required class="fb-input" placeholder="e.g. Thailand">
                                        </div>
                                        <div>
                                            <label class="fb-field-label mb-1">Nights</label>
                                            <input type="number" :name="'destinations[' + dIndex + '][nights]'" x-model="dest.nights" required class="fb-input" placeholder="Nights">
                                        </div>
                                    </div>
                                    <div class="pl-4 border-l-2 border-red-200 space-y-3">
                                        <template x-for="(city, cIndex) in dest.cities" :key="cIndex">
                                            <div class="flex gap-3 items-end">
                                                <div class="flex-1">
                                                    <label class="fb-field-label mb-1">City</label>
                                                    <input type="text" :name="'destinations[' + dIndex + '][cities][' + cIndex + '][name]'" x-model="city.name" class="fb-input" placeholder="e.g. Bangkok">
                                                </div>
                                                <div class="w-24">
                                                    <label class="fb-field-label mb-1">Nights</label>
                                                    <input type="number" :name="'destinations[' + dIndex + '][cities][' + cIndex + '][nights]'" x-model="city.nights" class="fb-input">
                                                </div>
                                                <button type="button" @click="removeCity(dIndex, cIndex)" x-show="dest.cities.length > 1" class="mb-1 text-gray-400 hover:text-red-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button type="button" @click="addCity(dIndex)" class="text-[10px] font-bold text-red-500 hover:text-red-600 flex items-center gap-1 transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                            Add city
                                        </button>
                                    </div>
                                </div>
                            </template>
                            <button type="button" @click="addDestination()" class="w-full py-2.5 border-2 border-dashed border-gray-200 rounded-xl text-xs font-bold text-gray-400 hover:border-red-300 hover:text-red-500 transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Add Destination
                            </button>
                        </div>

                        <div class="grid grid-cols-3 gap-3">
                            @foreach([['adults','Adults','1'],['children','Child','0'],['infants','Infant','0']] as [$n,$l,$m])
                            <div>
                                <label class="fb-field-label mb-1">{{ $l }}</label>
                                <input type="number" name="{{ $n }}" x-model="inquiryForm.{{ $n }}" min="{{ $m }}" class="fb-input text-center">
                            </div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="fb-field-label mb-1">Hotel Type</label>
                                <select name="hotel_type" x-model="inquiryForm.hotel_type" class="fb-input">
                                    <option value="">Select Category</option>
                                    <option value="3-star">3 Star</option>
                                    <option value="4-star">4 Star</option>
                                    <option value="5-star">5 Star</option>
                                    <option value="budget">Budget</option>
                                </select>
                            </div>
                            <div>
                                <label class="fb-field-label mb-1">Travel Date</label>
                                <input type="date" name="travel_date" x-model="inquiryForm.travel_date" class="fb-input">
                            </div>
                        </div>

                        <div>
                            <label class="fb-field-label mb-1">Notes / Requirements</label>
                            <textarea name="message" x-model="inquiryForm.message" rows="3" class="fb-input resize-none" placeholder="Special requests, preferences…"></textarea>
                        </div>

                        <div class="grid grid-cols-3 gap-3 pt-4 border-t border-gray-100">
                            <div>
                                <label class="fb-field-label mb-1">Name</label>
                                <input type="text" name="name" x-model="inquiryForm.name" required class="fb-input" placeholder="John Doe">
                            </div>
                            <div>
                                <label class="fb-field-label mb-1">Mobile</label>
                                <input type="text" name="phone" x-model="inquiryForm.phone" required class="fb-input" placeholder="+8801...">
                            </div>
                            <div>
                                <label class="fb-field-label mb-1">Email</label>
                                <input type="email" name="email" x-model="inquiryForm.email" required class="fb-input" placeholder="john@example.com">
                            </div>
                        </div>

                        <div class="text-center pt-2">
                            <button type="submit" class="btn-primary px-12 py-3.5">
                                Submit Request
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
