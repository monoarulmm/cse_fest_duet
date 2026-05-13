@extends('layouts.app')

@section('title', 'Final Registration | CSE FEST 2026')

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
        }

        .input-readonly {
            opacity: 0.6;
            cursor: not-allowed;
            background: rgba(0, 0, 0, 0.1) !important;
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
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-5xl mx-auto">

            <div class="text-center mb-10">
                <h1 class="heading-font text-3xl md:text-5xl font-black uppercase">
                    Final <span class="text-cyan-400">Registration</span>
                </h1>
                <p class="text-xs tracking-[0.3em] opacity-70 mt-2 uppercase">
                    {{ $event->slug == 'iupc' ? 'Update Info & Proceed to Payment' : 'Review Info & Proceed to Payment' }}
                </p>
            </div>

            <form action="{{ route('iupc.payment.process') }}" method="POST"
                class="final-form-glass rounded-[2.5rem] p-8 md:p-12 shadow-2xl">
                @csrf
                <input type="hidden" name="team_id" value="{{ $team->id }}">
                <input type="hidden" name="amount" value="{{ $finalAmount ?? $team->event->reg_fee }}">

                {{-- University & Team (Always ReadOnly) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                    <div>
                        <label
                            class="block text-[10px] font-bold text-cyan-500 uppercase mb-2 tracking-widest">University</label>
                        <input type="text" value="{{ $team->university_name }}" readonly
                            class="input-field w-full rounded-xl px-4 py-4 input-readonly">
                    </div>
                    <div>
                        <label class="block text-[10px] font-bold text-cyan-500 uppercase mb-2 tracking-widest">Team
                            Name</label>
                        <input type="text" value="{{ $team->team_name }}"
                            class="input-field w-full rounded-xl px-4 py-4 ">
                    </div>
                </div>

                {{-- Coach Info --}}
                <h3 class="section-title heading-font text-sm font-bold mb-6 uppercase">Coach Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
                    <input type="text" name="coach_name" value="{{ $team->coach_name }}"
                        {{ $event->slug != 'iupc' ? 'readonly' : 'required' }}
                        class="input-field rounded-xl px-4 py-4 {{ $event->slug != 'iupc' ? 'input-readonly' : '' }}"
                        placeholder="Coach Name">

                    <input type="text" name="coach_phone" value="{{ $team->coach_phone }}"
                        {{ $event->slug != 'iupc' ? 'readonly' : 'required' }}
                        class="input-field rounded-xl px-4 py-4 {{ $event->slug != 'iupc' ? 'input-readonly' : '' }}"
                        placeholder="Phone">

                    <input type="email" name="coach_email" value="{{ $team->coach_email }}"
                        {{ $event->slug != 'iupc' ? 'readonly' : 'required' }}
                        class="input-field rounded-xl px-4 py-4 {{ $event->slug != 'iupc' ? 'input-readonly' : '' }}"
                        placeholder="Email">
                    <input type="text" name="coach_designation" value="{{ $team->coach_designation }}"
                        {{ $event->slug != 'iupc' ? 'readonly' : 'required' }}
                        class="input-field rounded-xl px-4 py-4 {{ $event->slug != 'iupc' ? 'input-readonly' : '' }}"
                        placeholder="Designation">
                </div>

                {{-- Members Info --}}
                @for ($i = 1; $i <= 3; $i++)
                    @php
                        $name = "m{$i}_name";
                        $email = "m{$i}_email";
                        $phone = "m{$i}_phone";
                        $cf = "m{$i}_cf_handle";
                    @endphp
                    <div class="mb-12">
                        <h3 class="section-title heading-font text-sm font-bold mb-6 uppercase">Member {{ $i }}
                            {{ $i == 3 ? '(Optional)' : '' }}</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <input type="text" name="m{{ $i }}_name" value="{{ $team->$name }}"
                                {{ $event->slug != 'iupc' || $i == 3 ? 'readonly' : 'required' }}
                                class="input-field rounded-xl px-4 py-4 text-xs {{ $event->slug != 'iupc' ? 'input-readonly' : '' }}"
                                placeholder="Full Name">

                            <input type="email" name="m{{ $i }}_email" value="{{ $team->$email }}"
                                {{ $event->slug != 'iupc' || $i == 3 ? 'readonly' : 'required' }}
                                class="input-field rounded-xl px-4 py-4 text-xs {{ $event->slug != 'iupc' ? 'input-readonly' : '' }}"
                                placeholder="Email">

                            <input type="text" name="m{{ $i }}_phone" value="{{ $team->$phone }}"
                                {{ $event->slug != 'iupc' || $i == 3 ? 'readonly' : 'required' }}
                                class="input-field rounded-xl px-4 py-4 text-xs {{ $event->slug != 'iupc' ? 'input-readonly' : '' }}"
                                placeholder="Phone">

                            <input type="text" name="m{{ $i }}_cf_handle" value="{{ $team->$cf }}"
                                {{ $event->slug != 'iupc' ? 'readonly' : '' }}
                                class="input-field rounded-xl px-4 py-4 text-xs {{ $event->slug != 'iupc' ? 'input-readonly' : '' }}"
                                placeholder="CF Handle">
                        </div>
                    </div>
                @endfor

                {{-- Summary & Button --}}
                <div class="mt-16 p-8 rounded-3xl bg-cyan-500/10 border-2 border-dashed border-cyan-500/30">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                        <div>
                            <h3 class="heading-font text-xl text-white uppercase italic">Order Summary</h3>
                            <p class="text-3xl font-black text-cyan-400 mt-2">
                                {{ number_format($finalAmount ?? $team->event->reg_fee) }} BDT
                            </p>
                        </div>

                        <button type="submit"
                            class="pay-btn-gradient w-full md:w-80 text-white font-black py-5 rounded-2xl transition-all hover:scale-105 tracking-widest uppercase">
                            {{ $event->slug == 'iupc' ? 'Update & Pay Now' : 'Confirm & Pay Now' }}
                            <i class="fa-solid fa-rocket ml-2"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
