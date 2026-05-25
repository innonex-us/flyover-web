<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('patch')

        {{-- Hidden file input for avatar (triggered by the main page's upload button) --}}
        <input type="file" id="avatar-upload" name="avatar" class="hidden" accept="image/*"
               @change="document.querySelector('[x-data]').__x.$data.handleFile($event.target.files[0])">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            {{-- Name --}}
            <div>
                <x-input-label for="name" :value="__('Full Name')" class="text-gray-700 font-semibold" />
                <x-text-input id="name" name="name" type="text"
                              class="mt-1 block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"
                              :value="old('name', $user->name)" required autofocus autocomplete="name" />
                <x-input-error class="mt-2" :messages="$errors->get('name')" />
            </div>

            {{-- Email --}}
            <div>
                <x-input-label for="email" :value="__('Email Address')" class="text-gray-700 font-semibold" />
                <div class="relative">
                    <x-text-input id="email" name="email" type="email"
                                  class="mt-1 block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 {{ $user->email_verified_at ? 'pr-10' : '' }}"
                                  :value="old('email', $user->email)" required autocomplete="username" />
                    @if($user->email_verified_at)
                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-green-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    @endif
                </div>
                <x-input-error class="mt-2" :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <div class="mt-2 p-3 bg-yellow-50 border border-yellow-200 rounded-lg">
                        <p class="text-sm text-yellow-800">
                            {{ __('Your email address is unverified.') }}
                            <button form="send-verification" class="underline font-medium hover:text-yellow-900">
                                {{ __('Re-send verification email') }}
                            </button>
                        </p>
                        @if (session('status') === 'verification-link-sent')
                            <p class="mt-1 text-sm text-green-600 font-medium">
                                {{ __('Verification link sent!') }}
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Phone --}}
            <div>
                <x-input-label for="phone" :value="__('Phone Number')" class="text-gray-700 font-semibold" />
                <x-text-input id="phone" name="phone" type="tel"
                              class="mt-1 block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500"
                              :value="old('phone', $user->phone)" placeholder="+880 1XXX-XXXXXX" autocomplete="tel" />
                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
            </div>

            {{-- Timezone --}}
            <div>
                <x-input-label for="timezone" :value="__('Timezone')" class="text-gray-700 font-semibold" />
                <select id="timezone" name="timezone"
                        class="mt-1 block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500">
                    @foreach($timezones ?? [] as $value => $label)
                        <option value="{{ $value }}" {{ old('timezone', $user->timezone) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('timezone')" />
            </div>

            {{-- Language --}}
            <div>
                <x-input-label for="language" :value="__('Language')" class="text-gray-700 font-semibold" />
                <select id="language" name="language"
                        class="mt-1 block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500">
                    @foreach($languages ?? [] as $value => $label)
                        <option value="{{ $value }}" {{ old('language', $user->language) === $value ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
                <x-input-error class="mt-2" :messages="$errors->get('language')" />
            </div>

            {{-- Role (Read-only) --}}
            <div>
                <x-input-label :value="__('Role')" class="text-gray-700 font-semibold" />
                <div class="mt-1 px-4 py-2 bg-gray-100 rounded-xl text-gray-700 font-medium">
                    {{ ucfirst($user->role) }}
                </div>
                <p class="text-xs text-gray-500 mt-1">Role can only be changed by an administrator.</p>
            </div>
        </div>

        {{-- Bio --}}
        <div>
            <x-input-label for="bio" :value="__('Bio / About')" class="text-gray-700 font-semibold" />
            <textarea id="bio" name="bio" rows="3"
                      class="mt-1 block w-full rounded-xl border-gray-300 focus:border-red-500 focus:ring-red-500 resize-none"
                      placeholder="Tell us a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
            <div class="flex justify-between mt-1">
                <x-input-error :messages="$errors->get('bio')" />
                <p class="text-xs text-gray-400">Max 500 characters</p>
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
            <div class="flex items-center gap-4">
                <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 text-white font-semibold rounded-xl transition-all shadow-lg hover:opacity-90"
                        style="background:#C8102E;">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    {{ __('Save Changes') }}
                </button>

                @if (session('status') === 'profile-updated')
                    <p x-data="{ show: true }"
                       x-show="show"
                       x-transition
                       x-init="setTimeout(() => show = false, 3000)"
                       class="flex items-center text-sm text-green-600 font-medium">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                        </svg>
                        {{ __('Saved successfully!') }}
                    </p>
                @endif
            </div>

            <p class="text-sm text-gray-500">
                Member since {{ $user->created_at->format('M Y') }}
            </p>
        </div>
    </form>
</section>
