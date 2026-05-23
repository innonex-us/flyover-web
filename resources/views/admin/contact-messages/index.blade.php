<x-admin-layout>

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="flex items-center gap-3">
            <div>
                <h1 class="text-2xl font-bold" style="color:#0F1419;">Messages</h1>
                <p class="text-sm mt-0.5" style="color:#6B7280;">
                    {{ $messages->total() }} {{ Str::plural('message', $messages->total()) }} total
                </p>
            </div>
            @php
                $unreadCount = $messages->getCollection()->where('is_read', false)->count();
            @endphp
            @if($unreadCount > 0)
                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold"
                      style="background:#FEF3C7;color:#92400E;">
                    {{ $unreadCount }} unread
                </span>
            @endif
        </div>
    </div>

    {{-- Table Card --}}
    <div class="rounded-xl overflow-hidden" style="background:#fff;border:1px solid #E6E8EC;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#F4F5F7;border-bottom:1px solid #E6E8EC;">
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Sender</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Subject</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Date</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $message)
                    <tr class="transition hover:bg-[#F4F5F7]"
                        style="border-bottom:1px solid #E6E8EC;{{ !$message->is_read ? 'background:#FFFBEB;' : '' }}">

                        {{-- Sender --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                {{-- Unread dot --}}
                                <div class="w-2 h-2 rounded-full flex-shrink-0"
                                     style="{{ !$message->is_read ? 'background:#C8102E;' : 'background:#E6E8EC;' }}"></div>
                                <div>
                                    <div class="font-semibold" style="color:#0F1419;">
                                        {{ $message->first_name }} {{ $message->last_name }}
                                    </div>
                                    <div class="text-xs mt-0.5" style="color:#6B7280;">
                                        {{ $message->email }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Subject --}}
                        <td class="px-5 py-4" style="color:#0F1419;">
                            {{ Str::limit($message->subject ?? 'No Subject', 40) }}
                        </td>

                        {{-- Date --}}
                        <td class="px-5 py-4 whitespace-nowrap" style="color:#6B7280;">
                            {{ $message->created_at->format('M d, Y') }}
                            <div class="text-xs" style="color:#6B7280;">
                                {{ $message->created_at->format('h:i A') }}
                            </div>
                        </td>

                        {{-- Status badge --}}
                        <td class="px-5 py-4 text-center">
                            @if($message->is_read)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                      style="background:#F3F4F6;color:#6B7280;">
                                    Read
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                      style="background:#FEF3C7;color:#92400E;">
                                    New
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.contact-messages.show', $message->id) }}"
                                   class="text-xs font-semibold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                   style="background:#F4F5F7;color:#0F1419;border:1px solid #E6E8EC;">
                                    View
                                </a>
                                <form action="{{ route('admin.contact-messages.destroy', $message->id) }}" method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Delete this message? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs font-semibold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                            style="background:#FEF2F2;color:#C8102E;border:1px solid #FECACA;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center" style="color:#6B7280;">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <p class="font-medium">No messages yet.</p>
                            <p class="text-xs mt-1">Messages from the contact form will appear here.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($messages->hasPages())
        <div class="px-5 py-4" style="border-top:1px solid #E6E8EC;background:#F4F5F7;">
            {{ $messages->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
