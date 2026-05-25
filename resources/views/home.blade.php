@php
    $metaImage = asset('banner/hero-banner-1.png');
@endphp
<x-app-layout
    title="FlyoverBD | Tours, Visa & Travel Packages Bangladesh"
    meta_description="Bangladesh's trusted travel agency for tour packages, visa processing, hotel bookings & airport transfers. Book your dream vacation with FlyoverBD."
    :meta_image="$metaImage"
>
    @push('meta')
    {{-- Organization Structured Data --}}
    <script type="application/ld+json">
    {
        "@@context": "https://schema.org",
        "@type": "TravelAgency",
        "name": "FlyoverBD",
        "alternateName": "FlyoverBD Travel Agency",
        "url": {{ Illuminate\Support\Js::from(config('app.url')) }},
        "logo": {{ Illuminate\Support\Js::from(asset('logo.png')) }},
        "image": {{ Illuminate\Support\Js::from(asset('banner/hero-banner-1.png')) }},
        "description": "Bangladesh's trusted travel agency for tour packages, visa processing, and holiday packages.",
        "address": {
            "@type": "PostalAddress",
            "streetAddress": "House 45, Road 13, Block D, Banani",
            "addressLocality": "Dhaka",
            "addressCountry": "BD"
        },
        "telephone": "+8809611677989",
        "email": "info@flyoverbd.net",
        "sameAs": [
            "https://www.facebook.com/flyoverbd",
            "https://twitter.com/flyoverbd"
        ]
    }
    </script>
    @endpush

