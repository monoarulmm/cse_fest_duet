@extends('layouts.app')

@section('title', 'Final Registration | CSE CARNIVAL')

@section('custom_css')
    <style>
        .final-form-glass {
            background: var(--nav-bg);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(34, 211, 238, 0.2);
        }
        .input-field {
            background: rgba(34, 211, 238, 0.05);
            border: 1px solid rgba(34, 211, 238, 0.2);
            color: var(--text-color);
            transition: all 0.3s ease;
        }
        .input-field:focus {
            border-color: #22d3ee;
            outline: none;
            box-shadow: 0 0 15px rgba(34, 211, 238, 0.1);
        }
        .input-readonly {
            opacity: 0.6;
            cursor: not-allowed;
            background: rgba(0, 0, 0, 0.2) !important;
        }
        .section-title {
            border-left: 4px solid #22d3ee;
            padding-left: 12px;
            color: #22d3ee;
        }
        .pay-btn-gradient {
            background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            box-shadow: 0 10px 25px -5px rgba(6, 182, 212, 0.4);
        }
        .step-badge {
            background: rgba(34, 211, 238, 0.1);
            border: 1px solid rgba(34, 211, 238, 0.3);
            color: #22d3ee;
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto">

            {{-- ✅ Error Message --}}
            @if (session('error'))
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-2xl text-red-500 text-sm font-bold uppercase text-center animate-pulse">
                    <i class="fa-solid fa-circle-xmark mr-2"></i> {{ session('error') }}
                </div>
            @endif

            {{-- ✅ Success Message --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/50 rounded-2xl text-emerald-400 text-sm font-bold uppercase text-center">
                    <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
                </div>
            @endif

            {{-- ✅ Validation Errors --}}
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-2xl text-red-400 text-xs">
                    <ul class="list-disc list-inside italic font-medium">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Page Title --}}
            <div class="text-center mb-10">
                <h1 class="heading-font text-3xl md:text-5xl font-black uppercase">
                    Final <span class="text-cyan-400">Registration</span>
                </h1>
                <div class="flex items-center justify-center gap-3 mt-4">
                    <span class="step-badge px-4 py-1 rounded-full text-[10px] font-bold uppercase italic">
                        Team: {{ $team->team_name ?? 'Unnamed Team' }}
                    </span>
                    <span class="step-badge px-4 py-1 rounded-full text-[10px] font-bold uppercase italic">
                        ID: #{{ $team->id }}
                    </span>
                </div>
            </div>

            {{-- ─────────────────────────────────────────────────────────────
                 ✅ FIX: (int) cast দিয়ে strict compare — type mismatch নেই
                 ───────────────────────────────────────────────────────────── --}}
            @if (!session()->has('verified_coupon_code') || (int) session('verified_team_id') !== (int) $team->id)

                {{-- STEP 1: Coupon Verification Form --}}
                <form action="{{ route('iupc.verify_coupon') }}" method="POST"
                    class="final-form-glass rounded-[2.5rem] p-8 md:p-12 shadow-2xl max-w-xl mx-auto">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->id }}">

                    <div class="p-6 bg-cyan-500/5 rounded-3xl border border-cyan-500/20 mb-6">
                        <h3 class="section-title heading-font text-sm font-bold mb-4 uppercase">Coupon Verification</h3>
                        <div class="w-full">
                            <label for="coupon_code"
                                class="block text-cyan-400 text-[10px] font-bold mb-2 uppercase tracking-widest">
                                University Coupon Code
                            </label>
                            <input type="text" name="coupon_code" id="coupon_code" required
                                value="{{ old('coupon_code') }}"
                                class="input-field w-full rounded-xl px-4 py-3 text-white focus:border-cyan-400 @error('coupon_code') border-red-500 @enderror"
                                placeholder="Ex: XXXXXX">
                            <p class="text-[10px] text-slate-500 mt-2 italic">
                                *Valid Coupon Required to Unlock Final Form
                            </p>
                        </div>
                    </div>

                    <button type="submit"
                        class="pay-btn-gradient w-full text-white font-black py-4 rounded-2xl transition-all hover:scale-102 tracking-widest uppercase text-sm">
                        Verify & Proceed <i class="fa-solid fa-arrow-right ml-2"></i>
                    </button>
                </form>

            @else

                {{-- STEP 2: Full Registration + Payment Form --}}
                <form action="{{ route('iupc.verify.process') }}" method="POST"
                    class="final-form-glass rounded-[2.5rem] p-8 md:p-12 shadow-2xl">
                    @csrf
                    <input type="hidden" name="team_id" value="{{ $team->id }}">

                    {{-- ✅ Verified Coupon Indicator --}}
                    <div class="mb-10 p-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-2xl text-xs font-bold uppercase flex justify-between items-center tracking-wider">
                        <span>
                            <i class="fa-solid fa-lock-open mr-2"></i>
                            Coupon (<strong>{{ session('verified_coupon_code') }}</strong>) Verified & Applied!
                        </span>
                    </div>

                    {{-- Basic Information --}}
                    <h3 class="section-title heading-font text-sm font-bold mb-6 uppercase">Basic Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">

                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] uppercase text-cyan-500/70 font-bold ml-2">University Name</label>
                            <input type="text" value="{{ $team->university_name }}" readonly
                                class="input-field rounded-xl px-4 py-4 input-readonly">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-[10px] uppercase text-cyan-500/70 font-bold ml-2">Team Name</label>
                            <input type="text" name="team_name"
                                value="{{ old('team_name', $team->team_name) }}" required
                                class="input-field rounded-xl px-4 py-4">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-[10px] font-bold uppercase text-cyan-400">All-Female Team?*</label>
                            <select name="team_person" required class="input-field w-full rounded-xl px-4 py-4">
                                <option value="Mixed"
                                    {{ in_array(old('team_person', $team->team_person), ['Mixed', 'Male']) ? 'selected' : '' }}>
                                    NO
                                </option>
                                <option value="Female"
                                    {{ in_array(old('team_person', $team->team_person), ['Female', 'Femail']) ? 'selected' : '' }}>
                                    YES
                                </option>
                            </select>
                        </div>
                    </div>

                    {{-- Coach Details --}}
                    <h3 class="section-title heading-font text-sm font-bold mb-6 uppercase">Coach Details</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">

                        <div class="flex flex-col gap-2">
                            <label class="text-[9px] uppercase text-slate-500 font-bold ml-2">Full Name</label>
                            <input type="text" name="coach_name"
                                value="{{ old('coach_name', $team->coach_name) }}" required
                                class="input-field rounded-xl px-4 py-4 text-xs">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-[9px] uppercase text-slate-500 font-bold ml-2">Phone</label>
                            <input type="text" name="coach_phone"
                                value="{{ old('coach_phone', $team->coach_phone) }}" required
                                class="input-field rounded-xl px-4 py-4 text-xs">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-[9px] uppercase text-slate-500 font-bold ml-2">Email</label>
                            <input type="email" name="coach_email"
                                value="{{ old('coach_email', $team->coach_email) }}" required
                                class="input-field rounded-xl px-4 py-4 text-xs">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-[9px] uppercase text-slate-500 font-bold ml-2">Designation</label>
                            <input type="text" name="coach_designation"
                                value="{{ old('coach_designation', $team->coach_designation) }}" required
                                class="input-field rounded-xl px-4 py-4 text-xs">
                        </div>

                        <div class="flex flex-col gap-2">
                            <label class="text-[9px] uppercase text-slate-500 font-bold ml-2">T-Shirt Size</label>
                            <select name="coach_tshirt" required class="input-field rounded-xl px-4 py-4 text-xs">
                                <option value="">T-Shirt Size</option>
                                @foreach (['M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                    <option value="{{ $size }}"
                                        {{ old('coach_tshirt', $team->coach_tshirt) == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Members Info --}}
                    @for ($i = 1; $i <= 3; $i++)
                        @php
                            $nameKey   = "m{$i}_name";
                            $emailKey  = "m{$i}_email";
                            $phoneKey  = "m{$i}_phone";
                            $cfKey     = "m{$i}_cf_handle";
                            $prevExKey = "m{$i}_prev_ex";
                            $tshirtKey = "m{$i}_tshirt";
                            $req       = $i < 3 ? 'required' : '';
                        @endphp

                        <div class="mb-12">
                            <h3 class="section-title heading-font text-sm font-bold mb-6 uppercase">
                                Member {{ $i }}
                                <span class="text-[10px] lowercase text-slate-500 italic">
                                    {{ $i == 1 ? '(Leader)' : ($i == 3 ? '(Optional)' : '') }}
                                </span>
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

                                <div class="flex flex-col gap-1">
                                    <input type="text" name="m{{ $i }}_name"
                                        value="{{ old("m{$i}_name", $team->$nameKey) }}"
                                        {{ $req }} placeholder="Name"
                                        class="input-field rounded-xl px-4 py-4 text-xs">
                                </div>

                                <div class="flex flex-col gap-1">
                                    <input type="email" name="m{{ $i }}_email"
                                        value="{{ old("m{$i}_email", $team->$emailKey) }}"
                                        {{ $req }} placeholder="Email"
                                        class="input-field rounded-xl px-4 py-4 text-xs">
                                </div>

                                <div class="flex flex-col gap-1">
                                    <input type="text" name="m{{ $i }}_phone"
                                        value="{{ old("m{$i}_phone", $team->$phoneKey) }}"
                                        {{ $req }} placeholder="Phone"
                                        class="input-field rounded-xl px-4 py-4 text-xs">
                                </div>

                                <div class="flex flex-col gap-1">
                                    <input type="text" name="m{{ $i }}_cf_handle"
                                        value="{{ old("m{$i}_cf_handle", $team->$cfKey) }}"
                                        {{ $req }} placeholder="CF Handle"
                                        class="input-field rounded-xl px-4 py-4 text-xs">
                                </div>

                                <select name="m{{ $i }}_prev_ex" {{ $req }}
                                    class="input-field rounded-xl px-4 py-4 text-xs">
                                    <option value="">Previous Experience</option>
                                    <option value="YES"
                                        {{ old("m{$i}_prev_ex", $team->$prevExKey) == 'YES' ? 'selected' : '' }}>
                                        YES (Previously Participated)
                                    </option>
                                    <option value="NO"
                                        {{ old("m{$i}_prev_ex", $team->$prevExKey) == 'NO' ? 'selected' : '' }}>
                                        NO (First time Participating)
                                    </option>
                                </select>

                                <select name="m{{ $i }}_tshirt" {{ $req }}
                                    class="input-field rounded-xl px-4 py-4 text-xs">
                                    <option value="">T-Shirt Size</option>
                                    @foreach (['M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                        <option value="{{ $size }}"
                                            {{ old("m{$i}_tshirt", $team->$tshirtKey) == $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                    @endfor

                    {{-- Submit Button --}}
                    <div class="mt-8">
                        <button type="submit"
                            class="pay-btn-gradient w-full text-white font-black py-4 rounded-2xl transition-all hover:scale-102 tracking-widest uppercase text-sm">
                            Update & Proceed to Payment <i class="fa-solid fa-credit-card ml-2"></i>
                        </button>
                    </div>

                </form>
            @endif

        </div>
    </div>
@endsection