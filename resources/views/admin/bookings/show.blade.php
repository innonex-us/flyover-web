<x-admin-layout>

    {{-- Page header --}}
    <div class="mb-6 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('admin.bookings.index') }}"
               class="inline-flex items-center gap-1.5 text-sm transition hover:opacity-70"
               style="color:#6B7280;">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Bookings
            </a>
            <div class="mt-3 flex items-center gap-3">
                <h1 class="text-2xl font-bold" style="color:#0F1419;">
                    Booking #{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}
                </h1>
                @php
                    $headerPill = match($booking->status) {
                        'confirmed'  => 'background:#D1FAE5;color:#065F46;',
                        'cancelled'  => 'background:#FEE2E2;color:#991B1B;',
                        'completed'  => 'background:#D1FAE5;color:#065F46;',
                        default      => 'background:#FEF3C7;color:#92400E;',
                    };
                @endphp
                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                      style="{{ $headerPill }}">
                    {{ ucfirst($booking->status) }}
                </span>
            </div>
        </div>

        {{-- Delete --}}
        <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST"
              onsubmit="return confirm('Delete this booking permanently? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <button type="submit"
                    class="inline-flex items-center gap-1.5 rounded-lg border px-4 py-2 text-sm font-medium transition hover:opacity-80"
                    style="border-color:#FEE2E2; color:#991B1B; background:#FEF2F2;">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                </svg>
                Delete Booking
            </button>
        </form>
    </div>

    {{-- Two-column layout --}}
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

        {{-- LEFT — details (2/3) --}}
        <div class="space-y-6 lg:col-span-2">

            {{-- Customer Information --}}
            <div class="rounded-xl p-6" style="background:#fff; border:1px solid #E6E8EC;">
                <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Customer Information</p>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">

                    <div>
                        <p class="mb-0.5 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Name</p>
                        <p class="font-medium" style="color:#0F1419;">
                            {{ $booking->user ? $booking->user->name : $booking->guest_name }}
                            @unless($booking->user)
                                <span class="ml-1 inline-block rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase"
                                      style="background:#F3F4F6; color:#6B7280;">Guest</span>
                            @endunless
                        </p>
                    </div>

                    <div>
                        <p class="mb-0.5 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Email</p>
                        <p class="font-medium" style="color:#0F1419;">
                            {{ $booking->user ? $booking->user->email : $booking->guest_email }}
                        </p>
                    </div>

                    <div>
                        <p class="mb-0.5 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Phone</p>
                        <p class="font-medium" style="color:#0F1419;">
                            {{ $booking->user ? ($booking->user->phone ?? '—') : ($booking->guest_phone ?? '—') }}
                        </p>
                    </div>

                    <div>
                        <p class="mb-0.5 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Account Type</p>
                        <p class="font-medium" style="color:#0F1419;">
                            {{ $booking->user ? 'Registered User' : 'Guest Checkout' }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Package / Service Information --}}
            <div class="rounded-xl p-6" style="background:#fff; border:1px solid #E6E8EC;">
                <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Package / Service</p>

                @if($booking->payable)
                    <div class="flex items-start gap-5">
                        @if(isset($booking->payable->thumbnail) && $booking->payable->thumbnail)
                            <img src="{{ Storage::url($booking->payable->thumbnail) }}"
                                 alt="Service thumbnail"
                                 class="h-20 w-28 flex-shrink-0 rounded-lg object-cover"
                                 style="border:1px solid #E6E8EC;">
                        @endif
                        <div class="flex-1">
                            <p class="text-base font-bold" style="color:#0F1419;">
                                {{ $booking->payable->title ?? ($booking->payable->country . ' — ' . $booking->payable->type) }}
                            </p>
                            <p class="mb-3 text-xs uppercase tracking-wider" style="color:#6B7280;">
                                {{ class_basename($booking->payable_type) }}
                            </p>
                            <div class="grid grid-cols-2 gap-3 text-sm sm:grid-cols-3">
                                <div class="rounded-lg p-3 text-center" style="background:#F4F5F7;">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Booking Date</p>
                                    <p class="mt-1 font-semibold" style="color:#0F1419;">{{ $booking->booking_date->format('M d, Y') }}</p>
                                </div>
                                <div class="rounded-lg p-3 text-center" style="background:#F4F5F7;">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Persons</p>
                                    <p class="mt-1 font-semibold" style="color:#0F1419;">{{ $booking->quantity ?? '—' }}</p>
                                </div>
                                <div class="rounded-lg p-3 text-center" style="background:#F4F5F7;">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Unit Price</p>
                                    <p class="mt-1 font-semibold" style="color:#0F1419;">৳{{ number_format($booking->payable->price ?? 0) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="rounded-lg p-4 text-sm" style="background:#FEF2F2; color:#991B1B; border:1px solid #FEE2E2;">
                        The linked service has been deleted from the system.
                    </div>
                @endif
            </div>

            {{-- Travel Details & Special Requests --}}
            <div class="rounded-xl p-6" style="background:#fff; border:1px solid #E6E8EC;">
                <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Travel Details &amp; Requests</p>

                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                    {{-- PAX breakdown --}}
                    @if($booking->details)
                    <div>
                        <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Group Composition</p>
                        <div class="flex flex-wrap gap-3">
                            @foreach(['adults' => 'Adults', 'children' => 'Children', 'infants' => 'Infants'] as $key => $label)
                                @if(isset($booking->details[$key]))
                                <div class="rounded-lg px-4 py-3 text-center" style="background:#F4F5F7; min-width:72px;">
                                    <p class="text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">{{ $label }}</p>
                                    <p class="mt-1 text-lg font-bold" style="color:#0F1419;">{{ $booking->details[$key] }}</p>
                                </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- Special requests / notes --}}
                    <div>
                        <p class="mb-3 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Special Requests</p>
                        @if($booking->notes)
                            <div class="rounded-lg p-4 text-sm italic" style="background:#FEF3C7; color:#92400E; border:1px solid #FDE68A;">
                                {{ $booking->notes }}
                            </div>
                        @else
                            <p class="text-sm" style="color:#6B7280;">No special requests.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>

        {{-- RIGHT — status + payment (1/3) --}}
        <div class="space-y-6">

            {{-- Update Status card --}}
            <div class="rounded-xl p-6" style="background:#fff; border:1px solid #E6E8EC;">
                <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Update Status</p>

                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    {{-- Booking status --}}
                    <div>
                        <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                            Booking Status
                        </label>
                        <select name="status"
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                style="border-color:#E6E8EC; color:#0F1419;">
                            @foreach(['pending','confirmed','completed','cancelled'] as $s)
                                <option value="{{ $s }}" {{ $booking->status == $s ? 'selected' : '' }}>
                                    {{ ucfirst($s) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Payment status --}}
                    <div>
                        <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                            Payment Status
                        </label>
                        <select name="payment_status"
                                class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                style="border-color:#E6E8EC; color:#0F1419;">
                            @foreach(['unpaid','partial','paid','refunded'] as $ps)
                                <option value="{{ $ps }}" {{ ($booking->payment_status ?? 'unpaid') == $ps ? 'selected' : '' }}>
                                    {{ ucfirst($ps) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Admin notes --}}
                    <div>
                        <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                            Admin Notes
                        </label>
                        <textarea name="notes" rows="3"
                                  placeholder="Internal notes (not visible to customer)…"
                                  class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                  style="border-color:#E6E8EC; color:#0F1419;">{{ $booking->notes }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full rounded-lg py-2.5 text-sm font-semibold text-white transition hover:opacity-90 focus:outline-none focus:ring-2"
                            style="background:#C8102E;">
                        Save Changes
                    </button>
                </form>
            </div>

            {{-- Payment Summary card --}}
            <div class="rounded-xl p-6" style="background:#fff; border:1px solid #E6E8EC;">
                <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Payment Summary</p>

                <div class="space-y-3 text-sm">
                    @if($booking->payable)
                    <div class="flex justify-between">
                        <span style="color:#6B7280;">Unit price</span>
                        <span style="color:#0F1419;">৳{{ number_format($booking->payable->price ?? 0) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:#6B7280;">Persons</span>
                        <span style="color:#0F1419;">× {{ $booking->quantity ?? 1 }}</span>
                    </div>
                    @endif

                    @if(isset($booking->details['adults']) || isset($booking->details['children']))
                    <div style="border-top:1px solid #E6E8EC;" class="pt-3">
                        @if(isset($booking->details['adults']) && $booking->details['adults'] > 0)
                        <div class="flex justify-between">
                            <span style="color:#6B7280;">Adults</span>
                            <span style="color:#0F1419;">{{ $booking->details['adults'] }}</span>
                        </div>
                        @endif
                        @if(isset($booking->details['children']) && $booking->details['children'] > 0)
                        <div class="flex justify-between mt-1">
                            <span style="color:#6B7280;">Children</span>
                            <span style="color:#0F1419;">{{ $booking->details['children'] }}</span>
                        </div>
                        @endif
                        @if(isset($booking->details['infants']) && $booking->details['infants'] > 0)
                        <div class="flex justify-between mt-1">
                            <span style="color:#6B7280;">Infants</span>
                            <span style="color:#0F1419;">{{ $booking->details['infants'] }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="flex justify-between rounded-lg px-3 py-3 text-base font-bold"
                         style="border-top:2px solid #E6E8EC; margin-top:8px; color:#0F1419;">
                        <span>Total</span>
                        <span style="color:#C8102E;">৳{{ number_format($booking->total_amount) }}</span>
                    </div>
                </div>

                {{-- Payment status badge --}}
                <div class="mt-4 flex items-center justify-between rounded-lg px-3 py-2" style="background:#F4F5F7;">
                    <span class="text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Payment</span>
                    @php
                        $payBadge = match($booking->payment_status ?? 'unpaid') {
                            'paid'     => 'background:#D1FAE5;color:#065F46;',
                            'partial'  => 'background:#FEF3C7;color:#92400E;',
                            'refunded' => 'background:#FEE2E2;color:#991B1B;',
                            default    => 'background:#F3F4F6;color:#6B7280;',
                        };
                    @endphp
                    <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                          style="{{ $payBadge }}">
                        {{ ucfirst($booking->payment_status ?? 'Unpaid') }}
                    </span>
                </div>
            </div>

            {{-- Quick meta --}}
            <div class="rounded-xl p-6" style="background:#fff; border:1px solid #E6E8EC;">
                <p class="mb-4 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Timeline</p>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span style="color:#6B7280;">Booked on</span>
                        <span style="color:#0F1419;">{{ $booking->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:#6B7280;">Travel date</span>
                        <span style="color:#0F1419;">{{ $booking->booking_date->format('M d, Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span style="color:#6B7280;">Last updated</span>
                        <span style="color:#0F1419;">{{ $booking->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-admin-layout>
