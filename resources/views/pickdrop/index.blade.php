<x-app-layout>

{{-- ── PICK & DROP HERO ─────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pt-12 pb-16" style="background:#FAF6EE;">
    <div class="max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-10 lg:gap-16 items-center">

        {{-- Left copy --}}
        <div>
            <div class="flex items-center gap-3 mb-4">
                <span class="ota-tag-red text-[10px] tracking-widest font-bold">NEW</span>
                <span class="fb-eyebrow">Service / 04 · Pick &amp; Drop</span>
            </div>
            <h1 class="fb-serif mt-2 leading-[0.95]" style="font-size:clamp(48px,6.5vw,82px);color:#18130E;">
                A driver,<br>waiting.
            </h1>
            <p class="mt-5 text-base leading-relaxed max-w-md" style="color:#3A332B;">
                Airport transfers, hourly chauffeurs, intercity road trips, and cross-border runs — all with real-time tracking, vetted drivers, and no surge pricing.
            </p>
            <div class="flex flex-wrap gap-5 mt-8">
                @foreach([['Fixed price','No surge ever'],['Live tracking','Know where your car is'],['Verified drivers','Background checked']] as [$label,$sub])
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0" style="background:#1F6E3D;">✓</span>
                    <div>
                        <p class="text-sm font-semibold" style="color:#18130E;">{{ $label }}</p>
                        <p class="text-xs" style="color:#7A7166;">{{ $sub }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Right: visual --}}
        <div class="relative hidden lg:block" style="height:400px;">
            {{-- Main card --}}
            <div class="absolute top-0 right-0 w-80 h-72 rounded-2xl overflow-hidden shadow-2xl" style="background:linear-gradient(135deg,#1A1F26 0%,#2A3340 55%,#5D6A78 100%);">
                <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                <div class="absolute inset-0" style="background:radial-gradient(circle at 25% 35%,rgba(255,255,255,0.06),transparent 45%);"></div>
                <div class="absolute bottom-5 left-5 right-5">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-white font-bold text-lg">Toyota Camry</p>
                            <p class="text-white/60 text-xs mt-0.5">Business Sedan · AC</p>
                        </div>
                        <div class="text-right">
                            <p class="text-white font-bold">৳1,800</p>
                            <p class="text-white/60 text-[10px]">Airport → Hotel</p>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Driver assigned float --}}
            <div class="absolute top-16 left-0 bg-white rounded-xl px-4 py-3 shadow-xl border border-[#E4DCC9] flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm text-white shrink-0" style="background:#C8102E;">R</div>
                <div>
                    <p class="text-xs font-bold" style="color:#18130E;">Driver assigned</p>
                    <p class="text-xs" style="color:#7A7166;">Rafiqul · ⭐ 4.97 · 3 min away</p>
                </div>
            </div>
            {{-- ETA float --}}
            <div class="absolute bottom-4 left-8 bg-white rounded-xl px-4 py-3 shadow-xl border border-[#E4DCC9]">
                <p class="text-[10px] font-bold tracking-widest uppercase" style="color:#7A7166;">Arriving in</p>
                <p class="text-xl font-bold mt-0.5" style="color:#C8102E;">3 min</p>
            </div>
        </div>
    </div>
</section>

