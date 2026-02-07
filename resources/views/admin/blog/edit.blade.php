<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Edit Post</h2>
        <p class="text-sm text-gray-500 mt-1">Updating: {{ $post->title }}</p>
    </div>

    <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8 relative" x-data="formUploader" @submit.prevent="submitForm">
        
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
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Post Title</label>
                    <input type="text" name="title" value="{{ old('title', $post->title) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200" placeholder="Enter an engaging title">
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
                    @php
                        $contentValue = old('content', $post->content ?? '');
                        if ($contentValue !== '' && $contentValue === strip_tags($contentValue)) {
                            $lines = explode("\n", $contentValue);
                            $contentValue = '<p>' . implode('</p><p>', array_map('e', $lines)) . '</p>';
                        }
                    @endphp
                    <textarea name="content" rows="15" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 p-4" placeholder="Write your article content here...">{!! $contentValue !!}</textarea>
                    @error('content') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="lg:col-span-1 space-y-6">
                <!-- Status -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <label class="flex items-center space-x-3 mb-4 cursor-pointer">
                        <input type="checkbox" name="is_published" value="1" {{ old('is_published', $post->is_published) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 h-5 w-5">
                        <span class="text-sm font-bold text-gray-900">Publish Immediately</span>
                    </label>
                     @if($post->published_at)
                        <p class="text-xs text-gray-500">Originally published: {{ $post->published_at->format('M d, Y') }}</p>
                    @endif
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
                <div class="pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase">SEO Settings</h3>
                    
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-gray-500 mb-1">SEO Title (Tab Title)</label>
                        <input type="text" name="seo_title" value="{{ old('seo_title', $post->seo_title) }}" class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:border-red-500 focus:ring-red-200">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-500 mb-1">Meta Description</label>
                        <textarea name="seo_description" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm text-sm focus:border-red-500 focus:ring-red-200">{{ old('seo_description', $post->seo_description) }}</textarea>
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

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.2/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: 'textarea[name="content"]',
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            height: 500
        });

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
