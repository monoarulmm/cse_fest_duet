@extends('layouts.app')

@section('title', $event->name . ' Registration | CSE CARNIVAL 2026')

@section('custom_css')
    <style>
        /* ─── REGISTRATION FORM VARIABLES ─────────────────────────── */
        :root {
            --form-surface: rgba(15, 23, 42, 0.82);
            --form-surface2: rgba(2, 6, 23, 0.55);
            --input-glow: rgba(34, 211, 238, 0.22);
            --neon-border: rgba(34, 211, 238, 0.22);
            --label-col: #22d3ee;
            --divider: rgba(255, 255, 255, 0.06);
            --member-accent: #22d3ee;
        }

        body.light-mode {
            --form-surface: rgba(221, 217, 244, 0.9);
            --form-surface2: rgba(205, 200, 240, 0.5);
            --input-glow: rgba(91, 79, 207, 0.15);
            --neon-border: rgba(91, 79, 207, 0.25);
            --label-col: #5B4FCF;
            --divider: rgba(91, 79, 207, 0.1);
            --member-accent: #5B4FCF;
        }

        /* ─── GLASS FORM CARD ──────────────────────────────────────── */
        .form-glass {
            background: var(--form-surface);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid var(--neon-border);
            border-radius: 2.5rem;
            transition: background 0.4s ease, border-color 0.4s ease;
        }

        /* ─── PROGRESS BAR ─────────────────────────────────────────── */
        .reg-progress {
            height: 2px;
            background: var(--divider);
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 2rem;
        }

        .reg-progress-fill {
            height: 100%;
            background: var(--neon-cyan);
            border-radius: 2px;
            width: 20%;
            transition: width 0.5s ease;
            box-shadow: 0 0 8px var(--input-glow);
        }

        body.light-mode .reg-progress-fill {
            box-shadow: none;
        }

        /* ─── SECTION DIVIDER ──────────────────────────────────────── */
        .section-divider {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 1.5rem;
            margin-top: 2.5rem;
        }

        .section-divider:first-child {
            margin-top: 0;
        }

        .sec-line {
            flex: 1;
            height: 1px;
            background: var(--neon-border);
        }

        .sec-title {
            font-family: 'Orbitron', sans-serif;
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            color: var(--neon-cyan);
            white-space: nowrap;
        }

        .sec-pill {
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.15em;
            padding: 3px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            background: rgba(34, 211, 238, 0.1);
            color: var(--neon-cyan);
            border: 1px solid var(--neon-border);
        }

        body.light-mode .sec-pill {
            background: rgba(91, 79, 207, 0.1);
        }

        /* ─── FORM INPUTS ──────────────────────────────────────────── */
        .input-field {
            background: var(--form-surface2);
            border: 1px solid var(--neon-border);
            color: var(--text-color);
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            font-weight: 500;
            transition: border-color 0.25s, box-shadow 0.25s, background 0.4s;
        }

        .input-field::placeholder {
            color: var(--text-muted);
            font-size: 11px;
        }

        .input-field:focus {
            border-color: var(--neon-cyan) !important;
            box-shadow: 0 0 0 3px var(--input-glow), 0 0 20px var(--input-glow);
            background: rgba(34, 211, 238, 0.03);
            outline: none;
        }

        body.light-mode .input-field:focus {
            box-shadow: 0 0 0 3px rgba(91, 79, 207, 0.15);
            background: rgba(91, 79, 207, 0.03);
        }

        .input-field option {
            background: #0f172a;
            color: #e2e8f0;
        }

        body.light-mode .input-field option {
            background: #f5f4fc;
            color: #1E1852;
        }

        input[type="file"].input-field {
            font-size: 11px;
        }

        input[type="file"].input-field::file-selector-button {
            background: var(--neon-cyan);
            color: #020617;
            border: none;
            border-radius: 8px;
            padding: 4px 12px;
            cursor: pointer;
            font-size: 10px;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-right: 10px;
            transition: opacity 0.2s;
        }

        input[type="file"].input-field::file-selector-button:hover {
            opacity: 0.8;
        }

        body.light-mode input[type="file"].input-field::file-selector-button {
            color: #fff;
        }

        /* ─── LABEL ────────────────────────────────────────────────── */
        .field-label {
            display: block;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.3em;
            color: var(--label-col);
            margin-bottom: 8px;
        }

        /* ─── EVENT SELECTOR CARDS ─────────────────────────────────── */
        .event-selector-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 10px;
            margin-bottom: 1.5rem;
        }

        .ev-sel-card {
            padding: 16px 12px;
            border-radius: 16px;
            cursor: pointer;
            border: 1px solid var(--neon-border);
            background: var(--form-surface2);
            transition: all 0.2s;
            text-align: center;
            user-select: none;
        }

        .ev-sel-card:hover {
            border-color: var(--neon-cyan);
        }

        .ev-sel-card.active {
            border-color: var(--neon-cyan);
            background: rgba(34, 211, 238, 0.07);
            box-shadow: 0 0 16px var(--input-glow), inset 0 0 12px rgba(34, 211, 238, 0.04);
        }

        body.light-mode .ev-sel-card.active {
            background: rgba(91, 79, 207, 0.08);
            box-shadow: none;
        }

        .ev-sel-icon {
            font-size: 24px;
            margin-bottom: 6px;
            display: block;
        }

        .ev-sel-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 9px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--text-color);
        }

        .ev-sel-card.active .ev-sel-name {
            color: var(--neon-cyan);
        }

        .ev-sel-fee {
            font-size: 10px;
            color: var(--text-muted);
            margin-top: 4px;
            font-weight: 700;
        }

        /* ─── TOGGLE BUTTONS ───────────────────────────────────────── */
        .toggle-group {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .toggle-opt {
            flex: 1;
            min-width: 120px;
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid var(--neon-border);
            background: var(--form-surface2);
            color: var(--text-muted);
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.12em;
            cursor: pointer;
            transition: all 0.2s;
            text-align: center;
        }

        .toggle-opt.active {
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
            background: rgba(34, 211, 238, 0.07);
            box-shadow: 0 0 14px var(--input-glow);
        }

        body.light-mode .toggle-opt.active {
            box-shadow: none;
            background: rgba(91, 79, 207, 0.08);
        }

        .toggle-opt:hover {
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
        }

        /* ─── T-SHIRT SIZE BUTTONS ─────────────────────────────────── */
        .size-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .sz-opt {
            padding: 8px 16px;
            border-radius: 10px;
            border: 1px solid var(--neon-border);
            background: var(--form-surface2);
            color: var(--text-muted);
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            text-transform: uppercase;
        }

        .sz-opt.active {
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
            background: rgba(34, 211, 238, 0.07);
        }

        body.light-mode .sz-opt.active {
            background: rgba(91, 79, 207, 0.08);
        }

        .sz-opt:hover {
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
        }

        /* ─── MEMBER CARD ──────────────────────────────────────────── */
        .member-card {
            border: 1px solid var(--neon-border);
            border-radius: 20px;
            padding: 24px 20px;
            background: var(--form-surface2);
            margin-top: 16px;
            transition: border-color 0.3s;
            position: relative;
            overflow: hidden;
        }

        .member-card::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 3px;
            height: 100%;
            background: var(--neon-cyan);
            border-radius: 3px 0 0 3px;
            box-shadow: 0 0 10px var(--input-glow);
        }

        body.light-mode .member-card::before {
            box-shadow: none;
        }

        .member-card:hover {
            border-color: rgba(34, 211, 238, 0.4);
        }

        body.light-mode .member-card:hover {
            border-color: rgba(91, 79, 207, 0.4);
        }

        .member-heading {
            font-family: 'Orbitron', sans-serif;
            font-size: 10px;
            color: var(--neon-cyan);
            text-transform: uppercase;
            letter-spacing: 0.3em;
            font-weight: 900;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .optional-badge {
            font-size: 8px;
            padding: 2px 10px;
            border-radius: 999px;
            border: 1px solid var(--neon-border);
            color: var(--text-muted);
            letter-spacing: 0.15em;
            text-transform: uppercase;
            font-family: 'JetBrains Mono', monospace;
            font-weight: 700;
        }

        /* ─── INFO CHIPS ───────────────────────────────────────────── */
        .info-chip-row {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 1.5rem;
        }

        .info-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border-radius: 12px;
            border: 1px solid var(--neon-border);
            background: var(--form-surface2);
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--text-muted);
        }

        .info-chip i {
            color: var(--neon-cyan);
        }

        /* ─── SUBMIT BUTTON ────────────────────────────────────────── */
        .submit-neon {
            width: 100%;
            padding: 20px;
            background: var(--neon-cyan);
            color: #020617;
            border: none;
            border-radius: 18px;
            font-family: 'Orbitron', sans-serif;
            font-size: 14px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            cursor: pointer;
            transition: all 0.25s;
            margin-top: 2rem;
            box-shadow: 0 0 28px var(--input-glow);
        }

        body.light-mode .submit-neon {
            color: #fff;
            box-shadow: none;
        }

        .submit-neon:hover {
            transform: scale(1.01);
            box-shadow: 0 0 44px var(--input-glow);
            filter: brightness(1.08);
        }

        body.light-mode .submit-neon:hover {
            box-shadow: none;
            filter: brightness(1.1);
        }

        .submit-neon:active {
            transform: scale(0.99);
        }

        /* ─── AUTOCOMPLETE DROPDOWN ────────────────────────────────── */
        .uni-suggest-box {
            position: absolute;
            top: calc(100% + 4px);
            left: 0;
            right: 0;
            z-index: 999;
            background: var(--dropdown-bg);
            border: 1px solid var(--neon-border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
        }

        .uni-suggest-item {
            padding: 11px 16px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            color: var(--text-muted);
            border-bottom: 1px solid var(--divider);
            transition: background 0.15s, color 0.15s;
        }

        .uni-suggest-item:last-child {
            border-bottom: none;
        }

        .uni-suggest-item:hover {
            background: var(--surface-hover);
            color: var(--neon-cyan);
        }

        /* ─── BACK BUTTON ──────────────────────────────────────────── */
        .back-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            background: transparent;
            border: 1px solid var(--neon-border);
            border-radius: 12px;
            color: var(--text-muted);
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            cursor: pointer;
            transition: all 0.2s;
        }

        .back-btn:hover {
            border-color: var(--neon-cyan);
            color: var(--neon-cyan);
        }

        .back-btn i {
            color: var(--neon-cyan);
            transition: transform 0.2s;
        }

        .back-btn:hover i {
            transform: translateX(-3px);
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">

            {{-- ── HEADER ───────────────────────────────────────────── --}}
            <div class="text-center mb-10">
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border mb-4"
                    style="border-color:var(--neon-border); background:rgba(34,211,238,0.05); color:var(--neon-cyan); font-size:10px; font-weight:700; letter-spacing:0.35em; text-transform:uppercase">
                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                    Registration Portal · Live
                </div>
                <h1 class="heading-font font-black uppercase tracking-tighter"
                    style="font-size: clamp(1.8rem, 5vw, 3.5rem); line-height:1.1">
                    {{ $event->name }} <span style="color:var(--neon-cyan)">Registration</span>
                </h1>
                <div class="h-[3px] w-24 mx-auto mt-3 rounded-full"
                    style="background:var(--neon-cyan); box-shadow:0 0 12px var(--input-glow, rgba(34,211,238,0.3))"></div>
                <p class="mt-3 text-[10px] tracking-[0.3em] uppercase font-bold" style="color:var(--text-muted)">
                    Fee: <span style="color:var(--neon-cyan)">{{ $event->reg_fee }} BDT</span>
                    &nbsp;·&nbsp;
                    Ends: <span style="color:var(--neon-cyan)">{{ $event->end_date->format('d M, Y') }}</span>
                </p>
            </div>

            {{-- ── PROGRESS BAR ────────────────────────────────────── --}}
            <div class="reg-progress">
                <div class="reg-progress-fill" id="regProgress"></div>
            </div>

            {{-- ── FORM ─────────────────────────────────────────────── --}}
            <form action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data"
                class="form-glass p-8 md:p-14 shadow-2xl" id="regForm">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                {{-- Form header row --}}
                <div class="flex justify-between items-center pb-5 mb-6" style="border-bottom:1px solid var(--neon-border)">
                    <h2 class="heading-font font-black uppercase text-xl tracking-tighter" style="color:var(--text-color)">
                        Registration <span style="color:var(--neon-cyan)">Details</span>
                    </h2>
                    <button type="button" onclick="window.history.back()" class="back-btn">
                        <i class="fa-solid fa-chevron-left"></i>
                        <span>Back</span>
                    </button>
                </div>

                {{-- Info chips --}}
                <div class="info-chip-row">
                    <div class="info-chip"><i class="fa-solid fa-location-dot"></i> DUET, Gazipur</div>
                    <div class="info-chip"><i class="fa-solid fa-calendar-days"></i> {{ $event->end_date->format('d M Y') }}
                    </div>
                    <div class="info-chip"><i class="fa-solid fa-bangladeshi-taka-sign"></i> {{ $event->reg_fee }} BDT</div>
                </div>

                {{-- ── SECTION 01: Segment ────────────────────────── --}}
                @php
                    $slug = $event->slug;
                    $teamSlugs = ['iupc', 'project-showcase', 'ai-hackathon'];
                    $isTeam = in_array($slug, $teamSlugs);
                    $maxMembers = $isTeam ? 3 : 1;
                    $minRequired = $slug === 'iupc' || $slug === 'ai-hackathon' ? 2 : 1;
                @endphp

                <div class="section-divider !mt-0">
                    <div class="sec-line"></div>
                    <span class="sec-title">01 · Institutional Info</span>
                    <div class="sec-line"></div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">

                    {{-- University --}}
                    <div>
                        <label class="field-label"><span style="color:var(--neon-cyan)">*</span> Institution /
                            University</label>
                        <div class="relative">
                            <input type="text" name="university_name" id="uniInput" value="{{ old('university_name') }}"
                                placeholder="Search or type your university…"
                                class="input-field w-full rounded-xl px-4 py-4" autocomplete="off" required
                                oninput="filterUni(this.value)">
                            <div id="uniSuggest" style="position:relative"></div>
                        </div>
                    </div>

                    {{-- Team Name --}}
                    @if ($isTeam)
                        <div>
                            <label class="field-label"><span style="color:var(--neon-cyan)">*</span> Team Name</label>
                            <input type="text" name="team_name" value="{{ old('team_name') }}" required
                                placeholder="Enter your team name" class="input-field w-full rounded-xl px-4 py-4">
                        </div>

                        {{-- All-female team toggle --}}
                        <div>
                            <label class="field-label">All-Female Team?</label>
                            <div class="toggle-group" id="genderToggle">
                                <button type="button" class="toggle-opt"
                                    onclick="setToggle('genderToggle','genderVal','NO',this)">
                                    🧑 No (Mixed)
                                </button>
                                <button type="button" class="toggle-opt active"
                                    onclick="setToggle('genderToggle','genderVal','YES',this)">
                                    👩 Yes (All-Female)
                                </button>
                            </div>
                            <input type="hidden" name="team_person" id="genderVal" value="NO">
                        </div>
                    @endif

                    {{-- Project Showcase extras --}}
                    @if ($slug === 'project-showcase')
                        <div>
                            <label class="field-label"><span style="color:var(--neon-cyan)">*</span> Project Title</label>
                            <input type="text" name="project_title" required placeholder="Your project name"
                                class="input-field w-full rounded-xl px-4 py-4" value="{{ old('project_title') }}">
                        </div>
                        <div>
                            <label class="field-label"><span style="color:var(--neon-cyan)">*</span> Abstract (PDF)</label>
                            <input type="file" name="abstract_file" accept=".pdf" required
                                class="input-field w-full rounded-xl px-4 py-3">
                        </div>
                    @endif
                </div>

                {{-- ── SECTION 02: Coach (IUPC only) ──────────────── --}}
                @if ($slug === 'iupc')
                    <div class="section-divider">
                        <div class="sec-line"></div>
                        <span class="sec-title">02 · Coach Details</span>
                        <span class="sec-pill">IUPC Only</span>
                        <div class="sec-line"></div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-2">
                        <div>
                            <label class="field-label"><span style="color:var(--neon-cyan)">*</span> Coach Full
                                Name</label>
                            <input type="text" name="coach_name" required placeholder="Full name"
                                class="input-field w-full rounded-xl px-4 py-4">
                        </div>
                        <div>
                            <label class="field-label"><span style="color:var(--neon-cyan)">*</span> Coach Email</label>
                            <input type="email" name="coach_email" required placeholder="email@university.edu"
                                class="input-field w-full rounded-xl px-4 py-4">
                        </div>
                        <div>
                            <label class="field-label"><span style="color:var(--neon-cyan)">*</span> Phone</label>
                            <input type="text" name="coach_phone" required placeholder="01XXXXXXXXX"
                                class="input-field w-full rounded-xl px-4 py-4">
                        </div>
                        <div>
                            <label class="field-label"><span style="color:var(--neon-cyan)">*</span> Designation</label>
                            <input type="text" name="coach_designation" required placeholder="Lecturer / Professor"
                                class="input-field w-full rounded-xl px-4 py-4">
                        </div>
                        <div>
                            <label class="field-label"><span style="color:var(--neon-cyan)">*</span> T-Shirt Size</label>
                            <div class="size-row" id="coachSizes">
                                <button type="button" class="sz-opt"
                                    onclick="pickSize(this,'coachSizes','coach_tshirt')">M</button>
                                <button type="button" class="sz-opt"
                                    onclick="pickSize(this,'coachSizes','coach_tshirt')">L</button>
                                <button type="button" class="sz-opt"
                                    onclick="pickSize(this,'coachSizes','coach_tshirt')">XL</button>
                                <button type="button" class="sz-opt"
                                    onclick="pickSize(this,'coachSizes','coach_tshirt')">XXL</button>
                            </div>
                            <input type="hidden" name="coach_tshirt" id="coach_tshirt"
                                value="{{ old('coach_tshirt') }}">
                        </div>
                    </div>
                @endif

                {{-- ── SECTION 03/04: Members ───────────────────────── --}}
                @php
                    $handleLabel = match ($slug) {
                        'iupc' => 'Codeforces Handle',
                        'ai-hackathon' => 'Kaggle Profile Link',
                        default => 'Student ID / Roll',
                    };
                    $sectionNum = $slug === 'iupc' ? '03' : '03';
                @endphp

                <div class="section-divider" style="margin-top:2.5rem">
                    <div class="sec-line"></div>
                    <span class="sec-title">
                        {{ $sectionNum }} · {{ $isTeam ? 'Team Members' : 'Participant Info' }}
                    </span>
                    <div class="sec-line"></div>
                </div>

                @for ($i = 1; $i <= $maxMembers; $i++)
                    @php $optional = $i > $minRequired; @endphp
                    <div class="member-card">
                        <div class="member-heading">
                            {{ $maxMembers === 1 ? 'Participant Details' : 'Member ' . $i }}
                            @if ($optional)
                                <span class="optional-badge">Optional</span>
                            @endif
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">

                            <div>
                                <label class="field-label">
                                    @if (!$optional)
                                        <span style="color:var(--neon-cyan)">*</span>
                                    @endif
                                    Full Name
                                </label>
                                <input type="text" name="m{{ $i }}_name"
                                    {{ !$optional ? 'required' : '' }} placeholder="Full name"
                                    value="{{ old('m' . $i . '_name') }}"
                                    class="input-field w-full rounded-xl px-4 py-4">
                            </div>

                            <div>
                                <label class="field-label">
                                    @if (!$optional)
                                        <span style="color:var(--neon-cyan)">*</span>
                                    @endif
                                    Email
                                </label>
                                <input type="email" name="m{{ $i }}_email"
                                    {{ !$optional ? 'required' : '' }} placeholder="email@university.edu"
                                    value="{{ old('m' . $i . '_email') }}"
                                    class="input-field w-full rounded-xl px-4 py-4">
                            </div>

                            <div>
                                <label class="field-label">
                                    @if (!$optional)
                                        <span style="color:var(--neon-cyan)">*</span>
                                    @endif
                                    Phone
                                </label>
                                <input type="text" name="m{{ $i }}_phone"
                                    {{ !$optional ? 'required' : '' }} placeholder="01XXXXXXXXX"
                                    value="{{ old('m' . $i . '_phone') }}"
                                    class="input-field w-full rounded-xl px-4 py-4">
                            </div>

                            <div>
                                <label class="field-label">
                                    @if (!$optional)
                                        <span style="color:var(--neon-cyan)">*</span>
                                    @endif
                                    {{ $handleLabel }}
                                </label>
                                <input type="text" name="m{{ $i }}_cf_handle"
                                    {{ !$optional ? 'required' : '' }} placeholder="{{ $handleLabel }}"
                                    value="{{ old('m' . $i . '_cf_handle') }}"
                                    class="input-field w-full rounded-xl px-4 py-4">
                            </div>

                            <div>
                                <label class="field-label">
                                    @if (!$optional)
                                        <span style="color:var(--neon-cyan)">*</span>
                                    @endif
                                    Prior Experience?
                                </label>
                                <select name="m{{ $i }}_person" {{ !$optional ? 'required' : '' }}
                                    class="input-field w-full rounded-xl px-4 py-4">
                                    <option value="">Select…</option>
                                    <option value="YES" {{ old('m' . $i . '_person') === 'YES' ? 'selected' : '' }}>✅
                                        Yes
                                    </option>
                                    <option value="NO" {{ old('m' . $i . '_person') === 'NO' ? 'selected' : '' }}>❌ No
                                    </option>
                                </select>
                            </div>

                            <div>
                                <label class="field-label">
                                    @if (!$optional)
                                        <span style="color:var(--neon-cyan)">*</span>
                                    @endif
                                    T-Shirt Size
                                </label>
                                <div class="size-row" id="m{{ $i }}Sizes">
                                    @foreach (['M', 'L', 'XL', 'XXL'] as $sz)
                                        <button type="button"
                                            class="sz-opt {{ old('m' . $i . '_tshirt') === $sz ? 'active' : '' }}"
                                            onclick="pickSize(this,'m{{ $i }}Sizes','m{{ $i }}_tshirt')">
                                            {{ $sz }}
                                        </button>
                                    @endforeach
                                </div>
                                <input type="hidden" name="m{{ $i }}_tshirt"
                                    id="m{{ $i }}_tshirt" value="{{ old('m' . $i . '_tshirt') }}">
                            </div>

                        </div>
                    </div>
                @endfor

                {{-- ── SUBMIT ─────────────────────────────────────── --}}
                <button type="submit" class="submit-neon">
                    ⚡ {{ $slug === 'ict-olympiad' ? 'Proceed to Payment' : 'Submit Registration' }}
                </button>

            </form>
        </div>
    </div>

    <script>
        /* ─── University autocomplete ────────────────────── */
        const UNI_LIST = [
            "BUET", "DUET", "CUET", "KUET", "RUET", "Dhaka University", "BUBT",
            "BRAC University", "NSU", "AIUB", "IUT", "SUST", "PUST", "JUST", "MIST",
            "BAU", "HSTU", "Rajshahi University", "Jahangirnagar University",
            "Chittagong University", "Khulna University", "Comilla University",
            "Noakhali Science & Tech University", "Daffodil International University",
            "East West University", "Southeast University", "United International University",
            "Bangladesh University", "American International University Bangladesh",
            "Leading University", "City University", "Stamford University Bangladesh",
            "Green University", "Ahsanullah University of Science & Technology",
            "Primeasia University", "Bangladesh University of Business & Technology"
        ];

        function filterUni(q) {
            const box = document.getElementById('uniSuggest');
            if (!q || q.length < 1) {
                box.innerHTML = '';
                return;
            }
            const matches = UNI_LIST.filter(u => u.toLowerCase().includes(q.toLowerCase())).slice(0, 7);
            if (!matches.length) {
                box.innerHTML = '';
                return;
            }
            box.innerHTML = `<div class="uni-suggest-box">${
            matches.map(m => `<div class="uni-suggest-item" onclick="pickUni('${m.replace(/'/g,"\\'")}')">
                            <i class="fa-solid fa-university" style="font-size:10px;margin-right:8px;opacity:0.5"></i>${m}
                        </div>`).join('')
        }</div>`;
        }

        function pickUni(name) {
            document.getElementById('uniInput').value = name;
            document.getElementById('uniSuggest').innerHTML = '';
            updateProgress();
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('#uniInput') && !e.target.closest('#uniSuggest')) {
                document.getElementById('uniSuggest').innerHTML = '';
            }
        });

        /* ─── Toggle group ───────────────────────────────── */
        function setToggle(groupId, inputId, val, btn) {
            document.querySelectorAll(`#${groupId} .toggle-opt`).forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(inputId).value = val;
            updateProgress();
        }

        /* ─── T-shirt size picker ────────────────────────── */
        function pickSize(btn, groupId, inputId) {
            document.querySelectorAll(`#${groupId} .sz-opt`).forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            document.getElementById(inputId).value = btn.textContent.trim();
            updateProgress();
        }

        /* ─── Progress bar ───────────────────────────────── */
        function updateProgress() {
            const form = document.getElementById('regForm');
            const required = form.querySelectorAll('[required]');
            let filled = 0;
            required.forEach(el => {
                if (el.value && el.value.trim()) filled++;
            });
            const pct = required.length > 0 ?
                Math.max(10, Math.round((filled / required.length) * 100)) :
                20;
            document.getElementById('regProgress').style.width = pct + '%';
        }

        document.querySelectorAll('#regForm input, #regForm select').forEach(el => {
            el.addEventListener('input', updateProgress);
            el.addEventListener('change', updateProgress);
        });

        updateProgress();
    </script>
@endsection
