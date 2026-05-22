<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result & Seat Plan | DUET CSE CARNIVAL 2026</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Space+Mono:wght@400;700&family=DM+Sans:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    @php
        $setting = \App\Models\Setting::first();
        $activeEvents = \App\Models\Event::where('is_active', true)->get();
    @endphp

    @if ($setting && $setting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('duet-logo.png') }}">
    @endif
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }

        .page-wrap {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            width: 100%;
        }

        /* ─── CARD ─── */
        .pass-card {
            width: 100%;
            max-width: 560px;
            background: #ffffff;
            border: 2px solid #0e1829;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 7px 7px 0 #0e1829;
        }

        /* Header */
        .card-header {
            background: #0e1829;
            padding: 20px 24px 16px;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .fest-label {
            font-family: 'Rajdhani', sans-serif;
            font-size: 9px;
            letter-spacing: 0.35em;
            color: #38bdf8;
            text-transform: uppercase;
            font-weight: 600;
            display: block;
            margin-bottom: 4px;
        }

        .fest-title {
            font-family: 'Rajdhani', sans-serif;
            font-size: 22px;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 0.04em;
            line-height: 1.1;
        }

        .fest-title span {
            color: #38bdf8;
        }

        .uni-name {
            font-size: 10px;
            color: #94a3b8;
            margin-top: 4px;
            line-height: 1.4;
            max-width: 220px;
        }

        .header-badge {
            background: #1e3a5f;
            border: 1px solid #2d5a8e;
            border-radius: 8px;
            padding: 10px 14px;
            text-align: center;
            flex-shrink: 0;
        }

        .badge-label {
            font-size: 7px;
            letter-spacing: 0.3em;
            color: #64748b;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .badge-year {
            font-family: 'Space Mono', monospace;
            font-size: 20px;
            font-weight: 700;
            color: #38bdf8;
            line-height: 1;
        }

        /* Stripe */
        .stripe {
            height: 5px;
            background: repeating-linear-gradient(90deg, #38bdf8 0 14px, #0e1829 14px 18px);
        }

        /* Body */
        .card-body {
            padding: 22px 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* Participant block */
        .participant-block {
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            align-items: center;
            gap: 14px;
            background: #f8fafc;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #0e1829;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-family: 'Rajdhani', sans-serif;
            font-size: 17px;
            font-weight: 700;
            color: #38bdf8;
            border: 2px solid #38bdf8;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        .part-tag {
            font-size: 7.5px;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 500;
        }

        .part-name {
            font-family: 'Rajdhani', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            line-height: 1.1;
            margin: 2px 0;
        }

        .part-id {
            font-family: 'Space Mono', monospace;
            font-size: 9px;
            color: #38bdf8;
            letter-spacing: 0.15em;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .info-cell {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 10px 12px;
            background: #f8fafc;
        }

        .cell-label {
            font-size: 7.5px;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            color: #94a3b8;
            font-weight: 600;
            margin-bottom: 3px;
        }

        .cell-value {
            font-family: 'Rajdhani', sans-serif;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.02em;
        }

        .status-val {
            color: #d97706;
            /* Golden/Amber color for ranks */
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Seat section updated for single column data */
        .seat-section {
            border: 2px solid #0e1829;
            border-radius: 10px;
            overflow: hidden;
        }

        .seat-header {
            background: #0e1829;
            color: #38bdf8;
            font-size: 9px;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            font-weight: 600;
            padding: 9px 14px;
            font-family: 'Rajdhani', sans-serif;
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .seat-display-box {
            padding: 18px;
            background: #f8fafc;
            text-align: center;
        }

        .seat-no-badge {
            font-family: 'Space Mono', monospace;
            font-size: 18px;
            font-weight: 700;
            color: #0e1829;
            background: #dbeafe;
            border-radius: 8px;
            padding: 8px 16px;
            display: inline-block;
            border: 1.5px solid #bfdbfe;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            max-width: 100%;
            word-break: break-word;
        }

        /* Notice */
        .notice {
            border-top: 2px dashed #e2e8f0;
            padding-top: 12px;
            display: flex;
            align-items: flex-start;
            gap: 7px;
        }

        .notice-text {
            font-size: 9px;
            color: #94a3b8;
            line-height: 1.7;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .notice-icon {
            color: #94a3b8;
            font-size: 11px;
            margin-top: 2px;
            flex-shrink: 0;
        }

        /* Footer */
        .card-footer {
            background: #f8fafc;
            border-top: 1.5px solid #e2e8f0;
            padding: 13px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dev-tag {
            font-size: 8px;
            color: #94a3b8;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            font-weight: 500;
        }

        .print-btn {
            background: #0e1829;
            color: #38bdf8;
            border: none;
            border-radius: 7px;
            padding: 9px 18px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 7px;
            transition: background 0.15s;
        }

        .print-btn:hover {
            background: #1e3a5f;
        }

        /* ─── PRINT STYLES ─── */
        @media print {
            body {
                background: #fff !important;
                padding: 0;
                display: block;
            }

            .page-wrap {
                align-items: flex-start;
            }

            .pass-card {
                max-width: 100%;
                box-shadow: none !important;
                border: 2px solid #000 !important;
                border-radius: 10px;
                page-break-inside: avoid;
            }

            .card-footer {
                display: none !important;
            }

            .notice {
                display: flex !important;
            }

            .stripe,
            .card-header,
            .seat-header,
            .avatar,
            .seat-no-badge {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="page-wrap">
        <div class="pass-card">

            <div class="card-header">
                <div>
                    <span class="fest-label">Official Digital Pass &middot; CARNIVAL Portal</span>
                    <div class="fest-title">DUET <span>CSE</span> CARNIVAL</div>
                    <div class="uni-name">Dhaka University of Engineering &amp; Technology</div>
                </div>
                <div class="header-badge">
                    <span class="badge-label">Year</span>
                    <span class="badge-year">2026</span>
                </div>
            </div>

            <div class="stripe"></div>

            <div class="card-body">

                <div class="participant-block">
                    {{-- নামের প্রথম ২ টি অক্ষর ডাইনামিকালি জেনারেট করার জন্য লজিক --}}
                    @php
                        $name = $result->team_name ?? 'Team Alpha';
                        $initials = strtoupper(substr($name, 0, 2));
                    @endphp
                    <div class="avatar">{{ $initials }}</div>
                    <div>
                        <div class="part-tag">Participant Identity</div>
                        <div class="part-name">{{ $name }}</div>
                        <div class="part-id">ID: #{{ $result->participant_id ?? '2026-CSE-047' }}</div>
                    </div>
                </div>

                <div class="info-grid">
                    <div class="info-cell">
                        <div class="cell-label">Target Event</div>
                        <div class="cell-value">{{ $result->event_name ?? 'Programming Contest' }}</div>
                    </div>
                    <div class="info-cell">
                        <div class="cell-label">Standing / Ranking</div>
                        <div class="cell-value">
                            <span class="status-val">
                                <i class="fa-solid fa-trophy"></i>
                                {{ $result->result_status ?? 'Participant' }}
                            </span>
                        </div>
                    </div>
                    <div class="info-cell">
                        <div class="cell-label">Date</div>
                        <div class="cell-value">26 June 2026</div>
                    </div>
                    <div class="info-cell">
                        <div class="cell-label">Report Time</div>
                        <div class="cell-value">08:30 AM</div>
                    </div>
                </div>

                <div class="seat-section">
                    <div class="seat-header">
                        <i class="fa-solid fa-map-location-dot"></i>
                        Allocated Seat &amp; Venue Details
                    </div>
                    <div class="seat-display-box">
                        <span class="seat-no-badge">
                            <i class="fa-solid fa-door-open" style="margin-right: 6px; color: #1e3a5f;"></i>
                            {{ $result->seat_plan ?? 'Lab-3, PC-24, Old Building 2nd Floor' }}
                        </span>
                    </div>
                </div>

                <div class="notice">
                    <i class="fa-solid fa-circle-exclamation notice-icon"></i>
                    <p class="notice-text">
                        This document is required at the entry point. Participant must carry their University ID card
                        along with this pass. Unauthorized entry is strictly prohibited.
                    </p>
                </div>

            </div>
            <div class="card-footer">
                <span class="dev-tag">Developed by DUET CSE Community</span>
                <button class="print-btn" onclick="window.print()">
                    <i class="fa-solid fa-print"></i>
                    Print / Save PDF
                </button>
            </div>

        </div>
    </div>
</body>

</html>
