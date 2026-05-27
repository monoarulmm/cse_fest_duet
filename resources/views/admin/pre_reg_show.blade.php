@extends('layouts.app')

@section('custom_css')
<style>
    .detail-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-accent);
        border-radius: 1.5rem;
        padding: 1rem;
    }
    .detail-section-title {
        color: var(--accent);
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.15em;
    }
    .detail-label {
        color: var(--text-muted);
        font-size: 0.625rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        margin-bottom: 0.2rem;
    }
    .detail-value {
        color: var(--text-primary);
        font-size: 0.875rem;
    }
    .detail-value.accent { color: var(--accent); font-weight: 700; font-size: 1.1rem; }
    .member-card {
        background: var(--bg-elevated);
        border: 1px solid var(--border-soft);
        border-radius: 0.75rem;
        padding: 1rem;
        transition: border-color 0.2s;
    }
    .member-card:hover { border-color: var(--accent-border); }
    .member-divider { border-top: 1px solid var(--border-soft); padding-top: 0.5rem; margin-top: 0.75rem; }
    .coach-block {
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    .action-block {
        background: var(--bg-elevated);
        border: 1px solid var(--border-soft);
        border-radius: 1rem;
        padding: 1.5rem;
        margin-top: 2rem;
    }
    .status-select {
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        color: var(--text-primary);
        font-size: 0.75rem;
        border-radius: 0.5rem;
        padding: 0.625rem 1rem;
        outline: none;
        transition: border-color 0.2s;
        font-family: 'JetBrains Mono', monospace;
    }
    .status-select:focus { border-color: var(--accent); }
    .status-select option {
        background: var(--bg-surface);
        color: var(--text-primary);
    }
    .update-btn {
        background: var(--accent);
        color: #020617;
        font-weight: 900;
        font-size: 0.625rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        padding: 0.625rem 1.25rem;
        border-radius: 0.5rem;
        border: none;
        cursor: pointer;
        transition: opacity 0.2s, transform 0.15s;
        box-shadow: 0 0 15px rgba(34,211,238,0.25);
    }
    .update-btn:hover { opacity: 0.88; transform: scale(1.02); }
    .link-input {
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        color: var(--text-primary);
        font-size: 0.75rem;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        outline: none;
        flex: 1;
        transition: border-color 0.2s;
    }
    .link-input:focus { border-color: var(--accent); }
    .link-input::placeholder { color: var(--text-muted); }
    .send-btn {
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        color: var(--text-secondary);
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        border-radius: 0.5rem;
        padding: 0.5rem 1rem;
        cursor: pointer;
        transition: background 0.2s, border-color 0.2s, color 0.2s;
    }
    .send-btn:hover {
        background: var(--accent-dim);
        border-color: var(--accent-border);
        color: var(--accent);
    }
    .phase-block {
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        border-radius: 0.75rem;
        padding: 1rem;
        margin-bottom: 1rem;
    }
    .back-link {
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        transition: color 0.2s;
        text-decoration: none;
    }
    .back-link:hover { color: var(--accent); }
    .pdf-btn {
        display: inline-block;
        background: var(--accent-dim);
        border: 1px solid var(--accent-border);
        color: var(--accent);
        padding: 0.5rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        transition: background 0.2s, color 0.2s;
        text-decoration: none;
        margin-top: 1rem;
    }
    .pdf-btn:hover {
        background: var(--accent);
        color: #020617;
    }
    .header-divider {
        border-bottom: 1px solid var(--accent-border);
        padding-bottom: 1rem;
        margin-bottom: 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto detail-card shadow-2xl p-8">

        {{-- Header --}}
        <div class="header-divider">
            <h2 class="heading-font text-2xl uppercase font-black" style="color:var(--text-primary)">
                Registration <span style="color:var(--accent)">Details</span>
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="back-link" style="color:var(--text-muted)">← Back</a>
        </div>

        {{-- Main Info Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            {{-- General Info --}}
            <div class="space-y-4">
                <p class="detail-section-title">General Info</p>
                <div class="member-card">
                    <p class="detail-label">Event Name</p>
                    <p class="detail-value font-bold">{{ $registration->event->name }}</p>

                    <p class="detail-label mt-3">University</p>
                    <p class="detail-value">{{ $registration->university_name }}</p>

                    @if ($registration->team_name)
                        <p class="detail-label mt-3">Team Name</p>
                        <p class="detail-value accent">{{ $registration->team_name }}</p>
                        <p class="detail-value accent">{{ $registration->team_person }}</p>
                    @endif

                    @if ($registration->event->slug === 'project-showcase')
                        <p class="detail-label mt-3">Project Title</p>
                        <p class="detail-value italic">"{{ $registration->project_title }}"</p>
                        <a href="{{ asset('storage/' . $registration->abstract_file) }}" target="_blank" class="pdf-btn">
                            View PDF Abstract 📄
                        </a>
                    @endif
                </div>
            </div>

            {{-- Status & Payment --}}
            <div class="space-y-4">
                <p class="detail-section-title">Status & Payment</p>
                <div class="member-card h-full">
                    <p class="detail-label">Current Status</p>
                    <div class="mt-1">
                        @php
                            $statusStyles = [
                                'verified' => 'background:rgba(34,197,94,0.12); color:rgb(74,222,128); border:1px solid rgba(34,197,94,0.3)',
                                'selected' => 'background:rgba(59,130,246,0.12); color:rgb(96,165,250); border:1px solid rgba(59,130,246,0.3)',
                            ];
                            $style = $statusStyles[$registration->status] ?? 'background:rgba(234,179,8,0.12); color:rgb(250,204,21); border:1px solid rgba(234,179,8,0.3)';
                        @endphp
                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase"
                            style="{{ $style }}">
                            {{ $registration->status }}
                        </span>
                    </div>

                    <p class="detail-label mt-4">Payment Status</p>
                    <p class="font-bold text-sm"
                        style="{{ $registration->payment_status == 'paid' ? 'color:rgb(74,222,128)' : 'color:rgb(248,113,113)' }}">
                        {{ strtoupper($registration->payment_status) }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Team Members --}}
        <div class="mt-8">
            <p class="detail-section-title mb-4">Team Members</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @for ($i = 1; $i <= 3; $i++)
                    @php $name = "m{$i}_name"; @endphp
                    @if ($registration->$name)
                        <div class="member-card">
                            <p class="text-xs font-bold uppercase mb-2" style="color:var(--accent)">Member {{ $i }}</p>
                            <p class="text-sm font-medium" style="color:var(--text-primary)">{{ $registration->{"m{$i}_name"} }}</p>
                            <p class="text-[10px]" style="color:var(--text-muted)">{{ $registration->{"m{$i}_email"} }}</p>
                            <p class="text-[10px]" style="color:var(--text-muted)">{{ $registration->{"m{$i}_phone"} }}</p>
                            <div class="member-divider flex flex-wrap justify-between items-center gap-1">
                                <span class="text-[9px] uppercase" style="color:var(--text-muted)">
                                    Prev EX: {{ $registration->{"m{$i}_prev_ex"} }}
                                </span>
                                <span class="text-[9px] uppercase" style="color:var(--text-muted)">
                                    Size: {{ $registration->{"m{$i}_tshirt"} }}
                                </span>
                                @if ($registration->{"m{$i}_cf_handle"})
                                    <span class="text-[9px] font-mono" style="color:var(--accent)">
                                        CF/KC: {{ $registration->{"m{$i}_cf_handle"} }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                @endfor
            </div>
        </div>

        {{-- Coach Info (IUPC only) --}}
        @if ($registration->event->slug === 'iupc')
            <div class="coach-block">
                <p class="detail-section-title mb-3">Coach Information</p>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ([
                        ['label' => 'Name',        'value' => $registration->coach_name],
                        ['label' => 'Designation', 'value' => $registration->coach_designation],
                        ['label' => 'Email',        'value' => $registration->coach_email],
                        ['label' => 'T-Shirt',      'value' => $registration->coach_tshirt, 'accent' => true],
                    ] as $field)
                    <div>
                        <p class="detail-label">{{ $field['label'] }}</p>
                        <p class="text-sm {{ isset($field['accent']) ? 'font-bold' : '' }}"
                            style="{{ isset($field['accent']) ? 'color:var(--accent)' : 'color:var(--text-primary)' }}">
                            {{ $field['value'] }}
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Action Section --}}
        <div class="action-block flex flex-col md:flex-row justify-between items-center gap-6">

            @if ($registration->event->slug === 'iupc')
                {{-- IUPC --}}
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 w-full">
                    <div class="text-center md:text-left">
                        <p class="detail-section-title mb-1">IUPC Team Management</p>
                        <p class="text-[11px]" style="color:var(--text-muted)">
                            Updating to 'Selected' will notify the
                            <span class="font-bold" style="color:var(--accent)">Coach</span> via email.
                        </p>
                    </div>
                    <form action="{{ route('admin.registration.updateStatus_pw', $registration->id) }}" method="POST"
                        class="flex items-center gap-2">
                        @csrf @method('PATCH')
                        <select name="status" class="status-select">
                            <option value="pending"  {{ $registration->status == 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="selected" {{ $registration->status == 'selected' ? 'selected' : '' }}>Selected</option>
                            <option value="verified" {{ $registration->status == 'verified' ? 'selected' : '' }}>Verified (Paid)</option>
                            <option value="rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button type="submit" class="update-btn">Update</button>
                    </form>
                </div>

            @elseif ($registration->event->slug === 'ai-hackathon')
                {{-- AI Hackathon --}}
                <div class="space-y-4 w-full">
                    <div class="phase-block">
                        <p class="detail-section-title text-[10px] mb-2">Phase 1: Send Preliminary Link</p>
                        <form action="{{ route('admin.event.sendBulkLink', $registration->event_id) }}" method="POST"
                            class="flex gap-2">
                            @csrf
                            <input type="url" name="contest_link" placeholder="Enter Global Contest Link" required
                                class="link-input">
                            <button type="submit" class="send-btn">Send to All</button>
                        </form>
                    </div>
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="text-center md:text-left">
                            <p class="detail-section-title mb-1">Phase 2: Status Update</p>
                            <p class="text-[11px]" style="color:var(--text-muted)">Updates will notify all team members.</p>
                        </div>
                        <form action="{{ route('admin.registration.updateStatus.ai', $registration->id) }}" method="POST"
                            class="flex items-center gap-2">
                            @csrf @method('PATCH')
                            <select name="status" class="status-select">
                                <option value="pending"  {{ $registration->status == 'pending'  ? 'selected' : '' }}>Pending</option>
                                <option value="selected" {{ $registration->status == 'selected' ? 'selected' : '' }}>Selected</option>
                                <option value="verified" {{ $registration->status == 'verified' ? 'selected' : '' }}>Verified (Paid)</option>
                                <option value="rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                            <button type="submit" class="update-btn">Update</button>
                        </form>
                    </div>
                </div>

            @else
                {{-- All other events --}}
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 w-full">
                    <div class="text-center md:text-left">
                        <p class="detail-section-title mb-1">
                            {{ str_replace('-', ' ', $registration->event->slug) }} Management
                        </p>
                        <p class="text-[11px]" style="color:var(--text-muted)">
                            Update status and trigger automatic email notifications.
                        </p>
                    </div>
                    <form action="{{ route('admin.registration.updateStatus_pw', $registration->id) }}" method="POST"
                        class="flex items-center gap-2">
                        @csrf @method('PATCH')
                        <select name="status" class="status-select">
                            <option value="pending"  {{ $registration->status == 'pending'  ? 'selected' : '' }}>Pending</option>
                            <option value="selected" {{ $registration->status == 'selected' ? 'selected' : '' }}>Selected</option>
                            <option value="verified" {{ $registration->status == 'verified' ? 'selected' : '' }}>Verified (Paid)</option>
                            <option value="rejected" {{ $registration->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <button type="submit" class="update-btn">Update Status</button>
                    </form>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection