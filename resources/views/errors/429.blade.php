<x-app-layout>
    <div class="bg-white min-h-[60vh] flex items-center">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="max-w-2xl mx-auto text-center">
                <div class="inline-flex items-center justify-center h-24 w-24 rounded-full bg-red-50 mb-8">
                    <svg class="h-12 w-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                
                <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight sm:text-5xl mb-4">
                    Top Many Requests
                </h1>
                
                <p class="text-xl text-gray-500 mb-8 border-b border-gray-100 pb-8">
                    We've detected unusual booking activity from your trusted device.
                </p>

                <div class="bg-red-50 rounded-xl p-8 mb-10 text-left border border-red-100">
                    <h3 class="text-lg font-bold text-red-800 mb-2">Security Protocol Activated</h3>
                    <p class="text-red-700 mb-4">
                        To ensure the security of our platform and fairness for all travelers, we have temporarily paused booking capabilities for your account.
                    </p>
                    <div class="flex items-center text-sm font-semibold text-red-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        Please wait 30 minutes before trying again.
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-xl text-white bg-red-600 hover:bg-red-700 md:py-4 md:text-lg transition shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                        Return to Home
                    </a>
                    <a href="{{ route('packages.index') }}" class="inline-flex items-center justify-center px-8 py-3 border-2 border-gray-200 text-base font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 md:py-4 md:text-lg transition">
                        Browse Packages
                    </a>
                </div>
                
                <p class="mt-8 text-xs text-gray-400">
                    Reference ID: {{ request()->id() }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
