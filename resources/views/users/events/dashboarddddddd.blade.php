@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-16">

        {{-- 1. Dynamic Header Section --}}
        <div class="text-center mb-16 relative">
            <div class="inline-block px-4 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 mb-4">
                <p class="text-cyan-400 tracking-[0.3em] text-[10px] font-black uppercase">
                    Official Participant Portal
                </p>
            </div>
            <h1 class="text-4xl md:text-6xl font-black text-white uppercase tracking-tighter">
                {{ $event->name }}
            </h1>
            <div class="flex justify-center items-center gap-3 mt-4">
                <span class="w-8 h-px bg-slate-700"></span>
                <p class="text-slate-400 font-mono text-xs uppercase tracking-widest">
                    Status: <span class="text-green-400 animate-pulse">Live Registration</span>
                </p>
                <span class="w-8 h-px bg-slate-700"></span>
            </div>
        </div>

        {{-- 2. Stats & Tab Section (Modern Counters) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 max-w-6xl mx-auto mb-16">
            {{-- Pre-registered Count --}}
            <div
                class="bg-slate-900/50 border border-slate-800 p-6 rounded-[2rem] text-center group hover:border-cyan-500/30 transition-all">
                <h4 class="text-2xl md:text-3xl font-black text-white group-hover:text-cyan-400">
                    {{ $counts['pre-registered'] ?? 0 }}</h4>
                <p class="text-[9px] uppercase tracking-widest text-slate-500 font-bold mt-1">Pre-Registered</p>
            </div>

            {{-- Paid/Verified Count --}}
            <div
                class="bg-slate-900/50 border border-slate-800 p-6 rounded-[2rem] text-center group hover:border-green-500/30 transition-all">
                <h4 class="text-2xl md:text-3xl font-black text-white group-hover:text-green-400">
                    {{ $counts['verified'] ?? 0 }}</h4>
                <p class="text-[9px] uppercase tracking-widest text-slate-500 font-bold mt-1">Paid / Verified</p>
            </div>

            {{-- Number of Institutes --}}
            <div
                class="bg-slate-900/50 border border-slate-800 p-6 rounded-[2rem] text-center group hover:border-purple-500/30 transition-all">
                <h4 class="text-2xl md:text-3xl font-black text-white group-hover:text-purple-400">
                    {{ $counts['institutes'] ?? 0 }}</h4>
                <p class="text-[9px] uppercase tracking-widest text-slate-500 font-bold mt-1">Institutions</p>
            </div>

            {{-- Search/Portal Link --}}
            <a href="{{ route('event.pre_registered', $event->slug) }}"
                class="bg-cyan-500 p-6 rounded-[2rem] text-center group hover:bg-cyan-400 transition-all shadow-[0_0_20px_rgba(34,211,238,0.2)]">
                <i class="fas fa-search text-slate-900 text-xl mb-1"></i>
                <p class="text-[9px] uppercase tracking-widest text-slate-900 font-black">Search List</p>
            </a>
        </div>

        {{-- 3. Dynamic Menu Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">

            {{-- Common: Registration Form (Always First) --}}
            <a href="{{ route('event.register', $event->slug) }}" class="menu-card group">
                <div class="icon-box"><i class="fas fa-file-signature"></i></div>
                <div class="card-content">
                    <span>Pre-Registration</span>
                    <small>Submit Application</small>
                </div>
            </a>

            {{-- Conditional: IUPC Specific --}}
            @if ($event->slug == 'iupc')
                <a href="#" class="menu-card group !border-blue-500/20">
                    <div class="icon-box !bg-blue-500/10 !text-blue-400"><i class="fas fa-gavel"></i></div>
                    <div class="card-content">
                        <span>Problem Setters</span>
                        <small>Judges Introduction</small>
                    </div>
                </a>
                <a href="{{ route('event.final_registered', $event->slug) }}" class="menu-card group !border-green-500/20">
                    <div class="icon-box !bg-green-500/10 !text-green-400"><i class="fas fa-trophy"></i></div>
                    <div class="card-content">
                        <span>Selected Teams</span>
                        <small>Finalists List</small>
                    </div>
                </a>

                {{-- Conditional: Project Showcasing --}}
            @elseif($event->slug == 'project-showcase')
                <a href="#" class="menu-card group !border-yellow-500/20">
                    <div class="icon-box !bg-yellow-500/10 !text-yellow-400"><i class="fas fa-chalkboard-teacher"></i></div>
                    <div class="card-content">
                        <span>Showcase Judges</span>
                        <small>Expert Panel</small>
                    </div>
                </a>
                <a href="{{ route('event.final_registered', $event->slug) }}" class="menu-card group !border-indigo-500/20">
                    <div class="icon-box !bg-indigo-500/10 !text-indigo-400"><i class="fas fa-project-diagram"></i></div>
                    <div class="card-content">
                        <span>Selected Projects</span>
                        <small>Final Selection</small>
                    </div>
                </a>

                {{-- Conditional: Hackathon --}}
            @elseif($event->slug == 'hackathon')
                <a href="#" class="menu-card group !border-red-500/20">
                    <div class="icon-box !bg-red-500/10 !text-red-400"><i class="fas fa-user-ninja"></i></div>
                    <div class="card-content">
                        <span>Mentors & Judges</span>
                        <small>Introduction</small>
                    </div>
                </a>

                {{-- Conditional: Olympiad --}}
            @elseif($event->slug == 'ict-olympiad')
                <a href="{{ route('event.final_registered', $event->slug) }}"
                    class="menu-card group !border-emerald-500/20">
                    <div class="icon-box !bg-emerald-500/10 !text-emerald-400"><i class="fas fa-user-check"></i></div>
                    <div class="card-content">
                        <span>Paid Participants</span>
                        <small>Verified List</small>
                    </div>
                </a>
                <a href="#" class="menu-card group !border-orange-500/20">
                    <div class="icon-box !bg-orange-500/10 !text-orange-400"><i class="fas fa-microchip"></i></div>
                    <div class="card-content">
                        <span>Olympiad Judges</span>
                        <small>Panelists</small>
                    </div>
                </a>
            @endif

            {{-- Common: Rulebook --}}
            <a href="#" class="menu-card group">
                <div class="icon-box !bg-slate-800/50 text-slate-400"><i class="fas fa-book-open"></i></div>
                <div class="card-content">
                    <span>Rulebook</span>
                    <small>Syllabus & Guidelines</small>
                </div>
            </a>
        </div>
    </div>

    <style>
        .menu-card {
            @apply relative overflow-hidden flex items-center p-6 bg-slate-900/40 border border-slate-800 rounded-[2.2rem] transition-all duration-500 backdrop-blur-2xl hover:-translate-y-2 hover:bg-slate-800/90 hover:shadow-[0_20px_40px_rgba(0, 0, 0, 0.4)] hover:border-cyan-500/30;
        }

        .icon-box {
            @apply flex items-center justify-center min-w-[4.5rem] min-h-[4.5rem] bg-cyan-500/10 text-cyan-400 text-2xl rounded-2xl transition-all duration-700 group-hover:scale-110 group-hover:rotate-[10deg];
        }

        .card-content {
            @apply ml-5 flex flex-col overflow-hidden;
        }

        .card-content span {
            @apply text-white font-black uppercase text-sm md:text-base tracking-tight truncate group-hover:text-cyan-400 transition-colors;
        }

        .card-content small {
            @apply text-slate-500 text-[10px] uppercase font-bold tracking-widest mt-1;
        }
    </style>
@endsection
