<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('put')

        <div class="space-y-4">
            {{-- Current Password --}}
            <div>
                <x-input-label for="update_password_current_password" :value="__('Current Password')" class="text-gray-700 font-semibold" />
                <x-text-input id="update_password_current_password" name="current_password" type="password"
                              class="mt-1 block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"
                              autocomplete="current-password" placeholder="Enter your current password" />
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </div>

            {{-- New Password --}}
            <div>
                <x-input-label for="update_password_password" :value="__('New Password')" class="text-gray-700 font-semibold" />
                <x-text-input id="update_password_password" name="password" type="password"
                              class="mt-1 block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"
                              autocomplete="new-password" placeholder="Enter new password (min 8 characters)" />
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                <p class="text-xs text-gray-500 mt-1">Password must be at least 8 characters long.</p>
            </div>

            {{-- Confirm Password --}}
            <div>
                <x-input-label for="update_password_password_confirmation" :value="__('Confirm New Password')" class="text-gray-700 font-semibold" />
                <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password"
                              class="mt-1 block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"
                              autocomplete="new-password" placeholder="Confirm your new password" />
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center gap-4 pt-2">
            <button type="submit"
                    class="inline-flex items-center px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-semibold rounded-xl transition-all shadow-lg">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
                {{ __('Update Password') }}
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="flex items-center text-sm text-green-600 font-medium">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                    </svg>
                    {{ __('Password updated!') }}
                </p>
            @endif
        </div>
    </form>
</section>
