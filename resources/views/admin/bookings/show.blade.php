<x-admin-layout>
    <div class="mb-8">
        <a href="{{ route('admin.bookings.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Bookings
        </a>
        <div class="flex justify-between items-start">
            <h2 class="text-3xl font-bold text-gray-800">Booking #{{ $booking->id }}</h2>
            <form action="{{ route('admin.bookings.destroy', $booking) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this booking? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm border border-red-200 hover:bg-red-50 rounded-lg px-4 py-2 transition">
                    Delete Booking
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Booking Details Column -->
        <div class="lg:col-span-2 space-y-8">
            
            <!-- Service Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Service Details</h3>
                <div class="flex items-start">
                     @if($booking->payable)
                        @if(isset($booking->payable->thumbnail))
                        <div class="flex-shrink-0 h-24 w-24 mr-6">
                             <img class="h-24 w-24 rounded-lg object-cover" src="{{ Storage::url($booking->payable->thumbnail) }}" alt="">
                        </div>
                        @endif
                        <div>
                            <div class="text-xl font-bold text-gray-900 mb-1">
                                {{ $booking->payable->title ?? $booking->payable->country . ' (' . $booking->payable->type . ')' }}
                            </div>
                            <div class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-2">
                                {{ class_basename($booking->payable_type) }}
                            </div>
                            <div class="text-gray-600 space-y-1 text-sm">
                                <p><span class="font-semibold">Booking Date:</span> {{ $booking->booking_date->format('F d, Y') }}</p>
                                <p><span class="font-semibold">Persons:</span> {{ $booking->quantity }}</p>
                                <p><span class="font-semibold">Price per Unit:</span> ৳{{ number_format($booking->payable->price) }}</p>
                                <p><span class="font-semibold">Total Amount:</span> ৳{{ number_format($booking->total_amount) }}</p>
                            </div>
                        </div>
                    @else
                        <div class="text-red-500">Service has been deleted from the system.</div>
                    @endif
                </div>
            </div>

            <!-- User Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Customer Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Name</p>
                        <p class="font-semibold text-gray-800">{{ $booking->user ? $booking->user->name : $booking->guest_name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Email</p>
                        <p class="font-semibold text-gray-800">{{ $booking->user ? $booking->user->email : $booking->guest_email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Phone</p>
                        <p class="font-semibold text-gray-800">{{ $booking->user ? $booking->user->phone : $booking->guest_phone }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">User Type</p>
                        <p class="font-semibold text-gray-800">
                             @if($booking->user)
                                Registered User
                             @else
                                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">Guest</span>
                             @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Booking Details (PAX & Notes) -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Pax Breakdown & Requirements</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @if($booking->details)
                    <div class="space-y-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Group Composition</p>
                        <div class="flex gap-4">
                            <div class="bg-gray-50 px-4 py-2 rounded-lg text-center">
                                <p class="text-[10px] text-gray-500 uppercase">Adults</p>
                                <p class="font-bold text-gray-800">{{ $booking->details['adults'] ?? 0 }}</p>
                            </div>
                            <div class="bg-gray-50 px-4 py-2 rounded-lg text-center">
                                <p class="text-[10px] text-gray-500 uppercase">Child</p>
                                <p class="font-bold text-gray-800">{{ $booking->details['children'] ?? 0 }}</p>
                            </div>
                            <div class="bg-gray-50 px-4 py-2 rounded-lg text-center">
                                <p class="text-[10px] text-gray-500 uppercase">Infant</p>
                                <p class="font-bold text-gray-800">{{ $booking->details['infants'] ?? 0 }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="space-y-2">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Special Requirements</p>
                        <div class="bg-red-50/50 p-4 rounded-xl border border-red-100 text-sm italic text-gray-700">
                            {{ $booking->notes ?: 'No special requirements listed.' }}
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Actions Column -->
        <div class="lg:col-span-1 space-y-8">
            
            <!-- Update Status -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Booking Status</h3>
                
                @if(session('success'))
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-3 py-2 rounded text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Order Status</label>
                            <select name="status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                                <option value="pending" {{ $booking->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ $booking->status == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="completed" {{ $booking->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $booking->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Payment Status</label>
                             <select name="payment_status" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                                <option value="unpaid" {{ $booking->payment_status == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                                <option value="partial" {{ $booking->payment_status == 'partial' ? 'selected' : '' }}>Partial</option>
                                <option value="paid" {{ $booking->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="refunded" {{ $booking->payment_status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>

                         <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Admin Notes</label>
                            <textarea name="notes" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200" placeholder="Internal notes...">{{ $booking->notes }}</textarea>
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
