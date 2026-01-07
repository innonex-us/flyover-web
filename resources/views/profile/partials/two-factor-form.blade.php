<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Two-Factor Authentication') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Add additional security to your account using two-factor authentication.') }}
        </p>
    </header>

    <div class="mt-6">
        @if(auth()->user()->google2fa_secret)
             <div class="flex items-center text-green-600 font-semibold mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Two-Factor Authentication is Enabled
            </div>
            
             <form method="POST" action="{{ route('two-factor.disable') }}" onsubmit="return confirm('Are you sure you want to disable 2FA? This will make your account less secure.');">
                 @csrf
                 @method('DELETE')
                 
                 <div class="mt-4">
                    <x-input-label for="password" value="Confirm Password to Disable" class="sr-only" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full max-w-xs" placeholder="Current Password" required />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                 <x-danger-button class="mt-4">
                    {{ __('Disable 2FA') }}
                </x-danger-button>
            </form>
        @else
            <div class="text-gray-600 mb-4 text-sm">
                You have not enabled two-factor authentication.
            </div>
            <a href="{{ route('two-factor.enable') }}">
                <x-primary-button>
                    {{ __('Enable 2FA') }}
                </x-primary-button>
            </a>
        @endif
        
         @if (session('status') === 'two-factor-enabled')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="mt-4 text-sm text-green-600 font-semibold"
            >{{ __('Two-factor authentication enabled successfully.') }}</p>
        @endif
        
         @if (session('status') === 'two-factor-disabled')
            <p
                x-data="{ show: true }"
                x-show="show"
                x-transition
                x-init="setTimeout(() => show = false, 2000)"
                class="mt-4 text-sm text-gray-600"
            >{{ __('Two-factor authentication disabled.') }}</p>
        @endif
    </div>
</section>
