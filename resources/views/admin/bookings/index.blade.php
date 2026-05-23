<x-admin-layout>

    {{-- Page header --}}
    <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold" style="color:#0F1419;">Bookings</h1>
            <p class="mt-0.5 text-sm" style="color:#6B7280;">
                {{ $bookings->total() }} {{ Str::plural('booking', $bookings->total()) }} total
            </p>
        </div>

        {{-- Search / Filter bar --}}
        <form action="{{ route('admin.bookings.index') }}" method="GET"
              class="flex flex-wrap items-center gap-2">
            {{-- Preserve type filter when using search --}}
            @if(request('type'))
                <input type="hidden" name="type" value="{{ request('type') }}">
            @endif

            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Search ID or name…"
                   class="rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2 w-48"
                   style="border-color:#E6E8EC; color:#0F1419; focus-ring-color:#C8102E;">

            <select name="status" onchange="this.form.submit()"
                    class="rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                    style="border-color:#E6E8EC; color:#0F1419;">
                <option value="">All Statuses</option>
                @foreach(['pending','confirmed','cancelled','completed'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                        {{ ucfirst($s) }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="rounded-lg px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90"
                    style="background:#C8102E;">
                Filter
            </button>

            @if(request()->hasAny(['search','status']))
                <a href="{{ route('admin.bookings.index', request()->only('type')) }}"
                   class="rounded-lg border px-4 py-2 text-sm font-medium transition hover:opacity-70"
                   style="border-color:#E6E8EC; color:#6B7280;">
                    Clear
                </a>
            @endif
        </form>
    </div>

    {{-- Status filter tabs --}}
    <div class="mb-4 flex gap-1 overflow-x-auto">
        @php
            $tabs = [
                null       => 'All',
                'pending'  => 'Pending',
                'confirmed'=> 'Confirmed',
                'cancelled'=> 'Cancelled',
            ];
            $currentStatus = request('status');
        @endphp
        @foreach($tabs as $value => $label)
            <a href="{{ route('admin.bookings.index', array_filter(array_merge(request()->query(), ['status' => $value]), fn($v) => $v !== null && $v !== '')) }}"
               class="flex-shrink-0 rounded-lg px-4 py-2 text-sm font-medium transition"
               @if($currentStatus === $value || ($value === null && !$currentStatus))
                   style="background:#C8102E; color:#fff;"
               @else
                   style="background:#F4F5F7; color:#6B7280;"
               @endif>
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Table card --}}
    <div class="overflow-hidden rounded-xl" style="background:#fff; border:1px solid #E6E8EC;">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-sm">
                <thead>
                    <tr style="border-bottom:1px solid #E6E8EC; background:#F4F5F7;">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Booking #</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Package / Service</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Amount</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Date</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                    <tr class="transition-colors hover:bg-[#F4F5F7]" style="border-bottom:1px solid #E6E8EC;">

                        {{-- Booking ID (monospace) --}}
                        <td class="px-6 py-4">
                            <span class="font-mono font-semibold" style="color:#0F1419;">#{{ str_pad($booking->id, 5, '0', STR_PAD_LEFT) }}</span>
                        </td>

                        {{-- Customer --}}
                        <td class="px-6 py-4">
                            @if($booking->user)
                                <p class="font-medium" style="color:#0F1419;">{{ $booking->user->name }}</p>
                                <p class="text-xs" style="color:#6B7280;">{{ $booking->user->email }}</p>
                            @else
                                <p class="font-medium" style="color:#0F1419;">
                                    {{ $booking->guest_name }}
                                    <span class="ml-1 inline-block rounded px-1.5 py-0.5 text-[10px] font-semibold uppercase"
                                          style="background:#F3F4F6; color:#6B7280;">Guest</span>
                                </p>
                                <p class="text-xs" style="color:#6B7280;">{{ $booking->guest_email }}</p>
                            @endif
                        </td>

                        {{-- Package / Service --}}
                        <td class="px-6 py-4">
                            @if($booking->payable)
                                <p class="font-medium" style="color:#0F1419;">
                                    {{ $booking->payable->title ?? ($booking->payable->country . ' — ' . $booking->payable->type) }}
                                </p>
                                <p class="text-xs uppercase tracking-wide" style="color:#6B7280;">
                                    {{ class_basename($booking->payable_type) }}
                                </p>
                            @else
                                <span class="text-xs italic" style="color:#991B1B;">Service deleted</span>
                            @endif
                        </td>

                        {{-- Amount --}}
                        <td class="px-6 py-4 font-medium" style="color:#0F1419;">
                            ৳{{ number_format($booking->total_amount) }}
                        </td>

                        {{-- Status pill --}}
                        <td class="px-6 py-4 text-center">
                            @php
                                $pill = match($booking->status) {
                                    'confirmed'  => ['background:#D1FAE5;color:#065F46;', 'Confirmed'],
                                    'cancelled'  => ['background:#FEE2E2;color:#991B1B;', 'Cancelled'],
                                    'completed'  => ['background:#D1FAE5;color:#065F46;', 'Completed'],
                                    default      => ['background:#FEF3C7;color:#92400E;', 'Pending'],
                                };
                            @endphp
                            <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                  style="{{ $pill[0] }}">
                                {{ $pill[1] }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-4 text-xs" style="color:#6B7280;">
                            {{ $booking->booking_date->format('M d, Y') }}
                        </td>

                        {{-- Action --}}
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.bookings.show', $booking) }}"
                               class="inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs font-medium transition hover:opacity-80"
                               style="border-color:#E6E8EC; color:#0F1419;">
                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-16 text-center" style="color:#6B7280;">
                            <svg class="mx-auto mb-3 h-10 w-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="font-medium">No bookings found.</p>
                            <p class="mt-1 text-xs">Try adjusting your filters.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
        <div class="px-6 py-4" style="border-top:1px solid #E6E8EC; background:#F4F5F7;">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
