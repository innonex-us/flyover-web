<x-app-layout>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden print:shadow-none print:border print:border-gray-200">
                
                <!-- Header -->
                <div class="bg-red-600 px-8 py-10 text-center text-white print:bg-white print:text-black print:border-b print:border-gray-300">
                    <div class="mb-4 flex justify-center print:hidden">
                        <div class="h-16 w-16 bg-white rounded-full flex items-center justify-center text-red-600">
                             <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                    </div>
                    <h1 class="text-3xl font-bold mb-2">Booking Confirmed!</h1>
                    <p class="text-red-100 print:hidden">Thank you for your booking. We have received your request.</p>
                    <p class="hidden print:block text-gray-500">Receipt / Invoice</p>
                </div>

                <!-- Content -->
                <div class="px-8 py-8">
                    
                    <div class="flex justify-between items-center mb-8 border-b border-gray-100 pb-8">
                        <div>
                            <p class="text-sm text-gray-500 uppercase tracking-wide">Booking Reference</p>
                            <p class="text-2xl font-bold text-gray-900">#{{ $booking->id }}</p>
                        </div>
                         <div class="text-right">
                            <p class="text-sm text-gray-500 uppercase tracking-wide">Date</p>
                            <p class="text-lg font-bold text-gray-900">{{ $booking->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                        <div>
                            <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wide mb-3">Customer Details</h3>
                            <div class="text-gray-900 font-medium">
                                <p class="mb-1">{{ $booking->user ? $booking->user->name : $booking->guest_name }}</p>
                                <p class="mb-1 text-gray-600 font-normal">{{ $booking->user ? $booking->user->email : $booking->guest_email }}</p>
                                <p class="text-gray-600 font-normal">{{ $booking->user ? $booking->user->phone : $booking->guest_phone }}</p>
                            </div>
                        </div>
                         <div>
                            <h3 class="text-gray-500 text-sm font-semibold uppercase tracking-wide mb-3">Service Details</h3>
                             @if($booking->payable)
                                <div class="text-gray-900 font-medium">
                                     <p class="mb-1">{{ $booking->payable->title ?? $booking->payable->country . ' (' . $booking->payable->type . ')' }}</p>
                                     <p class="text-sm text-gray-500">{{ class_basename($booking->payable_type) }}</p>
                                     <p class="mt-2 text-sm">Travel Date: <span class="font-semibold">{{ $booking->booking_date->format('M d, Y') }}</span></p>
                                     <p class="text-sm">Persons: <span class="font-semibold">{{ $booking->quantity }}</span></p>
                                </div>
                             @else
                                <span class="text-red-500 italic">Service info unavailable</span>
                             @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-6 mb-8 print:bg-white print:border print:border-gray-100">
                        <div class="flex justify-between items-center text-lg font-bold text-gray-900">
                            <span>Total Amount</span>
                            <span>৳{{ number_format($booking->total_amount ?? $booking->payable->price ?? 0) }}</span>
                        </div>
                        <p class="text-xs text-center text-gray-400 mt-2 uppercase tracking-widest">Payment Status: {{ $booking->payment_status }}</p>
                    </div>

                    <div class="text-center space-y-4 print:hidden">
                        <button onclick="window.print()" class="bg-gray-900 text-white px-6 py-3 rounded-xl font-bold hover:bg-black transition shadow-lg w-full sm:w-auto flex items-center justify-center mx-auto">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                            Print Receipt
                        </button>
                        
                        <a href="{{ route('home') }}" class="block text-gray-500 hover:text-red-600 font-medium transition text-sm">
                            Return to Home
                        </a>
                    </div>

                     <!-- Print Footer -->
                    <div class="hidden print:block text-center text-xs text-gray-400 mt-12 pt-8 border-t border-gray-100">
                        <p>FlyoverBD - Your trusted travel partner</p>
                        <p>{{ url('/') }}</p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
