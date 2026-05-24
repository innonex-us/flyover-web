<x-guest-layout>
    <div>
        <div class="mb-8">
            <p class="fb-eyebrow mb-2">Password reset</p>
            <h1 class="font-display text-3xl text-gray-900">Set new password</h1>
            <p class="mt-3 text-sm leading-6 text-gray-500">Choose a stronger password to secure your account again.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <div>
                <label for="email" class="fb-field-label mb-1.5">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                       class="fb-input @error('email') !border-red-500 @enderror">
                @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password" class="fb-field-label mb-1.5">New Password</label>
                <input id="password" type="password" name="password" required autocomplete="new-password"
                       class="fb-input @error('password') !border-red-500 @enderror">
                @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <div>
                <label for="password_confirmation" class="fb-field-label mb-1.5">Confirm New Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                       class="fb-input @error('password_confirmation') !border-red-500 @enderror">
                @error('password_confirmation')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            <button type="submit" class="btn-primary w-full py-3.5 text-base shadow-lg shadow-[#C8102E]/20">
                Reset Password
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </button>
        </form>
    </div>
</x-guest-layout>
