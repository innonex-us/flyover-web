<x-admin-layout pageTitle="Message Detail">
    <div class="mb-5">
        <a href="{{ route('admin.contact-messages.index') }}" class="inline-flex items-center gap-1.5 text-sm transition hover:opacity-70" style="color:#6B7280;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Messages
        </a>
    </div>

    <div class="rounded-xl overflow-hidden max-w-3xl" style="background:#fff;border:1px solid #E6E8EC;">
        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4" style="border-bottom:1px solid #E6E8EC;background:#F9FAFB;">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold text-white" style="background:#C8102E;">
                    {{ strtoupper(substr($contactMessage->first_name, 0, 1)) }}
                </div>
                <div>
                    <div class="font-semibold text-sm" style="color:#0F1419;">{{ $contactMessage->first_name }} {{ $contactMessage->last_name }}</div>
                    <div class="text-xs" style="color:#6B7280;">{{ $contactMessage->email }}{{ $contactMessage->phone ? ' · '.$contactMessage->phone : '' }}</div>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-xs" style="color:#9CA3AF;">{{ $contactMessage->created_at->format('M d, Y · h:i A') }}</span>
                @if($contactMessage->is_read)
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background:#F3F4F6;color:#6B7280;">Read</span>
                @else
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background:#D1FAE5;color:#065F46;">New</span>
                @endif
            </div>
        </div>

        {{-- Subject --}}
        <div class="px-6 pt-5 pb-3">
            <h3 class="text-lg font-bold" style="color:#0F1419;">{{ $contactMessage->subject ?? 'No Subject' }}</h3>
        </div>

        {{-- Body --}}
        <div class="px-6 pb-6">
            <div class="rounded-xl p-5 text-sm leading-relaxed whitespace-pre-wrap" style="background:#F9FAFB;border:1px solid #E6E8EC;color:#374151;">{{ $contactMessage->message }}</div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-end gap-3 px-6 py-4" style="border-top:1px solid #E6E8EC;">
            <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ urlencode($contactMessage->subject ?? 'Your Inquiry') }}"
               class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition hover:opacity-90"
               style="background:#18130E;">
                Reply via Email
            </a>
            <form action="{{ route('admin.contact-messages.destroy', $contactMessage->id) }}" method="POST" onsubmit="return confirm('Delete this message?');">
                @csrf @method('DELETE')
                <button type="submit" class="px-4 py-2 rounded-lg text-sm font-semibold transition" style="background:#FEE2E2;color:#991B1B;">Delete</button>
            </form>
        </div>
    </div>
</x-admin-layout>
