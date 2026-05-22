@extends('layouts.app')

@section('title', 'Payment Error — DUET CSE CARNIVAL 2026')

@section('custom_css')
<style>
    .err-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-mid);
        box-shadow: var(--shadow-card);
    }
    .err-accent-bar {
        background: linear-gradient(90deg, #f59e0b, #d97706);
    }
    .err-icon-wrap {
        background: rgba(245, 158, 11, 0.10);
        border: 1px solid rgba(245, 158, 11, 0.2);
    }
    .err-info-box {
        background: var(--bg-elevated);
        border: 1px solid var(--border-soft);
    }
    .err-info-divider { border-color: var(--border-mid); }

    .err-btn-primary {
        background: var(--accent);
        color: #020617;
        transition: opacity 0.2s, transform 0.15s;
    }
    .err-btn-primary:hover  { opacity: 0.88; }
    .err-btn-primary:active { transform: scale(0.97); }

    .err-btn-secondary {
        background: transparent;
        border: 1px solid var(--border-accent);
        color: var(--text-secondary);
        transition: background 0.2s, color 0.2s, transform 0.15s;
    }
    .err-btn-secondary:hover  { background: var(--accent-dim); color: var(--text-primary); }
    .err-btn-secondary:active { transform: scale(0.97); }

    .err-contact-link { color: var(--accent); }
    .err-contact-link:hover { opacity: 0.8; }

    /* code block for error type */
    .err-code {
        background: var(--bg-elevated);
        border: 1px solid var(--border-soft);
        color: var(--text-secondary);
        font-family: 'JetBrains Mono', monospace;
    }

    @keyframes warn-pulse {
        0%   { transform: scale(1);   opacity: 0.45; }
        100% { transform: scale(1.65); opacity: 0; }
    }
    .warn-pulse {
        animation: warn-pulse 2s ease-out infinite;
        border: 2px solid rgba(245, 158, 11, 0.35);
        border-radius: 9999px;
        position: absolute;
        inset: 0;
    }
</style>
@endsection

@section('content')
<div class="min-h-[80vh] flex items-center justify-center px-4 py-16">
    <div class="err-card w-full max-w-md rounded-[2.25rem] overflow-hidden relative">

        {{-- Top accent bar --}}
        <div class="err-accent-bar h-1.5 w-full"></div>

        <div class="p-8 md:p-10 text-center">

            {{-- Animated icon --}}
            <div class="flex justify-center mb-7">
                <div class="relative w-20 h-20 flex items-center justify-center">
                    <div class="warn-pulse"></div>
                    <div class="err-icon-wrap w-20 h-20 rounded-full flex items-center justify-center relative z-10">
                        @if (isset($error_type) && $error_type === 'gateway_error')
                            {{-- Gateway / network error icon --}}
                            <svg class="w-9 h-9" fill="none" stroke="#f59e0b" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                            </svg>
                        @else
                            {{-- Generic system error icon --}}
                            <svg class="w-9 h-9" fill="none" stroke="#f59e0b" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Title --}}
            <h1 class="heading-font font-black text-2xl uppercase tracking-tight" style="color:var(--text-primary)">
                @if (isset($error_type) && $error_type === 'gateway_error')
                    Payment Error
                @else
                    System Error
                @endif
            </h1>

            {{-- Subtitle badge --}}
            <div class="inline-flex items-center gap-1.5 mt-3 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest"
                style="background:rgba(245,158,11,0.10); color:#f59e0b; border:1px solid rgba(245,158,11,0.2)">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                @if (isset($error_type) && $error_type === 'gateway_error')
                    Gateway Unreachable
                @else
                    Unexpected Error
                @endif
            </div>

            {{-- Message --}}
            <p class="text-sm mt-5 leading-relaxed px-2"
                style="color:var(--text-secondary); font-family:'JetBrains Mono',monospace">
                {{ $message ?? 'একটি সিস্টেম এরর হয়েছে। পেমেন্ট প্রসেস হয়নি। অনুগ্রহ করে কিছুক্ষণ পর আবার চেষ্টা করুন।' }}
            </p>

            {{-- Error type + registration info --}}
            @if (isset($error_type) || $registration ?? false)
                <div class="err-info-box mt-6 rounded-2xl p-4 text-left space-y-2.5">

                    @if (isset($error_type))
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] font-black uppercase tracking-[0.2em]"
                                style="color:var(--text-muted)">Error Type</span>
                            <span class="err-code text-[10px] font-bold px-2.5 py-0.5 rounded-lg">
                                {{ $error_type }}
                            </span>
                        </div>
                    @endif

                    @if ($registration ?? false)
                        @if (isset($error_type))
                            <div class="err-info-divider border-t pt-2"></div>
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

                @if ($registration ?? false)
                    {{-- Registration আছে → retry করার সুযোগ দাও --}}
                    <a href="{{ route('payment.make', $registration->id) }}"
                        class="err-btn-primary flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-lg"
                        style="box-shadow: 0 8px 20px rgba(34,211,238,0.25)">
                        <i class="fa-solid fa-rotate-right text-sm"></i>
                        আবার চেষ্টা করুন
                    </a>

                    <a href="{{ route('event.dashboard', $slug ?? 'event') }}"
                        class="err-btn-secondary flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl text-[11px] font-bold uppercase tracking-widest">
                        <i class="fa-solid fa-arrow-left text-xs"></i>
                        Event Page এ ফিরে যান
                    </a>

                @else
                    {{-- Registration নেই → শুধু event page এ ফেরো --}}
                    <a href="{{ route('event.dashboard', $slug ?? 'event') }}"
                        class="err-btn-primary flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl text-[11px] font-black uppercase tracking-widest shadow-lg"
                        style="box-shadow: 0 8px 20px rgba(34,211,238,0.25)">
                        <i class="fa-solid fa-arrow-left text-sm"></i>
                        Go Back to Event Page
                    </a>
                @endif

            </div>

            {{-- Contact --}}
            <div class="mt-8 pt-6" style="border-top:1px solid var(--border-soft)">
                <p class="text-[11px]" style="color:var(--text-muted); font-family:'JetBrains Mono',monospace">
                    কোনো সমস্যা হলে যোগাযোগ করুন —
                </p>
                <a href="mailto:csefest2026@duet.ac.bd"
                    class="err-contact-link text-[11px] font-bold font-mono mt-1 inline-block">
                    csefest2026@duet.ac.bd
                </a>
            </div>

        </div>
    </div>
</div>
@endsection