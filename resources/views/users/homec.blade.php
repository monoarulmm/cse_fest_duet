@extends('layouts.app')

@section('custom_css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        /* ═══════════════════════════════════════════════
                   CSS VARIABLES — Dark & Light mode সব এখানে
                ═══════════════════════════════════════════════ */
        :root {
            --page-bg: #020617;
            --hero-overlay1: rgba(2, 6, 23, 0.95);
            --hero-overlay2: rgba(2, 6, 23, 0.50);
            --section-border: rgba(255, 255, 255, 0.06);
            --card-bg: rgba(15, 23, 42, 0.60);
            --card-border: rgba(255, 255, 255, 0.08);
            --text-muted: #64748b;
            --text-body: #cbd5e1;
            --sponsor-bg: rgba(15, 23, 42, 0.40);
            --sponsor-border: rgba(255, 255, 255, 0.08);
            --gallery-bg: #0f172a;
        }

        /* Light mode overrides */
        body.light-mode {
            --page-bg: #f1f5f9;
            --hero-overlay1: rgba(241, 245, 249, 0.90);
            --hero-overlay2: rgba(241, 245, 249, 0.30);
            --section-border: rgba(0, 0, 0, 0.06);
            --card-bg: rgba(255, 255, 255, 0.80);
            --card-border: rgba(0, 0, 0, 0.10);
            --text-muted: #475569;
            --text-body: #334155;
            --sponsor-bg: rgba(255, 255, 255, 0.70);
            --sponsor-border: rgba(0, 0, 0, 0.10);
            --gallery-bg: #ffffff;
        }

        /* ── Hero ────────────────────────────────── */
        .hero-section {
            background-color: var(--page-bg);
            transition: background-color 0.4s;
        }

        /* Gradient overlay — mode অনুযায়ী বদলায় */
        .hero-gradient {
            background: linear-gradient(to top,
                    var(--hero-overlay1) 0%,
                    transparent 50%,
                    var(--hero-overlay2) 100%);
            transition: background 0.4s;
        }

        /* Countdown card */
        .countdown-card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            backdrop-filter: blur(8px);
            transition: background 0.4s, border-color 0.4s;
        }

        .countdown-number {
            color: #22d3ee;
        }

        .countdown-label {
            color: var(--text-muted);
        }

        /* Hero text */
        .hero-tagline {
            border-color: #22d3ee;
            color: var(--text-body);
            transition: color 0.4s;
        }

        /* ── Sponsors section ────────────────────── */
        .sponsors-section {
            background-color: var(--page-bg);
            border-top: 1px solid var(--section-border);
            transition: background-color 0.4s, border-color 0.4s;
        }

        .sponsor-card {
            background: var(--sponsor-bg);
            border: 1px solid var(--sponsor-border);
            transition: background 0.4s, border-color 0.4s;
        }

        body.light-mode .sponsor-card:hover {
            border-color: rgba(6, 182, 212, 0.5);
        }

        /* ── Gallery section ─────────────────────── */
        .gallery-section {
            background-color: var(--page-bg);
            border-top: 1px solid var(--section-border);
            transition: background-color 0.4s, border-color 0.4s;
        }

        .gallery-card {
            background: var(--gallery-bg);
            border: 1px solid var(--card-border);
            transition: background 0.4s, border-color 0.4s, transform 0.4s;
        }

        body.light-mode .gallery-card:hover {
            border-color: rgba(6, 182, 212, 0.5);
            box-shadow: 0 20px 40px rgba(6, 182, 212, 0.08);
        }

        /* Section heading — mode responsive */
        .section-heading {
            color: var(--text-body) !important;
            transition: color 0.4s;
        }

        body:not(.light-mode) .section-heading {
            color: #ffffff !important;
        }

        /* "Powered By" & sponsor text in light mode */
        body.light-mode .powered-label {
            color: #64748b;
        }

        /* Hero img overlay transition */
        .slider-wrapper {
            transition: transform 0.9s cubic-bezier(0.77, 0, 0.175, 1);
        }

        /* Sponsor sweep bar */
        .sweep-bar {
            transition: width 0.7s ease;
            width: 0;
        }

        .sponsor-card:hover .sweep-bar {
            width: 100%;
        }

        /* Hero badge */
        .hero-badge {
            border-color: rgba(34, 211, 238, 0.5);
            background: rgba(34, 211, 238, 0.07);
            color: #22d3ee;
            transition: background 0.3s;
        }

        body.light-mode .hero-badge {
            background: rgba(6, 182, 212, 0.10);
            color: #0891b2;
        }

        /* "View Archive" button */
        .archive-btn {
            background: rgba(34, 211, 238, 0.05);
            border-color: rgba(34, 211, 238, 0.2);
            transition: background 0.3s;
        }

        body.light-mode .archive-btn {
            background: rgba(6, 182, 212, 0.08);
            border-color: rgba(6, 182, 212, 0.3);
        }

        /* Gallery hover overlay — light mode */
        body.light-mode .gallery-hover-overlay {
            background: linear-gradient(to top, rgba(241, 245, 249, 0.95), rgba(241, 245, 249, 0.2), transparent);
        }

        body.light-mode .gallery-card-title {
            color: #0891b2;
        }

        body.light-mode .gallery-card-sub {
            color: #64748b;
        }
    </style>
