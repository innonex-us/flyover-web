<x-guest-layout>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">

        <div class="mb-7 text-center">
            <p class="section-eyebrow mb-2">Welcome back</p>
            <h1 class="font-extrabold text-2xl text-gray-900" style="font-family:'Merriweather',Georgia,serif;">Sign in to FlyoverBD</h1>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="fb-field-label mb-1.5">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                       class="fb-input @error('email') !border-red-500 @enderror">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <label for="password" class="fb-field-label">Password</label>
                    @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-xs text-red-600 hover:text-red-700 font-semibold">Forgot password?</a>
                    @endif
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password"
                       class="fb-input @error('password') !border-red-500 @enderror">
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                       class="w-4 h-4 rounded border-gray-300 focus:ring-0" style="accent-color:#C8102E;">
                <label for="remember_me" class="text-sm text-gray-600">Remember me</label>
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-base mt-2">
                Sign In
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        @if (Route::has('register'))
        <p class="mt-6 text-center text-sm text-gray-500">
            Don't have an account?
            <a href="{{ route('register') }}" class="font-semibold text-red-600 hover:text-red-700">Create one</a>
        </p>
        @endif
    </div>
</x-guest-layout>
