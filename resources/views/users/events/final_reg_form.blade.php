@extends('layouts.app')

@section('title', 'Final Registration | CSE CARNIVAL')

@section('custom_css')
<style>
    .final-form-glass {
        background: var(--bg-surface);
        border: 1px solid var(--accent-border);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
    }
    .input-field {
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        color: var(--text-primary);
        width: 100%;
        border-radius: 0.75rem;
        padding: 1rem;
        font-size: 0.75rem;
        font-family: 'JetBrains Mono', monospace;
        outline: none;
        transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .input-field:focus {
        border-color: var(--accent);
        box-shadow: 0 0 15px rgba(34, 211, 238, 0.12);
    }
    .input-field::placeholder { color: var(--text-muted); }
    .input-readonly {
        opacity: 0.5;
        cursor: not-allowed;
        background: var(--bg-elevated) !important;
    }
    .input-field option {
        background: var(--bg-surface);
        color: var(--text-primary);
    }
    .section-title {
        border-left: 4px solid var(--accent);
        padding-left: 12px;
        color: var(--accent);
    }
    .pay-btn-gradient {
        background: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
        box-shadow: 0 10px 25px -5px rgba(6, 182, 212, 0.35);
        color: white;
        font-weight: 900;
        border-radius: 1rem;
        padding: 1rem;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        font-size: 0.875rem;
        transition: transform 0.2s, box-shadow 0.2s;
        border: none;
        cursor: pointer;
    }
    .pay-btn-gradient:hover {
        transform: scale(1.015);
        box-shadow: 0 14px 30px -5px rgba(6, 182, 212, 0.45);
    }
    .step-badge {
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        color: var(--accent);
        border-radius: 9999px;
        padding: 0.25rem 1rem;
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        font-style: italic;
    }
    .coupon-verified-bar {
        background: rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: rgb(52, 211, 153);
        border-radius: 1rem;
        padding: 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .coupon-box {
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        border-radius: 1.5rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }
    .field-label {
        display: block;
        font-size: 0.5625rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-bottom: 0.5rem;
        margin-left: 0.5rem;
        color: var(--text-muted);
    }
    .field-label.accent { color: var(--accent); opacity: 0.8; }
    .alert-error {
        background: rgba(239, 68, 68, 0.08);
        border: 1px solid rgba(239, 68, 68, 0.4);
        color: rgb(248, 113, 113);
        border-radius: 1rem;
        padding: 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .alert-success {
        background: rgba(16, 185, 129, 0.08);
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: rgb(52, 211, 153);
        border-radius: 1rem;
        padding: 1rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        text-align: center;
        margin-bottom: 1.5rem;
    }
    .alert-warning {
        background: rgba(234, 179, 8, 0.08);
        border: 1px solid rgba(234, 179, 8, 0.3);
        color: rgb(250, 204, 21);
        border-radius: 1rem;
        padding: 1rem;
        font-size: 0.625rem;
        margin-bottom: 1.5rem;
    }
    .member-block {
        background: var(--bg-elevated);
        border: 1px solid var(--border-soft);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="max-w-5xl mx-auto">

        {{-- Alerts --}}
        @if (session('error'))
            <div class="alert-error animate-pulse">
                <i class="fa-solid fa-circle-xmark mr-2"></i> {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert-success">
                <i class="fa-solid fa-circle-check mr-2"></i> {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert-warning">
                <ul class="list-disc list-inside italic font-medium space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Page Title --}}
        <div class="text-center mb-10">
            <h1 class="heading-font text-3xl md:text-5xl font-black uppercase" style="color:var(--text-primary)">
                Final <span style="color:var(--accent)">Registration</span>
            </h1>
            <div class="flex items-center justify-center gap-3 mt-4">
                <span class="step-badge">Team: {{ $team->team_name ?? 'Unnamed Team' }}</span>
                <span class="step-badge">ID: #{{ $team->id }}</span>
            </div>
        </div>

        @if (!session()->has('verified_coupon_code') || (int) session('verified_team_id') !== (int) $team->id)

            {{-- STEP 1: Coupon Verification --}}
            <form action="{{ route('iupc.verify_coupon') }}" method="POST"
                class="final-form-glass rounded-[2.5rem] p-8 md:p-12 shadow-2xl max-w-xl mx-auto">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">

                <div class="coupon-box">
                    <h3 class="section-title heading-font text-sm font-bold mb-6 uppercase">Coupon Verification</h3>
                    <label for="coupon_code" class="field-label accent">University Coupon Code</label>
                    <input type="text" name="coupon_code" id="coupon_code" required
                        value="{{ old('coupon_code') }}"
                        class="input-field @error('coupon_code') border-red-500 @enderror"
                        placeholder="Ex: XXXXXX">
                    <p class="text-[10px] mt-2 italic" style="color:var(--text-muted)">
                        *Valid Coupon Required to Unlock Final Form
                    </p>
                </div>

                <button type="submit" class="pay-btn-gradient">
                    Verify & Proceed <i class="fa-solid fa-arrow-right ml-2"></i>
                </button>
            </form>

        @else

            {{-- STEP 2: Full Registration Form --}}
            <form action="{{ route('iupc.verify.process') }}" method="POST"
                class="final-form-glass rounded-[2.5rem] p-8 md:p-12 shadow-2xl">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">

                {{-- Coupon verified bar --}}
                <div class="coupon-verified-bar mb-10">
                    <span>
                        <i class="fa-solid fa-lock-open mr-2"></i>
                        Coupon (<strong>{{ session('verified_coupon_code') }}</strong>) Verified & Applied!
                    </span>
                </div>

                {{-- Basic Information --}}
                <h3 class="section-title heading-font text-sm font-bold mb-6 uppercase">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
                    <div class="flex flex-col gap-2">
                        <label class="field-label">University Name</label>
                        <input type="text" value="{{ $team->university_name }}" readonly
                            class="input-field input-readonly">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="field-label">Team Name</label>
                        <input type="text" name="team_name"
                            value="{{ old('team_name', $team->team_name) }}" required
                            class="input-field">
                    </div>
                    <div class="flex flex-col gap-2">
                        <label class="field-label accent">All-Female Team?</label>
                        <select name="team_person" required class="input-field">
                            <option value="Mixed" {{ in_array(old('team_person', $team->team_person), ['Mixed', 'Male']) ? 'selected' : '' }}>NO</option>
                            <option value="Female" {{ in_array(old('team_person', $team->team_person), ['Female', 'Femail']) ? 'selected' : '' }}>YES</option>
                        </select>
                    </div>
                </div>

                {{-- Coach Details --}}
                <h3 class="section-title heading-font text-sm font-bold mb-6 uppercase">Coach Details</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
                    @foreach ([
                        ['name' => 'coach_name',        'label' => 'Full Name',    'type' => 'text'],
                        ['name' => 'coach_phone',       'label' => 'Phone',        'type' => 'text'],
                        ['name' => 'coach_email',       'label' => 'Email',        'type' => 'email'],
                        ['name' => 'coach_designation', 'label' => 'Designation',  'type' => 'text'],
                    ] as $field)
                    <div class="flex flex-col gap-2">
                        <label class="field-label">{{ $field['label'] }}</label>
                        <input type="{{ $field['type'] }}" name="{{ $field['name'] }}"
                            value="{{ old($field['name'], $team->{$field['name']}) }}" required
                            class="input-field">
                    </div>
                    @endforeach

                    <div class="flex flex-col gap-2">
                        <label class="field-label">T-Shirt Size</label>
                        <select name="coach_tshirt" required class="input-field">
                            <option value="">Select Size</option>
                            @foreach (['M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                <option value="{{ $size }}" {{ old('coach_tshirt', $team->coach_tshirt) == $size ? 'selected' : '' }}>{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Members --}}
                @for ($i = 1; $i <= 3; $i++)
                    @php
                        $req = $i < 3 ? 'required' : '';
                        $label = $i == 1 ? '(Leader)' : ($i == 3 ? '(Optional)' : '');
                    @endphp
                    <div class="member-block">
                        <h3 class="section-title heading-font text-sm font-bold mb-6 uppercase">
                            Member {{ $i }}
                            <span class="text-[10px] lowercase italic" style="color:var(--text-muted)">{{ $label }}</span>
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            <input type="text" name="m{{ $i }}_name"
                                value="{{ old("m{$i}_name", $team->{"m{$i}_name"}) }}"
                                {{ $req }} placeholder="Full Name" class="input-field">

                            <input type="email" name="m{{ $i }}_email"
                                value="{{ old("m{$i}_email", $team->{"m{$i}_email"}) }}"
                                {{ $req }} placeholder="Email" class="input-field">

                            <input type="text" name="m{{ $i }}_phone"
                                value="{{ old("m{$i}_phone", $team->{"m{$i}_phone"}) }}"
                                {{ $req }} placeholder="Phone" class="input-field">

                            <input type="text" name="m{{ $i }}_cf_handle"
                                value="{{ old("m{$i}_cf_handle", $team->{"m{$i}_cf_handle"}) }}"
                                {{ $req }} placeholder="CF Handle" class="input-field">

                            <select name="m{{ $i }}_prev_ex" {{ $req }} class="input-field">
                                <option value="">Previous Experience</option>
                                <option value="YES" {{ old("m{$i}_prev_ex", $team->{"m{$i}_prev_ex"}) == 'YES' ? 'selected' : '' }}>YES – Previously Participated</option>
                                <option value="NO"  {{ old("m{$i}_prev_ex", $team->{"m{$i}_prev_ex"}) == 'NO'  ? 'selected' : '' }}>NO – First Time</option>
                            </select>

                            <select name="m{{ $i }}_tshirt" {{ $req }} class="input-field">
                                <option value="">T-Shirt Size</option>
                                @foreach (['M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                    <option value="{{ $size }}" {{ old("m{$i}_tshirt", $team->{"m{$i}_tshirt"}) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endfor

                <button type="submit" class="pay-btn-gradient mt-4">
                    Update & Proceed to Payment <i class="fa-solid fa-credit-card ml-2"></i>
                </button>
            </form>
        @endif

    </div>
</div>
@endsection