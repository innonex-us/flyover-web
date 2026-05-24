<x-admin-layout pageTitle="Hotel Bookings">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <form action="{{ route('admin.hotel-bookings.index') }}" method="GET" class="flex flex-col md:flex-row gap-2.5 w-full md:w-auto ml-auto">
            <select name="status" class="w-full md:w-40 pl-3 pr-10 py-2 text-sm border-gray-200 focus:border-red-500 focus:ring-red-200 rounded-lg shadow-sm" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name, email..."
                    class="w-full md:w-60 pl-9 pr-4 py-2 text-sm border-gray-200 focus:border-red-500 focus:ring-red-200 rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow-sm">Filter</button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.hotel-bookings.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition">Clear</a>
            @endif
        </form>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">ID</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Hotel / Room</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Guest Name</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Check-in</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Check-out</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Nights</th>
                        <th class="px-5 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Amount</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5 text-sm font-semibold text-gray-900">#{{ $booking->id }}</td>
                        <td class="px-5 py-3.5">
                            @if($booking->room)
                                <div class="text-sm font-medium text-gray-900">{{ $booking->room->hotel->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-400 mt-0.5">{{ $booking->room->name }}</div>
                            @else
                                <span class="text-xs text-red-400">Room deleted</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($booking->user)
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->user->email }}</div>
                            @else
                                <div class="text-sm font-medium text-gray-900">{{ $booking->guest_name }}</div>
                                <div class="text-xs text-gray-400">{{ $booking->guest_email }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center text-sm text-gray-600">{{ $booking->check_in->format('M d, Y') }}</td>
                        <td class="px-5 py-3.5 text-center text-sm text-gray-600">{{ $booking->check_out->format('M d, Y') }}</td>
                        <td class="px-5 py-3.5 text-center text-sm font-semibold text-gray-700">{{ $booking->nights }}</td>
                        <td class="px-5 py-3.5 text-right text-sm font-semibold text-gray-900">৳{{ number_format($booking->total_amount) }}</td>
                        <td class="px-5 py-3.5 text-center">
                            @php
                                $badgeClass = match($booking->status) {
                                    'confirmed' => 'bg-green-100 text-green-700',
                                    'pending'   => 'bg-yellow-100 text-yellow-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    'completed' => 'bg-blue-100 text-blue-700',
                                    default     => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $badgeClass }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <a href="{{ route('admin.hotel-bookings.show', $booking) }}" class="inline-flex items-center text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition">
                                View
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-5 py-12 text-center text-sm text-gray-400">No hotel bookings found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($bookings->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
