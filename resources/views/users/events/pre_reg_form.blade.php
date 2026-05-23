@extends('layouts.app')

@section('title', $event->name . ' Registration | CARNIVAL 2026')

@section('custom_css')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

<style>
    /* ═══════════════════════════════════════════
       FORM GLASS CARD
    ═══════════════════════════════════════════ */
    .reg-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-accent);
        box-shadow: var(--shadow-card);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
    }

    /* ═══════════════════════════════════════════
       SECTION DIVIDER
    ═══════════════════════════════════════════ */
    .section-divider {
        border-left: 4px solid var(--accent);
        padding-left: 14px;
        margin-bottom: 24px;
        margin-top: 40px;
    }
    .section-divider:first-child { margin-top: 0; }

    /* ═══════════════════════════════════════════
       BASE INPUT / SELECT
    ═══════════════════════════════════════════ */
    .reg-input,
    .reg-select {
        background-color: var(--accent-dim);   /* background-color আলাদা — .reg-select এর background-image এ override না হওয়ার জন্য */
        border: 1px solid var(--accent-border);
        color: var(--text-primary);
        width: 100%;
        border-radius: 12px;
        padding: 14px 16px;
        font-size: 13px;
        font-family: 'JetBrains Mono', monospace;
        font-weight: 600;
        transition: border-color 0.2s, box-shadow 0.2s, background-color 0.2s;
        appearance: none;
        -webkit-appearance: none;
    }
    .reg-input::placeholder { color: var(--text-muted); }

    .reg-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-dim);
        outline: none;
        background-color: var(--bg-elevated);
    }

    /* Select custom arrow — background-size stops the tile/repeat bug */
    .reg-select {
        background-color:    var(--accent-dim);
        background-image:    url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpolyline fill='none' stroke='%2322d3ee' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' points='1,1 6,7 11,1'/%3E%3C/svg%3E");
        background-repeat:   no-repeat;
        background-position: right 14px center;
        background-size:     12px 8px;
        padding-right:       42px;
    }
    [data-theme="light"] .reg-select {
        background-color: var(--accent-dim);
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 12 8'%3E%3Cpolyline fill='none' stroke='%230891b2' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' points='1,1 6,7 11,1'/%3E%3C/svg%3E");
        background-size:  12px 8px;
    }
    .reg-select:focus {
        background-color: var(--bg-elevated);
    }

    /* Native select option colours */
    .reg-select option {
        background: var(--bg-surface);
        color: var(--text-primary);
    }

    /* File input */
    .reg-file {
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        color: var(--text-primary);
        width: 100%;
        border-radius: 12px;
        padding: 10px 16px;
        font-size: 12px;
        font-family: 'JetBrains Mono', monospace;
        font-weight: 600;
        transition: border-color 0.2s, box-shadow 0.2s;
        cursor: pointer;
    }
    .reg-file::file-selector-button {
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        color: var(--accent);
        border-radius: 8px;
        padding: 6px 14px;
        font-size: 11px;
        font-weight: 800;
        font-family: 'JetBrains Mono', monospace;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        cursor: pointer;
        margin-right: 12px;
        transition: background 0.2s;
    }
    .reg-file::file-selector-button:hover {
        background: var(--accent);
        color: #020617;
    }
    .reg-file:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-dim);
        outline: none;
    }

    /* ═══════════════════════════════════════════
       LABEL
    ═══════════════════════════════════════════ */
    .reg-label {
        display: block;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.18em;
        margin-bottom: 8px;
        color: var(--accent);
        font-family: 'JetBrains Mono', monospace;
    }

    /* ═══════════════════════════════════════════
       TOM SELECT — Dark + Light mode
    ═══════════════════════════════════════════ */

    /* Wrapper overrides */
    .ts-wrapper { width: 100%; }

    /* Control (the visible box) */
    .ts-control {
        background: var(--accent-dim)  !important;
        border: 1px solid var(--accent-border) !important;
        color: var(--text-primary)     !important;
        border-radius: 12px            !important;
        padding: 14px 16px             !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-weight: 600               !important;
        font-size: 13px                !important;
        box-shadow: none               !important;
        min-height: unset              !important;
        transition: border-color 0.2s, box-shadow 0.2s, background 0.2s !important;
    }

    .ts-control:focus-within {
        border-color: var(--accent)    !important;
        box-shadow: 0 0 0 3px var(--accent-dim) !important;
        background: var(--bg-elevated) !important;
    }

    /* Typed text & placeholder */
    .ts-control input {
        color: var(--text-primary) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-weight: 600 !important;
        font-size: 13px !important;
        background: transparent !important;
        caret-color: var(--accent) !important;
    }
    .ts-control input::placeholder            { color: var(--text-muted) !important; }
    .ts-control input::-webkit-input-placeholder { color: var(--text-muted) !important; }
    .ts-control input::-moz-placeholder       { color: var(--text-muted) !important; }

    /* Selected item chip */
    .ts-control .item {
        color: var(--text-primary) !important;
        background: var(--bg-elevated) !important;
        border: 1px solid var(--border-accent) !important;
        border-radius: 6px !important;
        padding: 2px 8px !important;
        font-size: 12px !important;
    }

    /* Dropdown panel */
    .ts-dropdown {
        background: var(--bg-surface) !important;
        border: 1px solid var(--border-accent) !important;
        border-radius: 14px !important;
        margin-top: 6px !important;
        box-shadow: 0 12px 40px rgba(0,0,0,0.35) !important;
        overflow: hidden !important;
        backdrop-filter: blur(16px) !important;
    }

    .ts-dropdown-content { padding: 4px !important; }

    /* Dropdown item */
    .ts-dropdown .option {
        color: var(--text-secondary) !important;
        padding: 10px 14px !important;
        border-radius: 10px !important;
        font-size: 12px !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-weight: 600 !important;
        transition: background 0.15s, color 0.15s !important;
    }
    .ts-dropdown .option:hover,
    .ts-dropdown .option.active {
        background: var(--accent-dim) !important;
        color: var(--accent) !important;
    }
    .ts-dropdown .option.selected {
        background: var(--accent-dim) !important;
        color: var(--accent) !important;
    }

    /* Create option */
    .ts-dropdown .create {
        color: var(--accent) !important;
        padding: 10px 14px !important;
        font-size: 11px !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-weight: 800 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.1em !important;
        border-top: 1px solid var(--border-soft) !important;
    }

    /* No results */
    .ts-dropdown .no-results {
        color: var(--text-muted) !important;
        font-size: 12px !important;
        padding: 12px 14px !important;
        font-family: 'JetBrains Mono', monospace !important;
    }

    /* ═══════════════════════════════════════════
       SUBMIT BUTTON
    ═══════════════════════════════════════════ */
    .reg-submit {
        background: var(--accent);
        color: #020617;
        width: 100%;
        padding: 20px;
        border-radius: 1rem;
        font-family: 'Orbitron', sans-serif;
        font-weight: 900;
        font-size: 16px;
        text-transform: uppercase;
        letter-spacing: 0.2em;
        border: none;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s, box-shadow 0.2s;
        box-shadow: 0 0 32px rgba(34, 211, 238, 0.35);
    }
    .reg-submit:hover  { opacity: 0.9; box-shadow: 0 0 48px rgba(34,211,238,0.5); }
    .reg-submit:active { transform: scale(0.98); }
    .reg-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        box-shadow: none;
    }

    /* Optional badge on section header */
    .optional-tag {
        font-size: 10px;
        font-weight: 600;
        text-transform: lowercase;
        opacity: 0.45;
        font-family: 'JetBrains Mono', monospace;
    }

    /* Title gradient line */
    .title-line {
        height: 3px;
        width: 80px;
        background: linear-gradient(90deg, var(--accent), transparent);
        margin: 10px auto 0;
        border-radius: 99px;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto">

        {{-- Page Title --}}
        <div class="text-center mb-12">
            <h1 class="heading-font text-4xl md:text-5xl font-black uppercase tracking-tighter"
                style="color:var(--text-primary)">
                {{ $event->name }}
                <span style="color:var(--accent)">Registration</span>
            </h1>
            <div class="title-line"></div>
        </div>

        {{-- Form Card --}}
        <form action="{{ route('registration.store') }}" method="POST"
              enctype="multipart/form-data" id="regForm"
              class="reg-card rounded-[2.5rem] p-8 md:p-14 shadow-2xl">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            @php
                $slug          = $event->slug;
                $noStudentId   = ['iupc', 'project-showcase', 'ai-hackathon'];
                $isTeamEvent   = in_array($slug, $noStudentId);
                $maxMembers    = $isTeamEvent ? 3 : 1;
                $minRequired   = in_array($slug, ['iupc', 'ai-hackathon']) ? 2 : 1;
            @endphp

            {{-- ──────────── GENERAL INFORMATION ──────────── --}}
            <div class="section-divider !mt-0">
                <h3 class="heading-font text-base font-bold uppercase" style="color:var(--text-primary)">
                    General Information
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">

                {{-- Institution (TomSelect) --}}
                <div>
                    <label class="reg-label">Institution*</label>
                    <select name="university_name" id="uni_select" required></select>
                </div>

                @if ($isTeamEvent)
                    {{-- Team Name --}}
                    <div>
                        <label class="reg-label">Team Name*</label>
                        <input type="text" name="team_name" value="{{ old('team_name') }}"
                               required placeholder="Enter Team Name" class="reg-input">
                    </div>

                    {{-- All-Female --}}
                    <div>
                        <label class="reg-label">All-Female Team?*</label>
                        <select name="team_person" required class="reg-select">
                            <option value="Mixed"  {{ old('team_person') == 'Mixed'  ? 'selected' : '' }}>No (Mixed)</option>
                            <option value="Female" {{ old('team_person') == 'Female' ? 'selected' : '' }}>Yes (All-Female)</option>
                        </select>
                    </div>
                @endif

                @if ($slug === 'project-showcase')
                    <div>
                        <label class="reg-label">Project Title*</label>
                        <input type="text" name="project_title" value="{{ old('project_title') }}"
                               required class="reg-input" placeholder="Your Project Title">
                    </div>
                    <div>
                        <label class="reg-label">Abstract (PDF)* — Max 3MB</label>
                        <input type="file" name="abstract_file" accept=".pdf" required class="reg-file">
                    </div>
                    <div>
                        <label class="reg-label">Domain*</label>
                        <select name="domain" required class="reg-select">
                            <option value="">Select Domain</option>
                            @foreach (['AI & Data Science' => 'AI & Data Science', 'IoT' => 'IoT & Embedded Intelligence', 'Software' => 'Software & Digital Platforms', 'Smart' => 'Smart Solutions'] as $val => $label)
                                <option value="{{ $val }}" {{ old('domain') == $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif

            </div>

            {{-- ──────────── COACH INFO (IUPC only) ──────────── --}}
            @if ($slug === 'iupc')
                <div class="section-divider">
                    <h3 class="heading-font text-base font-bold uppercase" style="color:var(--text-primary)">
                        Coach Details
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                    <div>
                        <label class="reg-label">Coach Name*</label>
                        <input type="text" name="coach_name" required placeholder="Full Name"
                               value="{{ old('coach_name') }}" class="reg-input">
                    </div>
                    <div>
                        <label class="reg-label">Coach Email*</label>
                        <input type="email" name="coach_email" required placeholder="Email"
                               value="{{ old('coach_email') }}" class="reg-input">
                    </div>
                    <div>
                        <label class="reg-label">Coach Phone*</label>
                        <input type="text" name="coach_phone" required placeholder="Phone"
                               value="{{ old('coach_phone') }}" class="reg-input">
                    </div>
                    <div>
                        <label class="reg-label">Designation*</label>
                        <input type="text" name="coach_designation" required placeholder="Designation"
                               value="{{ old('coach_designation') }}" class="reg-input">
                    </div>
                    <div>
                        <label class="reg-label">T-Shirt Size*</label>
                        <select name="coach_tshirt" required class="reg-select">
                            <option value="">Select Size</option>
                            @foreach (['M','L','XL','XXL','XXXL'] as $s)
                                <option value="{{ $s }}" {{ old('coach_tshirt') == $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            @endif

            {{-- ──────────── MEMBER / PARTICIPANT INFO ──────────── --}}
            @for ($i = 1; $i <= $maxMembers; $i++)
                <div class="mb-10">
                    <div class="section-divider">
                        <h3 class="heading-font text-base font-bold uppercase" style="color:var(--text-primary)">
                            {{ $maxMembers == 1 ? 'Participant Info' : 'Member ' . $i }}
                            @if ($i > $minRequired)
                                <span class="optional-tag">(Optional)</span>
                            @endif
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                        <div>
                            <label class="reg-label">Full Name{{ $i <= $minRequired ? '*' : '' }}</label>
                            <input type="text" name="m{{ $i }}_name"
                                   {{ $i <= $minRequired ? 'required' : '' }}
                                   placeholder="Full Name" class="reg-input"
                                   value="{{ old('m'.$i.'_name') }}">
                        </div>

                        <div>
                            <label class="reg-label">Email{{ $i <= $minRequired ? '*' : '' }}</label>
                            <input type="email" name="m{{ $i }}_email"
                                   {{ $i <= $minRequired ? 'required' : '' }}
                                   placeholder="Email Address" class="reg-input"
                                   value="{{ old('m'.$i.'_email') }}">
                        </div>

                        <div>
                            <label class="reg-label">Phone{{ $i <= $minRequired ? '*' : '' }}</label>
                            <input type="text" name="m{{ $i }}_phone"
                                   {{ $i <= $minRequired ? 'required' : '' }}
                                   placeholder="Phone Number" class="reg-input"
                                   value="{{ old('m'.$i.'_phone') }}">
                        </div>

                        <div>
                            <label class="reg-label">Previous Experience{{ $i <= $minRequired ? '*' : '' }}</label>
                            <select name="m{{ $i }}_prev_ex"
                                    {{ $i <= $minRequired ? 'required' : '' }}
                                    class="reg-select">
                                <option value="">Select Experience</option>
                                <option value="YES" {{ old('m'.$i.'_prev_ex') == 'YES' ? 'selected' : '' }}>
                                    YES — Previously Participated
                                </option>
                                <option value="NO" {{ old('m'.$i.'_prev_ex') == 'NO' ? 'selected' : '' }}>
                                    NO — First Time
                                </option>
                            </select>
                        </div>

                        {{-- Conditional: Codeforces / Kaggle / Student ID --}}
                        @if ($slug === 'iupc')
                            <div>
                                <label class="reg-label">Codeforces Handle{{ $i <= $minRequired ? '*' : '' }}</label>
                                <input type="text" name="m{{ $i }}_cf_handle"
                                       {{ $i <= $minRequired ? 'required' : '' }}
                                       placeholder="CF Handle" class="reg-input"
                                       value="{{ old('m'.$i.'_cf_handle') }}">
                            </div>
                        @elseif ($slug === 'ai-hackathon')
                            <div>
                                <label class="reg-label">Kaggle Profile Link{{ $i <= $minRequired ? '*' : '' }}</label>
                                <input type="text" name="m{{ $i }}_cf_handle"
                                       {{ $i <= $minRequired ? 'required' : '' }}
                                       placeholder="https://kaggle.com/..." class="reg-input"
                                       value="{{ old('m'.$i.'_cf_handle') }}">
                            </div>
                        @elseif (!$isTeamEvent)
                            <div>
                                <label class="reg-label">Student ID / Roll*</label>
                                <input type="text" name="student_id" required
                                       placeholder="Student ID" class="reg-input"
                                       value="{{ old('student_id') }}">
                            </div>
                        @endif

                        {{-- T-Shirt --}}
                        @if ($isTeamEvent || $slug === 'ict-olympiad')
                            <div>
                                <label class="reg-label">T-Shirt Size{{ $i <= $minRequired ? '*' : '' }}</label>
                                <select name="m{{ $i }}_tshirt"
                                        {{ $i <= $minRequired ? 'required' : '' }}
                                        class="reg-select">
                                    <option value="">Select Size</option>
                                    @foreach (['M','L','XL','XXL','XXXL'] as $s)
                                        <option value="{{ $s }}" {{ old('m'.$i.'_tshirt') == $s ? 'selected' : '' }}>
                                            {{ $s }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                    </div>
                </div>
            @endfor

            {{-- ──────────── SUBMIT ──────────── --}}
            <div class="mt-12">
                <button type="submit" id="submitBtn" class="reg-submit">
                    {{ $isTeamEvent ? 'Submit Registration' : 'Submit & Pay →' }}
                </button>
            </div>

        </form>
    </div>
</div>
@endsection

@section('custom_js')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ── TomSelect University ─────────────────────────────── */
    new TomSelect('#uni_select', {
        valueField:  'name',
        labelField:  'name',
        searchField: 'name',
        create:      true,
        placeholder: 'Search Institute...',
        maxOptions:  50,
        load: function (query, callback) {
            fetch('/data/universities.json')
                .then(r => r.json())
                .then(json => callback(json.map(item => ({ name: item }))))
                .catch(() => callback());
        }
    });

    /* ── Double-submit prevention ─────────────────────────── */
    const form      = document.getElementById('regForm');
    const submitBtn = document.getElementById('submitBtn');
    let submitting  = false;

    form.addEventListener('submit', function (e) {
        if (submitting) { e.preventDefault(); return; }
        if (!form.checkValidity()) return;

        submitting            = true;
        submitBtn.textContent = 'PROCESSING… PLEASE WAIT';
        submitBtn.disabled    = true;
    });
});
</script>
@endsection
