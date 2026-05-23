<x-admin-layout>

    {{-- Page header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color:#0F1419;">Visa Services</h1>
            <p class="mt-0.5 text-sm" style="color:#6B7280;">
                {{ $visas->total() }} {{ Str::plural('service', $visas->total()) }} total
            </p>
        </div>
        <a href="{{ route('admin.visas.create') }}"
           class="inline-flex items-center gap-2 rounded-lg px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2"
           style="background:#C8102E; focus-ring-color:#C8102E;">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add Visa
        </a>
    </div>

    {{-- Table card --}}
    <div class="overflow-hidden rounded-xl" style="background:#fff; border:1px solid #E6E8EC;">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap text-sm">
                <thead>
                    <tr style="border-bottom:1px solid #E6E8EC; background:#F4F5F7;">
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Country</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Processing Time</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Fee</th>
                        <th class="px-6 py-3 text-center text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visas as $visa)
                    <tr class="group transition-colors hover:bg-[#F4F5F7]" style="border-bottom:1px solid #E6E8EC;">

                        {{-- Country + flag / thumbnail --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($visa->thumbnail)
                                    <img class="h-9 w-9 flex-shrink-0 rounded-lg object-cover"
                                         src="{{ Str::startsWith($visa->thumbnail, 'http') ? $visa->thumbnail : Storage::url($visa->thumbnail) }}"
                                         alt="{{ $visa->country }}">
                                @else
                                    <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg text-xl"
                                         style="background:#F4F5F7;">
                                        🌐
                                    </div>
                                @endif
                                <span class="font-semibold" style="color:#0F1419;">{{ $visa->country }}</span>
                            </div>
                        </td>

                        {{-- Type --}}
                        <td class="px-6 py-4" style="color:#6B7280;">
                            {{ $visa->type ?? '—' }}
                        </td>

                        {{-- Processing time (validity / max stay) --}}
                        <td class="px-6 py-4" style="color:#6B7280;">
                            @if($visa->validity || $visa->maximum_stay)
                                {{ $visa->validity ?? '—' }}&nbsp;/&nbsp;{{ $visa->maximum_stay ?? '—' }}
                            @else
                                —
                            @endif
                        </td>

                        {{-- Fee --}}
                        <td class="px-6 py-4 font-medium" style="color:#0F1419;">
                            ৳{{ number_format($visa->price) }}
                        </td>

                        {{-- Status pill --}}
                        <td class="px-6 py-4 text-center">
                            @if($visa->is_active)
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                      style="background:#D1FAE5; color:#065F46;">
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-semibold"
                                      style="background:#F3F4F6; color:#6B7280;">
                                    Inactive
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4 text-right">
                            <div class="inline-flex items-center gap-2">
                                <a href="{{ route('admin.visas.edit', $visa) }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs font-medium transition hover:opacity-80"
                                   style="border-color:#E6E8EC; color:#0F1419;">
                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </a>

                                <form action="{{ route('admin.visas.destroy', $visa) }}" method="POST"
                                      onsubmit="return confirm('Delete this visa service? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs font-medium transition hover:opacity-80"
                                            style="border-color:#FEE2E2; color:#991B1B; background:#FEF2F2;">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center" style="color:#6B7280;">
                            <svg class="mx-auto mb-3 h-10 w-10 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="font-medium">No visa services yet.</p>
                            <p class="mt-1 text-xs">Click <strong>Add Visa</strong> to create your first one.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($visas->hasPages())
        <div class="px-6 py-4" style="border-top:1px solid #E6E8EC; background:#F4F5F7;">
            {{ $visas->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
