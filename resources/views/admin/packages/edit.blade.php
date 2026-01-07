<x-admin-layout>
    <div class="mb-8">
        <a href="{{ route('admin.packages.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Packages
        </a>
        <h2 class="text-3xl font-bold text-gray-800">Edit Package: {{ $package->title }}</h2>
    </div>

    <form action="{{ route('admin.packages.update', $package) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        @method('PUT')
        
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
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail Image</label>
                        @if($package->thumbnail)
                            <img src="{{ Storage::url($package->thumbnail) }}" alt="Current Thumbnail" class="h-32 w-48 object-cover rounded-lg mb-2">
                        @endif
                        <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        <p class="text-xs text-gray-400 mt-1">Leave blank to keep current thumbnail</p>
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
