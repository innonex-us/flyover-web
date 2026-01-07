<x-app-layout>
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-2xl mx-auto mb-16">
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight mb-4">Latest Travel Insights</h1>
                <p class="text-xl text-gray-500">Discover tips, guides, and news about traveling to and from Bangladesh.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
                @forelse($posts as $post)
                    <article class="flex flex-col bg-white rounded-2xl shadow-lg hover:shadow-xl transition overflow-hidden border border-gray-100 group">
                        <a href="{{ route('blog.show', $post->slug) }}" class="overflow-hidden h-56 relative">
                            <img src="{{ $post->image ? Storage::url($post->image) : 'https://via.placeholder.com/800x600?text=FlyoverBD+Blog' }}" 
                                 alt="{{ $post->title }}" 
                                 class="w-full h-full object-cover transform group-hover:scale-105 transition duration-500 ease-in-out">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            @if(!$post->is_published)
                                <div class="absolute top-4 right-4 bg-red-600/90 backdrop-blur text-white text-xs font-bold px-2 py-1 rounded shadow-sm border border-red-400">
                                    DRAFT (Admin View)
                                </div>
                            @endif
                        </a>
                        <div class="flex-1 p-8 flex flex-col">
                            <div class="flex items-center text-sm text-gray-500 mb-3 space-x-4">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}
                                </span>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-red-600 transition">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h2>
                            <p class="text-gray-600 mb-6 flex-1 line-clamp-3">
                                {{ $post->seo_description ?? Str::limit(strip_tags($post->content), 120) }}
                            </p>
                            <div class="mt-auto">
                                <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center text-red-600 font-semibold hover:text-red-700">
                                    Read Article 
                                    <svg class="w-4 h-4 ml-2 transform group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 text-center py-12">
                        <p class="text-gray-500 text-lg">No posts published yet. Stay tuned!</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-12">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
