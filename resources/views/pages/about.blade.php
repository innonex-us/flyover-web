<x-app-layout
    title="About FlyoverBD | Bangladesh's Trusted Travel Agency"
    meta_description="FlyoverBD is a premier travel agency in Dhaka offering visa processing, tour packages, and expert travel consulting since 2016."
>

{{-- ── Hero ─────────────────────────────────────────────────── --}}
<section class="relative overflow-hidden" style="background:linear-gradient(135deg,#18130E 0%,#2E1A0E 50%,#18130E 100%);">
    <div class="absolute inset-0 opacity-10" style="background-image:url(\"data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='1'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E\");"></div>
    <div class="relative max-w-5xl mx-auto px-4 py-24 text-center">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-widest mb-6" style="background:rgba(200,16,46,.2);color:#FF6B85;border:1px solid rgba(200,16,46,.3);">
            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
            Est. 2016 &nbsp;·&nbsp; Dhaka, Bangladesh
        </span>
        <h1 class="font-extrabold text-4xl md:text-6xl mb-6 leading-tight" style="font-family:'Merriweather',Georgia,serif;color:#FAF6EE;">
            Your Journey Starts<br>
            <span style="color:#C8102E;">Here</span>
        </h1>
        <p class="max-w-2xl mx-auto text-base md:text-lg leading-relaxed mb-10" style="color:#A09890;">
            For nearly a decade, FlyoverBD has been Bangladesh's most trusted travel partner — simplifying visas, crafting unforgettable tours, and turning travel dreams into reality.
        </p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('contact') }}" class="btn-primary px-8 py-3.5">Start Planning</a>
            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl text-sm font-semibold transition-all" style="border:1.5px solid rgba(255,255,255,.25);color:#FAF6EE;">
                Browse Tours
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <div class="flex flex-wrap justify-center gap-8 mt-14 pt-10" style="border-top:1px solid rgba(255,255,255,.1);">
            @php $heroStats = [
                [$siteStats['travellers'], 'Happy Travellers'],
                [$siteStats['destinations'], 'Destinations'],
                [$siteStats['visa_approval'].'%', 'Visa Approval'],
                ['9+', 'Years Experience'],
            ]; @endphp
            @foreach($heroStats as [$n, $l])
            <div class="text-center">
                <p class="font-extrabold text-3xl md:text-4xl" style="color:#C8102E;">{{ $n }}</p>
                <p class="text-xs font-medium mt-1 uppercase tracking-wider" style="color:#7A6E68;">{{ $l }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Who We Are ────────────────────────────────────────────── --}}
<section class="bg-white py-20">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
        <div>
            <p class="section-eyebrow mb-3">Who We Are</p>
            <h2 class="font-extrabold text-3xl md:text-4xl text-gray-900 mb-6 leading-snug" style="font-family:'Merriweather',Georgia,serif;">Bangladesh's Most<br>Trusted Travel Agency</h2>
            <div class="space-y-4 text-gray-600 leading-relaxed">
                <p>FlyoverBD is a premier travel consultancy headquartered in Dhaka. Born from a passion for making travel seamless, we've grown into a full-service agency trusted by thousands of Bangladeshis every year.</p>
                <p>From a single visa application to a full family vacation package — we handle every detail with precision and care. Our multilingual team understands the unique challenges Bangladeshi travellers face and we're built to solve them.</p>
                <p>We don't just book trips; we build experiences that last a lifetime.</p>
            </div>
            <div class="flex flex-wrap gap-3 mt-8">
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-red-50 text-red-700">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                    IATA Affiliated
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                    TOAB Member
                </span>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold bg-green-50 text-green-700">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                    Govt. Licensed
                </span>
            </div>
        </div>
        <div class="space-y-4">
            <div class="rounded-2xl p-7 border flex items-start gap-5 transition hover:shadow-md" style="background:#FFFBF5;border-color:#E4DCC9;">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#C8102E;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-2">Our Mission</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">To empower every Bangladeshi traveller with accurate information, transparent processing, and stress-free services — making global travel accessible to all.</p>
                </div>
            </div>
            <div class="rounded-2xl p-7 border flex items-start gap-5 transition hover:shadow-md" style="background:#FFFBF5;border-color:#E4DCC9;">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#18130E;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-2">Our Vision</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">To be Bangladesh's most trusted and innovative travel partner, recognised globally for integrity, excellence, and unmatched customer satisfaction.</p>
                </div>
            </div>
            <div class="rounded-2xl p-7 border flex items-start gap-5 transition hover:shadow-md" style="background:#FFFBF5;border-color:#E4DCC9;">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#1D4ED8;">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-2">Our Values</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">Transparency, reliability, and a genuine love of travel guide everything we do — from the first consultation to the final boarding pass.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Stats Bar ─────────────────────────────────────────────── --}}
