<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $registration->participant_id ?? 'Receipt' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        @media print {
            body {
                background-color: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .print-shadow {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
            }
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

<body class="antialiased text-slate-800 bg-slate-50 min-h-screen py-10 px-4 flex flex-col items-center">

    <div class="max-w-3xl w-full space-y-6">

        <div class="no-print space-y-4">
            @if ($message)
                <div
                    class="p-4 rounded-2xl flex items-center gap-3 border shadow-sm
                    {{ $payment_status === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : 'bg-amber-50 border-amber-200 text-amber-800' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="text-sm font-semibold">{{ $message }}</span>
                </div>
            @endif

            <div
                class="flex flex-col sm:flex-row gap-3 justify-between items-center bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
                <a href="{{ url('/') }}"
                    class="text-slate-600 hover:text-slate-900 text-xs font-bold uppercase tracking-wider flex items-center gap-2">
                    ← Back to Home
                </a>

                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('event.admit_card', ['slug' => $registration->event->slug ?? ($registration->event_slug ?? 'iupc'), 'id' => $registration->id]) }}"
                        class="bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-700 hover:to-blue-700 text-white font-extrabold text-xs px-6 py-2.5 rounded-xl uppercase tracking-wider transition-all shadow-md shadow-cyan-600/10 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                        Get Admit Card
                    </a>

                    <button onclick="window.print()"
                        class="bg-slate-800 hover:bg-slate-900 text-white font-bold text-xs px-6 py-2.5 rounded-xl uppercase tracking-wider transition-colors shadow-sm">
                        Print Money Receipt
                    </button>
                </div>
            </div>
        </div>

        <div
            class="print-shadow bg-white border border-slate-200 rounded-[2rem] p-8 md:p-12 shadow-xl shadow-slate-200/50 relative overflow-hidden">

            <div
                class="absolute top-0 right-0 bg-emerald-500 text-white font-black text-[10px] tracking-widest uppercase py-1 px-10 rotate-45 translate-x-8 translate-y-4 shadow-sm">
                PAID
            </div>

            <div class="flex flex-col md:flex-row justify-between items-start gap-6 pb-8 border-b border-slate-100">
                <div class="flex items-center gap-4">
                    @if ($setting && $setting->logo)
                        <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ $setting->site_name ?? 'Logo' }}"
                            class="w-8 h-8 md:w-10 md:h-10 object-contain">
                    @else
                        <img src="{{ asset('duet-logo.png') }}" alt="DUET Logo"
                            class="w-8 h-8 md:w-10 md:h-10 object-contain">
                    @endif
                    <div>
                        <h1 class="text-xl font-black text-slate-900 tracking-tight uppercase">
                            DUET CSE CARNIVAL {{ $setting->site_name ?? '2026' }}
                        </h1>
                    </div>
                </div>
                <div class="text-left md:text-right md:ml-auto">
                    <h2 class="text-2xl font-black text-slate-400 uppercase tracking-tight">INVOICE</h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Date: <span class="font-semibold text-slate-700">{{ now()->format('d M, Y') }}</span>
                    </p>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Payment Status: <span class="font-bold text-emerald-600 uppercase">Successful</span>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 py-8 text-sm">
                <div class="space-y-2">
                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Billed To /
                        Participant</span>
                    <h3 class="text-base font-extrabold text-slate-900 uppercase">
                        {{ $registration->team_name ?? $registration->m1_name }}
                    </h3>
                    <p class="text-slate-600 font-medium text-xs leading-relaxed">
                        {{ $registration->university_name }}
                    </p>
                    <p class="text-slate-500 text-xs font-mono">
                        Contact: {{ $registration->m1_phone }}
                    </p>
                </div>

                <div class="space-y-2 md:text-right flex flex-col md:items-end justify-start">
                    <div>
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Participant ID
                            Reference</span>
                        <span
                            class="text-base font-mono font-black text-cyan-600">#{{ $registration->participant_id ?? 'N/A' }}</span>
                    </div>
                    <div class="pt-2">
                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-wider block">Gateway
                            Transaction ID</span>
                        <span
                            class="text-xs font-mono font-bold text-slate-700">{{ $transaction->transaction_id ?? $registration->transaction_id }}</span>
                    </div>
                </div>
            </div>

            <div class="border border-slate-100 rounded-2xl overflow-hidden mt-4">
                <table class="w-full text-left text-sm">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="p-4 pl-6">Event Details & Description</th>
                            <th class="p-4 text-right pr-6">Amount Paid</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50 font-medium text-slate-700">
                        <tr>
                            <td class="p-4 pl-6">
                                <span
                                    class="text-slate-900 font-bold uppercase block">{{ $registration->event->name ?? 'Event Registration' }}</span>
                                <span class="text-xs text-slate-400 font-medium mt-0.5 block">Access Pass for All
                                    Keynote Events & Sub-Segments</span>
                            </td>
                            <td class="p-4 text-right pr-6 font-bold text-slate-900 font-mono">
                                {{ number_format($transaction->amount ?? 0, 2) }}
                                {{ $transaction->currency ?? 'BDT' }}
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="mt-6 flex flex-col items-end text-sm">
                <div class="w-full md:w-64 space-y-2 border-t border-slate-100 pt-4 font-medium">
                    <div class="flex justify-between text-slate-500">
                        <span>Subtotal:</span>
                        <span class="font-mono text-slate-700">{{ number_format($transaction->amount ?? 0, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-slate-500">
                        <span>VAT / Tax (0%):</span>
                        <span class="font-mono text-slate-700">0.00</span>
                    </div>
                    <div
                        class="flex justify-between text-base font-black text-slate-900 border-t border-slate-200 pt-2">
                        <span>Total Paid:</span>
                        <span class="font-mono text-cyan-600">
                            {{ number_format($transaction->amount ?? 0, 2) }} {{ $transaction->currency ?? 'BDT' }}
                        </span>
                    </div>
                </div>
            </div>

            <div
                class="mt-12 pt-6 border-t border-slate-100 flex flex-col sm:flex-row justify-between items-center gap-4 text-center sm:text-left">
                <p class="text-[10px] text-slate-400 font-semibold leading-relaxed max-w-sm">
                    * This is an automated electronic money receipt; no manual signature is required. Please secure your
                    official <span class="text-slate-600 font-bold">Admit Card / Entry Pass</span> using the dynamic
                    action panel above prior to venue check-in.
                </p>
                <div class="text-slate-300 font-serif italic text-sm select-none">
                    Thank You!
                </div>
            </div>

        </div>

        <p class="no-print text-center text-[10px] font-bold text-slate-400 uppercase tracking-widest pt-4">
            DUET CSE CARNIVAL 2026 • Powered by Department of CSE, DUET
        </p>
    </div>

</body>

</html>
