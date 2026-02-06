<x-app-layout>
    {{-- @push('meta')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Article",
      "headline": "{{ $post->title }}",
      "description": "{{ $post->seo_description ?? \Illuminate\Support\Str::limit(strip_tags($post->content), 160) }}",
      "image": "{{ $meta_image ?? '' }}",
      "author": {
        "@type": "Person",
        "name": "{{ $post->custom_author ?? $post->author->name ?? 'FlyoverBD' }}"
      },
      "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : '' }}",
      "dateModified": "{{ $post->updated_at->toIso8601String() }}"
    }
    </script>
    @endpush
    <x-slot name="header">
        <meta name="description" content="{{ $post->seo_description ?? Str::limit(strip_tags($post->content), 160) }}">
        <meta property="og:title" content="{{ $post->seo_title ?? $post->title }} - FlyoverBD">
        <meta property="og:description" content="{{ $post->seo_description ?? Str::limit(strip_tags($post->content), 160) }}">
        <meta property="og:image" content="{{ $post->image ? Storage::url($post->image) : '' }}">
        <title>{{ $post->seo_title ?? $post->title }} - FlyoverBD</title>
    </x-slot> --}}

    <div class="bg-gray-50 min-h-screen pb-12">
        @if(!$post->is_published)
            <div class="bg-yellow-50 border-b border-yellow-200 px-4 py-3">
                <div class="max-w-7xl mx-auto flex items-center justify-center">
                    <svg class="h-5 w-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    <p class="text-sm font-medium text-yellow-800">
                        This post is currently a <span class="font-bold">Draft</span>. It is only visible to you (Admin). Public visitors cannot see this page.
                    </p>
                </div>
            </div>
        @endif
        <!-- Breadcrumb & Header Image -->
        <div class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <nav class="flex text-sm font-medium text-gray-500 mb-6">
                    <a href="{{ route('home') }}" class="hover:text-red-600 transition">Home</a>
                    <span class="mx-2">/</span>
                    <a href="{{ route('blog.index') }}" class="hover:text-red-600 transition">Blog</a>
                    <span class="mx-2">/</span>
                    <span class="text-gray-900 truncate">{{ $post->title }}</span>
                </nav>

                <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6 max-w-4xl">
                    {{ $post->title }}
                </h1>

                <div class="flex items-center space-x-4">
                    <div class="flex items-center">
                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-lg">
                            {{ substr($post->custom_author ?? $post->author->name, 0, 1) }}
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900">{{ $post->custom_author ?? $post->author->name }}</p>
                            <div class="flex space-x-1 text-sm text-gray-500">
                                <time datetime="{{ $post->published_at }}">{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</time>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <!-- Main Content -->
                <article class="lg:col-span-2">
                    @if($post->image)
                        <div class="rounded-2xl overflow-hidden shadow-lg mb-10">
                            <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
                        </div>
                    @endif

                    <div class="bg-white rounded-2xl shadow-sm p-8 md:p-12 prose prose-lg prose-red max-w-none prose-headings:font-bold prose-headings:text-gray-900 prose-p:text-gray-600 prose-img:rounded-xl">
                        {!! nl2br(e($post->content)) !!}
                    </div>

                    <!-- Share Buttons -->
                    <div class="mt-8 border-t border-b border-gray-100 py-6">
                        <div class="flex items-center justify-between flex-wrap gap-4">
                            <span class="text-gray-900 font-bold text-lg">Share this article:</span>
                            <div class="flex space-x-3">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 hover:bg-blue-600 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-sky-100 flex items-center justify-center text-sky-500 hover:bg-sky-500 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.94-.56.98-1.56.67-2.02-.35.18-.7.45-1.05.67-.35.22-.73.4-1.12.55C18.97 3.3 17.84 3 16.63 3c-2.3 0-4.14 1.88-4.14 4.18 0 .32.05.64.12.95-3.44-.18-6.5-1.84-8.54-4.34-.35.6-.55 1.3-.55 2.06 0 1.45.74 2.74 1.85 3.48-.67-.02-1.3-.2-1.85-.5v.05c0 2.03 1.44 3.73 3.36 4.1-.35.1-.72.15-1.1.15-.27 0-.53-.02-.8-.06.54 1.67 2.1 2.88 3.93 2.9-1.42 1.1-3.2 1.77-5.14 1.77-.33 0-.66-.02-1 .04 1.63 1.05 3.56 1.66 5.63 1.66 6.75 0 10.45-5.6 10.45-10.45 0-.16 0-.32-.02-.48.72-.5 1.35-1.14 1.84-1.87z"/></svg>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($post->title) }}" target="_blank" class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 hover:bg-indigo-700 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($post->title . ' ' . url()->current()) }}" target="_blank" class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 hover:bg-green-600 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                                </a>
                                <button x-data="{ copied: false }" 
                                        @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)" 
                                        class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-600 hover:bg-gray-600 hover:text-white transition relative">
                                    <svg x-show="!copied" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                    <svg x-show="copied" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    <span x-show="copied" x-cloak class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs py-1 px-2 rounded shadow-lg whitespace-nowrap">Copied!</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Author Box -->
                    <div class="mt-10 bg-white rounded-2xl shadow-sm p-8 flex items-start space-x-6">
                        <div class="h-16 w-16 rounded-full bg-red-100 flex flex-shrink-0 items-center justify-center text-red-600 font-bold text-2xl">
                            {{ substr($post->custom_author ?? $post->author->name, 0, 1) }}
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Written by {{ $post->custom_author ?? $post->author->name }}</h3>
                            <p class="text-gray-600">Travel expert at FlyoverBD. Passionate about helping you explore the beauty of Bangladesh and beyond.</p>
                        </div>
                    </div>
                </article>

                <!-- Sidebar -->
                <aside class="space-y-8">
                    <!-- CTA Card -->
                    <div class="bg-gradient-to-br from-red-600 to-red-700 rounded-2xl shadow-lg p-8 text-white text-center">
                        <h3 class="text-2xl font-bold mb-4">Planning a Trip?</h3>
                        <p class="mb-6 text-red-100">Let us handle the details. From visa processing to tour packages, we've got you covered.</p>
                        <a href="{{ route('packages.index') }}" class="inline-block bg-white text-red-600 font-bold py-3 px-8 rounded-full hover:bg-red-50 transition shadow-md">
                            Explore Packages
                        </a>
                    </div>

                    <!-- Recent Posts -->
                    <div class="bg-white rounded-2xl shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-6 border-b border-gray-100 pb-4">Recent Articles</h3>
                        <div class="space-y-6">
                            @forelse($recentPosts as $recent)
                                <div class="group flex space-x-4">
                                    <a href="{{ route('blog.show', $recent->slug) }}" class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden">
                                        <img src="{{ $recent->image ? Storage::url($recent->image) : 'https://via.placeholder.com/150' }}" 
                                             alt="{{ $recent->title }}" 
                                             class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    </a>
                                    <div class="flex-1">
                                        <h4 class="text-sm font-bold text-gray-900 group-hover:text-red-600 transition leading-snug mb-1">
                                            <a href="{{ route('blog.show', $recent->slug) }}">
                                                {{ $recent->title }}
                                            </a>
                                        </h4>
                                        <p class="text-xs text-gray-500">{{ $recent->published_at ? $recent->published_at->format('M d, Y') : '' }}</p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No recent posts.</p>
                            @endforelse
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
