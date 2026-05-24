<x-guest-layout>
    <div>
        <div class="mb-8">
            <p class="fb-eyebrow mb-2">Verify email</p>
            <h1 class="font-display text-3xl text-gray-900">Check your inbox</h1>
            <p class="mt-3 text-sm leading-6 text-gray-500">Thanks for signing up. Verify your email address to continue using the application.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="space-y-3">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <x-primary-button class="w-full justify-center shadow-lg shadow-[#C8102E]/20">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="btn-outline w-full justify-center">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</x-guest-layout>
