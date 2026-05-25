@php
    use Illuminate\Support\Str;
    $uid = 'g' . Str::random(6);
    $wrapId = 'tour-gallery-wrap-' . $uid;
    $mainId = 'main-tour-image-' . $uid;
    $images = $images ?? [];
    $alt = $alt ?? '';
    $showPhotoGallery = count($images) > 1;
    if ($images === []) { $images = [asset('banner/hero-banner-1.png')]; $showPhotoGallery = false; }
@endphp

<div id="{{ $wrapId }}" class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    @if($showPhotoGallery)
    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 p-2 sm:p-4 items-start">
        <figure class="relative m-0 min-w-0 flex-1 aspect-video max-h-[500px] bg-gray-100 rounded-xl overflow-hidden order-1">
            <img id="{{ $mainId }}" src="{{ $images[0] }}" alt="{{ $alt }}" class="w-full h-full object-cover" decoding="async" fetchpriority="high">
        </figure>
        <aside class="order-2 w-full sm:w-[5.25rem] md:w-28 flex-none gap-3 sm:flex-col overflow-x-auto sm:overflow-y-auto mt-3 sm:mt-0">
            <div class="flex flex-row sm:flex-col gap-2 overflow-x-auto sm:overflow-y-auto max-h-[min(28rem,60vh)] pl-0.5 [scrollbar-width:thin]" role="list">
                @foreach($images as $index => $gImg)
                <button type="button" role="listitem" data-tour-image="{{ e($gImg) }}"
                        class="tour-gallery-thumb group block flex-none w-24 h-16 sm:w-full sm:aspect-[4/3] rounded-lg overflow-hidden bg-gray-100 border-2 {{ $index === 0 ? 'border-red-500 ring-2 ring-red-500 ring-offset-1 ring-offset-white' : 'border-gray-200' }} hover:border-red-400 focus:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-1 transition-colors text-left"
                        aria-label="Show image {{ $index + 1 }}" aria-pressed="{{ $index === 0 ? 'true' : 'false' }}">
                    <img src="{{ $gImg }}" alt="" class="w-full h-full object-cover pointer-events-none group-hover:opacity-95 transition-opacity" loading="lazy" decoding="async">
                </button>
                @endforeach
            </div>
        </aside>
    </div>
    <script>
        (function() {
            var wrap = document.getElementById('{{ $wrapId }}');
            var mainImg = document.getElementById('{{ $mainId }}');
            var thumbs = wrap ? wrap.querySelectorAll('.tour-gallery-thumb') : [];
            if (!mainImg || !thumbs.length) return;
            thumbs.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var url = this.getAttribute('data-tour-image');
                    if (url) mainImg.src = url;
                    thumbs.forEach(function(b) {
                        b.classList.remove('border-red-500', 'ring-2', 'ring-red-500', 'ring-offset-1', 'ring-offset-white');
                        b.classList.add('border-gray-200');
                        b.setAttribute('aria-pressed', 'false');
                    });
                    this.classList.add('border-red-500', 'ring-2', 'ring-red-500', 'ring-offset-1', 'ring-offset-white');
                    this.classList.remove('border-gray-200');
                    this.setAttribute('aria-pressed', 'true');
                });
            });
        })();
    </script>
    @else
    <figure class="relative m-0 aspect-video max-h-[500px] bg-gray-100">
        <img id="{{ $mainId }}" src="{{ $images[0] }}" alt="{{ $alt }}" class="w-full h-full object-cover" decoding="async">
    </figure>
    @endif
</div>
