@extends('layouts.app')

@section('custom_css')
<style>
    .search-input {
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        color: var(--text-primary);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input::placeholder { color: var(--text-muted); }
    .search-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-dim);
        outline: none;
    }

    .data-table-wrap {
        background: var(--bg-surface);
        border: 1px solid var(--accent-border);
        border-radius: 1.5rem;
    }
    .data-table-head {
        background: var(--accent-dim);
        color: var(--accent);
        border-bottom: 1px solid var(--accent-border);
    }
    .data-table-row {
        border-bottom: 1px solid var(--border-soft);
        transition: background 0.15s;
    }
    .data-table-row:last-child { border-bottom: none; }
    .data-table-row:hover { background: var(--accent-dim); }

    .empty-state {
        border: 2px dashed var(--border-mid);
        background: var(--bg-elevated);
        border-radius: 1.5rem;
    }

    .back-btn {
        background: var(--accent-dim);
        border: 1px solid var(--border-accent);
        color: var(--text-secondary);
        transition: border-color 0.2s, color 0.2s;
    }
    .back-btn:hover { border-color: var(--accent); color: var(--text-primary); }
</style>
@endsection

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-10">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="mb-8">
            <button onclick="window.history.back()" class="back-btn flex items-center gap-2 px-4 py-2 rounded-xl mb-4">
                <i class="fa-solid fa-chevron-left text-xs" style="color:var(--accent)"></i>
                <span class="text-xs font-bold uppercase tracking-[0.15em]">Back</span>
            </button>
            <div class="text-center">
                <h1 class="text-2xl sm:text-4xl font-black uppercase tracking-wider heading-font"
                    style="color:var(--text-primary)">{{ $event->name }}</h1>
                <p class="tracking-widest mt-1 text-xs sm:text-sm italic"
                   style="color:var(--accent)">Registration Status &amp; List</p>
            </div>
        </div>

        {{-- Search --}}
        <form action="{{ url()->current() }}" method="GET" class="mb-6 flex justify-center px-1">
            <div class="relative w-full max-w-xl">
                <input type="text" name="search" value="{{ request('search') }}" required
                    placeholder="Team, University or Student ID..."
                    class="search-input w-full rounded-full px-5 sm:px-8 py-3 sm:py-4 text-sm pr-28 sm:pr-36">
                <button type="submit"
                    class="absolute right-2 top-1.5 bottom-1.5 sm:top-2 sm:bottom-2 px-4 sm:px-6 rounded-full
                           font-black hover:opacity-90 transition-all flex items-center gap-1.5 text-sm"
                    style="background:var(--accent); color:#020617">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    <span class="hidden sm:inline text-xs font-black uppercase tracking-wider">Search</span>
                </button>
            </div>
        </form>

        @if (request('search'))
            <div class="mb-3 px-1 flex items-center justify-between text-xs" style="color:var(--text-muted)">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-filter" style="color:var(--accent)"></i>
                    <span>Results for: <strong style="color:var(--accent)">"{{ request('search') }}"</strong></span>
                </div>
                <a href="{{ url()->current() }}"
                   class="underline underline-offset-2 font-bold uppercase text-[10px] tracking-wider"
                   style="color:#f87171">Clear Search</a>
            </div>

            <div class="data-table-wrap shadow-2xl">
                <div class="overflow-x-auto w-full" style="-webkit-overflow-scrolling:touch">
                    <table class="text-left border-collapse" style="width:100%; min-width:560px">
                        <thead class="data-table-head text-[10px] sm:text-xs uppercase tracking-widest">
                            <tr>
                                <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">Team / Participant</th>
                                <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">University / Institute</th>
                                <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">Status</th>
                                <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse ($teams as $team)
                                <tr class="data-table-row">
                                    <td class="px-4 sm:px-6 py-4">
                                        <div class="font-bold text-sm leading-tight whitespace-nowrap"
                                             style="color:var(--text-primary)">
                                            {{ $team->team_name ?? $team->m1_name }} <br>
                                            <p>    {{ $team->team_id ?? $team->student_id }}</p>
                                        </div>
                                        @if ($team->team_id)
                                            <div class="text-[10px] font-mono mt-0.5" style="color:var(--text-muted)">
                                                {{ $team->team_id }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-4 sm:px-6 py-4">
                                        <span class="text-xs whitespace-nowrap" style="color:var(--text-secondary)">
                                            {{ $team->university_name }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-[9px] uppercase font-black tracking-wider
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
                                            <span class="text-[10px] font-bold uppercase italic"
                                                  style="color:#22c55e">
                                                <i class="fa-solid fa-circle-check mr-0.5"></i> Confirmed
                                            </span>
                                        @elseif($slug === 'iupc')
                                            <a href="{{ route('iupc.final.reg.form',$team->id) }}"
                                                class="inline-flex items-center gap-1 px-3 sm:px-4 py-1.5 rounded-full
                                                       text-[10px] font-black uppercase hover:opacity-90 transition-all whitespace-nowrap"
                                                style="background:var(--accent); color:#020617">
                                                <i class="fa-solid fa-pen-to-square text-[9px]"></i> Finalize
                                            </a>
                                        @elseif($slug !== 'project-showcase' && $slug !== 'ai-hackathon')
                                            <a href="{{ route('payment.retry',$team->id) }}"
                                                class="inline-flex items-center gap-1 px-3 sm:px-4 py-1.5 rounded-full
                                                       text-[10px] font-black uppercase hover:opacity-90 transition-all whitespace-nowrap"
                                                style="background:#22c55e; color:#020617">
                                                <i class="fa-solid fa-credit-card text-[9px]"></i> Pay
                                            </a>
                                        @else
                                            <span class="text-[10px] italic uppercase whitespace-nowrap"
                                                  style="color:var(--text-muted)">
                                                <i class="fa-solid fa-hourglass-start mr-1"></i> In Review
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-sm" style="color:var(--text-muted)">
                                        <i class="fa-solid fa-magnifying-glass text-2xl mb-3 block opacity-30"></i>
                                        No results found for
                                        <strong style="color:var(--text-primary)">"{{ request('search') }}"</strong>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            @if ($teams->count() > 0)
                <p class="sm:hidden text-center text-[10px] mt-2 italic" style="color:var(--text-muted)">
                    <i class="fa-solid fa-arrows-left-right mr-1"></i> বাম/ডানে স্ক্রল করুন
                </p>
            @endif

            @if ($teams->hasPages())
                <div class="mt-6">{{ $teams->appends(request()->input())->links() }}</div>
            @endif

        @else
            <div class="empty-state text-center py-20 px-4">
                <i class="fa-solid fa-magnifying-glass text-5xl mb-4 block" style="color:var(--border-mid)"></i>
                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-widest" style="color:var(--text-muted)">
                    Enter your Team, University or Student ID to track Registration
                </h3>
                <p class="text-[10px] sm:text-xs mt-2 italic" style="color:var(--text-muted); opacity:.6">
                    Search to complete final registration step or proceed to payment.
                </p>
            </div>
        @endif

        <div class="mt-10 text-center">
            <a href="{{ route('event.dashboard',$event->slug) }}"
               class="uppercase text-xs font-bold tracking-widest transition-colors"
               style="color:var(--text-muted)"
               onmouseover="this.style.color='var(--accent)'"
               onmouseout="this.style.color='var(--text-muted)'">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Event Details
            </a>
        </div>
    </div>
</div>
@endsection