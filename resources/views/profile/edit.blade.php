@if(request()->routeIs('admin.profile.edit') || (auth()->user() && auth()->user()->role === 'admin'))
    <x-admin-layout>
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Profile Settings</h2>
            <p class="text-sm text-gray-500 mt-1">Manage your account information and password</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="p-6 bg-white shadow-sm rounded-xl border border-gray-100">
                @include('profile.partials.update-profile-information-form')
            </div>

            <div class="p-6 bg-white shadow-sm rounded-xl border border-gray-100">
                @include('profile.partials.update-password-form')
            </div>
            
            <div class="md:col-span-2 p-6 bg-white shadow-sm rounded-xl border border-blue-100">
                @include('profile.partials.two-factor-form')
            </div>

            <div class="lg:col-span-2 p-6 bg-white shadow-sm rounded-xl border border-red-100">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </x-admin-layout>
@else
    <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Profile') }}
            </h2>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </x-app-layout>
@endif
