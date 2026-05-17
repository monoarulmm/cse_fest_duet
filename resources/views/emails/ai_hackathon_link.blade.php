<!DOCTYPE html>
<html>

<head>
    <style>
        .button {
            background-color: #00ebff;
            border: none;
            color: #0f172a;
            padding: 12px 24px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            text-transform: uppercase;
        }

        .container {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f172a;
            color: #ffffff;
            padding: 40px;
            border-radius: 20px;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
        }

        .header {
            background: #0f172a;
            color: #22d3ee;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 30px;
            background: #ffffff;
        }

        .project-info {
            background: #f8fafc;
            border-left: 4px solid #22d3ee;
            padding: 15px;
            margin: 20px 0;
        }

        .btn {
            display: inline-block;
            background: #0891b2;
            color: #ffffff !important;
            padding: 12px 25px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            background: #f1f5f9;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }
    </style>

</head>

<body>
    <div class="container">
        <h2 style="color: #00ebff;">DUET CARNIVAl 2026</h2>
        <h3>Hello Team {{ $registration->team_name }},</h3>

        <p>Your registration for the <strong>AI-Hackathon</strong> has been received. We are excited to invite you to
            the preliminary contest phase.</p>

        <p>Please use the link below to access the contest platform and start your challenge:</p>

        <div style="margin: 30px 0;">
            <a href="{{ $contestLink }}" class="button">Access Contest Link</a>
        </div>

        <p style="font-size: 13px; color: #94a3b8;">
            Link: <a href="{{ $contestLink }}" style="color: #00ebff;">{{ $contestLink }}</a>
        </p>

        <hr style="border: 0; border-top: 1px solid #1e293b; margin: 20px 0;">

        <p><strong>Instructions:</strong></p>
        <ul style="font-size: 13px; color: #cbd5e1;">
            <li>Ensure you use your registered team email for the contest platform.</li>
            <li>Follow the rules mentioned in the event guidelines.</li>
            <li>Once the contest is over, we will review your performance and update your status in our portal.</li>
        </ul>

        <div class="footer">
            <p>Department of Computer Science and Engineering<br>
                Dhaka University of Engineering & Technology (DUET), Gazipur</p>
        </div>
    </div>
</body>

</html>
