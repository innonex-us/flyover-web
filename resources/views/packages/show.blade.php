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
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $package->title }}</h1>
                    <div class="flex items-center text-gray-500 mb-6">
                        <span class="mr-4 flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $package->location }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $package->duration_days }} Days / {{ $package->duration_days - 1 }} Nights
                        </span>
                    </div>

                    <img src="{{ $package->thumbnail ?? 'https://via.placeholder.com/800x450?text=Tour+Package' }}" alt="{{ $package->title }}" class="w-full rounded-lg shadow-md mb-8">

                    <div class="prose max-w-none text-gray-600">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
                        <p>{{ $package->description }}</p>

                        <!-- Add dummy itinerary for now as we don't have it in schema yet -->
                        <h3 class="text-xl font-bold text-gray-900 mt-8 mb-4">Itinerary</h3>
                        <div class="space-y-4">
                            <div class="border-l-4 border-red-500 pl-4 py-2">
                                <h4 class="font-bold text-gray-900">Day 1: Arrival & Check-in</h4>
                                <p class="text-sm">Arrive at destination, transfer to hotel, and leisure time.</p>
                            </div>
                            <div class="border-l-4 border-red-500 pl-4 py-2">
                                <h4 class="font-bold text-gray-900">Day 2: City Tour</h4>
                                <p class="text-sm">Full day guided tour of the city's major attractions.</p>
                            </div>
                            <div class="border-l-4 border-red-500 pl-4 py-2">
                                <h4 class="font-bold text-gray-900">Day {{ $package->duration_days }}: Departure</h4>
                                <p class="text-sm">Breakfast at hotel and transfer to airport.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Booking Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-lg shadow-xl p-6 sticky top-24 border border-gray-100">
                        <div class="flex justify-between items-center mb-6">
                            <span class="text-gray-500">Price per person</span>
                            <span class="text-3xl font-bold text-red-600">৳{{ number_format($package->price) }}</span>
                        </div>
                        
                        <form action="{{ route('bookings.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payable_type" value="package">
                            <input type="hidden" name="payable_id" value="{{ $package->id }}">
                            
                            <div class="space-y-4 mb-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Travel Date</label>
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

                            <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 px-4 rounded-md hover:bg-red-700 transition mb-4">
                                Book Now
                            </button>
                        </form>
                        
                        <button class="w-full border border-red-600 text-red-600 font-bold py-3 px-4 rounded-md hover:bg-red-50 transition">
                            Contact Support
                        </button>

                        <div class="mt-6 pt-6 border-t border-gray-100 text-sm text-gray-500">
                            <p class="flex items-center mb-2">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Instant Confirmation
                            </p>
                            <p class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                Best Price Guaranteed
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
