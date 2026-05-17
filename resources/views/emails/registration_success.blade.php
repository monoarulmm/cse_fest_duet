<!DOCTYPE html>
<html>

<head>
    <title>Registration Success</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 30px; margin: 0;">
    <div
        style="max-width: 600px; background: #fff; margin: 0 auto; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border-top: 4px solid #06b6d4;">
        <div style="padding: 30px; text-align: center; background: #0f172a; color: #fff;">
            <h2 style="margin: 0; font-size: 20px; text-transform: uppercase; tracking: 1px;">Registration Confirmed!
            </h2>
        </div>
        <div style="padding: 30px; color: #334155; line-height: 1.6;">
            <p>প্রিয় গ্রাহক,</p>
            <p>আপনার কুপন/স্লট ব্যবহার করে পেমেন্টটি সফলভাবে সম্পন্ন হয়েছে। উৎসবের মূল ইভেন্টে অংশ নেওয়ার জন্য আপনার
                টিম বা অংশগ্রহণকারীকে সফলভাবে তালিকাভুক্ত করা হয়েছে।</p>

            <div
                style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 20px; border-radius: 12px; margin: 20px 0;">
                <h4 style="margin: 0 0 10px 0; color: #0f172a;">নিবন্ধন বিবরণী:</h4>
                <p style="margin: 4px 0;"><strong>Participant ID:</strong> <span
                        style="font-family: monospace; font-weight: bold; color: #0891b2;">{{ $registration->participant_id }}</span>
                </p>
                <p style="margin: 4px 0;"><strong>Event:</strong> {{ optional($registration->event)->title }}</p>
                <p style="margin: 4px 0;"><strong>Transaction ID:</strong> {{ $registration->transaction_id }}</p>
            </div>

            <p>অনুগ্রহ করে নিচের বাটনে ক্লিক করে আপনার অফিসিয়াল অ্যাডমিট কার্ডটি ডাউনলোড করে নিন:</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('admin.slots.index') }}"
                    style="background: #0f172a; color: #fff; padding: 12px 30px; border-radius: 10px; text-decoration: none; font-weight: bold; font-size: 13px; display: inline-block; text-transform: uppercase;">Download
                    Admit Card</a>
            </div>

            <p style="font-size: 11px; color: #94a3b8; text-align: center; margin-top: 4px;">যদি বাটনটি কাজ না করে, তবে
                এই লিংকটি ব্রাউজারে পেস্ট করুন: <br> {{ route('admin.slots.index') }}</p>
        </div>
    </div>
</body>

</html>
