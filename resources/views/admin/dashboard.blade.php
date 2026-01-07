<x-admin-layout>
    <div class="mb-8 flex justify-between items-center">
        <h2 class="text-3xl font-bold text-gray-800">Dashboard</h2>
        <div class="text-sm text-gray-500">Welcome back, {{ auth()->user()->name }}</div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Stat Card 1 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center">
            <div class="p-4 bg-blue-100 rounded-full text-blue-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Total Packages</p>
                <h3 class="text-2xl font-bold text-gray-800">12</h3>
            </div>
        </div>

        <!-- Stat Card 2 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center">
             <div class="p-4 bg-green-100 rounded-full text-green-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">Active Visas</p>
                <h3 class="text-2xl font-bold text-gray-800">5</h3>
            </div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 flex items-center">
             <div class="p-4 bg-purple-100 rounded-full text-purple-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <p class="text-gray-500 text-sm font-medium">New Bookings</p>
                <h3 class="text-2xl font-bold text-gray-800">8</h3>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <h3 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <button class="flex items-center justify-center p-6 bg-white border border-dashed border-gray-300 rounded-xl hover:border-red-500 hover:text-red-500 transition text-gray-600 font-medium">
            + Add New Package
        </button>
        <button class="flex items-center justify-center p-6 bg-white border border-dashed border-gray-300 rounded-xl hover:border-red-500 hover:text-red-500 transition text-gray-600 font-medium">
            + Add New Visa
        </button>
        <button class="flex items-center justify-center p-6 bg-white border border-dashed border-gray-300 rounded-xl hover:border-red-500 hover:text-red-500 transition text-gray-600 font-medium">
            View All Bookings
        </button>
    </div>
</x-admin-layout>
