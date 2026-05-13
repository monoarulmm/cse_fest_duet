<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
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
</style>

<body>
    <div style="font-family: sans-serif; padding: 20px; border: 1px solid #22d3ee; border-radius: 10px;">
        <h2 style="color: #0891b2;">Congratulations! Your team is selected.</h2>
        <p>Institue Name: <strong>{{ $team->university_name }}</strong></p>
        <p>Team Name: <strong>{{ $team->team_name }}</strong></p>
        <p>Team id: <strong>{{ $team->team_id }}</strong></p>
        <p>Your unique coupon code for final registration and payment is:</p>
        <h1 style="background: #f1f5f9; padding: 10px; display: inline-block; color: #22d3ee; letter-spacing: 5px;">
            {{ $team->coupon_code }}
        </h1>
        <p>Please go to the website, find your team in the list, and use this code to complete payment.</p>
    </div>
</body>

</html>
