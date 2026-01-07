<x-admin-layout>
    <div class="mb-8">
        <a href="{{ route('admin.visas.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Visas
        </a>
        <h2 class="text-3xl font-bold text-gray-800">Edit Visa: {{ $visa->country }} ({{ $visa->type }})</h2>
    </div>

    <form action="{{ route('admin.visas.update', $visa) }}" method="POST" enctype="multipart/form-data" class="max-w-4xl">
        @csrf
        @method('PUT')
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 md:p-8 space-y-8">
            
            <!-- Basic Info -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>


                        <label class="block text-sm font-semibold text-gray-700 mb-2">Country</label>
                        <input type="text" name="country" value="{{ old('country', $visa->country) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Visa Type</label>
                         <select name="type" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                            <option value="Tourist Visa" {{ $visa->type == 'Tourist Visa' ? 'selected' : '' }}>Tourist Visa</option>
                            <option value="Business Visa" {{ $visa->type == 'Business Visa' ? 'selected' : '' }}>Business Visa</option>
                            <option value="Student Visa" {{ $visa->type == 'Student Visa' ? 'selected' : '' }}>Student Visa</option>
                            <option value="Work Visa" {{ $visa->type == 'Work Visa' ? 'selected' : '' }}>Work Visa</option>
                            <option value="E-Visa" {{ $visa->type == 'E-Visa' ? 'selected' : '' }}>E-Visa</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Price (BDT)</label>
                        <input type="number" name="price" value="{{ old('price', $visa->price) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    </div>

                    <div>
                         <label class="block text-sm font-semibold text-gray-700 mb-2">Processing Time</label>
                        <input type="text" name="processing_time" value="{{ old('processing_time', $visa->processing_time) }}" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    </div>

                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Entry Type</label>
                         <input type="text" name="entry_type" value="{{ old('entry_type', $visa->entry_type) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    </div>

                     <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Validity Info</label>
                         <input type="text" name="validity_info" value="{{ old('validity_info', $visa->validity_info) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">
                    </div>
                </div>
            </div>

            <!-- Docs & Description -->
            <div>
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-6">Details</h3>
                <div class="space-y-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Description / Overview</label>
                        <textarea name="description" rows="4" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('description', $visa->description) }}</textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Thumbnail Image</label>
                        @if($visa->thumbnail)
                            <img src="{{ Storage::url($visa->thumbnail) }}" alt="Current Thumbnail" class="h-32 w-48 object-cover rounded-lg mb-2">
                        @endif
                        <input type="file" name="thumbnail" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
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
                
                <div x-show="tabs === 'required_documents'"> 
                    <div x-data="{ docs: {{ json_encode($visa->required_documents ?? ['']) }} }">
                        <label class="block text-sm font-semibold text-gray-700 mb-4">Required Documents List</label>
                        <template x-for="(doc, index) in docs" :key="index">
                            <div class="flex gap-2 mb-3">
                                <input type="text" name="required_documents[]" x-model="docs[index]" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200" placeholder="e.g. Original Passport">
                                <button type="button" @click="docs.splice(index, 1)" x-show="docs.length > 0" class="text-red-500 hover:text-red-700 p-2">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>
                        </template>
                        <button type="button" @click="docs.push('')" class="mt-2 text-sm text-red-600 font-bold hover:underline py-2 px-3 bg-red-50 rounded-lg border border-red-100">
                            + Add Another Document
                        </button>
                    </div>
                </div>

                <div x-show="tabs === 'fees'" style="display: none;">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Fees Info</label>
                            <textarea name="fees" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('fees', $visa->fees) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Terms & Conditions</label>
                            <textarea name="terms" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('terms', $visa->terms) }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Important Notes</label>
                            <textarea name="important_notes" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200">{{ old('important_notes', $visa->important_notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                <div class="flex items-center">
                    <label class="flex items-center space-x-3 cursor-pointer bg-gray-50 px-4 py-2 rounded-lg border border-gray-200 hover:bg-gray-100 transition">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $visa->is_active) ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 shadow-sm focus:border-red-300 focus:ring focus:ring-red-200 focus:ring-opacity-50 h-5 w-5">
                        <div>
                            <span class="text-sm font-bold text-gray-900 block">Active Status</span>
                            <span class="text-xs text-gray-500 block">Show on website</span>
                        </div>
                    </label>
                </div>
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-8 rounded-xl shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5">
                    Update Visa Service
                </button>
            </div>

        </div>
    </form>
</x-admin-layout>
