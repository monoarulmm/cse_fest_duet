@extends('layouts.app')

@section('content')
    <div class="min-h-screen py-20 px-4 bg-[#0a0f1a]"> <!-- আপনার থিমের ব্যাকগ্রাউন্ড কালার -->
        <div class="max-w-2xl mx-auto">

            <!-- Header Section -->
            <div class="text-center mb-12">
                <div class="inline-block px-4 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 mb-4">
                    <span class="text-[10px] text-cyan-500 font-bold uppercase tracking-[0.2em]">Live Results</span>
                </div>
                <h1 class="text-5xl font-black text-white uppercase tracking-tighter mb-3">
                    Event <span class="text-cyan-500">Results</span>
                </h1>
                <p class="text-slate-500 text-xs font-medium tracking-[0.3em] uppercase">DUET CSE FEST 2026 • Season II</p>
            </div>

            <!-- Main Search Card -->
            <div class="max-w-xl mx-auto mt-10 animate-in fade-in zoom-in duration-700">
                <!-- Main Result Card -->
                <div class="relative group">
                    <!-- Outer Glow Effect -->
                    <div
                        class="absolute -inset-1 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-[2.5rem] blur opacity-20 group-hover:opacity-40 transition duration-1000">
                    </div>

                    <div class="relative bg-[#0d1117] border border-white/10 rounded-[2.5rem] overflow-hidden shadow-2xl">
                        <!-- Top Banner: University & Icon -->
                        <div class="bg-gradient-to-r from-cyan-500/10 via-transparent to-transparent p-8 pb-4">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="w-2 h-2 rounded-full bg-cyan-500 shadow-[0_0_8px_rgba(6,182,212,0.8)]"></span>
                                        <p class="text-[9px] font-black text-cyan-500 uppercase tracking-[0.3em]">Official
                                            Result Card</p>
                                    </div>
                                    <h2 class="text-white font-black text-xs uppercase tracking-widest opacity-80 mb-1">
                                        {{ $result->university_name ?? 'Dhaka University of Engineering & Technology' }}
                                    </h2>
                                    <h1 class="text-2xl font-black text-white uppercase tracking-tighter">Event <span
                                            class="text-cyan-500">Outcome</span></h1>
                                </div>
                                <div
                                    class="w-14 h-14 rounded-2xl bg-slate-950/50 border border-white/5 flex items-center justify-center backdrop-blur-md shadow-inner">
                                    <i class="fa-solid fa-trophy text-cyan-500 text-xl"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Content Body -->
                        <div class="p-8 pt-2 space-y-5">
                            <!-- Primary Info: Team Name -->
                            <div
                                class="bg-slate-950/40 p-6 rounded-[1.5rem] border border-white/5 text-center relative overflow-hidden group">
                                <div
                                    class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-cyan-500/50 to-transparent">
                                </div>
                                <span class="text-[8px] text-slate-500 font-bold uppercase tracking-[0.4em] block mb-2">Team
                                    / Member Name</span>
                                <h3
                                    class="text-white font-black text-xl uppercase tracking-tight group-hover:text-cyan-400 transition-colors">
                                    {{ $result->team_name ?? 'N/A' }}
                                </h3>
                            </div>

                            <!-- Info Grid: Participant ID & Event Name -->
                            <div class="grid grid-cols-2 gap-4">
                                <div
                                    class="bg-slate-950/50 p-5 rounded-3xl border border-white/5 transition-transform hover:scale-[1.02]">
                                    <span
                                        class="text-[8px] text-slate-500 font-bold uppercase tracking-widest block mb-1">Participant
                                        ID</span>
                                    <span
                                        class="text-cyan-500 font-black text-sm tracking-widest">{{ $result->participant_id }}</span>
                                </div>
                                <div
                                    class="bg-slate-950/50 p-5 rounded-3xl border border-white/5 transition-transform hover:scale-[1.02]">
                                    <span
                                        class="text-[8px] text-slate-500 font-bold uppercase tracking-widest block mb-1">Target
                                        Event</span>
                                    <span
                                        class="text-white font-bold text-sm tracking-tight">{{ $result->event_name }}</span>
                                </div>
                            </div>

                            <!-- Status Section -->
                            <div
                                class="relative p-8 rounded-[2.5rem] bg-slate-950/80 border border-white/5 text-center overflow-hidden shadow-inner">
                                <div class="absolute -top-10 -left-10 w-24 h-24 bg-cyan-500/5 rounded-full blur-3xl"></div>
                                <div class="absolute -bottom-10 -right-10 w-24 h-24 bg-blue-500/5 rounded-full blur-3xl">
                                </div>

                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-[0.3em] mb-4">Performance
                                    Status</p>

                                <div
                                    class="inline-flex items-center gap-4 px-12 py-4 rounded-full bg-cyan-500 text-slate-950 shadow-[0_10px_30px_rgba(6,182,212,0.3)] transform transition hover:scale-105 active:scale-95 duration-300">
                                    <i class="fa-solid fa-award text-lg"></i>
                                    <span
                                        class="font-black uppercase tracking-[0.2em] text-sm italic">{{ $result->result_status }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer -->
                        <div class="px-8 py-5 bg-slate-950/90 border-t border-white/5 flex justify-between items-center">
                            <div class="flex items-center gap-3">
                                <div class="flex -space-x-1">
                                    <div class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></div>
                                    <div class="w-1.5 h-1.5 rounded-full bg-cyan-400 opacity-50"></div>
                                </div>
                                <span
                                    class="text-[8px] text-slate-500 font-bold uppercase tracking-[0.2em] italic font-mono">Digital
                                    Certificate Generated by DUET CSE</span>
                            </div>
                            <div class="flex items-center gap-4">
                                <button onclick="window.print()"
                                    class="text-slate-600 hover:text-cyan-500 transition-all transform hover:rotate-12">
                                    <i class="fa-solid fa-print text-sm"></i>
                                </button>
                                <button class="text-slate-600 hover:text-cyan-500 transition-all">
                                    <i class="fa-solid fa-share-nodes text-sm"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Footer Note -->
            <p class="mt-12 text-center text-slate-600 text-[10px] font-bold uppercase tracking-widest">
                Developed for DUET CSE Department
            </p>
        </div>
    </div>
@endsection
