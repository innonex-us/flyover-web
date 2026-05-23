<x-app-layout>

<div class="px-4 lg:px-16 py-16 min-h-screen" style="background:#FAF6EE;">
    <div class="max-w-2xl mx-auto">

        {{-- ── BIG CHECKMARK HERO ───────────────────────────────────────── --}}
        <div class="text-center mb-10">
            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full mb-5 shadow-lg" style="background:#1F6E3D;">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
            <h1 class="fb-serif leading-tight" style="font-size:clamp(36px,5vw,56px);color:#18130E;">Booking confirmed!</h1>
            <p class="mt-3 text-base" style="color:#7A7166;">
                Thank you for booking with FlyoverBD. We've received your request and will be in touch shortly.
            </p>
            {{-- Reference number --}}
            <div class="inline-flex items-center gap-3 mt-5 px-5 py-3 rounded-full border border-[#E4DCC9] bg-white">
                <span class="fb-mono" style="color:#7A7166;">Booking ref</span>
                <span class="font-bold text-lg tracking-widest fb-mono" style="color:#C8102E;">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
            </div>
        </div>

        {{-- ── BOOKING SUMMARY CARD ─────────────────────────────────────── --}}
        <div class="ota-card overflow-hidden mb-5">
            {{-- Card header --}}
            <div class="px-6 py-4 border-b border-[#E4DCC9]" style="background:#FAF6EE;">
                <div class="flex items-center justify-between">
                    <h2 class="font-bold text-base" style="color:#18130E;">Booking summary</h2>
                    <span class="ota-tag-green text-xs">{{ ucfirst($booking->payment_status ?? 'Pending') }}</span>
                </div>
            </div>

            <div class="p-6 space-y-5">
                {{-- Customer details --}}
                <div>
                    <p class="fb-eyebrow mb-3">Traveller</p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-8">
                        <div>
                            <p class="fb-mono mb-0.5">Name</p>
                            <p class="font-semibold text-sm" style="color:#18130E;">
                                {{ $booking->user ? $booking->user->name : $booking->guest_name }}
                            </p>
                        </div>
                        <div>
                            <p class="fb-mono mb-0.5">Email</p>
                            <p class="font-semibold text-sm" style="color:#18130E;">
                                {{ $booking->user ? $booking->user->email : $booking->guest_email }}
                            </p>
                        </div>
                        @if(($booking->user ? $booking->user->phone : $booking->guest_phone))
                        <div>
                            <p class="fb-mono mb-0.5">Phone</p>
                            <p class="font-semibold text-sm" style="color:#18130E;">
                                {{ $booking->user ? $booking->user->phone : $booking->guest_phone }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <hr class="border-[#E4DCC9]">

                {{-- Service details --}}
                <div>
                    <p class="fb-eyebrow mb-3">Service</p>
                    @if($booking->payable)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-3 gap-x-8">
                        <div>
                            <p class="fb-mono mb-0.5">Service</p>
                            <p class="font-semibold text-sm" style="color:#18130E;">
                                {{ $booking->payable->title ?? ($booking->payable->country . ' (' . $booking->payable->type . ')') }}
                            </p>
                            <p class="text-xs mt-0.5" style="color:#7A7166;">{{ class_basename($booking->payable_type) }}</p>
                        </div>
                        <div>
                            <p class="fb-mono mb-0.5">Travel date</p>
                            <p class="font-semibold text-sm" style="color:#18130E;">
                                {{ $booking->booking_date->format('d M Y') }}
                            </p>
                        </div>
                        <div>
                            <p class="fb-mono mb-0.5">Travellers / qty</p>
                            <p class="font-semibold text-sm" style="color:#18130E;">{{ $booking->quantity }}</p>
                        </div>
                        <div>
                            <p class="fb-mono mb-0.5">Booked on</p>
                            <p class="font-semibold text-sm" style="color:#18130E;">
                                {{ $booking->created_at->format('d M Y, g:ia') }}
                            </p>
                        </div>
                    </div>
                    @else
                    <p class="text-sm italic" style="color:#7A7166;">Service details unavailable.</p>
                    @endif
                </div>

                <hr class="border-[#E4DCC9]">

                {{-- Total --}}
                <div class="flex items-center justify-between py-2">
                    <div>
                        <p class="fb-mono mb-0.5">Total amount</p>
                        <p class="text-xs" style="color:#7A7166;">Payment status: {{ ucfirst($booking->payment_status ?? 'pending') }}</p>
                    </div>
                    <p class="text-3xl font-bold" style="color:#18130E;">
                        ৳{{ number_format($booking->total_amount ?? $booking->payable->price ?? 0) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ── NEXT STEPS ────────────────────────────────────────────────── --}}
        <div class="ota-card p-6 mb-5">
            <h3 class="font-bold text-base mb-4" style="color:#18130E;">What happens next</h3>
            <ol class="space-y-3.5">
                @foreach([
                    ['Confirmation email','A copy of this booking has been sent to your email address. Check your spam folder if you don\'t see it.'],
                    ['Our team contacts you','Within 2 hours (9am–8pm) one of our team will call or WhatsApp to confirm the details and answer any questions.'],
                    ['Document checklist','For visa and tour bookings, we\'ll send you a checklist of documents to prepare. We\'ll guide you through each one.'],
                    ['Your trip begins','Show up. We handle the rest.'],
                ] as $i => [$step,$desc])
                <li class="flex gap-4">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0 mt-0.5" style="background:#C8102E;">{{ $i+1 }}</span>
                    <div>
                        <p class="font-semibold text-sm" style="color:#18130E;">{{ $step }}</p>
                        <p class="text-sm mt-0.5 leading-relaxed" style="color:#7A7166;">{{ $desc }}</p>
                    </div>
                </li>
                @endforeach
            </ol>
        </div>

        {{-- ── DOWNLOAD / SHARE BUTTONS ─────────────────────────────────── --}}
        <div class="flex flex-col sm:flex-row gap-3 mb-5 print:hidden">
            <button onclick="window.print()"
                class="flex-1 ota-btn-dark py-3.5 justify-center text-sm gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                Download / Print
            </button>
            <button onclick="navigator.share ? navigator.share({title:'FlyoverBD Booking #{{ str_pad($booking->id,6,'0',STR_PAD_LEFT) }}',url:window.location.href}) : navigator.clipboard.writeText(window.location.href)"
                class="flex-1 ota-btn-ghost py-3.5 justify-center text-sm gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
                Share booking
            </button>
        </div>

        {{-- ── NEED HELP CARD ────────────────────────────────────────────── --}}
        <div class="rounded-2xl overflow-hidden border border-[#E4DCC9] mb-8 print:hidden">
            <div class="px-6 py-4 border-b border-[#E4DCC9]" style="background:#18130E;">
                <p class="font-bold text-white text-sm">Need help with this booking?</p>
            </div>
            <div class="p-5 bg-white grid grid-cols-1 sm:grid-cols-2 gap-3">
                <a href="https://wa.me/8801335111370?text=Hi%2C%20I%20have%20a%20question%20about%20booking%20%23{{ str_pad($booking->id,6,'0',STR_PAD_LEFT) }}"
                    target="_blank"
                    class="flex items-center gap-3 px-4 py-3.5 rounded-xl border border-[#E4DCC9] hover:border-[#1F6E3D] transition-colors duration-150">
                    <span class="w-9 h-9 rounded-lg flex items-center justify-center text-white shrink-0" style="background:#1F6E3D;">💬</span>
                    <div>
                        <p class="font-bold text-sm" style="color:#18130E;">WhatsApp us</p>
                        <p class="text-xs" style="color:#7A7166;">+880 1335 111370</p>
                    </div>
                </a>
                <a href="tel:09611677989"
                    class="flex items-center gap-3 px-4 py-3.5 rounded-xl border border-[#E4DCC9] hover:border-[#18130E] transition-colors duration-150">
                    <span class="w-9 h-9 rounded-lg flex items-center justify-center text-white shrink-0" style="background:#18130E;">📞</span>
                    <div>
                        <p class="font-bold text-sm" style="color:#18130E;">Call us</p>
                        <p class="text-xs" style="color:#7A7166;">09611-677989</p>
                    </div>
                </a>
            </div>
        </div>

        {{-- Back to home --}}
        <div class="text-center print:hidden">
            <a href="{{ route('home') }}" class="text-sm font-semibold hover:underline" style="color:#7A7166;">
                ← Return to FlyoverBD home
            </a>
        </div>

        {{-- Print footer --}}
        <div class="hidden print:block text-center text-xs mt-12 pt-8 border-t border-gray-100" style="color:#7A7166;">
            <p class="font-bold" style="color:#18130E;">FlyoverBD — Your trusted travel partner</p>
            <p class="mt-1">{{ url('/') }} · 09611-677989 · info.flyoverbd@gmail.com</p>
            <p class="mt-1">Booking #{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }} · Printed {{ now()->format('d M Y, g:ia') }}</p>
        </div>

    </div>
</div>

</x-app-layout>
