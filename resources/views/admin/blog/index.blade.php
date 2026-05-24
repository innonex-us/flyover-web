<x-admin-layout pageTitle="Blog Posts">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <h2 class="text-2xl font-bold" style="color:#0F1419;">Blog Posts</h2>
            <span class="px-2.5 py-0.5 text-xs font-bold rounded-full" style="background:#F3F4F6;color:#6B7280;">{{ $posts->total() }}</span>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold text-white transition hover:opacity-90" style="background:#C8102E;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            New Post
        </a>
    </div>

    {{-- Filter tabs --}}
    @php $statusFilter = request('status', ''); @endphp
    <div class="flex gap-1 mb-5">
        @foreach([''=>'All', 'published'=>'Published', 'draft'=>'Drafts'] as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['status' => $val]) }}"
               class="px-4 py-1.5 rounded-full text-xs font-semibold transition"
               style="{{ $statusFilter === $val ? 'background:#C8102E;color:#fff;' : 'background:#F3F4F6;color:#6B7280;' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="rounded-xl overflow-hidden" style="background:#fff;border:1px solid #E6E8EC;">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr style="background:#F9FAFB;border-bottom:1px solid #E6E8EC;">
                        <th class="px-5 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Post</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Author</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Status</th>
                        <th class="px-5 py-3 text-left text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Published</th>
                        <th class="px-5 py-3 text-center text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr class="transition hover:bg-gray-50" style="border-bottom:1px solid #E6E8EC;">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if($post->image)
                                    <img src="{{ Storage::url($post->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover flex-shrink-0">
                                @else
                                    <div class="h-10 w-10 rounded-lg flex items-center justify-center flex-shrink-0" style="background:#F3F4F6;color:#9CA3AF;">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-semibold" style="color:#0F1419;">{{ $post->title }}</div>
                                    <div class="text-xs font-mono mt-0.5" style="color:#9CA3AF;">{{ Str::limit($post->slug, 35) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm" style="color:#6B7280;">
                            {{ $post->custom_author ?? ($post->author->name ?? '—') }}
                        </td>
                        <td class="px-5 py-3.5 text-center">
                            <div class="flex flex-col items-center gap-1">
                                @if($post->is_published)
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background:#D1FAE5;color:#065F46;">Published</span>
                                @else
                                    <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background:#F3F4F6;color:#6B7280;">Draft</span>
                                @endif
                                @if(!($post->seo_title ?? null) || !($post->seo_description ?? null))
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold" style="background:#FEF3C7;color:#92400E;" title="Missing SEO data">SEO ⚠</span>
                                @endif
                            </div>
                        </td>
                        <td class="px-5 py-3.5 text-sm" style="color:#6B7280;">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : '—' }}
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" title="View" style="color:#9CA3AF;" class="hover:opacity-70 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                                <a href="{{ route('admin.blog.edit', $post) }}" title="Edit" style="color:#18130E;" class="hover:opacity-70 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?');" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" title="Delete" style="color:#C8102E;" class="hover:opacity-70 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-12 text-center text-sm" style="color:#9CA3AF;">No posts found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($posts->hasPages())
        <div class="px-5 py-3" style="border-top:1px solid #E6E8EC;">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
