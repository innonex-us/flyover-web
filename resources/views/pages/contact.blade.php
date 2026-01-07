<x-app-layout>
    <div class="bg-gray-50 py-24 sm:py-32">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:mx-0 mb-16">
                <h2 class="text-3xl font-bold tracking-tight text-gray-900 sm:text-4xl">Contact Us</h2>
                <p class="mt-2 text-lg leading-8 text-gray-600">Have questions about your next trip or visa application? We're here to help.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16">
                <!-- Contact Info -->
                <div>
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 space-y-8">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <span class="bg-red-100 p-2 rounded-lg text-red-600 mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                </span>
                                Visit Our Office
                            </h3>
                            <p class="text-gray-600 ml-12">
                                House 45, Road 13, Block D<br>
                                Banani, Dhaka, Bangladesh
                            </p>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <span class="bg-red-100 p-2 rounded-lg text-red-600 mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                </span>
                                Call Us
                            </h3>
                            <p class="text-gray-600 ml-12">
                                +880 1335 111370<br>
                                +880 9678 332211
                            </p>
                        </div>

                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                                <span class="bg-red-100 p-2 rounded-lg text-red-600 mr-3">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </span>
                                Email Us
                            </h3>
                            <p class="text-gray-600 ml-12">
                                support@flyoverbd.com<br>
                                visa@flyoverbd.com
                            </p>
                        </div>
                    </div>

                    <!-- Map Placeholder -->
                    <div class="mt-8 bg-gray-200 rounded-2xl h-64 w-full overflow-hidden shadow-sm border border-gray-300 flex items-center justify-center relative">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3650.318322300062!2d90.40393457597148!3d23.79242968659107!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3755c70c0c609053%3A0xc48641477727382b!2sBanani%2C%20Dhaka!5e0!3m2!1sen!2sbd!4v1705574400000!5m2!1sen!2sbd" width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-lg p-8 lg:p-12">
                     <h3 class="text-2xl font-bold text-gray-900 mb-6">Send us a Message</h3>
                     <form action="#" method="POST" class="space-y-6">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">First Name</label>
                                <input type="text" name="first_name" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3" required>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Last Name</label>
                                <input type="text" name="last_name" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3" required>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                            <input type="email" name="email" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3" required>
                        </div>

                         <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Phone Number</label>
                            <input type="text" name="phone" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3" required>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Subject</label>
                            <select name="subject" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3">
                                <option>Visa Inquiry</option>
                                <option>Tour Package</option>
                                <option>Flight Booking</option>
                                <option>Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Message</label>
                            <textarea name="message" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-200 py-3" required></textarea>
                        </div>

                        <button type="button" class="w-full bg-red-600 text-white font-bold py-4 rounded-xl hover:bg-red-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Send Message
                        </button>
                     </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
