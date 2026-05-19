@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-10">
        <div class="max-w-6xl mx-auto">

            {{-- Header --}}
            <div class="mb-8">
                <button onclick="window.history.back()"
                    class="flex items-center gap-2 px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl hover:border-cyan-500 group transition-all mb-4">
                    <i
                        class="fa-solid fa-chevron-left text-cyan-500 group-hover:-translate-x-1 transition-transform text-xs"></i>
                    <span
                        class="text-xs font-bold text-slate-400 group-hover:text-white uppercase tracking-[0.15em]">Back</span>
                </button>
                <div class="text-center">
                    <h1 class="text-2xl sm:text-4xl font-black text-white uppercase tracking-wider">{{ $event->name }}</h1>
                    <p class="text-cyan-400 tracking-widest mt-1 text-xs sm:text-sm italic">Registration Status & List</p>
                </div>
            </div>

            {{-- Search Form --}}
            <form action="{{ url()->current() }}" method="GET" class="mb-6 flex justify-center px-1">
                <div class="relative w-full max-w-xl group">
                    <input type="text" name="search" value="{{ request('search') }}" required
                        placeholder="Team, University or Student ID..."
                        class="w-full bg-slate-900/80 border border-cyan-500/30 rounded-full px-5 sm:px-8 py-3 sm:py-4 text-sm text-white focus:outline-none focus:border-cyan-500 transition-all pr-28 sm:pr-36">
                    <button type="submit"
                        class="absolute right-2 top-1.5 bottom-1.5 sm:top-2 sm:bottom-2 bg-cyan-500 px-4 sm:px-6 rounded-full text-slate-900 font-black hover:scale-105 transition-transform flex items-center gap-1.5 text-sm">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        <span class="hidden sm:inline text-xs font-black uppercase tracking-wider">Search</span>
                    </button>
                </div>
            </form>

            {{-- ডেটা পার্ট: শুধুমাত্র সার্চ করা হলেই কন্টেন্ট দেখাবে --}}
            @if (request('search'))
                <div class="mb-3 px-1 flex items-center justify-between text-slate-400 text-xs">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-filter text-cyan-500"></i>
                        <span>Results for: <strong class="text-cyan-400">"{{ request('search') }}"</strong></span>
                    </div>
                    <a href="{{ url()->current() }}"
                        class="text-red-400 hover:text-red-300 underline underline-offset-2 font-bold uppercase text-[10px] tracking-wider">Clear
                        Search</a>
                </div>

                {{-- টেবিল কন্টেইনার --}}
                <div
                    class="rounded-2xl sm:rounded-3xl border border-cyan-500/20 bg-slate-900/40 backdrop-blur-md shadow-2xl">
                    <div class="overflow-x-auto w-full" style="-webkit-overflow-scrolling: touch;">
                        <table class="text-left border-collapse" style="width: 100%; min-width: 560px;">
                            <thead class="bg-cyan-500/10 text-cyan-400 text-[10px] sm:text-xs uppercase tracking-widest">
                                <tr>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">Team / Participant</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">University</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">Status</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-300 text-sm divide-y divide-cyan-500/10">
                                @forelse ($teams as $team)
                                    <tr class="hover:bg-cyan-500/5 transition duration-200">
                                        <td class="px-4 sm:px-6 py-4">
                                            <div class="font-bold text-white text-sm leading-tight whitespace-nowrap">
                                                {{ $team->team_name ?? $team->m1_name }}
                                            </div>
                                            @if ($team->team_id)
                                                <div class="text-[10px] text-slate-500 mt-0.5 font-mono">
                                                    {{ $team->team_id }}</div>
                                            @endif
                                        </td>
                                        <td class="px-4 sm:px-6 py-4">
                                            <span class="text-slate-300 text-xs whitespace-nowrap">
                                                {{ $team->university_name }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-1 rounded-full text-[9px] uppercase font-black tracking-wider
                                            {{ $team->status === 'verified'
                                                ? 'bg-green-500/20 text-green-400 border border-green-500/30'
                                                : ($team->status === 'selected' || $team->status === 'pre-registered'
                                                    ? 'bg-purple-500/20 text-purple-400 border border-purple-500/30'
                                                    : 'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30') }}">
                                                {{ $team->status }}
                                            </span>
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                            @php $slug = $event->slug; @endphp

                                            @if ($team->status === 'verified')
                                                <span
                                                    class="text-green-500 text-[10px] font-bold uppercase italic whitespace-nowrap">
                                                    <i class="fa-solid fa-circle-check mr-0.5"></i> Confirmed
                                                </span>
                                            @elseif($slug === 'iupc')
                                                <a href="{{ route('iupc.final.reg.form', $team->id) }}"
                                                    class="inline-flex items-center gap-1 bg-cyan-500 text-slate-900 px-3 sm:px-4 py-1.5 rounded-full text-[10px] font-black uppercase hover:scale-105 transition-all whitespace-nowrap">
                                                    <i class="fa-solid fa-pen-to-square text-[9px]"></i> Finalize
                                                </a>
                                            @elseif($slug !== 'project-showcase' && $slug !== 'ai-hackathon')
                                                <a href="{{ route('payment.retry', $team->id) }}"
                                                    class="inline-flex items-center gap-1 bg-green-500 text-slate-900 px-3 sm:px-4 py-1.5 rounded-full text-[10px] font-black uppercase hover:scale-105 transition-all whitespace-nowrap">
                                                    <i class="fa-solid fa-credit-card text-[9px]"></i> Pay
                                                </a>
                                            @else
                                                <span class="text-slate-500 text-[10px] italic uppercase whitespace-nowrap">
                                                    <i class="fa-solid fa-hourglass-start mr-1"></i> In Review
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center">
                                            <div class="text-slate-400 text-sm">
                                                <i
                                                    class="fa-solid fa-magnifying-glass text-slate-600 text-2xl mb-3 block"></i>
                                                No results found for <strong
                                                    class="text-white">"{{ request('search') }}"</strong>
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

                {{-- Pagination --}}
                @if ($teams->hasPages())
                    <div class="mt-6">
                        {{ $teams->appends(request()->input())->links() }}
                    </div>
                @endif
            @else
                {{-- সার্চ করার আগের সুন্দর ফাঁকা স্টেট মেসেজ (Admit Card পেজের মতো) --}}
                <div class="text-center py-20 border-2 border-dashed border-slate-800 rounded-3xl bg-slate-900/10 px-4">
                    <i class="fa-solid fa-magnifying-glass text-5xl text-slate-700 mb-4 block"></i>
                    <h3 class="text-slate-500 text-xs sm:text-sm font-bold uppercase tracking-widest">Enter your Team,
                        University or Student ID to track Registration</h3>
                    <p class="text-slate-600 text-[10px] sm:text-xs mt-2 italic">Search to complete final registration step
                        or proceed to payment.</p>
                </div>
            @endif

        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('event.dashboard', $event->slug) }}"
                class="text-slate-500 hover:text-cyan-400 transition-colors uppercase text-xs font-bold tracking-widest">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Event Details
            </a>
        </div>
    </div>
@endsection
