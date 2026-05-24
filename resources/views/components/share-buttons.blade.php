@props(['title', 'url' => null, 'showLabel' => true])

@php
    $shareUrl = $url ?? url()->current();
@endphp

<div class="flex items-center justify-between flex-wrap gap-4 @if($showLabel) mt-8 pt-6 pb-4 @endif" @if($showLabel) style="border-top:1px solid #E4DCC9;" @endif>
    @if($showLabel)
        <span class="text-sm font-bold" style="color:#18130E;">Share this {{ str_contains($shareUrl, '/tours/') ? 'tour' : (str_contains($shareUrl, '/visas/') ? 'visa' : 'story') }}</span>
    @endif
    <div class="flex gap-2">
        {{-- Facebook --}}
        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank"
           class="w-9 h-9 rounded-full flex items-center justify-center transition hover:opacity-80" style="background:#EBF0FB;color:#1877F2;"
           title="Share on Facebook">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
        </a>
        
        {{-- Twitter/X --}}
        <a href="https://twitter.com/intent/tweet?text={{ urlencode($title) }}&url={{ urlencode($shareUrl) }}" target="_blank"
           class="w-9 h-9 rounded-full flex items-center justify-center transition hover:opacity-80" style="background:#E7F5FF;color:#1DA1F2;"
           title="Share on X (Twitter)">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.94-.56.98-1.56.67-2.02-.35.18-.7.45-1.05.67-.35.22-.73.4-1.12.55C18.97 3.3 17.84 3 16.63 3c-2.3 0-4.14 1.88-4.14 4.18 0 .32.05.64.12.95-3.44-.18-6.5-1.84-8.54-4.34-.35.6-.55 1.3-.55 2.06 0 1.45.74 2.74 1.85 3.48-.67-.02-1.3-.2-1.85-.5v.05c0 2.03 1.44 3.73 3.36 4.1-.35.1-.72.15-1.1.15-.27 0-.53-.02-.8-.06.54 1.67 2.1 2.88 3.93 2.9-1.42 1.1-3.2 1.77-5.14 1.77-.33 0-.66-.02-1 .04 1.63 1.05 3.56 1.66 5.63 1.66 6.75 0 10.45-5.6 10.45-10.45 0-.16 0-.32-.02-.48.72-.5 1.35-1.14 1.84-1.87z"/></svg>
        </a>
        
        {{-- WhatsApp --}}
        <a href="https://wa.me/?text={{ urlencode($title.' '.$shareUrl) }}" target="_blank"
           class="w-9 h-9 rounded-full flex items-center justify-center transition hover:opacity-80" style="background:#E8F9EF;color:#25D366;"
           title="Share on WhatsApp">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.017-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
        </a>
        
        {{-- Copy Link --}}
        <button x-data="{ copied: false }"
                @click="navigator.clipboard.writeText('{{ $shareUrl }}'); copied = true; setTimeout(() => copied = false, 2000)"
                class="w-9 h-9 rounded-full flex items-center justify-center transition hover:opacity-80 relative" style="background:#F3F4F6;color:#374151;"
                title="Copy link">
            <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            <svg x-show="copied" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span x-show="copied" x-cloak class="absolute -top-8 left-1/2 -translate-x-1/2 text-white text-[10px] font-bold py-1 px-2 rounded whitespace-nowrap" style="background:#18130E;">Copied!</span>
        </button>
    </div>
</div>
