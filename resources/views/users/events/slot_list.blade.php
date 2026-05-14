@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#F8FAFC] dark:bg-slate-900 py-8 px-4 md:px-8 transition-colors duration-300">
        <div class="max-w-7xl mx-auto">

            {{-- ১. হেডার ও ফিল্টার সেকশন --}}
            <div
                class="bg-white dark:bg-slate-800 p-6 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 mb-8 backdrop-blur-sm bg-opacity-80">
                <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-indigo-600 dark:text-indigo-400 uppercase tracking-tight">
                            <i class="fas fa-university mr-2"></i> Institution Quotas
                        </h2>
                        <p class="text-slate-400 text-[10px] uppercase font-mono mt-1 tracking-widest">Database Protocol:
                            Active</p>
                    </div>
                    <div class="flex gap-2">
                        <div
                            class="bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-bold text-xs px-5 py-2.5 rounded-full border border-indigo-200 dark:border-indigo-800 transition-all">
                            Total Institutions: <span id="totalCount">{{ $universitySlots->flatten()->count() }}</span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-5 items-end">
                    {{-- Filter by Category --}}
                    <div>
                        <label
                            class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-wider">
                            <i class="fas fa-filter mr-1 text-indigo-500"></i> Category
                        </label>
                        <select id="categoryFilter"
                            class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none dark:text-slate-200 cursor-pointer">
                            <option value="all">All Categories</option>
                            @foreach ($universitySlots->keys() as $category)
                                <option value="{{ Str::slug($category) }}">{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sort By --}}
                    <div>
                        <label
                            class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-wider">
                            <i class="fas fa-sort mr-1 text-indigo-500"></i> Sort By
                        </label>
                        <select id="sortOrder"
                            class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-3 text-sm focus:ring-2 focus:ring-indigo-500 outline-none dark:text-slate-200 cursor-pointer">
                            <option value="asc">Name (A-Z)</option>
                            <option value="desc">Name (Z-A)</option>
                            <option value="quota-high">Highest Quota</option>
                        </select>
                    </div>

                    {{-- Quick Search --}}
                    <div class="md:col-span-1">
                        <label
                            class="block text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase mb-2 tracking-wider">
                            <i class="fas fa-search mr-1 text-indigo-500"></i> Search Institution
                        </label>
                        <div class="relative">
                            <input type="text" id="searchInput" placeholder="Enter name..."
                                class="w-full bg-slate-50 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl p-3 pl-10 text-sm focus:ring-2 focus:ring-indigo-500 outline-none dark:text-slate-200">
                            <i class="fas fa-search absolute left-3 top-3.5 text-slate-400 text-xs"></i>
                        </div>
                    </div>

                    {{-- Reset Button --}}
                    <div>
                        <button onclick="window.location.reload()"
                            class="w-full bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-200 font-bold text-xs py-3.5 rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-indigo-600 hover:text-white transition-all uppercase shadow-sm">
                            <i class="fas fa-sync-alt mr-2"></i> Reset
                        </button>
                    </div>
                </div>
            </div>

            {{-- ২. টেবিল হেডার --}}
            <div
                class="flex items-center bg-indigo-600 dark:bg-indigo-700 rounded-t-2xl overflow-hidden shadow-lg border-b border-indigo-500">
                <div class="w-3/4 p-5 text-white font-black text-xs md:text-sm uppercase tracking-[0.2em]">
                    Institution Name & Segment
                </div>
                <div
                    class="w-1/4 p-5 text-white font-black text-xs md:text-sm uppercase tracking-[0.2em] text-center border-l border-indigo-500/50">
                    Max Slots
                </div>
            </div>

            {{-- ৩. ডাটা লিস্ট --}}
            <div id="slotsContainer"
                class="bg-white dark:bg-slate-800 shadow-2xl rounded-b-2xl overflow-hidden border-x border-b border-slate-200 dark:border-slate-700">
                @foreach ($universitySlots as $category => $slots)
                    <div class="category-group" data-category="{{ Str::slug($category) }}">
                        {{-- ক্যাটাগরি লেবেল সর্ট হলেও যেন থাকে তার জন্য এটি জরুরি --}}
                        <div
                            class="category-header bg-slate-50 dark:bg-slate-900/50 px-6 py-2 border-b border-slate-100 dark:border-slate-700 text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                            Category: {{ $category }}
                        </div>

                        @foreach ($slots as $slot)
                            <div class="slot-item flex items-center border-b border-slate-100 dark:border-slate-700 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-all duration-200"
                                data-name="{{ strtolower($slot->university_name) }}" data-quota="{{ $slot->max_slots }}"
                                data-cat="{{ Str::slug($category) }}">

                                <div class="w-3/4 p-6">
                                    <h5
                                        class="text-slate-800 dark:text-slate-100 font-extrabold text-sm md:text-lg tracking-tight mb-1">
                                        {{ $slot->university_name }}
                                    </h5>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-[9px] font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-widest bg-indigo-50 dark:bg-indigo-900/40 px-3 py-1 rounded-full border border-indigo-100 dark:border-indigo-800">
                                            {{ $category }}
                                        </span>
                                    </div>
                                </div>

                                <div
                                    class="w-1/4 p-6 flex justify-center border-l border-slate-50 dark:border-slate-700/50">
                                    <div
                                        class="group bg-white dark:bg-slate-900 px-6 py-3 rounded-2xl flex items-center gap-3 border border-slate-200 dark:border-slate-700 transition-all hover:border-indigo-400 shadow-sm">
                                        <div
                                            class="hidden md:flex h-8 w-8 items-center justify-center rounded-full bg-indigo-100 dark:bg-indigo-900/50">
                                            <i class="fas fa-users text-indigo-600 dark:text-indigo-400 text-xs"></i>
                                        </div>
                                        <span
                                            class="text-indigo-700 dark:text-indigo-300 font-black text-xl md:text-2xl">{{ $slot->max_slots }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>

            {{-- No Result Found --}}
            <div id="noResult"
                class="hidden text-center py-32 bg-white dark:bg-slate-800 rounded-b-2xl border border-slate-200 dark:border-slate-700 shadow-xl">
                <div class="mb-4">
                    <i
                        class="fas fa-search bg-slate-100 dark:bg-slate-700 p-6 rounded-full text-slate-300 dark:text-slate-500 text-3xl"></i>
                </div>
                <p class="text-slate-500 dark:text-slate-400 font-bold uppercase tracking-[0.3em] text-xs italic">
                    No matching institution found in this category
                </p>
            </div>
        </div>
    </div>

    <style>
        .slot-item {
            animation: fadeIn 0.4s ease forwards;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* কাস্টম স্ক্রলবার */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: #6366f1;
            border-radius: 10px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const categoryFilter = document.getElementById('categoryFilter');
            const sortOrder = document.getElementById('sortOrder');
            const slotsContainer = document.getElementById('slotsContainer');
            const noResult = document.getElementById('noResult');
            const totalCountSpan = document.getElementById('totalCount');

            function updateUI() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedCat = categoryFilter.value;
                const items = Array.from(document.querySelectorAll('.slot-item'));
                let visibleCount = 0;

                items.forEach(item => {
                    const name = item.dataset.name;
                    const itemCat = item.dataset.cat;

                    const matchesSearch = name.includes(searchTerm);
                    const matchesCat = (selectedCat === 'all' || selectedCat === itemCat);

                    if (matchesSearch && matchesCat) {
                        item.style.display = 'flex';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // ক্যাটাগরি হেডার ম্যানেজমেন্ট (খালি ক্যাটাগরি হাইড করা)
                document.querySelectorAll('.category-group').forEach(group => {
                    const hasVisible = Array.from(group.querySelectorAll('.slot-item'))
                        .some(i => i.style.display !== 'none');
                    group.style.display = hasVisible ? 'block' : 'none';
                });

                // টোটাল কাউন্ট আপডেট
                totalCountSpan.innerText = visibleCount;

                // নো রেজাল্ট মেসেজ
                if (visibleCount === 0) {
                    noResult.classList.remove('hidden');
                    slotsContainer.classList.add('hidden');
                } else {
                    noResult.classList.add('hidden');
                    slotsContainer.classList.remove('hidden');
                }
            }

            function sortItems() {
                const items = Array.from(document.querySelectorAll('.slot-item'));
                const order = sortOrder.value;

                items.sort((a, b) => {
                    if (order === 'asc') return a.dataset.name.localeCompare(b.dataset.name);
                    if (order === 'desc') return b.dataset.name.localeCompare(a.dataset.name);
                    if (order === 'quota-high') return parseInt(b.dataset.quota) - parseInt(a.dataset
                    .quota);
                });

                // সর্টিং এর সময় গ্রুপিং ভেঙে আইটেমগুলো নতুন করে সাজানো
                // তবে ক্যাটাগরি হেডারগুলো রিমুভ করে দেওয়া ভালো যদি গ্লোবাল সর্ট হয়
                const headers = document.querySelectorAll('.category-header');

                if (order === 'quota-high') {
                    headers.forEach(h => h.style.display = 'none');
                } else {
                    headers.forEach(h => h.style.display = 'block');
                }

                items.forEach(item => slotsContainer.appendChild(item));
                updateUI(); // সর্ট করার পর ফিল্টার বজায় রাখা
            }

            searchInput.addEventListener('keyup', updateUI);
            categoryFilter.addEventListener('change', updateUI);
            sortOrder.addEventListener('change', sortItems);
        });
    </script>
@endsection
