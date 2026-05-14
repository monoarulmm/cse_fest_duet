@extends('layouts.app')

@section('content')
    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .swiper {
            width: 100%;
            padding-top: 50px;
            padding-bottom: 50px;
        }

        .swiper-slide {
            background-position: center;
            background-size: cover;
            width: 300px;
            height: 400px;
            border-radius: 20px;
            border: 1px solid rgba(34, 211, 238, 0.2);
        }

        .swiper-pagination-bullet {
            background: #06b6d4 !important;
        }

        .stat-circle {
            position: relative;
            overflow: hidden;
        }

        .stat-circle::after {
            content: '';
            position: absolute;
            inset: -2px;
            background: conic-gradient(from 0deg, transparent, #06b6d4, transparent);
            animation: rotate 4s linear infinite;
            border-radius: 50%;
            z-index: -1;
        }

        @keyframes rotate {
            100% {
                transform: rotate(360deg);
            }
        }
    </style>

    <div class="container mx-auto px-4 py-10">
        <div class="max-w-6xl mx-auto">

            {{-- Event Header --}}
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-white uppercase tracking-tighter">{{ $event->name }}</h1>
                <div class="flex justify-center items-center gap-2 mt-2">
                    <span class="h-[1px] w-10 bg-cyan-500"></span>
                    <p class="text-cyan-400 tracking-widest text-sm italic uppercase">Registration Portal</p>
                    <span class="h-[1px] w-10 bg-cyan-500"></span>
                </div>
            </div>

            {{-- Stats Cards --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-16">
                {{-- Pre-registered --}}
                <a href="{{ route('event.pre_registered', [$event->slug, 'status' => 'pre-registered']) }}"
                    class="group relative overflow-hidden bg-slate-900/40 border border-slate-800/50 p-6 rounded-[2rem] text-center transition-all hover:border-cyan-500/50 hover:shadow-[0_0_20px_rgba(34,211,238,0.15)] block">
                    <h4 class="text-3xl font-black text-white group-hover:text-cyan-400">
                        {{ $counts['pre-registered'] ?? 0 }}</h4>
                    <p class="text-[9px] uppercase tracking-widest text-slate-500 font-bold mt-2">Registered</p>
                </a>

                {{-- Verified --}}
                <a href="{{ route('event.final_registered', [$event->slug, 'status' => 'verified']) }}"
                    class="group relative overflow-hidden bg-slate-900/40 border border-slate-800/50 p-6 rounded-[2rem] text-center transition-all hover:border-green-500/50 hover:shadow-[0_0_20px_rgba(34,197,94,0.15)] block">
                    <h4 class="text-3xl font-black text-white group-hover:text-green-400">{{ $counts['verified'] ?? 0 }}
                    </h4>
                    <p class="text-[9px] uppercase tracking-widest text-slate-500 font-bold mt-2">Paid / Verified</p>
                </a>

                {{-- Conditional Slot/Selected --}}
                @if ($event->slug !== 'ict-olympiad')
                    @php
                        $isIupc = $event->slug == 'iupc';
                        $route = $isIupc
                            ? route('event.slot_list', $event->slug)
                            : route('event.select_registered', $event->slug);
                        $colorClass = $isIupc
                            ? 'hover:border-blue-500/50 hover:text-blue-400'
                            : 'hover:border-purple-500/50 hover:text-purple-400';
                        $countKey = $isIupc ? 'slots' : 'selected';
                        $label = $isIupc ? 'Slot List' : 'Selected';
                    @endphp
                    <a href="{{ $route }}"
                        class="group relative overflow-hidden bg-slate-900/40 border border-slate-800/50 p-6 rounded-[2rem] text-center transition-all {{ $colorClass }} block">
                        <h4 class="text-3xl font-black text-white transition-colors">{{ $counts[$countKey] ?? 0 }}</h4>
                        <p class="text-[9px] uppercase tracking-widest text-slate-500 font-bold mt-2">{{ $label }}
                        </p>
                    </a>
                @endif

                {{-- Institutions --}}
                <a href="{{ route('event.institutes', $event->slug) }}"
                    class="group relative overflow-hidden bg-slate-900/40 border border-slate-800/50 p-6 rounded-[2rem] text-center transition-all hover:border-amber-500/50 block">
                    <h4 class="text-3xl font-black text-white group-hover:text-amber-400">{{ $counts['institutes'] ?? 0 }}
                    </h4>
                    <p class="text-[9px] uppercase tracking-widest text-slate-500 font-bold mt-2">Institutions</p>
                </a>
            </div>

            {{-- Dynamic Tab Menu --}}
            <div class="flex flex-wrap justify-center gap-3 mb-10">
                @php $isDeadlineOver = $event->end_date && now()->gt($event->end_date); @endphp

                @if (!$isDeadlineOver)
                    <a href="{{ route('event.register', $event->slug) }}"
                        class="px-6 py-3 rounded-xl border {{ request()->routeIs('event.register') ? 'bg-cyan-500 text-slate-900 font-bold' : 'bg-slate-900 text-cyan-400 border-cyan-500/20' }}">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> Register Now
                    </a>
                @else
                    <button disabled
                        class="px-6 py-3 rounded-xl border border-red-500/20 bg-red-500/10 text-red-500 opacity-60">
                        <i class="fa-solid fa-lock mr-2"></i> Closed
                    </button>
                @endif


                {{-- Rulebook Link --}}
                <a href="{{ $event->rules ?? '#' }}" target="_blank"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ !$event->rules ? 'opacity-50 cursor-not-allowed' : '' }} bg-slate-900/50 text-purple-400 border-purple-500/20 hover:border-purple-500/50 hover:bg-purple-500/10 hover:shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-book-open mr-2"></i> Rulebook
                </a>

                {{-- Result Link --}}
                <a href="{{ $event->result ?? '#' }}" target="_blank"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ !$event->result ? 'opacity-50 cursor-not-allowed' : '' }} bg-slate-900/50 text-purple-400 border-purple-500/20 hover:border-purple-500/50 hover:bg-purple-500/10 hover:shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-square-poll-vertical mr-2"></i> Result
                </a>

                {{-- Seat Plan Link --}}
                <a href="{{ $event->seatplan ?? '#' }}" target="_blank"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ !$event->seatplan ? 'opacity-50 cursor-not-allowed' : '' }} bg-slate-900/50 text-purple-400 border-purple-500/20 hover:border-purple-500/50 hover:bg-purple-500/10 hover:shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-map-location-dot mr-2"></i> Seat Plan
                </a>


                <a href="{{ route('event.schedule', $event->slug) }}"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ request()->routeIs('event.final_registered') ? 'bg-green-500 text-slate-900 font-bold shadow-[0_0_15px_rgba(34,197,94,0.4)]' : 'bg-slate-900/50 text-green-400 border-green-500/20 hover:border-green-500/50' }}">
                    Schedule
                </a>

                <a href="{{ route('event.pre_registered', $event->slug) }}"
                    class="px-6 py-3 rounded-xl border {{ request()->routeIs('event.register') ? 'bg-cyan-500 text-slate-900 font-bold' : 'bg-slate-900 text-cyan-400 border-cyan-500/20' }}">
                    <i class="fa-solid fa-pen-to-square mr-1"></i> Final Register Now </a>
            </div>

            {{-- Professional Image Slider Section --}}
            @if (!empty($judges) && count($judges) > 0)
                <div class="mt-20">
                    <div class="mb-10 border-l-4 border-cyan-500 pl-6">
                        <h3 class="text-2xl font-bold uppercase text-white tracking-widest">Event <span
                                class="text-cyan-500">Highlights</span></h3>
                        <p class="text-slate-500 font-mono text-xs mt-1">>> VIEWING_RECORDS_SUCCESS</p>
                    </div>

                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            @foreach ($judges as $img)
                                <div class="swiper-slide group">
                                    <img src="{{ asset('storage/' . $img) }}"
                                        class="w-full h-full object-cover rounded-2xl" alt="Highlight" />
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-80 rounded-2xl">
                                    </div>
                                    <div class="absolute bottom-4 left-4">
                                        <p class="text-cyan-400 font-mono text-[10px] tracking-widest uppercase">
                                            <i class="fa-solid fa-microchip mr-1"></i> NODE_{{ $loop->iteration }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                        <!-- Navigation buttons -->
                        <div class="swiper-button-next !text-cyan-500 after:!text-2xl"></div>
                        <div class="swiper-button-prev !text-cyan-500 after:!text-2xl"></div>
                    </div>
                </div>
            @endif

        </div>

    </div>

    {{-- Fixed Size Event Judges Panel Slider --}}
    <section class="mt-20 mb-20 px-4">
        {{-- Section Header --}}
        <div class="max-w-6xl mx-auto mb-10 border-l-4 border-cyan-500 pl-6">
            <h3 class="text-2xl font-bold uppercase text-white tracking-widest">
                {{ $event->name }} <span class="text-cyan-500">Judges Panel</span>
            </h3>
            <p class="text-slate-500 font-mono text-xs mt-1">>> VIEWING_FIXED_DATA_NODES</p>
        </div>

        @if (!empty($event->images) && is_array($event->images))
            <div class="max-w-7xl mx-auto relative group">
                <div class="swiper fixedJudges PanelSwiper py-10">
                    <div class="swiper-wrapper">
                        @foreach ($event->images as $image)
                            <div class="swiper-slide !w-auto">
                                {{-- Fixed Size Container --}}
                                <div
                                    class="relative w-[280px] h-[200px] md:w-[400px] md:h-[300px] overflow-hidden rounded-2xl border border-slate-800 bg-slate-900 transition-all duration-500 hover:border-cyan-500/50 hover:shadow-[0_0_30px_rgba(6,182,212,0.2)]">

                                    {{-- Fixed Size Image --}}
                                    <img src="{{ asset('storage/' . $image) }}" alt="Event Image"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">

                                    {{-- Gradient Overlay --}}
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent opacity-70">
                                    </div>

                                    {{-- Label --}}
                                    <div class="absolute bottom-4 left-4">
                                        <p class="text-cyan-400 font-mono text-[10px] tracking-widest uppercase">
                                            <i class="fa-solid fa-image mr-1"></i> NODE_{{ $loop->iteration }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- Pagination --}}
                    <div class="swiper-pagination !-bottom-2"></div>
                </div>

                {{-- Custom Arrows --}}
                <div
                    class="Judges Panel-prev absolute top-1/2 -left-4 md:-left-12 -translate-y-1/2 z-20 text-cyan-500 cursor-pointer opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <i class="fa-solid fa-circle-arrow-left text-4xl"></i>
                </div>
                <div
                    class="Judges Panel-next absolute top-1/2 -right-4 md:-right-12 -translate-y-1/2 z-20 text-cyan-500 cursor-pointer opacity-0 group-hover:opacity-100 transition-all duration-300">
                    <i class="fa-solid fa-circle-arrow-right text-4xl"></i>
                </div>
            </div>
        @else
            <div
                class="max-w-6xl mx-auto bg-slate-900/30 border border-dashed border-slate-800 rounded-3xl p-16 text-center">
                <p class="text-slate-500 italic font-mono text-sm uppercase">No Images Found</p>
            </div>
        @endif
    </section>

    {{-- Swiper CSS & JS (Ensure these are in your layout or here) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper(".fixedJudges PanelSwiper", {
                slidesPerView: "auto", // ইমেজগুলোর ফিক্সড উইডথ বজায় রাখার জন্য এটি জরুরি
                spaceBetween: 25,
                centeredSlides: false,
                loop: true,
                grabCursor: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".Judges Panel-next",
                    prevEl: ".Judges Panel-prev",
                },
            });
        });
    </script>

    <style>
        /* Pagination Color Correction */
        .swiper-pagination-bullet {
            background: #64748b !important;
        }

        .swiper-pagination-bullet-active {
            background: #06b6d4 !important;
            box-shadow: 0 0 10px #06b6d4;
        }

        /* Ensure all images maintain aspect ratio */
        .swiper-slide img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* এটি ইমেজকে ক্রপ করে ফিক্সড বক্সে সুন্দরভাবে ফিট করবে */
        }
    </style>
    {{-- Countdown Section --}}
    <section class="py-12 bg-slate-950/80 border-y border-cyan-500/10">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-10 items-center">
            <div class="text-center md:text-left">
                <p class="text-red-500 text-xs font-bold tracking-[0.2em] mb-4 uppercase">Registration Closes In</p>
                <div class="flex gap-2 justify-center md:justify-start" id="countdown-wrapper">
                    <div class="flex flex-col items-center">
                        <div id="days"
                            class="w-14 h-14 flex items-center justify-center text-xl font-black text-cyan-400 border border-cyan-500/20 bg-slate-900 rounded-lg">
                            00</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div id="hours"
                            class="w-14 h-14 flex items-center justify-center text-xl font-black text-cyan-400 border border-cyan-500/20 bg-slate-900 rounded-lg">
                            00</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div id="minutes"
                            class="w-14 h-14 flex items-center justify-center text-xl font-black text-cyan-400 border border-cyan-500/20 bg-slate-900 rounded-lg">
                            00</div>
                    </div>
                    <div class="flex flex-col items-center">
                        <div id="seconds"
                            class="w-14 h-14 flex items-center justify-center text-xl font-black text-red-500 border border-red-500/20 bg-slate-900 rounded-lg animate-pulse">
                            00</div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center">
                <p class="text-xs font-bold tracking-[0.2em] mb-4 uppercase text-white">Total Registered</p>
                <div class="stat-circle w-28 h-28 rounded-full flex items-center justify-center bg-slate-900 z-10">
                    <span class="text-3xl font-black text-white italic">{{ number_format($totalRegistered) }}</span>
                </div>
            </div>

            <div class="text-center md:text-right">
                <p class="text-cyan-500 text-xs font-bold tracking-[0.2em] mb-4 uppercase">Main Event Date</p>
                <div class="inline-block px-8 py-4 border border-red-500/20 bg-slate-900 rounded-xl">
                    <span class="text-2xl font-black text-red-500 italic uppercase">
                        {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>
    </section>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        // Initialize Swiper
        var swiper = new Swiper(".mySwiper", {
            effect: "coverflow",
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: "auto",
            loop: true,
            coverflowEffect: {
                rotate: 20,
                stretch: 0,
                depth: 200,
                modifier: 1,
                slideShadows: true,
            },
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
        });

        // Countdown logic
        (function() {
            const targetDate = new Date("{{ $event->end_date }}").getTime();
            const updateCountdown = () => {
                const now = new Date().getTime();
                const gap = targetDate - now;
                if (gap <= 0) {
                    document.getElementById("countdown-wrapper").innerHTML =
                        "<span class='text-red-500 font-black uppercase'>Closed</span>";
                    return;
                }
                const d = Math.floor(gap / (1000 * 60 * 60 * 24));
                const h = Math.floor((gap % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const m = Math.floor((gap % (1000 * 60 * 60)) / (1000 * 60));
                const s = Math.floor((gap % (1000 * 60)) / 1000);

                document.getElementById("days").innerText = d.toString().padStart(2, '0');
                document.getElementById("hours").innerText = h.toString().padStart(2, '0');
                document.getElementById("minutes").innerText = m.innerText = m.toString().padStart(2, '0');
                document.getElementById("seconds").innerText = s.toString().padStart(2, '0');
            };
            setInterval(updateCountdown, 1000);
            updateCountdown();
        })();
    </script>
@endsection
