<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $registration->participant_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background-color: white;
                padding: 0;
            }

            .print-shadow {
                shadow: none !important;
                border: 1px solid #e2e8f0;
            }
        }
    </style>
</head>

<body class="py-10 px-4">

    <div class="max-w-3xl mx-auto bg-white rounded-3xl shadow-xl overflow-hidden print-shadow">
        {{-- Header Section --}}
        <div class="bg-slate-900 p-8 text-white flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-black uppercase tracking-tighter">CSE CARNIVAL 2026</h1>
                <p class="text-slate-400 text-sm mt-1 uppercase tracking-widest">{{ $registration->event->title }}</p>
            </div>
            <div class="text-right">
                <div
                    class="bg-green-500/20 text-green-400 px-4 py-1 rounded-full text-xs font-bold uppercase border border-green-500/30">
                    <i class="fa-solid fa-check-circle mr-1"></i> Payment Successful
                </div>
                <p class="text-[10px] text-slate-500 mt-2 italic uppercase">Order ID: {{ $registration->order_id }}</p>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="p-8">
            <div class="grid grid-cols-2 gap-8 mb-10">
                <div>
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Registration Details
                    </h3>
                    <div class="space-y-2">
                        @if ($event_slug == 'iupc')
                            <p class="text-sm font-semibold text-slate-700">Team: <span
                                    class="text-slate-900">{{ $registration->team_name }}</span></p>
                            <p class="text-sm font-semibold text-slate-700">Coach: <span
                                    class="text-slate-900">{{ $registration->coach_name }}</span></p>
                        @elseif($event_slug == 'ict-olympiad')
                            <p class="text-sm font-semibold text-slate-700">Participant: <span
                                    class="text-slate-900">{{ $registration->m1_name }}</span></p>
                            <p class="text-sm font-semibold text-slate-700">ID: <span
                                    class="text-slate-900">{{ $registration->student_id }}</span></p>
                        @else
                            <p class="text-sm font-semibold text-slate-700">Leader: <span
                                    class="text-slate-900">{{ $registration->m1_name }}</span></p>
                            @if ($registration->team_name)
                                <p class="text-sm font-semibold text-slate-700">Team: <span
                                        class="text-slate-900">{{ $registration->team_name }}</span></p>
                            @endif
                        @endif
                        <p class="text-sm font-semibold text-slate-700">University: <span
                                class="text-slate-900">{{ $registration->university_name }}</span></p>
                    </div>
                </div>
                <div class="text-right">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-3">Invoice Info</h3>
                    <div class="space-y-2 text-sm">
                        <p class="font-semibold">Participant ID: <span
                                class="text-cyan-600">#{{ $registration->participant_id }}</span></p>
                        <p class="font-semibold italic">Date: <span
                                class="text-slate-900">{{ $transaction->created_at->format('d M, Y') }}</span></p>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="rounded-2xl border border-slate-100 overflow-hidden mb-8">
                <table class="w-full text-left">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase">Description</th>
                            <th class="p-4 text-xs font-bold text-slate-500 uppercase text-right">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-slate-700">
                        <tr class="border-b border-slate-50">
                            <td class="p-4">
                                <span class="font-bold text-slate-900">Registration Fee</span><br>
                                <span class="text-[10px] text-slate-400 uppercase tracking-tight">Event:
                                    {{ $registration->event->title }}</span>
                            </td>
                            <td class="p-4 text-right font-bold">{{ number_format($transaction->amount, 2) }} BDT</td>
                        </tr>
                    </tbody>
                    <tfoot class="bg-slate-900 text-white">
                        <tr>
                            <td class="p-4 font-bold uppercase text-xs">Total Paid Amount</td>
                            <td
                                class="p-4 text-right font-black text-lg underline decoration-green-500 decoration-2 underline-offset-4">
                                {{ number_format($transaction->amount, 2) }} BDT
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            {{-- Transaction Info --}}
            <div class="bg-slate-50 rounded-2xl p-6 border border-dashed border-slate-300">
                <div class="grid grid-cols-2 gap-4 text-xs">
                    <p class="text-slate-500 uppercase font-bold tracking-tighter">Transaction ID: <span
                            class="text-slate-900">{{ $transaction->transaction_id }}</span></p>
                    <p class="text-slate-500 uppercase font-bold tracking-tighter text-right">Method: <span
                            class="text-slate-900">{{ $transaction->payment_method }}</span></p>
                </div>
            </div>
        </div>

        {{-- Footer Section --}}
        <div class="p-8 bg-slate-50 text-center border-t border-slate-100">
            <p class="text-xs text-slate-400 font-semibold uppercase italic">This is an auto-generated invoice for CSE
                Carnival 2026. No signature required.</p>
            <div class="mt-6 flex justify-center gap-4 no-print">
                <button onclick="window.print()"
                    class="bg-slate-900 text-white px-6 py-2 rounded-full text-xs font-bold hover:bg-slate-800 transition-all flex items-center gap-2">
                    <i class="fa-solid fa-print"></i> Print Invoice
                </button>
                <a href="{{ url('/') }}"
                    class="border border-slate-300 text-slate-600 px-6 py-2 rounded-full text-xs font-bold hover:bg-white transition-all flex items-center gap-2">
                    <i class="fa-solid fa-house"></i> Go Home
                </a>
            </div>
        </div>
    </div>

    <div class="mt-10 text-center no-print">
        <p class="text-slate-400 text-xs italic tracking-widest uppercase">Powered by ShurjoPay & DUET CSE FEST AI</p>
    </div>

</body>

</html>
