<x-admin-layout>

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold" style="color:#0F1419;">Blog Posts</h1>
            <p class="text-sm mt-0.5" style="color:#6B7280;">
                {{ $posts->total() }} {{ Str::plural('post', $posts->total()) }} total
            </p>
        </div>
        <a href="{{ route('admin.blog.create') }}"
           class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold text-white shadow-sm transition hover:opacity-90 active:scale-95"
           style="background:#C8102E;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Post
        </a>
    </div>

    {{-- Filter Tabs --}}
    <div class="flex items-center gap-1 mb-5" x-data="{ tab: '{{ request('tab', 'all') }}' }">
        @foreach(['all' => 'All', 'published' => 'Published', 'drafts' => 'Drafts'] as $key => $label)
            <a href="{{ request()->fullUrlWithQuery(['tab' => $key]) }}"
               class="px-4 py-1.5 rounded-lg text-sm font-medium transition
                      {{ request('tab', 'all') === $key
                            ? 'text-white shadow-sm'
                            : 'hover:bg-white' }}"
               style="{{ request('tab', 'all') === $key
                            ? 'background:#C8102E;color:#fff;'
                            : 'color:#6B7280;' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- Table Card --}}
    <div class="rounded-xl overflow-hidden" style="background:#fff;border:1px solid #E6E8EC;">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr style="background:#F4F5F7;border-bottom:1px solid #E6E8EC;">
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Post</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Author</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Status</th>
                        <th class="px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Date</th>
                        <th class="px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($posts as $post)
                    <tr class="transition hover:bg-[#F4F5F7]" style="border-bottom:1px solid #E6E8EC;">

                        {{-- Post title + thumbnail --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3">
                                @if($post->image)
                                    <img src="{{ Storage::url($post->image) }}"
                                         alt=""
                                         class="w-10 h-10 rounded-lg object-cover flex-shrink-0"
                                         style="border:1px solid #E6E8EC;">
                                @else
                                    <div class="w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0"
                                         style="background:#F4F5F7;border:1px solid #E6E8EC;">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:#6B7280;">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="min-w-0">
                                    <div class="font-semibold truncate max-w-xs" style="color:#0F1419;">
                                        {{ $post->title }}
                                    </div>
                                    <div class="text-xs truncate max-w-xs mt-0.5 font-mono" style="color:#6B7280;">
                                        /{{ $post->slug }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Author --}}
                        <td class="px-5 py-4" style="color:#6B7280;">
                            {{ $post->custom_author ?? optional($post->author)->name ?? '—' }}
                        </td>

                        {{-- Status pills --}}
                        <td class="px-5 py-4">
                            <div class="flex flex-col items-center gap-1.5">
                                @if($post->is_published)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                          style="background:#D1FAE5;color:#065F46;">
                                        Published
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold"
                                          style="background:#F3F4F6;color:#6B7280;">
                                        Draft
                                    </span>
                                @endif
                                @if(!$post->seo_title || !$post->seo_description)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-semibold"
                                          style="background:#FEF3C7;color:#92400E;"
                                          title="Missing SEO title or meta description">
                                        SEO incomplete
                                    </span>
                                @endif
                            </div>
                        </td>

                        {{-- Date --}}
                        <td class="px-5 py-4 whitespace-nowrap" style="color:#6B7280;">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : '—' }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center justify-center gap-3">
                                <a href="{{ route('blog.show', $post->slug) }}"
                                   target="_blank"
                                   title="View live"
                                   class="transition hover:opacity-70"
                                   style="color:#6B7280;">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.blog.edit', $post) }}"
                                   class="text-xs font-semibold px-3 py-1.5 rounded-lg transition"
                                   style="background:#F4F5F7;color:#0F1419;border:1px solid #E6E8EC;">
                                    Edit
                                </a>
                                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Delete this post? This cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="text-xs font-semibold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                            style="background:#FEF2F2;color:#C8102E;border:1px solid #FECACA;">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-5 py-16 text-center" style="color:#6B7280;">
                            <svg class="w-10 h-10 mx-auto mb-3 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10l6 6v10a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="font-medium">No posts found.</p>
                            <p class="text-xs mt-1">Create your first blog post to get started.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($posts->hasPages())
        <div class="px-5 py-4" style="border-top:1px solid #E6E8EC;background:#F4F5F7;">
            {{ $posts->links() }}
        </div>
        @endif
    </div>

</x-admin-layout>