{{-- ═══════════════════════════════════════
     HERO  - image slider + search card
═══════════════════════════════════════ --}}
<div class="relative" style="min-height:560px;"
    x-data="{
        s:0,
        waterCtx: null,
        waterCanvas: null,
        waterFrame: null,
        pointer: { x: 0, y: 0, tx: 0, ty: 0, active: false, strength: 0 },
        waves: [],
        init(){
            this.$nextTick(() => this.setupWater());
            setInterval(()=>this.s=(this.s+1)%3, 5500);
        },
        setupWater(){
            this.waterCanvas = this.$refs.waterCanvas;
            if (!this.waterCanvas) return;

            const resize = () => {
                const dpr = window.devicePixelRatio || 1;
                const canvas = this.waterCanvas;
                canvas.width = Math.floor(canvas.clientWidth * dpr);
                canvas.height = Math.floor(canvas.clientHeight * dpr);
                this.waterCtx = canvas.getContext('2d');
                if (this.waterCtx) {
                    this.waterCtx.setTransform(dpr, 0, 0, dpr, 0, 0);
                }
            };

            resize();
            window.addEventListener('resize', resize, { passive: true });

            this.pointer.x = this.pointer.tx = this.waterCanvas.clientWidth / 2;
            this.pointer.y = this.pointer.ty = this.waterCanvas.clientHeight / 2;

            const animate = () => {
                this.drawWater();
                this.waterFrame = requestAnimationFrame(animate);
            };

            animate();
        },
        trackWater(event){
            if (!this.waterCanvas) return;
            const rect = this.waterCanvas.getBoundingClientRect();
            this.pointer.tx = event.clientX - rect.left;
            this.pointer.ty = event.clientY - rect.top;
            this.pointer.active = true;

            const dx = this.pointer.tx - this.pointer.x;
            const dy = this.pointer.ty - this.pointer.y;
            const distance = Math.sqrt(dx * dx + dy * dy);

            if (distance > 10) {
                this.waves.push({
                    x: this.pointer.tx,
                    y: this.pointer.ty,
                    radius: 28,
                    alpha: 0.18,
                    spread: 0.95,
                    drift: Math.min(1.6, distance / 140)
                });
                if (this.waves.length > 14) this.waves.shift();
            }
        },
        settleWater(){
            this.pointer.active = false;
        },
        drawWater(){
            if (!this.waterCtx || !this.waterCanvas) return;

            const ctx = this.waterCtx;
            const width = this.waterCanvas.clientWidth;
            const height = this.waterCanvas.clientHeight;

            this.pointer.x += (this.pointer.tx - this.pointer.x) * 0.08;
            this.pointer.y += (this.pointer.ty - this.pointer.y) * 0.08;
            this.pointer.strength += ((this.pointer.active ? 1 : 0) - this.pointer.strength) * 0.06;

            ctx.clearRect(0, 0, width, height);
            ctx.globalCompositeOperation = 'screen';

            const washRadius = 180 + this.pointer.strength * 100;
            const wash = ctx.createRadialGradient(this.pointer.x, this.pointer.y, 0, this.pointer.x, this.pointer.y, washRadius);
            wash.addColorStop(0, `rgba(255,255,255,${0.16 + this.pointer.strength * 0.05})`);
            wash.addColorStop(0.3, `rgba(255,255,255,${0.08 + this.pointer.strength * 0.03})`);
            wash.addColorStop(0.7, 'rgba(255,255,255,0)');
            ctx.filter = 'blur(24px)';
            ctx.fillStyle = wash;
            ctx.beginPath();
            ctx.arc(this.pointer.x, this.pointer.y, washRadius, 0, Math.PI * 2);
            ctx.fill();

            const bandY = this.pointer.y + Math.sin(Date.now() / 420) * 8;
            ctx.filter = 'blur(18px)';
            const band = ctx.createLinearGradient(this.pointer.x - 240, bandY, this.pointer.x + 240, bandY);
            band.addColorStop(0, 'rgba(255,255,255,0)');
            band.addColorStop(0.32, `rgba(255,255,255,${0.06 + this.pointer.strength * 0.03})`);
            band.addColorStop(0.5, `rgba(255,255,255,${0.14 + this.pointer.strength * 0.05})`);
            band.addColorStop(0.68, `rgba(255,255,255,${0.06 + this.pointer.strength * 0.03})`);
            band.addColorStop(1, 'rgba(255,255,255,0)');
            ctx.fillStyle = band;
            ctx.beginPath();
            ctx.ellipse(this.pointer.x, bandY, 220 + this.pointer.strength * 50, 26 + this.pointer.strength * 8, 0, 0, Math.PI * 2);
            ctx.fill();

            this.waves = this.waves.filter((wave) => {
                wave.radius += 0.55 + wave.drift * 0.18;
                wave.alpha *= 0.972;
                wave.spread += 0.02;

                if (wave.alpha <= 0.01 || wave.radius > 260) return false;

                const glow = ctx.createRadialGradient(wave.x, wave.y, wave.radius * 0.45, wave.x, wave.y, wave.radius * 1.65);
                glow.addColorStop(0, `rgba(255,255,255,${wave.alpha * 0.18})`);
                glow.addColorStop(0.42, `rgba(255,255,255,${wave.alpha * 0.08})`);
                glow.addColorStop(1, 'rgba(255,255,255,0)');

                ctx.filter = 'blur(20px)';
                ctx.fillStyle = glow;
                ctx.beginPath();
                ctx.ellipse(wave.x, wave.y, wave.radius, wave.radius * 0.55, 0.2, 0, Math.PI * 2);
                ctx.fill();

                ctx.filter = 'none';

                return true;
            });

            ctx.globalCompositeOperation = 'source-over';
            ctx.filter = 'none';
        }
    }"
    @mousemove.passive="trackWater($event)"
    @mouseleave="settleWater()">

    {{-- Slides (overflow-hidden keeps scaled images clipped) --}}
    <div class="absolute inset-0 overflow-hidden">
    <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000"
            style="background-image:url('{{ asset('banner/hero-banner-5.jpg') }}'); filter: blur(.45px) saturate(1.08) brightness(1.01); transform: scale(1.025);"
         :class="s===0?'opacity-100':'opacity-0'"></div>
    <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000"
            style="background-image:url('{{ asset('banner/hero-banner-4.jpg') }}'); filter: blur(.45px) saturate(1.08) brightness(1.01); transform: scale(1.025);"
         :class="s===1?'opacity-100':'opacity-0'"></div>
        <div class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000"
            style="background-image:url('{{ asset('banner/hero-banner-1.png') }}'); filter: blur(.45px) saturate(1.08) brightness(1.01); transform: scale(1.025);"
            :class="s===2?'opacity-100':'opacity-0'"></div>
    </div>{{-- /slides overflow-hidden --}}

        <canvas x-ref="waterCanvas" class="absolute inset-0 z-[2] h-full w-full pointer-events-none mix-blend-screen opacity-75"></canvas>

        {{-- Special atmosphere layer --}}
        <div class="absolute inset-0 pointer-events-none"
            style="background-image:
                 radial-gradient(circle at 18% 20%, rgba(255,255,255,.22) 0, rgba(255,255,255,0) 28%),
                 radial-gradient(circle at 82% 18%, rgba(200,16,46,.34) 0, rgba(200,16,46,0) 24%),
                 radial-gradient(circle at 50% 82%, rgba(248,184,3,.18) 0, rgba(248,184,3,0) 30%),
                 linear-gradient(120deg, rgba(255,255,255,.10) 0%, rgba(255,255,255,0) 26%, rgba(255,255,255,0) 74%, rgba(0,0,0,.18) 100%);
                 mix-blend-mode: screen;
                 opacity: .72;"></div>

        <div class="absolute inset-0 pointer-events-none"
            style="background:
                 radial-gradient(circle at 12% 78%, rgba(200,16,46,.18), transparent 30%),
                 radial-gradient(circle at 88% 28%, rgba(248,184,3,.16), transparent 24%),
                 radial-gradient(circle at 52% 48%, rgba(255,255,255,.08), transparent 30%);
                 filter: blur(52px);
                 opacity: .68;"></div>

        {{-- Soft grid for depth --}}
        <div class="absolute inset-0 pointer-events-none opacity-20"
            style="background-image: linear-gradient(rgba(255,255,255,.10) 1px, transparent 1px), linear-gradient(90deg, rgba(255,255,255,.10) 1px, transparent 1px); background-size: 120px 120px; mask-image: linear-gradient(180deg, rgba(0,0,0,.95), rgba(0,0,0,.25) 85%, transparent 100%);"></div>

    {{-- Gradient overlay --}}
        <div class="absolute inset-0" style="background:linear-gradient(180deg,rgba(11,16,32,.26) 0%,rgba(0,0,0,.12) 34%,rgba(0,0,0,.34) 100%);"></div>

    {{-- Content --}}
    <div class="absolute inset-0 z-10 flex flex-col items-center justify-center px-4 py-10 text-center">
        <h1 class="text-white font-extrabold text-3xl sm:text-4xl md:text-6xl leading-tight mb-2 drop-shadow-xl"
            style="font-family:'Merriweather',Georgia,serif;">
            Discover <span class="text-red-400"> Beyond</span>
        </h1>
        <p class="text-white/80 text-sm sm:text-base md:text-lg mb-6 max-w-xl">Tours · Visa · Hotels · Airport Transfers. All in one trusted place.</p>

        {{-- ── Search Widget ── --}}
        <div class="mx-auto w-full max-w-3xl"
             x-data="{
                 tab: 'tours',
                 query: '', suggestions: [], show: false, loading: false, timer: null,
                 checkIn: null, checkOut: null, persons: 1,
                 travelDate: null, passengers: 1,
                 calOpen: '', calYear: new Date().getFullYear(), calMonth: new Date().getMonth(),
                 months: ['January','February','March','April','May','June','July','August','September','October','November','December'],
                 days: ['Su','Mo','Tu','We','Th','Fr','Sa'],
                 today: new Date(new Date().toDateString()),
                 get calLabel(){ return this.months[this.calMonth] + ' ' + this.calYear; },
                 calPrev(){ if(this.calMonth===0){this.calMonth=11;this.calYear--;}else{this.calMonth--;} },
                 calNext(){ if(this.calMonth===11){this.calMonth=0;this.calYear++;}else{this.calMonth++;} },
                 calDays(){
                     let d=[], first=new Date(this.calYear,this.calMonth,1).getDay(), daysIn=new Date(this.calYear,this.calMonth+1,0).getDate();
                     for(let i=0;i<first;i++) d.push(null);
                     for(let i=1;i<=daysIn;i++) d.push(new Date(this.calYear,this.calMonth,i));
                     return d;
                 },
                 fmtDate(d){ if(!d) return ''; return d.toLocaleDateString('en-US',{month:'short',day:'numeric',year:'numeric'}); },
                 fmtDateShort(d){ if(!d) return ''; return d.toLocaleDateString('en-US',{month:'short',day:'numeric'}); },
                 fmtISO(d){ if(!d) return ''; let m=(d.getMonth()+1).toString().padStart(2,'0'),dd=d.getDate().toString().padStart(2,'0'); return d.getFullYear()+'-'+m+'-'+dd; },
                 pickDate(d){
                     if(!d || d < this.today) return;
                     if(this.calOpen==='checkIn'||this.calOpen==='travelDate'){ this[this.calOpen]=d; this.calOpen=''; }
                     else if(this.calOpen==='checkOut'){ if(this.checkIn && d<=this.checkIn){ this.checkIn=d; }else{ this.checkOut=d; this.calOpen=''; } }
                 },
                 isSelected(d,field){ return d && this[field] && this[field].toDateString()===d.toDateString(); },
                 isInRange(d){ return d && this.checkIn && this.checkOut && d>this.checkIn && d<this.checkOut; },
                 isPast(d){ return d && d < this.today; },
                 openCal(field){ this.calOpen=this.calOpen===field?'':field; this.calYear=new Date().getFullYear(); this.calMonth=new Date().getMonth(); },
                 fetchSuggestions(){
                     if(this.tab==='transfers'){ return; }
                     this.loading=true; clearTimeout(this.timer);
                     this.timer=setTimeout(()=>{
                         window.fetch(`{{ route('search.suggestions') }}?type=${this.tab}&query=${encodeURIComponent(this.query)}`)
                             .then(r=>r.json()).then(d=>{ this.suggestions=d; this.show=d.length>0; this.loading=false; })
                             .catch(()=>this.loading=false);
                     }, 180);
                 },
                 go(url){ window.location.href=url; },
                 reset(t){ this.tab=t; this.query=''; this.suggestions=[]; this.show=false; this.calOpen=''; }
             }"
             @keydown.escape.window="show=false; calOpen=''"
             @click.outside="calOpen=''; show=false">

            {{-- Tabs --}}
            <div class="flex justify-center gap-1 mb-4 bg-white/10 backdrop-blur-md rounded-2xl p-1 w-full sm:w-fit mx-auto overflow-x-auto scrollbar-hide">
                <button @click="reset('tours')" class="flex-shrink-0 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center gap-1.5" :class="tab==='tours' ? 'bg-white text-gray-900 shadow-lg' : 'text-white hover:bg-white/10'">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" :class="tab==='tours'?'text-red-600':'text-current'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Tours
                </button>
                <button @click="reset('visas')" class="flex-shrink-0 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center gap-1.5" :class="tab==='visas' ? 'bg-white text-gray-900 shadow-lg' : 'text-white hover:bg-white/10'">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" :class="tab==='visas'?'text-red-600':'text-current'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0H6"/></svg>
                    Visa
                </button>
                <button @click="reset('hotels')" class="flex-shrink-0 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center gap-1.5" :class="tab==='hotels' ? 'bg-white text-gray-900 shadow-lg' : 'text-white hover:bg-white/10'">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" :class="tab==='hotels'?'text-red-600':'text-current'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    Hotels
                </button>
                <button @click="reset('transfers')" class="flex-shrink-0 px-3 sm:px-5 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-all flex items-center gap-1.5" :class="tab==='transfers' ? 'bg-white text-gray-900 shadow-lg' : 'text-white hover:bg-white/10'">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" :class="tab==='transfers'?'text-red-600':'text-current'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Pick &amp; Drop
                </button>
            </div>

            {{-- Search card --}}
            <div class="relative">

                {{-- ── Tours ── --}}
                <template x-if="tab==='tours'">
                    <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl shadow-black/25 p-3">
                        <div class="flex flex-col sm:flex-row items-stretch gap-2">
                            <div class="flex-1 flex items-center px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus-within:border-red-400 focus-within:ring-2 focus-within:ring-red-100 transition-all">
                                <svg class="w-4 h-4 text-gray-400 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <input type="text" x-model="query" @input="fetchSuggestions()" @focus="fetchSuggestions()"
                                       placeholder="Where do you want to go?"
                                       class="w-full text-gray-800 text-sm border-0 p-0 focus:ring-0 outline-none placeholder-gray-400 bg-transparent"
                                       autocomplete="off">
                                <div x-show="loading" class="w-3.5 h-3.5 border-2 border-red-500 border-t-transparent rounded-full animate-spin ml-2 flex-shrink-0"></div>
                            </div>
                            <button @click="if(suggestions.length){ go(suggestions[0].url) }else{ window.location='{{ route('packages.index') }}?search='+encodeURIComponent(query) }"
                                    class="w-full sm:w-auto bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold px-7 py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-red-600/30 whitespace-nowrap flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Search
                            </button>
                        </div>
                    </div>
                </template>

                {{-- ── Visas ── --}}
                <template x-if="tab==='visas'">
                    <div class="bg-white/95 backdrop-blur-md rounded-3xl shadow-2xl shadow-black/25 p-3">
                        <div class="flex flex-col sm:flex-row items-stretch gap-2">
                            <div class="flex-1 flex items-center px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl focus-within:border-red-400 focus-within:ring-2 focus-within:ring-red-100 transition-all">
                                <svg class="w-4 h-4 text-gray-400 mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <input type="text" x-model="query" @input="fetchSuggestions()" @focus="fetchSuggestions()"
                                       placeholder="Malaysia, Thailand, Schengen…"
                                       class="w-full text-gray-800 text-sm border-0 p-0 focus:ring-0 outline-none placeholder-gray-400 bg-transparent"
                                       autocomplete="off">
                                <div x-show="loading" class="w-3.5 h-3.5 border-2 border-red-500 border-t-transparent rounded-full animate-spin ml-2 flex-shrink-0"></div>
                            </div>
                            <button @click="window.location='{{ route('visas.index') }}?search='+encodeURIComponent(query)"
                                    class="w-full sm:w-auto bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold px-7 py-3.5 rounded-2xl text-sm transition-all shadow-lg shadow-red-600/30 whitespace-nowrap flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Find Visa
                            </button>
                        </div>
                    </div>
                </template>

                {{-- ── Hotels ── --}}
                <template x-if="tab==='hotels'">
                    <div class="bg-white rounded-3xl shadow-2xl shadow-black/25 p-4 text-left">
                        <div class="flex flex-col gap-3">
                            {{-- destination --}}
                            <div class="flex items-center px-3 py-3 bg-gray-50 border border-gray-200 rounded-2xl focus-within:border-red-400 focus-within:ring-2 focus-within:ring-red-100 transition-all">
                                <svg class="w-4 h-4 text-red-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <input type="text" x-model="query" @input="fetchSuggestions()" @focus="fetchSuggestions()"
                                       placeholder="Where do you want to stay?"
                                       class="w-full text-gray-800 text-sm border-0 p-0 focus:ring-0 outline-none placeholder-gray-400 bg-transparent"
                                       autocomplete="off">
                                <div x-show="loading" class="w-3.5 h-3.5 border-2 border-red-500 border-t-transparent rounded-full animate-spin ml-2 flex-shrink-0"></div>
                            </div>
                            {{-- date row --}}
                            <div class="grid grid-cols-3 gap-2">
                                <button type="button" @click.stop="openCal('checkIn')"
                                        class="flex items-center gap-1.5 px-2.5 py-3 bg-gray-50 border rounded-xl transition-all text-left col-span-1"
                                        :class="calOpen==='checkIn' ? 'border-red-400 bg-red-50' : 'border-gray-200'">
                                    <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide whitespace-nowrap">Check-in</p>
                                        <p class="text-sm font-semibold text-gray-700 truncate" x-text="checkIn ? fmtDateShort(checkIn) : 'Select'"></p>
                                    </div>
                                </button>
                                <button type="button" @click.stop="openCal('checkOut')"
                                        class="flex items-center gap-1.5 px-2.5 py-3 bg-gray-50 border rounded-xl transition-all text-left col-span-1"
                                        :class="calOpen==='checkOut' ? 'border-red-400 bg-red-50' : 'border-gray-200'">
                                    <svg class="w-3.5 h-3.5 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide whitespace-nowrap">Check-Out</p>
                                        <p class="text-sm font-semibold text-gray-700 truncate" x-text="checkOut ? fmtDateShort(checkOut) : 'Select'"></p>
                                    </div>
                                </button>
                                <div class="flex items-center gap-1.5 px-2.5 py-3 bg-gray-50 border border-gray-200 rounded-xl col-span-1">
                                    <svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Guests</p>
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click.stop="persons=Math.max(1,persons-1)" class="w-5 h-5 flex items-center justify-center text-gray-500 hover:text-red-600 font-bold text-base leading-none">−</button>
                                            <span class="text-sm font-bold text-gray-800 w-4 text-center" x-text="persons"></span>
                                            <button type="button" @click.stop="persons=Math.min(20,persons+1)" class="w-5 h-5 flex items-center justify-center text-gray-500 hover:text-red-600 font-bold text-base leading-none">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- inline calendar --}}
                            <div x-show="calOpen==='checkIn' || calOpen==='checkOut'"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 style="display:none;"
                                 class="bg-white border border-gray-200 rounded-2xl shadow-lg p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <button type="button" @click.stop="calPrev()" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 transition text-gray-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                                    </button>
                                    <span class="text-sm font-bold text-gray-800" x-text="calLabel"></span>
                                    <button type="button" @click.stop="calNext()" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 transition text-gray-500">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
                                    </button>
                                </div>
                                <div class="grid grid-cols-7 mb-1">
                                    <template x-for="d in days" :key="d">
                                        <div class="text-center text-xs font-semibold text-gray-400 py-0.5" x-text="d"></div>
                                    </template>
                                </div>
                                <div class="grid grid-cols-7">
                                    <template x-for="(d,i) in calDays()" :key="i">
                                        <div class="flex items-center justify-center py-0.5">
                                            <button x-show="d!==null" type="button" @click.stop="pickDate(d)" :disabled="isPast(d)"
                                                    :class="{
                                                        'bg-red-600 text-white font-bold': (calOpen==='checkIn'&&isSelected(d,'checkIn'))||(calOpen==='checkOut'&&isSelected(d,'checkOut')),
                                                        'bg-red-100 text-red-600 font-medium': isInRange(d),
                                                        'text-gray-300 cursor-not-allowed': isPast(d),
                                                        'hover:bg-red-50 hover:text-red-600 text-gray-700': !isPast(d)&&!isSelected(d,'checkIn')&&!isSelected(d,'checkOut'),
                                                        'ring-2 ring-red-400 font-bold text-red-600': d&&d.toDateString()===today.toDateString()&&!isSelected(d,'checkIn')&&!isSelected(d,'checkOut')
                                                    }"
                                                    class="w-8 h-8 text-xs rounded-full transition-all"
                                                    x-text="d?d.getDate():''"></button>
                                        </div>
                                    </template>
                                </div>
                                <div x-show="checkIn||checkOut" class="mt-2 pt-2 border-t border-gray-100 flex items-center justify-between">
                                    <span class="text-xs text-gray-500">
                                        <span class="font-semibold text-gray-800" x-text="checkIn?fmtDateShort(checkIn):'-'"></span>
                                        <span class="mx-1 text-gray-300">→</span>
                                        <span class="font-semibold text-gray-800" x-text="checkOut?fmtDateShort(checkOut):'-'"></span>
                                    </span>
                                    <button type="button" @click.stop="checkIn=null;checkOut=null" class="text-xs text-red-500 font-semibold hover:text-red-700">Clear</button>
                                </div>
                            </div>
                            {{-- search --}}
                            <button @click="window.location='{{ route('hotels.index') }}?search='+encodeURIComponent(query)+'&check_in='+fmtISO(checkIn)+'&check_out='+fmtISO(checkOut)+'&persons='+persons"
                                    class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 rounded-2xl text-sm transition-all shadow-lg shadow-red-600/30 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Search Hotels
                            </button>
                        </div>
                    </div>
                </template>

                {{-- ── Pick & Drop ── --}}
                <template x-if="tab==='transfers'">
                    <div class="bg-white rounded-3xl shadow-2xl shadow-black/25 p-4 text-left">
                        <div class="flex flex-col gap-3">
                            {{-- Pickup + Drop-off + Passengers in 3-column grid like Hotels --}}
                            <div class="grid grid-cols-3 gap-2">
                                {{-- Pickup --}}
                                <div class="flex items-center gap-1.5 px-2.5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus-within:border-red-400 focus-within:ring-2 focus-within:ring-red-100 transition-all col-span-1">
                                    <svg class="w-3.5 h-3.5 text-green-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide whitespace-nowrap">Pickup</p>
                                        <input type="text" name="pickup" x-model="query" placeholder="From..."
                                               class="w-full text-gray-800 text-sm border-0 p-0 focus:ring-0 outline-none placeholder-gray-400 bg-transparent truncate" autocomplete="off">
                                    </div>
                                </div>
                                {{-- Drop-off --}}
                                <div class="flex items-center gap-1.5 px-2.5 py-3 bg-gray-50 border border-gray-200 rounded-xl focus-within:border-red-400 focus-within:ring-2 focus-within:ring-red-100 transition-all col-span-1">
                                    <svg class="w-3.5 h-3.5 text-red-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide whitespace-nowrap">Drop-off</p>
                                        <input type="text" name="drop" placeholder="To..."
                                               class="w-full text-gray-800 text-sm border-0 p-0 focus:ring-0 outline-none placeholder-gray-400 bg-transparent truncate" autocomplete="off">
                                    </div>
                                </div>
                                {{-- Passengers --}}
                                <div class="flex items-center gap-1.5 px-2.5 py-3 bg-gray-50 border border-gray-200 rounded-xl col-span-1">
                                    <svg class="w-3.5 h-3.5 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide whitespace-nowrap">Passengers</p>
                                        <div class="flex items-center gap-1">
                                            <button type="button" @click.stop="passengers=Math.max(1,passengers-1)" class="w-5 h-5 flex items-center justify-center text-gray-500 hover:text-red-600 font-bold text-base leading-none">−</button>
                                            <span class="text-sm font-bold text-gray-800 w-4 text-center" x-text="passengers"></span>
                                            <button type="button" @click.stop="passengers=Math.min(50,passengers+1)" class="w-5 h-5 flex items-center justify-center text-gray-500 hover:text-red-600 font-bold text-base leading-none">+</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- Date selector --}}
                            <button type="button" @click.stop="openCal('travelDate')"
                                    class="flex items-center gap-2 px-3 py-3 bg-gray-50 border rounded-xl transition-all text-left w-full"
                                    :class="calOpen==='travelDate' ? 'border-red-400 bg-red-50' : 'border-gray-200'">
                                <svg class="w-4 h-4 text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <div class="min-w-0">
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wide">Travel Date</p>
                                    <p class="text-sm font-semibold text-gray-700 truncate" x-text="travelDate ? fmtDateShort(travelDate) : 'Select date'"></p>
                                </div>
                            </button>
                            {{-- inline calendar for transfer --}}
                            <div x-show="calOpen==='travelDate'"
                                 x-transition:enter="transition ease-out duration-150"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 style="display:none;"
                                 class="bg-white border border-gray-200 rounded-2xl shadow-lg p-3">
                                <div class="flex items-center justify-between mb-2">
                                    <button type="button" @click.stop="calPrev()" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 transition text-gray-500"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg></button>
                                    <span class="text-sm font-bold text-gray-800" x-text="calLabel"></span>
                                    <button type="button" @click.stop="calNext()" class="w-7 h-7 flex items-center justify-center rounded-lg hover:bg-gray-100 transition text-gray-500"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg></button>
                                </div>
                                <div class="grid grid-cols-7 mb-1">
                                    <template x-for="d in days" :key="d">
                                        <div class="text-center text-xs font-semibold text-gray-400 py-0.5" x-text="d"></div>
                                    </template>
                                </div>
                                <div class="grid grid-cols-7">
                                    <template x-for="(d,i) in calDays()" :key="i">
                                        <div class="flex items-center justify-center py-0.5">
                                            <button x-show="d!==null" type="button" @click.stop="pickDate(d)" :disabled="isPast(d)"
                                                    :class="{
                                                        'bg-red-600 text-white font-bold': isSelected(d,'travelDate'),
                                                        'text-gray-300 cursor-not-allowed': isPast(d),
                                                        'hover:bg-red-50 hover:text-red-600 text-gray-700': !isPast(d)&&!isSelected(d,'travelDate'),
                                                        'ring-2 ring-red-400 font-bold text-red-600': d&&d.toDateString()===today.toDateString()&&!isSelected(d,'travelDate')
                                                    }"
                                                    class="w-8 h-8 text-xs rounded-full transition-all"
                                                    x-text="d?d.getDate():''"></button>
                                        </div>
                                    </template>
                                </div>
                            </div>
                            <button @click="window.location='{{ route('transfers.index') }}?pickup='+encodeURIComponent(query)+'&travel_date='+fmtISO(travelDate)+'&passengers='+passengers"
                                    class="w-full bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 rounded-2xl text-sm transition-all shadow-lg shadow-red-600/30 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                Book Transfer
                            </button>
                        </div>
                    </div>
                </template>



                {{-- ── Real-time suggestions dropdown ── --}}
                <div x-show="show && suggestions.length > 0 && calOpen === ''"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-3 scale-[0.98]"
                     x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                     x-transition:leave-end="opacity-0 translate-y-2 scale-[0.98]"
                     @click.outside="show=false"
                     class="absolute top-full left-0 right-0 mt-3 bg-white rounded-2xl shadow-2xl shadow-black/25 border border-gray-100 overflow-hidden z-50"
                     style="display:none;">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-4 py-3 bg-gradient-to-r from-gray-50 to-white border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <span class="text-xs font-semibold text-gray-600" x-text="query ? 'Search results for &quot;' + query + '&quot;' : 'Popular destinations'"></span>
                        </div>
                        <div x-show="loading" class="w-4 h-4 border-2 border-red-500 border-t-transparent rounded-full animate-spin"></div>
                    </div>
                    {{-- Results list --}}
                    <ul class="max-h-[320px] overflow-y-auto py-2">
                        <template x-for="(item, index) in suggestions" :key="item.url">
                            <li @click="go(item.url)"
                                class="group mx-2 rounded-xl cursor-pointer transition-all duration-200 border border-transparent hover:border-red-100 hover:bg-red-50/50 hover:shadow-sm"
                                :class="{'bg-red-50/30': index === 0}">
                                <div class="flex items-center gap-3 px-3 py-2.5">
                                    {{-- Image with category badge --}}
                                    <div class="relative flex-shrink-0">
                                        <img :src="item.image || 'https://via.placeholder.com/120x80?text=' + tab.charAt(0).toUpperCase() + tab.slice(1)"
                                             alt=""
                                             class="w-16 h-12 object-cover rounded-lg bg-gray-100 shadow-sm group-hover:scale-105 transition-transform duration-300">
                                        <span class="absolute -bottom-1 -right-1 px-1.5 py-0.5 text-[9px] font-bold uppercase tracking-wider rounded-md"
                                              :class="{
                                                  'bg-emerald-500 text-white': tab === 'tours',
                                                  'bg-blue-500 text-white': tab === 'visas',
                                                  'bg-purple-500 text-white': tab === 'hotels',
                                                  'bg-orange-500 text-white': tab === 'transfers'
                                              }"
                                              x-text="tab === 'transfers' ? 'Transfer' : tab.charAt(0).toUpperCase() + tab.slice(1, -1)"></span>
                                    </div>
                                    {{-- Content --}}
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-semibold text-gray-800 truncate group-hover:text-red-600 transition-colors" x-text="item.text"></p>
                                        <div class="flex items-center gap-1.5 mt-0.5">
                                            <svg class="w-3 h-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            <p class="text-xs text-gray-500 truncate" x-text="item.subtext"></p>
                                        </div>
                                    </div>
                                    {{-- Price or arrow --}}
                                    <div class="flex-shrink-0 flex items-center gap-2">
                                        <span x-show="item.price" class="text-sm font-bold text-red-600" x-text="item.price ? '৳' + item.price : ''"></span>
                                        <div class="w-7 h-7 rounded-full bg-gray-100 group-hover:bg-red-500 flex items-center justify-center transition-colors">
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </template>
                    </ul>
                    {{-- Footer --}}
                    <div class="px-4 py-2 bg-gray-50 border-t border-gray-100">
                        <a :href="tab === 'tours' ? '{{ route('packages.index') }}?search=' + encodeURIComponent(query) : (tab === 'visas' ? '{{ route('visas.index') }}?search=' + encodeURIComponent(query) : (tab === 'hotels' ? '{{ route('hotels.index') }}?search=' + encodeURIComponent(query) : '{{ route('transfers.index') }}'))"
                           class="flex items-center justify-center gap-2 text-xs font-semibold text-gray-600 hover:text-red-600 transition-colors py-1">
                            <span>View all results</span>
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Slide dots --}}
    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
        @foreach([0,1,2] as $i)
        <button @click="s={{ $i }}" class="w-2 h-2 rounded-full transition-all" :class="s==={{ $i }}?'bg-white w-6':'bg-white/40'"></button>
        @endforeach
    </div>
