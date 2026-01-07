<x-app-layout>
    <div class="bg-white min-h-[60vh] flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-2xl mx-auto text-center">
                <div class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-red-50 mb-8">
                    <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl mb-4">
                    Page Expired
                </h1>
                
                <p class="text-xl text-gray-500 mb-8 max-w-lg mx-auto">
                    Your session has expired due to inactivity. Please refresh the page and try again.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 md:py-4 md:text-lg transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Login Again
                    </a>
                    <button onclick="window.location.reload()" class="inline-flex items-center justify-center px-8 py-3 border-2 border-gray-200 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg transition">
                        Refresh Page
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
