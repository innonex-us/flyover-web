{{-- ═══════════════════════════════════════════════════════════
     FlyoverBD — Cinematic Loader  v4 (pixel-perfect logo.png)
     File: resources/views/components/loader.blade.php

     Logo: 400×130 px  |  Globe cx=200 cy=48 r=42  |  Scale=0.75

     Each letter is a SVG <image> of the real logo.png masked to
     its exact pixel-column region → 100% shape accuracy.

     Animation sequence:
       0.10 s  "F L Y" clip-regions drop in from top (stagger)
       0.35 s  Globe "O" scales in from center (JS easeOutBack)
       0.58 s  "V E R" clip-regions drop in
       0.75 s  Orbit ellipse fades in
       0.85 s  Plane flies in → lands on orbit
       1.10 s  Plane orbits the globe (rAF loop)
       1.30 s  Tagline + dots appear
       ~load   Plane blasts off top-right with trail
       +0.15 s Smoke puffs burst
       +0.40 s SVG fades, real logo.png fades in
       +0.90 s Loader fades away
═══════════════════════════════════════════════════════════ --}}

<style>
/* ── Overlay ───────────────────────────────────────────────────── */
#flyover-loader {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: #ffffff;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    transition: opacity 0.65s ease, visibility 0.65s ease;
}
#flyover-loader.fol-hide {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

/* ── Stage ─────────────────────────────────────────────────────── */
.fol-stage {
    position: relative;
    display: inline-block;
    line-height: 0;
}

/* ── SVG logo ───────────────────────────────────────────────────── */
#fol-svg-logo {
    display: block;
    width: 300px;
    height: auto;
    overflow: visible;
}

/* ── Letter groups — drop in from above ────────────────────────── */
.fol-ltr {
    opacity: 0;
    transform: translateY(-24px);
    transition: opacity 0.42s ease,
                transform 0.42s cubic-bezier(0.22,1,0.36,1);
}
.fol-ltr.fol-in {
    opacity: 1;
    transform: translateY(0);
}

/* ── Real logo image (hidden until blast-off) ─────────────────── */
#fol-logo-real {
    position: absolute;
    left: 0; top: 0;
    width: 300px;
    height: auto;
    opacity: 0;
    transition: opacity 0.65s ease;
    pointer-events: none;
}
#fol-logo-real.fol-visible { opacity: 1; }

/* ── Orbit ellipse overlay ──────────────────────────────────────── */
#fol-orbit-svg {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    pointer-events: none;
    overflow: visible;
}
#fol-orb-ell {
    opacity: 0;
    transition: opacity 0.4s ease;
}
#fol-orb-ell.fol-show { opacity: 0.28; }

/* ── Animated plane ─────────────────────────────────────────────── */
#fol-plane {
    position: absolute;
    left: 0; top: 0;
    width: 44px;
    height: 44px;
    pointer-events: none;
    opacity: 0;
    will-change: transform, opacity;
}

/* ── Smoke puffs ──────────────────────────────────────────────── */
.fol-puff {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    opacity: 0;
    will-change: transform, opacity;
}

/* ── Tagline ────────────────────────────────────────────────────── */
.fol-tagline {
    margin-top: 6px;
    font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
    font-size: 8.5px;
    font-weight: 400;
    letter-spacing: 4px;
    color: #333333;
    text-transform: uppercase;
    opacity: 0;
    transition: opacity 0.6s ease;
}
.fol-tagline.fol-in { opacity: 1; }