@endsection

@section('content')
    @php

        $setting = \App\Models\Setting::first();

        // {{-- layout.app থেকে $setting ও $activeEvents আসছে — নতুন query নেই --}}
        $sponsors = $setting && $setting->sponsor_banner ? $setting->sponsor_banner : [];
    @endphp

    {{-- ═══════════════════════════════════
     HERO SECTION
═══════════════════════════════════ --}}
    <section class="hero-section relative w-full h-[90vh] flex items-center justify-center overflow-hidden">

        {{-- Background Slider --}}
        <div class="absolute inset-0 w-full h-full z-0">
            <div class="slider-wrapper flex h-full" id="main-slider-wrapper">
                @foreach (['main_banner1', 'main_banner2', 'main_banner3'] as $banner)
                    <div class="min-w-full h-full relative shrink-0">
                        <img src="{{ asset('storage/' . ($setting->$banner ?? '')) }}"
                            class="w-full h-full object-cover opacity-50" loading="eager"
                            onerror="this.src='https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=2070';"
                            alt="">
                    </div>
                @endforeach
            </div>

            {{-- Mode-aware gradient overlay --}}
            <div class="hero-gradient absolute inset-0 z-10 pointer-events-none"></div>
        </div>

        {{-- Hero Content --}}
        <div class="container mx-auto px-6 relative z-20 text-center">

            <div
                class="hero-badge inline-block px-4 py-1 border rounded-full text-[10px] font-bold tracking-[0.3em] uppercase mb-6">
                Preliminary Round Starts Soon
            </div>

            <h1 class="heading-font text-5xl md:text-8xl font-black mb-4 tracking-tighter italic uppercase section-heading">
                CODE THE
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">FUTURE</span>
            </h1>

            <p class="hero-tagline max-w-2xl mx-auto text-sm md:text-lg mb-10 border-l-2 px-6 py-2">
                Join the DUET CSE FEST 2026. 10 Hours of pure competitive programming battle.
            </p>

            {{-- Countdown --}}
            <div class="flex flex-wrap justify-center gap-4 mb-10" id="hero-countdown">
                @foreach (['Days' => 'hero-days', 'Hours' => 'hero-hours', 'Mins' => 'hero-mins', 'Secs' => 'hero-secs'] as $label => $id)
                    <div
                        class="countdown-card w-20 h-20 md:w-28 md:h-28 rounded-xl flex flex-col items-center justify-center shadow-2xl">
                        <span id="{{ $id }}" class="countdown-number text-2xl md:text-4xl font-black">00</span>
                        <span
                            class="countdown-label text-[9px] font-bold tracking-widest mt-1 uppercase">{{ $label }}</span>
                    </div>
                @endforeach
            </div>

            {{-- Powered By --}}
            <div class="mt-16 text-center">
                <p class="powered-label text-[10px] text-slate-500 font-bold tracking-[0.4em] uppercase mb-6">Powered By</p>
                <div
                    class="flex flex-wrap justify-center gap-10 grayscale opacity-40 hover:opacity-90 hover:grayscale-0 transition duration-500">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/2/2f/Google_2015_logo.svg" class="h-6"
                        alt="Google">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/4/44/Microsoft_logo.svg" class="h-6"
                        alt="Microsoft">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/a/a9/Amazon_logo.svg" class="h-6"
                        alt="Amazon">
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════
     SPONSORS SECTION
