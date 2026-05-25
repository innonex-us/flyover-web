<x-admin-layout pageTitle="Create Coupon">
    <div class="max-w-3xl mx-auto">
        {{-- Header --}}
        <div class="flex items-center gap-4 mb-6">
            <a href="{{ route('admin.coupons.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Create Coupon</h1>
        </div>

        {{-- Form --}}
        <form action="{{ route('admin.coupons.store') }}" method="POST" class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 space-y-6">
            @csrf

            {{-- Code & Description --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Coupon Code <span class="text-red-500">*</span></label>
                    <input type="text" id="code" name="code" value="{{ old('code') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 uppercase font-mono"
                           placeholder="SUMMER2024">
                    @error('code')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Letters, numbers, hyphens, underscores only. Auto-converted to uppercase.</p>
                </div>
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <input type="text" id="description" name="description" value="{{ old('description') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Summer sale - 20% off">
                    @error('description')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Discount Type & Value --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Discount Type <span class="text-red-500">*</span></label>
                    <select id="type" name="type" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                        <option value="percentage" {{ old('type') === 'percentage' ? 'selected' : '' }}>Percentage (%)</option>
                        <option value="fixed" {{ old('type') === 'fixed' ? 'selected' : '' }}>Fixed Amount (৳)</option>
                    </select>
                    @error('type')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Discount Value <span class="text-red-500">*</span></label>
                    <input type="number" id="value" name="value" value="{{ old('value') }}" required min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="20">
                    @error('value')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="max_discount_amount" class="block text-sm font-medium text-gray-700 mb-1">Max Discount (৳)</label>
                    <input type="number" id="max_discount_amount" name="max_discount_amount" value="{{ old('max_discount_amount') }}" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Unlimited">
                    @error('max_discount_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">For percentage discounts only</p>
                </div>
            </div>

            {{-- Applies To --}}
            <div>
                <label for="applies_to" class="block text-sm font-medium text-gray-700 mb-1">Applies To <span class="text-red-500">*</span></label>
                <select id="applies_to" name="applies_to" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        x-data="{}"
                        x-on:change="document.getElementById('specific-items').style.display = ['tours', 'hotels', 'transfers', 'visas'].includes($event.target.value) ? 'block' : 'none'">
                    @foreach($serviceTypes as $key => $label)
                    <option value="{{ $key }}" {{ old('applies_to', 'all') === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('applies_to')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Specific Items (shown when specific service selected) --}}
            <div id="specific-items" style="display: {{ in_array(old('applies_to'), ['tours', 'hotels', 'transfers', 'visas']) ? 'block' : 'none' }};">
                <label class="block text-sm font-medium text-gray-700 mb-2">Select Specific Items (Optional)</label>
                <p class="text-xs text-gray-500 mb-2">Leave empty to apply to all items in this category</p>
                
                {{-- Tours --}}
                <div id="tours-select" style="display: {{ old('applies_to') === 'tours' ? 'block' : 'none' }};">
                    <select name="applicable_ids[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg h-32">
                        @foreach($tours as $tour)
                        <option value="{{ $tour->id }}" {{ in_array($tour->id, old('applicable_ids', [])) ? 'selected' : '' }}>{{ $tour->title }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Hotels --}}
                <div id="hotels-select" style="display: {{ old('applies_to') === 'hotels' ? 'block' : 'none' }};">
                    <select name="applicable_ids[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg h-32">
                        @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ in_array($hotel->id, old('applicable_ids', [])) ? 'selected' : '' }}>{{ $hotel->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Transfers --}}
                <div id="transfers-select" style="display: {{ old('applies_to') === 'transfers' ? 'block' : 'none' }};">
                    <select name="applicable_ids[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg h-32">
                        @foreach($transfers as $transfer)
                        <option value="{{ $transfer->id }}" {{ in_array($transfer->id, old('applicable_ids', [])) ? 'selected' : '' }}>{{ $transfer->pickup_location }} → {{ $transfer->drop_location }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Visas --}}
                <div id="visas-select" style="display: {{ old('applies_to') === 'visas' ? 'block' : 'none' }};">
                    <select name="applicable_ids[]" multiple class="w-full px-3 py-2 border border-gray-300 rounded-lg h-32">
                        @foreach($visas as $visa)
                        <option value="{{ $visa->id }}" {{ in_array($visa->id, old('applicable_ids', [])) ? 'selected' : '' }}>{{ $visa->country }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Limits --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="min_order_amount" class="block text-sm font-medium text-gray-700 mb-1">Min Order Amount (৳)</label>
                    <input type="number" id="min_order_amount" name="min_order_amount" value="{{ old('min_order_amount') }}" min="0" step="0.01"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="0">
                    @error('min_order_amount')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="usage_limit" class="block text-sm font-medium text-gray-700 mb-1">Total Usage Limit</label>
                    <input type="number" id="usage_limit" name="usage_limit" value="{{ old('usage_limit') }}" min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Unlimited">
                    @error('usage_limit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="usage_limit_per_user" class="block text-sm font-medium text-gray-700 mb-1">Usage Per User</label>
                    <input type="number" id="usage_limit_per_user" name="usage_limit_per_user" value="{{ old('usage_limit_per_user') }}" min="1"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                           placeholder="Unlimited">
                    @error('usage_limit_per_user')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Validity Period --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="starts_at" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                    <input type="datetime-local" id="starts_at" name="starts_at" value="{{ old('starts_at') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    @error('starts_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label for="expires_at" class="block text-sm font-medium text-gray-700 mb-1">Expiry Date</label>
                    <input type="datetime-local" id="expires_at" name="expires_at" value="{{ old('expires_at') }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500">
                    @error('expires_at')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Status --}}
            <div class="flex items-center gap-3">
                <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                       class="w-4 h-4 text-red-600 border-gray-300 rounded focus:ring-red-500">
                <label for="is_active" class="text-sm font-medium text-gray-700">Active</label>
            </div>

            {{-- Submit --}}
            <div class="flex items-center gap-3 pt-4 border-t border-gray-200">
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-6 py-2.5 rounded-lg transition">
                    Create Coupon
                </button>
                <a href="{{ route('admin.coupons.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        // Show/hide specific item selects based on service type
        document.getElementById('applies_to').addEventListener('change', function() {
            const value = this.value;
            const specificItems = document.getElementById('specific-items');
            
            // Hide all selects
            document.querySelectorAll('[id$="-select"]').forEach(el => el.style.display = 'none');
            
            if (['tours', 'hotels', 'transfers', 'visas'].includes(value)) {
                specificItems.style.display = 'block';
                document.getElementById(value + '-select').style.display = 'block';
            } else {
                specificItems.style.display = 'none';
            }
        });
    </script>
</x-admin-layout>
