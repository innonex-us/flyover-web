<x-admin-layout pageTitle="Add Room">

    <div class="mb-8">
        <a href="{{ route('admin.hotels.rooms.index', $hotel) }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Rooms
        </a>
        <h2 class="text-3xl font-bold text-gray-800">Add Room - {{ $hotel->name }}</h2>
    </div>

    <form action="{{ route('admin.hotels.rooms.store', $hotel) }}" method="POST" enctype="multipart/form-data" class="max-w-2xl" x-data="formUploader" @submit.prevent="submitForm">
        @csrf

        <!-- Upload Overlay -->
        <div x-show="uploading" class="fixed inset-0 bg-white/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center" style="display:none;">
            <div class="w-64 bg-gray-200 rounded-full h-4 mb-4 overflow-hidden">
                <div class="bg-red-600 h-4 rounded-full transition-all duration-300" :style="`width: ${progress}%`"></div>
            </div>
            <div class="text-gray-800 font-bold text-lg">Uploading... <span x-text="progress + '%'"></span></div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-6">

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Room Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    placeholder="e.g. Deluxe King Room"
                    class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Room Type <span class="text-red-500">*</span></label>
                    <select name="room_type" required class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                        <option value="">Select type...</option>
                        <option value="standard" {{ old('room_type') == 'standard' ? 'selected' : '' }}>Standard</option>
                        <option value="deluxe" {{ old('room_type') == 'deluxe' ? 'selected' : '' }}>Deluxe</option>
                        <option value="suite" {{ old('room_type') == 'suite' ? 'selected' : '' }}>Suite</option>
                        <option value="family" {{ old('room_type') == 'family' ? 'selected' : '' }}>Family</option>
                    </select>
                    @error('room_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Capacity (max guests) <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity" value="{{ old('capacity', 2) }}" required min="1"
                        class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    @error('capacity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Price per Night (BDT) <span class="text-red-500">*</span></label>
                <input type="number" name="price_per_night" value="{{ old('price_per_night') }}" required min="0" step="0.01"
                    placeholder="0.00"
                    class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                @error('price_per_night') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Description <span class="text-gray-400 font-normal">(optional)</span></label>
                <textarea name="description" rows="4"
                    class="w-full py-3 border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200"
                    placeholder="Describe the room...">{{ old('description') }}</textarea>
                @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div x-data="fileUploader">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Room Image <span class="text-gray-400 font-normal">(optional)</span></label>
                <input type="file" name="image" @change="handleFileChange" accept="image/*"
                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                <div x-show="fileName" class="mt-2 text-xs text-green-600 font-medium" style="display:none;">
                    Selected: <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                </div>
                @error('image') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center">
                <input type="checkbox" name="is_active" value="1" checked
                    class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600">Active (available for booking)</span>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.hotels.rooms.index', $hotel) }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition">Cancel</a>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition">
                    Create Room
                </button>
            </div>
        </div>
    </form>

</x-admin-layout>
