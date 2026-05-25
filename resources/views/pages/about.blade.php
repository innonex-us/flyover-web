<x-app-layout
    title="About FlyoverBD | Bangladesh's Trusted Travel Agency"
    meta_description="FlyoverBD is a premier travel agency in Dhaka offering visa processing, tour packages, and expert travel consulting since 2016."
>

{{-- ── Hero ─────────────────────────────── --}}
<section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="px-4 py-16 text-center">
    <p class="section-eyebrow mb-3">Est. 2016 · Dhaka, Bangladesh</p>
    <h1 class="font-extrabold text-3xl sm:text-4xl md:text-5xl mb-4" style="font-family:'Merriweather',Georgia,serif;color:#18130E;">
        About FlyoverBD
    </h1>
    <p class="max-w-xl mx-auto text-base leading-relaxed" style="color:#7A7166;">
        Bangladesh's trusted travel partner for visa processing, tour packages, hotel bookings, and airport transfers — all under one roof.
    </p>
</section>

{{-- ── Who We Are ───────────────────────── --}}
<section class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-2 gap-12 items-start">
        <div>
            <p class="section-eyebrow mb-3">Who We Are</p>
            <h2 class="font-extrabold text-2xl sm:text-3xl text-gray-900 mb-5" style="font-family:'Merriweather',Georgia,serif;">A Travel Agency Built Around You</h2>
            <div class="space-y-4 text-gray-600 leading-relaxed text-sm md:text-base">
                <p>FlyoverBD is a full-service travel consultancy based in Banani, Dhaka. We started with a simple goal: remove the frustration from travel planning. Today we handle everything from visa applications to complete holiday packages for thousands of Bangladeshis every year.</p>
                <p>Whether you're a first-time traveller or a frequent flyer, a family planning a vacation or a student applying for a study visa — our experienced team is here to make it smooth, transparent, and stress-free.</p>
            </div>

            <div class="mt-8 grid grid-cols-2 gap-4">
                @php $quickStats = [
                    [$siteStats['travellers'], 'Happy Travellers'],
                    [$siteStats['destinations'], 'Destinations Covered'],
                    [($siteStats['visa_total'] > 0 ? number_format($siteStats['visa_total']).'+' : '5,000+'), 'Visas Processed'],
                    [$siteStats['visa_approval'].'%', 'Visa Approval Rate'],
                ]; @endphp
                @foreach($quickStats as [$n, $l])
                <div class="border border-gray-100 rounded-xl p-4 bg-gray-50">
                    <p class="font-extrabold text-2xl text-gray-900 mb-0.5" style="color:#C8102E;">{{ $n }}</p>
                    <p class="text-xs text-gray-500 font-medium">{{ $l }}</p>
                </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-4">
            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-6 flex items-start gap-4">
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Our Mission</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">To make global travel accessible to every Bangladeshi through accurate information, transparent pricing, and dependable service.</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-6 flex items-start gap-4">
                <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Our Vision</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">To be Bangladesh's most trusted one-stop travel platform, recognised for integrity, efficiency, and genuine care for every traveller.</p>
                </div>
            </div>
            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-6 flex items-start gap-4">
                <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 mb-1">Our Promise</h3>
                    <p class="text-sm text-gray-500 leading-relaxed">No hidden fees. No guesswork. Just clear guidance, honest timelines, and a team that treats your trip like their own.</p>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ── What We Do ────────────────────────── --}}
