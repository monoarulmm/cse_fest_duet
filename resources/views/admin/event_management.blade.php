@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">

        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-slate-800 pb-8 mb-10">

            {{-- ১. বড় প্রফেশনাল টাইটেল --}}
            <div>
                <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter">
                    Event Management <span class="text-cyan-400">Details</span>
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

        {{-- Create New Event Section --}}
        <div class="admin-card p-8 rounded-3xl border border-cyan-500/20 bg-slate-900/50 mb-10 shadow-2xl">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-8 w-1 bg-cyan-500 shadow-[0_0_15px_rgba(34,211,238,0.8)]"></div>
                <h2 class="heading-font text-xl text-white uppercase font-black tracking-tighter">Create New <span
                        class="text-cyan-400">Event Segment</span></h2>
            </div>

            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Event
                            Name</label>
                        <input type="text" name="name" required
                            class="w-full bg-[#1e2636] border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition shadow-inner">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Slug</label>
                        <input type="text" name="slug" required
                            class="w-full bg-[#1e2636] border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition shadow-inner">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Reg Fee</label>
                        <input type="number" name="reg_fee" required
                            class="w-full bg-[#1e2636] border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition shadow-inner">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Deadline</label>
                        <input type="datetime-local" name="end_date" required
                            class="w-full bg-[#1e2636] border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition shadow-inner invert-[0.1]">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Min
                            Members</label>
                        <input type="number" name="min_members" value="1"
                            class="w-full bg-[#1e2636] border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition shadow-inner">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Max
                            Members</label>
                        <input type="number" name="max_members" value="3"
                            class="w-full bg-[#1e2636] border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition shadow-inner">
                    </div>

                    {{-- CKEditor for Description Only --}}
                    <div class="md:col-span-2 space-y-1">
                        <label
                            class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Description</label>
                        <div class="custom-editor-container">
                            <textarea name="description" id="editor_description"></textarea>
                        </div>
                    </div>

                    {{-- Regular Textarea for Rules (Based on image_d3cd6d.png style) --}}
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Rules &
                            Regulations</label>
                        <input type="text" name="rules"
                            class="w-full bg-[#1e2636] border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[10px] text-cyan-400 uppercase font-bold tracking-widest ml-1">Event Images
                            (Multiple)</label>
                        <div class="relative group">
                            <input type="file" name="images[]" multiple
                                class="w-full bg-[#1e2636] border border-cyan-500/10 rounded-xl px-4 py-2.5 text-xs text-slate-400 file:bg-cyan-500 file:text-slate-900 file:border-0 file:px-4 file:py-1.5 file:rounded-lg file:mr-4 file:font-black file:uppercase file:cursor-pointer">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Result
                            URL</label>
                        <input type="text" name="result"
                            class="w-full bg-[#1e2636] border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Seat Plan
                            URL</label>
                        <input type="text" name="seatplan"
                            class="w-full bg-[#1e2636] border border-white/5 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="flex items-center space-x-3 lg:col-span-3">
                        <input type="checkbox" name="needs_coach" id="needs_coach" value="1"
                            class="w-5 h-5 accent-cyan-500 bg-[#1e2636] border-white/10 rounded">
                        <label for="needs_coach"
                            class="text-[10px] text-white uppercase font-black cursor-pointer tracking-wider">Requires
                            Coach?</label>
                    </div>

                    <div class="lg:col-span-1">
                        <button type="submit"
                            class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-900 py-3 rounded-xl font-black uppercase text-[11px] transition transform hover:scale-[1.02] shadow-[0_4px_20px_rgba(34,211,238,0.4)]">
                            + Add Segment
                        </button>
                    </div>
                </div>
            </form>
        </div>

        {{-- Table Section (Simplified for management) --}}
        {{-- Existing Segments Table --}}
        <div class="admin-card p-8 rounded-3xl border border-cyan-500/20 bg-slate-900/50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="heading-font text-xl text-cyan-400 uppercase font-black">Existing <span
                        class="text-white">Segments</span></h2>
                <span class="text-[10px] text-slate-500 uppercase tracking-widest">Total: {{ count($events) }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-separate border-spacing-y-2">
                    <thead class="text-[10px] text-slate-400 uppercase">
                        <tr>
                            <th class="px-4 pb-2">Name & Slug</th>
                            <th class="px-4 pb-2">Fee (BDT)</th>
                            <th class="px-4 pb-2">Deadline</th>
                            <th class="px-4 pb-2">Members (Min-Max)</th>
                            <th class="px-4 pb-2 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-white text-sm">
                        @foreach ($events as $event)
                            <tr class="bg-white/5 hover:bg-white/10 transition group">

                                <td class="p-4 rounded-l-xl">
                                    <input type="text" name="" value="{{ $event->name }} " readonly
                                        class="bg-transparent border-b border-white/10 focus:border-cyan-500 outline-none w-full font-bold text-white mb-1">
                                    <input type="text" name="" value="{{ $event->slug }}" readonly
                                        class="bg-transparent border-b border-white/10 focus:border-cyan-500 outline-none w-full text-[10px] text-cyan-500 uppercase tracking-widest">
                                </td>
                                <td class="p-4">
                                    <input type="number" name="reg_fee" value="{{ $event->reg_fee }}" readonly
                                        class="bg-slate-800 border border-white/10 rounded px-2 py-1.5 w-20 text-cyan-400 focus:border-cyan-500 outline-none">
                                </td>
                                <td class="p-4">
                                    <input type="datetime-local" name="end_date"
                                        value="{{ $event->end_date->format('Y-m-d\TH:i') }}" readonly
                                        class="bg-slate-800 border border-white/10 rounded px-2 py-1.5 text-[10px] focus:border-cyan-500 outline-none">
                                </td>
                                <td class="p-4">
                                    <div class="flex items-center gap-2">
                                        <input type="number" name="min_members" value="{{ $event->min_members }}"
                                            readonly
                                            class="bg-slate-800 border border-white/10 rounded px-1 py-1 w-10 text-center text-xs">
                                        <span class="text-slate-600">-</span>
                                        <input type="number" name="max_members" value="{{ $event->max_members }}"
                                            readonly
                                            class="bg-slate-800 border border-white/10 rounded px-1 py-1 w-10 text-center text-xs">
                                    </div>
                                </td>
                                <td>
                                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this event?');"
                                        class="inline-block">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                            class="bg-red-500/10 hover:bg-red-500 border border-red-500/50 text-red-500 hover:text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>

                                </td>
                                <td>
                                    <a href="{{ route('events.edit', $event->id) }}"
                                        class="text-cyan-400 hover:text-cyan-300">
                                        Full Edit
                                    </a>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- CKEditor Custom Styling for Dark & Day Mode Compatibility --}}
    <style>
        /* এডিটরের কন্টেইনার স্টাইল */
        .ck-editor__editable_inline {
            min-height: 150px;
            background-color: #1e2636 !important;
            /* image_d3cd6d.png এর ইনপুট কালার */
            color: #ffffff !important;
            border-bottom-left-radius: 12px !important;
            border-bottom-right-radius: 12px !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
        }

        /* টুলবার স্টাইল */
        .ck-toolbar {
            background-color: #2d3748 !important;
            border-top-left-radius: 12px !important;
            border-top-right-radius: 12px !important;
            border: none !important;
        }

        /* ডে মোডে টেক্সট সিলেকশন ড্রপডাউনের কালার ঠিক করা */
        .ck.ck-list__item,
        .ck.ck-button {
            color: #1a202c !important;
            /* ড্রপডাউন লিস্ট আইটেমগুলো ডার্ক থাকবে যেন সাদা ব্যাকগ্রাউন্ডে পড়া যায় */
        }

        .ck.ck-toolbar .ck-button {
            color: #ffffff !important;
            /* টুলবার বাটনগুলো সাদা থাকবে */
        }

        .ck.ck-editor__main>.ck-editor__editable:focus {
            border-color: #06b6d4 !important;
            /* সায়ান ফোকাস বর্ডার */
            box-shadow: none !important;
        }
    </style>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            ClassicEditor
                .create(document.querySelector('#editor_description'), {
                    toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList',
                        'blockQuote', 'undo', 'redo'
                    ]
                })
                .catch(error => {
                    console.error(error);
                });
        });
    </script>
@endsection
