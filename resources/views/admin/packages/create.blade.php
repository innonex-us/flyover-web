<x-admin-layout>
    {{-- Page Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.packages.index') }}"
           class="inline-flex items-center gap-1.5 text-sm font-medium mb-4 transition hover:opacity-70"
           style="color:#6B7280;">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Packages
        </a>
        <h2 class="text-2xl font-bold" style="color:#0F1419;">Create New Package</h2>
    </div>

    <form action="{{ route('admin.packages.store') }}" method="POST" enctype="multipart/form-data"
          x-data="formUploader" @submit.prevent="submitForm">
        @csrf

        <div class="flex flex-col lg:flex-row gap-6 items-start relative">

            {{-- Upload Overlay (covers full form area) --}}
            <div x-show="uploading"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex flex-col items-center justify-center"
                 style="background:rgba(255,255,255,0.85);backdrop-filter:blur(4px);display:none;">
                <div class="w-64 rounded-full h-3 mb-4 overflow-hidden" style="background:#E6E8EC;">
                    <div class="h-3 rounded-full transition-all duration-300" style="background:#C8102E;" :style="`width: ${progress}%`"></div>
                </div>
                <div class="font-bold text-lg" style="color:#0F1419;">Uploading… <span x-text="progress + '%'"></span></div>
                <div class="text-sm mt-1" style="color:#6B7280;">Please wait while we process your files.</div>
            </div>

            {{-- LEFT: Main Content (8/12) --}}
            <div class="flex-1 min-w-0 space-y-6">

                {{-- Basic Information --}}
                <div class="rounded-xl border p-6 space-y-5" style="background:#fff;border-color:#E6E8EC;">
                    <h3 class="text-[10px] font-bold tracking-widest uppercase pb-3" style="color:#6B7280;border-bottom:1px solid #E6E8EC;">Basic Information</h3>

                    {{-- Title --}}
                    <div>
                        <label class="block text-[10px] font-bold tracking-widest uppercase mb-1.5" style="color:#6B7280;">Package Title</label>
                        <input type="text" name="title" value="{{ old('title') }}" required
                               class="w-full px-3 py-2.5 rounded-lg border text-sm transition focus:outline-none focus:border-[#C8102E]"
                               style="border-color:#E6E8EC;color:#0F1419;"
                               placeholder="e.g. Magnificent Cox's Bazar Tour">
                        @error('title') <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Price + Duration --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-bold tracking-widest uppercase mb-1.5" style="color:#6B7280;">Price (BDT)</label>
                            <input type="number" name="price" value="{{ old('price') }}" required
                                   class="w-full px-3 py-2.5 rounded-lg border text-sm transition focus:outline-none focus:border-[#C8102E]"
                                   style="border-color:#E6E8EC;color:#0F1419;"
                                   placeholder="0">
                            @error('price') <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-widest uppercase mb-1.5" style="color:#6B7280;">Duration (Days)</label>
                            <input type="number" name="duration_days" value="{{ old('duration_days') }}" required
                                   class="w-full px-3 py-2.5 rounded-lg border text-sm transition focus:outline-none focus:border-[#C8102E]"
                                   style="border-color:#E6E8EC;color:#0F1419;">
                            @error('duration_days') <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- Location + Start Date --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label class="block text-[10px] font-bold tracking-widest uppercase mb-1.5" style="color:#6B7280;">Location</label>
                            <input type="text" name="location" value="{{ old('location') }}" required
                                   class="w-full px-3 py-2.5 rounded-lg border text-sm transition focus:outline-none focus:border-[#C8102E]"
                                   style="border-color:#E6E8EC;color:#0F1419;"
                                   placeholder="e.g. Cox's Bazar">
                            @error('location') <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold tracking-widest uppercase mb-1.5" style="color:#6B7280;">Start Date <span class="normal-case font-normal">(Optional)</span></label>
                            <input type="date" name="start_date" value="{{ old('start_date') }}"
                                   class="w-full px-3 py-2.5 rounded-lg border text-sm transition focus:outline-none focus:border-[#C8102E]"
                                   style="border-color:#E6E8EC;color:#0F1419;">
                            @error('start_date') <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Details / Text Areas --}}
                <div class="rounded-xl border p-6 space-y-5" style="background:#fff;border-color:#E6E8EC;">
                    <h3 class="text-[10px] font-bold tracking-widest uppercase pb-3" style="color:#6B7280;border-bottom:1px solid #E6E8EC;">Details</h3>

                    @foreach([
                        ['name' => 'description',       'label' => 'Description',          'required' => true],
                        ['name' => 'policy',            'label' => 'Policy',               'required' => false],
                        ['name' => 'requirements',      'label' => 'Requirements',         'required' => false],
                        ['name' => 'hotel_details',     'label' => 'Hotel Details',        'required' => false],
                        ['name' => 'additional_info',   'label' => 'Additional Information','required' => false],
                        ['name' => 'travel_tips',       'label' => 'Travel Tips',          'required' => false],
                        ['name' => 'pickup_note',       'label' => 'Pickup Note',          'required' => false],
                    ] as $field)
                    <div>
                        <label class="block text-[10px] font-bold tracking-widest uppercase mb-1.5" style="color:#6B7280;">{{ $field['label'] }}</label>
                        <textarea name="{{ $field['name'] }}" rows="{{ $field['name'] === 'description' ? 6 : 4 }}"
                                  {{ $field['required'] ? 'required' : '' }}
                                  class="w-full px-3 py-2.5 rounded-lg border text-sm transition focus:outline-none focus:border-[#C8102E] min-h-32"
                                  style="border-color:#E6E8EC;color:#0F1419;">{{ old($field['name']) }}</textarea>
                        @error($field['name']) <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p> @enderror
                    </div>
                    @endforeach

                    {{-- Key Information (dynamic) --}}
                    <div x-data="{ items: {{ json_encode(old('travel_data', [['label' => '', 'content' => '']])) }} }">
                        <label class="block text-[10px] font-bold tracking-widest uppercase mb-1.5" style="color:#6B7280;">Key Information <span class="normal-case font-normal">(Optional)</span></label>
                        <p class="text-xs mb-3" style="color:#6B7280;">Add key–value info displayed on the tour page. E.g. <strong>Anytime</strong>, <strong>Flexible</strong>, <strong>Fix Date</strong>, Group size, Flight, Visa, etc.</p>
                        <div class="space-y-3">
                            <template x-for="(item, index) in items" :key="index">
                                <div class="p-4 rounded-xl border" style="background:#F9FAFB;border-color:#E6E8EC;">
                                    <div class="flex justify-between items-center mb-3">
                                        <span class="text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Item <span x-text="index + 1"></span></span>
                                        <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1"
                                                class="p-1 rounded-lg transition hover:bg-red-50" style="color:#C8102E;">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    <div class="space-y-2">
                                        <input type="text" :name="'travel_data[' + index + '][label]'" x-model="item.label"
                                               class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none focus:border-[#C8102E]"
                                               style="border-color:#E6E8EC;color:#0F1419;"
                                               placeholder="e.g. Anytime, Flexible, Fix Date, Group size">
                                        <textarea :name="'travel_data[' + index + '][content]'" x-model="item.content" rows="2"
                                                  class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none focus:border-[#C8102E]"
                                                  style="border-color:#E6E8EC;color:#0F1419;"
                                                  placeholder="Value or short description…"></textarea>
                                    </div>
                                </div>
                            </template>
                            <button type="button" @click="items.push({ label: '', content: '' })"
                                    class="w-full py-2 rounded-xl border-2 border-dashed text-sm font-semibold transition hover:border-[#C8102E] hover:text-[#C8102E] flex items-center justify-center"
                                    style="border-color:#E6E8EC;color:#6B7280;">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                Add Key Information
                            </button>
                        </div>
                    </div>

                    {{-- Itinerary Builder --}}
                    <div x-data="{ days: {{ json_encode(old('itinerary', [['day' => 1, 'title' => '', 'activities' => ['']]])) }} }">
                        <label class="block text-[10px] font-bold tracking-widest uppercase mb-3" style="color:#6B7280;">Itinerary</label>
                        <div class="space-y-4">
                            <template x-for="(day, dIndex) in days" :key="dIndex">
                                <div class="p-5 rounded-xl border" style="background:#F9FAFB;border-color:#E6E8EC;">
                                    <div class="flex justify-between items-center mb-4">
                                        <h4 class="font-bold text-sm flex items-center gap-2" style="color:#0F1419;">
                                            <span class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                                  style="background:#18130E;" x-text="dIndex + 1"></span>
                                            Day <span x-text="dIndex + 1" class="ml-0.5"></span>
                                        </h4>
                                        <button type="button" @click="days.splice(dIndex, 1)" x-show="days.length > 1"
                                                class="p-1 rounded-lg transition hover:bg-red-50" style="color:#C8102E;">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                    <input type="hidden" :name="'itinerary[' + dIndex + '][day]'" :value="dIndex + 1">
                                    <div class="mb-3">
                                        <label class="block text-[10px] font-bold tracking-widest uppercase mb-1" style="color:#6B7280;">Day Title</label>
                                        <input type="text" :name="'itinerary[' + dIndex + '][title]'" x-model="day.title"
                                               placeholder="e.g. Arrival and City Tour"
                                               class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none focus:border-[#C8102E]"
                                               style="border-color:#E6E8EC;color:#0F1419;">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="block text-[10px] font-bold tracking-widest uppercase" style="color:#6B7280;">Activities</label>
                                        <template x-for="(activity, aIndex) in day.activities" :key="aIndex">
                                            <div class="flex gap-2">
                                                <input type="text"
                                                       :name="'itinerary[' + dIndex + '][activities][' + aIndex + ']'"
                                                       x-model="day.activities[aIndex]"
                                                       placeholder="Describe activity…"
                                                       class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none focus:border-[#C8102E]"
                                                       style="border-color:#E6E8EC;color:#0F1419;" required>
                                                <button type="button" @click="day.activities.splice(aIndex, 1)"
                                                        x-show="day.activities.length > 1"
                                                        class="p-1 rounded-lg transition hover:text-red-500" style="color:#6B7280;">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                </button>
                                            </div>
                                        </template>
                                        <button type="button" @click="(day.activities = day.activities || []).push('')"
                                                class="text-xs font-bold flex items-center gap-1 transition hover:opacity-70" style="color:#C8102E;">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                                            Add Activity
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <button type="button" @click="days.push({ day: days.length + 1, title: '', activities: [''] })"
                                class="mt-4 w-full py-3 rounded-xl border-2 border-dashed text-sm font-semibold flex items-center justify-center gap-2 transition hover:border-[#18130E] hover:text-[#18130E]"
                                style="border-color:#E6E8EC;color:#6B7280;background:#fff;">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                            Add New Day
                        </button>
                    </div>
                </div>

                {{-- Inclusions / Exclusions --}}
                <div class="rounded-xl border p-6" style="background:#fff;border-color:#E6E8EC;">
                    <h3 class="text-[10px] font-bold tracking-widest uppercase pb-3 mb-5" style="color:#6B7280;border-bottom:1px solid #E6E8EC;">Inclusions &amp; Exclusions</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Inclusions --}}
                        <div x-data="{ items: {{ json_encode(old('inclusions', [''])) }} }">
                            <h4 class="text-sm font-bold mb-3" style="color:#065F46;">Inclusions</h4>
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" name="inclusions[]" x-model="items[index]"
                                           class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none focus:border-[#C8102E]"
                                           style="border-color:#E6E8EC;color:#0F1419;"
                                           placeholder="Add inclusion">
                                    <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1"
                                            class="transition hover:text-red-500" style="color:#6B7280;">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="items.push('')"
                                    class="text-sm font-semibold transition hover:opacity-70" style="color:#065F46;">+ Add Inclusion</button>
                        </div>

                        {{-- Exclusions --}}
                        <div x-data="{ items: {{ json_encode(old('exclusions', [''])) }} }">
                            <h4 class="text-sm font-bold mb-3" style="color:#C8102E;">Exclusions</h4>
                            <template x-for="(item, index) in items" :key="index">
                                <div class="flex gap-2 mb-2">
                                    <input type="text" name="exclusions[]" x-model="items[index]"
                                           class="w-full px-3 py-2 rounded-lg border text-sm focus:outline-none focus:border-[#C8102E]"
                                           style="border-color:#E6E8EC;color:#0F1419;"
                                           placeholder="Add exclusion">
                                    <button type="button" @click="items.splice(index, 1)" x-show="items.length > 1"
                                            class="transition hover:text-red-500" style="color:#6B7280;">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            </template>
                            <button type="button" @click="items.push('')"
                                    class="text-sm font-semibold transition hover:opacity-70" style="color:#C8102E;">+ Add Exclusion</button>
                        </div>

                    </div>
                </div>

                {{-- Media --}}
                <div class="rounded-xl border p-6 space-y-5" style="background:#fff;border-color:#E6E8EC;">
                    <h3 class="text-[10px] font-bold tracking-widest uppercase pb-3" style="color:#6B7280;border-bottom:1px solid #E6E8EC;">Media</h3>

                    {{-- Thumbnail --}}
                    <div x-data="fileUploader">
                        <label class="block text-[10px] font-bold tracking-widest uppercase mb-1.5" style="color:#6B7280;">Thumbnail Image <span class="normal-case font-normal">(Required)</span></label>
                        <input type="file" name="thumbnail" @change="handleFileChange" required accept="image/*"
                               class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:cursor-pointer"
                               style="color:#6B7280;"
                               x-bind:class="'file:bg-red-50 file:text-[#C8102E] hover:file:bg-red-100'">
                        <div x-show="fileName" class="mt-1.5 text-xs font-medium" style="color:#065F46;display:none;">
                            Selected: <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                        </div>
                        @error('thumbnail') <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p> @enderror
                    </div>

                    {{-- Gallery --}}
                    <div x-data="fileUploader">
                        <label class="block text-[10px] font-bold tracking-widest uppercase mb-1.5" style="color:#6B7280;">Gallery Images <span class="normal-case font-normal">(Optional, multiple)</span></label>
                        <input type="file" name="images[]" @change="handleFileChange" multiple accept="image/*"
                               class="w-full text-sm file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:cursor-pointer"
                               style="color:#6B7280;"
                               x-bind:class="'file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200'">
                        <div x-show="fileName" class="mt-1.5 text-xs font-medium" style="color:#065F46;display:none;">
                            Selected: <span x-text="fileName"></span> (<span x-text="fileSize"></span>)
                        </div>
                        @error('images') <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p> @enderror
                        @error('images.*') <p class="text-xs mt-1" style="color:#C8102E;">{{ $message }}</p> @enderror
                    </div>
                </div>

            </div>{{-- /LEFT --}}

            {{-- RIGHT: Publish Card (4/12) --}}
            <div class="w-full lg:w-72 flex-shrink-0 space-y-4">
                <div class="rounded-xl border p-5 sticky top-6" style="background:#fff;border-color:#E6E8EC;">
                    <h3 class="text-[10px] font-bold tracking-widest uppercase mb-4" style="color:#6B7280;">Publish</h3>

                    {{-- Visibility toggle --}}
                    <label class="flex items-center gap-3 cursor-pointer mb-5 p-3 rounded-lg" style="background:#F9FAFB;">
                        <input type="checkbox" name="is_active" value="1" checked
                               class="w-4 h-4 rounded border text-[#C8102E] focus:ring-[#C8102E] focus:ring-2 focus:ring-offset-1"
                               style="border-color:#E6E8EC;">
                        <div>
                            <div class="text-sm font-semibold" style="color:#0F1419;">Active</div>
                            <div class="text-xs" style="color:#6B7280;">Visible to users on site</div>
                        </div>
                    </label>

                    <button type="submit"
                            class="w-full py-2.5 rounded-xl text-sm font-bold transition hover:opacity-90"
                            style="background:#C8102E;color:#fff;">
                        Create Package
                    </button>

                    <a href="{{ route('admin.packages.index') }}"
                       class="mt-2 w-full py-2 rounded-xl text-sm font-semibold text-center block transition hover:opacity-70"
                       style="color:#6B7280;">
                        Cancel
                    </a>
                </div>
            </div>

        </div>{{-- /flex --}}
    </form>
</x-admin-layout>