═══════════════════════════════════ --}}
    <section class="sponsors-section py-24 relative overflow-hidden">
        <div class="container mx-auto px-6 relative">

            <div class="mb-16 text-center">
                <h2 class="heading-font text-4xl md:text-6xl font-black uppercase tracking-tighter section-heading">
                    Our Proud <span class="text-cyan-500 drop-shadow-[0_0_15px_rgba(6,182,212,0.3)]">Sponsors</span>
                </h2>
                <div class="flex justify-center items-center gap-3 mt-4">
                    <span class="h-px w-8 bg-cyan-500"></span>
                    <p class="text-slate-500 font-mono text-xs uppercase tracking-[0.3em]">Partnership_Protocols_Active</p>
                    <span class="h-px w-8 bg-cyan-500"></span>
                </div>
            </div>

            <div class="relative">
                <div class="swiper sponsor-swiper !px-2 md:!px-10">
                    <div class="swiper-wrapper items-center">
                        @if ($setting && is_array($sponsors) && count($sponsors))
                            @foreach ($sponsors as $banner)
                                <div class="swiper-slide !h-auto">
                                    <div class="group relative p-2">
                                        <div class="sponsor-card p-8 flex items-center justify-center h-48 md:h-56 rounded-2xl relative overflow-hidden shadow-xl"
                                            style="clip-path: polygon(10% 0, 100% 0, 100% 90%, 90% 100%, 0 100%, 0 10%);">

                                            <img src="{{ asset('storage/' . $banner) }}" alt="Sponsor"
                                                class="max-h-24 md:max-h-32 w-auto max-w-[85%] object-contain grayscale group-hover:grayscale-0 transition-all duration-700 opacity-50 group-hover:opacity-100 group-hover:scale-110">

                                            <div class="absolute bottom-0 left-0 w-full h-[3px] bg-slate-800">
                                                <div class="sweep-bar h-full bg-cyan-400"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="swiper-slide">
                                <p class="text-center text-slate-500 text-sm py-16">No sponsors added yet.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <button
                    class="sponsor-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-xl bg-slate-950/80 border border-slate-800 text-cyan-500 items-center justify-center hover:bg-cyan-500/10 transition-all hidden md:flex md:-left-4">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button
                    class="sponsor-next absolute right-0 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-xl bg-slate-950/80 border border-slate-800 text-cyan-500 items-center justify-center hover:bg-cyan-500/10 transition-all hidden md:flex md:-right-4">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════
     GALLERY SECTION
