<x-app-layout>
    <div class="bg-gray-50/50 min-h-screen pb-20">
        <!-- Hero Section -->
        <div class="bg-white border-b border-gray-100/80 pt-12 pb-16">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <nav class="flex justify-center text-xs font-medium text-gray-500 mb-6" aria-label="Breadcrumb">
                    <ol class="flex items-center space-x-2">
                        <li><a href="{{ route('home') }}" class="hover:text-red-500 transition">Home</a></li>
                        <li><svg class="w-3 h-3 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg></li>
                        <li class="text-gray-900 font-medium">Custom Plan</li>
                    </ol>
                </nav>
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 mb-4 tracking-tight">Plan Your Dream Trip</h1>
                <p class="text-sm md:text-base text-gray-500 max-w-2xl mx-auto leading-relaxed">
                    Tell us where you want to go, and our travel experts will craft a personalized itinerary that fits your style and budget perfectly.
                </p>
                <div class="w-20 h-1.5 bg-red-500 mx-auto mt-8 rounded-full"></div>
            </div>
        </div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10">
            @if(session('success'))
                <div class="bg-green-50 border border-green-200/50 text-green-800 px-6 py-4 rounded-2xl relative mb-8 flex items-center shadow-sm animate-in fade-in slide-in-from-top-4 duration-300" role="alert">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-4 shrink-0 shadow-sm">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="font-bold text-sm">Form Submitted Successfully!</p>
                        <p class="text-xs opacity-90">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden" x-data="{ 
                inquiryForm: {
                    destinations: [
                        { country: '', nights: '', cities: [{ name: '', nights: '' }] }
                    ],
                    adults: 1,
                    children: 0,
                    infants: 0,
                    hotel_type: '',
                    travel_date: '',
                    message: '',
                    name: '',
                    email: '',
                    phone: ''
                },
                addDestination() {
                    this.inquiryForm.destinations.push({ country: '', nights: '', cities: [{ name: '', nights: '' }] });
                },
                removeDestination(index) {
                    this.inquiryForm.destinations.splice(index, 1);
                },
                addCity(dIndex) {
                    this.inquiryForm.destinations[dIndex].cities.push({ name: '', nights: '' });
                },
                removeCity(dIndex, cIndex) {
                    this.inquiryForm.destinations[dIndex].cities.splice(cIndex, 1);
                }
            }">
                <div class="p-6 sm:p-10 md:p-12">
                    <form action="{{ route('packages.customize.general') }}" method="POST" class="space-y-10">
                        @csrf
                        
                        <!-- Destinations & Nights -->
                        <div class="space-y-6">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-8 h-8 bg-red-50 text-red-600 rounded-lg flex items-center justify-center font-bold text-sm">01</div>
                                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Where would you like to go?</h3>
                            </div>
                            
                            <template x-for="(dest, dIndex) in inquiryForm.destinations" :key="dIndex">
                                <div class="p-6 bg-gray-50/50 rounded-2xl border border-gray-100 space-y-6 relative group transition-all hover:bg-white hover:shadow-md">
                                    <button type="button" @click="removeDestination(dIndex)" x-show="inquiryForm.destinations.length > 1" class="absolute -top-3 -right-3 bg-white text-gray-400 hover:text-red-500 p-2 rounded-full shadow-lg border border-gray-100 transition-all active:scale-95">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] ml-1">Destination Country</label>
                                            <input type="text" :name="'destinations[' + dIndex + '][country]'" x-model="dest.country" required class="w-full bg-white border-gray-200 rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-red-500/10 focus:border-red-500 shadow-sm transition-all" placeholder="e.g. Switzerland">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] ml-1">Total Nights</label>
                                            <input type="number" :name="'destinations[' + dIndex + '][nights]'" x-model="dest.nights" required class="w-full bg-white border-gray-200 rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-red-500/10 focus:border-red-500 shadow-sm transition-all" placeholder="Number of nights">
                                        </div>
                                    </div>

                                    <!-- Cities List -->
                                    <div class="pl-5 border-l-2 border-red-200/50 space-y-4">
                                        <template x-for="(city, cIndex) in dest.cities" :key="cIndex">
                                            <div class="flex flex-col sm:flex-row gap-4 sm:items-end">
                                                <div class="flex-1 space-y-2">
                                                    <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1">City / Location</label>
                                                    <input type="text" :name="'destinations[' + dIndex + '][cities][' + cIndex + '][name]'" x-model="city.name" class="w-full bg-white border-gray-200 rounded-xl py-2.5 px-4 text-xs focus:ring-2 focus:ring-red-500/10 focus:border-red-500 shadow-sm transition-all" placeholder="e.g. Zurich">
                                                </div>
                                                <div class="flex items-end gap-3">
                                                    <div class="w-24 space-y-2">
                                                        <label class="text-[9px] font-bold text-gray-400 uppercase tracking-widest ml-1">Nights</label>
                                                        <input type="number" :name="'destinations[' + dIndex + '][cities][' + cIndex + '][nights]'" x-model="city.nights" class="w-full bg-white border-gray-200 rounded-xl py-2.5 px-4 text-xs focus:ring-2 focus:ring-red-500/10 focus:border-red-500 shadow-sm transition-all">
                                                    </div>
                                                    <button type="button" @click="removeCity(dIndex, cIndex)" x-show="dest.cities.length > 1" class="mb-2 text-gray-300 hover:text-red-500 transition-colors">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </template>
                                        <button type="button" @click="addCity(dIndex)" class="text-[10px] font-bold text-red-500 hover:text-red-600 flex items-center transition group">
                                            <span class="w-5 h-5 rounded-full border border-red-200 flex items-center justify-center mr-2 group-hover:bg-red-500 group-hover:text-white transition-all">+</span>
                                            Add Another City
                                        </button>
                                    </div>
                                </div>
                            </template>
                            
                            <button type="button" @click="addDestination()" class="w-full py-4 border-2 border-dashed border-gray-200 rounded-2xl text-xs font-bold text-gray-400 hover:border-red-300 hover:text-red-500 hover:bg-red-50/30 transition-all flex items-center justify-center space-x-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Add Another Destination Country</span>
                            </button>
                        </div>

                        <!-- PAX Count -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center font-bold text-sm">02</div>
                                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Who's traveling?</h3>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100/80">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1 ml-1">Adults (12+)</label>
                                    <input type="number" name="adults" x-model="inquiryForm.adults" min="1" class="w-full bg-white border-gray-200 rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 shadow-sm transition-all text-center font-bold">
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100/80">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1 ml-1">Child (2-12)</label>
                                    <input type="number" name="children" x-model="inquiryForm.children" min="0" class="w-full bg-white border-gray-200 rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 shadow-sm transition-all text-center font-bold">
                                </div>
                                <div class="bg-gray-50 rounded-2xl p-4 border border-gray-100/80">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest block mb-1 ml-1">Infant (0-2)</label>
                                    <input type="number" name="infants" x-model="inquiryForm.infants" min="0" class="w-full bg-white border-gray-200 rounded-xl py-3 px-4 text-sm focus:ring-2 focus:ring-blue-500/10 focus:border-blue-500 shadow-sm transition-all text-center font-bold">
                                </div>
                            </div>
                        </div>

                        <!-- Travel Details -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-8 h-8 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center font-bold text-sm">03</div>
                                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Travel Preferences</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Hotel Category</label>
                                    <div class="relative">
                                        <select name="hotel_type" x-model="inquiryForm.hotel_type" class="w-full bg-gray-50/50 border-gray-200 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-amber-500/10 focus:border-amber-500 shadow-sm appearance-none transition-all">
                                            <option value="">Select Hotel Category</option>
                                            <option value="3-star">⭐⭐⭐ 3 Star (Standard)</option>
                                            <option value="4-star">⭐⭐⭐⭐ 4 Star (Premium)</option>
                                            <option value="5-star">⭐⭐⭐⭐⭐ 5 Star (Luxury)</option>
                                            <option value="budget">🏠 Budget / Guest House</option>
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                                            <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"/></svg>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Estimated Travel Date</label>
                                    <input type="date" name="travel_date" x-model="inquiryForm.travel_date" class="w-full bg-gray-50/50 border-gray-200 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-amber-500/10 focus:border-amber-500 shadow-sm transition-all">
                                </div>
                            </div>

                            <div class="space-y-2">
                                <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Special Requirements / Notes</label>
                                <textarea name="message" x-model="inquiryForm.message" rows="4" class="w-full bg-gray-50/50 border-gray-200 rounded-2xl py-4 px-5 text-sm focus:ring-2 focus:ring-red-500/10 focus:border-red-500 shadow-sm transition-all" placeholder="Tell us more about your ideal trip (preferred activities, pace of travel, dietary needs, etc.)..."></textarea>
                            </div>
                        </div>

                        <!-- Contact Info -->
                        <div class="space-y-6 pt-6 border-t border-gray-50">
                            <div class="flex items-center space-x-3 mb-2">
                                <div class="w-8 h-8 bg-green-50 text-green-600 rounded-lg flex items-center justify-center font-bold text-sm">04</div>
                                <h3 class="text-lg font-bold text-gray-900 tracking-tight">Your Contact Information</h3>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Full Name</label>
                                    <input type="text" name="name" x-model="inquiryForm.name" required class="w-full bg-gray-50/50 border-gray-200 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-green-500/10 focus:border-green-500 shadow-sm transition-all" placeholder="John Doe">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Mobile / WhatsApp</label>
                                    <input type="text" name="phone" x-model="inquiryForm.phone" required class="w-full bg-gray-50/50 border-gray-200 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-green-500/10 focus:border-green-500 shadow-sm transition-all" placeholder="+880 1XXX-XXXXXX">
                                </div>
                                <div class="md:col-span-2 space-y-2">
                                    <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest ml-1">Email Address</label>
                                    <input type="email" name="email" x-model="inquiryForm.email" required class="w-full bg-gray-50/50 border-gray-200 rounded-xl py-3.5 px-4 text-sm focus:ring-2 focus:ring-green-500/10 focus:border-green-500 shadow-sm transition-all" placeholder="john@example.com">
                                </div>
                            </div>
                        </div>

                        <div class="text-center pt-10">
                            <button type="submit" class="inline-flex items-center justify-center px-16 py-5 bg-red-600 text-white rounded-2xl font-black text-base shadow-xl shadow-red-200 hover:bg-red-700 hover:-translate-y-1 transition-all active:scale-95 group">
                                <span>Submit My Request</span>
                                <svg class="w-5 h-5 ml-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </button>
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em] mt-6 leading-relaxed">
                                Our experts will respond within 24-48 hours with a draft itinerary.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
