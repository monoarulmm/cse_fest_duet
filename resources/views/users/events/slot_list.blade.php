@extends('layouts.app')

@section('content')
    {{-- Main Background wrapper --}}
    <div class="min-h-screen py-12 px-4 md:px-8 font-sans selection:bg-indigo-500/30 selection:text-white">
        <div class="max-w-7xl mx-auto space-y-10">

            {{-- 1. Control Center (Cyber-UI Header) --}}
            <div
                class="relative overflow-hidden bg-slate-900/40 backdrop-blur-3xl border border-slate-800/60 rounded-[2.5rem] p-8 md:p-10 shadow-2xl">
                <div class="absolute -top-24 -right-24 w-96 h-96 bg-indigo-600/10 rounded-full blur-[120px]"></div>

                <div class="relative flex flex-col md:flex-row justify-between items-start md:items-center mb-12 gap-8">
                    <div class="space-y-2">
                        <div class="flex items-center gap-5">
                            <div
                                class="h-14 w-14 rounded-2xl bg-indigo-600/20 flex items-center justify-center border border-indigo-500/30 shadow-lg shadow-indigo-500/5">
                                <i class="fas fa-university text-indigo-400 text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-3xl md:text-4xl font-black text-white uppercase tracking-tighter italic">
                                    Institution <span class="text-indigo-500">Quotas</span>
                                </h2>
                                <p
                                    class="text-emerald-500 text-[10px] uppercase font-mono tracking-[0.4em] flex items-center gap-2 mt-1">
                                    <span
                                        class="h-1.5 w-1.5 bg-emerald-500 rounded-full animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                    Status: Live_Data_Synchronized
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-5">
                        <div class="px-7 py-3 bg-slate-950/60 border border-slate-800 rounded-2xl shadow-inner">
                            <p class="text-slate-500 text-[9px] font-black uppercase tracking-widest mb-1">Total Capacity
                            </p>
                            <p class="text-white font-mono text-2xl leading-none font-bold">
                                <span id="totalCount"
                                    class="text-indigo-400">{{ $universitySlots->flatten()->count() }}</span>
                                <span class="text-[10px] text-slate-600 uppercase ml-1">Units</span>
                            </p>
                        </div>

                        <button onclick="window.history.back()"
                            class="h-14 px-8 bg-slate-950/80 border border-slate-800 rounded-2xl hover:border-indigo-500/50 transition-all group flex items-center gap-4">
                            <i
                                class="fa-solid fa-arrow-left text-indigo-500 group-hover:-translate-x-1 transition-transform text-sm"></i>
                            <span
                                class="text-[10px] font-black text-slate-400 group-hover:text-white uppercase tracking-widest">Return</span>
                        </button>
                    </div>
                </div>

                {{-- Filter Hub (Grid Adjusted to 3 Columns) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 relative z-10">
                    <div class="space-y-2.5">
                        <label
                            class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Order/Priority</label>
                        <select id="sortOrder"
                            class="w-full bg-slate-950/80 border border-slate-800 rounded-xl p-4 text-sm text-slate-300 focus:border-indigo-500/50 outline-none transition-all cursor-pointer appearance-none shadow-lg">
                            <option value="asc">Alphabetical (A-Z)</option>
                            <option value="desc">Alphabetical (Z-A)</option>
                            <option value="quota-high">Capacity Priority</option>
                        </select>
                    </div>

                    <div class="space-y-2.5">
                        <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Global
                            Query</label>
                        <div class="relative group">
                            <input type="text" id="searchInput" placeholder="Search ID or Name..."
                                class="w-full bg-slate-950/80 border border-slate-800 rounded-xl p-4 pl-12 text-sm text-slate-200 focus:border-indigo-500/50 outline-none placeholder:text-slate-700 transition-all shadow-lg">
                            <i
                                class="fas fa-search absolute left-5 top-1/2 -translate-y-1/2 text-slate-600 group-focus-within:text-indigo-500 transition-colors"></i>
                        </div>
                    </div>

                    <div class="flex items-end">
                        <button onclick="window.location.reload()"
                            class="w-full h-[54px] bg-slate-800/80 hover:bg-indigo-600 border border-slate-700/50 hover:border-indigo-400 text-white font-black text-[10px] rounded-xl transition-all uppercase tracking-[0.2em] flex items-center justify-center gap-3 shadow-xl">
                            <i class="fas fa-sync-alt animate-spin-slow"></i> Refresh System
                        </button>
                    </div>
                </div>
            </div>

            {{-- 2. Data Registry Table --}}
            <div
                class="rounded-[2.5rem] overflow-hidden border border-slate-800/60 shadow-2xl bg-slate-900/20 backdrop-blur-sm">
                {{-- Table Header --}}
                <div class="flex items-center bg-indigo-600 px-10 py-6">
                    <div class="flex-1 text-white font-black text-[11px] uppercase tracking-[0.4em] italic">
                        Registry_Identification</div>
                    <div
                        class="w-40 text-white font-black text-[11px] uppercase tracking-[0.4em] text-center border-l border-white/20 italic">
                        Allocation</div>
                </div>

                {{-- Single List Container --}}
                {{-- Single List Container --}}
                <div id="slotsContainer">
                    @foreach ($universitySlots as $slot)
                        <div class="slot-item group flex items-center border-b border-slate-800/40 hover:bg-indigo-500/[0.03] transition-all duration-500"
                            data-name="{{ strtolower($slot->university_name) }}" data-quota="{{ $slot->max_slots }}">

                            <div class="flex-1 px-10 py-8">
                                <h5
                                    class="text-slate-100 group-hover:text-indigo-400 font-bold text-xl tracking-tight transition-colors duration-300">
                                    {{ $slot->university_name }}
                                </h5>
                            </div>

                            <div class="w-40 p-8 flex justify-center border-l border-slate-800/30">
                                <div
                                    class="h-16 w-20 bg-slate-950/60 rounded-2xl flex items-center justify-center border border-slate-800 group-hover:border-indigo-500/40 group-hover:shadow-[0_0_15px_rgba(99,102,241,0.1)] transition-all">
                                    <span
                                        class="text-indigo-400 group-hover:text-indigo-300 font-mono font-black text-2xl tracking-tighter">
                                        {{ sprintf('%02d', $slot->max_slots) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- 3. Empty State --}}
            <div id="noResult"
                class="hidden text-center py-40 bg-slate-900/20 rounded-[3rem] border border-slate-800 border-dashed">
                <i class="fas fa-database text-slate-800 text-6xl mb-6"></i>
                <h3 class="text-slate-400 font-black uppercase tracking-[0.6em] text-sm mb-2">Zero Matches Found_</h3>
                <p class="text-slate-600 text-xs font-mono">System query returned null response.</p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const sortOrder = document.getElementById('sortOrder');
            const slotsContainer = document.getElementById('slotsContainer');
            const noResult = document.getElementById('noResult');
            const totalCount = document.getElementById('totalCount');

            function filterAndSort() {
                const searchValue = searchInput.value.toLowerCase();
                const sortValue = sortOrder.value;

                let items = Array.from(document.querySelectorAll('.slot-item'));
                let visibleCount = 0;

                // Filtering (Category logic removed)
                items.forEach(item => {
                    const name = item.getAttribute('data-name');
                    const matchesSearch = name.includes(searchValue);

                    if (matchesSearch) {
                        item.style.display = 'flex';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Sorting
                const visibleItems = items.filter(item => item.style.display !== 'none');
                visibleItems.sort((a, b) => {
                    const nameA = a.getAttribute('data-name');
                    const nameB = b.getAttribute('data-name');
                    const quotaA = parseInt(a.getAttribute('data-quota'));
                    const quotaB = parseInt(b.getAttribute('data-quota'));

                    if (sortValue === 'asc') return nameA.localeCompare(nameB);
                    if (sortValue === 'desc') return nameB.localeCompare(nameA);
                    if (sortValue === 'quota-high') return quotaB - quotaA;
                    return 0;
                });

                // Re-append sorted items
                visibleItems.forEach(item => slotsContainer.appendChild(item));

                // UI updates
                totalCount.textContent = visibleCount;
                noResult.classList.toggle('hidden', visibleCount > 0);
            }

            searchInput.addEventListener('input', filterAndSort);
            sortOrder.addEventListener('change', filterAndSort);
        });
    </script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@700&display=swap');

        .font-mono {
            font-family: 'JetBrains Mono', monospace;
        }

        .slot-item {
            animation: cyberReveal 0.6s cubic-bezier(0.23, 1, 0.32, 1) both;
        }

        @keyframes cyberReveal {
            from {
                opacity: 0;
                transform: translateY(15px);
                filter: blur(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
                filter: blur(0);
            }
        }

        .animate-spin-slow {
            animation: spin 4s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }

            to {
                transform: rotate(360deg);
            }
        }
    </style>
@endsection
