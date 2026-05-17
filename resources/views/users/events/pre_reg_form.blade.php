@extends('layouts.app')

@section('title', $event->name . ' Registration | CARNIVAl 2026')

@section('custom_css')
    <style>
        :root {
            --form-bg: rgba(15, 23, 42, 0.7);
            --input-bg: rgba(33, 41, 71, 0.5);
            --input-border: rgba(34, 211, 238, 0.2);
        }

        .form-glass {
            background: var(--form-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--input-border);
        }

        .input-field {
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            color: white;
            transition: all 0.3s ease;
        }

        .input-field:focus {
            border-color: #22d3ee;
            box-shadow: 0 0 12px rgba(34, 211, 238, 0.3);
            outline: none;
        }

        .section-divider {
            border-left: 4px solid #22d3ee;
            padding-left: 12px;
            margin-bottom: 24px;
            margin-top: 40px;
        }

        /* TomSelect Styling */
        .ts-control {
            background: rgba(34, 211, 238, 0.05) !important;
            border: 1px solid rgba(34, 211, 238, 0.2) !important;
            color: #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 14px 16px !important;
        }

        .ts-dropdown {
            background: #0f172a !important;
            color: #e2e8f0 !important;
            border: 1px solid rgba(34, 211, 238, 0.2) !important;
        }
    </style>
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
@endsection

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto">

            <div class="text-center mb-12">
                <h1 class="heading-font text-4xl md:text-6xl font-black uppercase tracking-tighter text-white">
                    {{ $event->name }} <span class="text-cyan-400">REGISTRATION</span>
                </h1>
                <div class="h-1 w-32 bg-cyan-500 mx-auto mt-2 rounded-full"></div>
            </div>

            <form action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data"
                class="form-glass rounded-[2.5rem] p-8 md:p-14 shadow-2xl">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                {{-- Institutional & Team Info --}}
                <div class="section-divider !mt-0">
                    <h3 class="heading-font text-lg font-bold uppercase text-white">General Information</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase text-cyan-400">Institution*</label>
                        <select name="university_name" id="uni_select" required class="w-full">
                            <option value=""></option>
                        </select>
                    </div>

                    @php
                        $slug = $event->slug;
                        $noStudentIdEvents = ['iupc', 'project-showcase', 'ai-hackathon'];
                    @endphp

                    @if (in_array($slug, $noStudentIdEvents))
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase text-cyan-400">Team Name*</label>
                            <input type="text" name="team_name" value="{{ old('team_name') }}" required
                                class="input-field w-full rounded-xl px-4 py-4" placeholder="Enter Team Name">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase text-cyan-400">All-Female Team?*</label>
                            <select name="team_person" required class="input-field w-full rounded-xl px-4 py-4">
                                <option value="Mixed" {{ old('team_person') == 'Mail' ? 'selected' : '' }}>NO</option>
                                <option value="Femail" {{ old('team_person') == 'Femail' ? 'selected' : '' }}>YES</option>
                            </select>
                        </div>
                    @endif

                    @if ($slug === 'project-showcase')
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase text-cyan-400">Project Title*</label>
                            <input type="text" name="project_title" value="{{ old('project_title') }}" required
                                class="input-field w-full rounded-xl px-4 py-4">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase text-cyan-400">Abstract (PDF)*</label>
                            <input type="file" name="abstract_file" accept=".pdf" required
                                class="input-field w-full rounded-xl px-4 py-3">
                        </div>

                        <div class="space-y-2">
                            <label
                                class="block text-[10px] font-bold uppercase tracking-widest text-cyan-400">Domain*</label>
                            <select name="domain" required class="input-field w-full rounded-xl px-4 py-4">
                                <option value="">Select Domain</option>
                                <option value="AI & Data Science">AI & Data Science</option>
                                <option value="IoT">IoT & Embedded Intelligence</option>
                                <option value="Software">Software & Digital Platforms</option>
                                <option value="Smart">Smart Solutions</option>
                            </select>
                        </div>
                    @endif
                </div>

                {{-- Step 2: Coach Info (IUPC Only) --}}
                @if ($slug === 'iupc')
                    <div class="section-divider">
                        <h3 class="heading-font text-lg font-bold uppercase text-white">Coach Details</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <input type="text" name="coach_name" required placeholder="Coach Name"
                            class="input-field rounded-xl px-4 py-4">
                        <input type="email" name="coach_email" required placeholder="Coach Email"
                            class="input-field rounded-xl px-4 py-4">
                        <input type="text" name="coach_phone" required placeholder="Coach Phone"
                            class="input-field rounded-xl px-4 py-4">
                        <input type="text" name="coach_designation" required placeholder="Designation"
                            class="input-field rounded-xl px-4 py-4">
                        <select name="coach_tshirt" required class="input-field rounded-xl px-4 py-4">
                            <option value="">T-Shirt Size</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                    </div>
                @endif

                {{-- Step 3: Member Info --}}
                @php
                    $isTeamEvent = in_array($slug, $noStudentIdEvents);
                    $maxMembers = $isTeamEvent ? 3 : 1;
                    $minRequired = in_array($slug, ['iupc', 'ai-hackathon']) ? 2 : 1;
                @endphp

                @for ($i = 1; $i <= $maxMembers; $i++)
                    <div class="mb-12">
                        <div class="section-divider">
                            <h3 class="heading-font text-lg font-bold uppercase text-white">
                                {{ $maxMembers == 1 ? 'Participant Info' : 'Member ' . $i }}
                                {!! $i > $minRequired ? '<span class="text-xs lowercase opacity-50">(Optional)</span>' : '' !!}
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <input type="text" name="m{{ $i }}_name"
                                {{ $i <= $minRequired ? 'required' : '' }} placeholder="Full Name"
                                class="input-field rounded-xl px-4 py-4" value="{{ old('m' . $i . '_name') }}">

                            <input type="email" name="m{{ $i }}_email"
                                {{ $i <= $minRequired ? 'required' : '' }} placeholder="Email"
                                class="input-field rounded-xl px-4 py-4" value="{{ old('m' . $i . '_email') }}">

                            <input type="text" name="m{{ $i }}_phone"
                                {{ $i <= $minRequired ? 'required' : '' }} placeholder="Phone"
                                class="input-field rounded-xl px-4 py-4" value="{{ old('m' . $i . '_phone') }}">

                            <select name="m{{ $i }}_prev_ex" {{ $i <= $minRequired ? 'required' : '' }}
                                class="input-field rounded-xl px-4 py-4">
                                <option value="">Previous Experience</option>
                                <option value="YES" {{ old('m' . $i . '_prev_ex') == 'YES' ? 'selected' : '' }}>YES
                                    (Previously Perticipated )
                                </option>
                                <option value="NO" {{ old('m' . $i . '_prev_ex') == 'NO' ? 'selected' : '' }}>No
                                    (First
                                    time Participating)
                                </option>
                            </select>

                            {{-- Handles Logic --}}
                            @if ($slug === 'iupc')
                                <input type="text" name="m{{ $i }}_cf_handle"
                                    {{ $i <= $minRequired ? 'required' : '' }} placeholder="Codeforces Handle"
                                    class="input-field rounded-xl px-4 py-4" value="{{ old('m' . $i . '_cf_handle') }}">
                            @elseif ($slug === 'ai-hackathon')
                                <input type="text" name="m{{ $i }}_cf_handle"
                                    {{ $i <= $minRequired ? 'required' : '' }} placeholder="Kaggle Link"
                                    class="input-field rounded-xl px-4 py-4" value="{{ old('m' . $i . '_cf_handle') }}">
                            @elseif (!in_array($slug, $noStudentIdEvents))
                                {{-- শুধুমাত্র ICT বা অন্য সাধারণ ইভেন্টে (যেগুলো $noStudentIdEvents এ নেই) স্টুডেন্ট আইডি আসবে --}}
                                {{-- অন্য সব ইভেন্টের জন্য স্টুডেন্ট আইডি/রোল --}}
                                <input type="text" name="student_id" required placeholder="Student ID/Roll"
                                    class="input-field rounded-xl px-4 py-4">
                            @endif

                            <select name="m{{ $i }}_tshirt" {{ $i <= $minRequired ? 'required' : '' }}
                                class="input-field rounded-xl px-4 py-4">
                                <option value="">T-Shirt Size</option>
                                @foreach (['M', 'L', 'XL', 'XXL'] as $size)
                                    <option value="{{ $size }}"
                                        {{ old('m' . $i . '_tshirt') == $size ? 'selected' : '' }}>{{ $size }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor

                <div class="mt-12">
                    <button type="submit"
                        class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-black py-6 rounded-2xl transition-all shadow-[0_0_30px_rgba(34,211,238,0.4)] uppercase tracking-[0.2em] text-lg">
                        {{ $slug === 'ict-olympiad' ? 'Proceed to Payment' : 'Submit Registration' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script remains same as before --}}
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            new TomSelect("#uni_select", {
                valueField: 'name',
                labelField: 'name',
                searchField: 'name',
                create: true,
                placeholder: "Search University...",
                load: function(query, callback) {
                    fetch('/data/universities.json').then(res => res.json()).then(json => {
                        callback(json.map(item => ({
                            name: item
                        })));
                    }).catch(() => callback());
                }
            });
        });
    </script>
@endsection
