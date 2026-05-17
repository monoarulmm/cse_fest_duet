<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>System Error - DUET CSE FEST 2026</title>
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
        <link rel="icon" type="image/x-icon" href="{{ asset('duet-logo.png') }}">
    @endif
</head>

<body class="antialiased text-slate-800 min-h-screen flex items-center justify-center p-4">

    <div
        class="max-w-md w-full bg-white border border-slate-200 rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-slate-200/50 text-center relative overflow-hidden">

        <div class="absolute top-0 left-0 right-0 h-2 bg-amber-500"></div>

        <div
            class="inline-flex items-center justify-center w-20 h-20 bg-amber-50 text-amber-500 rounded-full mb-6 border border-amber-100">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                </path>
            </svg>
        </div>

        <h1 class="text-2xl font-black text-slate-900 uppercase tracking-tight">
            System Alert
        </h1>

        <p class="text-sm font-medium text-slate-600 mt-3 px-2 leading-relaxed">
            {{ $message ?? 'সিস্টেমে একটি অপ্রত্যাশিত ত্রুটি ঘটেছে। অনুগ্রহ করে কিছুক্ষণ পর আবার চেষ্টা করুন।' }}
        </p>

        <div class="mt-6 p-4 bg-slate-50 border border-slate-100 rounded-2xl text-left space-y-1.5">
            <div class="flex justify-between items-center text-xs">
                <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Error Type:</span>
                <span class="font-mono font-bold text-amber-700 text-[11px]">
                    {{ $payment_status ?? 'RUNTIME_EXCEPTION' }}
                </span>
            </div>

            <div class="flex justify-between items-center text-xs pt-1.5 border-t border-slate-200/60">
                <span class="text-slate-400 font-bold uppercase tracking-wider text-[9px]">Timestamp:</span>
                <span class="font-mono text-slate-500 text-[11px]">{{ now()->format('Y-m-d H:i:s') }}</span>
            </div>
        </div>

        <div class="mt-8 space-y-3">
            <button onclick="window.location.reload()"
                class="block w-full bg-slate-900 hover:bg-slate-800 text-white font-black text-xs py-3.5 rounded-xl uppercase tracking-widest transition-all shadow-md shadow-slate-900/10">
                Refresh Page
            </button>

            <a href="{{ url('/') }}"
                class="block w-full bg-white border-2 border-slate-200 hover:border-slate-300 hover:bg-slate-50 text-slate-700 font-bold text-xs py-3 rounded-xl uppercase tracking-widest transition-all">
                Back to Safety (Home)
            </a>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100">
            <p class="text-[11px] text-slate-400 font-medium">
                যদি আপনার ট্রানজেকশন পেন্ডিং থাকে, তবে যোগাযোগ করুন: <span
                    class="text-cyan-600 font-bold font-mono">csefest2026@duet.ac.bd</span>
            </p>
        </div>

    </div>

</body>

</html>