<section style="background:#18130E;" class="py-20">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="section-eyebrow mb-3" style="color:#C8102E;">Numbers that speak</p>
            <h2 class="font-extrabold text-3xl md:text-4xl" style="font-family:'Merriweather',Georgia,serif;color:#FAF6EE;">Our Track Record</h2>
            <p class="text-sm mt-3 max-w-md mx-auto" style="color:#7A6E68;">Every number represents a traveller we helped, a dream we fulfilled, and a journey made effortless.</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @php
            $aboutStats = [
                ['icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z', 'num'=>$siteStats['travellers'], 'label'=>'Happy Travellers', 'color'=>'#C8102E'],
                ['icon'=>'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'num'=>($siteStats['visa_total'] > 0 ? number_format($siteStats['visa_total']).'+ ': '5,000+'), 'label'=>'Visas Processed', 'color'=>'#3B82F6'],
                ['icon'=>'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064', 'num'=>$siteStats['destinations'], 'label'=>'Destinations', 'color'=>'#10B981'],
                ['icon'=>'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'num'=>$siteStats['visa_approval'].'%', 'label'=>'Visa Approval Rate', 'color'=>'#F59E0B'],
            ];
            @endphp
            @foreach($aboutStats as $stat)
            <div class="text-center py-8 px-4 rounded-2xl" style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.08);">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mx-auto mb-4" style="background:rgba(255,255,255,.06);">
                    <svg class="w-6 h-6" style="color:{{ $stat['color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $stat['icon'] }}"/>
                    </svg>
                </div>
                <p class="font-extrabold text-3xl md:text-4xl mb-1.5" style="color:{{ $stat['color'] }}">{{ $stat['num'] }}</p>
                <p class="text-xs font-medium uppercase tracking-wider" style="color:#7A6E68;">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Our Journey / Timeline ───────────────────────────────── --}}
<section class="bg-white py-20">
    <div class="max-w-4xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="section-eyebrow mb-3">Our Journey</p>
            <h2 class="font-extrabold text-3xl md:text-4xl text-gray-900" style="font-family:'Merriweather',Georgia,serif;">A Decade of Milestones</h2>
            <p class="text-sm text-gray-500 mt-3 max-w-md mx-auto">From a small consulting desk to Bangladesh's leading travel platform — here's how we got here.</p>
        </div>
        @php $timeline = [
            ['year'=>'2016','title'=>'Founded in Dhaka','desc'=>'Started as a small visa consultancy in Banani, helping clients navigate complex embassy requirements.','side'=>'left'],
            ['year'=>'2018','title'=>'Tour Packages Launched','desc'=>'Expanded into curated domestic and international tour packages, serving families and corporate groups.','side'=>'right'],
            ['year'=>'2020','title'=>'Digital Transformation','desc'=>'Launched our online booking platform, making it seamless to apply for visas and book tours 24/7.','side'=>'left'],
            ['year'=>'2022','title'=>'10,000+ Travellers Served','desc'=>'Crossed a major milestone with over ten thousand happy travellers placing their trust in FlyoverBD.','side'=>'right'],
            ['year'=>'2024','title'=>'Hotel & Transfer Services','desc'=>'Introduced hotel bookings and pick-and-drop airport transfers, becoming a one-stop travel solution.','side'=>'left'],
            ['year'=>'2025','title'=>'Expanding Horizons','desc'=>'Partnering with international agencies, launching loyalty rewards, and growing to serve all of Bangladesh.','side'=>'right'],
        ]; @endphp
        <div class="relative">
            <div class="absolute left-1/2 top-0 bottom-0 w-px -translate-x-1/2 hidden md:block" style="background:linear-gradient(to bottom,transparent,#E4DCC9 10%,#E4DCC9 90%,transparent);"></div>
            <div class="space-y-8">
                @foreach($timeline as $item)
                <div class="relative flex flex-col md:flex-row {{ $item['side']==='right' ? 'md:flex-row-reverse' : '' }} items-center gap-6 md:gap-10">
                    <div class="{{ $item['side']==='left' ? 'md:text-right' : 'md:text-left' }} flex-1">
                        <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-md transition">
                            <span class="inline-block text-xs font-bold uppercase tracking-widest px-3 py-1 rounded-full mb-3" style="background:#FFF0F2;color:#C8102E;">{{ $item['year'] }}</span>
                            <h3 class="font-bold text-gray-900 mb-2">{{ $item['title'] }}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $item['desc'] }}</p>
                        </div>
                    </div>
                    <div class="hidden md:flex w-4 h-4 rounded-full flex-shrink-0 z-10 ring-4 ring-white" style="background:#C8102E;"></div>
                    <div class="flex-1 hidden md:block"></div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- ── Why Choose Us ─────────────────────────────────────────── --}}
