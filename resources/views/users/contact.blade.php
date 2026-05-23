@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-6 py-12">
        <section class="space-y-12">
            <div class="text-center space-y-4">
                <h2 class="heading-font text-4xl font-black uppercase">
                    <span class="text-slate-500">Have any</span> <br>
                    Queries? Reach Out
                </h2>
                <div class="h-1 w-20 bg-cyan-500 mx-auto"></div>
                <p class="text-slate-400 text-sm italic">Feel free to contact our segment leads for any assistance.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

                <div class="content-glass p-6 border-l-4 border-cyan-500 hover:bg-white/5 transition-all">
                    <h3 class="text-cyan-500 font-bold uppercase text-sm mb-4 tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-trophy"></i> ICT Olympiad
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class=" font-semibold text-sm">Boktiar Mahamud</p>
                            <p class="text-slate-500 text-[10px] leading-tight uppercase mb-1">Multimedia & Gaming
                                Secretary, DUETCS</p>
                            <a href="tel:+8801744555821"
                                class="text-cyan-400 text-xs hover:underline font-mono tracking-tighter">
                                <i class="fa-solid fa-phone-volume mr-1"></i> +880 1744-555821
                            </a>
                        </div>
                        <div>
                            <p class=" font-semibold text-sm">Raju Mia</p>
                            <p class="text-slate-500 text-[10px] leading-tight uppercase mb-1">CSE 3/2, DUET</p>
                            <a href="tel:+8801835397371"
                                class="text-cyan-400 text-xs hover:underline font-mono tracking-tighter">
                                <i class="fa-solid fa-phone-volume mr-1"></i> +880 1835-397371
                            </a>
                        </div>
                    </div>
                </div>

                <div class="content-glass p-6 border-l-4 border-purple-500 hover:bg-white/5 transition-all">
                    <h3 class="text-purple-500 font-bold uppercase text-sm mb-4 tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-robot"></i> AI Hackathon
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class=" font-semibold text-sm">Abdul Owadud Islam Raton</p>
                            <p class="text-slate-500 text-[10px] leading-tight uppercase mb-1">Research & Innovation
                                Secretary, DUETCS</p>
                            <a href="tel:+8801318175391"
                                class="text-cyan-400 text-xs hover:underline font-mono tracking-tighter">
                                <i class="fa-solid fa-phone-volume mr-1"></i> +880 1318-175391
                            </a>
                        </div>
                        <div>
                            <p class=" font-semibold text-sm">Md. Abdullah Al Masum</p>
                            <p class="text-slate-500 text-[10px] leading-tight uppercase mb-1">Additional R&I Secretary,
                                DUETCS</p>
                            <a href="tel:+8801738276219"
                                class="text-cyan-400 text-xs hover:underline font-mono tracking-tighter">
                                <i class="fa-solid fa-phone-volume mr-1"></i> +880 1738-276219
                            </a>
                        </div>
                    </div>
                </div>

                <div class="content-glass p-6 border-l-4 border-green-500 hover:bg-white/5 transition-all">
                    <h3 class="text-green-500 font-bold uppercase text-sm mb-4 tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-code"></i> IUPC Segment
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class=" font-semibold text-sm">Mahamudul Hasan</p>
                            <p class="text-slate-500 text-[10px] leading-tight uppercase mb-1">Programming & Algorithms
                                Secretary, DUETCS</p>
                            <a href="tel:+8801705870272"
                                class="text-cyan-400 text-xs hover:underline font-mono tracking-tighter">
                                <i class="fa-solid fa-phone-volume mr-1"></i> +880 1705-870272
                            </a>
                        </div>
                        <div>
                            <p class=" font-semibold text-sm">Touhid Ahmed</p>
                            <p class="text-slate-500 text-[10px] leading-tight uppercase mb-1">Additional P&A Secretary,
                                DUETCS</p>
                            <a href="tel:+8801982652458"
                                class="text-cyan-400 text-xs hover:underline font-mono tracking-tighter">
                                <i class="fa-solid fa-phone-volume mr-1"></i> +880 1982-652458
                            </a>
                        </div>
                    </div>
                </div>

                <div class="content-glass p-6 border-l-4 border-yellow-500 hover:bg-white/5 transition-all">
                    <h3 class="text-yellow-500 font-bold uppercase text-sm mb-4 tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-lightbulb"></i> Project Showcasing
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <p class=" font-semibold text-sm">Prosanto Kumar Barman</p>
                            <p class="text-slate-500 text-[10px] leading-tight uppercase mb-1">CSE 3/2, DUET</p>
                            <a href="tel:+8801332704025"
                                class="text-cyan-400 text-xs hover:underline font-mono tracking-tighter">
                                <i class="fa-solid fa-phone-volume mr-1"></i> +880 1332-704025
                            </a>
                        </div>
                        <div>
                            <p class=" font-semibold text-sm">Avijit Mondal</p>
                            <p class="text-slate-500 text-[10px] leading-tight uppercase mb-1">CSE 3/2, DUET</p>
                            <a href="tel:+8801855512502"
                                class="text-cyan-400 text-xs hover:underline font-mono tracking-tighter">
                                <i class="fa-solid fa-phone-volume mr-1"></i> +880 1855-512502
                            </a>
                        </div>
                    </div>
                </div>

                <div class="content-glass p-6 border-l-4 border-red-500 lg:col-span-2 hover:bg-white/5 transition-all">
                    <h3 class="text-red-500 font-bold uppercase text-sm mb-4 tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-circle-exclamation"></i> Significant Issues
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <p class=" font-semibold text-sm">Imran Mansur</p>
                            <p class="text-slate-500 text-[10px] leading-tight uppercase mb-1">President, DUET Computer
                                Society</p>
                            <a href="tel:+8801738587110"
                                class="text-cyan-400 text-xs hover:underline font-mono tracking-tighter">
                                <i class="fa-solid fa-phone-volume mr-1"></i> +880 1738-587110
                            </a>
                        </div>
                        <div>
                            <p class=" font-semibold text-sm">M. Aiman Aousaf Hossain</p>
                            <p class="text-slate-500 text-[10px] leading-tight uppercase mb-1">CEO, WhiteBoard Initiatives
                            </p>
                            <a href="tel:+8801810115665"
                                class="text-cyan-400 text-xs hover:underline font-mono tracking-tighter">
                                <i class="fa-solid fa-phone-volume mr-1"></i> +880 1810-115665
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
@endsection
