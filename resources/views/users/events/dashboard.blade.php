@extends('layouts.app')

@section('custom_css')
<link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style" onload="this.rel='stylesheet'">
<link rel="preload" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" as="style" onload="this.rel='stylesheet'">
<noscript>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
</noscript>
<style>
/* ═══════════════════════════════════════════════════════
   PAGE FADE-IN
═══════════════════════════════════════════════════════ */
#page-root { opacity: 0; transition: opacity .35s ease; }
#page-root.ready { opacity: 1; }

/* ═══════════════════════════════════════════════════════
   GLASS CARD (shared)
═══════════════════════════════════════════════════════ */
.ev-card {
    background: var(--bg-surface);
    border: 1px solid var(--border-mid);
    transition: transform .22s ease, box-shadow .22s ease, border-color .22s ease;
}
.ev-card:hover {
    transform: translateY(-4px);
    border-color: var(--accent-border);
    box-shadow: 0 12px 40px rgba(0,0,0,.25), var(--shadow-glow);
}
[data-theme="light"] .ev-card { box-shadow: 0 2px 16px rgba(0,0,0,.06); }
[data-theme="light"] .ev-card:hover { box-shadow: 0 10px 32px rgba(0,0,0,.12), var(--shadow-glow); }

/* ═══════════════════════════════════════════════════════
   STAT CARDS
═══════════════════════════════════════════════════════ */
.stat-icon-wrap {
    width: 52px; height: 52px;
    border-radius: 14px;
    display: inline-flex; align-items: center; justify-content: center;
    margin-bottom: 16px;
    transition: transform .2s;
}
.ev-card:hover .stat-icon-wrap { transform: scale(1.12); }

