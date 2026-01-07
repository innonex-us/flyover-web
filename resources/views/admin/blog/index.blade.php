<x-admin-layout>
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-3xl font-bold text-gray-800">Blog Posts</h2>
            <p class="text-sm text-gray-500 mt-1">Manage articles and SEO content</p>
        </div>
        <a href="{{ route('admin.blog.create') }}" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Create Post
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
                        <th class="px-6 py-4 text-left">Title</th>
                        <th class="px-6 py-4 text-left">Author</th>
                        <th class="px-6 py-4 text-center">Status</th>
                        <th class="px-6 py-4 text-left">Published Date</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($posts as $post)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                @if($post->image)
                                    <img src="{{ Storage::url($post->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover mr-3">
                                @else
                                    <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center mr-3 text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $post->title }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit($post->slug, 30) }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $post->author->name }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($post->is_published)
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Published
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                    Draft
                                </span>
                            @endif
                        </td>
                         <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-center text-sm font-medium">
                            <div class="flex justify-center space-x-3">
                                <a href="{{ route('blog.show', $post->slug) }}" target="_blank" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                </a>
                                <a href="{{ route('admin.blog.edit', $post) }}" class="text-indigo-600 hover:text-indigo-900">
                                    Edit
                                </a>
                                <form action="{{ route('admin.blog.destroy', $post) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            No posts found.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($posts->hasPages())
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
            {{ $posts->links() }}
        </div>
        @endif
    </div>
</x-admin-layout>
