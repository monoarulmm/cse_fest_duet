@extends('layouts.app')

@section('content')
    @php
        $setting = \App\Models\Setting::first();
    @endphp
    <div class="container mx-auto px-6 py-16 space-y-24">

        <!-- DUET CSE CARNIVAL 2026 - ABOUT & SEGMENTS SECTION -->
        <div class="space-y-20">

            <!-- SECTION: ABOUT THE EVENT -->
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <div class="order-2 lg:order-1 space-y-6">
                    <div class="flex items-center gap-4">
                        <span
                            class="tech-badge px-3 py-1 bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-mono uppercase tracking-widest">
                            Initialization
                        </span>
                        <div class="h-[1px] flex-grow bg-slate-800"></div>
                    </div>

                    <h2 class="heading-font text-4xl md:text-5xl font-black uppercase ">
                        About the <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Carnival</span>
                    </h2>

                    <div class="content-glass p-8 space-y-6 border border-slate-800 rounded-xl">
                        <p class=" leading-relaxed italic border-l-2 border-cyan-500 pl-4 bg-cyan-500/5 py-2">
                            "A national-level  technology  CARNIVAl bringing together students, educators, and innovators
                            on a
                            dynamic platform of creativity and collaboration."
                        </p>

                        <p class="text-slate-400 leading-relaxed text-sm">
                            DUET CSE Carnival 2026 is organized by the <span class="text-cyan-400 font-bold">Department of
                                CSE, DUET</span>, in association with <span class="text-cyan-400 font-bold">DUET Computer
                                Society</span> and in collaboration with <span class="text-cyan-400 font-bold">WhiteBoard
                                Initiatives</span>. The event is designed to promote technical excellence and innovation
                            through prestigious competitions.
                        </p>
                        <p>Competition Segments</p>

                        <!-- Segments Quick List -->
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 text-[12px] font-mono ">
                            <li class="flex items-center gap-2 group">
                                <span class="text-cyan-500 group-hover:translate-x-1 transition-transform">▶</span> IUPC
                            </li>
                            <li class="flex items-center gap-2 group">
                                <span class="text-cyan-500 group-hover:translate-x-1 transition-transform">▶</span> AI
                                Hackathon
                            </li>
                            <li class="flex items-center gap-2 group">
                                <span class="text-cyan-500 group-hover:translate-x-1 transition-transform">▶</span> ICT
                                Olympiad
                            </li>
                            <li class="flex items-center gap-2 group">
                                <span class="text-cyan-500 group-hover:translate-x-1 transition-transform">▶</span> Project
                                Showcasing
                            </li>
                        </ul>
                    </div>

                    <!-- Key Info Grid -->

                </div>

                <!-- Visual Poster Block -->
                <div class="order-1 lg:order-2 relative group">
                    <div
                        class="absolute -inset-2 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg blur opacity-10 group-hover:opacity-25 transition duration-1000">
                    </div>
                    <div class="relative  border border-slate-800 p-2 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $setting->main_banner3) }}" alt="DUET CSE Carnival 2026"
                            class="w-full rounded-md grayscale group-hover:grayscale-0 transition duration-700 transform group-hover:scale-105 shadow-2xl"
                            onerror="this.src='https://via.placeholder.com/800x1000/0f172a/22d3ee?text=CSE+CARNIVAL+2026'">

                        <div
                            class="absolute top-6 right-6 bg-cyan-500 text-black font-black px-4 py-1 text-[11px] uppercase tracking-tighter skew-x-[-12deg] shadow-lg">
                            2026 EDITION
                        </div>

                        <div class="absolute bottom-6 left-6 bg-black/90 backdrop-blur px-4 py-2 border border-cyan-500/40">
                            <p class="text-cyan-400 font-mono text-[10px] font-bold tracking-tighter">
                                <span class="animate-pulse">●</span> LOC: DUET_CAMPUS_GAZIPUR
                            </p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- SECTION: CARNIVAL SEGMENTS (Interactive Tabs) -->
            <section x-data="{ activeSegment: 'iupc' }" class="py-12 space-y-8">

                <div class="text-center space-y-2">
                    <h3 class="text-white font-mono text-xs uppercase tracking-[0.3em] text-cyan-500/70">Explore Segments
                    </h3>
                    <div class="h-px w-20 bg-cyan-500/30 mx-auto"></div>
                </div>

                <!-- Tab Buttons -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <button @click="activeSegment = 'iupc'"
                        :class="activeSegment === 'iupc' ?
                            'bg-cyan-500 border-cyan-400 text-black shadow-[0_0_20px_rgba(34,211,238,0.3)] scale-105' :
                            ' border-slate-800 text-slate-400 hover:border-slate-600'"
                        class="px-4 py-5 border rounded-xl font-black uppercase tracking-widest text-[11px] transition-all duration-300 transform">
                        IUPC
                    </button>

                    <button @click="activeSegment = 'hackathon'"
                        :class="activeSegment === 'hackathon' ?
                            'bg-cyan-500 border-cyan-400 text-black shadow-[0_0_20px_rgba(34,211,238,0.3)] scale-105' :
                            ' border-slate-800 text-slate-400 hover:border-slate-600'"
                        class="px-4 py-5 border rounded-xl font-black uppercase tracking-widest text-[11px] transition-all duration-300 transform">
                        AI Hackathon
                    </button>

                    <button @click="activeSegment = 'olympiad'"
                        :class="activeSegment === 'olympiad' ?
                            'bg-cyan-500 border-cyan-400 text-black shadow-[0_0_20px_rgba(34,211,238,0.3)] scale-105' :
                            ' border-slate-800 text-slate-400 hover:border-slate-600'"
                        class="px-4 py-5 border rounded-xl font-black uppercase tracking-widest text-[11px] transition-all duration-300 transform">
                        ICT Olympiad
                    </button>

                    <button @click="activeSegment = 'showcase'"
                        :class="activeSegment === 'showcase' ?
                            'bg-cyan-500 border-cyan-400 text-black shadow-[0_0_20px_rgba(34,211,238,0.3)] scale-105' :
                            ' border-slate-800 text-slate-400 hover:border-slate-600'"
                        class="px-4 py-5 border rounded-xl font-black uppercase tracking-widest text-[11px] transition-all duration-300 transform">
                        Project Showcasing
                    </button>
                </div>

                <!-- Content Display Area -->
                <div class="relative min-h-[220px]">
                    <!-- IUPC -->
                    <div x-show="activeSegment === 'iupc'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class=" from-slate-900 to-slate-950 border border-cyan-500/20 p-8 rounded-2xl shadow-2xl">
                        <h4 class="text-2xl font-black text-cyan-400 uppercase mb-4 flex items-center gap-3">
                            <span class="w-8 h-px "></span> IUPC
                        </h4>
                        <p class=" leading-relaxed text-sm md:text-base italic">
                            "IUPC (Inter University Programming Contest) will bring together teams from universities
                            nationwide to compete in challenging algorithmic and problem-solving contests through
                            competitive programming."
                        </p>
                    </div>

                    <!-- AI Hackathon -->
                    <div x-show="activeSegment === 'hackathon'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class=" from-slate-900 to-slate-950 border border-cyan-500/20 p-8 rounded-2xl shadow-2xl">
                        <h4 class="text-2xl font-black text-cyan-400 uppercase mb-4 flex items-center gap-3">
                            <span class="w-8 h-px bg-cyan-500"></span> AI Hackathon
                        </h4>
                        <p class=" leading-relaxed text-sm md:text-base italic">
                            "AI Hackathon will provide participants with an opportunity to develop innovative AI-driven
                            solutions addressing real-world challenges using modern technologies and creative approaches."
                        </p>
                    </div>

                    <!-- ICT Olympiad -->
                    <div x-show="activeSegment === 'olympiad'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class=" from-slate-900 to-slate-950 border border-cyan-500/20 p-8 rounded-2xl shadow-2xl">
                        <h4 class="text-2xl font-black text-cyan-400 uppercase mb-4 flex items-center gap-3">
                            <span class="w-8 h-px bg-cyan-500"></span> ICT Olympiad
                        </h4>
                        <p class=" leading-relaxed text-sm md:text-base italic">
                            "ICT Olympiad will engage students in analytical and technology-based problem-solving activities
                            designed to test their knowledge and understanding of ICT and emerging technologies."
                        </p>
                    </div>

                    <!-- Project Showcasing -->
                    <div x-show="activeSegment === 'showcase'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        class=" from-slate-900 to-slate-950 border border-cyan-500/20 p-8 rounded-2xl shadow-2xl">
                        <h4 class="text-2xl font-black text-cyan-400 uppercase mb-4 flex items-center gap-3">
                            <span class="w-8 h-px bg-cyan-500"></span> Project Showcasing
                        </h4>
                        <p class=" leading-relaxed text-sm md:text-base italic">
                            "Project Showcasing will offer a platform for participants to present innovative projects,
                            research ideas, and technological solutions, encouraging creativity, collaboration, and
                            practical implementation of engineering concepts."
                        </p>
                    </div>
                </div>
            </section>
        </div>




        {{-- <section class="space-y-12">
            <div class="text-center space-y-4">
                <h2 class="heading-font text-4xl font-black uppercase">
                    <span class="text-slate-500">Department of</span> <br>
                    Computer Science & Engineering
                </h2>
                <div class="h-1 w-20 bg-cyan-500 mx-auto"></div>
                <p class="text-slate-400 max-w-2xl mx-auto text-sm">
                    Empowering the next generation of engineers through cutting-edge technology,
                    rigorous academics, and a culture of innovation.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div
                    class="content-glass p-8 group hover:bg-cyan-500/5 transition-all border border-white/5 hover:border-cyan-500/30">
                    <div class="text-cyan-500 mb-6 text-3xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                    <h3 class="text-white font-bold uppercase mb-4 tracking-wider">Academic Excellence</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        DUET CSE is a premier hub for technical education, offering a curriculum that
                        perfectly balances core theoretical foundations with hands-on practical expertise.
                    </p>
                </div>

                <div class="content-glass p-8 group border border-cyan-500/30 bg-cyan-500/5">
                    <div class="text-cyan-500 mb-6 text-3xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-code-branch"></i>
                    </div>
                    <h3 class="text-white font-bold uppercase mb-4 tracking-wider">Innovation Hub</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Home to numerous award-winning projects and applications. Our students consistently
                        excel in national and international competitions, pushing the boundaries of technology.
                    </p>
                </div>

                <div
                    class="content-glass p-8 group hover:bg-cyan-500/5 transition-all border border-white/5 hover:border-cyan-500/30">
                    <div class="text-cyan-500 mb-6 text-3xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-flask-vial"></i>
                    </div>
                    <h3 class="text-white font-bold uppercase mb-4 tracking-wider">R&D Culture</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Our faculty and students are actively involved in high-impact research,
                        publishing in prestigious journals across AI, Data Science, and Cybersecurity domains.
                    </p>
                </div>

                <div
                    class="content-glass p-8 group hover:bg-cyan-500/5 transition-all border border-white/5 hover:border-cyan-500/30">
                    <div class="text-cyan-500 mb-6 text-3xl group-hover:scale-110 transition-transform">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                    <h3 class="text-white font-bold uppercase mb-4 tracking-wider">Global Alumni</h3>
                    <p class="text-slate-400 text-xs leading-relaxed">
                        Our graduates are leading the tech industry worldwide, holding key positions
                        at global tech giants and contributing to the digital transformation of Bangladesh.
                    </p>
                </div>
            </div>
        </section> --}}
    </div>
@endsection
