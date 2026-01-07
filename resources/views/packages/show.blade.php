<x-app-layout>
    <div class="py-12 bg-white" x-data="{ openInquiryModal: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Breadcrumbs -->
            <nav class="flex mb-8 text-sm font-medium text-gray-500" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li><a href="{{ route('home') }}" class="hover:text-red-600 transition">Home</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </li>
                    <li><a href="{{ route('packages.index') }}" class="hover:text-red-600 transition">Tour Packages</a></li>
                    <li>
                        <svg class="w-4 h-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    </li>
                    <li class="text-gray-900" aria-current="page">{{ $package->title }}</li>
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
                    
                    <!-- Image Gallery (Alpine.js) -->
                    <div class="mb-8" x-data="{ 
                        activeImage: '{{ $package->thumbnail ?? 'https://via.placeholder.com/800x450?text=Tour+Package' }}',
                        images: [
                            '{{ $package->thumbnail ?? 'https://via.placeholder.com/800x450?text=Tour+Package' }}',
                            @if(!empty($package->images) && is_array($package->images))
                                @foreach($package->images as $img)
                                    '{{ $img }}',
                                @endforeach
                            @endif
                        ]
                    }">
                        <div class="relative h-96 rounded-2xl overflow-hidden shadow-xl mb-4 group">
                            <img :src="activeImage" alt="{{ $package->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </div>
                        
                        <!-- Thumbnails -->
                        <div class="flex space-x-3 overflow-x-auto pb-2 scrollbar-hide">
                            <template x-for="(img, index) in images" :key="index">
                                <button 
                                    @click="activeImage = img" 
                                    class="flex-shrink-0 w-24 h-24 rounded-lg overflow-hidden border-2 transition-all duration-200"
                                    :class="activeImage === img ? 'border-red-600 ring-2 ring-red-200' : 'border-transparent opacity-70 hover:opacity-100'"
                                >
                                    <img :src="img" class="w-full h-full object-cover">
                                </button>
                            </template>
                        </div>
                    </div>

                    <h1 class="text-4xl font-extrabold text-gray-900 mb-4">{{ $package->title }}</h1>
                    
                    <div class="flex flex-wrap items-center gap-6 text-gray-600 mb-8 pb-8 border-b border-gray-100">
                        <span class="flex items-center px-4 py-2 bg-gray-50 rounded-full">
                            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            {{ $package->location }}
                        </span>
                        <span class="flex items-center px-4 py-2 bg-gray-50 rounded-full">
                            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $package->duration_days }} Days / {{ $package->duration_days - 1 }} Nights
                        </span>
                    </div>

                    <div class="prose prose-lg max-w-none text-gray-600 mb-8">
                        <div x-data="{ activeTab: 'details' }">
                            
                            <!-- Tabs Navigation -->
                            <div class="border-b border-gray-200 mb-6">
                                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                                    <button 
                                        @click="activeTab = 'details'"
                                        :class="activeTab === 'details' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg transition-colors duration-200"
                                    >
                                        Details
                                    </button>
                                    <button 
                                        @click="activeTab = 'options'"
                                        :class="activeTab === 'options' ? 'border-red-500 text-red-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                        class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-lg transition-colors duration-200"
                                    >
                                        Options
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

                            <!-- Details Tab -->
                            <div x-show="activeTab === 'details'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                
                                <h2 class="text-2xl font-bold text-gray-900 mb-4">Overview</h2>
                                <p class="leading-relaxed mb-8">{{ $package->description }}</p>

                                @if(!empty($package->inclusions) || !empty($package->exclusions))
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                                        @if(!empty($package->inclusions))
                                            <div class="bg-green-50 rounded-xl p-6 border border-green-100">
                                                <h3 class="text-lg font-bold text-green-800 mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                    Inclusions
                                                </h3>
                                                <ul class="space-y-2">
                                                    @foreach($package->inclusions as $inc)
                                                        <li class="flex items-start text-sm text-green-700">
                                                            <svg class="w-4 h-4 mr-2 mt-1 flex-shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                            {{ $inc }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        
                                        @if(!empty($package->exclusions))
                                            <div class="bg-red-50 rounded-xl p-6 border border-red-100">
                                                <h3 class="text-lg font-bold text-red-800 mb-4 flex items-center">
                                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    Exclusions
                                                </h3>
                                                <ul class="space-y-2">
                                                    @foreach($package->exclusions as $exc)
                                                        <li class="flex items-start text-sm text-red-700">
                                                            <svg class="w-4 h-4 mr-2 mt-1 flex-shrink-0 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                            {{ $exc }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                @endif

                                @if($package->requirements)
                                    <div class="bg-blue-50 rounded-xl p-6 border border-blue-100 mb-8">
                                        <h3 class="text-lg font-bold text-blue-800 mb-2">Requirements</h3>
                                        <div class="text-sm text-blue-700 whitespace-pre-line">
                                            {{ $package->requirements }}
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <!-- Options Tab -->
                            <div x-show="activeTab === 'options'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="bg-gray-50 rounded-xl p-8 text-center" x-data="{ openCustomModal: false }">
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">Customize Your Package</h3>
                                    <p class="text-gray-600 mb-6">Want to upgrade your hotel or add more activities? Contact our support team for a tailored experience.</p>
                                    
                                    <button 
                                        @click="openCustomModal = true"
                                        class="bg-white border border-gray-300 text-gray-700 font-semibold py-2 px-6 rounded-lg hover:bg-gray-100 transition shadow-sm"
                                    >
                                        Request Customization
                                    </button>

                                    <!-- Customization Modal -->
                                    <div 
                                        x-show="openCustomModal" 
                                        style="display: none;"
                                        class="fixed inset-0 z-50 overflow-y-auto" 
                                        aria-labelledby="modal-title" 
                                        role="dialog" 
                                        aria-modal="true"
                                    >
                                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                            
                                            <div 
                                                x-show="openCustomModal" 
                                                x-transition:enter="ease-out duration-300" 
                                                x-transition:enter-start="opacity-0" 
                                                x-transition:enter-end="opacity-100" 
                                                x-transition:leave="ease-in duration-200" 
                                                x-transition:leave-start="opacity-100" 
                                                x-transition:leave-end="opacity-0"
                                                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                                                @click="openCustomModal = false"
                                                aria-hidden="true"
                                            ></div>

                                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                                            <div 
                                                x-show="openCustomModal" 
                                                x-transition:enter="ease-out duration-300" 
                                                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                                                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                                                x-transition:leave="ease-in duration-200" 
                                                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                                                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
                                            >
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start">
                                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                                Request Customization
                                                            </h3>
                                                            <div class="mt-2">
                                                                <p class="text-sm text-gray-500 mb-4">
                                                                    Tell us how you'd like to customize the <strong>{{ $package->title }}</strong>.
                                                                </p>
                                                                
                                                                <form action="{{ route('packages.customize', $package->id) }}" method="POST">
                                                                    @csrf
                                                                    <div class="space-y-4">
                                                                        <div>
                                                                            <label class="block text-sm font-medium text-gray-700">Name</label>
                                                                            <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                                                        </div>
                                                                        <div>
                                                                            <label class="block text-sm font-medium text-gray-700">Email</label>
                                                                            <input type="email" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                                                        </div>
                                                                           <div>
                                                                            <label class="block text-sm font-medium text-gray-700">Phone</label>
                                                                            <input type="text" name="phone" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                                                        </div>
                                                                        <div>
                                                                            <label class="block text-sm font-medium text-gray-700">What would you like to update?</label>
                                                                            <textarea name="message" rows="3" required placeholder="E.g., upgrading hotel, changing dates, adding flights..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                                                                        </div>
                                                                    </div>
                                                                    
                                                                    <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                                                                            Send Request
                                                                        </button>
                                                                        <button type="button" @click="openCustomModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                                                            Cancel
                                                                        </button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Policy Tab -->
                            <div x-show="activeTab === 'policy'" style="display: none;" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                                <div class="bg-gray-50 rounded-xl p-8 border border-gray-100">
                                    <div class="prose prose-sm max-w-none text-gray-600 whitespace-pre-line">
                                        {{ $package->policy ?? "Cancellation
To cancel any tour, an email has to be sent to support@flyoverbd.com mentioning the tour booking ID and details about the cancellation.
Travelers are responsible for notifying us of any cancellations as soon as possible.

Refund
80% of the fees will be refunded if the booking is canceled more than Twenty-One (21) days before the beginning of the experience/tour.
50% of the fees will be refunded if the booking is canceled within Fourteen (14) to Twenty-One (21) days before the beginning of the experience/tour.
30% of the tour fee will be refunded if the booking is canceled within Seven (7) to Fourteen (14) days before the beginning of the experience/tour.
Refund will not be provided if the tour is cancelled less than Seven (7) days before the beginning of the experience/tour." }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Right Column: Booking Card -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-xl p-6 sm:p-8 sticky top-24 border border-gray-100 transform transition hover:scale-[1.01]">
                        <div class="mb-6 pb-6 border-b border-gray-100">
                            <p class="text-gray-500 text-sm uppercase tracking-wide font-semibold mb-1">Starting from</p>
                            <div class="flex items-baseline">
                                <span class="text-4xl font-extrabold text-red-600">৳{{ number_format($package->price) }}</span>
                                <span class="text-gray-400 ml-2">/ person</span>
                            </div>
                        </div>
                        
                        <form action="{{ route('bookings.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="payable_type" value="package">
                            <input type="hidden" name="payable_id" value="{{ $package->id }}">
                            
                            <div class="space-y-5 mb-8">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-2">When do you want to go?</label>
                                    <input type="date" name="booking_date" required class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3" min="{{ date('Y-m-d') }}">
                                </div>
                                
                                @guest
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
                                @endguest

                            </div>

                            <button type="submit" class="w-full bg-red-600 text-white text-lg font-bold py-4 px-6 rounded-xl hover:bg-red-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 mb-4">
                                Book This Package
                            </button>
                        </form>
                        
                        <!-- Ask a Question Modal Trigger -->
                        <button 
                            @click="openInquiryModal = true"
                            class="w-full border-2 border-red-100 text-red-600 font-bold py-3 px-6 rounded-xl hover:bg-red-50 transition"
                        >
                            Ask a Question
                        </button>

                        <div class="mt-8 pt-6 border-t border-gray-100 text-sm text-gray-500 space-y-3">
                            <p class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Instant Confirmation
                            </p>
                            <p class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Best Price Guaranteed
                            </p>
                            <p class="flex items-center">
                                <svg class="w-5 h-5 mr-3 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                24/7 Expert Support
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Inquiry Modal (Moved Outside) -->
        <div 
            x-show="openInquiryModal" 
            style="display: none;"
            class="fixed inset-0 z-50 overflow-y-auto" 
            aria-labelledby="modal-title" 
            role="dialog" 
            aria-modal="true"
        >
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div 
                    x-show="openInquiryModal" 
                    x-transition:enter="ease-out duration-300" 
                    x-transition:enter-start="opacity-0" 
                    x-transition:enter-end="opacity-100" 
                    x-transition:leave="ease-in duration-200" 
                    x-transition:leave-start="opacity-100" 
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" 
                    @click="openInquiryModal = false"
                    aria-hidden="true"
                ></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div 
                    x-show="openInquiryModal" 
                    x-transition:enter="ease-out duration-300" 
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                    x-transition:leave="ease-in duration-200" 
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full"
                >
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                    Ask a Question about {{ $package->title }}
                                </h3>
                                <div class="mt-2">
                                    <p class="text-sm text-gray-500 mb-4">
                                        Have a question? Fill out the form below and our team will get back to you shortly.
                                    </p>
                                    
                                    <form action="{{ route('packages.customize', $package->id) }}" method="POST">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                                <input type="text" name="name" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                                <input type="email" name="email" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                            </div>
                                                <div>
                                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                                <input type="text" name="phone" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">Your Question</label>
                                                <textarea name="message" rows="3" required placeholder="Type your question here..." class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring-red-500"></textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-5 sm:mt-6 sm:grid sm:grid-cols-2 sm:gap-3 sm:grid-flow-row-dense">
                                            <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:col-start-2 sm:text-sm">
                                                Send Inquiry
                                            </button>
                                            <button type="button" @click="openInquiryModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:mt-0 sm:col-start-1 sm:text-sm">
                                                Cancel
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
