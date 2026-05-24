<x-app-layout>
    @php
        $user = Auth::user();
        $bookings = \App\Models\Booking::where('user_id', $user->id)
            ->with('payable')
            ->latest()
            ->take(5)
            ->get();
    @endphp

    <div class="min-h-screen pb-16" style="background:#F9F6EF;">

        {{-- Hero strip --}}
        <section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="py-10">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <p class="section-eyebrow mb-2">My Account</p>
                <h1 class="font-extrabold text-3xl text-gray-900 mb-1" style="font-family:'Merriweather',Georgia,serif;">
                    Welcome, {{ $user->name }}
                </h1>
                <p class="text-sm" style="color:#7A7166;">{{ $user->email }}</p>
            </div>
        </section>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-8">

            {{-- Quick links --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @foreach([
                    ['Tours', 'Browse all holiday packages', 'packages.index', 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064', 'bg-red-50', 'text-red-600'],
                    ['Visas', 'Apply for a visa', 'visas.index', 'M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2', 'bg-blue-50', 'text-blue-600'],
                    ['Custom Plan', 'Build your own itinerary', 'customize.index', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01', 'bg-green-50', 'text-green-600'],
                ] as [$title, $desc, $route, $icon, $bg, $color])
                <a href="{{ route($route) }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5 flex items-center gap-4 hover:border-red-200 transition group">
                    <div class="w-12 h-12 {{ $bg }} rounded-xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                    </div>
                    <div>
                        <p class="font-bold text-gray-900 text-sm">{{ $title }}</p>
                        <p class="text-xs text-gray-500">{{ $desc }}</p>
                    </div>
                </a>
                @endforeach
            </div>

            {{-- Bookings --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="font-bold text-gray-900 flex items-center gap-2">
                        <span class="w-1 h-5 rounded-full flex-shrink-0" style="background:#C8102E;"></span>
                        My Bookings
                    </h2>
                    <span class="text-xs text-gray-400">Recent {{ $bookings->count() }} of {{ \App\Models\Booking::where('user_id', $user->id)->count() }}</span>
                </div>

                @if($bookings->isEmpty())
                <div class="px-6 py-12 text-center">
                    <div class="w-14 h-14 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    </div>
                    <p class="font-semibold text-gray-900 mb-1">No bookings yet</p>
                    <p class="text-sm text-gray-500 mb-5">Start exploring and book your next adventure.</p>
                    <a href="{{ route('packages.index') }}" class="btn-primary inline-flex">Browse Tours</a>
                </div>
                @else
                <div class="divide-y divide-gray-50">
                    @foreach($bookings as $booking)
                    <div class="px-6 py-4 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0" style="background:#FFF1F2;">
                                <svg class="w-5 h-5" style="color:#C8102E;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-gray-900 text-sm truncate">
                                    @if($booking->payable)
                                        {{ $booking->payable->title ?? ($booking->payable->country . ' Visa') }}
                                    @else
                                        Booking #{{ $booking->id }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    {{ $booking->booking_date->format('M d, Y') }} &middot; {{ $booking->quantity }} person(s)
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4 flex-shrink-0">
                            <div class="text-right hidden sm:block">
                                <p class="font-bold text-sm" style="color:#C8102E;">৳{{ number_format($booking->total_amount ?? 0) }}</p>
                                <span class="inline-block text-[10px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full
                                    {{ $booking->payment_status === 'paid' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $booking->payment_status }}
                                </span>
                            </div>
                            <a href="{{ route('bookings.confirmation', $booking) }}"
                               class="text-xs font-semibold text-gray-400 hover:text-red-600 transition flex items-center gap-1">
                                View <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Profile quick-edit link --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-extrabold text-lg text-white flex-shrink-0" style="background:#C8102E;">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                    </div>
                </div>
                <a href="{{ route('profile.edit') }}" class="btn-outline text-sm">Edit Profile</a>
            </div>

        </div>
    </div>
</x-app-layout>
