<x-admin-layout>
    @php $unreadCount = $messages->getCollection()->where('is_read', false)->count(); @endphp
    <div class="flex items-center gap-3 mb-6">
        <h2 class="text-2xl font-bold" style="color:#0F1419;">Messages</h2>
        @if($unreadCount > 0)
            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full" style="background:#FEF3C7;color:#92400E;">{{ $unreadCount }} new</span>
        @endif
    </div>

    <div class="rounded-xl overflow-hidden" style="background:#fff;border:1px solid #E6E8EC;">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr style="background:#F9FAFB;border-bottom:1px solid #E6E8EC;">
                        <th class="px-5 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Date</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">From</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Subject</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Status</th>
                        <th class="px-5 py-3 text-right text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr class="transition hover:bg-gray-50" style="border-bottom:1px solid #E6E8EC;{{ !$message->is_read ? 'border-left:3px solid #C8102E;' : '' }}">
                        <td class="px-5 py-3.5 text-xs" style="color:#6B7280;">
                            {{ $message->created_at->format('M d, Y') }}<br>
                            <span style="color:#9CA3AF;">{{ $message->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-semibold" style="color:#0F1419;">{{ $message->first_name }} {{ $message->last_name }}</div>
                            <div class="text-xs" style="color:#6B7280;">{{ $message->email }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-sm" style="color:#374151;">
                            {{ Str::limit($message->subject ?? 'No Subject', 40) }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($message->is_read)
                                <span class="inline-flex items-center gap-1 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#D1D5DB;"></span>
                                    <span style="color:#6B7280;">Read</span>
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-semibold">
                                    <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#10B981;"></span>
                                    <span style="color:#065F46;">New</span>
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.contact-messages.show', $message->id) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition"
                                   style="background:#F3F4F6;color:#374151;">View</a>
                                <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST" onsubmit="return confirm('Delete?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition" style="background:#FEE2E2;color:#991B1B;">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sm" style="color:#9CA3AF;">No messages yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($messages->hasPages())
        <div class="px-5 py-3" style="border-top:1px solid #E6E8EC;">
            {{ $messages->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
