@extends('layouts.app')

@section('content')
    @php
        $setting = \App\Models\Setting::first();
        // ডাটাবেজে যদি ইমেজগুলো JSON স্ট্রিং হিসেবে থাকে তবে সেটিকে ডিকোড করা
        $sponsors = $setting && $setting->sponsor_logos ? json_decode($setting->sponsor_logos, true) : [];
    @endphp



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
            <!-- Sponsor Section -->
            <div class="mt-32 text-center">
                <p class="text-[10px] text-slate-500 font-bold tracking-[0.4em] uppercase mb-8">Powered By</p>
                <div
                    class="flex flex-wrap justify-center gap-12 grayscale opacity-50 hover:opacity-100 transition duration-500">
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

    {{-- sponser section --}}



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const swiper = new Swiper('.sponsor-swiper', {
                slidesPerView: 2, // মোবাইলে ২টা দেখাবে
                spaceBetween: 10,
                loop: true,
                speed: 1000,
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.sponsor-next',
                    prevEl: '.sponsor-prev',
                },
                breakpoints: {
                    // ল্যাপটপ বা বড় স্ক্রিনে ৫টি দেখাবে
                    640: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    1024: {
                        slidesPerView: 4,
                        spaceBetween: 30,
                    },
                    1280: {
                        slidesPerView: 5,
                        spaceBetween: 30,
                    }
                }
            });
        });
    </script>

    <style>
        /* স্লাইডার যেন ভেঙে না যায় তার জন্য ফিক্স */
        .sponsor-swiper {
            width: 100%;
            overflow: hidden;
        }

        .swiper-wrapper {
            display: flex !important;
            flex-direction: row !important;
        }
    </style>
    <section class="py-24 bg-[#020617] relative overflow-hidden">
        <div class="container mx-auto px-6 relative">
            <div class="mb-16 border-l-4 border-cyan-500 pl-6">
                <h2 class="heading-font text-4xl md:text-6xl font-black uppercase tracking-tighter text-white">
                    Our Proud <span class="text-cyan-500 drop-shadow-[0_0_15px_rgba(6,182,212,0.4)]">Sponsors</span>
                </h2>
                <p class="text-slate-500 font-mono text-xs mt-2 tracking-[0.3em] uppercase">>> PARTNERSHIP_PROTOCOLS_ACTIVE
                </p>
            </div>

            <div class="relative group">
                <div class="swiper sponsor-swiper !px-2 md:!px-10">
                    <div class="swiper-wrapper flex items-center"> {{-- flex items-center added for safety --}}
                        @if ($setting && is_array($setting->sponsor_banner))
                            @foreach ($setting->sponsor_banner as $banner)
                                <div class="swiper-slide !h-auto">
                                    <div class="group relative p-2">
                                        {{-- Increased Height for Large Logos --}}
                                        <div class="sponsor-card bg-slate-900/60 border border-slate-800 p-8 flex items-center justify-center h-48 md:h-52 rounded-2xl group-hover:border-cyan-500/50 transition-all duration-500 relative overflow-hidden shadow-2xl"
                                            style="clip-path: polygon(12% 0, 100% 0, 100% 88%, 88% 100%, 0 100%, 0 12%);">

                                            {{-- Image scale increased --}}
                                            <img src="{{ asset('storage/' . $banner) }}" alt="Sponsor"
                                                class="max-h-24 md:max-h-32 w-auto max-w-[90%] object-contain grayscale group-hover:grayscale-0 transition-all duration-700 opacity-60 group-hover:opacity-100 group-hover:scale-110 drop-shadow-2xl">

                                            {{-- Cyber Bottom Progress Bar --}}
                                            <div class="absolute bottom-0 left-0 w-full h-[3px] bg-slate-800/50">
                                                <div
                                                    class="h-full bg-cyan-400 w-0 group-hover:w-full transition-all duration-700 ease-out shadow-[0_0_10px_#22d3ee]">
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
                    class="sponsor-prev absolute left-0 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-xl bg-slate-950/80 border border-slate-800 text-cyan-500 flex items-center justify-center hover:bg-cyan-500/10 hover:border-cyan-500/50 transition-all duration-300 md:-left-6 hidden md:flex shadow-xl">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button
                    class="sponsor-next absolute right-0 top-1/2 -translate-y-1/2 z-20 w-12 h-12 rounded-xl bg-slate-950/80 border border-slate-800 text-cyan-500 flex items-center justify-center hover:bg-cyan-500/10 hover:border-cyan-500/50 transition-all duration-300 md:-right-6 hidden md:flex shadow-xl">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>


    <section class="py-20 bg-[#020617] border-t border-slate-900">
        <div class="container mx-auto px-6">
            {{-- Section Header: Sponsor Style --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 border-l-4 border-cyan-500 pl-6 gap-6">
                <div>
                    <h2 class="heading-font text-3xl md:text-5xl font-black uppercase text-white">
                        Event <span class="text-cyan-500">Gallery</span>
                    </h2>
                    <p class="text-slate-500 font-mono text-xs mt-2">>> VISUAL_ARCHIVE_ACCESS_GRANTED</p>
                </div>

                {{-- View All Button --}}
                <a href="{{ url('cse-gallery') }}"
                    class="group flex items-center gap-3 px-6 py-3 bg-cyan-500/5 border border-cyan-500/20 rounded-full hover:bg-cyan-500/10 transition-all">
                    <span class="text-cyan-500 font-black text-[10px] uppercase tracking-[0.2em]">View Full Archive</span>
                    <i class="fa-solid fa-arrow-right text-cyan-500 group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>

            {{-- Gallery Grid: Limited to 6 Items --}}
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
                            class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/20 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-500 flex flex-col justify-end p-6">
                            <div
                                class="flex justify-between items-end transform translate-y-4 group-hover:translate-y-0 transition-transform duration-500">
                                <div>
                                    <h4 class="heading-font text-cyan-400 text-lg font-bold uppercase tracking-tighter">
                                        {{ $titles[($i - 1) % count($titles)] }}
                                    </h4>
                                    <p class="text-[9px] text-slate-400 font-mono uppercase tracking-widest mt-1">
                                        Snapshot_0{{ $i }}
                                    </p>
                                </div>
                                <div class="bg-cyan-500 p-2.5 rounded-lg shadow-[0_0_15px_rgba(6,182,212,0.4)]">
                                    <i class="fa-solid fa-expand text-slate-950 text-sm"></i>
                                </div>
                            </div>

                            <div class="mt-4 h-[1px] w-full bg-white/10 overflow-hidden">
                                <div class="h-full bg-cyan-400 w-0 group-hover:w-full transition-all duration-1000"></div>
                            </div>
                        </div>
                    </div>
                @endfor
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

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />




@endsection
