<x-admin-layout>

    {{-- Back + heading --}}
    <div class="mb-6">
        <a href="{{ route('admin.customizations.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium transition hover:opacity-70 mb-2"
           style="color:#6B7280;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Requests
        </a>
        <h1 class="text-2xl font-bold" style="color:#0F1419;">
            Request #{{ $customization->id }}
        </h1>
        <p class="text-sm mt-0.5" style="color:#6B7280;">
            Submitted {{ $customization->created_at->format('M d, Y · h:i A') }}
        </p>
    </div>

    <div class="flex flex-col xl:flex-row gap-6">

        {{-- ── Left: Request Details ── --}}
        <div class="flex-1 min-w-0 space-y-5">

            {{-- Customer & Package --}}
            <div class="rounded-xl p-6" style="background:#fff;border:1px solid #E6E8EC;">
                <h3 class="text-xs font-semibold uppercase tracking-wider mb-4 pb-3"
                    style="color:#6B7280;border-bottom:1px solid #E6E8EC;">
                    Customer &amp; Package
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#6B7280;">Customer</p>
                        <p class="font-bold text-lg uppercase" style="color:#0F1419;">{{ $customization->name }}</p>
                        <a href="mailto:{{ $customization->email }}"
                           class="text-sm transition hover:opacity-70 block mt-0.5"
                           style="color:#C8102E;">{{ $customization->email }}</a>
                        @if($customization->phone)
                            <p class="text-sm mt-0.5" style="color:#6B7280;">{{ $customization->phone }}</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#6B7280;">Target Package</p>
                        @if($customization->package)
                            <a href="{{ route('packages.show', $customization->package->slug) }}"
                               target="_blank"
                               class="font-bold transition hover:underline"
                               style="color:#C8102E;">
                                {{ $customization->package->title }}
                            </a>
                            @if($customization->package->location)
                                <p class="text-sm mt-0.5" style="color:#6B7280;">{{ $customization->package->location }}</p>
                            @endif
                        @else
                            <p style="color:#6B7280;">General / Deleted</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Travel Details (from structured details JSON) --}}
            @if($customization->details)
            <div class="rounded-xl p-6" style="background:#fff;border:1px solid #E6E8EC;">
                <h3 class="text-xs font-semibold uppercase tracking-wider mb-4 pb-3"
                    style="color:#6B7280;border-bottom:1px solid #E6E8EC;">
                    Travel Details
                </h3>

                {{-- PAX & Hotel grid --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 rounded-xl p-4"
                     style="background:#F4F5F7;border:1px solid #E6E8EC;">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#6B7280;">Adults</p>
                        <p class="font-bold text-lg" style="color:#0F1419;">{{ $customization->details['adults'] ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#6B7280;">Children</p>
                        <p class="font-bold text-lg" style="color:#0F1419;">{{ $customization->details['children'] ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#6B7280;">Infants</p>
                        <p class="font-bold text-lg" style="color:#0F1419;">{{ $customization->details['infants'] ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#6B7280;">Hotel Type</p>
                        <p class="font-bold uppercase" style="color:#C8102E;">
                            {{ str_replace('-', ' ', $customization->details['hotel_type'] ?? 'Not Specified') }}
                        </p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-1" style="color:#6B7280;">Travel Date</p>
                        <p class="font-bold" style="color:#0F1419;">
                            {{ isset($customization->details['travel_date']) && $customization->details['travel_date']
                                ? \Carbon\Carbon::parse($customization->details['travel_date'])->format('M d, Y')
                                : 'Flexible' }}
                        </p>
                    </div>
                </div>

                {{-- Planned Route --}}
                @if(isset($customization->details['destinations']) && is_array($customization->details['destinations']))
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest mb-3" style="color:#6B7280;">Planned Route</p>
                        <div class="space-y-3">
                            @foreach($customization->details['destinations'] as $dest)
                                <div class="rounded-xl overflow-hidden" style="border:1px solid #E6E8EC;">
                                    <div class="flex items-center justify-between px-4 py-2"
                                         style="background:#F4F5F7;border-bottom:1px solid #E6E8EC;">
                                        <span class="text-xs font-bold uppercase" style="color:#0F1419;">
                                            {{ $dest['country'] ?? 'Country' }}
                                        </span>
                                        <span class="text-[10px] font-black px-2 py-0.5 rounded-full"
                                              style="background:#fff;border:1px solid #E6E8EC;color:#6B7280;">
                                            {{ $dest['nights'] ?? 0 }} NIGHTS
                                        </span>
                                    </div>
                                    <div class="p-3">
                                        <ul class="space-y-1.5">
                                            @foreach($dest['cities'] ?? [] as $city)
                                                <li class="flex items-center justify-between text-sm">
                                                    <div class="flex items-center gap-2" style="color:#0F1419;">
                                                        <svg class="w-3 h-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" style="color:#C8102E;">
                                                            <path fill-rule="evenodd"
                                                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                                  clip-rule="evenodd"/>
                                                        </svg>
                                                        {{ $city['name'] }}
                                                    </div>
                                                    <span class="text-xs font-semibold" style="color:#6B7280;">
                                                        {{ $city['nights'] }} Nights
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
            @endif

            {{-- Message / Requirements --}}
            <div class="rounded-xl p-6" style="background:#fff;border:1px solid #E6E8EC;">
                <h3 class="text-xs font-semibold uppercase tracking-wider mb-4 pb-3"
                    style="color:#6B7280;border-bottom:1px solid #E6E8EC;">
                    Message / Requirements
                </h3>
                <div class="rounded-xl p-4 whitespace-pre-wrap leading-relaxed text-sm"
                     style="background:#FFF5F5;border:1px solid #FECACA;color:#0F1419;">
                    {{ $customization->message }}
                </div>
            </div>

        </div>

        {{-- ── Right: Status Sidebar ── --}}
        <div class="xl:w-72 w-full space-y-5">

            {{-- Status Card --}}
            <div class="rounded-xl p-6 sticky top-24" style="background:#fff;border:1px solid #E6E8EC;">
                <h3 class="text-xs font-semibold uppercase tracking-wider mb-4 pb-3"
                    style="color:#6B7280;border-bottom:1px solid #E6E8EC;">
                    Request Status
                </h3>

                {{-- Current status badge --}}
                <div class="mb-5">
                    @if($customization->status === 'contacted')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold"
                              style="background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0;">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Contacted
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-sm font-bold"
                              style="background:#FEF3C7;color:#92400E;border:1px solid #FDE68A;">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                      clip-rule="evenodd"/>
                            </svg>
                            Pending
                        </span>
                    @endif
                </div>

                {{-- Mark contacted form (status update) --}}
                @if($customization->status !== 'contacted')
                    <form action="{{ route('admin.customizations.update', $customization) }}" method="POST" class="mb-4">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="block text-xs font-semibold mb-1" style="color:#6B7280;">Update Status</label>
                            <select name="status"
                                    class="w-full rounded-lg px-3 py-2.5 text-sm outline-none transition"
                                    style="border:1px solid #E6E8EC;color:#0F1419;background:#fff;">
                                <option value="pending" {{ $customization->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="contacted" {{ $customization->status === 'contacted' ? 'selected' : '' }}>Contacted</option>
                            </select>
                        </div>
                        <button type="submit"
                                class="w-full py-2.5 rounded-lg text-sm font-bold text-white transition hover:opacity-90 active:scale-95 shadow-sm"
                                style="background:#C8102E;">
                            Save Status
                        </button>
                        <p class="text-[10px] font-bold text-center uppercase tracking-widest mt-2" style="color:#6B7280;">
                            This will close the lead
                        </p>
                    </form>
                @else
                    <div class="text-center py-4 rounded-xl"
                         style="border:1px dashed #E6E8EC;background:#F4F5F7;">
                        <p class="text-xs font-bold uppercase tracking-wider" style="color:#6B7280;">Lead is closed</p>
                    </div>
                @endif

                {{-- Quick contact buttons --}}
                <div class="mt-5 space-y-2" style="border-top:1px solid #E6E8EC;padding-top:1.25rem;">
                    <p class="text-[10px] font-semibold uppercase tracking-wider mb-2" style="color:#6B7280;">Quick Contact</p>

                    <a href="mailto:{{ $customization->email }}"
                       class="flex items-center gap-2 w-full px-3 py-2.5 rounded-lg text-sm font-semibold transition hover:opacity-80"
                       style="background:#F4F5F7;color:#0F1419;border:1px solid #E6E8EC;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Send Email
                    </a>

                    @if($customization->phone)
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $customization->phone) }}"
                       target="_blank"
                       class="flex items-center gap-2 w-full px-3 py-2.5 rounded-lg text-sm font-semibold text-white transition hover:opacity-90"
                       style="background:#25D366;">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                            <path d="M12 0C5.373 0 0 5.373 0 12c0 2.125.558 4.122 1.532 5.86L.057 23.215a.75.75 0 00.918.899l5.487-1.437A11.942 11.942 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75a9.737 9.737 0 01-4.946-1.349l-.355-.21-3.676.962.98-3.58-.232-.368A9.738 9.738 0 012.25 12C2.25 6.615 6.615 2.25 12 2.25S21.75 6.615 21.75 12 17.385 21.75 12 21.75z"/>
                        </svg>
                        WhatsApp
                    </a>
                    @endif
                </div>
            </div>

        </div>
    </div>

</x-admin-layout>
