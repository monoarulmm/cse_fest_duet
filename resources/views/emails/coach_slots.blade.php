<!DOCTYPE html>
<html>

<head>
    <title>IUPC Registration Slots</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Hello {{ $coachName }},</h2>
    <p>Your registration slots for <strong>DUET CSE FEST IUPC</strong> have been approved.</p>
    <p>Below are the unique coupon codes generated for your teams. Please share one code with each team for their final
        registration:</p>

    <table-slot style="margin: 20px 0;">
        <ul style="list-style: none; padding: 0;">
            @foreach ($generatedCodes as $code)
                <li
                    style="background: #f4f4f5; border: 1px solid #e4e4e7; padding: 10px 15px; margin-bottom: 8px; border-radius: 6px; font-family: monospace; font-size: 16px; font-weight: bold; letter-spacing: 1px; color: #0891b2; width: fit-content;">
                    {{ $code }}
                </li>
            @endforeach
        </ul>
    </table-slot>

    <p><em>Note: Each code can only be used once for a single team registration.</em></p>
    <p>Best Regards,<br>University CSE Fest Committee<br>DUET, Gazipur.</p>
</body>

</html>
