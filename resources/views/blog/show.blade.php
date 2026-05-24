@php
    $postAuthor = $post->custom_author ?? ($post->author->name ?? 'FlyoverBD');
    $wordCount  = str_word_count(strip_tags($post->content ?? ''));
    $readTime   = max(1, (int) ceil($wordCount / 200));
@endphp

<x-app-layout>
    @push('meta')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org/",
      "@@type": "Article",
      "headline": {!! Illuminate\Support\Js::from($post->title) !!},
      "description": {!! Illuminate\Support\Js::from($meta_description) !!},
      "image": "{{ $meta_image }}",
      "author": { "@@type": "Person", "name": {!! Illuminate\Support\Js::from($postAuthor) !!} },
      "publisher": { "@@type": "Organization", "name": "FlyoverBD", "logo": { "@@type": "ImageObject", "url": "{{ asset('logo.png') }}" } },
      "datePublished": "{{ $post->published_at ? $post->published_at->toIso8601String() : $post->created_at->toIso8601String() }}",
      "dateModified": "{{ $post->updated_at->toIso8601String() }}"
    }
    </script>
    <meta property="article:published_time" content="{{ $post->published_at ? $post->published_at->toIso8601String() : '' }}">
    <meta property="article:modified_time" content="{{ $post->updated_at->toIso8601String() }}">
    <meta property="article:author" content="{{ $postAuthor }}">
    @endpush

    @if(!$post->is_published)
    <div class="px-5 py-2.5 text-center text-xs font-bold" style="background:#FEF3C7;color:#92400E;border-bottom:1px solid #FDE68A;">
        DRAFT — only visible to admins
    </div>
    @endif

    {{-- Hero band --}}
    <section style="background:#FAF6EE;border-bottom:1px solid #E4DCC9;" class="px-5 pt-10 pb-12">
        <div class="max-w-3xl mx-auto">
            {{-- Breadcrumb --}}
            <nav class="flex items-center gap-1.5 mb-6 text-xs font-mono tracking-widest uppercase" style="color:#7A7166;">
                <a href="{{ route('home') }}" class="hover:text-[#C8102E] transition">Home</a>
                <span>/</span>
                <a href="{{ route('blog.index') }}" class="hover:text-[#C8102E] transition">Blog</a>
                <span>/</span>
                <span style="color:#18130E;">{{ Str::limit($post->title, 40) }}</span>
            </nav>

            <h1 class="fb-serif text-4xl md:text-5xl leading-[1.08] mb-6" style="color:#18130E;">{{ $post->title }}</h1>

            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full overflow-hidden flex-shrink-0" style="background:#E4DCC9;">
                        <img src="{{ asset('logo.png') }}" alt="{{ $postAuthor }}" class="w-full h-full object-contain p-1">
                    </div>
                    <div>
                        <p class="text-xs font-semibold" style="color:#18130E;">{{ $postAuthor }}</p>
                        <p class="text-[11px]" style="color:#7A7166;">
                            {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3 text-[11px] font-mono tracking-wider" style="color:#7A7166;">
                    <span>{{ $readTime }} min read</span>
                    <span>·</span>
                    <span>{{ number_format($wordCount) }} words</span>
                </div>
            </div>
        </div>
    </section>

    {{-- Content --}}
    <section style="background:#F9F6EF;" class="px-5 py-12">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

                {{-- Article --}}
                <article class="lg:col-span-2">
                    @if($post->image)
                    <div class="rounded-2xl overflow-hidden mb-8 shadow-sm">
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}" class="w-full h-auto object-cover">
                    </div>
                    @endif

                    <div class="rounded-2xl p-8 md:p-10 prose prose-lg prose-red max-w-none
                         prose-headings:font-bold prose-p:leading-relaxed
                         prose-img:rounded-xl prose-img:max-w-full prose-img:h-auto
                         prose-a:text-[#C8102E] prose-a:no-underline hover:prose-a:underline
                         [&_img]:max-w-full [&_img]:h-auto
                         [&_iframe]:w-full [&_iframe]:aspect-video [&_iframe]:h-auto [&_iframe]:rounded-xl [&_iframe]:my-4
                         [&_table]:block [&_table]:overflow-x-auto [&_pre]:overflow-x-auto [&_pre]:rounded-lg"
                         style="background:#fff;border:1px solid #E4DCC9;">
                        @php $hasHtml = ($post->content ?? '') !== strip_tags($post->content ?? ''); @endphp
                        @if($hasHtml)
                            {!! $post->content !!}
                        @else
                            {!! nl2br(e($post->content)) !!}
                        @endif
                    </div>

                    <x-share-buttons :title="$post->title" />

                    {{-- Author --}}
                    <div class="flex items-start gap-5 rounded-2xl p-6 mt-6" style="background:#fff;border:1px solid #E4DCC9;">
                        <div class="w-12 h-12 rounded-full overflow-hidden flex-shrink-0" style="background:#FAF6EE;border:1px solid #E4DCC9;">
                            <img src="{{ asset('logo.png') }}" alt="{{ $postAuthor }}" class="w-full h-full object-contain p-1.5">
                        </div>
                        <div>
                            <p class="text-sm font-bold mb-1" style="color:#18130E;">{{ $postAuthor }}</p>
                            <p class="text-sm" style="color:#7A7166;">Travel expert at FlyoverBD. Passionate about helping you explore the beauty of Bangladesh and beyond.</p>
                        </div>
                    </div>
                </article>

                {{-- Sidebar --}}
                <aside class="space-y-6">
                    {{-- CTA --}}
                    <div class="rounded-2xl p-7 text-center" style="background:#18130E;color:#FAF6EE;">
                        <p class="fb-eyebrow mb-2" style="color:#C8102E;">Ready to travel?</p>
                        <h3 class="fb-serif text-2xl mb-3">Plan your trip with us.</h3>
                        <p class="text-sm mb-5" style="color:#7A7166;">Visas, packages, and pick & drop — all in one place.</p>
                        <a href="{{ route('packages.index') }}" class="ota-btn-primary block text-center">Browse Packages</a>
                    </div>

                    {{-- Recent posts --}}
                    <div class="rounded-2xl p-6" style="background:#fff;border:1px solid #E4DCC9;">
                        <h3 class="text-xs font-mono tracking-widest uppercase mb-5 pb-4" style="color:#7A7166;border-bottom:1px solid #E4DCC9;">Recent Articles</h3>
                        <div class="space-y-5">
                            @forelse($recentPosts as $recent)
                            <div class="flex gap-3 group">
                                <a href="{{ route('blog.show', $recent->slug) }}" class="flex-shrink-0 w-16 h-16 rounded-xl overflow-hidden">
                                    @if($recent->image)
                                        <img src="{{ Storage::url($recent->image) }}" alt="{{ $recent->title }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                    @else
                                        <div class="w-full h-full" style="background:linear-gradient(135deg,#18130E,#C8102E);"></div>
                                    @endif
                                </a>
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-xs font-semibold leading-snug line-clamp-2 group-hover:text-[#C8102E] transition" style="color:#18130E;">
                                        <a href="{{ route('blog.show', $recent->slug) }}">{{ $recent->title }}</a>
                                    </h4>
                                    <p class="text-[11px] mt-1" style="color:#7A7166;">
                                        {{ $recent->published_at ? $recent->published_at->format('M d, Y') : '' }}
                                    </p>
                                </div>
                            </div>
                            @empty
                            <p class="text-xs" style="color:#7A7166;">No recent posts.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- WhatsApp --}}
                    <a href="https://wa.me/8801XXXXXXXXX?text={{ urlencode('Hi, I read your blog about '.$post->title.' and have a question.') }}"
                       target="_blank"
                       class="flex items-center gap-3 rounded-2xl px-5 py-4 text-sm font-semibold transition hover:opacity-90"
                       style="background:#D1FAE5;color:#065F46;">
                        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                        Chat about this trip
                    </a>
                </aside>

            </div>
        </div>
    </section>
</x-app-layout>
