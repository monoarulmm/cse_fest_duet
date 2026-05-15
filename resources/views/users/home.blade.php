@extends('layouts.app')

@section('content')
    @php
        $setting = \App\Models\Setting::first();
        // স্পন্সর লোগো ডিকোড
        $sponsors = $setting && $setting->sponsor_banner ? $setting->sponsor_banner : [];
    @endphp

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />


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
    <section class="py-24 bg-[#020617] relative overflow-hidden border-t border-slate-900">
        <div class="container mx-auto px-6 relative">
            <div class="mb-16 text-center">
                <h2 class="heading-font text-4xl md:text-6xl font-black uppercase tracking-tighter text-white">
                    Our Proud <span class="text-cyan-500 drop-shadow-[0_0_15px_rgba(6,182,212,0.4)]">Sponsors</span>
                </h2>
                <div class="flex justify-center items-center gap-3 mt-4">
                    <span class="h-px w-8 bg-cyan-500"></span>
                    <p class="text-slate-500 font-mono text-xs uppercase tracking-[0.3em]">Partnership_Protocols_Active</p>
                    <span class="h-px w-8 bg-cyan-500"></span>
                </div>
            </div>

            <div class="relative group">
                <div class="swiper sponsor-swiper !px-2 md:!px-10">
                    <div class="swiper-wrapper flex items-center">
                        @if ($setting && is_array($sponsors))
                            @foreach ($sponsors as $banner)
                                <div class="swiper-slide !h-auto">
                                    <div class="group relative p-2">
                                        <div class="sponsor-card bg-slate-900/40 border border-slate-800 p-8 flex items-center justify-center h-48 md:h-56 rounded-2xl group-hover:border-cyan-500/50 transition-all duration-500 relative overflow-hidden shadow-2xl"
                                            style="clip-path: polygon(10% 0, 100% 0, 100% 90%, 90% 100%, 0 100%, 0 10%);">

                                            <img src="{{ asset('storage/' . $banner) }}" alt="Sponsor"
                                                class="max-h-24 md:max-h-32 w-auto max-w-[85%] object-contain grayscale group-hover:grayscale-0 transition-all duration-700 opacity-50 group-hover:opacity-100 group-hover:scale-110">

                                            <div class="absolute bottom-0 left-0 w-full h-[3px] bg-slate-800">
                                                <div
                                                    class="h-full bg-cyan-400 w-0 group-hover:w-full transition-all duration-700">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <button
                    class="sponsor-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-xl bg-slate-950/80 border border-slate-800 text-cyan-500 flex items-center justify-center hover:bg-cyan-500/10 transition-all hidden md:flex md:-left-4">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button
                    class="sponsor-next absolute right-0 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-xl bg-slate-950/80 border border-slate-800 text-cyan-500 flex items-center justify-center hover:bg-cyan-500/10 transition-all hidden md:flex md:-right-4">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <section class="py-24 bg-[#020617] border-t border-slate-900">
        <div class="container mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="heading-font text-4xl md:text-6xl font-black uppercase text-white">
                    Event <span class="text-cyan-500">Gallery</span>
                </h2>
                <p class="text-slate-500 font-mono text-xs mt-4 tracking-[0.2em]">>> VISUAL_ARCHIVE_ACCESS_GRANTED</p>

                <div class="mt-8 flex justify-center">
                    <a href="{{ url('cse-gallery') }}"
                        class="group flex items-center gap-3 px-8 py-3 bg-cyan-500/5 border border-cyan-500/20 rounded-full hover:bg-cyan-500/10 transition-all">
                        <span class="text-cyan-500 font-black text-[10px] uppercase tracking-[0.2em]">View Full
                            Archive</span>
                        <i class="fa-solid fa-arrow-right text-cyan-500 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
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

                @for ($i = 1; $i <= 6; $i++)
                    <div
                        class="group gallery-card relative bg-slate-900 border border-slate-800 hover:border-cyan-500/50 overflow-hidden h-72 rounded-2xl transition-all duration-500 shadow-2xl">
                        <img src="{{ asset('gallery/' . $i . '.jpg') }}"
                            class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-1000 grayscale-[50%] group-hover:grayscale-0"
                            alt="DUET CSE Carnival">

                        <div
                            class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/10 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-6 text-left">
                            <h4 class="heading-font text-cyan-400 text-lg font-bold uppercase tracking-tighter">
                                {{ $titles[($i - 1) % count($titles)] }}
                            </h4>
                            <p class="text-[9px] text-slate-400 font-mono mt-1">SNAPSHOT_0{{ $i }}</p>
                            <div class="mt-4 h-[1px] w-0 group-hover:w-full bg-cyan-400 transition-all duration-1000"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Timer Logic
            const targetDate = new Date("2026-06-26 23:59:59").getTime();
            setInterval(() => {
                const now = new Date().getTime();
                const diff = targetDate - now;
                if (diff > 0) {
                    document.getElementById("hero-days").innerText = Math.floor(diff / (1000 * 60 * 60 *
                        24)).toString().padStart(2, '0');
                    document.getElementById("hero-hours").innerText = Math.floor((diff % (1000 * 60 * 60 *
                        24)) / (1000 * 60 * 60)).toString().padStart(2, '0');
                    document.getElementById("hero-mins").innerText = Math.floor((diff % (1000 * 60 * 60)) /
                        (1000 * 60)).toString().padStart(2, '0');
                    document.getElementById("hero-secs").innerText = Math.floor((diff % (1000 * 60)) / 1000)
                        .toString().padStart(2, '0');
                }
            }, 1000);

            // 2. Main Hero Slider
            let currentIdx = 0;
            const wrapper = document.getElementById('main-slider-wrapper');
            setInterval(() => {
                currentIdx = (currentIdx + 1) % 3;
                wrapper.style.transform = `translateX(-${currentIdx * 100}%)`;
            }, 5000);

            // 3. Swiper Sponsor Logic (Ensuring side-by-side)
            new Swiper('.sponsor-swiper', {
                slidesPerView: 2,
                spaceBetween: 15,
                loop: true,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false
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
                    }
                }
            });
        });
    </script>

    <style>
        .swiper-wrapper {
            display: flex !important;
            flex-direction: row !important;
        }

        .sponsor-swiper {
            width: 100%;
            overflow: hidden;
        }
    </style>
@endsection
