@extends('layouts.app')

@section('content')
    {{-- Floating Back Button (Top Left Corner) --}}
    <div class="fixed top-6 left-6 z-50">
        <a href="javascript:history.back()"
            class="flex flex-col items-center justify-center w-14 h-14 bg-slate-900/80 backdrop-blur-md text-cyan-400 border border-cyan-500/30 rounded-2xl shadow-[0_0_20px_rgba(34,211,238,0.15)] hover:bg-cyan-500 hover:text-slate-900 hover:scale-105 transition-all duration-300 group"
            title="Go Back">
            <i class="fa-solid fa-chevron-left text-xl group-hover:-translate-x-1 transition-transform"></i>
            <span class="text-[7px] font-black uppercase mt-1 tracking-tighter">Back</span>
        </a>
    </div>
    @php
        $setting = \App\Models\Setting::first();
    @endphp
    <div class="container mx-auto px-6 py-16 space-y-24">

        <!-- SECTION: ABOUT THE EVENT -->
        <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="order-2 lg:order-1 space-y-6">
                <div class="flex items-center gap-4">
                    <span class="tech-badge">Initialization</span>
                    <div class="h-[1px] flex-grow bg-slate-800"></div>
                </div>

                <h2 class="heading-font text-4xl md:text-5xl font-black uppercase">
                    About the <span class="text-gradient">Event</span>
                </h2>

                <div class="content-glass p-8 space-y-4">
                    <p class="text-slate-300 leading-relaxed italic border-l-2 border-red-500 pl-4 bg-red-500/5 py-2">
                        "Something Big is Coming! Get ready for DUET CSE FEST 2026 (Season II)."
                    </p>
                    <p class="text-slate-400 leading-relaxed text-sm">
                        ঢাকা প্রকৌশল ও প্রযুক্তি বিশ্ববিদ্যালয় (DUET) এর CSE বিভাগ আয়োজিত এই উৎসবটি আগামী ২৬-২৭ জুন অনুষ্ঠিত
                        হতে যাচ্ছে। এটি শুধুমাত্র একটি উৎসব নয়, বরং ভবিষ্যৎ ইঞ্জিনিয়ারদের জন্য একটি বড় প্ল্যাটফর্ম যেখানে
                        উদ্ভাবন এবং প্রযুক্তির মেলবন্ধন ঘটে।
                    </p>
                </div>

                <!-- Key Info Grid -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 border border-cyan-500/10 bg-slate-900/50">
                        <span class="block text-cyan-400 font-bold text-lg italic">JUNE 26-27</span>
                        <span class="text-[10px] uppercase text-slate-500 tracking-widest">Target Date</span>
                    </div>
                    <div class="p-4 border border-cyan-500/10 bg-slate-900/50">
                        <span class="block text-cyan-400 font-bold text-lg italic italic uppercase">CSE DEPT</span>
                        <span class="text-[10px] uppercase text-slate-500 tracking-widest">Main Host</span>
                    </div>
                </div>
            </div>

            <!-- Visual Poster/Image Block -->
            <div class="order-1 lg:order-2 relative group">
                <div
                    class="absolute -inset-1 bg-cyan-500 rounded-lg blur opacity-10 group-hover:opacity-30 transition duration-1000">
                </div>
                <div class="relative bg-slate-900 border border-slate-800 p-2 rounded-lg overflow-hidden">
                    <img src="{{ asset('storage/' . $setting->main_banner3) }}" alt="CSE Fest Poster"
                        class="w-full rounded-md grayscale hover:grayscale-0 transition duration-500">
                    <!-- Floating Labels -->
                    <div class="absolute bottom-6 left-6 bg-black/80 backdrop-blur px-4 py-2 border border-cyan-500/40">
                        <p class="text-cyan-400 font-mono text-[10px] font-bold tracking-tighter">LOC: DUET_CAMPUS_GAZIPUR
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
