{{-- resources/views/invoices/generic.blade.php --}}
@extends('layouts.app')

@section('title', 'Payment Invoice | CSE CARNIVAL')

@section('custom_css')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=Syne:wght@400;600;700;800&display=swap');

        :root {
            --cyan: #22d3ee;
            --cyan-dim: rgba(34, 211, 238, 0.15);
            --cyan-border: rgba(34, 211, 238, 0.25);
            --green: #4ade80;
            --green-dim: rgba(74, 222, 128, 0.1);
            --red: #f87171;
            --bg-card: rgba(10, 18, 30, 0.85);
            --text-muted: rgba(148, 163, 184, 0.7);
        }

        .inv-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 3rem 1rem;
        }

        .inv-card {
            width: 100%;
            max-width: 700px;
            background: var(--bg-card);
            border: 1px solid var(--cyan-border);
            border-radius: 2rem;
            overflow: hidden;
            backdrop-filter: blur(20px);
            box-shadow: 0 0 80px rgba(34, 211, 238, 0.07), 0 40px 80px rgba(0, 0, 0, 0.5);
            animation: fadeUp 0.6s ease both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── HEADER ── */
        .inv-header {
            padding: 2.5rem 2.5rem 2rem;
            border-bottom: 1px solid var(--cyan-border);
            position: relative;
            overflow: hidden;
        }

        .inv-header::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(ellipse at 50% -30%, rgba(34, 211, 238, 0.12) 0%, transparent 70%);
            pointer-events: none;
        }

        .inv-status-icon {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin: 0 auto 1.25rem;
        }

        .inv-status-icon.success {
            background: var(--green-dim);
            border: 2px solid rgba(74, 222, 128, 0.4);
            box-shadow: 0 0 24px rgba(74, 222, 128, 0.2);
        }

        .inv-status-icon.failed {
            background: rgba(248, 113, 113, 0.1);
            border: 2px solid rgba(248, 113, 113, 0.4);
        }

        .inv-event-badge {
            display: inline-block;
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            letter-spacing: 0.15em;
            color: var(--cyan);
            background: var(--cyan-dim);
            border: 1px solid var(--cyan-border);
            padding: 0.3rem 1rem;
            border-radius: 999px;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
        }

        .inv-headline {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1.75rem;
            color: #fff;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .inv-sub {
            font-size: 0.8rem;
            color: var(--text-muted);
            margin-top: 0.25rem;
            font-family: 'Space Mono', monospace;
        }

        /* ── PARTICIPANT ID BADGE ── */
        .pid-badge {
            margin: 1.5rem auto 0;
            max-width: 340px;
            background: linear-gradient(135deg, rgba(74, 222, 128, 0.08), rgba(34, 211, 238, 0.08));
            border: 1px dashed rgba(74, 222, 128, 0.45);
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .pid-label {
            font-family: 'Space Mono', monospace;
            font-size: 0.6rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }

        .pid-value {
            font-family: 'Space Mono', monospace;
            font-size: 1.4rem;
            font-weight: 700;
            color: var(--green);
            letter-spacing: 0.08em;
        }

        /* ── BODY ── */
        .inv-body {
            padding: 2rem 2.5rem 2.5rem;
        }

        .inv-section-label {
            font-family: 'Space Mono', monospace;
            font-size: 0.6rem;
            letter-spacing: 0.18em;
            color: var(--cyan);
            text-transform: uppercase;
            margin-bottom: 0.75rem;
            border-left: 3px solid var(--cyan);
            padding-left: 0.6rem;
        }

        .inv-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2rem;
        }

        .inv-table tr {
            border-bottom: 1px solid rgba(34, 211, 238, 0.07);
        }

        .inv-table tr:last-child {
            border-bottom: none;
        }

        .inv-table th {
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.75rem 0;
            width: 38%;
            font-weight: 400;
            vertical-align: top;
        }

        .inv-table td {
            font-size: 0.85rem;
            color: #e2e8f0;
            padding: 0.75rem 0;
            vertical-align: top;
            font-family: 'Syne', sans-serif;
            font-weight: 600;
        }

        /* ── TRANSACTION SUMMARY BOX ── */
        .txn-box {
            background: rgba(34, 211, 238, 0.04);
            border: 1px solid var(--cyan-border);
            border-radius: 1.25rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .txn-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.6rem 0;
            border-bottom: 1px solid rgba(34, 211, 238, 0.07);
            font-family: 'Space Mono', monospace;
            font-size: 0.75rem;
        }

        .txn-row:last-child {
            border-bottom: none;
        }

        .txn-row .lbl {
            color: var(--text-muted);
        }

        .txn-row .val {
            color: #e2e8f0;
        }

        .txn-amount {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 0.75rem;
            padding-top: 0.75rem;
            border-top: 1px solid var(--cyan-border);
        }

        .txn-amount .lbl {
            font-family: 'Syne', sans-serif;
            font-weight: 700;
            font-size: 0.85rem;
            color: #fff;
            text-transform: uppercase;
        }

        .txn-amount .val {
            font-family: 'Space Mono', monospace;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--green);
        }

        /* ── STATUS PILL ── */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            font-family: 'Space Mono', monospace;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            padding: 0.3rem 0.8rem;
            border-radius: 999px;
        }

        .status-pill.paid {
            background: rgba(74, 222, 128, 0.12);
            color: var(--green);
            border: 1px solid rgba(74, 222, 128, 0.35);
        }

        .status-pill::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            animation: blink 1.4s ease infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.3;
            }
        }

        /* ── DIVIDER ── */
        .inv-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--cyan-border), transparent);
            margin: 1.75rem 0;
        }

        /* ── ACTIONS ── */
        .inv-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-inv-primary {
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #000;
            background: var(--cyan);
            border: none;
            padding: 0.8rem 1.75rem;
            border-radius: 0.75rem;
            cursor: pointer;
            text-decoration: none;
            transition: opacity 0.2s, transform 0.2s;
            font-weight: 700;
        }

        .btn-inv-primary:hover {
            opacity: 0.85;
            transform: translateY(-1px);
            color: #000;
        }

        .btn-inv-outline {
            font-family: 'Space Mono', monospace;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--cyan);
            background: transparent;
            border: 1px solid var(--cyan-border);
            padding: 0.8rem 1.75rem;
            border-radius: 0.75rem;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s, transform 0.2s;
        }

        .btn-inv-outline:hover {
            background: var(--cyan-dim);
            transform: translateY(-1px);
            color: var(--cyan);
        }

        /* ── FOOTER ── */
        .inv-footer {
            font-family: 'Space Mono', monospace;
            font-size: 0.6rem;
            color: var(--text-muted);
            text-align: center;
            padding: 1.25rem 2.5rem;
            border-top: 1px solid var(--cyan-border);
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        /* ── FAILED STATE ── */
        .inv-failed-msg {
            text-align: center;
            padding: 1rem;
            color: var(--red);
            font-family: 'Space Mono', monospace;
            font-size: 0.8rem;
        }

        @media print {

            .inv-actions,
            nav,
            footer {
                display: none !important;
            }

            .inv-card {
                box-shadow: none;
                border: 1px solid #ccc;
            }

            body {
                background: #fff;
            }
        }

        @media (max-width: 600px) {

            .inv-header,
            .inv-body {
                padding: 1.5rem;
            }

            .inv-headline {
                font-size: 1.35rem;
            }

            .pid-badge {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
@endsection

@section('content')
    <div class="inv-wrap">
        <div class="inv-card">

            @if (in_array($payment_status, ['success', 'already_paid']))

                {{-- ══ HEADER ══ --}}
                <div class="inv-header text-center">
                    <div class="inv-status-icon success mx-auto">✅</div>
                    <div class="inv-event-badge">{{ strtoupper(str_replace('-', ' ', $slug)) }}</div>
                    <h1 class="inv-headline">পেমেন্ট সফল</h1>
                    <p class="inv-sub">{{ $message }}</p>

                    {{-- Participant ID —  সবচেয়ে গুরুত্বপূর্ণ তথ্য, সবার আগে --}}
                    @if ($registration->participant_id)
                        <div class="pid-badge">
                            <div>
                                <div class="pid-label">Participant ID</div>
                                <div class="pid-value">{{ $registration->participant_id }}</div>
                            </div>
                            <div style="font-size:1.5rem;">🎫</div>
                        </div>
                    @endif
                </div>

                {{-- ══ BODY ══ --}}
                <div class="inv-body">

                    {{-- Participant / Team Info --}}
                    <div class="inv-section-label">Participant Info</div>
                    <table class="inv-table">
                        @if ($registration->team_name)
                            <tr>
                                <th>Team Name</th>
                                <td>{{ $registration->team_name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th>Name</th>
                            <td>{{ $registration->m1_name }}</td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td>{{ $registration->m1_email }}</td>
                        </tr>
                        <tr>
                            <th>Phone</th>
                            <td>{{ $registration->m1_phone }}</td>
                        </tr>
                        @if ($registration->university_name)
                            <tr>
                                <th>University</th>
                                <td>{{ $registration->university_name }}</td>
                            </tr>
                        @endif
                    </table>

                    {{-- Transaction Details --}}
                    @if ($transaction)
                        <div class="inv-section-label">Transaction Details</div>
                        <div class="txn-box">
                            <div class="txn-row">
                                <span class="lbl">Transaction ID</span>
                                <span class="val">{{ $transaction->transaction_id }}</span>
                            </div>
                            <div class="txn-row">
                                <span class="lbl">Payment Method</span>
                                <span class="val">{{ $transaction->payment_method }}</span>
                            </div>
                            <div class="txn-row">
                                <span class="lbl">Date & Time</span>
                                <span class="val">{{ $transaction->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="txn-row">
                                <span class="lbl">Status</span>
                                <span class="val">
                                    <span class="status-pill paid">{{ $transaction->status }}</span>
                                </span>
                            </div>
                            <div class="txn-amount">
                                <span class="lbl">Total Paid</span>
                                <span class="val">৳ {{ number_format($transaction->amount, 2) }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="inv-divider"></div>

                    {{-- Actions --}}
                    <div class="inv-actions">
                        <a href="{{ route('event.dashboard', $slug) }}" class="btn-inv-primary">
                            Dashboard →
                        </a>
                        <button onclick="window.print()" class="btn-inv-outline">
                            🖨️ Print Invoice
                        </button>
                    </div>
                </div>

                {{-- ══ FOOTER ══ --}}
                <div class="inv-footer">
                    CSE Carnival &nbsp;·&nbsp; Keep this invoice for your records
                </div>
            @else
                {{-- FAILED --}}
                @include('payment.failed', ['slug' => $slug, 'registration' => $registration ?? null])
            @endif

        </div>
    </div>
@endsection
