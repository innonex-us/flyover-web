<x-admin-layout>

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color:#0F1419;">Custom Requests</h1>
            <p class="text-sm mt-0.5" style="color:#6B7280;">
                {{ $requests->total() }} {{ Str::plural('request', $requests->total()) }} total
            </p>
        </div>
        @php
            $pendingCount = $requests->getCollection()->where('status', 'pending')->count();
        @endphp
        @if($pendingCount > 0)
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold self-start sm:self-center"
                  style="background:#FEF3C7;color:#92400E;">
                {{ $pendingCount }} pending
            </span>
        @endif
    </div>

    {{-- Table Card --}}
    <div class="rounded-xl overflow-hidden" style="background:#fff;border:1px solid #E6E8EC;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#F4F5F7;border-bottom:1px solid #E6E8EC;">
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Customer</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Package</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Message</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Date</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Status</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr class="transition hover:bg-[#F4F5F7]" style="border-bottom:1px solid #E6E8EC;">

                        {{-- Customer --}}
                        <td class="px-5 py-4">
                            <div class="font-semibold" style="color:#0F1419;">{{ $req->name }}</div>
                            <div class="text-xs mt-0.5" style="color:#6B7280;">{{ $req->email }}</div>
                            @if($req->phone)
                                <div class="text-xs mt-0.5" style="color:#6B7280;">{{ $req->phone }}</div>
                            @endif
                        </td>

                        {{-- Package --}}
                        <td class="px-5 py-4">
                            @if($req->package)
                                <a href="{{ route('packages.show', $req->package->slug) }}"
                                   target="_blank"
                                   class="font-medium text-xs transition hover:underline"
                                   style="color:#C8102E;">
                                    {{ $req->package->title }}
                                </a>
                            @else
                                <span class="text-xs" style="color:#6B7280;">General / Deleted</span>
                            @endif
                        </td>

                        {{-- Message snippet --}}
                        <td class="px-5 py-4">
                            <p class="text-xs max-w-xs truncate" style="color:#6B7280;" title="{{ $req->message }}">
                                {{ $req->message }}
                            </p>
                        </td>

                        {{-- Date --}}
                        <td class="px-5 py-4 whitespace-nowrap" style="color:#6B7280;">
                            {{ $req->created_at->format('M d, Y') }}
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-4 text-center">
                            @if($req->status === 'contacted')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                      style="background:#D1FAE5;color:#065F46;">
                                    Contacted
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                      style="background:#FEF3C7;color:#92400E;">
                                    Pending
                                </span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.customizations.show', $req) }}"
                                   class="text-xs font-semibold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                   style="background:#F4F5F7;color:#0F1419;border:1px solid #E6E8EC;">
                                    View
                                </a>
                                @if($req->status === 'pending')
                                    <form action="{{ route('admin.customizations.update', $req) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="contacted">
                                        <button type="submit"
                                                class="text-xs font-semibold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                                style="background:#D1FAE5;color:#065F46;border:1px solid #A7F3D0;">
                                            Mark Contacted
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-16 text-center" style="color:#6B7280;">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <p class="font-medium">No custom requests yet.</p>
                            <p class="text-xs mt-1">Custom trip inquiries from visitors will appear here.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($requests->hasPages())
        <div class="px-5 py-4" style="border-top:1px solid #E6E8EC;background:#F4F5F7;">
            {{ $requests->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
