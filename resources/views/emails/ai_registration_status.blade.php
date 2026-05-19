<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Registration Update | DUET CSE CARNIVAL 2026</title>
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

        .status-rejected {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.4);
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
            padding: 13px 28px;
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
            padding: 13px 28px;
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
        <h1
            style="margin: 0 0 25px 0; font-size: 22px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; color: #f8fafc; text-align: center; border-bottom: 1px solid #1e293b; padding-bottom: 15px;">
            DUET CSE CARNIVAL 2026
        </h1>

        @if ($registration->status === 'verified')
            <div class="status-badge status-verified">Registration Confirmed</div>
            <h2 style="color: #22d3ee; margin-top: 0; font-size: 22px;">Congratulations! 🎉</h2>
            <p>Dear <strong>{{ $registration->team_name ?? $registration->m1_name }}</strong>,</p>
            <p>Your payment has been successfully verified. You are now a <span class="highlight">Confirmed
                    Participant</span> for this event.</p>

            <div class="id-container">
                <span class="id-label">YOUR OFFICIAL PARTICIPANT ID</span>
                <span class="id-badge">{{ $registration->participant_id }}</span>
            </div>

            <div class="btn-container">
                <a href="{{ route('event.final_registered', ['slug' => $registration->event->slug ?? ($registration->event_slug ?? 'iupc')]) }}"
                    class="btn-cyan">
                    View Final Registered Teams
                </a>
            </div>

            <p style="font-size: 13px; color: #94a3b8; margin-top: 20px;">* Please save this email securely. Your
                Participant ID is required for on-spot check-in and receiving the participant kit.</p>
        @elseif ($status == 'selected')
            <div class="status-badge status-selected">Selected for Next Phase</div>
            <h2 style="color: #10b981; margin-top: 0; font-size: 22px;">Selection Notification!</h2>
            <p>Dear <strong>{{ $registration->team_name ?? $registration->m1_name }}</strong>,</
