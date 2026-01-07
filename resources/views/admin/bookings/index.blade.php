<x-admin-layout>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Bookings</h2>
            <p class="text-sm text-gray-500 mt-1">Track and manage all bookings</p>
        </div>
        
        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
            <div class="relative">
                 <select name="status" class="w-full md:w-40 pl-3 pr-10 py-2 text-sm border-gray-300 focus:border-red-500 focus:ring-red-200 rounded-lg shadow-sm" onchange="this.form.submit()">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            
            <div class="relative">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search ID, Name..." class="w-full md:w-64 pl-10 pr-4 py-2 text-sm border-gray-300 focus:border-red-500 focus:ring-red-200 rounded-lg shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                Filter
            </button>
            
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.bookings.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2 rounded-lg text-sm font-medium transition">
                    Clear
                </a>
            @endif
        </form>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-sm uppercase font-semibold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Booking ID</th>
                        <th class="px-6 py-4 text-left">Service</th>
                        <th class="px-6 py-4 text-left">User / Guest</th>
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($bookings as $booking)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">
                            #{{ $booking->id }}
                        </td>
                        <td class="px-6 py-4">
                            @if($booking->payable)
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $booking->payable->title ?? $booking->payable->country . ' (' . $booking->payable->type . ')' }}
                                </div>
                                <div class="text-xs text-gray-500 uppercase tracking-wide">
                                    {{ class_basename($booking->payable_type) }}
                                </div>
                            @else
                                <span class="text-red-500 text-sm">Service Deleted</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                             @if($booking->user)
                                <div class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $booking->user->email }}</div>
                            @else
                                <div class="text-sm font-medium text-gray-900">{{ $booking->guest_name }} <span class="text-xs bg-gray-200 text-gray-600 px-1.5 py-0.5 rounded ml-1">Guest</span></div>
                                <div class="text-sm text-gray-500">{{ $booking->guest_email }}</div>
                            @endif
                        </td>
                         <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $booking->booking_date->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($booking->status == 'confirmed')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Confirmed
                                </span>
                            @elseif($booking->status == 'cancelled')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                    Cancelled
                                </span>
                             @elseif($booking->status == 'completed')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Completed
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium">
                            <div class="flex justify-center space-x-3">
                                <a href="{{ route('admin.bookings.show', $booking) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition">
                                    View
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No bookings found yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($bookings->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $bookings->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
