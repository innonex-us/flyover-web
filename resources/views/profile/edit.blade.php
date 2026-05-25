@if(request()->routeIs('admin.profile.edit') || (auth()->user() && auth()->user()->role === 'admin'))
    <x-admin-layout pageTitle="Profile Settings">
        {{-- Profile Header with Avatar --}}
        <div class="mb-8 rounded-2xl p-6 sm:p-8 text-white shadow-xl" style="background:#C8102E;"
             x-data="{
                 avatarPreview: '{{ $user->avatar_url }}',
                 isDragging: false,
                 handleFile(file) {
                     if (file && file.type.startsWith('image/')) {
                         const reader = new FileReader();
                         reader.onload = (e) => this.avatarPreview = e.target.result;
                         reader.readAsDataURL(file);
                     }
                 }
             }"
             @dragover.prevent="isDragging = true"
             @dragleave.prevent="isDragging = false"
             @drop.prevent="isDragging = false; handleFile($event.dataTransfer.files[0])">

            <div class="flex flex-col sm:flex-row items-center gap-6">
                {{-- Avatar Upload --}}
                <div class="relative group">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-full overflow-hidden border-4 border-white shadow-lg bg-white"
                         :class="isDragging ? 'ring-4 ring-white/70' : ''">
                        <img :src="avatarPreview" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    </div>

                    {{-- Upload Overlay --}}
                    <label for="avatar-upload"
                           class="absolute inset-0 rounded-full bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center cursor-pointer">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </label>

                    @if($user->avatar)
                        <button type="button"
                                onclick="event.preventDefault(); document.getElementById('delete-avatar-form').submit();"
                                class="absolute -bottom-1 -right-1 w-8 h-8 bg-white text-red-600 rounded-full shadow-md flex items-center justify-center hover:bg-red-50 transition"
                                title="Remove photo">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    @endif
                </div>

                {{-- User Info --}}
                <div class="text-center sm:text-left flex-1">
                    <h1 class="text-2xl sm:text-3xl font-bold">{{ $user->name }}</h1>
                    <p class="text-white/80 mt-1">{{ $user->email }}</p>
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-3">
                        <span class="px-3 py-1 bg-white/20 rounded-full text-sm font-medium backdrop-blur-sm">
                            {{ ucfirst($user->role) }}
                        </span>
                        @if($user->email_verified_at)
                            <span class="px-3 py-1 bg-green-500/30 rounded-full text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Verified
                            </span>
                        @endif
                        @if($user->google2fa_secret)
                            <span class="px-3 py-1 bg-blue-500/30 rounded-full text-sm font-medium flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                2FA Enabled
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Quick Stats --}}
                <div class="flex gap-6 text-center">
                    <div>
                        <p class="text-2xl font-bold">{{ $user->created_at->diffInDays(now()) }}</p>
                        <p class="text-xs text-white/70 uppercase tracking-wide">Days Active</p>
                    </div>
                    <div>
                        <p class="text-2xl font-bold">{{ $user->updated_at->diffForHumans(null, true) }}</p>
                        <p class="text-xs text-white/70 uppercase tracking-wide">Last Updated</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hidden Avatar Delete Form --}}
        <form id="delete-avatar-form" action="{{ route('profile.avatar.delete') }}" method="POST" class="hidden">
            @csrf
            @method('DELETE')
        </form>

        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            {{-- Left Column - Profile Info --}}
            <div class="xl:col-span-2 space-y-6">
                {{-- Profile Information Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile Information
                        </h3>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                {{-- Password Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Security - Password
                        </h3>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Two Factor Card --}}
                <div class="bg-white rounded-2xl shadow-sm border border-blue-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-blue-100 bg-blue-50/30">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Two-Factor Authentication
                        </h3>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.two-factor-form')
                    </div>
                </div>
            </div>

            {{-- Right Column - Security & Account --}}
            <div class="space-y-6">
                {{-- Active Sessions --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Active Sessions
                        </h3>
                    </div>
                    <div class="p-4 space-y-3">
                        @forelse($sessions as $session)
                            <div class="flex items-start gap-3 p-3 rounded-xl {{ $session['is_current'] ? 'bg-green-50 border border-green-100' : 'bg-gray-50' }}">
                                <div class="w-10 h-10 rounded-lg bg-white flex items-center justify-center shadow-sm">
                                    <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ $session['device'] }}
                                        @if($session['is_current'])
                                            <span class="ml-2 px-2 py-0.5 bg-green-100 text-green-700 text-xs rounded-full">Current</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-gray-500 mt-0.5 truncate">{{ $session['browser'] }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $session['ip'] }} • {{ $session['last_active']->diffForHumans() }}</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 text-center py-4">No active sessions found.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Delete Account --}}
                <div class="bg-white rounded-2xl shadow-sm border border-red-100 overflow-hidden">
                    <div class="px-6 py-4 border-b border-red-100 bg-red-50/30">
                        <h3 class="text-lg font-semibold text-red-700 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Danger Zone
                        </h3>
                    </div>
                    <div class="p-6">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </x-admin-layout>
@else
    <x-app-layout>
        <div class="min-h-screen pb-16" style="background:#F9F6EF;">

            {{-- Hero strip --}}
            <section style="background:#F9F6EF;border-bottom:1px solid #E4DCC9;" class="py-10">
                <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                    <p class="section-eyebrow mb-2">Account</p>
                    <h1 class="font-extrabold text-3xl text-gray-900" style="font-family:'Merriweather',Georgia,serif;">Profile Settings</h1>
                    <p class="text-sm mt-1" style="color:#7A7166;">Manage your personal information and security</p>
                </div>
            </section>

            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 space-y-6">

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    @include('profile.partials.update-profile-information-form')
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 sm:p-8">
                    @include('profile.partials.update-password-form')
                </div>

                <div class="bg-white rounded-2xl border border-red-100 shadow-sm p-6 sm:p-8">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>
        </div>
    </x-app-layout>
@endif
