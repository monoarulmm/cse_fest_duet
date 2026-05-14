@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-6xl mx-auto">

            {{-- ইভেন্ট হেডার --}}
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-white uppercase tracking-tight">{{ $event->name }}</h1>
                <div class="flex justify-center items-center gap-3 mt-2">
                    <span class="h-[2px] w-8 bg-green-500"></span>
                    <p class="text-green-400 tracking-[0.2em] text-sm font-bold uppercase">Search & Download Admit Card</p>
                    <span class="h-[2px] w-8 bg-green-500"></span>
                </div>
            </div>

            {{-- প্রফেশনাল সার্চ ফরম --}}
            <form action="{{ url()->current() }}" method="GET" class="mb-10 flex justify-center">
                <div class="relative w-full max-w-xl group">
                    <input type="text" name="search" value="{{ request('search') }}" required
                        placeholder="Enter Participant Name or Registration ID..."
                        class="w-full bg-slate-900/80 border border-green-500/30 rounded-full px-8 py-4 text-white focus:outline-none focus:border-green-500 transition-all shadow-xl">
                    <button type="submit"
                        class="absolute right-3 top-2.5 bottom-2.5 bg-green-500 px-6 rounded-full text-slate-900 font-black hover:scale-105 transition-transform flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span class="hidden md:inline text-[10px]">SEARCH</span>
                    </button>
                </div>
            </form>

            {{-- ডেটা টেবিল: শুধুমাত্র সার্চ রেজাল্ট থাকলে দেখাবে --}}
            @if (request('search'))
                <div
                    class="form-glass rounded-3xl overflow-hidden shadow-2xl border border-green-500/20 bg-slate-900/40 backdrop-blur-md">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-green-500/10 text-green-400 heading-font text-[10px] uppercase tracking-widest">
                            <tr>
                                <th class="p-6">Participant Details</th>
                                <th class="p-6">University</th>
                                <th class="p-6 text-center">Status</th>
                                <th class="p-6 text-center">Download</th>
                                <th class="p-6 text-right">Reg ID</th>
                            </tr>
                        </thead>
                        <tbody class="text-slate-300 text-sm">
                            @forelse ($teams as $team)
                                <tr class="border-b border-green-500/10 hover:bg-green-500/5 transition duration-300">
                                    <td class="p-6">
                                        <div class="font-bold text-white">{{ $team->team_name ?? $team->m1_name }}</div>
                                        <div class="text-[10px] text-slate-500 uppercase mt-1">Confirmed Participant</div>
                                    </td>

                                    <td class="p-6 text-slate-400">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-university text-xs opacity-50"></i>
                                            {{ $team->university_name }}
                                        </div>
                                    </td>

                                    <td class="p-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <div
                                                class="h-2 w-2 rounded-full bg-green-500 animate-pulse shadow-[0_0_10px_#22c55e]">
                                            </div>
                                            <span class="text-green-400 font-black uppercase text-[9px]">Verified</span>
                                        </div>
                                    </td>

                                    <td class="p-6">
                                        <div class="flex justify-center">

                                            <a href="{{ route('event.admit_card', [$event->slug, $team->id]) }}"
                                                target="_blank"
                                                class="flex items-center gap-2 bg-cyan-500/10 hover:bg-cyan-500 border border-cyan-500/30 py-2 px-4 rounded-xl text-[10px] font-bold uppercase text-cyan-400 hover:text-white transition-all group">
                                                <i class="fas fa-download group-hover:bounce"></i> Admit Card
                                            </a>

                                        </div>
                                    </td>

                                    <td class="p-6 text-right font-mono text-cyan-400 font-bold">
                                        <span class="bg-slate-800 px-3 py-1 rounded-lg border border-slate-700">
                                            #{{ str_pad($team->id, 5, '0', STR_PAD_LEFT) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="p-16 text-center">
                                        <div class="flex flex-col items-center opacity-40">
                                            <i class="fa-solid fa-face-frown text-5xl mb-4 text-red-400"></i>
                                            <span class="text-slate-500 italic font-mono uppercase text-xs">No matching
                                                confirmed record found</span>
                                        </div>
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
            @else
                {{-- সার্চ করার আগের মেসেজ --}}
                <div class="text-center py-20 border-2 border-dashed border-slate-800 rounded-3xl">
                    <i class="fa-solid fa-search text-6xl text-slate-700 mb-4"></i>
                    <h3 class="text-slate-500 font-bold uppercase tracking-widest">Enter your name or ID to download Admit
                        Card</h3>
                    <p class="text-slate-600 text-xs mt-2 italic">Only confirmed and paid participants will find their cards
                        here.</p>
                </div>
            @endif

            {{-- Back Button --}}
            <div class="mt-16 text-center">
                <a href="{{ route('event.dashboard', $event->slug) }}"
                    class="text-slate-500 hover:text-green-400 transition-colors uppercase text-sm font-bold tracking-widest">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Back to Event Details
                </a>
            </div>
        </div>
    </div>
@endsection
