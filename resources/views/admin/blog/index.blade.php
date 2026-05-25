<x-admin-layout pageTitle="Blog Posts">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-5">
        {{-- Filter tabs --}}
        @php $statusFilter = request('status', ''); @endphp
        <div class="inline-flex gap-1">
            @foreach([''=>'All', 'published'=>'Published', 'draft'=>'Drafts'] as $val => $label)
                <a href="{{ request()->fullUrlWithQuery(['status' => $val, 'search' => request('search')]) }}"
                   class="px-4 py-1.5 rounded-full text-xs font-semibold transition {{ $statusFilter === $val ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-500 hover:bg-gray-200' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="flex items-center gap-2 w-full md:w-auto">
            {{-- Search --}}
            <form action="{{ route('admin.blog.index') }}" method="GET" class="flex gap-2 w-full md:w-auto">
                @if(request('status')) <input type="hidden" name="status" value="{{ request('status') }}"> @endif
                <div class="relative flex-1 md:flex-none">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, author..."
                           class="w-full md:w-60 pl-9 pr-4 py-2 text-sm border-gray-200 focus:border-red-500 focus:ring-red-200 rounded-lg shadow-sm">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded-lg text-sm font-semibold transition">Search</button>
                @if(request('search'))
                    <a href="{{ route('admin.blog.index', array_filter(['status' => request('status')])) }}" class="text-gray-400 hover:text-gray-600 px-2 py-2 rounded-lg text-sm transition">✕</a>
                @endif
            </form>

            <a href="{{ route('admin.blog.create') }}" class="flex items-center gap-2 px-5 py-2 rounded-xl text-sm font-semibold text-white bg-red-600 hover:bg-red-700 transition shadow-sm whitespace-nowrap">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                New Post
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Post</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Author</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Published</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($post->image)
                                    <img src="{{ Storage::url($post->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="h-10 w-10 rounded-lg flex items-center justify-center flex-shrink-0 bg-gray-100 text-gray-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $post->title }}</div>
                                    <div class="text-xs text-gray-400 font-mono mt-0.5">{{ Str::limit($post->slug, 35) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-500">
                            {{ $post->custom_author ?? ($post->author->name ?? '-') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex flex-col items-center gap-1">
                                @if($post->is_published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">Published</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Draft</span>
                                @endif
                                @if(!($post->seo_title ?? null) || !($post->seo_description ?? null))
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700" title="Missing SEO data">SEO ⚠</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm text-gray-500">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" title="View live" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                <a href="{{ route('admin.blog.edit', $post) }}" title="Edit" class="text-gray-500 hover:text-gray-700 p-1.5 rounded-lg hover:bg-gray-100 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Delete" class="text-red-400 hover:text-red-600 p-1.5 rounded-lg hover:bg-red-50 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sm text-gray-400">No posts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($posts->hasPages())
        <div class="px-5 py-3.5 border-t border-gray-100 bg-gray-50">
            {{ $posts->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