</div>

{{-- ═══════════════════════════════════════
     TRUST BAR
═══════════════════════════════════════ --}}
<div class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-5xl mx-auto px-4 py-3 flex flex-wrap items-center justify-center gap-4 sm:gap-8 text-sm">
        <div class="flex items-center gap-2.5 text-gray-600">
            <div class="w-8 h-8 rounded-full bg-green-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-green-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            </div>
            <span><strong class="text-gray-900">{{ $siteStats['travellers'] }}</strong> Happy Travellers</span>
        </div>
        <div class="flex items-center gap-2.5 text-gray-600">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <span><strong class="text-gray-900">{{ $siteStats['destinations'] }}</strong> Destinations</span>
        </div>
        <div class="flex items-center gap-2.5 text-gray-600">
            <div class="w-8 h-8 rounded-full bg-yellow-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
            </div>
            <span><strong class="text-gray-900">{{ $siteStats['rating'] }}★</strong> Rating</span>
        </div>
        <div class="flex items-center gap-2.5 text-gray-600">
            <div class="w-8 h-8 rounded-full bg-red-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>
            <span><strong class="text-gray-900">{{ $siteStats['visa_approval'] }}%</strong> Visa Approval</span>
        </div>
        <div class="flex items-center gap-2.5 text-gray-600">
            <div class="w-8 h-8 rounded-full bg-purple-50 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
            </div>
            <span><strong class="text-gray-900">24/7</strong> Support</span>
        </div>
    </div>
