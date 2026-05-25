<x-admin-layout pageTitle="Modify Hotel Facility">

@push('styles')
    <style>
        .form-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 10px 15px -5px rgba(0,0,0,.02);
            border: 1px solid #f3f4f6;
        }
        .section-label {
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #9ca3af;
            margin-bottom: 1rem;
            display: flex;
            align-items: center; gap: 0.5rem;
        }
        .section-label::after {
            content: ""; flex: 1; height: 1px; background: #f3f4f6;
        }
        .input-label {
            font-size: 0.75rem; font-weight: 700; color: #4b5563; margin-bottom: 0.375rem; display: block;
        }
        .custom-input {
            width: 100%; padding: 0.625rem 1rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 0.875rem; transition: all 0.2s ease;
        }
        .custom-input:focus {
            background: #fff; border-color: #dc2626; ring: 4px; outline: none; --tw-ring-color: rgba(220, 38, 38, 0.1);
        }
        .btn-brand {
            background: #dc2626; color: #fff;
        }
        .btn-brand:hover {
            background: #b91c1c;
        }
    </style>
@endpush

<div class="max-w-5xl mx-auto" x-data="formUploader" @submit.prevent="submitForm">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.hotels.index') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-red-600 transition flex items-center gap-1 mb-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                Back to Archive
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Edit: {{ $hotel->name }}</h1>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                <input type="checkbox" name="is_active" value="1" {{ $hotel->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-100">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-tighter">Public</span>
            </div>
            <button type="submit" form="hotel-form" class="btn-brand px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-900/10 transition-all transform hover:-translate-y-0.5">
                Commit Changes
            </button>
        </div>
    </div>

    <form id="hotel-form" action="{{ route('admin.hotels.update', $hotel) }}" method="POST" enctype="multipart/form-data" class="relative">
        @csrf
        @method('PUT')

        <!-- Upload Overlay -->
        <div x-show="uploading" x-transition class="absolute inset-0 bg-white/90 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-2xl" style="display: none;">
            <div class="w-64 bg-gray-100 rounded-full h-2 mb-6 overflow-hidden">
                <div class="bg-red-600 h-full transition-all duration-500" :style="`width: ${progress}%`"></div>
            </div>
            <p class="text-sm font-black text-gray-900 uppercase tracking-widest">Updating Data: <span x-text="progress + '%'"></span></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-8">
                <div class="form-card p-6 sm:p-8">
                    <h3 class="section-label">Identity & Context</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="input-label">Official Hotel Name</label>
                            <input type="text" name="name" value="{{ old('name', $hotel->name) }}" required class="custom-input font-bold" placeholder="e.g. The Grand Palace Resort">
                            @error('name') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="input-label">Geo-Location</label>
                                <input type="text" name="location" value="{{ old('location', $hotel->location) }}" required class="custom-input" placeholder="e.g. Cox's Bazar, Bangladesh">
                            </div>
                            <div>
                                <label class="input-label">Quality Rating</label>
                                <select name="star_rating" required class="custom-input font-bold">
                                    <option value="">Select Stars</option>
                                    @foreach([1,2,3,4,5] as $star)
                                    <option value="{{ $star }}" {{ old('star_rating', $hotel->star_rating) == $star ? 'selected' : '' }}>{{ $star }} Star{{ $star > 1 ? 's' : '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="input-label">Official Description</label>
                            <textarea name="description" rows="5" class="custom-input text-xs" placeholder="Describe the facility, heritage, and unique features...">{{ old('description', $hotel->description) }}</textarea>
                        </div>

                        <div>
                            <label class="input-label">Core Amenities (Comma Separated)</label>
                            <input type="text" name="amenities" value="{{ old('amenities', is_array($hotel->amenities) ? implode(', ', $hotel->amenities) : $hotel->amenities) }}" placeholder="e.g. WiFi, Pool, Spa, Gym, Breakfast" class="custom-input text-xs">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-8">
                <div class="form-card p-6">
                    <h3 class="section-label">Visual Assets</h3>

                    {{-- Thumbnail --}}
                    <div x-data="fileUploader" class="mb-6">
                        <label class="input-label">Thumbnail Image</label>
                        @if($hotel->thumbnail)
                            <div class="relative mb-3 group inline-block w-full">
                                <img src="{{ Storage::url($hotel->thumbnail) }}" class="w-full h-32 object-cover rounded-xl border border-gray-100 shadow-sm">
                            </div>
                        @endif
                        <input type="file" name="thumbnail" id="thumbnail-input" @change="handleFileChange" accept="image/*" class="w-full text-[10px] text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition">
                        <div x-show="fileName" class="mt-2 p-2 bg-emerald-50 rounded-lg border border-emerald-100 flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-tighter truncate" x-text="fileName"></span>
                            </div>
                            <button type="button" @click="clearSelection('thumbnail-input')" class="text-emerald-500 hover:text-red-500"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg></button>
                        </div>
                    </div>

                    {{-- Gallery --}}
                    <div x-data="galleryUploader">
                        <label class="input-label">Image Gallery <span class="text-gray-400 font-normal normal-case">(multiple)</span></label>

                        {{-- Existing gallery images --}}
                        @if($hotel->gallery && count($hotel->gallery) > 0)
                            <div class="grid grid-cols-3 gap-2 mb-3">
                                @foreach($hotel->gallery as $image)
                                    <div class="relative group" x-data="{ marked: false }">
                                        <img src="{{ Storage::url($image) }}" class="w-full h-16 object-cover rounded-lg border border-gray-100" :class="marked ? 'opacity-40 ring-2 ring-red-400' : ''">
                                        <input type="checkbox" name="remove_gallery[]" value="{{ $image }}" x-model="marked" class="sr-only">
                                        <button type="button" @click="marked = !marked" :class="marked ? 'bg-red-500 text-white' : 'bg-white text-gray-400 opacity-0 group-hover:opacity-100'" class="absolute top-0.5 right-0.5 rounded-full p-0.5 shadow transition">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-[9px] text-gray-400 font-bold uppercase tracking-tight mb-3">Click × to mark for removal on save</p>
                        @endif

                        <input type="file" name="gallery[]" id="gallery-input" @change="handleGalleryChange" accept="image/*" multiple class="w-full text-[10px] text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-gray-50 file:text-gray-600 hover:file:bg-gray-100 transition">
                        <template x-if="previews.length > 0">
                            <div class="mt-3 grid grid-cols-3 gap-2">
                                <template x-for="(src, i) in previews" :key="i">
                                    <div class="relative group">
                                        <img :src="src" class="w-full h-16 object-cover rounded-lg border border-gray-100">
                                        <button type="button" @click="removePreview(i)" class="absolute top-0.5 right-0.5 bg-white rounded-full p-0.5 shadow opacity-0 group-hover:opacity-100 transition text-red-500 hover:text-red-700">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                        <p x-show="previews.length > 0" class="text-[9px] text-gray-400 font-bold uppercase tracking-tight mt-1" x-text="previews.length + ' new image(s) to add'"></p>
                    </div>
                </div>
                
                <div class="p-6 bg-red-50 rounded-2xl border border-red-100">
                    <h4 class="text-[10px] font-black text-red-700 uppercase tracking-widest mb-2">Notice</h4>
                    <p class="text-[10px] text-red-600 leading-relaxed font-medium">Updating hotel details will reflect immediately across all associated room listings and customer search results.</p>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
    function formUploader() {
        return {
            uploading: false, progress: 0,
            submitForm(e) {
                this.uploading = true; this.progress = 0;
                const form = e.target; const formData = new FormData(form); const xhr = new XMLHttpRequest();
                xhr.open('POST', form.action, true); xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                xhr.upload.addEventListener('progress', (e) => { if (e.lengthComputable) this.progress = Math.round((e.loaded / e.total) * 100); });
                xhr.addEventListener('load', () => {
                    if (xhr.status >= 200 && xhr.status < 300) { window.location.href = "{{ route('admin.hotels.index') }}"; } 
                    else { this.uploading = false; alert('Process failed.'); }
                });
                xhr.send(formData);
            }
        }
    }
    function fileUploader() {
        return {
            fileName: '', handleFileChange(e) { if (e.target.files.length > 0) this.fileName = e.target.files[0].name; },
            clearSelection(inputId) { const input = document.getElementById(inputId); if (input) { input.value = ''; this.fileName = ''; } }
        }
    }
    function galleryUploader() {
        return {
            files: [], previews: [],
            handleGalleryChange(e) {
                const newFiles = Array.from(e.target.files);
                newFiles.forEach(file => {
                    this.files.push(file);
                    const reader = new FileReader();
                    reader.onload = (r) => this.previews.push(r.target.result);
                    reader.readAsDataURL(file);
                });
                this.syncInput();
            },
            removePreview(index) {
                this.files.splice(index, 1);
                this.previews.splice(index, 1);
                this.syncInput();
            },
            syncInput() {
                const dt = new DataTransfer();
                this.files.forEach(f => dt.items.add(f));
                document.getElementById('gallery-input').files = dt.files;
            }
        }
    }
</script>
@endpush

</x-admin-layout>
