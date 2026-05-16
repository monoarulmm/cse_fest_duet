{{-- resources/views/invoices/generic.blade.php --}}
@extends('layouts.app')

@section('title', 'Payment Invoice | CSE CARNIVAL')

@section('custom_css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Instrument+Serif:ital@0;1&family=DM+Mono:wght@300;400;500&family=Outfit:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --ink: #0e0e14;
            --ink-2: #1a1a24;
            --ink-3: #252535;
            --gold: #c9a84c;
            --gold-light: #e8c97a;
            --gold-dim: rgba(201, 168, 76, .12);
            --gold-border: rgba(201, 168, 76, .22);
            --silver: #94a3b8;
            --text: #e8e4dc;
            --text-muted: #7a7a90;
            --green: #4ade80;
            --green-dim: rgba(74, 222, 128, .1);
            --radius: 16px;
        }

        /* ══ PAGE SHELL ══ */
        .inv-page {
            min-height: 100vh;
            background: var(--ink);
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 48px 16px 80px;
            position: relative;
        }

        .inv-page::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 50% 0%, rgba(201, 168, 76, .06) 0%, transparent 60%),
                radial-gradient(ellipse 30% 30% at 85% 90%, rgba(201, 168, 76, .04) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Subtle dot grid */
        .inv-page::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: radial-gradient(circle, rgba(255, 255, 255, .035) 1px, transparent 1px);
            background-size: 28px 28px;
            pointer-events: none;
        }

        /* ══ INVOICE DOCUMENT ══ */
        .inv-doc {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 720px;
            animation: docIn .7s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes docIn {
            from {
                opacity: 0;
                transform: translateY(32px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Action Bar (outside printable area) ── */
        .action-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            padding: 0 4px;
        }

        .action-bar-left {
            font-family: 'DM Mono', monospace;
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .action-bar-left span {
            color: var(--gold);
        }

        .action-btns {
            display: flex;
            gap: 10px;
        }

        .btn-action {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 9px 20px;
            border-radius: 10px;
            font-family: 'DM Mono', monospace;
            font-size: 11px;
            letter-spacing: .06em;
            text-transform: uppercase;
            font-weight: 500;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .2s ease;
        }

        .btn-download {
            background: var(--gold);
            color: var(--ink);
        }

        .btn-download:hover {
            background: var(--gold-light);
            color: var(--ink);
            transform: translateY(-1px);
            box-shadow: 0 6px 20px rgba(201, 168, 76, .3);
        }

        .btn-dashboard {
            background: var(--ink-3);
            border: 1px solid rgba(255, 255, 255, .08);
            color: var(--silver);
        }

        .btn-dashboard:hover {
            color: var(--text);
            border-color: rgba(255, 255, 255, .15);
        }

        /* ══ PRINTABLE CARD ══ */
        .inv-card {
            background: var(--ink-2);
            border: 1px solid var(--gold-border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow:
                0 0 0 1px rgba(201, 168, 76, .06),
                0 48px 80px rgba(0, 0, 0, .7),
                inset 0 1px 0 rgba(255, 255, 255, .04);
        }

        /* ── Decorative top strip ── */
        .inv-crown {
            height: 4px;
            background: linear-gradient(90deg,
                    transparent 0%,
                    var(--gold-border) 15%,
                    var(--gold) 40%,
                    var(--gold-light) 50%,
                    var(--gold) 60%,
                    var(--gold-border) 85%,
                    transparent 100%);
        }

        /* ── Header ── */
        .inv-header {
            padding: 40px 48px 36px;
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 24px;
            align-items: start;
            border-bottom: 1px solid rgba(255, 255, 255, .05);
            position: relative;
        }

        .inv-org {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 20px;
        }

        .inv-org-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .inv-org-name {
            font-family: 'Outfit', sans-serif;
            font-weight: 700;
            font-size: 15px;
            color: var(--gold-light);
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .inv-org-sub {
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: .08em;
            text-transform: uppercase;
            margin-top: 2px;
        }

        .inv-title-block h1 {
            font-family: 'Instrument Serif', serif;
            font-size: 36px;
            font-weight: 400;
            color: #fff;
            line-height: 1.1;
            margin-bottom: 6px;
        }

        .inv-title-block h1 em {
            color: var(--gold-light);
            font-style: italic;
        }

        .inv-event-tag {
            display: inline-block;
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            color: var(--gold);
            background: var(--gold-dim);
            border: 1px solid var(--gold-border);
            padding: 4px 12px;
            border-radius: 99px;
            letter-spacing: .1em;
            text-transform: uppercase;
        }

        /* Status badge — top right */
        .inv-status-badge {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }

        .status-stamp {
            display: flex;
            align-items: center;
            gap: 7px;
            padding: 8px 16px;
            border-radius: 10px;
            font-family: 'DM Mono', monospace;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .status-stamp.paid {
            background: var(--green-dim);
            border: 1px solid rgba(74, 222, 128, .25);
            color: var(--green);
        }

        .status-stamp .dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            animation: blink 1.4s ease infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .25
            }
        }

        .inv-date {
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: .06em;
            text-align: right;
        }

        /* ── Participant ID hero ── */
        .pid-hero {
            margin: 0 48px;
            margin-top: -1px;
            background: linear-gradient(135deg, rgba(74, 222, 128, .06), rgba(201, 168, 76, .06));
            border: 1px solid rgba(74, 222, 128, .2);
            border-top: none;
            border-radius: 0 0 20px 20px;
            padding: 20px 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .pid-hero-left {}

        .pid-hero-label {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            color: var(--text-muted);
            letter-spacing: .14em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .pid-hero-value {
            font-family: 'DM Mono', monospace;
            font-size: 28px;
            font-weight: 500;
            color: var(--green);
            letter-spacing: .08em;
        }

        .pid-hero-right {
            font-family: 'Outfit', sans-serif;
            font-size: 11px;
            color: var(--text-muted);
            text-align: right;
            line-height: 1.7;
        }

        .pid-hero-right strong {
            color: var(--text);
            font-weight: 500;
        }

        /* ── Body ── */
        .inv-body {
            padding: 36px 48px 40px;
        }

        /* Two-column layout */
        .inv-cols {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 28px;
        }

        .inv-section {
            background: rgba(255, 255, 255, .02);
            border: 1px solid rgba(255, 255, 255, .06);
            border-radius: var(--radius);
            padding: 20px 22px;
        }

        .inv-section-label {
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: .16em;
            color: var(--gold);
            text-transform: uppercase;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .inv-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--gold-border);
            opacity: .5;
        }

        .info-list {}

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
            padding: 9px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .04);
        }

        .info-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-item:first-child {
            padding-top: 0;
        }

        .info-key {
            font-family: 'DM Mono', monospace;
            font-size: 9.5px;
            color: var(--text-muted);
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .info-val {
            font-family: 'Outfit', sans-serif;
            font-size: 13.5px;
            font-weight: 500;
            color: var(--text);
            word-break: break-all;
        }

        .info-val.mono {
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            font-weight: 400;
        }

        /* ── Transaction summary ── */
        .txn-summary {
            background: rgba(201, 168, 76, .04);
            border: 1px solid var(--gold-border);
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 28px;
        }

        .txn-head {
            padding: 14px 22px;
            border-bottom: 1px solid var(--gold-border);
            font-family: 'DM Mono', monospace;
            font-size: 9px;
            letter-spacing: .16em;
            text-transform: uppercase;
            color: var(--gold);
            background: rgba(201, 168, 76, .04);
        }

        .txn-rows {
            padding: 8px 22px;
        }

        .txn-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(255, 255, 255, .04);
            gap: 16px;
        }

        .txn-row:last-child {
            border-bottom: none;
        }

        .txn-lbl {
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            color: var(--text-muted);
            letter-spacing: .05em;
            text-transform: uppercase;
        }

        .txn-val {
            font-family: 'DM Mono', monospace;
            font-size: 12px;
            color: var(--text);
            text-align: right;
        }

        .txn-total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 22px;
            border-top: 1px solid var(--gold-border);
            background: rgba(201, 168, 76, .03);
        }

        .txn-total-lbl {
            font-family: 'Outfit', sans-serif;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: .04em;
        }

        .txn-total-val {
            font-family: 'DM Mono', monospace;
            font-size: 26px;
            font-weight: 500;
            color: var(--green);
            letter-spacing: .04em;
        }

        .txn-total-currency {
            font-size: 14px;
            opacity: .7;
            margin-right: 2px;
        }

        /* ── Divider ── */
        .inv-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, .06), transparent);
            margin: 4px 0 28px;
        }

        /* ── Note / QR area ── */
        .inv-note {
            background: rgba(255, 255, 255, .02);
            border: 1px dashed rgba(255, 255, 255, .07);
            border-radius: var(--radius);
            padding: 16px 20px;
            font-family: 'DM Mono', monospace;
            font-size: 10px;
            color: var(--text-muted);
            line-height: 1.8;
            letter-spacing: .03em;
            margin-bottom: 28px;
        }

        .inv-note strong {
            color: var(--gold-light);
        }

        /* ── Footer ── */
        .inv-foot {
            border-top: 1px solid rgba(255, 255, 255, .05);
            padding: 20px 48px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .inv-foot-brand {
            font-family: 'Instrument Serif', serif;
            font-size: 16px;
            color: var(--gold);
            font-style: italic;
        }

        .inv-foot-meta {
            font-family: 'DM Mono', monospace;
            font-size: 9.5px;
            color: var(--text-muted);
            letter-spacing: .07em;
            text-align: right;
            line-height: 1.8;
            text-transform: uppercase;
        }

        /* ════════════════════════════════
           PRINT STYLES
           ════════════════════════════════ */
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            body,
            html {
                background: #fff !important;
            }

            .inv-page {
                background: #fff !important;
                padding: 0 !important;
                display: block;
            }

            .inv-page::before,
            .inv-page::after {
                display: none;
            }

            .action-bar {
                display: none !important;
            }

            .inv-doc {
                max-width: 100% !important;
                animation: none !important;
            }

            .inv-card {
                border-radius: 0 !important;
                box-shadow: none !important;
                border: 1px solid #e0d9c8 !important;
                background: #fff !important;
            }

            /* Remap CSS vars for white background */
            :root {
                --ink-2: #fff;
                --ink-3: #f8f6f0;
                --text: #1a1a1a;
                --text-muted: #666;
                --gold: #8a6a1f;
                --gold-light: #a07820;
                --gold-dim: rgba(138, 106, 31, .08);
                --gold-border: rgba(138, 106, 31, .2);
                --green: #166534;
                --green-dim: rgba(22, 101, 52, .08);
            }

            .inv-crown {
                background: linear-gradient(90deg, transparent, #c9a84c, #e8c97a, #c9a84c, transparent) !important;
                print-color-adjust: exact;
            }

            .inv-section {
                background: #f9f7f2 !important;
                border-color: #e5dfc8 !important;
            }

            .txn-summary {
                background: #f9f7f0 !important;
                border-color: #d4c090 !important;
            }

            .txn-total {
                background: #f5f0e0 !important;
            }

            .pid-hero {
                background: #f0f7f0 !important;
                border-color: #90c090 !important;
            }

            nav,
            footer,
            .action-bar {
                display: none !important;
            }

            a {
                text-decoration: none !important;
                color: inherit !important;
            }

            .inv-foot-brand {
                color: #8a6a1f !important;
            }
        }

        /* ── Responsive ── */
        @media (max-width: 640px) {
            .inv-header {
                padding: 28px 24px;
                grid-template-columns: 1fr;
            }

            .inv-body {
                padding: 24px;
            }

            .inv-foot {
                padding: 18px 24px;
                flex-direction: column;
                text-align: center;
            }

            .pid-hero {
                margin: 0 24px;
                padding: 16px 20px;
                flex-direction: column;
            }

            .inv-cols {
                grid-template-columns: 1fr;
            }

            .inv-status-badge {
                align-items: flex-start;
            }

            .inv-date {
                text-align: left;
            }

            .inv-title-block h1 {
                font-size: 28px;
            }

            .action-bar {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .action-btns {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    <div class="inv-page">
        <div class="inv-doc">

            @if (in_array($payment_status, ['success', 'already_paid']))

                {{-- ── Action Bar ── --}}
                <div class="action-bar no-print">
                    <div class="action-bar-left">
                        Invoice &nbsp;/&nbsp; <span>{{ strtoupper(str_replace('-', ' ', $slug)) }}</span>
                        &nbsp;/&nbsp; {{ $registration->participant_id ?? 'Pending' }}
                    </div>
                    <div class="action-btns">
                        <a href="{{ route('event.dashboard', $slug) }}" class="btn-action btn-dashboard">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" />
                            </svg>
                            Dashboard
                        </a>
                        <button onclick="window.print()" class="btn-action btn-download">
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none">
                                <polyline points="6,9 6,2 18,2 18,9" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6,18H4a2 2 0 01-2-2V11a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" />
                                <rect x="6" y="14" width="12" height="8" rx="1" stroke="currentColor"
                                    stroke-width="2" />
                            </svg>
                            Print / Save PDF
                        </button>
                    </div>
                </div>

                {{-- ══ INVOICE CARD ══ --}}
                <div class="inv-card">

                    {{-- Gold crown ─ }}
        <div class="inv-crown"></div>

        {{-- ── Header ── --}}
                    <div class="inv-header">
                        <div>
                            <div class="inv-org">
                                <div class="inv-org-icon">🎓</div>
                                <div>
                                    <div class="inv-org-name">CSE Carnival</div>
                                    <div class="inv-org-sub">DUET · Dept. of CSE</div>
                                </div>
                            </div>
                            <div class="inv-title-block">
                                <h1>Payment <em>Invoice</em></h1>
                                <div style="margin-top:10px;">
                                    <span class="inv-event-tag">{{ strtoupper(str_replace('-', ' ', $slug)) }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="inv-status-badge">
                            <div class="status-stamp paid">
                                <span class="dot"></span>
                                @if ($payment_status === 'already_paid')
                                    Verified
                                @else
                                    Paid
                                @endif
                            </div>
                            <div class="inv-date">
                                @if ($transaction)
                                    {{ $transaction->created_at->format('d M Y') }}<br>
                                    {{ $transaction->created_at->format('h:i A') }}
                                @else
                                    {{ now()->format('d M Y') }}
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- ── Participant ID Hero ── --}}
                    @if ($registration->participant_id)
                        <div class="pid-hero">
                            <div class="pid-hero-left">
                                <div class="pid-hero-label">Participant ID</div>
                                <div class="pid-hero-value">{{ $registration->participant_id }}</div>
                            </div>
                            <div class="pid-hero-right">
                                Present this ID at the event venue<br>
                                <strong>Entry will not be allowed without ID</strong>
                            </div>
                        </div>
                    @endif

                    {{-- ── Body ── --}}
                    <div class="inv-body">

                        {{-- Two columns: Participant + Event ── --}}
                        <div class="inv-cols">

                            {{-- Participant Info ── --}}
                            <div class="inv-section">
                                <div class="inv-section-label">Participant</div>
                                <div class="info-list">
                                    @if ($registration->team_name)
                                        <div class="info-item">
                                            <span class="info-key">Team Name</span>
                                            <span class="info-val">{{ $registration->team_name }}</span>
                                        </div>
                                    @endif
                                    <div class="info-item">
                                        <span class="info-key">Name</span>
                                        <span class="info-val">{{ $registration->m1_name }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-key">Email</span>
                                        <span class="info-val mono">{{ $registration->m1_email }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-key">Phone</span>
                                        <span class="info-val mono">{{ $registration->m1_phone }}</span>
                                    </div>
                                    @if ($registration->university_name)
                                        <div class="info-item">
                                            <span class="info-key">University</span>
                                            <span class="info-val">{{ $registration->university_name }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            {{-- Event Info ── --}}
                            <div class="inv-section">
                                <div class="inv-section-label">Event Details</div>
                                <div class="info-list">
                                    <div class="info-item">
                                        <span class="info-key">Event</span>
                                        <span
                                            class="info-val">{{ optional($registration->event)->name ?? strtoupper(str_replace('-', ' ', $slug)) }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-key">Invoice No.</span>
                                        <span
                                            class="info-val mono">INV-{{ str_pad($registration->id, 5, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-key">Order ID</span>
                                        <span class="info-val mono"
                                            style="font-size:11px;">{{ $registration->order_id ?? '—' }}</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-key">Status</span>
                                        <span class="info-val">
                                            <span style="color:#4ade80; font-family:'DM Mono',monospace; font-size:12px;">●
                                                Verified</span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Transaction Summary ── --}}
                        @if ($transaction)
                            <div class="txn-summary">
                                <div class="txn-head">Transaction Summary</div>
                                <div class="txn-rows">
                                    <div class="txn-row">
                                        <span class="txn-lbl">Transaction ID</span>
                                        <span class="txn-val">{{ $transaction->transaction_id }}</span>
                                    </div>
                                    <div class="txn-row">
                                        <span class="txn-lbl">Payment Method</span>
                                        <span class="txn-val">{{ $transaction->payment_method }}</span>
                                    </div>
                                    <div class="txn-row">
                                        <span class="txn-lbl">Currency</span>
                                        <span class="txn-val">{{ $transaction->currency ?? 'BDT' }}</span>
                                    </div>
                                    <div class="txn-row">
                                        <span class="txn-lbl">Date & Time</span>
                                        <span
                                            class="txn-val">{{ $transaction->created_at->format('d M Y, h:i A') }}</span>
                                    </div>
                                </div>
                                <div class="txn-total">
                                    <span class="txn-total-lbl">Total Paid</span>
                                    <span class="txn-total-val">
                                        <span
                                            class="txn-total-currency">৳</span>{{ number_format($transaction->amount, 2) }}
                                    </span>
                                </div>
                            </div>
                        @endif

                        {{-- Note ── --}}
                        <div class="inv-note">
                            <strong>Important:</strong> &nbsp;This invoice is your official payment confirmation for CSE
                            Carnival.
                            Please keep a printed or digital copy for entry at the venue. &nbsp;·&nbsp;
                            For any queries, contact <strong>cse.carnival@duet.ac.bd</strong> &nbsp;·&nbsp;
                            This document was generated automatically and is valid without a signature.
                        </div>

                    </div>

                    {{-- ── Footer ── --}}
                    <div class="inv-foot">
                        <div class="inv-foot-brand">CSE Carnival</div>
                        <div class="inv-foot-meta">
                            Dhaka University of Engineering &amp; Technology<br>
                            Dept. of Computer Science &amp; Engineering · Gazipur
                        </div>
                    </div>

                </div>
                {{-- end .inv-card --}}
            @else
                @include('payment.failed', ['slug' => $slug, 'registration' => $registration ?? null])
            @endif

        </div>
    </div>
@endsection
