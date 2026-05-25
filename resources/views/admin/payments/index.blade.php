<x-admin-layout pageTitle="Payment History">

    {{-- Stats --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Total Collected</p>
            <p class="text-2xl font-black text-gray-900">৳{{ number_format($totals['total'], 0) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Paid Revenue</p>
            <p class="text-2xl font-black text-green-600">৳{{ number_format($totals['paid'], 0) }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Pending</p>
            <p class="text-2xl font-black text-yellow-500">{{ $totals['pending'] }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-5 py-4">
            <p class="text-[10px] font-bold uppercase tracking-widest text-gray-400 mb-1">Failed / Cancelled</p>
            <p class="text-2xl font-black text-red-500">{{ $totals['failed'] }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <form action="{{ route('admin.payments.index') }}" method="GET"
          class="flex flex-col md:flex-row gap-2.5 mb-6 ml-auto w-full md:w-auto justify-end">
        <select name="status" class="w-full md:w-36 pl-3 pr-10 py-2 text-sm border-gray-200 focus:border-red-500 focus:ring-red-200 rounded-lg shadow-sm" onchange="this.form.submit()">
            <option value="">All Statuses</option>
            <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
            <option value="paid"      {{ request('status') == 'paid'      ? 'selected' : '' }}>Paid</option>
            <option value="failed"    {{ request('status') == 'failed'    ? 'selected' : '' }}>Failed</option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
        <select name="gateway" class="w-full md:w-36 pl-3 pr-10 py-2 text-sm border-gray-200 focus:border-red-500 focus:ring-red-200 rounded-lg shadow-sm" onchange="this.form.submit()">
            <option value="">All Gateways</option>
            <option value="bkash" {{ request('gateway') == 'bkash' ? 'selected' : '' }}>bKash</option>
        </select>
        <div class="relative">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Payment ID, invoice..."
                   class="w-full md:w-64 pl-9 pr-4 py-2 text-sm border-gray-200 focus:border-red-500 focus:ring-red-200 rounded-lg shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
        </div>
        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">Filter</button>
        @if(request()->hasAny(['search', 'status', 'gateway']))
            <a href="{{ route('admin.payments.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition">Clear</a>
        @endif
    </form>

    {{-- Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">#</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Customer</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Service</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Gateway</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Gateway TxID</th>
                        <th class="px-5 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Amount</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Date</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($payments as $payment)
                    @php
                        $booking    = $payment->payable;
                        $customerName  = $booking?->user?->name  ?? $booking?->guest_name  ?? '—';
                        $customerEmail = $booking?->user?->email ?? $booking?->guest_email ?? '';
                        $serviceType = match ($payment->payable_type) {
                            \App\Models\Booking::class         => 'Tour / Visa',
                            \App\Models\HotelBooking::class    => 'Hotel',
                            \App\Models\TransferBooking::class => 'Pik & Drop',
                            default                            => 'Unknown',
                        };
                        $statusClass = match ($payment->status) {
                            'paid'      => 'bg-green-100 text-green-700',
                            'pending'   => 'bg-yellow-100 text-yellow-700',
                            'failed'    => 'bg-red-100 text-red-700',
                            'cancelled' => 'bg-gray-100 text-gray-500',
                            default     => 'bg-gray-100 text-gray-600',
                        };
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5 text-sm font-semibold text-gray-900">#{{ $payment->id }}</td>
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-medium text-gray-900">{{ $customerName }}</div>
                            @if ($customerEmail)
                                <div class="text-xs text-gray-400">{{ $customerEmail }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold
                                @if($payment->payable_type === \App\Models\Booking::class) bg-blue-50 text-blue-700
                                @elseif($payment->payable_type === \App\Models\HotelBooking::class) bg-purple-50 text-purple-700
                                @else bg-green-50 text-green-700 @endif">
                                {{ $serviceType }}
                            </span>
                            @if ($booking)
                                <div class="text-xs text-gray-400 mt-0.5">Booking #{{ $booking->id }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center gap-1.5 text-sm font-bold text-[#E2136E]">
                                <span class="w-5 h-5 rounded bg-[#E2136E] text-white text-[10px] font-black flex items-center justify-center">b</span>
                                {{ ucfirst($payment->gateway) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-xs font-mono text-gray-500">
                            {{ $payment->gateway_transaction_id ?? $payment->gateway_payment_id ?? '—' }}
                        </td>
                        <td class="px-5 py-3.5 text-right text-sm font-bold text-gray-900">
                            ৳{{ number_format($payment->amount, 0) }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $statusClass }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-500">
                            {{ $payment->created_at->format('M d, Y') }}
                            <div class="text-xs text-gray-400">{{ $payment->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <a href="{{ route('admin.payments.show', $payment) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 hover:text-red-800 transition">
                                View
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-16 text-center text-gray-400 text-sm">No payments found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($payments->hasPages())
            <div class="px-5 py-4 border-t border-gray-100">
                {{ $payments->links() }}
            </div>
        @endif
    </div>

</x-admin-layout>
