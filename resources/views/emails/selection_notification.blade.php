<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Update | DUET CSE Carnival 2026</title>
    <style>
        body {
            background-color: #020617;
            padding: 40px 20px;
            margin: 0;
        }

        .card {
            border: 1px solid #1e293b;
            background: #0f172a;
            color: #f8fafc;
            padding: 40px 35px;
            border-radius: 16px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            max-width: 600px;
            margin: auto;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }

        .highlight {
            color: #22d3ee;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
        }

        .status-verified {
            background: rgba(34, 211, 238, 0.1);
            color: #22d3ee;
            border: 1px solid rgba(34, 211, 238, 0.4);
        }

        .status-selected {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
            border: 1px solid rgba(16, 185, 129, 0.4);
        }

        .id-container {
            margin: 25px 0;
            border: 1px dashed #22d3ee;
            padding: 20px;
            background: rgba(34, 211, 238, 0.03);
            text-align: center;
            border-radius: 12px;
        }

        .id-label {
            font-size: 13px;
            color: #94a3b8;
            display: block;
            margin-bottom: 8px;
            letter-spacing: 1px;
        }

        .id-badge {
            color: #0f172a;
            background: #22d3ee;
            padding: 8px 24px;
            border-radius: 8px;
            font-size: 26px;
            font-weight: 900;
            letter-spacing: 2px;
            display: inline-block;
            box-shadow: 0 0 20px rgba(34, 211, 238, 0.3);
        }

        .btn-container {
            text-align: center;
            margin: 30px 0 15px 0;
        }

        .btn-cyan {
            display: inline-block;
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            color: #ffffff !important;
            padding: 12px 28px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 16px -4px rgba(6, 182, 212, 0.3);
        }

        .btn-green {
            display: inline-block;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #ffffff !important;
            padding: 12px 28px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: bold;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 8px 16px -4px rgba(16, 185, 129, 0.3);
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #1e293b;
            padding-top: 20px;
            line-height: 1.5;
        }
    </style>
</head>

<body>
    <div class="card">

        @if ($registration->status === 'verified')
            <div class="status-badge status-verified">Registration Confirmed</div>
            <h2 style="color: #22d3ee; margin-top: 0; font-size: 24px;">Congratulations! 🎉</h2>
            <p>Dear <strong>{{ $registration->team_name ?? $registration->m1_name }}</strong>,</p>
            <p>Your payment has been successfully verified. You are now a <span class="highlight">Confirmed
                    Participant</span> for <strong>{{ strtoupper(str_replace('-', ' ', $slug ?? $type)) }}</strong>.</p>

            <div class="id-container">
                <span class="id-label">YOUR OFFICIAL PARTICIPANT ID</span>
                <span class="id-badge">{{ $registration->participant_id }}</span>
            </div>

            <div class="btn-container">
                <a href="{{ route('event.final_registered', ['slug' => $registration->event_slug ?? ($slug ?? $type)]) }}"
                    class="btn-cyan">
                    View Final Registered Teams
                </a>
            </div>

            <p style="font-size: 13px; color: #94a3b8; margin-top: 20px;">* Please save this email securely. Your
                Participant ID is required for on-spot check-in and claiming your participant kit at the venue.</p>
        @else
            <div class="status-badge status-selected">Selected for Next Phase</div>
            <h2 style="color: #10b981; margin-top: 0; font-size: 24px;">Selection Notification!</h2>
            <p>Dear <strong>{{ $registration->team_name ?? $registration->m1_name }}</strong>,</p>
            <p>We are happy to inform you that your registration for
                <strong>{{ strtoupper(str_replace('-', ' ', $slug ?? $type)) }}</strong> has been <span
                    class="highlight">Selected</span>.</p>
            <p style="color: #94a3b8;">To complete your registration and secure your slot, please proceed with the
                payment process immediately.</p>

            <div class="btn-container">
                <a href="{{ route('event.select_registered', ['slug' => $registration->event_slug ?? ($slug ?? $type)]) }}"
                    class="btn-green">
                    Proceed to Payment
                </a>
            </div>

            <p style="font-size: 13px; color: #94a3b8; margin-top: 20px;">* Note: Once you visit the page, search for
                your Team Name or Registration ID (<strong>#{{ $registration->id }}</strong>) to find your payment
                option.</p>
        @endif

        <p
            style="margin-top: 25px; background: rgba(30, 41, 59, 0.5); padding: 12px 15px; border-radius: 8px; font-size: 13px; color: #cbd5e1;">
            <strong>Event Schedule & Venue:</strong><br>
            📅 Date: June 26-27, 2026<br>
            📍 Venue: DUET Campus, Gazipur
        </p>

        <div class="footer">
            Best Regards,<br>
            <strong>Organizing Committee</strong><br>
            <span style="color: #94a3b8;">DUET CSE Carnival 2026 (Season II)</span><br>
            <span style="font-size: 11px; color: #475569;">Department of Computer Science and Engineering, DUET</span>
        </div>
    </div>
</body>

</html>
