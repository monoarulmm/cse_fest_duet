@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-slate-800 pb-8 mb-10">

            {{-- ১. বড় প্রফেশনাল টাইটেল --}}
            <div>
                <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter ">
                    Event Update <span class="text-cyan-400">Details</span>
                </h2>
                <div class="h-1 w-20 mt-2 bg-cyan-500 shadow-[0_0_10px_rgba(34,211,238,0.5)]"></div>
            </div>

            {{-- ২. ব্যাক বাটন --}}
            <button onclick="window.history.back()"
                class="flex items-center gap-3 px-6 py-3 border border-slate-700 rounded-xl hover:border-cyan-500 group transition-all bg-transparent cursor-pointer">
                <i class="fa-solid fa-chevron-left text-cyan-500 group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em]">
                    Back to previous
                </span>
            </button>

        </div>

        <div class="admin-card p-8 rounded-3xl border border-cyan-500/20 mb-10 shadow-2xl ">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-8 w-1 bg-cyan-500 shadow-[0_0_15px_rgba(34,211,238,0.8)]"></div>
                <h2 class="heading-font text-xl uppercase font-black tracking-tighter ">
                    Update <span class="text-cyan-400">Event Segment</span>
                </h2>
            </div>

            {{-- Form Start --}}
            <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Event Name</label>
                        <input type="text" name="name" value="{{ old('name', $event->name) }}" required
                            class="w-full border border-white/5 rounded-xl px-4 py-3 text-sm focus:border-cyan-500  outline-none transition shadow-inner">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $event->slug) }}" required
                            class="w-full border border-white/5 rounded-xl px-4 py-3 text-sm focus:border-cyan-500  outline-none transition shadow-inner">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Reg Fee (BDT)</label>
                        <input type="number" step="0.01" name="reg_fee" value="{{ old('reg_fee', $event->reg_fee) }}" required
                            class="w-full border border-white/5 rounded-xl px-4 py-3 text-sm focus:border-cyan-500  outline-none transition shadow-inner">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Deadline</label>
                        <input type="datetime-local" name="end_date"
                            value="{{ old('end_date', $event->end_date ? \Carbon\Carbon::parse($event->end_date)->format('Y-m-d\TH:i') : '') }}"
                            required
                            class="w-full border border-white/5 rounded-xl px-4 py-3 text-sm focus:border-cyan-500  outline-none transition shadow-inner">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Min Members</label>
                        <input type="number" name="min_members" value="{{ old('min_members', $event->min_members) }}" required
                            class="w-full border border-white/5 rounded-xl px-4 py-3 text-sm focus:border-cyan-500  outline-none transition shadow-inner">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Max Members</label>
                        <input type="number" name="max_members" value="{{ old('max_members', $event->max_members) }}" required
                            class="w-full border border-white/5 rounded-xl px-4 py-3 text-sm focus:border-cyan-500  outline-none transition shadow-inner">
                    </div>

                    {{-- CKEditor Description --}}
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Description</label>
                        <div class="custom-editor-container">
                            <textarea name="description" id="editor_update_description">{!! old('description', $event->description) !!}</textarea>
                        </div>
                    </div>

                    {{-- Rules --}}
                    <div class="md:col-span-2 space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Rules & Regulations</label>
                        <input type="text" name="rules" value="{{ old('rules', $event->rules) }}"
                            class="w-full border border-white/5 rounded-xl px-4 py-3 text-sm focus:border-cyan-500  outline-none transition">
                    </div>

                    {{-- Image Manage Section --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[10px] text-cyan-400 uppercase font-bold tracking-widest ml-1">Current Images (Check to delete)</label>
                        
                        <div class="flex flex-wrap gap-3 mb-2">
                            @if ($event->images)
                                @php
                                    $imagesArray = is_array($event->images) ? $event->images : json_decode($event->images, true) ?? [];
                                @endphp
                                @foreach ($imagesArray as $img)
                                    <div class="relative group w-20 h-20 border border-cyan-500/30 rounded-xl overflow-hidden">
                                        <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                                        
                                        <label class="absolute inset-0 bg-black/70 flex items-center justify-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer select-none">
                                            <input type="checkbox" name="delete_images[]" value="{{ $img }}" class="w-4 h-4 accent-red-500">
                                            <span class="text-[9px] text-red-400 font-bold uppercase">Delete</span>
                                        </label>
                                    </div>
                                @endforeach
                            @else
                                <span class="text-xs text-slate-500 italic">No images available</span>
                            @endif
                        </div>

                        <div class="relative group">
                            <input type="file" name="images[]" multiple
                                class="w-full border border-cyan-500/10 rounded-xl px-4 py-2.5 text-xs text-slate-400 file:bg-cyan-500 file:text-slate-900 file:border-0 file:px-4 file:py-1.5 file:rounded-lg file:mr-4 file:font-black file:uppercase file:cursor-pointer hover:file:bg-cyan-400">
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Result URL</label>
                        <input type="text" name="result" value="{{ old('result', $event->result) }}"
                            class="w-full border border-white/5 rounded-xl px-4 py-3 text-sm focus:border-cyan-500  outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Seat Plan URL</label>
                        <input type="text" name="seatplan" value="{{ old('seatplan', $event->seatplan) }}"
                            class="w-full border border-white/5 rounded-xl px-4 py-3 text-sm focus:border-cyan-500  outline-none transition">
                    </div>

                    <div class="flex items-center space-x-3 lg:col-span-2 pt-4">
                        <input type="checkbox" name="needs_coach" id="update_needs_coach" value="1"
                            {{ $event->needs_coach ? 'checked' : '' }}
                            class="w-5 h-5 accent-cyan-500 border-white/10 rounded cursor-pointer">
                        <label for="update_needs_coach" class="text-[10px] text-slate-300 uppercase font-black cursor-pointer tracking-wider select-none">
                            Requires Coach?
                        </label>
                    </div>

                    <div class="flex items-center space-x-3 lg:col-span-1 pt-4">
                        <label class="flex items-center cursor-pointer select-none gap-2">
                            <span class="text-[10px] text-slate-300 uppercase font-black tracking-wider">Status:</span>
                            <input type="checkbox" name="is_active" value="1" {{ $event->is_active ? 'checked' : '' }} class="hidden peer">
                            <div class="w-9 h-5 bg-slate-700 peer-checked:bg-cyan-500 rounded-full relative transition-all after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:w-4 after:h-4 after:rounded-full after:transition-all peer-checked:after:translate-x-4"></div>
                        </label>
                    </div>

                    <div class="lg:col-span-1 pt-4">
                        <button type="submit"
                            class="w-full bg-orange-500 hover:bg-orange-400 text-slate-900 py-3 rounded-xl font-black uppercase text-[11px] transition transform hover:scale-[1.02] shadow-[0_4px_20px_rgba(249,115,22,0.4)] cursor-pointer">
                            Update Segment
                        </button>
                    </div>
                </div>

            </form>
        </div>

        {{-- CKEditor Scripts --}}
        <script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                ClassicEditor
                    .create(document.querySelector('#editor_update_description'), {
                        toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'undo', 'redo']
                    })
                    .then(editor => {
                        editor.editing.view.change(writer => {
                            writer.setStyle('min-height', '200px', editor.editing.view.document.getRoot());
                        });
                    })
                    .catch(error => console.error(error));
            });
        </script>

        <style>
            /* CKEditor Dark CSS Custom styling */
            .ck-reset_all {
                --ck-color-base-background: #1e2636 !important;
                --ck-color-toolbar-background: #2d3748 !important;
            }
            .ck-editor__editable_inline {
                background-color: #1e2636 !important;
                color: #e2e8f0 !important;
                border-radius: 0 0 16px 16px !important;
                border: 1px solid rgba(255, 255, 255, 0.05) !important;
                padding: 10px 20px !important;
            }
            .ck-toolbar {
                background-color: #2d3748 !important;
                border-radius: 16px 16px 0 0 !important;
                border: 1px solid rgba(255, 255, 255, 0.05) !important;
                color: white !important;
            }
            .ck.ck-editor__main>.ck-editor__editable:not(.ck-focused) {
                border-color: rgba(255, 255, 255, 0.05) !important;
            }

            @media (prefers-color-scheme: light) {
                .admin-card { background-color: #ffffff; border-color: #e2e8f0; }
                .admin-card input, .admin-card textarea { background-color: #f8fafc; border-color: #e2e8f0; color: #1e293b; }
                .heading-font { color: #1e293b !important; }
            }
        </style>
    </div>
@endsection