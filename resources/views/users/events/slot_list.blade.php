@extends('layouts.app')

@section('custom_css')
<style>
    .slots-hero {
        background: var(--bg-surface);
        border: 1px solid var(--border-accent);
        border-radius: 2.5rem;
        padding: 2rem 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-card);
    }
    .slots-hero::before {
        content: '';
        position: absolute;
        top: -6rem;
        right: -6rem;
        width: 24rem;
        height: 24rem;
        background: var(--accent-dim);
        border-radius: 50%;
        filter: blur(80px);
        pointer-events: none;
    }
    .stat-chip {
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        border-radius: 1rem;
        padding: 0.75rem 1.75rem;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.04);
    }
    .hero-icon-box {
        width: 3.5rem;
        height: 3.5rem;
        border-radius: 1rem;
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .filter-select,
    .filter-input {
        width: 100%;
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        color: var(--text-primary);
        border-radius: 0.75rem;
        padding: 1rem;
        font-size: 0.875rem;
        outline: none;
        appearance: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: 'JetBrains Mono', monospace;
    }
    .filter-select:focus,
    .filter-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px var(--accent-dim);
    }
    .filter-select option {
        background: var(--bg-surface);
        color: var(--text-primary);
    }
    .filter-input::placeholder { color: var(--text-muted); }
    .filter-label {
        font-size: 0.625rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        color: var(--text-muted);
        display: block;
        margin-bottom: 0.625rem;
        margin-left: 0.25rem;
    }
    .filter-search-icon {
        position: absolute;
        left: 1.25rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-muted);
        pointer-events: none;
        transition: color 0.2s;
    }
    .filter-search-wrap:focus-within .filter-search-icon { color: var(--accent); }
    .refresh-btn {
        width: 100%;
        height: 54px;
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        color: var(--text-secondary);
        border-radius: 0.75rem;
        font-size: 0.625rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s, color 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
    }
    .refresh-btn:hover {
        border-color: var(--accent-border);
        background: var(--accent-dim);
        color: var(--accent);
    }
    .slots-table-wrap {
        background: var(--bg-surface);
        border: 1px solid var(--border-accent);
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: var(--shadow-card);
    }
    .slots-table-head {
        background: var(--accent);
        display: flex;
        align-items: center;
        padding: 1.25rem 2.5rem;
    }
    .slots-table-head span {
        color: #020617;
        font-weight: 900;
        font-size: 0.6875rem;
        text-transform: uppercase;
        letter-spacing: 0.4em;
        font-style: italic;
    }
    .slot-item {
        display: flex;
        align-items: center;
        border-bottom: 1px solid var(--border-soft);
        transition: background 0.2s;
        animation: cyberReveal 0.5s cubic-bezier(0.23, 1, 0.32, 1) both;
    }
    .slot-item:last-child { border-bottom: none; }
    .slot-item:hover { background: var(--accent-dim); }
    .slot-name {
        flex: 1;
        padding: 2rem 2.5rem;
        font-weight: 700;
        font-size: 1.125rem;
        color: var(--text-primary);
        transition: color 0.2s;
    }
    .slot-item:hover .slot-name { color: var(--accent); }
    .slot-count-col {
        width: 10rem;
        padding: 1.5rem;
        display: flex;
        justify-content: center;
        border-left: 1px solid var(--border-soft);
    }
    .slot-count-badge {
        height: 4rem;
        width: 5rem;
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .slot-item:hover .slot-count-badge {
        border-color: var(--accent-border);
        box-shadow: 0 0 15px var(--accent-dim);
    }
    .slot-count-badge span {
        color: var(--accent);
        font-family: 'JetBrains Mono', monospace;
        font-weight: 900;
        font-size: 1.5rem;
        letter-spacing: -0.05em;
    }
    .empty-state {
        text-align: center;
        padding: 10rem 2rem;
        background: var(--bg-surface);
        border: 1px dashed var(--border-accent);
        border-radius: 2rem;
    }
    .back-btn {
        height: 3.5rem;
        padding: 0 2rem;
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: border-color 0.2s, background 0.2s;
        cursor: pointer;
        text-decoration: none;
    }
    .back-btn:hover {
        border-color: var(--accent-border);
        background: var(--accent-dim);
    }
    @keyframes cyberReveal {
        from { opacity: 0; transform: translateY(12px); filter: blur(6px); }
        to   { opacity: 1; transform: translateY(0);    filter: blur(0);   }
    }
    .animate-spin-slow { animation: spin 4s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
</style>
@endsection

@section('content')
<div class="min-h-screen py-12 px-4 md:px-8">
    <div class="max-w-7xl mx-auto space-y-8">

        {{-- Hero / Control Center --}}
        <div class="slots-hero">
            <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-8">
                <div class="flex items-center gap-5">
                    <div class="hero-icon-box">
                        <i class="fas fa-university text-xl" style="color:var(--accent)"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl md:text-4xl font-black uppercase tracking-tighter italic" style="color:var(--text-primary)">
                            Institution <span style="color:var(--accent)">Slots</span>
                        </h2>
                        <p class="text-[10px] uppercase font-mono tracking-[0.4em] flex items-center gap-2 mt-1"
                            style="color:rgb(52,211,153)">
                            <span class="h-1.5 w-1.5 rounded-full animate-pulse" style="background:rgb(52,211,153); box-shadow:0 0 8px #10b981"></span>
                            To be Declared later
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="stat-chip">
                        <p class="text-[9px] font-black uppercase tracking-widest mb-1" style="color:var(--text-muted)">Total Institutes</p>
                        <p class="font-mono text-2xl font-bold leading-none" style="color:var(--accent)">
                            <span id="totalCount">{{ $universitySlots->flatten()->count() }}</span>
                        </p>
                    </div>
                    <button onclick="window.history.back()" class="back-btn group">
                        <i class="fa-solid fa-arrow-left text-sm group-hover:-translate-x-1 transition-transform" style="color:var(--accent)"></i>
                        <span class="text-[10px] font-black uppercase tracking-widest" style="color:var(--text-secondary)">Return</span>
                    </button>
                </div>
            </div>

            {{-- Filters --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="filter-label">Order / Priority</label>
                    <select id="sortOrder" class="filter-select">
                        <option value="asc">Alphabetical (A–Z)</option>
                        <option value="desc">Alphabetical (Z–A)</option>
                        <option value="quota-high">Capacity Priority</option>
                    </select>
                </div>
                <div>
                    <label class="filter-label">Global Query</label>
                    <div class="relative filter-search-wrap">
                        <input type="text" id="searchInput"
                            placeholder="Search by Institute Name..."
                            class="filter-input pl-12">
                    </div>
                </div>
                <div class="flex items-end">
                    <button onclick="window.location.reload()" class="refresh-btn">
                        <i class="fas fa-sync-alt animate-spin-slow"></i> Refresh System
                    </button>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="slots-table-wrap">
            <div class="slots-table-head">
                <span class="flex-1">Institute</span>
                <span class="w-40 text-center border-l" style="border-color:rgba(2,6,23,0.2)">No. of Slots</span>
            </div>

            <div id="slotsContainer">
                @foreach ($universitySlots as $slot)
                    <div class="slot-item"
                        data-name="{{ strtolower($slot->university_name) }}"
                        data-quota="{{ $slot->max_slots }}">
                        <div class="slot-name">{{ $slot->university_name }}</div>
                        <div class="slot-count-col">
                            <div class="slot-count-badge">
                                <span>{{ sprintf('%02d', $slot->max_slots) }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Empty State --}}
        <div id="noResult" class="hidden empty-state">
            <i class="fas fa-database text-6xl mb-6" style="color:var(--border-mid)"></i>
            <h3 class="font-black uppercase tracking-[0.6em] text-sm mb-2" style="color:var(--text-muted)">Zero Matches Found_</h3>
            <p class="text-xs font-mono" style="color:var(--text-muted); opacity:0.5">System query returned null response.</p>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput    = document.getElementById('searchInput');
    const sortOrder      = document.getElementById('sortOrder');
    const slotsContainer = document.getElementById('slotsContainer');
    const noResult       = document.getElementById('noResult');
    const totalCount     = document.getElementById('totalCount');

    function filterAndSort() {
        const q    = searchInput.value.toLowerCase();
        const sort = sortOrder.value;
        let items  = Array.from(document.querySelectorAll('.slot-item'));
        let visible = 0;

        items.forEach(item => {
            const match = item.getAttribute('data-name').includes(q);
            item.style.display = match ? 'flex' : 'none';
            if (match) visible++;
        });

        const shown = items.filter(i => i.style.display !== 'none');
        shown.sort((a, b) => {
            if (sort === 'asc')        return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
            if (sort === 'desc')       return b.getAttribute('data-name').localeCompare(a.getAttribute('data-name'));
            if (sort === 'quota-high') return parseInt(b.getAttribute('data-quota')) - parseInt(a.getAttribute('data-quota'));
        });
        shown.forEach(i => slotsContainer.appendChild(i));

        totalCount.textContent = visible;
        noResult.classList.toggle('hidden', visible > 0);
    }

    searchInput.addEventListener('input', filterAndSort);
    sortOrder.addEventListener('change', filterAndSort);
});
</script>
@endsection