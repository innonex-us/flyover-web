<x-guest-layout>
    <div>
        <div class="mb-8">
            <p class="fb-eyebrow mb-2">Security check</p>
            <h1 class="font-display text-3xl text-gray-900">Confirm your password</h1>
            <p class="mt-3 text-sm leading-6 text-gray-500">This is a secure area. Please confirm your password before continuing.</p>
        </div>

        <form method="POST" action="{{ route('password.confirm') }}" class="space-y-4">
            @csrf

            <div>
                <label for="password" class="fb-field-label mb-1.5">Password</label>
                <input id="password" class="fb-input" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end pt-2">
                <x-primary-button>
                    {{ __('Confirm') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
