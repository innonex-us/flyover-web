<x-admin-layout pageTitle="Messages">
    @php $unreadCount = $messages->getCollection()->where('is_read', false)->count(); @endphp

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-5">
        {{-- Status tabs --}}
        @php $statusFilter = request('status', ''); @endphp
        <div class="flex items-center gap-2">
            <div class="inline-flex gap-1">
                @foreach([''=>'All', 'unread'=>'Unread', 'read'=>'Read'] as $val => $label)
                    <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'search' => request('search')]) }}"
                       class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ $statusFilter === $val ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
            @if($unreadCount > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">{{ $unreadCount }} unread</span>
            @endif
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            {{-- Search --}}
            <form action="{{ route('admin.contact-messages.index') }}" method="GET" class="flex gap-2 flex-1 md:flex-none">
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                <div class="relative flex-1 md:flex-none">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, email, subject..."
                           class="w-full md:w-60 pl-9 pr-4 py-2 text-sm border-gray-200 focus:border-red-500 focus:ring-red-200 rounded-lg shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded-lg text-sm font-semibold transition">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.contact-messages.index', array_filter(['status' => request('status')])) }}" class="text-gray-400 hover:text-gray-600 px-2 py-2 rounded-lg text-sm transition">✕</a>
                @endif
            </form>

            @if($unreadCount > 0)
            <form action="{{ route('admin.contact-messages.markAllRead') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 transition whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Mark all read
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Date</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">From</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Subject</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-5 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($messages as $message)
                    <tr class="hover:bg-gray-50 transition {{ !$message->is_read ? 'border-l-2 border-red-400' : '' }}">
                        <td class="px-5 py-3.5 text-xs text-gray-500">
                            {{ $message->created_at->format('M d, Y') }}<br>
                            <span class="text-gray-400">{{ $message->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-semibold text-gray-900">{{ $message->first_name }} {{ $message->last_name }}</div>
                            <div class="text-xs text-gray-400">{{ $message->email }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-600">
                            {{ Str::limit($message->subject ?? 'No Subject', 40) }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($message->is_read)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-gray-400">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-300 inline-block"></span>
                                    Read
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold text-green-700">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500 inline-block"></span>
                                    New
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.contact-messages.show', $message->id) }}"
                                   class="inline-flex items-center text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition">
                                    View
                                </a>
                                <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Delete?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="inline-flex items-center text-xs font-semibold text-red-600 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400">No messages yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50">
            {{ $messages->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
