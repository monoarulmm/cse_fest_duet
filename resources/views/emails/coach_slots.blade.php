<!DOCTYPE html>
<html>

<head>
    <title>Registration Slots</title>
</head>

<body style="font-family: sans-serif; line-height: 1.6;">
    <h2>Hello, {{ $coachName }}!</h2>
    <p>Your requested slots for IUPC registration have been generated. Please use the following codes for team
        registration:</p>

    <ul>
        @foreach ($generatedCodes as $code)
            <li style="font-family: monospace; font-size: 18px; color: #0891b2;">
                <strong>{{ $code }}</strong>
            </li>
        @endforeach
    </ul>

    <p>Thank you for participating in DUET CSE FEST 2026.</p>
    <p>Best Regards,<br>CSE FEST Organizing Team</p>
</body>

</html>
