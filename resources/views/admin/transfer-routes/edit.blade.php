<x-admin-layout pageTitle="Modify Transfer Route">

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

<div class="max-w-4xl mx-auto" x-data="formUploader" @submit.prevent="submitForm">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.transfer-routes.index') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-red-600 transition flex items-center gap-1 mb-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                Back to Archive
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Edit: {{ $transferRoute->name }}</h1>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                <input type="checkbox" name="is_active" value="1" {{ $transferRoute->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-100">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-tighter">Public</span>
            </div>
            <button type="submit" form="route-form" class="btn-brand px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-900/10 transition-all transform hover:-translate-y-0.5">
                Commit Changes
            </button>
        </div>
    </div>

    <form id="route-form" action="{{ route('admin.transfer-routes.update', $transferRoute) }}" method="POST" enctype="multipart/form-data" class="relative">
        @csrf
        @method('PUT')

        <!-- Upload Overlay -->
        <div x-show="uploading" x-transition class="absolute inset-0 bg-white/90 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-2xl" style="display: none;">
            <div class="w-64 bg-gray-100 rounded-full h-2 mb-6 overflow-hidden">
                <div class="bg-red-600 h-full transition-all duration-500" :style="`width: ${progress}%`"></div>
            </div>
            <p class="text-sm font-black text-gray-900 uppercase tracking-widest">Updating Route: <span x-text="progress + '%'"></span></p>
        </div>

        <div class="space-y-8">
            {{-- Main Section --}}
            <div class="form-card p-6 sm:p-8">
                <h3 class="section-label">Route Specification</h3>
                
                <div class="space-y-6">
                    <div>
                        <label class="input-label">Public Route Name</label>
                        <input type="text" name="name" value="{{ old('name', $transferRoute->name) }}" required class="custom-input font-bold" placeholder="e.g. Airport → Cox's Bazar Hotel">
                        @error('name') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="input-label">Pickup Hub</label>
                            <input type="text" name="pickup_location" value="{{ old('pickup_location', $transferRoute->pickup_location) }}" required class="custom-input" placeholder="e.g. Dhaka Airport">
                        </div>
                        <div>
                            <label class="input-label">Destination Hub</label>
                            <input type="text" name="drop_location" value="{{ old('drop_location', $transferRoute->drop_location) }}" required class="custom-input" placeholder="e.g. Ocean Blue Hotel">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="input-label">Price per Traveler (BDT)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">৳</span>
                                <input type="number" name="price_per_person" value="{{ old('price_per_person', $transferRoute->price_per_person) }}" required class="custom-input pl-8 font-black text-gray-900" placeholder="0.00">
                            </div>
                        </div>
                        <div x-data="fileUploader">
                            <label class="input-label">Thumbnail Asset</label>
                            @if($transferRoute->thumbnail)
                                <div class="relative mb-3 group inline-block">
                                    <img src="{{ Storage::url($transferRoute->thumbnail) }}" class="w-32 h-20 object-cover rounded-xl border border-gray-100 shadow-sm">
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
                    </div>

                    <div>
                        <label class="input-label">Logistics Description</label>
                        <textarea name="description" rows="4" class="custom-input text-xs" placeholder="Operational details, vehicle type, etc...">{{ old('description', $transferRoute->description) }}</textarea>
                    </div>
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
                    if (xhr.status >= 200 && xhr.status < 300) { window.location.href = "{{ route('admin.transfer-routes.index') }}"; } 
                    else { this.uploading = false; alert('Update failed.'); }
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
</script>
@endpush

</x-admin-layout>
