@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-10">
        <div class="max-w-6xl mx-auto">

            {{-- ব্যাক বাটন --}}
            <div class="mb-6">
                <button onclick="window.history.back()"
                    class="flex items-center gap-2 px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl hover:border-cyan-500 group transition-all">
                    <i
                        class="fa-solid fa-chevron-left text-cyan-500 group-hover:-translate-x-1 transition-transform text-xs"></i>
                    <span
                        class="text-xs font-bold text-slate-400 group-hover:text-white uppercase tracking-[0.15em]">Back</span>
                </button>
            </div>

            {{-- ইভেন্ট হেডার --}}
            <div class="text-center mb-8">
                <h1 class="text-2xl sm:text-4xl font-black text-white uppercase tracking-wider">{{ $event->name }}</h1>
                <div class="flex justify-center items-center gap-3 mt-2">
                    <span class="h-[2px] w-6 bg-green-500 opacity-50"></span>
                    <p class="text-green-400 tracking-[0.15em] text-xs sm:text-sm font-bold uppercase">Search & Download
                        Admit Card</p>
                    <span class="h-[2px] w-6 bg-green-500 opacity-50"></span>
                </div>
            </div>

            {{-- প্রফেশনাল সার্চ ফরম --}}
            <form action="{{ url()->current() }}" method="GET" class="mb-6 flex justify-center px-1">
                <div class="relative w-full max-w-xl group">
                    <input type="text" name="search" value="{{ request('search') }}" required
                        placeholder="Enter Participant ID or Student & Team ID..."
                        class="w-full bg-slate-900/80 border border-green-500/30 rounded-full px-5 sm:px-8 py-3 sm:py-4 text-sm text-white focus:outline-none focus:border-green-500 transition-all pr-28 sm:pr-36 shadow-xl">
                    <button type="submit"
                        class="absolute right-2 top-1.5 bottom-1.5 sm:top-2 sm:bottom-2 bg-green-500 px-4 sm:px-6 rounded-full text-slate-900 font-black hover:scale-105 transition-transform flex items-center gap-1.5 text-sm">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        <span class="hidden sm:inline text-xs font-black uppercase tracking-wider">Search</span>
                    </button>
                </div>
            </form>

            {{-- ডেটা টেবিল: শুধুমাত্র সার্চ রেজাল্ট থাকলে দেখাবে --}}
            @if (request('search'))
                {{-- ✅ FIX: রেসপন্সিভ মোবাইল স্ক্রলবার লেআউট স্ট্রাকচার --}}
                <div
                    class="rounded-2xl sm:rounded-3xl border border-green-500/20 bg-slate-900/40 backdrop-blur-md shadow-2xl">
                    <div class="overflow-x-auto w-full" style="-webkit-overflow-scrolling: touch;">
                        <table class="text-left border-collapse" style="width: 100%; min-width: 650px;">
                            <thead class="bg-green-500/10 text-green-400 text-[10px] sm:text-xs uppercase tracking-widest">
                                <tr>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">Participant Details</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">University</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold text-center">Status</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold text-center">Download</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold text-right">Reg ID</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-300 text-sm divide-y divide-green-500/10">
                                @forelse ($teams as $team)
                                    <tr class="hover:bg-green-500/5 transition duration-200">
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <div class="font-bold text-white text-sm">
                                                {{ $team->team_name ?? $team->m1_name }}</div>
                                            <div class="text-[10px] text-slate-500 uppercase mt-0.5 font-mono">Confirmed
                                                Participant</div>
                                        </td>

                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs text-slate-400">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-university text-[10px] opacity-50"></i>
                                                {{ $team->university_name }}
                                            </div>
                                        </td>

                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                <div
                                                    class="h-2 w-2 rounded-full bg-green-500 animate-pulse shadow-[0_0_10px_#22c55e]">
                                                </div>
                                                <span
                                                    class="text-green-400 font-black uppercase text-[9px] tracking-wider">Verified</span>
                                            </div>
                                        </td>

                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                            <div class="flex justify-center">
                                                <a href="{{ route('event.admit_card', [$event->slug, $team->id]) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center gap-1.5 bg-cyan-500/10 hover:bg-cyan-500 border border-cyan-500/30 py-1.5 px-3 rounded-xl text-[10px] font-bold uppercase text-cyan-400 hover:text-white transition-all group">
                                                    <i class="fas fa-download text-[9px]"></i> Admit Card
                                                </a>
                                            </div>
                                        </td>

                                        <td
                                            class="px-4 sm:px-6 py-4 whitespace-nowrap text-right font-mono text-cyan-400 font-bold text-xs">
                                            <span class="bg-slate-800 px-2.5 py-1 rounded-lg border border-slate-700">
                                                #{{ str_pad($team->id, 5, '0', STR_PAD_LEFT) }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic text-sm">
                                            <div class="flex flex-col items-center opacity-40">
                                                <i class="fa-solid fa-face-frown text-3xl mb-3 text-red-400"></i>
                                                <span class="font-mono uppercase text-xs">No matching confirmed record
                                                    found</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Scroll hint — only mobile --}}
                @if ($teams->count() > 0)
                    <p class="sm:hidden text-center text-slate-600 text-[10px] mt-2 italic">
                        <i class="fa-solid fa-arrows-left-right mr-1"></i> বাম/ডানে স্ক্রল করুন
                    </p>
                @endif

                {{-- প্যাজিনেশন --}}
                @if ($teams->hasPages())
                    <div class="mt-6">
                        {{ $teams->appends(request()->input())->links() }}
                    </div>
                @endif
            @else
                {{-- সার্চ করার আগের মেসেজ --}}
                <div class="text-center py-16 px-4 border-2 border-dashed border-slate-800 rounded-3xl bg-slate-900/10">
                    <i class="fa-solid fa-magnifying-glass text-5xl text-slate-700 mb-4 block"></i>
                    <h3 class="text-slate-500 text-xs sm:text-sm font-bold uppercase tracking-widest">Enter your name or ID
                        to download Admit Card</h3>
                    <p class="text-slate-600 text-[10px] sm:text-xs mt-2 italic">Only confirmed and paid participants will
                        find their cards here.</p>
                </div>
            @endif

            {{-- Back Button --}}
            <div class="mt-12 text-center">
                <a href="{{ route('event.dashboard', $event->slug) }}"
                    class="text-slate-500 hover:text-green-400 transition-colors uppercase text-xs font-bold tracking-widest">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Event Details
                </a>
            </div>
        </div>
    </div>
@endsection
