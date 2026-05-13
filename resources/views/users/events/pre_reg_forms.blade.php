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

        .light-mode {
            --form-bg: rgba(255, 255, 255, 0.9);
            --input-bg: #f8fafc;
            --input-border: #cbd5e1;
            --label-color: #0891b2;
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
            color: var(--text-color);
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
            color: var(--text-color);
        }

        option {
            background: #0f172a;
            color: white;
        }

        .light-mode option {
            background: white;
            color: #0f172a;
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto">

            <div class="text-center mb-12">
                <h1 class="heading-font text-4xl md:text-6xl font-black uppercase tracking-tighter">
                    {{ $event->name }} <span class="text-cyan-400">REGISTRATION</span>
                </h1>
                <div class="h-1 w-32 bg-cyan-500 mx-auto mt-2 rounded-full shadow-[0_0_10px_#22d3ee]"></div>
                <p class="mt-4 text-[10px] tracking-[0.3em] opacity-70 uppercase">
                    Fee: {{ $event->reg_fee }} BDT | Deadline: {{ $event->end_date->format('d M, Y') }}
                </p>
            </div>

            {{-- Error Handling --}}
            @if ($errors->any())
                <div class="mb-8 p-4 rounded-2xl bg-red-500/10 border border-red-500/50 text-red-400">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Form Action updated to dynamic store and added multipart support --}}
            <form action="{{ route('registration.store') }}" method="POST" enctype="multipart/form-data"
                class="form-glass rounded-[2rem] p-6 md:p-12 shadow-2xl">
                @csrf

                <input type="hidden" name="event_id" value="{{ $event->id }}">

                {{-- --- Common Top Fields --- --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                    {{-- <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest"
                            style="color: var(--label-color)">University Name*</label>
                        <select name="university_name" id="uni_select" required
                            class="input-field w-full rounded-xl px-4 py-4" onchange="toggleUniInput()">
                            <option value="" disabled selected>Select Institution</option>
                            <option value="DUET" {{ old('university_name') == 'DUET' ? 'selected' : '' }}>Dhaka University
                                of Engineering & Technology (DUET)</option>
                            <option value="BUET" {{ old('university_name') == 'BUET' ? 'selected' : '' }}>BUET</option>
                            <option value="Others" {{ old('university_name') == 'Others' ? 'selected' : '' }}>Others
                                (Specify Name)</option>
                        </select>
                    </div> --}}

                    <div class="space-y-2">
                        <label class="block text-[10px] font-bold uppercase tracking-widest"
                            style="color: var(--label-color)">University Name*</label>

                        <select name="university_name" id="uni_select" required
                            class="input-field w-full rounded-xl px-4 py-4" onchange="toggleUniInput()">
                            <option value="" disabled selected>Select or Type Institution</option>

                            {{-- পাবলিক ইউনিভার্সিটি --}}
                            <optgroup label="Public Universities">
                                <option value="University of Dhaka">University of Dhaka</option>
                                <option value="Bangladesh University of Engineering & Technology (BUET)">BUET</option>
                                <option value="Dhaka University of Engineering & Technology (DUET)">DUET</option>
                            </optgroup>

                            {{-- পলিটেকনিক --}}
                            <optgroup label="Polytechnic Institutes">
                                <option value="Dhaka Polytechnic Institute">Dhaka Polytechnic Institute</option>
                            </optgroup>

                            <option value="Others">Others (Specify Name)</option>
                        </select>
                    </div>

                    <div id="other_uni_container"
                        class="space-y-2 {{ old('university_name') == 'Others' ? '' : 'hidden' }}">
                        <label class="block text-[10px] font-bold uppercase tracking-widest"
                            style="color: var(--label-color)">Full University Name*</label>
                        <input type="text" name="other_university" id="other_uni_input"
                            value="{{ old('other_university') }}" placeholder="Type University Name..."
                            class="input-field w-full rounded-xl px-4 py-4">
                    </div>

                    {{-- Team Name: Hide for solo events like Olympiad if not needed --}}
                    @if ($event->slug !== 'ict-olympiad')
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest"
                                style="color: var(--label-color)">Team Name* (Unique)</label>
                            <input type="text" name="team_name" value="{{ old('team_name') }}" required
                                placeholder="Ex: DUET_ByteBlasters" class="input-field w-full rounded-xl px-4 py-4">
                        </div>
                    @endif
                </div>

                {{-- --- Project Showcase Specific Fields --- --}}
                @if ($event->slug === 'project-showcase')
                    <div class="section-divider">
                        <h3 class="heading-font text-lg font-bold uppercase text-white">Project Details</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest"
                                style="color: var(--label-color)">Project Title*</label>
                            <input type="text" name="project_title" required placeholder="Enter Project Name"
                                class="input-field w-full rounded-xl px-4 py-4">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest"
                                style="color: var(--label-color)">Domain*</label>
                            <select name="domain" required class="input-field w-full rounded-xl px-4 py-4">
                                <option value="">Select Domain</option>
                                <option value="AI & Data Science">AI & Data Science</option>
                                <option value="IoT">IoT & Embedded Intelligence</option>
                                <option value="Software">Software & Digital Platforms</option>
                            </select>
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="block text-[10px] font-bold uppercase tracking-widest"
                                style="color: var(--label-color)">Project Abstract (PDF, Max 3MB)*</label>
                            <input type="file" name="abstract_file" required accept="application/pdf"
                                class="input-field w-full rounded-xl px-4 py-4 file:bg-cyan-500 file:border-none file:px-4 file:py-1 file:rounded-lg file:mr-4 file:text-slate-900 file:font-bold">
                        </div>
                    </div>
                @endif

                {{-- --- Coach Information (IUPC Only) --- --}}
                @if ($event->slug === 'iupc')
                    <div class="section-divider">
                        <h3 class="heading-font text-lg font-bold uppercase text-white">Coach Information</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
                        <input type="text" name="coach_name" required placeholder="Coach Name"
                            class="input-field rounded-xl px-4 py-4">
                        <input type="text" name="coach_desgination" required placeholder="phone"
                            class="input-field rounded-xl px-4 py-4">
                        <input type="email" name="coach_email" required placeholder="Coach Email"
                            class="input-field rounded-xl px-4 py-4">
                        <input type="text" name="coach_phone" required placeholder="Phone"
                            class="input-field rounded-xl px-4 py-4">
                        <select name="coach_tshirt" required class="input-field rounded-xl px-4 py-4">
                            <option value="">Coach T-Shirt Size</option>
                            <option value="M">M</option>
                            <option value="L">L</option>
                            <option value="XL">XL</option>
                            <option value="XXL">XXL</option>
                        </select>
                        {{-- <input type="text" name="coupon_code" placeholder="Coupon Code (If any)"
                            class="input-field rounded-xl px-4 py-4"> --}}
                    </div>
                @endif

                {{-- --- Member Loop --- --}}
                @php
                    $max = $event->slug === 'ict-olympiad' ? 1 : 3;
                    $min = $event->slug === 'ict-olympiad' ? 1 : 2;
                @endphp

                @for ($i = 1; $i <= $max; $i++)
                    <div class="mb-12">
                        <div class="section-divider">
                            <h3 class="heading-font text-lg font-bold uppercase text-white">
                                {{ $max == 1 ? 'Participant Details' : 'Member ' . $i }}
                                {!! $i > $min ? '<span class="text-xs lowercase opacity-50">(Optional)</span>' : '' !!}
                            </h3>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <input type="text" name="m{{ $i }}_name" {{ $i <= $min ? 'required' : '' }}
                                placeholder="Full Name" class="input-field rounded-xl px-4 py-4">
                            <input type="email" name="m{{ $i }}_email" {{ $i <= $min ? 'required' : '' }}
                                placeholder="Email" class="input-field rounded-xl px-4 py-4">
                            <input type="text" name="m{{ $i }}_phone" {{ $i <= $min ? 'required' : '' }}
                                placeholder="Phone" class="input-field rounded-xl px-4 py-4">

                            {{-- Specific handle for events --}}
                            @if ($event->slug === 'iupc')
                                <input type="text" name="m{{ $i }}_cf_handle" placeholder="CF Handle"
                                    class="input-field rounded-xl px-4 py-4">
                            @elseif($event->slug === 'ai-hackathon')
                                {{-- <input type="url" name="m{{ $i }}_github" placeholder="GitHub URL"
                                    class="input-field rounded-xl px-4 py-4"> --}}
                            @elseif($event->slug === 'ict-olympiad')
                                <input type="text" name="student_id" required placeholder="Student ID"
                                    class="input-field rounded-xl px-4 py-4">
                            @endif

                            <select name="m{{ $i }}_tshirt" {{ $i <= $min ? 'required' : '' }}
                                class="input-field rounded-xl px-4 py-4 {{ $event->slug === 'ict-olympiad' ? '' : 'md:col-span-2' }}">
                                <option value="">T-Shirt Size</option>
                                <option value="M">M</option>
                                <option value="L">L</option>
                                <option value="XL">XL</option>
                                <option value="XXL">XXL</option>
                            </select>
                        </div>
                    </div>
                @endfor

                <div class="mt-8">
                    <button type="submit"
                        class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-900 font-black py-5 rounded-2xl transition-all duration-300 shadow-[0_0_30px_rgba(34,211,238,0.4)] hover:shadow-[0_0_50px_rgba(34,211,238,0.6)] uppercase tracking-widest heading-font">
                        Confirm Registration
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('custom_js')
    <script>
        function toggleUniInput() {
            const select = document.getElementById('uni_select');
            const container = document.getElementById('other_uni_container');
            const input = document.getElementById('other_uni_input');

            if (select.value === 'Others') {
                container.classList.remove('hidden');
                input.setAttribute('required', 'required');
            } else {
                container.classList.add('hidden');
                input.removeAttribute('required');
            }
        }

        // Run once on load to handle validation back errors
        window.onload = toggleUniInput;
    </script>



    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">

    <style>
        /* আপনার ডার্ক থিমের সাথে ম্যাচ করানোর জন্য কাস্টম স্টাইল */
        .ts-control {
            background-color: #0f172a !important;
            /* Slate 900 */
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            border-radius: 0.75rem !important;
            padding: 12px 16px !important;
            color: white !important;
        }

        .ts-dropdown {
            background-color: #0f172a !important;
            color: white !important;
            border: 1px solid rgba(34, 211, 238, 0.3) !important;
        }

        .ts-dropdown .active {
            background-color: #06b6d4 !important;
            /* Cyan 500 */
            color: #000 !important;
        }

        .ts-control input {
            color: white !important;
        }

        /* সাজেশন বক্সে অপশনগুলোর কালার */
        .ts-dropdown .option {
            padding: 10px;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
@endsection
