<x-guest-layout>
    <div>
        <p class="fb-eyebrow mb-2">✈ New traveller</p>
        <h2 class="text-2xl font-bold" style="color:#18130E;letter-spacing:-0.015em;">Create your account</h2>
        <p class="text-sm mt-1" style="color:#3A332B;">Book tours, track visas, get ৳ 500 app credit on sign-up.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="flex flex-col gap-3 mt-6">
        @csrf

        <div class="fb-field {{ $errors->has('name') ? 'border-red-400' : '' }}">
            <label for="name" class="fb-field-label">FULL NAME</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                   class="fb-input mt-1" placeholder="Anika Hossain">
        </div>
        @error('name')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror

        <div class="fb-field {{ $errors->has('email') ? 'border-red-400' : '' }}">
            <label for="email" class="fb-field-label">EMAIL</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                   class="fb-input mt-1" placeholder="you@email.com">
        </div>
        @error('email')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror

        <div class="fb-field {{ $errors->has('password') ? 'border-red-400' : '' }}">
            <label for="password" class="fb-field-label">PASSWORD</label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                   class="fb-input mt-1" placeholder="Min 8 characters">
        </div>
        @error('password')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror

        <div class="fb-field {{ $errors->has('password_confirmation') ? 'border-red-400' : '' }}">
            <label for="password_confirmation" class="fb-field-label">CONFIRM PASSWORD</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="fb-input mt-1" placeholder="Repeat password">
        </div>
        @error('password_confirmation')<p class="text-xs text-red-500 mt-0.5">{{ $message }}</p>@enderror

        <button type="submit" class="ota-btn-primary w-full py-3.5 text-base mt-2">Create account →</button>

        <p class="text-xs text-center" style="color:#7A7166;">
            By registering you agree to our
            <a href="{{ route('privacy') }}" class="underline">Privacy Policy</a> and Terms.
        </p>
    </form>

    <p class="text-sm text-center mt-6" style="color:#7A7166;">
        Already have an account?
        <a href="{{ route('login') }}" class="font-semibold" style="color:#C8102E;">Sign in →</a>
    </p>
</x-guest-layout>
