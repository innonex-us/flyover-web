<x-app-layout>
    {{-- @push('meta')
    <script type="application/ld+json">
    {
      "@context": "https://schema.org/",
      "@type": "Product",
      "name": "{{ $visa->country }} Visa Processing",
      "description": "{{ \Illuminate\Support\Str::limit(strip_tags($visa->description), 160) }}",
      "image": "{{ $meta_image ?? '' }}",
      "offers": {
        "@type": "Offer",
        "priceCurrency": "BDT",
        "price": "{{ $visa->price }}",
        "availability": "https://schema.org/InStock",
        "url": "{{ url()->current() }}"
      }
    }
    </script>
    @endpush --}}
    <div class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-8 text-sm font-medium text-gray-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('home') }}" class="hover:text-red-600 transition">Home</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </li>
                    <li><a href="{{ route('visas.index') }}" class="hover:text-red-600 transition">Visa Services</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </li>
                    <li class="text-gray-900" aria-current="page">{{ $visa->country }}</li>
                </ol>
            </nav>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                <!-- Left Column: Details -->
                <div class="lg:col-span-2">
                    <div class="flex items-start mb-8">
                        <img src="{{ Str::startsWith($visa->thumbnail, 'http') ? $visa->thumbnail : Storage::url($visa->thumbnail) }}" alt="{{ $visa->country }}" class="w-24 h-auto shadow-md rounded-lg mr-6 object-cover border border-gray-100">
                        <div>
                            <h1 class="text-3xl font-extrabold text-gray-900 mb-2">{{ $visa->country }} Visa</h1>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm font-semibold">{{ $visa->entry_type ?? $visa->type }}</span>
                                <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm font-semibold">{{ $visa->processing_time }}</span>
                            </div>
                        </div>
                    </div>

                    <div x-data="{ activeTab: 'summary' }">
                        <!-- Tabs Navigation -->
                        <div class="border-b border-gray-200 mb-8">
                            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                <button 
                                    @click="activeTab = 'summary'"
                                    :class="activeTab === 'summary' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg transition-colors duration-200"
                                >
                                    Summary
                                </button>
                                <button 
                                    @click="activeTab = 'checklist'"
                                    :class="activeTab === 'checklist' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg transition-colors duration-200"
                                >
                                    Checklist
                                </button>
                                <button 
                                    @click="activeTab = 'policy'"
                                    :class="activeTab === 'policy' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg transition-colors duration-200"
                                >
                                    Policy
                                </button>
                            </nav>
                        </div>

                        <!-- Summary Tab -->
                        <div x-show="activeTab === 'summary'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            <h2 class="text-xl font-bold text-gray-900 mb-4">Visa Overview</h2>
                            <p class="text-gray-600 leading-relaxed mb-8">{{ $visa->description }}</p>

                            <div class="bg-gray-50 rounded-xl p-6 border border-gray-100 mb-8">
                                <h3 class="text-lg font-bold text-gray-900 mb-4">Processing Details</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Processing Time</p>
                                        <p class="font-semibold text-gray-900">{{ $visa->validity_info ?? $visa->processing_time }}</p>
                                        <p class="text-xs text-gray-400 mt-1">(from the date of submission)</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500 mb-1">Visa Fee</p>
                                        <p class="font-semibold text-gray-900">{{ $visa->fees ?? '৳'.number_format($visa->price) }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="bg-blue-50 rounded-xl p-6 border border-blue-100">
                                <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center">
                                    <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    Visit Our Experience Center
                                </h3>
                                <p class="text-blue-800 mb-4">
                                    You can visit our Experience Center for a free Visa consultation with our travel experts and get started on your application.
                                </p>
                                <div class="bg-white p-4 rounded-lg border border-blue-200 mb-4">
                                    <p class="font-semibold text-gray-900">House 45, Road 13, Block D, Banani, Dhaka</p>
                                </div>
                                <div class="flex flex-col sm:flex-row gap-4">
                                    <a href="#" class="flex items-center text-blue-700 font-semibold hover:underline">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                        +88 09678 332211
                                    </a>
                                    <a href="#" class="flex items-center text-blue-700 font-semibold hover:underline">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                                        View location in Google Maps
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Checklist Tab -->
                        <div x-show="activeTab === 'checklist'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">List of Documents Needed</h2>
                            
                            @if(!empty($visa->required_documents) && is_array($visa->required_documents))
                                <div class="space-y-6">
                                    @foreach($visa->required_documents as $category => $docs)
                                        <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                                <h3 class="font-bold text-gray-900">{{ $category }}</h3>
                                            </div>
                                            <div class="p-6">
                                                <ul class="space-y-3">
                                                    @if(is_array($docs))
                                                        @foreach($docs as $doc)
                                                            <li class="flex items-start">
                                                                <svg class="w-5 h-5 mr-3 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                <span class="text-gray-600 text-sm">{{ $doc }}</span>
                                                            </li>
                                                        @endforeach
                                                    @else
                                                        <li class="flex items-start">
                                                            <svg class="w-5 h-5 mr-3 text-green-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            <span class="text-gray-600 text-sm">{{ $docs }}</span>
                                                        </li>
                                                    @endif
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-6 p-4 bg-yellow-50 text-yellow-800 text-sm rounded-lg border border-yellow-200">
                                    <strong>N.B:</strong> All documents must be submitted in physical form, in person at our office.
                                </div>
                            @else
                                <div class="bg-white border border-gray-200 rounded-xl p-6">
                                    <p class="whitespace-pre-line text-gray-600">{{ $visa->requirements }}</p>
                                </div>
                            @endif
                        </div>

                        <!-- Policy Tab -->
                        <div x-show="activeTab === 'policy'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                            <h2 class="text-xl font-bold text-gray-900 mb-6">Important Notes & Policy</h2>
                            
                            @if($visa->important_notes)
                                <div class="bg-gray-50 rounded-xl p-8 border border-gray-100">
                                    <ul class="space-y-4">
                                        @foreach(explode("\n", $visa->important_notes) as $note)
                                            @if(trim($note))
                                                <li class="flex items-start text-sm text-gray-600">
                                                    <span class="w-1.5 h-1.5 bg-red-500 rounded-full mt-2 mr-3 flex-shrink-0"></span>
                                                    {{ trim($note) }}
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @elseif($visa->terms)
                                <div class="prose max-w-none text-gray-600">{{ $visa->terms }}</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Column: Assistance/Booking -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-8 sticky top-24 border border-gray-100 transform transition hover:scale-[1.01]">
                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <p class="text-gray-500 text-sm uppercase tracking-wide font-semibold mb-1">Visa Fee</p>
                            <div class="flex items-baseline">
                                <span class="text-4xl font-extrabold text-red-600">৳{{ number_format($visa->price) }}</span>
                                <span class="text-gray-400 ml-2">/ person</span>
                            </div>
                        </div>
                        
                         <h3 class="text-xl font-bold text-gray-900 mb-4">Apply Now</h3>
                         
                         <form action="{{ route('bookings.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payable_type" value="visa">
                            <input type="hidden" name="payable_id" value="{{ $visa->id }}">
                            
                            <div class="space-y-5 mb-8">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Expected Travel Date</label>
                                    <input type="date" name="booking_date" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3" min="{{ date('Y-m-d') }}">
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">Number of Persons</label>
                                    <input type="number" name="quantity" value="1" min="1" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3">
                                </div>
                                
                                @auth
                                    <!-- Read-only fields for authenticated users -->
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                                        <input type="text" value="{{ Auth::user()->name }}" readonly class="w-full border-gray-300 bg-gray-100 cursor-not-allowed text-gray-500 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                        <input type="email" value="{{ Auth::user()->email }}" readonly class="w-full border-gray-300 bg-gray-100 cursor-not-allowed text-gray-500 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                                        <input type="text" value="{{ Auth::user()->phone }}" readonly class="w-full border-gray-300 bg-gray-100 cursor-not-allowed text-gray-500 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3">
                                    </div>
                                @else
                                    <!-- Editable fields for guests -->
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Full Name</label>
                                        <input type="text" name="guest_name" required placeholder="John Doe" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Email Address</label>
                                        <input type="email" name="guest_email" required placeholder="john@example.com" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-2">Phone Number</label>
                                        <input type="text" name="guest_phone" required placeholder="+8801..." class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3">
                                    </div>
                                @endauth
                            </div>

                            <button type="submit" class="w-full bg-red-600 text-white text-lg font-bold py-4 px-6 rounded-xl hover:bg-red-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 mb-6">
                                Submit Application
                            </button>
                        </form>

                        <div class="border-t border-gray-100 pt-6">
                             <h3 class="text-lg font-bold text-gray-900 mb-4">Need Help?</h3>
                            <div class="space-y-4">
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="bg-white p-2 rounded-full mr-3 text-green-500 shadow-sm border border-gray-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold">Call Us 24/7</p>
                                        <p class="font-bold text-gray-900">+88 09678 332211</p>
                                    </div>
                                </div>
                                <div class="flex items-center p-3 bg-gray-50 rounded-lg">
                                    <div class="bg-white p-2 rounded-full mr-3 text-blue-500 shadow-sm border border-gray-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 uppercase font-semibold">Email Us</p>
                                        <p class="font-bold text-gray-900">visa@flyoverbd.com</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
