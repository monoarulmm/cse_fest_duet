@extends('layouts.app')

@section('custom_css')
<style>
    .search-input {
        background: var(--accent-dim);
        border: 1px solid rgba(34,197,94,.3);
        color: var(--text-primary);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input::placeholder { color: var(--text-muted); }
    .search-input:focus {
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34,197,94,.12);
        outline: none;
    }

    .data-table-wrap {
        background: var(--bg-surface);
        border: 1px solid rgba(34,197,94,.2);
        border-radius: 1.5rem;
    }
    .data-table-head {
        background: rgba(34,197,94,.07);
        color: #22c55e;
        border-bottom: 1px solid rgba(34,197,94,.12);
    }
    .data-table-row {
        border-bottom: 1px solid rgba(34,197,94,.07);
        transition: background 0.15s;
    }
    .data-table-row:last-child { border-bottom: none; }
    .data-table-row:hover { background: rgba(34,197,94,.04); }

    .reg-id-badge {
        background: var(--bg-elevated);
        border: 1px solid var(--border-soft);
        color: var(--accent);
        font-family: 'JetBrains Mono', monospace;
        border-radius: .5rem;
        padding: 2px 10px;
        font-size: 11px;
        font-weight: 700;
    }

    .admit-btn {
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        color: var(--accent);
        transition: background 0.2s, color 0.2s;
        border-radius: .75rem;
        padding: 6px 12px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .admit-btn:hover {
        background: var(--accent);
        color: #020617;
    }

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

        {{-- Back --}}
        <div class="mb-6">
            <button onclick="window.history.back()" class="back-btn flex items-center gap-2 px-4 py-2 rounded-xl">
                <i class="fa-solid fa-chevron-left text-xs" style="color:var(--accent)"></i>
                <span class="text-xs font-bold uppercase tracking-[0.15em]">Back</span>
            </button>
        </div>

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-2xl sm:text-4xl font-black uppercase tracking-wider heading-font"
                style="color:var(--text-primary)">{{ $event->name }}</h1>
            <div class="flex justify-center items-center gap-3 mt-2">
                <span class="h-[2px] w-6 rounded-full opacity-50" style="background:#22c55e"></span>
                <p class="tracking-[0.15em] text-xs sm:text-sm font-bold uppercase" style="color:#22c55e">
                    Search &amp; Download Admit Card
                </p>
                <span class="h-[2px] w-6 rounded-full opacity-50" style="background:#22c55e"></span>
            </div>
        </div>

        {{-- Search --}}
        <form action="{{ url()->current() }}" method="GET" class="mb-6 flex justify-center px-1">
            <div class="relative w-full max-w-xl">
                <input type="text" name="search" value="{{ request('search') }}" required
                    placeholder="Enter Participant ID or Student &amp; Team ID..."
                    class="search-input w-full rounded-full px-5 sm:px-8 py-3 sm:py-4 text-sm pr-28 sm:pr-36 shadow-xl">
                <button type="submit"
                    class="absolute right-2 top-1.5 bottom-1.5 sm:top-2 sm:bottom-2 px-4 sm:px-6 rounded-full
                           font-black hover:opacity-90 transition-all flex items-center gap-1.5 text-sm"
                    style="background:#22c55e; color:#020617">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    <span class="hidden sm:inline text-xs font-black uppercase tracking-wider">Search</span>
                </button>
            </div>
        </form>

        @if (request('search'))
            <div class="data-table-wrap shadow-2xl">
                <div class="overflow-x-auto w-full" style="-webkit-overflow-scrolling:touch">
                    <table class="text-left border-collapse" style="width:100%; min-width:650px">
                        <thead class="data-table-head text-[10px] sm:text-xs uppercase tracking-widest">
                            <tr>
                                <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">Participant Details</th>
                                <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">University / Institute</th>
                                <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold text-center">Status</th>
                                <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold text-center">Download</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm">
                            @forelse ($teams as $team)
                                <tr class="data-table-row">
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <div class="font-bold text-sm" style="color:var(--text-primary)">
                                            {{ $team->team_name ?? $team->m1_name }}
                                        </div>
                                        <div class="text-[10px] uppercase mt-0.5 font-mono"
                                             style="color:var(--text-muted)">Confirmed Participant <b> {{$team->participant_id}}</b></div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs"
                                        style="color:var(--text-secondary)">
                                        <div class="flex items-center gap-2">
                                            <i class="fa-solid fa-university text-[10px] opacity-40"></i>
                                            {{ $team->university_name }}
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center gap-1.5">
                                            <div class="h-2 w-2 rounded-full bg-green-500 animate-pulse
                                                         shadow-[0_0_10px_#22c55e]"></div>
                                            <span class="font-black uppercase text-[9px] tracking-wider"
                                                  style="color:#22c55e">Verified</span>
                                        </div>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('event.admit_card',[$event->slug,$team->id]) }}"
                                           target="_blank" class="admit-btn">
                                            <i class="fas fa-download text-[9px]"></i> Admit Card
                                        </a>
                                    </td>
                                    
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-sm">
                                        <div class="flex flex-col items-center opacity-40">
                                            <i class="fa-solid fa-face-frown text-3xl mb-3 text-red-400"></i>
                                            <span class="font-mono uppercase text-xs"
                                                  style="color:var(--text-muted)">
                                                No matching confirmed record found
                                            </span>
                                        </div>
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
            <div class="empty-state text-center py-16 px-4">
                <i class="fa-solid fa-magnifying-glass text-5xl mb-4 block" style="color:var(--border-mid)"></i>
                <h3 class="text-xs sm:text-sm font-bold uppercase tracking-widest" style="color:var(--text-muted)">
                    Enter your name or ID to download Admit Card
                </h3>
                <p class="text-[10px] sm:text-xs mt-2 italic" style="color:var(--text-muted); opacity:.6">
                    Only confirmed and paid participants will find their cards here.
                </p>
            </div>
        @endif

        <div class="mt-12 text-center">
            <a href="{{ route('event.dashboard',$event->slug) }}"
               class="uppercase text-xs font-bold tracking-widest transition-colors"
               style="color:var(--text-muted)"
               onmouseover="this.style.color='#22c55e'"
               onmouseout="this.style.color='var(--text-muted)'">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Event Details
            </a>
        </div>
    </div>
</div>
@endsection