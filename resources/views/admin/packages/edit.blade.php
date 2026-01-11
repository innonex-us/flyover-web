<x-admin-layout>
    <div class="mb-8">
        <a href="{{ route('admin.packages.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Packages
        </a>
        <h2 class="text-3xl font-bold text-gray-800">Edit Package: {{ $package->title }}</h2>
    </div>

    <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl relative" x-data="formUploader" @submit.prevent="submitForm">
        @csrf
        @method('PUT')
        
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

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-8">
            
            <!-- Basic Info -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Package Title</label>
                        <input type="text" name="title" value="{{ old('title', $package->title) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Price (BDT)</label>
                        <input type="number" name="price" value="{{ old('price', $package->price) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Duration (Days)</label>
                        <input type="number" name="duration_days" value="{{ old('duration_days', $package->duration_days) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Location</label>
                        <input type="text" name="location" value="{{ old('location', $package->location) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Start Date</label>
                        <input type="date" name="start_date" value="{{ old('start_date', optional($package->start_date)->format('Y-m-d')) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Details</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('description', $package->description) }}</textarea>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Policy</label>
                        <textarea name="policy" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('policy', $package->policy) }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Requirements</label>
                        <textarea name="requirements" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('requirements', $package->requirements) }}</textarea>
                    </div>

                    <!-- Itinerary Builder -->
                    <div x-data="{ days: {{ json_encode($package->itinerary ?? [['day' => 1, 'activity' => '']]) }} }">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Itinerary</label>
                        <div class="space-y-4">
                            <template x-for="(day, index) in days" :key="index">
                                <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 relative">
                                    <div class="flex justify-between mb-2">
                                        <h4 class="font-bold text-gray-700" x-text="'Day ' + (index + 1)"></h4>
                                        <button type="button" @click="days.splice(index, 1)" class="text-red-500 hover:text-red-700 text-sm">Remove</button>
                                    </div>
                                    <input type="hidden" :name="'itinerary[' + index + '][day]'" :value="index + 1">
                                    <input type="text" :name="'itinerary[' + index + '][activity]'" x-model="day.activity" placeholder="Activity description..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-200 text-sm">
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="days.push({ day: days.length + 1, activity: '' })" class="mt-2 text-sm text-blue-600 font-bold hover:underline">
                            + Add Day
                        </button>
                    </div>
                </div>
            </div>

            <!-- Inclusions/Exclusions -->
             <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div x-data="{ items: {{ json_encode($package->inclusions ?? ['']) }} }">
                    <h3 class="text-lg font-bold text-green-700 border-b pb-2 mb-4">Inclusions</h3>
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="inclusions[]" x-model="items[index]" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-200 text-sm">
                            <button type="button" @click="items.splice(index, 1)" x-show="items.length > 0" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="items.push('')" class="text-sm text-green-600 font-semibold hover:underline">+ Add Inclusion</button>
                </div>

                <div x-data="{ items: {{ json_encode($package->exclusions ?? ['']) }} }">
                    <h3 class="text-lg font-bold text-red-700 border-b pb-2 mb-4">Exclusions</h3>
                     <template x-for="(item, index) in items" :key="index">
                        <div class="flex gap-2 mb-2">
                            <input type="text" name="exclusions[]" x-model="items[index]" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm">
                            <button type="button" @click="items.splice(index, 1)" x-show="items.length > 0" class="text-red-500 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    </template>
                    <button type="button" @click="items.push('')" class="text-sm text-red-600 font-semibold hover:underline">+ Add Exclusion</button>
                </div>
            </div>

            <!-- Media -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Media</h3>
                <div class="space-y-6">
                    <div x-data="fileUploader">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail Image</label>
                        @if($package->thumbnail)
                            <img src="{{ Storage::url($package->thumbnail) }}" alt="Current Thumbnail" class="h-32 w-48 object-cover rounded-lg mb-2 shadow-sm">
                        @endif
                        <input type="file" name="thumbnail" @change="handleFileChange" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        <p class="text-xs text-gray-400 mt-1">Leave blank to keep current thumbnail</p>
                        <div x-show="fileName" class="mt-2 text-xs text-green-600 font-medium" style="display: none;">
                            Selected: <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                        </div>
                    </div>

                    <div x-data="fileUploader">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Gallery Images</label>
                        @if($package->images && count($package->images) > 0)
                            <div class="flex gap-2 mb-3 overflow-x-auto py-2">
                                @foreach($package->images as $img)
                                    <img src="{{ Storage::url($img) }}" class="h-20 w-20 object-cover rounded-lg shadow-sm border border-gray-200">
                                @endforeach
                            </div>
                        @endif
                        <input type="file" name="images[]" @change="handleFileChange" multiple accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        <p class="text-xs text-gray-400 mt-1">Upload multiple images to append to the gallery.</p>
                        <div x-show="fileName" class="mt-2 text-xs text-green-600 font-medium" style="display: none;">
                            Selected: <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                        </div>
                    </div>
                </div>
            </div>

             <div class="flex items-center">
                <input type="checkbox" name="is_active" value="1" {{ $package->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50">
                <span class="ml-2 text-sm text-gray-600">Active (Visible to users)</span>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                    Update Package
                </button>
            </div>

        </div>
    </form>
</x-admin-layout>
