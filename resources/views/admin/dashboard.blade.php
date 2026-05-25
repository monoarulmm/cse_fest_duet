@extends('layouts.app')

@section('custom_css')
<style>
    .admin-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-accent);
        color: var(--text-primary);
    }
    .active-tab {
        border-bottom: 2px solid var(--accent);
        color: var(--accent) !important;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: var(--bg-elevated);
        border-radius: 10px;
    }
    .admin-table-head {
        background: var(--bg-elevated);
        color: var(--text-muted);
    }
    .admin-table-row {
        border-bottom: 1px solid var(--border-soft);
        transition: background 0.15s;
    }
    .admin-table-row:hover { background: var(--accent-dim); }
    .admin-input {
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        color: var(--text-primary);
        border-radius: 0.5rem;
        padding: 0.375rem 0.5rem;
        font-size: 0.75rem;
        width: 100%;
        outline: none;
        transition: border-color 0.2s;
    }
    .admin-input:focus { border-color: var(--accent); }
    .admin-file-input {
        background: var(--bg-elevated);
        border: 1px solid var(--border-mid);
        color: var(--text-secondary);
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        font-size: 0.7rem;
        width: 100%;
        display: block;
    }
    .tab-nav-link {
        color: var(--text-muted);
        font-size: 0.625rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid transparent;
        transition: color 0.2s, border-color 0.2s;
        white-space: nowrap;
    }
    .tab-nav-link:hover { color: var(--accent); }
    .page-title { color: var(--text-primary); }
    .page-divider { background: var(--border-accent); height: 1px; }
    .back-btn {
        border: 1px solid var(--border-mid);
        color: var(--text-secondary);
        border-radius: 0.75rem;
        padding: 0.75rem 1.5rem;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        transition: border-color 0.2s;
        background: transparent;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    .back-btn:hover { border-color: var(--accent); }
    .action-link-green {
        background: rgba(34,197,94,0.1);
        color: rgb(74,222,128);
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        transition: background 0.2s;
    }
    .action-link-green:hover { background: rgba(34,197,94,0.2); }
    .action-link-muted {
        color: var(--accent);
        border-radius: 0.75rem;
        padding: 0.5rem 1rem;
        font-size: 0.625rem;
        font-weight: 700;
        text-transform: uppercase;
        transition: background 0.2s;
    }
    .action-link-muted:hover { background: var(--bg-elevated); }
    .badge-verified {
        background: rgba(34,197,94,0.1);
        color: rgb(74,222,128);
        border-radius: 4px;
        padding: 1px 6px;
        font-size: 0.5625rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-pending {
        background: rgba(234,179,8,0.1);
        color: rgb(250,204,21);
        border-radius: 4px;
        padding: 1px 6px;
        font-size: 0.5625rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .coupon-table-wrap { max-height: 16rem; overflow-y: auto; }
    .result-table-wrap { max-height: 16rem; overflow-y: auto; }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-8">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 pb-8" style="border-bottom:1px solid var(--border-accent)">
        <div>
            <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter page-title">
                Admin Dashboard <span style="color:var(--accent)">Details</span>
            </h2>
            <div class="h-1 w-20 mt-2 rounded-full" style="background:var(--accent); box-shadow:0 0 10px rgba(34,211,238,0.5)"></div>
        </div>
        <button onclick="window.history.back()" class="back-btn group">
            <i class="fa-solid fa-chevron-left group-hover:-translate-x-1 transition-transform" style="color:var(--accent)"></i>
            Back to previous
        </button>
    </div>

    {{-- Top Action Bar --}}
    <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
        <h1 class="text-xl font-black uppercase italic" style="color:var(--text-primary)">
            Admin <span style="color:var(--accent)">Panel</span>
            <span class="text-[10px] ml-2" style="color:var(--text-muted)">[{{ $selectedEvent->name }}]</span>
        </h1>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.export.result.template', $selectedEvent->id) }}" class="action-link-muted">
                Result & Seat Plan Template
            </a>
            <a href="{{ route('admin.export.all_event', ['event_id' => $selectedEvent->id]) }}" class="action-link-green">
                Export Registration
            </a>
            <a href="{{ route('admin.iupc.export') }}" class="action-link-muted">
                IUPC Coach Export
            </a>
        </div>
    </div>

    {{-- Upload Tools Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">

        {{-- Result Upload --}}
        <div class="admin-card p-6 rounded-3xl">
            <h3 class="text-xs font-bold uppercase mb-4 flex items-center gap-2" style="color:var(--text-secondary)">
                <i class="fa-solid fa-upload" style="color:var(--accent)"></i> Results Upload
            </h3>
            <form action="{{ route('admin.upload.result') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="excel_file" class="admin-file-input mb-4" required>
                <button type="submit"
                    class="w-full py-2 rounded-xl font-bold text-[10px] uppercase transition text-slate-900"
                    style="background:var(--accent)">
                    Process Results
                </button>
            </form>
        </div>

        @if ($selectedEvent->slug == 'iupc')
            {{-- Slots Upload --}}
            <div class="admin-card p-6 rounded-3xl">
                <h3 class="text-xs font-bold uppercase mb-4" style="color:var(--text-secondary)">University Slots</h3>
                <form action="{{ route('admin.slots.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="file" class="admin-file-input mb-4" required>
                    <button type="submit"
                        class="w-full py-2 rounded-xl text-[10px] font-bold uppercase transition"
                        style="background:var(--bg-elevated); color:var(--text-secondary)">
                        Update Slots
                    </button>
                </form>
            </div>

            {{-- Coach Coupons --}}
            <div class="admin-card p-6 rounded-3xl">
                <h3 class="text-xs font-bold uppercase mb-4" style="color:var(--text-secondary)">Coach Coupons</h3>
                <form action="{{ route('admin.coupons.import', $selectedEvent->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="excel_file" class="admin-file-input mb-4" required>
                    <button type="submit"
                        class="w-full py-2 rounded-xl text-[10px] font-bold uppercase transition text-white mb-3"
                        style="background:rgba(34,197,94,0.6)">
                        Generate & Mail
                    </button>
                </form>
                <a href="{{ route('coupons.export', $selectedEvent->id) }}"
                    class="inline-flex items-center gap-2 text-[10px] font-bold uppercase transition"
                    style="color:var(--accent)">
                    <i class="fa-solid fa-download"></i> Export Coupon Codes (Excel)
                </a>
            </div>

            {{-- Coupon Status Table --}}
            <div class="p-8 admin-card rounded-[2rem] mb-8 lg:col-span-3">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <h2 class="text-xl font-black uppercase flex items-center gap-2" style="color:var(--accent)">
                        <span class="w-2 h-6 rounded-full" style="background:var(--accent)"></span>
                        Coupon Status Tracker
                    </h2>
                    <button type="button" id="delete-selected-btn" disabled
                        class="px-4 py-2 border rounded-xl text-xs font-bold uppercase tracking-wider transition-all opacity-50 cursor-not-allowed flex items-center gap-2"
                        style="background:rgba(239,68,68,0.1); border-color:rgba(239,68,68,0.4); color:rgb(248,113,113)">
                        <i class="fa-solid fa-trash-can"></i> Delete Selected (<span id="selected-count">0</span>)
                    </button>
                </div>

                <form id="bulk-delete-form" action="{{ route('coupons.bulkDelete') }}" method="POST">
                    @csrf
                    <div class="coupon-table-wrap custom-scrollbar">
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="uppercase" style="color:var(--text-muted); border-bottom:1px solid var(--border-soft)">
                                    <th class="py-3 px-2 w-10">
                                        <input type="checkbox" id="select-all-coupons"
                                            class="rounded w-4 h-4 cursor-pointer accent-cyan-500">
                                    </th>
                                    <th class="py-3 px-2">University</th>
                                    <th class="py-3 px-2">Coach</th>
                                    <th class="py-3 px-2">Code</th>
                                    <th class="py-3 px-2">Status</th>
                                </tr>
                            </thead>
                            <tbody style="color:var(--text-secondary)">
                                @forelse ($coupons as $coupon)
                                    <tr class="admin-table-row">
                                        <td class="py-3 px-2">
                                            <input type="checkbox" name="coupon_ids[]" value="{{ $coupon->id }}"
                                                class="coupon-checkbox rounded w-4 h-4 cursor-pointer accent-cyan-500">
                                        </td>
                                        <td class="py-3 px-2">{{ $coupon->university }}</td>
                                        <td class="py-3 px-2">{{ $coupon->coach_name }}</td>
                                        <td class="py-3 px-2 font-mono" style="color:var(--accent)">{{ $coupon->code }}</td>
                                        <td class="py-3 px-2">
                                            @if ($coupon->is_used)
                                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase" style="background:rgba(239,68,68,0.1); color:rgb(248,113,113)">Used</span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[9px] font-bold uppercase" style="background:rgba(34,197,94,0.1); color:rgb(74,222,128)">Active</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-8 italic" style="color:var(--text-muted)">No coupons generated yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        @else
            <div class="lg:col-span-2 admin-card p-6 rounded-3xl flex items-center justify-center italic text-sm"
                style="color:var(--text-muted)">
                Additional tools only available for IUPC event.
            </div>
        @endif
    </div>

    {{-- Tabs --}}
    <div class="flex gap-6 mb-6 overflow-x-auto" style="border-bottom:1px solid var(--border-soft)">
        @foreach ($events as $e)
            <a href="{{ route('admin.dashboard', ['event_id' => $e->id]) }}"
                class="tab-nav-link {{ $selectedEvent->id == $e->id ? 'active-tab' : '' }}">
                {{ $e->name }}
            </a>
        @endforeach
    </div>

    <div class="flex flex-col lg:flex-row gap-8">

        {{-- Stats Sidebar --}}
        <div class="w-full lg:w-1/4 space-y-6">
            <div class="admin-card p-6 rounded-3xl">
                <h4 class="text-[10px] font-black uppercase mb-4 pb-2" style="color:var(--text-secondary); border-bottom:1px solid var(--border-soft)">
                    Uni Stats
                </h4>
                <div class="space-y-3 max-h-60 overflow-y-auto custom-scrollbar">
                    @foreach ($stats as $stat)
                        <div class="flex justify-between items-center text-[10px]">
                            <span class="truncate pr-2" style="color:var(--text-secondary)">{{ $stat->university_name }}</span>
                            <span class="font-bold px-2 py-0.5 rounded" style="color:var(--accent); background:var(--accent-dim)">{{ $stat->total }}</span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Main Content --}}
        <div class="w-full lg:w-3/4 space-y-8">

            {{-- Registrations Table --}}
            <div class="admin-card rounded-3xl overflow-hidden">
                <form id="bulkDeleteForm" action="{{ route('admin.registrations.bulkDelete') }}" method="POST">
                    @csrf @method('DELETE')
                    <div class="p-4 flex justify-between items-center" style="background:var(--bg-elevated)">
                        <span class="text-[10px] font-bold uppercase" style="color:var(--text-secondary)">Registration Records</span>
                        <button type="submit" onclick="return confirm('Delete selected?')"
                            class="hidden bulk-btn px-3 py-1 rounded text-[9px] font-bold uppercase text-white"
                            style="background:rgba(239,68,68,0.7)">
                            Delete Selected
                        </button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="admin-table-head text-[9px] uppercase">
                                <tr>
                                    <th class="p-4"><input type="checkbox" id="selectAll" class="accent-cyan-500"></th>
                                    <th class="p-4">Participant</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody style="color:var(--text-secondary)">
                                @foreach ($teams as $team)
                                    <tr class="admin-table-row">
                                        <td class="p-4">
                                            <input type="checkbox" name="ids[]" value="{{ $team->id }}" class="row-checkbox accent-cyan-500">
                                        </td>
                                        <td class="p-4">
                                            <div class="font-bold" style="color:var(--text-primary)">
                                                {{ $selectedEvent->slug == 'ict-olympiad' ? $team->m1_name : $team->team_name }}
                                            </div>
                                            <div class="text-[10px]" style="color:var(--accent); opacity:0.7">{{ $team->university_name }}</div>
                                        </td>
                                        <td class="p-4">
                                            <span class="{{ $team->status == 'verified' ? 'badge-verified' : 'badge-pending' }}">
                                                {{ $team->status }}
                                            </span>
                                            <span class="{{ $team->status == 'verified' ? 'badge-verified' : 'badge-pending' }} ml-1">
                                                {{ $team->team_id }}
                                            </span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <a href="{{ route('admin.registration.show', $team->id) }}"
                                                style="color:var(--accent)" class="hover:underline">Details →</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="p-4">{{ $teams->links() }}</div>
                </form>
            </div>

            {{-- Results Table --}}
            <div class="admin-card rounded-3xl overflow-hidden">
                <div class="p-4" style="background:var(--accent-dim); border-bottom:1px solid var(--border-soft)">
                    <h3 class="text-[10px] font-black uppercase" style="color:var(--accent)">Live Result Hub</h3>
                </div>
                <div class="result-table-wrap custom-scrollbar">
                    <table class="w-full text-left text-[10px]">
                        <tbody>
                            @forelse($results as $res)
                                <tr class="admin-table-row">
                                    <td class="p-4 font-mono" style="color:var(--text-primary)">{{ $res->participant_id }}</td>
                                    <td class="p-4" style="color:var(--text-muted)">{{ $res->university_name }}</td>
                                    <td class="p-4" style="color:var(--accent)">{{ $res->result_status }}</td>
                                    <td class="p-4 text-right">
                                        <form action="{{ route('admin.result.delete', $res->id) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="opacity-50 hover:opacity-100 transition" style="color:rgb(248,113,113)">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-10 text-center italic" style="color:var(--text-muted)">No results uploaded for this event.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const selectAll = document.getElementById('selectAll');
    const rowCheckboxes = document.querySelectorAll('.row-checkbox');
    const bulkBtn = document.querySelector('.bulk-btn');

    selectAll?.addEventListener('change', function() {
        rowCheckboxes.forEach(cb => cb.checked = this.checked);
        toggleBulkBtn();
    });
    rowCheckboxes.forEach(cb => cb.addEventListener('change', toggleBulkBtn));

    function toggleBulkBtn() {
        const anyChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
        bulkBtn?.classList.toggle('hidden', !anyChecked);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCoupons = document.getElementById('select-all-coupons');
        const couponCheckboxes = document.querySelectorAll('.coupon-checkbox');
        const deleteBtn = document.getElementById('delete-selected-btn');
        const countSpan = document.getElementById('selected-count');
        const deleteForm = document.getElementById('bulk-delete-form');

        function updateButtonState() {
            const n = document.querySelectorAll('.coupon-checkbox:checked').length;
            if (countSpan) countSpan.textContent = n;
            if (!deleteBtn) return;
            if (n > 0) {
                deleteBtn.removeAttribute('disabled');
                deleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                deleteBtn.setAttribute('disabled', 'true');
                deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        selectAllCoupons?.addEventListener('change', function() {
            couponCheckboxes.forEach(cb => cb.checked = selectAllCoupons.checked);
            updateButtonState();
        });
        couponCheckboxes.forEach(cb => cb.addEventListener('change', updateButtonState));

        deleteBtn?.addEventListener('click', function() {
            const n = document.querySelectorAll('.coupon-checkbox:checked').length;
            if (n === 0) return;
            if (confirm(`আপনি কি নিশ্চিত যে এই ${n}টি কুপন মুছে ফেলতে চান?`)) {
                deleteForm.submit();
            }
        });
    });
</script>
@endsection