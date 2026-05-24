<x-admin-layout>
    @php $pendingCount = $requests->getCollection()->where('status', 'pending')->count(); @endphp
    <div class="flex items-center gap-3 mb-6">
        <h2 class="text-2xl font-bold" style="color:#0F1419;">Custom Requests</h2>
        <span class="px-2.5 py-0.5 text-xs font-bold rounded-full" style="background:#F3F4F6;color:#6B7280;">{{ $requests->total() }}</span>
        @if($pendingCount > 0)
            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full" style="background:#FEF3C7;color:#92400E;">{{ $pendingCount }} pending</span>
        @endif
    </div>

    <div class="rounded-xl overflow-hidden" style="background:#fff;border:1px solid #E6E8EC;">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr style="background:#F9FAFB;border-bottom:1px solid #E6E8EC;">
                        <th class="px-5 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Date</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Customer</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Package</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Message</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Status</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $req)
                    <tr class="transition hover:bg-gray-50" style="border-bottom:1px solid #E6E8EC;">
                        <td class="px-5 py-3.5 text-xs whitespace-nowrap" style="color:#6B7280;">
                            {{ $req->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-semibold" style="color:#0F1419;">{{ $req->name }}</div>
                            <div class="text-xs" style="color:#6B7280;">{{ $req->email }}</div>
                            @if($req->phone)
                                <div class="text-xs" style="color:#9CA3AF;">{{ $req->phone }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($req->package)
                                <a href="{{ route('packages.show', $req->package->slug) }}" target="_blank" class="text-sm font-medium transition hover:underline" style="color:#C8102E;">
                                    {{ Str::limit($req->package->title, 30) }}
                                </a>
                            @else
                                <span class="text-sm" style="color:#9CA3AF;">General</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-sm max-w-xs truncate" style="color:#6B7280;" title="{{ $req->message }}">
                            {{ Str::limit($req->message, 50) }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($req->status === 'contacted')
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background:#D1FAE5;color:#065F46;">Contacted</span>
                            @else
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background:#FEF3C7;color:#92400E;">Pending</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.customizations.show', $req) }}"
                                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition"
                                   style="background:#F3F4F6;color:#374151;">View</a>
                                @if($req->status === 'pending')
                                    <form action="{{ route('admin.customizations.update', $req) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="contacted">
                                        <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-semibold transition" style="background:#D1FAE5;color:#065F46;">
                                            ✓ Contacted
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm" style="color:#9CA3AF;">No requests yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
        <div class="px-5 py-3" style="border-top:1px solid #E6E8EC;">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
