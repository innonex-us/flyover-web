<x-admin-layout>

    {{-- Page header --}}
    <div class="mb-6 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <a href="{{ route('admin.visas.index') }}"
               class="inline-flex items-center gap-1.5 text-sm transition hover:opacity-70"
               style="color:#6B7280;">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Visa Services
            </a>
            <h1 class="mt-3 text-2xl font-bold" style="color:#0F1419;">
                Edit — {{ $visa->country }}
                <span class="text-base font-normal" style="color:#6B7280;">({{ $visa->type }})</span>
            </h1>
        </div>
        {{-- View on site link --}}
        @if($visa->slug)
        <a href="{{ url('/visas/' . $visa->slug) }}" target="_blank" rel="noopener"
           class="inline-flex items-center gap-1.5 rounded-lg border px-4 py-2 text-sm font-medium transition hover:opacity-80"
           style="border-color:#E6E8EC; color:#0F1419;">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            View Visa
        </a>
        @endif
    </div>

    <form action="{{ route('admin.visas.update', $visa) }}" method="POST" enctype="multipart/form-data"
          class="relative" x-data="formUploader" @submit.prevent="submitForm">
        @csrf
        @method('PUT')

        {{-- Upload progress overlay --}}
        <div x-show="uploading"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0 z-50 flex flex-col items-center justify-center rounded-xl backdrop-blur-sm"
             style="background:rgba(255,255,255,0.85); display:none;">
            <div class="mb-3 h-2 w-64 overflow-hidden rounded-full" style="background:#E6E8EC;">
                <div class="h-2 rounded-full transition-all duration-300" style="background:#C8102E;"
                     :style="`width:${progress}%`"></div>
            </div>
            <p class="font-semibold" style="color:#0F1419;">Uploading… <span x-text="progress + '%'"></span></p>
            <p class="mt-1 text-xs" style="color:#6B7280;">Please wait while we process your files.</p>
        </div>

        {{-- Two-column layout --}}
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12">

            {{-- LEFT — main content (8/12) --}}
            <div class="space-y-6 lg:col-span-8">

                {{-- Basic Information --}}
                <div class="rounded-xl p-6" style="background:#fff; border:1px solid #E6E8EC;">
                    <p class="mb-5 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Basic Information</p>

                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">

                        {{-- Country --}}
                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Country <span style="color:#C8102E;">*</span>
                            </label>
                            <input type="text" name="country" value="{{ old('country', $visa->country) }}" required
                                   class="w-full rounded-lg border px-3 py-2 text-sm transition focus:outline-none focus:ring-2"
                                   style="border-color:{{ $errors->has('country') ? '#C8102E' : '#E6E8EC' }}; color:#0F1419;">
                            @error('country')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Slug --}}
                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Slug
                            </label>
                            <input type="text" name="slug" value="{{ old('slug', $visa->slug) }}"
                                   placeholder="auto-generated if blank"
                                   class="w-full rounded-lg border px-3 py-2 text-sm transition focus:outline-none focus:ring-2"
                                   style="border-color:{{ $errors->has('slug') ? '#C8102E' : '#E6E8EC' }}; color:#0F1419;">
                            @error('slug')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Visa Type --}}
                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Visa Type <span style="color:#C8102E;">*</span>
                            </label>
                            <select name="type" required
                                    class="w-full rounded-lg border px-3 py-2 text-sm transition focus:outline-none focus:ring-2"
                                    style="border-color:{{ $errors->has('type') ? '#C8102E' : '#E6E8EC' }}; color:#0F1419;">
                                @foreach(['Tourist Visa','Business Visa','Student Visa','Work Visa','E-Visa'] as $t)
                                    <option value="{{ $t }}" {{ old('type', $visa->type) == $t ? 'selected' : '' }}>{{ $t }}</option>
                                @endforeach
                            </select>
                            @error('type')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Fee --}}
                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Fee (BDT) <span style="color:#C8102E;">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-3 flex items-center text-sm" style="color:#6B7280;">৳</span>
                                <input type="number" name="price" value="{{ old('price', $visa->price) }}" required min="0" step="0.01"
                                       class="w-full rounded-lg border py-2 pl-7 pr-3 text-sm transition focus:outline-none focus:ring-2"
                                       style="border-color:{{ $errors->has('price') ? '#C8102E' : '#E6E8EC' }}; color:#0F1419;">
                            </div>
                            @error('price')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Validity --}}
                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Validity
                            </label>
                            <input type="text" name="validity" value="{{ old('validity', $visa->validity) }}"
                                   placeholder="e.g. 3 Months"
                                   class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                   style="border-color:#E6E8EC; color:#0F1419;">
                            @error('validity')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Maximum Stay --}}
                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Maximum Stay
                            </label>
                            <input type="text" name="maximum_stay" value="{{ old('maximum_stay', $visa->maximum_stay) }}"
                                   placeholder="e.g. 30 Days"
                                   class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                   style="border-color:#E6E8EC; color:#0F1419;">
                            @error('maximum_stay')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Details --}}
                <div class="rounded-xl p-6" style="background:#fff; border:1px solid #E6E8EC;">
                    <p class="mb-5 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Details</p>

                    <div class="space-y-5">

                        {{-- Visa Summary --}}
                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Visa Summary <span style="color:#C8102E;">*</span>
                            </label>
                            <textarea name="description" rows="5" required
                                      class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                      style="border-color:{{ $errors->has('description') ? '#C8102E' : '#E6E8EC' }}; color:#0F1419;">{{ old('description', $visa->description) }}</textarea>
                            <p class="mt-1 text-xs" style="color:#6B7280;">One summary point per line. Line breaks are preserved on the public site.</p>
                            @error('description')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Thumbnail --}}
                        <div x-data="fileUploader">
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Thumbnail Image
                            </label>
                            @if($visa->thumbnail)
                                <div class="mb-3 flex items-center gap-4">
                                    <img src="{{ Storage::url($visa->thumbnail) }}"
                                         alt="Current thumbnail"
                                         class="h-20 w-32 rounded-lg object-cover"
                                         style="border:1px solid #E6E8EC;">
                                    <p class="text-xs" style="color:#6B7280;">Current thumbnail. Upload a new file to replace it.</p>
                                </div>
                            @endif
                            <input type="file" name="thumbnail" @change="handleFileChange" accept="image/*"
                                   class="w-full rounded-lg border px-3 py-2 text-sm file:mr-3 file:cursor-pointer file:rounded-md file:border-0 file:px-3 file:py-1 file:text-xs file:font-semibold file:text-white transition"
                                   style="border-color:#E6E8EC; file:background:#C8102E;">
                            @error('thumbnail')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                            <p x-show="fileName" class="mt-1.5 text-xs" style="color:#065F46; display:none;">
                                Selected: <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                            </p>
                        </div>

                        {{-- Requirements --}}
                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Requirements (plain text)
                            </label>
                            <textarea name="requirements" rows="4"
                                      class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                      style="border-color:#E6E8EC; color:#0F1419;">{{ old('requirements', $visa->requirements) }}</textarea>
                            @error('requirements')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Required Documents builder --}}
                <div class="rounded-xl p-6" style="background:#fff; border:1px solid #E6E8EC;"
                     x-data="{ tabs: 'required_documents' }">

                    <div class="mb-5 flex gap-1" style="border-bottom:1px solid #E6E8EC;">
                        <button type="button"
                                @click="tabs = 'required_documents'"
                                class="pb-3 pr-4 text-xs uppercase tracking-wider transition"
                                :style="tabs === 'required_documents' ? 'border-bottom:2px solid #C8102E; color:#C8102E; font-weight:600;' : 'color:#6B7280;'">
                            Required Documents
                        </button>
                        <button type="button"
                                @click="tabs = 'fees'"
                                class="pb-3 pr-4 text-xs uppercase tracking-wider transition"
                                :style="tabs === 'fees' ? 'border-bottom:2px solid #C8102E; color:#C8102E; font-weight:600;' : 'color:#6B7280;'">
                            Fees &amp; Terms
                        </button>
                    </div>

                    {{-- Required Documents tab --}}
                    @php
                        $existingDocs = $visa->required_documents ?? [];
                        $formattedDocs = [];
                        if (!empty($existingDocs)) {
                            $reindexed = array_values($existingDocs);
                            if (isset($reindexed[0]['section'])) {
                                foreach ($reindexed as &$sec) {
                                    $sec['documents'] = isset($sec['documents']) && is_array($sec['documents'])
                                        ? array_values($sec['documents'])
                                        : [];
                                }
                                unset($sec);
                                $formattedDocs = $reindexed;
                            } else {
                                $formattedDocs = [['section' => 'General Requirements', 'documents' => array_values($existingDocs)]];
                            }
                        } else {
                            $formattedDocs = [['section' => 'General Requirements', 'documents' => ['']]];
                        }
                    @endphp

                    <div x-show="tabs === 'required_documents'"
                         x-data="{ sections: {{ json_encode($formattedDocs) }} }">

                        <template x-for="(section, sIndex) in sections" :key="sIndex">
                            <div class="mb-4 rounded-lg p-4" style="border:1px solid #E6E8EC; background:#F4F5F7;">
                                <div class="mb-3 flex items-center gap-3">
                                    <input type="text"
                                           :name="'required_documents[' + sIndex + '][section]'"
                                           x-model="sections[sIndex].section"
                                           placeholder="Section title (e.g. Job Holders)"
                                           required
                                           class="flex-1 rounded-lg border px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2"
                                           style="border-color:#E6E8EC; color:#0F1419; background:#fff;">
                                    <button type="button" @click="sections.splice(sIndex, 1)"
                                            class="flex-shrink-0 rounded-lg border p-2 transition hover:opacity-70"
                                            style="border-color:#FEE2E2; color:#991B1B; background:#FEF2F2;"
                                            title="Remove section">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>

                                <div class="space-y-2 border-l-2 pl-4" style="border-color:#C8102E;">
                                    <template x-for="(doc, dIndex) in sections[sIndex].documents" :key="dIndex">
                                        <div class="flex items-center gap-2">
                                            <input type="text"
                                                   :name="'required_documents[' + sIndex + '][documents][]'"
                                                   x-model="sections[sIndex].documents[dIndex]"
                                                   placeholder="e.g. Valid passport with 6+ months validity"
                                                   required
                                                   class="flex-1 rounded-lg border px-3 py-1.5 text-sm focus:outline-none focus:ring-2"
                                                   style="border-color:#E6E8EC; color:#0F1419; background:#fff;">
                                            <button type="button"
                                                    @click="sections[sIndex].documents.splice(dIndex, 1)"
                                                    x-show="sections[sIndex].documents.length > 1"
                                                    class="rounded p-1 transition hover:opacity-70"
                                                    style="color:#6B7280;">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                    <button type="button"
                                            @click="sections[sIndex].documents.push('')"
                                            class="mt-1 inline-flex items-center gap-1.5 rounded-lg border px-3 py-1.5 text-xs font-semibold transition hover:opacity-80"
                                            style="border-color:#E6E8EC; color:#C8102E; background:#fff;">
                                        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                        Add Document
                                    </button>
                                </div>
                            </div>
                        </template>

                        <button type="button"
                                @click="sections.push({ section: '', documents: [''] })"
                                class="inline-flex items-center gap-2 rounded-lg px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90"
                                style="background:#C8102E;">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            Add Section
                        </button>
                    </div>

                    {{-- Fees & Terms tab --}}
                    <div x-show="tabs === 'fees'" style="display:none;" class="space-y-5">
                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Terms &amp; Conditions
                            </label>
                            <textarea name="terms" rows="4"
                                      class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                      style="border-color:#E6E8EC; color:#0F1419;">{{ old('terms', $visa->terms) }}</textarea>
                            @error('terms')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Important Notes
                            </label>
                            <textarea name="important_notes" rows="4"
                                      class="w-full rounded-lg border px-3 py-2 text-sm focus:outline-none focus:ring-2"
                                      style="border-color:#E6E8EC; color:#0F1419;">{{ old('important_notes', $visa->important_notes) }}</textarea>
                            @error('important_notes')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </div>

            </div>

            {{-- RIGHT — publish sidebar (4/12) --}}
            <div class="lg:col-span-4">
                <div class="sticky top-6 rounded-xl p-6" style="background:#fff; border:1px solid #E6E8EC;">
                    <p class="mb-5 text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">Publish</p>

                    {{-- Metadata --}}
                    <div class="mb-5 space-y-2 text-xs" style="color:#6B7280;">
                        <div class="flex justify-between">
                            <span>Created</span>
                            <span style="color:#0F1419;">{{ $visa->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Last updated</span>
                            <span style="color:#0F1419;">{{ $visa->updated_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    <div style="border-top:1px solid #E6E8EC;" class="pt-5">

                        {{-- is_active toggle --}}
                        <div class="mb-6" x-data="{ active: {{ old('is_active', $visa->is_active) ? 'true' : 'false' }} }">
                            <label class="mb-1.5 block text-[10px] font-semibold uppercase tracking-widest" style="color:#6B7280;">
                                Visibility
                            </label>
                            <button type="button"
                                    @click="active = !active"
                                    class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 focus:outline-none"
                                    :style="active ? 'background:#C8102E;' : 'background:#E6E8EC;'"
                                    role="switch"
                                    :aria-checked="active.toString()">
                                <span class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200"
                                      :class="active ? 'translate-x-5' : 'translate-x-0'"></span>
                            </button>
                            <input type="hidden" name="is_active" :value="active ? '1' : '0'">
                            <p class="mt-2 text-xs">
                                <span x-show="active" style="color:#065F46;">Active — visible on the website</span>
                                <span x-show="!active" style="color:#6B7280;">Inactive — hidden from public</span>
                            </p>
                            @error('is_active')
                                <p class="mt-1 text-xs" style="color:#C8102E;">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-3">
                            <button type="submit"
                                    class="w-full rounded-lg py-2.5 text-sm font-semibold text-white transition hover:opacity-90 focus:outline-none focus:ring-2"
                                    style="background:#C8102E;">
                                Update Visa Service
                            </button>
                            @if($visa->slug)
                            <a href="{{ url('/visas/' . $visa->slug) }}" target="_blank" rel="noopener"
                               class="block w-full rounded-lg border py-2.5 text-center text-sm font-medium transition hover:opacity-70"
                               style="border-color:#E6E8EC; color:#6B7280;">
                                View on Site
                            </a>
                            @endif
                            <a href="{{ route('admin.visas.index') }}"
                               class="block w-full rounded-lg border py-2.5 text-center text-sm font-medium transition hover:opacity-70"
                               style="border-color:#E6E8EC; color:#6B7280;">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

</x-admin-layout>
