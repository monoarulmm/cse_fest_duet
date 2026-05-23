<!DOCTYPE html>
<html lang="en" data-theme="dark">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>@yield('title', 'DUET CSE CARNIVAL 2026 ')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=JetBrains+Mono:ital,wght@0,300;0,500;0,700;1,300&display=swap">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @php
        $setting = \App\Models\Setting::first();
        $activeEvents = \App\Models\Event::where('is_active', true)->get();
    @endphp

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if ($setting && $setting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('duet-logo.png') }}">
    @endif

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* ═══════════════════════════════════════════════════
           DESIGN TOKENS — Dark Mode (default)
        ═══════════════════════════════════════════════════ */
        :root,
        [data-theme="dark"] {
            --bg-base:       #020617;
            --bg-surface:    #0f172a;
            --bg-elevated:   #1e293b;
            --bg-card:       rgba(15, 23, 42, 0.8);

            --text-primary:  #f1f5f9;
            --text-secondary:#94a3b8;
            --text-muted:    #475569;

            --accent:        #22d3ee;
            --accent-dim:    rgba(34, 211, 238, 0.15);
            --accent-border: rgba(34, 211, 238, 0.2);
            --accent-glow:   0 0 20px rgba(34, 211, 238, 0.3);

            --border-soft:   rgba(255, 255, 255, 0.06);
            --border-mid:    rgba(255, 255, 255, 0.10);
            --border-accent: rgba(34, 211, 238, 0.25);

            --nav-bg:        rgba(2, 6, 23, 0.82);
            --nav-border:    rgba(34, 211, 238, 0.15);
            --nav-shadow:    0 8px 40px rgba(0,0,0,0.5), inset 0 1px 0 rgba(255,255,255,0.04);

            --tab-bg:        rgba(2, 6, 23, 0.92);
            --tab-border:    rgba(34, 211, 238, 0.2);

            --shadow-card:   0 4px 24px rgba(0,0,0,0.4);
            --shadow-glow:   0 0 24px rgba(34,211,238,0.2);

            --matrix-fade:   rgba(2, 6, 23, 0.14);

            --swal-bg:       #0f172a;
            --swal-text:     #f8fafc;
        }

        /* ═══════════════════════════════════════════════════
           DESIGN TOKENS — Light Mode
        ═══════════════════════════════════════════════════ */
        [data-theme="light"] {
            --bg-base:       #f0f6ff;
            --bg-surface:    #ffffff;
            --bg-elevated:   #e8f0fe;
            --bg-card:       rgba(255, 255, 255, 0.9);

            --text-primary:  #0f172a;
            --text-secondary:#334155;
            --text-muted:    #64748b;

            --accent:        #0891b2;
            --accent-dim:    rgba(8, 145, 178, 0.10);
            --accent-border: rgba(8, 145, 178, 0.25);
            --accent-glow:   0 0 20px rgba(8, 145, 178, 0.2);

            --border-soft:   rgba(0, 0, 0, 0.06);
            --border-mid:    rgba(0, 0, 0, 0.10);
            --border-accent: rgba(8, 145, 178, 0.25);

            --nav-bg:        rgba(240, 246, 255, 0.90);
            --nav-border:    rgba(8, 145, 178, 0.20);
            --nav-shadow:    0 4px 24px rgba(0,0,0,0.08), inset 0 1px 0 rgba(255,255,255,0.8);

            --tab-bg:        rgba(255, 255, 255, 0.95);
            --tab-border:    rgba(8, 145, 178, 0.2);

            --shadow-card:   0 2px 16px rgba(0,0,0,0.08);
            --shadow-glow:   0 0 16px rgba(8,145,178,0.15);

            --matrix-fade:   rgba(240, 246, 255, 0.18);

            --swal-bg:       #ffffff;
            --swal-text:     #0f172a;
        }

        /* ═══════════════════════════════════════════════════
           BASE
        ═══════════════════════════════════════════════════ */
        *, *::before, *::after { box-sizing: border-box; }

        html { scroll-behavior: smooth; }

        body {
            background-color: var(--bg-base);
            color: var(--text-primary);
            font-family: 'JetBrains Mono', monospace;
            margin: 0;
            overflow-x: hidden;
            transition: background-color 0.4s ease, color 0.35s ease;
            padding-bottom: 90px;
            touch-action: manipulation;
            -webkit-tap-highlight-color: transparent;
        }

        body.loading { overflow: hidden; }

        .heading-font { font-family: 'Orbitron', sans-serif; }

        /* ═══════════════════════════════════════════════════
           MATRIX BACKGROUND
        ═══════════════════════════════════════════════════ */
        #matrix-bg {
            position: fixed;
            inset: 0;
            z-index: -1;
            opacity: 0.12;
            pointer-events: none;
            transition: opacity 0.4s;
        }
        [data-theme="light"] #matrix-bg { opacity: 0.05; }

        /* ═══════════════════════════════════════════════════
           PRELOADER
        ═══════════════════════════════════════════════════ */
        #preloader {
            position: fixed;
            inset: 0;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            background: var(--bg-base);
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }
        #preloader.loader-hidden {
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
        }
        @keyframes loading-slide {
            0%   { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* ═══════════════════════════════════════════════════
           GLASS NAV
        ═══════════════════════════════════════════════════ */
        .glass-nav {
            background: var(--nav-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid var(--nav-border);
            box-shadow: var(--nav-shadow);
        }

        /* ═══════════════════════════════════════════════════
           MOBILE MENU
        ═══════════════════════════════════════════════════ */
        #mobile-menu {
            transition: opacity 0.28s ease, transform 0.28s ease, visibility 0.28s ease;
        }
        #mobile-menu.menu-closed {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            pointer-events: none;
        }
        #mobile-menu.menu-open {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
            pointer-events: auto;
        }

        /* ═══════════════════════════════════════════════════
           MOBILE SEARCH PANEL
        ═══════════════════════════════════════════════════ */
        #mobile-search-panel {
            transition: max-height 0.32s cubic-bezier(0.4,0,0.2,1),
                        opacity 0.25s ease;
            overflow: hidden;
            max-height: 0;
            opacity: 0;
        }
        #mobile-search-panel.search-open {
            max-height: 80px;
            opacity: 1;
        }

        /* ═══════════════════════════════════════════════════
           PROFILE DROPDOWN
        ═══════════════════════════════════════════════════ */
        #profile-menu {
            transition: opacity 0.2s ease, transform 0.2s ease, visibility 0.2s ease;
            transform-origin: top right;
        }
        #profile-menu.dropdown-hidden {
            opacity: 0;
            visibility: hidden;
            transform: scale(0.95) translateY(-6px);
            pointer-events: none;
        }
        #profile-menu.dropdown-visible {
            opacity: 1;
            visibility: visible;
            transform: scale(1) translateY(0);
            pointer-events: auto;
        }

        /* ═══════════════════════════════════════════════════
           NAV INPUT — Light/Dark aware
        ═══════════════════════════════════════════════════ */
        .nav-input {
            background: var(--accent-dim);
            border: 1px solid var(--accent-border);
            color: var(--text-primary);
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }
        .nav-input::placeholder { color: var(--text-muted); }
        .nav-input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 2px var(--accent-dim);
            outline: none;
            background: var(--bg-surface);
        }

        /* ═══════════════════════════════════════════════════
           MOBILE BOTTOM TAB BAR
        ═══════════════════════════════════════════════════ */
        .tab-bar {
            background: var(--tab-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid var(--tab-border);
            box-shadow: 0 -4px 32px rgba(0,0,0,0.25), var(--shadow-glow);
        }
        [data-theme="light"] .tab-bar {
            box-shadow: 0 -2px 20px rgba(0,0,0,0.08), 0 -1px 0 rgba(8,145,178,0.1);
        }

        .tab-link {
            color: var(--text-muted);
            transition: color 0.25s ease, transform 0.2s ease;
            position: relative;
        }
        .tab-link:hover { color: var(--text-secondary); }
        .tab-link.tab-active { color: var(--accent); }
        .tab-link.tab-active::after {
            content: '';
            position: absolute;
            bottom: -6px;
            left: 50%;
            transform: translateX(-50%);
            width: 4px;
            height: 4px;
            background: var(--accent);
            border-radius: 50%;
            box-shadow: 0 0 6px var(--accent);
        }

        /* ═══════════════════════════════════════════════════
           FOOTER
        ═══════════════════════════════════════════════════ */
        /* footer {
            background: var(--bg-surface);
            border-top: 1px solid var(--border-accent);
        }
        [data-theme="light"] footer { background: #f8faff; } */

        /* ═══════════════════════════════════════════════════
           MOBILE MENU CARD
        ═══════════════════════════════════════════════════ */
        .mobile-menu-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-accent);
        }
        [data-theme="light"] .mobile-menu-card { background: rgba(255,255,255,0.97); }

        /* ═══════════════════════════════════════════════════
           DROPDOWN CARD
        ═══════════════════════════════════════════════════ */
        .dropdown-card {
            background: var(--bg-surface);
            border: 1px solid var(--border-accent);
        }
        [data-theme="light"] .dropdown-card { background: rgba(255,255,255,0.98); }

        /* ═══════════════════════════════════════════════════
           HAMBURGER ANIMATION
        ═══════════════════════════════════════════════════ */
        .ham-line {
            display: block;
            width: 20px;
            height: 2px;
            background: var(--accent);
            border-radius: 99px;
            transition: transform 0.3s ease, opacity 0.3s ease, width 0.3s ease;
            transform-origin: center;
        }
        .ham-open .ham-line:nth-child(1) { transform: translateY(7px) rotate(45deg); }
        .ham-open .ham-line:nth-child(2) { opacity: 0; transform: scaleX(0); }
        .ham-open .ham-line:nth-child(3) { transform: translateY(-7px) rotate(-45deg); }

        /* ═══════════════════════════════════════════════════
           THEME TOGGLE ICON SWAP
        ═══════════════════════════════════════════════════ */
        .theme-icon-dark,
        .theme-icon-light { transition: opacity 0.2s, transform 0.3s; }
        [data-theme="dark"]  .theme-icon-light { display: none; }
        [data-theme="dark"]  .theme-icon-dark  { display: block; }
        [data-theme="light"] .theme-icon-dark  { display: none; }
        [data-theme="light"] .theme-icon-light { display: block; }

        /* ═══════════════════════════════════════════════════
           SEARCH PANEL BG
        ═══════════════════════════════════════════════════ */
        #mobile-search-panel .search-panel-inner {
            background: var(--bg-surface);
            border: 1px solid var(--border-accent);
        }

        /* ═══════════════════════════════════════════════════
           MOBILE MENU LINK HOVER
        ═══════════════════════════════════════════════════ */
        .menu-item:hover { background: var(--accent-dim); }
        .menu-event-item:hover {
            border-color: var(--accent-border);
            background: var(--accent-dim);
        }

        /* ═══════════════════════════════════════════════════
           SCROLLBAR
        ═══════════════════════════════════════════════════ */
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: var(--bg-base); }
        ::-webkit-scrollbar-thumb { background: var(--accent-border); border-radius: 99px; }
    </style>

    @yield('custom_css')
</head>

<body class="loading">
    <canvas id="matrix-bg"></canvas>

    {{-- ═══════════ PRELOADER ═══════════ --}}
    <div id="preloader">
        <div class="relative">
            <div class="w-24 h-24 border-4 border-cyan-500/10 border-t-cyan-500 rounded-full animate-spin"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-12 h-12 rounded-xl border border-cyan-500/30 bg-cyan-500/5 animate-pulse flex items-center justify-center">
                    <span class="text-cyan-500 text-[10px] font-black tracking-tighter heading-font">CSE</span>
                </div>
            </div>
        </div>
        <div class="mt-10 text-center">
            <div class="text-[10px] uppercase tracking-[0.5em] animate-pulse" style="color:var(--accent)">
                System_Initializing...
            </div>
            <div class="mt-4 w-56 h-px rounded-full overflow-hidden relative" style="background:var(--border-soft)">
                <div class="absolute inset-0 w-full animate-[loading-slide_1.5s_ease-in-out_infinite]"
                    style="background: linear-gradient(90deg, transparent, var(--accent), transparent)"></div>
            </div>
        </div>
    </div>
{{-- ═══════════ NAVBAR ═══════════ --}}
    <nav class="fixed top-4 md:top-5 left-1/2 -translate-x-1/2 z-50 w-[95%] max-w-6xl">

        {{-- Main Bar --}}
        <div class="glass-nav rounded-full px-3 sm:px-4 md:px-6 py-2.5 flex justify-between items-center gap-2">

            {{-- Logo --}}
            <a href="{{ url('/') }}" class="group flex items-center gap-2 sm:gap-3 transition-all duration-300 shrink-0">
                <div class="relative">
                    <div class="absolute inset-0 rounded-xl blur-md transition-all duration-500"
                        style="background: var(--accent-dim); group-hover: background: rgba(34,211,238,0.3)"></div>
                    <div class="relative w-9 h-9 md:w-11 md:h-11 flex items-center justify-center rounded-xl overflow-hidden transition-all shadow-lg"
                        style="background: var(--bg-base); border: 1px solid var(--border-mid)">
                        @if ($setting && $setting->logo)
                            <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ $setting->site_name ?? 'Logo' }}"
                                class="w-7 h-7 md:w-8 md:h-8 object-contain transition-transform duration-500 group-hover:scale-110">
                        @else
                            <span class="font-black text-lg md:text-xl heading-font" style="color:var(--accent)">
                                {{ substr($setting->site_name ?? 'D', 0, 1) }}
                            </span>
                        @endif
                    </div>
                </div>
                <div class="flex flex-col justify-center pl-2 sm:pl-3 border-l transition-colors duration-300"
                    style="border-color: var(--border-mid)">
                    <span class="heading-font font-black text-base sm:text-lg md:text-xl tracking-tighter uppercase leading-none"
                        style="color: var(--text-primary)">
                        DUET <span style="color:var(--accent)">CSE</span>
                    </span>
                    <div class="flex items-center gap-1.5 mt-0.5">
                        <span class="text-[6px] sm:text-[7px] md:text-[8px] font-black tracking-[0.3em] uppercase leading-none"
                            style="color: var(--accent); opacity: 0.6">CARNIVAL</span>
                        <span class="w-1 h-1 rounded-full animate-pulse" style="background:var(--accent)"></span>
                    </div>
                </div>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden lg:flex items-center gap-8 text-[11px] font-bold tracking-[0.18em] uppercase"
                style="color: var(--text-primary)">
                <a href="/" class="transition-colors duration-200 hover:opacity-100"
                    style="color:var(--text-secondary)" onmouseover="this.style.color='var(--accent)'"
                    onmouseout="this.style.color='var(--text-secondary)'">Home</a>

                <a href="/about" style="color:var(--text-secondary)"
                    onmouseover="this.style.color='var(--accent)'"
                    onmouseout="this.style.color='var(--text-secondary)'"
                    class="transition-colors duration-200">About</a>

                {{-- Segment Dropdown --}}
                <div class="relative group">
                    <button class="flex items-center gap-1.5 transition-colors duration-200 font-bold text-[11px] tracking-[0.18em] uppercase focus:outline-none"
                        style="color:var(--text-secondary)"
                        onmouseover="this.style.color='var(--accent)'"
                        onmouseout="this.style.color='var(--text-secondary)'">
                        Segment
                        <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300 group-hover:rotate-180"></i>
                    </button>
                    <div class="absolute left-0 top-full pt-3 w-64 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-250 z-50 translate-y-1 group-hover:translate-y-0">
                        <div class="dropdown-card rounded-2xl shadow-2xl overflow-hidden backdrop-blur-xl">
                            @forelse ($activeEvents as $event)
                                <a href="{{ route('event.dashboard', $event->slug) }}"
                                    class="flex items-center gap-3 px-5 py-3.5 text-[10px] font-bold uppercase tracking-widest border-b transition-all"
                                    style="color:var(--text-secondary); border-color:var(--border-soft)"
                                    onmouseover="this.style.background='var(--accent-dim)'; this.style.color='var(--accent)'"
                                    onmouseout="this.style.background=''; this.style.color='var(--text-secondary)'">
                                    <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background:var(--accent); box-shadow: 0 0 6px var(--accent)"></span>
                                    {{ $event->name }}
                                </a>
                            @empty
                                <span class="block px-5 py-4 text-[10px] italic" style="color:var(--text-muted)">No Active Events</span>
                            @endforelse
                        </div>
                    </div>
                </div>

                <a href="/schedule" style="color:var(--text-secondary)"
                    onmouseover="this.style.color='var(--accent)'"
                    onmouseout="this.style.color='var(--text-secondary)'"
                    class="transition-colors duration-200">Schedule</a>

                <a href="/contact" style="color:var(--text-secondary)"
                    onmouseover="this.style.color='var(--accent)'"
                    onmouseout="this.style.color='var(--text-secondary)'"
                    class="transition-colors duration-200">Contact</a>
            </div>

            {{-- Right Actions --}}
            <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">

                {{-- Desktop Search --}}
                <div class="hidden lg:block relative w-52 xl:w-60">
                    <form action="{{ route('check.result') }}" method="POST">
                        @csrf
                        <input type="text" name="participant_id"
                            placeholder="Find Seat-plan & Result..."
                            class="nav-input w-full pl-4 pr-9 py-2 rounded-full text-[11px] font-mono">
                        <button type="submit"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 transition-colors"
                            style="color:var(--accent); opacity:0.7"
                            onmouseover="this.style.opacity='1'"
                            onmouseout="this.style.opacity='0.7'">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </button>
                    </form>
                </div>

                {{-- Mobile Search Trigger --}}
                <button id="mobile-search-btn"
                    class="lg:hidden w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl transition-all active:scale-90"
                    style="border:1px solid var(--border-accent); color:var(--accent); background:var(--accent-dim)"
                    aria-label="Search">
                    <i class="fa-solid fa-magnifying-glass text-xs sm:text-sm"></i>
                </button>

                {{-- Theme Toggle --}}
                <button id="theme-toggle"
                    class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl transition-all active:scale-90"
                    style="border:1px solid var(--border-accent); color:var(--accent); background:var(--accent-dim)"
                    aria-label="Toggle theme">
                    <i class="fa-solid fa-moon text-xs sm:text-sm theme-icon-dark"></i>
                    <i class="fa-solid fa-sun text-xs sm:text-sm theme-icon-light"></i>
                </button>

                {{-- Auth --}}
                @auth
                    <div class="relative">
                        <button id="profile-dropdown-btn" class="flex items-center gap-2 outline-none group">
                            <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl flex items-center justify-center transition-all"
                                style="border:1px solid var(--border-accent); color:var(--accent); background:var(--accent-dim)">
                                <i class="fa-solid fa-user-gear text-xs sm:text-sm"></i>
                            </div>
                            <div class="hidden lg:block text-left">
                                <p class="text-[9px] leading-none" style="color:var(--text-muted)">Welcome,</p>
                                <p class="text-[11px] font-bold truncate w-20" style="color:var(--text-primary)">{{ Auth::user()->name }}</p>
                            </div>
                        </button>

                        <div id="profile-menu" class="dropdown-hidden dropdown-card absolute right-0 mt-3 w-56 rounded-2xl backdrop-blur-2xl p-2 z-[100] shadow-2xl">
                            <div class="px-4 py-3 mb-2 lg:hidden" style="border-bottom:1px solid var(--border-soft)">
                                <p class="text-[9px] font-black uppercase tracking-widest" style="color:var(--accent)">Account</p>
                                <p class="text-xs font-bold truncate mt-0.5" style="color:var(--text-primary)">{{ Auth::user()->name }}</p>
                            </div>

                            @php $menuItems = [
                                ['route' => route('dashboard'),        'icon' => 'fa-gauge-high',   'label' => 'Dashboard'],
                            ]; @endphp

                            @if (Auth::user()->role === 'admin')
                                @php array_push($menuItems,
                                    ['route' => route('admin.dashboard'), 'icon' => 'fa-user-shield',  'label' => 'Admin Panel'],
                                    ['route' => url('settings'),          'icon' => 'fa-sliders',      'label' => 'Settings'],
                                    ['route' => url('events'),            'icon' => 'fa-calendar-star','label' => 'Segments'],
                                ); @endphp
                            @endif

                            @foreach ($menuItems as $item)
                                <a href="{{ $item['route'] }}"
                                    class="flex items-center gap-3 px-4 py-3 rounded-xl text-[11px] font-bold uppercase tracking-tight transition-all"
                                    style="color:var(--text-secondary)"
                                    onmouseover="this.style.background='var(--accent-dim)'; this.style.color='var(--text-primary)'"
                                    onmouseout="this.style.background=''; this.style.color='var(--text-secondary)'">
                                    <i class="fa-solid {{ $item['icon'] }} w-4 text-center" style="color:var(--accent)"></i>
                                    {{ $item['label'] }}
                                </a>
                            @endforeach

                            <div class="my-2 mx-2 h-px" style="background:var(--border-soft)"></div>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-[11px] font-bold uppercase tracking-tight transition-all text-red-400"
                                    onmouseover="this.style.background='rgba(239,68,68,0.08)'"
                                    onmouseout="this.style.background=''">
                                    <i class="fa-solid fa-power-off w-4 text-center"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                 @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-2 bg-cyan-500/10 px-4 py-2 rounded-xl border border-cyan-500/20 text-cyan-400 hover:bg-cyan-500 hover:text-slate-950 transition-all shadow-lg active:scale-95">
                        <!-- <i class="fa-solid fa-fingerprint text-sm"></i> -->
                        <span class="text-[10px] font-black uppercase tracking-widest">login</span>
                    </a>
                @endauth

                {{-- Hamburger Menu Button --}}
                <button id="mobile-btn"
                    class="lg:hidden w-8 h-8 sm:w-10 sm:h-10 flex flex-col items-center justify-center gap-[4px] sm:gap-[5px] rounded-xl transition-all focus:outline-none shrink-0"
                    style="background:var(--accent-dim); border:1px solid var(--border-accent)"
                    aria-label="Menu">
                    <span class="w-4 h-[2px] bg-cyan-400 rounded-full transition-all duration-300"></span>
                    <span class="w-3 h-[2px] bg-cyan-400 rounded-full transition-all duration-300 align-self-end" style="margin-right: -2px;"></span>
                    <span class="w-4 h-[2px] bg-cyan-400 rounded-full transition-all duration-300"></span>
                </button>
            </div>
        </div>

        {{-- Mobile Search Panel --}}
        <div id="mobile-search-panel" class="lg:hidden">
            <div class="mx-1 mt-2">
                <div class="search-panel-inner rounded-2xl px-2 py-2 backdrop-blur-xl">
                    <form action="{{ route('check.result') }}" method="POST" class="relative">
                        @csrf
                        <input type="text" name="participant_id" id="mobile-search-input"
                            placeholder="Enter Participant ID to find seat-plan & result..."
                            class="nav-input w-full pl-5 pr-12 py-3.5 rounded-xl text-[11px] font-mono">
                        <button type="submit"
                            class="absolute right-3.5 top-1/2 -translate-y-1/2 w-8 h-8 flex items-center justify-center rounded-lg transition-all"
                            style="background:var(--accent-dim); color:var(--accent)"
                            onmouseover="this.style.background='var(--accent)'; this.style.color='#020617'"
                            onmouseout="this.style.background='var(--accent-dim)'; this.style.color='var(--accent)'">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="menu-closed lg:hidden absolute top-full left-0 w-full mt-2 z-50">
            <div class="mobile-menu-card rounded-[2rem] p-5 backdrop-blur-2xl shadow-2xl mx-1">
                <div class="flex flex-col gap-1">

                    @php
                        $navLinks = [
                            ['href' => '/',        'icon' => 'fa-house-chimney', 'label' => 'Home'],
                            ['href' => '/about',   'icon' => 'fa-circle-info',   'label' => 'About Us'],
                            ['href' => '/schedule','icon' => 'fa-calendar-days', 'label' => 'Schedule'],
                        ];
                    @endphp

                    @foreach ($navLinks as $link)
                        <a href="{{ $link['href'] }}"
                            class="menu-item flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all group">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center transition-all shrink-0"
                                style="background:var(--accent-dim); color:var(--accent)">
                                <i class="fa-solid {{ $link['icon'] }} text-sm"></i>
                            </div>
                            <span class="text-[11px] font-bold tracking-[0.18em] uppercase"
                                style="color:var(--text-primary)">{{ $link['label'] }}</span>
                        </a>
                    @endforeach

                    @if ($activeEvents->count() > 0)
                        <div class="mt-3 px-4 mb-2">
                            <p class="text-[8px] font-black uppercase tracking-[0.35em]"
                                style="color:var(--accent); opacity:0.5">Active Events</p>
                        </div>

                        @foreach ($activeEvents as $event)
                            <a href="{{ route('event.dashboard', $event->slug) }}"
                                class="menu-event-item flex items-center justify-between px-4 py-3.5 rounded-2xl border transition-all group"
                                style="border-color:var(--border-soft)">
                                <div class="flex items-center gap-3">
                                    <span class="w-2 h-2 rounded-full shrink-0"
                                        style="background:var(--accent); box-shadow:0 0 8px var(--accent)"></span>
                                    <span class="text-[10px] font-bold uppercase tracking-widest"
                                        style="color:var(--text-secondary)">{{ $event->name }}</span>
                                </div>
                                <i class="fa-solid fa-chevron-right text-[9px] transition-transform group-hover:translate-x-0.5"
                                    style="color:var(--text-muted)"></i>
                            </a>
                        @endforeach
                    @endif

                    <div class="my-3 mx-2 h-px" style="background:var(--border-accent); opacity:0.4"></div>

                    <a href="/contact"
                        class="menu-item flex items-center gap-4 px-4 py-3.5 rounded-2xl transition-all group">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                            style="background:var(--accent-dim); color:var(--accent)">
                            <i class="fa-solid fa-headset text-sm"></i>
                        </div>
                        <span class="text-[11px] font-bold tracking-[0.18em] uppercase"
                            style="color:var(--text-primary)">Contact</span>
                    </a>

                    <a href="{{ url('cse-gallery') }}"
                        class="mt-2 flex items-center justify-center gap-3 py-4 rounded-2xl font-black text-[11px] uppercase tracking-tight transition-all hover:opacity-90 active:scale-[0.98]"
                        style="background:var(--accent); color:#020617; box-shadow:0 8px 20px rgba(34,211,238,0.3)">
                        <i class="fa-solid fa-id-card-clip"></i>
                        Visual Archive
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ═══════════ MAIN CONTENT ═══════════ --}}
    <main class="pt-24 md:pt-32">
        @yield('content')
    </main>
{{-- ═══════════ FOOTER ═══════════ --}}
<footer class="relative pt-14 pb-28 md:pb-14 overflow-hidden">
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-px"
        style="background: linear-gradient(90deg, transparent, var(--accent), transparent); opacity:0.5"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-16">
        
        {{-- ROW 1: 3 Column Dashboard Layout for Core Content --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10 mb-10">

            {{-- Segments Column --}}
            <div>
                <h4 class="heading-font text-[9px] tracking-[0.3em] uppercase mb-5 font-bold"
                    style="color:var(--accent)">Segments</h4>
                <ul class="space-y-3 text-[11px] font-bold uppercase tracking-wider"
                    style="color:var(--text-secondary)">

                    @forelse ($activeEvents as $event)
                        <li>
                            <a href="{{ route('event.dashboard', $event->slug) }}" 
                               class="transition-all duration-200 hover:opacity-100 flex items-center gap-2 group"
                               style="color:var(--text-secondary)"
                               onmouseover="this.style.color='var(--accent)'; this.style.transform='translateX(4px)'"
                               onmouseout="this.style.color='var(--text-secondary)'; this.style.transform='translateX(0)'">
                               
                               {{-- একটি ছোট গ্লোয়িং ডট আইকন --}}
                               <span class="w-1 h-1 rounded-full bg-current transition-all group-hover:scale-125"></span>
                               
                               {{ $event->name }}
                            </a>
                        </li>
                    @empty
                        <li>
                            <span class="block py-1 text-[10px] italic font-normal normal-case" style="color:var(--text-muted)">
                                No Active Events
                            </span>
                        </li>
                    @endforelse
                </ul>
            </div>

            {{-- Connectivity / Contact Column --}}
            <div>
                <h4 class="heading-font text-[9px] tracking-[0.3em] uppercase mb-5 font-bold"
                    style="color:var(--accent)">Connectivity</h4>
                <ul class="space-y-3 text-[11px]" style="color:var(--text-muted)">
                    
                    {{-- 1. Address --}}
                    <li class="flex items-start">
                        <i class="fa-solid fa-location-dot mr-2 mt-0.5 shrink-0" style="color:var(--accent)"></i>
                        <span>{{ $setting->address ?? 'DUET, Gazipur-1707, Bangladesh' }}</span>
                    </li>

                    {{-- 2. Email --}}
                    <li>
                        <a href="mailto:{{ $setting->email ?? 'carnival.cse@duet.ac.bd' }}" 
                           class="hover:underline transition-all flex items-center" 
                           style="color:var(--text-muted)" 
                           onmouseover="this.style.color='var(--text-primary)'" 
                           onmouseout="this.style.color='var(--text-muted)'">
                            <i class="fa-solid fa-envelope mr-2 shrink-0" style="color:var(--accent)"></i>
                            {{ $setting->email ?? 'info@carnival.cse.duet.ac.bd' }}
                        </a>
                    </li>

                    {{-- 3. Facebook --}}
                    <li>
                        <a href="{{ $setting->fb_link ?? 'https://facebook.com/csecarnivalduet' }}" 
                           target="_blank" rel="noopener noreferrer" 
                           class="hover:underline transition-all flex items-center" 
                           style="color:var(--text-muted)" 
                           onmouseover="this.style.color='var(--text-primary)'" 
                           onmouseout="this.style.color='var(--text-muted)'">
                            <i class="fa-brands fa-facebook mr-2 shrink-0" style="color:var(--accent)"></i>
                            DUET CSE CARNIVAL 
                        </a>
                    </li>

                    {{-- 4. YouTube --}}
                    <!-- <li>
                        <a href="{{ $setting->youtube_link ?? 'https://youtube.com/@duetcsecarnival' }}" 
                           target="_blank" rel="noopener noreferrer" 
                           class="hover:underline transition-all flex items-center" 
                           style="color:var(--text-muted)" 
                           onmouseover="this.style.color='var(--text-primary)'" 
                           onmouseout="this.style.color='var(--text-muted)'">
                            <i class="fa-brands fa-youtube mr-2 shrink-0" style="color:var(--accent)"></i>
                            YouTube Channel
                        </a>
                    </li> -->
                </ul>
            </div>

            {{-- Social Shortcuts & Messaging Support --}}
            <div>
                <h4 class="heading-font text-[9px] tracking-[0.3em] uppercase mb-5 font-bold"
                    style="color:var(--accent)">Instant Channels</h4>
                <div class="space-y-4">
                    <div class="flex gap-2.5">
                        @if ($setting && $setting->fb_link)
                            <a href="{{ $setting->fb_link }}"
                                class="w-8 h-8 rounded-lg flex items-center justify-center transition-all"
                                style="border:1px solid var(--border-accent); color:var(--accent)"
                                onmouseover="this.style.background='#1877f2'; this.style.color='white'; this.style.borderColor='#1877f2'"
                                onmouseout="this.style.background=''; this.style.color='var(--accent)'; this.style.borderColor='var(--border-accent)'">
                                <i class="fa-brands fa-facebook-f text-xs"></i>
                            </a>
                        @endif
                        @if ($setting && $setting->youtube_link)
                            <a href="{{ $setting->youtube_link }}"
                                class="w-8 h-8 rounded-lg flex items-center justify-center transition-all"
                                style="border:1px solid var(--border-accent); color:var(--accent)"
                                onmouseover="this.style.background='#ff0000'; this.style.color='white'; this.style.borderColor='#ff0000'"
                                onmouseout="this.style.background=''; this.style.color='var(--accent)'; this.style.borderColor='var(--border-accent)'">
                                <i class="fa-brands fa-youtube text-xs"></i>
                            </a>
                        @endif
                    </div>
                    
                 
                    
                </div>
            </div>

        </div>

     

        {{-- Bottom Copyright Section --}}
        <div class="pt-8 flex flex-col md:flex-row justify-between items-center gap-3 text-[9px] font-bold uppercase tracking-[0.2em]"
            style="border-top:1px solid var(--border-soft); color:var(--text-muted)">
            <p>&copy; DUET CSE CARNIVAL. All Rights Reserved.</p>
            <p>Developed by <span style="color:var(--accent)">Monoarul Islam</span></p>
        </div>
    </div>
</footer>

{{-- ═══════════ MOBILE BOTTOM TAB BAR ═══════════ --}}
    <div class="lg:hidden fixed bottom-0 left-0 right-0 z-50 px-3 pb-3">
        <nav class="tab-bar rounded-[1.75rem] flex items-center px-2 py-2 bg-slate-950/90 backdrop-blur-md border border-slate-800 shadow-2xl">

            {{-- Home --}}
            <a href="{{ url('/') }}" 
               class="tab-link flex flex-col items-center justify-center flex-1 py-1.5 gap-1 rounded-2xl transition-all {{ request()->is('/') ? 'tab-active text-cyan-400' : 'text-slate-400' }}">
                <i class="fa-solid fa-house-chimney text-[18px]"></i>
                <span class="text-[8px] font-black uppercase tracking-wider">Home</span>
            </a>
{{-- Schedule --}}
<a href="/schedule" class="mobile-link flex flex-col items-center flex-1 text-gray-600 hover:text-blue-600 transition-colors">
    <i class="fa-solid fa-calendar-days text-xl"></i>
    <span class="text-[9px] mt-1 font-bold uppercase tracking-wider">Schedule</span>
</a>

{{-- Gallery --}}
<a href="/cse-gallery" class="mobile-link flex flex-col items-center flex-1 text-gray-600 hover:text-blue-600 transition-colors">
    <i class="fa-solid fa-images text-xl"></i>
    <span class="text-[9px] mt-1 font-bold uppercase tracking-wider">Gallery</span>
</a>

            {{-- Center FAB (Quick Join / Dashboard / Plus) --}}
            

            {{-- About --}}
            <a href="{{ url('/about') }}" 
               class="tab-link flex flex-col items-center justify-center flex-1 py-1.5 gap-1 rounded-2xl transition-all {{ request()->is('about*') ? 'tab-active text-cyan-400' : 'text-slate-400' }}">
                <i class="fa-solid fa-circle-info text-[18px]"></i> {{-- ইনফো/অ্যাবাউট আইকন --}}
                <span class="text-[8px] font-black uppercase tracking-wider">About</span>
            </a>

            {{-- Contact --}}
            <a href="{{ url('/contact') }}" 
               class="tab-link flex flex-col items-center justify-center flex-1 py-1.5 gap-1 rounded-2xl transition-all {{ request()->is('contact*') ? 'tab-active text-cyan-400' : 'text-slate-400' }}">
                <i class="fa-solid fa-address-book text-[18px]"></i> {{-- কন্টাক্ট/যোগাযোগ আইকন --}}
                <span class="text-[8px] font-black uppercase tracking-wider">Contact</span>
            </a>

        </nav>
    </div>

    {{-- ═══════════ SCRIPTS ═══════════ --}}

    {{-- SweetAlert --}}
    <script>
        const customSwal = Swal.mixin({
            background: 'var(--swal-bg)',
            color: 'var(--swal-text)',
            confirmButtonColor: '#06b6d4',
            customClass: {
                popup: 'rounded-3xl border border-cyan-500/20 shadow-2xl backdrop-blur-xl font-mono',
                title: 'heading-font uppercase tracking-widest text-lg',
                confirmButton: 'uppercase tracking-widest font-black text-xs px-10 py-3 rounded-xl',
            }
        });
        @if (session('success'))
            customSwal.fire({ icon:'success', iconColor:'#06b6d4', title:'SUCCESSFUL!', text:"{{ session('success') }}", confirmButtonText:'OK' });
        @endif
        @if (session('error'))
            customSwal.fire({ icon:'error', iconColor:'#ef4444', title:'ERROR!', text:"{{ session('error') }}", confirmButtonText:'TRY AGAIN' });
        @endif
        @if ($errors->any())
            customSwal.fire({ icon:'warning', iconColor:'#eab308', title:'VALIDATION FAILED!',
                html:`<div style="text-align:left;font-size:11px;opacity:.8;text-transform:uppercase;letter-spacing:.05em">• {!! implode('<br>• ', $errors->all()) !!}</div>`,
                confirmButtonText:'GOT IT' });
        @endif
    </script>

    {{-- Core JS --}}
    <script>
    (function () {
        'use strict';

        /* ── Theme ─────────────────────────────────────────── */
        const root     = document.documentElement;
        const themeBtn = document.getElementById('theme-toggle');
        const stored   = localStorage.getItem('cse-theme') || 'dark';
        root.setAttribute('data-theme', stored);

        themeBtn?.addEventListener('click', () => {
            const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            localStorage.setItem('cse-theme', next);
        });

        /* ── Mobile hamburger ──────────────────────────────── */
        const mobileBtn   = document.getElementById('mobile-btn');
        const mobileMenu  = document.getElementById('mobile-menu');
        let menuOpen = false;

        function setMenu(open) {
            menuOpen = open;
            mobileMenu?.classList.toggle('menu-open',   open);
            mobileMenu?.classList.toggle('menu-closed', !open);
            mobileBtn?.classList.toggle('ham-open', open);
        }

        mobileBtn?.addEventListener('click', e => { e.stopPropagation(); setMenu(!menuOpen); });

        /* ── Mobile search ─────────────────────────────────── */
        const searchBtn    = document.getElementById('mobile-search-btn');
        const searchPanel  = document.getElementById('mobile-search-panel');
        const searchInput  = document.getElementById('mobile-search-input');
        let searchOpen = false;

        function setSearch(open) {
            searchOpen = open;
            searchPanel?.classList.toggle('search-open', open);
            if (open) {
                setMenu(false); // close hamburger if open
                setTimeout(() => searchInput?.focus(), 200);
            }
        }

        searchBtn?.addEventListener('click', e => { e.stopPropagation(); setSearch(!searchOpen); });

        /* ── Profile dropdown ──────────────────────────────── */
        const profileBtn  = document.getElementById('profile-dropdown-btn');
        const profileMenu = document.getElementById('profile-menu');
        let dropOpen = false;

        function setDrop(open) {
            dropOpen = open;
            profileMenu?.classList.toggle('dropdown-visible', open);
            profileMenu?.classList.toggle('dropdown-hidden',  !open);
        }

        profileBtn?.addEventListener('click', e => { e.stopPropagation(); setDrop(!dropOpen); });

        /* ── Close all on outside click ────────────────────── */
        document.addEventListener('click', () => {
            setMenu(false);
            setDrop(false);
            setSearch(false);
        });

        /* Stop propagation inside menus so they don't self-close */
        [mobileMenu, profileMenu, searchPanel].forEach(el =>
            el?.addEventListener('click', e => e.stopPropagation())
        );

        /* ── Tab bar active state ──────────────────────────── */
        const tabLinks = document.querySelectorAll('.tab-link');
        const path = window.location.pathname;
        tabLinks.forEach(link => {
            link.classList.remove('tab-active');
            if (link.getAttribute('href') === path ||
                (path !== '/' && link.getAttribute('href') !== '/' && path.startsWith(link.getAttribute('href')))) {
                link.classList.add('tab-active');
            }
        });

        /* ── Prevent pinch zoom ────────────────────────────── */
        document.addEventListener('touchstart', e => {
            if (e.touches.length > 1) e.preventDefault();
        }, { passive: false });
    })();
    </script>

    {{-- Matrix Background --}}
    <script>
    (function () {
        const canvas = document.getElementById('matrix-bg');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let drops = [];
        const letters = "01";
        const fs = 14;

        function resize() {
            canvas.width  = window.innerWidth;
            canvas.height = window.innerHeight;
            drops = Array(Math.ceil(canvas.width / fs)).fill(1);
        }
        window.addEventListener('resize', resize);
        resize();

        let last = 0;
        function draw(ts) {
            if (ts - last >= 45) {
                last = ts;
                const light = document.documentElement.getAttribute('data-theme') === 'light';
                ctx.fillStyle = light ? 'rgba(240,246,255,0.18)' : 'rgba(2,6,23,0.14)';
                ctx.fillRect(0, 0, canvas.width, canvas.height);
                ctx.fillStyle = light ? '#0891b2' : '#22d3ee';
                ctx.font = fs + 'px monospace';
                drops.forEach((y, i) => {
                    ctx.fillText(letters[Math.floor(Math.random() * 2)], i * fs, y * fs);
                    if (y * fs > canvas.height && Math.random() > 0.975) drops[i] = 0;
                    drops[i]++;
                });
            }
            requestAnimationFrame(draw);
        }
        requestAnimationFrame(draw);
    })();
    </script>

    {{-- Preloader --}}
    <script>
    (function () {
        function hide() {
            const el = document.getElementById('preloader');
            if (!el || el.classList.contains('loader-hidden')) return;
            el.classList.add('loader-hidden');
            document.body.classList.remove('loading');
            setTimeout(() => el.remove(), 600);
        }
        window.addEventListener('load', hide);
        setTimeout(hide, 4000);
    })();
    </script>

    @yield('custom_js')
</body>
</html>