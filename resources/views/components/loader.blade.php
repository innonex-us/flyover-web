{{-- ═══════════════════════════════════════════════════════════
     FlyoverBD — Cinematic Loader  v3 (same-to-same SVG logo)
     File: resources/views/components/loader.blade.php

     Animation sequence:
       0.00 s  White screen
       0.10 s  "FLY" letters drop in from top  (stagger)
       0.35 s  Globe "O" pops in with spring
       0.55 s  "VER" letters drop in
       0.70 s  Orbit arc draws itself
       0.85 s  Plane flies in from off-screen → lands on orbit
       1.10 s  Plane starts orbiting the globe (rAF loop)
       1.30 s  Tagline + dots appear
       ~load   Plane blasts off top-right with trail
       +0.15s  Smoke puffs burst
       +0.40s  Smoke clears, logo.png fades in (identical to SVG)
       +0.90s  Loader fades away
═══════════════════════════════════════════════════════════ --}}

<style>
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@800;900&display=swap');

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

/* ── SVG logo (shown during loading) ────────────────────────────
 *  viewBox matches logo proportions: 400 × 130 → displayed 300 × 97.5
 */
#fol-svg-logo {
    display: block;
    width: 300px;
    height: auto;
    overflow: visible;
}

/* letter groups — drop in from above */
.fol-ltr {
    opacity: 0;
    transform: translateY(-28px);
    transition: opacity 0.38s ease, transform 0.38s cubic-bezier(0.34,1.56,0.64,1);
}
.fol-ltr.fol-in { opacity: 1; transform: translateY(0); }

/* globe group — scale in */
#fol-ggrp {
    opacity: 0;
    transform-origin: 200px 55px;   /* globe centre in viewBox */
    transform: scale(0);
    transition: opacity 0.4s ease, transform 0.4s cubic-bezier(0.34,1.56,0.64,1);
}
#fol-ggrp.fol-in { opacity: 1; transform: scale(1); }

/* orbit arc draw */
#fol-arc {
    stroke-dasharray: 115;
    stroke-dashoffset: 115;
    transition: stroke-dashoffset 0.65s ease-out;
}
#fol-arc.fol-draw { stroke-dashoffset: 0; }

/* ── Real logo image (hidden → revealed after blast-off) ──────── */
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

/* ── Orbit ellipse overlay (SVG, full stage) ────────────────────── */
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
#fol-orb-ell.fol-show { opacity: 0.45; }

/* ── Animated plane (absolute in #flyover-loader) ────────────── */
#fol-plane {
    position: absolute;
    left: 0; top: 0;
    width: 34px;
    height: 34px;
    pointer-events: none;
    opacity: 0;
    will-change: transform, opacity;
}

/* ── Smoke puffs (absolute in #flyover-loader, created by JS) ── */
.fol-puff {
    position: absolute;
    border-radius: 50%;
    pointer-events: none;
    opacity: 0;
    will-change: transform, opacity;
}

