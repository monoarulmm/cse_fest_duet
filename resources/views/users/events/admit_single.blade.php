<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card - {{ $team->participant_id }}</title>
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
            justify-content: center;
            padding: 2rem 1rem;
            gap: 1.2rem;
        }

        /* Loading text */
        .loading-txt {
            font-size: 11px;
            letter-spacing: 0.3em;
            color: #334155;
            text-transform: uppercase;
            font-weight: 700;
            animation: blink 2s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .3
            }
        }

        /* ── CARD ── */
        .card {
            width: 380px;
            background: #0b1120;
            border-radius: 24px;
            overflow: hidden;
            border: 1.5px solid #1e3a5f;
        }

        /* Header */
        .c-head {
            background: #040c1a;
            border-bottom: 3px solid #22d3ee;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo-area {
            display: flex;
            align-items: center;
        }

        .vline {
            width: 1px;
            height: 32px;
            background: #1e3a5f;
            margin: 0 12px;
        }

        .h-tag {
            font-size: 7px;
            letter-spacing: 0.3em;
            color: #475569;
            text-transform: uppercase;
            font-weight: 700;
            display: block;
            margin-bottom: 3px;
        }

        .h-name {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: #fff;
            line-height: 1;
        }

        .h-name em {
            color: #22d3ee;
            font-style: normal;
        }

        .h-sub {
            font-size: 7px;
            color: #334155;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-top: 3px;
            display: block;
        }

        .id-blk {
            text-align: right;
        }

        .id-lbl {
            font-size: 7px;
            color: #334155;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            display: block;
            margin-bottom: 2px;
        }

        .id-val {
            font-family: 'Space Mono', monospace;
            font-size: 16px;
            font-weight: 700;
            color: #fff;
        }

        /* Body */
        .c-body {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }

        /* Name block — centered, white pill */
        .name-block {
            text-align: center;
            padding: 10px 0 6px;
        }

        .name-pill {
            display: inline-block;
            background: #fff;
            padding: 5px 22px;
            margin-bottom: 8px;
        }

        .name-txt {
            font-family: 'Syne', sans-serif;
            font-size: 18px;
            font-weight: 800;
            color: #020617;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }

        .pid {
            font-family: 'Space Mono', monospace;
            font-size: 10px;
            color: #22d3ee;
            letter-spacing: 0.18em;
            display: block;
        }

        .divider {
            height: 1px;
            background: #1e293b;
        }

        /* Info box */
        .info-box {
            background: #0a1628;
            border: 1px solid #1e3a5f;
            border-radius: 10px;
            padding: 13px 15px;
            display: flex;
            flex-direction: column;
            gap: 9px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 8px;
        }

        .info-lbl {
            font-size: 8px;
            color: #334155;
            text-transform: uppercase;
            letter-spacing: 0.25em;
            font-weight: 700;
            flex-shrink: 0;
            padding-top: 1px;
        }

        .info-val {
            font-size: 11px;
            color: #e2e8f0;
            font-weight: 700;
            text-align: right;
            letter-spacing: 0.02em;
            line-height: 1.35;
        }

        .info-divider {
            height: 1px;
            background: #1e293b;
        }

        /* QR section */
        .qr-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            padding: 4px 0;
        }

        .qr-wrap {
            background: #fff;
            border-radius: 14px;
            padding: 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            /* Real QR SVG will auto-size; override with: */
        }

        /* Force the QrCode SVG to be exactly 110×110 */
        .qr-wrap svg,
        .qr-wrap img {
            width: 110px !important;
            height: 110px !important;
            display: block;
        }

        .qr-lbl {
            font-size: 8px;
            color: #334155;
            letter-spacing: 0.25em;
            text-transform: uppercase;
            font-weight: 700;
        }

        /* Footer */
        .c-foot {
            background: #040c1a;
            border-top: 1px solid #1e293b;
            padding: 11px 20px;
            text-align: center;
        }

        .venue-txt {
            font-size: 9px;
            color: #334155;
            letter-spacing: 0.3em;
            text-transform: uppercase;
            font-weight: 700;
        }

        /* Stripe */
        .stripe {
            height: 5px;
            background: repeating-linear-gradient(90deg,
                    #22d3ee 0 14px,
                    #6366f1 14px 28px,
                    #22d3ee 28px 42px);
        }

        /* Back button */
        .back-btn {
            font-family: 'Rajdhani', sans-serif;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #334155;
            background: none;
            border: none;
            cursor: pointer;
            transition: color 0.15s;
        }

        .back-btn:hover {
            color: #94a3b8;
        }

        /* ── PRINT ── */
        @media print {
            @page {
                size: A4;
                margin: 20mm;
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

            .no-print {
                display: none !important;
            }

            .card {
                page-break-inside: avoid;
                break-inside: avoid;
                border-color: #1e3a5f !important;
            }

            .c-head,
            .stripe,
            .info-box,
            .name-pill {
                print-color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
            }
        }
    </style>
</head>

<body>

    <p class="loading-txt no-print">Generating Your Admit Card...</p>

    <div class="card">

        <!-- ── HEADER ── -->
        <div class="c-head">
            <div class="logo-area">

                <!-- SVG Logo — never breaks, no external URL -->
                <svg width="42" height="42" viewBox="0 0 42 42" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <rect width="42" height="42" rx="8" fill="#0f1e35" />
                    <text x="21" y="15" text-anchor="middle" font-family="Syne,sans-serif" font-size="8"
                        font-weight="800" fill="#22d3ee" letter-spacing="1">DUET</text>
                    <line x1="5" y1="19" x2="37" y2="19" stroke="#1e3a5f"
                        stroke-width="1" />
                    <text x="21" y="27.5" text-anchor="middle" font-family="Rajdhani,sans-serif" font-size="6.5"
                        font-weight="700" fill="#64748b" letter-spacing="0.5">CSE DEPT</text>
                    <rect x="5" y="33" width="32" height="3" rx="1.5" fill="#22d3ee" opacity="0.5" />
                </svg>

                <div class="vline"></div>

                <div>
                    <span class="h-tag">Official Entry Pass</span>
                    <div class="h-name">CSE <em>FEST</em> 2026</div>
                    <span class="h-sub">DUET, Gazipur</span>
                </div>
            </div>

            <div class="id-blk">
                <span class="id-lbl">ID NO</span>
                <div class="id-val">#{{ $team->participant_id }}</div>
            </div>
        </div>

        <!-- ── BODY ── -->
        <div class="c-body">

            <!-- Name pill (centered, white background) -->
            <div class="name-block">
                <div class="name-pill">
                    <span class="name-txt">{{ $team->m1_name }}</span>
                </div>
                <span class="pid">ID: {{ $team->participant_id }}</span>
            </div>

            <div class="divider"></div>

            <!-- Event & Institution info -->
            <div class="info-box">
                <div class="info-row">
                    <span class="info-lbl">Event</span>
                    <span class="info-val">{{ $event->name }}</span>
                </div>
                <div class="info-divider"></div>
                <div class="info-row">
                    <span class="info-lbl">Institution</span>
                    <span class="info-val" style="color:#94a3b8;">
                        {{ \Illuminate\Support\Str::limit($team->university_name, 28) }}
                    </span>
                </div>
            </div>

            <!-- QR Code — centered, large white box -->
            <div class="qr-section">
                <div class="qr-wrap">
                    {{-- Replace placeholder SVG below with real QR:
               {!! QrCode::size(110)->margin(0)->generate(url()->current()) !!}
          --}}
                    <svg width="110" height="110" viewBox="0 0 110 110" xmlns="http://www.w3.org/2000/svg">
                        <rect x="2" y="2" width="46" height="46" rx="5" fill="none" stroke="#000"
                            stroke-width="3.5" />
                        <rect x="14" y="14" width="22" height="22" fill="#000" />
                        <rect x="62" y="2" width="46" height="46" rx="5" fill="none" stroke="#000"
                            stroke-width="3.5" />
                        <rect x="74" y="14" width="22" height="22" fill="#000" />
                        <rect x="2" y="62" width="46" height="46" rx="5" fill="none" stroke="#000"
                            stroke-width="3.5" />
                        <rect x="14" y="74" width="22" height="22" fill="#000" />
                        <rect x="62" y="62" width="9" height="9" fill="#000" />
                        <rect x="73" y="62" width="9" height="9" fill="#000" />
                        <rect x="84" y="62" width="9" height="9" fill="#000" />
                        <rect x="95" y="62" width="9" height="9" fill="#000" />
                        <rect x="62" y="73" width="9" height="9" fill="#000" />
                        <rect x="84" y="73" width="9" height="9" fill="#000" />
                        <rect x="95" y="73" width="9" height="9" fill="#000" />
                        <rect x="73" y="84" width="9" height="9" fill="#000" />
                        <rect x="95" y="84" width="9" height="9" fill="#000" />
                        <rect x="62" y="95" width="9" height="9" fill="#000" />
                        <rect x="73" y="95" width="9" height="9" fill="#000" />
                        <rect x="84" y="95" width="9" height="9" fill="#000" />
                    </svg>
                </div>
                <span class="qr-lbl">Scan to Verify Registration</span>
            </div>

        </div>

        <!-- ── FOOTER ── -->
        <div class="c-foot">
            <p class="venue-txt">Venue: DUET Campus, Gazipur</p>
        </div>

        <div class="stripe"></div>
    </div>

    <button class="back-btn no-print" onclick="window.history.back()">
        ← Back to Dashboard
    </button>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);
            window.onafterprint = function() {
                window.history.back();
            };
        };
    </script>
</body>

</html>
