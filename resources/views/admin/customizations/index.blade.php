<x-admin-layout pageTitle="Custom Requests">
    @php $pendingCount = $requests->getCollection()->where('status', 'pending')->count(); @endphp

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gray-100 text-gray-500">{{ $requests->total() }}</span>
            @if($pendingCount > 0)
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">{{ $pendingCount }} pending</span>
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Date</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Customer</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Package</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Message</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5 text-xs text-gray-500 whitespace-nowrap">
                            {{ $req->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-semibold text-gray-900">{{ $req->name }}</div>
                            <div class="text-xs text-gray-400">{{ $req->email }}</div>
                            @if($req->phone)
                                <div class="text-xs text-gray-400">{{ $req->phone }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            @if($req->package)
                                <a href="{{ route('packages.show', $req->package->slug) }}" target="_blank" class="text-sm font-medium text-red-600 hover:underline transition">
                                    {{ Str::limit($req->package->title, 30) }}
                                </a>
                            @else
                                <span class="text-sm text-gray-400">General</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-500 max-w-xs truncate" title="{{ $req->message }}">
                            {{ Str::limit($req->message, 50) }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            @if($req->status === 'contacted')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Contacted</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Pending</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.customizations.show', $req) }}"
                                   class="inline-flex items-center text-xs font-semibold text-gray-600 bg-gray-100 hover:bg-gray-200 px-3 py-1.5 rounded-lg transition">
                                    View
                                </a>
                                @if($req->status === 'pending')
                                    <form action="{{ route('admin.customizations.update', $req) }}" method="POST" class="inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="status" value="contacted">
                                        <button type="submit" class="inline-flex items-center text-xs font-semibold text-green-700 bg-green-50 hover:bg-green-100 px-3 py-1.5 rounded-lg transition">
                                            ✓ Contacted
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">No requests yet.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($requests->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50">
            {{ $requests->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
