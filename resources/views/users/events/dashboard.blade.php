@extends('layouts.app')

@section('content')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.css" />

    <style>
        /* Cyberpunk Smooth Scrolling & Glassmorphism */
        .glass-card {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .swiper {
            width: 100%;
            padding-top: 20px;
            padding-bottom: 50px;
        }

        /* Animation for Stat Circles */
        .stat-circle-border {
            background: conic-gradient(from 0deg, transparent, #06b6d4, transparent);
            animation: rotate 4s linear infinite;
        }

        @keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }

        /* Judge Image Hover Effect */
        .judge-card:hover .zoom-img {
            transform: scale(1.1);
        }

        .btn-glow:hover {
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.4);
            transform: translateY(-2px);
        }
    </style>

    <div class="container mx-auto px-4 py-10 min-h-screen">

        {{-- Header Navigation --}}
        <div class="flex flex-col md:flex-row justify-between items-center gap-6 border-b border-white/10 pb-8 mb-12">
            <div>
                <h2 class="heading-font text-3xl font-black text-white uppercase tracking-tighter">
                    {{ $event->name }} <span class="text-cyan-500">Hub</span>
                </h2>
                <p class="text-slate-500 text-xs mt-1 font-mono uppercase tracking-[0.3em]">Management Dashboard v2.0</p>
            </div>
            <button onclick="window.history.back()"
                class="flex items-center gap-3 px-6 py-3 bg-slate-900 border border-slate-700 rounded-2xl hover:border-cyan-500 group transition-all btn-glow">
                <i class="fa-solid fa-arrow-left-long text-cyan-500 group-hover:-translate-x-2 transition-transform"></i>
                <span class="text-xs font-bold text-slate-300 uppercase tracking-widest">Return to Base</span>
            </button>
        </div>

        <div class="max-w-7xl mx-auto">

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-16">
                {{-- Card Item Template --}}
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
                @endphp

                @foreach ($stats as $stat)
                    <a href="{{ $stat['route'] }}"
                        class="glass-card p-6 rounded-[2rem] text-center group transition-all hover:border-{{ $stat['color'] }}-500/50">
                        <i class="fa-solid {{ $stat['icon'] }} text-{{ $stat['color'] }}-500/30 text-2xl mb-2"></i>
                        <h4
                            class="text-3xl font-black text-white group-hover:text-{{ $stat['color'] }}-400 transition-colors">
                            {{ $stat['value'] }}</h4>
                        <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold mt-2">{{ $stat['label'] }}
                        </p>
                    </a>
                @endforeach

                {{-- Conditional Logic for IUPC/Selection --}}
                @if ($event->slug === 'iupc' || in_array($event->slug, ['ai-hackathon', 'project-showcase']))
                    @php
                        $isIupc = $event->slug === 'iupc';
                        $route = $isIupc
                            ? route('event.slot_list', $event->slug)
                            : route('event.select_registered', $event->slug);
                        $dispCount = $isIupc ? $totalSlots ?? 0 : $counts['selected'] ?? 0;
                        $label = $isIupc ? 'Available Slots' : 'Final Selected';
                        $color = $isIupc ? 'blue' : 'purple';
                    @endphp
                    <a href="{{ $route }}"
                        class="glass-card p-6 rounded-[2rem] text-center group transition-all border-{{ $color }}-500/20 hover:border-{{ $color }}-500/50">
                        <i class="fa-solid fa-id-badge text-{{ $color }}-500/30 text-2xl mb-2"></i>
                        <h4 class="text-3xl font-black text-white group-hover:text-{{ $color }}-400">
                            {{ $dispCount }}</h4>
                        <p class="text-[10px] uppercase tracking-widest text-slate-500 font-bold mt-2">{{ $label }}
                        </p>
                    </a>
                @endif
            </div>

            {{-- Action Buttons --}}
            <div class="flex flex-wrap justify-center gap-4 mb-20">
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

                {{-- Primary Register Button --}}
                @if (!$isDeadlineOver)
                    <a href="{{ route('event.register', $event->slug) }}"
                        class="px-8 py-4 bg-cyan-500 text-slate-950 font-black rounded-2xl btn-glow flex items-center gap-2 uppercase text-sm tracking-tighter">
                        <i class="fa-solid fa-bolt"></i> Register Now
                    </a>
                @else
                    <button disabled
                        class="px-8 py-4 bg-red-500/10 border border-red-500/20 text-red-500 rounded-2xl opacity-50 flex items-center gap-2 uppercase text-sm font-bold">
                        <i class="fa-solid fa-lock"></i> Admission Closed
                    </button>
                @endif

                @foreach ($actions as $action)
                    <a href="{{ $action['url'] ?? '#' }}" target="_blank"
                        class="px-6 py-4 glass-card rounded-2xl text-{{ $action['color'] }}-400 border-{{ $action['color'] }}-500/20 hover:border-{{ $action['color'] }}-500/50 transition-all flex items-center gap-2 uppercase text-xs font-bold tracking-widest {{ !$action['url'] ? 'opacity-30 cursor-not-allowed' : '' }}">
                        <i class="fa-solid {{ $action['icon'] }}"></i> {{ $action['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- Judges Panel Section --}}
            @if (!empty($event->images) && is_array($event->images))
                <section class="mt-32">
                    <div class="flex flex-col items-center mb-12">
                        <div class="bg-cyan-500/10 border border-cyan-500/20 px-4 py-1 rounded-full mb-4">
                            <span class="text-cyan-500 font-mono text-[10px] uppercase tracking-[0.4em]">Expert Panel</span>
                        </div>
                        <h3 class="text-4xl font-black text-white uppercase tracking-tighter text-center">
                            Meet Our <span class="text-cyan-500">Distinguished</span> Judges
                        </h3>
                    </div>

                    <div class="relative px-12 group">
                        <div class="swiper judgesSwiper">
                            <div class="swiper-wrapper">
                                @foreach ($event->images as $image)
                                    <div class="swiper-slide !w-auto">
                                        {{-- Image Card --}}
                                        <div
                                            class="judge-card relative w-[280px] h-[380px] rounded-[2.5rem] overflow-hidden border border-white/5 bg-slate-900 group/item">

                                            {{-- Zoom and View Button (Fancybox) --}}
                                            <a href="{{ asset('storage/' . $image) }}" data-fancybox="judges"
                                                data-caption="Judge {{ $loop->iteration }}"
                                                class="absolute top-4 right-4 z-20 w-10 h-10 bg-black/40 backdrop-blur-md rounded-full flex items-center justify-center text-white opacity-0 group-hover/item:opacity-100 transition-opacity">
                                                <i class="fa-solid fa-expand"></i>
                                            </a>

                                            <img src="{{ asset('storage/' . $image) }}"
                                                class="zoom-img w-full h-full object-cover transition-transform duration-700"
                                                alt="Judge">

                                            {{-- Overlay Info --}}
                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent">
                                            </div>
                                            <div class="absolute bottom-6 left-6 right-6">
                                                <div class="flex items-center gap-2 mb-2">
                                                    <span class="h-1 w-8 bg-cyan-500 rounded-full"></span>
                                                    <span
                                                        class="text-cyan-400 font-mono text-[10px] uppercase tracking-widest">Evaluator</span>
                                                </div>
                                                <p class="text-white font-bold text-lg leading-tight uppercase">Expert Node
                                                    {{ $loop->iteration }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Professional Nav Buttons --}}
                        <div
                            class="swiper-nav-prev absolute top-1/2 left-0 -translate-y-1/2 w-12 h-12 glass-card rounded-full flex items-center justify-center text-cyan-500 cursor-pointer z-30 hover:bg-cyan-500 hover:text-slate-950 transition-all border-cyan-500/30">
                            <i class="fa-solid fa-chevron-left"></i>
                        </div>
                        <div
                            class="swiper-nav-next absolute top-1/2 right-0 -translate-y-1/2 w-12 h-12 glass-card rounded-full flex items-center justify-center text-cyan-500 cursor-pointer z-30 hover:bg-cyan-500 hover:text-slate-950 transition-all border-cyan-500/30">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </div>
                </section>
            @endif

        </div>
    </div>

    {{-- Stats Bar Section --}}
    <section class="py-20 bg-slate-900/50 border-y border-white/5 relative overflow-hidden">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/10 blur-[120px] rounded-full"></div>

        <div class="container mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 items-center">

                {{-- Countdown --}}
                <div class="text-center md:text-left">
                    <p class="text-slate-500 text-[10px] font-black tracking-[0.3em] mb-4 uppercase">System Countdown</p>
                    <div class="flex gap-3 justify-center md:justify-start" id="countdown-wrapper">
                        @foreach (['days', 'hours', 'minutes', 'seconds'] as $unit)
                            <div class="flex flex-col items-center">
                                <div id="{{ $unit }}"
                                    class="w-16 h-16 glass-card rounded-2xl flex items-center justify-center text-2xl font-black text-cyan-400 border-cyan-500/20">
                                    00
                                </div>
                                <span
                                    class="text-[8px] text-slate-500 uppercase mt-2 font-bold tracking-widest">{{ $unit }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Total Registration Circle --}}
                <div class="flex flex-col items-center">
                    <div class="relative w-40 h-40 flex items-center justify-center">
                        <div class="stat-circle-border absolute inset-0 rounded-full"></div>
                        <div
                            class="absolute inset-[3px] bg-slate-950 rounded-full flex flex-col items-center justify-center border border-white/10">
                            <span
                                class="text-4xl font-black text-white italic">{{ number_format($totalRegistered) }}</span>
                            <span class="text-[9px] text-slate-500 uppercase font-bold tracking-widest">Enrolled</span>
                        </div>
                    </div>
                </div>

                {{-- Event Date --}}
                <div class="text-center md:text-right">
                    <p class="text-slate-500 text-[10px] font-black tracking-[0.3em] mb-4 uppercase">Main Deployment</p>
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

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@5.0/dist/fancybox/fancybox.umd.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Swiper for Judges
            new Swiper(".judgesSwiper", {
                slidesPerView: "auto",
                spaceBetween: 30,
                centeredSlides: false,
                loop: true,
                grabCursor: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false
                },
                navigation: {
                    nextEl: ".swiper-nav-next",
                    prevEl: ".swiper-nav-prev",
                },
                breakpoints: {
                    640: {
                        spaceBetween: 20
                    },
                    1024: {
                        spaceBetween: 30
                    }
                }
            });

            // Initialize Fancybox (For zooming images)
            Fancybox.bind("[data-fancybox]", {
                Toolbar: {
                    display: {
                        left: ["infobar"],
                        right: ["close"]
                    }
                },
            });

            // Countdown Logic
            const targetDate = new Date("{{ $event->end_date }}").getTime();
            const timer = setInterval(() => {
                const now = new Date().getTime();
                const diff = targetDate - now;

                if (diff <= 0) {
                    clearInterval(timer);
                    document.getElementById("countdown-wrapper").innerHTML =
                        "<p class='text-red-500 font-black'>MISSION EXPIRED</p>";
                    return;
                }

                document.getElementById("days").innerText = Math.floor(diff / (1000 * 60 * 60 * 24))
                    .toString().padStart(2, '0');
                document.getElementById("hours").innerText = Math.floor((diff % (1000 * 60 * 60 * 24)) / (
                    1000 * 60 * 60)).toString().padStart(2, '0');
                document.getElementById("minutes").innerText = Math.floor((diff % (1000 * 60 * 60)) / (
                    1000 * 60)).toString().padStart(2, '0');
                document.getElementById("seconds").innerText = Math.floor((diff % (1000 * 60)) / 1000)
                    .toString().padStart(2, '0');
            }, 1000);
        });
    </script>
@endsection
