<x-app-layout>
    <style>
        .err-card {
            background: #fff;
            border-radius: 24px;
            border: 1px solid #E5E7EB;
            box-shadow: 0 4px 32px rgba(0,0,0,.07);
            max-width: 640px;
            width: 100%;
            padding: 3rem 2.5rem;
            text-align: center;
        }

        .err-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #fff5f5;
            color: #C8102E;
            font-size: .75rem;
            font-weight: 700;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: .35rem .85rem;
            border-radius: 100px;
            margin-bottom: 1.5rem;
        }
        .err-badge svg { width: 14px; height: 14px; }

        .err-icon-wrap {
            width: 96px;
            height: 96px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.75rem;
        }
        .err-icon-wrap svg { width: 48px; height: 48px; }

        .icon-red    { background: #FEE2E2; color: #C8102E; }
        .icon-orange { background: #FEF3C7; color: #D97706; }
        .icon-blue   { background: #DBEAFE; color: #2563EB; }
        .icon-gray   { background: #F3F4F6; color: #6B7280; }

        .err-number {
            font-size: 5rem;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -.04em;
            margin-bottom: .5rem;
            background: linear-gradient(135deg, #C8102E 0%, #FF6B6B 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .err-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #111827;
            margin-bottom: .75rem;
            letter-spacing: -.02em;
        }

        .err-subtitle {
            color: #6B7280;
            font-size: 1rem;
            line-height: 1.65;
            margin-bottom: 2rem;
            max-width: 480px;
            margin-left: auto;
            margin-right: auto;
        }

        .err-info-box {
            border-radius: 14px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
            border: 1px solid;
        }
        .err-info-box.red    { background: #FFF5F5; border-color: #FECACA; }
        .err-info-box.orange { background: #FFFBEB; border-color: #FDE68A; }
        .err-info-box.blue   { background: #EFF6FF; border-color: #BFDBFE; }

        .err-info-box h3 { font-size: .875rem; font-weight: 700; margin-bottom: .4rem; }
        .err-info-box p  { font-size: .8125rem; line-height: 1.6; }
        .err-info-box.red    h3 { color: #991B1B; }
        .err-info-box.red    p  { color: #B91C1C; }
        .err-info-box.orange h3 { color: #92400E; }
        .err-info-box.orange p  { color: #B45309; }
        .err-info-box.blue   h3 { color: #1E40AF; }
        .err-info-box.blue   p  { color: #1D4ED8; }

        .err-btn-group {
            display: flex;
            flex-wrap: wrap;
            gap: .75rem;
            justify-content: center;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: .5rem;
            padding: .75rem 1.5rem;
            border-radius: 12px;
            font-size: .9375rem;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all .15s ease;
            font-family: inherit;
        }
        .btn svg { width: 18px; height: 18px; flex-shrink: 0; }

        .btn-primary { background: #C8102E; color: #fff; }
        .btn-primary:hover { background: #a80d24; transform: translateY(-1px); box-shadow: 0 6px 20px rgba(200,16,46,.3); }

        .btn-ghost {
            background: transparent;
            color: #374151;
            border: 1.5px solid #E5E7EB;
        }
        .btn-ghost:hover { background: #F9FAFB; border-color: #D1D5DB; }

        .err-meta {
            font-size: .75rem;
            color: #9CA3AF;
            margin-top: 1.25rem;
        }

        @media (max-width: 480px) {
            .err-card { padding: 2rem 1.25rem; }
            .err-number { font-size: 4rem; }
            .err-title { font-size: 1.4rem; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>

    <div class="bg-gray-50 min-h-[70vh] flex items-center justify-center py-12 px-4">
        <div class="err-card">

            <div class="err-badge">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                HTTP {{ $code }}
            </div>

            <div class="err-icon-wrap {{ $iconColor ?? 'icon-red' }}">
                {!! $icon !!}
            </div>

            <div class="err-number">{{ $code }}</div>

            <h1 class="err-title">{{ $title }}</h1>

            <p class="err-subtitle">{{ $message }}</p>

            @isset($infoBox)
            <div class="err-info-box {{ $infoBox['type'] ?? 'red' }}">
                <h3>{{ $infoBox['heading'] }}</h3>
                <p>{{ $infoBox['body'] }}</p>
            </div>
            @endisset

            <div class="err-btn-group">
                {!! $actions !!}
            </div>

            @isset($meta)
            <p class="err-meta">{{ $meta }}</p>
            @endisset

        </div>
    </div>
</x-app-layout>
