<x-admin-layout pageTitle="Payment Detail">

    <div class="mb-6">
        <a href="{{ route('admin.payments.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-semibold text-gray-500 hover:text-red-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Payment History
        </a>
    </div>

    @php
        $booking = $payment->payable;
        $customerName  = $booking?->user?->name  ?? $booking?->guest_name  ?? '—';
        $customerEmail = $booking?->user?->email ?? $booking?->guest_email ?? '—';
        $customerPhone = $booking?->user?->phone ?? $booking?->guest_phone ?? '—';

        $statusClass = match ($payment->status) {
            'paid'      => 'bg-green-100 text-green-700 border-green-200',
            'pending'   => 'bg-yellow-100 text-yellow-700 border-yellow-200',
            'failed'    => 'bg-red-100 text-red-700 border-red-200',
            'cancelled' => 'bg-gray-100 text-gray-500 border-gray-200',
            default     => 'bg-gray-100 text-gray-600 border-gray-200',
        };

        $serviceType = match ($payment->payable_type) {
            \App\Models\Booking::class         => 'Tour / Visa Booking',
            \App\Models\HotelBooking::class    => 'Hotel Booking',
            \App\Models\TransferBooking::class => 'Pik & Drop Booking',
            default                            => 'Unknown',
        };
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Main Detail --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Payment Info --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-700">Payment #{{ $payment->id }}</h2>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                <div class="divide-y divide-gray-50">
                    @php
                        $rows = [
                            ['Gateway',        ucfirst($payment->gateway)],
                            ['Amount',         '৳' . number_format($payment->amount, 2) . ' ' . $payment->currency],
                            ['Payment Token',  $payment->public_token],
                            ['Invoice No.',    $payment->merchant_invoice_number ?? '—'],
                            ['Gateway Pay ID', $payment->gateway_payment_id      ?? '—'],
                            ['Transaction ID', $payment->gateway_transaction_id  ?? '—'],
                        ];
                    @endphp
                    @foreach ($rows as [$label, $value])
                    <div class="px-6 py-3.5 flex items-center justify-between gap-4">
                        <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider shrink-0">{{ $label }}</span>
                        <span class="text-sm text-gray-800 font-medium text-right break-all">{{ $value }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Timeline --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-700">Timeline</h2>
                </div>
                <div class="px-6 py-4 space-y-3">
                    @php
                        $events = [
                            ['Created',     $payment->created_at,    'text-gray-400'],
                            ['Initiated',   $payment->initiated_at,  'text-blue-500'],
                            ['Executed',    $payment->executed_at,   'text-indigo-500'],
                            ['Completed',   $payment->completed_at,  'text-green-500'],
                            ['Failed',      $payment->failed_at,     'text-red-500'],
                            ['Cancelled',   $payment->cancelled_at,  'text-gray-400'],
                        ];
                    @endphp
                    @foreach ($events as [$label, $ts, $color])
                        @if ($ts)
                        <div class="flex items-center gap-3">
                            <span class="w-2 h-2 rounded-full {{ $color }} shrink-0" style="background:currentColor"></span>
                            <span class="text-xs font-semibold text-gray-500 w-24 shrink-0">{{ $label }}</span>
                            <span class="text-xs text-gray-700">{{ $ts->format('M d, Y  H:i:s') }}</span>
                        </div>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Booking Detail --}}
            @if ($booking)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-700">{{ $serviceType }}</h2>
                    @php
                        $bookingRoute = match ($payment->payable_type) {
                            \App\Models\Booking::class         => route('admin.bookings.show', $booking),
                            \App\Models\HotelBooking::class    => route('admin.hotel-bookings.show', $booking),
                            \App\Models\TransferBooking::class => route('admin.transfer-bookings.show', $booking),
                            default                            => null,
                        };
                    @endphp
                    @if ($bookingRoute)
                    <a href="{{ $bookingRoute }}"
                       class="text-xs font-semibold text-red-600 hover:text-red-800 transition flex items-center gap-1">
                        View Booking
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                    @endif
                </div>
                <div class="divide-y divide-gray-50">

                    {{-- Tour / Visa --}}
                    @if ($payment->payable_type === \App\Models\Booking::class)
                        @php $service = $booking->payable; @endphp
                        @foreach ([
                            ['Service', $service?->title ?? ($service?->country . ' (' . $service?->type . ')')],
                            ['Travel Date', $booking->booking_date?->format('M d, Y')],
                            ['Persons', $booking->quantity],
                            ['Status', ucfirst($booking->status ?? '—')],
                            ['Payment Status', ucfirst($booking->payment_status ?? '—')],
                        ] as [$label, $value])
                        <div class="px-6 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider shrink-0">{{ $label }}</span>
                            <span class="text-sm text-gray-800 font-medium text-right">{{ $value }}</span>
                        </div>
                        @endforeach

                    {{-- Hotel --}}
                    @elseif ($payment->payable_type === \App\Models\HotelBooking::class)
                        @php $hotel = $booking->room?->hotel; @endphp
                        @foreach ([
                            ['Hotel',    $hotel?->name ?? '—'],
                            ['Room',     $booking->room?->name ?? '—'],
                            ['Check-in', $booking->check_in?->format('M d, Y')],
                            ['Check-out',$booking->check_out?->format('M d, Y')],
                            ['Nights',   $booking->nights],
                            ['Guests',   $booking->guests],
                            ['Status',   ucfirst($booking->status ?? '—')],
                        ] as [$label, $value])
                        <div class="px-6 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider shrink-0">{{ $label }}</span>
                            <span class="text-sm text-gray-800 font-medium text-right">{{ $value }}</span>
                        </div>
                        @endforeach

                    {{-- Transfer --}}
                    @elseif ($payment->payable_type === \App\Models\TransferBooking::class)
                        @foreach ([
                            ['Pickup',      $booking->pickup_location],
                            ['Drop',        $booking->drop_location],
                            ['Travel Date', $booking->travel_date?->format('M d, Y')],
                            ['Time',        $booking->pickup_time ?? '—'],
                            ['Passengers',  $booking->passenger_count],
                            ['Status',      ucfirst($booking->status ?? '—')],
                        ] as [$label, $value])
                        <div class="px-6 py-3.5 flex items-center justify-between gap-4">
                            <span class="text-xs font-semibold text-gray-400 uppercase tracking-wider shrink-0">{{ $label }}</span>
                            <span class="text-sm text-gray-800 font-medium text-right">{{ $value }}</span>
                        </div>
                        @endforeach
                    @endif

                </div>
            </div>
            @endif

        </div>

        {{-- Sidebar --}}
        <div class="space-y-5">

            {{-- Customer --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-700">Customer</h2>
                </div>
                <div class="px-6 py-4 space-y-2">
                    <p class="text-sm font-bold text-gray-900">{{ $customerName }}</p>
                    <p class="text-xs text-gray-500">{{ $customerEmail }}</p>
                    <p class="text-xs text-gray-500">{{ $customerPhone }}</p>
                    @if ($booking?->user)
                        <a href="{{ route('admin.users.index') }}?search={{ $booking->user->email }}"
                           class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:text-red-800 transition mt-1">
                            View User Profile
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-500 mt-1">Guest</span>
                    @endif
                </div>
            </div>

            {{-- Amount Summary --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h2 class="text-sm font-bold text-gray-700">Amount</h2>
                </div>
                <div class="px-6 py-5 text-center">
                    <p class="text-3xl font-black text-gray-900">৳{{ number_format($payment->amount, 0) }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ $payment->currency }}</p>
                    <span class="inline-flex items-center mt-3 px-3 py-1 rounded-full text-xs font-bold border {{ $statusClass }}">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
            </div>

            {{-- Raw Gateway Payload (collapsible) --}}
            @if ($payment->response_payload)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden" x-data="{ open: false }">
                <button @click="open = !open"
                        class="w-full px-6 py-4 border-b border-gray-100 flex items-center justify-between text-sm font-bold text-gray-700 hover:text-red-600 transition">
                    Gateway Response
                    <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-90' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </button>
                <div x-show="open" x-cloak class="px-6 py-4">
                    <pre class="text-[10px] text-gray-600 bg-gray-50 rounded-lg p-3 overflow-x-auto max-h-60">{{ json_encode($payment->response_payload, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>
            @endif

        </div>

    </div>

</x-admin-layout>
