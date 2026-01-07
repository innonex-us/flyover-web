<x-admin-layout>
    <div class="mb-6">
        <a href="{{ route('admin.contact-messages.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Messages
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-100 max-w-3xl mx-auto">
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Message Details</h3>
            <span class="text-sm text-gray-500">{{ $contactMessage->created_at->format('M d, Y h:i A') }}</span>
        </div>
        
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">From</label>
                    <div class="text-gray-900 font-semibold">{{ $contactMessage->first_name }} {{ $contactMessage->last_name }}</div>
                    <div class="text-gray-600 text-sm">{{ $contactMessage->email }}</div>
                    <div class="text-gray-600 text-sm">{{ $contactMessage->phone }}</div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Status</label>
                    <div>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $contactMessage->is_read ? 'bg-gray-100 text-gray-800' : 'bg-green-100 text-green-800' }}">
                            {{ $contactMessage->is_read ? 'Read' : 'New' }}
                        </span>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-1">Subject</label>
                <div class="text-gray-900 text-lg font-medium">{{ $contactMessage->subject ?? 'No Subject' }}</div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-500 mb-2">Message</label>
                <div class="bg-gray-50 p-4 rounded-lg text-gray-700 whitespace-pre-wrap leading-relaxed border border-gray-100">
                    {{ $contactMessage->message }}
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex justify-end space-x-3">
                <a href="mailto:{{ $contactMessage->email }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Reply via Email
                </a>
                <form action="{{ route('admin.contact-messages.destroy', $contactMessage->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-white border border-red-200 text-red-600 px-4 py-2 rounded-lg hover:bg-red-50 transition">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-admin-layout>
