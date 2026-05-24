<x-admin-layout pageTitle="Edit Hotel">

    <div class="mb-8">
        <a href="{{ route('admin.hotels.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Hotels
        </a>
        <h2 class="text-3xl font-bold text-gray-800">Edit Hotel</h2>
    </div>

    <form action="{{ route('admin.hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl" x-data="formUploader" @submit.prevent="submitForm">
        @csrf
        @method('PUT')

        <!-- Upload Overlay -->
        <div x-show="uploading" class="fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center" style="display:none;">
            <div class="w-64 bg-gray-200 rounded-full h-4 mb-4 overflow-hidden">
                <div class="bg-red-600 h-4 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
            </div>
            <div class="text-gray-800 font-bold text-lg">Uploading... <span x-text="progress + '%'"></span></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Hotel Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $hotel->name) }}" required
                    class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Location <span class="text-red-500">*</span></label>
                <input type="text" name="location" value="{{ old('location', $hotel->location) }}" required
                    class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                @error('location') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Star Rating <span class="text-red-500">*</span></label>
                <select name="star_rating" required class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    @foreach([1,2,3,4,5] as $star)
                    <option value="{{ $star }}" {{ old('star_rating', (int)$hotel->star_rating) == $star ? 'selected' : '' }}>
                        {{ $star }} Star{{ $star > 1 ? 's' : '' }}
                    </option>
                    @endforeach
                </select>
                @error('star_rating') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                <textarea name="description" rows="5"
                    class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('description', $hotel->description) }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Amenities <span class="text-gray-400 font-normal">(comma-separated)</span></label>
                <input type="text" name="amenities" value="{{ old('amenities', is_array($hotel->amenities) ? implode(', ', $hotel->amenities) : '') }}"
                    placeholder="WiFi, Pool, Gym, Restaurant"
                    class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                @error('amenities') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div x-data="fileUploader">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail Image</label>
                @if($hotel->thumbnail)
                    <div class="mb-3">
                        <img src="{{ Storage::url($hotel->thumbnail) }}" alt="" class="h-24 w-auto rounded-lg object-cover">
                        <p class="text-xs text-gray-400 mt-1">Current thumbnail. Upload a new one to replace.</p>
                    </div>
                @endif
                <input type="file" name="thumbnail" @change="handleFileChange" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                <div x-show="fileName" class="mt-2 text-xs text-green-600 font-medium" style="display:none;">
                    Selected: <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                </div>
                @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $hotel->is_active) ? 'checked' : '' }}
                    class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600">Active (visible to users)</span>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.hotels.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition">Cancel</a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    Update Hotel
                </button>
            </div>
        </div>
    </form>

</x-admin-layout>
