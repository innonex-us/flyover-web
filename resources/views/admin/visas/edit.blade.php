<x-admin-layout pageTitle="Modify Visa Service">

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
            align-items: center;
            gap: 0.5rem;
        }
        .section-label::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #f3f4f6;
        }
        .input-label {
            font-size: 0.75rem;
            font-weight: 700;
            color: #4b5563;
            margin-bottom: 0.375rem;
            display: block;
        }
        .custom-input {
            width: 100%;
            padding: 0.625rem 1rem;
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }
        .custom-input:focus {
            background: #fff;
            border-color: #dc2626; ring: 4px; outline: none; --tw-ring-color: rgba(220, 38, 38, 0.1);
        }
        .btn-brand {
            background: #dc2626;
            color: #fff;
        }
        .btn-brand:hover {
            background: #b91c1c;
        }
        .dynamic-row {
            background: #f9fafb;
            border: 1px solid #f3f4f6;
            border-radius: 0.75rem;
            padding: 1rem;
            margin-bottom: 1rem;
            position: relative;
        }
    </style>
@endpush

<div class="max-w-6xl mx-auto" x-data="formUploader" @submit.prevent="submitForm">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <a href="{{ route('admin.visas.index') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-red-600 transition flex items-center gap-1 mb-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                Back to Archive
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Edit: {{ $visa->country }}</h1>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                <input type="checkbox" name="is_active" value="1" {{ $visa->is_active ? 'checked' : '' }} class="rounded border-gray-300 text-red-600 focus:ring-red-100">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-tighter">Public Visibility</span>
            </div>
            <button type="submit" form="visa-form" class="btn-brand px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-900/10 transition-all transform hover:-translate-y-0.5">
                Commit Changes
            </button>
        </div>
    </div>

    <form id="visa-form" action="{{ route('admin.visas.update', $visa) }}" method="POST" enctype="multipart/form-data" class="relative">
        @csrf
        @method('PUT')

        <!-- Upload Overlay -->
        <div x-show="uploading" x-transition class="absolute inset-0 bg-white/90 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-2xl" style="display: none;">
            <div class="w-64 bg-gray-100 rounded-full h-2 mb-6 overflow-hidden">
                <div class="bg-red-600 h-full transition-all duration-500" :style="`width: ${progress}%`"></div>
            </div>
            <p class="text-sm font-black text-gray-900 uppercase tracking-widest">Updating Service: <span x-text="progress + '%'"></span></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content Column --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Basic Info --}}
                <div class="form-card p-6 sm:p-8">
                    <h3 class="section-label">Service Core</h3>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="input-label">Target Territory / Country</label>
                                <input type="text" name="country" value="{{ old('country', $visa->country) }}" required class="custom-input font-bold" placeholder="e.g. Thailand">
                                @error('country') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                            </div>

                            <div>
                                <label class="input-label">Visa Classification</label>
                                <select name="type" required class="custom-input font-bold">
                                    <option value="Tourist Visa" {{ old('type', $visa->type) == 'Tourist Visa' ? 'selected' : '' }}>Tourist Visa</option>
                                    <option value="Business Visa" {{ old('type', $visa->type) == 'Business Visa' ? 'selected' : '' }}>Business Visa</option>
                                    <option value="Student Visa" {{ old('type', $visa->type) == 'Student Visa' ? 'selected' : '' }}>Student Visa</option>
                                    <option value="Work Visa" {{ old('type', $visa->type) == 'Work Visa' ? 'selected' : '' }}>Work Visa</option>
                                    <option value="E-Visa" {{ old('type', $visa->type) == 'E-Visa' ? 'selected' : '' }}>E-Visa</option>
                                </select>
                                @error('type') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="input-label">Visa Summary / Overview</label>
                            <textarea name="description" rows="4" required class="custom-input" placeholder="Primary service description...">{{ old('description', $visa->description) }}</textarea>
                            @error('description') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Documents & Terms --}}
                <div class="form-card p-6 sm:p-8" x-data="{ tabs: 'docs' }">
                    <div class="flex items-center justify-between mb-6 border-b border-gray-50 pb-4">
                        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Procedural Assets</h3>
                        <div class="flex gap-4">
                            <button type="button" @click="tabs = 'docs'" :class="tabs === 'docs' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-400'" class="text-[10px] font-black uppercase tracking-tighter pb-1 transition-all">Required Docs</button>
                            <button type="button" @click="tabs = 'terms'" :class="tabs === 'terms' ? 'text-red-600 border-b-2 border-red-600' : 'text-gray-400'" class="text-[10px] font-black uppercase tracking-tighter pb-1 transition-all">Fees & Legal</button>
                        </div>
                    </div>
                    
                    <div x-show="tabs === 'docs'">
                        @php
                            $oldDocs = $visa->required_documents ?? [];
                            $formattedDocs = [];
                            if (!empty($oldDocs)) {
                                $oldDocsReindexed = array_values($oldDocs);
                                if (isset($oldDocsReindexed[0]['section'])) {
                                    foreach ($oldDocsReindexed as &$sec) {
                                        $sec['documents'] = isset($sec['documents']) && is_array($sec['documents']) ? array_values($sec['documents']) : [];
                                    }
                                    $formattedDocs = $oldDocsReindexed;
                                } else {
                                    $formattedDocs = [['section' => 'General Requirements', 'documents' => array_values($oldDocs)]];
                                }
                            } else {
                                $formattedDocs = [['section' => 'Job Holders', 'documents' => ['']]];
                            }
                        @endphp
                        <div x-data="{ sections: {{ json_encode($formattedDocs) }} }">
                            <div class="space-y-4">
                                <template x-for="(section, sIndex) in sections" :key="sIndex">
                                    <div class="dynamic-row">
                                        <div class="flex items-center justify-between mb-4">
                                            <input type="text" :name="'required_documents[' + sIndex + '][section]'" x-model="section.section" class="bg-transparent border-none p-0 text-xs font-black text-gray-900 uppercase tracking-tight focus:ring-0 w-full" placeholder="SECTION TITLE (e.g. BUSINESS PERSON)">
                                            <button type="button" @click="sections.splice(sIndex, 1)" x-show="sections.length > 1" class="text-gray-400 hover:text-red-600 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                                        </div>
                                        
                                        <div class="space-y-2 pl-4 border-l-2 border-red-50">
                                            <template x-for="(doc, dIndex) in section.documents" :key="dIndex">
                                                <div class="flex gap-2">
                                                    <input type="text" :name="'required_documents[' + sIndex + '][documents][]'" x-model="section.documents[dIndex]" class="custom-input text-xs py-1.5" placeholder="Document requirement detail..." required>
                                                    <button type="button" @click="section.documents.splice(dIndex, 1)" x-show="section.documents.length > 1" class="text-gray-300 hover:text-red-400 transition"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                                </div>
                                            </template>
                                            <button type="button" @click="section.documents.push('')" class="text-[10px] font-black text-red-600 uppercase tracking-tighter hover:underline flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg> Add Row
                                            </button>
                                        </div>
                                    </div>
                                </template>
                                <button type="button" @click="sections.push({ section: '', documents: [''] })" class="w-full py-3 border-2 border-dashed border-gray-100 rounded-xl text-[10px] font-black text-gray-400 uppercase tracking-widest hover:border-red-200 hover:text-red-600 transition flex items-center justify-center gap-2">+ Append Category Section</button>
                            </div>
                        </div>
                    </div>

                    <div x-show="tabs === 'terms'" class="space-y-6">
                        <div>
                            <label class="input-label">Legal Terms & Conditions</label>
                            <textarea name="terms" rows="4" class="custom-input text-xs">{{ old('terms', $visa->terms) }}</textarea>
                        </div>
                        <div>
                            <label class="input-label">Critical Operational Notes</label>
                            <textarea name="important_notes" rows="4" class="custom-input text-xs">{{ old('important_notes', $visa->important_notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="space-y-8">
                {{-- Pricing & Specs --}}
                <div class="form-card p-6">
                    <h3 class="section-label">Parameters</h3>
                    <div class="space-y-5">
                        <div>
                            <label class="input-label">Standard Price (BDT)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">৳</span>
                                <input type="number" name="price" value="{{ old('price', $visa->price) }}" required class="custom-input pl-8 font-black text-gray-900" placeholder="0.00">
                            </div>
                            @error('price') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="input-label">Service Validity</label>
                            <input type="text" name="validity" value="{{ old('validity', $visa->validity) }}" class="custom-input font-bold" placeholder="e.g. 90 Days">
                        </div>

                        <div>
                            <label class="input-label">Max Permitted Stay</label>
                            <input type="text" name="maximum_stay" value="{{ old('maximum_stay', $visa->maximum_stay) }}" class="custom-input font-bold" placeholder="e.g. 30 Days">
                        </div>
                    </div>
                </div>

                {{-- Media Manager --}}
                <div class="form-card p-6">
                    <h3 class="section-label">Visual Asset</h3>
                    <div x-data="fileUploader">
                        <label class="input-label">Thumbnail Image</label>
                        @if($visa->thumbnail)
                            <div class="relative mb-3 group inline-block">
                                <img src="{{ Storage::url($visa->thumbnail) }}" alt="Current Thumbnail" class="w-48 h-32 object-cover rounded-xl border border-gray-100 shadow-sm">
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
                        @error('thumbnail') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
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
            uploading: false,
            progress: 0,
            submitForm(e) {
                this.uploading = true;
                this.progress = 0;
                
                const form = e.target;
                const formData = new FormData(form);
                const xhr = new XMLHttpRequest();
                
                xhr.open('POST', form.action, true);
                xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                
                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        this.progress = Math.round((e.loaded / e.total) * 100);
                    }
                });
                
                xhr.addEventListener('load', () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        try {
                            const response = JSON.parse(xhr.responseText);
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else {
                                window.location.href = "{{ route('admin.visas.index') }}";
                            }
                        } catch (e) {
                            window.location.href = "{{ route('admin.visas.index') }}";
                        }
                    } else {
                        this.uploading = false;
                        alert('Update failed. Please check for validation errors.');
                    }
                });
                
                xhr.addEventListener('error', () => {
                    this.uploading = false;
                    alert('An error occurred during transmission.');
                });
                
                xhr.send(formData);
            }
        }
    }

    function fileUploader() {
        return {
            fileName: '',
            fileSize: '',
            handleFileChange(e) {
                const files = e.target.files;
                if (files.length > 0) {
                    this.fileName = files[0].name;
                    this.fileSize = (files[0].size / 1024).toFixed(1) + ' KB';
                }
            },
            clearSelection(inputId) {
                const input = document.getElementById(inputId);
                if (input) {
                    input.value = '';
                    this.fileName = '';
                    this.fileSize = '';
                }
            }
        }
    }
</script>
@endpush

</x-admin-layout>
