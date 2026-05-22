@extends('layouts.app')

@section('content')
    <section class="py-20 container mx-auto px-6" x-data="{ activeDay: 1 }">
        <!-- Section Header -->
        <div
            class="mb-16 border-l-4 border-cyan-500 pl-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
            <div>
                <h2 class="heading-font text-4xl md:text-6xl font-black  tracking-tighter">
                    Event <span
                        class="text-cyan-500 text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 tracking-[4px]"   letter-spacing: 4px; >Timeline</span>
                </h2>
                <p class="text-slate-500 font-mono text-sm mt-2 tracking-widest italic">*Tentative Schedule. The event sequence and time may alter.</p>
            </div>

            <!-- Day Selector Tabs -->
            <!-- Day Selector Tabs -->
<div class="flex p-1  border border-slate-800 rounded-lg shadow-xl max-w-md mx-auto">
    <button @click="activeDay = 1"
        :class="activeDay === 1 ? 
            'bg-cyan-500 text-black shadow-[0_0_15px_rgba(6,182,212,0.4)] font-extrabold' : 
            'text-slate-400 hover:text-cyan-400 hover:bg-slate-800/60'"
        class="flex-1 cursor-pointer px-4 py-2.5 text-[11px] uppercase tracking-widest transition-all duration-300 rounded-md font-bold">
        26 June (Day 01)
    </button>
    
    <button @click="activeDay = 2"
        :class="activeDay === 2 ? 
            'bg-cyan-500 text-black shadow-[0_0_15px_rgba(6,182,212,0.4)] font-extrabold' : 
            'text-slate-400 hover:text-cyan-400 hover:bg-slate-800/60'"
        class="flex-1 cursor-pointer px-4 py-2.5 text-[11px] uppercase tracking-widest transition-all duration-300 rounded-md font-bold">
        27 June (Day 02)
    </button>
