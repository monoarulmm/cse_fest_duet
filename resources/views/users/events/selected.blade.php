@extends('layouts.app')

@section('custom_css')
<style>
    /* ── Search input ─────────────────────────────────────── */
    .search-input {
        background: var(--accent-dim);
        border: 1px solid rgba(168,85,247,.3);
        color: var(--text-primary);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .search-input::placeholder { color: var(--text-muted); }
    .search-input:focus {
        border-color: #a855f7;
        box-shadow: 0 0 0 3px rgba(168,85,247,.15);
        outline: none;
    }

    /* ── Table wrapper ────────────────────────────────────── */
    .data-table-wrap {
        background: var(--bg-surface);
        border: 1px solid rgba(168,85,247,.2);
        border-radius: 1.5rem;
    }
    .data-table-head {
        background: rgba(168,85,247,.08);
        color: #a855f7;
        border-bottom: 1px solid rgba(168,85,247,.12);
    }
    .data-table-row {
        border-bottom: 1px solid rgba(168,85,247,.08);
        transition: background 0.15s;
    }
    .data-table-row:last-child { border-bottom: none; }
    .data-table-row:hover { background: rgba(168,85,247,.05); }

    /* ── Empty / search-prompt state ─────────────────────── */
    .empty-state {
        border: 2px dashed var(--border-mid);
        background: var(--bg-elevated);
        border-radius: 1.5rem;
    }

    /* ── Back btn ─────────────────────────────────────────── */
    .back-btn {
        background: var(--accent-dim);
        border: 1px solid var(--border-accent);
        color: var(--text-secondary);
        transition: border-color 0.2s, color 0.2s;
    }
    .back-btn:hover { border-color: var(--accent); color: var(--text-primary); }

    /* ── Coupon modal ─────────────────────────────────────── */
    .coupon-modal-card {
        background: var(--bg-surface);
        border: 1px solid rgba(34,211,238,.25);
        box-shadow: 0 0 50px rgba(34,211,238,.12);
    }
    .coupon-input {
        background: var(--bg-elevated);
        border: 1px solid var(--border-accent);
        color: var(--text-primary);
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .coupon-input::placeholder { color: var(--text-muted); }
    .coupon-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-dim);
        outline: none;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-3 sm:px-4 py-6 sm:py-10">
    <div class="max-w-6xl mx-auto">

        {{-- Header --}}
        <div class="mb-8 text-center">
            <h1 class="text-2xl sm:text-4xl font-black uppercase tracking-wider heading-font"
                style="color:var(--text-primary)">{{ $event->name }}</h1>
            <p class="tracking-[0.2em] mt-1 text-xs sm:text-sm font-bold uppercase"
               style="color:#a855f7">Teams for Onsite Competition </p>
        </div>

        {{-- Back --}}
        <div class="mb-6">
            <button onclick="window.history.back()"
                class="back-btn flex items-center gap-2 px-4 py-2 rounded-xl">
                <i class="fa-solid fa-chevron-left text-xs transition-transform group-hover:-translate-x-1"
                   style="color:var(--accent)"></i>
                <span class="text-xs font-bold uppercase tracking-[0.15em]">Back</span>
            </button>
        </div>

        {{-- Search --}}
        <form action="{{ url()->current() }}" method="GET" class="mb-6 flex justify-center px-1">
            <div class="relative w-full max-w-xl">
                <input type="text" name="search" value="{{ request('search') }}" required
                    placeholder="Search Selected Team or Student ID..."
                    class="search-input w-full rounded-full px-5 sm:px-8 py-3 sm:py-4 text-sm pr-28 sm:pr-36 shadow-xl">
                <button type="submit"
                    class="absolute right-2 top-1.5 bottom-1.5 sm:top-2 sm:bottom-2 px-4 sm:px-6 rounded-full
                           font-black hover:opacity-90 transition-all flex items-center gap-1.5 text-sm"
                    style="background:#a855f7; color:#fff">
                    <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    <span class="hidden sm:inline text-xs font-black uppercase tracking-wider">Search</span>
                </button>
            </div>
        </form>

        @if (request('search'))
            {{-- Filter meta --}}
            <div class="mb-3 px-1 flex items-center justify-between text-xs"
                 style="color:var(--text-muted)">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-filter" style="color:#a855f7"></i>
                    <span>Results for: <strong style="color:#a855f7">"{{ request('search') }}"</strong></span>
                </div>
                <a href="{{ url()->current() }}"
                   class="underline underline-offset-2 font-bold uppercase text-[10px] tracking-wider"
                   style="color:#f87171">Clear Search</a>
            </div>

            {{-- Table --}}
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
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold"
                                        style="color:var(--text-primary)">
                                           {{ $team->team_name ?? $team->m1_name }} <br>
                                            <p>    {{ $team->team_id ?? $team->student_id }}</p>                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs"
                                        style="color:var(--text-secondary)">
                                        {{ $team->university_name }}
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded-full text-[9px] uppercase font-black tracking-wider
                                                     bg-green-500/20 text-green-400 border border-green-500/30">
                                            {{ $team->status }}
                                        </span>
                                    </td>
                                    <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                        @if ($event->slug === 'iupc')
                                            <button onclick="openCouponModal('{{ $team->team_name ?? $team->m1_name }}')"
                                                class="px-3 sm:px-4 py-1.5 rounded-full text-[10px] font-black
                                                       uppercase hover:opacity-90 transition-all"
                                                style="background:var(--accent); color:#020617">
                                                Verify Coupon
                                            </button>
                                        @else
                                            <a href="{{ route('event.final_reg_direct',[$event->slug,$team->id]) }}"
                                                class="inline-flex items-center px-3 sm:px-4 py-1.5 rounded-full
                                                       text-[10px] font-black uppercase hover:opacity-90 transition-all"
                                                style="background:#22c55e; color:#020617">
                                                Make Payment
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-12 text-center text-sm"
                                        style="color:var(--text-muted)">
                                        <i class="fa-solid fa-magnifying-glass text-2xl mb-3 block opacity-30"></i>
                                        No selected teams found for
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
                    Enter your Team Name or Student ID to Check Selection Status
                </h3>
                <p class="text-[10px] sm:text-xs mt-2 italic" style="color:var(--text-muted); opacity:.6">
                    Search to check if your team is selected and proceed with the next steps.
                </p>
            </div>
        @endif
    </div>
</div>

{{-- Coupon Modal --}}
<div id="couponModal"
     class="fixed inset-0 z-[100] hidden items-center justify-center px-4"
     style="background:rgba(2,6,23,.85); backdrop-filter:blur(8px)">
    <div class="w-full max-w-md">
        <div class="coupon-modal-card rounded-[2.5rem] overflow-hidden">
            {{-- Modal header --}}
            <div class="p-6 text-center" style="background:var(--accent)">
                <h3 class="font-black uppercase tracking-tighter text-xl" style="color:#020617">
                    Finalize Registration
                </h3>
                <p class="text-[10px] font-bold uppercase tracking-widest mt-1" style="color:rgba(2,6,23,.6)">
                    Verify your team with a coupon code
                </p>
            </div>
            {{-- Modal body --}}
            <div class="p-8">
                <p class="text-sm text-center mb-6" style="color:var(--text-secondary)">
                    Team: <span id="modalTeamName" class="font-bold italic" style="color:var(--text-primary)"></span>
                </p>
                <form id="couponForm" action="{{ url('event.verify_coupon',$event->slug) }}" method="POST">
                    @csrf
                    <input type="hidden" name="team_name" id="hiddenTeamName">
                    <input type="text" name="coupon_code" required placeholder="ENTER COUPON CODE"
                        class="coupon-input w-full rounded-2xl px-6 py-4 text-center font-mono
                               tracking-[0.3em] uppercase placeholder:opacity-40">
                    <div class="mt-8 flex flex-col gap-3">
                        <button type="submit"
                            class="w-full font-black py-4 rounded-2xl uppercase tracking-widest
                                   hover:opacity-90 transition-all active:scale-[.98]"
                            style="background:var(--accent); color:#020617;
                                   box-shadow:0 10px 20px rgba(34,211,238,.25)">
                            Verify & Confirm
                        </button>
                        <button type="button" onclick="closeCouponModal()"
                            class="w-full py-3 rounded-2xl uppercase text-[10px] font-bold transition-colors"
                            style="color:var(--text-muted)">
                            Maybe Later
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('custom_js')
<script>
function openCouponModal(teamName) {
    document.getElementById('modalTeamName').innerText = teamName;
    document.getElementById('hiddenTeamName').value   = teamName;
    const m = document.getElementById('couponModal');
    m.classList.remove('hidden');
    m.classList.add('flex');
}
function closeCouponModal() {
    const m = document.getElementById('couponModal');
    m.classList.add('hidden');
    m.classList.remove('flex');
}
window.addEventListener('click', e => {
    if (e.target === document.getElementById('couponModal')) closeCouponModal();
});
</script>
@endsection