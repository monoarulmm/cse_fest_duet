@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-6xl mx-auto">

            {{-- ইভেন্ট হেডার --}}
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-white uppercase">{{ $event->name }}</h1>
                {{-- ২. জাভাস্ক্রিপ্ট ব্যাক বাটন (হিস্ট্রি অনুযায়ী কাজ করবে) --}}
                <button onclick="window.history.back()"
                    class="flex items-center gap-3 px-6 py-3 bg-slate-900 border border-slate-700 rounded-xl hover:border-cyan-500 group transition-all">
                    <i class="fa-solid fa-chevron-left text-cyan-500 group-hover:-translate-x-1 transition-transform"></i>
                    <span class="text-xs font-bold text-slate-400 group-hover:text-white uppercase tracking-[0.2em]">
                        Back to previous
                    </span>
                </button>
                <p class="text-cyan-400 tracking-widest mt-1 text-sm italic">Registration Status & List</p>
            </div>

            {{-- ডাইনামিক ট্যাব মেনু উইথ কাউন্ট --}}
            {{-- <div class="flex flex-wrap justify-center gap-4 mb-10">
                <a href="{{ route('event.pre_registered', $event->slug) }}"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ request()->routeIs('event.pre_registered') ? 'bg-cyan-500 text-slate-900 font-bold shadow-[0_0_15px_rgba(34,211,238,0.4)]' : 'bg-slate-900/50 text-cyan-400 border-cyan-500/20 hover:border-cyan-500/50' }}">
                    Pre-Registered ({{ $counts['pre-registered'] ?? 0 }})
                </a>

                <a href="{{ route('event.select_registered', $event->slug) }}"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ request()->routeIs('event.select_registered') ? 'bg-purple-500 text-white font-bold shadow-[0_0_15px_rgba(168,85,247,0.4)]' : 'bg-slate-900/50 text-purple-400 border-purple-500/20 hover:border-purple-500/50' }}">
                    Selected ({{ $counts['selected'] ?? 0 }})
                </a>

                <a href="{{ route('event.final_registered', $event->slug) }}"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 {{ request()->routeIs('event.final_registered') ? 'bg-green-500 text-slate-900 font-bold shadow-[0_0_15px_rgba(34,197,94,0.4)]' : 'bg-slate-900/50 text-green-400 border-green-500/20 hover:border-green-500/50' }}">
                    Final Verified ({{ $counts['verified'] ?? 0 }})
                </a>
            </div> --}}

            {{-- প্রফেশনাল সার্চ বার --}}
            <form action="{{ url()->current() }}" method="GET" class="mb-10 flex justify-center">
                <div class="relative w-full max-w-xl group">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search by Team, University or Student ID"
                        class="w-full bg-slate-900/80 border border-cyan-500/30 rounded-full px-8 py-4 text-white focus:outline-none focus:border-cyan-500 transition-all group-hover:border-cyan-500/60 shadow-xl">
                    <button type="submit"
                        class="absolute right-3 top-2.5 bottom-2.5 bg-cyan-500 px-6 rounded-full text-slate-900 font-black hover:scale-105 transition-transform flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span class="hidden md:inline">SEARCH</span>
                    </button>
                </div>
            </form>

            {{-- ডেটা টেবিল --}}
            <div
                class="form-glass rounded-3xl overflow-hidden shadow-2xl border border-cyan-500/20 bg-slate-900/40 backdrop-blur-md">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-cyan-500/10 text-cyan-400 heading-font text-xs uppercase tracking-widest">
                        <tr>
                            <th class="p-6">Team / Participant</th>
                            <th class="p-6">University / Institute</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Action</th>
                        </tr>
                    </thead>

                    <tbody class="text-slate-300 text-sm">
                        @forelse ($teams as $team)
                            <tr class="border-b border-cyan-500/10 hover:bg-cyan-500/5 transition duration-300">
                                <td class="p-6 font-bold text-white">
                                    {{ $team->team_name ?? $team->m1_name }}
                                </td>
                                <td class="p-6">{{ $team->university_name }}</td>
                                <td class="p-6">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] uppercase font-black 
                    {{ $team->status == 'verified'
                        ? 'bg-green-500/20 text-green-400'
                        : ($team->status == 'selected' || $team->status == 'pre-registered'
                            ? 'bg-purple-500/20 text-purple-400'
                            : 'bg-yellow-500/20 text-yellow-400') }}">
                                        {{ $team->status }}
                                    </span>
                                </td>
                                <td class="p-6 text-right">
                                    @php $slug = $event->slug; @endphp

                                    {{-- ১. যদি অলরেডি ভেরিফাইড (পেইড) থাকে --}}
                                    @if ($team->status == 'verified')
                                        <span
                                            class="text-green-500 text-[10px] font-bold uppercase tracking-tighter italic">
                                            <i class="fa-solid fa-circle-check"></i> Confirmed
                                        </span>

                                        {{-- ২. IUPC এর জন্য ফাইনাল রেজিস্ট্রেশন ফর্ম লিঙ্ক --}}
                                    @elseif($slug === 'iupc')
                                        <a href="{{ route('iupc.final.reg.form', $team->id) }}"
                                            class="inline-block bg-cyan-500 text-slate-900 px-5 py-2 rounded-full text-[10px] font-black uppercase hover:scale-110 hover:shadow-[0_0_15px_rgba(34,211,238,0.6)] transition-all">
                                            <i class="fa-solid fa-pen-to-square mr-1"></i> Finalize Reg
                                        </a>

                                        {{-- ৩. ICT Olympiad এবং অন্যান্য (যেখানে সরাসরি পেমেন্ট হবে) --}}
                                    @elseif($slug !== 'project-showcase' && $slug !== 'ai-hackathon')
                                        <a href="{{ route('payment.retry', $team->id) }}"
                                            class="inline-block bg-green-500 text-slate-900 px-5 py-2 rounded-full text-[10px] font-black uppercase hover:scale-110 hover:shadow-[0_0_15px_rgba(34,197,94,0.6)] transition-all">
                                            <i class="fa-solid fa-credit-card mr-1"></i> Make Payment
                                        </a>

                                        {{-- ৪. Project Showcase এবং AI Hackathon এর জন্য বাটন হাইড থাকবে (ইন রিভিউ মোড) --}}
                                    @else
                                        <span class="text-slate-500 text-[10px] italic uppercase tracking-widest">
                                            <i class="fa-solid fa-hourglass-start mr-1"></i> In Review
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-slate-500 italic">
                                    No records found matching your criteria.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- প্যাজিনেশন --}}
            <div class="mt-8">
                {{ $teams->appends(request()->input())->links() }}
            </div>
        </div>

        {{-- Back Button --}}
        <div class="mt-16 text-center">
            <a href="{{ route('event.dashboard', $event->slug) }}"
                class="text-slate-500 hover:text-green-400 transition-colors uppercase text-sm font-bold tracking-widest">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Event Details
            </a>
        </div>
    </div>
@endsection
