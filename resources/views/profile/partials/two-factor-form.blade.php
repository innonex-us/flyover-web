<section>
    <div class="space-y-5">
        @if(auth()->user()->google2fa_secret)
            {{-- 2FA Enabled State --}}
            <div class="flex items-center gap-4 p-4 bg-green-50 border border-green-100 rounded-xl">
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-green-800">Two-Factor Authentication is Enabled</h4>
                    <p class="text-sm text-green-600">Your account is protected with an additional layer of security.</p>
                </div>
                <span class="px-3 py-1 bg-green-200 text-green-800 text-xs font-semibold rounded-full">Active</span>
            </div>

            {{-- Disable 2FA Form --}}
            <form method="POST" action="{{ route('two-factor.disable') }}"
                  onsubmit="return confirm('Are you sure you want to disable 2FA? This will make your account less secure.');"
                  class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                <p class="text-sm text-gray-600 mb-4">
                    To disable two-factor authentication, please enter your current password for security verification.
                </p>

                @csrf
                @method('DELETE')

                <div class="flex flex-col sm:flex-row gap-3">
                    <x-text-input id="password" name="password" type="password"
                                  class="flex-1 rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"
                                  placeholder="Enter your current password" required />

                    <button type="submit"
                            class="inline-flex items-center justify-center px-5 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 font-semibold rounded-xl transition-all border border-red-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        {{ __('Disable 2FA') }}
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </form>
        @else
            {{-- 2FA Disabled State --}}
            <div class="flex items-center gap-4 p-4 bg-amber-50 border border-amber-100 rounded-xl">
                <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-amber-800">Two-Factor Authentication is Disabled</h4>
                    <p class="text-sm text-amber-600">Enable 2FA to add an extra layer of security to your account.</p>
                </div>
                <span class="px-3 py-1 bg-amber-200 text-amber-800 text-xs font-semibold rounded-full">Inactive</span>
            </div>

            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-gray-600">Protect your account with authenticator app verification</p>
                </div>
                <a href="{{ route('two-factor.enable') }}">
                    <button type="button"
                            class="inline-flex items-center px-5 py-2.5 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-xl transition-all shadow-lg">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        {{ __('Enable 2FA') }}
                    </button>
                </a>
            </div>
        @endif

        {{-- Status Messages --}}
        @if (session('status') === 'two-factor-enabled')
            <div x-data="{ show: true }"
                 x-show="show"
                 x-transition
                 x-init="setTimeout(() => show = false, 5000)"
                 class="flex items-center gap-2 p-3 bg-green-100 text-green-700 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ __('Two-factor authentication enabled successfully!') }}</span>
            </div>
        @endif

        @if (session('status') === 'two-factor-disabled')
            <div x-data="{ show: true }"
                 x-show="show"
                 x-transition
                 x-init="setTimeout(() => show = false, 5000)"
                 class="flex items-center gap-2 p-3 bg-gray-100 text-gray-700 rounded-xl">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium">{{ __('Two-factor authentication has been disabled.') }}</span>
            </div>
        @endif
    </div>
</section>
