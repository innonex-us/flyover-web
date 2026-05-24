<x-admin-layout pageTitle="Dashboard">

    {{-- Welcome bar --}}
    <div class="flex items-center justify-between mb-6">
        <p class="text-sm text-gray-500">
            Good @php
                $hour = now()->hour;
                if ($hour < 12) echo 'morning';
                elseif ($hour < 17) echo 'afternoon';
                else echo 'evening';
            @endphp, <span class="font-semibold text-gray-700">{{ auth()->user()->name }}</span>
        </p>
        <span class="text-xs text-gray-400">{{ now()->format('l, F j, Y') }}</span>
    </div>

    {{-- Stats Grid — 4 cols lg, 2 cols sm --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-6">

        {{-- Revenue --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Revenue</p>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">৳{{ number_format($totalRevenue) }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $confirmedBookingsCount }} confirmed</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Bookings --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Bookings</p>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ number_format($totalBookings) }}</p>
                <p class="text-xs text-gray-400 mt-2">{{ $newBookingsCount }} pending &middot; {{ $confirmedBookingsCount }} confirmed</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
            </div>
        </div>

        {{-- Packages --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Packages</p>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $totalPackages }}</p>
                <p class="text-xs text-gray-400 mt-2">Live tour packages</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- Visa Services --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold">Visa Services</p>
                <p class="text-3xl font-extrabold text-gray-900 mt-1">{{ $activeVisas }}</p>
                <p class="text-xs text-gray-400 mt-2">Active destinations</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
            </div>
        </div>

    </div>

    {{-- Blog mini-stats row --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-4 mb-6 flex items-center gap-6">
        <p class="text-xs text-gray-500 uppercase tracking-wide font-semibold mr-2">Blog</p>
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-green-500 flex-shrink-0"></span>
            <span class="text-sm font-semibold text-gray-800">{{ $publishedPostsCount }}</span>
            <span class="text-xs text-gray-400">Published</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-yellow-400 flex-shrink-0"></span>
            <span class="text-sm font-semibold text-gray-800">{{ $draftPostsCount }}</span>
            <span class="text-xs text-gray-400">Drafts</span>
        </div>
        <a href="{{ route('admin.blog.index') }}" class="ml-auto text-xs font-semibold text-gray-500 hover:text-gray-800 transition">Manage posts &rarr;</a>
    </div>

    {{-- Two-column grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Bookings --}}
        <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="text-sm font-bold text-gray-800">Recent Bookings</h3>
                <a href="{{ route('admin.bookings.index') }}" class="text-xs font-semibold text-red-600 hover:text-red-700 transition">View all &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="border-b border-gray-100">
                        <tr>
                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Customer</th>
                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Service</th>
                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Amount</th>
                            <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($recentBookings as $booking)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-5 py-3.5 text-sm font-medium text-gray-900">
                                {{ $booking->user ? $booking->user->name : $booking->guest_name }}
                            </td>
                            <td class="px-5 py-3.5 text-sm text-gray-600">
                                {{ $booking->payable->title ?? $booking->payable->country ?? 'N/A' }}
                            </td>
                            <td class="px-5 py-3.5 text-sm font-semibold text-gray-900">
                                ৳{{ number_format($booking->total_amount) }}
                            </td>
                            <td class="px-5 py-3.5">
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
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-sm text-gray-400">No bookings yet.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="lg:col-span-1 bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <h3 class="text-sm font-bold text-gray-800 mb-4">Quick Actions</h3>
            <div class="space-y-2.5">

                <a href="{{ route('admin.packages.create') }}" class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                    <div class="w-9 h-9 rounded-lg bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800 group-hover:text-gray-900">Add Tour Package</div>
                        <div class="text-xs text-gray-400">Create a new tour package</div>
                    </div>
                </a>

                <a href="{{ route('admin.visas.create') }}" class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                    <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800 group-hover:text-gray-900">Add Visa Service</div>
                        <div class="text-xs text-gray-400">Offer a new visa destination</div>
                    </div>
                </a>

                <a href="{{ route('admin.blog.create') }}" class="flex items-center gap-3 p-4 rounded-xl bg-gray-50 hover:bg-gray-100 transition group">
                    <div class="w-9 h-9 rounded-lg bg-orange-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-semibold text-gray-800 group-hover:text-gray-900">Write Blog Post</div>
                        <div class="text-xs text-gray-400">Publish a new article</div>
                    </div>
                </a>

            </div>

            @if($recentPosts->count())
            <div class="mt-5 pt-4 border-t border-gray-100">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Recent Posts</h4>
                    <a href="{{ route('admin.blog.index') }}" class="text-xs text-red-600 hover:text-red-700 font-semibold transition">View all</a>
                </div>
                <div class="space-y-1.5">
                    @foreach($recentPosts as $rp)
                    <a href="{{ route('admin.blog.edit', $rp) }}" class="flex items-center gap-2.5 p-2 rounded-lg hover:bg-gray-50 transition group">
                        <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $rp->is_published ? 'bg-green-500' : 'bg-gray-300' }}"></span>
                        <span class="text-xs text-gray-600 group-hover:text-red-600 truncate transition">{{ $rp->title }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>

    </div>

</x-admin-layout>
