<x-admin-layout pageTitle="Transfer Booking Detail">

    <div class="mb-8">
        <a href="{{ route('admin.transfer-bookings.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Bookings
        </a>
        <div class="flex justify-between items-start">
            <h2 class="text-3xl font-bold text-gray-800">Transfer Booking #{{ $transferBooking->id }}</h2>
            <form action="{{ route('admin.transfer-bookings.destroy', $transferBooking) }}" method="POST" onsubmit="return confirm('Delete this booking?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm border border-red-200 hover:bg-red-50 rounded-lg px-4 py-2 transition">
                    Delete Booking
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        <div class="lg:col-span-2 space-y-8">

            <!-- Route Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Transfer Details</h3>
                <div class="space-y-3">
                    @if($transferBooking->is_custom)
                        <div class="flex items-center gap-2 mb-3">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700">Custom Transfer</span>
                            <span class="text-xs text-gray-400">(Admin will set price)</span>
                        </div>
                    @elseif($transferBooking->route)
                        <p class="font-semibold text-gray-900">{{ $transferBooking->route->name }}</p>
                    @endif
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500 mb-1">Pickup Location</p>
                            <p class="font-semibold text-gray-800">{{ $transferBooking->pickup_location }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Drop Location</p>
                            <p class="font-semibold text-gray-800">{{ $transferBooking->drop_location }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Travel Date</p>
                            <p class="font-semibold text-gray-800">{{ $transferBooking->travel_date->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Pickup Time</p>
                            <p class="font-semibold text-gray-800">{{ $transferBooking->pickup_time ?? 'Not specified' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Passengers</p>
                            <p class="font-semibold text-gray-800">{{ $transferBooking->passenger_count }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500 mb-1">Total Amount</p>
                            <p class="font-semibold text-gray-800">{{ $transferBooking->total_amount > 0 ? '৳' . number_format($transferBooking->total_amount) : '—' }}</p>
                        </div>
                    </div>
                    @if($transferBooking->special_request)
                    <div class="mt-4">
                        <p class="text-gray-500 text-sm mb-1">Special Request</p>
                        <div class="bg-red-50/50 p-4 rounded-xl border border-red-100 text-sm italic text-gray-700">
                            {{ $transferBooking->special_request }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Passenger Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Passenger Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Name</p>
                        <p class="font-semibold text-gray-800">{{ $transferBooking->user ? $transferBooking->user->name : $transferBooking->guest_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Email</p>
                        <p class="font-semibold text-gray-800">{{ $transferBooking->user ? $transferBooking->user->email : $transferBooking->guest_email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Phone</p>
                        <p class="font-semibold text-gray-800">{{ $transferBooking->guest_phone ?? ($transferBooking->user->phone ?? '—') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Type</p>
                        <p class="font-semibold text-gray-800">
                            @if($transferBooking->user)
                                Registered User
                            @else
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">Guest</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Status Panel -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Booking Status</h3>

                <form action="{{ route('admin.transfer-bookings.update', $transferBooking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                                <option value="pending" {{ $transferBooking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $transferBooking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ $transferBooking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $transferBooking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg shadow transition">
                            Update Status
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>
