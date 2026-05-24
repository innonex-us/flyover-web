<x-guest-layout>
    <div>
        <div class="mb-8">
            <p class="fb-eyebrow mb-2">Get started</p>
            <h1 class="font-display text-3xl text-gray-900">Create your account</h1>
            <p class="mt-3 text-sm leading-6 text-gray-500">Join the platform to manage trips, guests, and travel content with clarity.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="fb-field-label mb-1.5">Full Name</label>
                  <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="fb-input @error('name') !border-red-500 @enderror" placeholder="John Doe">
                @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="email" class="fb-field-label mb-1.5">Email Address</label>
                  <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="fb-input @error('email') !border-red-500 @enderror" placeholder="john@example.com">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="fb-field-label mb-1.5">Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="fb-input @error('password') !border-red-500 @enderror">
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="fb-field-label mb-1.5">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="fb-input @error('password_confirmation') !border-red-500 @enderror">
                @error('password_confirmation')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-base mt-2 shadow-lg shadow-[#C8102E]/20">
                Create Account
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-gray-500">
            Already have an account?
            <a href="{{ route('login') }}" class="font-semibold text-[#C8102E] hover:underline">Sign in</a>
        </p>
    </div>
</x-guest-layout>
