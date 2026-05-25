<x-admin-layout pageTitle="Push Notifications">

@push('styles')
    <style>
        .push-card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.05), 0 10px 15px -5px rgba(0,0,0,.02);
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }
        .browser-preview {
            background: #fff;
            border-radius: 0.75rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,.1), 0 2px 4px -1px rgba(0,0,0,.06);
            border: 1px solid #e5e7eb;
            overflow: hidden;
            width: 100%;
            max-width: 320px;
        }
        .browser-preview-header {
            background: #f9fafb;
            padding: 8px 12px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .dot { width: 4px; height: 4px; border-radius: 50%; background: #d1d5db; }
        
        .btn-brand {
            background: #dc2626;
            color: #fff;
        }
        .btn-brand:hover {
            background: #b91c1c;
        }
    </style>
@endpush

<div class="space-y-4 sm:space-y-8 max-w-6xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 sm:gap-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Broadcast Center</h1>
            <p class="text-sm text-gray-500 mt-1">Deploy browser-level push notifications to your active audience.</p>
        </div>
        
        <div class="flex items-center gap-4 bg-white px-4 sm:px-6 py-3 rounded-2xl border border-gray-100 shadow-sm">
            <div class="w-10 h-10 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div>
                <p class="text-lg sm:text-xl font-black text-gray-900 leading-none">{{ number_format($total) }}</p>
                <p class="text-[9px] sm:text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Active Subs</p>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-100 text-emerald-800 px-6 py-4 rounded-2xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 sm:gap-8">
        {{-- Compose Section --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="push-card p-4 sm:p-8">
                <div class="flex items-center gap-3 mb-6 sm:mb-8">
                    <div class="w-8 h-8 rounded-lg bg-red-600 flex items-center justify-center text-white">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                    </div>
                    <h2 class="text-lg font-bold text-gray-900">Compose Notification</h2>
                </div>

                <form method="POST" action="{{ route('admin.push-notifications.send') }}" class="space-y-4 sm:space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Headline</label>
                            <input type="text" name="title" value="{{ old('title') }}" maxlength="80" required
                                   placeholder="Enter alert title..."
                                   class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm focus:ring-red-100 focus:border-red-400 transition">
                            @error('title')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Redirect URL</label>
                            <input type="url" name="url" value="{{ old('url') }}"
                                   placeholder="{{ config('app.url') }}"
                                   class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm focus:ring-red-100 focus:border-red-400 transition">
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Message Content</label>
                        <textarea name="body" maxlength="200" required rows="4"
                                  placeholder="Describe your offer or announcement in 200 characters..."
                                  class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm resize-none focus:ring-red-100 focus:border-red-400 transition">{{ old('body') }}</textarea>
                        @error('body')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Custom Icon URL (Optional)</label>
                        <input type="url" name="icon" value="{{ old('icon') }}"
                               placeholder="https://example.com/icon.png"
                               class="w-full px-4 py-3 rounded-xl border-gray-100 bg-gray-50 text-sm focus:ring-red-100 focus:border-red-400 transition">
                    </div>

                    <button type="submit" @if($total === 0) disabled @endif
                            class="w-full btn-brand py-3.5 sm:py-4 rounded-xl font-bold shadow-lg shadow-red-900/10 transition-all flex items-center justify-center gap-3 disabled:opacity-50 disabled:grayscale">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Transmit to {{ number_format($total) }} Device{{ $total !== 1 ? 's' : '' }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Preview Section --}}
        <div class="space-y-6 order-first lg:order-none">
            <div class="push-card p-6 bg-gray-50 border border-gray-200">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-6">Real-time Preview</p>
                
                <div class="flex justify-center py-4 sm:py-8" x-data="{
                    title: '{{ old('title', 'Summer Special Offer!') }}',
                    body: '{{ old('body', 'Get 20% off on all Thailand tour packages this month.') }}',
                    init() {
                        document.querySelector('[name=title]').addEventListener('input', e => this.title = e.target.value);
                        document.querySelector('[name=body]').addEventListener('input', e => this.body = e.target.value);
                    }
                }">
                    <div class="browser-preview">
                        <div class="browser-preview-header">
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <div class="dot"></div>
                            <span class="text-[9px] text-gray-400 font-bold ml-2 uppercase">Browser Notification</span>
                        </div>
                        <div class="p-4 flex items-start gap-3 bg-white text-gray-900">
                            <div class="w-12 h-12 bg-gray-50 rounded-lg flex-shrink-0 flex items-center justify-center border border-gray-100">
                                <img src="{{ asset('logo.png') }}" class="w-8 h-auto opacity-80" alt="Logo">
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold truncate" x-text="title || 'Notification Title'"></p>
                                <p class="text-[11px] text-gray-500 leading-tight mt-1 line-clamp-2" x-text="body || 'Your message will appear here...'"></p>
                                <div class="flex items-center justify-between mt-2">
                                    <span class="text-[9px] text-gray-400 font-bold uppercase tracking-tighter">{{ parse_url(config('app.url'), PHP_URL_HOST) }}</span>
                                    <span class="text-[9px] text-gray-300">just now</span>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-2 border-t border-gray-100 flex justify-end">
                            <div class="text-[10px] font-bold text-red-600 uppercase tracking-widest">Settings</div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 pt-6 border-t border-gray-200 space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        <p class="text-xs text-gray-500 font-medium">Global Delivery Enabled</p>
                    </div>
                    <p class="text-[10px] text-gray-400 leading-relaxed italic">
                        Note: Performance may vary based on user settings and network conditions.
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Subscriptions Table --}}
    <div class="push-card overflow-hidden">
        <div class="px-8 py-6 border-b border-gray-50 flex items-center justify-between bg-gray-50/50">
            <div>
                <h3 class="font-bold text-gray-900">Registered Endpoints</h3>
                <p class="text-[11px] text-gray-500 mt-0.5">Inventory of authorized browser tokens for this application.</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead>
                    <tr class="bg-gray-50/30 border-b border-gray-50">
                        <th class="px-8 py-3 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Client / Browser</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Endpoint Hash</th>
                        <th class="px-8 py-4 text-left text-[10px] font-bold uppercase tracking-widest text-gray-400">Subscription Date</th>
                        <th class="px-8 py-4 text-center text-[10px] font-bold uppercase tracking-widest text-gray-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($subscriptions as $sub)
                    <tr class="hover:bg-gray-50/50 transition duration-150">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-gray-800">{{ Str::limit($sub->user_agent ?? 'Unknown Client', 30) }}</p>
                                    <p class="text-[10px] text-gray-400 font-mono">{{ substr($sub->endpoint, 0, 40) }}...</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <code class="text-[10px] bg-gray-50 px-2 py-1 rounded border border-gray-100">{{ $sub->endpoint_hash }}</code>
                        </td>
                        <td class="px-8 py-5">
                            <div class="text-sm font-medium text-gray-700">{{ $sub->created_at->format('M d, Y') }}</div>
                            <div class="text-[10px] text-gray-400">{{ $sub->created_at->diffForHumans() }}</div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-100 uppercase tracking-tighter">Verified</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="max-w-xs mx-auto">
                                <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                                </div>
                                <p class="text-sm font-bold text-gray-900">No active subscribers</p>
                                <p class="text-xs text-gray-500 mt-1">Push notifications require user consent via browser permissions.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</x-admin-layout>
