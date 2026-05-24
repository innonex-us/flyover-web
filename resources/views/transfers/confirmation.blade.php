<x-app-layout>
    <x-slot name="title">Transfer Booking Confirmed — FlyoverBD</x-slot>

    <section class="min-h-[60vh] flex items-center justify-center py-20 px-4">
        <div class="max-w-lg w-full">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Booking Confirmed!</h1>
                <p class="text-gray-500">Thank you for your transfer booking. We'll contact you shortly.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Booking ID</span>
                    <span class="font-bold text-gray-900">#{{ $booking->id }}</span>
                </div>
                <div class="border-t border-gray-100"></div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Pickup</span>
                    <span class="font-semibold text-gray-800 text-right max-w-[60%]">{{ $booking->pickup_location }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Drop-off</span>
                    <span class="font-semibold text-gray-800 text-right max-w-[60%]">{{ $booking->drop_location }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Travel Date</span>
                    <span class="font-semibold text-gray-800">{{ $booking->travel_date->format('F d, Y') }}</span>
                </div>
                @if($booking->pickup_time)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Pickup Time</span>
                    <span class="font-semibold text-gray-800">{{ $booking->pickup_time }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Passengers</span>
                    <span class="font-semibold text-gray-800">{{ $booking->passenger_count }}</span>
                </div>
                @if($booking->total_amount > 0)
                <div class="border-t border-gray-100 pt-3 flex justify-between">
                    <span class="font-bold text-gray-700">Total Amount</span>
                    <span class="font-extrabold text-red-600 text-lg">৳{{ number_format($booking->total_amount) }}</span>
                </div>
                @else
                <div class="border-t border-gray-100 pt-3">
                    <p class="text-xs text-gray-400 text-center">Custom transfer — our team will provide pricing shortly.</p>
                </div>
                @endif
            </div>

            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('transfers.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition">
                    Book Another
                </a>
                <a href="{{ route('home') }}" class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl transition">
                    Back to Home
                </a>
            </div>
        </div>
    </section>

</x-app-layout>
