<x-admin-layout pageTitle="Pick & Drop Routes">

    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.transfer-routes.create') }}" class="ml-auto bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Route
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Name</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Pickup → Drop</th>
                        <th class="px-5 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Price / Person</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($routes as $route)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-semibold text-gray-900">{{ $route->name }}</div>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-sm text-gray-700">
                                <span class="font-medium">{{ $route->pickup_location }}</span>
                                <span class="mx-2 text-gray-400">→</span>
                                <span class="font-medium">{{ $route->drop_location }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-right text-sm font-semibold text-gray-900">৳{{ number_format($route->price_per_person) }}</td>
                        <td class="px-5 py-3.5 text-center">
                            @if($route->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.transfer-routes.edit', $route) }}" class="text-gray-500 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-100 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.transfer-routes.destroy', $route) }}" method="POST" onsubmit="return confirm('Delete this route?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-400 hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400">No transfer routes yet. Add your first one!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($routes->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50">
            {{ $routes->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
