<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Unsuccessful - DUET CSE FEST 2026</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
    </style>

    @php
        $setting = \App\Models\Setting::first();
        $activeEvents = \App\Models\Event::where('is_active', true)->get();
    @endphp

    @if ($setting && $setting->favicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $setting->favicon) }}">
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $setting->favicon) }}">
    @else
        <link rel="icon" type="image/x-icon" href="{{ asset('cse.jpg') }}">
    @endif
</head>

<body class="antialiased text-slate-800 min-h-screen flex items-center justify-center p-4">

    <div
        class="max-w-md w-full bg-white border border-slate-200 rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 text-center relative overflow-hidden">

        {{-- Top accent bar --}}
        <div class="absolute top-0 left-0 right-0 h-2 bg-rose-500"></div>

        {{-- Icon --}}
        <div
            class="inline-flex items-center justify-center w-20 h-20 bg-rose-50 text-rose-500 rounded-full mb-6 border border-rose-100">
            @if ($payment_status === 'error')
                {{-- Warning icon for system error --}}
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                </svg>
            @else
                {{-- X icon for payment failed --}}
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            @endif
        </div>

        {{-- Title --}}
        <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">
            {{ $payment_status === 'error' ? 'System Error' : 'Payment Failed' }}
        </h1>

        {{-- Message --}}
        <p class="text-sm font-medium text-slate-600 mt-3 px-2 leading-relaxed">
            {{ $message ?? 'পেমেন্ট সফল হয়নি। আপনার অ্যাকাউন্ট থেকে টাকা কেটে থাকলে ২৪ ঘণ্টার মধ্যে তা স্বয়ংক্রিয়ভাবে ফেরত চলে আসবে।' }}
        </p>

        {{-- Info box — শুধু failed হলে দেখাও, system error-এ gateway status দেখানো misleading --}}
        @if ($payment_status === 'failed' || $registration)
            <div class="mt-6 p-4 bg-slate-50 border border-slate-100 rounded-2xl text-left space-y-2">
                @if ($payment_status === 'failed' && !empty($sp_code))
                    <div class="flex justify-between items-center text-xs">
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Gateway
                            Status:</span>
                        <span
                            class="font-mono font-bold px-2 py-0.5 bg-rose-50 text-rose-600 rounded-md border border-rose-100">
                            {{ $sp_code }}
                        </span>
                    </div>
                @endif

                @if ($registration)
                    <div
                        class="flex justify-between items-center text-xs {{ $payment_status === 'failed' && !empty($sp_code) ? 'pt-1 border-t border-slate-200/60' : '' }}">
                        <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Participant
                            Ref:</span>
                        <span class="font-mono font-bold text-slate-700">#{{ $registration->id }}</span>
                    </div>
                @endif
            </div>
        @endif

        {{-- Action buttons --}}
        <div class="mt-8 space-y-3">
            {{--
                "Try Again" শুধু failed হলে দেখাও।
                Route: event.payment.retry — registration id পাঠাও, controller নতুন order_id বানাবে।
                payment/initiate/{order_id} ভুল ছিল — পুরানো failed order_id দিয়ে pay হয় না।
            --}}
            @if ($payment_status === 'failed' && $registration)
                <a href="{{ route('payment.make', $registration->id) }}"
                    class="block w-full bg-slate-900 hover:bg-slate-800 text-white font-black text-xs py-3.5 rounded-xl uppercase tracking-widest transition-all shadow-md shadow-slate-900/10">
                    Try Payment Again
                </a>
            @endif

            <a href="{{ url('/event/' . ($slug ?? 'event')) }}"
                class="block w-full bg-white border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs py-3 rounded-xl uppercase tracking-widest transition-all">
                Go Back to Event Page
            </a>
        </div>

        {{-- Contact --}}
        <div class="mt-8 pt-6 border-t border-slate-100">
            <p class="text-[11px] text-slate-400 font-medium">
                কোনো সমস্যা হলে যোগাযোগ করুন:
                <span class="text-cyan-600 font-bold font-mono">csefest2026@duet.ac.bd</span>
            </p>
        </div>

    </div>

</body>

</html>
