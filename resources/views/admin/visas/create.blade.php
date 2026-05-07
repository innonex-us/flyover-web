<x-admin-layout>
    <div class="mb-8">
        <a href="{{ route('admin.visas.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Visas
        </a>
        <h2 class="text-3xl font-bold text-gray-800">Add New Visa</h2>
    </div>

    <form action="{{ route('admin.visas.store') }}" method="POST" enctype="multipart/form-data" class="max-w-4xl relative" x-data="formUploader" @submit.prevent="submitForm">
        @csrf
        
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
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                        <input type="text" name="country" value="{{ old('country') }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200" placeholder="e.g. Thailand">
                        @error('country') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Visa Type</label>
                        <select name="type" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                            <option value="Tourist Visa" {{ old('type') == 'Tourist Visa' ? 'selected' : '' }}>Tourist Visa</option>
                            <option value="Business Visa" {{ old('type') == 'Business Visa' ? 'selected' : '' }}>Business Visa</option>
                            <option value="Student Visa" {{ old('type') == 'Student Visa' ? 'selected' : '' }}>Student Visa</option>
                            <option value="Work Visa" {{ old('type') == 'Work Visa' ? 'selected' : '' }}>Work Visa</option>
                            <option value="E-Visa" {{ old('type') == 'E-Visa' ? 'selected' : '' }}>E-Visa</option>
                        </select>
                        @error('type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Price (BDT)</label>
                        <input type="number" name="price" value="{{ old('price') }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200" placeholder="0.00">
                        @error('price') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Validity</label>
                        <input type="text" name="validity" value="{{ old('validity') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200" placeholder="e.g. 3 Months">
                        @error('validity') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Maximum Stay</label>
                        <input type="text" name="maximum_stay" value="{{ old('maximum_stay') }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200" placeholder="e.g. 30 Days">
                        @error('maximum_stay') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <!-- Docs & Description -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Details</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Visa Summary</label>
                        <textarea name="description" rows="4" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('description') }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Use a new line for each summary point. Line breaks will be preserved on the website.</p>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>


                    <div x-data="fileUploader">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail Image (Required)</label>
                        <input type="file" name="thumbnail" @change="handleFileChange" required accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                        @error('thumbnail') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        <div x-show="fileName" class="mt-2 text-xs text-green-600 font-medium" style="display: none;">
                            Selected: <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Additional Info -->
            <div x-data="{ tabs: 'required_documents' }">
                <div class="border-b border-gray-200 mb-4">
                    <nav class="-mb-px flex space-x-6">
                        <a href="#" @click.prevent="tabs = 'required_documents'" :class="tabs === 'required_documents' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium">Required Documents</a>
                        <a href="#" @click.prevent="tabs = 'fees'" :class="tabs === 'fees' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'" class="whitespace-nowrap pb-3 px-1 border-b-2 font-medium">Fees & Terms</a>
                    </nav>
                </div>
                
                <!-- JSON Documents Handling Placeholder: For now simple textareas for structured text if JSON logic is too complex for basic CRUD -->
                <!-- We will rely on simple fields or text areas for now as user didn't ask for full JSON builder UI yet -->
                <div x-show="tabs === 'required_documents'"> 
                    <div x-data="{ 
                        sections: [
                            { 
                                section: 'Job Holders', 
                                documents: [''] 
                            }
                        ] 
                    }">
                        <label class="block text-sm font-semibold text-gray-700 mb-4">Required Documents List</label>
                        <template x-for="(section, sIndex) in sections" :key="sIndex">
                            <div class="mb-6 p-5 border border-gray-200 rounded-xl bg-gray-50/50">
                                <div class="flex justify-between items-center mb-4">
                                    <input type="text" :name="'required_documents[' + sIndex + '][section]'" x-model="sections[sIndex].section" class="font-bold text-gray-800 bg-white border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 w-full max-w-md" placeholder="Section Title (e.g. Job Holders)" required>
                                    <button type="button" @click="sections.splice(sIndex, 1)" class="text-red-500 hover:text-red-700 p-2 ml-4 bg-white rounded-lg border border-red-100 shadow-sm" title="Remove Section">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </div>
                                
                                <div class="space-y-3 pl-4 sm:pl-6 border-l-2 border-red-200">
                                    <template x-for="(doc, dIndex) in sections[sIndex].documents" :key="dIndex">
                                        <div class="flex gap-2 items-start">
                                            <input type="text" :name="'required_documents[' + sIndex + '][documents][]'" x-model="sections[sIndex].documents[dIndex]" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 text-sm" placeholder="e.g. A passport valid for at least seven (7) months..." required>
                                            <button type="button" @click="sections[sIndex].documents.splice(dIndex, 1)" x-show="sections[sIndex].documents.length > 1" class="text-gray-400 hover:text-red-600 p-2 mt-1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button" @click="sections[sIndex].documents.push('')" class="text-xs text-red-600 font-bold hover:underline py-1.5 px-3 bg-white rounded border border-red-100 flex items-center mt-2 shadow-sm transition-shadow hover:shadow">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Add Document
                                    </button>
                                </div>
                            </div>
                        </template>
                        
                        <button type="button" @click="sections.push({ section: '', documents: [''] })" class="inline-flex items-center mt-2 text-sm text-white font-bold hover:bg-red-700 py-2.5 px-4 bg-red-600 rounded-xl shadow-sm hover:shadow transition-all">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Add New Section
                        </button>
                    </div>
                </div>

                <div x-show="tabs === 'fees'" style="display: none;">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Terms & Conditions</label>
                            <textarea name="terms" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('terms') }}</textarea>
                            @error('terms') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Important Notes</label>
                            <textarea name="important_notes" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('important_notes') }}</textarea>
                            @error('important_notes') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center">
                    <label class="flex items-center space-x-3 cursor-pointer bg-gray-50 px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 h-5 w-5">
                        <div>
                            <span class="text-sm font-bold text-gray-900 block">Active Status</span>
                            <span class="text-xs text-gray-500 block">Show on website</span>
                        </div>
                    </label>
                    @error('is_active') <p class="text-red-500 text-xs mt-1 ml-3">{{ $message }}</p> @enderror
                </div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                    Create Visa Service
                </button>
            </div>

        </div>
    </form>
</x-admin-layout>
