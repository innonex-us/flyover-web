<x-admin-layout>
    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <div>
            <h2 class="text-2xl font-bold" style="color:#0F1419;">Tour Packages
                <span class="ml-2 text-base font-semibold px-2.5 py-0.5 rounded-full align-middle" style="background:#F3F4F6;color:#6B7280;">{{ $packages->total() }}</span>
            </h2>
            <p class="text-sm mt-0.5" style="color:#6B7280;">Manage all tour packages</p>
        </div>
        <a href="{{ route('admin.packages.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-sm font-bold shadow-sm transition hover:opacity-90"
           style="background:#C8102E;color:#fff;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Package
        </a>
    </div>

    {{-- Table Card --}}
    <div class="rounded-xl border shadow-sm overflow-hidden" style="background:#fff;border-color:#E6E8EC;">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr style="background:#F9FAFB;border-bottom:1px solid #E6E8EC;">
                        <th class="px-6 py-3.5 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Package</th>
                        <th class="px-6 py-3.5 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Price</th>
                        <th class="px-6 py-3.5 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Duration</th>
                        <th class="px-6 py-3.5 text-center text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Status</th>
                        <th class="px-6 py-3.5 text-center text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y" style="border-color:#E6E8EC;">
                    @forelse($packages as $package)
                    <tr class="hover:bg-gray-50 transition-colors">
                        {{-- Thumbnail + Title --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <img
                                    class="h-10 w-10 rounded-lg object-cover flex-shrink-0"
                                    src="{{ Str::startsWith($package->thumbnail, 'http') ? $package->thumbnail : Storage::url($package->thumbnail) }}"
                                    alt="{{ $package->title }}">
                                <div>
                                    <div class="text-sm font-semibold" style="color:#0F1419;">{{ $package->title }}</div>
                                    <div class="text-xs mt-0.5" style="color:#6B7280;">{{ $package->location }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- Price --}}
                        <td class="px-6 py-4 text-sm font-semibold" style="color:#0F1419;">
                            ৳{{ number_format($package->price) }}
                        </td>

                        {{-- Duration --}}
                        <td class="px-6 py-4 text-sm" style="color:#6B7280;">
                            {{ $package->duration_days }} Days
                        </td>

                        {{-- Status --}}
                        <td class="px-6 py-4 text-center">
                            @if($package->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold" style="background:#D1FAE5;color:#065F46;">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold" style="background:#F3F4F6;color:#6B7280;">Inactive</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-center items-center gap-3">
                                <a href="{{ route('admin.packages.edit', $package) }}"
                                   class="p-1.5 rounded-lg transition hover:bg-gray-100"
                                   title="Edit">
                                    <svg class="w-4 h-4" style="color:#18130E;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.packages.destroy', $package) }}" method="POST"
                                      onsubmit="return confirm('Delete this package? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 rounded-lg transition hover:bg-red-50" title="Delete">
                                        <svg class="w-4 h-4" style="color:#C8102E;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-sm" style="color:#6B7280;">
                            No packages found. <a href="{{ route('admin.packages.create') }}" class="font-semibold underline" style="color:#C8102E;">Create one now &rarr;</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($packages->hasPages())
        <div class="px-6 py-4" style="background:#F9FAFB;border-top:1px solid #E6E8EC;">
            {{ $packages->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
