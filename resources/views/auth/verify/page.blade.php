@extends('layouts.app')

@section('content')
    <section class="relative min-h-screen flex items-center justify-center py-20">
        {{-- Background Glow --}}
        <div
            class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-cyan-500/5 rounded-full blur-[120px] pointer-events-none">
        </div>

        <div class="max-w-md w-full px-6 relative z-10">

            {{-- Header Section --}}
            <div class="text-center mb-8">
                <div
                    class="w-16 h-16 bg-slate-900 border border-cyan-500/30 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-[0_0_15px_rgba(34,211,238,0.1)]">
                    <i class="fa-solid fa-shield-halved text-cyan-400 text-xl"></i>
                </div>
                <h2 class="heading-font text-2xl font-black text-white uppercase italic">VERIFY <span
                        class="text-cyan-400">IDENTITY</span></h2>
                <p class="text-slate-500 text-[10px] mt-2 uppercase tracking-widest">Enter the 6-digit decryption code sent
                    to your email</p>
            </div>

            {{-- Verification Form --}}
            <div class="bg-slate-900/50 backdrop-blur-xl border border-white/5 p-8 rounded-3xl shadow-2xl">
                <form action="{{ route('verify.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <label
                            class="text-slate-400 text-[10px] uppercase tracking-widest font-bold mb-3 block">Verification
                            Code</label>
                        <input type="text" name="verification_code" required maxlength="6" placeholder="· · · · · ·"
                            class="w-full bg-slate-800/50 border border-white/10 rounded-xl px-5 py-4 text-white text-center text-2xl font-black tracking-[0.5em] focus:outline-none focus:border-cyan-500/50 transition-all placeholder:text-slate-700">
                    </div>

                    <button type="submit"
                        class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-900 py-4 rounded-xl font-black uppercase text-xs tracking-widest transition-all shadow-[0_0_20px_rgba(34,211,238,0.2)]">
                        Verify Access →
                    </button>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-slate-500 text-[10px] uppercase">Didn't receive code?
                        <a href="#" class="text-cyan-500 hover:underline ml-1">Resend Link</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
