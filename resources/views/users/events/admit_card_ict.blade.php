@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-6 min-h-screen">
        {{-- Print Button --}}
        <div class="mb-6 flex justify-end no-print">
            <button onclick="window.print()"
                class="bg-indigo-600 text-white px-6 py-2 rounded-xl font-bold uppercase text-xs flex items-center gap-2 hover:scale-105 transition shadow-lg shadow-indigo-500/20">
                <i class="fas fa-print"></i> Print Admit Card
            </button>
        </div>

        <div id="admit-card"
            class="bg-white dark:bg-slate-900 rounded-[2rem] overflow-hidden shadow-2xl border border-slate-200 dark:border-slate-800 print:border-2 print:border-slate-300">
            {{-- Header Section --}}
            <div class="bg-indigo-950 p-8 flex justify-between items-center text-white print:bg-indigo-950">
                <div>
                    <h1 class="text-2xl font-black uppercase tracking-tighter italic">ICT <span
                            class="text-indigo-400">Olympiad</span> 2026</h1>
                    <p class="text-[10px] text-indigo-400 font-bold tracking-[0.3em] uppercase">DUET, Gazipur</p>
                </div>
                <div class="text-right">
                    <p class="text-[10px] uppercase opacity-60">Reg ID</p>
                    <p class="text-xl font-mono font-black text-indigo-400">#{{ str_pad($team->id, 5, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
            </div>

            <div class="p-10">
                <div class="flex flex-col md:flex-row justify-between items-start gap-10">
                    <div class="space-y-6 flex-1">
                        <div>
                            <h2 class="text-4xl font-black text-slate-800 dark:text-white uppercase leading-none">
                                {{ $event->name }}</h2>
                            <span
                                class="inline-block mt-2 px-3 py-1 bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-black uppercase tracking-widest rounded-full">Official
                                Entry Pass</span>
                        </div>

                        <div class="space-y-4">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-indigo-500">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div>
                                    <label class="block text-[9px] uppercase text-slate-400 font-black">Participant
                                        Name</label>
                                    <p class="text-lg font-bold text-slate-800 dark:text-white">{{ $team->m1_name }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-indigo-500">
                                    <i class="fa-solid fa-id-card"></i>
                                </div>
                                <div>
                                    <label class="block text-[9px] uppercase text-slate-400 font-black">Student ID</label>
                                    <p class="text-lg font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ $team->student_id ?? 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-indigo-500">
                                    <i class="fa-solid fa-university"></i>
                                </div>
                                <div>
                                    <label class="block text-[9px] uppercase text-slate-400 font-black">Institution</label>
                                    <p class="font-bold text-slate-600 dark:text-slate-300">{{ $team->university_name }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- QR Code --}}
                    <div class="text-center p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                        {!! QrCode::size(120)->margin(1)->generate(url()->current()) !!}
                        <p class="text-[8px] font-black uppercase text-slate-400 mt-2">Scan to Verify</p>
                    </div>
                </div>

                {{-- Footer Info --}}
                <div class="mt-12 pt-8 border-t border-slate-100 dark:border-slate-800 flex justify-between items-end">
                    <div class="text-[10px] text-slate-400 space-y-1 font-medium">
                        <p>• Reporting Time: 09:30 AM</p>
                        <p>• Venue: CSE Dept, DUET</p>
                        <p>• Please bring your Institutional ID Card.</p>
                    </div>
                    <div class="text-right">
                        <div class="w-32 border-b border-slate-200 dark:border-slate-700 h-8 mb-1"></div>
                        <p class="text-[10px] font-black uppercase text-slate-800 dark:text-white">Authorized by</p>
                        <p class="text-[8px] font-bold text-slate-400 uppercase leading-none">DUET CSE FEST 2026</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white !important;
            }

            #admit-card {
                border: none !important;
                box-shadow: none !important;
                margin: 0 !important;
            }

            .dark {
                color-scheme: light !important;
            }
        }
    </style>
@endsection
