@extends('layouts.app')

@section('content')
    @php
        $setting = \App\Models\Setting::first();
        // ডাটাবেজে যদি ইমেজগুলো JSON স্ট্রিং হিসেবে থাকে তবে সেটিকে ডিকোড করা
        $sponsors = $setting && $setting->sponsor_logos ? json_decode($setting->sponsor_logos, true) : [];
    @endphp

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        .neon-text {
            text-shadow: 0 0 20px rgba(34, 211, 238, 0.6), 0 0 40px rgba(34, 211, 238, 0.2);
        }

        .slider-wrapper {
            will-change: transform;
        }

        .cyber-btn {
            background: linear-gradient(90deg, #22d3ee, #3b82f6);
            color: #000;
            padding: 10px 25px;
            font-weight: bold;
            clip-path: polygon(10% 0, 100% 0, 90% 100%, 0 100%);
            transition: 0.3s;
        }

        .cyber-btn:hover {
            transform: scale(1.05);
            box-shadow: 0 0 15px #22d3ee;
        }

        /* Sponsor Card styling */
        .sponsor-card {
            background: rgba(15, 23, 42, 0.8);
            border: 1px solid rgba(34, 211, 238, 0.2);
            transition: all 0.3s ease;
        }

        .sponsor-card:hover {
            border-color: #22d3ee;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.2);
        }
    </style>

    <!-- MAIN HERO SECTION WITH SLIDER & TIMER -->
    <section class="relative w-full h-[90vh] flex items-center justify-center overflow-hidden bg-[#020617]">
        <!-- Background Slider -->
        <div class="absolute inset-0 w-full h-full z-0">
            <div class="slider-wrapper flex h-full transition-transform duration-1000 ease-in-out" id="main-slider-wrapper">
                @foreach (['main_banner1', 'main_banner2', 'main_banner3'] as $index => $banner)
                    <div class="min-w-full h-full relative">
                        <img src="{{ asset('storage/' . ($setting->$banner ?? '')) }}"
                            class="w-full h-full object-cover opacity-40"
                            onerror="this.src='https://images.unsplash.com/photo-1550745165-9bc0b252726f?q=80&w=2070';">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-transparent to-[#020617]/50"></div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Content Overlay -->
        <div class="container mx-auto px-6 relative z-10 text-center">
            <div
                class="inline-block px-4 py-1 border border-cyan-500/50 rounded-full bg-cyan-500/5 text-cyan-400 text-[10px] font-bold tracking-[0.3em] uppercase mb-6">
                Preliminary Round Starts Soon
            </div>

            <h1
                class="heading-font text-5xl md:text-8xl font-black mb-4 tracking-tighter neon-text italic uppercase text-white">
                CODE THE <span
                    class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 text-shadow-none">FUTURE</span>
            </h1>

            <p class="text-slate-300 max-w-2xl mx-auto text-sm md:text-lg mb-10 border-l-2 border-cyan-500 px-6 py-2">
                Join the DUET CSE FEST 2026. 10 Hours of pure competitive programming battle.
            </p>

            <!-- Countdown Timer -->
            <div class="flex flex-wrap justify-center gap-4 mb-10" id="hero-countdown">
                @foreach (['Days' => 'hero-days', 'Hours' => 'hero-hours', 'Mins' => 'hero-mins', 'Secs' => 'hero-secs'] as $label => $id)
                    <div
                        class="w-20 h-20 md:w-28 md:h-28 bg-slate-900/90 border border-slate-800 rounded-xl flex flex-col items-center justify-center shadow-2xl">
                        <span id="{{ $id }}" class="text-2xl md:text-4xl font-black text-cyan-400">00</span>
                        <span
                            class="text-[9px] text-slate-500 font-bold tracking-widest mt-1 uppercase">{{ $label }}</span>
                    </div>
                @endforeach
            </div>
            {{-- 
            <div class="flex justify-center gap-4">
                <button class="cyber-btn">REGISTER NOW</button>
                <div class="flex gap-2" id="slider-dots">
                    <span class="w-3 h-3 rounded-full bg-cyan-400 cursor-pointer" onclick="goToSlide(0)"></span>
                    <span class="w-3 h-3 rounded-full bg-slate-600 cursor-pointer" onclick="goToSlide(1)"></span>
                    <span class="w-3 h-3 rounded-full bg-slate-600 cursor-pointer" onclick="goToSlide(2)"></span>
                </div>
            </div> --}}
        </div>
    </section>

    <!-- SPONSORS SECTION -->
    <section class="py-20 bg-[#020617] border-t border-slate-900">
        <div class="container mx-auto px-6">
            <div class="mb-12 border-l-4 border-cyan-500 pl-6">
                <h2 class="heading-font text-3xl md:text-5xl font-black uppercase text-white">
                    Our Proud <span class="text-cyan-500">Sponsors</span>
                </h2>
                <p class="text-slate-500 font-mono text-xs mt-2">>> PARTNERSHIP_PROTOCOLS_ACTIVE</p>
            </div>

            <div class="swiper sponsor-swiper">
                <div class="swiper-wrapper">
                    @if ($setting && is_array($setting->sponsor_banner))
                        @foreach ($setting->sponsor_banner as $banner)
                            <div class="swiper-slide">
                                <div class="sponsor-card p-6 flex items-center justify-center h-32 rounded-lg overflow-hidden"
                                    style="clip-path: polygon(10% 0, 100% 0, 100% 90%, 90% 100%, 0 100%, 0 10% );">
                                    <img src="{{ asset('storage/' . $banner) }}" alt="Sponsor"
                                        class="max-h-16 grayscale hover:grayscale-0 transition-all duration-500 opacity-60 hover:opacity-100 scale-100 hover:scale-110">
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <!-- Navigation -->
                <div class="swiper-button-next !text-cyan-500"></div>
                <div class="swiper-button-prev !text-cyan-500"></div>
            </div>
        </div>
    </section>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // --- 1. Timer Logic ---
        (function() {
            const targetDate = new Date("2026-06-26 23:59:59").getTime();
            const updateTimer = () => {
                const now = new Date().getTime();
                const diff = targetDate - now;

                if (diff <= 0) {
                    document.getElementById("hero-countdown").innerHTML =
                        "<h2 class='text-cyan-400 font-bold text-2xl'>REGISTRATION CLOSED</h2>";
                    return;
                }

                document.getElementById("hero-days").innerText = Math.floor(diff / (1000 * 60 * 60 * 24)).toString()
                    .padStart(2, '0');
                document.getElementById("hero-hours").innerText = Math.floor((diff % (1000 * 60 * 60 * 24)) / (
                    1000 * 60 * 60)).toString().padStart(2, '0');
                document.getElementById("hero-mins").innerText = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))
                    .toString().padStart(2, '0');
                document.getElementById("hero-secs").innerText = Math.floor((diff % (1000 * 60)) / 1000).toString()
                    .padStart(2, '0');
            };
            setInterval(updateTimer, 1000);
            updateTimer();
        })();

        // --- 2. Hero Slider Logic ---
        let currentIdx = 0;
        const wrapper = document.getElementById('main-slider-wrapper');
        const dots = document.querySelectorAll('#slider-dots span');

        function goToSlide(idx) {
            currentIdx = idx;
            wrapper.style.transform = `translateX(-${currentIdx * 100}%)`;
            dots.forEach((dot, i) => {
                dot.classList.toggle('bg-cyan-400', i === idx);
                dot.classList.toggle('bg-slate-600', i !== idx);
            });
        }

        setInterval(() => {
            currentIdx = (currentIdx + 1) % 3;
            goToSlide(currentIdx);
        }, 5000);

        // --- 3. Swiper for Sponsors ---
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.sponsor-swiper', {
                slidesPerView: 2,
                spaceBetween: 30,
                loop: true,
                autoplay: {
                    delay: 2500
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev'
                },
                breakpoints: {
                    640: {
                        slidesPerView: 3
                    },
                    1024: {
                        slidesPerView: 5
                    },
                }
            });
        });
    </script>
@endsection
