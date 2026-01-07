<x-app-layout>
    <x-slot name="header">
        <meta name="description" content="{{ $post->seo_description ?? Str::limit(strip_tags($post->content), 160) }}">
        <meta property="og:title" content="{{ $post->seo_title ?? $post->title }} - FlyoverBD">
        <meta property="og:description" content="{{ $post->seo_description ?? Str::limit(strip_tags($post->content), 160) }}">
        <meta property="og:image" content="{{ $post->image ? Storage::url($post->image) : '' }}">
        <title>{{ $post->seo_title ?? $post->title }} - FlyoverBD</title>
    </x-slot>

    <div class="bg-gray-50 min-h-screen pb-12">
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
                        {!! $post->content !!}
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
