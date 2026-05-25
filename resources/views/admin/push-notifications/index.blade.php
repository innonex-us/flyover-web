<x-admin-layout title="Push Notifications">

    <div class="p-6 max-w-5xl mx-auto">

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Push Notifications</h1>
            <p class="text-sm text-gray-500 mt-1">Send browser push notifications to all subscribers for offers, news, and announcements.</p>
        </div>

        {{-- Stats --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-5 mb-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($total) }}</p>
                <p class="text-sm text-gray-500">Active subscribers</p>
            </div>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-medium">
            {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="mb-4 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium">
            {{ session('error') }}
        </div>
        @endif

        {{-- Compose Form --}}
        <div class="bg-white rounded-2xl border border-gray-200 p-6">
            <h2 class="text-base font-semibold text-gray-800 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                </svg>
                Compose Notification
            </h2>

            <form method="POST" action="{{ route('admin.push-notifications.send') }}">
                @csrf

                {{-- Title --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Title <span class="text-red-500">*</span></label>
                    <input type="text" name="title" value="{{ old('title') }}" maxlength="80" required
                           placeholder="e.g. 🎉 Special Offer — 20% Off Tours!"
                           class="w-full px-4 py-2.5 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-red-100 {{ $errors->has('title') ? 'border border-red-400 focus:border-red-400' : 'border border-gray-200 focus:border-red-400' }}">
                    @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Body --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Message <span class="text-red-500">*</span></label>
                    <textarea name="body" maxlength="200" required rows="3"
                              placeholder="Short notification message visible in browser…"
                              class="w-full px-4 py-2.5 rounded-xl text-sm resize-none focus:outline-none focus:ring-2 focus:ring-red-100 {{ $errors->has('body') ? 'border border-red-400 focus:border-red-400' : 'border border-gray-200 focus:border-red-400' }}">{{ old('body') }}</textarea>
                    @error('body')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- URL --}}
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Click URL <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="url" name="url" value="{{ old('url') }}"
                           placeholder="{{ config('app.url') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100">
                    <p class="text-xs text-gray-400 mt-1">Where to send user when they click. Defaults to site root.</p>
                </div>

                {{-- Icon --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Icon URL <span class="text-gray-400 font-normal">(optional)</span></label>
                    <input type="url" name="icon" value="{{ old('icon') }}"
                           placeholder="{{ asset('images/logo.png') }}"
                           class="w-full px-4 py-2.5 rounded-xl border border-gray-200 text-sm focus:outline-none focus:border-red-400 focus:ring-2 focus:ring-red-100">
                </div>

                {{-- Preview --}}
                <div class="mb-6 p-4 bg-gray-50 border border-dashed border-gray-200 rounded-xl" x-data="{
                    title: '{{ old('title', 'Notification Title') }}',
                    body: '{{ old('body', 'Your message will appear here…') }}'
                }">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Live Preview</p>
                    <div class="flex items-start gap-3 bg-white rounded-xl border border-gray-100 shadow-sm p-3">
                        <img src="{{ asset('images/logo.png') }}" class="w-10 h-10 rounded-lg object-cover flex-shrink-0" onerror="this.style.display='none'">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-900 truncate" x-text="title || 'Notification Title'"></p>
                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2" x-text="body || 'Message…'"></p>
                            <p class="text-[10px] text-gray-400 mt-1">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</p>
                        </div>
                    </div>
                    <input type="text" class="hidden" @input="title = $event.target.value"
                           x-init="$nextTick(()=>{ document.querySelector('[name=title]').addEventListener('input', e => title=e.target.value); document.querySelector('[name=body]').addEventListener('input', e => body=e.target.value); })">
                </div>

                <button type="submit"
                        @if($total === 0) disabled @endif
                        class="w-full bg-red-600 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold py-3 rounded-xl text-sm transition flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    Send to {{ number_format($total) }} Subscriber{{ $total !== 1 ? 's' : '' }}
                </button>

                @if($total === 0)
                <p class="text-xs text-center text-gray-400 mt-2">No subscribers yet. Users need to allow notifications on the site.</p>
                @endif
            </form>
        </div>

        <div class="mt-6 bg-white rounded-2xl border border-gray-200 p-6">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div>
                    <h2 class="text-base font-semibold text-gray-800">Registered Subscriptions</h2>
                    <p class="text-sm text-gray-500 mt-1">Stored browser endpoints that can receive push notifications.</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-gray-900">{{ number_format($total) }}</p>
                    <p class="text-xs uppercase tracking-wide text-gray-400">Active</p>
                </div>
            </div>

            @if($subscriptions->isEmpty())
                <div class="text-sm text-gray-500 bg-gray-50 border border-dashed border-gray-200 rounded-xl p-4">
                    No stored push subscriptions yet.
                </div>
            @else
                <div class="overflow-hidden rounded-xl border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Browser</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Endpoint</th>
                                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">Added</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @foreach($subscriptions as $subscription)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-4 py-4 align-top">
                                            <div class="flex items-start gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-red-50 flex items-center justify-center flex-shrink-0">
                                                    <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $subscription->user_agent ?? 'Unknown browser' }}</p>
                                                    <p class="text-xs text-gray-400 mt-0.5 truncate">{{ $subscription->endpoint_hash }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 align-top">
                                            <p class="text-xs text-gray-600 break-all leading-relaxed">{{ $subscription->endpoint }}</p>
                                        </td>
                                        <td class="px-4 py-4 align-top whitespace-nowrap">
                                            <p class="text-sm text-gray-700">{{ $subscription->created_at->format('M d, Y') }}</p>
                                            <p class="text-xs text-gray-400">{{ $subscription->created_at->diffForHumans() }}</p>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

    </div>

</x-admin-layout>
