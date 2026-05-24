<x-admin-layout pageTitle="Hotel Rooms">

    <div class="mb-6">
        <a href="{{ route('admin.hotels.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-3 transition text-sm">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Hotels
        </a>
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">{{ $hotel->name }}</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $hotel->location }}</p>
            </div>
            <a href="{{ route('admin.hotels.rooms.create', $hotel) }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Add Room
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Room Name</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Type</th>
                        <th class="px-5 py-3 text-right text-[10px] font-bold uppercase tracking-widest text-gray-400">Price / Night</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Capacity</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rooms as $room)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($room->image)
                                    <img class="h-9 w-9 rounded-lg object-cover flex-shrink-0" src="{{ Storage::url($room->image) }}" alt="">
                                @endif
                                <div class="text-sm font-semibold text-gray-900">{{ $room->name }}</div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-700 capitalize">
                                {{ $room->room_type }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-right text-sm font-semibold text-gray-900">৳{{ number_format($room->price_per_night) }}</td>
                        <td class="px-5 py-3.5 text-center text-sm text-gray-600">{{ $room->capacity }} guests</td>
                        <td class="px-5 py-3.5 text-center">
                            @if($room->is_active)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Inactive</span>
                            @endif
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('admin.hotels.rooms.edit', [$hotel, $room]) }}" class="text-gray-500 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-100 transition" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.hotels.rooms.destroy', [$hotel, $room]) }}" method="POST" onsubmit="return confirm('Delete this room?');">
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
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">No rooms yet. Add the first room for this hotel!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($rooms->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50">
            {{ $rooms->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
