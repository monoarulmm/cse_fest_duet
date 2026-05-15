@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-8">

        <div
            class="max-w-4xl mx-auto admin-card rounded-3xl overflow-hidden p-8 shadow-2xl bg-slate-900/80 backdrop-blur-xl border border-white/5">
            {{-- Header --}}
            <div class="flex justify-between items-center border-b border-cyan-500/20 pb-4 mb-6">
                <h2 class="heading-font text-2xl text-white uppercase">
                    Registration <span class="text-cyan-400">Details</span>
                </h2>
                <a href="{{ route('admin.dashboard') }}"
                    class="text-xs text-muted hover:text-white uppercase tracking-widest transition-colors">← Back</a>
            </div>

            {{-- Main Info Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- ১. জেনারেল ইনফো --}}
                <div class="space-y-4">
                    <h4 class="text-cyan-500 font-bold uppercase text-xs tracking-widest">General Info</h4>
                    <div class="bg-slate-900/50 p-4 rounded-xl border border-white/5">
                        <p class="text-muted text-[10px] uppercase">Event Name</p>
                        <p class="text-white font-bold">{{ $registration->event->name }}</p>

                        <p class="text-muted text-[10px] uppercase mt-3">University</p>
                        <p class="text-white">{{ $registration->university_name }}</p>

                        @if ($registration->team_name)
                            <p class="text-muted text-[10px] uppercase mt-3">Team Name</p>
                            <p class="text-cyan-400 font-bold text-lg">{{ $registration->team_name }}</p>
                        @endif

                        @if ($registration->event->slug === 'project-showcase')
                            <p class="text-muted text-[10px] uppercase mt-3">Project Title</p>
                            <p class="text-white italic">"{{ $registration->project_title }}"</p>
                            <div class="mt-4">
                                <a href="{{ asset('storage/' . $registration->abstract_file) }}" target="_blank"
                                    class="inline-block bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 px-3 py-2 rounded-lg text-[10px] uppercase font-bold hover:bg-cyan-500 hover:text-slate-900 transition">
                                    View PDF Abstract 📄
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- ২. স্ট্যাটাস ও পেমেন্ট --}}
                <div class="space-y-4">
                    <h4 class="text-cyan-500 font-bold uppercase text-xs tracking-widest">Status & Payment</h4>
                    <div class="bg-slate-900/50 p-4 rounded-xl border border-white/5 h-full">
                        <p class="text-muted text-[10px] uppercase">Current Status</p>
                        <div class="mt-1">
                            <span
                                class="px-3 py-1 rounded-full text-[10px] font-bold uppercase border 
                                {{ $registration->status == 'verified'
                                    ? 'bg-green-500/20 text-green-400 border-green-500/30'
                                    : ($registration->status == 'selected'
                                        ? 'bg-blue-500/20 text-blue-400 border-blue-500/30'
                                        : 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30') }}">
                                {{ $registration->status }}
                            </span>
                        </div>



                        <p class="text-muted text-[10px] uppercase mt-4">Payment Status</p>
                        <p
                            class="font-bold {{ $registration->payment_status == 'paid' ? 'text-green-400' : 'text-red-400' }}">
                            {{ strtoupper($registration->payment_status) }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- ৩. টিম মেম্বারস --}}
            <div class="mt-8">
                <h4 class="text-cyan-500 font-bold uppercase text-xs tracking-widest mb-4">Team Members</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @for ($i = 1; $i <= 3; $i++)
                        @php $name = "m{$i}_name"; @endphp
                        @if ($registration->$name)
                            <div
                                class="bg-slate-900/50 p-4 rounded-xl border border-white/5 hover:border-cyan-500/30 transition">
                                <p class="text-cyan-400 font-bold text-xs uppercase mb-2">Member {{ $i }}</p>
                                <p class="text-white text-sm font-medium">{{ $registration->{"m{$i}_name"} }}</p>
                                <p class="text-muted text-[10px]">{{ $registration->{"m{$i}_email"} }}</p>
                                <p class="text-muted text-[10px]">{{ $registration->{"m{$i}_phone"} }}</p>
                                <div class="mt-3 flex justify-between items-center border-t border-white/5 pt-2">
                                    <span class="text-[9px] text-muted uppercase">Size:
                                        {{ $registration->{"m{$i}_tshirt"} }}</span>
                                    @if ($registration->{"m{$i}_cf_handle"})
                                        <span class="text-[9px] text-cyan-500 font-mono">CF:
                                            {{ $registration->{"m{$i}_cf_handle"} }}</span>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endfor
                </div>
            </div>

            {{-- ৪. কোচ ইনফো (শুধুমাত্র IUPC এর জন্য) --}}
            @if ($registration->event->slug === 'iupc')
                <div class="mt-8 bg-cyan-500/5 p-6 rounded-2xl border border-cyan-500/20">
                    <h4 class="text-cyan-500 font-bold uppercase text-xs tracking-widest mb-3">Coach Information</h4>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div>
                            <p class="text-muted text-[10px] uppercase">Name</p>
                            <p class="text-white text-sm">{{ $registration->coach_name }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[10px] uppercase">Designation</p>
                            <p class="text-white text-sm">{{ $registration->coach_designation }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[10px] uppercase">Email</p>
                            <p class="text-white text-sm">{{ $registration->coach_email }}</p>
                        </div>
                        <div>
                            <p class="text-muted text-[10px] uppercase">T-Shirt</p>
                            <p class="text-white text-sm font-bold text-cyan-400">{{ $registration->coach_tshirt }}</p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- ৫. একশন বাটন সেকশন --}}
            <div
                class="mt-8 bg-slate-900/50 p-6 rounded-2xl border border-white/5 flex flex-col md:flex-row justify-between items-center gap-6">



                {{-- ১. IUPC ম্যানেজমেন্ট --}}
                @if ($registration->event->slug === 'iupc')
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4 w-full">
                        <div class="text-center md:text-left">
                            <h4 class="text-cyan-500 font-bold uppercase text-xs tracking-widest mb-1">IUPC Team Management
                            </h4>
                            <p class="text-slate-500 text-[11px]">Updating to 'Selected' will notify the <span
                                    class="text-cyan-400 font-bold">Coach</span> via email.</p>
                        </div>
                        <form action="{{ route('admin.registration.updateStatus_pw', $registration->id) }}" method="POST"
                            class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="status"
                                class="bg-slate-800 text-white text-xs border border-white/10 rounded-lg px-4 py-2.5 focus:outline-none focus:border-cyan-500 transition">
                                <option value="pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="selected" {{ $registration->status == 'selected' ? 'selected' : '' }}>
                                    Selected</option>
                                <option value="verified" {{ $registration->status == 'verified' ? 'selected' : '' }}>
                                    Verified (Paid)</option>
                                <option value="rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>
                                    Rejected</option>
                            </select>
                            <button type="submit"
                                class="bg-cyan-500 hover:bg-cyan-400 text-slate-900 px-5 py-2.5 rounded-lg font-black uppercase text-[10px] transition-all shadow-[0_0_15px_rgba(34,211,238,0.3)]">Update</button>
                        </form>
                    </div>

                    {{-- ২. এআই হ্যাকাথন ম্যানেজমেন্ট --}}
                @elseif($registration->event->slug === 'ai-hackathon')
                    <div class="space-y-6">
                        {{-- পার্ট ১: বাল্ক লিঙ্ক --}}
                        <div class="bg-cyan-500/5 p-4 rounded-xl border border-cyan-500/20">
                            <h4 class="text-cyan-500 font-bold uppercase text-[10px] mb-2">Phase 1: Send Preliminary Link
                            </h4>
                            <form action="{{ route('admin.event.sendBulkLink', $registration->event_id) }}" method="POST"
                                class="flex gap-2">
                                @csrf
                                <input type="url" name="contest_link" placeholder="Enter Global Contest Link" required
                                    class="bg-slate-800 text-white text-xs border border-white/10 rounded-lg px-4 py-2 focus:outline-none focus:border-cyan-500 transition flex-1">
                                <button type="submit"
                                    class="bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-[10px] font-bold uppercase transition">Send
                                    to All</button>
                            </form>
                        </div>
                        {{-- পার্ট ২: ইন্ডিভিজুয়াল আপডেট --}}
                        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                            <div class="text-center md:text-left">
                                <h4 class="text-white font-bold uppercase text-xs tracking-widest mb-1">Phase 2: Status
                                    Update</h4>
                                <p class="text-muted text-[11px]">Updates will notify all team members.</p>
                            </div>
                            <form action="{{ route('admin.registration.updateStatus.ai', $registration->id) }}"
                                method="POST" class="flex items-center gap-2">
                                @csrf @method('PATCH')
                                <select name="status"
                                    class="bg-slate-800 text-white text-xs border border-white/10 rounded-lg px-4 py-2.5 focus:outline-none focus:border-cyan-500 transition">
                                    <option value="pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="selected" {{ $registration->status == 'selected' ? 'selected' : '' }}>
                                        Selected</option>
                                    <option value="verified" {{ $registration->status == 'verified' ? 'selected' : '' }}>
                                        Verified (Paid)</option>
                                    <option value="rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>
                                        Rejected</option>
                                </select>
                                <button type="submit"
                                    class="bg-cyan-500 hover:bg-cyan-400 text-slate-900 px-6 py-2.5 rounded-lg font-black uppercase text-[10px] transition-all">Update</button>
                            </form>
                        </div>
                    </div>

                    {{-- ৩. প্রজেক্ট শোকেস, আইসিটি অলিম্পিয়াড এবং অন্যান্য সকল ইভেন্ট --}}
                @else
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4 w-full">
                        <div class="text-center md:text-left">
                            <h4 class="text-cyan-500 font-bold uppercase text-xs tracking-widest mb-1">
                                {{ str_replace('-', ' ', $registration->event->slug) }} Management
                            </h4>
                            <p class="text-slate-500 text-[11px]">Update status and trigger automatic email notifications.
                            </p>
                        </div>

                        <form action="{{ route('admin.registration.updateStatus_pw', $registration->id) }}"
                            method="POST" class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="status"
                                class="bg-slate-800 text-white text-xs border border-white/10 rounded-lg px-4 py-2.5 focus:outline-none focus:border-cyan-500 transition">
                                <option value="pending" {{ $registration->status == 'pending' ? 'selected' : '' }}>Pending
                                </option>
                                <option value="selected" {{ $registration->status == 'selected' ? 'selected' : '' }}>
                                    Selected</option>
                                <option value="verified" {{ $registration->status == 'verified' ? 'selected' : '' }}>
                                    Verified (Paid)</option>
                                <option value="rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>
                                    Rejected</option>
                            </select>
                            <button type="submit"
                                class="bg-cyan-500 hover:bg-cyan-400 text-slate-900 px-5 py-2.5 rounded-lg font-black uppercase text-[10px] transition-all shadow-[0_0_10px_rgba(34,211,238,0.2)]">
                                Update Status
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