{{-- ── BOOKING WIDGET ────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 -mt-4 relative z-10 pb-10 max-w-screen-xl mx-auto"
    x-data="{ activeTab: 'airport' }">
    <div class="bg-white rounded-2xl shadow-xl border border-[#E4DCC9] overflow-hidden">

        {{-- Tab bar --}}
        <div class="flex overflow-x-auto border-b border-[#EFE9DA]" style="scrollbar-width:none;">
            @foreach([
                ['airport','✈️','Airport transfer'],
                ['hourly','🕐','Hourly'],
                ['intercity','🛣️','Intercity'],
                ['crossborder','🗺️','Cross-border'],
            ] as [$tab,$icon,$label])
            <button
                @click="activeTab = '{{ $tab }}'"
                :class="activeTab === '{{ $tab }}'
                    ? 'text-[#C8102E] border-[#C8102E] border-b-2 -mb-px'
                    : 'text-[#3A332B] border-transparent border-b-2 -mb-px hover:text-[#18130E]'"
                class="flex items-center gap-2 px-5 py-4 text-sm font-semibold whitespace-nowrap shrink-0 transition-colors duration-150">
                <span>{{ $icon }}</span>{{ $label }}
            </button>
            @endforeach
        </div>

        {{-- Form body --}}
        <div class="p-5 lg:p-6">
            <form action="{{ route('contact') }}" method="GET">
                <input type="hidden" name="service" value="pick-drop">
                <input type="hidden" name="tab" :value="activeTab" x-model="activeTab">

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
                    {{-- Pickup --}}
                    <div class="fb-field">
                        <label class="fb-field-label">Pickup location</label>
                        <input type="text" name="pickup" placeholder="Address or airport…" class="fb-input text-sm" value="{{ old('pickup') }}">
                    </div>
                    {{-- Dropoff --}}
                    <div class="fb-field">
                        <label class="fb-field-label">Drop-off location</label>
                        <input type="text" name="dropoff" placeholder="Hotel, terminal, city…" class="fb-input text-sm" value="{{ old('dropoff') }}">
                    </div>
                    {{-- Date --}}
                    <div class="fb-field">
                        <label class="fb-field-label">Date</label>
                        <input type="date" name="date" class="fb-input text-sm" value="{{ old('date') }}">
                    </div>
                    {{-- Time --}}
                    <div class="fb-field">
                        <label class="fb-field-label">Time</label>
                        <input type="time" name="time" class="fb-input text-sm" value="{{ old('time') }}">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-5">
                    {{-- Passengers --}}
                    <div class="fb-field">
                        <label class="fb-field-label">Passengers</label>
                        <select name="passengers" class="fb-input text-sm bg-transparent">
                            @foreach(['1','2','3','4','5','6','7','8+'] as $n)
                                <option {{ old('passengers') === $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                    {{-- Luggage --}}
                    <div class="fb-field">
                        <label class="fb-field-label">Luggage pieces</label>
                        <select name="luggage" class="fb-input text-sm bg-transparent">
                            @foreach(['0','1','2','3','4','5+'] as $n)
                                <option {{ old('luggage') === $n ? 'selected' : '' }}>{{ $n }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Vehicle selector --}}
                <p class="fb-eyebrow mb-3">Select vehicle class</p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5" x-data="{ vehicle: 'sedan' }">
                    @foreach([
                        ['sedan','🚗','Sedan','Up to 4 pax · 2 bags','From ৳1,200'],
                        ['suv','🚙','SUV / MPV','Up to 7 pax · 4 bags','From ৳2,000'],
                        ['premium','🚘','Premium','Up to 4 pax · 2 bags','From ৳3,500'],
                    ] as [$val,$icon,$name,$cap,$price])
                    <label
                        :class="vehicle === '{{ $val }}'
                            ? 'border-[#C8102E] bg-[#FFFAFA]'
                            : 'border-[#E4DCC9] bg-white hover:border-[#3A332B]'"
                        class="flex items-center gap-3 p-4 rounded-xl border cursor-pointer transition-all duration-150">
                        <input type="radio" name="vehicle" value="{{ $val }}" class="hidden" x-model="vehicle">
                        <span class="text-2xl">{{ $icon }}</span>
                        <div class="flex-1">
                            <p class="font-semibold text-sm" style="color:#18130E;">{{ $name }}</p>
                            <p class="text-xs" style="color:#7A7166;">{{ $cap }}</p>
                            <p class="text-xs font-bold mt-0.5" style="color:#C8102E;">{{ $price }}</p>
                        </div>
                        <div
                            :class="vehicle === '{{ $val }}' ? 'border-[#C8102E] bg-[#C8102E]' : 'border-[#E4DCC9]'"
                            class="w-4 h-4 rounded-full border-2 flex items-center justify-center shrink-0 transition-all">
                            <div x-show="vehicle === '{{ $val }}'" class="w-1.5 h-1.5 rounded-full bg-white"></div>
                        </div>
                    </label>
                    @endforeach
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="ota-btn-primary px-8 py-3 text-base">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Confirm booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>

{{-- ── SERVICE TYPES ─────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-16 max-w-screen-xl mx-auto">
    <div class="mb-8">
        <span class="fb-eyebrow">What we offer</span>
        <h2 class="fb-serif mt-2" style="font-size:clamp(28px,3.5vw,44px);color:#18130E;">Four ways to ride.</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach([
            ['✈️','Airport Transfer','From ৳1,200','Domestic & international terminals — Dhaka, Chattogram, Sylhet, Cox\'s Bazar.'],
            ['🕐','Hourly Chauffeur','From ৳800/hr','Block your driver by the hour for meetings, shopping runs, or a day out in the city.'],
            ['🛣️','Intercity','From ৳3,500','Dhaka to Chittagong, Sylhet, Cox\'s Bazar, Sundarbans — in an AC car, not a bus.'],
            ['🗺️','Cross-border','From ৳7,000','Petrapole, Hili, Bhomra, Benapole — we handle both-side waiting & border paperwork support.'],
        ] as [$icon,$title,$price,$desc])
        <div class="ota-card p-5 flex flex-col">
            <div class="text-3xl mb-4">{{ $icon }}</div>
            <h3 class="font-bold text-base mb-1" style="color:#18130E;">{{ $title }}</h3>
            <p class="text-sm font-semibold mb-3" style="color:#C8102E;">{{ $price }}</p>
            <p class="text-sm leading-relaxed flex-1" style="color:#7A7166;">{{ $desc }}</p>
            <a href="{{ route('contact') }}?service=pick-drop&type={{ strtolower(str_replace(' ','_',$title)) }}"
                class="ota-btn-ghost mt-4 text-sm py-2">Book →</a>
        </div>
        @endforeach
    </div>
</section>

{{-- ── FLEET ─────────────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-16 max-w-screen-xl mx-auto" style="background:#FAF6EE;border-radius:24px;margin:0 1rem 4rem;">
    <div class="mb-8">
        <span class="fb-eyebrow">Our fleet</span>
        <h2 class="fb-serif mt-2" style="font-size:clamp(28px,3.5vw,44px);color:#18130E;">Clean cars, professional drivers.</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        @foreach([
            ['Toyota Camry','4 pax · 2 bags','৳1,800–3,200','Business Sedan','1A2026','2E3C4A','60748A'],
            ['Toyota Hiace','7 pax · 5 bags','৳2,800–4,500','Premium MPV','1E2A22','36473A','7C8E80'],
            ['Toyota Land Cruiser','5 pax · 3 bags','৳4,200–7,000','4WD SUV','2B2117','574535','A89178'],
            ['Mercedes E-Class','3 pax · 2 bags','৳5,500–9,000','Luxury Sedan','1C1820','2E3040','606880'],
        ] as [$name,$cap,$range,$type,$c1,$c2,$c3])
        <div class="ota-card overflow-hidden">
            {{-- Placeholder image --}}
            <div class="relative h-40" style="background:linear-gradient(135deg,#{{ $c1 }} 0%,#{{ $c2 }} 55%,#{{ $c3 }} 100%);">
                <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                <div class="absolute bottom-3 left-3">
                    <span class="ota-tag-soft text-[10px]">{{ $type }}</span>
                </div>
            </div>
            <div class="p-4">
                <h3 class="font-bold text-base" style="color:#18130E;">{{ $name }}</h3>
                <p class="text-xs mt-1" style="color:#7A7166;">{{ $cap }}</p>
                <div class="flex items-center justify-between mt-3">
                    <div>
                        <p class="text-xs" style="color:#7A7166;">from</p>
                        <p class="font-bold text-sm" style="color:#18130E;">{{ $range }}</p>
                    </div>
                    <a href="{{ route('contact') }}?service=pick-drop" class="ota-btn-primary text-xs px-4 py-2">Book</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

{{-- ── HOW IT WORKS: DARK CARD ──────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pb-20 max-w-screen-xl mx-auto">
    <div class="rounded-2xl overflow-hidden" style="background:#18130E;">
        <div class="px-8 lg:px-16 py-14">
            <div class="mb-10">
                <span class="fb-eyebrow" style="color:#E4DCC9;opacity:0.6;">Simple process</span>
                <h2 class="fb-serif mt-2 text-white" style="font-size:clamp(28px,3.5vw,44px);">Booked in 60 seconds.</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach([
                    ['01','Book in 60s','Fill the form above. We confirm by WhatsApp within minutes.'],
                    ['02','Driver assigned','Your verified driver and vehicle details are sent to you.'],
                    ['03','Live tracking','Share your tracking link. Family can watch your route in real time.'],
                    ['04','Pay &amp; rate','Pay by cash, card, or bKash. Rate your experience — we read every review.'],
                ] as [$step,$title,$desc])
                <div class="relative pl-14">
                    <span class="absolute left-0 top-0 font-bold text-5xl leading-none" style="color:#3A332B;font-family:'Instrument Serif',serif;">{{ $step }}</span>
                    <h3 class="font-bold text-white mb-2 mt-1">{{ $title }}</h3>
                    <p class="text-sm leading-relaxed" style="color:#7A7166;">{!! $desc !!}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── COVERAGE GRID ─────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pb-20 max-w-screen-xl mx-auto">
    <div class="mb-8">
        <span class="fb-eyebrow">Coverage</span>
        <h2 class="fb-serif mt-2" style="font-size:clamp(28px,3.5vw,44px);color:#18130E;">We go where you go.</h2>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-8 gap-3">
        @foreach([
            ['🏙️','Dhaka','Capital city'],
            ['⚓','Chittagong','Port city'],
            ['🍃','Sylhet','Tea country'],
            ['🌊','Cox\'s Bazar','Longest beach'],
            ['🌿','Sundarbans','Mangrove delta'],
            ['🏔️','Rangamati','Hill tracts'],
            ['🛂','Petrapole','India border'],
            ['🛂','Phulbari','India border'],
        ] as [$icon,$city,$tag])
        <div class="ota-card p-4 text-center flex flex-col items-center gap-2">
            <span class="text-2xl">{{ $icon }}</span>
            <p class="font-bold text-sm" style="color:#18130E;">{{ $city }}</p>
            <span class="ota-tag-soft text-[9px] py-0.5">{{ $tag }}</span>
        </div>
        @endforeach
    </div>
</section>

</x-app-layout>
