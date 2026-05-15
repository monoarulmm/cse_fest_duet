@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto p-6 bg-slate-900/50 border border-slate-800 rounded-[2rem]">
        <h1 class="text-3xl font-black text-cyan-400 mb-6">{{ $event->name }}</h1>

        <!-- CKEditor কন্টেন্ট দেখানোর নিয়ম -->
        <div class="prose prose-invert max-w-none text-slate-300">
            {!! $event->description !!}
        </div>

        {{-- Back Button --}}
        <div class="mt-16 text-center">
            {{-- ২. জাভাস্ক্রিপ্ট ব্যাক বাটন (হিস্ট্রি অনুযায়ী কাজ করবে) --}}
            <button onclick="window.history.back()"
                class="flex items-center gap-3 px-6 py-3 bg-slate-900 border border-slate-700 rounded-xl hover:border-cyan-500 group transition-all">
                <i class="fa-solid fa-chevron-left text-cyan-500 group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-xs font-bold text-slate-400 group-hover:text-white uppercase tracking-[0.2em]">
                    Back to previous
                </span>
            </button>
        </div>
    </div>
@endsection
