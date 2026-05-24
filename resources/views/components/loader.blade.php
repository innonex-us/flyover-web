{{-- ═══════════════════════════════════════════════════════════
     FlyoverBD — Cinematic Loader v2
     File: resources/views/components/loader.blade.php

     Animation sequence:
       0.00s  White screen fades in
       0.15s  Plane flies in from off-screen bottom-right
       0.70s  Plane begins orbiting the "O" globe
       0.70s  Orbit arc draws itself
       1.00s  Tagline fades up
       0.30s  Progress bar starts filling
       ~load  Plane blasts off to top-right corner (with motion blur)
       +0.1s  Smoke puffs burst from where plane was
       +0.5s  Smoke reveals crisp logo.png underneath
       +0.8s  Entire loader fades away
═══════════════════════════════════════════════════════════ --}}

<style>
/* ─── Google Font ──────────────────────────────────────────────────── */
@import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@800;900&display=swap');

/* ─── Overlay ───────────────────────────────────────────────────────── */
#flyover-loader {
    position: fixed;
    inset: 0;
    z-index: 99999;
    background: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    transition: opacity 0.7s ease, visibility 0.7s ease;
}
#flyover-loader.fol-hide {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

/* ─── Stage: holds logo + all overlays ─────────────────────────────── */
.fol-stage {
    position: relative;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

/* ─── Logo image ────────────────────────────────────────────────────── */
/*
 * The logo PNG is ~400×100px (approx). We display it at 280px wide.
 * The globe "O" centre is roughly at 50.5% from left, 38% from top.
 * At 280px wide (natural h ≈ 70px):
 *   globe-cx ≈ 141px from left edge of img
 *   globe-cy ≈ 27px from top edge of img
 * Globe radius ~28px at display size.
 */
.fol-logo {
    display: block;
    width: 280px;
    height: auto;
    opacity: 0;
    /* Logo fades in from underneath as smoke clears — triggered via JS */
    transition: opacity 0.6s ease;
}
.fol-logo.fol-logo-visible {
    opacity: 1;
}

/* ─── Pre-load logo overlay (for smoke reveal) ───────────────────────
 * Initially the logo is shown in the background at full opacity,
 * hidden by the white overlay pane. The pane dissolves as smoke does.
 * Actually simpler: logo starts invisible, smoke appears over where
 * plane was, then logo fades in as smoke fades.
 */

/* ─── Orbit canvas (absolute, over logo) ────────────────────────────
 * Positioned to sit exactly over the "O" globe in the logo.
 * Globe centre: left ~141px, top ~27px from img top-left.
 * We place a 80×80 SVG centred on that point.
 * img top-left within .fol-stage is at (0,0).
 */
.fol-orbit-canvas {
    position: absolute;
    /* centred on globe "O": 141 - 40 = 101 from left; 27 - 40 = -13 from top */
    left: 101px;
    top: -13px;
    width: 80px;
    height: 80px;
    pointer-events: none;
    overflow: visible;
}

/* ── Orbit arc draw ─────────────────────────────────────────────────── */
#fol-arc {
    stroke-dasharray: 90;
    stroke-dashoffset: 90;
    opacity: 0;
}
#fol-arc.draw {
    opacity: 1;
    animation: folDrawArc 0.7s ease-out forwards;
}
@keyframes folDrawArc {
    to { stroke-dashoffset: 0; }
}

/* ── Plane container ────────────────────────────────────────────────── */
#fol-plane-el {
    position: absolute;
    width: 28px;
    height: 28px;
    pointer-events: none;
    /* Start off-screen bottom-right */
    transform: translate(200px, 120px) rotate(45deg) scale(0.3);
    opacity: 0;
    will-change: transform, opacity;
}
#fol-plane-el svg {
    width: 100%;
    height: 100%;
}

/* ── Blast-off ──────────────────────────────────────────────────────── */
#fol-plane-el.blastoff {
    transition: transform 0.55s cubic-bezier(0.55, 0, 1, 0.45),
                opacity   0.35s ease-in 0.2s;
}

/* ── Smoke puffs container ──────────────────────────────────────────── */
.fol-smokes {
    position: absolute;
    /* centred on globe "O" */
    left: 141px;
    top: 27px;
    width: 0;
    height: 0;
    pointer-events: none;
    overflow: visible;
}
.fol-smoke {
    position: absolute;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(180,180,180,0.85) 0%, rgba(220,220,220,0) 70%);
    opacity: 0;
    transform: scale(0.2);
}

/* ── Tagline ─────────────────────────────────────────────────────────── */
.fol-tagline {
    margin-top: 6px;
    font-family: 'Plus Jakarta Sans', Arial, sans-serif;
    font-size: 10px;
    font-weight: 800;
    letter-spacing: 4px;
    color: #1a1a1a;
    text-transform: uppercase;
    opacity: 0;
    transition: opacity 0.5s ease;
}
.fol-tagline.visible { opacity: 1; }

/* ── Progress bar ────────────────────────────────────────────────────── */
.fol-bar-wrap {
    margin-top: 32px;
    width: 210px;
    height: 3px;
    background: #eee;
    border-radius: 99px;
    overflow: hidden;
}
.fol-bar {
    height: 100%;
    width: 0;
    background: linear-gradient(90deg, #C8102E 0%, #ff6b6b 100%);
    border-radius: 99px;
    animation: folBar 3s ease-in-out forwards 0.3s;
}
@keyframes folBar {
    0%   { width: 0%; }
    40%  { width: 55%; }
    80%  { width: 88%; }
    100% { width: 100%; }
}

/* ── Dots ────────────────────────────────────────────────────────────── */
.fol-dots {
    margin-top: 14px;
    display: flex;
    gap: 6px;
    opacity: 0;
    transition: opacity 0.4s ease;
}
.fol-dots.visible { opacity: 1; }
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

    <div class="fol-stage" id="fol-stage">

        {{-- Real logo image (starts invisible, revealed after blast-off) --}}
        <img id="fol-logo-img"
             src="{{ asset('logo.png') }}"
             alt="FlyoverBD"
             class="fol-logo"
             draggable="false">

        {{-- Orbit SVG canvas (sits over the "O" globe) --}}
        <svg class="fol-orbit-canvas"
             viewBox="0 0 80 80"
             xmlns="http://www.w3.org/2000/svg"
             overflow="visible"
             aria-hidden="true">

            {{-- Dashed orbit ellipse --}}
            <ellipse id="fol-ellipse"
                     cx="40" cy="40" rx="34" ry="14"
                     fill="none"
                     stroke="#C8102E"
                     stroke-width="1"
                     stroke-dasharray="5 4"
                     opacity="0"
                     transform="rotate(-15,40,40)"/>

            {{-- Arc that "draws in" --}}
            <path id="fol-arc"
                  d="M 10 28 Q 40 8 70 28"
                  fill="none"
                  stroke="#C8102E"
                  stroke-width="1.5"
                  stroke-linecap="round"
                  opacity="0"/>
        </svg>

        {{-- Smoke puffs origin point --}}
        <div class="fol-smokes" id="fol-smokes"></div>

        {{-- Plane element (absolutely positioned, JS moves it) --}}
        <div id="fol-plane-el" style="left:0;top:0;position:absolute;">
            <svg viewBox="0 0 28 28" xmlns="http://www.w3.org/2000/svg" overflow="visible">
                {{-- Airplane pointing right --}}
                <g transform="translate(14,14)">
                    {{-- Body --}}
                    <path d="M-8,0 L8,-2.5 L8,2.5 Z" fill="#C8102E"/>
                    {{-- Top wing --}}
                    <path d="M-2,-2 L2,-10 L5,-9 L0,0 Z" fill="#C8102E" opacity="0.9"/>
                    {{-- Bottom wing --}}
                    <path d="M-2,2 L2,10 L5,9 L0,0 Z" fill="#C8102E" opacity="0.9"/>
                    {{-- Tail fin top --}}
                    <path d="M-7,0 L-10,-4 L-8,-4 L-5,0 Z" fill="#C8102E" opacity="0.75"/>
                    {{-- Tail fin bottom --}}
                    <path d="M-7,0 L-10,4 L-8,4 L-5,0 Z" fill="#C8102E" opacity="0.75"/>
                    {{-- Nose --}}
                    <circle cx="8" cy="0" r="1.5" fill="#C8102E"/>
                </g>
            </svg>
        </div>

    </div>

    {{-- Tagline --}}
    <p class="fol-tagline" id="fol-tagline">Make Your Life Borderless</p>

    {{-- Progress bar --}}
    <div class="fol-bar-wrap">
        <div class="fol-bar"></div>
    </div>

    {{-- Pulse dots --}}
    <div class="fol-dots" id="fol-dots" aria-hidden="true">
        <span class="fol-dot"></span>
        <span class="fol-dot"></span>
        <span class="fol-dot"></span>
    </div>

</div>

<script>
(function () {
    'use strict';

    /* ── Elements ──────────────────────────────────────────────── */
    var loader    = document.getElementById('flyover-loader');
    var logo      = document.getElementById('fol-logo-img');
    var plane     = document.getElementById('fol-plane-el');
    var arc       = document.getElementById('fol-arc');
    var ellipse   = document.getElementById('fol-ellipse');
    var smokes    = document.getElementById('fol-smokes');
    var tagline   = document.getElementById('fol-tagline');
    var dots      = document.getElementById('fol-dots');
    if (!loader || !plane) return;

    /* ── Globe "O" centre in the logo ──────────────────────────
     * logo.png displayed at 280px wide, natural ~400×100px
     * globe cx ≈ 50.5% → 141px, cy ≈ 38% → top of img
     * img offsetLeft/Top within stage gives absolute offset.
     */
    var GLOBE_LEFT_PCT = 0.505;  /* fraction of logo width  */
    var GLOBE_TOP_PCT  = 0.38;   /* fraction of logo height */

    /* Orbit parameters (display pixels) */
    var ORB_RX   = 34;   /* semi-major axis */
    var ORB_RY   = 14;   /* semi-minor axis */
    var ORB_TILT = -15;  /* degrees */
    var PLANE_HALF = 14; /* half plane size for centering */

    var orbitAngle = -90; /* start at top (12 o'clock) */
    var orbitSpeed = 2.2; /* degrees per rAF tick */
    var rafId      = null;
    var orbiting   = false;
    var blasted    = false;

    /* Globe centre relative to the .fol-stage */
    var globeCx, globeCy;

    function computeGlobeCentre() {
        var imgRect   = logo.getBoundingClientRect();
        var stageRect = logo.parentElement.getBoundingClientRect();
        globeCx = (imgRect.left - stageRect.left) + imgRect.width  * GLOBE_LEFT_PCT;
        globeCy = (imgRect.top  - stageRect.top)  + imgRect.height * GLOBE_TOP_PCT;
    }

    function toRad(d) { return d * Math.PI / 180; }

    /* Return {x,y,heading} on the tilted ellipse at angleDeg */
    function ellipsePoint(angleDeg) {
        var a = toRad(angleDeg);
        var t = toRad(ORB_TILT);
        var ex = ORB_RX * Math.cos(a);
        var ey = ORB_RY * Math.sin(a);
        /* rotate by tilt */
        var rx = ex * Math.cos(t) - ey * Math.sin(t);
        var ry = ex * Math.sin(t) + ey * Math.cos(t);
        /* tangent */
        var dx = -ORB_RX * Math.sin(a);
        var dy =  ORB_RY * Math.cos(a);
        var heading = Math.atan2(
            dy * Math.cos(t) + dx * Math.sin(t),
            dx * Math.cos(t) - dy * Math.sin(t)
        ) * 180 / Math.PI;
        return { x: rx, y: ry, heading: heading };
    }

    function movePlaneToOrbit(angleDeg) {
        var p = ellipsePoint(angleDeg);
        plane.style.transform =
            'translate(' + (globeCx + p.x - PLANE_HALF) + 'px, ' +
                           (globeCy + p.y - PLANE_HALF) + 'px) ' +
            'rotate(' + p.heading + 'deg)';
    }

    /* ── Tick ──────────────────────────────────────────────────── */
    function tick() {
        if (!orbiting) return;
        orbitAngle += orbitSpeed;
        movePlaneToOrbit(orbitAngle);
        rafId = requestAnimationFrame(tick);
    }

    /* ── Phase 1: Fly-in ──────────────────────────────────────── */
    /* Plane starts at bottom-right of stage, flies to globe */
    function startFlyIn() {
        computeGlobeCentre();

        /* Position plane far bottom-right */
        var stage = logo.parentElement.getBoundingClientRect();
        var startX = stage.width  * 0.85;
        var startY = stage.height * 1.2;

        plane.style.transition = 'none';
        plane.style.opacity    = '1';
        plane.style.transform  =
            'translate(' + startX + 'px, ' + startY + 'px) ' +
            'rotate(-35deg) scale(0.35)';

        /* Tiny delay so the browser registers the start state */
        requestAnimationFrame(function () {
            requestAnimationFrame(function () {
                /* Fly to globe top (12 o'clock) */
                var p = ellipsePoint(-90);
                plane.style.transition =
                    'transform 0.55s cubic-bezier(0.25,0.46,0.45,0.94), ' +
                    'opacity 0.3s ease';
                plane.style.transform =
                    'translate(' + (globeCx + p.x - PLANE_HALF) + 'px, ' +
                                   (globeCy + p.y - PLANE_HALF) + 'px) ' +
                    'rotate(' + p.heading + 'deg) scale(1)';
            });
        });
    }

    /* ── Phase 2: Start orbit ─────────────────────────────────── */
    function startOrbit() {
        plane.style.transition = 'none';
        /* Draw orbit ellipse */
        ellipse.style.opacity = '0.35';
        ellipse.style.transition = 'opacity 0.4s ease';
        /* Draw arc */
        arc.classList.add('draw');

        orbiting = true;
        rafId = requestAnimationFrame(tick);
    }

    /* ── Phase 3: Blast-off + smoke + logo reveal ─────────────── */
    function blastOff() {
        if (blasted) return;
        blasted = true;
        if (rafId) cancelAnimationFrame(rafId);
        orbiting = false;

        /* Get current plane position (from orbit) */
        var p = ellipsePoint(orbitAngle);
        var cx = globeCx + p.x;
        var cy = globeCy + p.y;

        /* Blast plane to top-right */
        plane.style.transition =
            'transform 0.5s cubic-bezier(0.55, 0, 1, 0.45), ' +
            'opacity 0.3s ease-in 0.18s';
        var stage    = logo.parentElement;
        var stageW   = stage.offsetWidth;
        plane.style.transform =
            'translate(' + (stageW * 0.92 - PLANE_HALF) + 'px, ' +
                           (-80 - PLANE_HALF) + 'px) ' +
            'rotate(-40deg) scale(0.2)';
        plane.style.opacity = '0';

        /* Smoke puffs */
        var puffs = [
            { size: 64, dx: -5,  dy: -5,  delay: 60  },
            { size: 48, dx: 10,  dy: -12, delay: 120 },
            { size: 38, dx: -12, dy: 8,   delay: 180 },
            { size: 28, dx: 15,  dy: 3,   delay: 240 },
            { size: 20, dx: 0,   dy: -18, delay: 300 }
        ];
        puffs.forEach(function (cfg) {
            var div = document.createElement('div');
            div.className = 'fol-smoke';
            var s = cfg.size;
            div.style.cssText =
                'width:'  + s + 'px;' +
                'height:' + s + 'px;' +
                'left:'   + (cx - s/2 + cfg.dx) + 'px;' +
                'top:'    + (cy - s/2 + cfg.dy) + 'px;' +
                'position:absolute;' +
                'border-radius:50%;' +
                'background:radial-gradient(circle,rgba(180,180,180,0.9) 0%,rgba(230,230,230,0) 70%);' +
                'pointer-events:none;' +
                'opacity:0;' +
                'transform:scale(0.2);';
            /* Append to document body so position is viewport-based */
            /* Instead, append to loader and correct position */
            var loaderRect = loader.getBoundingClientRect();
            var stageRect  = stage.getBoundingClientRect();
            div.style.left = (stageRect.left - loaderRect.left + cx - s/2 + cfg.dx) + 'px';
            div.style.top  = (stageRect.top  - loaderRect.top  + cy - s/2 + cfg.dy) + 'px';
            loader.appendChild(div);

            setTimeout(function () {
                div.style.transition =
                    'transform 1.0s ease-out, opacity 1.0s ease-out';
                div.style.opacity   = '0.9';
                div.style.transform = 'scale(2.5) translate(' + (-cfg.dx*0.5) + 'px,' + (-Math.abs(cfg.dy)*0.8) + 'px)';
                setTimeout(function () {
                    div.style.opacity   = '0';
                    div.style.transform = 'scale(4) translate(' + (-cfg.dx) + 'px,' + (-Math.abs(cfg.dy)*1.5) + 'px)';
                }, 300);
            }, cfg.delay);
        });

        /* Logo reveal — fades in as smoke dissipates */
        setTimeout(function () {
            logo.classList.add('fol-logo-visible');
        }, 350);

        /* Fade tagline & dots */
        setTimeout(function () {
            tagline.classList.add('visible');
            dots.classList.add('visible');
        }, 550);

        /* Hide loader */
        setTimeout(function () {
            loader.classList.add('fol-hide');
        }, 950);
    }

    /* ── Scheduling ──────────────────────────────────────────── */
    var MIN_SHOW = 2600; /* ms minimum */
    var t0 = Date.now();

    /* Start fly-in after short delay */
    setTimeout(startFlyIn, 200);
    /* Begin orbit after fly-in completes (0.55s) */
    setTimeout(startOrbit, 820);
    /* Show tagline */
    setTimeout(function () { tagline.classList.add('visible'); }, 980);
    /* Show dots */
    setTimeout(function () { dots.classList.add('visible');   }, 1250);

    function scheduleBlastOff() {
        var elapsed = Date.now() - t0;
        var wait    = Math.max(0, MIN_SHOW - elapsed);
        setTimeout(blastOff, wait);
    }

    if (document.readyState === 'complete') {
        scheduleBlastOff();
    } else {
        window.addEventListener('load', scheduleBlastOff);
    }

    /* Hard cap */
    setTimeout(blastOff, 6500);

}());
</script>
