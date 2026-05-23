<x-app-layout>

{{-- ── HERO ──────────────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pt-12 pb-14" style="background:#FAF6EE;">
    <div class="max-w-screen-xl mx-auto">
        <span class="fb-eyebrow">Contact</span>
        <h1 class="fb-serif mt-3" style="font-size:clamp(40px,5vw,64px);color:#18130E;line-height:0.96;">
            Talk to a real person.
        </h1>
        <p class="mt-4 text-base max-w-lg" style="color:#3A332B;">
            No chatbots. No ticket queues. 9am–8pm every day — call, WhatsApp, email, or drop by the Banani office.
        </p>
    </div>
</section>

{{-- ── MAIN GRID ────────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 py-16 max-w-screen-xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-10 lg:gap-14">

        {{-- Left: contact info (2/5) --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Contact info cards --}}
            @foreach([
                ['📍','Visit our office','House 45, Road 13, Block D, Banani, Dhaka 1213'],
                ['📞','Call us','09611-677989 · +880 1335 111370'],
                ['✉️','Email us','info.flyoverbd@gmail.com · info@flyoverbd.net'],
                ['🕐','Office hours','9am – 8pm · Every day including Friday'],
            ] as [$icon,$title,$value])
            <div class="ota-card p-5 flex items-start gap-4">
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-lg shrink-0" style="background:#FAF6EE;">{{ $icon }}</div>
                <div>
                    <p class="font-bold text-sm mb-1" style="color:#18130E;">{{ $title }}</p>
                    <p class="text-sm leading-relaxed" style="color:#7A7166;">{{ $value }}</p>
                </div>
            </div>
            @endforeach

            {{-- Quick links --}}
            <div class="ota-card p-5">
                <p class="fb-eyebrow mb-4">Quick actions</p>
                <div class="space-y-2.5">
                    <a href="https://wa.me/8801335111370" target="_blank"
                        class="flex items-center gap-3 text-sm font-semibold hover:opacity-80 transition"
                        style="color:#1F6E3D;">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm" style="background:#1F6E3D;">💬</span>
                        WhatsApp us now
                    </a>
                    <a href="tel:09611677989"
                        class="flex items-center gap-3 text-sm font-semibold hover:opacity-80 transition"
                        style="color:#18130E;">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm" style="background:#18130E;">📞</span>
                        Call 09611-677989
                    </a>
                    <a href="mailto:info.flyoverbd@gmail.com"
                        class="flex items-center gap-3 text-sm font-semibold hover:opacity-80 transition"
                        style="color:#C8102E;">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm" style="background:#C8102E;">✉️</span>
                        Send an email
                    </a>
                </div>
            </div>

            {{-- Map placeholder --}}
            <div class="rounded-2xl overflow-hidden border border-[#E4DCC9] shadow-sm" style="height:240px;">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.318322300062!2d90.40393457597148!3d23.79242968659107!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c70c0c609053%3A0xc48641477727382b!2sBanani%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1705574400000!5m2!1sen!2sbd"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>

        {{-- Right: contact form (3/5) --}}
        <div class="lg:col-span-3">
            <div class="ota-card p-8 lg:p-10">

                {{-- Success message --}}
                @if(session('success'))
                <div class="mb-6 flex items-start gap-3 px-5 py-4 rounded-xl" style="background:#E8F5EC;">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0 mt-0.5" style="background:#1F6E3D;">✓</span>
                    <div>
                        <p class="font-bold text-sm" style="color:#1F6E3D;">Message sent!</p>
                        <p class="text-sm mt-0.5" style="color:#1F6E3D;">{{ session('success') }}</p>
                    </div>
                </div>
                @endif

                {{-- Error messages --}}
                @if($errors->any())
                <div class="mb-6 px-5 py-4 rounded-xl" style="background:#FFF0F2;">
                    <p class="font-bold text-sm mb-2" style="color:#C8102E;">Please fix the following:</p>
                    <ul class="space-y-1">
                        @foreach($errors->all() as $error)
                        <li class="text-sm" style="color:#C8102E;">· {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <h2 class="font-bold text-xl mb-6" style="color:#18130E;">Send us a message</h2>

                {{-- Subject tabs using Alpine --}}
                <div x-data="{ subject: '{{ old('subject', 'Visa Inquiry') }}' }" class="mb-6">
                    <p class="fb-field-label mb-2.5">What's this about?</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach(['Visa Inquiry','Tour Package','Flight Booking','Hotel Booking','Pick & Drop','Other'] as $opt)
                        <button type="button"
                            @click="subject = '{{ $opt }}'"
                            :class="subject === '{{ $opt }}'
                                ? 'border-[#C8102E] text-white'
                                : 'border-[#E4DCC9] text-[#3A332B] bg-white hover:border-[#18130E]'"
                            :style="subject === '{{ $opt }}' ? 'background:#C8102E;' : ''"
                            class="px-3.5 py-1.5 rounded-full border text-xs font-semibold transition-all duration-150">
                            {{ $opt }}
                        </button>
                        @endforeach
                    </div>

                    {{-- Hidden form --}}
                    <form action="{{ route('contact.store') }}" method="POST" class="mt-6 space-y-4">
                        @csrf
                        <input type="hidden" name="subject" :value="subject">

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div class="fb-field">
                                <label class="fb-field-label">First name</label>
                                <input type="text" name="first_name" value="{{ old('first_name') }}"
                                    placeholder="Karim" class="fb-input text-sm" required>
                            </div>
                            <div class="fb-field">
                                <label class="fb-field-label">Last name</label>
                                <input type="text" name="last_name" value="{{ old('last_name') }}"
                                    placeholder="Ansari" class="fb-input text-sm" required>
                            </div>
                        </div>

                        <div class="fb-field">
                            <label class="fb-field-label">Email address</label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                placeholder="you@example.com" class="fb-input text-sm" required>
                        </div>

                        <div class="fb-field">
                            <label class="fb-field-label">Phone number</label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                placeholder="+880 1700-000000" class="fb-input text-sm" required>
                        </div>

                        <div class="fb-field">
                            <label class="fb-field-label">Message</label>
                            <textarea name="message" rows="5" placeholder="Tell us what you need — the more detail, the faster we can help."
                                class="fb-input text-sm resize-none leading-relaxed" required>{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full ota-btn-primary py-4 text-base justify-center">
                            Send message →
                        </button>
                        <p class="text-center text-xs" style="color:#7A7166;">
                            We reply within 2 hours during office hours (9am – 8pm).
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── FAQ STRIP ─────────────────────────────────────────────────────── --}}
<section class="px-4 lg:px-16 pb-20 max-w-screen-xl mx-auto" x-data="{ open: null }">
    <div class="mb-8">
        <span class="fb-eyebrow">Common questions</span>
        <h2 class="fb-serif mt-2" style="font-size:clamp(24px,3vw,36px);color:#18130E;">You probably want to know…</h2>
    </div>
    <div class="max-w-2xl space-y-3">
        @foreach([
            ['How long does visa processing take?','It depends on the destination. Most tourist visas take 5–10 working days from submission. Urgent processing is available for some countries at an additional cost. We'll tell you exactly what to expect before you apply.'],
            ['Do you charge for visa consultations?','No. Initial consultations are free. We only charge when you proceed with an application — and we tell you the full fee before you commit.'],
            ['What if my visa gets rejected?','We'll explain why, at no extra charge, and advise whether a re-application makes sense. Our success rate is above 94% — but when rejections happen, we stand by you.'],
            ['Can I book a tour without a visa in hand?','Yes. We'll help you plan the itinerary and hold spots while your visa is processed. If it's rejected, tour payments are fully refunded.'],
        ] as $i => [$q,$a])
        <div class="ota-card overflow-hidden">
            <button
                @click="open = open === {{ $i }} ? null : {{ $i }}"
                class="w-full flex items-center justify-between gap-4 p-5 text-left">
                <span class="font-semibold text-sm" style="color:#18130E;">{{ $q }}</span>
                <svg :class="open === {{ $i }} ? 'rotate-180' : ''"
                    class="w-4 h-4 shrink-0 transition-transform duration-200" style="color:#7A7166;"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open === {{ $i }}" x-collapse class="px-5 pb-5">
                <p class="text-sm leading-relaxed" style="color:#7A7166;">{{ $a }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

</x-app-layout>