</div>

{{-- ═══════════════════════════════════════
     FEATURED PACKAGES
═══════════════════════════════════════ --}}
<section class="bg-gray-50 py-8 sm:py-14">
    <div class="max-w-6xl mx-auto px-4">

        <div class="flex items-end justify-between mb-5 sm:mb-8">
            <div>
                <p class="section-eyebrow mb-1">Handpicked for you</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Featured Tour Packages</h2>
            </div>
            <a href="{{ route('packages.index') }}" class="hidden md:flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-700">
                View all <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if($packages->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            @foreach($packages as $package)
            <a href="{{ route('packages.show', $package->slug) }}" class="travel-card flex flex-col group">
                {{-- Image --}}
                <div class="relative overflow-hidden" style="height:160px;">
                    @if($package->thumbnail)
                        <img src="{{ Str::startsWith($package->thumbnail,'http') ? $package->thumbnail : Storage::url($package->thumbnail) }}"
                             alt="{{ $package->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        {{-- Styled gradient placeholder --}}
                        @php $colors = [['#667eea','#764ba2'],['#f093fb','#f5576c'],['#4facfe','#00f2fe'],['#43e97b','#38f9d7'],['#fa709a','#fee140'],['#a18cd1','#fbc2eb']]; $c=$colors[$loop->index % count($colors)]; @endphp
                        <div class="w-full h-full flex flex-col items-center justify-center" style="background:linear-gradient(135deg,{{ $c[0] }},{{ $c[1] }});">
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none" class="opacity-40 mb-2"><path d="M12 34 L24 12 L36 34 L31 34 L28 28 L20 28 L17 34 Z" fill="white"/></svg>
                            <span class="text-white/60 text-xs font-medium">{{ $package->destination ?? $package->location ?? 'Destination' }}</span>
                        </div>
                    @endif
                    <div class="absolute inset-0" style="background:linear-gradient(to top,rgba(0,0,0,.6) 0%,transparent 50%);"></div>
                    @if($package->is_active)
                    <span class="absolute top-3 left-3 bg-red-600 text-white text-[10px] font-bold px-2.5 py-1 rounded-lg">POPULAR</span>
                    @endif
                    @if($package->duration_days)
                    <span class="absolute bottom-3 right-3 bg-black/50 text-white text-[11px] font-semibold px-2 py-0.5 rounded-lg backdrop-blur-sm">
                        {{ $package->duration_days }}D / {{ $package->duration_days - 1 }}N
                    </span>
                    @endif
                </div>
                {{-- Info --}}
                <div class="p-3 sm:p-4 flex flex-col flex-1">
                    <div class="flex items-center gap-1 text-[10px] sm:text-xs text-gray-400 font-medium mb-1">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                        <span class="truncate">{{ $package->location ?? $package->destination ?? 'International' }}</span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-xs sm:text-sm leading-snug line-clamp-2 mb-2 sm:mb-3 group-hover:text-red-600 transition flex-1">
                        {{ $package->title }}
                    </h3>
                    <div class="flex items-center justify-between border-t border-gray-50 pt-2 sm:pt-3">
                        <div>
                            <span class="text-[9px] sm:text-[10px] text-gray-400 block">From</span>
                            <span class="text-sm sm:text-lg font-extrabold text-red-600">৳{{ number_format($package->price) }}</span>
                        </div>
                        <span class="bg-gray-900 text-white text-[10px] sm:text-xs font-semibold px-2 sm:px-3 py-1.5 sm:py-2 rounded-lg group-hover:bg-red-600 transition">
                            Book
                        </span>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="text-center mt-8 md:hidden">
            <a href="{{ route('packages.index') }}" class="btn-primary">View All Packages</a>
        </div>

        @else
        <div class="text-center py-20 bg-white rounded-2xl border-2 border-dashed border-gray-200">
            <svg class="w-16 h-16 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <p class="text-gray-400 font-medium">Tour packages coming soon</p>
        </div>
        @endif
    </div>
</section>

{{-- ═══════════════════════════════════════
     POPULAR DESTINATIONS
═══════════════════════════════════════ --}}
<section class="bg-white py-8 sm:py-12">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-center justify-between mb-4 sm:mb-6">
            <h2 class="text-xl sm:text-2xl font-extrabold text-gray-900">Popular Destinations</h2>
            <a href="{{ route('packages.index') }}" class="text-sm font-semibold text-red-600 hover:underline">Explore all</a>
        </div>
        <div class="grid grid-cols-5 sm:grid-cols-5 lg:grid-cols-10 gap-2 sm:gap-3">
            @php $dests = [
                ["Cox's Bazar",'BD','#3b82f6'],['Bandarban','BD','#10b981'],['Sylhet','BD','#8b5cf6'],
                ['Maldives','MV','#06b6d4'],['Bangkok','TH','#f59e0b'],['Bali','ID','#ef4444'],
                ['Singapore','SG','#64748b'],['Dubai','AE','#d97706'],['Kuala Lumpur','MY','#7c3aed'],['Japan','JP','#ec4899'],
            ]; @endphp
            @foreach($dests as [$name,$code,$color])
            <a href="{{ route('packages.index', ['search'=>$name]) }}"
               class="flex flex-col items-center gap-1.5 p-2 sm:p-3 rounded-2xl hover:bg-gray-50 transition group text-center">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl flex items-center justify-center font-bold text-white text-sm sm:text-lg shadow-sm transition group-hover:scale-110"
                     style="background:{{ $color }};">
                    {{ $code }}
                </div>
                <span class="text-[10px] sm:text-xs font-semibold text-gray-700 group-hover:text-red-600 transition leading-tight">{{ $name }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     PAYMENT / OFFER BANNERS
═══════════════════════════════════════ --}}
<section class="bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <div class="w-12 h-12 bg-pink-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm">bKash &amp; Nagad</p>
                <p class="text-xs text-gray-500 mt-0.5">0% charge on all mobile payments</p>
            </div>
        </div>
        <div class="flex items-center gap-4 bg-white border border-gray-100 rounded-2xl p-5 shadow-sm">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm">Easy EMI</p>
                <p class="text-xs text-gray-500 mt-0.5">Up to 12 months, 0% interest rate</p>
            </div>
        </div>
        <div class="flex items-center gap-4 bg-gradient-to-r from-red-600 to-red-700 rounded-2xl p-5 shadow-sm text-white">
            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="font-bold text-sm">Download Our App</p>
                <p class="text-xs text-white/80 mt-0.5">Exclusive app-only deals &amp; discounts</p>
            </div>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     VISA SERVICES
