@extends('layouts.app')

@section('title', $event->name . ' Registration | CSE FEST 2026')

@section('custom_css')
    <style>
        :root {
            --form-bg: rgba(15, 23, 42, 0.7);
            --input-bg: rgba(2, 6, 23, 0.5);
            --input-border: rgba(34, 211, 238, 0.2);
            --label-color: #22d3ee;
        }

        .form-glass {
            background: var(--form-bg);
            backdrop-filter: blur(12px);
            border: 1px solid var(--input-border);
            transition: all 0.4s ease;
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

        /* TomSelect Dark Theme Support */
        .ts-control {
            background-color: var(--input-bg) !important;
            border: 1px solid var(--input-border) !important;
            border-radius: 0.75rem !important;
            padding: 14px 16px !important;
            color: white !important;
        }

        .ts-dropdown {
            background-color: #0f172a !important;
            color: white !important;
            border: 1px solid #22d3ee !important;
        }

        .ts-dropdown .active {
            background-color: #22d3ee !important;
            color: #000 !important;
        }
    </style>

    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>

    <style>
        /* আপনার সাইবারপাঙ্ক থিমের সাথে মিল রাখার জন্য কাস্টম স্টাইল */
        .ts-control {
            background: rgba(34, 211, 238, 0.05) !important;
            border: 1px solid rgba(34, 211, 238, 0.2) !important;
            color: #e2e8f0 !important;
            border-radius: 12px !important;
            padding: 10px !important;
        }

        .ts-dropdown {
            background: #0f172a !important;
            color: #e2e8f0 !important;
            border: 1px solid rgba(34, 211, 238, 0.2) !important;
        }

        .ts-dropdown .active {
            background: rgba(34, 211, 238, 0.2) !important;
            color: #22d3ee !important;
        }

        .ts-control input::placeholder {
            color: rgba(148, 163, 184, 0.5) !important;
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto">

            <div class="text-center mb-12">
                <h1 class="heading-font text-4xl md:text-6xl font-black uppercase tracking-tighter text-white">
                    {{ $event->name }} <span class="text-cyan-400">REGISTRATION</span>
                </h1>
                <div class="h-1 w-32 bg-cyan-500 mx-auto mt-2 rounded-full shadow-[0_0_10px_#22d3ee]"></div>
                <p class="mt-4 text-[11px] tracking-[0.3em] text-cyan-400/80 uppercase font-bold">
                    Registration Fee: {{ $event->reg_fee }} BDT | Ends: {{ $event->end_date->format('d M, Y') }}
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-8 p-4 rounded-2xl bg-red-500/10 border border-red-500/50 text-red-400">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data"
                class="form-glass rounded-[2.5rem] p-8 md:p-14 shadow-2xl">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">

                {{-- --- Step 1: Institutional & Team Info --- --}}
                <div class="section-divider !mt-0">
                    <h3 class="heading-font text-lg font-bold uppercase text-white">Institutional Details</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    {{-- ইউনিভার্সিটি সিলেকশন --}}
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-cyan-400">
                            Institution*
                        </label>
                        <select name="university_name" id="uni_select" required class="w-full"
                            placeholder="Search or type your institution...">
                            <option value=""></option>
                        </select>
                    </div>

                    {{-- নতুন ফিল্ড: Previous Experience --}}
                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-cyan-400">
                            Previous Experience in CSE FEST?*
                        </label>
                        <select name="prev_ex" required class="input-field w-full rounded-xl px-4 py-4">
                            <option value="NO" {{ old('prev_ex') == 'NO' ? 'selected' : '' }}>NO</option>
                            <option value="YES" {{ old('prev_ex') == 'YES' ? 'selected' : '' }}>YES</option>
                        </select>
                    </div>

                    {{-- টিম নেম ফিল্ড: শুধুমাত্র IUPC এবং Project Showcase এর জন্য দেখাবে --}}
                    {{-- অন্য সব ইভেন্ট (ICT Olympiad, AI Hackathon ইত্যাদি) এর জন্য এটা হাইড থাকবে --}}
                    @if (in_array($event->slug, ['iupc', 'project-showcase']))
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-cyan-400">Team
                                Name*</label>
                            <input type="text" name="team_name" value="{{ old('team_name') }}" required
                                class="input-field w-full rounded-xl px-4 py-4">
                        </div>
                    @endif

                    {{-- Project Showcase এর জন্য অতিরিক্ত ফিল্ডসমূহ --}}
                    @if ($event->slug === 'project-showcase')
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-cyan-400">Project
                                Title*</label>
                            <input type="text" name="project_title" required
                                class="input-field w-full rounded-xl px-4 py-4">
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

                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest text-cyan-400">Abstract
                                (PDF)*</label>
                            <input type="file" name="abstract_file" accept=".pdf" required
                                class="input-field w-full rounded-xl px-4 py-3">
                        </div>
                    @endif
                </div>

                {{-- --- Step 2: Coach Info (Only for IUPC) --- --}}
                @if ($event->slug === 'iupc')
                    <div class="section-divider">
                        <h3 class="heading-font text-lg font-bold uppercase text-white">Coach Details</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <input type="text" name="coach_name" required placeholder="Coach Full Name"
                            class="input-field rounded-xl px-4 py-4">
                        <input type="email" name="coach_email" required placeholder="Coach Email"
                            class="input-field rounded-xl px-4 py-4">
                        <input type="text" name="coach_phone" required placeholder="Coach Phone"
                            class="input-field rounded-xl px-4 py-4">
                        <input type="text" name="coach_designation" required placeholder="Designation"
                            class="input-field rounded-xl px-4 py-4">
                        <select name="coach_tshirt" required class="input-field rounded-xl px-4 py-4">
                            <option value="">Coach T-Shirt Size</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                            <option value="XXXL">XXXL</option>
                        </select>
                    </div>
                @endif

                {{-- --- Step 3: Participant Details --- --}}
                @php
                    $slug = $event->slug;
                    $isOlympiad = $slug === 'ict-olympiad';

                    // চেক করা হচ্ছে এটা IUPC, ICT Olympiad বা Project Showcase কি না
                    $isSpecialEvent = in_array($slug, ['iupc', 'ict-olympiad', 'project-showcase']);

                    // যদি স্পেশাল ইভেন্ট না হয়, তবে মেম্বার সংখ্যা ১ হবে
                    $maxMembers = $isSpecialEvent ? ($isOlympiad ? 1 : 3) : 1;
                    $minRequired = $isSpecialEvent ? ($isOlympiad ? 1 : 2) : 1;
                @endphp

                @for ($i = 1; $i <= $maxMembers; $i++)
                    <div class="mb-12">
                        <div class="section-divider">
                            <h3 class="heading-font text-lg font-bold uppercase text-white">
                                {{ $maxMembers == 1 ? 'Participant Info' : 'Member ' . $i }}
                                {!! $i > $minRequired ? '<span class="text-xs lowercase opacity-50">(Optional)</span>' : '' !!}
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                            {{-- ১. নাম --}}
                            <input type="text" name="m{{ $i }}_name"
                                {{ $i <= $minRequired ? 'required' : '' }} placeholder="Full Name"
                                class="input-field rounded-xl px-4 py-4">

                            {{-- ২. ইমেইল --}}
                            <input type="email" name="m{{ $i }}_email"
                                {{ $i <= $minRequired ? 'required' : '' }} placeholder="Email"
                                class="input-field rounded-xl px-4 py-4">

                            {{-- ৩. ফোন --}}
                            <input type="text" name="m{{ $i }}_phone"
                                {{ $i <= $minRequired ? 'required' : '' }} placeholder="Phone"
                                class="input-field rounded-xl px-4 py-4">

                            {{-- ৪. স্পেশাল ফিল্ড অথবা স্টুডেন্ট আইডি --}}
                            @if ($slug === 'iupc')
                                <input type="text" name="m{{ $i }}_cf_handle" required
                                    placeholder="Codeforces Handle" class="input-field rounded-xl px-4 py-4">
                            @elseif ($slug === 'ai-hackathon')
                                <input type="text" name="m{{ $i }}_cf_handle" required
                                    placeholder="Kaggle Account Link" class="input-field rounded-xl px-4 py-4">
                            @else
                                {{-- অন্য সব ইভেন্টের জন্য স্টুডেন্ট আইডি/রোল --}}
                                <input type="text" name="student_id" required placeholder="Student ID/Roll"
                                    class="input-field rounded-xl px-4 py-4">
                            @endif

                            {{-- টি-শার্ট ফিল্ড: শুধুমাত্র স্পেশাল ইভেন্টগুলোর জন্য দেখানো হবে --}}
                            @if ($isSpecialEvent)
                                <div class="md:col-span-2 lg:col-span-1">
                                    <select name="m{{ $i }}_tshirt" {{ $i <= $minRequired ? 'required' : '' }}
                                        class="input-field w-full rounded-xl px-4 py-4">
                                        <option value="">T-Shirt Size</option>
                                        <option value="M">M</option>
                                        <option value="L">L</option>
                                        <option value="XL">XL</option>
                                        <option value="XXL">XXL</option>
                                        <option value="XXXL">XXXL</option>
                                    </select>
                                </div>
                            @endif
                        </div>
                    </div>
                @endfor

                {{-- --- Submit Button --- --}}
                <div class="mt-12">
                    <button type="submit"
                        class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-black py-6 rounded-2xl transition-all duration-300 shadow-[0_0_30px_rgba(34,211,238,0.4)] uppercase tracking-[0.2em] text-lg">
                        {{ $isOlympiad ? 'Confirm & Pay ' : 'Submit Registration' }}
                    </button>
                </div>
            </form>
        </div>
    </div>





    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Tom Select ইনিশিয়ালাইজ করা
            new TomSelect("#uni_select", {
                valueField: 'name',
                labelField: 'name',
                searchField: 'name',
                create: true, // ইউজার লিস্টে না থাকলে নিজে টাইপ করতে পারবে
                placeholder: "Search University or Polytechnic...",
                load: function(query, callback) {
                    // আপনার JSON ফাইল থেকে ডেটা ফেচ করা
                    var url = '/data/universities.json';
                    fetch(url)
                        .then(response => response.json())
                        .then(json => {
                            // JSON ডেটাকে Tom Select এর ফরম্যাটে ম্যাপ করা
                            let data = json.map(item => {
                                return {
                                    name: item
                                };
                            });
                            callback(data);
                        }).catch(() => {
                            callback();
                        });
                },
                // অপশনাল: ড্রপডাউন রেন্ডারিং স্টাইল
                render: {
                    option: function(item, escape) {
                        return `<div class="py-2 px-3 uppercase text-[11px] tracking-wider font-bold">
                            ${escape(item.name)}
                        </div>`;
                    },
                    no_results: function(data, escape) {
                        return `<div class="no-results py-2 px-3 text-red-400 text-[10px]">No matches found. Press Enter to add "${escape(data.query)}"</div>`;
                    }
                }
            });
        });
    </script>
@endsection