</div>
        </div>

        <!-- Pre-Event Special Mention (AI Hackathon Preliminary) -->
        <div class="mb-12  border border-cyan-500/20 p-4 rounded-lg flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="px-3 py-1 bg-cyan-500/10  text-[10px] font-bold rounded border border-cyan-500/20">
                    PRE-EVENT</div>
                <p class="text-slate-400 text-sm italic"><span class="text-cyan-500 font-bold">15 June 2026:</span> AI
                    Hackathon Preliminary Contest on Kaggle (Virtual Platform)</p>
            </div>
            <span class="text-[10px] text-slate-600 font-mono hidden md:block">STATUS: COMPLETED_OR_UPCOMING</span>
        </div>

        <!-- TIMELINE CONTAINER -->
        <div class="relative ml-4 md:ml-10 border-l border-slate-800/50">

            <!-- DAY 01: 26 JUNE -->
            <div x-show="activeDay === 1" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-x-4">

                <!-- ICT Olympiad & Showcasing Reporting -->
                <div class="relative pl-8 pb-12 group">
                    <div
                        class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full  group-hover:bg-cyan-500 transition-colors">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <span class=" font-black text-xl tracking-tighter">08:00 AM</span>
                            <p class="text-[10px] text-slate-500 font-mono">REPORTING_STAGE</p>
                        </div>
                        <div class="md:col-span-3">
                            <div class=" border border-slate-800 p-6 rounded-xl">
                                <h3 class=" font-bold uppercase tracking-wider mb-2 text-lg">Morning Reporting
                                </h3>
                                <p class="text-slate-400 text-sm italic">Reporting for ICT Olympiad & Project Showcasing
                                    participants.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ICT Olympiad Exam -->
                <div class="relative pl-8 pb-12 group">
                    <div
                        class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-slate-700 group-hover:bg-blue-500 transition-colors">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <span class="text-blue-500 font-black text-xl tracking-tighter">09:00 AM</span>
                            <p class="text-[10px] text-slate-500 font-mono uppercase">Exam Session</p>
                        </div>
                        <div class="md:col-span-3">
                            <div
                                class=" border border-blue-500/10 p-6 rounded-xl border-l-4 border-l-blue-500">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class=" font-bold uppercase tracking-wider text-xl">ICT Olympiad Exam</h3>
                                   
                                </div>
                                <p class="text-slate-400 text-sm">Main examination for the ICT Olympiad segment (09:00 AM –
                                    09:30 AM).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Demonstration -->
                <div class="relative pl-8 pb-12 group">
                    <div
                        class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-slate-700 group-hover:bg-cyan-500 transition-colors">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <span class=" font-black text-xl tracking-tighter">09:00 AM</span>
                            <p class="text-[10px] text-slate-500 font-mono uppercase">Morning Session</p>
                        </div>
                        <div class="md:col-span-3">
                            <div
                                class=" border border-slate-800 p-6 rounded-xl border-l-4 border-l-cyan-500">
                                <h3 class=" font-bold uppercase tracking-wider text-xl mb-2">Project Showcasing                                </h3>
                                <p class="text-slate-400 text-sm">Interactive project showcase and judging (Initial Session
                                    until 01:00 PM).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Demonstration (Continued) -->
                <div class="relative pl-8 pb-12 group">
                    <div
                        class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-slate-700 group-hover:bg-cyan-500 transition-colors">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <span class=" font-black text-xl tracking-tighter">02:30 PM</span>
                            <p class="text-[10px] text-slate-500 font-mono uppercase">Afternoon Session</p>
                        </div>
                        <div class="md:col-span-3">
                            <div
                                class=" border border-slate-800 p-6 rounded-xl border-l-4 border-l-cyan-500">
                                <h3 class=" font-bold uppercase tracking-wider text-xl mb-2">Project Showcasing                                    (Contd.)</h3>
                                <p class="text-slate-400 text-sm">Continued showcase and final evaluation (02:30 PM – 03:30
                                    PM).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Programming Mock Contest -->
                <div class="relative pl-8 pb-12 group">
                    <div
                        class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-slate-700 group-hover:bg-red-500 transition-colors">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <span class="text-red-500 font-black text-xl tracking-tighter">03:00 PM</span>
                            <p class="text-[10px] text-slate-500 font-mono uppercase">Mock Test</p>
                        </div>
                        <div class="md:col-span-3">
                            <div class="bg-red-500/5 border border-red-500/20 p-6 rounded-xl border-l-4 border-l-red-500">
                                <h3 class=" font-bold uppercase tracking-wider text-xl mb-2">IUPC Mock Contest
                                </h3>
                                <p class="text-slate-400 text-sm">Environment testing and practice contest for programming
                                    participants (03:00 PM – 05:00 PM).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DAY 02: 27 JUNE -->
            <div x-show="activeDay === 2" x-transition:enter="transition ease-out duration-500"
                x-transition:enter-start="opacity-0 translate-x-4">

                <!-- Morning Reporting -->
                <div class="relative pl-8 pb-12 group">
                    <div
                        class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-slate-700 group-hover:bg-cyan-500 transition-colors">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <span class=" font-black text-xl tracking-tighter">07:30 AM</span>
                            <p class="text-[10px] text-slate-500 font-mono uppercase">REPORTING STAGE</p>
                        </div>
                        <div class="md:col-span-3">
                            <div class=" border border-slate-800 p-6 rounded-xl">
                                <h3 class=" font-bold uppercase tracking-wider mb-2">IUPC & AI Hackathon Reporting
                                </h3>
                                <p class="text-slate-400 text-sm italic">Reporting for Day 02 main segments. AI Hackathon
                                    reporting starts at 08:00 AM.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- IUPC Main Contest -->
                <div class="relative pl-8 pb-12 group">
                    <div
                        class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-slate-700 group-hover:bg-red-500 transition-colors">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <span class="text-red-500 font-black text-xl tracking-tighter">08:30 AM</span>
                            <p class="text-[10px] text-slate-500 font-mono uppercase">Main Contest</p>
                        </div>
                        <div class="md:col-span-3">
                            <div class="bg-red-500/5 border border-red-500/10 p-6 rounded-xl border-l-4 border-l-red-500">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class=" font-bold uppercase tracking-wider text-xl">IUPC Main Contest</h3>
                                    <span
                                        class="bg-red-500/10 text-red-500 text-[9px] px-2 py-1 font-bold rounded tracking-tighter italic font-mono">MISSION_CP_ACTIVE</span>
                                </div>
                                <p class="text-slate-400 text-sm">The ultimate programming showdown starts now (08:30 AM –
                                    01:30 PM).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- AI Hackathon Defense -->
                <div class="relative pl-8 pb-12 group">
                    <div
                        class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-slate-700 group-hover:bg-purple-500 transition-colors">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <span class="text-purple-400 font-black text-xl tracking-tighter">09:00 AM</span>
                            <p class="text-[10px] text-slate-500 font-mono uppercase">Defense Session</p>
                        </div>
                        <div class="md:col-span-3">
                            <div
                                class=" border border-purple-500/10 p-6 rounded-xl border-l-4 border-l-purple-500">
                                <h3 class=" font-bold uppercase tracking-wider text-xl mb-2">AI Hackathon:
                                    Presentation & Defense</h3>
                                <p class="text-slate-400 text-sm">Participants defend their AI solutions to the jury panel
                                    (09:00 AM – 01:30 PM).</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Prize Giving Ceremony -->
                <div class="relative pl-8 pb-12 group">
                    <div
                        class="absolute -left-[5px] top-2 h-2.5 w-2.5 rounded-full bg-slate-700 group-hover:bg-yellow-500 transition-colors shadow-[0_0_15px_rgba(234,179,8,0.3)]">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="md:col-span-1">
                            <span class="text-yellow-500 font-black text-xl tracking-tighter">05:00 PM</span>
                            <p class="text-[10px] text-slate-500 font-mono uppercase">Closing Cerenony</p>
                        </div>
                        <div class="md:col-span-3">
                            <div
                                class="bg-yellow-500/5 border border-yellow-500/20 p-6 rounded-xl hover:border-yellow-500/40 transition-all">
                                <h3 class="text-yellow-500 font-black uppercase tracking-[0.2em] mb-2 text-xl"> Prize
                                    Giving and Closing Ceremony</h3>
                                <p class="text-slate-400 text-sm">The official closing of DUET CSE Carnival 2026 and
                                    awarding the Winners.</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
