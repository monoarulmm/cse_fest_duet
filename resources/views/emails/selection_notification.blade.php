<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            background-color: #020617;
            padding: 20px;
        }

        .card {
            border: 1px solid #1e293b;
            background: #0f172a;
            color: #f8fafc;
            padding: 30px;
            border-radius: 12px;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            max-width: 600px;
            margin: auto;
        }

        .highlight {
            color: #22d3ee;
            font-weight: bold;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }

        .status-verified {
            background: rgba(34, 211, 238, 0.1);
            color: #22d3ee;
            border: 1px solid #22d3ee;
        }

        .id-container {
            margin: 25px 0;
            border: 1px dashed #22d3ee;
            padding: 20px;
            background: rgba(34, 211, 238, 0.05);
            text-align: center;
            border-radius: 8px;
        }

        .id-label {
            font-size: 14px;
            color: #94a3b8;
            display: block;
            margin-bottom: 5px;
        }

        .id-badge {
            color: #0f172a;
            background: #22d3ee;
            padding: 8px 20px;
            border-radius: 6px;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 2px;
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.4);
        }

        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #64748b;
            border-top: 1px solid #1e293b;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="card">
        @if ($registration->status === 'verified')
            <div class="status-badge status-verified">Registration Confirmed</div>
            <h2 style="color: #22d3ee; margin-top: 0;">Congratulations!</h2>
            <p>Dear <strong>{{ $registration->team_name ?? $registration->m1_name }}</strong>,</p>
            <p>Your payment has been successfully verified. You are now a <span class="highlight">Confirmed
                    Participant</span> for <strong>{{ strtoupper(str_replace('-', ' ', $type)) }}</strong>.</p>

            <!-- Participant ID Section -->
            <div class="id-container">
                <span class="id-label">YOUR OFFICIAL PARTICIPANT ID</span>
                <span class="id-badge">{{ $registration->participant_id }}</span>
            </div>
        @else
            <h2 style="color: #22d3ee; margin-top: 0;">Selection Notification</h2>
            <p>Dear <strong>{{ $registration->team_name ?? $registration->m1_name }}</strong>,</p>
            <p>We are happy to inform you that your registration for
                <strong>{{ strtoupper(str_replace('-', ' ', $type)) }}</strong> has been <span
                    class="highlight">Selected</span>.</p>
            <p style="color: #94a3b8;">Please proceed with the payment (if applicable) to receive your official
                Participant ID.</p>
        @endif

        <p style="margin-top: 20px;">
            <strong>Event Details:</strong><br>
            📅 Date: June 26-27, 2026<br>
            📍 Venue: DUET Campus, Gazipur
        </p>

        <p style="font-size: 13px; color: #94a3b8;">* Please save this email. Your Participant ID is required for
            on-spot check-in and receiving the participant kit.</p>

        <div class="footer">
            Best Regards,<br>
            <strong>Organizing Committee</strong><br>
            DUET CSE Carnival 2026 (Season II)
        </div>
    </div>
</body>

</html>
