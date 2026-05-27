@extends('layouts.app')

@section('custom_css')
<style>
    .event-desc-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-accent);
        border-radius: 2.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-card);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
    }
    .event-desc-card::before,
    .event-desc-card::after {
        content: '';
        position: absolute;
        width: 16rem;
        height: 16rem;
        border-radius: 50%;
        filter: blur(80px);
        pointer-events: none;
        background: var(--accent-dim);
        opacity: 0.6;
    }
    .event-desc-card::before { top: -6rem; left: -6rem; }
    .event-desc-card::after  { bottom: -6rem; right: -6rem; }

    .back-btn {
        display: inline-flex;
        align-items: center;
        gap: 1rem;
        padding: 0.75rem 2rem;
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        border-radius: 0.75rem;
        transition: border-color 0.3s ease, background 0.3s ease;
        text-decoration: none;
    }
    .back-btn:hover {
        border-color: var(--accent-border);
        background: var(--accent-dim);
    }
    .back-divider {
        border-top: 1px solid var(--border-soft);
        margin-top: 4rem;
        padding-top: 2rem;
    }

    /* ── Rich Content (CKEditor output) ── */
    .custom-event-content {
        overflow-x: auto;
        color: var(--text-secondary);
        line-height: 1.8;
    }
    .custom-event-content h1,
    .custom-event-content h2,
    .custom-event-content h3,
    .custom-event-content h4 {
        color: var(--text-primary);
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: -0.02em;
        margin-top: 2rem;
        margin-bottom: 0.75rem;
        font-family: 'Orbitron', sans-serif;
    }
    .custom-event-content p {
        color: var(--text-secondary);
        margin-bottom: 1rem;
    }
    .custom-event-content strong {
        color: var(--accent);
        font-weight: 700;
    }
    .custom-event-content ul,
    .custom-event-content ol {
        color: var(--text-secondary);
        padding-left: 1.5rem;
        margin-bottom: 1rem;
    }
    .custom-event-content li { margin-bottom: 0.35rem; }
    .custom-event-content a {
        color: var(--accent);
        text-decoration: underline;
        text-underline-offset: 3px;
    }
    .custom-event-content blockquote {
        border-left: 3px solid var(--accent);
        padding-left: 1rem;
        margin: 1.5rem 0;
        color: var(--text-muted);
        font-style: italic;
        background: var(--accent-dim);
        border-radius: 0 0.5rem 0.5rem 0;
        padding: 0.75rem 1rem;
    }

    /* ── Table ── */
    .custom-event-content table {
        width: 100% !important;
        border-collapse: separate;
        border-spacing: 0 8px;
        background: transparent !important;
        min-width: 560px;
    }
    .custom-event-content thead th {
        color: var(--accent) !important;
        font-family: 'JetBrains Mono', monospace;
        font-size: 0.6875rem !important;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        padding: 0.625rem 1.25rem !important;
        border: none !important;
        text-align: left;
        background: transparent;
    }
    .custom-event-content tbody tr {
        background: var(--bg-elevated) !important;
        transition: transform 0.2s ease, background 0.25s ease;
    }
    .custom-event-content tbody tr:hover {
        background: var(--accent-dim) !important;
        transform: scale(1.004);
    }
    .custom-event-content td {
        padding: 1rem 1.25rem !important;
        border-top: 1px solid var(--border-soft) !important;
        border-bottom: 1px solid var(--border-soft) !important;
        color: var(--text-secondary) !important;
        font-size: 0.875rem;
    }
    .custom-event-content td:first-child {
        border-left: 1px solid var(--border-soft) !important;
        border-radius: 12px 0 0 12px;
        color: var(--accent) !important;
        font-weight: 700;
        font-family: monospace;
    }
    .custom-event-content td:last-child {
        border-right: 1px solid var(--border-soft) !important;
        border-radius: 0 12px 12px 0;
        color: var(--text-primary) !important;
    }

    /* ── Scrollbar ── */
    .custom-event-content::-webkit-scrollbar { height: 4px; }
    .custom-event-content::-webkit-scrollbar-thumb {
        background: var(--accent-border);
        border-radius: 10px;
    }
</style>
@endsection

@section('content')
<div class="min-h-screen py-12 md:py-20 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="event-desc-card">
            <div class="relative p-6 md:p-12">

                {{-- Rich Content --}}
                <div class="custom-event-content">
                    {!! $event->description !!}
                </div>

                {{-- Back Button --}}
                <div class="back-divider flex justify-center">
                    <a href="{{ url()->previous() }}" class="back-btn group">
                        <i class="fa-solid fa-arrow-left-long group-hover:-translate-x-1 transition-transform"
                            style="color:var(--accent)"></i>
                        <span class="text-[10px] font-black uppercase tracking-[0.3em]"
                            style="color:var(--text-secondary)">
                            Return to Hub
                        </span>
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection