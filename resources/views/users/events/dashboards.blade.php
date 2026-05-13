@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-16">

        {{-- Header Section --}}
        <div class="text-center mb-16 relative">
            <div class="absolute inset-x-0 -top-10 flex justify-center -z-10">
                <div class="w-64 h-64 bg-cyan-500/10 blur-[120px] rounded-full"></div>
            </div>

            <h1
                class="text-5xl md:text-7xl font-black text-white uppercase tracking-tighter drop-shadow-[0_10px_20px_rgba(34,211,238,0.3)]">
                {{ $event->name }}
            </h1>

            <div class="flex justify-center items-center gap-4 mt-6">
                <span class="h-[2px] w-12 md:w-16 bg-gradient-to-r from-transparent to-cyan-500"></span>
                <p class="text-cyan-400 tracking-[0.4em] uppercase text-[10px] md:text-sm font-black">
                    Official Participant Portal
                </p>
                <span class="h-[2px] w-12 md:w-16 bg-gradient-to-l from-transparent to-cyan-500"></span>
            </div>
        </div>

        {{-- Menu Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-7xl mx-auto">

            {{-- ১. Registration Form --}}
            <a href="{{ route('event.register', $event->slug) }}" class="menu-card group">
                <div class="icon-box">
                    <i class="fas fa-id-badge"></i>
                </div>
                <div class="card-content">
                    <span>Registration Form</span>
                    <small>Join the Competition</small>
                </div>
                <div class="arrow-icon">
                    <i class="fas fa-chevron-right"></i>
                </div>
            </a>

            {{-- ২. Conditional Menu: ICT Olympiad (Direct Final & Admit) --}}
            @if ($event->slug == 'ict-olympiad')
                {{-- Registered Participants List --}}
                <a href="{{ route('event.final_registered', $event->slug) }}"
                    class="menu-card group !border-green-500/20 hover:!border-green-500/50">
                    <div class="icon-box !bg-green-500/10 !text-green-400">
                        <i class="fas fa-user-check"></i>
                    </div>
                    <div class="card-content">
                        <span>Verified Participants</span>
                        <div class="status-badge bg-green-500/10 text-green-400 border border-green-500/20">
                            {{ $counts['verified'] ?? 0 }} Confirmed
                        </div>
                    </div>
                </a>

                {{-- Download Admit Card --}}
                <a href="#"
                    class="menu-card group !border-orange-500/30 hover:!border-orange-500/60 shadow-[0_0_20px_rgba(249,115,22,0.05)]">
                    <div class="icon-box !bg-orange-500/10 !text-orange-400">
                        <i class="fas fa-download"></i>
                    </div>
                    <div class="card-content">
                        <span>Download Admit</span>
                        <small class="text-orange-500/70 italic font-black">Available for Paid Users</small>
                    </div>
                </a>
            @else
                {{-- ডাইনামিক ট্যাব মেনু (কাউন্ট সহ) --}}
                <div class="flex flex-wrap justify-center gap-4 mb-10">
                    <a href="{{ route('event.pre_registered', $event->slug) }}"
                        class="px-6 py-3 rounded-2xl border transition-all duration-300 bg-slate-900/50 text-cyan-400 border-cyan-500/20 hover:border-cyan-500/50">
                        Pre-Registered ({{ $counts['pending'] ?? 0 }})
                    </a>

                    <a href="{{ route('event.select_registered', $event->slug) }}"
                        class="px-6 py-3 rounded-2xl border transition-all duration-300 bg-slate-900/50 text-purple-400 border-purple-500/20 hover:border-purple-500/50">
                        Selected ({{ $counts['selected'] ?? 0 }})
                    </a>

                    <a href="{{ route('event.final_registered', $event->slug) }}"
                        class="px-6 py-3 rounded-2xl border transition-all duration-300 bg-green-600 text-white font-bold shadow-[0_0_20px_rgba(34,197,94,0.4)]">
                        Final Verified ({{ $counts['verified'] ?? 0 }})
                    </a>
                </div>
            @endif



            {{-- ৩. Common Menus --}}
            <a href="#" class="menu-card group">
                <div class="icon-box !bg-slate-800/50 text-slate-400">
                    <i class="fas fa-book-open"></i>
                </div>
                <div class="card-content">
                    <span>Rulebook</span>
                    <small>Guide & Syllabus</small>
                </div>
            </a>

            <a href="#" class="menu-card group !border-pink-500/20 hover:!border-pink-500/50">
                <div class="icon-box !bg-pink-500/10 text-pink-400">
                    <i class="fas fa-headset"></i>
                </div>
                <div class="card-content">
                    <span>Support Desk</span>
                    <small>Queries & Help</small>
                </div>
            </a>

        </div>
    </div>

    <style>
        .menu-card {
            @apply relative overflow-hidden flex items-center p-8 bg-slate-900/40 border border-slate-700/40 rounded-[2.8rem] transition-all duration-500 backdrop-blur-2xl hover:-translate-y-3 hover:bg-slate-800/90 hover:shadow-[0_30px_60px_rgba(0, 0, 0, 0.6)] hover:border-cyan-500/30;
        }

        .icon-box {
            @apply flex items-center justify-center min-w-[5.5rem] min-h-[5.5rem] bg-cyan-500/10 text-cyan-400 text-3xl rounded-[1.8rem] transition-all duration-700 group-hover:scale-110 group-hover:rotate-[12deg] group-hover:shadow-[0_0_30px_rgba(34, 211, 238, 0.2)];
        }

        .card-content {
            @apply ml-6 flex flex-col overflow-hidden;
        }

        .card-content span {
            @apply text-white font-black uppercase text-base md:text-lg tracking-tight truncate group-hover:text-cyan-400 transition-colors;
        }

        .card-content small {
            @apply text-slate-500 text-[11px] uppercase font-bold tracking-[0.15em] mt-2;
        }

        .status-badge {
            @apply mt-3 px-5 py-2 rounded-xl text-[10px] font-black uppercase inline-block w-fit tracking-wider transition-all duration-500 group-hover:bg-opacity-20;
        }

        .arrow-icon {
            @apply absolute right-8 text-slate-700 opacity-0 group-hover:opacity-100 group-hover:text-cyan-500 transition-all duration-500 translate-x-4 group-hover:translate-x-0;
        }

        /* Animated Glow Effect */
        .menu-card::before {
            content: '';
            @apply absolute -inset-full w-full h-full bg-gradient-to-r from-transparent via-cyan-500/5 to-transparent transition-all duration-1000 rotate-45;
        }

        .menu-card:hover::before {
            @apply inset-full;
        }
    </style>
@endsection
