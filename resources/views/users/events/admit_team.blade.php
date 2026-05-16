<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card — {{ $team->participant_id }} | DUET CSE FEST 2026</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Rajdhani:wght@500;600;700&family=Space+Mono:wght@400;700&family=Syne:wght@700;800&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: #020617;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            padding: 2rem 1rem;
        }

        /* Action buttons */
        .action-bar {
            display: flex;
            gap: 10px;
            margin-bottom: 2rem;
        }

        .btn {
            font-family: 'Rajdhani', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            padding: 10px 22px;
            border-radius: 10px;
            cursor: pointer;
            border: none;
            transition: opacity 0.15s;
        }

        .btn:hover {
            opacity: 0.8;
        }

        .btn-primary {
            background: #22d3ee;
            color: #020617;
        }

        .btn-ghost {
            background: #1e293b;
            color: #94a3b8;
        }

        /* ── ID CARD ── */
        .id-card {
            width: 480px;
            background: #0b1120;
            border-radius: 22px;
            overflow: hidden;
            border: 1.5px solid #1e3a5f;
            box-shadow: 0 0 60px rgba(34, 211, 238, 0.08);
        }

        /* Header */
        .card-header {
            background: #040c1a;
            padding: 20px 24px 16px;
            border-bottom: 3px solid #22d3ee;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .org-tag {
            font-size: 8px;
            letter-spacing: 0.35em;
            color: #22d3ee;
            text-transform: uppercase;
            font-weight: 700;
            display: block;
            margin-bottom: 4px;
        }

        .org-title {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            letter-spacing: 0.05em;
            line-height: 1;
        }

        .org-title em {
            color: #22d3ee;
            font-style: normal;
        }

        .org-sub {
            font-size: 8px;
            color: #475569;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-top: 4px;
        }

        .id-badge {
            text-align: right;
        }

        .id-lbl {
            font-size: 7px;
            color: #475569;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .id-val {
            font-family: 'Space Mono', monospace;
            font-size: 18px;
            font-weight: 700;
            color: #fff;
        }

        /* Body */
        .card-body {
            padding: 20px 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* Event Row */
        .event-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .event-pass-lbl {
            font-size: 8px;
            color: #22d3ee;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            font-weight: 700;
            border-left: 2px solid #22d3ee;
            padding-left: 8px;
            margin-bottom: 4px;
            display: block;
        }

        .event-name {
            font-family: 'Syne', sans-serif;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .qr-wrap {
            background: #fff;
            border-radius: 10px;
            width: 72px;
            height: 72px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        /* QR placeholder — replace with real QrCode::size(68) */
        .qr-wrap svg {
            width: 64px;
            height: 64px;
        }

        .divider {
            height: 1px;
            background: #1e293b;
        }

        .section-lbl {
            font-size: 8px;
            color: #475569;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .section-lbl i {
            font-size: 12px;
        }

        /* Team block */
        .team-name {
            font-family: 'Syne', sans-serif;
            font-size: 19px;
            font-weight: 800;
            color: #fff;
            text-transform: uppercase;
            line-height: 1.1;
        }

        .university {
            font-size: 11px;
            color: #64748b;
            font-weight: 600;
            margin-top: 3px;
            letter-spacing: 0.02em;
        }

        /* Coach box */
        .coach-box {
            background: #0f1e35;
            border: 1px solid #1e3a5f;
            border-radius: 12px;
            padding: 12px 14px;
        }

        .coach-name {
            font-size: 14px;
            font-weight: 700;
            color: #e2e8f0;
            letter-spacing: 0.02em;
        }

        .coach-contact {
            font-size: 9px;
            color: #475569;
            margin-top: 3px;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        /* Members */
        .members-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 7px;
        }

        .member-chip {
            background: #131f35;
            border: 1px solid #1e3a5f;
            border-radius: 7px;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            color: #94a3b8;
            letter-spacing: 0.03em;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .member-num {
            font-family: 'Space Mono', monospace;
            font-size: 8px;
            color: #22d3ee;
        }

        .member-lead {
            color: #e2e8f0;
            border-color: #2d5a8e;
            background: #0f1e35;
        }

        /* Footer */
        .card-footer {
            border-top: 1px solid #1e293b;
            padding: 14px 24px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
        }

        .notice-text {
            font-size: 8px;
            color: #374151;
            line-height: 1.7;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
        }

        .auth-block {
            text-align: right;
        }

        .auth-lbl {
            font-size: 7px;
            color: #22d3ee;
            letter-spacing: 0.35em;
            text-transform: uppercase;
            font-weight: 700;
            display: block;
            margin-bottom: 2px;
        }

        .auth-name {
            font-size: 10px;
            color: #64748b;
            font-style: italic;
            font-weight: 600;
            font-family: Georgia, serif;
        }

        /* Bottom stripe */
        .stripe {
            height: 6px;
            background: repeating-linear-gradient(90deg,
                    #22d3ee 0 18px,
                    #6366f1 18px 36px,
                    #22d3ee 36px 54px);
        }

        /* ── PRINT STYLES ── */
        @media print {
            @page {
                size: A4;
                margin: 15mm;
            }

            body {
                background: #020617 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
                padding: 0;
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
            }

            .action-bar {
                display: none !important;
            }

            .id-card {
                width: 480px !important;
                box-shadow: none !important;
                border-color: #1e3a5f !important;
                page-break-inside: avoid;
                break-inside: avoid;
            }

            .card-header,
            .coach-box,
            .member-chip,
            .event-pass-lbl,
            .stripe {
                print-color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>

    <div class="action-bar no-print">
        <button class="btn btn-ghost" onclick="window.history.back()">
            <i class="fa-solid fa-arrow-left"></i> Back
        </button>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fa-solid fa-print"></i> Print Official ID Card
        </button>
    </div>

    <div class="id-card">

        <!-- Header -->
        <div class="card-header">
            <div>
                <span class="org-tag">Official Entry Pass · CSE Fest Portal</span>
                <div class="org-title">CSE <em>FEST</em> 2026</div>
                <div class="org-sub">DUET, Gazipur · Bangladesh</div>
            </div>
            <div class="id-badge">
                <span class="id-lbl">ID No.</span>
                <div class="id-val">#{{ $team->participant_id }}</div>
            </div>
        </div>

        <!-- Body -->
        <div class="card-body">

            <!-- Event + QR -->
            <div class="event-row">
                <div>
                    <span class="event-pass-lbl">Entry Pass</span>
                    <div class="event-name">{{ $event->name }}</div>
                </div>
                <div class="qr-wrap">
                    {{-- Replace with: {!!
            QrCode::size(64)->margin(1)->generate(url()->current()) !!} --}}
                    <svg viewBox="0 0 64 64" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="2" width="26" height="26" rx="3" fill="none" stroke="#000"
                            stroke-width="2.5" />
                        <rect x="9" y="9" width="12" height="12" fill="#000" />
                        <rect x="36" y="2" width="26" height="26" rx="3" fill="none" stroke="#000"
                            stroke-width="2.5" />
                        <rect x="43" y="9" width="12" height="12" fill="#000" />
                        <rect x="2" y="36" width="26" height="26" rx="3" fill="none" stroke="#000"
                            stroke-width="2.5" />
                        <rect x="9" y="43" width="12" height="12" fill="#000" />
                        <rect x="36" y="36" width="5" height="5" fill="#000" />
                        <rect x="43" y="36" width="5" height="5" fill="#000" />
                        <rect x="50" y="36" width="5" height="5" fill="#000" />
                        <rect x="57" y="36" width="5" height="5" fill="#000" />
                        <rect x="36" y="43" width="5" height="5" fill="#000" />
                        <rect x="50" y="43" width="5" height="5" fill="#000" />
                        <rect x="43" y="50" width="5" height="5" fill="#000" />
                        <rect x="57" y="50" width="5" height="5" fill="#000" />
                        <rect x="36" y="57" width="5" height="5" fill="#000" />
                        <rect x="50" y="57" width="5" height="5" fill="#000" />
                        <rect x="57" y="57" width="5" height="5" fill="#000" />
                    </svg>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Team Info -->
            <div>
                <div class="section-lbl">
                    <i class="fa-solid fa-users" style="color:#22d3ee;"></i>
                    Team Identity
                </div>
                <div class="team-name">{{ $team->team_name ?? 'INDIVIDUAL' }}</div>
                <div class="university">{{ $team->university_name }}</div>
            </div>

            <!-- Coach / Team Leader -->
            <div class="coach-box">
                @if ($event->slug == 'iupc' || isset($team->coach_name))
                    <div class="section-lbl" style="margin-bottom:8px;">
                        <i class="fa-solid fa-id-badge" style="color:#22d3ee;"></i>
                        Coach / Faculty In-Charge
                    </div>
                    <div class="coach-name">{{ $team->coach_name }}</div>
                    <div class="coach-contact">{{ $team->coach_phone }} &nbsp;|&nbsp; {{ $team->coach_email }}</div>
                @else
                    <div class="section-lbl" style="margin-bottom:8px;">
                        <i class="fa-solid fa-id-badge" style="color:#22d3ee;"></i>
                        Team Leader Details
                    </div>
                    <div class="coach-name">{{ $team->m1_name }}</div>
                    <div class="coach-contact">{{ $team->m1_phone }} &nbsp;|&nbsp; {{ $team->m1_email }}</div>
                @endif
            </div>

            <!-- Members -->
            <div>
                <div class="section-lbl">
                    <i class="fa-solid fa-list" style="color:#475569;"></i>
                    Participants List
                </div>
                <div class="members-grid">
                    <div class="member-chip member-lead">
                        <span class="member-num">01</span>
                        {{ $team->m1_name }}
                    </div>
                    @if ($team->m2_name)
                        <div class="member-chip">
                            <span class="member-num">02</span>
                            {{ $team->m2_name }}
                        </div>
                    @endif
                    @if ($team->m3_name)
                        <div class="member-chip">
                            <span class="member-num">03</span>
                            {{ $team->m3_name }}
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Footer -->
        <div class="card-footer">
            <div class="notice-text">
                * Identity card mandatory at entry<br>
                * Reporting: 09:30 AM, DUET Campus
            </div>
            <div class="auth-block">
                <span class="auth-lbl">Authorized By</span>
                <div class="auth-name">Convener, DUET CSE Fest 2026</div>
            </div>
        </div>

        <div class="stripe"></div>

    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1200);
            window.onafterprint = function() {
                window.history.back();
            };
        };
    </script>
</body>

</html>
