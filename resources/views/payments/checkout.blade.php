<x-app-layout>
    <x-slot name="title">Complete Payment - FlyoverBD</x-slot>

    <div class="min-h-screen py-12" style="background:#F9F6EF;">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Header --}}
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Complete Your Payment</h1>
                <p class="text-gray-500 mt-2 text-sm">Review your booking and choose a payment method.</p>
            </div>

            {{-- Session Errors --}}
            @if (session('error'))
                <div class="mb-6 rounded-2xl bg-red-50 border border-red-200 px-5 py-4 text-sm font-semibold text-red-700 flex items-center gap-3">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">

                {{-- Booking Summary --}}
                <div class="px-8 pt-8 pb-6 border-b border-gray-100">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-5">Booking Summary</h2>

                    @php
                        $booking = $payment->payable;
                    @endphp

                    {{-- Tours / Visa Booking --}}
                    @if ($payment->payable_type === \App\Models\Booking::class)
                        @php
                            $service = $booking->payable;
                            $serviceLabel = $service instanceof \App\Models\Package ? 'Tour Package' : 'Visa Service';
                            $serviceName  = $service->title ?? ($service->country . ' (' . $service->type . ')');
                        @endphp
                        <div class="flex items-start gap-4">
                            @if ($service?->thumbnail)
                                <img src="{{ asset('storage/' . $service->thumbnail) }}" alt="{{ $serviceName }}"
                                     class="w-20 h-16 object-cover rounded-xl shrink-0">
                            @else
                                <div class="w-20 h-16 rounded-xl bg-red-50 flex items-center justify-center shrink-0">
                                    <svg class="w-8 h-8 text-red-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064"/></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-red-600 bg-red-50 px-2 py-0.5 rounded-full mb-1">{{ $serviceLabel }}</span>
                                <p class="text-base font-bold text-gray-900 leading-tight">{{ $serviceName }}</p>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-sm text-gray-500">
                                    <span>Travel: <strong class="text-gray-700">{{ \Carbon\Carbon::parse($booking->booking_date)->format('M d, Y') }}</strong></span>
                                    <span>Persons: <strong class="text-gray-700">{{ $booking->quantity }}</strong></span>
                                </div>
                            </div>
                        </div>

                    {{-- Hotel Booking --}}
                    @elseif ($payment->payable_type === \App\Models\HotelBooking::class)
                        @php
                            $room  = $booking->room;
                            $hotel = $room?->hotel;
                        @endphp
                        <div class="flex items-start gap-4">
                            @if ($hotel?->thumbnail)
                                <img src="{{ asset('storage/' . $hotel->thumbnail) }}" alt="{{ $hotel->name }}"
                                     class="w-20 h-16 object-cover rounded-xl shrink-0">
                            @else
                                <div class="w-20 h-16 rounded-xl bg-purple-50 flex items-center justify-center shrink-0">
                                    <svg class="w-8 h-8 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                </div>
                            @endif
                            <div class="flex-1 min-w-0">
                                <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-purple-600 bg-purple-50 px-2 py-0.5 rounded-full mb-1">Hotel Booking</span>
                                <p class="text-base font-bold text-gray-900 leading-tight">{{ $hotel?->name ?? 'Hotel' }}</p>
                                <p class="text-sm text-gray-500 mt-0.5">{{ $room?->name }}</p>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-sm text-gray-500">
                                    <span>Check-in: <strong class="text-gray-700">{{ $booking->check_in->format('M d, Y') }}</strong></span>
                                    <span>Check-out: <strong class="text-gray-700">{{ $booking->check_out->format('M d, Y') }}</strong></span>
                                    <span>{{ $booking->nights }} night{{ $booking->nights > 1 ? 's' : '' }}</span>
                                    <span>{{ $booking->guests }} guest{{ $booking->guests > 1 ? 's' : '' }}</span>
                                </div>
                            </div>
                        </div>

                    {{-- Transfer / Pik & Drop Booking --}}
                    @elseif ($payment->payable_type === \App\Models\TransferBooking::class)
                        <div class="flex items-start gap-4">
                            <div class="w-20 h-16 rounded-xl bg-green-50 flex items-center justify-center shrink-0">
                                <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-green-600 bg-green-50 px-2 py-0.5 rounded-full mb-1">Pik & Drop</span>
                                <p class="text-base font-bold text-gray-900 leading-tight">
                                    {{ $booking->pickup_location }} → {{ $booking->drop_location }}
                                </p>
                                <div class="flex flex-wrap gap-x-4 gap-y-1 mt-2 text-sm text-gray-500">
                                    <span>Date: <strong class="text-gray-700">{{ \Carbon\Carbon::parse($booking->travel_date)->format('M d, Y') }}</strong></span>
                                    @if ($booking->pickup_time)
                                        <span>Time: <strong class="text-gray-700">{{ $booking->pickup_time }}</strong></span>
                                    @endif
                                    <span>Passengers: <strong class="text-gray-700">{{ $booking->passenger_count }}</strong></span>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- Customer --}}
                    @php
                        $customerName  = $booking->user?->name  ?? $booking->guest_name;
                        $customerEmail = $booking->user?->email ?? $booking->guest_email;
                    @endphp
                    <div class="mt-5 pt-5 border-t border-gray-100 flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-gray-800">{{ $customerName }}</p>
                            <p class="text-xs text-gray-400">{{ $customerEmail }}</p>
                        </div>
                    </div>
                </div>

                {{-- Amount --}}
                <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
                    @php
                        $amount = $payment->amount;
                    @endphp
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-semibold text-gray-500">Total Amount</span>
                        <span class="text-2xl font-black text-gray-900">৳{{ number_format($amount, 0) }}</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-1 text-right">{{ $payment->currency }}</p>
                </div>

                {{-- Payment Methods --}}
                <div class="px-8 py-7">
                    <h2 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-5">Choose Payment Method</h2>

                    {{-- bKash --}}
                    <a href="{{ route('payments.bkash.start', $payment) }}"
                       class="group flex items-center justify-between w-full px-5 py-4 rounded-2xl border-2 border-[#E2136E] bg-white hover:bg-[#E2136E] transition-all duration-200 shadow-sm hover:shadow-md mb-3">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-[#E2136E] group-hover:bg-white flex items-center justify-center transition-colors duration-200 shrink-0">
                                <span class="text-white group-hover:text-[#E2136E] font-black text-lg leading-none transition-colors duration-200">b</span>
                            </div>
                            <div class="text-left">
                                <p class="text-sm font-bold text-gray-900 group-hover:text-white transition-colors duration-200">Pay with bKash</p>
                                <p class="text-xs text-gray-400 group-hover:text-pink-100 transition-colors duration-200">Secure mobile banking payment</p>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-[#E2136E] group-hover:text-white transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>

                    <p class="text-center text-xs text-gray-400 mt-4">
                        You will be redirected to bKash to complete payment securely.
                    </p>
                </div>

                {{-- Footer --}}
                <div class="px-8 pb-7 pt-2 text-center">
                    <a href="{{ route('home') }}" class="text-sm text-gray-400 hover:text-red-600 transition font-medium">
                        ← Cancel and return to home
                    </a>
                </div>

            </div>

            {{-- Security note --}}
            <div class="mt-5 flex items-center justify-center gap-2 text-xs text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                Payments are processed securely. FlyoverBD does not store card details.
            </div>

        </div>
    </div>
</x-app-layout>
