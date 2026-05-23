<x-admin-layout>

    {{-- Back link --}}
    <div class="mb-6">
        <a href="{{ route('admin.contact-messages.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium transition hover:opacity-70"
           style="color:#6B7280;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Messages
        </a>
    </div>

    {{-- Message Card --}}
    <div class="max-w-3xl mx-auto rounded-xl overflow-hidden" style="background:#fff;border:1px solid #E6E8EC;">

        {{-- Card Header --}}
        <div class="px-6 py-4 flex items-center justify-between" style="border-bottom:1px solid #E6E8EC;background:#F4F5F7;">
            <h2 class="text-base font-bold" style="color:#0F1419;">Message Details</h2>
            <div class="flex items-center gap-3">
                @if($contactMessage->is_read)
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
                <span class="text-sm" style="color:#6B7280;">
                    {{ $contactMessage->created_at->format('M d, Y · h:i A') }}
                </span>
            </div>
        </div>

        {{-- Card Body --}}
        <div class="p-6 space-y-6">

            {{-- Sender Info Box --}}
            <div class="rounded-xl p-5" style="background:#F4F5F7;border:1px solid #E6E8EC;">
                <p class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#6B7280;">From</p>
                <div class="flex flex-wrap gap-6">
                    <div>
                        <p class="text-base font-bold" style="color:#0F1419;">
                            {{ $contactMessage->first_name }} {{ $contactMessage->last_name }}
                        </p>
                        <a href="mailto:{{ $contactMessage->email }}"
                           class="text-sm transition hover:opacity-70"
                           style="color:#C8102E;">
                            {{ $contactMessage->email }}
                        </a>
                    </div>
                    @if($contactMessage->phone)
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider mb-1" style="color:#6B7280;">Phone</p>
                        <p class="text-sm font-medium" style="color:#0F1419;">{{ $contactMessage->phone }}</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Subject --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider mb-1.5" style="color:#6B7280;">Subject</p>
                <p class="text-lg font-semibold" style="color:#0F1419;">
                    {{ $contactMessage->subject ?? 'No Subject' }}
                </p>
            </div>

            {{-- Message Body --}}
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider mb-2" style="color:#6B7280;">Message</p>
                <div class="rounded-xl p-5 whitespace-pre-wrap leading-relaxed text-sm"
                     style="background:#F4F5F7;border:1px solid #E6E8EC;color:#0F1419;">
                    {{ $contactMessage->message }}
                </div>
            </div>

            {{-- Actions --}}
            <div class="flex flex-wrap items-center gap-3 pt-2" style="border-top:1px solid #E6E8EC;">
                <a href="mailto:{{ $contactMessage->email }}?subject=Re: {{ urlencode($contactMessage->subject ?? '') }}"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition hover:opacity-90 shadow-sm"
                   style="background:#18130E;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Reply via Email
                </a>

                @if(isset($contactMessage->phone) && $contactMessage->phone)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $contactMessage->phone) }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white transition hover:opacity-90 shadow-sm"
                   style="background:#25D366;">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                        <path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.558 4.122 1.532 5.86L.057 23.215a.75.75 0 00.918.899l5.487-1.437A11.942 11.942 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75a9.737 9.737 0 01-4.946-1.349l-.355-.21-3.676.962.98-3.58-.232-.368A9.738 9.738 0 012.25 12C2.25 6.615 6.615 2.25 12 2.25S21.75 6.615 21.75 12 17.385 21.75 12 21.75z"/>
                    </svg>
                    WhatsApp
                </a>
                @endif

                <form action="{{ route('admin.contact-messages.destroy', $contactMessage->id) }}" method="POST"
                      class="ml-auto"
                      onsubmit="return confirm('Delete this message permanently?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition hover:opacity-80"
                            style="background:#FEF2F2;color:#C8102E;border:1px solid #FECACA;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete Message
                    </button>
                </form>
            </div>
        </div>
    </div>

</x-admin-layout>
