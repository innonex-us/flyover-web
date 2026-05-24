<x-admin-layout pageTitle="Notifications">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-bold text-gray-900">All Notifications</h1>
            <p class="text-sm text-gray-500 mt-0.5">{{ auth()->user()->unreadNotifications()->count() }} unread</p>
        </div>
        @if(auth()->user()->unreadNotifications()->count() > 0)
        <form method="POST" action="{{ route('admin.notifications.read-all') }}">
            @csrf
            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-semibold text-white transition" style="background:#C8102E;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Mark All as Read
            </button>
        </form>
        @endif
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        @forelse($notifications as $notification)
        @php
            $data     = $notification->data;
            $isUnread = is_null($notification->read_at);
            $color    = match($data['color'] ?? 'gray') {
                'blue'   => ['bg' => 'bg-blue-100',   'text' => 'text-blue-600'],
                'purple' => ['bg' => 'bg-purple-100', 'text' => 'text-purple-600'],
                'green'  => ['bg' => 'bg-green-100',  'text' => 'text-green-600'],
                'yellow' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-600'],
                'red'    => ['bg' => 'bg-red-100',    'text' => 'text-red-600'],
                default  => ['bg' => 'bg-gray-100',   'text' => 'text-gray-600'],
            };
            $iconPath = match($data['icon'] ?? 'bell') {
                'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
                'car'      => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4',
                'building' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
                'document' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                'mail'     => 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z',
                default    => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
            };
        @endphp
        <div class="flex items-start gap-4 px-5 py-4 border-b border-gray-50 hover:bg-gray-50 transition {{ $isUnread ? '' : 'opacity-60' }}">
            {{-- Unread dot --}}
            <div class="mt-3 flex-shrink-0">
                @if($isUnread)
                <span class="w-2 h-2 rounded-full block" style="background:#C8102E;"></span>
                @else
                <span class="w-2 h-2 rounded-full block bg-gray-200"></span>
                @endif
            </div>

            {{-- Icon --}}
            <div class="w-10 h-10 rounded-xl {{ $color['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5 {{ $color['text'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/>
                </svg>
            </div>

            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-sm font-bold text-gray-900">{{ $data['title'] ?? 'Notification' }}</p>
                        <p class="text-sm text-gray-600 mt-0.5">{{ $data['message'] ?? '' }}</p>
                        @if(!empty($data['amount']) && $data['amount'] > 0)
                        <p class="text-xs font-semibold mt-1" style="color:#C8102E;">৳{{ number_format($data['amount']) }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->format('M d, Y · h:i A') }} &middot; {{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if(!empty($data['url']))
                        <form method="POST" action="{{ route('admin.notifications.read', $notification->id) }}">
                            @csrf
                            <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg transition" style="background:#FFF1F2; color:#C8102E;">
                                {{ $isUnread ? 'View & Mark Read' : 'View' }}
                            </button>
                        </form>
                        @endif
                        <form method="POST" action="{{ route('admin.notifications.destroy', $notification->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-1.5 rounded-lg text-gray-300 hover:text-red-500 hover:bg-red-50 transition" title="Delete">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="py-20 text-center">
            <svg class="w-12 h-12 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            <p class="text-gray-500 font-semibold">No notifications yet</p>
            <p class="text-sm text-gray-400 mt-1">New bookings and requests will appear here.</p>
        </div>
        @endforelse
    </div>

    @if($notifications->hasPages())
    <div class="mt-4">
        {{ $notifications->links() }}
    </div>
    @endif

</x-admin-layout>
