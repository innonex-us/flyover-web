<x-app-layout
    title="Contact Us | FlyoverBD"
    meta_description="Get in touch with FlyoverBD for visa inquiries, tour packages, or travel consultation. We're available 24/7 on WhatsApp and phone."
>

{{-- ── Hero ─────────────────────────────── --}}
<section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="px-4 py-14 text-center">
    <p class="section-eyebrow mb-3">We're here to help</p>
    <h1 class="font-extrabold text-3xl sm:text-4xl md:text-5xl mb-3" style="font-family:'Merriweather',Georgia,serif;color:#18130E;">
        Contact Us
    </h1>
    <p class="max-w-md mx-auto text-sm" style="color:#7A7166;">Have questions about your next trip or visa application? Our travel experts are ready to help.</p>
</section>

{{-- ── Main ─────────────────────────────── --}}
<section class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 lg:grid-cols-5 gap-10">

        {{-- Left: info + map --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Contact cards --}}
            <div class="bg-gray-50 rounded-2xl border border-gray-100 p-6 space-y-6">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Office</p>
                        <p class="text-sm text-gray-700 font-medium leading-snug">House 45, Road 13, Block D<br>Banani, Dhaka, Bangladesh</p>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Phone</p>
                        <a href="tel:09611677989" class="block text-sm font-medium text-gray-700 hover:text-red-600 transition">09611-677989</a>
                        <a href="tel:+8801335111370" class="block text-sm font-medium text-gray-700 hover:text-red-600 transition">+880 1335-111370</a>
                    </div>
                </div>
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-1">Email</p>
                        <a href="mailto:info.flyoverbd@gmail.com" class="block text-sm font-medium text-gray-700 hover:text-red-600 transition">info.flyoverbd@gmail.com</a>
                        <a href="mailto:info@flyoverbd.net" class="block text-sm font-medium text-gray-700 hover:text-red-600 transition">info@flyoverbd.net</a>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-100">
                    <a href="https://wa.me/8801335111370" target="_blank"
                       class="flex items-center justify-center gap-2 w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 rounded-xl transition text-sm">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        Chat on WhatsApp
                    </a>
                </div>
            </div>

            {{-- Map --}}
            <div class="rounded-2xl overflow-hidden border border-gray-100 shadow-sm" style="height:220px;">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.318322300062!2d90.40393457597148!3d23.79242968659107!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c70c0c609053%3A0xc48641477727382b!2sBanani%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1705574400000!5m2!1sen!2sbd"
                        width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>

        {{-- Right: form --}}
        <div class="lg:col-span-3">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-8">





                <h2 class="font-bold text-xl text-gray-900 mb-6">Send us a Message</h2>

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="fb-field-label mb-1.5">First Name</label>
                            <input type="text" name="first_name" value="{{ old('first_name') }}" class="fb-input" required>
                        </div>
                        <div>
                            <label class="fb-field-label mb-1.5">Last Name</label>
                            <input type="text" name="last_name" value="{{ old('last_name') }}" class="fb-input" required>
                        </div>
                    </div>
                    <div>
                        <label class="fb-field-label mb-1.5">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="fb-input" required>
                    </div>
                    <div>
                        <label class="fb-field-label mb-1.5">Phone Number</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="fb-input" required>
                    </div>
                    <div>
                        <label class="fb-field-label mb-1.5">Subject</label>
                        <select name="subject" class="fb-input">
                            <option {{ old('subject') === 'Visa Inquiry' ? 'selected' : '' }}>Visa Inquiry</option>
                            <option {{ old('subject') === 'Tour Package' ? 'selected' : '' }}>Tour Package</option>
                            <option {{ old('subject') === 'Custom Trip' ? 'selected' : '' }}>Custom Trip</option>
                            <option {{ old('subject') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="fb-field-label mb-1.5">Message</label>
                        <textarea name="message" rows="5" class="fb-input resize-none" required>{{ old('message') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full py-4 text-base">
                        Send Message
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

</x-app-layout>
