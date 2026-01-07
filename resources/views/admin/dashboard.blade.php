<x-admin-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
            <div class="text-sm text-gray-500 mt-1">Welcome back, {{ auth()->user()->name }}</div>
        </div>
        <div class="flex space-x-3">
             <a href="{{ route('admin.bookings.index') }}" class="bg-white border border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-800 font-semibold py-2 px-4 rounded-lg shadow-sm transition flex items-center">
                 <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Bookings
            </a>
            <a href="{{ route('profile.edit') }}" class="bg-gray-800 hover:bg-gray-900 text-white font-semibold py-2 px-4 rounded-lg shadow transition flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Settings
            </a>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        <!-- Revenue Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div>
                     <p class="text-gray-500 text-sm font-medium">Total Revenue</p>
                    <h3 class="text-2xl font-bold text-gray-800">৳{{ number_format($totalRevenue) }}</h3>
                </div>
                 <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
             <p class="text-xs text-green-600 font-semibold flex items-center">
                 <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                 From confirmed bookings
             </p>
        </div>

        <!-- Bookings Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div>
                     <p class="text-gray-500 text-sm font-medium">Bookings</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ number_format($totalBookings) }}</h3>
                </div>
                 <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                </div>
            </div>
             <div class="flex gap-2">
                 <span class="text-xs bg-yellow-100 text-yellow-700 px-2 py-0.5 rounded-full">{{ $newBookingsCount }} Pending</span>
                 <span class="text-xs bg-green-100 text-green-700 px-2 py-0.5 rounded-full">{{ $confirmedBookingsCount }} Confirmed</span>
             </div>
        </div>

         <!-- Packages Card -->
         <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div>
                     <p class="text-gray-500 text-sm font-medium">Active Packages</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $totalPackages }}</h3>
                </div>
                 <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
             <p class="text-xs text-gray-500">Live tour packages</p>
        </div>

        <!-- Visas Card -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100">
            <div class="flex justify-between items-start mb-4">
                <div>
                     <p class="text-gray-500 text-sm font-medium">Visa Services</p>
                    <h3 class="text-2xl font-bold text-gray-800">{{ $activeVisas }}</h3>
                </div>
                 <div class="p-2 bg-red-50 rounded-lg text-red-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                </div>
            </div>
            <p class="text-xs text-gray-500">Available destinations</p>
        </div>

    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Recent Bookings Table -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="font-bold text-gray-800">Recent Bookings</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-sm text-red-600 hover:text-red-700 font-medium">View All</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-100">
                        <tr>
                            <th class="px-6 py-3">Customer</th>
                            <th class="px-6 py-3">Service</th>
                            <th class="px-6 py-3">Amount</th>
                            <th class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentBookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900">{{ $booking->user ? $booking->user->name : $booking->guest_name }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                {{ $booking->payable->title ?? $booking->payable->country ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                ৳{{ number_format($booking->total_amount) }}
                            </td>
                             <td class="px-6 py-4">
                                @if($booking->status == 'confirmed')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800">Confirmed</span>
                                @elseif($booking->status == 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-500">No bookings yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="lg:col-span-1 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h3 class="font-bold text-gray-800 mb-6">Quick Actions</h3>
            <div class="space-y-4">
                <a href="{{ route('admin.packages.create') }}" class="block p-4 border border-dashed border-gray-200 rounded-xl hover:border-red-500 hover:bg-red-50 transition group">
                    <div class="flex items-center">
                        <div class="bg-red-100 text-red-600 p-2 rounded-lg mr-3 group-hover:bg-red-200 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-800 group-hover:text-red-700">Add New Package</div>
                            <div class="text-xs text-gray-500">Create a new tour package</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.visas.create') }}" class="block p-4 border border-dashed border-gray-200 rounded-xl hover:border-blue-500 hover:bg-blue-50 transition group">
                    <div class="flex items-center">
                        <div class="bg-blue-100 text-blue-600 p-2 rounded-lg mr-3 group-hover:bg-blue-200 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                         <div>
                            <div class="font-semibold text-gray-800 group-hover:text-blue-700">Add Visa Service</div>
                            <div class="text-xs text-gray-500">Offer a new visa destination</div>
                        </div>
                    </div>
                </a>

                 <div class="mt-6 pt-6 border-t border-gray-100">
                    <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">System Status</h4>
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Server Status</span>
                            <span class="text-green-600 font-semibold flex items-center"><span class="w-2 h-2 bg-green-500 rounded-full mr-1.5"></span> Online</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">App Version</span>
                            <span class="text-gray-800">v1.2.0</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-admin-layout>
