<x-admin-layout>
    <div class="mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Visas</h2>
            <p class="text-sm text-gray-500 mt-1">Manage all visa services</p>
        </div>
        <a href="{{ route('admin.visas.create') }}" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition shadow-lg hover:shadow-xl flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add New Visa
        </a>
    </div>

    @if(session('success'))
        <div class="mb-8 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-gray-50 text-gray-500 text-sm uppercase font-semibold tracking-wider">
                    <tr>
                        <th class="px-6 py-4 text-left">Country / Type</th>
                        <th class="px-6 py-4 text-left">Price</th>
                        <th class="px-6 py-4 text-left">Processing Time</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($visas as $visa)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-lg object-cover" src="{{ Storage::url($visa->thumbnail) }}" alt="">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $visa->country }}</div>
                                    <div class="text-xs text-gray-500 uppercase tracking-wide bg-gray-100 px-2 py-0.5 rounded-full inline-block mt-0.5">{{ $visa->type }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            ৳{{ number_format($visa->price) }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $visa->processing_time }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium">
                            <div class="flex justify-center space-x-3">
                                <a href="{{ route('admin.visas.edit', $visa) }}" class="text-indigo-600 hover:text-indigo-900">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.visas.destroy', $visa) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            No visas found. Start by adding one!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($visas->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $visas->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
