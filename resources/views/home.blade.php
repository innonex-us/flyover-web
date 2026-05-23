<x-app-layout>

{{-- ── HERO ──────────────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pt-10 pb-14" style="background:#FAF6EE;">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center max-w-screen-xl mx-auto">

        {{-- Left copy --}}
        <div>
            <span class="ota-eyebrow">🏆 #1 travel agency in Bangladesh · 2024 award</span>
            <h1 class="mt-5 font-bold leading-none" style="font-size:clamp(44px,6vw,76px);letter-spacing:-0.025em;color:#18130E;">
                The world is<br>
                <span class="relative inline-block">cheaper
                    <svg style="position:absolute;left:0;bottom:-8px;width:100%;" height="12" viewBox="0 0 220 12" fill="none">
                        <path d="M2 6 Q 60 1, 120 6 T 218 6" stroke="#C8102E" stroke-width="4" stroke-linecap="round" fill="none"/>
                    </svg>
                </span>
                with us.
            </h1>
            <p class="mt-6 text-base leading-relaxed max-w-md" style="color:#3A332B;">
                Up to 65% off hotels · 11% off international flights with bKash · EMI from 0%. Real deals, no fine print.
            </p>
            <div class="flex flex-wrap gap-3 mt-7">
                <a href="{{ route('packages.index') }}" class="ota-btn-primary px-6 py-3 text-base">🔍 Find your trip</a>
                <a href="{{ route('packages.index') }}" class="ota-btn-ghost px-6 py-3 text-base">View all deals →</a>
            </div>
            <div class="flex items-center gap-4 mt-7">
                <div class="flex -space-x-2.5">
                    @foreach(['2A2520','6B4F40','36473A','574535'] as $c)
                        <div class="w-9 h-9 rounded-full border-2 border-white" style="background:#{{ $c }};"></div>
                    @endforeach
                </div>
                <div>
                    <div class="flex items-center gap-1.5">
                        <span class="text-amber-400 text-sm">★★★★★</span>
                        <span class="text-sm font-bold">4.8 / 5</span>
                    </div>
                    <div class="text-xs mt-0.5" style="color:#7A7166;">Trusted by 1.2M+ travellers</div>
                </div>
            </div>
        </div>

        {{-- Right photo collage --}}
        <div class="relative h-96 lg:h-[460px] hidden lg:block">
            <div class="absolute top-0 right-0 w-72 h-60 rounded-2xl overflow-hidden shadow-2xl" style="background:linear-gradient(135deg,#1A1F26 0%,#384552 55%,#7B8895 100%);">
                <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                <div class="absolute bottom-4 left-4 text-white">
                    <p class="font-serif italic text-xl leading-none">Maldives</p>
                    <p class="font-mono text-[10px] tracking-widest mt-1.5 opacity-75">FROM ৳1,24,000</p>
                </div>
            </div>
            <div class="absolute top-12 left-0 w-56 h-64 rounded-2xl overflow-hidden shadow-2xl" style="background:linear-gradient(135deg,#1E2A22 0%,#36473A 55%,#7C8E80 100%);transform:rotate(-3deg);">
                <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                <div class="absolute bottom-4 left-4 text-white">
                    <p class="font-serif italic text-xl leading-none">Bhutan</p>
                    <p class="font-mono text-[10px] tracking-widest mt-1.5 opacity-75">FROM ৳98,000</p>
                </div>
            </div>
            <div class="absolute bottom-0 right-16 w-52 h-48 rounded-2xl overflow-hidden shadow-2xl" style="background:linear-gradient(135deg,#2B2117 0%,#574535 55%,#A89178 100%);transform:rotate(4deg);">
                <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                <div class="absolute bottom-4 left-4 text-white">
                    <p class="font-serif italic text-xl leading-none">Dubai</p>
                    <p class="font-mono text-[10px] tracking-widest mt-1.5 opacity-75">FROM ৳74,000</p>
                </div>
            </div>
            <div class="absolute bottom-8 left-4 bg-white px-4 py-3 rounded-xl shadow-xl flex items-center gap-3">
                <span class="text-2xl">🎁</span>
                <div>
                    <p class="text-xs font-semibold" style="color:#7A7166;">FLASH SALE · ENDS IN</p>
                    <p class="text-base font-bold mt-0.5" style="color:#C8102E;" x-data="{}" x-text="'14 : 22 : 09'">14 : 22 : 09</p>
                </div>
            </div>
            <div class="absolute top-20 -right-2 bg-white px-3 py-2.5 rounded-xl shadow-xl flex items-center gap-2.5 max-w-[200px]">
                <span class="w-8 h-8 rounded-full flex items-center justify-center text-base shrink-0" style="background:#E8F5EC;">✓</span>
                <div>
                    <p class="text-xs font-bold">Just booked</p>
                    <p class="text-xs mt-0.5" style="color:#7A7166;">Anika H. · Nepal 7N</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── SEARCH WIDGET ─────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pb-8 max-w-screen-xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-[#E4DCC9] overflow-hidden">
        <div class="flex border-b border-[#EFE9DA] overflow-x-auto px-2">
            @foreach([['Flights','✈',false,false],['Hotels','🏨',false,true],['Tour Packages','🧳',true,false],['Visa','📘',false,false],['Pick &amp; Drop','🚗',false,true]] as $i => [$t,$e,$active,$isNew])
                <button class="flex items-center gap-2 px-5 py-4 text-sm font-semibold whitespace-nowrap shrink-0 border-b-2 transition-colors duration-150 -mb-px
                    {{ $active ? 'text-[#C8102E] border-[#C8102E]' : 'text-[#3A332B] border-transparent hover:text-[#18130E]' }}">
                    <span class="text-base">{{ $e }}</span>
                    {!! $t !!}
                    @if($isNew)<span class="ml-1 text-[9px] font-bold bg-[#C8102E] text-white px-1.5 py-0.5 rounded">NEW</span>@endif
                </button>
            @endforeach
        </div>
        <form action="{{ route('packages.index') }}" method="GET" class="p-4">
            <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach([['DESTINATION','Pokhara, Nepal','📍','destination'],['DURATION','7 nights','📅','duration'],['TRAVELLERS','2 adults','👤','travellers'],['BUDGET','৳ 60k – 1.2L','💰','budget']] as $i => [$l,$v,$e,$name])
                    <div class="p-3 border rounded-xl {{ $i === 0 ? 'border-[#C8102E] bg-[#FFFAFA]' : 'border-[#E4DCC9] bg-white' }}">
                        <p class="fb-field-label">{{ $l }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span>{{ $e }}</span>
                            <input type="text" name="{{ $name }}" placeholder="{{ $v }}" class="text-sm font-semibold bg-transparent border-none outline-none w-full text-[#18130E] placeholder-[#7A7166]">
                        </div>
                    </div>
                @endforeach
                <button type="submit" class="ota-btn-primary py-3 text-sm font-semibold rounded-xl">Find trip →</button>
            </div>
        </form>
    </div>
</section>

{{-- ── PROMO STRIP ───────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pb-10 max-w-screen-xl mx-auto">
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
        @foreach([
            ['🎁','Up to 11% off','on international flights · bKash payment','#FFE9EC','#C8102E'],
            ['💳','EMI from 0%','3 / 6 / 12 months · all major banks','#E8F5EC','#1F6E3D'],
            ['📱','Get the app','৳ 500 first-booking credit','#FFF4D6','#8A5A00'],
            ['🏝','Cox\'s Bazar deals','Stays from ৳ 2,200 / night','#E5F0FF','#1E4DAA'],
        ] as [$emo,$t,$d,$bg,$fg])
            <div class="flex items-center gap-3 p-4 rounded-xl border border-black/5" style="background:{{ $bg }};">
                <span class="text-3xl">{{ $emo }}</span>
                <div>
                    <p class="text-sm font-bold" style="color:{{ $fg }};">{{ $t }}</p>
                    <p class="text-xs mt-0.5" style="color:#3A332B;">{{ $d }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ── FEATURED DEALS ────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-10 max-w-screen-xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div>
            <span class="ota-eyebrow">🔥 Hot deals this week</span>
            <h2 class="ota-h2 mt-3">Limited time, real savings</h2>
        </div>
        <span class="text-sm font-bold px-3 py-2 rounded-full" style="color:#C8102E;background:#FFE9EC;">⏱ Refresh in 2h 14m</span>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-3 lg:grid-rows-2 gap-4" style="min-height:500px;">
        {{-- Big featured deal --}}
        <div class="col-span-2 lg:col-span-1 lg:row-span-2 ota-card overflow-hidden relative min-h-[400px]">
            <div class="absolute inset-0" style="background:linear-gradient(135deg,#1A1F26 0%,#384552 55%,#7B8895 100%);"></div>
            <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
            <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(24,19,14,0.1) 30%,rgba(24,19,14,0.85) 100%);"></div>
            <div class="absolute top-5 left-5 flex gap-2">
                <span class="ota-tag-red text-xs">🏆 EDITOR'S PICK</span>
                <span class="px-2.5 py-1 rounded-md text-xs font-bold bg-white text-[#18130E]">SAVE 22%</span>
            </div>
            <div class="absolute bottom-0 left-0 right-0 p-7 text-white">
                <p class="text-sm font-semibold opacity-85">🧳 TOUR PACKAGE · 4N / 5D</p>
                <h3 class="text-2xl font-bold mt-2 leading-tight">Maldives honeymoon — overwater villa</h3>
                <div class="flex gap-4 mt-3 text-xs opacity-90">
                    <span>★ 4.93 (211)</span><span>📍 Noonu Atoll</span>
                </div>
                <div class="flex justify-between items-end mt-4">
                    <div>
                        <p class="text-xs opacity-70 line-through">৳ 1,58,000</p>
                        <p class="text-3xl font-bold">৳ 1,24,000</p>
                    </div>
                    <a href="{{ route('packages.index') }}" class="px-4 py-2 bg-white font-bold rounded-lg text-sm" style="color:#C8102E;">Book →</a>
                </div>
            </div>
        </div>

        @foreach([
            ['Bhutan 6N','from ৳98,000','TOP RATED','1E2A22','6B4F40'],
            ['Dubai 5N','from ৳74,000','FAMILY','2B2117','574535'],
            ['Bali 8N','from ৳1,02,000','ADVENTURE','1A1F26','384552'],
            ['Kashmir 6N','from ৳68,000','NEW','3D2E26','6B4F40'],
        ] as [$t,$p,$tag,$c1,$c2])
            <div class="ota-card overflow-hidden relative min-h-[180px]">
                <div class="absolute inset-0" style="background:linear-gradient(135deg,#{{ $c1 }} 0%,#{{ $c2 }} 100%);"></div>
                <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(0,0,0,0) 50%,rgba(0,0,0,0.8) 100%);"></div>
                <span class="absolute top-3 left-3 px-2.5 py-1 bg-white text-xs font-bold rounded" style="color:#18130E;">{{ $tag }}</span>
                <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                    <p class="text-lg font-bold">{{ $t }}</p>
                    <p class="text-xs mt-1 opacity-90">{{ $p }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ── CATEGORY STRIP ────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pb-10 max-w-screen-xl mx-auto">
    <div class="flex gap-3 overflow-x-auto pb-2" style="scrollbar-width:none;">
        @foreach([
            ['Beach & Islands','🏝',248,'#E5F0FF'],
            ['Mountains','⛰',142,'#E8F5EC'],
            ['Cultural tours','🏛',86,'#FFE9EC'],
            ['Adventure','🥾',64,'#FFF4D6'],
            ['Honeymoon','💍',38,'#F0E5FF'],
            ['Family-friendly','👨‍👩‍👧',120,'#FFE9D6'],
            ['Group tours','👥',56,'#E5F4FF'],
            ['Wildlife','🐅',28,'#FFE0E5'],
        ] as [$t,$e,$c,$bg])
            <a href="{{ route('packages.index') }}?style={{ urlencode($t) }}" class="ota-card shrink-0 p-5 cursor-pointer" style="min-width:180px;">
                <div class="w-12 h-12 rounded-full flex items-center justify-center text-2xl" style="background:{{ $bg }};">{{ $e }}</div>
                <p class="text-sm font-bold mt-3.5">{{ $t }}</p>
                <p class="text-xs mt-0.5" style="color:#7A7166;">{{ $c }} trips</p>
            </a>
        @endforeach
    </div>
</section>

{{-- ── HOTEL DEALS ───────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-10 max-w-screen-xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div>
            <span class="ota-eyebrow">🏨 Hotel deals · NEW SERVICE</span>
            <h2 class="ota-h2 mt-3">Negotiated rates, paid at hotel</h2>
        </div>
        <a href="{{ route('hotels.index') }}" class="text-sm font-bold" style="color:#C8102E;">All 8,400 properties →</a>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['Pavilions Himalayas','Pokhara, Nepal','5★ Resort','18,500','23,400','4.92',421,'EDITOR PICK','2A2520'],
            ['Amankora Punakha','Punakha, Bhutan','5★ Lodge','84,000',null,'4.97',188,'','1E2A22'],
            ['Soneva Jani','Noonu, Maldives','Overwater villa','1,42,000','1,68,000','4.99',96,'15% OFF','1A1F26'],
            ['Atlantis the Palm','Dubai, UAE','5★ Resort','26,800',null,'4.84',1422,'FAMILY','2B2117'],
        ] as [$n,$loc,$type,$p,$was,$r,$rev,$tag,$c])
            <div class="ota-card overflow-hidden">
                <div class="relative">
                    <div class="h-44" style="background:linear-gradient(135deg,#{{ $c }} 0%,#574535 55%,#A89178 100%);"></div>
                    @if($tag)
                        <span class="absolute top-2.5 left-2.5 text-[10px] font-bold text-white px-2 py-0.5 rounded"
                              style="background:{{ $tag === 'EDITOR PICK' ? '#C8102E' : ($tag === 'FAMILY' ? '#18130E' : '#1F6E3D') }};">{{ $tag }}</span>
                    @endif
                    <div class="absolute top-2.5 right-2.5 w-7 h-7 rounded-full bg-white/95 flex items-center justify-center text-xs">♡</div>
                </div>
                <div class="p-3.5">
                    <p class="fb-field-label">{{ strtoupper($type) }}</p>
                    <p class="text-sm font-bold mt-1 leading-snug">{{ $n }}</p>
                    <p class="text-xs mt-1" style="color:#7A7166;">📍 {{ $loc }}</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span class="rating-badge">★ {{ $r }}</span>
                        <span class="text-xs" style="color:#7A7166;">({{ $rev }})</span>
                    </div>
                    <div class="flex justify-between items-end mt-3 pt-3 border-t border-[#EFE9DA]">
                        <div>
                            @if($was)<p class="text-xs line-through" style="color:#7A7166;">৳ {{ $was }}</p>@endif
                            <p class="text-base font-bold" style="color:#C8102E;">৳ {{ $p }}</p>
                            <p class="text-[9px]" style="color:#7A7166;">per night</p>
                        </div>
                        <a href="{{ route('hotels.index') }}" class="text-xs font-semibold">View →</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ── PICK & DROP BANNER ────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-10 max-w-screen-xl mx-auto">
    <div class="ota-card overflow-hidden grid grid-cols-1 lg:grid-cols-2">
        <div class="p-10 lg:p-12 relative overflow-hidden" style="background:#18130E;">
            <div class="absolute top-0 right-0 w-48 h-48 rounded-full" style="background:rgba(200,16,46,0.2);filter:blur(40px);transform:translate(20px,-20px);"></div>
            <div class="relative">
                <span class="ota-tag-red">🚗 NEW SERVICE · 2026</span>
                <h2 class="text-3xl font-bold mt-4 leading-tight text-white">A driver, <span style="color:#C8102E;">waiting</span> at arrivals.</h2>
                <p class="text-sm mt-4 leading-relaxed" style="color:rgba(255,255,255,0.7);">Airport, hourly, intercity, cross-border. Verified drivers, fixed pricing, English on request.</p>
                <a href="{{ route('pickdrop.index') }}" class="inline-block mt-6 px-6 py-3 bg-white font-bold text-sm rounded-lg" style="color:#18130E;">Book a ride →</a>
            </div>
        </div>
        <div class="p-6 grid grid-cols-2 gap-3">
            @foreach([
                ['✈','Airport transfer','From ৳ 1,400','Driver waits in arrivals · flight tracked'],
                ['⏱','Hourly chauffeur','৳ 600 / hr','Meetings, shopping, airport at the end'],
                ['🛣','Intercity','From ৳ 8,500','Dhaka ↔ Chittagong / Sylhet'],
                ['🌐','Cross-border','From ৳ 18,000','Dhaka → Kolkata via Petrapole'],
            ] as [$e,$t,$p,$d])
                <div class="p-4 border border-[#E4DCC9] rounded-xl">
                    <span class="text-xl">{{ $e }}</span>
                    <p class="text-sm font-bold mt-2.5">{{ $t }}</p>
                    <p class="text-xs font-semibold mt-1" style="color:#C8102E;">{{ $p }}</p>
                    <p class="text-xs mt-1.5 leading-relaxed" style="color:#7A7166;">{{ $d }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── STATS BAND ────────────────────────────────────────────────────── --}}
<section class="px-4 py-14 bg-white border-y border-[#E4DCC9]">
    <div class="max-w-screen-xl mx-auto grid grid-cols-2 md:grid-cols-5 divide-x divide-[#E4DCC9]">
        @foreach([['1.2M+','travellers booked'],['62','destinations'],['4.8★','24,000 reviews'],['94.2%','visa approval'],['24/7','support']] as [$n,$l])
            <div class="px-6 py-4 text-center">
                <p class="font-bold" style="font-size:clamp(28px,3.5vw,44px);letter-spacing:-0.02em;color:#18130E;">{{ $n }}</p>
                <p class="text-xs font-semibold mt-1.5 tracking-wide uppercase" style="color:#7A7166;">{{ $l }}</p>
            </div>
        @endforeach
    </div>
</section>

{{-- ── PACKAGES GRID ─────────────────────────────────────────────────── --}}
@if(isset($packages) && $packages->count())
<section class="px-4 lg:px-16 py-10 max-w-screen-xl mx-auto">
    <div class="flex justify-between items-end mb-6">
        <div>
            <span class="ota-eyebrow">🧳 Tour packages</span>
            <h2 class="ota-h2 mt-3">Handpicked, never resold</h2>
        </div>
        <a href="{{ route('packages.index') }}" class="text-sm font-bold" style="color:#C8102E;">All packages →</a>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($packages->take(6) as $pkg)
            <a href="{{ route('packages.show', $pkg->slug) }}" class="ota-card overflow-hidden flex flex-col">
                @if($pkg->image)
                    <img src="{{ asset('storage/'.$pkg->image) }}" alt="{{ $pkg->title }}" class="w-full h-48 object-cover">
                @else
                    <div class="h-48 relative" style="background:linear-gradient(135deg,#2A2520 0%,#4A3F35 55%,#8C7F6E 100%);">
                        <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                        <div class="absolute bottom-4 left-4 text-white">
                            <p class="font-serif italic text-lg leading-none">{{ $pkg->destination ?? $pkg->title }}</p>
                        </div>
                    </div>
                @endif
                <div class="p-5 flex flex-col flex-1">
                    <p class="text-xs font-semibold tracking-wide uppercase" style="color:#7A7166;">{{ $pkg->duration ?? '7N / 8D' }}</p>
                    <h3 class="text-lg font-bold mt-1.5 leading-snug">{{ $pkg->title }}</h3>
                    <p class="text-xs mt-2" style="color:#7A7166;">📍 {{ $pkg->destination ?? 'International' }}</p>
                    <div class="flex justify-between items-end mt-auto pt-4 border-t border-[#EFE9DA]">
                        <div>
                            <p class="text-xs font-mono tracking-wide" style="color:#C8102E;">FROM</p>
                            <p class="text-xl font-bold mt-0.5">৳ {{ number_format($pkg->price ?? 0) }}</p>
                        </div>
                        <span class="ota-btn-dark text-xs px-4 py-2 rounded-lg">View →</span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</section>
@endif

{{-- ── VISA HIGHLIGHT ────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-10 max-w-screen-xl mx-auto">
    <div class="rounded-2xl p-8 lg:p-12" style="background:linear-gradient(135deg,#18130E 0%,#3A332B 100%);">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
            <div>
                <span class="ota-tag-red">📘 VISA PROCESSING</span>
                <h2 class="text-3xl font-bold mt-4 leading-tight text-white">The paperwork, <span style="color:#C8102E;font-style:italic;">handled.</span></h2>
                <p class="text-sm mt-4 leading-relaxed" style="color:rgba(255,255,255,0.7);">94.2% approval rate · 12,800+ applications filed · 184 countries supported.</p>
                <a href="{{ route('visas.index') }}" class="inline-block mt-6 px-6 py-3 font-bold text-sm rounded-lg" style="background:#C8102E;color:#fff;">Start an application →</a>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @foreach([['94.2%','Approval rate'],['12,800+','Applications filed'],['184','Countries'],['4.2 days','Avg. doc prep']] as [$n,$l])
                    <div class="p-4 rounded-xl" style="background:rgba(255,255,255,0.08);">
                        <p class="text-2xl font-bold text-white">{{ $n }}</p>
                        <p class="text-xs mt-1" style="color:rgba(255,255,255,0.6);">{{ $l }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── CTA STRIP ─────────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-10 max-w-screen-xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <div class="p-10 rounded-2xl relative overflow-hidden" style="background:#C8102E;">
            <span class="inline-block text-xs font-bold tracking-wide px-3 py-1.5 rounded-full mb-4" style="background:rgba(255,255,255,0.2);color:#fff;">📱 GET THE APP</span>
            <h3 class="text-2xl font-bold text-white leading-tight">৳ 500 off your first booking</h3>
            <p class="text-sm mt-2 text-white/85 max-w-xs">Download the Flyover app · iOS and Android · 100k+ downloads</p>
            <div class="flex gap-3 mt-6">
                <span class="px-4 py-2.5 bg-white font-bold text-sm rounded-lg" style="color:#C8102E;">App Store</span>
                <span class="px-4 py-2.5 bg-white font-bold text-sm rounded-lg" style="color:#C8102E;">Google Play</span>
            </div>
        </div>
        <div class="p-10 rounded-2xl relative" style="background:#18130E;">
            <span class="inline-block text-xs font-bold tracking-wide px-3 py-1.5 rounded-full mb-4" style="background:rgba(255,255,255,0.12);color:#fff;">💬 NEED HELP?</span>
            <h3 class="text-2xl font-bold text-white leading-tight">Chat with a human now</h3>
            <p class="text-sm mt-2 text-white/70 max-w-xs">WhatsApp or call · 14-minute average response · open 24/7</p>
            <div class="flex flex-wrap gap-3 mt-6">
                <a href="https://wa.me/8801335111370" target="_blank" class="px-5 py-2.5 font-bold text-sm rounded-lg text-white" style="background:#25D366;">WhatsApp now</a>
                <a href="tel:+8809678332211" class="px-5 py-2.5 font-bold text-sm rounded-lg border text-white" style="border-color:rgba(255,255,255,0.3);">+880 9678 332211</a>
            </div>
        </div>
    </div>
</section>

</x-app-layout>
