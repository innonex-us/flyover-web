<x-admin-layout>

    {{-- Page Header --}}
    <div class="mb-6">
        <div class="flex items-center gap-2 mb-1">
            <a href="{{ route('admin.blog.index') }}"
               class="text-sm font-medium transition hover:opacity-70 flex items-center gap-1"
               style="color:#6B7280;">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Blog Posts
            </a>
            <span style="color:#E6E8EC;">/</span>
            <span class="text-sm font-medium truncate max-w-xs" style="color:#0F1419;">Edit Post</span>
        </div>
        <h1 class="text-2xl font-bold" style="color:#0F1419;">Edit Post</h1>
        <p class="text-sm mt-0.5" style="color:#6B7280;">Updating: {{ $post->title }}</p>
    </div>

    <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data"
          class="relative"
          x-data="formUploader"
          @submit.prevent="submitForm">

        {{-- Upload Progress Overlay --}}
        <div x-show="uploading"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex flex-col items-center justify-center"
             style="background:rgba(255,255,255,0.85);backdrop-filter:blur(4px);display:none;">
            <div class="w-64 rounded-full h-3 mb-4 overflow-hidden" style="background:#E6E8EC;">
                <div class="h-3 rounded-full transition-all duration-300"
                     style="background:#C8102E;"
                     :style="`width:${progress}%`"></div>
            </div>
            <p class="font-bold text-lg" style="color:#0F1419;">
                Uploading… <span x-text="progress + '%'"></span>
            </p>
            <p class="text-sm mt-1" style="color:#6B7280;">Please wait while we process your files.</p>
        </div>

        @csrf
        @method('PUT')

        <div class="flex flex-col xl:flex-row gap-6">

            {{-- ── Left: Main Content ── --}}
            <div class="flex-1 min-w-0 space-y-5">

                {{-- Title --}}
                <div class="rounded-xl p-6" style="background:#fff;border:1px solid #E6E8EC;"
                     x-data="{ title: '{{ old('title', addslashes($post->title)) }}' }">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-sm font-semibold" style="color:#0F1419;">Post Title</label>
                        <span class="text-xs tabular-nums" style="color:#6B7280;" x-text="title.length + ' chars'"></span>
                    </div>
                    <input type="text" name="title" id="post-title"
                           x-model="title"
                           value="{{ old('title', $post->title) }}"
                           required
                           placeholder="Enter an engaging title…"
                           class="w-full rounded-lg px-3 py-2.5 text-sm outline-none transition focus:ring-2"
                           style="border:1px solid #E6E8EC;color:#0F1419;">
                    <p class="text-xs mt-2" style="color:#6B7280;">
                        Current slug: <span class="font-mono" style="color:#0F1419;">{{ $post->slug }}</span>
                        &nbsp;·&nbsp;
                        New: <span id="slug-preview" class="font-mono" style="color:#0F1419;"></span>
                    </p>
                    @error('title')
                        <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Custom Author --}}
                <div class="rounded-xl p-6" style="background:#fff;border:1px solid #E6E8EC;">
                    <label class="block text-sm font-semibold mb-2" style="color:#0F1419;">
                        Author Name
                        <span class="font-normal text-xs ml-1" style="color:#6B7280;">(optional — leave blank for default)</span>
                    </label>
                    <input type="text" name="custom_author"
                           value="{{ old('custom_author', $post->custom_author) }}"
                           placeholder="e.g. Travel Guide"
                           class="w-full rounded-lg px-3 py-2.5 text-sm outline-none transition focus:ring-2"
                           style="border:1px solid #E6E8EC;color:#0F1419;">
                </div>

                {{-- Content Editor --}}
                <div class="rounded-xl p-6" style="background:#fff;border:1px solid #E6E8EC;">
                    <label class="block text-sm font-semibold mb-3" style="color:#0F1419;">Content</label>
                    <textarea name="content">{!! old('content', $post->content) !!}</textarea>
                    @error('content')
                        <p class="text-xs mt-2" style="color:#C8102E;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Day-wise Itinerary --}}
                <div class="rounded-xl p-6" style="background:#fff;border:1px solid #E6E8EC;">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-semibold uppercase tracking-wider" style="color:#0F1419;">
                            Day-wise Itinerary
                        </h3>
                        <button type="button" onclick="addItineraryDay()"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg transition hover:opacity-80"
                                style="background:#F4F5F7;color:#0F1419;border:1px solid #E6E8EC;">
                            + Add Day
                        </button>
                    </div>
                    <div id="itinerary-container" class="space-y-3">
                        @if($post->itinerary && is_array($post->itinerary))
                            @foreach($post->itinerary as $index => $day)
                                @php $loopIndex = $index + 1; @endphp
                                <div class="rounded-lg p-4 relative group" id="day-{{ $loopIndex }}"
                                     style="background:#F4F5F7;border:1px solid #E6E8EC;">
                                    <button type="button" onclick="removeDay({{ $loopIndex }})"
                                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition"
                                            style="color:#6B7280;">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-semibold mb-1" style="color:#6B7280;">Day Title</label>
                                            <input type="text" name="itinerary[{{ $loopIndex }}][title]"
                                                   value="{{ $day['title'] ?? '' }}"
                                                   class="w-full rounded-md px-3 py-2 text-sm outline-none"
                                                   style="border:1px solid #E6E8EC;color:#0F1419;">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold mb-1" style="color:#6B7280;">Description</label>
                                            <textarea name="itinerary[{{ $loopIndex }}][description]" rows="2"
                                                      class="w-full rounded-md px-3 py-2 text-sm outline-none resize-none"
                                                      style="border:1px solid #E6E8EC;color:#0F1419;">{{ $day['description'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

            </div>

            {{-- ── Right: Sidebar ── --}}
            <div class="xl:w-72 w-full space-y-5">

                {{-- Publish Card --}}
                <div class="rounded-xl p-6" style="background:#fff;border:1px solid #E6E8EC;">
                    <h3 class="text-xs font-semibold uppercase tracking-wider mb-4" style="color:#6B7280;">
                        Publish
                    </h3>

                    <label class="flex items-center gap-3 cursor-pointer mb-2">
                        <div class="relative" x-data="{ checked: {{ old('is_published', $post->is_published) ? 'true' : 'false' }} }">
                            <input type="checkbox" name="is_published" value="1"
                                   x-model="checked"
                                   {{ old('is_published', $post->is_published) ? 'checked' : '' }}
                                   class="sr-only">
                            <div @click="checked = !checked"
                                 class="w-10 h-5 rounded-full transition-colors cursor-pointer"
                                 :style="checked ? 'background:#C8102E;' : 'background:#E6E8EC;'">
                                <div class="w-4 h-4 bg-white rounded-full shadow transition-transform mt-0.5"
                                     :style="checked ? 'transform:translateX(22px);margin-left:2px;' : 'transform:translateX(2px);'"></div>
                            </div>
                        </div>
                        <span class="text-sm font-semibold" style="color:#0F1419;">Published</span>
                    </label>

                    @if($post->published_at)
                        <p class="text-xs" style="color:#6B7280;">
                            Originally published: {{ $post->published_at->format('M d, Y') }}
                        </p>
                    @endif

                    <div class="mt-5 space-y-3">
                        <button type="submit"
                                class="w-full py-2.5 rounded-lg text-sm font-bold text-white transition hover:opacity-90 active:scale-95 shadow-sm"
                                style="background:#C8102E;">
                            Update Post
                        </button>
                        <a href="{{ route('admin.blog.index') }}"
                           class="block w-full text-center text-sm py-2.5 rounded-lg transition hover:opacity-70"
                           style="color:#6B7280;">
                            Cancel
                        </a>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="rounded-xl p-6" style="background:#fff;border:1px solid #E6E8EC;"
                     x-data="fileUploader">
                    <h3 class="text-xs font-semibold uppercase tracking-wider mb-3" style="color:#6B7280;">
                        Featured Image
                    </h3>
                    <label class="flex flex-col items-center justify-center w-full h-44 rounded-lg cursor-pointer transition relative overflow-hidden"
                           style="border:2px dashed #E6E8EC;background:#F4F5F7;">
                        <div id="image-placeholder"
                             class="flex flex-col items-center justify-center gap-2 {{ $post->image ? 'hidden' : '' }}">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 20 16" style="color:#6B7280;">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                            </svg>
                            <p class="text-xs text-center" style="color:#6B7280;">Click to upload new image</p>
                            <p class="text-[10px] text-center" style="color:#6B7280;">Recommended: 1200×630px</p>
                        </div>
                        <img id="image-preview"
                             src="{{ $post->image ? Storage::url($post->image) : '#' }}"
                             class="{{ $post->image ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover"/>
                        <input type="file" name="image" class="hidden" accept="image/*"
                               @change="handleFileChange($event); previewImage($event.target)"/>
                    </label>
                    @error('image')
                        <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p>
                    @enderror
                    <div x-show="fileName" class="mt-2 text-xs font-medium" style="color:#065F46;display:none;">
                        Selected: <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                    </div>
                </div>

                {{-- SEO Settings --}}
                <div class="rounded-xl p-6 space-y-4" style="background:#fff;border:1px solid #E6E8EC;"
                     x-data="{
                         seoTitle: '{{ old('seo_title', addslashes($post->seo_title ?? '')) }}',
                         seoDesc:  '{{ old('seo_description', addslashes($post->seo_description ?? '')) }}',
                         get titleLen() { return this.seoTitle.length; },
                         get descLen()  { return this.seoDesc.length; },
                         get titleColor() { return this.titleLen > 60 ? '#C8102E' : this.titleLen > 50 ? '#92400E' : '#065F46'; },
                         get descColor()  { return this.descLen  > 160 ? '#C8102E' : this.descLen  > 140 ? '#92400E' : '#065F46'; }
                     }">
                    <h3 class="text-xs font-semibold uppercase tracking-wider" style="color:#6B7280;">
                        SEO Settings
                    </h3>

                    {{-- Focus Keyword --}}
                    <div>
                        <label class="block text-xs font-semibold mb-1" style="color:#6B7280;">Focus Keyword</label>
                        <input type="text" name="focus_keyword"
                               value="{{ old('focus_keyword', $post->focus_keyword ?? '') }}"
                               placeholder="e.g. Bangladesh tour packages"
                               class="w-full rounded-lg px-3 py-2 text-xs outline-none transition focus:ring-2"
                               style="border:1px solid #E6E8EC;color:#0F1419;">
                        <p class="text-[10px] mt-1" style="color:#6B7280;">Primary keyword this post should rank for.</p>
                    </div>

                    {{-- SEO Title --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-xs font-semibold" style="color:#6B7280;">SEO Title</label>
                            <span class="text-xs font-semibold tabular-nums"
                                  :style="`color:${titleColor}`"
                                  x-text="titleLen + '/60'"></span>
                        </div>
                        <input type="text" name="seo_title" x-model="seoTitle"
                               value="{{ old('seo_title', $post->seo_title) }}"
                               maxlength="70"
                               placeholder="Leave blank to use post title"
                               class="w-full rounded-lg px-3 py-2 text-xs outline-none transition focus:ring-2"
                               style="border:1px solid #E6E8EC;color:#0F1419;">
                        <div class="mt-1.5 h-1 rounded-full overflow-hidden" style="background:#E6E8EC;">
                            <div class="h-full rounded-full transition-all"
                                 :style="`width:${Math.min(100,(titleLen/60)*100)}%;background:${titleColor}`"></div>
                        </div>
                    </div>

                    {{-- Meta Description --}}
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <label class="text-xs font-semibold" style="color:#6B7280;">Meta Description</label>
                            <span class="text-xs font-semibold tabular-nums"
                                  :style="`color:${descColor}`"
                                  x-text="descLen + '/160'"></span>
                        </div>
                        <textarea name="seo_description" x-model="seoDesc"
                                  rows="3" maxlength="180"
                                  placeholder="Compelling summary that appears in Google results…"
                                  class="w-full rounded-lg px-3 py-2 text-xs outline-none transition focus:ring-2 resize-none"
                                  style="border:1px solid #E6E8EC;color:#0F1419;">{{ old('seo_description', $post->seo_description) }}</textarea>
                        <div class="mt-1.5 h-1 rounded-full overflow-hidden" style="background:#E6E8EC;">
                            <div class="h-full rounded-full transition-all"
                                 :style="`width:${Math.min(100,(descLen/160)*100)}%;background:${descColor}`"></div>
                        </div>
                    </div>

                    {{-- SERP Preview --}}
                    <div class="rounded-lg p-3" style="border:1px solid #E6E8EC;background:#F4F5F7;">
                        <p class="text-[10px] font-semibold uppercase tracking-wider mb-2" style="color:#6B7280;">
                            Google Preview
                        </p>
                        <div class="text-[13px] font-medium leading-tight truncate" style="color:#1a0dab;"
                             x-text="seoTitle || '{{ addslashes($post->title) }}'"></div>
                        <div class="text-[11px] mt-0.5 truncate" style="color:#006621;">
                            {{ url('/blog') }}/{{ $post->slug }}
                        </div>
                        <div class="text-[11px] mt-1 leading-relaxed line-clamp-2" style="color:#545454;"
                             x-text="seoDesc || 'Your meta description will appear here. Make it compelling to improve click-through rates.'"></div>
                    </div>
                </div>

            </div>
        </div>
    </form>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@3/build/jodit.min.css">
    <style>
        .jodit-container { border-radius: 0.5rem !important; border-color: #E6E8EC !important; }
        .jodit-toolbar__box { border-radius: 0.5rem 0.5rem 0 0 !important; background: #F4F5F7 !important; }
        .jodit-wysiwyg { font-size: 15px !important; line-height: 1.75 !important; color: #0F1419 !important; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/jodit@3/build/jodit.min.js"></script>
    <script>
        (function() {
            var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            var editor = Jodit.make('textarea[name="content"]', {
                height: 520,
                language: 'en',
                toolbarAdaptive: false,
                spellcheck: true,
                showCharsCounter: true,
                showWordsCounter: true,
                showXPathInStatusbar: false,
                allowResizeY: true,
                allowResizeX: false,
                buttons: [
                    'source', '|',
                    'bold', 'italic', 'underline', 'strikethrough', '|',
                    'superscript', 'subscript', '|',
                    'ul', 'ol', '|',
                    'outdent', 'indent', '|',
                    'font', 'fontsize', 'brush', 'paragraph', '|',
                    'image', 'video', 'table', 'link', '|',
                    'align', '|',
                    'undo', 'redo', '|',
                    'hr', 'eraser', '|',
                    'fullsize', 'print', '|',
                    'find'
                ],
                uploader: {
                    url: '{{ route("admin.upload.image") }}',
                    headers: { 'X-CSRF-TOKEN': csrfToken },
                    isSuccess: function(resp) { return !resp.error; },
                    getMessage: function(resp) { return resp.message || 'Upload failed'; },
                    process: function(resp) { return resp; },
                    defaultHandlerSuccess: function(data) {
                        var j = this;
                        if (data.files && data.files.length) {
                            data.files.forEach(function(url) { j.s.insertImage(url); });
                        }
                    }
                },
                filebrowser: { ajax: { url: '{{ route("admin.upload.image") }}' } }
            });

            window._joditSync = function() { editor.synchronizeValues(); };
            document.querySelector('textarea[name="content"]').closest('form')
                .addEventListener('submit', window._joditSync, true);
        })();

        function previewImage(input) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('image-placeholder');
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    placeholder.classList.add('hidden');
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function slugify(text) {
            return text.toLowerCase().trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }

        const titleInput  = document.getElementById('post-title');
        const slugPreview = document.getElementById('slug-preview');
        if (titleInput && slugPreview) {
            titleInput.addEventListener('input', function() {
                slugPreview.textContent = slugify(this.value) || '(unchanged)';
            });
        }

        let dayCount = {{ $post->itinerary && is_array($post->itinerary) ? count($post->itinerary) : 0 }};

        function addItineraryDay() {
            dayCount++;
            const container = document.getElementById('itinerary-container');
            const dayHtml = `
                <div class="rounded-lg p-4 relative group" id="day-${dayCount}"
                     style="background:#F4F5F7;border:1px solid #E6E8EC;">
                    <button type="button" onclick="removeDay(${dayCount})"
                            class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition"
                            style="color:#6B7280;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#6B7280;">Day Title</label>
                            <input type="text" name="itinerary[${dayCount}][title]"
                                   class="w-full rounded-md px-3 py-2 text-sm outline-none"
                                   style="border:1px solid #E6E8EC;color:#0F1419;"
                                   placeholder="e.g. Day ${dayCount}: Arrival in Dhaka">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold mb-1" style="color:#6B7280;">Description</label>
                            <textarea name="itinerary[${dayCount}][description]" rows="2"
                                      class="w-full rounded-md px-3 py-2 text-sm outline-none resize-none"
                                      style="border:1px solid #E6E8EC;color:#0F1419;"
                                      placeholder="Brief description of the day's activities…"></textarea>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', dayHtml);
        }

        function removeDay(id) {
            const el = document.getElementById(`day-${id}`);
            if (el) el.remove();
        }
    </script>
    @endpush

</x-admin-layout>
