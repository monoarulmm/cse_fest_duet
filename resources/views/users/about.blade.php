@extends('layouts.app')

@section('content')
    @php
        $setting = \App\Models\Setting::first();
    @endphp
    <div class="container mx-auto px-6 py-16 space-y-24">

        <!-- SECTION: ABOUT THE EVENT -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

            <div class="order-2 lg:order-1 space-y-6">
                <div class="flex items-center gap-4">
                    <span
                        class="tech-badge px-3 py-1 bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-mono uppercase tracking-widest">Initialization</span>
                    <div class="h-[1px] flex-grow bg-slate-800"></div>
                </div>

                <h2 class="heading-font text-4xl md:text-5xl font-black uppercase text-white">
                    About the <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Carnival</span>
                </h2>

                <div class="content-glass p-8 space-y-6 bg-slate-900/40 border border-slate-800 rounded-xl">
                    <p class="text-slate-300 leading-relaxed italic border-l-2 border-cyan-500 pl-4 bg-cyan-500/5 py-2">
                        "Empowering Innovation, Shaping the Future."
                    </p>

                    <p class="text-slate-400 leading-relaxed text-sm">
                        DUET CSE Carnival 2026 একটি জাতীয় পর্যায়ের প্রযুক্তি উৎসব, যা শিক্ষার্থী, উদ্ভাবক এবং
                        প্রযুক্তিপ্রেমীদের জন্য এক অনন্য প্ল্যাটফর্ম। ঢাকা প্রকৌশল ও প্রযুক্তি বিশ্ববিদ্যালয় (DUET) এর CSE
                        বিভাগ এবং DUET Computer Society আয়োজিত এই আসরে আধুনিক প্রযুক্তির মেলবন্ধনে দেশসেরা মেধাবীদের
                        উদ্ভাবনী শক্তিকে উন্মোচন করা হয়।
                    </p>

                    <!-- Segments List -->
                    <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 text-[12px] font-mono text-slate-300">
                        <li class="flex items-center gap-2">
                            <span class="text-cyan-500">▶</span> IUPC (Programming Contest)
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-cyan-500">▶</span> AI Hackathon
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-cyan-500">▶</span> ICT Olympiad
                        </li>
                        <li class="flex items-center gap-2">
                            <span class="text-cyan-500">▶</span> Project Showcasing
                        </li>
                    </ul>
                </div>

                <!-- Key Info Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                    <div class="p-4 border border-cyan-500/10 bg-slate-900/50 rounded-lg">
                        <span class="block text-cyan-400 font-bold text-lg italic uppercase">June 26-27</span>
                        <span class="text-[9px] uppercase text-slate-500 tracking-widest font-bold">Event Date</span>
                    </div>
                    <div class="p-4 border border-cyan-500/10 bg-slate-900/50 rounded-lg">
                        <span class="block text-cyan-400 font-bold text-lg italic uppercase">CSE DEPT</span>
                        <span class="text-[9px] uppercase text-slate-500 tracking-widest font-bold">Main Host</span>
                    </div>
                    <div class="p-4 border border-cyan-500/10 bg-slate-900/50 rounded-lg">
                        <span class="block text-cyan-400 font-bold text-lg italic uppercase">DUETCS</span>
                        <span class="text-[9px] uppercase text-slate-500 tracking-widest font-bold">Association</span>
                    </div>
                    <div class="p-4 border border-cyan-500/10 bg-slate-900/50 rounded-lg">
                        <span class="block text-cyan-400 font-bold text-lg italic uppercase">National</span>
                        <span class="text-[9px] uppercase text-slate-500 tracking-widest font-bold">Event Level</span>
                    </div>
                </div>
            </div>

            <!-- Visual Poster/Image Block -->
            <div class="order-1 lg:order-2 relative group">
                <!-- Neon Glow Effect -->
                <div
                    class="absolute -inset-2 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg blur opacity-10 group-hover:opacity-25 transition duration-1000">
                </div>

                <div class="relative bg-slate-900 border border-slate-800 p-2 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $setting->main_banner3) }}" alt="DUET CSE Carnival 2026 Poster"
                        class="w-full rounded-md grayscale group-hover:grayscale-0 transition duration-700 transform group-hover:scale-105"
                        onerror="this.src='https://via.placeholder.com/800x1000/0f172a/22d3ee?text=CSE+CARNIVAL+2026'">

                    <!-- Floating Label Overlay -->
                    <div
                        class="absolute top-6 right-6 bg-cyan-500 text-black font-black px-3 py-1 text-[10px] uppercase tracking-tighter skew-x-[-12deg]">
                        Season II
                    </div>

                    <!-- Location Label -->
                    <div class="absolute bottom-6 left-6 bg-black/90 backdrop-blur px-4 py-2 border border-cyan-500/40">
                        <p class="text-cyan-400 font-mono text-[10px] font-bold tracking-tighter">
                            <span class="animate-pulse">●</span> LOC: DUET_CAMPUS_GAZIPUR
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- SECTION: ABOUT CSE DEPARTMENT -->
        <section class="space-y-12">
            <div class="text-center space-y-4">
                <h2 class="heading-font text-4xl font-black uppercase">
                    <span class="text-slate-500">Department of</span> <br>
                    Computer Science & Engineering
                </h2>
                <div class="h-1 w-20 bg-cyan-500 mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Info Card 1 -->
                <div class="content-glass p-10 group hover:bg-cyan-500/5 transition-all">
                    <div class="text-cyan-500 mb-6 text-3xl">
                        <i class="fa-solid fa-microchip"></i>
                    </div>
                    <h3 class="text-white font-bold uppercase mb-4 tracking-wider">Academic Excellence</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        DUET CSE বিভাগ প্রযুক্তিগত শিক্ষার অন্যতম কেন্দ্র। এখানে তাত্ত্বিক শিক্ষার পাশাপাশি ব্যবহারিক
                        গবেষণার ওপর সর্বোচ্চ গুরুত্ব দেওয়া হয়।
                    </p>
                </div>

                <!-- Info Card 2 -->
                <div class="content-glass p-10 group border-cyan-500/30">
                    <div class="text-cyan-500 mb-6 text-3xl">
                        <i class="fa-solid fa-code-branch"></i>
                    </div>
                    <h3 class="text-white font-bold uppercase mb-4 tracking-wider">Innovation Hub</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        প্রতি বছর এই বিভাগ থেকে অসংখ্য উদ্ভাবনী প্রোজেক্ট, অ্যাপ এবং রিসার্চ পেপার প্রকাশিত হয় যা জাতীয় ও
                        আন্তর্জাতিক পর্যায়ে প্রশংসিত।
                    </p>
                </div>

                <!-- Info Card 3 -->
                <div class="content-glass p-10 group hover:bg-cyan-500/5 transition-all">
                    <div class="text-cyan-500 mb-6 text-3xl">
                        <i class="fa-solid fa-network-wired"></i>
                    </div>
                    <h3 class="text-white font-bold uppercase mb-4 tracking-wider">Community & Alumni</h3>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        আমাদের অ্যালামনাই নেটওয়ার্ক বিশ্বব্যাপী টেক জায়ান্ট কোম্পানিগুলোতে সফলতার সাথে কাজ করে যাচ্ছে।
                    </p>
                </div>
            </div>
        </section>
    </div>
@endsection
