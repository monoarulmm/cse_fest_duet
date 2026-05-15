<!DOCTYPE html>
<html lang="bn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Result & Seat Plan | DUET CSE FEST 2026</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Inter:wght@300;400;700;900&display=swap"
        rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #020617;
        }

        .cyber-font {
            font-family: 'Orbitron', sans-serif;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 5px;
        }

        ::-webkit-scrollbar-track {
            background: #020617;
        }

        ::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 10px;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .print-card {
                background: white !important;
                color: black !important;
                border: 2px solid #000 !important;
                box-shadow: none !important;
                width: 100% !important;
                margin: 0 !important;
            }

            .text-cyan-500 {
                color: #0891b2 !important;
            }
        }
    </style>
</head>

<body class="antialiased text-slate-300">

    <div class="min-h-screen py-10 px-4 flex items-center justify-center relative overflow-hidden">

        <div class="absolute top-0 left-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-[120px] pointer-events-none">
        </div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-600/10 rounded-full blur-[120px] pointer-events-none">
        </div>

        <div class="max-w-xl w-full z-10">

            <div class="text-center mb-10">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 bg-cyan-500/10 border border-cyan-500/20 rounded-full mb-4">
                    <span class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse"></span>
                    <span class="text-[10px] cyber-font font-bold text-cyan-500 tracking-widest uppercase">Fest Portal
                        2026</span>
                </div>
                <h1 class="text-4xl font-black text-white italic tracking-tighter uppercase">CSE <span
                        class="text-cyan-400">Fest</span> Status</h1>
            </div>

            <div
                class="print-card relative bg-[#0d1117] border border-white/10 rounded-[2.5rem] shadow-2xl overflow-hidden transition-all duration-500 hover:border-cyan-500/30">

                <div class="bg-gradient-to-r from-cyan-500/10 via-transparent p-8 pb-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <p
                                class="text-[9px] font-black text-slate-500 uppercase tracking-[0.3em] mb-1 leading-none">
                                Official Digital Pass</p>
                            <h2 class="text-white text-lg font-black uppercase tracking-tight leading-tight">
                                {{ $result->university_name ?? 'Dhaka University of Engineering & Technology' }}
                            </h2>
                        </div>
                        <div class="bg-slate-900 border border-white/5 p-3 rounded-2xl shadow-inner">
                            <i class="fa-solid fa-qrcode text-cyan-500 text-xl"></i>
                        </div>
                    </div>
                </div>

                <div class="p-8 pt-0">

                    <div class="bg-slate-950/40 border border-white/5 p-6 rounded-3xl mb-6 text-center group">
                        <span
                            class="text-[8px] text-slate-500 font-bold uppercase tracking-[0.4em] block mb-2">PARTICIPANT_IDENTITY</span>
                        <h3
                            class="text-2xl font-black text-white uppercase tracking-tighter group-hover:text-cyan-400 transition-colors">
                            {{ $result->team_name ?? ($result->name ?? 'PARTICIPANT NAME') }}
                        </h3>
                        <p class="text-cyan-500/60 font-mono text-[10px] mt-2 tracking-widest">ID:
                            #{{ $result->participant_id ?? '0000-XX' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/5">
                            <p class="text-[8px] text-slate-600 font-bold uppercase tracking-widest mb-1">Target Event
                            </p>
                            <p class="text-white text-[11px] font-black uppercase tracking-tight italic">
                                {{ $result->event_name ?? 'N/A' }}</p>
                        </div>
                        <div class="bg-slate-950/60 p-4 rounded-2xl border border-white/5">
                            <p class="text-[8px] text-slate-600 font-bold uppercase tracking-widest mb-1">Status</p>
                            <div class="flex items-center gap-2">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-500"></span>
                                <p class="text-cyan-400 text-[11px] font-black uppercase tracking-widest">
                                    {{ $result->result_status ?? 'PENDING' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-950/80 border border-white/5 rounded-3xl overflow-hidden shadow-inner">
                        <div class="bg-white/5 px-5 py-3 border-b border-white/5 flex items-center gap-2">
                            <i class="fa-solid fa-map-location-dot text-cyan-500 text-[10px]"></i>
                            <span class="text-[9px] text-slate-400 font-black uppercase tracking-widest">Venue Details &
                                Seat Plan</span>
                        </div>

                        <div class="p-0">
                            <table class="w-full text-[11px] uppercase font-bold text-center">
                                <thead>
                                    <tr class="text-slate-600 border-b border-white/5">
                                        <th class="py-4 border-r border-white/5">Lab / Room</th>
                                        <th class="py-4 border-r border-white/5">Seat / PC</th>
                                        <th class="py-4">Floor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="text-white">
                                        <td class="py-5 border-r border-white/5">{{ $result->room_no ?? 'CSE-101' }}
                                        </td>
                                        <td class="py-5 border-r border-white/5 text-cyan-500 cyber-font text-sm">
                                            {{ $result->seat_no ?? 'PC-00' }}</td>
                                        <td class="py-5">{{ $result->floor ?? '3rd' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="mt-6 flex gap-3 items-start opacity-60">
                        <i class="fa-solid fa-circle-exclamation text-cyan-500 text-[10px] mt-1"></i>
                        <p class="text-[8px] text-slate-400 leading-relaxed italic uppercase tracking-wider">
                            This document is required at the entry point. Participant must carry university ID card
                            along with this pass.
                        </p>
                    </div>
                </div>

                <div class="bg-slate-900/50 p-6 border-t border-white/5 flex items-center justify-between no-print">
                    <button onclick="window.print()"
                        class="bg-white hover:bg-cyan-500 text-slate-950 px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-tighter flex items-center gap-2 transition-all">
                        <i class="fa-solid fa-file-pdf"></i> Save Document
                    </button>

                    <div class="flex gap-2">
                        <button
                            class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-white transition-all">
                            <i class="fa-solid fa-share-nodes text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="mt-10 text-center opacity-30">
                <p class="text-[9px] font-black uppercase tracking-[0.5em] text-slate-500">
                    Developed By DUET CSE Community
                </p>
            </div>
        </div>
    </div>

</body>

</html>
