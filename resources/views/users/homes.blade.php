@extends('layouts.app')

@section('content')
    <!-- HERO SECTION START -->
    <!-- HERO SECTION START -->

    @php
        $setting = \App\Models\Setting::first();
        $settings = \App\Models\Setting::first();

        // ডাটাবেজে যদি ইমেজগুলো JSON স্ট্রিং হিসেবে থাকে তবে সেটিকে ডিকোড করা
        $sponsors = $settings && $settings->sponsor_logos ? json_decode($settings->sponsor_logos, true) : [];
    @endphp


    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />



    <style>
        /* টাইটেল গ্লো ইফেক্ট */
        .neon-text {
            text-shadow: 0 0 20px rgba(34, 211, 238, 0.6), 0 0 40px rgba(34, 211, 238, 0.2);
        }

        /* মোবাইল ভিউতে টেক্সট একটু বড় দেখানোর জন্য কাস্টম অ্যাডজাস্টমেন্ট */
        @media (max-width: 768px) {
            .heading-font {
                letter-spacing: -1px;
            }
        }

        .slider-wrapper {
            will-change: transform;
        }
    </style>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const wrapper = document.querySelector('.slider-wrapper');
        const dots = document.querySelectorAll('.dot');
        let autoSlideInterval;

        function updateSlider() {
            wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;
            // আপডেট ডটস
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.style.backgroundColor = '#22d3ee';
                    dot.style.width = '24px'; // একটিভ ডট একটু লম্বা দেখাবে
                } else {
                    dot.style.backgroundColor = 'rgba(34, 211, 238, 0.2)';
                    dot.style.width = '8px';
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % slides.length;
            updateSlider();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + slides.length) % slides.length;
            updateSlider();
        }

        // অটো স্লাইড শুরু করা
        function startAutoSlide() {
            autoSlideInterval = setInterval(nextSlide, 5000);
        }

        // ইউজার ক্লিক করলে অটো স্লাইড রিসেট করা
        function resetTimer() {
            clearInterval(autoSlideInterval);
            startAutoSlide();
        }

        // স্লাইডারের ওপর টাচ করলে যেন স্লাইড করা যায় (Mobile Swipe Support)
        let touchStartX = 0;
        let touchEndX = 0;

        wrapper.addEventListener('touchstart', e => {
            touchStartX = e.changedTouches[0].screenX;
        }, {
            passive: true
        });

        wrapper.addEventListener('touchend', e => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        }, {
            passive: true
        });

        function handleSwipe() {
            if (touchStartX - touchEndX > 50) nextSlide();
            if (touchEndX - touchStartX > 50) prevSlide();
            resetTimer();
        }

        // শুরুতে লোড করা
        updateSlider();
        startAutoSlide();
    </script>
    <!-- HERO SECTION END -->
    <section class="relative pt-32 pb-20 overflow-hidden">
        <!-- Matrix Decorative Background -->
        <div
            class="absolute inset-0 opacity-10 pointer-events-none select-none overflow-hidden text-[10px] leading-tight text-cyan-500 heading-font break-all">
            @for ($i = 0; $i < 20; $i++)
                010111010010101101010101010110101011010101110100101011010101010101101010110101
            @endfor
        </div>

        <div class="container mx-auto px-6 relative z-10 text-center">
            <!-- Badge -->
            <div
                class="inline-block px-4 py-1 border border-cyan-500/50 rounded-full bg-cyan-500/5 text-cyan-400 text-[10px] font-bold tracking-[0.3em] uppercase mb-8">
                Preliminary Round Starts Soon
            </div>

            <!-- Hero Title -->
            <h1 class="heading-font text-5xl md:text-8xl font-black mb-6 tracking-tighter neon-glow italic uppercase">
                {{ $event->name ?? 'Inter-University' }} <br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">
                    Programming Contest
                </span>
            </h1>

            <p class="text-slate-400 max-w-2xl mx-auto text-sm md:text-base mb-12 border-l-2 border-cyan-500 px-6 py-2">
                10 Hours. Pure Code. No Limits. Join the most prestigious competitive programming event of the year.
            </p>

            <!-- Dynamic Timer Grid -->
            <div class="flex flex-wrap justify-center gap-4 mb-16" id="hero-countdown">
                <div
                    class="w-24 h-24 md:w-32 md:h-32 bg-slate-900/80 border border-slate-800 rounded-xl flex flex-col items-center justify-center shadow-xl">
                    <span id="hero-days" class="text-3xl md:text-5xl font-black text-cyan-400 heading-font">00</span>
                    <span class="text-[9px] text-slate-500 font-bold tracking-widest mt-2 uppercase">Days</span>
                </div>
                <div
                    class="w-24 h-24 md:w-32 md:h-32 bg-slate-900/80 border border-slate-800 rounded-xl flex flex-col items-center justify-center shadow-xl">
                    <span id="hero-hours" class="text-3xl md:text-5xl font-black text-cyan-400 heading-font">00</span>
                    <span class="text-[9px] text-slate-500 font-bold tracking-widest mt-2 uppercase">Hours</span>
                </div>
                <div
                    class="w-24 h-24 md:w-32 md:h-32 bg-slate-900/80 border border-slate-800 rounded-xl flex flex-col items-center justify-center shadow-xl">
                    <span id="hero-mins" class="text-3xl md:text-5xl font-black text-cyan-400 heading-font">00</span>
                    <span class="text-[9px] text-slate-500 font-bold tracking-widest mt-2 uppercase">Mins</span>
                </div>
                <div
                    class="w-24 h-24 md:w-32 md:h-32 bg-slate-900/80 border border-slate-800 rounded-xl flex flex-col items-center justify-center shadow-xl border-cyan-500/30">
                    <span id="hero-secs"
                        class="text-3xl md:text-5xl font-black text-red-500 heading-font animate-pulse">00</span>
                    <span class="text-[9px] text-slate-500 font-bold tracking-widest mt-2 uppercase">Secs</span>
                </div>
            </div>


        </div>

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
    </section>

    <script>
        (function() {
            // ডাটাবেজ থেকে আসা তারিখ (উদা: 2026-06-26 23:59:59)
            // আপনার পছন্দমতো ডেট এবং টাইম এখানে দিন (YYYY-MM-DD HH:MM:SS ফরম্যাটে)
            const targetDate = new Date("2026-06-26 23:59:59").getTime();
            const updateHeroTimer = () => {
                const now = new Date().getTime();
                const diff = targetDate - now;

                if (diff <= 0) {
                    document.getElementById("hero-countdown").innerHTML =
                        "<div class='text-cyan-400 heading-font text-2xl font-black uppercase tracking-widest'>Registration Closed</div>";
                    return;
                }

                // সময় ক্যালকুলেশন
                const days = Math.floor(diff / (1000 * 60 * 60 * 24));
                const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((diff % (1000 * 60)) / 1000);

                // DOM আপডেট (০৯, ০৫ এভাবে দেখানোর জন্য padStart ব্যবহার করা হয়েছে)
                document.getElementById("hero-days").innerText = days.toString().padStart(2, '0');
                document.getElementById("hero-hours").innerText = hours.toString().padStart(2, '0');
                document.getElementById("hero-mins").innerText = minutes.toString().padStart(2, '0');
                document.getElementById("hero-secs").innerText = seconds.toString().padStart(2, '0');
            };

            // প্রতি ১ সেকেন্ডে ফাংশনটি রান করবে
            setInterval(updateHeroTimer, 1000);
            updateHeroTimer(); // পেজ লোড হওয়ার সাথে সাথেই একবার রান করবে
        })();
    </script>
    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- HERO SECTION END -->
    <!-- SPONSORS SECTION -->
    <section class="py-16 container mx-auto px-6 relative">
        <!-- Section Header -->
        <div class="mb-12 border-l-4 border-cyan-500 pl-6">
            <h2 class="heading-font text-3xl md:text-5xl font-black uppercase tracking-tighter">
                Our Proud <span class="text-cyan-500">Sponsors</span>
            </h2>
            <p class="text-slate-500 font-mono text-xs mt-2 tracking-widest">>> PARTNERSHIP_PROTOCOLS_ACTIVE</p>
        </div>

        <!-- Swiper Container -->
        <div class="swiper sponsor-swiper relative px-12">
            <div class="swiper-wrapper">
                @if ($setting && is_array($setting->sponsor_banner))
                    @foreach ($setting->sponsor_banner as $banner)
                        <div class="swiper-slide">
                            <div class="group relative p-2">
                                <div class="sponsor-card bg-slate-900/80 border border-slate-800 p-6 flex items-center justify-center h-32 group-hover:border-cyan-500/50 transition-all duration-300 relative overflow-hidden"
                                    style="clip-path: polygon(15% 0, 100% 0, 100% 85%, 85% 100%, 0 100%, 0 15%);">

                                    <img src="{{ asset('storage/' . $banner) }}" alt="Sponsor"
                                        class="max-h-16 max-w-full object-contain grayscale group-hover:grayscale-0 transition-all duration-500 opacity-60 group-hover:opacity-100 group-hover:scale-110">

                                    <div class="absolute bottom-0 left-0 w-full h-[2px] bg-cyan-500/30">
                                        <div class="h-full bg-cyan-400 w-0 group-hover:w-full transition-all duration-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>

            <!-- Navigation Buttons -->
            <div
                class="swiper-button-prev !text-cyan-500 !w-10 !h-10 after:!text-xl bg-slate-900 border border-cyan-500/30 rounded-full hover:bg-cyan-500 hover:!text-slate-950 transition-all">
            </div>
            <div
                class="swiper-button-next !text-cyan-500 !w-10 !h-10 after:!text-xl bg-slate-900 border border-cyan-500/30 rounded-full hover:bg-cyan-500 hover:!text-slate-950 transition-all">
            </div>
        </div>
    </section>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.sponsor-swiper', {
                slidesPerView: 2, // মোবাইলে ২টি দেখাবে
                spaceBetween: 20,
                loop: true,
                autoplay: {
                    delay: 3000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                breakpoints: {
                    640: {
                        slidesPerView: 3,
                    },
                    768: {
                        slidesPerView: 4,
                    },
                    1024: {
                        slidesPerView: 5,
                    },
                },
            });
        });
    </script>

    <style>
        /* স্লাইডার বাটনগুলোকে পজিশন করার জন্য */
        .sponsor-swiper {
            padding-bottom: 20px;
        }

        .swiper-button-prev,
        .swiper-button-next {
            top: 50% !important;
            transform: translateY(-50%);
        }

        .swiper-button-prev {
            left: -10px !important;
        }

        .swiper-button-next {
            right: -10px !important;
        }

        /* স্লাইডগুলো যাতে মাঝখানে থাকে */
        .swiper-slide {
            display: flex;
            justify-content: center;
        }
    </style>
    <script>
        // simple Background Image Switcher
        let slides = document.querySelectorAll('.hero-slide');
        let index = 0;

        function nextSlide() {
            slides[index].classList.remove('opacity-100');
            slides[index].classList.add('opacity-0');
            index = (index + 1) % slides.length;
            slides[index].classList.remove('opacity-0');
            slides[index].classList.add('opacity-100');
        }

        setInterval(nextSlide, 5000); // Change image every 5 seconds
    </script>

    <style>
        /* Sponsor Card Styling */
        .sponsor-card {
            background: rgba(34, 211, 238, 0.03);
            border: 1px solid rgba(34, 211, 238, 0.1);
            backdrop-filter: blur(5px);
            transition: all 0.3s ease;
        }

        .light-mode .sponsor-card {
            background: rgba(255, 255, 255, 0.8);
            border: 1px solid rgba(13, 148, 136, 0.2);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .sponsor-card:hover {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.2);
            transform: translateY(-5px);
        }

        /* Stats Section Styling */
        .stats-box {
            background: linear-gradient(145deg, rgba(15, 23, 42, 0.9), rgba(2, 6, 23, 0.9));
            border: 1px solid rgba(34, 211, 238, 0.2);
            clip-path: polygon(0 0, 100% 0, 100% 80%, 90% 100%, 0 100%);
        }

        .light-mode .stats-box {
            background: linear-gradient(145deg, #f8fafc, #e2e8f0);
            border: 1px solid rgba(13, 148, 136, 0.3);
        }

        .stat-circle {
            border: 4px solid var(--neon-cyan);
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.3);
        }
    </style>



    <section class="relative w-full h-[85vh] md:h-[90vh] flex items-center justify-center overflow-hidden">
        <!-- Swiper/Slider Container -->
        <div class="absolute inset-0 w-full h-full z-0">
            <div class="relative w-full h-full overflow-hidden" id="hero-slider">

                <!-- Slides Wrapper -->
                <div class="slider-wrapper flex transition-transform duration-700 ease-in-out h-full" id="slider-wrapper">

                    <!-- Slide 1 -->
                    <div class="min-w-full h-full relative slide">
                        <img src="{{ asset('storage/' . ($setting->main_banner1 ?? '')) }}"
                            class="w-full h-full object-cover opacity-50 md:opacity-60" alt="CSE FEST 1"
                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1550745165-9bc0b252726f?auto=format&fit=crop&q=80&w=2070';">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/40 to-transparent"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6">
                            <h1
                                class="heading-font text-4xl sm:text-5xl md:text-7xl font-black neon-text text-white mb-4 leading-tight">
                                CODE THE <br class="md:hidden"> FUTURE
                            </h1>
                            <p class="text-cyan-400 font-mono tracking-[0.2em] text-xs sm:text-sm md:text-lg uppercase">
                                Welcome to DUET CSE FEST 2026
                            </p>
                            <div class="mt-8">
                                <button class="cyber-btn text-xs">Join Now</button>
                            </div>
                        </div>
                    </div>

                    <!-- Slide 2 -->
                    <div class="min-w-full h-full relative slide">
                        <img src="{{ asset('storage/' . ($setting->main_banner2 ?? '')) }}"
                            class="w-full h-full object-cover opacity-50 md:opacity-60" alt="CSE FEST 2"
                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1555066931-4365d14bab8c?auto=format&fit=crop&q=80&w=2070';">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/40 to-transparent"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6">
                            <h1
                                class="heading-font text-4xl sm:text-5xl md:text-7xl font-black neon-text text-white mb-4 leading-tight">
                                CYBER <br class="md:hidden"> BATTLEFIELD
                            </h1>
                            <p class="text-cyan-400 font-mono tracking-[0.2em] text-xs sm:text-sm md:text-lg uppercase">
                                Hackathon • CTF • DL Sprint
                            </p>
                        </div>
                    </div>

                    <!-- Slide 3 -->
                    <div class="min-w-full h-full relative slide">
                        <img src="{{ asset('storage/' . ($setting->main_banner3 ?? '')) }}"
                            class="w-full h-full object-cover opacity-50 md:opacity-60" alt="CSE FEST 3"
                            onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1517694712202-14dd9538aa97?auto=format&fit=crop&q=80&w=2070';">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#020617] via-[#020617]/40 to-transparent"></div>
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6">
                            <h1
                                class="heading-font text-4xl sm:text-5xl md:text-7xl font-black neon-text text-white mb-4 leading-tight">
                                DOMINATE <br class="md:hidden"> THE RANK
                            </h1>
                            <p class="text-cyan-400 font-mono tracking-[0.2em] text-xs sm:text-sm md:text-lg uppercase">
                                Show your skills to the world
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <button onclick="prevSlide()"
                    class="hidden md:flex absolute left-6 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full border border-cyan-500/30 items-center justify-center text-cyan-400 hover:bg-cyan-400 hover:text-black transition-all">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button onclick="nextSlide()"
                    class="hidden md:flex absolute right-6 top-1/2 -translate-y-1/2 z-10 w-12 h-12 rounded-full border border-cyan-500/30 items-center justify-center text-cyan-400 hover:bg-cyan-400 hover:text-black transition-all">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>

                <!-- Slider Indicators (Dots) -->
                <div class="absolute bottom-12 left-1/2 -translate-x-1/2 flex gap-3 z-10" id="slider-dots">
                    <div onclick="goToSlide(0)"
                        class="w-2 h-2 md:w-3 md:h-3 rounded-full cursor-pointer dot transition-all duration-300 bg-cyan-400">
                    </div>
                    <div onclick="goToSlide(1)"
                        class="w-2 h-2 md:w-3 md:h-3 rounded-full cursor-pointer dot transition-all duration-300 bg-cyan-400/20">
                    </div>
                    <div onclick="goToSlide(2)"
                        class="w-2 h-2 md:w-3 md:h-3 rounded-full cursor-pointer dot transition-all duration-300 bg-cyan-400/20">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        const dots = document.querySelectorAll('.dot');
        const wrapper = document.getElementById('slider-wrapper');
        const totalSlides = slides.length;

        function updateSlider() {
            // স্লাইড পরিবর্তন
            wrapper.style.transform = `translateX(-${currentSlide * 100}%)`;

            // ডটস আপডেট
            dots.forEach((dot, index) => {
                if (index === currentSlide) {
                    dot.classList.remove('bg-cyan-400/20');
                    dot.classList.add('bg-cyan-400');
                } else {
                    dot.classList.remove('bg-cyan-400');
                    dot.classList.add('bg-cyan-400/20');
                }
            });
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            updateSlider();
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            updateSlider();
        }

        function goToSlide(index) {
            currentSlide = index;
            updateSlider();
        }

        // অটো প্লে (৫ সেকেন্ড পর পর)
        setInterval(nextSlide, 5000);
    </script>
@endsection
