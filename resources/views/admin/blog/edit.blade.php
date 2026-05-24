<x-admin-layout pageTitle="Edit Post">
    <div class="mb-6">
        <h2 class="text-2xl font-bold" style="color:#0F1419;">Edit Post</h2>
        <p class="text-sm mt-1" style="color:#6B7280;">Updating: {{ $post->title }}</p>
    </div>

    <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="rounded-xl p-8 relative" style="background:#fff;border:1px solid #E6E8EC;" x-data="formUploader" @submit.prevent="submitForm">
        
        <!-- Upload Overlay -->
        <div x-show="uploading" 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute inset-0 bg-white/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center rounded-xl"
            style="display: none;">
            <div class="w-64 bg-gray-200 rounded-full h-4 mb-4 overflow-hidden">
                <div class="bg-red-600 h-4 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
            </div>
            <div class="text-gray-800 font-bold text-lg">Uploading... <span x-text="progress + '%'"></span></div>
            <div class="text-gray-500 text-sm mt-2">Please wait while we process your files.</div>
        </div>
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Title -->
                <div x-data="{ title: '{{ old('title', addslashes($post->title)) }}' }">
                    <div class="flex justify-between items-center mb-2">
                        <label class="block text-sm font-bold text-gray-700">Post Title</label>
                        <span class="text-xs text-gray-400" x-text="title.length + ' chars'"></span>
                    </div>
                    <input type="text" name="title" x-model="title" value="{{ old('title', $post->title) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200" placeholder="Enter an engaging title" id="post-title">
                    <p class="text-xs text-gray-400 mt-1">Current slug: <span class="font-mono text-gray-600">{{ $post->slug }}</span> · New: <span id="slug-preview" class="font-mono text-gray-600 break-all"></span></p>
                    @error('title') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Custom Author -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Author Name (Optional)</label>
                    <input type="text" name="custom_author" value="{{ old('custom_author', $post->custom_author) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200" placeholder="e.g. Travel Guide (Leave empty for default)">
                </div>

                <!-- Content -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Content</label>
                    <textarea name="content" id="jodit-content">{!! old('content', $post->content) !!}</textarea>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <!-- Status -->
                <div class="p-5 rounded-xl" style="background:#F9FAFB;border:1px solid #E6E8EC;">
                    <div class="flex items-center justify-between mb-2">
                        @if($post->is_published)
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background:#D1FAE5;color:#065F46;">Published</span>
                        @else
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold" style="background:#F3F4F6;color:#6B7280;">Draft</span>
                        @endif
                        @if($post->published_at)
                            <span class="text-xs" style="color:#9CA3AF;">{{ $post->published_at->format('M d, Y') }}</span>
                        @endif
                    </div>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }} class="rounded h-4 w-4" style="accent-color:#C8102E;">
                        <span class="text-sm font-semibold" style="color:#0F1419;">Publish</span>
                    </label>
                </div>

                <!-- Featured Image -->
                <div x-data="fileUploader">
                    <label class="block text-sm font-bold text-gray-700 mb-2">Featured Image</label>
                     @if($post->image)
                        <div class="mb-2">
                             <img src="{{ Storage::url($post->image) }}" class="w-full h-auto rounded-lg shadow-sm">
                        </div>
                    @endif

                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 {{ $post->image ? 'hidden' : '' }}" id="image-placeholder">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="text-xs text-center text-gray-500 mb-1">Click to upload new image</p>
                                <p class="text-[10px] text-center text-gray-400">Recommended size: 1200x630px</p>
                            </div>
                            <img id="image-preview" src="{{ $post->image ? Storage::url($post->image) : '#' }}" class="{{ $post->image ? '' : 'hidden' }} absolute inset-0 w-full h-full object-cover" />
                            <input type="file" name="image" class="hidden" accept="image/*" @change="handleFileChange($event); previewImage($event.target)" />
                        </label>
                    </div>
                     @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                     <div x-show="fileName" class="mt-2 text-xs text-green-600 font-medium" style="display: none;">
                        Selected: <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                    </div>
                </div>
                
                <!-- Itinerary Section -->
                <div class="pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-sm font-bold text-gray-900 uppercase">Day Wise Itinerary</h3>
                        <button type="button" onclick="addItineraryDay()" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 font-bold py-1 px-3 rounded-lg transition">
                            + Add Day
                        </button>
                    </div>
                    
                    <div id="itinerary-container" class="space-y-4">
                        @if($post->itinerary && is_array($post->itinerary))
                            @foreach($post->itinerary as $index => $day)
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 relative group" id="day-{{ $login_loop_index = $index + 1 }}">
                                    <button type="button" onclick="removeDay({{ $login_loop_index }})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                    <div class="space-y-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Day Title</label>
                                            <input type="text" name="itinerary[{{ $login_loop_index }}][title]" value="{{ $day['title'] ?? '' }}" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-200">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-gray-500 mb-1">Description</label>
                                            <textarea name="itinerary[{{ $login_loop_index }}][description]" rows="2" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-200">{{ $day['description'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <!-- SEO Settings -->
                <div class="pt-6 border-t border-gray-200" x-data="{
                    seoTitle: '{{ old('seo_title', addslashes($post->seo_title ?? '')) }}',
                    seoDesc: '{{ old('seo_description', addslashes($post->seo_description ?? '')) }}',
                    get titleLen() { return this.seoTitle.length; },
                    get descLen() { return this.seoDesc.length; },
                    get titleColor() { return this.titleLen > 60 ? 'text-red-600' : this.titleLen > 50 ? 'text-yellow-600' : 'text-green-600'; },
                    get descColor() { return this.descLen > 160 ? 'text-red-600' : this.descLen > 140 ? 'text-yellow-600' : 'text-green-600'; }
                }">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase flex items-center gap-2">
                        SEO Settings
                        <span class="text-xs font-normal text-gray-400 normal-case">Helps rank on Google</span>
                    </h3>

                    <!-- Focus Keyword -->
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Focus Keyword</label>
                        <input type="text" name="focus_keyword" value="{{ old('focus_keyword') }}" placeholder="e.g. Bangladesh tour packages" class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:border-red-500 focus:ring-red-200">
                        <p class="text-xs text-gray-400 mt-1">Primary keyword this post should rank for.</p>
                    </div>

                    <!-- SEO Title -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-semibold text-gray-500">SEO Title</label>
                            <span class="text-xs font-semibold" :class="titleColor" x-text="titleLen + '/60'"></span>
                        </div>
                        <input type="text" name="seo_title" x-model="seoTitle" value="{{ old('seo_title', $post->seo_title) }}" maxlength="70" class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:border-red-500 focus:ring-red-200" placeholder="Leave blank to use post title">
                        <div class="mt-1 h-1 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full rounded-full transition-all" :class="titleLen > 60 ? 'bg-red-500' : titleLen > 50 ? 'bg-yellow-400' : 'bg-green-500'" :style="'width:' + Math.min(100, (titleLen/60)*100) + '%'"></div>
                        </div>
                    </div>

                    <!-- Meta Description -->
                    <div class="mb-4">
                        <div class="flex justify-between items-center mb-1">
                            <label class="block text-xs font-semibold text-gray-500">Meta Description</label>
                            <span class="text-xs font-semibold" :class="descColor" x-text="descLen + '/160'"></span>
                        </div>
                        <textarea name="seo_description" x-model="seoDesc" rows="3" maxlength="180" class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:border-red-500 focus:ring-red-200" placeholder="Compelling summary that appears in Google results...">{{ old('seo_description', $post->seo_description) }}</textarea>
                        <div class="mt-1 h-1 rounded-full bg-gray-100 overflow-hidden">
                            <div class="h-full rounded-full transition-all" :class="descLen > 160 ? 'bg-red-500' : descLen > 140 ? 'bg-yellow-400' : 'bg-green-500'" :style="'width:' + Math.min(100, (descLen/160)*100) + '%'"></div>
                        </div>
                    </div>

                    <!-- SERP Preview -->
                    <div class="mt-4 p-4 bg-white rounded-lg border border-gray-200">
                        <p class="text-xs font-semibold text-gray-400 mb-3 uppercase tracking-wider">Google Preview</p>
                        <div class="text-[13px] text-blue-700 font-medium leading-tight truncate" x-text="seoTitle || '{{ addslashes($post->title) }}'"></div>
                        <div class="text-[11px] text-green-700 mt-0.5 truncate">{{ url('/blog') }}/{{ $post->slug }}</div>
                        <div class="text-[12px] text-gray-600 mt-1 line-clamp-2 leading-relaxed" x-text="seoDesc || 'Your meta description will appear here. Make it compelling to improve click-through rates.'"></div>
                    </div>
                </div>

                <div class="pt-6">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-xl shadow transition transform hover:-translate-y-0.5">
                        Update Post
                    </button>
                    <a href="{{ route('admin.blog.index') }}" class="block w-full text-center mt-3 text-sm text-gray-500 hover:text-gray-700">Cancel</a>
                </div>
            </div>
        </div>
    </form>

    @push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jodit@3/build/jodit.min.css">
    <style>
        .jodit-container { border-radius: 0.5rem !important; border-color: #d1d5db !important; }
        .jodit-toolbar__box { border-radius: 0.5rem 0.5rem 0 0 !important; background: #f9fafb !important; }
        .jodit-wysiwyg { font-size: 15px !important; line-height: 1.75 !important; }
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
                }
                
                reader.readAsDataURL(input.files[0]);
            }
        }

        // Slug preview from title
        function slugify(text) {
            return text.toLowerCase().trim()
                .replace(/[^\w\s-]/g, '')
                .replace(/[\s_-]+/g, '-')
                .replace(/^-+|-+$/g, '');
        }
        const titleInput = document.getElementById('post-title');
        const slugPreview = document.getElementById('slug-preview');
        if (titleInput && slugPreview) {
            titleInput.addEventListener('input', function() {
                slugPreview.textContent = slugify(this.value) || '(unchanged)';
            });
        }

        // Initialize dayCount based on existing items
        let dayCount = {{ $post->itinerary && is_array($post->itinerary) ? count($post->itinerary) : 0 }};

        function addItineraryDay() {
            dayCount++;
            const container = document.getElementById('itinerary-container');
            const dayHtml = `
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 relative group" id="day-${dayCount}">
                    <button type="button" onclick="removeDay(${dayCount})" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 opacity-0 group-hover:opacity-100 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Day Title</label>
                            <input type="text" name="itinerary[${dayCount}][title]" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-200" placeholder="e.g. Day ${dayCount}: Arrival in Dhaka">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-500 mb-1">Description</label>
                            <textarea name="itinerary[${dayCount}][description]" rows="2" class="w-full border-gray-300 rounded-md shadow-sm text-sm focus:border-red-500 focus:ring-red-200" placeholder="Brief description of the day's activities..."></textarea>
                        </div>
                    </div>
                </div>
            `;
            container.insertAdjacentHTML('beforeend', dayHtml);
        }

        function removeDay(id) {
            const element = document.getElementById(`day-${id}`);
            if (element) {
                element.remove();
            }
        }
    </script>
    @endpush
</x-admin-layout>
