<x-app-layout>
    <x-slot name="title">Hotel Booking Confirmed - FlyoverBD</x-slot>

    <section class="min-h-[60vh] flex items-center justify-center py-20 px-4">
        <div class="max-w-lg w-full">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-100 rounded-full mb-6">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">Booking Confirmed!</h1>
                <p class="text-gray-500">Your hotel room has been booked. We'll confirm your reservation shortly.</p>
            </div>

            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-4">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Booking ID</span>
                    <span class="font-bold text-gray-900">#{{ $booking->id }}</span>
                </div>
                <div class="border-t border-gray-100"></div>
                @if($booking->room)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Hotel</span>
                    <span class="font-semibold text-gray-800 text-right">{{ $booking->room->hotel->name ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Room</span>
                    <span class="font-semibold text-gray-800">{{ $booking->room->name }}</span>
                </div>
                @endif
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Check-in</span>
                    <span class="font-semibold text-gray-800">{{ $booking->check_in->format('F d, Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Check-out</span>
                    <span class="font-semibold text-gray-800">{{ $booking->check_out->format('F d, Y') }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Nights</span>
                    <span class="font-semibold text-gray-800">{{ $booking->nights }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-gray-500 font-medium">Guests</span>
                    <span class="font-semibold text-gray-800">{{ $booking->guests }}</span>
                </div>
                <div class="border-t border-gray-100 pt-3 flex justify-between">
                    <span class="font-bold text-gray-700">Total Amount</span>
                    <span class="font-extrabold text-red-600 text-lg">৳{{ number_format($booking->total_amount) }}</span>
                </div>
            </div>

            <div class="mt-8 flex flex-col sm:flex-row gap-3">
                <a href="{{ route('hotels.index') }}" class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 px-6 rounded-xl transition">
                    Browse More Hotels
                </a>
                <a href="{{ route('home') }}" class="flex-1 text-center bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-6 rounded-xl transition">
                    Back to Home
                </a>
            </div>
        </div>
    </section>

</x-app-layout>
