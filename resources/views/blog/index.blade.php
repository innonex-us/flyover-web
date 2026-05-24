<x-app-layout
    title="Travel Blog | {{ config('app.name', 'FlyoverBD') }}"
    meta_description="Discover travel tips, visa guides, and tour insights from Bangladesh's trusted travel agency FlyoverBD."
    meta_image="{{ asset('logo.png') }}"
>
    @push('meta')
    <script type="application/ld+json">
    {
      "@@context": "https://schema.org",
      "@@type": "Blog",
      "name": "FlyoverBD Travel Blog",
      "description": "Travel tips, visa guides, and tour insights from Bangladesh",
      "url": "{{ route('blog.index') }}",
      "publisher": { "@@type": "Organization", "name": "FlyoverBD", "logo": { "@@type": "ImageObject", "url": "{{ asset('logo.png') }}" } }
    }
    </script>
    @endpush

    {{-- Hero --}}
    <section style="background:#FAF6EE;border-bottom:1px solid #E4DCC9;" class="px-5 py-16 text-center">
        <p class="fb-eyebrow mb-3">FlyoverBD Journal</p>
        <h1 class="fb-serif text-5xl md:text-6xl leading-[1.08] mb-4" style="color:#18130E;">Stories from<br><em>the road.</em></h1>
        <p class="text-base max-w-md mx-auto" style="color:#7A7166;">Visa guides, trip diaries, and destination deep-dives — written for travellers by travellers.</p>
    </section>

    {{-- Grid --}}
    <section style="background:#F9F6EF;" class="px-5 py-14">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($posts as $post)
                    @php
                        $cardAuthor = $post->custom_author ?? ($post->author->name ?? 'FlyoverBD');
                        $cardWords  = str_word_count(strip_tags($post->content ?? ''));
                        $cardRead   = max(1, (int) ceil($cardWords / 200));
                    @endphp
                    <article class="ota-card flex flex-col group overflow-hidden" style="border-radius:14px;">
                        <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden relative" style="height:200px;">
                            @if($post->image)
                                <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background:linear-gradient(135deg,#18130E 0%,#C8102E 100%);">
                                    <svg width="32" height="32" viewBox="0 0 32 32" fill="none"><path d="M9 22 L16 9 L23 22 L20 22 L18 18 L14 18 L12 22 Z" fill="#FAF6EE"/></svg>
                                </div>
                            @endif
                            <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(24,19,14,.45),transparent);"></div>
                            @if(!$post->is_published)
                                <span class="absolute top-3 right-3 px-2 py-0.5 rounded text-[10px] font-bold" style="background:#C8102E;color:#fff;">DRAFT</span>
                            @endif
                        </a>

                        <div class="flex flex-col flex-1 p-5">
                            <div class="flex items-center gap-2 mb-3 text-[11px] font-mono tracking-widest uppercase" style="color:#7A7166;">
                                <span>{{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</span>
                                <span>·</span>
                                <span>{{ $cardRead }} min</span>
                            </div>
                            <h2 class="fb-serif text-xl leading-tight mb-2 line-clamp-2 group-hover:text-[#C8102E] transition" style="color:#18130E;">
                                <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                            </h2>
                            <p class="text-sm leading-relaxed flex-1 line-clamp-3 mb-4" style="color:#7A7166;">
                                {{ $post->seo_description ?? Str::limit(strip_tags($post->content), 110) }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="text-xs" style="color:#9CA3AF;">By {{ $cardAuthor }}</span>
                                <a href="{{ route('blog.show', $post->slug) }}" class="flex items-center gap-1 text-xs font-bold transition hover:gap-2" style="color:#C8102E;">
                                    Read
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-3 text-center py-16" style="color:#7A7166;">
                        <p class="fb-serif text-2xl mb-2">Nothing here yet.</p>
                        <p class="text-sm">Stay tuned — great stories are coming.</p>
                    </div>
                @endforelse
            </div>

            @if($posts->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $posts->links() }}
            </div>
            @endif
        </div>
    </section>

    {{-- CTA band --}}
    <section class="px-5 py-14 text-center" style="background:#18130E;color:#FAF6EE;">
        <p class="fb-eyebrow mb-3" style="color:#C8102E;">Ready to travel?</p>
        <h2 class="fb-serif text-3xl md:text-4xl mb-6">Turn inspiration into a booking.</h2>
        <div class="flex flex-wrap justify-center gap-3">
            <a href="{{ route('packages.index') }}" class="ota-btn-primary">Browse Tour Packages</a>
            <a href="{{ route('visas.index') }}" class="ota-btn-ghost" style="border-color:#FAF6EE;color:#FAF6EE;">Visa Services</a>
        </div>
    </section>
</x-app-layout>
