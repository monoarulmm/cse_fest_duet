@extends('layouts.app')

@section('content')
    <div class="min-h-screen py-12 md:py-20 px-4">
        <div class="max-w-5xl mx-auto">

            {{-- Main Container --}}
            <div
                class="relative overflow-hidden  backdrop-blur-2xl border border-slate-800/50 rounded-[2rem] md:rounded-[2.5rem] shadow-2xl">

                {{-- Decorative Glow --}}
                <div class="absolute -top-24 -left-24 w-64 h-64 bg-cyan-500/10 rounded-full blur-[100px]"></div>
                <div class="absolute -bottom-24 -right-24 w-64 h-64 bg-purple-500/5 rounded-full blur-[100px]"></div>

                <div class="relative p-6 md:p-12">

                    {{-- Header Section --}}
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-6">
                        <div class="space-y-2">
                           
                            <h1 class="text-3xl md:text-6xl font-black  uppercase tracking-tighter leading-tight">
                                {{ $event->name }}
                            </h1>
                        </div>


                    </div>

                    {{-- Dynamic Content Section --}}
                    <div
                        class="prose prose-invert max-w-none 
                                prose-headings: prose-headings:font-black prose-headings:uppercase prose-headings:tracking-tight
                                prose-p:text-slate-400 prose-p:leading-relaxed
                                prose-strong:text-cyan-400 prose-strong:font-bold
                                custom-event-content">

                        {!! $event->description !!}
                    </div>

                    {{-- Back Button --}}
                    <div class="mt-16 pt-8 border-t border-slate-800/50 flex justify-center">
                        <a href="{{ url()->previous() }}"
                            class="inline-flex items-center gap-4 px-8 py-3 bg-slate-950/30 border border-slate-800 rounded-xl hover:border-cyan-500/50 group transition-all duration-300">
                            <i
                                class="fa-solid fa-arrow-left-long text-cyan-500 group-hover:-translate-x-1 transition-transform"></i>
                            <span
                                class="text-[10px] font-black text-slate-400 group-hover: uppercase tracking-[0.3em]">
                                Return to Hub
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Enhanced Styles --}}
    <style>
        /* Table Container Responsiveness */
        .custom-event-content {
            overflow-x: auto;
        }

        .custom-event-content table {
            width: 100% !important;
            border-collapse: separate;
            border-spacing: 0 10px;
            /* Space between rows */
            background: transparent !important;
            min-width: 600px;
            /* Ensures table doesn't squash on mobile */
        }

        /* Table Header */
        .custom-event-content thead th {
            color: #22d3ee !important;
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px !important;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            padding: 10px 20px !important;
            border: none !important;
            text-align: left;
        }

        /* Table Body Rows */
        .custom-event-content tbody tr {
            background: rgba(30, 41, 59, 0.3) !important;
            transition: transform 0.2s ease, background 0.3s ease;
        }

        .custom-event-content tbody tr:hover {
            background: rgba(34, 211, 238, 0.05) !important;
            transform: scale(1.005);
        }

        /* Cells Design */
        .custom-event-content td {
            padding: 1.25rem 1.5rem !important;
            border-top: 1px solid rgba(51, 65, 85, 0.3) !important;
            border-bottom: 1px solid rgba(51, 65, 85, 0.3) !important;
            color: #94a3b8 !important;
        }

        /* First and Last Column Styling */
        .custom-event-content td:first-child {
            border-left: 1px solid rgba(51, 65, 85, 0.3) !important;
            border-radius: 12px 0 0 12px;
            color: #22d3ee !important;
            font-weight: 700;
            font-family: monospace;
        }

        .custom-event-content td:last-child {
            border-right: 1px solid rgba(51, 65, 85, 0.3) !important;
            border-radius: 0 12px 12px 0;
            color: #f8fafc !important;
        }

        /* Scrollbar styling for table */
        .custom-event-content::-webkit-scrollbar {
            height: 4px;
        }

        .custom-event-content::-webkit-scrollbar-thumb {
            background: rgba(34, 211, 238, 0.2);
            border-radius: 10px;
        }
    </style>
@endsection
