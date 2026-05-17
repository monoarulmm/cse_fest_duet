@extends('layouts.app')

@section('title', 'Recover Access | CARNIVAl 2026')

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center px-4 relative overflow-hidden">

        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-cyan-500/10 blur-[120px] rounded-full -z-10 opacity-60">
        </div>

        <div
            class="max-w-md w-full glass-nav p-8 md:p-12 rounded-[3.5rem] border border-cyan-500/20 shadow-2xl relative transition-all duration-500">

            <div class="text-center mb-10">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 rounded-3xl bg-cyan-500/10 text-cyan-500 text-3xl mb-6 border border-cyan-500/20 shadow-[0_0_25px_rgba(34,211,238,0.1)]">
                    <i class="fa-solid fa-key text-cyan-400 text-xl"></i>
                </div>
                <h2 class="heading-font text-2xl md:text-3xl font-black uppercase tracking-widest italic">
                    Recover <span class="text-cyan-500">Access</span>
                </h2>
                <p class="text-[10px] font-bold uppercase mt-4 tracking-[0.2em] opacity-60 leading-relaxed px-4">
                    Enter your email to receive a secure decryption link
                </p>
            </div>

            {{-- Status Alert --}}
            @if (session('status'))
                <div
                    class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-500 text-[10px] font-black p-4 rounded-2xl mb-8 flex items-center gap-3 animate-pulse uppercase tracking-wider">
                    <i class="fa-solid fa-circle-check"></i>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            <form action="{{ url('password.email') }}" method="POST" class="space-y-8">
                @csrf

                <div class="space-y-3">
                    <label class="text-[10px] text-cyan-500 font-black uppercase ml-4 tracking-[0.2em]">
                        Recovery Email
                    </label>

                    <div class="relative group">
                        <span
                            class="absolute left-6 top-1/2 -translate-y-1/2 text-cyan-500/50 group-focus-within:text-cyan-500 transition-colors">
                            <i class="fa-solid fa-at text-sm"></i>
                        </span>
                        <input type="email" name="email" required
                            class="w-full bg-slate-500/5 border border-cyan-500/10 rounded-2xl py-5 pl-14 pr-6 text-sm focus:border-cyan-500 focus:ring-4 focus:ring-cyan-500/5 outline-none transition-all duration-300 placeholder:text-slate-600 placeholder:uppercase placeholder:text-[9px] placeholder:tracking-widest"
                            placeholder="Enter your registered email">
                    </div>

                    @error('email')
                        <div class="flex items-center gap-2 mt-2 ml-4 text-red-500">
                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                            <p class="text-[10px] font-bold uppercase tracking-tight">{{ $message }}</p>
                        </div>
                    @enderror
                </div>

                <button type="submit"
                    class="group relative w-full bg-cyan-500 text-slate-950 font-black py-5 rounded-2xl transition-all duration-500 uppercase text-[11px] tracking-[0.3em] overflow-hidden shadow-[0_15px_30px_rgba(34,211,238,0.2)] hover:shadow-cyan-500/40 hover:-translate-y-1">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Send Reset Link <i
                            class="fa-solid fa-paper-plane text-[10px] group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                    </span>

                    <div
                        class="absolute inset-0 w-full h-full bg-gradient-to-r from-transparent via-white/30 to-transparent -translate-x-full group-hover:animate-[shimmer_2s_infinite]">
                    </div>
                </button>
            </form>

            <div class="mt-10 text-center">
                <a href="{{ url('login') }}"
                    class="text-[10px] font-black text-slate-500 hover:text-cyan-500 transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-2 group">
                    <i class="fa-solid fa-chevron-left text-[8px] group-hover:-translate-x-1 transition-transform"></i>
                    Back to Login
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

        /* Light Mode Adaptation */
        .light-mode input {
            background: rgba(0, 0, 0, 0.03);
            color: #0f172a;
            border: 1px solid rgba(34, 211, 238, 0.2);
        }

        .light-mode .text-slate-500 {
            color: #64748b;
        }

        .light-mode .glass-nav {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid rgba(34, 211, 238, 0.2);
        }
    </style>
@endsection
