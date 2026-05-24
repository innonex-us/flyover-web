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
