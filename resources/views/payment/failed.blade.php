<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>পেমেন্ট ব্যর্থ হয়েছে</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link
        href="https://fonts.googleapis.com/css2?family=Hind+Siliguri:wght@300;400;500;600;700&family=Sora:wght@300;400;600;700&display=swap"
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
            --red: #e63946;
            --red-dark: #b5202d;
            --red-soft: #fff0f1;
            --orange: #f4a261;
            --bg: #0d0d0d;
            --surface: #161616;
            --surface2: #1e1e1e;
            --border: rgba(255, 255, 255, 0.07);
            --text: #f0f0f0;
            --muted: #888;
            --radius: 16px;
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
            padding: 24px;
            overflow-x: hidden;
        }

        /* ── Animated Background ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(ellipse 60% 50% at 20% 20%, rgba(230, 57, 70, .18) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 80% 80%, rgba(180, 20, 40, .12) 0%, transparent 60%);
            pointer-events: none;
            z-index: 0;
        }

        /* Floating particles */
        .particles {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            border-radius: 50%;
            background: var(--red);
            opacity: 0;
            animation: float-up var(--d, 6s) var(--delay, 0s) infinite ease-in;
        }

        @keyframes float-up {
            0% {
                transform: translateY(110vh) scale(1);
                opacity: 0;
            }

            10% {
                opacity: .5;
            }

            90% {
                opacity: .2;
            }

            100% {
                transform: translateY(-10vh) scale(0.4);
                opacity: 0;
            }
        }

        /* ── Card ── */
        .card {
            position: relative;
            z-index: 1;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 48px 40px;
            width: 100%;
            max-width: 480px;
            text-align: center;
            box-shadow: 0 40px 80px rgba(0, 0, 0, .6), 0 0 0 1px rgba(230, 57, 70, .08);
            animation: card-in .7s cubic-bezier(.22, 1, .36, 1) both;
        }

        @keyframes card-in {
            from {
                opacity: 0;
                transform: translateY(32px) scale(.97);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* ── Icon ── */
        .icon-wrap {
            width: 88px;
            height: 88px;
            border-radius: 50%;
            background: rgba(230, 57, 70, .12);
            border: 2px solid rgba(230, 57, 70, .3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            animation: pulse-ring 2s ease infinite;
            position: relative;
        }

        @keyframes pulse-ring {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(230, 57, 70, .3);
            }

            50% {
                box-shadow: 0 0 0 14px rgba(230, 57, 70, 0);
            }
        }

        .icon-wrap svg {
            animation: icon-shake 4s ease-in-out 1s infinite;
        }

        @keyframes icon-shake {

            0%,
            90%,
            100% {
                transform: rotate(0);
            }

            92% {
                transform: rotate(-8deg);
            }

            94% {
                transform: rotate(8deg);
            }

            96% {
                transform: rotate(-4deg);
            }

            98% {
                transform: rotate(4deg);
            }
        }

        /* ── Status Badge ── */
        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-family: 'Sora', sans-serif;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 99px;
            margin-bottom: 16px;
        }

        .badge.failed {
            background: rgba(230, 57, 70, .15);
            color: #ff6b77;
            border: 1px solid rgba(230, 57, 70, .25);
        }

        .badge.error {
            background: rgba(244, 162, 97, .12);
            color: var(--orange);
            border: 1px solid rgba(244, 162, 97, .25);
        }

        .badge.dot::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: currentColor;
            display: inline-block;
            animation: blink 1.2s ease infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .2
            }
        }

        /* ── Typography ── */
        h1 {
            font-family: 'Sora', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: #fff;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .subtitle {
            font-size: 15px;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 28px;
        }

        /* ── Info Box ── */
        .info-box {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: 20px 24px;
            margin-bottom: 28px;
            text-align: left;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            font-size: 13.5px;
            border-bottom: 1px solid var(--border);
        }

        .info-row:last-child {
            border-bottom: none;
            padding-bottom: 0;
        }

        .info-row:first-child {
            padding-top: 0;
        }

        .info-label {
            color: var(--muted);
        }

        .info-value {
            font-family: 'Sora', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: #ddd;
            max-width: 60%;
            text-align: right;
            word-break: break-all;
        }

        .info-value.code {
            background: rgba(230, 57, 70, .1);
            color: #ff8c94;
            padding: 2px 10px;
            border-radius: 6px;
            border: 1px solid rgba(230, 57, 70, .2);
        }

        /* ── Divider ── */
        .divider {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 20px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border);
        }

        /* ── Buttons ── */
        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            padding: 15px 24px;
            border-radius: 12px;
            font-family: 'Hind Siliguri', sans-serif;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all .2s ease;
            position: relative;
            overflow: hidden;
        }

        .btn::after {
            content: '';
            position: absolute;
            inset: 0;
            background: white;
            opacity: 0;
            transition: opacity .15s;
        }

        .btn:hover::after {
            opacity: .06;
        }

        .btn:active {
            transform: scale(.98);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--red) 0%, var(--red-dark) 100%);
            color: #fff;
            box-shadow: 0 8px 24px rgba(230, 57, 70, .35);
        }

        .btn-primary:hover {
            box-shadow: 0 12px 32px rgba(230, 57, 70, .45);
            transform: translateY(-1px);
        }

        .btn-ghost {
            background: var(--surface2);
            border: 1px solid var(--border);
            color: #bbb;
        }

        .btn-ghost:hover {
            border-color: rgba(255, 255, 255, .15);
            color: #fff;
        }

        /* ── Footer ── */
        .footer-note {
            margin-top: 24px;
            font-size: 12px;
            color: var(--muted);
            line-height: 1.7;
        }

        .footer-note a {
            color: var(--red);
            text-decoration: none;
        }

        .footer-note a:hover {
            text-decoration: underline;
        }

        /* ── Responsive ── */
        @media (max-width: 520px) {
            .card {
                padding: 36px 24px;
            }

            h1 {
                font-size: 22px;
            }
        }
    </style>
