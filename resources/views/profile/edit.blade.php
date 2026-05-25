@if(request()->routeIs('admin.profile.edit') || (auth()->user() && auth()->user()->role === 'admin'))
<x-admin-layout pageTitle="Profile Settings">

@push('styles')
<style>
    .s-input {
        width: 100%; padding: 0.625rem 1rem; background: #f9fafb;
        border: 1px solid #e5e7eb; border-radius: 0.75rem; font-size: 0.875rem;
        transition: all 0.2s ease; color: #111827;
    }
    .s-input:focus { background: #fff; border-color: #dc2626; outline: none; box-shadow: 0 0 0 3px rgba(220,38,38,.08); }
    .s-label { font-size: 0.75rem; font-weight: 700; color: #374151; margin-bottom: 0.375rem; display: block; }
    .s-hint  { font-size: 0.7rem; color: #9ca3af; margin-top: 0.25rem; }
    .s-card  { background:#fff; border-radius:1rem; border:1px solid #f3f4f6; box-shadow:0 1px 3px rgba(0,0,0,.05); }
    .s-section-title { font-size:0.6rem; font-weight:800; text-transform:uppercase; letter-spacing:.1em; color:#9ca3af; margin-bottom:1rem; display:flex; align-items:center; gap:.5rem; }
    .s-section-title::after { content:""; flex:1; height:1px; background:#f3f4f6; }
    .s-avatar { width: 96px; height: 96px; border-radius: 50%; object-fit: cover; border: 3px solid #fff; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); }
    .s-avatar-overlay { position: absolute; inset: 0; background: rgba(0,0,0,0.4); border-radius: 50%; opacity: 0; transition: opacity 0.2s; display: flex; align-items: center; justify-content: center; cursor: pointer; }
    .s-avatar-wrap:hover .s-avatar-overlay { opacity: 1; }
    .nav-tab { display:flex; align-items:center; gap:.625rem; padding:.625rem .875rem; border-radius:.75rem; font-size:.8rem; font-weight:600; color:#6b7280; transition:all .15s; width:100%; text-align:left; }
    .nav-tab:hover { background:#f9fafb; color:#111827; }
    .nav-tab.active { background:#fef2f2; color:#dc2626; }
    .nav-tab.active svg { color:#dc2626; }
    .nav-tab svg { width:16px; height:16px; color:#9ca3af; flex-shrink:0; }
</style>
@endpush

<div class="max-w-6xl mx-auto" x-data="{ tab: 'general' }">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">Profile Settings</h1>
            <p class="text-sm text-gray-400 mt-0.5">Manage your account information and security</p>
        </div>
    </div>

    @if(session('success') || session('status'))
    <div class="mb-6 flex items-center gap-3 px-5 py-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-sm font-semibold text-emerald-700">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') ?? session('status') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- Sidebar nav --}}
        <div class="lg:col-span-1" x-data="{
            preview: '{{ $user->avatar_url }}',
            original: '{{ $user->avatar_url }}',
            hasFile: false,
            handleFile(file) {
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.preview = e.target.result;
                        this.hasFile = true;
                    };
                    reader.readAsDataURL(file);
                }
            },
            cancelFile() {
                this.preview = this.original;
                this.hasFile = false;
                document.getElementById('avatar-upload').value = '';
            }
        }">
            <div class="s-card p-3 sticky top-24">
                {{-- Avatar section --}}
                <div class="text-center mb-4 pb-4 border-b border-gray-100">
                    <div class="s-avatar-wrap relative inline-block mb-3">
                        <img :src="preview" alt="{{ $user->name }}" class="s-avatar" :class="hasFile ? 'ring-2 ring-red-500 ring-offset-2' : ''">
                        <label for="avatar-upload" class="s-avatar-overlay" title="Change photo">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </label>
                        {{-- Cancel new selection --}}
                        <button type="button"
                                x-show="hasFile"
                                @click.prevent="cancelFile()"
                                class="absolute -bottom-1 -right-1 w-7 h-7 bg-red-500 text-white rounded-full shadow-md flex items-center justify-center hover:bg-red-600 transition border-2 border-white"
                                title="Cancel selection">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                        {{-- Delete existing avatar --}}
                        @if($user->avatar)
                        <button type="button"
                                x-show="!hasFile"
                                onclick="event.preventDefault(); if(confirm('Remove profile photo?')) document.getElementById('delete-avatar-form').submit();"
                                class="absolute -bottom-1 -right-1 w-7 h-7 bg-white text-red-500 rounded-full shadow-md flex items-center justify-center hover:bg-red-50 transition border border-gray-100"
                                title="Remove photo">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                        @endif
                    </div>
                </div>
                <div class="text-center mb-3" x-show="hasFile" x-cloak>
                    <p class="text-xs text-red-600 font-medium">New photo selected - save to apply</p>
                </div>
                <div class="text-center mb-4">
                    <h3 class="font-bold text-gray-900">{{ $user->name }}</h3>
                    <p class="text-xs text-gray-500">{{ $user->email }}</p>
                    <span class="inline-block mt-2 px-2 py-0.5 bg-red-50 text-red-600 text-xs font-semibold rounded-full">{{ ucfirst($user->role) }}</span>
                </div>

                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2 mb-2">Settings</p>
                <nav class="space-y-0.5">
                    <button @click="tab = 'general'" class="nav-tab" :class="tab === 'general' ? 'active' : ''">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        General
                    </button>
                    <button @click="tab = 'security'" class="nav-tab" :class="tab === 'security' ? 'active' : ''">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Security
                    </button>
                    <button @click="tab = '2fa'" class="nav-tab" :class="tab === '2fa' ? 'active' : ''">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Two-Factor Auth
                    </button>
                    <button @click="tab = 'danger'" class="nav-tab" :class="tab === 'danger' ? 'active' : ''">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        Danger Zone
                    </button>
                </nav>
            </div>
        </div>

        {{-- Form panels --}}
        <div class="lg:col-span-3 space-y-6">

            {{-- ── GENERAL TAB ── --}}
            <div x-show="tab === 'general'" x-cloak>
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <input type="file" id="avatar-upload" name="avatar" class="hidden" accept="image/*" @change="const file = $event.target.files[0]; if(file) { handleFile(file); }">

                    <div class="s-card p-6 sm:p-8">
                        <p class="s-section-title">Personal Information</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <div>
                                <label class="s-label">Full Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="s-input" required>
                                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="s-label">Email Address</label>
                                <div class="relative">
                                    <input type="email" name="email" value="{{ old('email', $user->email) }}" class="s-input {{ $user->email_verified_at ? 'pr-10' : '' }}" required>
                                    @if($user->email_verified_at)
                                        <div class="absolute right-3 top-1/2 -translate-y-1/2 text-green-500">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        </div>
                                    @endif
                                </div>
                                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                @if (!$user->email_verified_at)
                                    <p class="s-hint text-amber-600">Email not verified. <button type="submit" formaction="{{ route('verification.send') }}" class="underline">Resend</button></p>
                                @endif
                            </div>
                            <div>
                                <label class="s-label">Phone Number</label>
                                <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}" class="s-input" placeholder="+880 1XXX-XXXXXX">
                                @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="s-label">Role</label>
                                <div class="s-input bg-gray-100 cursor-not-allowed">{{ ucfirst($user->role) }}</div>
                                <p class="s-hint">Role can only be changed by an administrator.</p>
                            </div>
                            <div>
                                <label class="s-label">Timezone</label>
                                <select name="timezone" class="s-input">
                                    @foreach($timezones ?? [] as $value => $label)
                                        <option value="{{ $value }}" {{ old('timezone', $user->timezone) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('timezone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="s-label">Language</label>
                                <select name="language" class="s-input">
                                    @foreach($languages ?? [] as $value => $label)
                                        <option value="{{ $value }}" {{ old('language', $user->language) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('language')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <p class="s-section-title">About</p>
                        <div class="mb-2">
                            <label class="s-label">Bio</label>
                            <textarea name="bio" rows="3" class="s-input" style="resize:vertical;" placeholder="Tell us a little about yourself...">{{ old('bio', $user->bio) }}</textarea>
                            <p class="s-hint">Max 500 characters. This will be visible on your public profile.</p>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-7 py-2.5 rounded-xl shadow-sm transition text-sm">Save Changes</button>
                    </div>
                </form>
            </div>

            {{-- ── SECURITY TAB ── --}}
            <div x-show="tab === 'security'" x-cloak>
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')
                    <div class="s-card p-6 sm:p-8">
                        <p class="s-section-title">Change Password</p>
                        <div class="space-y-5 max-w-md">
                            <div>
                                <label class="s-label">Current Password</label>
                                <input type="password" name="current_password" class="s-input" placeholder="Enter your current password" required>
                                @error('current_password', 'updatePassword')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label class="s-label">New Password</label>
                                <input type="password" name="password" class="s-input" placeholder="Min 8 characters" required>
                                @error('password', 'updatePassword')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                <p class="s-hint">Password must be at least 8 characters long.</p>
                            </div>
                            <div>
                                <label class="s-label">Confirm New Password</label>
                                <input type="password" name="password_confirmation" class="s-input" placeholder="Re-enter new password" required>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-end mt-4">
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-7 py-2.5 rounded-xl shadow-sm transition text-sm">Update Password</button>
                    </div>
                </form>
            </div>

            {{-- ── 2FA TAB ── --}}
            <div x-show="tab === '2fa'" x-cloak>
                <div class="s-card p-6 sm:p-8">
                    <p class="s-section-title">Two-Factor Authentication</p>

                    @if($user->google2fa_secret)
                        <div class="flex items-center gap-4 p-4 bg-green-50 border border-green-200 rounded-xl mb-6">
                            <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-green-800">2FA is Enabled</h4>
                                <p class="text-sm text-green-600">Your account is protected with an additional layer of security.</p>
                            </div>
                            <span class="px-3 py-1 bg-green-200 text-green-800 text-xs font-bold rounded-full">Active</span>
                        </div>

                        <form method="POST" action="{{ route('two-factor.disable') }}" onsubmit="return confirm('Disable 2FA? This will make your account less secure.');">
                            @csrf
                            @method('DELETE')
                            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200">
                                <p class="text-sm text-gray-600 mb-4">To disable two-factor authentication, please enter your current password.</p>
                                <div class="flex flex-col sm:flex-row gap-3">
                                    <input type="password" name="password" class="s-input flex-1" placeholder="Current password" required>
                                    <button type="submit" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold px-5 py-2.5 rounded-xl transition border border-red-200">Disable 2FA</button>
                                </div>
                                @error('password')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                            </div>
                        </form>
                    @else
                        <div class="flex items-center gap-4 p-4 bg-amber-50 border border-amber-200 rounded-xl mb-6">
                            <div class="w-12 h-12 rounded-full bg-amber-100 flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-semibold text-amber-800">2FA is Disabled</h4>
                                <p class="text-sm text-amber-600">Enable 2FA to add an extra layer of security to your account.</p>
                            </div>
                            <span class="px-3 py-1 bg-amber-200 text-amber-800 text-xs font-bold rounded-full">Inactive</span>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border border-gray-200">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm text-gray-600">Protect your account with authenticator app verification</p>
                            </div>
                            <a href="{{ route('two-factor.enable') }}" class="bg-red-600 hover:bg-red-700 text-white font-semibold px-5 py-2 rounded-xl transition shadow-sm text-sm">Enable 2FA</a>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ── DANGER ZONE TAB ── --}}
            <div x-show="tab === 'danger'" x-cloak>
                <div class="s-card p-6 sm:p-8 border-red-200">
                    <p class="s-section-title" style="color:#dc2626;">Delete Account</p>
                    <p class="text-sm text-gray-600 mb-4">Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.</p>
                    <button type="button" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')" class="bg-red-50 hover:bg-red-100 text-red-600 font-semibold px-5 py-2.5 rounded-xl transition border border-red-200">Delete My Account</button>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Delete Account Modal --}}
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
                <h2 class="text-lg font-semibold text-gray-900">Delete Account?</h2>
                <p class="text-sm text-gray-500">This action is permanent and cannot be undone.</p>
            </div>
        </div>
        <div class="bg-red-50 border border-red-100 rounded-xl p-4 mb-4">
            <p class="text-sm text-red-700">Please enter your password to confirm you would like to permanently delete your account.</p>
        </div>
        <div class="mb-4">
            <label class="s-label">Current Password</label>
            <input type="password" name="password" class="s-input" placeholder="Enter your password" required>
            @error('password', 'userDeletion')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex justify-end gap-3">
            <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-gray-100 text-gray-700 hover:bg-gray-200 font-medium rounded-xl transition">Cancel</button>
            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition">Yes, Delete Account</button>
        </div>
    </form>
</x-modal>

{{-- Hidden Avatar Delete Form --}}
<form id="delete-avatar-form" action="{{ route('profile.avatar.delete') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

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
