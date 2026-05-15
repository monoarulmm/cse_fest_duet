@extends('layouts.app')

@section('content')

    {{-- ============================================================
     CRITICAL FIX 1: Preload external CSS/fonts so no FOUC
     ============================================================ --}}
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" as="style"
        onload="this.rel='stylesheet'">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" as="style"
        onload="this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css">
    </noscript>

    <style>
        /* ============================================================
                   CRITICAL FIX 2: All dynamic Tailwind color classes hardcoded
                   here so PurgeCSS never removes them at build time.
                   ============================================================ */

        /* Stat card colors */
        .stat-icon-cyan {
            background: rgba(6, 182, 212, .1);
        }

        .stat-icon-green {
            background: rgba(34, 197, 94, .1);
        }

        .stat-icon-amber {
            background: rgba(245, 158, 11, .1);
        }

        .stat-icon-blue {
            background: rgba(59, 130, 246, .1);
        }

        .stat-icon-purple {
            background: rgba(168, 85, 247, .1);
        }

        .stat-icon-cyan i {
            color: #06b6d4;
        }

        .stat-icon-green i {
            color: #22c55e;
        }

        .stat-icon-amber i {
            color: #f59e0b;
        }

        .stat-icon-blue i {
            color: #3b82f6;
        }

        .stat-icon-purple i {
            color: #a855f7;
        }

        .stat-card:hover .stat-value-cyan {
            color: #67e8f9;
        }

        .stat-card:hover .stat-value-green {
            color: #86efac;
        }

        .stat-card:hover .stat-value-amber {
            color: #fcd34d;
        }

        .stat-card:hover .stat-value-blue {
            color: #93c5fd;
        }

        .stat-card:hover .stat-value-purple {
            color: #d8b4fe;
        }

        .stat-card:hover.border-cyan {
            border-color: rgba(6, 182, 212, .4);
        }

        .stat-card:hover.border-green {
            border-color: rgba(34, 197, 94, .4);
        }

        .stat-card:hover.border-amber {
            border-color: rgba(245, 158, 11, .4);
        }

        .stat-card:hover.border-blue {
            border-color: rgba(59, 130, 246, .4);
        }

        .stat-card:hover.border-purple {
            border-color: rgba(168, 85, 247, .4);
        }

        /* Action button colors */
        .action-btn-purple {
            color: #c084fc;
            border-color: rgba(168, 85, 247, .2);
        }

        .action-btn-blue {
            color: #60a5fa;
            border-color: rgba(59, 130, 246, .2);
        }

        .action-btn-emerald {
            color: #34d399;
            border-color: rgba(52, 211, 153, .2);
        }

        .action-btn-orange {
            color: #fb923c;
            border-color: rgba(251, 146, 60, .2);
        }

        .action-btn-purple:hover {
            border-color: rgba(168, 85, 247, .5);
        }

        .action-btn-blue:hover {
            border-color: rgba(59, 130, 246, .5);
        }

        .action-btn-emerald:hover {
            border-color: rgba(52, 211, 153, .5);
        }

        .action-btn-orange:hover {
            border-color: rgba(251, 146, 60, .5);
        }

        /* ============================================================
                   GLASSMORPHISM BASE
                   ============================================================ */
        .glass-card {
            background: rgba(15, 23, 42, .6);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, .1);
        }

        /* ============================================================
                   SKELETON LOADER — shown while JS/images load
                   ============================================================ */
        @keyframes shimmer {
            0% {
                background-position: -800px 0;
            }

            100% {
                background-position: 800px 0;
            }
        }

        .skeleton {
            background: linear-gradient(90deg,
                    rgba(255, 255, 255, .04) 25%,
                    rgba(255, 255, 255, .08) 50%,
                    rgba(255, 255, 255, .04) 75%);
            background-size: 800px 100%;
            animation: shimmer 1.6s ease-in-out infinite;
            border-radius: 1rem;
        }

        /* ============================================================
                   STAT CARDS
                   ============================================================ */
        .stat-card {
            transition: transform .25s ease, border-color .25s ease;
            flex: 1;
            min-width: 160px;
            max-width: 240px;
        }

        .stat-card:hover {
            transform: translateY(-4px);
        }

        /* ============================================================
                   BUTTONS
                   ============================================================ */
        .btn-glow:hover {
            box-shadow: 0 0 20px rgba(6, 182, 212, .35);
            transform: translateY(-2px);
        }

        /* ============================================================
                   ROTATING BORDER ANIMATION (stat circle)
                   ============================================================ */
        .stat-circle-border {
            background: conic-gradient(from 0deg, transparent, #06b6d4, transparent);
            animation: spin-ring 4s linear infinite;
        }

        @keyframes spin-ring {
            to {
                transform: rotate(360deg);
            }
        }

        /* ============================================================
                   SWIPER
                   ============================================================ */
        .swiper {
            width: 100%;
            padding-top: 20px;
            padding-bottom: 50px;
        }

        /* ============================================================
                   JUDGE CARDS
                   ============================================================ */
        .judge-card {
            transition: transform .3s ease;
        }

        .judge-card:hover {
            transform: translateY(-6px);
        }

        .judge-card .zoom-img {
            transition: transform .7s ease;
        }

        .judge-card:hover .zoom-img {
            transform: scale(1.08);
        }

        /* ============================================================
                   PAGE FADE-IN — avoids FOUC while styles parse
                   ============================================================ */
        #page-root {
            opacity: 0;
            transition: opacity .35s ease;
        }

        #page-root.ready {
            opacity: 1;
        }

        /* ============================================================
                   CONTENT VISIBILITY — paint performance boost
                   ============================================================ */
        .cv-auto {
            content-visibility: auto;
            contain-intrinsic-size: 0 400px;
        }
    </style>

    {{-- ============================================================
     PAGE ROOT — fades in after CSS is ready
     ============================================================ --}}
    <div id="page-root">

        <div class="container mx-auto px-4 py-10 min-h-screen">

            {{-- ── Header ─────────────────────────────────────────── --}}
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 border-b border-white/10 pb-8 mb-12">
                <div>
                    <h2 class="heading-font text-3xl font-black text-white uppercase tracking-tighter">
                        {{ $event->name }} <span class="text-cyan-500">Hub</span>
                    </h2>
                    <p class="text-slate-500 text-xs mt-1 font-mono uppercase tracking-[0.3em]">
                        Management Dashboard v2.0
                    </p>
                </div>

                <button onclick="window.history.back()"
                    class="flex items-center gap-3 px-6 py-3 bg-slate-900 border border-slate-700 rounded-2xl
                       hover:border-cyan-500 group transition-all btn-glow">
                    <i
                        class="fa-solid fa-arrow-left-long text-cyan-500 group-hover:-translate-x-2 transition-transform"></i>
                    <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Return to Base</span>
                </button>
            </div>

            <div class="max-w-7xl mx-auto">

                {{-- ── Stats Grid ──────────────────────────────────── --}}
                @php
                    $stats = [
                        [
                            'label' => 'Total Registered',
                            'value' => $counts['pre-registered'] ?? 0,
                            'color' => 'cyan',
                            'icon' => 'fa-users',
                            'route' => route('event.pre_registered', [$event->slug, 'status' => 'pre-registered']),
                        ],
                        [
                            'label' => 'Verified / Paid',
                            'value' => $counts['verified'] ?? 0,
                            'color' => 'green',
                            'icon' => 'fa-circle-check',
                            'route' => route('event.final_registered', [$event->slug, 'status' => 'verified']),
                        ],
                        [
                            'label' => 'Institutions',
                            'value' => $counts['institutes'] ?? 0,
                            'color' => 'amber',
                            'icon' => 'fa-university',
                            'route' => route('event.institutes', $event->slug),
                        ],
                    ];

                    if ($event->slug === 'iupc' || in_array($event->slug, ['ai-hackathon', 'project-showcase'])) {
                        $isIupc = $event->slug === 'iupc';
                        $stats[] = [
                            'label' => $isIupc ? 'Available Slots' : 'Final Selected',
                            'value' => $isIupc ? $totalSlots ?? 0 : $counts['selected'] ?? 0,
                            'color' => $isIupc ? 'blue' : 'purple',
                            'icon' => 'fa-id-badge',
                            'route' => $isIupc
                                ? route('event.slot_list', $event->slug)
                                : route('event.select_registered', $event->slug),
                        ];
                    }
                @endphp

                <div class="flex flex-wrap justify-center gap-4 mb-16">
                    @foreach ($stats as $stat)
                        <a href="{{ $stat['route'] }}"
                            class="stat-card border-{{ $stat['color'] }} glass-card p-6 rounded-[2rem] text-center group
                              border border-slate-800/50 hover:bg-slate-800/20 shadow-xl">

                            {{-- Icon badge --}}
                            <div
                                class="stat-icon-{{ $stat['color'] }} inline-flex items-center justify-center
                                    w-12 h-12 rounded-2xl mb-4 group-hover:scale-110 transition-transform">
                                <i class="fa-solid {{ $stat['icon'] }} text-xl"></i>
                            </div>

                            {{-- Number --}}
                            <h4
                                class="stat-value-{{ $stat['color'] }} text-3xl font-black text-white
                                   transition-colors tracking-tight">
                                {{ number_format($stat['value']) }}
                            </h4>

                            {{-- Label --}}
                            <p class="text-[10px] uppercase font-bold tracking-[0.2em] text-slate-500 mt-2">
                                {{ $stat['label'] }}
                            </p>
                        </a>
                    @endforeach
                </div>

                {{-- ── Action Buttons ─────────────────────────────── --}}
                @php
                    $isDeadlineOver = $event->end_date && now()->gt($event->end_date);
                    $actions = [
                        ['label' => 'Rulebook', 'icon' => 'fa-book-open', 'url' => $event->rules, 'color' => 'purple'],
                        [
                            'label' => 'Result',
                            'icon' => 'fa-square-poll-vertical',
                            'url' => $event->result,
                            'color' => 'blue',
                        ],
                        [
                            'label' => 'Seat Plan',
                            'icon' => 'fa-map-location-dot',
                            'url' => $event->seatplan,
                            'color' => 'emerald',
                        ],
                        [
                            'label' => 'Schedule',
                            'icon' => 'fa-calendar-days',
                            'url' => route('event.schedule', $event->slug),
                            'color' => 'orange',
                        ],
                    ];
                @endphp

                <div class="flex flex-wrap justify-center gap-4 mb-20">

                    {{-- Register / Closed button --}}
                    @if (!$isDeadlineOver)
                        <a href="{{ route('event.register', $event->slug) }}"
                            class="px-8 py-4 bg-cyan-500 text-slate-950 font-black rounded-2xl btn-glow
                              flex items-center gap-2 uppercase text-sm tracking-tighter transition-all">
                            <i class="fa-solid fa-bolt"></i> Register Now
                        </a>
                    @else
                        <button disabled
                            class="px-8 py-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl
                               opacity-50 flex items-center gap-2 uppercase text-sm font-bold cursor-not-allowed">
                            <i class="fa-solid fa-lock"></i> Admission Closed
                        </button>
                    @endif

                    @foreach ($actions as $action)
                        <a href="{{ $action['url'] ?? '#' }}"
                            @if ($action['url']) target="_blank" rel="noopener" @endif
                            class="action-btn-{{ $action['color'] }} px-6 py-4 glass-card rounded-2xl
                              border transition-all flex items-center gap-2 uppercase text-xs font-bold
                              tracking-widest {{ !$action['url'] ? 'opacity-30 pointer-events-none' : '' }}">
                            <i class="fa-solid {{ $action['icon'] }}"></i> {{ $action['label'] }}
                        </a>
                    @endforeach

                    @php
                        $slug = $event->slug;

                        // নির্দিষ্ট ৩টি ইভেন্টের স্লাগ চেক করছি
                        $special_events = ['iupc', 'project-showcase', 'ai-hackathon'];

                        if ($slug == 'iupc') {
                            // শুধু IUPC এর জন্য
                            $url = route('event.pre_registered', $slug);
                        } elseif (!in_array($slug, $special_events)) {
                            // IUPC, Project, AI বাদে বাকি সব (ICT Olympiad এবং অন্যান্য) পেমেন্ট রাউটে যাবে
                            // নিশ্চিত করুন আপনার কাছে $registration->id বা সমতুল্য ডাটা আছে
                            $url = route('payment.make', $registration->id ?? $event->id);
                        } else {
                            // Project বা AI এর জন্য যদি আলাদা কিছু না থাকে তবে ডিফল্ট
                            $url = route('event.register', $slug);
                        }
                    @endphp

                    <a href="{{ $url }}"
                        class="px-6 py-3 rounded-xl border {{ request()->is('payment/*') ? 'bg-cyan-500 text-slate-900 font-bold' : 'bg-slate-900 text-cyan-400 border-cyan-500/20 hover:bg-cyan-500/10 transition-all' }}">
                        <i class="fa-solid fa-pen-to-square mr-1"></i>
                        Final Register Now
                    </a>
                </div>

                {{-- ── Judges Panel ─────────────────────────────────── --}}
                @if (!empty($event->images) && is_array($event->images))
                    <section class="mt-32 cv-auto">
                        <div class="flex flex-col items-center mb-12">
                            <div class="bg-cyan-500/10 border border-cyan-500/20 px-4 py-1 rounded-full mb-4">
                                <span class="text-cyan-500 font-mono text-[10px] uppercase tracking-[0.4em]">Expert
                                    Panel</span>
                            </div>
                            <h3 class="text-4xl font-black text-white uppercase tracking-tighter text-center">
                                Meet Our <span class="text-cyan-500">Distinguished</span> Judges
                            </h3>
                        </div>

                        {{-- Skeleton shown until Swiper initialises --}}
                        <div id="judges-skeleton" class="flex gap-6 overflow-hidden px-12 mb-6">
                            @for ($i = 0; $i < 4; $i++)
                                <div class="skeleton flex-none w-[280px] h-[380px] rounded-[2.5rem]"></div>
                            @endfor
                        </div>

                        <div id="judges-swiper-wrap" class="relative px-12 hidden">
                            <div class="swiper judgesSwiper">
                                <div class="swiper-wrapper">
                                    @foreach ($event->images as $image)
                                        <div class="swiper-slide !w-auto">
                                            <div
                                                class="judge-card relative w-[280px] h-[380px] rounded-[2.5rem]
                                                    overflow-hidden border border-white/5 bg-slate-900 group">

                                                {{-- Fancybox zoom trigger --}}
                                                <a href="{{ asset('storage/' . $image) }}" data-fancybox="judges"
                                                    data-caption="Judge {{ $loop->iteration }}"
                                                    class="absolute top-4 right-4 z-20 w-10 h-10 bg-black/40
                                                      backdrop-blur-md rounded-full flex items-center
                                                      justify-center text-white opacity-0
                                                      group-hover:opacity-100 transition-opacity">
                                                    <i class="fa-solid fa-expand"></i>
                                                </a>

                                                {{-- CRITICAL FIX 3: loading="lazy" + explicit dimensions --}}
                                                <img src="{{ asset('storage/' . $image) }}" loading="lazy" width="280"
                                                    height="380" class="zoom-img w-full h-full object-cover"
                                                    alt="Judge {{ $loop->iteration }}">

                                                <div
                                                    class="absolute inset-0 bg-gradient-to-t from-slate-950
                                                        via-slate-950/20 to-transparent pointer-events-none">
                                                </div>

                                                <div class="absolute bottom-6 left-6 right-6">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <span class="h-1 w-8 bg-cyan-500 rounded-full"></span>
                                                        <span
                                                            class="text-cyan-400 font-mono text-[10px]
                                                                 uppercase tracking-widest">Evaluator</span>
                                                    </div>
                                                    <p class="text-white font-bold text-lg leading-tight uppercase">
                                                        Expert Node {{ $loop->iteration }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <button aria-label="Previous judge"
                                class="swiper-nav-prev absolute top-1/2 left-0 -translate-y-1/2 w-12 h-12
                                   glass-card rounded-full flex items-center justify-center text-cyan-500
                                   cursor-pointer z-30 hover:bg-cyan-500 hover:text-slate-950
                                   transition-all border-cyan-500/30">
                                <i class="fa-solid fa-chevron-left"></i>
                            </button>
                            <button aria-label="Next judge"
                                class="swiper-nav-next absolute top-1/2 right-0 -translate-y-1/2 w-12 h-12
                                   glass-card rounded-full flex items-center justify-center text-cyan-500
                                   cursor-pointer z-30 hover:bg-cyan-500 hover:text-slate-950
                                   transition-all border-cyan-500/30">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </div>
                    </section>
                @endif

            </div>{{-- /max-w-7xl --}}
        </div>{{-- /container --}}

        {{-- ── Stats Bar ──────────────────────────────────────────── --}}
        <section class="py-20 bg-slate-900/50 border-y border-white/5 relative overflow-hidden cv-auto">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/10 blur-[120px] rounded-full
                    pointer-events-none"
                aria-hidden="true"></div>

            <div class="container mx-auto px-6 relative z-10">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-center">

                    {{-- Countdown --}}
                    <div class="text-center md:text-left">
                        <p class="text-slate-500 text-[10px] font-black tracking-[0.3em] mb-4 uppercase">
                            System Countdown
                        </p>

                        <div class="flex gap-3 justify-center md:justify-start" id="countdown-wrapper">
                            @foreach (['days', 'hours', 'minutes', 'seconds'] as $unit)
                                <div class="flex flex-col items-center">
                                    <div id="{{ $unit }}"
                                        class="w-16 h-16 glass-card rounded-2xl flex items-center
                                            justify-center text-2xl font-black text-cyan-400
                                            border-cyan-500/20 tabular-nums">
                                        00
                                    </div>
                                    <span
                                        class="text-[8px] text-slate-500 uppercase mt-2 font-bold
                                             tracking-widest">{{ $unit }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Enrolled Circle --}}
                    <div class="flex flex-col items-center">
                        <div class="relative w-40 h-40 flex items-center justify-center">
                            <div class="stat-circle-border absolute inset-0 rounded-full" aria-hidden="true"></div>
                            <div
                                class="absolute inset-[3px] bg-slate-950 rounded-full flex flex-col
                                    items-center justify-center border border-white/10">
                                <span class="text-4xl font-black text-white italic tabular-nums">
                                    {{ number_format($totalRegistered) }}
                                </span>
                                <span class="text-[9px] text-slate-500 uppercase font-bold tracking-widest">
                                    Enrolled
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Event Date --}}
                    <div class="text-center md:text-right">
                        <p class="text-slate-500 text-[10px] font-black tracking-[0.3em] mb-4 uppercase">
                            Main Deployment
                        </p>
                        <div class="inline-block px-10 py-5 glass-card rounded-[2rem] border-red-500/20">
                            <i class="fa-solid fa-calendar-check text-red-500 mr-2"></i>
                            <span class="text-2xl font-black text-white italic uppercase tracking-tighter">
                                {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>
        </section>

    </div>{{-- /#page-root --}}

    {{-- ============================================================
     CRITICAL FIX 4: Scripts at bottom, loaded async/defer
     ============================================================ --}}
    <script>
        // Reveal page immediately (no FOUC)
        document.getElementById('page-root').classList.add('ready');

        // Countdown — runs before Swiper/Fancybox load
        (function() {
            var target = new Date("{{ $event->end_date }}").getTime();
            var pad = function(n) {
                return String(n).padStart(2, '0');
            };
            var els = {
                days: document.getElementById('days'),
                hours: document.getElementById('hours'),
                minutes: document.getElementById('minutes'),
                seconds: document.getElementById('seconds'),
            };

            var tick = function() {
                var diff = target - Date.now();
                if (diff <= 0) {
                    document.getElementById('countdown-wrapper').innerHTML =
                        "<p class='text-red-500 font-black tracking-widest uppercase text-sm'>Mission Expired</p>";
                    return;
                }
                els.days.textContent = pad(Math.floor(diff / 86400000));
                els.hours.textContent = pad(Math.floor((diff % 86400000) / 3600000));
                els.minutes.textContent = pad(Math.floor((diff % 3600000) / 60000));
                els.seconds.textContent = pad(Math.floor((diff % 60000) / 1000));
            };

            tick();
            setInterval(tick, 1000);
        })();
    </script>

    {{-- CRITICAL FIX 5: Swiper & Fancybox loaded async after page paint --}}
    <script async src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" onload="initSwiper()"></script>
    <script async src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"
        onload="initFancybox()"></script>

    <script>
        function initSwiper() {
            var skeleton = document.getElementById('judges-skeleton');
            var wrap = document.getElementById('judges-swiper-wrap');
            if (!wrap) return;

            new Swiper('.judgesSwiper', {
                slidesPerView: 'auto',
                spaceBetween: 30,
                loop: true,
                grabCursor: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false
                },
                navigation: {
                    nextEl: '.swiper-nav-next',
                    prevEl: '.swiper-nav-prev',
                },
            });

            // Swap skeleton → real slider
            if (skeleton) skeleton.style.display = 'none';
            wrap.classList.remove('hidden');
        }

        function initFancybox() {
            Fancybox.bind('[data-fancybox]', {
                Toolbar: {
                    display: {
                        left: ['infobar'],
                        right: ['close']
                    },
                },
            });
        }
    </script>

@endsection