</head>

<body>

    {{-- Floating particles --}}
    <div class="particles" id="particles"></div>

    <div class="card">

        {{-- ── Icon ── --}}
        <div class="icon-wrap">
            @if (isset($payment_status) && $payment_status === 'error')
                {{-- System Error Icon --}}
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none">
                    <path
                        d="M12 8v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"
                        stroke="#e63946" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            @else
                {{-- Payment Failed Icon --}}
                <svg width="38" height="38" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="10" stroke="#e63946" stroke-width="1.8" />
                    <path d="M15 9l-6 6M9 9l6 6" stroke="#e63946" stroke-width="2.2" stroke-linecap="round" />
                </svg>
            @endif
        </div>

        {{-- ── Badge ── --}}
        @if (isset($payment_status))
            @if ($payment_status === 'failed')
                <div class="badge failed dot">পেমেন্ট ব্যর্থ</div>
            @elseif($payment_status === 'error')
                <div class="badge error dot">সিস্টেম এরর</div>
            @else
                <div class="badge failed dot">বাতিল</div>
            @endif
        @endif

        {{-- ── Title ── --}}
        <h1>
            @if (isset($payment_status) && $payment_status === 'error')
                কিছু একটা ভুল হয়েছে
            @else
                পেমেন্ট সফল হয়নি
            @endif
        </h1>

        <p class="subtitle">
            {{ $message ?? 'পেমেন্ট প্রক্রিয়া সম্পন্ন করা সম্ভব হয়নি। আপনার কোনো টাকা কাটা হয়নি।' }}
        </p>

        {{-- ── Info Box ── --}}
        @if ((isset($registration) && $registration) || (isset($sp_code) && $sp_code !== 'N/A'))
            <div class="info-box">
                @if (isset($registration) && $registration)
                    @if ($registration->name)
                        <div class="info-row">
                            <span class="info-label">নাম</span>
                            <span class="info-value">{{ $registration->name }}</span>
                        </div>
                    @endif
                    @if ($registration->order_id)
                        <div class="info-row">
                            <span class="info-label">অর্ডার আইডি</span>
                            <span class="info-value">{{ $registration->order_id }}</span>
                        </div>
                    @endif
                    @if ($registration->event)
                        <div class="info-row">
                            <span class="info-label">ইভেন্ট</span>
                            <span class="info-value">{{ $registration->event->name ?? '—' }}</span>
                        </div>
                    @endif
                @endif
                @if (isset($sp_code) && $sp_code !== 'N/A')
                    <div class="info-row">
                        <span class="info-label">এরর কোড</span>
                        <span class="info-value code">{{ $sp_code }}</span>
                    </div>
                @endif
            </div>
        @endif

        {{-- ── Action Buttons ── --}}
        <div class="divider">আপনি কী করতে চান?</div>

        <div class="btn-group">
            @if (isset($slug) && $slug && $payment_status !== 'error')
                <a href="{{ route('event.register', $slug) }}" class="btn btn-primary">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M1 4v6h6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M3.51 15a9 9 0 1 0 .49-4.47" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    আবার চেষ্টা করুন
                </a>
            @endif

            <a href="{{ url('/') }}" class="btn btn-ghost">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <polyline points="9,22 9,12 15,12 15,22" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                হোম পেজে ফিরুন
            </a>
        </div>

        {{-- ── Footer Note ── --}}
        <p class="footer-note">
            সমস্যা থাকলে আমাদের সাথে যোগাযোগ করুন —
            <a href="mailto:support@yourdomain.com">support@yourdomain.com</a><br>
            আপনার একাউন্ট থেকে কোনো টাকা কাটা হলে ৩ কার্যদিবসের মধ্যে ফেরত আসবে।
        </p>

    </div>

    <script>
        // Generate floating particles
        const container = document.getElementById('particles');
        for (let i = 0; i < 18; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.cssText = `
            left: ${Math.random() * 100}%;
            --d: ${4 + Math.random() * 6}s;
            --delay: ${Math.random() * 6}s;
            width: ${2 + Math.random() * 3}px;
            height: ${2 + Math.random() * 3}px;
            opacity: ${0.1 + Math.random() * 0.4};
        `;
            container.appendChild(p);
        }
    </script>
</body>

</html>
