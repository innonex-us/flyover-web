<section>
    <div class="flex items-start gap-4">
        <div class="flex-1">
            <p class="text-sm text-gray-600 leading-relaxed">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. This includes your bookings, preferences, and any associated records. This action cannot be undone.') }}
            </p>
        </div>
    </div>

    <div class="mt-4">
        <button type="button"
                x-data=""
                x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
                class="inline-flex items-center px-5 py-2.5 bg-red-50 text-red-600 hover:bg-red-100 font-semibold rounded-xl transition-all border border-red-200">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            {{ __('Delete My Account') }}
        </button>
    </div>

    {{-- Delete Confirmation Modal --}}
    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">
                        {{ __('Delete Account?') }}
                    </h2>
                    <p class="text-sm text-gray-500">This action is permanent and cannot be undone.</p>
                </div>
            </div>

            <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-4">
                <p class="text-sm text-red-700">
                    {{ __('Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>
            </div>

            <div class="mb-4">
                <x-input-label for="password" value="{{ __('Current Password') }}" class="text-gray-700 font-semibold" />
                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"
                    placeholder="{{ __('Enter your password to confirm') }}"
                    required
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')"
                        class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 font-medium rounded-xl transition">
                    {{ __('Cancel') }}
                </button>

                <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition shadow-lg">
                    {{ __('Yes, Delete Account') }}
                </button>
            </div>
        </form>
    </x-modal>
</section>