/* Theme Color Utilities */
.si-cyan   { background: rgba(34,211,238,.12); color: #22d3ee; }
.si-green  { background: rgba(34,197,94,.12);  color: #22c55e; }
.si-amber  { background: rgba(245,158,11,.12); color: #f59e0b; }
.si-blue   { background: rgba(59,130,246,.12); color: #3b82f6; }
.si-purple { background: rgba(168,85,247,.12); color: #a855f7; }

.sv-cyan   { color: #22d3ee; }
.sv-green  { color: #22c55e; }
.sv-amber  { color: #f59e0b; }
.sv-blue   { color: #3b82f6; }
.sv-purple { color: #a855f7; }

.stat-line-cyan   { background: linear-gradient(90deg,#22d3ee,transparent); }
.stat-line-green  { background: linear-gradient(90deg,#22c55e,transparent); }
.stat-line-amber  { background: linear-gradient(90deg,#f59e0b,transparent); }
.stat-line-blue   { background: linear-gradient(90deg,#3b82f6,transparent); }
.stat-line-purple { background: linear-gradient(90deg,#a855f7,transparent); }

/* ═══════════════════════════════════════════════════════
   ACTION BUTTONS
═══════════════════════════════════════════════════════ */
.act-btn {
    display: inline-flex; align-items: center; gap: 8px;
    padding: 14px 24px;
    border-radius: 16px;
    font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .15em;
    border: 1px solid transparent;
    transition: transform .15s, opacity .15s, box-shadow .2s;
    font-family: 'JetBrains Mono', monospace;
}
.act-btn:active { transform: scale(.97); }
.act-btn:hover  { opacity: .88; }

.act-primary {
    background: var(--accent); color: #020617;
    box-shadow: 0 0 24px rgba(34,211,238,.3);
}
.act-primary:hover { box-shadow: 0 0 36px rgba(34,211,238,.5); opacity: 1; }

.act-purple  { background: rgba(168,85,247,.1); border-color: rgba(168,85,247,.3); color: #a855f7; }
.act-blue    { background: rgba(59,130,246,.1);  border-color: rgba(59,130,246,.3);  color: #3b82f6; }
.act-emerald { background: rgba(16,185,129,.1);  border-color: rgba(16,185,129,.3);  color: #10b981; }
.act-orange  { background: rgba(249,115,22,.1);  border-color: rgba(249,115,22,.3);  color: #f97316; }
.act-cyan    { background: var(--accent-dim);    border-color: var(--accent-border); color: var(--accent); }

.act-purple:hover  { background: rgba(168,85,247,.2); }
.act-blue:hover    { background: rgba(59,130,246,.2); }
.act-emerald:hover { background: rgba(16,185,129,.2); }
.act-orange:hover  { background: rgba(249,115,22,.2); }
.act-cyan:hover    { background: var(--accent); color: #020617; }

.act-disabled {
    background: rgba(239,68,68,.08); border-color: rgba(239,68,68,.2); color: #f87171;
    opacity: .55; cursor: not-allowed;
}

/* ═══════════════════════════════════════════════════════
   BACK BUTTON
═══════════════════════════════════════════════════════ */
.back-btn {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 10px 22px; border-radius: 16px;
    font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .18em;
    font-family: 'JetBrains Mono', monospace;
    background: var(--accent-dim);
    border: 1px solid var(--border-accent);
    color: var(--text-secondary);
    transition: border-color .2s, color .2s, background .2s;
}
.back-btn:hover { border-color: var(--accent); color: var(--text-primary); background: var(--accent-dim); }

.header-divider { border-bottom: 1px solid var(--border-soft); }

/* ═══════════════════════════════════════════════════════
   JUDGES / SWIPER
═══════════════════════════════════════════════════════ */
.judge-card {
    background: var(--bg-elevated);
    border: 1px solid var(--border-soft);
    overflow: hidden; border-radius: 2.5rem;
    position: relative; width: 280px; height: 380px;
}
[data-theme="light"] .judge-card { border-color: var(--border-mid); }

.skeleton {
    background: linear-gradient(90deg, var(--bg-elevated) 0%, var(--border-mid) 50%, var(--bg-elevated) 100%);
    background-size: 200% 100%;
    animation: sk-sweep 1.6s ease-in-out infinite;
    border-radius: 2.5rem;
}
@keyframes sk-sweep {
    0%   { background-position: 200% center; }
    100% { background-position: -200% center; }
}

.swiper-nav-btn {
    position: absolute; top: 50%; transform: translateY(-50%);
    width: 48px; height: 48px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    z-index: 30; cursor: pointer;
    background: var(--bg-surface);
    border: 1px solid var(--border-accent);
    color: var(--accent);
    transition: background .2s, color .2s, box-shadow .2s;
}
.swiper-nav-btn:hover {
    background: var(--accent); color: #020617;
    box-shadow: var(--shadow-glow);
}

/* ═══════════════════════════════════════════════════════
   STATS BAR SECTION
═══════════════════════════════════════════════════════ */
.stats-section {
    background: var(--bg-surface);
    border-top:    1px solid var(--border-soft);
    border-bottom: 1px solid var(--border-soft);
}
[data-theme="light"] .stats-section { background: #f8faff; }

.cd-unit {
    background: var(--bg-elevated);
    border: 1px solid var(--accent-border);
    border-radius: 16px;
    width: 64px; height: 64px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px; font-weight: 900;
    color: var(--accent);
    font-family: 'JetBrains Mono', monospace;
    font-variant-numeric: tabular-nums;
    transition: border-color .3s, box-shadow .3s;
}
.cd-unit.tick { box-shadow: 0 0 12px rgba(34,211,238,.25); }

.circle-ring {
    background: conic-gradient(var(--accent) 0% 68%, var(--border-soft) 68% 100%);
    border-radius: 50%;
}
.circle-inner {
    background: var(--bg-base);
    border: 1px solid var(--border-soft);
    border-radius: 50%;
}
[data-theme="light"] .circle-inner { background: #f0f6ff; }

.deadline-pill {
    display: inline-flex; align-items: center; gap: 10px;
    padding: 16px 28px; border-radius: 1.5rem;
    background: var(--bg-elevated);
    border: 1px solid rgba(239,68,68,.2);
}

.expert-badge {
    background: var(--accent-dim);
    border: 1px solid var(--accent-border);
    border-radius: 9999px;
    padding: 4px 16px;
    display: inline-block;
    margin-bottom: 16px;
}
</style>
@endsection

@section('content')
<div id="page-root">

    <div class="container mx-auto px-4 py-10">

        {{-- ── ১. Page Header ──────────────────────────────────── --}}
        <div class="header-divider flex flex-col md:flex-row justify-between items-start md:items-center gap-5 pb-8 mb-6">
            <div>
                <p class="text-[9px] font-black uppercase tracking-[.35em] mb-2"
                   style="color:var(--accent); opacity:.7">Event Overview</p>
                <h1 class="heading-font text-3xl md:text-4xl font-black uppercase tracking-tighter leading-none"
                    style="color:var(--text-primary)">
                    {{ $event->name }}
                </h1>
            </div>
            <button onclick="window.history.back()" class="back-btn shrink-0">
                <i class="fa-solid fa-arrow-left-long" style="color:var(--accent)"></i>
                Back
            </button>
        </div>

        {{-- ── লজিক প্রসেসিং পার্ট (Ict-র জন্য ৩০০ কন্ডিশন ট্র্যাক) ── --}}
        @php
            $verifiedCount = $counts['verified'] ?? 0;
            $slug = $event->slug;
            
            // কন্ডিশন: শুধুমাত্র Ict olympiad-এর জন্য ৩০০ বা তার বেশি ভেরিফাইড হলে স্লট ফুল ধরবে
            $isRegistrationFilled = ($slug === 'ict' && $verifiedCount >= 300);
            
            // মেইন টাইমলাইন ওভার হওয়া অথবা স্লট ফুল হওয়া—উভয় ক্ষেত্রেই ডেডলাইন ট্রিপড হবে
            $isDeadlineOver = ($event->end_date && now()->gt($event->end_date)) || $isRegistrationFilled;
        @endphp

        {{-- ── ২. STATS BAR (সবার উপরে স্থানান্তরিত উইজেট) ────────────────── --}}
        <div class="stats-section py-12 relative overflow-hidden rounded-[2.5rem] mb-12">
            <div class="absolute top-0 left-1/4 w-96 h-96 rounded-full pointer-events-none"
                 style="background:var(--accent-dim); filter:blur(100px)" aria-hidden="true"></div>
            <div class="absolute bottom-0 right-1/4 w-72 h-72 rounded-full pointer-events-none"
                 style="background:rgba(168,85,247,.08); filter:blur(80px)" aria-hidden="true"></div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-center">

                    {{-- Countdown (টাইমার অংশ) --}}
                    <div class="text-center md:text-left">
                        <p class="text-[9px] font-black tracking-[.3em] uppercase mb-5"
                           style="color:var(--text-muted)">Time Remaining to Register</p>
                        <div id="countdown-wrapper" class="flex gap-3 justify-center md:justify-start">
                            @if (!$isRegistrationFilled)
                                @foreach (['days','hours','minutes','seconds'] as $unit)
                                    <div class="flex flex-col items-center">
                                        <div id="{{ $unit }}" class="cd-unit">00</div>
                                        <span class="text-[8px] uppercase mt-2 font-black tracking-widest"
                                              style="color:var(--text-muted)">{{ $unit }}</span>
                                    </div>
                                @endforeach
                            @else
                                {{-- ডেডলাইন শেষ না হয়ে শুধু iupc ৩০০ স্লট ফুল হলে হলুদ কন্টেন্ট --}}
                                @if (!($event->end_date && now()->gt($event->end_date)))
                                    <p class="text-sm font-black uppercase tracking-widest" style="color:#f59e0b">
                                        <i class="fa-solid fa-hourglass-half mr-2"></i>Slots Full • Coming Soon
                                    </p>
                                @else
                                    <p class="text-sm font-black uppercase tracking-widest" style="color:#f87171">
                                        <i class="fa-solid fa-lock mr-2"></i>Registration Closed
                                    </p>
                                @endif
                            @endif
                        </div>
                    </div>

                    {{-- Circle Stat (মোট সংখ্যা) --}}
                    <div class="flex flex-col items-center">
                        <div class="relative w-44 h-44 flex items-center justify-center">
                            <div class="circle-ring absolute inset-0 rounded-full" aria-hidden="true"></div>
                            <div class="circle-inner absolute inset-[4px] rounded-full flex flex-col items-center justify-center">
                                <span class="text-4xl font-black italic tabular-nums"
                                      style="color:var(--text-primary)">
                                    {{ number_format($totalRegistered) }}
                                </span>
                                <span class="text-[9px] uppercase font-black tracking-widest mt-1"
                                      style="color:var(--text-muted)">Total Registered</span>
                            </div>
                        </div>
                    </div>

                    {{-- Deadline (শেষ সময়) --}}
                    <div class="text-center md:text-right">
                        <p class="text-[9px] tracking-[.3em] uppercase mb-5 font-black"
                           style="color:var(--text-muted)">Registration Deadline</p>
                        <div class="deadline-pill inline-flex justify-center md:justify-end">
                            <i class="fa-solid fa-calendar-check" style="color:#f87171"></i>
                            <span class="text-xl md:text-2xl font-black italic uppercase tracking-tighter"
                                  style="color:var(--text-primary)">
                                {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </div> {{-- /stats-section --}}


        <div class="max-w-7xl mx-auto">

            {{-- ── ৩. Stat Cards Grid ───────────────────────────────── --}}
            @php
                $stats = [
                    ['label'=>'Total Pre Registered','value'=>$counts['pre-registered']??0,'color'=>'cyan',   'icon'=>'fa-users',        'route'=>route('event.pre_registered',[$event->slug,'status'=>'pre-registered'])],
                    ['label'=>'Verified / Paid', 'value'=>$verifiedCount,      'color'=>'green', 'icon'=>'fa-circle-check', 'route'=>route('event.final_registered',[$event->slug,'status'=>'verified'])],
                    ['label'=>'Institutions',    'value'=>$counts['institutes']??0,     'color'=>'amber', 'icon'=>'fa-university',   'route'=>route('event.institutes',$event->slug)],
                ];
                if ($slug==='iupc' || in_array($slug,['ai-hackathon','project-showcase'])) {
                    $isIupc = $slug==='iupc';
                    $stats[] = [
                        'label' => $isIupc?'Available Slots':'Final Selected',
                        'value' => $isIupc?($totalSlots??0):($counts['selected']??0),
                        'color' => $isIupc?'blue':'purple',
                        'icon'  => 'fa-id-badge',
                        'route' => $isIupc?route('event.slot_list',$event->slug):route('event.select_registered',$event->slug),
                    ];
                }
            @endphp

            <div class="flex flex-wrap justify-center gap-5 mb-16">
                @foreach ($stats as $s)
                    <a href="{{ $s['route'] }}"
                       class="ev-card rounded-[1.75rem] p-7 text-center relative overflow-hidden"
                       style="min-width:170px">
                        <div class="stat-line-{{ $s['color'] }} absolute top-0 left-0 right-0 h-[3px] rounded-t-[1.75rem]"></div>
                        <div class="stat-icon-wrap si-{{ $s['color'] }} mx-auto">
                            <i class="fa-solid {{ $s['icon'] }} text-xl"></i>
                        </div>
                        <h4 class="sv-{{ $s['color'] }} text-4xl font-black tracking-tight font-mono">
                            {{ number_format($s['value']) }}
                        </h4>
                        <p class="text-[9px] uppercase font-black tracking-[.22em] mt-2"
                           style="color:var(--text-muted)">{{ $s['label'] }}</p>
                    </a>
                @endforeach
            </div>

            {{-- ── ৪. Action Buttons (রেজিস্ট্রেশন বাটনসহ) ────────────────── --}}
            @php
                $actions = [
                    ['label'=>'Rulebook', 'icon'=>'fa-book-open',           'url'=>$event->rules,                               'cls'=>'act-purple'],
                    ['label'=>'Result',   'icon'=>'fa-square-poll-vertical', 'url'=>$event->result,                              'cls'=>'act-blue'],
                    ['label'=>'Seat Plan','icon'=>'fa-map-location-dot',     'url'=>$event->seatplan,                            'cls'=>'act-emerald'],
                    ['label'=>'Schedule', 'icon'=>'fa-calendar-days',        'url'=>route('event.schedule',$event->slug), 'cls'=>'act-orange'],
                ];
            @endphp

            <div class="flex flex-wrap justify-center gap-3 mb-20">
                @if (!$isDeadlineOver)
                    <a href="{{ route('event.register',$slug) }}" class="act-btn act-primary">
                        <i class="fa-solid fa-bolt"></i> Register Now
                    </a>
                @else
                    {{-- ডেডলাইন পার হয়নি কিন্তু শুধু IUPC-তে ৩০০ স্লট পূরণ হওয়ার কারণে হোল্ড --}}
                    @if ($isRegistrationFilled && !($event->end_date && now()->gt($event->end_date)))
                        <button disabled class="act-btn" style="background: rgba(245,158,11,.08); border-color: rgba(245,158,11,.3); color: #f59e0b; opacity: .85; cursor: not-allowed;">
                            <i class="fa-solid fa-hourglass-half"></i> Registration Coming Soon
                        </button>
                    @else
                        {{-- সাধারণ টাইম ডেডলাইন ওভার হলে পার্মানেন্ট ক্লোজড --}}
                        <button disabled class="act-btn act-disabled">
                            <i class="fa-solid fa-lock"></i> Registration Closed
                        </button>
                    @endif
                @endif

                @foreach ($actions as $a)
                    <a href="{{ $a['url'] ?? '#' }}"
                       @if($a['url']) target="_blank" rel="noopener" @endif
                       class="act-btn {{ $a['cls'] }} {{ !$a['url'] ? 'opacity-30 pointer-events-none' : '' }}">
                        <i class="fa-solid {{ $a['icon'] }}"></i> {{ $a['label'] }}
                    </a>
                @endforeach

                @if ($slug === 'iupc' && !$isRegistrationFilled)
                    <a href="{{ route('event.pre_registered',$slug) }}" class="act-btn act-cyan">
                        <i class="fa-solid fa-pen-to-square"></i> Final Register Now
                    </a>
                @endif
            </div>

            {{-- ── ৫. Judges Panel ─────────────────────────────── --}}
            @if (!empty($event->images) && is_array($event->images))
                <section class="mt-24">
                    <div class="flex flex-col items-center mb-12">
                        <div class="expert-badge">
                            <span class="font-mono text-[10px] uppercase tracking-[.4em] font-black"
                                  style="color:var(--accent)">Expert Panel</span>
                        </div>
                        <h3 class="heading-font text-3xl md:text-4xl font-black uppercase tracking-tighter text-center"
                            style="color:var(--text-primary)">
                            Meet Our <span style="color:var(--accent)">Distinguished</span> Judges
                        </h3>
                        <div class="mt-4 h-[3px] w-20 rounded-full"
                             style="background:linear-gradient(90deg,var(--accent),transparent)"></div>
                    </div>

                    {{-- Skeleton --}}
                    <div id="judges-skeleton" class="flex gap-6 overflow-hidden px-12">
                        @for($i=0;$i<4;$i++)
                            <div class="skeleton flex-none w-[280px] h-[380px]"></div>
                        @endfor
                    </div>

                    {{-- Real Swiper --}}
                    <div id="judges-swiper-wrap" class="relative px-14 hidden">
                        <div class="swiper judgesSwiper">
                            <div class="swiper-wrapper">
                                @foreach ($event->images as $image)
                                    <div class="swiper-slide !w-auto">
                                        <div class="judge-card group">
                                            <a href="{{ asset('storage/'.$image) }}"
                                               data-fancybox="judges"
                                               data-caption="Judge {{ $loop->iteration }}"
                                               class="absolute top-4 right-4 z-20 w-10 h-10 rounded-full flex items-center justify-center text-white opacity-0 group-hover:opacity-100 transition-opacity"
                                               style="background:rgba(0,0,0,.5); backdrop-filter:blur(8px)">
                                                <i class="fa-solid fa-expand text-sm"></i>
                                            </a>
                                            <img src="{{ asset('storage/'.$image) }}"
                                                 loading="lazy" width="280" height="380"
                                                 class="w-full h-full object-cover"
                                                 alt="Judge {{ $loop->iteration }}">
                                            <div class="absolute inset-0 pointer-events-none"
                                                 style="background:linear-gradient(to top, rgba(2,6,23,.95) 0%, rgba(2,6,23,.1) 55%, transparent 100%)">
                                            </div>
                                            <div class="absolute bottom-6 left-6 right-6">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="h-[3px] w-8 rounded-full"
                                                          style="background:var(--accent)"></span>
                                                    <span class="font-mono text-[9px] uppercase tracking-widest"
                                                          style="color:var(--accent)">Evaluator</span>
                                                </div>
                                                <p class="font-bold text-base leading-tight uppercase text-white">
                                                    Expert Node {{ $loop->iteration }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <button aria-label="Previous" class="swiper-nav-btn swiper-nav-prev" style="left:0">
                            <i class="fa-solid fa-chevron-left text-sm"></i>
                        </button>
                        <button aria-label="Next" class="swiper-nav-btn swiper-nav-next" style="right:0">
                            <i class="fa-solid fa-chevron-right text-sm"></i>
                        </button>
                    </div>
                </section>
            @endif

        </div>{{-- /max-w-7xl --}}
    </div>{{-- /container --}}
</div>{{-- /#page-root --}}


{{-- ══════════════════════════════════════════
    SCRIPTS PART
══════════════════════════════════════════ --}}
<script>
    document.getElementById('page-root').classList.add('ready');

    /* Countdown Counter Script */
    (function () {
        const isFilled = {{ $isRegistrationFilled ? 'true' : 'false' }};
        if (isFilled) return; // কন্ডিশনাল স্টপ (iupc ৩শ ফিলআপ হলে টাইমার স্টপ)

        const target = new Date("{{ $event->end_date }}").getTime();
        const pad = n => String(n).padStart(2, '0');
        const els = {
            days:     document.getElementById('days'),
            hours:    document.getElementById('hours'),
            minutes:  document.getElementById('minutes'),
            seconds:  document.getElementById('seconds'),
        };

        function tick() {
            const diff = target - Date.now();
            if (diff <= 0) {
                document.getElementById('countdown-wrapper').innerHTML =
                    `<p class="text-sm font-black uppercase tracking-widest" style="color:#f87171">
                        <i class="fa-solid fa-lock mr-2"></i>Registration Closed
                     </p>`;
                return;
            }
            if(els.days) els.days.textContent       = pad(Math.floor(diff / 86400000));
            if(els.hours) els.hours.textContent     = pad(Math.floor((diff % 86400000) / 3600000));
            if(els.minutes) els.minutes.textContent = pad(Math.floor((diff % 3600000)  / 60000));
            if(els.seconds) {
                els.seconds.textContent = pad(Math.floor((diff % 60000)    / 1000));
                els.seconds.classList.add('tick');
                setTimeout(() => els.seconds.classList.remove('tick'), 300);
            }
        }
        tick();
        setInterval(tick, 1000);
    })();
</script>

<script async src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"         onload="initSwiper()"></script>
<script async src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js" onload="initFancybox()"></script>
<script>
function initSwiper() {
    const skeleton = document.getElementById('judges-skeleton');
    const wrap     = document.getElementById('judges-swiper-wrap');
    if (!wrap) return;
    new Swiper('.judgesSwiper', {
        slidesPerView: 'auto', spaceBetween: 28, loop: true, grabCursor: true,
        autoplay: { delay: 4000, disableOnInteraction: false },
        navigation: { nextEl: '.swiper-nav-next', prevEl: '.swiper-nav-prev' },
    });
    if (skeleton) skeleton.style.display = 'none';
    wrap.classList.remove('hidden');
}
function initFancybox() {
    Fancybox.bind('[data-fancybox]', {
        Toolbar: { display: { left: ['infobar'], right: ['close'] } }
    });
}
</script>
@endsection