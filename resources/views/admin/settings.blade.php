<x-admin-layout pageTitle="System Settings">

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
    .toggle-wrap { display:flex; align-items:center; gap:.75rem; padding:.75rem 1rem; background:#f9fafb; border-radius:.75rem; border:1px solid #e5e7eb; }
    .toggle { position:relative; display:inline-block; width:40px; height:22px; flex-shrink:0; }
    .toggle input { opacity:0; width:0; height:0; }
    .toggle-slider { position:absolute; cursor:pointer; inset:0; background:#d1d5db; border-radius:22px; transition:.25s; }
    .toggle-slider:before { position:absolute; content:""; height:16px; width:16px; left:3px; bottom:3px; background:#fff; border-radius:50%; transition:.25s; }
    .toggle input:checked + .toggle-slider { background:#dc2626; }
    .toggle input:checked + .toggle-slider:before { transform:translateX(18px); }
    .nav-tab { display:flex; align-items:center; gap:.625rem; padding:.625rem .875rem; border-radius:.75rem; font-size:.8rem; font-weight:600; color:#6b7280; transition:all .15s; width:100%; text-align:left; }
    .nav-tab:hover { background:#f9fafb; color:#111827; }
    .nav-tab.active { background:#fef2f2; color:#dc2626; }
    .nav-tab.active svg { color:#dc2626; }
    .nav-tab svg { width:16px; height:16px; color:#9ca3af; flex-shrink:0; }
</style>
@endpush

@php $g = $group ?? 'general'; @endphp

<div class="max-w-6xl mx-auto">

    {{-- Page header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-black text-gray-900 tracking-tight">System Settings</h1>
            <p class="text-sm text-gray-400 mt-0.5">Configure your application preferences</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 flex items-center gap-3 px-5 py-3.5 bg-emerald-50 border border-emerald-200 rounded-xl text-sm font-semibold text-emerald-700">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        {{ session('success') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

        {{-- Sidebar nav --}}
        <div class="lg:col-span-1">
            <div class="s-card p-3 sticky top-24">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest px-2 mb-2">Configuration</p>
                <nav class="space-y-0.5">
                    @php
                    $tabs = [
                        'general'  => ['label'=>'General',  'icon'=>'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z'],
                        'email'    => ['label'=>'Email',    'icon'=>'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                        'security' => ['label'=>'Security', 'icon'=>'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                        'payment'  => ['label'=>'Payment',  'icon'=>'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'],
                        'social'   => ['label'=>'Social',   'icon'=>'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
                        'stats'    => ['label'=>'Stats',    'icon'=>'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                    ];
                    @endphp
                    @foreach($tabs as $key => $tab)
                    <a href="{{ route('admin.settings.show', $key) }}" class="nav-tab {{ $g === $key ? 'active' : '' }}">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/></svg>
                        {{ $tab['label'] }}
                    </a>
                    @endforeach
                </nav>
            </div>
        </div>

        {{-- Form panel --}}
        <div class="lg:col-span-3">

            {{-- ── GENERAL ── --}}
            @if($g === 'general')
            <form method="POST" action="{{ route('admin.settings.update', 'general') }}">
                @csrf
                <div class="s-card p-6 sm:p-8">
                    <p class="s-section-title">Site Identity</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="s-label">Application Name</label>
                            <input type="text" name="app_name" value="{{ old('app_name', $settings['general']['app_name'] ?? '') }}" class="s-input" placeholder="FlyoverBD">
                        </div>
                        <div>
                            <label class="s-label">Application URL</label>
                            <input type="url" name="app_url" value="{{ old('app_url', $settings['general']['app_url'] ?? '') }}" class="s-input" placeholder="https://flyoverbd.com">
                        </div>
                        <div>
                            <label class="s-label">Contact Email</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings['general']['contact_email'] ?? '') }}" class="s-input" placeholder="info@flyoverbd.com">
                        </div>
                        <div>
                            <label class="s-label">Contact Phone</label>
                            <input type="tel" name="contact_phone" value="{{ old('contact_phone', $settings['general']['contact_phone'] ?? '') }}" class="s-input" placeholder="+8801234567890">
                        </div>
                        <div>
                            <label class="s-label">Default Language</label>
                            <select name="language" class="s-input">
                                @foreach(['en'=>'English','bn'=>'বাংলা','ar'=>'العربية'] as $val => $label)
                                <option value="{{ $val }}" {{ ($settings['general']['language'] ?? 'en') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="s-label">Timezone</label>
                            <select name="timezone" class="s-input">
                                @foreach(['Asia/Dhaka'=>'Asia/Dhaka (UTC+6)','UTC'=>'UTC (UTC+0)','America/New_York'=>'America/New_York (UTC-5)','Asia/Kolkata'=>'Asia/Kolkata (UTC+5:30)'] as $val => $label)
                                <option value="{{ $val }}" {{ ($settings['general']['timezone'] ?? 'Asia/Dhaka') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mb-6">
                        <label class="s-label">Site Description</label>
                        <textarea name="site_description" rows="3" class="s-input" style="resize:vertical;" placeholder="Describe your travel business...">{{ old('site_description', $settings['general']['site_description'] ?? '') }}</textarea>
                    </div>
                    <p class="s-section-title">System</p>
                    <div class="toggle-wrap">
                        <label class="toggle">
                            <input type="hidden" name="maintenance_mode" value="0">
                            <input type="checkbox" name="maintenance_mode" value="1" {{ ($settings['general']['maintenance_mode'] ?? '0') === '1' ? 'checked' : '' }}>
                            <span class="toggle-slider"></span>
                        </label>
                        <div>
                            <p class="text-sm font-semibold text-gray-700">Maintenance Mode</p>
                            <p class="text-xs text-gray-400">Disable public access while you make updates</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-7 py-2.5 rounded-xl shadow-sm transition text-sm">Save General Settings</button>
                </div>
            </form>
            @endif

            {{-- ── EMAIL ── --}}
            @if($g === 'email')
            <form method="POST" action="{{ route('admin.settings.update', 'email') }}">
                @csrf
                <div class="s-card p-6 sm:p-8">
                    <p class="s-section-title">SMTP Configuration</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="s-label">Mail Driver</label>
                            <select name="mail_mailer" class="s-input">
                                @foreach(['smtp'=>'SMTP','log'=>'Log (Dev)','sendmail'=>'Sendmail'] as $val => $label)
                                <option value="{{ $val }}" {{ ($settings['email']['mail_mailer'] ?? 'smtp') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="s-label">Mail Host</label>
                            <input type="text" name="mail_host" value="{{ old('mail_host', $settings['email']['mail_host'] ?? '') }}" class="s-input" placeholder="smtp.gmail.com">
                        </div>
                        <div>
                            <label class="s-label">Mail Port</label>
                            <input type="number" name="mail_port" value="{{ old('mail_port', $settings['email']['mail_port'] ?? '587') }}" class="s-input" placeholder="587">
                        </div>
                        <div>
                            <label class="s-label">Encryption</label>
                            <select name="mail_encryption" class="s-input">
                                @foreach(['tls'=>'TLS','ssl'=>'SSL',''=>'None'] as $val => $label)
                                <option value="{{ $val }}" {{ ($settings['email']['mail_encryption'] ?? 'tls') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="s-label">Username</label>
                            <input type="text" name="mail_username" value="{{ old('mail_username', $settings['email']['mail_username'] ?? '') }}" class="s-input" placeholder="your@email.com">
                        </div>
                        <div>
                            <label class="s-label">Password</label>
                            <input type="password" name="mail_password" value="{{ old('mail_password', $settings['email']['mail_password'] ?? '') }}" class="s-input" placeholder="••••••••">
                            <p class="s-hint">Leave blank to keep existing password</p>
                        </div>
                    </div>
                    <p class="s-section-title">Sender Identity</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="s-label">From Address</label>
                            <input type="email" name="mail_from_address" value="{{ old('mail_from_address', $settings['email']['mail_from_address'] ?? '') }}" class="s-input" placeholder="noreply@flyoverbd.com">
                        </div>
                        <div>
                            <label class="s-label">From Name</label>
                            <input type="text" name="mail_from_name" value="{{ old('mail_from_name', $settings['email']['mail_from_name'] ?? '') }}" class="s-input" placeholder="FlyoverBD">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-7 py-2.5 rounded-xl shadow-sm transition text-sm">Save Email Settings</button>
                </div>
            </form>
            @endif

            {{-- ── SECURITY ── --}}
            @if($g === 'security')
            <form method="POST" action="{{ route('admin.settings.update', 'security') }}">
                @csrf
                {{-- hidden fallback for all checkboxes --}}
                <input type="hidden" name="force_https" value="0">
                <input type="hidden" name="two_factor_admin" value="0">
                <input type="hidden" name="password_min_length" value="0">
                <input type="hidden" name="password_uppercase" value="0">
                <input type="hidden" name="password_numbers" value="0">
                <input type="hidden" name="password_symbols" value="0">
                <div class="s-card p-6 sm:p-8 space-y-4">
                    <p class="s-section-title">Access Control</p>
                    <div class="toggle-wrap">
                        <label class="toggle"><input type="checkbox" name="force_https" value="1" {{ ($settings['security']['force_https'] ?? '0') === '1' ? 'checked' : '' }}><span class="toggle-slider"></span></label>
                        <div><p class="text-sm font-semibold text-gray-700">Force HTTPS</p><p class="text-xs text-gray-400">Redirect all HTTP traffic to HTTPS</p></div>
                    </div>
                    <div class="toggle-wrap">
                        <label class="toggle"><input type="checkbox" name="two_factor_admin" value="1" {{ ($settings['security']['two_factor_admin'] ?? '0') === '1' ? 'checked' : '' }}><span class="toggle-slider"></span></label>
                        <div><p class="text-sm font-semibold text-gray-700">Two-Factor Authentication</p><p class="text-xs text-gray-400">Require 2FA for all admin logins</p></div>
                    </div>
                    <div class="mb-2">
                        <label class="s-label">Session Lifetime <span class="font-normal text-gray-400">(minutes)</span></label>
                        <input type="number" name="session_lifetime" value="{{ old('session_lifetime', $settings['security']['session_lifetime'] ?? '120') }}" class="s-input" min="5" placeholder="120">
                    </div>
                    <p class="s-section-title">Password Policy</p>
                    @foreach([
                        'password_min_length' => 'Minimum 8 characters',
                        'password_uppercase'  => 'Require uppercase letters',
                        'password_numbers'    => 'Require numbers',
                        'password_symbols'    => 'Require special characters',
                    ] as $key => $label)
                    <div class="toggle-wrap">
                        <label class="toggle"><input type="checkbox" name="{{ $key }}" value="1" {{ ($settings['security'][$key] ?? '0') === '1' ? 'checked' : '' }}><span class="toggle-slider"></span></label>
                        <p class="text-sm font-semibold text-gray-700">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-7 py-2.5 rounded-xl shadow-sm transition text-sm">Save Security Settings</button>
                </div>
            </form>
            @endif

            {{-- ── PAYMENT ── --}}
            @if($g === 'payment')
            <form method="POST" action="{{ route('admin.settings.update', 'payment') }}">
                @csrf
                <div class="s-card p-6 sm:p-8">
                    <p class="s-section-title">Currency</p>
                    <div class="mb-6">
                        <label class="s-label">Default Currency</label>
                        <select name="currency" class="s-input" style="max-width:240px;">
                            @foreach(['BDT'=>'Bangladeshi Taka (BDT)','USD'=>'US Dollar (USD)','EUR'=>'Euro (EUR)'] as $val => $label)
                            <option value="{{ $val }}" {{ ($settings['payment']['currency'] ?? 'BDT') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <p class="s-section-title">bKash</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                        <div>
                            <label class="s-label">App Key</label>
                            <input type="text" name="bkash_app_key" value="{{ old('bkash_app_key', $settings['payment']['bkash_app_key'] ?? '') }}" class="s-input" placeholder="bKash App Key">
                        </div>
                        <div>
                            <label class="s-label">App Secret</label>
                            <input type="password" name="bkash_app_secret" value="{{ old('bkash_app_secret', $settings['payment']['bkash_app_secret'] ?? '') }}" class="s-input" placeholder="••••••••">
                        </div>
                        <div>
                            <label class="s-label">Username</label>
                            <input type="text" name="bkash_username" value="{{ old('bkash_username', $settings['payment']['bkash_username'] ?? '') }}" class="s-input" placeholder="bKash Username">
                        </div>
                        <div>
                            <label class="s-label">Password</label>
                            <input type="password" name="bkash_password" value="{{ old('bkash_password', $settings['payment']['bkash_password'] ?? '') }}" class="s-input" placeholder="••••••••">
                        </div>
                    </div>
                    <p class="s-section-title">SSL Commerce</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="s-label">Store ID</label>
                            <input type="text" name="ssl_store_id" value="{{ old('ssl_store_id', $settings['payment']['ssl_store_id'] ?? '') }}" class="s-input" placeholder="Store ID">
                        </div>
                        <div>
                            <label class="s-label">Store Password</label>
                            <input type="password" name="ssl_store_password" value="{{ old('ssl_store_password', $settings['payment']['ssl_store_password'] ?? '') }}" class="s-input" placeholder="••••••••">
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-7 py-2.5 rounded-xl shadow-sm transition text-sm">Save Payment Settings</button>
                </div>
            </form>
            @endif

            {{-- ── SOCIAL ── --}}
            @if($g === 'social')
            <form method="POST" action="{{ route('admin.settings.update', 'social') }}">
                @csrf
                <div class="s-card p-6 sm:p-8">
                    <p class="s-section-title">Social Media Links</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @foreach([
                            'facebook'  => ['Facebook',  'https://facebook.com/flyoverbd',  'M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z'],
                            'twitter'   => ['Twitter/X',  'https://twitter.com/flyoverbd',   'M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z'],
                            'instagram' => ['Instagram',  'https://instagram.com/flyoverbd', 'M16 11.37A4 4 0 1112.63 8 4 4 0 0116 11.37zm1.5-4.87h.01M6.5 20.5h11a2 2 0 002-2v-11a2 2 0 00-2-2h-11a2 2 0 00-2 2v11a2 2 0 002 2z'],
                            'linkedin'  => ['LinkedIn',   'https://linkedin.com/company/flyoverbd', 'M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z M4 6a2 2 0 100-4 2 2 0 000 4z'],
                            'youtube'   => ['YouTube',    'https://youtube.com/flyoverbd',   'M22.54 6.42a2.78 2.78 0 00-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 00-1.95 1.96A29 29 0 001 12a29 29 0 00.46 5.58A2.78 2.78 0 003.41 19.6C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 001.95-1.95A29 29 0 0023 12a29 29 0 00-.46-5.58z M9.75 15.02l5.75-3.02-5.75-3.02v6.04z'],
                            'whatsapp'  => ['WhatsApp',   '+8801234567890',                  'M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z'],
                        ] as $key => [$label, $placeholder, $iconPath])
                        <div>
                            <label class="s-label flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"/></svg>
                                {{ $label }}
                            </label>
                            <input type="text" name="{{ $key }}" value="{{ old($key, $settings['social'][$key] ?? '') }}" class="s-input" placeholder="{{ $placeholder }}">
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-7 py-2.5 rounded-xl shadow-sm transition text-sm">Save Social Settings</button>
                </div>
            </form>
            @endif

            {{-- ── STATS ── --}}
            @if($g === 'stats')
            <form method="POST" action="{{ route('admin.settings.update', 'stats') }}">
                @csrf
                <div class="s-card p-6 sm:p-8">
                    <p class="s-section-title">Public Trust Bar Stats</p>
                    <p class="text-xs text-gray-400 mb-5">These values appear on the homepage trust bar and About page. Travellers, Destinations, and Visa Approval are auto-calculated from real DB data — use the fields below as fallbacks or overrides when real data is zero.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div>
                            <label class="s-label">Star Rating <span class="font-normal text-gray-400">(e.g. 4.8)</span></label>
                            <input type="text" name="stat_rating" value="{{ old('stat_rating', $settings['stats']['stat_rating'] ?? '4.8') }}" class="s-input" placeholder="4.8">
                            <p class="s-hint">Shown as "4.8★ Rating" — update manually or integrate a reviews API.</p>
                        </div>
                        <div>
                            <label class="s-label">Visa Approval Rate Fallback <span class="font-normal text-gray-400">(%)</span></label>
                            <input type="text" name="stat_visa_approval_rate" value="{{ old('stat_visa_approval_rate', $settings['stats']['stat_visa_approval_rate'] ?? '94.2') }}" class="s-input" placeholder="94.2">
                            <p class="s-hint">Used only when no visa booking records exist yet.</p>
                        </div>
                        <div>
                            <label class="s-label">Travellers Display Fallback</label>
                            <input type="text" name="stat_travellers_display" value="{{ old('stat_travellers_display', $settings['stats']['stat_travellers_display'] ?? '1.2M+') }}" class="s-input" placeholder="1.2M+">
                            <p class="s-hint">Shown when total bookings + offset is zero (e.g. fresh install).</p>
                        </div>
                        <div>
                            <label class="s-label">Travellers Historic Offset</label>
                            <input type="number" name="stat_travellers_offset" value="{{ old('stat_travellers_offset', $settings['stats']['stat_travellers_offset'] ?? '0') }}" class="s-input" placeholder="0" min="0">
                            <p class="s-hint">Added to DB booking count to represent pre-digital history (e.g. 10000).</p>
                        </div>
                        <div>
                            <label class="s-label">Destinations Fallback</label>
                            <input type="number" name="stat_destinations" value="{{ old('stat_destinations', $settings['stats']['stat_destinations'] ?? '62') }}" class="s-input" placeholder="62" min="0">
                            <p class="s-hint">Used when no active packages or visas exist in the database.</p>
                        </div>
                    </div>
                </div>
                <div class="flex justify-end mt-4">
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold px-7 py-2.5 rounded-xl shadow-sm transition text-sm">Save Stats Settings</button>
                </div>
            </form>
            @endif

        </div>
    </div>
</div>

</x-admin-layout>
