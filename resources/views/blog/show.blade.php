<x-app-layout>
    <x-slot name="header">
        <meta name="description" content="{{ $post->seo_description ?? Str::limit(strip_tags($post->content), 160) }}">
        <meta property="og:title" content="{{ $post->seo_title ?? $post->title }} - FlyoverBD">
        <meta property="og:description" content="{{ $post->seo_description ?? Str::limit(strip_tags($post->content), 160) }}">
        <meta property="og:image" content="{{ $post->image ? Storage::url($post->image) : '' }}">
        <title>{{ $post->seo_title ?? $post->title }} - FlyoverBD</title>
    </x-slot>

    <div class="bg-white py-12">
        <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10">
                <div class="flex items-center justify-center space-x-2 text-sm font-medium text-gray-500 mb-4">
                    <span>{{ $post->author->name }}</span>
                    <span>&bull;</span>
                    <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</span>
                </div>
                <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight leading-tight mb-6">
                    {{ $post->title }}
                </h1>
            </div>

            @if($post->image)
                <div class="mb-12 rounded-2xl overflow-hidden shadow-xl">
                    <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover max-h-[600px]">
                </div>
            @endif

            <div class="prose prose-lg prose-red mx-auto max-w-none">
                {!! $post->content !!}
            </div>
            
            <div class="mt-16 pt-8 border-t border-gray-100 flex justify-center">
                 <a href="{{ route('blog.index') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="w-5 h-5 mr-2 -ml-1 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to Blog
                </a>
            </div>
        </article>
    </div>
</x-app-layout>
