{{-- resources/views/payment/error.blade.php --}}
{{-- এই page টি gateway error / phone validation error / pre-payment error এর জন্য --}}
{{-- payment/failed.blade.php → callback এর পর failure এর জন্য --}}

<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পেমেন্ট সম্পন্ন করা যায়নি</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=DM+Serif+Display:ital@0;1&display=swap"
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
            --amber: #f59e0b;
            --amber-dark: #b45309;
            --amber-glow: rgba(245, 158, 11, .12);
            --red: #ef4444;
            --red-glow: rgba(239, 68, 68, .1);
            --bg: #0a0a0f;
            --surface: #12121a;
            --surface2: #1a1a24;
            --border: rgba(255, 255, 255, 0.06);
            --border-warm: rgba(245, 158, 11, 0.2);
            --text: #e8e8f0;
            --muted: #6b6b80;
            --radius: 14px;
        }

        html,
        body {
            min-height: 100vh;
            background: var(--bg);
            color: var(--text);
            font-family: 'Hind Siliguri', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
        }

        /* ── Layered Background ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 70% 60% at 50% -10%, rgba(245, 158, 11, .07) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 90% 90%, rgba(239, 68, 68, .05) 0%, transparent 50%);
            pointer-events: none;
        }

        /* Subtle grid texture */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background-image: linear-gradient(rgba(255, 255, 255, .015) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, .015) 1px, transparent 1px);
            background-size: 40px 40px;
            pointer-events: none;
        }

        /* ── Layout ── */
        .page {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 520px;
            animation: fadeUp .65s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(28px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Top accent line ── */
        .accent-bar {
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--amber), transparent);
            border-radius: 3px 3px 0 0;
            margin-bottom: -1px;
            opacity: .8;
        }

        /* ── Card ── */
        .card {
            background: var(--surface);
            border: 1px solid var(--border-warm);
            border-radius: 0 0 20px 20px;
            padding: 40px 36px 36px;
            box-shadow:
                0 0 0 1px rgba(245, 158, 11, .05),
                0 32px 64px rgba(0, 0, 0, .6),
                inset 0 1px 0 rgba(255, 255, 255, .04);
        }

        /* ── Icon ── */
        .icon-area {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 28px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border);
        }

        .icon-circle {
            flex-shrink: 0;
            width: 52px;
            height: 52px;
            border-radius: 12px;
            background: var(--amber-glow);
            border: 1px solid var(--border-warm);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-circle svg {
            animation: wiggle 3s ease-in-out 1.5s infinite;
        }

        @keyframes wiggle {

            0%,
            85%,
            100% {
                transform: rotate(0deg);
            }

            88% {
                transform: rotate(-10deg);
            }

            92% {
                transform: rotate(10deg);
            }

            96% {
                transform: rotate(-5deg);
            }

            99% {
                transform: rotate(3deg);
            }
        }

        .icon-text h1 {
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            font-weight: 400;
            color: #fff;
            line-height: 1.3;
            margin-bottom: 4px;
        }

        .icon-text p {
            font-size: 12.5px;
            color: var(--muted);
            letter-spacing: .02em;
        }

        /* ── Error Message Box ── */
        .error-msg {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            background: rgba(239, 68, 68, .07);
            border: 1px solid rgba(239, 68, 68, .18);
            border-left: 3px solid var(--red);
            border-radius: var(--radius);
            padding: 16px 18px;
            margin-bottom: 24px;
        }

        .error-msg .dot {
            margin-top: 3px;
            flex-shrink: 0;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--red);
            box-shadow: 0 0 8px var(--red);
            animation: pulse 1.5s ease infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .5;
                transform: scale(.8);
            }
        }

        .error-msg p {
            font-size: 14.5px;
            line-height: 1.7;
            color: #f0a0a0;
        }

        /* ── Info Table ── */
        .info-table {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            margin-bottom: 28px;
        }

        .info-table-header {
            padding: 10px 16px;
            font-size: 10.5px;
            font-weight: 600;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            border-bottom: 1px solid var(--border);
            background: rgba(255, 255, 255, .02);
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 11px 16px;
            font-size: 13.5px;
            border-bottom: 1px solid var(--border);
            gap: 12px;
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-label {
            color: var(--muted);
            flex-shrink: 0;
        }

        .info-value {
            font-weight: 500;
            color: #d0d0e0;
            text-align: right;
            word-break: break-all;
        }

        /* ── What to do section ── */
        .steps {
            margin-bottom: 28px;
        }

        .steps-title {
            font-size: 11px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 12px;
        }

        .step {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 10px 0;
            border-bottom: 1px solid var(--border);
            font-size: 13.5px;
            line-height: 1.6;
            color: #b0b0c8;
        }

        .step:last-child {
            border-bottom: none;
        }

        .step-num {
            flex-shrink: 0;
            width: 22px;
            height: 22px;
            border-radius: 6px;
            background: var(--amber-glow);
            border: 1px solid var(--border-warm);
            color: var(--amber);
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Buttons ── */
        .btn-group {
            display: flex;
            gap: 10px;
        }

        .btn {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 16px;
            border-radius: 10px;
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all .2s ease;
        }

        .btn:active {
            transform: scale(.97);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--amber) 0%, var(--amber-dark) 100%);
            color: #0a0a0f;
            box-shadow: 0 6px 20px rgba(245, 158, 11, .25);
        }

        .btn-primary:hover {
            box-shadow: 0 8px 28px rgba(245, 158, 11, .35);
            transform: translateY(-1px);
        }

        .btn-ghost {
            background: var(--surface2);
            border: 1px solid var(--border);
            color: #888;
        }

        .btn-ghost:hover {
            color: #bbb;
            border-color: rgba(255, 255, 255, .12);
        }

        /* ── Footer ── */
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 12px;
            color: var(--muted);
            line-height: 1.8;
        }

        .footer a {
            color: var(--amber);
            text-decoration: none;
            opacity: .8;
        }

        .footer a:hover {
            opacity: 1;
            text-decoration: underline;
        }

        /* Mobile */
        @media (max-width: 480px) {
            .card {
                padding: 28px 20px 24px;
            }

            .btn-group {
                flex-direction: column;
            }

            .icon-text h1 {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>

    <div class="page">

        <div class="accent-bar"></div>

        <div class="card">

            {{-- ── Header ── --}}
            <div class="icon-area">
                <div class="icon-circle">
                    @if (($error_type ?? '') === 'gateway_error')
                        {{-- Gateway / Connection Error --}}
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path
                                d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"
                                stroke="#f59e0b" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                            <line x1="12" y1="9" x2="12" y2="13" stroke="#f59e0b"
                                stroke-width="1.8" stroke-linecap="round" />
                            <line x1="12" y1="17" x2="12.01" y2="17" stroke="#f59e0b"
                                stroke-width="2.2" stroke-linecap="round" />
                        </svg>
                    @else
                        {{-- Generic Error --}}
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <circle cx="12" cy="12" r="10" stroke="#f59e0b" stroke-width="1.8" />
                            <line x1="12" y1="8" x2="12" y2="12" stroke="#f59e0b"
                                stroke-width="1.8" stroke-linecap="round" />
                            <line x1="12" y1="16" x2="12.01" y2="16" stroke="#f59e0b"
                                stroke-width="2.2" stroke-linecap="round" />
                        </svg>
                    @endif
                </div>
                <div class="icon-text">
                    <h1>পেমেন্ট সম্পন্ন হয়নি</h1>
                    <p>গেটওয়েতে পাঠানোর আগেই সমস্যা হয়েছে</p>
                </div>
            </div>

            {{-- ── Error Message ── --}}
            <div class="error-msg">
                <div class="dot"></div>
                <p>{{ $message ?? 'পেমেন্ট গেটওয়েতে সংযোগ করা সম্ভব হয়নি।' }}</p>
            </div>

            {{-- ── Registration Info (if available) ── --}}
            @if (isset($registration) && $registration)
                <div class="info-table">
                    <div class="info-table-header">রেজিস্ট্রেশন তথ্য</div>
                    @if ($registration->team_name ?? $registration->m1_name)
                        <div class="info-row">
                            <span class="info-label">নাম</span>
                            <span class="info-value">{{ $registration->team_name ?? $registration->m1_name }}</span>
                        </div>
                    @endif
                    @if ($registration->event)
                        <div class="info-row">
                            <span class="info-label">ইভেন্ট</span>
                            <span class="info-value">{{ $registration->event->name }}</span>
                        </div>
                        <div class="info-row">
                            <span class="info-label">ফি</span>
                            <span class="info-value">৳ {{ number_format($registration->event->reg_fee) }}</span>
                        </div>
                    @endif
                    @if ($registration->m1_phone)
                        <div class="info-row">
                            <span class="info-label">ফোন</span>
                            <span class="info-value">{{ $registration->m1_phone }}</span>
                        </div>
                    @endif
                </div>
            @endif

            {{-- ── What to do next ── --}}
            <div class="steps">
                <div class="steps-title">এখন কী করবেন?</div>

                <div class="step">
                    <span class="step-num">১</span>
                    <span>আপনার ফোন নম্বর সঠিক কিনা নিশ্চিত করুন — ১১ ডিজিটের বাংলাদেশী নম্বর হতে হবে (যেমন:
                        01XXXXXXXXX)</span>
                </div>
                <div class="step">
                    <span class="step-num">২</span>
                    <span>ইন্টারনেট সংযোগ ঠিক আছে কিনা দেখুন, তারপর আবার চেষ্টা করুন</span>
                </div>
                <div class="step">
                    <span class="step-num">৩</span>
                    <span>সমস্যা বারবার হলে আমাদের সাথে যোগাযোগ করুন — <strong>আপনার কোনো টাকা কাটা
                            হয়নি</strong></span>
                </div>
            </div>

            {{-- ── Buttons ── --}}
            <div class="btn-group">
                @if (isset($slug) && $slug)
                    <a href="javascript:history.back()" class="btn btn-primary">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M1 4v6h6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M3.51 15a9 9 0 100-9" stroke="currentColor" stroke-width="2.2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        আবার চেষ্টা করুন
                    </a>
                @endif
                <a href="{{ url('/') }}" class="btn btn-ghost">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    হোম
                </a>
            </div>

            {{-- ── Footer ── --}}
            <div class="footer">
                সাহায্যের জন্য: <a href="mailto:support@duet.ac.bd">support@duet.ac.bd</a>
                &nbsp;·&nbsp;
                আপনার তথ্য সুরক্ষিত আছে
            </div>

        </div>
    </div>

</body>

</html>
