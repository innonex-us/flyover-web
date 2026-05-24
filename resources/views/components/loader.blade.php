{{-- ═══════════════════════════════════════════════════════════
     FlyoverBD Page Loading Screen
     File: resources/views/components/loader.blade.php
═══════════════════════════════════════════════════════════ --}}

<style>
    /* ── Overlay ───────────────────────────────────────────── */
    #flyover-loader {
        position: fixed;
        inset: 0;
        z-index: 99999;
        background: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        gap: 0;
        transition: opacity 0.55s ease, visibility 0.55s ease;
    }
    #flyover-loader.fol-hide {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    /* ── Logo wrapper ──────────────────────────────────────── */
    .fol-logo-wrap {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Logo image — fade + scale up on entry */
    .fol-logo {
        width: 260px;
        height: auto;
        opacity: 0;
        transform: scale(0.82);
        animation: folLogoIn 0.65s cubic-bezier(0.34,1.56,0.64,1) 0.15s forwards;
    }
    @keyframes folLogoIn {
        to { opacity: 1; transform: scale(1); }
    }

    /* ── Airplane orbit ring ───────────────────────────────── */
    /*
     * Logo is displayed at 260px wide. The original PNG is ~400×100px.
     * Globe "O" centre is ~200px from left, ~32px from top in the original.
     * Scale factor: 260/400 = 0.65
     * So in display: left ≈ 200*0.65 = 130px, top ≈ 32*0.65 = 21px
     * Orbit wrap is 64×64, so offset by -32px each side.
     * left: 130-32 = 98px  top: 21-32 = -11px (relative to img)
     */
    .fol-orbit-wrap {
        position: absolute;
        top: -11px;
        left: 98px;
        width: 64px;
        height: 64px;
        opacity: 0;
        animation: folFadeIn 0.3s ease 0.7s forwards;
    }
    @keyframes folFadeIn {
        to { opacity: 1; }
    }

    /* Airplane rotates around the globe */
    .fol-orbit-ring {
        width: 64px;
        height: 64px;
        animation: folOrbit 1.6s linear infinite;
        transform-origin: center center;
    }
    @keyframes folOrbit {
        from { transform: rotate(0deg); }
        to   { transform: rotate(360deg); }
    }

    /* ── Tagline ───────────────────────────────────────────── */
    .fol-tagline {
        margin-top: 6px;
        font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 4px;
        color: #1a1a1a;
        text-transform: uppercase;
        opacity: 0;
        animation: folFadeIn 0.5s ease 0.85s forwards;
    }

    /* ── Progress bar ──────────────────────────────────────── */
    .fol-bar-wrap {
        margin-top: 32px;
        width: 200px;
        height: 3px;
        background: #eeeeee;
        border-radius: 99px;
        overflow: hidden;
    }
    .fol-bar {
        height: 100%;
        width: 0;
        background: linear-gradient(90deg, #C8102E 0%, #ff4d6d 100%);
        border-radius: 99px;
        animation: folBar 2s ease-in-out forwards 0.2s;
    }
    @keyframes folBar {
        0%   { width: 0%; }
        50%  { width: 70%; }
        85%  { width: 90%; }
        100% { width: 100%; }
    }

    /* ── Dots pulse ────────────────────────────────────────── */
    .fol-dots {
        margin-top: 14px;
        display: flex;
        gap: 6px;
        opacity: 0;
        animation: folFadeIn 0.3s ease 1.1s forwards;
    }
    .fol-dot {
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #C8102E;
        animation: folPulse 1.2s ease-in-out infinite;
    }
    .fol-dot:nth-child(2) { animation-delay: 0.2s; }
    .fol-dot:nth-child(3) { animation-delay: 0.4s; }
    @keyframes folPulse {
        0%, 100% { opacity: 0.25; transform: scale(0.7); }
        50%       { opacity: 1;    transform: scale(1);   }
    }
</style>

<div id="flyover-loader" role="status" aria-label="Loading FlyoverBD">

    <div class="fol-logo-wrap">

        {{-- Actual logo image --}}
        <img src="{{ asset('logo.png') }}"
             alt="FlyoverBD"
             class="fol-logo"
             draggable="false">

        {{-- Airplane SVG orbiting the globe "O" --}}
        <div class="fol-orbit-wrap">
            <svg class="fol-orbit-ring"
                 viewBox="0 0 64 64"
                 xmlns="http://www.w3.org/2000/svg"
                 overflow="visible">

                {{-- Faint elliptical orbit ring --}}
                <ellipse cx="32" cy="32" rx="28" ry="11"
                         fill="none"
                         stroke="#C8102E"
                         stroke-width="1"
                         stroke-dasharray="4 3"
                         opacity="0.35"
                         transform="rotate(-15,32,32)"/>

                {{-- Airplane at the 12 o'clock position of the ring --}}
                {{-- It rides the ring as the whole SVG rotates --}}
                <g transform="translate(32,4) rotate(90)">
                    {{-- Wing body --}}
                    <path d="M0,-3.5 L5,0 L0,1.8 L-5,0 Z"
                          fill="#C8102E"/>
                    {{-- Left wing --}}
                    <path d="M-1.5,-1.5 L-5.5,-4.5 L-6.5,-3.5 L-2.5,0.5 Z"
                          fill="#C8102E" opacity="0.85"/>
                    {{-- Right wing --}}
                    <path d="M-1.5,1.5 L-5.5,4.5 L-6.5,3.5 L-2.5,-0.5 Z"
                          fill="#C8102E" opacity="0.85"/>
                    {{-- Tail fin --}}
                    <path d="M-4,0 L-6,-2 L-6.5,-1.5 L-4.5,0.5 Z"
                          fill="#C8102E" opacity="0.7"/>
                </g>

            </svg>
        </div>

    </div>

    {{-- Tagline --}}
    <p class="fol-tagline">Make Your Life Borderless</p>

    {{-- Progress bar --}}
    <div class="fol-bar-wrap">
        <div class="fol-bar"></div>
    </div>

    {{-- Pulse dots --}}
    <div class="fol-dots" aria-hidden="true">
        <span class="fol-dot"></span>
        <span class="fol-dot"></span>
        <span class="fol-dot"></span>
    </div>

</div>

<script>
    (function () {
        var el = document.getElementById('flyover-loader');
        if (!el) return;

        function hide() {
            el.classList.add('fol-hide');
        }

        if (document.readyState === 'complete') {
            setTimeout(hide, 350);
        } else {
            window.addEventListener('load', function () {
                setTimeout(hide, 450);
            });
        }
        /* Hard cap: hide after 4.5 s no matter what */
        setTimeout(hide, 4500);
    }());
</script>