/* ── Tagline ────────────────────────────────────────────────────── */
.fol-tagline {
    margin-top: 7px;
    font-family: 'Plus Jakarta Sans', Arial, sans-serif;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 4.5px;
    color: #1a1a1a;
    text-transform: uppercase;
    opacity: 0;
    transition: opacity 0.5s ease;
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

        {{-- ══════════════════════════════════════════════════════
             SVG LOGO — exact replica of logo.png
             viewBox = 400 × 130 (natural dimensions of PNG)
             Red: #C8102E  |  Font: approximated with SVG paths
             The font used in the original is a wide bold sans.
             We use geometric SVG paths for letter-perfect shapes.
        ══════════════════════════════════════════════════════ --}}
        <svg id="fol-svg-logo"
             viewBox="0 0 400 130"
             xmlns="http://www.w3.org/2000/svg"
             aria-label="FlyoverBD"
             overflow="visible">

            {{-- ─── F ─── --}}
            <g id="fol-F" class="fol-ltr">
                <rect x="2"  y="8"  width="13" height="72" rx="2" fill="#C8102E"/>
                <rect x="2"  y="8"  width="44" height="13" rx="2" fill="#C8102E"/>
                <rect x="2"  y="38" width="34" height="12" rx="2" fill="#C8102E"/>
            </g>

            {{-- ─── L ─── --}}
            <g id="fol-L" class="fol-ltr">
                <rect x="52" y="8"  width="13" height="72" rx="2" fill="#C8102E"/>
                <rect x="52" y="68" width="44" height="12" rx="2" fill="#C8102E"/>
            </g>

            {{-- ─── Y ─── --}}
            <g id="fol-Y" class="fol-ltr">
                {{-- Left arm --}}
                <path d="M102 8 L122 44 L109 44 L102 8 Z" fill="#C8102E"/>
                {{-- Right arm --}}
                <path d="M152 8 L132 44 L145 44 L152 8 Z" fill="#C8102E"/>
                {{-- Stem --}}
                <rect x="116" y="41" width="13" height="39" rx="2" fill="#C8102E"/>
            </g>

            {{-- ─── O — the GLOBE (animated separately) ─── --}}
            {{-- Globe centre: (200, 53) in viewBox, radius 44 --}}
            <g id="fol-ggrp">
                {{-- Red globe circle --}}
                <circle cx="200" cy="53" r="44" fill="#C8102E"/>

                {{-- White swoosh arc (lower-left → upper-right) --}}
                <path id="fol-arc"
                      d="M 165 72 Q 200 18 235 38"
                      fill="none"
                      stroke="white"
                      stroke-width="4"
                      stroke-linecap="round"/>

                {{-- White airplane in upper-right of globe --}}
                <g transform="translate(228,30) rotate(-38)">
                    {{-- Body --}}
                    <path d="M-13,0 L14,-4 L14,4 Z" fill="white"/>
                    {{-- Top wing --}}
                    <path d="M0,-4 L-8,-17 L-13,-15 L-5,0 Z" fill="white" opacity="0.92"/>
                    {{-- Bottom wing --}}
                    <path d="M0,4 L-8,17 L-13,15 L-5,0 Z" fill="white" opacity="0.92"/>
                    {{-- Tail fin top --}}
                    <path d="M-10,0 L-17,-6 L-18,-4 L-12,1 Z" fill="white" opacity="0.75"/>
                    {{-- Tail fin bottom --}}
                    <path d="M-10,0 L-17,6 L-18,4 L-12,-1 Z" fill="white" opacity="0.75"/>
                    {{-- Nose tip --}}
                    <circle cx="14" cy="0" r="2.5" fill="white"/>
                </g>

                {{-- Outer dashed orbit ring --}}
                <ellipse cx="200" cy="53" rx="41" ry="16"
                         fill="none" stroke="white"
                         stroke-width="1.8"
                         stroke-dasharray="7 5"
                         opacity="0.28"
                         transform="rotate(-15,200,53)"/>
            </g>

            {{-- ─── V ─── --}}
            <g id="fol-V" class="fol-ltr">
                {{-- Left leg --}}
                <path d="M250 8 L270 80 L257 80 L237 8 Z" fill="#C8102E"/>
                {{-- Right leg --}}
                <path d="M300 8 L280 80 L267 80 L287 8 Z" fill="#C8102E"/>
            </g>

            {{-- ─── E ─── --}}
            <g id="fol-E" class="fol-ltr">
                <rect x="308" y="8"  width="13" height="72" rx="2" fill="#C8102E"/>
                <rect x="308" y="8"  width="54" height="13" rx="2" fill="#C8102E"/>
                <rect x="308" y="38" width="42" height="12" rx="2" fill="#C8102E"/>
                <rect x="308" y="67" width="54" height="13" rx="2" fill="#C8102E"/>
            </g>

            {{-- ─── R ─── --}}
            <g id="fol-R" class="fol-ltr">
                {{-- Vertical stem --}}
                <rect x="370" y="8"  width="13" height="72" rx="2" fill="#C8102E"/>
                {{-- Top bar --}}
                <rect x="370" y="8"  width="26" height="13" rx="2" fill="#C8102E"/>
                {{-- Middle bar --}}
                <rect x="370" y="36" width="26" height="12" rx="2" fill="#C8102E"/>
                {{-- Top-right bump --}}
                <rect x="383" y="8"  width="14" height="40" rx="7" fill="#C8102E"/>
                {{-- Diagonal leg --}}
                <path d="M378 48 L398 80 L385 80 L365 48 Z" fill="#C8102E"/>
            </g>

            {{-- ─── Tagline text (inside SVG, bottom area) ─── --}}
            {{-- We do NOT draw tagline in SVG; it's in HTML below --}}

        </svg>

        {{-- Real logo.png — revealed from smoke after blast-off --}}
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

    {{-- Animated plane (absolute within #flyover-loader) --}}
    <div id="fol-plane" aria-hidden="true">
        <svg viewBox="0 0 34 34"
             xmlns="http://www.w3.org/2000/svg"
             overflow="visible">
            <g transform="translate(17,17)">
                {{-- Body --}}
                <path d="M-11,0 L11,-3 L11,3 Z" fill="#C8102E"/>
                {{-- Top wing --}}
                <path d="M-1,-3 L-6,-12.5 L-9.5,-11 L-4,0 Z" fill="#C8102E" opacity="0.9"/>
                {{-- Bottom wing --}}
                <path d="M-1,3 L-6,12.5 L-9.5,11 L-4,0 Z" fill="#C8102E" opacity="0.9"/>
                {{-- Tail top --}}
                <path d="M-8,0 L-13.5,-5 L-14,-3.5 L-9,0.5 Z" fill="#C8102E" opacity="0.75"/>
                {{-- Tail bottom --}}
                <path d="M-8,0 L-13.5,5 L-14,3.5 L-9,-0.5 Z" fill="#C8102E" opacity="0.75"/>
                {{-- Nose --}}
                <circle cx="11" cy="0" r="2.2" fill="#C8102E"/>
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

    /* ── Elements ─────────────────────────────────────── */
    var loader    = document.getElementById('flyover-loader');
    var stage     = document.getElementById('fol-stage');
    var svgLogo   = document.getElementById('fol-svg-logo');
    var realLogo  = document.getElementById('fol-logo-real');
    var gGrp      = document.getElementById('fol-ggrp');
    var arc       = document.getElementById('fol-arc');
    var orbEll    = document.getElementById('fol-orb-ell');
    var plane     = document.getElementById('fol-plane');
    var tagline   = document.getElementById('fol-tagline');
    var dots      = document.getElementById('fol-dots');
    if (!loader) return;

    /* ── Globe position in the SVG viewBox (400×130) ─────────────
     * Globe centre: cx=200, cy=53, r=44
     * SVG displayed at 300px wide → scale = 300/400 = 0.75
     * In display px (relative to SVG/stage top-left):
     *   cx_display = 200 * 0.75 = 150
     *   cy_display = 53  * 0.75 = 39.75
     *   r_display  = 44  * 0.75 = 33
     * Orbit (in display px): rx = 46, ry = 18, tilt = -15°
     ────────────────────────────────────────────────────────────── */
    var SVG_VB_W  = 400, SVG_VB_H = 130;
    var SVG_DISP  = 300; /* display width */
    var SCALE     = SVG_DISP / SVG_VB_W; /* 0.75 */

    /* Globe centre in viewBox coords */
    var GCX_VB = 200, GCY_VB = 53;

    /* Orbit parameters in display-px */
    var ORB_RX   = 48 * SCALE;  /* 36 px */
    var ORB_RY   = 19 * SCALE;  /* ~14 px */
    var ORB_TILT = -15;         /* degrees */

    /* Plane element half-size */
    var PH = 17; /* half of 34px */

    /* Globe centre in loader-relative coords (computed after layout) */
    var gCxL = 0, gCyL = 0;

    function measureGlobe() {
        var loaderR = loader.getBoundingClientRect();
        var stageR  = stage.getBoundingClientRect();
        /* SVG sits at top-left of stage */
        var svgLeft = stageR.left - loaderR.left;
        var svgTop  = stageR.top  - loaderR.top;
        gCxL = svgLeft + GCX_VB * SCALE;
        gCyL = svgTop  + GCY_VB * SCALE;

        /* Set orbit ellipse on the overlay SVG (stage coords) */
        var gCxS = GCX_VB * SCALE;
        var gCyS = GCY_VB * SCALE;
        orbEll.setAttribute('cx', '' + gCxS);
        orbEll.setAttribute('cy', '' + gCyS);
        orbEll.setAttribute('rx', '' + ORB_RX);
        orbEll.setAttribute('ry', '' + ORB_RY);
        orbEll.setAttribute('transform',
            'rotate(' + ORB_TILT + ',' + gCxS + ',' + gCyS + ')');
    }

    /* ── Math helpers ─────────────────────────────────────────── */
    function toRad(d) { return d * Math.PI / 180; }

    /* Returns {x,y,h} of plane on orbit ellipse at angle deg.
     * x,y are in loader-relative coords. h = heading in degrees. */
    function orbitPt(deg) {
        var a  = toRad(deg);
        var t  = toRad(ORB_TILT);
        var ex = ORB_RX * Math.cos(a);
        var ey = ORB_RY * Math.sin(a);
        /* rotate by tilt */
        var rx = ex * Math.cos(t) - ey * Math.sin(t);
        var ry = ex * Math.sin(t) + ey * Math.cos(t);
        /* tangent heading */
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

    /* ── Orbit rAF loop ───────────────────────────────────────── */
    var angle    = -90; /* start at top (12 o'clock) */
    var speed    = 2.5; /* deg/frame */
    var orbiting = false;
    var rafId    = null;

    function tick() {
        if (!orbiting) return;
        angle += speed;
        var p = orbitPt(angle);
        setPlane(p.x, p.y, p.h, 1);
        rafId = requestAnimationFrame(tick);
    }

    /* ── Phase 1 — letters drop in (CSS class stagger) ────────── */
    function phase1Letters() {
        var ids = ['fol-F','fol-L','fol-Y','fol-V','fol-E','fol-R'];
        ids.slice(0,3).forEach(function (id, i) {
            setTimeout(function () {
                var el = document.getElementById(id);
                if (el) el.classList.add('fol-in');
            }, i * 90);
        });
        /* V E R come in after globe */
        ids.slice(3).forEach(function (id, i) {
            setTimeout(function () {
                var el = document.getElementById(id);
                if (el) el.classList.add('fol-in');
            }, 580 + i * 90);
        });
    }

    /* ── Phase 2 — globe pops in ──────────────────────────────── */
    function phase2Globe() {
        if (gGrp) gGrp.classList.add('fol-in');
    }

    /* ── Phase 3 — arc draws + plane flies in ─────────────────── */
    function phase3Plane() {
        /* Draw arc */
        if (arc) arc.classList.add('fol-draw');

        measureGlobe();

        var loaderR = loader.getBoundingClientRect();
        var startX  = loaderR.width  * 0.82;
        var startY  = loaderR.height * 0.80;

        /* Place plane at start position */
        plane.style.transition = 'none';
        plane.style.opacity    = '1';
        setPlane(startX, startY, -35, 0.3);

        /* Target: top of orbit (12 o'clock) */
        var p0 = orbitPt(-90);

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                plane.style.transition =
                    'transform 0.65s cubic-bezier(0.25,0.46,0.45,0.94),' +
                    'opacity 0.3s ease';
                setPlane(p0.x, p0.y, p0.h, 1);
            });
        });

        /* Show orbit ellipse */
        setTimeout(function () {
            orbEll.classList.add('fol-show');
        }, 380);

        /* Start rAF orbit */
        setTimeout(function () {
            plane.style.transition = 'none';
            orbiting = true;
            rafId = requestAnimationFrame(tick);
        }, 720);
    }

    /* ── Phase 4 — blast-off, smoke, logo reveal ──────────────── */
    var blasted = false;
    function blastOff() {
        if (blasted) return;
        blasted = true;
        if (rafId) cancelAnimationFrame(rafId);
        orbiting = false;

        /* Freeze plane at current orbit position */
        var p = orbitPt(angle);

        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                var loaderR = loader.getBoundingClientRect();
                plane.style.transition =
                    'transform 0.55s cubic-bezier(0.55,0,1,0.45),' +
                    'opacity 0.35s ease-in 0.14s';
                plane.style.opacity = '0';
                /* Blast upper-right */
                setPlane(
                    loaderR.width * 0.88,
                    -PH - 50,
                    -42, 0.18
                );
            });
        });

        /* ── Smoke puffs ──────────────────────────────────────── */
        var cx = gCxL, cy = gCyL;
        var puffData = [
            { r: 50, dx: -10, dy: -8,  delay: 80  },
            { r: 40, dx:  14, dy:-18,  delay: 140 },
            { r: 32, dx: -18, dy: 12,  delay: 200 },
            { r: 24, dx:  20, dy:  4,  delay: 260 },
            { r: 18, dx:   2, dy:-24,  delay: 320 },
            { r: 14, dx: -22, dy:-14,  delay: 380 }
        ];
        puffData.forEach(function (cfg) {
            var el = document.createElement('div');
            el.className = 'fol-puff';
            var d = cfg.r * 2;
            el.style.cssText =
                'width:'  + d + 'px;' +
                'height:' + d + 'px;' +
                'left:'   + (cx - cfg.r) + 'px;' +
                'top:'    + (cy - cfg.r) + 'px;' +
                'background:radial-gradient(circle,' +
                    'rgba(165,165,165,0.9) 0%,' +
                    'rgba(215,215,215,0) 70%);';
            loader.appendChild(el);

            setTimeout(function () {
                el.style.transition = 'transform 1s ease-out, opacity 1s ease-out';
                el.style.opacity    = '0.85';
                el.style.transform  = 'scale(1) translate(' + cfg.dx + 'px,' + cfg.dy + 'px)';
                setTimeout(function () {
                    el.style.opacity   = '0';
                    el.style.transform =
                        'scale(3) translate(' + (cfg.dx * 1.4) + 'px,' + (cfg.dy * 1.4) + 'px)';
                }, 220);
            }, cfg.delay);
        });

        /* ── Hide SVG logo + reveal real logo.png ─────────────── */
        setTimeout(function () {
            svgLogo.style.transition = 'opacity 0.4s ease';
            svgLogo.style.opacity    = '0';
            orbEll.style.opacity     = '0';
        }, 180);

        setTimeout(function () {
            realLogo.classList.add('fol-visible');
        }, 420);

        /* Show tagline + dots (if not already shown) */
        tagline.classList.add('fol-in');
        dots.classList.add('fol-in');

        /* Fade out loader */
        setTimeout(function () {
            loader.classList.add('fol-hide');
        }, 1000);
    }

    /* ── Boot sequence ─────────────────────────────────────────── */
    var t0   = Date.now();
    var MIN  = 2800; /* minimum show time ms */

    /* T=0: F L Y drop in */
    setTimeout(phase1Letters, 100);
    /* T=350: Globe pops in */
    setTimeout(phase2Globe,   350);
    /* T=700: V E R drop in + plane flies in */
    setTimeout(phase3Plane,   750);
    /* T=1300: tagline */
    setTimeout(function () { tagline.classList.add('fol-in'); }, 1300);
    /* T=1550: dots */
    setTimeout(function () { dots.classList.add('fol-in');    }, 1550);

    /* Blast off when page ready (min 2.8 s) */
    function scheduleBlast() {
        var wait = Math.max(0, MIN - (Date.now() - t0));
        setTimeout(blastOff, wait);
    }

    if (document.readyState === 'complete') {
        scheduleBlast();
    } else {
        window.addEventListener('load', scheduleBlast);
    }
    /* Hard cap */
    setTimeout(blastOff, 7000);

}());
</script>
