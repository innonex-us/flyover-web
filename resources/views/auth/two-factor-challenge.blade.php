<x-guest-layout>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <div class="mb-7 text-center">
            <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-4" style="background:#FFF1F2;">
                <svg class="w-7 h-7" style="color:#C8102E;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
            <p class="section-eyebrow mb-2">Security Check</p>
            <h1 class="font-extrabold text-2xl text-gray-900 mb-2" style="font-family:'Merriweather',Georgia,serif;">Two-Factor Authentication</h1>
            <p class="text-sm text-gray-500">Enter the code from your authenticator app.</p>
        </div>

        <form method="POST" action="{{ route('2fa.verify') }}" class="space-y-5">
            @csrf

            <div>
                <label for="code" class="fb-field-label mb-1.5">Authentication Code</label>
                <input id="code" type="text" name="code" required autofocus autocomplete="one-time-code"
                       placeholder="000 000"
                       class="fb-input text-center text-2xl font-bold tracking-[0.4em] @error('code') !border-red-500 @enderror">
                @error('code')<p class="mt-1 text-xs text-red-500 text-center">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-base">
                Verify
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </form>

        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-400 hover:text-gray-600 transition">Cancel and sign out</button>
            </form>
        </div>
    </div>
</x-guest-layout>
