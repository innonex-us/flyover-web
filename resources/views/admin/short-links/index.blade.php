<x-admin-layout pageTitle="Short Links">

    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('admin.short-links.create') }}" class="ml-auto bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 px-5 rounded-xl transition shadow-sm hover:shadow-md flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Create Short Link
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Short URL</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Label</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Target URL</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Clicks</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Created</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($links as $link)
                    @php $shortUrl = config('app.url') . '/s/' . $link->code; @endphp
                    <tr class="hover:bg-gray-50 transition" x-data="{ copied: false }">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-mono text-red-600 font-semibold">{{ $shortUrl }}</span>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-700">{{ $link->label ?: '—' }}</td>
                        <td class="px-5 py-3.5">
                            <a href="{{ $link->url }}" target="_blank" class="text-sm text-blue-600 hover:underline max-w-xs truncate block" style="max-width:200px;">
                                {{ Str::limit($link->url, 50) }}
                            </a>
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                {{ number_format($link->clicks) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-500">{{ $link->created_at->format('M d, Y') }}</td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <button
                                    type="button"
                                    @click="navigator.clipboard.writeText('{{ $shortUrl }}').then(() => { copied = true; setTimeout(() => copied = false, 2000) })"
                                    class="text-xs font-semibold px-3 py-1.5 rounded-lg transition"
                                    :class="copied ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'"
                                    x-text="copied ? 'Copied!' : 'Copy'"
                                ></button>
                                <form action="{{ route('admin.short-links.destroy', $link) }}" method="POST" onsubmit="return confirm('Delete this short link?');">
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
                        <td colspan="6" class="px-5 py-12 text-center text-sm text-gray-400">No short links yet. Create your first one!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($links->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50">
            {{ $links->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
