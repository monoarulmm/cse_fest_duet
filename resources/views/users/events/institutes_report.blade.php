@extends('layouts.app')

@section('custom_css')
<style>
    .institutes-table-wrap {
        background: var(--bg-surface);
        border: 1px solid var(--border-accent);
        border-radius: 2rem;
        overflow: hidden;
        box-shadow: var(--shadow-card);
    }
    .institutes-thead th {
        background: var(--bg-elevated);
        color: var(--text-muted);
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.25em;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border-soft);
    }
    .institutes-row {
        border-bottom: 1px solid var(--border-soft);
        transition: background 0.18s;
    }
    .institutes-row:last-child { border-bottom: none; }
    .institutes-row:hover { background: var(--accent-dim); }
    .institutes-row td {
        padding: 1.25rem 1.5rem;
        color: var(--text-secondary);
        font-size: 0.875rem;
    }
    .institutes-row:hover .inst-name { color: var(--accent); }
    .inst-name {
        font-weight: 600;
        color: var(--text-primary);
        transition: color 0.2s;
    }
    .team-count-badge {
        background: var(--accent-dim);
        color: var(--accent);
        border: 1px solid var(--accent-border);
        border-radius: 9999px;
        padding: 0.25rem 1rem;
        font-size: 0.875rem;
        font-weight: 700;
        display: inline-block;
    }
    .top-bar-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-accent);
        border-radius: 1rem;
        padding: 0.5rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .back-btn {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding: 0.75rem 1.5rem;
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        color: var(--text-secondary);
        border-radius: 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
    }
    .back-btn:hover {
        border-color: var(--accent-border);
        background: var(--accent-dim);
    }
    .back-link {
        font-size: 0.875rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        color: var(--text-muted);
        transition: color 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    .back-link:hover { color: var(--accent); }
    .empty-cell {
        padding: 5rem 2rem;
        text-align: center;
        color: var(--text-muted);
        font-style: italic;
        font-size: 0.875rem;
    }
</style>
@endsection

@section('content')
<div class="max-w-6xl mx-auto px-4 py-10">

    {{-- Top bar --}}
    <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <button onclick="window.history.back()" class="back-btn group">
            <i class="fa-solid fa-chevron-left group-hover:-translate-x-1 transition-transform" style="color:var(--accent)"></i>
            Back to previous
        </button>

        <div class="top-bar-card">
            <span class="text-sm" style="color:var(--text-secondary)">Total Unique Institutes:</span>
            <span class="font-bold text-sm ml-1" style="color:var(--accent)">{{ $institutes->count() }}</span>
        </div>
    </div>

    {{-- Table --}}
    <div class="institutes-table-wrap">
        <table class="w-full text-left border-collapse">
            <thead class="institutes-thead">
                <tr>
                    <th>Institution Name</th>
                    <th class="text-center">Total Teams</th>
                </tr>
            </thead>
            <tbody>
                @forelse($institutes as $institute)
                    <tr class="institutes-row group">
                        <td>
                            <span class="inst-name">{{ $institute->university_name }}</span>
                        </td>
                        <td class="text-center">
                            <span class="team-count-badge">{{ $institute->total_teams }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="empty-cell">
                            No registrations found for this event.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Bottom back link --}}
    <div class="mt-12 text-center">
        <a href="{{ route('event.dashboard', $event->slug) }}" class="back-link">
            <i class="fa-solid fa-arrow-left"></i> Back to Event Details
        </a>
    </div>

</div>
@endsection