═══════════════════════════════════ --}}
    <section class="gallery-section py-24">
        <div class="container mx-auto px-6">

            <div class="text-center mb-16">
                <h2 class="heading-font text-4xl md:text-6xl font-black uppercase section-heading">
                    Event <span class="text-cyan-500">Gallery</span>
                </h2>
                <p class="text-slate-500 font-mono text-xs mt-4 tracking-[0.2em]">&gt;&gt; VISUAL_ARCHIVE_ACCESS_GRANTED</p>

                <div class="mt-8 flex justify-center">
                    <a href="{{ url('cse-gallery') }}"
                        class="archive-btn group flex items-center gap-3 px-8 py-3 border rounded-full transition-all">
                        <span class="text-cyan-500 font-black text-[10px] uppercase tracking-[0.2em]">View Full
                            Archive</span>
                        <i class="fa-solid fa-arrow-right text-cyan-500 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            @php
                $titles = [
                    'IUPC Arena',
                    'Cyber Hackathon',
                    'Project Demo',
                    'ICT Olympiad',
                    'Deep Learning',
                    'Tech Seminar',
                ];
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @for ($i = 1; $i <= 6; $i++)
                    <div
                        class="gallery-card group relative overflow-hidden h-72 rounded-2xl transition-all duration-500 shadow-2xl">

                        <img src="{{ asset('gallery/' . $i . '.jpg') }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000 grayscale-[40%] group-hover:grayscale-0"
                            alt="DUET CSE Carnival">

                        <div
                            class="gallery-hover-overlay absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-6 text-left">
                            <h4
                                class="gallery-card-title heading-font text-cyan-400 text-lg font-bold uppercase tracking-tighter">
                                {{ $titles[($i - 1) % count($titles)] }}
                            </h4>
                            <p class="gallery-card-sub text-[9px] text-slate-400 font-mono mt-1">
                                SNAPSHOT_0{{ $i }}</p>
                            <div class="mt-4 h-[1px] w-0 group-hover:w-full bg-cyan-400 transition-all duration-1000"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    {{-- Swiper JS --}}
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ── 1. Countdown timer ────────────────────────────────────────────────
            const targetDate = new Date("2026-06-26T23:59:59").getTime();

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            function updateCountdown() {
                const diff = targetDate - Date.now();
                if (diff <= 0) {
                    ['hero-days', 'hero-hours', 'hero-mins', 'hero-secs'].forEach(id => {
                        document.getElementById(id).textContent = '00';
                    });
                    return;
                }
                document.getElementById('hero-days').textContent = pad(Math.floor(diff / 86400000));
                document.getElementById('hero-hours').textContent = pad(Math.floor((diff % 86400000) / 3600000));
                document.getElementById('hero-mins').textContent = pad(Math.floor((diff % 3600000) / 60000));
                document.getElementById('hero-secs').textContent = pad(Math.floor((diff % 60000) / 1000));
            }

            updateCountdown(); // সাথে সাথে চালাও, 1s ফাঁকা না
            const countdownTimer = setInterval(updateCountdown, 1000);

            // ── 2. Hero image slider ──────────────────────────────────────────────
            let currentIdx = 0;
            const wrapper = document.getElementById('main-slider-wrapper');
            const totalSlides = 3;

            const heroSlider = setInterval(() => {
                currentIdx = (currentIdx + 1) % totalSlides;
                wrapper.style.transform = `translateX(-${currentIdx * 100}%)`;
            }, 5000);

            // ── 3. Swiper — Sponsor ───────────────────────────────────────────────
            new Swiper('.sponsor-swiper', {
                slidesPerView: 2,
                spaceBetween: 15,
                loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.sponsor-next',
                    prevEl: '.sponsor-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 3,
                        spaceBetween: 20
                    },
                    1024: {
                        slidesPerView: 5,
                        spaceBetween: 30
                    },
                },
            });

            // ── 4. Light/dark mode পরিবর্তনে hero overlay এবং sponsor card আপডেট ─
            //    (theme-toggle button layout.app এ আছে, এখানে MutationObserver দিয়ে
            //     body class change ধরা হচ্ছে — কোনো extra event emit দরকার নেই)
            const body = document.body;
            const observer = new MutationObserver(() => {
                // CSS variable গুলো body তে সেট, তাই শুধু repaint হলেই হয়।
                // কোনো JS manipulation দরকার নেই — CSS handles everything.
            });
            observer.observe(body, {
                attributes: true,
                attributeFilter: ['class']
            });
        });
    </script>

    <style>
        .swiper-wrapper {
            display: flex !important;
        }

        .sponsor-swiper {
            width: 100%;
            overflow: hidden;
        }
    </style>
@endsection
