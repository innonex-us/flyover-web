<x-admin-layout pageTitle="Coupon Management">
    <div class="max-w-7xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Coupon Codes</h1>
                <p class="text-sm text-gray-500 mt-1">Manage discount codes for all services</p>
            </div>
            <a href="{{ route('admin.coupons.create') }}" class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2.5 rounded-lg transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Create Coupon
            </a>
        </div>

        {{-- Success/Error Messages --}}
        @if(session('success'))
        <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
        @endif

        {{-- Coupons Table --}}
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Code</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Discount</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Applies To</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Usage</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Validity</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($coupons as $coupon)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-2">
                                    <span class="font-mono font-bold text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ $coupon->code }}</span>
                                    @if($coupon->description)
                                    <span class="text-sm text-gray-500 truncate max-w-xs" title="{{ $coupon->description }}">{{ $coupon->description }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4">
                                <span class="font-semibold text-red-600">{{ $coupon->getFormattedDiscount() }}</span>
                                @if($coupon->max_discount_amount)
                                <span class="text-xs text-gray-400 block">Max: ৳{{ number_format($coupon->max_discount_amount) }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                @php
                                $labels = [
                                    'all' => 'All Services',
                                    'tours' => 'Tours',
                                    'hotels' => 'Hotels',
                                    'transfers' => 'Transfers',
                                    'visas' => 'Visas',
                                ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $coupon->applies_to === 'all' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $labels[$coupon->applies_to] ?? $coupon->applies_to }}
                                </span>
                                @if($coupon->applicable_ids && count($coupon->applicable_ids) > 0)
                                <span class="text-xs text-gray-400 block mt-1">({{ count($coupon->applicable_ids) }} specific items)</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <div class="text-sm">
                                    <span class="font-semibold text-gray-900">{{ $coupon->used_count }}</span>
                                    @if($coupon->usage_limit)
                                    <span class="text-gray-500"> / {{ $coupon->usage_limit }}</span>
                                    @else
                                    <span class="text-gray-400">unlimited</span>
                                    @endif
                                    used
                                </div>
                                @if($coupon->usage_limit_per_user)
                                <div class="text-xs text-gray-500">{{ $coupon->usage_limit_per_user }} per user</div>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-sm">
                                @if($coupon->starts_at || $coupon->expires_at)
                                    @if($coupon->starts_at && $coupon->starts_at->isFuture())
                                    <span class="text-amber-600">Starts {{ $coupon->starts_at->diffForHumans() }}</span>
                                    @elseif($coupon->expires_at && $coupon->expires_at->isPast())
                                    <span class="text-red-500 font-medium">Expired {{ $coupon->expires_at->diffForHumans() }}</span>
                                    @elseif($coupon->expires_at)
                                    <span class="text-green-600">Expires {{ $coupon->expires_at->diffForHumans() }}</span>
                                    @else
                                    <span class="text-green-600">Started {{ $coupon->starts_at->diffForHumans() }}</span>
                                    @endif
                                @else
                                <span class="text-gray-400">No expiry</span>
                                @endif
                            </td>
                            <td class="px-4 py-4">
                                <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium transition
                                        {{ $coupon->is_active ? 'bg-green-100 text-green-800 hover:bg-green-200' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}">
                                        {{ $coupon->is_active ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-4 py-4 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.coupons.edit', $coupon) }}" class="text-blue-600 hover:text-blue-800 font-medium text-sm">Edit</a>
                                    <span class="text-gray-300">|</span>
                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this coupon?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium text-sm">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                                No coupons created yet. <a href="{{ route('admin.coupons.create') }}" class="text-red-600 hover:underline">Create your first coupon</a>.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            {{-- Pagination --}}
            <div class="px-4 py-3 border-t border-gray-200">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>
</x-admin-layout>
