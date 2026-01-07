<x-app-layout>
    <div class="bg-white min-h-[60vh] flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-2xl mx-auto text-center">
                <div class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-red-50 mb-8">
                    <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl mb-4">
                    Access Denied
                </h1>
                
                <p class="text-xl text-gray-500 mb-8 max-w-lg mx-auto">
                    You do not have permission to view this page. Please contact support if you believe this is an error.
                </p>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 md:py-4 md:text-lg transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Return to Home
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