<section style="background:#F9F6EF;border-top:1px solid #E4DCC9;border-bottom:1px solid #E4DCC9;" class="py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-10">
            <p class="section-eyebrow mb-2">Our Services</p>
            <h2 class="font-extrabold text-2xl sm:text-3xl text-gray-900" style="font-family:'Merriweather',Georgia,serif;">Everything Travel, Under One Roof</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @php $services = [
                ['icon'=>'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0H6','bg'=>'bg-red-50','ic'=>'text-red-600','title'=>'Visa Processing','desc'=>'Tourist, student, business, and family visas for 50+ countries. Fast, accurate, hassle-free.'],
                ['icon'=>'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z','bg'=>'bg-blue-50','ic'=>'text-blue-600','title'=>'Tour Packages','desc'=>'Curated domestic and international packages for individuals, couples, families, and corporate groups.'],
                ['icon'=>'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4','bg'=>'bg-purple-50','ic'=>'text-purple-600','title'=>'Hotel Bookings','desc'=>'Verified hotels across popular destinations with competitive rates and flexible cancellation options.'],
                ['icon'=>'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4','bg'=>'bg-green-50','ic'=>'text-green-600','title'=>'Airport Transfers','desc'=>'Reliable pick-and-drop service between airports and hotels at transparent fixed rates.'],
            ]; @endphp
            @foreach($services as $s)
            <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-md transition">
                <div class="w-10 h-10 {{ $s['bg'] }} rounded-xl flex items-center justify-center mb-4">
                    <svg class="w-5 h-5 {{ $s['ic'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">{{ $s['title'] }}</h3>
                <p class="text-sm text-gray-500 leading-relaxed">{{ $s['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Why Choose Us ─────────────────────── --}}
<section class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="mb-10">
            <p class="section-eyebrow mb-2">Why FlyoverBD</p>
            <h2 class="font-extrabold text-2xl sm:text-3xl text-gray-900" style="font-family:'Merriweather',Georgia,serif;">What Sets Us Apart</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @php $reasons = [
                ['M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'High Visa Approval Rate', 'Our careful document review and embassy-specific expertise gives every application the best chance of success.'],
                ['M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z', 'Fast Turnaround', 'We know your time matters. Applications are submitted promptly and you\'re kept informed at every stage.'],
                ['M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z', 'Transparent Fees', 'You\'ll always know exactly what you\'re paying for. No surprise charges, no vague pricing.'],
                ['M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z', 'Responsive Support', 'Get answers via phone, email, or WhatsApp. Our team is available when you need us most.'],
            ]; @endphp
            @foreach($reasons as [$icon, $title, $desc])
            <div class="flex items-start gap-4 p-5 rounded-2xl border border-gray-100 bg-gray-50 hover:border-gray-200 transition">
                <div class="w-10 h-10 bg-white border border-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm">
                    <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
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

{{-- ── Find Us ───────────────────────────── --}}
<section style="background:#F9F6EF;border-top:1px solid #E4DCC9;" class="py-14">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-5 gap-10 items-start">
        <div class="lg:col-span-2">
            <p class="section-eyebrow mb-3">Find Us</p>
            <h2 class="font-extrabold text-2xl text-gray-900 mb-5" style="font-family:'Merriweather',Georgia,serif;">Visit Our Office</h2>
            <div class="space-y-5">
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-0.5">Address</p>
                        <p class="text-sm text-gray-700 leading-snug">House 45, Road 13, Block D<br>Banani, Dhaka 1213</p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-0.5">Phone</p>
                        <a href="tel:09611677989" class="block text-sm text-gray-700 hover:text-red-600 transition">09611-677989</a>
                        <a href="tel:+8801335111370" class="block text-sm text-gray-700 hover:text-red-600 transition">+880 1335-111370</a>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-0.5">Email</p>
                        <a href="mailto:info@flyoverbd.net" class="block text-sm text-gray-700 hover:text-red-600 transition">info@flyoverbd.net</a>
                        <a href="mailto:info.flyoverbd@gmail.com" class="block text-sm text-gray-700 hover:text-red-600 transition">info.flyoverbd@gmail.com</a>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="w-9 h-9 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-0.5">Office Hours</p>
                        <p class="text-sm text-gray-700">Saturday – Thursday: 9am – 7pm</p>
                        <p class="text-sm text-gray-500">Friday: Closed</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-3 rounded-2xl overflow-hidden border border-gray-200 shadow-sm" style="height:340px;">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.318322300062!2d90.40393457597148!3d23.79242968659107!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c70c0c609053%3A0xc48641477727382b!2sBanani%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1705574400000!5m2!1sen!2sbd"
                    width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
    </div>
</section>

{{-- ── CTA ────────────────────────────────── --}}
<section class="py-14 text-center bg-white" style="border-top:1px solid #E4DCC9;">
    <p class="section-eyebrow mb-3">Get in touch</p>
    <h2 class="font-extrabold text-2xl sm:text-3xl text-gray-900 mb-3" style="font-family:'Merriweather',Georgia,serif;">Ready to Plan Your Trip?</h2>
    <p class="text-sm text-gray-500 max-w-sm mx-auto mb-6">Talk to our team for a free consultation on visa requirements, tour packages, or custom itineraries.</p>
    <div class="flex flex-wrap justify-center gap-3">
        <a href="{{ route('contact') }}" class="btn-primary">Contact Us</a>
        <a href="{{ route('packages.index') }}" class="btn-outline">Browse Packages</a>
    </div>
</section>

</x-app-layout>
