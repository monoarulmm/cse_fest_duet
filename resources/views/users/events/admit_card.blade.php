@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-12 px-6">
        {{-- Print Action Button --}}
        <div class="mb-6 flex justify-end no-print">
            <button onclick="window.print()"
                class="bg-cyan-500 text-slate-900 px-6 py-2 rounded-xl font-bold uppercase text-xs flex items-center gap-2 hover:scale-105 transition">
                <i class="fas fa-print"></i> Print Admit Card
            </button>
        </div>

        {{-- Admit Card Content --}}
        <div id="admit-card"
            class="bg-white text-slate-900 rounded-[2rem] overflow-hidden shadow-2xl border-4 border-double border-slate-200 relative">

            {{-- Header --}}
            <div class="bg-slate-900 text-white p-8 flex justify-between items-center border-b-4 border-cyan-500">
                <div>
                    <h1 class="text-2xl font-black uppercase tracking-tighter">CSE <span class="text-cyan-500">FEST</span>
                        2026</h1>
                    <p class="text-[10px] text-cyan-500 font-bold tracking-[0.3em] uppercase">DUET, Gazipur</p>
                </div>
                <div class="text-right">
                    <div class="text-xs font-mono uppercase opacity-70">Participant ID</div>
                    <div class="text-xl font-black text-cyan-400">#{{ $team->participant_id }}</div>
                </div>
            </div>

            <div class="p-10">
                <div class="flex justify-between items-start mb-10">
                    <div>
                        <h2 class="text-3xl font-black text-slate-800 uppercase italic">{{ $event->name }}</h2>
                        <p class="text-slate-500 font-bold">Registration Confirmation & Entry Pass</p>
                    </div>
                    <div class="bg-slate-100 p-2 rounded-xl border border-slate-200">
                        {!! QrCode::size(80)->generate(route('event.admit_card', [$event->slug, $team->team_id])) !!}
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    {{-- Team Information --}}
                    <div class="space-y-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-cyan-600 border-b pb-1">Team Details
                        </h3>
                        <div>
                            <label class="block text-[10px] uppercase text-slate-400 font-bold">Team Name</label>
                            <p class="font-bold text-lg text-slate-800">{{ $team->team_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase text-slate-400 font-bold">Team Id</label>
                            <p class="font-bold text-lg text-slate-800">{{ $team->team_id ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <label class="block text-[10px] uppercase text-slate-400 font-bold">Institution</label>
                            <p class="font-semibold text-slate-700">{{ $team->university_name }}</p>
                        </div>
                    </div>

                    {{-- Coach/Lead Information --}}
                    <div class="space-y-4">
                        <h3 class="text-xs font-black uppercase tracking-widest text-cyan-600 border-b pb-1">Primary Contact
                        </h3>
                        @if ($event->slug == 'iupc')
                            <div>
                                <label class="block text-[10px] uppercase text-slate-400 font-bold">Coach Name</label>
                                <p class="font-bold text-slate-800">{{ $team->coach_name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase text-slate-400 font-bold">Coach Phone</label>
                                <p class="font-bold text-slate-800">{{ $team->coach_phone ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase text-slate-400 font-bold">Coach Email</label>
                                <p class="font-bold text-slate-800">{{ $team->coach_email ?? 'N/A' }}</p>
                            </div>
                        @else
                            <div>
                                <label class="block text-[10px] uppercase text-slate-400 font-bold">Team Lead (Name)</label>
                                <p class="font-bold text-slate-800">{{ $team->m1_name }}</p>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase text-slate-400 font-bold">Team Lead
                                    (Phone)</label>
                                <p class="font-bold text-slate-800">{{ $team->m1_phone }}</p>
                            </div>
                            <div>
                                <label class="block text-[10px] uppercase text-slate-400 font-bold">Team Lead
                                    (Email)</label>
                                <p class="font-bold text-slate-800">{{ $team->m1_email }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Participants List --}}
                <div class="mt-10 p-6 bg-slate-50 rounded-2xl border border-slate-200">
                    <h3 class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-4">Verified Participants
                    </h3>
                    <div class="flex flex-wrap gap-4">
                        <div class="px-4 py-2 bg-white rounded-lg border border-slate-200 text-xs font-bold">
                            {{ $team->m1_name }}</div>
                        @if ($team->m2_name)
                            <div class="px-4 py-2 bg-white rounded-lg border border-slate-200 text-xs font-bold">
                                {{ $team->m2_name }}</div>
                        @endif
                        @if ($team->m3_name)
                            <div class="px-4 py-2 bg-white rounded-lg border border-slate-200 text-xs font-bold">
                                {{ $team->m3_name }}</div>
                        @endif
                    </div>
                </div>

                {{-- Footer Info --}}
                <div class="mt-12 pt-6 border-t border-dashed border-slate-300 flex justify-between items-center">
                    <div class="text-[10px] text-slate-400 leading-tight">
                        * Please bring a printed copy or digital PDF of this document.<br>
                        * Carry your University ID card for physical verification.
                    </div>
                    <div class="text-right">
                        <p class="text-[10px] font-black uppercase text-slate-800">Authorized by</p>
                        <p class="text-xs font-serif italic text-slate-500">Convener, DUET CSE FEST 2026</p>
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
            }
        }
    </style>
@endsection
