@extends('layouts.app')

@section('custom_css')
    <style>
        :root {
            --card-bg: rgba(15, 23, 42, 0.8);
            --table-header: rgba(34, 211, 238, 0.1);
            --text-main: #ffffff;
            --text-muted: #94a3b8;
            --border-color: rgba(34, 211, 238, 0.2);
        }

        /* Light Mode Colors */
        .light-mode {
            --card-bg: #ffffff;
            --table-header: #f1f5f9;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
        }

        .admin-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .text-main {
            color: var(--text-main);
        }

        .text-muted {
            color: var(--text-muted);
        }

        .stat-badge {
            background: rgba(34, 211, 238, 0.1);
            border: 1px solid rgba(34, 211, 238, 0.2);
            color: #0891b2;
            /* Cyan 600 for better visibility in light mode */
        }
    </style>
@endsection
@section('content')
    <div class="container mx-auto px-4 py-8">

        <div class="flex justify-end mb-4 gap-3">
            <!-- রেজাল্ট টেমপ্লেট ডাউনলোড বাটন -->
            <a href="{{ route('admin.export.result.template', ['event' => $selectedEvent->id]) }}"
                class="bg-cyan-600 hover:bg-cyan-700 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase flex items-center gap-2 transition shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Result Template (.xlsx)
            </a>

            <!-- আপনার আগের এক্সেল ডাউনলোড বাটন (উদাহরণস্বরূপ) -->
            <a href="{{ route('admin.export.excel', ['event_id' => $selectedEvent->id]) }}"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-xs font-bold uppercase flex items-center gap-2 transition shadow-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Download Registration (.xlsx)
            </a>
        </div>

        <div class="bg-slate-900 p-6 rounded-2xl border border-cyan-500/20">
            <h3 class="text-white mb-4">Upload Event Results</h3>

            <!-- ফরম্যাট ডাউনলোড বাটন -->


            <form action="{{ route('admin.upload.result') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="excel_file"
                    class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-cyan-500/10 file:text-cyan-400 hover:file:bg-cyan-500/20">
                <button type="submit" class="mt-4 bg-cyan-500 px-6 py-2 rounded-lg font-bold">Upload Now</button>
            </form>
        </div>

        <div class="p-6 bg-slate-900 border border-slate-800 rounded-3xl">
            <h3 class="text-white font-bold mb-4">Upload University Slots (Excel)</h3>

            <form action="{{ route('admin.slots.upload') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="flex items-center gap-4">
                    <input type="file" name="file"
                        class="bg-slate-950 text-slate-400 border border-slate-800 p-2 rounded-xl text-xs">
                    <button type="submit"
                        class="bg-cyan-500 text-slate-950 px-6 py-2 rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-cyan-400 transition-all">
                        Upload & Update
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <p class="text-[10px] text-slate-500 uppercase tracking-widest font-black mb-2">Excel Column Headers
                    (Required):</p>
                <div class="flex gap-2 text-[9px] font-mono text-cyan-500/80">
                    <span class="bg-slate-950 px-2 py-1 border border-white/5 rounded">university_name</span>
                    <span class="bg-slate-950 px-2 py-1 border border-white/5 rounded">category</span>
                    <span class="bg-slate-950 px-2 py-1 border border-white/5 rounded">max_slots</span>
                </div>
            </div>
        </div>

        {{-- Tabs Section --}}
        <div class="flex gap-6 mb-8 border-b border-cyan-500/10 pb-2 overflow-x-auto">
            @foreach ($events as $e)
                <a href="{{ route('admin.dashboard', ['event_id' => $e->id]) }}"
                    class="heading-font uppercase text-xs tracking-widest pb-2 {{ $selectedEvent->id == $e->id ? 'active-tab text-cyan-500' : 'text-muted hover:text-cyan-400' }}">
                    {{ $e->name }}
                </a>
            @endforeach
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Stats Sidebar --}}
            <div class="w-full lg:w-1/4">
                <div class="admin-card p-6 rounded-3xl sticky top-24 shadow-sm">
                    <h3 class="heading-font text-main mb-6 uppercase text-sm border-b border-cyan-500/20 pb-2">University
                        Stats</h3>
                    <div class="space-y-3">
                        @foreach ($stats as $stat)
                            <div class="flex justify-between items-center text-[10px] uppercase tracking-wider">
                                <span class="text-muted">{{ $stat->university_name }}</span>
                                <span class="stat-badge px-2 py-1 rounded-md">{{ $stat->total }} Reg.</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Main Table --}}
            <div class="w-full lg:w-3/4">
                <div class="admin-card rounded-3xl overflow-hidden shadow-xl">
                    <table class="w-full text-left">
                        <thead
                            class="bg-[var(--table-header)] text-cyan-600 dark:text-cyan-400 heading-font text-[10px] uppercase">
                            <tr>
                                <th class="p-5">Team/Participant</th>
                                <th class="p-5">Details</th>
                                <th class="p-5">Status</th>
                                <th class="p-5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs">
                            @foreach ($teams as $team)
                                <tr class="border-b border-[var(--border-color)] hover:bg-cyan-500/5 transition">
                                    <td class="p-5">
                                        <div class="font-bold text-main text-sm">
                                            {{ $selectedEvent->slug == 'ict-olympiad' ? $team->m1_name : $team->team_name }}
                                        </div>
                                        <div class="text-cyan-600 opacity-80">{{ $team->university_name }}</div>
                                    </td>

                                    <td class="p-5 text-muted">
                                        @if ($selectedEvent->slug == 'iupc')
                                            <div class="text-main font-medium">Coach: {{ $team->coach_name }}</div>
                                            <div class="text-[10px]">CF: {{ $team->m1_cf_handle }},
                                                {{ $team->m2_cf_handle }}</div>
                                        @elseif($selectedEvent->slug == 'project-showcase')
                                            <div class="text-main font-medium">Title: {{ $team->project_title }}</div>
                                            <a href="{{ asset('storage/' . $team->abstract_file) }}"
                                                class="text-cyan-600 underline">PDF Abstract</a>
                                        @elseif($selectedEvent->slug == 'ict-olympiad')
                                            <div class="text-main font-medium">ID: {{ $team->student_id }}</div>
                                            <div>{{ $team->m1_phone }}</div>
                                        @endif
                                    </td>

                                    <td class="p-5">
                                        <div class="flex flex-col gap-1">
                                            <span
                                                class="w-fit text-[9px] px-2 py-0.5 rounded border {{ $team->status == 'verified' ? 'bg-green-500/10 text-green-600 border-green-500/20' : 'bg-yellow-500/10 text-yellow-600 border-yellow-500/20' }}">
                                                {{ strtoupper($team->status) }}
                                            </span>
                                            <span
                                                class="w-fit text-[9px] px-2 py-0.5 rounded border {{ $team->payment_status == 'paid' ? 'bg-cyan-500/10 text-cyan-600 border-cyan-500/20' : 'bg-red-500/10 text-red-600 border-red-500/20' }}">
                                                {{ strtoupper($team->payment_status) }}
                                            </span>
                                        </div>
                                    </td>

                                    <td class="p-5 text-right">
                                        <a href="{{ route('admin.registration.show', $team->id) }}"
                                            class="bg-slate-100 dark:bg-slate-800 text-main px-3 py-1.5 rounded-lg border border-[var(--border-color)] hover:border-cyan-500 transition text-[10px] uppercase font-bold tracking-wider inline-block">
                                            Details →
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
