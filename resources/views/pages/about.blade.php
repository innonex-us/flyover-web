<x-app-layout>

{{-- ── HERO ──────────────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pt-16 pb-20" style="background:#FAF6EE;">
    <div class="max-w-screen-xl mx-auto">
        <span class="fb-eyebrow">Our story</span>
        <h1 class="fb-serif mt-4 max-w-3xl leading-[0.96]" style="font-size:clamp(40px,5.5vw,72px);color:#18130E;">
            A small Dhaka office, seventeen years of sending people away well.
        </h1>
        <p class="mt-6 text-base leading-relaxed max-w-xl" style="color:#3A332B;">
            Founded in 2009 on a single desk in Banani, FlyoverBD grew one satisfied traveller at a time — no shortcuts, no fine print, no broken promises.
        </p>
    </div>
</section>

{{-- ── STORY SECTION ────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-20" style="background:#FFFFFF;">
    <div class="max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-14 lg:gap-20 items-start">

        {{-- Left: placeholder image --}}
        <div>
            <div class="relative rounded-2xl overflow-hidden shadow-xl" style="height:420px;background:linear-gradient(135deg,#1E2A22 0%,#36473A 55%,#7C8E80 100%);">
                <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                <div class="absolute inset-0" style="background:radial-gradient(circle at 30% 40%,rgba(255,255,255,0.06),transparent 50%);"></div>
                {{-- Caption overlay --}}
                <div class="absolute bottom-0 left-0 right-0 p-6" style="background:linear-gradient(to top,rgba(0,0,0,0.65),transparent);">
                    <p class="text-white font-bold text-lg">Our office, Banani — 2009</p>
                    <p class="text-white/60 text-sm mt-0.5">One desk, a phone, and an ambition to do it differently.</p>
                </div>
            </div>

            {{-- Stats row --}}
            <div class="grid grid-cols-3 gap-4 mt-6">
                @foreach([['2009','Founded'],['17,400+','Travellers'],['33','Staff']] as [$num,$label])
                <div class="ota-card p-4 text-center">
                    <p class="text-2xl font-bold leading-none" style="color:#18130E;">{{ $num }}</p>
                    <p class="fb-mono mt-1.5">{{ $label }}</p>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Right: story paragraphs --}}
        <div class="space-y-6">
            <div>
                <h2 class="fb-serif mb-4" style="font-size:clamp(26px,3vw,38px);color:#18130E;">How we began</h2>
                <p class="text-base leading-relaxed" style="color:#3A332B;">
                    FlyoverBD was founded by two friends who spent the better part of 2008 trying to get a Schengen visa and failing — not because they weren't eligible, but because no one in Dhaka was doing the paperwork correctly. They fixed that problem first, then never stopped fixing travel for Bangladeshis.
                </p>
            </div>
            <div>
                <p class="text-base leading-relaxed" style="color:#3A332B;">
                    By 2013 we were processing visas for forty countries. By 2016 we'd launched our first group tour to Nepal. By 2019 the office had tripled. Today we're 33 people who care obsessively about one thing: sending people away well and bringing them home happy.
                </p>
            </div>
            <div>
                <p class="text-base leading-relaxed" style="color:#3A332B;">
                    We have no venture funding and no corporate parent. Every taka we earn goes back into better service, better training, and better prices for our travellers. We're proud of that independence — it's why we can say no to bad deals and yes to what actually works.
                </p>
            </div>
            <div class="pt-4 border-t border-[#E4DCC9]">
                <div class="flex flex-wrap gap-3">
                    <span class="fb-chip">🏆 Best Travel Agency, Dhaka 2023</span>
                    <span class="fb-chip">✓ IATA Accredited</span>
                    <span class="fb-chip">📋 Atab Member</span>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── TEAM GRID ─────────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-20" style="background:#FAF6EE;">
    <div class="max-w-screen-xl mx-auto">
        <div class="mb-10">
            <span class="fb-eyebrow">The people</span>
            <h2 class="fb-serif mt-2" style="font-size:clamp(28px,3.5vw,44px);color:#18130E;">Meet the team.</h2>
            <p class="mt-3 text-base max-w-lg" style="color:#7A7166;">
                33 people, all of whom have personally used the services they sell.
            </p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @php
            $team = [
                ['Karim Ansari','Founder & CEO','Visa & immigration specialist. Has visited 47 countries. Speaks 4 languages.',['1E2A22','36473A','7C8E80']],
                ['Nadia Sultana','Co-founder & Head of Tours','Designed over 200 custom itineraries. Lives for hill-tracts travel.',['2B2117','574535','A89178']],
                ['Rafiqul Islam','Head of Visa Operations','Processed 8,000+ visa applications. Zero fraud cases.',['1A2026','2E3C4A','697D8E']],
                ['Shirin Akter','Customer Experience Lead','If something goes wrong at 2am, Shirin fixes it.',['261E14','4F3C24','96784A']],
                ['Tanvir Hossain','Hotel & Accommodation','Inspected over 300 properties personally before listing them.',['1C2417','354A2E','7A9466']],
                ['Mehnaz Parvin','Finance & Compliance','Keeps the books clean and the pricing honest.',['201C2A','403565','807BA0']],
                ['Shafiq Rahman','Fleet & Pick &amp; Drop','Runs the driver network. 40 vetted cars, 0 incidents this year.',['261C1A','4F3530','A87A72']],
                ['Lamia Chowdhury','Marketing & Digital','Writes the emails you actually want to read.',['1A2020','344040','6A8080']],
            ];
            @endphp
            @foreach($team as [$name,$role,$bio,$colors])
            <div class="ota-card overflow-hidden">
                {{-- Portrait placeholder --}}
                <div class="relative h-48" style="background:linear-gradient(160deg,#{{ $colors[0] }} 0%,#{{ $colors[1] }} 60%,#{{ $colors[2] }} 100%);">
                    <div class="absolute inset-0" style="background:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0 2px,transparent 2px 18px);"></div>
                    <div class="absolute bottom-4 left-4">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold text-xl text-white border-2 border-white/30"
                            style="background:rgba(255,255,255,0.15);backdrop-filter:blur(8px);">
                            {{ mb_substr($name, 0, 1) }}
                        </div>
                    </div>
                </div>
                <div class="p-4">
                    <h3 class="font-bold text-base" style="color:#18130E;">{{ $name }}</h3>
                    <p class="text-xs font-semibold mt-0.5 mb-2" style="color:#C8102E;">{{ $role }}</p>
                    <p class="text-xs leading-relaxed" style="color:#7A7166;">{!! $bio !!}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── VALUES SECTION ────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-20" style="background:#FAF6EE;">
    <div class="max-w-screen-xl mx-auto">
        <div class="mb-10">
            <span class="fb-eyebrow">What we believe</span>
            <h2 class="fb-serif mt-2" style="font-size:clamp(28px,3.5vw,44px);color:#18130E;">Four values, non-negotiable.</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach([
                ['Honesty first','We will never oversell a destination, inflate a price, or hide a fee. If a visa is unlikely to succeed, we tell you — and we don't charge you to find out.'],
                ['Speed without shortcuts','We move fast because we're prepared, not because we skip steps. A rejected visa isn't a shortcut. Neither is a bad hotel.'],
                ['Local expertise','Every destination we offer is one our team has personally visited, assessed, and can describe from the taxi rank to the best dinner table.'],
                ['Long-term relationship','70% of our business is repeat or referred. We measure our success by whether you come back — not by how many bookings we closed this month.'],
            ] as [$title,$desc])
            <div class="bg-white rounded-2xl border border-[#E4DCC9] p-8 flex gap-5">
                <div class="w-1 shrink-0 rounded-full" style="background:#C8102E;"></div>
                <div>
                    <h3 class="font-bold text-lg mb-2" style="color:#18130E;">{{ $title }}</h3>
                    <p class="text-base leading-relaxed" style="color:#7A7166;">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CONTACT SECTION ──────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-20" style="background:#18130E;">
    <div class="max-w-screen-xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">

        {{-- Left: contact info --}}
        <div>
            <span class="fb-eyebrow" style="color:#E4DCC9;opacity:0.6;">Get in touch</span>
            <h2 class="fb-serif mt-3 text-white" style="font-size:clamp(28px,3.5vw,44px);">We're here. Come in.</h2>
            <p class="mt-4 text-base leading-relaxed" style="color:#7A7166;">
                Walk in, call, or send a message — we'll respond the same day during office hours (9am–8pm, every day including Friday).
            </p>

            <div class="mt-8 space-y-5">
                @foreach([
                    ['📍','Address','House 45, Road 13, Block D, Banani, Dhaka 1213'],
                    ['📞','Phone','09611-677989 · +880 1335 111370'],
                    ['✉️','Email','info.flyoverbd@gmail.com · info@flyoverbd.net'],
                    ['🕐','Hours','9am – 8pm · Every day including Friday'],
                ] as [$icon,$label,$value])
                <div class="flex items-start gap-4">
                    <span class="text-xl shrink-0 mt-0.5">{{ $icon }}</span>
                    <div>
                        <p class="fb-mono" style="color:#7A7166;">{{ $label }}</p>
                        <p class="text-white font-medium mt-1 text-sm leading-relaxed">{{ $value }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-8 flex gap-3">
                <a href="https://wa.me/8801335111370" target="_blank" class="ota-btn-primary px-5 py-2.5 text-sm">
                    💬 WhatsApp
                </a>
                <a href="{{ route('contact') }}" class="ota-btn-ghost px-5 py-2.5 text-sm" style="background:transparent;color:#FAF6EE;border-color:#3A332B;">
                    Contact page →
                </a>
            </div>
        </div>

        {{-- Right: contact form --}}
        <div class="bg-white rounded-2xl p-8">
            @if(session('success'))
            <div class="mb-5 flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-semibold" style="background:#E8F5EC;color:#1F6E3D;">
                <span>✓</span> {{ session('success') }}
            </div>
            @endif
            @if($errors->any())
            <div class="mb-5 px-4 py-3 rounded-xl text-sm" style="background:#FFF0F2;color:#C8102E;">
                <ul class="list-disc pl-4 space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <h3 class="font-bold text-lg mb-6" style="color:#18130E;">Send us a message</h3>
            <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="fb-field">
                        <label class="fb-field-label">First name</label>
                        <input type="text" name="first_name" value="{{ old('first_name') }}" placeholder="Karim" class="fb-input text-sm" required>
                    </div>
                    <div class="fb-field">
                        <label class="fb-field-label">Last name</label>
                        <input type="text" name="last_name" value="{{ old('last_name') }}" placeholder="Ansari" class="fb-input text-sm" required>
                    </div>
                </div>
                <div class="fb-field">
                    <label class="fb-field-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" class="fb-input text-sm" required>
                </div>
                <div class="fb-field">
                    <label class="fb-field-label">Phone</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" placeholder="+880 1700-000000" class="fb-input text-sm" required>
                </div>
                <div class="fb-field">
                    <label class="fb-field-label">Subject</label>
                    <select name="subject" class="fb-input text-sm bg-transparent">
                        @foreach(['Visa Inquiry','Tour Package','Flight Booking','Hotel Booking','Pick & Drop','Other'] as $opt)
                        <option {{ old('subject') === $opt ? 'selected' : '' }}>{{ $opt }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="fb-field">
                    <label class="fb-field-label">Message</label>
                    <textarea name="message" rows="4" placeholder="Tell us what you need…" class="fb-input text-sm resize-none" required>{{ old('message') }}</textarea>
                </div>
                <button type="submit" class="w-full ota-btn-primary py-3.5 text-base justify-center">
                    Send message →
                </button>
            </form>
        </div>
    </div>
</section>

</x-app-layout>
