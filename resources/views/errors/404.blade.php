@extends('layouts.app')

@section('content')
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
        {{-- Background Animated Glows --}}
        <div class="absolute top-1/4 left-1/4 w-[300px] h-[300px] bg-cyan-500/10 rounded-full blur-[100px] animate-pulse">
        </div>
        <div
            class="absolute bottom-1/4 right-1/4 w-[400px] h-[400px] bg-blue-600/10 rounded-full blur-[120px] animate-pulse delay-700">
        </div>

        <div class="container mx-auto px-6 relative z-10 text-center">
            {{-- Large Glitchy Text --}}
            <div class="relative inline-block mb-8">
                <h1
                    class="heading-font text-[150px] md:text-[200px] font-black leading-none text-white opacity-5 select-none">
                    404
                </h1>
                <div class="absolute inset-0 flex items-center justify-center">
                    <h2
                        class="heading-font text-6xl md:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500 uppercase italic">
                        Lost?
                    </h2>
                </div>
            </div>

            {{-- Message --}}
            <div class="max-w-md mx-auto space-y-6">
                <h3 class="heading-font text-xl md:text-2xl text-white uppercase tracking-widest font-bold">
                    System <span class="text-cyan-400">Not Found</span>
                </h3>
                <p class="text-slate-500 text-sm md:text-base leading-relaxed">
                    The resource you are looking for has been moved, deleted, or never existed in this sector of the grid.
                </p>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 mt-10">
                    <a href="/"
                        class="w-full sm:w-auto bg-cyan-500 hover:bg-cyan-400 text-slate-900 px-8 py-4 rounded-xl font-black uppercase text-xs tracking-widest transition-all shadow-[0_0_20px_rgba(34,211,238,0.3)] flex items-center justify-center gap-2">
                        <i class="fa-solid fa-house"></i> Return Home
                    </a>

                    <button onclick="window.history.back()"
                        class="w-full sm:w-auto bg-slate-900 border border-white/10 hover:border-cyan-500/50 text-white px-8 py-4 rounded-xl font-black uppercase text-xs tracking-widest transition-all flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-left"></i> Go Back
                    </button>
                </div>
            </div>

            {{-- Extra Decorative Element --}}
            <div class="mt-20 flex justify-center gap-8 opacity-20">
                <div class="w-12 h-1 border-t border-cyan-500"></div>
                <div class="w-12 h-1 border-t border-cyan-500"></div>
                <div class="w-12 h-1 border-t border-cyan-500"></div>
            </div>
        </div>
    </section>
@endsection
