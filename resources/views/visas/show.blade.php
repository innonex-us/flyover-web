<x-app-layout>
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Left Column: Details -->
                <div class="lg:col-span-2">
                    <div class="flex items-center mb-6">
                        <img src="{{ $visa->thumbnail ?? 'https://via.placeholder.com/100x70?text=Flag' }}" alt="{{ $visa->country }}" class="w-16 h-auto shadow rounded mr-4">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">{{ $visa->country }} Visa</h1>
                            <p class="text-red-600 font-medium">{{ $visa->type }} Entry</p>
                        </div>
                    </div>

                    <div class="bg-red-50 border border-red-100 rounded-lg p-6 mb-8">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Visa at a Glance</h2>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Processing Time</p>
                                <p class="font-semibold">{{ $visa->processing_time }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Visa Fee</p>
                                <p class="font-semibold">৳{{ number_format($visa->price) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="prose max-w-none text-gray-600">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Required Documents</h2>
                        <div class="bg-white border border-gray-200 rounded-lg p-6">
                            @if($visa->requirements)
                                <p class="whitespace-pre-line">{{ $visa->requirements }}</p>
                            @else
                                <ul class="list-disc pl-5 space-y-2">
                                    <li>Passport with at least 6 months validity</li>
                                    <li>Recent passport-size photographs</li>
                                    <li>Completed visa application form</li>
                                    <li>Proof of accommodation/hotel booking</li>
                                    <li>Return flight ticket</li>
                                    <li>Bank statement for the last 6 months</li>
                                </ul>
                            @endif
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Description</h2>
                        <p>{{ $visa->description }}</p>
                    </div>
                </div>

                <!-- Right Column: Assistance/Booking -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-xl p-6 sticky top-24 border border-gray-100">
                        
                         <h3 class="text-xl font-bold text-gray-900 mb-4">Apply Now</h3>
                         
                         <form action="{{ route('bookings.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payable_type" value="visa">
                            <input type="hidden" name="payable_id" value="{{ $visa->id }}">
                            
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Expected Travel Date</label>
                                    <input type="date" name="booking_date" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50" min="{{ date('Y-m-d') }}">
                                </div>
                                
                                @guest
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                                        <input type="text" name="guest_name" required placeholder="John Doe" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                        <input type="email" name="guest_email" required placeholder="john@example.com" class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                        <input type="text" name="guest_phone" required placeholder="+8801..." class="w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                                    </div>
                                @endguest
                            </div>

                            <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 px-4 rounded-md hover:bg-red-700 transition mb-6">
                                Submit Application
                            </button>
                        </form>

                        <div class="border-t border-gray-100 pt-6">
                             <h3 class="text-lg font-bold text-gray-900 mb-2">Need Help?</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <div class="bg-red-100 p-2 rounded-full mr-3 text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Call Us</p>
                                        <p class="font-bold text-gray-900">+880 1234 567890</p>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <div class="bg-red-100 p-2 rounded-full mr-3 text-red-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500">Email Us</p>
                                        <p class="font-bold text-gray-900">visa@flyoverbd.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
