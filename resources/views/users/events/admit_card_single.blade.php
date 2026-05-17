<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card - {{ $team->participant_id }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800;900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f1f5f9;
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                background-color: #ffffff !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            #print-area {
                width: 100% !important;
                height: 100vh !important;
                display: flex !important;
                align-items: center !important;
                justify-content: center !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            .id-card-body {
                width: 460px !important;
                height: 660px !important;
                margin: 0 auto !important;
                border: 2px solid #06b6d4 !important;
                box-shadow: none !important;
                background-color: #ffffff !important;
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

<body class="antialiased overflow-x-hidden text-slate-800">

    <div class="no-print flex justify-center gap-4 py-8 relative z-50">
        <button onclick="window.history.back()"
            class="bg-slate-800 text-white px-6 py-2 rounded-xl text-xs font-bold hover:bg-slate-700 transition uppercase">
            Back
        </button>
        <button onclick="window.print()"
            class="bg-cyan-600 text-white px-8 py-2 rounded-xl text-xs font-black hover:bg-cyan-500 transition uppercase shadow-lg shadow-cyan-600/20">
            Print Official ID Card
        </button>
    </div>

    <div id="print-area" class="min-h-screen flex items-center justify-center p-4">

        <div
            class="id-card-body relative bg-white border-2 border-cyan-500/40 rounded-[2.5rem] overflow-hidden shadow-2xl w-full max-w-[460px] h-[660px] flex flex-col justify-between">

            <div class="bg-slate-950 p-6 flex justify-between items-center border-b-4 border-cyan-500 relative">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('storage/' . $setting->logo) }}" alt="{{ $setting->site_name ?? 'Logo' }}"
                        class="w-8 h-8 md:w-10 md:h-10 object-contain transition-transform duration-500 group-hover:scale-110">
                    <div>
                        <h1 class="text-lg font-black text-white leading-none tracking-tight uppercase">
                            DUET CSE <span class="text-cyan-400">{{ $setting->site_name }}</span>
                        </h1>
                        <p class="text-cyan-400/80 font-bold tracking-[0.2em] text-[8px] mt-1 uppercase">DUET, Gazipur
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">PARTICIPANT ID</div>
                    <div class="text-base font-black text-cyan-400 italic">#{{ $team->participant_id ?? $team->id }}
                    </div>
                </div>
            </div>

            <div class="p-6 flex-1 flex flex-col justify-between bg-gradient-to-b from-slate-50 to-white">

                <div class="flex justify-between items-start gap-4">
                    <div>
                        <span
                            class="text-cyan-600 text-[9px] font-black uppercase tracking-widest mb-1 block border-l-2 border-cyan-500 pl-2">
                            OFFICIAL ENTRY PASS
                        </span>
                        <h2 class="text-2xl font-black text-slate-900 uppercase tracking-tight leading-tight">
                            {{ $event->name ?? $team->event->name }}
                        </h2>
                    </div>
                    <div class="bg-white p-2 rounded-2xl shadow-md border border-slate-200/60">
                        {!! QrCode::size(85)->margin(1)->generate(route('event.final_registered', $team->id)) !!}
                    </div>
                </div>

                <div class="border-l-4 border-slate-900 pl-4 my-4">
                    <label class="block text-[8px] uppercase text-slate-400 font-black tracking-widest mb-1">
                        PARTICIPANT NAME
                    </label>
                    <p class="font-black text-2xl text-slate-900 uppercase leading-none tracking-tight">
                        {{ $team->m1_name ?? $team->name }}
                    </p>
                    <p class="text-xs font-bold text-cyan-600 mt-1.5">{{ $team->university_name }}</p>
                </div>

                <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200/60 shadow-sm space-y-3">
                    <div>
                        <label class="block text-[8px] uppercase text-slate-400 font-black tracking-widest mb-0.5">
                            EMAIL ADDRESS
                        </label>
                        <p class="text-xs font-bold text-slate-700 font-mono lowercase">
                            {{ $team->m1_email ?? $team->email }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-2 border-t border-slate-200/60 pt-2">
                        <div>
                            <label class="block text-[8px] uppercase text-slate-400 font-black tracking-widest mb-0.5">
                                PHONE NUMBER
                            </label>
                            <p class="text-xs font-bold text-slate-700 font-mono">{{ $team->m1_phone ?? $team->phone }}
                            </p>
                        </div>
                        @if (isset($team->m1_cf_handle) || isset($team->cf_handle))
                            <div>
                                <label
                                    class="block text-[8px] uppercase text-cyan-600 font-black tracking-widest mb-0.5">
                                    CODEFORCES
                                </label>
                                <p class="text-xs font-bold text-slate-800 font-mono">
                                    {{ $team->m1_cf_handle ?? $team->cf_handle }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-200 flex justify-between items-end">
                    <div class="space-y-1">
                        <p class="text-[7.5px] text-slate-400 font-bold uppercase leading-tight">
                            * Bring institute ID card for verification<br>
                            * Reporting: 08:30 AM @ DUET Campus
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] font-black text-slate-400 uppercase tracking-widest">AUTHORIZED BY</p>
                        <p class="text-[9px] font-black text-cyan-600 italic font-serif mt-0.5">
                            Convener, DUET CSE CARNIVAL 2026
                        </p>
                    </div>
                </div>

            </div>

            <div class="h-2.5 bg-gradient-to-r from-cyan-500 via-blue-600 to-purple-600"></div>
        </div>
    </div>

    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);

            window.onafterprint = function() {
                window.history.back();
            };
        };
    </script>
</body>

</html>
