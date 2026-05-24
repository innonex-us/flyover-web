<x-admin-layout pageTitle="Messages">
    @php $unreadCount = $messages->getCollection()->where('is_read', false)->count(); @endphp

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            @if($unreadCount > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">{{ $unreadCount }} new</span>
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
