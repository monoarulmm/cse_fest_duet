<!DOCTYPE html>
<html>

<head>
    <style>
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
        <div class="header">
            <h1 style="margin:0; font-size: 20px; text-transform: uppercase;">DUET CSE FEST 2026</h1>
        </div>

        <div class="content">
            <h2 style="color: #0f172a;">Congratulations! 🎉</h2>
            <p>Dear <strong>{{ $registration->m1_name }}</strong>,</p>
            <p>We are excited to inform you that your project proposal has been <strong>Selected</strong> for the
                Project Showcaseing competition.</p>

            <div class="project-info">
                <p style="margin: 0; font-size: 12px; color: #64748b; text-transform: uppercase;">Project Title</p>
                <p style="margin: 5px 0; font-weight: bold; font-size: 16px;">{{ $registration->project_title }}</p>

                <p style="margin: 10px 0 0 0; font-size: 12px; color: #64748b; text-transform: uppercase;">Team Name</p>
                <p style="margin: 5px 0; font-weight: bold;">{{ $registration->team_name ?? 'Individual' }}</p>
            </div>

            <p>To confirm your participation, please visit our website and complete the payment process.</p>

            {{-- ওয়েবসাইটের লিঙ্ক --}}
            <a href="{{ url('/') }}" class="btn">Visit Website & Pay</a>

            <p style="margin-top: 25px;">If you have any questions, feel free to contact the organizing committee.</p>
        </div>

        <div class="footer">
            <p>Department of Computer Science and Engineering<br>
                Dhaka University of Engineering & Technology (DUET), Gazipur</p>
        </div>
    </div>
</body>

</html>
