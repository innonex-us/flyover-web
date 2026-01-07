<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Customization Requests</h2>
        <p class="text-sm text-gray-500 mt-1">Manage user customization inquiries</p>
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
                        <th class="px-6 py-4 text-left">Date</th>
                        <th class="px-6 py-4 text-left">Customer</th>
                        <th class="px-6 py-4 text-left">Package</th>
                        <th class="px-6 py-4 text-left">Message</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($requests as $req)
                    <tr class="hover:bg-gray-50 transition">
                         <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $req->created_at->format('M d, Y') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $req->name }}</div>
                            <div class="text-sm text-gray-500">{{ $req->email }}</div>
                            <div class="text-sm text-gray-500">{{ $req->phone }}</div>
                        </td>
                         <td class="px-6 py-4">
                            @if($req->package)
                                <a href="{{ route('packages.show', $req->package->slug) }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 hover:underline">
                                    {{ $req->package->title }}
                                </a>
                            @else
                                <span class="text-gray-400 text-sm">General / Deleted</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-700 max-w-xs truncate" title="{{ $req->message }}">
                                {{ $req->message }}
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($req->status == 'contacted')
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Contacted
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    Pending
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium">
                            @if($req->status == 'pending')
                                <form action="{{ route('admin.customizations.update', $req) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="contacted">
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded transition">
                                        Mark Contacted
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-400">No Actions</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            No requests found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($requests->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $requests->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