<section style="background:#F9F6EF;" class="py-20">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="section-eyebrow mb-3">Why us</p>
            <h2 class="font-extrabold text-3xl md:text-4xl text-gray-900" style="font-family:'Merriweather',Georgia,serif;">Why Thousands Choose FlyoverBD</h2>
            <p class="text-sm text-gray-500 mt-3 max-w-md mx-auto">We're not just a travel agency — we're your travel partner.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @php $features = [
                ['bg'=>'#FFF0F2','ic'=>'#C8102E','svg'=>'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'High Success Rate','desc'=>'Our deep expertise in documentation ensures a high approval probability for every visa application we submit.'],
                ['bg'=>'#EFF6FF','ic'=>'#3B82F6','svg'=>'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'Fast Processing','desc'=>'Streamlined workflows and a dedicated team ensure your applications are processed well within your timeline.'],
                ['bg'=>'#F0FDF4','ic'=>'#10B981','svg'=>'M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z','title'=>'Transparent Pricing','desc'=>'No hidden fees, no surprises. Affordable and fully transparent pricing for every service we offer.'],
                ['bg'=>'#FFF7ED','ic'=>'#F59E0B','svg'=>'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z','title'=>'Expert Consultants','desc'=>'Our seasoned travel professionals stay updated on the latest visa policies and immigration regulations worldwide.'],
                ['bg'=>'#F5F3FF','ic'=>'#8B5CF6','svg'=>'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z','title'=>'Secure & Reliable','desc'=>'Your documents and personal data are handled with the highest standards of security and confidentiality.'],
                ['bg'=>'#FFF0F2','ic'=>'#E11D48','svg'=>'M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z','title'=>'24/7 Support','desc'=>"Travel emergencies don't keep office hours. Our support team is reachable around the clock, every day of the year."],
            ]; @endphp
            @foreach($features as $f)
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-200 group">
                <div class="w-12 h-12 rounded-xl flex items-center justify-center mb-4 transition-transform duration-200 group-hover:scale-110" style="background:{{ $f['bg'] }};">
                    <svg class="w-6 h-6" style="color:{{ $f['ic'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $f['svg'] }}"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $f['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $f['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Meet the Team ─────────────────────────────────────────── --}}
<section class="bg-white py-20">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-14">
            <p class="section-eyebrow mb-3">The people behind the magic</p>
            <h2 class="font-extrabold text-3xl md:text-4xl text-gray-900" style="font-family:'Merriweather',Georgia,serif;">Meet Our Team</h2>
            <p class="text-sm text-gray-500 mt-3 max-w-sm mx-auto">Passionate travel experts, dedicated to making your journey extraordinary.</p>
        </div>
        @php $team = [
            ['initials'=>'RA','name'=>'Rafiqul Alam','role'=>'Founder & CEO','bio'=>'20+ years in travel and tourism. Rafiqul built FlyoverBD from the ground up with a single belief: travel should be for everyone.','bg'=>'#C8102E'],
            ['initials'=>'SH','name'=>'Sumaiya Haque','role'=>'Head of Visa Services','bio'=>'A certified immigration consultant with deep expertise in South-East Asian, European, and North American visa requirements.','bg'=>'#1D4ED8'],
            ['initials'=>'MR','name'=>'Mosabbir Rahman','role'=>'Senior Tour Designer','bio'=>'Crafts every itinerary with the attention of a storyteller — balancing adventure, comfort, and cultural immersion.','bg'=>'#059669'],
            ['initials'=>'NK','name'=>'Nasrin Khanam','role'=>'Customer Relations Lead','bio'=>'Ensures every client feels heard and supported, from initial inquiry to safe return home.','bg'=>'#7C3AED'],
        ]; @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($team as $member)
            <div class="text-center group">
                <div class="w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-4 text-white font-extrabold text-xl transition-transform duration-200 group-hover:scale-105" style="background:{{ $member['bg'] }};">
                    {{ $member['initials'] }}
                </div>
                <h3 class="font-bold text-gray-900">{{ $member['name'] }}</h3>
                <p class="text-xs font-semibold uppercase tracking-wider mt-0.5 mb-3" style="color:#C8102E;">{{ $member['role'] }}</p>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $member['bio'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Testimonial / Trust strip ────────────────────────────── --}}
