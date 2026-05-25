<x-app-layout
    title="About FlyoverBD | Bangladesh's Trusted Travel Agency"
    meta_description="FlyoverBD is a premier travel agency in Dhaka offering visa processing, tour packages, and expert travel consulting since 2016."
>

{{-- ── Hero ─────────────────────────────── --}}
<section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="px-4 py-16 text-center">
    <p class="section-eyebrow mb-3">Our Story</p>
    <h1 class="font-extrabold text-4xl md:text-5xl mb-4" style="font-family:'Merriweather',Georgia,serif;color:#18130E;">
        About FlyoverBD
    </h1>
    <p class="max-w-xl mx-auto text-base leading-relaxed" style="color:#7A7166;">
        Your trusted partner in navigating the world. We simplify travel, one visa and one destination at a time.
    </p>
</section>

{{-- ── Who We Are ───────────────────────── --}}
<section class="bg-white py-16">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-14 items-center">
        <div>
            <p class="section-eyebrow mb-3">Who We Are</p>
            <h2 class="font-extrabold text-3xl text-gray-900 mb-5" style="font-family:'Merriweather',Georgia,serif;">Bangladesh's Premier Travel Agency</h2>
            <div class="space-y-4 text-gray-600 leading-relaxed text-sm md:text-base">
                <p>FlyoverBD is a premier travel consultancy based in Dhaka, Bangladesh. Established with a vision to eliminate the complexities of travel, we specialise in visa processing, tailored tour packages, and expert travel advice.</p>
                <p>We understand that every journey is unique. Whether you're a business traveler needing a quick visa turnaround, a family planning a vacation, or a student aspiring to study abroad - our dedicated team is here to guide you every step of the way.</p>
            </div>
        </div>
        <div class="grid grid-cols-1 gap-5">
            <div class="bg-gray-50 p-7 rounded-2xl border border-gray-100 flex items-start gap-4">
                <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Our Mission</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">To empower travelers by providing accurate information, transparent processing, and stress-free services - making global travel accessible to everyone.</p>
                </div>
            </div>
            <div class="bg-gray-50 p-7 rounded-2xl border border-gray-100 flex items-start gap-4">
                <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Our Vision</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">To be Bangladesh's most trusted and innovative travel partner, recognised for integrity, excellence, and customer satisfaction.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── Stats ────────────────────────────── --}}
<section style="background:#18130E;" class="py-16">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-10">
            <p class="section-eyebrow mb-2" style="color:#C8102E;">Trusted by travellers</p>
            <h2 class="font-extrabold text-3xl" style="font-family:'Merriweather',Georgia,serif;color:#FAF6EE;">Our Track Record</h2>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-px" style="background:#2E2720;">
            @php $stats = [['1.2M+','Happy Travellers'],['5,000+','Visas Processed'],['62','Destinations'],['8+','Years Experience']]; @endphp
            @foreach($stats as [$num,$label])
            <div class="text-center py-10 px-6" style="background:#18130E;">
                <p class="font-extrabold text-4xl mb-1" style="color:#C8102E;">{{ $num }}</p>
                <p class="text-sm font-medium" style="color:#A09890;">{{ $label }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Why Choose Us ────────────────────── --}}
<section style="background:#F9F6EF;" class="py-16">
    <div class="max-w-5xl mx-auto px-4">
        <div class="text-center mb-10">
            <p class="section-eyebrow mb-2">Why us</p>
            <h2 class="font-extrabold text-3xl text-gray-900" style="font-family:'Merriweather',Georgia,serif;">Why Choose FlyoverBD?</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @php $features = [
                ['bg-red-50','text-red-600','M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z','High Success Rate','Our expertise in documentation and visa requirements ensures a high approval probability for every application.'],
                ['bg-blue-50','text-blue-600','M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z','Fast Processing','Streamlined processes and a dedicated team work efficiently to meet your travel timelines.'],
                ['bg-green-50','text-green-600','M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z','Transparent Pricing','No hidden fees. Affordable and upfront pricing for all our premium services.'],
                ['bg-purple-50','text-purple-600','M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z','Expert Consultants','Travel veterans who stay updated with ever-changing immigration policies and travel trends.'],
            ]; @endphp
            @foreach($features as [$bg,$color,$icon,$title,$desc])
            <div class="bg-white rounded-2xl p-6 border border-gray-100 shadow-sm hover:shadow-md transition flex items-start gap-4">
                <div class="w-11 h-11 {{ $bg }} rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">{{ $title }}</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">{{ $desc }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ───────────────────────────────── --}}
<section class="py-14 text-center bg-white" style="border-top:1px solid #E4DCC9;">
    <p class="section-eyebrow mb-3">Work with us</p>
    <h2 class="font-extrabold text-3xl text-gray-900 mb-3" style="font-family:'Merriweather',Georgia,serif;">Ready to start your journey?</h2>
    <p class="text-sm text-gray-500 max-w-sm mx-auto mb-6">Get in touch with our team for a free consultation on tours, visas, or custom itineraries.</p>
    <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ route('contact') }}" class="btn-primary">Contact Us</a>
        <a href="{{ route('packages.index') }}" class="btn-outline">Browse Packages</a>
    </div>
</section>

</x-app-layout>
