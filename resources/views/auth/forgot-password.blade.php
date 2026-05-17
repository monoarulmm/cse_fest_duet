@extends('layouts.app')

@section('title', 'Reset Password | CSE CARNIVAL 2026')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center px-4 relative overflow-hidden">

        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-cyan-500/10 blur-[150px] rounded-full -z-10 opacity-50">
        </div>

        <div
            class="max-w-md w-full glass-nav p-8 md:p-12 rounded-[3.5rem] border border-cyan-500/20 shadow-2xl relative transition-all duration-500">

            <div class="text-center mb-10">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-cyan-500/10 text-cyan-500 text-3xl mb-6 border border-cyan-500/20 shadow-[0_0_25px_rgba(34,211,238,0.1)]">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <h2 class="heading-font text-2xl md:text-3xl font-black uppercase tracking-widest italic leading-none">
                    Reset <span class="text-cyan-500">Access</span>
                </h2>
                <p class="text-[9px] font-bold uppercase mt-4 tracking-[0.3em] opacity-60">
                    Security Verification Required
                </p>
            </div>

            @if (session('status'))
                <div
                    class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 text-[11px] font-black p-4 rounded-2xl mb-8 flex items-center gap-3 animate-pulse uppercase tracking-wider">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form action="{{ route('password.request') }}" method="POST" class="space-y-8">
                @csrf

                <div class="space-y-3">
                    <label class="text-[10px] text-cyan-500 font-black uppercase ml-4 tracking-[0.2em]">
                        Registered Identifier
                    </label>

                    <div class="relative group">
                        <span
                            class="absolute left-6 top-1/2 -translate-y-1/2 text-cyan-500/50 group-focus-within:text-cyan-500 transition-colors">
                            <i class="fa-solid fa-paper-plane text-sm"></i>
                        </span>
                        <input type="text" name="identifier" required
                            class="w-full bg-slate-500/5 border border-cyan-500/10 rounded-2xl py-5 pl-14 pr-6 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/5 outline-none transition-all duration-300 placeholder:text-slate-500 placeholder:uppercase placeholder:text-[10px] placeholder:tracking-widest shadow-inner"
                            placeholder="Email or Phone Number">
                    </div>

                    @error('identifier')
                        <div class="flex items-center gap-2 mt-2 ml-4 text-red-500">
                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                            <p class="text-[10px] font-bold uppercase tracking-tight">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <button type="submit"
                    class="group relative w-full bg-cyan-500 text-slate-950 font-black py-5 rounded-2xl transition-all duration-500 uppercase text-xs tracking-[0.3em] overflow-hidden shadow-[0_15px_30px_rgba(34,211,238,0.2)] hover:shadow-cyan-500/40 hover:-translate-y-1">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Request Link <i
                            class="fa-solid fa-arrow-right-long text-[10px] group-hover:translate-x-2 transition-transform"></i>
                    </span>

                    <div
                        class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]">
                    </div>
                </button>
            </form>

            <div class="mt-10 text-center">
                <a href="{{ route('login') }}"
                    class="text-[10px] font-black text-slate-500 hover:text-cyan-500 transition-all uppercase tracking-widest flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-chevron-left text-[8px] group-hover:-translate-x-1 transition-transform"></i>
                    Return to Login
                </a>
            </div>
        </div>
    </div>

    <style>
        @keyframes shimmer {
            100% {
                transform: translateX(100%);
            }
        }

        /* Light/Dark Mode Compatibility */
        .glass-nav {
            /* Layout CSS ভেরিয়েবল অনুযায়ী অটো কালার চেঞ্জ হবে */
            color: var(--text-color);
        }

        /* Light Mode এর জন্য ইনপুট বক্সকে একটু পরিষ্কার করা */
        .light-mode .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(34, 211, 238, 0.3);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
        }

        .light-mode input {
            background: rgba(0, 0, 0, 0.03);
            color: #0f172a;
        }

        .light-mode .text-slate-500 {
            color: #64748b;
        }
    </style>
@endsection
