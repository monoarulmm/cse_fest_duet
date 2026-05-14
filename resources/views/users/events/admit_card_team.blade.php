<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Admit Card - {{ $team->participant_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
            background-color: #020617;
        }

        @media print {
            @page {
                size: A4;
                margin: 0;
            }

            body {
                background-color: #020617 !important;
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
                width: 480px !important;
                /* Fixed width for consistency */
                height: 680px !important;
                /* Fixed height to prevent 2nd page */
                margin: 0 auto !important;
                border: none !important;
                box-shadow: none !important;
                background-color: #0f172a !important;
            }
        }
    </style>
</head>

<body class="antialiased overflow-x-hidden">

    <!-- Action Buttons -->
    <div class="no-print flex justify-center gap-4 py-8 relative z-50">
        <button onclick="window.history.back()"
            class="bg-slate-800 text-white px-6 py-2 rounded-xl text-xs font-bold hover:bg-slate-700 transition uppercase">
            Back
        </button>
        <button onclick="window.print()"
            class="bg-cyan-500 text-black px-8 py-2 rounded-xl text-xs font-black hover:bg-cyan-400 transition uppercase shadow-lg shadow-cyan-500/20">
            Print Official ID Card
        </button>
    </div>

    <div id="print-area" class="min-h-screen flex items-center justify-center p-4">

        <!-- ID Card Structure -->
        <div
            class="id-card-body relative bg-[#0f172a] border border-cyan-500/30 rounded-[2.5rem] overflow-hidden shadow-2xl w-full max-w-[480px] h-[680px] flex flex-col">

            <!-- Header Section -->
            <div class="bg-[#020617] p-8 flex justify-between items-center border-b-4 border-cyan-500 relative">
                <div class="flex items-center gap-4">
                    <img src="https://upload.wikimedia.org/wikipedia/en/b/be/Dhaka_University_of_Engineering_%26_Technology_Logo.png"
                        class="h-12 brightness-200" alt="DUET Logo">
                    <div>
                        <h1 class="text-xl font-black text-white leading-none tracking-tighter uppercase">
                            CSE <span class="text-cyan-500">FEST</span> 2026
                        </h1>
                        <p class="text-cyan-500/60 font-bold tracking-[0.2em] text-[8px] mt-1 uppercase">DUET, Gazipur
                        </p>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-[8px] font-bold text-slate-500 uppercase tracking-widest">ID NO</div>
                    <div class="text-lg font-black text-white italic">#{{ $team->participant_id }}</div>
                </div>
            </div>

            <!-- Content Body -->
            <div class="p-8 flex-1 flex flex-col justify-between">

                <!-- Event & QR -->
                <div class="flex justify-between items-start">
                    <div>
                        <span
                            class="text-cyan-500 text-[10px] font-black uppercase tracking-widest mb-1 block italic border-l-2 border-cyan-500 pl-2">ENTRY
                            PASS</span>
                        <h2 class="text-3xl font-black text-white uppercase italic leading-tight">{{ $event->name }}
                        </h2>
                    </div>
                    <div class="bg-white p-2 rounded-2xl shadow-xl">
                        {!! QrCode::size(75)->margin(1)->generate(url()->current()) !!}
                    </div>
                </div>

                <!-- Main Info Grid -->
                <div class="grid grid-cols-1 gap-6">
                    <!-- Team Info -->
                    <div>
                        <label
                            class="block text-[9px] uppercase text-cyan-600 font-black tracking-widest mb-1 flex items-center gap-2">
                            TEAM IDENTITY
                        </label>
                        <div class="space-y-1">
                            <p class="font-black text-xl text-white uppercase leading-tight tracking-tight">
                                {{ $team->team_name ?? 'INDIVIDUAL' }}</p>
                            <p class="text-[10px] font-bold text-slate-400">{{ $team->university_name }}</p>
                        </div>
                    </div>

                    <!-- Condition based Contact Info -->
                    <div class="bg-slate-900/50 p-4 rounded-2xl border border-white/5">
                        @if ($event->slug == 'iupc' || isset($team->coach_name))
                            <label
                                class="block text-[8px] uppercase text-cyan-600/60 font-black tracking-widest mb-2 italic">Coach
                                / Faculty In-Charge</label>
                            <p class="text-sm font-black text-slate-200">{{ $team->coach_name }}</p>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5 tracking-wide">
                                {{ $team->coach_phone }} | {{ $team->coach_email }}</p>
                        @else
                            <label
                                class="block text-[8px] uppercase text-cyan-600/60 font-black tracking-widest mb-2 italic">Team
                                Leader Details</label>
                            <p class="text-sm font-black text-slate-200">{{ $team->m1_name }}</p>
                            <p class="text-[10px] text-slate-400 font-medium mt-0.5 tracking-wide">{{ $team->m1_phone }}
                                | {{ $team->m1_email }}</p>
                        @endif
                    </div>
                </div>

                <!-- Team Members List -->
                <div class="space-y-3">
                    <label
                        class="block text-[9px] uppercase text-slate-500 font-black tracking-[0.2em] mb-1">Participants
                        List</label>
                    <div class="flex flex-wrap gap-2">
                        <span
                            class="bg-white/5 border border-white/10 px-3 py-1.5 rounded-lg text-[10px] font-bold text-slate-300">01.
                            {{ $team->m1_name }}</span>
                        @if ($team->m2_name)
                            <span
                                class="bg-white/5 border border-white/10 px-3 py-1.5 rounded-lg text-[10px] font-bold text-slate-400">02.
                                {{ $team->m2_name }}</span>
                        @endif
                        @if ($team->m3_name)
                            <span
                                class="bg-white/5 border border-white/10 px-3 py-1.5 rounded-lg text-[10px] font-bold text-slate-400">03.
                                {{ $team->m3_name }}</span>
                        @endif
                    </div>
                </div>

                <!-- Footer Section -->
                <div class="pt-6 border-t border-white/5 flex justify-between items-end">
                    <div class="space-y-2">
                        <p class="text-[8px] text-slate-500 font-bold uppercase leading-tight italic">
                            * Identity card mandatory<br>
                            * Reporting: 09:30 AM, DUET
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-[8px] font-black text-cyan-600 uppercase tracking-widest">AUTHORIZED BY</p>
                        <p class="text-[10px] font-bold text-slate-300 italic font-serif">Convener, DUET CSE FEST 2026
                        </p>
                    </div>
                </div>
            </div>

            <!-- Bottom Stripe -->
            <div class="h-2 bg-gradient-to-r from-cyan-500 to-purple-600"></div>
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
