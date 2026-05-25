@extends('layouts.app')

@section('custom_css')
<style>
    .about-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-accent);
        border-radius: 1rem;
    }
    .segment-btn {
        border: 1px solid var(--border-mid);
        background: var(--bg-elevated);
        color: var(--text-secondary);
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .segment-btn:hover {
        border-color: var(--accent-border);
        color: var(--accent);
        background: var(--accent-dim);
    }
    .segment-btn.active {
        background: var(--accent);
        border-color: var(--accent);
        color: #020617;
        box-shadow: 0 0 25px rgba(34,211,238,0.35);
        transform: scale(1.05);
        z-index: 10;
    }
    .segment-btn.active img { filter: brightness(0); }
    .segment-content {
        background: var(--bg-surface);
        border: 1px solid var(--accent-border);
        border-radius: 1rem;
    }
    .badge-tag {
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        color: var(--accent);
    }
    .quote-block {
        border-left: 2px solid var(--accent);
        background: var(--accent-dim);
        color: var(--text-primary);
    }
    .divider-line { background: var(--border-mid); }
    .hint-row { color: var(--text-muted); }
    .segment-list-item { color: var(--text-primary); }
    .segment-list-item span { color: var(--accent); }
</style>
@endsection

@section('content')
    @php
        $setting = \App\Models\Setting::first();
    @endphp
    <div class="container mx-auto px-6 py-16 space-y-24">

        <div class="space-y-20">

            {{-- ABOUT SECTION --}}
            <section class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

                <div class="order-2 lg:order-1 space-y-6">
                    <div class="flex items-center gap-4">
                        <span class="badge-tag px-3 py-1 text-xs font-mono uppercase tracking-widest rounded-full">
                            Initialization
                        </span>
                        <div class="h-px flex-grow divider-line"></div>
                    </div>

                    <h2 class="heading-font text-4xl md:text-5xl font-black uppercase" style="color:var(--text-primary)">
                        About the <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Carnival</span>
                    </h2>

                    <div class="about-card p-8 space-y-6">
                        <p class="quote-block leading-relaxed italic pl-4 py-2 text-sm">
                            "A national-level technology CARNIVAL bringing together students, educators, and innovators
                            on a dynamic platform of creativity and collaboration."
                        </p>

                        <p class="text-sm leading-relaxed" style="color:var(--text-secondary)">
                            DUET CSE Carnival 2026 is organized by the <span class="font-bold" style="color:var(--accent)">Department of
                                CSE, DUET</span>, in association with <span class="font-bold" style="color:var(--accent)">DUET Computer
                                Society</span> and in collaboration with <span class="font-bold" style="color:var(--accent)">WhiteBoard
                                Initiatives</span>. The event is designed to promote technical excellence and innovation
                            through prestigious competitions.
                        </p>

                        <p class="text-xs font-bold uppercase tracking-widest" style="color:var(--text-muted)">Competition Segments</p>

                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3 text-[12px] font-mono">
                            @foreach(['IUPC', 'AI Hackathon', 'ICT Olympiad', 'Project Showcasing'] as $seg)
                            <li class="flex items-center gap-2 group segment-list-item">
                                <span class="group-hover:translate-x-1 transition-transform" style="color:var(--accent)">▶</span>
                                {{ $seg }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                {{-- Poster --}}
                <div class="order-1 lg:order-2 relative group">
                    <div class="absolute -inset-2 bg-gradient-to-r from-cyan-500 to-blue-600 rounded-lg blur opacity-10 group-hover:opacity-25 transition duration-1000"></div>
                    <div class="relative border rounded-lg overflow-hidden p-2" style="border-color:var(--border-accent); background:var(--bg-surface)">
                        <img src="{{ asset('storage/' . $setting->main_banner3) }}" alt="DUET CSE Carnival 2026"
                            class="w-full rounded-md grayscale group-hover:grayscale-0 transition duration-700 transform group-hover:scale-105 shadow-2xl"
                            onerror="this.src='https://via.placeholder.com/800x1000/0f172a/22d3ee?text=CSE+CARNIVAL+2026'">
                    </div>
                </div>
            </section>

            {{-- SEGMENTS SECTION --}}
            <section x-data="{ activeSegment: 'iupc' }" class="py-12 space-y-8">

                <div class="text-center space-y-2">
                    <h3 class="font-mono text-xs uppercase tracking-[0.3em]" style="color:var(--accent); opacity:0.7">Explore Segments</h3>
                    <div class="h-px w-20 mx-auto" style="background:var(--accent-border)"></div>
                </div>

                <div class="flex items-center gap-2 mb-4 px-1 hint-row">
                    <i class="fa-solid fa-mouse-pointer text-[10px] animate-pulse" style="color:var(--accent)"></i>
                    <span class="text-[10px] font-bold uppercase tracking-[0.2em]">Select a segment to view details</span>
                </div>

                {{-- Segment Buttons --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                    @php
                        $segments = [
                            ['key' => 'iupc',      'img' => 'iupc.png',  'label' => 'IUPC'],
                            ['key' => 'hackathon', 'img' => 'ai.png',    'label' => 'AI Hackathon'],
                            ['key' => 'olympiad',  'img' => 'ict.png',   'label' => 'ICT Olympiad'],
                            ['key' => 'showcase',  'img' => 'ps.png',    'label' => 'Project Showcase'],
                        ];
                    @endphp

                    @foreach($segments as $seg)
                    <button @click="activeSegment = '{{ $seg['key'] }}'"
                        :class="activeSegment === '{{ $seg['key'] }}' ? 'active' : ''"
                        class="segment-btn px-4 py-5 font-black uppercase tracking-widest text-[11px] active:scale-95 group">
                        <span class="flex flex-col items-center gap-2">
                            <img src="{{ asset('images/' . $seg['img']) }}"
                                 alt="{{ $seg['label'] }}"
                                 :class="activeSegment === '{{ $seg['key'] }}' ? 'brightness-0' : 'opacity-70 group-hover:opacity-100'"
                                 class="w-8 h-8 object-contain transition-all duration-300 pointer-events-none"
                                 style="filter: var(--img-filter, none)">
                            <span>{{ $seg['label'] }}</span>
                        </span>
                    </button>
                    @endforeach
                </div>

                {{-- Content Panel --}}
                <div class="relative min-h-[220px]">

                    @php
                        $contents = [
                            'iupc' => [
                                'title' => 'IUPC',
                                'text'  => 'The Inter University Programming Contest (IUPC) of DUET CSE Carnival 2026 is a national-level competitive programming segment organized by the Department of CSE, DUET, in association with DUET Computer Society and White Board Initiative. The event aims to foster problem-solving ability, algorithmic thinking, and programming excellence among university students from across Bangladesh. Participating teams will compete in intense programming challenges designed to test their knowledge of algorithms, data structures, mathematics, and computational thinking under strict time constraints. The competition will feature a Mock Contest on 26 June 2026, followed by the Main Contest on 27 June 2026 at the DUET Campus, Gazipur. Teams will compete for prestigious titles and prize money worth over 180,000 BDT.',
                            ],
                            'hackathon' => [
                                'title' => 'AI Hackathon',
                                'text'  => 'The AI Hackathon of DUET CSE Carnival 2026 is a national-level competition organized by the Department of CSE, DUET, in association with DUET Computer Society and White Board Initiative. The event aims to encourage university students across Bangladesh to solve real-world problems using Artificial Intelligence, Machine Learning, and Data Science. The competition will be conducted in two rounds. The first round will be held on Kaggle, where teams will develop and submit their AI-based solutions. Shortlisted teams will advance to the final onsite round, where they will present their solutions before a panel of expert judges. The final evaluation will consist of 70% marks from Kaggle performance and 30% marks from onsite judging. The offline judging session will be held on 26 June 2026 at the DUET Campus, Gazipur.',
                            ],
                            'olympiad' => [
                                'title' => 'ICT Olympiad',
                                'text'  => 'The ICT Olympiad is a nationwide academic competition organized to promote ICT knowledge, computational thinking, and problem-solving skills among students across Bangladesh. The event will be held on 26 June 2026 and conducted offline through an OMR-based MCQ examination covering topics such as programming fundamentals, algorithms, networking, cybersecurity, databases, and modern technologies. Open to students up to undergraduate level, the Olympiad aims to identify talented individuals and foster a competitive learning environment. All participants will receive certificates, while top performers will be awarded cash prizes totaling 39,000 BDT.',
                            ],
                            'showcase' => [
                                'title' => 'Project Showcasing',
                                'text'  => 'The Project Showcase is the flagship competitive segment of DUET CSE Carnival 2026, serving as a national-level platform where student teams from across Bangladesh present innovative and impactful technical projects. Organized by the Department of Computer Science and Engineering, Dhaka University of Engineering & Technology (DUET), in association with DUET Computer Society and White Board Initiative, the event aims to inspire innovation, technical excellence, and real-world problem-solving among aspiring engineers and developers.',
                            ],
                        ];
                    @endphp

                    @foreach($contents as $key => $content)
                    <div x-show="activeSegment === '{{ $key }}'"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="segment-content p-8 shadow-2xl">
                        <h4 class="text-2xl font-black uppercase mb-4 flex items-center gap-3" style="color:var(--accent)">
                            <span class="w-8 h-px" style="background:var(--accent)"></span>
                            {{ $content['title'] }}
                        </h4>
                        <p class="leading-relaxed text-sm md:text-base italic" style="color:var(--text-secondary)">
                            {{ $content['text'] }}
                        </p>
                    </div>
                    @endforeach

                </div>
            </section>
        </div>
    </div>
@endsection