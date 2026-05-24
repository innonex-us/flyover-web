<x-guest-layout>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <div class="mb-7 text-center">
            <p class="section-eyebrow mb-2">Password Reset</p>
            <h1 class="font-extrabold text-2xl text-gray-900 mb-3" style="font-family:'Merriweather',Georgia,serif;">Forgot your password?</h1>
            <p class="text-sm text-gray-500">Enter your email and we'll send you a reset link.</p>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="fb-field-label mb-1.5">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="fb-input @error('email') !border-red-500 @enderror" placeholder="john@example.com">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-base">
                Send Reset Link
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            <a href="{{ route('login') }}" class="font-semibold text-red-600 hover:text-red-700">Back to Sign In</a>
        </p>
    </div>
</x-guest-layout>
