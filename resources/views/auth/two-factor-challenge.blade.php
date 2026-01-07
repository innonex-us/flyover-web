<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-gray-800">Two-Factor Authentication</h2>
        <p class="text-sm text-gray-500 mt-2">Please confirm access to your account by entering the authentication code provided by your authenticator application.</p>
    </div>

    <form method="POST" action="{{ route('2fa.verify') }}">
        @csrf

        <div class="mb-6">
            <x-input-label for="code" :value="__('Authentication Code')" class="sr-only" />
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="code" class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 sm:text-lg tracking-widest text-center" 
                    type="text" 
                    name="code" 
                    required 
                    autofocus 
                    autocomplete="one-time-code"
                    placeholder="000 000" />
            </div>
            <x-input-error :messages="$errors->get('code')" class="mt-2 text-center" />
        </div>

        <div class="flex items-center justify-end mt-4">
             <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg shadow transition">
                {{ __('Verify') }}
            </button>
        </div>
        
        <div class="mt-6 text-center">
             <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-gray-700 underline">
                    {{ __('Cancel and logout') }}
                </button>
            </form>
        </div>
    </form>
</x-guest-layout>