═══════════════════════════════════════ --}}
<section class="bg-white py-8 sm:py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-5 sm:mb-8">
            <div>
                <p class="section-eyebrow mb-1">184 countries covered</p>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">Visa Processing Services</h2>
                <p class="text-gray-500 mt-1 text-sm">94.2% approval rate · Hassle-free documentation</p>
            </div>
            <a href="{{ route('visas.index') }}" class="hidden md:flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-700">
                All visa services <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>

        @if(isset($visas) && $visas->count() > 0)
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 sm:gap-3">
            @foreach($visas->take(12) as $visa)
            <a href="{{ route('visas.show', $visa->slug) }}" class="visa-card group">
                <div class="text-2xl sm:text-3xl mb-1.5 sm:mb-2 block">{{ $visa->flag_emoji ?? '🌍' }}</div>
                <p class="text-[10px] sm:text-xs font-bold text-gray-800 group-hover:text-red-600 transition leading-tight">{{ $visa->country }}</p>
                @if($visa->processing_time)
                <p class="text-[10px] text-gray-400 mt-1">{{ $visa->processing_time }}</p>
                @endif
                @if($visa->fee)
                <p class="text-xs font-bold text-red-600 mt-1">৳{{ number_format($visa->fee) }}</p>
                @endif
            </a>
            @endforeach
        </div>
        @else
        <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 sm:gap-3">
            @foreach([['🇲🇾','Malaysia','3-5 days'],['🇹🇭','Thailand','3-5 days'],['🇦🇪','UAE','3-7 days'],['🇸🇬','Singapore','5-7 days'],['🇮🇩','Indonesia','3-5 days'],['🇯🇵','Japan','5-7 days'],['🇰🇷','S. Korea','5-7 days'],['🇬🇧','UK','10-15 days'],['🇺🇸','USA','15-30 days'],['🇨🇦','Canada','20-30 days'],['🇮🇳','India','2-3 days'],['🌍','Schengen','7-15 days']] as [$f,$n,$t])
            <div class="visa-card">
                <div class="text-3xl mb-2">{{ $f }}</div>
                <p class="text-xs font-bold text-gray-800 leading-tight">{{ $n }}</p>
                <p class="text-[10px] text-gray-400 mt-1">{{ $t }}</p>
            </div>
            @endforeach
        </div>
        @endif

        <div class="mt-6 flex flex-wrap gap-3">
            <a href="{{ route('visas.index') }}" class="btn-primary">Apply for Visa</a>
            <a href="{{ route('contact') }}" class="btn-outline">Free Consultation</a>
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     WHY CHOOSE US
═══════════════════════════════════════ --}}
<section class="bg-gray-50 py-8 sm:py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="text-center mb-6 sm:mb-10">
            <p class="section-eyebrow mb-2">Why travellers trust us</p>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-gray-900">The FlyoverBD Difference</h2>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-5">
            @php $features = [
                ['bg-red-50','text-red-600','M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z','Best Price Guarantee','We match any lower price you find within 24 hours of booking.'],
                ['bg-blue-50','text-blue-600','M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2','Visa Experts','Our visa team has a 94.2% approval rate across 184 destinations.'],
                ['bg-green-50','text-green-600','M12 19l9 2-9-18-9 18 9-2zm0 0v-8','On-time Transfers','Airport pick &amp; drop to 20+ cities - your driver is always on time.'],
                ['bg-purple-50','text-purple-600','M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z','24/7 Human Support','Real people on WhatsApp, not bots. Always available, always helpful.'],
            ]; @endphp
            @foreach($features as [$bg,$color,$icon,$title,$desc])
            <div class="bg-white rounded-2xl p-4 sm:p-6 border border-gray-100 shadow-sm hover:shadow-md transition">
                <div class="w-10 h-10 sm:w-12 sm:h-12 {{ $bg }} rounded-xl flex items-center justify-center mb-3 sm:mb-4">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 {{ $color }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/></svg>
                </div>
                <h3 class="font-bold text-gray-900 text-sm sm:text-base mb-1.5 sm:mb-2">{{ $title }}</h3>
                <p class="text-xs sm:text-sm text-gray-500 leading-relaxed hidden sm:block">{!! $desc !!}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══════════════════════════════════════
     BLOG
═══════════════════════════════════════ --}}
@if(isset($recentPosts) && $recentPosts->count() > 0)
<section class="bg-white py-14">
    <div class="max-w-6xl mx-auto px-4">
        <div class="flex items-end justify-between mb-8">
            <div>
                <p class="section-eyebrow mb-1.5">Travel insights</p>
                <h2 class="text-3xl font-extrabold text-gray-900">Latest from our Blog</h2>
            </div>
            <a href="{{ route('blog.index') }}" class="hidden md:flex items-center gap-1 text-sm font-semibold text-red-600 hover:text-red-700">
                All articles <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </a>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-5">
            @foreach($recentPosts as $post)
            <article class="travel-card group flex flex-col">
                <a href="{{ route('blog.show', $post->slug) }}" class="block overflow-hidden" style="height:130px;">
                    @if($post->image)
                        <img src="{{ Storage::url($post->image) }}" alt="{{ $post->title }}"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @else
                        <div class="w-full h-full" style="background:linear-gradient(135deg,#667eea,#764ba2);"></div>
                    @endif
                </a>
                <div class="p-3 sm:p-5 flex flex-col flex-1">
                    <p class="text-[10px] sm:text-xs text-gray-400 mb-1 sm:mb-2">{{ $post->published_at ? $post->published_at->format('M d, Y') : '' }}</p>
                    <h3 class="font-bold text-gray-900 text-xs sm:text-sm leading-snug line-clamp-2 mb-1 sm:mb-2 group-hover:text-red-600 transition flex-1">
                        <a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a>
                    </h3>
                    <a href="{{ route('blog.show', $post->slug) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-red-600 mt-2">
                        Read more <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

</x-app-layout>
