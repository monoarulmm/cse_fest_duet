@extends('layouts.app')

@section('title', 'Verify OTP | CSE CARNIVAL 2026')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center px-4 relative overflow-hidden">

        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-cyan-500/10 blur-[120px] rounded-full -z-10 opacity-60">
        </div>

        <div
            class="max-w-md w-full glass-nav p-8 md:p-12 rounded-[3.5rem] border border-cyan-500/20 shadow-2xl relative transition-all duration-500">

            <div class="text-center mb-10">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-cyan-500/10 text-cyan-500 text-3xl mb-6 border border-cyan-500/20 shadow-[0_0_25px_rgba(34,211,238,0.1)] animate-pulse">
                    <i class="fa-solid fa-unlock-keyhole"></i>
                </div>
                <h2 class="heading-font text-2xl md:text-3xl font-black uppercase tracking-widest italic">
                    Verify <span class="text-cyan-500">OTP</span>
                </h2>
                <p class="text-[10px] font-bold uppercase mt-4 tracking-[0.2em] opacity-60 leading-relaxed px-4">
                    @if (filter_var(session('reset_identifier'), FILTER_VALIDATE_EMAIL))
                        একটি ৪-ডিজিটের কোড আপনার ইমেইলে পাঠানো হয়েছে
                    @else
                        একটি ৪-ডিজিটের কোড আপনার ফোনে পাঠানো হয়েছে
                    @endif
                </p>
            </div>

            {{-- মেইন ওটিপি ফর্ম --}}
            <form action="{{ route('password.otp.verify') }}" method="POST" class="space-y-8">
                @csrf

                <div class="relative">
                    <input type="text" name="otp" maxlength="4" required
                        class="w-full bg-slate-500/5 border-2 border-cyan-500/20 rounded-2xl p-6 text-center text-4xl font-black tracking-[0.8em] text-cyan-500 outline-none focus:border-cyan-500 focus:ring-8 focus:ring-cyan-500/5 transition-all placeholder:text-slate-800"
                        placeholder="0000" autofocus autocomplete="one-time-code">

                    @error('otp')
                        <div class="flex items-center justify-center gap-2 mt-4 text-red-500 animate-bounce">
                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                            <p class="text-[10px] font-black uppercase tracking-widest">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <button type="submit"
                    class="group relative w-full bg-cyan-500 text-slate-950 font-black py-5 rounded-2xl transition-all duration-500 uppercase text-xs tracking-[0.3em] overflow-hidden shadow-[0_15px_30px_rgba(34,211,238,0.2)] hover:shadow-cyan-500/40 hover:-translate-y-1">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Verify Identity <i class="fa-solid fa-fingerprint text-sm"></i>
                    </span>
                    <div
                        class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]">
                    </div>
                </button>
            </form>

            {{-- রিসেন্ড সেকশন --}}
            <div class="mt-12 text-center border-t border-cyan-500/10 pt-8">
                <form action="{{ route('password.otp.resend') }}" method="POST" id="resend-form">
                    @csrf
                    <input type="hidden" name="identifier" value="{{ session('reset_identifier') }}">

                    <div id="timer-box"
                        class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-slate-500/5 border border-cyan-500/10 mb-4">
                        <i class="fa-regular fa-clock text-cyan-500/50 text-[10px]"></i>
                        <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.1em]">
                            Resend in <span id="timer" class="text-cyan-500">03:00</span>
                        </p>
                    </div>

                    <button type="submit" id="resend-btn" disabled
                        class="block w-full text-slate-500 font-black uppercase text-[11px] tracking-[0.2em] disabled:opacity-30 disabled:cursor-not-allowed hover:text-cyan-500 transition-all group">
                        <i
                            class="fa-solid fa-rotate-right mr-2 group-hover:rotate-180 transition-transform duration-700"></i>
                        Request New Code
                    </button>
                </form>
            </div>
        </div>
    </div>

    <style>
        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .light-mode input {
            background: rgba(0, 0, 0, 0.03);
            color: #0f172a;
        }

        .light-mode #timer-box {
            background: rgba(0, 0, 0, 0.03);
        }
    </style>

    <script>
        let timeLeft = 180;
        const timerElement = document.getElementById('timer');
        const resendBtn = document.getElementById('resend-btn');
        const timerBox = document.getElementById('timer-box');

        const countdown = setInterval(() => {
            if (timeLeft <= 0) {
                clearInterval(countdown);
                timerElement.innerText = "00:00";
                resendBtn.disabled = false;
                timerBox.classList.add('opacity-30');
            } else {
                let minutes = Math.floor(timeLeft / 60);
                let seconds = timeLeft % 60;
                timerElement.innerText =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                timeLeft--;
            }
        }, 1000);
    </script>
@endsection
