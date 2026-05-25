@props(['serviceType', 'serviceId', 'amount', 'onApply' => null])

<div x-data="{
    code: '',
    loading: false,
    applied: false,
    error: '',
    success: '',
    discount: 0,
    finalAmount: {{ $amount }},
    originalAmount: {{ $amount }},
    
    async validateCoupon() {
        if (!this.code.trim()) return;
        
        this.loading = true;
        this.error = '';
        this.success = '';
        
        try {
            const response = await fetch('{{ route('api.coupons.validate') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                },
                body: JSON.stringify({
                    code: this.code,
                    service_type: '{{ $serviceType }}',
                    service_id: {{ $serviceId ?? 'null' }},
                    amount: this.originalAmount
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                this.applied = true;
                this.success = data.message;
                this.discount = data.discount;
                this.finalAmount = data.final_amount;
                @if($onApply) { {!! $onApply !!} } @endif
            } else {
                this.error = data.message;
                this.applied = false;
                this.discount = 0;
                this.finalAmount = this.originalAmount;
            }
        } catch (e) {
            this.error = 'Failed to validate coupon. Please try again.';
        } finally {
            this.loading = false;
        }
    },
    
    async removeCoupon() {
        this.loading = true;
        
        try {
            await fetch('{{ route('api.coupons.remove') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                }
            });
            
            this.applied = false;
            this.code = '';
            this.success = '';
            this.discount = 0;
            this.finalAmount = this.originalAmount;
        } catch (e) {
            this.error = 'Failed to remove coupon.';
        } finally {
            this.loading = false;
        }
    }
}" class="space-y-3">
    
    {{-- Input Field --}}
    <div x-show="!applied" class="flex gap-2">
        <div class="relative flex-1">
            <input 
                type="text" 
                x-model="code"
                @keydown.enter.prevent="validateCoupon()"
                placeholder="Enter coupon code"
                class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 uppercase font-mono"
                :disabled="loading"
            >
            <div x-show="loading" class="absolute right-3 top-1/2 -translate-y-1/2">
                <svg class="w-5 h-5 text-gray-400 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>
        </div>
        <button 
            @click="validateCoupon()"
            :disabled="!code.trim() || loading"
            class="px-4 py-2.5 bg-gray-900 text-white font-semibold rounded-lg hover:bg-black disabled:opacity-50 disabled:cursor-not-allowed transition"
        >
            Apply
        </button>
    </div>
    
    {{-- Applied State --}}
    <div x-show="applied" class="flex items-center justify-between bg-green-50 border border-green-200 rounded-lg p-3">
        <div class="flex items-center gap-2">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            <span class="font-semibold text-green-800" x-text="code.toUpperCase()"></span>
            <span class="text-green-600 text-sm" x-text="`(-৳${discount.toLocaleString()})`"></span>
        </div>
        <button 
            @click="removeCoupon()"
            :disabled="loading"
            class="text-red-500 hover:text-red-700 font-medium text-sm"
        >
            Remove
        </button>
    </div>
    
    {{-- Messages --}}
    <p x-show="error" x-text="error" class="text-sm text-red-600" x-cloak></p>
    
    {{-- Price Breakdown --}}
    <div class="pt-3 border-t border-gray-100 space-y-1">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Original Price</span>
            <span class="font-medium">৳<span x-text="originalAmount.toLocaleString()"></span></span>
        </div>
        <div x-show="discount > 0" class="flex justify-between text-sm" x-cloak>
            <span class="text-green-600">Discount</span>
            <span class="font-medium text-green-600">-৳<span x-text="discount.toLocaleString()"></span></span>
        </div>
        <div class="flex justify-between text-lg font-bold pt-1">
            <span class="text-gray-900">Total</span>
            <span class="text-red-600">৳<span x-text="finalAmount.toLocaleString()"></span></span>
        </div>
    </div>
    
    {{-- Hidden inputs for form submission --}}
    <input type="hidden" name="coupon_code" x-model="code" :disabled="!applied">
    <input type="hidden" name="discount_amount" x-model="discount" :disabled="!applied">
    <input type="hidden" name="final_amount" x-model="finalAmount">
</div>

@push('scripts')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
