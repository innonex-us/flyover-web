<x-guest-layout>
    <div>
        <div class="mb-8 text-center">
            <p class="fb-eyebrow mb-2">Security check</p>
            <h1 class="font-display text-3xl text-gray-900 mb-2">Two-Factor Authentication</h1>
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

            <button type="submit" class="btn-primary w-full py-3.5 text-base shadow-lg shadow-[#C8102E]/20">
                Verify
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </form>

        <div class="mt-6 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm font-semibold text-gray-500 hover:text-[#C8102E] transition">Cancel and sign out</button>
            </form>
        </div>
    </div>
</x-guest-layout>
