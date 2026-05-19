@extends('layouts.app')

@section('custom_css')
    <style>
        :root {
            --card-bg: rgba(15, 23, 42, 0.8);
            --border-color: rgba(34, 211, 238, 0.2);
        }

        .admin-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
        }

        .active-tab {
            border-bottom: 2px solid #22d3ee;
            color: #22d3ee !important;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #1e293b;
            border-radius: 10px;
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-slate-800 pb-8 mb-10">

            {{-- ১. বড় প্রফেশনাল টাইটেল --}}
            <div>
                <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter">
                    Admin Dashboard <span class="text-cyan-400">Details</span>
                </h2>
                <div class="h-1 w-20 bg-cyan-500 mt-2 shadow-[0_0_10px_rgba(34,211,238,0.5)]"></div>
            </div>

            {{-- ২. জাভাস্ক্রিপ্ট ব্যাক বাটন (হিস্ট্রি অনুযায়ী কাজ করবে) --}}
            <button onclick="window.history.back()"
                class="flex items-center gap-3 px-6 py-3 bg-slate-900 border border-slate-700 rounded-xl hover:border-cyan-500 group transition-all">
                <i class="fa-solid fa-chevron-left text-cyan-500 group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-xs font-bold text-slate-400 group-hover:text-white uppercase tracking-[0.2em]">
                    Back to previous
                </span>
            </button>

        </div>
        {{-- Top Action Bar --}}
        <div class="flex flex-wrap justify-between items-center mb-8 gap-4">
            <h1 class="text-xl font-black text-white uppercase italic">
                Admin <span class="text-cyan-500">Panel</span> <span
                    class="text-[10px] text-slate-500 ml-2">[{{ $selectedEvent->name }}]</span>
            </h1>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('admin.export.result.template', $selectedEvent->id) }}"
                    class="bg-slate-800 hover:bg-slate-700 text-cyan-400 px-4 py-2 rounded-xl text-[10px] font-bold uppercase transition">
                    Result & Seat Plan Template
                </a>


                <a href="{{ route('admin.export.all_event', ['event_id' => $selectedEvent->id]) }}"
                    class="bg-green-600/20 hover:bg-green-600/30 text-green-500 px-4 py-2 rounded-xl text-[10px] font-bold uppercase transition">
                    Export Registration
                </a>

                <a href="{{ route('admin.iupc.export') }}"
                    class="bg-slate-800 hover:bg-slate-700 text-cyan-400 px-4 py-2 rounded-xl text-[10px] font-bold uppercase transition">
                    iupc coach export
                </a>
            </div>
        </div>

        {{-- Upload Tools Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            {{-- Result Upload --}}
            <div class="admin-card p-6 rounded-3xl">
                <h3 class="text-white text-xs font-bold uppercase mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-upload text-cyan-500"></i> Results Upload
                </h3>
                <form action="{{ route('admin.upload.result') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="excel_file"
                        class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:bg-cyan-500/10 file:text-cyan-400 mb-4"
                        required>
                    <button type="submit"
                        class="w-full bg-cyan-600 hover:bg-cyan-500 text-white py-2 rounded-xl font-bold text-[10px] uppercase transition">Process
                        Results</button>
                </form>
            </div>

            {{-- Event Specific Tools (Slots/Coupons) --}}
            @if ($selectedEvent->slug == 'iupc')
                <div class="admin-card p-6 rounded-3xl">
                    <h3 class="text-white text-xs font-bold uppercase mb-4">University Slots</h3>
                    <form action="{{ route('admin.slots.upload') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" class="block w-full text-[10px] mb-4" required>
                        <button type="submit"
                            class="w-full bg-slate-700 text-white py-2 rounded-xl text-[10px] font-bold uppercase">Update
                            Slots</button>
                    </form>
                </div>
                <div class="admin-card p-6 rounded-3xl">
                    <h3 class="text-white text-xs font-bold uppercase mb-4">Coach Coupons</h3>
                    <form action="{{ route('admin.coupons.import', $selectedEvent->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="excel_file" class="block w-full text-[10px] mb-4" required>
                        <button type="submit"
                            class="w-full bg-green-600 text-white py-2 rounded-xl text-[10px] font-bold uppercase">Generate
                            & Mail</button>
                    </form>


                    <a href="{{ route('coupons.export', $selectedEvent->id) }}" class="btn btn-success">
                        <i class="fa-solid fa-download"></i> Export Coupon Codes (Excel)
                    </a>
                </div>

                {{-- ৩. কুপন স্ট্যাটাস টেবিল (শুধুমাত্র IUPC এর জন্য) --}}
                <div class="p-8 bg-slate-900/50 border border-slate-800 rounded-[2rem] mb-8">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                        <h2 class="text-xl font-black text-cyan-400 uppercase flex items-center gap-2">
                            <span class="w-2 h-6 bg-cyan-500 rounded-full"></span>
                            Coupon Status Tracker
                        </h2>

                        {{-- মাল্টিপল ডিলিট বাটন --}}
                        <button type="button" id="delete-selected-btn" disabled
                            class="px-4 py-2 bg-red-600/20 border border-red-500/40 text-red-400 hover:bg-red-600 hover:text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all duration-300 opacity-50 cursor-not-allowed flex items-center gap-2">
                            <i class="fa-solid fa-trash-can"></i> Delete Selected (<span id="selected-count">0</span>)
                        </button>
                    </div>

                    {{-- বাল্ক ডিলিট ফরম --}}
                    <form id="bulk-delete-form" action="{{ route('coupons.bulkDelete') }}" method="POST">
                        @csrf
                        <div class="max-h-64 overflow-y-auto custom-scrollbar">
                            <table class="w-full text-left border-collapse text-xs">
                                <thead>
                                    <tr class="text-slate-500 border-b border-slate-800 uppercase">
                                        <th class="py-3 px-2 w-10">
                                            <input type="checkbox" id="select-all-coupons"
                                                class="rounded border-slate-700 bg-slate-800 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-slate-900 w-4 h-4 cursor-pointer">
                                        </th>
                                        <th class="py-3 px-2">University</th>
                                        <th class="py-3 px-2">Coach</th>
                                        <th class="py-3 px-2">Code</th>
                                        <th class="py-3 px-2">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="text-slate-400">
                                    @forelse ($coupons as $coupon)
                                        <tr class="border-b border-slate-800/50 hover:bg-white/5 transition-colors">
                                            <td class="py-3 px-2">
                                                <input type="checkbox" name="coupon_ids[]" value="{{ $coupon->id }}"
                                                    class="coupon-checkbox rounded border-slate-700 bg-slate-800 text-cyan-500 focus:ring-cyan-500 focus:ring-offset-slate-900 w-4 h-4 cursor-pointer">
                                            </td>
                                            <td class="py-3 px-2">{{ $coupon->university }}</td>
                                            <td class="py-3 px-2">{{ $coupon->coach_name }}</td>
                                            <td class="py-3 px-2 font-mono text-cyan-400">{{ $coupon->code }}</td>
                                            <td class="py-3 px-2">
                                                @if ($coupon->is_used)
                                                    <span
                                                        class="text-red-500 bg-red-500/10 px-2 py-0.5 rounded font-medium">Used</span>
                                                @else
                                                    <span
                                                        class="text-green-500 bg-green-500/10 px-2 py-0.5 rounded font-medium">Active</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-8 text-slate-600 italic">
                                                No coupons generated yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            @else
                <div
                    class="lg:col-span-2 admin-card p-6 rounded-3xl flex items-center justify-center text-slate-500 italic text-sm">
                    Additional tools only available for IUPC event.
                </div>
            @endif
        </div>

        {{-- Tabs --}}
        <div class="flex gap-6 mb-6 border-b border-white/5 overflow-x-auto">
            @foreach ($events as $e)
                <a href="{{ route('admin.dashboard', ['event_id' => $e->id]) }}"
                    class="pb-3 text-[10px] font-black uppercase tracking-widest transition-all {{ $selectedEvent->id == $e->id ? 'active-tab' : 'text-slate-500 hover:text-cyan-400' }}">
                    {{ $e->name }}
                </a>
            @endforeach
        </div>

        <div class="flex flex-col lg:flex-row gap-8">
            {{-- Stats Sidebar --}}
            <div class="w-full lg:w-1/4 space-y-6">
                <div class="admin-card p-6 rounded-3xl">
                    <h4 class="text-white text-[10px] font-black uppercase mb-4 border-b border-white/5 pb-2">Uni Stats
                    </h4>
                    <div class="space-y-3 max-h-60 overflow-y-auto custom-scrollbar">
                        @foreach ($stats as $stat)
                            <div class="flex justify-between items-center text-[10px]">
                                <span class="text-slate-400 truncate pr-2">{{ $stat->university_name }}</span>
                                <span
                                    class="text-cyan-500 font-bold bg-cyan-500/10 px-2 py-0.5 rounded">{{ $stat->total }}</span>
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
                        <div class="p-4 bg-white/5 flex justify-between items-center">
                            <span class="text-white text-[10px] font-bold uppercase">Registration Records</span>
                            <button type="submit" onclick="return confirm('Delete selected?')"
                                class="hidden bulk-btn bg-red-600 text-white px-3 py-1 rounded text-[9px] font-bold uppercase">Delete
                                Selected</button>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-xs">
                                <thead class="bg-slate-900 text-slate-500 uppercase text-[9px]">
                                    <tr>
                                        <th class="p-4"><input type="checkbox" id="selectAll"></th>
                                        <th class="p-4">Participant</th>
                                        <th class="p-4">Status</th>
                                        <th class="p-4 text-right">Action</th>
                                    </tr>
                                </thead>
                                <tbody class="text-slate-400 divide-y divide-white/5">
                                    @foreach ($teams as $team)
                                        <tr class="hover:bg-white/5 transition">
                                            <td class="p-4"><input type="checkbox" name="ids[]"
                                                    value="{{ $team->id }}" class="row-checkbox"></td>
                                            <td class="p-4">
                                                <div class="text-white font-bold">
                                                    {{ $selectedEvent->slug == 'ict-olympiad' ? $team->m1_name : $team->team_name }}
                                                </div>
                                                <div class="text-[10px] text-cyan-600">{{ $team->university_name }}</div>
                                            </td>
                                            <td class="p-4">
                                                <span
                                                    class="px-2 py-0.5 rounded-[4px] text-[9px] uppercase font-bold {{ $team->status == 'verified' ? 'bg-green-500/10 text-green-500' : 'bg-yellow-500/10 text-yellow-500' }}">
                                                    {{ $team->status }}
                                                </span>
                                                <span
                                                    class="px-2 py-0.5 rounded-[4px] text-[9px] uppercase font-bold {{ $team->status == 'verified' ? 'bg-green-500/10 text-green-500' : 'bg-yellow-500/10 text-yellow-500' }}">
                                                    {{ $team->team_id }}
                                                </span>
                                            </td>
                                            <td class="p-4 text-right">
                                                <a href="{{ route('admin.registration.show', $team->id) }}"
                                                    class="text-cyan-500 hover:underline">Details →</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="p-4">{{ $teams->links() }}</div>
                    </form>
                </div>

                {{-- Imported Results View --}}
                <div class="admin-card rounded-3xl overflow-hidden">
                    <div class="p-4 bg-cyan-500/5 border-b border-white/5">
                        <h3 class="text-cyan-400 text-[10px] font-black uppercase">Live Result Hub</h3>
                    </div>
                    <div class="max-h-64 overflow-y-auto custom-scrollbar">
                        <table class="w-full text-left text-[10px]">
                            <tbody class="divide-y divide-white/5">
                                @forelse($results as $res)
                                    <tr class="hover:bg-white/5">
                                        <td class="p-4 text-white font-mono">{{ $res->participant_id }}</td>
                                        <td class="p-4 text-slate-500">{{ $res->university_name }}</td>
                                        <td class="p-4"><span class="text-cyan-500">{{ $res->result_status }}</span>
                                        </td>
                                        <td class="p-4 text-right">
                                            <form action="{{ route('admin.result.delete', $res->id) }}" method="POST">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-500 opacity-50 hover:opacity-100"><i
                                                        class="fa-solid fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="p-10 text-center text-slate-600 italic">No results
                                            uploaded for this event.</td>
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
        // Bulk Select Logic
        const selectAll = document.getElementById('selectAll');
        const rowCheckboxes = document.querySelectorAll('.row-checkbox');
        const bulkBtn = document.querySelector('.bulk-btn');

        selectAll.addEventListener('change', function() {
            rowCheckboxes.forEach(cb => cb.checked = this.checked);
            toggleBulkBtn();
        });

        rowCheckboxes.forEach(cb => cb.addEventListener('change', toggleBulkBtn));

        function toggleBulkBtn() {
            const anyChecked = Array.from(rowCheckboxes).some(cb => cb.checked);
            bulkBtn.classList.toggle('hidden', !anyChecked);
        }
    </script>

    {{-- ফ্রন্টএন্ড চেকবক্স এবং বাটন কন্ট্রোল স্ক্রিপ্ট --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all-coupons');
            const checkboxes = document.querySelectorAll('.coupon-checkbox');
            const deleteBtn = document.getElementById('delete-selected-btn');
            const countSpan = document.getElementById('selected-count');
            const deleteForm = document.getElementById('bulk-delete-form');

            // বাটনের স্ট্যাটাস ও সংখ্যা আপডেট করার ফাংশন
            function updateButtonState() {
                const checkedCount = document.querySelectorAll('.coupon-checkbox:checked').length;
                countSpan.textContent = checkedCount;

                if (checkedCount > 0) {
                    deleteBtn.removeAttribute('disabled');
                    deleteBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    deleteBtn.classList.add('hover:scale-105');
                } else {
                    deleteBtn.setAttribute('disabled', 'true');
                    deleteBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    deleteBtn.classList.remove('hover:scale-105');
                }
            }

            // 'Select All' চেকবক্স টগল লজিক
            if (selectAll) {
                selectAll.addEventListener('change', function() {
                    checkboxes.forEach(cb => cb.checked = selectAll.checked);
                    updateButtonState();
                });
            }

            // সিঙ্গেল চেকবক্স ক্লিক লজিক
            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    const totalChecked = document.querySelectorAll('.coupon-checkbox:checked')
                        .length;
                    if (selectAll) {
                        selectAll.checked = (totalChecked === checkboxes.length);
                    }
                    updateButtonState();
                });
            });

            // ডিলিট সাবমিট করার আগে কনফার্মেশন প্রম্পট
            deleteBtn.addEventListener('click', function() {
                const finalCount = document.querySelectorAll('.coupon-checkbox:checked').length;
                if (finalCount === 0) return;

                if (confirm(`আপনি কি নিশ্চিত যে এই ${finalCount}টি কুপন মুছে ফেলতে চান?`)) {
                    deleteForm.submit();
                }
            });
        });
    </script>
@endsection
