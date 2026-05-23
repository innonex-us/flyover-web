<x-admin-layout>
    {{-- Page Header --}}
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold" style="color:#0F1419;">Dashboard</h2>
            <p class="text-sm mt-0.5" style="color:#6B7280;">Welcome back, {{ auth()->user()->name }}</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.bookings.index') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border text-sm font-semibold transition hover:opacity-80"
               style="background:#fff;border-color:#E6E8EC;color:#0F1419;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                Bookings
            </a>
            <a href="{{ route('admin.profile.edit') }}"
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition hover:opacity-90"
               style="background:#18130E;color:#FAF6EE;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Settings
            </a>
        </div>
    </div>

    {{-- Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-5 mb-8">

        {{-- Revenue Card (prominent gradient) --}}
        <div class="rounded-xl border p-5 lg:col-span-1 flex flex-col justify-between"
             style="background:linear-gradient(135deg,#18130E 0%,#2d2621 100%);border-color:#2d2621;color:#FAF6EE;">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-bold tracking-widest uppercase mb-1" style="color:#a89880;">Revenue</p>
                    <h3 class="text-2xl font-bold" style="color:#FAF6EE;">৳{{ number_format($totalRevenue) }}</h3>
                </div>
                <div class="p-2 rounded-lg" style="background:rgba(255,255,255,0.10);">
                    <svg class="w-5 h-5" style="color:#C8102E;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <p class="text-[11px] font-semibold flex items-center gap-1" style="color:#6ee7b7;">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                From confirmed bookings
            </p>
        </div>

        {{-- Bookings Card --}}
        <div class="rounded-xl border p-5 flex flex-col justify-between"
             style="background:#fff;border-color:#E6E8EC;">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-bold tracking-widest uppercase mb-1" style="color:#6B7280;">Total Bookings</p>
                    <h3 class="text-2xl font-bold" style="color:#0F1419;">{{ number_format($totalBookings) }}</h3>
                </div>
                <div class="p-2 rounded-lg" style="background:#EFF6FF;">
                    <svg class="w-5 h-5" style="color:#3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                </div>
            </div>
            <div class="flex gap-2 flex-wrap">
                <span class="adm-pill text-[11px] font-semibold px-2 py-0.5 rounded-full" style="background:#FEF3C7;color:#92400E;">{{ $newBookingsCount }} Pending</span>
                <span class="adm-pill text-[11px] font-semibold px-2 py-0.5 rounded-full" style="background:#D1FAE5;color:#065F46;">{{ $confirmedBookingsCount }} Confirmed</span>
            </div>
        </div>

        {{-- Packages Card --}}
        <div class="rounded-xl border p-5 flex flex-col justify-between"
             style="background:#fff;border-color:#E6E8EC;">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-bold tracking-widest uppercase mb-1" style="color:#6B7280;">Tour Packages</p>
                    <h3 class="text-2xl font-bold" style="color:#0F1419;">{{ $totalPackages }}</h3>
                </div>
                <div class="p-2 rounded-lg" style="background:#F5F3FF;">
                    <svg class="w-5 h-5" style="color:#7C3AED;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                </div>
            </div>
            <p class="text-[11px]" style="color:#6B7280;">Live tour packages</p>
        </div>

        {{-- Visa Services Card --}}
        <div class="rounded-xl border p-5 flex flex-col justify-between"
             style="background:#fff;border-color:#E6E8EC;">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-bold tracking-widest uppercase mb-1" style="color:#6B7280;">Visa Services</p>
                    <h3 class="text-2xl font-bold" style="color:#0F1419;">{{ $activeVisas }}</h3>
                </div>
                <div class="p-2 rounded-lg" style="background:#FEF2F2;">
                    <svg class="w-5 h-5" style="color:#C8102E;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
            </div>
            <p class="text-[11px]" style="color:#6B7280;">Available destinations</p>
        </div>

        {{-- Blog Posts Card --}}
        <div class="rounded-xl border p-5 flex flex-col justify-between"
             style="background:#fff;border-color:#E6E8EC;">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <p class="text-[10px] font-bold tracking-widest uppercase mb-1" style="color:#6B7280;">Blog Posts</p>
                    <h3 class="text-2xl font-bold" style="color:#0F1419;">{{ $publishedPostsCount + $draftPostsCount }}</h3>
                </div>
                <div class="p-2 rounded-lg" style="background:#FFF7ED;">
                    <svg class="w-5 h-5" style="color:#EA580C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                </div>
            </div>
            <div class="flex gap-2 flex-wrap">
                <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full" style="background:#D1FAE5;color:#065F46;">{{ $publishedPostsCount }} Published</span>
                @if($draftPostsCount > 0)
                    <span class="text-[11px] font-semibold px-2 py-0.5 rounded-full" style="background:#F3F4F6;color:#6B7280;">{{ $draftPostsCount }} Drafts</span>
                @endif
            </div>
        </div>

    </div>

    {{-- Bottom Grid: Recent Bookings + Quick Actions --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Recent Bookings Table --}}
        <div class="lg:col-span-2 rounded-xl border overflow-hidden shadow-sm" style="background:#fff;border-color:#E6E8EC;">
            <div class="px-6 py-4 flex justify-between items-center" style="border-bottom:1px solid #E6E8EC;">
                <h3 class="font-bold text-sm" style="color:#0F1419;">Recent Bookings</h3>
                <a href="{{ route('admin.bookings.index') }}"
                   class="text-xs font-semibold transition hover:opacity-70"
                   style="color:#C8102E;">View All &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr style="background:#F9FAFB;border-bottom:1px solid #E6E8EC;">
                            <th class="px-6 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Customer</th>
                            <th class="px-6 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Service</th>
                            <th class="px-6 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Amount</th>
                            <th class="px-6 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y" style="border-color:#E6E8EC;">
                        @forelse($recentBookings as $booking)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-medium" style="color:#0F1419;">
                                {{ $booking->user ? $booking->user->name : $booking->guest_name }}
                            </td>
                            <td class="px-6 py-4 text-sm" style="color:#6B7280;">
                                {{ $booking->payable->title ?? $booking->payable->country ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold" style="color:#0F1419;">
                                ৳{{ number_format($booking->total_amount) }}
                            </td>
                            <td class="px-6 py-4">
                                @if($booking->status === 'confirmed')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold" style="background:#D1FAE5;color:#065F46;">Confirmed</span>
                                @elseif($booking->status === 'pending')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold" style="background:#FEF3C7;color:#92400E;">Pending</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold" style="background:#F3F4F6;color:#6B7280;">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-sm" style="color:#6B7280;">
                                No bookings yet.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Quick Actions + Recent Posts --}}
        <div class="lg:col-span-1 rounded-xl border p-6 shadow-sm" style="background:#fff;border-color:#E6E8EC;">
            <h3 class="font-bold text-sm mb-4" style="color:#0F1419;">Quick Actions</h3>
            <div class="space-y-3">

                <a href="{{ route('admin.packages.create') }}"
                   class="block p-4 rounded-xl border border-dashed transition group hover:border-[#C8102E] hover:bg-red-50"
                   style="border-color:#E6E8EC;">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg transition group-hover:opacity-80" style="background:#FEF2F2;">
                            <svg class="w-4 h-4" style="color:#C8102E;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-semibold transition group-hover:text-[#C8102E]" style="color:#0F1419;">Add Tour Package</div>
                            <div class="text-xs" style="color:#6B7280;">Create a new tour package</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.visas.create') }}"
                   class="block p-4 rounded-xl border border-dashed transition group hover:border-blue-400 hover:bg-blue-50"
                   style="border-color:#E6E8EC;">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg" style="background:#EFF6FF;">
                            <svg class="w-4 h-4" style="color:#3B82F6;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-semibold transition group-hover:text-blue-700" style="color:#0F1419;">Add Visa Service</div>
                            <div class="text-xs" style="color:#6B7280;">Offer a new visa destination</div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.blog.create') }}"
                   class="block p-4 rounded-xl border border-dashed transition group hover:border-orange-400 hover:bg-orange-50"
                   style="border-color:#E6E8EC;">
                    <div class="flex items-center gap-3">
                        <div class="p-2 rounded-lg" style="background:#FFF7ED;">
                            <svg class="w-4 h-4" style="color:#EA580C;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </div>
                        <div>
                            <div class="text-sm font-semibold transition group-hover:text-orange-700" style="color:#0F1419;">Write Blog Post</div>
                            <div class="text-xs" style="color:#6B7280;">Publish a new article</div>
                        </div>
                    </div>
                </a>

            </div>

            {{-- Recent Blog Posts --}}
            @if($recentPosts->count())
            <div class="mt-5 pt-5" style="border-top:1px solid #E6E8EC;">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Recent Posts</span>
                    <a href="{{ route('admin.blog.index') }}" class="text-xs font-semibold transition hover:opacity-70" style="color:#C8102E;">View all</a>
                </div>
                <div class="space-y-1">
                    @foreach($recentPosts as $rp)
                    <a href="{{ route('admin.blog.edit', $rp) }}"
                       class="flex items-center gap-2 p-2 rounded-lg transition hover:bg-gray-50 group">
                        <div class="w-1.5 h-1.5 rounded-full flex-shrink-0 {{ $rp->is_published ? 'bg-green-500' : 'bg-gray-300' }}"></div>
                        <span class="text-xs truncate transition group-hover:text-[#C8102E]" style="color:#0F1419;">{{ $rp->title }}</span>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>
</x-admin-layout>