<section style="background:#F9F6EF;border-top:1px solid #E4DCC9;border-bottom:1px solid #E4DCC9;" class="py-16">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-12">
            <p class="section-eyebrow mb-3">What our travellers say</p>
            <h2 class="font-extrabold text-2xl md:text-3xl text-gray-900" style="font-family:'Merriweather',Georgia,serif;">Real Stories, Real Experiences</h2>
        </div>
        @php $reviews = [
            ['quote'=>'FlyoverBD handled my UK visa application flawlessly. Everything was submitted on time and I got approved in under two weeks!','name'=>'Tamanna Islam','location'=>'Dhaka','rating'=>5],
            ['quote'=>"Booked a Thailand family package and it was absolutely perfect. Hotel, transfers, everything was arranged. Didn't have to worry about a thing.",'name'=>'Kamal Uddin','location'=>'Chittagong','rating'=>5],
            ['quote'=>'Professional, transparent, and super responsive. I\'ve been using FlyoverBD for all my visa needs for the past three years.','name'=>'Rifat Hossain','location'=>'Sylhet','rating'=>5],
        ]; @endphp
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($reviews as $review)
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="flex gap-0.5 mb-4">
                    @for($i=0;$i<$review['rating'];$i++)
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    @endfor
                </div>
                <p class="text-sm text-gray-600 leading-relaxed mb-5 italic">"{{ $review['quote'] }}"</p>
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-xs font-bold flex-shrink-0" style="background:#18130E;">
                        {{ strtoupper(substr($review['name'],0,1)) }}
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-gray-900">{{ $review['name'] }}</p>
                        <p class="text-xs text-gray-400">{{ $review['location'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ───────────────────────────────────────────────────── --}}
<section class="py-20 text-center" style="background:linear-gradient(135deg,#18130E 0%,#2E1A0E 100%);">
    <div class="max-w-2xl mx-auto px-4">
        <p class="section-eyebrow mb-4" style="color:#C8102E;">Let's get started</p>
        <h2 class="font-extrabold text-3xl md:text-4xl mb-4 leading-tight" style="font-family:'Merriweather',Georgia,serif;color:#FAF6EE;">Ready to Explore<br>the World with Us?</h2>
        <p class="text-sm mb-8 max-w-sm mx-auto" style="color:#A09890;">Whether it's a visa, a tour, or a custom itinerary — our team is ready to make it happen. Free consultation, always.</p>
        <div class="flex flex-wrap justify-center gap-4">
            <a href="{{ route('contact') }}" class="btn-primary px-8 py-3.5 text-base">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                Get Free Consultation
            </a>
            <a href="{{ route('packages.index') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl text-sm font-semibold transition-all" style="border:1.5px solid rgba(255,255,255,.25);color:#FAF6EE;">
                Browse Tour Packages
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
        <p class="text-xs mt-6" style="color:#5A5248;">
            Or call us at <a href="tel:+8801711677469" class="underline" style="color:#A09890;">+880 1711-677469</a> &nbsp;·&nbsp; Mon–Sat, 9am–7pm
        </p>
    </div>
</section>

</x-app-layout>
