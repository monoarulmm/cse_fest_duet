<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <!-- user-scalable=no যোগ করা হয়েছে যাতে জুম না হয় -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'CSE CERNIVAL 2026 | DUET')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $setting = \App\Models\Setting::first();
    @endphp
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if ($setting && $setting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
        <!-- For Apple Devices -->
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
        <!-- Default Favicon if not set -->
        <link rel="icon" type="image/x-icon" href="{{ asset('duet-logo.png') }}">
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=JetBrains+Mono:wght@300;500&display=swap');

        :root {
            --neon-cyan: #22d3ee;
            --bg-color: #020617;
            --text-color: #e2e8f0;
            --nav-bg: rgba(15, 23, 42, 0.8);
        }

        .light-mode {
            /* Backgrounds */
            --bg-main: #F1F5F9;
            /* মূল ব্যাকগ্রাউন্ড */
            --bg-surface: #FFFFFF;
            /* কার্ড বা নেভবার ব্যাকগ্রাউন্ড */

            /* Texts */
            --text-main: #97a6c7;
            /* বড় হেডলাইন */
            --text-body: #334155;
            /* সাধারণ লেখা */

            /* Accents & Borders */
            --accent: #0891B2;
            /* বাটন বা লিংক কালার */
            --border-color: #E2E8F0;
            /* বর্ডার বা ডিভাইডার */

            /* Shadows (প্রফেশনাল লুকের জন্য হালকা শ্যাডো) */
            --nav-shadow: 0 4px 6px -1px rgba(231, 207, 207, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05);
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'JetBrains Mono', monospace;
            margin: 0;
            overflow-x: hidden;
            transition: background-color 0.4s, color 0.4s;
            padding-bottom: 90px;
            /* Space for mobile bottom nav */
            touch-action: manipulation;
            /* জুম রোধে সাহায্য করে */
        }

        #matrix-bg {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            opacity: 0.15;
            pointer-events: none;
        }

        .glass-nav {
            background: var(--nav-bg);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(34, 211, 238, 0.2);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
        }

        .heading-font {
            font-family: 'Orbitron', sans-serif;
        }

        .search-input {
            background: rgba(34, 211, 238, 0.05);
            border: 1px solid rgba(34, 211, 238, 0.2);
            transition: all 0.3s ease;
        }

        .search-input:focus {
            border-color: var(--neon-cyan);
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.2);
            outline: none;
        }

        /* Mobile specific bottom nav tweaks */
        .mobile-link {
            color: #94a3b8;
            transition: color 0.3s;
        }

        .mobile-link.active {
            color: var(--neon-cyan);
        }
    </style>
    @yield('custom_css')

</head>

<body class="dark-mode">
    <canvas id="matrix-bg"></canvas>

    <!-- NAVIGATION (Unified for Desktop & Mobile) -->
    <nav class="fixed top-4 md:top-6 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-6xl">
        <div class="glass-nav rounded-full px-4 md:px-6 py-2 md:py-3 flex justify-between items-center relative">

            <div class="flex items-center gap-2 md:gap-3">
                <div class="w-10 h-10 md:w-12 md:h-12 flex items-center justify-center overflow-hidden">
                    @if ($setting && $setting->logo)
                        <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ $setting->site_name ?? 'Logo' }}"
                            class="w-full h-full object-contain filter drop-shadow-[0_0_8px_rgba(34,211,238,0.5)]">
                    @else
                        <span
                            class="text-yellow-500 font-bold text-xl">{{ substr($setting->site_name ?? 'D', 0, 1) }}</span>
                    @endif
                </div>
                <div class="flex flex-col leading-none">
                    <span class="heading-font font-black text-sm md:text-xl tracking-tighter uppercase mr-2 text-white">
                        DUET <span class="text-cyan-400">CSE</span>
                    </span>
                    <span class="text-[8px] md:text-[10px] font-bold tracking-[0.2em] text-cyan-500/80 uppercase">
                        CERNIVAL</span>
                </div>
            </div>

            <div class="hidden lg:flex items-center gap-10 text-[12px] font-bold tracking-[0.2em] uppercase text-white">
                <a href="/" class="hover:text-cyan-400 transition duration-300">Home</a>
                <a href="/about" class="hover:text-cyan-400 transition duration-300">About</a>

                <div class="relative group">
                    <button
                        class="hover:text-cyan-400 transition duration-300 uppercase tracking-widest text-[12px] font-bold flex items-center gap-1 focus:outline-none">
                        Segment <i
                            class="fa-solid fa-chevron-down text-[10px] transition-transform group-hover:rotate-180"></i>
                    </button>
                    <div
                        class="absolute left-0 mt-2 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-50">
                        <div
                            class="bg-[#0f172a]/95 border border-cyan-500/20 rounded-xl shadow-2xl overflow-hidden backdrop-blur-md">
                            @php $activeEvents = \App\Models\Event::where('is_active', true)->get(); @endphp
                            @forelse ($activeEvents as $event)
                                <a href="{{ route('event.dashboard', $event->slug) }}"
                                    class="block px-5 py-3 text-[10px] text-slate-300 hover:bg-cyan-500/10 hover:text-cyan-400 border-b border-white/5 last:border-0 transition-all uppercase tracking-widest font-bold">
                                    {{ $event->name }}
                                </a>
                            @empty
                                <span class="block px-5 py-3 text-[10px] text-slate-500 italic">No Active Events</span>
                            @endforelse
                        </div>
                    </div>
                </div>
                <a href="/schedule" class="hover:text-cyan-400 transition duration-300">Schedule</a>
                <a href="/contact" class="hover:text-cyan-400 transition duration-300">Contact</a>
                <a href="/cse-gallery" class="hover:text-cyan-400 transition duration-300">Gallery</a>
            </div>

            {{-- <div class="flex items-center gap-2">
                <div class="hidden md:block relative w-48 lg:w-64">
                    <input type="text" placeholder="Search..."
                        class="bg-slate-950/40 border border-slate-800 focus:border-cyan-500/50 w-full px-4 py-2 rounded-full text-xs text-white outline-none transition-all">
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-cyan-500/50 text-xs"></i>
                </div>

                <button id="theme-toggle"
                    class="w-8 h-8 md:w-10 md:h-10 rounded-full border border-cyan-500/20 flex items-center justify-center text-cyan-400 hover:bg-cyan-500 hover:text-white transition-all">
                    <i class="fa-solid fa-moon text-xs md:text-base" id="theme-icon"></i>
                </button>

                @auth
                    <div class="relative group">
                        <button
                            class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400">
                            <i class="fa-solid fa-user text-xs"></i>
                        </button>
                        <div
                            class="absolute right-0 mt-3 w-48 bg-[#0f172a]/95 border border-cyan-500/20 rounded-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all backdrop-blur-xl p-2 z-[100]">
                            <a href="{{ route('dashboard') }}"
                                class="block px-4 py-2 text-[10px] text-slate-300 hover:bg-cyan-500/10 rounded-lg transition-all uppercase font-bold">Dashboard</a>
                            <form action="{{ route('logout') }}" method="POST"> @csrf
                                <button
                                    class="w-full text-left px-4 py-2 text-[10px] text-red-400 hover:bg-red-500/10 rounded-lg transition-all uppercase font-bold">Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="hidden sm:flex items-center gap-2 bg-cyan-500/10 p-2 md:px-4 md:py-2 rounded-full border border-cyan-500/20 text-cyan-400 hover:bg-cyan-500 hover:text-slate-950 transition-all">
                        <i class="fa-solid fa-right-to-bracket text-xs md:text-base"></i>
                        <span class="text-[10px] font-bold uppercase">Join</span>
                    </a>
                @endauth

                <button id="mobile-btn"
                    class="lg:hidden w-8 h-8 flex flex-col items-center justify-center gap-1.5 focus:outline-none">
                    <span id="line1" class="w-5 h-0.5 bg-cyan-400 rounded-full transition-all duration-300"></span>
                    <span id="line2" class="w-5 h-0.5 bg-cyan-400 rounded-full transition-all duration-300"></span>
                    <span id="line3" class="w-5 h-0.5 bg-cyan-400 rounded-full transition-all duration-300"></span>
                </button>
            </div> --}}

            <div class="flex items-center gap-2 md:gap-4">
                <!-- ১. সার্চ সেকশন -->
                {{-- <div id="search-container" class="hidden md:block relative w-48 lg:w-64 transition-all duration-300">
                    <input type="text" placeholder="Search events..."
                        class="bg-slate-950/40 border border-slate-800 focus:border-cyan-500/50 w-full px-4 py-2 rounded-full text-xs text-white outline-none transition-all focus:ring-1 focus:ring-cyan-500/30">
                    <i class="fa-solid fa-magnifying-glass absolute right-3 top-2.5 text-cyan-500/50 text-xs"></i>

                    
                </div> --}}

                <div id="search-container" class="hidden md:block relative w-48 lg:w-64">
                    <form action="{{ route('check.result') }}" method="POST">
                        @csrf
                        <input type="text" name="participant_id" placeholder="ID দিয়ে রেজাল্ট খুঁজুন..."
                            class="bg-slate-950/40 border border-slate-800 focus:border-cyan-500/50 w-full px-4 py-2 rounded-full text-xs text-white outline-none transition-all focus:ring-1 focus:ring-cyan-500/30">
                        <button type="submit"
                            class="absolute right-3 top-2.5 text-cyan-500/50 text-xs hover:text-cyan-400">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </form>
                </div>

                <!-- মোবাইল সার্চ ট্রিগার বাটন -->
                <button id="mobile-search-btn"
                    class="md:hidden w-8 h-8 flex items-center justify-center text-cyan-400 active:scale-90 transition-transform">
                    <i class="fa-solid fa-magnifying-glass text-sm"></i>
                </button>

                <!-- ২. থিম টগল -->
                <button id="theme-toggle"
                    class="w-8 h-8 md:w-10 md:h-10 rounded-xl border border-cyan-500/20 flex items-center justify-center text-cyan-400 hover:bg-cyan-500/10 transition-all active:scale-90">
                    <i class="fa-solid fa-moon text-xs md:text-base" id="theme-icon"></i>
                </button>

                <!-- ৩. অথ সেকশন -->
                @auth
                    <div class="relative">
                        <!-- প্রোফাইল বাটন (ক্লিকের জন্য আইডি যোগ করা হয়েছে) -->
                        <button id="profile-dropdown-btn" class="flex items-center gap-2 group outline-none">
                            <div
                                class="w-8 h-8 md:w-10 md:h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 flex items-center justify-center text-cyan-400 group-hover:border-cyan-500/50 transition-all">
                                <i class="fa-solid fa-user-gear text-sm"></i>
                            </div>
                            <div class="hidden lg:block text-left">
                                <p class="text-[10px] text-slate-400 leading-none">Welcome,</p>
                                <p class="text-[11px] font-bold text-white truncate w-20">{{ Auth::user()->name }}</p>
                            </div>
                        </button>

                        <!-- ড্রপডাউন মেনু (ID: profile-menu) -->
                        <div id="profile-menu"
                            class="absolute right-0 mt-3 w-56 bg-[#0f172a]/95 border border-cyan-500/20 rounded-[1.5rem] opacity-0 invisible transition-all duration-300 backdrop-blur-2xl p-2 z-[100] shadow-2xl">

                            <div class="px-4 py-3 border-b border-white/5 mb-2 md:hidden">
                                <p class="text-[10px] text-cyan-500 font-black uppercase tracking-widest">Account</p>
                                <p class="text-xs text-white font-bold truncate">{{ Auth::user()->name }}</p>
                            </div>

                            <!-- সকল ইউজারের জন্য কমন ড্যাশবোর্ড (যদি প্রয়োজন হয়) -->
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center gap-3 px-4 py-3 text-[11px] text-slate-300 hover:bg-cyan-500/10 rounded-xl transition-all uppercase font-bold tracking-tighter">
                                <i class="fa-solid fa-gauge-high text-cyan-500"></i> Dashboard
                            </a>

                            <!-- শুধুমাত্র অ্যাডমিন হলে এই অপশনগুলো দেখাবে -->
                            @if (Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-[11px] text-slate-300 hover:bg-cyan-500/10 rounded-xl transition-all uppercase font-bold tracking-tighter">
                                    <i class="fa-solid fa-user-shield text-cyan-500"></i> Admin Panel
                                </a>


                                <a href="{{ url('settings') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-[11px] text-slate-300 hover:bg-cyan-500/10 rounded-xl transition-all uppercase font-bold tracking-tighter">
                                    <i class="fa-solid fa-sliders text-cyan-500"></i> Settings
                                </a>
                                <a href="{{ url('events') }}"
                                    class="flex items-center gap-3 px-4 py-3 text-[11px] text-slate-300 hover:bg-cyan-500/10 rounded-xl transition-all uppercase font-bold tracking-tighter">
                                    <i class="fa-solid fa-sliders text-cyan-500"></i>
                                    Segmetn Managment
                                </a>
                            @endif

                            <div class="h-[1px] bg-white/5 my-2 mx-2"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 text-[11px] text-red-400 hover:bg-red-500/10 rounded-xl transition-all uppercase font-bold tracking-tighter">
                                    <i class="fa-solid fa-power-off"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-2 bg-cyan-500/10 px-4 py-2 rounded-xl border border-cyan-500/20 text-cyan-400 hover:bg-cyan-500 hover:text-slate-950 transition-all shadow-lg active:scale-95">
                        <i class="fa-solid fa-fingerprint text-sm"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest">Join</span>
                    </a>
                @endauth

                <!-- ৪. হ্যামবার্গার মেনু -->
                <button id="mobile-btn"
                    class="lg:hidden w-10 h-10 flex flex-col items-center justify-center gap-1.5 focus:outline-none bg-slate-900/50 rounded-xl border border-white/5">
                    <span id="line1" class="w-5 h-[2px] bg-cyan-400 rounded-full transition-all"></span>
                    <span id="line2"
                        class="w-3 h-[2px] bg-cyan-400 rounded-full transition-all ml-auto mr-2"></span>
                    <span id="line3" class="w-5 h-[2px] bg-cyan-400 rounded-full transition-all"></span>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Container -->
        <div id="mobile-menu"
            class="lg:hidden absolute top-full left-0 w-full mt-4 opacity-0 invisible -translate-y-4 transition-all duration-500 z-50">

            <div
                class="bg-[var(--nav-bg)] border border-cyan-500/20 rounded-[2.5rem] p-6 backdrop-blur-2xl shadow-[0_20px_50px_rgba(0,0,0,0.3)] mx-2">

                <div class="flex flex-col gap-2">
                    <!-- Basic Links -->
                    <a href="/"
                        class="flex items-center gap-4 px-5 py-4 rounded-2xl hover:bg-cyan-500/10 transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-500 group-hover:bg-cyan-500 group-hover:text-slate-950 transition-all">
                            <i class="fa-solid fa-house-chimney text-sm"></i>
                        </div>
                        <span
                            class="text-[12px] font-bold tracking-[0.2em] uppercase text-[var(--text-main)]">Home</span>
                    </a>

                    <a href="/about"
                        class="flex items-center gap-4 px-5 py-4 rounded-2xl hover:bg-cyan-500/10 transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-500 group-hover:bg-cyan-500 group-hover:text-slate-950 transition-all">
                            <i class="fa-solid fa-circle-info text-sm"></i>
                        </div>
                        <span class="text-[12px] font-bold tracking-[0.2em] uppercase text-[var(--text-main)]">About
                            Us</span>
                    </a>

                    <!-- Events Section -->
                    <div class="mt-4 px-5 mb-2">
                        <p class="text-[9px] font-black text-cyan-500/50 uppercase tracking-[0.3em]">Active Events</p>
                    </div>

                    <div class="grid grid-cols-1 gap-2">
                        @foreach ($activeEvents as $event)
                            <a href="{{ route('event.dashboard', $event->slug) }}"
                                class="flex items-center justify-between px-5 py-4 rounded-2xl bg-slate-500/5 border border-white/5 hover:border-cyan-500/30 transition-all group">
                                <div class="flex items-center gap-4">
                                    <div class="w-2 h-2 rounded-full bg-cyan-500 shadow-[0_0_8px_#06b6d4]"></div>
                                    <span
                                        class="text-[10px] font-bold uppercase tracking-widest text-[var(--text-main)] opacity-80 group-hover:opacity-100">{{ $event->name }}</span>
                                </div>
                                <i
                                    class="fa-solid fa-chevron-right text-[10px] text-slate-500 group-hover:text-cyan-500 transition-all"></i>
                            </a>
                        @endforeach
                    </div>

                    <!-- Contact & Auth -->
                    <div class="h-[1px] bg-cyan-500/10 my-4 mx-5"></div>

                    <a href="/contact"
                        class="flex items-center gap-4 px-5 py-4 rounded-2xl hover:bg-cyan-500/10 transition-all group">
                        <div
                            class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-500 group-hover:bg-cyan-500 group-hover:text-slate-950 transition-all">
                            <i class="fa-solid fa-headset text-sm"></i>
                        </div>
                        <span
                            class="text-[12px] font-bold tracking-[0.2em] uppercase text-[var(--text-main)]">Contact</span>
                    </a>
                    <a href="{{ url('cse-gallery') }}"
                        class="mt-4 flex items-center justify-center gap-3 bg-cyan-500 py-4 rounded-2xl text-slate-950 font-black text-[12px] uppercase tracking-tighter hover:scale-[0.98] transition-transform shadow-[0_10px_20px_rgba(6,182,212,0.3)]">
                        <i class="fa-solid fa-id-card-clip text-lg"></i>
                        Visual Archive
                    </a>
                    {{-- @guest
                        <a href="{{ route('login') }}"
                            class="mt-4 flex items-center justify-center gap-3 bg-cyan-500 py-4 rounded-2xl text-slate-950 font-black text-[12px] uppercase tracking-tighter hover:scale-[0.98] transition-transform shadow-[0_10px_20px_rgba(6,182,212,0.3)]">
                            <i class="fa-solid fa-id-card-clip text-lg"></i>
                            Get Access Card
                        </a>
                    @endguest --}}
                </div>
            </div>
        </div>
    </nav>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileBtn = document.getElementById('mobile-btn');
            const mobileMenu = document.getElementById('mobile-menu');

            // Lines for Hamburger Animation
            const l1 = document.getElementById('line1');
            const l2 = document.getElementById('line2');
            const l3 = document.getElementById('line3');

            mobileBtn.addEventListener('click', function() {
                // Toggle menu display
                mobileMenu.classList.toggle('opacity-0');
                mobileMenu.classList.toggle('invisible');
                mobileMenu.classList.toggle('translate-y-[-10px]');
                mobileMenu.classList.toggle('translate-y-0');

                // Animate Hamburger to X
                l1.classList.toggle('rotate-45');
                l1.classList.toggle('translate-y-2');
                l2.classList.toggle('opacity-0');
                l3.classList.toggle('-rotate-45');
                l3.classList.toggle('-translate-y-2');
            });

            // Close menu if clicking outside
            window.addEventListener('click', function(e) {
                if (!mobileBtn.contains(e.target) && !mobileMenu.contains(e.target)) {
                    mobileMenu.classList.add('opacity-0', 'invisible', 'translate-y-[-10px]');
                    l1.classList.remove('rotate-45', 'translate-y-2');
                    l2.classList.remove('opacity-0');
                    l3.classList.remove('-rotate-45', '-translate-y-2');
                }
            });
        });
    </script>

    {{-- SweetAlert Scripts with Theme Integration --}}
    <script>
        const customSwal = Swal.mixin({
            background: '#0f172a', // Slate 900
            color: '#f8fafc', // Slate 50
            confirmButtonColor: '#06b6d4', // Cyan 500
            customClass: {
                popup: 'rounded-3xl border border-cyan-500/20 shadow-2xl backdrop-blur-xl',
                title: 'heading-font uppercase italic tracking-widest text-lg',
                confirmButton: 'uppercase tracking-widest font-black text-xs px-10 py-3 rounded-xl transition-all'
            }
        });

        @if (session('success'))
            customSwal.fire({
                icon: 'success',
                iconColor: '#06b6d4',
                title: 'SUCCESSFUL!',
                text: "{{ session('success') }}",
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            customSwal.fire({
                icon: 'error',
                iconColor: '#ef4444', // Red 500
                title: 'ERROR!',
                text: "{{ session('error') }}",
                confirmButtonText: 'TRY AGAIN'
            });
        @endif

        @if ($errors->any())
            customSwal.fire({
                icon: 'warning',
                iconColor: '#eab308', // Yellow 500
                title: 'VALIDATION FAILED!',
                html: '<div class="text-left text-[11px] opacity-80 uppercase tracking-wider">{!! implode('<br>• ', $errors->all()) !!}</div>',
                confirmButtonText: 'GOT IT'
            });
        @endif
    </script>
    <main class="pt-24 md:pt-32">

        @yield('content')
    </main>
    <footer class="relative border-t border-cyan-500/10 pt-16 pb-32 md:pb-12 overflow-hidden">
        <div
            class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-px bg-gradient-to-r from-transparent via-cyan-500/50 to-transparent">
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

                {{-- Brand Section --}}
                <div class="space-y-4">
                    <div class="flex items-center gap-2">
                        <span class="heading-font font-black text-xl tracking-tighter uppercase">
                            CSE <span class="text-cyan-400">FEST</span>
                        </span>
                    </div>
                    <p class="text-xs leading-relaxed opacity-70 italic">
                        &gt; Executing mission: Future Tech<br>
                        &gt; Status: Innovation Active<br>
                        &gt; Location: DUET, Gazipur
                    </p>
                    <div class="flex gap-3 pt-2">
                        @if ($setting->fb_link)
                            <a href="{{ $setting->fb_link }}"
                                class="w-8 h-8 rounded-lg border border-cyan-500/20 flex items-center justify-center hover:bg-cyan-500 hover:text-black transition-all"><i
                                    class="fa-brands fa-facebook-f text-xs"></i></a>
                        @endif
                        @if ($setting->youtube_link)
                            <a href="{{ $setting->youtube_link }}"
                                class="w-8 h-8 rounded-lg border border-cyan-500/20 flex items-center justify-center hover:bg-red-500 hover:text-white transition-all"><i
                                    class="fa-brands fa-youtube text-xs"></i></a>
                        @endif
                    </div>
                </div>

                {{-- Quick Links --}}
                <div>
                    <h4 class="heading-font text-cyan-400 text-[10px] tracking-[0.3em] uppercase mb-6 font-bold">
                        Directory</h4>
                    <ul class="space-y-3 text-[11px] font-bold uppercase tracking-wider">
                        <li><a href="#" class="hover:text-cyan-400 transition-colors">Events Schedule</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition-colors">Leaderboard</a></li>
                        <li><a href="#" class="hover:text-cyan-400 transition-colors">Rules & Policy</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div>
                    <h4 class="heading-font text-cyan-400 text-[10px] tracking-[0.3em] uppercase mb-6 font-bold">
                        Connectivity</h4>
                    <ul class="space-y-3 text-[11px] opacity-80">
                        <li><i class="fa-solid fa-location-dot mr-2 text-cyan-500"></i>
                            {{ $setting->address ?? 'Gazipur, BD' }}</li>
                        <li><i class="fa-solid fa-envelope mr-2 text-cyan-500"></i>
                            {{ $setting->email ?? 'csefest@duet.ac.bd' }}</li>
                    </ul>
                </div>

                {{-- System Status Card --}}
                <div style="background-color: var(--card-bg)"
                    class="p-5 rounded-2xl border border-cyan-500/10 relative group">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-[9px] font-black uppercase tracking-widest opacity-60">System Core</span>
                        <span class="flex h-2 w-2 rounded-full bg-cyan-500 animate-ping"></span>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between text-[10px] font-mono">
                            <span>Runtime</span>
                            <span class="text-cyan-400"> {{ app()->version() }}</span>
                        </div>
                        <div class="w-full h-1 bg-black/20 rounded-full overflow-hidden">
                            <div class="w-[70%] h-full bg-cyan-500"></div>
                        </div>
                        <p class="text-[9px] mt-4 opacity-50 font-mono">
                            Console: login_success...<br>
                            Ready for DUET_CSE_FEST_2026
                        </p>
                    </div>
                </div>
            </div>

            {{-- Bottom Bar --}}
            <div
                class="pt-8 border-t border-cyan-500/5 flex flex-col md:flex-row justify-between items-center gap-4 text-[9px] font-bold uppercase tracking-[0.2em] opacity-60">
                <p>&copy; 2026 DUET CSE FEST. All Rights Reserved.</p>
                <p>Developed by <span class="text-cyan-400">Monoar Islam</span></p>
            </div>
        </div>
    </footer>
    <!-- MOBILE BOTTOM TAB BAR (5 Items) - Fixed for No Zoom -->
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-50 px-4 pb-4">
        <div
            class="glass-nav rounded-3xl flex justify-between items-center px-2 py-3 border border-cyan-500/30 shadow-2xl">
            <!-- 1. Home -->
            <a href="/" class="mobile-link active flex flex-col items-center flex-1">
                <i class="fa-solid fa-house-chimney text-lg"></i>
                <span class="text-[9px] mt-1 font-bold uppercase">Home</span>
            </a>
            <!-- 2. Events -->
            <a href="/schedule" class="mobile-link flex flex-col items-center flex-1">
                <i class="fa-solid fa-code text-lg"></i>
                <span class="text-[9px] mt-1 font-bold uppercase">Events</span>
            </a>
            <!-- 3. Central Plus Button -->
            <div class="relative -top-6 flex-1 flex justify-center">
                <div
                    class="bg-cyan-500 w-12 h-12 rounded-2xl rotate-45 flex items-center justify-center shadow-[0_0_15px_rgba(34,211,238,0.6)] border-4 border-[#020617]">
                    <i class="fa-solid fa-plus text-slate-900 text-xl -rotate-45"></i>
                </div>
            </div>
            <!-- 4. Rank -->
            <a href="/about" class="mobile-link flex flex-col items-center flex-1">
                <i class="fa-solid fa-ranking-star text-lg"></i>
                <span class="text-[9px] mt-1 font-bold uppercase">Rank</span>
            </a>
            <!-- 5. Profile/Settings -->
            <a href="/contact" class="mobile-link flex flex-col items-center flex-1">
                <i class="fa-solid fa-user-gear text-lg"></i>
                <span class="text-[9px] mt-1 font-bold uppercase">Setup</span>
            </a>
        </div>
    </div>


    <script>
        // Matrix Canvas Background
        const canvas = document.getElementById('matrix-bg');
        const ctx = canvas.getContext('2d');

        function resizeCanvas() {
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;
        }
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();

        const letters = "010101";
        const fontSize = 16;
        const columns = canvas.width / fontSize;
        const drops = Array(Math.floor(columns)).fill(1);

        function drawMatrix() {
            ctx.fillStyle = document.body.classList.contains('light-mode') ? "rgba(248, 250, 252, 0.15)" :
                "rgba(2, 6, 23, 0.15)";
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            ctx.fillStyle = "#22d3ee";
            ctx.font = fontSize + "px monospace";
            drops.forEach((y, i) => {
                const text = letters[Math.floor(Math.random() * letters.length)];
                ctx.fillText(text, i * fontSize, y * fontSize);
                if (y * fontSize > canvas.height && Math.random() > 0.975) drops[i] = 0;
                drops[i]++;
            });
        }
        setInterval(drawMatrix, 40);

        // Theme Toggle Logic
        const themeBtn = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');

        themeBtn.addEventListener('click', () => {
            document.body.classList.toggle('light-mode');
            if (document.body.classList.contains('light-mode')) {
                themeIcon.classList.replace('fa-moon', 'fa-sun');
            } else {
                themeIcon.classList.replace('fa-sun', 'fa-moon');
            }
        });

        // মোবাইল জুম প্রতিরোধ করার অতিরিক্ত স্ক্রিপ্ট
        document.addEventListener('touchstart', function(event) {
            if (event.touches.length > 1) {
                event.preventDefault();
            }
        }, {
            passive: false
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchBtn = document.getElementById('mobile-search-btn');
            const searchContainer = document.getElementById('search-container');
            const profileBtn = document.getElementById('profile-dropdown-btn');
            const profileMenu = document.getElementById('profile-menu');

            // ১. মোবাইল সার্চ টগল
            if (searchBtn) {
                searchBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    searchContainer.classList.toggle('hidden');
                    // মোবাইলে সার্চ বক্সটি সুন্দরভাবে দেখানোর জন্য কিছু ক্লাস অ্যাড করা
                    if (!searchContainer.classList.contains('hidden')) {
                        searchContainer.classList.add('absolute', 'top-full', 'right-0', 'left-0', 'mt-4',
                            'px-4', 'z-[110]', 'animate-in', 'fade-in', 'slide-in-from-top-2');
                    }
                });
            }

            // ২. প্রোফাইল ড্রপডাউন টগল
            if (profileBtn) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isVisible = !profileMenu.classList.contains('invisible');

                    // মেনু খোলা বা বন্ধ করা
                    if (isVisible) {
                        profileMenu.classList.add('opacity-0', 'invisible');
                    } else {
                        profileMenu.classList.remove('opacity-0', 'invisible');
                    }
                });
            }

            // ৩. ড্রপডাউনের বাইরে ক্লিক করলে মেনু বন্ধ হওয়া
            window.addEventListener('click', function() {
                if (profileMenu) {
                    profileMenu.classList.add('opacity-0', 'invisible');
                }
                // সার্চ কন্টেইনার বন্ধ করতে চাইলে নিচের লাইনটি আনকমেন্ট করুন
                // if (window.innerWidth < 768) searchContainer.classList.add('hidden');
            });
        });
    </script>
</body>

</html>
