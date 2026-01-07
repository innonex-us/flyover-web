<x-admin-layout>
    <div class="mb-8">
        <h2 class="text-3xl font-bold text-gray-800">Edit Post</h2>
        <p class="text-sm text-gray-500 mt-1">Updating: {{ $post->title }}</p>
    </div>

    <form action="{{ route('admin.blog.update', $post) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-xl shadow-sm border border-gray-100 p-8">
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
                    <textarea name="content" rows="15" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 p-4" placeholder="Write your article content here...">{{ old('content', $post->content) }}</textarea>
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
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Featured Image</label>
                     @if($post->image)
                        <div class="mb-2">
                             <img src="{{ Storage::url($post->image) }}" class="w-full h-auto rounded-lg shadow-sm">
                        </div>
                    @endif

                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition relative overflow-hidden" id="drop-zone">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6" id="upload-placeholder">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="text-xs text-gray-500">Upload to replace</p>
                            </div>
                            <img id="image-preview" src="#" alt="Preview" class="absolute inset-0 w-full h-full object-cover hidden">
                            <input type="file" name="image" class="hidden" accept="image/*" onchange="previewImage(this)" />
                        </label>
                    </div>
                    <script>
                        function previewImage(input) {
                            if (input.files && input.files[0]) {
                                var reader = new FileReader();
                                reader.onload = function(e) {
                                    document.getElementById('image-preview').src = e.target.result;
                                    document.getElementById('image-preview').classList.remove('hidden');
                                    document.getElementById('upload-placeholder').classList.add('hidden');
                                }
                                reader.readAsDataURL(input.files[0]);
                            }
                        }
                    </script>
                     @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
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
    </script>
    @endpush
</x-admin-layout>
