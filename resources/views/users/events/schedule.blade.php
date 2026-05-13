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
            <a href="{{ route('event.dashboard', $event->slug) }}"
                class="text-slate-500 hover:text-green-400 transition-colors uppercase text-sm font-bold tracking-widest">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Event Details
            </a>
        </div>
    </div>
@endsection
