<x-admin-layout>
    <div class="mb-8">
        <a href="{{ route('admin.customizations.index') }}" class="text-gray-500 hover:text-gray-700 flex items-center mb-4 transition">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Back to Requests
        </a>
        <h2 class="text-3xl font-bold text-gray-800">Request #{{ $customization->id }}</h2>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Customer & Service Info -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Customer & Package</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-widest mb-1">Customer</p>
                        <p class="font-bold text-gray-800 uppercase text-lg">{{ $customization->name }}</p>
                        <p class="text-sm text-gray-600">{{ $customization->email }}</p>
                        <p class="text-sm text-gray-600">{{ $customization->phone }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 uppercase font-bold tracking-widest mb-1">Target Package</p>
                        @if($customization->package)
                            <a href="{{ route('packages.show', $customization->package->slug) }}" target="_blank" class="font-bold text-red-600 hover:underline">
                                {{ $customization->package->title }}
                            </a>
                            <p class="text-sm text-gray-500 mt-1">{{ $customization->package->location }}</p>
                        @else
                            <p class="text-gray-400">General / Deleted</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Structured Details (The New Dynamic Content) -->
            @if($customization->details)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Travel Details</h3>
                
                <!-- PAX & Hotel Info -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 bg-gray-50 p-4 rounded-xl">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Adults</p>
                        <p class="font-bold text-gray-800">{{ $customization->details['adults'] ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Children</p>
                        <p class="font-bold text-gray-800">{{ $customization->details['children'] ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Infants</p>
                        <p class="font-bold text-gray-800">{{ $customization->details['infants'] ?? 0 }}</p>
                    </div>
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Hotel Type</p>
                        <p class="font-bold text-indigo-600 uppercase">{{ str_replace('-', ' ', $customization->details['hotel_type'] ?? 'Not Specified') }}</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Travel Date</p>
                        <p class="font-bold text-gray-800">{{ $customization->details['travel_date'] ? \Carbon\Carbon::parse($customization->details['travel_date'])->format('M d, Y') : 'Flexible' }}</p>
                    </div>
                </div>

                <!-- Planned Destinations -->
                @if(isset($customization->details['destinations']) && is_array($customization->details['destinations']))
                    <div class="space-y-4">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Planned Route</p>
                        @foreach($customization->details['destinations'] as $dest)
                            <div class="border border-gray-100 rounded-xl overflow-hidden shadow-sm">
                                <div class="bg-gray-50 px-4 py-2 border-b border-gray-100 flex justify-between items-center">
                                    <span class="font-bold text-gray-800 uppercase text-xs">{{ $dest['country'] ?? 'Country' }}</span>
                                    <span class="text-[10px] font-black bg-white px-2 py-0.5 rounded-full border border-gray-100">{{ $dest['nights'] ?? 0 }} NIGHTS</span>
                                </div>
                                <div class="p-3">
                                    <ul class="space-y-2">
                                        @foreach($dest['cities'] ?? [] as $city)
                                            <li class="flex items-center justify-between text-sm">
                                                <div class="flex items-center text-gray-700">
                                                    <svg class="w-3 h-3 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                                    {{ $city['name'] }}
                                                </div>
                                                <span class="text-xs font-bold text-gray-400">{{ $city['nights'] }} Nights</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif

            <!-- Message/Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Message / Requirements</h3>
                <div class="prose prose-sm max-w-none text-gray-600 bg-red-50/30 p-4 rounded-xl border border-red-100/50">
                    {{ $customization->message }}
                </div>
            </div>

        </div>

        <div class="lg:col-span-1 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 sticky top-24">
                <h3 class="text-lg font-bold text-gray-900 border-b pb-2 mb-4">Request Status</h3>
                
                <div class="mb-6">
                    @if($customization->status == 'contacted')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-green-100 text-green-800 border border-green-200">
                            Status: Contacted
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-bold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                            Status: Pending
                        </span>
                    @endif
                </div>

                @if($customization->status == 'pending')
                <form action="{{ route('admin.customizations.update', $customization) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="contacted">
                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 rounded-lg shadow-md transition transform active:scale-95">
                        Mark as Contacted
                    </button>
                    <p class="text-[10px] font-bold text-gray-400 text-center uppercase tracking-widest mt-3">This will close the lead</p>
                </form>
                @else
                    <div class="text-center p-4 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        <p class="text-xs font-bold text-gray-400 uppercase">Lead is closed</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
