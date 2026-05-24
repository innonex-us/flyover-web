<x-admin-layout pageTitle="Hotel Booking Detail">

    <div class="mb-8">
        <a href="{{ route('admin.hotel-bookings.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Bookings
        </a>
        <div class="flex justify-between items-start">
            <h2 class="text-3xl font-bold text-gray-800">Hotel Booking #{{ $hotelBooking->id }}</h2>
            <form action="{{ route('admin.hotel-bookings.destroy', $hotelBooking) }}" method="POST" onsubmit="return confirm('Delete this booking?');">
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

            <!-- Room Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Booking Details</h3>
                @if($hotelBooking->room)
                    <div class="flex gap-4 mb-4">
                        @if($hotelBooking->room->image)
                            <img src="{{ Storage::url($hotelBooking->room->image) }}" alt="" class="h-20 w-20 rounded-lg object-cover flex-shrink-0">
                        @endif
                        <div>
                            <p class="font-bold text-gray-900 text-lg">{{ $hotelBooking->room->hotel->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600 mt-0.5">{{ $hotelBooking->room->name }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700 capitalize mt-1">{{ $hotelBooking->room->room_type }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-red-500 text-sm mb-4">Room has been deleted.</p>
                @endif

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Check-in</p>
                        <p class="font-semibold text-gray-800">{{ $hotelBooking->check_in->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Check-out</p>
                        <p class="font-semibold text-gray-800">{{ $hotelBooking->check_out->format('M d, Y') }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Nights</p>
                        <p class="font-semibold text-gray-800">{{ $hotelBooking->nights }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Guests</p>
                        <p class="font-semibold text-gray-800">{{ $hotelBooking->guests }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Total Amount</p>
                        <p class="font-semibold text-gray-800">৳{{ number_format($hotelBooking->total_amount) }}</p>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <p class="text-xs text-gray-500 mb-1">Booked On</p>
                        <p class="font-semibold text-gray-800">{{ $hotelBooking->created_at->format('M d, Y') }}</p>
                    </div>
                </div>

                @if($hotelBooking->special_request)
                <div class="mt-4">
                    <p class="text-gray-500 text-sm mb-1">Special Request</p>
                    <div class="bg-red-50/50 p-4 rounded-xl border border-red-100 text-sm italic text-gray-700">
                        {{ $hotelBooking->special_request }}
                    </div>
                </div>
                @endif
            </div>

            <!-- Guest Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Guest Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                    <div>
                        <p class="text-gray-500 mb-1">Name</p>
                        <p class="font-semibold text-gray-800">{{ $hotelBooking->user ? $hotelBooking->user->name : $hotelBooking->guest_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Email</p>
                        <p class="font-semibold text-gray-800">{{ $hotelBooking->user ? $hotelBooking->user->email : $hotelBooking->guest_email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Phone</p>
                        <p class="font-semibold text-gray-800">{{ $hotelBooking->guest_phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 mb-1">Type</p>
                        <p class="font-semibold text-gray-800">
                            @if($hotelBooking->user)
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

                <form action="{{ route('admin.hotel-bookings.update', $hotelBooking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                                <option value="pending" {{ $hotelBooking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $hotelBooking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ $hotelBooking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $hotelBooking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
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
