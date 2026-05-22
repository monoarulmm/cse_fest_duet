@extends('layouts.app')

@section('title', 'Payment Failed — DUET CSE CARNIVAL 2026')

@section('custom_css')
<style>
    .pay-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-mid);
        box-shadow: var(--shadow-card);
    }
    .pay-accent-bar {
        background: linear-gradient(90deg, #f43f5e, #e11d48);
    }
    .pay-icon-wrap {
        background: rgba(244, 63, 94, 0.10);
        border: 1px solid rgba(244, 63, 94, 0.2);
    }
    .pay-info-box {
        background: var(--bg-elevated);
        border: 1px solid var(--border-soft);
    }
    .pay-info-divider { border-color: var(--border-mid); }

    .pay-btn-primary {
        background: var(--accent);
        color: #020617;
        transition: opacity 0.2s, transform 0.15s;
    }
    .pay-btn-primary:hover  { opacity: 0.88; }
    .pay-btn-primary:active { transform: scale(0.97); }

    .pay-btn-secondary {
        background: transparent;
        border: 1px solid var(--border-accent);
        color: var(--text-secondary);
        transition: background 0.2s, color 0.2s, transform 0.15s;
    }
    .pay-btn-secondary:hover  { background: var(--accent-dim); color: var(--text-primary); }
    .pay-btn-secondary:active { transform: scale(0.97); }

    .pay-contact-link { color: var(--accent); }
    .pay-contact-link:hover { opacity: 0.8; }

    /* pulse ring around icon */
    @keyframes ring-pulse {
        0%   { transform: scale(1);   opacity: 0.5; }
        100% { transform: scale(1.6); opacity: 0; }
    }
    .ring-pulse {
        animation: ring-pulse 1.8s ease-out infinite;
        border: 2px solid rgba(244, 63, 94, 0.4);
        border-radius: 9999px;
        position: absolute;
        inset: 0;
    }
</style>
@endsection

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="pay-card w-full max-w-md rounded-[2.25rem] overflow-hidden relative">

        {{-- Top accent bar --}}
        <div class="pay-accent-bar h-1.5 w-full"></div>

        <div class="p-8 md:p-10 text-center">

            {{-- Animated icon --}}
            <div class="flex justify-center mb-7">
                <div class="relative w-20 h-20 flex items-center justify-center">
                    <div class="ring-pulse"></div>
                    <div class="pay-icon-wrap w-20 h-20 rounded-full flex items-center justify-center relative z-10">
                        <svg class="w-9 h-9" fill="none" stroke="#f43f5e" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </div>
                </div>
            </div>

            {{-- Title --}}
            <h1 class="heading-font font-black text-2xl uppercase tracking-tight" style="color:var(--text-primary)">
                Payment Failed
            </h1>

            {{-- Subtitle badge --}}
            <div class="inline-flex items-center gap-1.5 mt-3 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"
                style="background:rgba(244,63,94,0.10); color:#f43f5e; border:1px solid rgba(244,63,94,0.2)">
                <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                Transaction Unsuccessful
            </div>

            {{-- Message --}}
            <p class="text-sm mt-5 leading-relaxed px-2"
                style="color:var(--text-secondary); font-family:'JetBrains Mono',monospace">
                {{ $message ?? 'পেমেন্ট সফল হয়নি। আপনার অ্যাকাউন্ট থেকে টাকা কেটে থাকলে ২৪ ঘণ্টার মধ্যে তা স্বয়ংক্রিয়ভাবে ফেরত চলে আসবে।' }}
            </p>

            {{-- Info box --}}
            @if (!empty($sp_code) || $registration)
                <div class="pay-info-box mt-6 rounded-2xl p-4 text-left space-y-2.5">

                    @if (!empty($sp_code))
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-black uppercase tracking-[0.2em]"
                                style="color:var(--text-muted)">Gateway Status</span>
                            <span class="font-mono font-bold text-[11px] px-2.5 py-0.5 rounded-lg"
                                style="background:rgba(244,63,94,0.10); color:#f43f5e; border:1px solid rgba(244,63,94,0.18)">
                                {{ $sp_code }}
                            </span>
                        </div>
                    @endif

                    @if ($registration)
                        @if (!empty($sp_code))
                            <div class="pay-info-divider border-t pt-2"></div>
                        @endif
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-black uppercase tracking-[0.2em]"
                                style="color:var(--text-muted)">Participant Ref</span>
                            <span class="font-mono font-bold text-[11px]"
                                style="color:var(--text-primary)">#{{ $registration->id }}</span>
                        </div>
                    @endif

                </div>
            @endif

            {{-- Buttons --}}
            <div class="mt-8 space-y-3">
                @if ($registration)
                    <a href="{{ route('payment.make', $registration->id) }}"
                        class="pay-btn-primary flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-lg"
                        style="box-shadow: 0 8px 20px rgba(34,211,238,0.25)">
                        <i class="fa-solid fa-rotate-right text-sm"></i>
                        Try Payment Again
                    </a>
                @endif

                <a href="{{ url('/event/' . ($slug ?? 'event')) }}"
                    class="pay-btn-secondary flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl text-[11px] font-bold uppercase tracking-widest">
                    <i class="fa-solid fa-arrow-left text-xs"></i>
                    Go Back to Event Page
                </a>
            </div>

            {{-- Divider --}}
            <div class="mt-8 pt-6" style="border-top:1px solid var(--border-soft)">
                <p class="text-[11px]" style="color:var(--text-muted); font-family:'JetBrains Mono',monospace">
                    কোনো সমস্যা হলে যোগাযোগ করুন —
                </p>
                <a href="mailto:csefest2026@duet.ac.bd"
                    class="pay-contact-link text-[11px] font-bold font-mono mt-1 inline-block">
                    csefest2026@duet.ac.bd
                </a>
            </div>

        </div>
    </div>
</div>
@endsection