/* ── Progress bar ───────────────────────────────────────────────── */
.fol-bar-wrap {
    margin-top: 34px;
    width: 220px;
    height: 3px;
    background: #eeeeee;
    border-radius: 99px;
    overflow: hidden;
}
.fol-bar {
    height: 100%;
    width: 0;
    background: linear-gradient(90deg, #C8102E 0%, #ff6b6b 100%);
    border-radius: 99px;
    animation: folBar 3.2s ease-in-out forwards 0.3s;
}
@keyframes folBar {
    0%   { width:  0% }
    35%  { width: 52% }
    75%  { width: 85% }
    100% { width: 100% }
}

/* ── Dots ───────────────────────────────────────────────────────── */
.fol-dots {
    margin-top: 14px;
    display: flex;
    gap: 6px;
    opacity: 0;
    transition: opacity 0.4s ease;
}
.fol-dots.fol-in { opacity: 1; }
.fol-dot {
    width: 6px; height: 6px;
    border-radius: 50%;
    background: #C8102E;
    animation: folPulse 1.2s ease-in-out infinite;
}
.fol-dot:nth-child(2) { animation-delay: 0.2s; }
.fol-dot:nth-child(3) { animation-delay: 0.4s; }
@keyframes folPulse {
    0%,100% { opacity:.25; transform:scale(.7); }
    50%     { opacity:1;   transform:scale(1);  }
}
</style>

<div id="flyover-loader" role="status" aria-label="Loading FlyoverBD">

    <div class="fol-stage" id="fol-stage">

        {{-- ══════════════════════════════════════════════════════════
             SVG LOGO — each letter = clipPath window onto logo.png
             Logo PNG: 400×130 px (exact match, preserveAspectRatio=none)

             Letter x-boundaries (from PNG pixel analysis):
               F  :   0–50   (active red:   8–49)
               L  :  50–106  (active red:  63–105)
               Y  : 106–158  (active red: 106–155)
               O  : circle cx=200 cy=48 r=45  (actual globe)
               V  : 244–278  (active red: 248–277)
               E  : 278–335  (active red: 285–327)
               R  : 335–400  (active red: 340–396)

             Static white plane in logo covered by #C8102E circle
             so animated plane can replace it visually.
        ══════════════════════════════════════════════════════════ --}}
        <svg id="fol-svg-logo"
             viewBox="0 0 400 130"
             xmlns="http://www.w3.org/2000/svg"
             aria-label="FlyoverBD"
             overflow="visible">

            <defs>
                {{-- height=97 clips tagline (y=106–123). Adjacent clips overlap 2px
                     so antialiased edges never leave hairline white gaps.        --}}
                <clipPath id="cp-F"><rect x="0"   y="0" width="52"  height="97"/></clipPath>
                <clipPath id="cp-L"><rect x="48"  y="0" width="62"  height="97"/></clipPath>
                <clipPath id="cp-Y"><rect x="104" y="0" width="56"  height="97"/></clipPath>
                <clipPath id="cp-O"><circle cx="200" cy="48" r="46"/></clipPath>
                <clipPath id="cp-V"><rect x="240" y="0" width="42"  height="97"/></clipPath>
                <clipPath id="cp-E"><rect x="276" y="0" width="61"  height="97"/></clipPath>
                <clipPath id="cp-R"><rect x="333" y="0" width="67"  height="97"/></clipPath>
            </defs>

            {{-- F --}}
            <g clip-path="url(#cp-F)">
                <g id="fol-F" class="fol-ltr">
                    <image href="{{ asset('logo.png') }}" x="0" y="0" width="400" height="130" preserveAspectRatio="none"/>
                </g>
            </g>

            {{-- L --}}
            <g clip-path="url(#cp-L)">
                <g id="fol-L" class="fol-ltr">
                    <image href="{{ asset('logo.png') }}" x="0" y="0" width="400" height="130" preserveAspectRatio="none"/>
                </g>
            </g>

            {{-- Y --}}
            <g clip-path="url(#cp-Y)">
                <g id="fol-Y" class="fol-ltr">
                    <image href="{{ asset('logo.png') }}" x="0" y="0" width="400" height="130" preserveAspectRatio="none"/>
                </g>
            </g>

            {{-- O / Globe — JS-driven scale from cx=200,cy=48 --}}
            <g clip-path="url(#cp-O)">
                <g id="fol-ggrp" style="opacity:0" transform="translate(200 48) scale(0)">
                    <image href="{{ asset('logo.png') }}" x="0" y="0" width="400" height="130" preserveAspectRatio="none"/>
                </g>
            </g>

            {{-- V --}}
            <g clip-path="url(#cp-V)">
                <g id="fol-V" class="fol-ltr">
                    <image href="{{ asset('logo.png') }}" x="0" y="0" width="400" height="130" preserveAspectRatio="none"/>
                </g>
            </g>

            {{-- E --}}
            <g clip-path="url(#cp-E)">
                <g id="fol-E" class="fol-ltr">
                    <image href="{{ asset('logo.png') }}" x="0" y="0" width="400" height="130" preserveAspectRatio="none"/>
                </g>
            </g>

            {{-- R --}}
            <g clip-path="url(#cp-R)">
                <g id="fol-R" class="fol-ltr">
                    <image href="{{ asset('logo.png') }}" x="0" y="0" width="400" height="130" preserveAspectRatio="none"/>
                </g>
            </g>

        </svg>

        {{-- Real logo.png revealed from smoke after blast-off --}}
        <img id="fol-logo-real"
             src="{{ asset('logo.png') }}"
             alt="FlyoverBD"
             draggable="false">

        {{-- Orbit ellipse overlay --}}
        <svg id="fol-orbit-svg" aria-hidden="true">
            <ellipse id="fol-orb-ell"
                     fill="none"
                     stroke="#C8102E"
                     stroke-width="1.2"
                     stroke-dasharray="5 4"/>
        </svg>

    </div>

    {{-- Animated plane — white + red glow so visible on both globe and bg --}}
    <div id="fol-plane" aria-hidden="true">
        <svg viewBox="0 0 44 44"
             xmlns="http://www.w3.org/2000/svg"
             overflow="visible">
            <defs>
                <filter id="plane-glow" x="-60%" y="-60%" width="220%" height="220%">
                    <feDropShadow dx="0" dy="0" stdDeviation="2.5"
                                  flood-color="#C8102E" flood-opacity="0.85"/>
                </filter>
            </defs>
            <g transform="translate(22,22)" filter="url(#plane-glow)">
                {{-- Body --}}
                <path d="M-14,0 L15,-4 L15,4 Z"
                      fill="white" stroke="#C8102E" stroke-width="0.8" stroke-linejoin="round"/>
                {{-- Top wing --}}
                <path d="M1,-4 L-8,-17 L-13,-14 L-6,0 Z"
                      fill="white" stroke="#C8102E" stroke-width="0.6" stroke-linejoin="round"/>
                {{-- Bottom wing --}}
                <path d="M1,4 L-8,17 L-13,14 L-6,0 Z"
                      fill="white" stroke="#C8102E" stroke-width="0.6" stroke-linejoin="round"/>
                {{-- Tail top --}}
                <path d="M-10,0 L-17,-7 L-19,-4.5 L-12,0.5 Z"
                      fill="white" stroke="#C8102E" stroke-width="0.5" stroke-linejoin="round"/>
                {{-- Tail bottom --}}
                <path d="M-10,0 L-17,7 L-19,4.5 L-12,-0.5 Z"
                      fill="white" stroke="#C8102E" stroke-width="0.5" stroke-linejoin="round"/>
            </g>
        </svg>
    </div>

    {{-- Tagline --}}
    <p class="fol-tagline" id="fol-tagline">Make Your Life Borderless</p>

    {{-- Progress bar --}}
    <div class="fol-bar-wrap">
        <div class="fol-bar"></div>
    </div>

    {{-- Dots --}}
    <div class="fol-dots" id="fol-dots" aria-hidden="true">
        <span class="fol-dot"></span>
        <span class="fol-dot"></span>
        <span class="fol-dot"></span>
    </div>

</div>

<script>
(function () {
    'use strict';

    /* ── Elements ─────────────────────────────────────────── */
    var loader  = document.getElementById('flyover-loader');
    var stage   = document.getElementById('fol-stage');
    var svgLogo = document.getElementById('fol-svg-logo');
    var realLogo= document.getElementById('fol-logo-real');
    var gGrp    = document.getElementById('fol-ggrp');
    var orbEll  = document.getElementById('fol-orb-ell');
    var plane   = document.getElementById('fol-plane');
    var tagline = document.getElementById('fol-tagline');
    var dots    = document.getElementById('fol-dots');
    if (!loader) return;

    function markSeen() {
        if (document.cookie.indexOf('flyover_home_loader_seen=') !== -1) return;

        var maxAge = 60 * 60 * 24 * 365;
        var cookie = 'flyover_home_loader_seen=1; path=/; max-age=' + maxAge + '; samesite=lax';

        if (window.location.protocol === 'https:') {
            cookie += '; secure';
        }

        document.cookie = cookie;
    }

    markSeen();

    /* ── Globe constants (pixel-exact from logo.png) ──────────
     * Logo: 400×130 px  |  Globe: cx=200, cy=48, r=42
     * SVG displayed 300px wide  →  SCALE = 300/400 = 0.75
     * Globe centre in display-px (rel. SVG top-left):
     *   cx_disp = 200 * 0.75 = 150 px
     *   cy_disp =  48 * 0.75 =  36 px
     ──────────────────────────────────────────────────────── */
    var SVG_VB_W = 400;
    var SVG_DISP = 300;
    var SCALE    = SVG_DISP / SVG_VB_W;   /* 0.75 */

    var GCX_VB = 200;   /* globe cx in viewBox units */
    var GCY_VB = 48;    /* globe cy in viewBox units (pixel-exact) */

    /* Orbit ellipse in display-px */
    var ORB_RX   = 52 * SCALE;   /* 39 px — slightly outside globe edge */
    var ORB_RY   = 20 * SCALE;   /* 15 px */
    var ORB_TILT = -15;           /* degrees, matches logo dashed ring */

    var PH = 22;   /* plane element half-size (44px / 2) */

    /* Globe centre in loader-relative px (set by measureGlobe) */
    var gCxL = 0, gCyL = 0;

    function measureGlobe() {
        var lr = loader.getBoundingClientRect();
        var sr = stage.getBoundingClientRect();
        gCxL = (sr.left - lr.left) + GCX_VB * SCALE;
        gCyL = (sr.top  - lr.top)  + GCY_VB * SCALE;

        /* Position orbit ellipse in stage-coordinate space */
        var gCxS = GCX_VB * SCALE;
        var gCyS = GCY_VB * SCALE;
        orbEll.setAttribute('cx', '' + gCxS);
        orbEll.setAttribute('cy', '' + gCyS);
        orbEll.setAttribute('rx', '' + ORB_RX);
        orbEll.setAttribute('ry', '' + ORB_RY);
        orbEll.setAttribute('transform',
            'rotate(' + ORB_TILT + ',' + gCxS + ',' + gCyS + ')');
    }

    /* ── Math helpers ─────────────────────────────────────── */
    function toRad(d) { return d * Math.PI / 180; }

    /* Point + heading on the tilted orbit ellipse */
    function orbitPt(deg) {
        var a  = toRad(deg);
        var t  = toRad(ORB_TILT);
        var ex = ORB_RX * Math.cos(a);
        var ey = ORB_RY * Math.sin(a);
        var rx = ex * Math.cos(t) - ey * Math.sin(t);
        var ry = ex * Math.sin(t) + ey * Math.cos(t);
        var dx = -ORB_RX * Math.sin(a);
        var dy =  ORB_RY * Math.cos(a);
        var h  = Math.atan2(
            dy * Math.cos(t) + dx * Math.sin(t),
            dx * Math.cos(t) - dy * Math.sin(t)
        ) * 180 / Math.PI;
        return { x: gCxL + rx, y: gCyL + ry, h: h };
    }

    function setPlane(x, y, h, sc) {
        sc = sc == null ? 1 : sc;
        plane.style.transform =
            'translate(' + (x - PH) + 'px,' + (y - PH) + 'px)' +
            ' rotate(' + h + 'deg)' +
            ' scale(' + sc + ')';
    }

    /* ── Globe scale animation (SVG transform, viewBox-exact) ──
     * Scale around (GCX_VB, GCY_VB) using SVG transform attribute.
     * Equivalent to: translate(cx,cy) scale(s) translate(-cx,-cy)
     * Simplified:    translate(cx*(1-s), cy*(1-s)) scale(s)
     ──────────────────────────────────────────────────────────── */
    function easeOutBack(x) {
        var c1 = 0.8, c3 = c1 + 1;   /* gentle overshoot — clipped cleanly by circle */
        return 1 + c3 * Math.pow(x - 1, 3) + c1 * Math.pow(x - 1, 2);
    }

    function animateGlobe() {
        var t0  = null;
        var dur = 480;
        function frame(ts) {
            if (!t0) t0 = ts;
            var p  = Math.min(1, (ts - t0) / dur);
            var s  = easeOutBack(p);
            var tx = GCX_VB * (1 - s);
            var ty = GCY_VB * (1 - s);
            gGrp.setAttribute('transform',
                'translate(' + tx + ' ' + ty + ') scale(' + s + ')');
            gGrp.style.opacity = '' + Math.min(1, p * 2.5);
            if (p < 1) requestAnimationFrame(frame);
        }
        requestAnimationFrame(frame);
    }

    /* ── Orbit rAF loop ───────────────────────────────────── */
    var angle    = -90;    /* 12 o'clock start */
    var speed    = 1.6;    /* deg / frame — ~3.7s full orbit at 60fps */
    var orbiting = false;
    var rafId    = null;

    function tick() {
        if (!orbiting) return;
        angle += speed;
        var p = orbitPt(angle);
        setPlane(p.x, p.y, p.h, 1);
        rafId = requestAnimationFrame(tick);
    }

    /* ── Phase 1 — F L Y drop in ────────────────────────────── */
    function phase1Letters() {
        ['fol-F', 'fol-L', 'fol-Y'].forEach(function (id, i) {
            setTimeout(function () {
                var el = document.getElementById(id);
                if (el) el.classList.add('fol-in');
            }, i * 90);
        });
        /* V E R — start after globe finishes (globe: 350ms + 430ms = 780ms;
           phase1 called at 100ms → offset = 780-100 = 680ms + 60ms buffer) */
        ['fol-V', 'fol-E', 'fol-R'].forEach(function (id, i) {
            setTimeout(function () {
                var el = document.getElementById(id);
                if (el) el.classList.add('fol-in');
            }, 740 + i * 100);
        });
    }

    /* ── Phase 2 — globe scales in ──────────────────────────── */
    function phase2Globe() {
        animateGlobe();
    }

    /* ── Phase 3 — plane flies in → orbit ──────────────────── */
    function phase3Plane() {
        measureGlobe();

        var lr     = loader.getBoundingClientRect();
        var startX = lr.width  * 0.82;
        var startY = lr.height * 0.80;

        plane.style.transition = 'none';
        plane.style.opacity    = '1';
        setPlane(startX, startY, -35, 0.3);

        var p0 = orbitPt(-90);

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                plane.style.transition =
                    'transform 0.65s cubic-bezier(0.25,0.46,0.45,0.94),' +
                    'opacity 0.3s ease';
                setPlane(p0.x, p0.y, p0.h, 1);
            });
        });

        setTimeout(function () {
            orbEll.classList.add('fol-show');
        }, 380);

        setTimeout(function () {
            plane.style.transition = 'none';
            orbiting = true;
            rafId = requestAnimationFrame(tick);
        }, 720);
    }

    /* ── Phase 4 — blast-off, smoke, logo reveal ────────────── */
    var blasted = false;
    function blastOff() {
        if (blasted) return;
        blasted = true;
        if (rafId) cancelAnimationFrame(rafId);
        orbiting = false;

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                var lr = loader.getBoundingClientRect();
                plane.style.transition =
                    'transform 0.55s cubic-bezier(0.55,0,1,0.45),' +
                    'opacity 0.35s ease-in 0.14s';
                plane.style.opacity = '0';
                setPlane(lr.width * 0.88, -PH - 50, -42, 0.18);
            });
        });

        /* Smoke puffs centred on globe */
        var cx = gCxL, cy = gCyL;
        [
            { r: 50, dx: -10, dy:  -8, delay:  80 },
            { r: 40, dx:  14, dy: -18, delay: 140 },
            { r: 32, dx: -18, dy:  12, delay: 200 },
            { r: 24, dx:  20, dy:   4, delay: 260 },
            { r: 18, dx:   2, dy: -24, delay: 320 },
            { r: 14, dx: -22, dy: -14, delay: 380 }
        ].forEach(function (cfg) {
            var el = document.createElement('div');
            el.className = 'fol-puff';
            var d = cfg.r * 2;
            el.style.cssText =
                'width:'  + d + 'px;height:' + d + 'px;' +
                'left:'   + (cx - cfg.r) + 'px;' +
                'top:'    + (cy - cfg.r) + 'px;' +
                'background:radial-gradient(circle,' +
                    'rgba(165,165,165,0.9) 0%,rgba(215,215,215,0) 70%);';
            loader.appendChild(el);
            setTimeout(function () {
                el.style.transition = 'transform 1s ease-out,opacity 1s ease-out';
                el.style.opacity    = '0.85';
                el.style.transform  =
                    'scale(1) translate(' + cfg.dx + 'px,' + cfg.dy + 'px)';
                setTimeout(function () {
                    el.style.opacity   = '0';
                    el.style.transform =
                        'scale(3) translate(' +
                        (cfg.dx * 1.4) + 'px,' + (cfg.dy * 1.4) + 'px)';
                }, 220);
            }, cfg.delay);
        });

        /* Fade SVG, reveal logo.png */
        setTimeout(function () {
            svgLogo.style.transition = 'opacity 0.4s ease';
            svgLogo.style.opacity    = '0';
            orbEll.style.opacity     = '0';
        }, 180);

        setTimeout(function () {
            realLogo.classList.add('fol-visible');
        }, 420);

        tagline.classList.add('fol-in');
        dots.classList.add('fol-in');

        setTimeout(function () {
            loader.classList.add('fol-hide');
        }, 1000);
    }

    /* ── Boot sequence ────────────────────────────────────── */
    var t0  = Date.now();
    var MIN = 2800;

    /* Timeline (all relative to boot):
       100ms  F drop | 190ms L | 280ms Y
       350ms  Globe scale start (done ~780ms)
       840ms  V drop | 940ms E | 1040ms R  (after globe, inside phase1)
       1000ms Plane flies in
       1450ms Tagline
       1650ms Dots                                                      */
    setTimeout(phase1Letters, 100);
    setTimeout(phase2Globe,   350);
    setTimeout(phase3Plane,   1000);
    setTimeout(function () { tagline.classList.add('fol-in'); }, 1450);
    setTimeout(function () { dots.classList.add('fol-in');    }, 1650);

    function scheduleBlast() {
        var wait = Math.max(0, MIN - (Date.now() - t0));
        setTimeout(blastOff, wait);
    }

    if (document.readyState === 'complete') {
        scheduleBlast();
    } else {
        window.addEventListener('load', scheduleBlast);
    }
    setTimeout(blastOff, 7000);   /* hard cap */

}());
</script>
