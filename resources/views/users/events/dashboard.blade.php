@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-6xl mx-auto">

            {{-- ইভেন্ট হেডার --}}
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-white uppercase">{{ $event->name }}</h1>
                <p class="text-cyan-400 tracking-widest mt-1 text-sm italic">Registration Status & List</p>
            </div>


            {{-- 2. Stats & Tab Section (Modern Counters) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 max-w-6xl mx-auto mb-16 px-4">

                {{-- Pre-registered Card --}}
                <a href="{{ route('event.pre_registered', [$event->slug, 'status' => 'pre-registered']) }}"
                    class="group relative overflow-hidden bg-slate-900/40 border border-slate-800/50 p-6 rounded-[2rem] text-center transition-all duration-300 hover:border-cyan-500/50 hover:shadow-[0_0_20px_rgba(34,211,238,0.15)] hover:-translate-y-1 block">
                    <div class="relative z-10">
                        <h4 class="text-2xl md:text-3xl font-black text-white group-hover:text-cyan-400 transition-colors">
                            {{ $counts['pre-registered'] ?? 0 }}
                        </h4>
                        <p
                            class="text-[9px] uppercase tracking-[0.2em] text-slate-500 font-bold mt-2 group-hover:text-slate-300 transition-colors">
                            Pre-Registered
                        </p>
                    </div>
                    {{-- Background Glow --}}
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-cyan-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                </a>

                {{-- Paid/Verified Card --}}
                <a href="{{ route('event.final_registered', [$event->slug, 'status' => 'verified']) }}"
                    class="group relative overflow-hidden bg-slate-900/40 border border-slate-800/50 p-6 rounded-[2rem] text-center transition-all duration-300 hover:border-green-500/50 hover:shadow-[0_0_20px_rgba(34,197,94,0.15)] hover:-translate-y-1 block">
                    <div class="relative z-10">
                        <h4 class="text-2xl md:text-3xl font-black text-white group-hover:text-green-400 transition-colors">
                            {{ $counts['verified'] ?? 0 }}
                        </h4>
                        <p
                            class="text-[9px] uppercase tracking-[0.2em] text-slate-500 font-bold mt-2 group-hover:text-slate-300 transition-colors">
                            Paid / Verified
                        </p>
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                </a>
                {{-- ICT Olympiad হলে এই কার্ডটি দেখাবে না --}}
                @if ($event->slug !== 'ict-olympiad')
                    {{-- যদি ইভেন্টটি IUPC হয় তবে এটি Slot List হিসেবে কাজ করবে --}}
                    @if ($event->slug == 'iupc')
                        <a href="{{ route('event.slot_list', $event->slug) }}" {{-- এখানে আপনার স্লট লিস্টের রাউট নাম দিন --}}
                            class="group relative overflow-hidden bg-slate-900/40 border border-slate-800/50 p-6 rounded-[2rem] text-center transition-all duration-300 hover:border-blue-500/50 hover:shadow-[0_0_20px_rgba(59,130,246,0.15)] hover:-translate-y-1 block">
                            <div class="relative z-10">
                                <h4
                                    class="text-2xl md:text-3xl font-black text-white group-hover:text-blue-400 transition-colors">
                                    {{ $counts['slots'] ?? 0 }} {{-- স্লট কাউন্ট থাকলে সেটি দেখাবে --}}
                                </h4>
                                <p
                                    class="text-[9px] uppercase tracking-[0.2em] text-slate-500 font-bold mt-2 group-hover:text-slate-300 transition-colors">
                                    Slot List
                                </p>
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                        </a>

                        {{-- অন্য সব ইভেন্টের জন্য আগের 'Selected' কার্ডটি দেখাবে --}}
                    @else
                        <a href="{{ route('event.select_registered', $event->slug) }}"
                            class="group relative overflow-hidden bg-slate-900/40 border border-slate-800/50 p-6 rounded-[2rem] text-center transition-all duration-300 hover:border-purple-500/50 hover:shadow-[0_0_20px_rgba(168,85,247,0.15)] hover:-translate-y-1 block">
                            <div class="relative z-10">
                                <h4
                                    class="text-2xl md:text-3xl font-black text-white group-hover:text-purple-400 transition-colors">
                                    {{ $counts['selected'] ?? 0 }}
                                </h4>
                                <p
                                    class="text-[9px] uppercase tracking-[0.2em] text-slate-500 font-bold mt-2 group-hover:text-slate-300 transition-colors">
                                    Selected
                                </p>
                            </div>
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            </div>
                        </a>
                    @endif
                @endif


                {{-- Institutions Card --}}
                <a href="{{ route('event.institutes', $event->slug) }}"
                    class="group relative overflow-hidden bg-slate-900/40 border border-slate-800/50 p-6 rounded-[2rem] text-center transition-all duration-300 hover:border-amber-500/50 hover:shadow-[0_0_20px_rgba(245,158,11,0.15)] hover:-translate-y-1 block">
                    <div class="relative z-10">
                        <h4 class="text-2xl md:text-3xl font-black text-white group-hover:text-amber-400 transition-colors">
                            {{ $counts['institutes'] ?? 0 }}
                        </h4>
                        <p
                            class="text-[9px] uppercase tracking-[0.2em] text-slate-500 font-bold mt-2 group-hover:text-slate-300 transition-colors">
                            Institutions
                        </p>
                    </div>
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                    </div>
                </a>

            </div>

            {{-- ডাইনামিক ট্যাব মেনু উইথ কাউন্ট --}}
            <div class="flex flex-wrap justify-center gap-4 mb-10">

                @php
                    // ডেডলাইন ওভার হয়েছে কিনা তা চেক করা
                    $isDeadlineOver = $event->end_date && now()->gt($event->end_date);
                @endphp

                @if (!$isDeadlineOver)
                    {{-- রেজিস্ট্রেশন ওপেন থাকলে এই বাটন দেখাবে --}}
                    <a href="{{ route('event.register', $event->slug) }}"
                        class="px-6 py-3 rounded-2xl border transition-all duration-300 
                 {{ request()->routeIs('event.register')
                     ? 'bg-cyan-500 text-slate-900 font-bold shadow-[0_0_15px_rgba(34,211,238,0.4)]'
                     : 'bg-slate-900/50 text-cyan-400 border-cyan-500/20 hover:border-cyan-500/50' }}">
                        <i class="fa-solid fa-pen-to-square mr-2"></i> Registration Form
                    </a>
                @else
                    {{-- ডেডলাইন শেষ হয়ে গেলে এই বাটন দেখাবে --}}
                    <button disabled
                        class="px-6 py-3 rounded-2xl border border-red-500/20 bg-red-500/10 text-red-500 opacity-60 cursor-not-allowed">
                        <i class="fa-solid fa-lock mr-2"></i> Registration Closed
                    </button>
                @endif

                {{-- Rulebook Link --}}
                <a href="{{ $event->rules ?? '#' }}" target="_blank"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ !$event->rules ? 'opacity-50 cursor-not-allowed' : '' }} bg-slate-900/50 text-purple-400 border-purple-500/20 hover:border-purple-500/50 hover:bg-purple-500/10 hover:shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-book-open mr-2"></i> Rulebook
                </a>

                {{-- Result Link --}}
                <a href="{{ $event->result ?? '#' }}" target="_blank"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ !$event->result ? 'opacity-50 cursor-not-allowed' : '' }} bg-slate-900/50 text-purple-400 border-purple-500/20 hover:border-purple-500/50 hover:bg-purple-500/10 hover:shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-square-poll-vertical mr-2"></i> Result
                </a>

                {{-- Seat Plan Link --}}
                <a href="{{ $event->seatplan ?? '#' }}" target="_blank"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ !$event->seatplan ? 'opacity-50 cursor-not-allowed' : '' }} bg-slate-900/50 text-purple-400 border-purple-500/20 hover:border-purple-500/50 hover:bg-purple-500/10 hover:shadow-[0_0_15px_rgba(168,85,247,0.2)]">
                    <i class="fa-solid fa-map-location-dot mr-2"></i> Seat Plan
                </a>

                <a href="{{ route('event.judges', $event->slug) }}"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ request()->routeIs('event.judges') ? 'bg-green-500 text-slate-900 font-bold shadow-[0_0_15px_rgba(34,197,94,0.4)]' : 'bg-slate-900/50 text-green-400 border-green-500/20 hover:border-green-500/50' }}">
                    <i class="fa-solid fa-gavel mr-2"></i> Judge Panel
                </a>
                <a href="{{ route('event.schedule', $event->slug) }}"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ request()->routeIs('event.final_registered') ? 'bg-green-500 text-slate-900 font-bold shadow-[0_0_15px_rgba(34,197,94,0.4)]' : 'bg-slate-900/50 text-green-400 border-green-500/20 hover:border-green-500/50' }}">
                    Schedule
                </a>
            </div>





        </div>
    </div>

    <!-- EVENT STATS & COUNTDOWN SECTION -->
    <!-- EVENT STATS & COUNTDOWN SECTION -->
    <section class="py-12 bg-slate-950/50 border-y border-cyan-500/10">
        <div class="container mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-10 items-center">

            <!-- Live Countdown -->
            <div class="text-center md:text-left">
                <p class="heading-font text-red-500 text-xs font-bold tracking-[0.2em] mb-4 uppercase">
                    Registration Closes In
                </p>
                <div class="flex gap-2 justify-center md:justify-start" id="countdown-wrapper">
                    {{-- Days --}}
                    <div class="flex flex-col items-center">
                        <div id="days"
                            class="w-12 h-12 md:w-14 md:h-14 flex items-center justify-center text-xl font-black text-cyan-400 border border-cyan-500/20 bg-slate-900 rounded-lg shadow-[0_0_15px_rgba(6,182,212,0.1)]">
                            00
                        </div>
                        <span class="text-[7px] mt-2 opacity-50 uppercase tracking-widest">Days</span>
                    </div>
                    {{-- Hours --}}
                    <div class="flex flex-col items-center">
                        <div id="hours"
                            class="w-12 h-12 md:w-14 md:h-14 flex items-center justify-center text-xl font-black text-cyan-400 border border-cyan-500/20 bg-slate-900 rounded-lg shadow-[0_0_15px_rgba(6,182,212,0.1)]">
                            00
                        </div>
                        <span class="text-[7px] mt-2 opacity-50 uppercase tracking-widest">Hours</span>
                    </div>
                    {{-- Mins --}}
                    <div class="flex flex-col items-center">
                        <div id="minutes"
                            class="w-12 h-12 md:w-14 md:h-14 flex items-center justify-center text-xl font-black text-cyan-400 border border-cyan-500/20 bg-slate-900 rounded-lg shadow-[0_0_15px_rgba(6,182,212,0.1)]">
                            00
                        </div>
                        <span class="text-[7px] mt-2 opacity-50 uppercase tracking-widest">Mins</span>
                    </div>
                    {{-- Secs --}}
                    <div class="flex flex-col items-center">
                        <div id="seconds"
                            class="w-12 h-12 md:w-14 md:h-14 flex items-center justify-center text-xl font-black text-red-500 border border-red-500/20 bg-slate-900 rounded-lg shadow-[0_0_15px_rgba(239,68,68,0.1)] animate-pulse">
                            00
                        </div>
                        <span class="text-[7px] mt-2 opacity-50 uppercase tracking-widest">Secs</span>
                    </div>
                </div>
            </div>

            <!-- Total Registered -->
            <div class="flex flex-col items-center">
                <p class="heading-font text-xs font-bold tracking-[0.2em] mb-4 uppercase">Total Registered</p>
                <div
                    class="stat-circle w-28 h-28 rounded-full flex items-center justify-center bg-slate-900 border-2 border-dashed border-cyan-500/30 shadow-[0_0_20px_rgba(6,182,212,0.1)]">
                    <span class="text-3xl font-black text-white italic">{{ number_format($totalRegistered) }}</span>
                </div>
            </div>

            <!-- Event Date -->
            <div class="text-center md:text-right">
                <p class="heading-font text-cyan-500 text-xs font-bold tracking-[0.2em] mb-4 uppercase">Main Event Date</p>
                <div class="inline-block px-8 py-4 border border-red-500/20 bg-slate-900 rounded-xl shadow-lg">
                    <span class="text-2xl md:text-3xl font-black text-red-500 italic uppercase">
                        {{ \Carbon\Carbon::parse($event->end_date)->format('M d, Y') }}
                    </span>
                </div>
            </div>

        </div>
    </section>
    <script>
        (function() {
            const targetDate = new Date("{{ $event->end_date }}").getTime();

            const updateCountdown = () => {
                const now = new Date().getTime();
                const gap = targetDate - now;

                // যদি সময় শেষ হয়ে যায়
                if (gap <= 0) {
                    document.getElementById("countdown-wrapper").innerHTML =
                        "<span class='text-red-500 font-black uppercase tracking-widest animate-bounce'>Registration Closed!</span>";
                    return;
                }

                // সময়ের হিসাব
                const second = 1000;
                const minute = second * 60;
                const hour = minute * 60;
                const day = hour * 24;

                const d = Math.floor(gap / day);
                const h = Math.floor((gap % day) / hour);
                const m = Math.floor((gap % hour) / minute);
                const s = Math.floor((gap % minute) / second);

                // ব্লেড এলিমেন্টে ডেটা পুশ করা (Format: 01, 09 ইত্যাদি)
                document.getElementById("days").innerText = d.toString().padStart(2, '0');
                document.getElementById("hours").innerText = h.toString().padStart(2, '0');
                document.getElementById("minutes").innerText = m.toString().padStart(2, '0');
                document.getElementById("seconds").innerText = s.toString().padStart(2, '0');
            };

            // প্রতি সেকেন্ডে কল হবে
            setInterval(updateCountdown, 1000);
            updateCountdown(); // পেজ লোড হওয়ার সাথে সাথেই একবার রান করবে
        })();
    </script>
@endsection
