<x-admin-layout pageTitle="New Tour Package">

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
            border-color: #dc2626;
            ring: 4px;
            outline: none;
            --tw-ring-color: rgba(220, 38, 38, 0.1);
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
            <a href="{{ route('admin.packages.index') }}" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest hover:text-red-600 transition flex items-center gap-1 mb-2">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"/></svg>
                Back to Archive
            </a>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Construct New Package</h1>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 bg-white px-4 py-2 rounded-xl border border-gray-100 shadow-sm">
                <input type="checkbox" name="is_active" value="1" checked class="rounded border-gray-300 text-red-600 focus:ring-red-100">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-tighter">Public Visibility</span>
            </div>
            <button type="submit" form="package-form" class="btn-brand px-8 py-3 rounded-xl font-bold shadow-lg shadow-red-900/10 transition-all transform hover:-translate-y-0.5">
                Deploy Package
            </button>
        </div>
    </div>

    <form id="package-form" action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data" class="relative">
        @csrf

        <!-- Upload Overlay -->
        <div x-show="uploading" x-transition class="absolute inset-0 bg-white/90 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-2xl" style="display: none;">
            <div class="w-64 bg-gray-100 rounded-full h-2 mb-6 overflow-hidden">
                <div class="bg-red-600 h-full transition-all duration-500" :style="`width: ${progress}%`"></div>
            </div>
            <p class="text-sm font-black text-gray-900 uppercase tracking-widest">Transmitting Data: <span x-text="progress + '%'"></span></p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content Column --}}
            <div class="lg:col-span-2 space-y-8">
                {{-- Basic Info Section --}}
                <div class="form-card p-6 sm:p-8">
                    <h3 class="section-label">Core Content</h3>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="input-label">Package Title</label>
                            <input type="text" name="title" value="{{ old('title') }}" required class="custom-input text-lg font-bold" placeholder="Enter high-impact title...">
                            @error('title') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="input-label">Executive Summary</label>
                            <textarea name="description" rows="4" required class="custom-input" placeholder="Primary tour description..."></textarea>
                            @error('description') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="input-label">Booking Policy</label>
                                <textarea name="policy" rows="3" class="custom-input text-xs"></textarea>
                            </div>
                            <div>
                                <label class="input-label">Requirements</label>
                                <textarea name="requirements" rows="3" class="custom-input text-xs"></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="input-label">Hotel Details</label>
                                <textarea name="hotel_details" rows="3" class="custom-input text-xs" placeholder="Accommodation details..."></textarea>
                            </div>
                            <div>
                                <label class="input-label">Additional Information</label>
                                <textarea name="additional_info" rows="3" class="custom-input text-xs" placeholder="Extra notes for travelers..."></textarea>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="input-label">Travel Tips</label>
                                <textarea name="travel_tips" rows="3" class="custom-input text-xs" placeholder="Packing tips, local customs..."></textarea>
                            </div>
                            <div>
                                <label class="input-label">Pickup Note</label>
                                <textarea name="pickup_note" rows="3" class="custom-input text-xs" placeholder="Airport pickup instructions..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Itinerary Builder --}}
                <div class="form-card p-6 sm:p-8" x-data="{ days: {{ json_encode(old('itinerary', [['day' => 1, 'title' => '', 'activities' => ['']]])) }} }">
                    <h3 class="section-label">Operational Itinerary</h3>
                    
                    <div class="space-y-4">
                        <template x-for="(day, dIndex) in days" :key="dIndex">
                            <div class="dynamic-row">
                                <div class="flex items-center justify-between mb-4 pb-2 border-b border-gray-100">
                                    <div class="flex items-center gap-2">
                                        <span class="w-6 h-6 rounded-full bg-red-600 text-white text-[10px] font-black flex items-center justify-center" x-text="dIndex + 1"></span>
                                        <span class="text-xs font-black text-gray-900 uppercase tracking-tight">Phase / Day</span>
                                    </div>
                                    <button type="button" @click="days.splice(dIndex, 1)" x-show="days.length > 1" class="text-gray-400 hover:text-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>

                                <div class="space-y-4">
                                    <input type="hidden" :name="'itinerary[' + dIndex + '][day]'" :value="dIndex + 1">
                                    <input type="text" :name="'itinerary[' + dIndex + '][title]'" x-model="day.title" placeholder="Day highlight title..." class="custom-input font-bold text-gray-800">

                                    <div class="space-y-2 pl-4 border-l-2 border-red-50">
                                        <label class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Scheduled Activities</label>
                                        <template x-for="(activity, aIndex) in day.activities" :key="aIndex">
                                            <div class="flex gap-2">
                                                <input type="text" :name="'itinerary[' + dIndex + '][activities][' + aIndex + ']'" x-model="day.activities[aIndex]" placeholder="Activity detail..." class="custom-input text-xs py-1.5" required>
                                                <button type="button" @click="day.activities.splice(aIndex, 1)" x-show="day.activities.length > 1" class="text-gray-300 hover:text-red-400 transition">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button type="button" @click="(day.activities = day.activities || []).push('')" class="text-[10px] font-black text-red-600 uppercase tracking-tighter hover:underline flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                            Add Entry
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>
                        
                        <button type="button" @click="days.push({ day: days.length + 1, title: '', activities: [''] })" class="w-full py-3 border-2 border-dashed border-gray-100 rounded-xl text-xs font-black text-gray-400 uppercase tracking-widest hover:border-red-200 hover:text-red-600 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Append New Day
                        </button>
                    </div>
                </div>

                {{-- Inclusions & Exclusions --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="form-card p-6" x-data="{ items: {{ json_encode(old('inclusions', [''])) }} }">
                        <h3 class="text-[10px] font-black text-emerald-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Inclusions
                        </h3>
                        <div class="space-y-2">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" name="inclusions[]" x-model="items[index]" class="custom-input text-xs" placeholder="Included feature...">
                                    <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" class="text-gray-300 hover:text-red-500 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </template>
                            <button type="button" @click="items.push('')" class="text-[10px] font-black text-emerald-600 uppercase hover:underline">+ Add Row</button>
                        </div>
                    </div>

                    <div class="form-card p-6" x-data="{ items: {{ json_encode(old('exclusions', [''])) }} }">
                        <h3 class="text-[10px] font-black text-red-600 uppercase tracking-widest mb-4 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span> Exclusions
                        </h3>
                        <div class="space-y-2">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex gap-2">
                                    <input type="text" name="exclusions[]" x-model="items[index]" class="custom-input text-xs" placeholder="Not included...">
                                    <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" class="text-gray-300 hover:text-red-500 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                                </div>
                            </template>
                            <button type="button" @click="items.push('')" class="text-[10px] font-black text-red-600 uppercase hover:underline">+ Add Row</button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Column --}}
            <div class="space-y-8">
                {{-- Pricing & Meta --}}
                <div class="form-card p-6">
                    <h3 class="section-label">Parameters</h3>
                    <div class="space-y-5">
                        <div>
                            <label class="input-label">Base Price (BDT)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold text-xs">৳</span>
                                <input type="number" name="price" value="{{ old('price') }}" required class="custom-input pl-8 font-black text-gray-900" placeholder="0.00">
                            </div>
                            @error('price') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="input-label">Timeline (Days)</label>
                            <input type="number" name="duration_days" value="{{ old('duration_days') }}" required class="custom-input font-bold" placeholder="Total duration">
                            @error('duration_days') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="input-label">Primary Geo-Location</label>
                            <input type="text" name="location" value="{{ old('location') }}" required class="custom-input" placeholder="e.g. Dubai, UAE">
                            @error('location') <p class="text-[10px] text-red-600 font-bold mt-1 uppercase">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="input-label">Expedition Start</label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}" class="custom-input">
                        </div>
                    </div>
                </div>

                {{-- Media Manager --}}
                <div class="form-card p-6">
                    <h3 class="section-label">Media Assets</h3>
                    <div class="space-y-6">
                        <div x-data="fileUploader">
                            <label class="input-label">Cover Thumbnail <span class="text-red-500">*</span></label>
                            <input type="file" name="thumbnail" @change="handleFileChange" required accept="image/*" class="w-full text-[10px] text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-red-50 file:text-red-700 hover:file:bg-red-100 transition">
                            <div x-show="fileName" class="mt-2 p-2 bg-emerald-50 rounded-lg border border-emerald-100 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                                <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-tighter truncate" x-text="fileName"></span>
                            </div>
                        </div>

                        <div x-data="fileUploader">
                            <label class="input-label">Gallery Collection</label>
                            <input type="file" name="images[]" @change="handleFileChange" multiple accept="image/*" class="w-full text-[10px] text-gray-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-gray-100 file:text-gray-600 hover:file:bg-gray-200 transition">
                            <div x-show="fileName" class="mt-2 p-2 bg-blue-50 rounded-lg border border-blue-100 flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                                <span class="text-[9px] font-bold text-blue-700 uppercase tracking-tighter truncate" x-text="fileName"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Key Attributes --}}
                <div class="form-card p-6" x-data="{ items: {{ json_encode(old('travel_data', [['label' => '', 'content' => '']])) }} }">
                    <h3 class="section-label">Key Specifications</h3>
                    <div class="space-y-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="p-3 bg-gray-50 rounded-xl border border-gray-100 relative group">
                                <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1" class="absolute -top-2 -right-2 w-5 h-5 bg-white border border-gray-200 rounded-full text-gray-400 hover:text-red-600 shadow-sm flex items-center justify-center transition">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                                <div class="space-y-2">
                                    <input type="text" :name="'travel_data[' + index + '][label]'" x-model="item.label" class="w-full bg-transparent border-none p-0 text-[10px] font-black text-gray-400 uppercase tracking-widest focus:ring-0" placeholder="ATTRIBUTE (e.g. VISA)">
                                    <input type="text" :name="'travel_data[' + index + '][content]'" x-model="item.content" class="w-full bg-transparent border-none p-0 text-xs font-bold text-gray-900 focus:ring-0" placeholder="Value detail...">
                                </div>
                            </div>
                        </template>
                        <button type="button" @click="items.push({ label: '', content: '' })" class="w-full py-2 border-2 border-dashed border-gray-100 rounded-xl text-[10px] font-black text-gray-400 uppercase tracking-widest hover:border-red-200 hover:text-red-600 transition">
                            + Add Specification
                        </button>
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
                        // Check if it's a redirect or JSON response
                        const response = JSON.parse(xhr.responseText);
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        } else {
                            window.location.href = "{{ route('admin.packages.index') }}";
                        }
                    } else {
                        this.uploading = false;
                        alert('Upload failed. Please check for validation errors.');
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
                    if (files.length === 1) {
                        this.fileName = files[0].name;
                        this.fileSize = (files[0].size / 1024).toFixed(1) + ' KB';
                    } else {
                        this.fileName = files.length + ' files selected';
                        this.fileSize = '';
                    }
                }
            }
        }
    }
</script>
@endpush

</x-admin-layout>
