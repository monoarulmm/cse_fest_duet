@extends('layouts.app')

@section('custom_css')
<style>
    .admin-card {
        background: var(--bg-surface);
        border: 1px solid var(--border-accent);
        color: var(--text-primary);
    }
    .admin-input {
        width: 100%;
        background: var(--bg-elevated);
        border: 1px solid var(--border-soft);
        color: var(--text-primary);
        border-radius: 0.75rem;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .admin-input:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 2px var(--accent-dim);
    }
    .admin-label {
        font-size: 0.625rem;
        color: var(--text-muted);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.15em;
        display: block;
        margin-bottom: 0.25rem;
        margin-left: 0.25rem;
    }
    .admin-label.accent { color: var(--accent); }
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
    .table-row-card {
        background: var(--bg-elevated);
        transition: background 0.15s;
        border-radius: 0;
    }
    .table-row-card:hover { background: var(--accent-dim); }
    .table-head { color: var(--text-muted); font-size: 0.625rem; text-transform: uppercase; }
    .readonly-input {
        background: var(--bg-elevated);
        border: 1px solid var(--border-soft);
        color: var(--text-primary);
        border-radius: 0.25rem;
        padding: 0.375rem 0.5rem;
        outline: none;
        transition: border-color 0.2s;
        font-size: 0.75rem;
    }
    .readonly-input:focus { border-color: var(--accent); }
    /* CKEditor dark/light */
    .ck-editor__editable_inline {
        background-color: var(--bg-elevated) !important;
        color: var(--text-primary) !important;
        border-bottom-left-radius: 12px !important;
        border-bottom-right-radius: 12px !important;
        border: 1px solid var(--border-soft) !important;
        min-height: 150px;
    }
    .ck-toolbar {
        background-color: var(--bg-elevated) !important;
        border-top-left-radius: 12px !important;
        border-top-right-radius: 12px !important;
        border: 1px solid var(--border-soft) !important;
        border-bottom: none !important;
    }
    .ck.ck-toolbar .ck-button { color: var(--text-primary) !important; }
    .ck.ck-list__item { color: #1a202c !important; }
    .ck.ck-editor__main>.ck-editor__editable:focus {
        border-color: var(--accent) !important;
        box-shadow: none !important;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-10">

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 mb-10 pb-8"
        style="border-bottom:1px solid var(--border-accent)">
        <div>
            <h2 class="text-4xl md:text-5xl font-black uppercase tracking-tighter" style="color:var(--text-primary)">
                Event Management <span style="color:var(--accent)">Details</span>
            </h2>
            <div class="h-1 w-20 mt-2 rounded-full" style="background:var(--accent); box-shadow:0 0 10px rgba(34,211,238,0.5)"></div>
        </div>
        <button onclick="window.history.back()" class="back-btn group">
            <i class="fa-solid fa-chevron-left group-hover:-translate-x-1 transition-transform" style="color:var(--accent)"></i>
            Back to previous
        </button>
    </div>

    {{-- Create New Event --}}
    <div class="admin-card p-8 rounded-3xl mb-10 shadow-2xl">
        <div class="flex items-center gap-3 mb-6">
            <div class="h-8 w-1 rounded-full" style="background:var(--accent); box-shadow:0 0 15px rgba(34,211,238,0.8)"></div>
            <h2 class="heading-font text-xl uppercase font-black tracking-tighter" style="color:var(--text-primary)">
                Create New <span style="color:var(--accent)">Event Segment</span>
            </h2>
        </div>

        <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <div class="space-y-1">
                    <label class="admin-label">Event Name</label>
                    <input type="text" name="name" required class="admin-input">
                </div>
                <div class="space-y-1">
                    <label class="admin-label">Slug</label>
                    <input type="text" name="slug" required class="admin-input">
                </div>
                <div class="space-y-1">
                    <label class="admin-label">Reg Fee</label>
                    <input type="number" name="reg_fee" required class="admin-input">
                </div>
                <div class="space-y-1">
                    <label class="admin-label">Deadline</label>
                    <input type="datetime-local" name="end_date" required class="admin-input">
                </div>
                <div class="space-y-1">
                    <label class="admin-label">Min Members</label>
                    <input type="number" name="min_members" value="1" class="admin-input">
                </div>
                <div class="space-y-1">
                    <label class="admin-label">Max Members</label>
                    <input type="number" name="max_members" value="3" class="admin-input">
                </div>

                <div class="md:col-span-2 space-y-1">
                    <label class="admin-label">Description</label>
                    <div class="custom-editor-container">
                        <textarea name="description" id="editor_description"></textarea>
                    </div>
                </div>

                <div class="md:col-span-2 space-y-1">
                    <label class="admin-label">Rules & Regulations</label>
                    <input type="text" name="rules" class="admin-input">
                </div>

                <div class="md:col-span-2 space-y-1">
                    <label class="admin-label accent">Event Images (Multiple)</label>
                    <input type="file" name="images[]" multiple
                        class="w-full rounded-xl px-4 py-2.5 text-xs cursor-pointer"
                        style="background:var(--bg-elevated); border:1px solid var(--accent-border); color:var(--text-secondary)">
                </div>

                <div class="space-y-1">
                    <label class="admin-label">Result URL</label>
                    <input type="text" name="result" class="admin-input">
                </div>
                <div class="space-y-1">
                    <label class="admin-label">Seat Plan URL</label>
                    <input type="text" name="seatplan" class="admin-input">
                </div>

                <div class="flex items-center space-x-3 lg:col-span-3">
                    <input type="checkbox" name="needs_coach" id="needs_coach" value="1"
                        class="w-5 h-5 accent-cyan-500 rounded">
                    <label for="needs_coach" class="text-[10px] font-black cursor-pointer uppercase tracking-wider"
                        style="color:var(--text-primary)">Requires Coach?</label>
                </div>

                <div class="lg:col-span-1">
                    <button type="submit"
                        class="w-full py-3 rounded-xl font-black uppercase text-[11px] transition transform hover:scale-[1.02] text-slate-900"
                        style="background:var(--accent); box-shadow:0 4px 20px rgba(34,211,238,0.4)">
                        + Add Segment
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Existing Segments Table --}}
    <div class="admin-card p-8 rounded-3xl">
        <div class="flex justify-between items-center mb-6">
            <h2 class="heading-font text-xl uppercase font-black" style="color:var(--accent)">
                Existing <span style="color:var(--text-primary)">Segments</span>
            </h2>
            <span class="text-[10px] uppercase tracking-widest" style="color:var(--text-muted)">Total: {{ count($events) }}</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-separate border-spacing-y-2">
                <thead class="table-head">
                    <tr>
                        <th class="px-4 pb-2">Name & Slug</th>
                        <th class="px-4 pb-2">Fee (BDT)</th>
                        <th class="px-4 pb-2">Deadline</th>
                        <th class="px-4 pb-2">Members (Min-Max)</th>
                        <th class="px-4 pb-2 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach ($events as $event)
                        <tr class="table-row-card">
                            <td class="p-4 rounded-l-xl">
                                <input type="text" value="{{ $event->name }}" readonly
                                    class="readonly-input font-bold mb-1">
                                <input type="text" value="{{ $event->slug }}" readonly
                                    class="readonly-input text-[10px] uppercase tracking-widest" style="color:var(--accent)">
                            </td>
                            <td class="p-4">
                                <input type="number" value="{{ $event->reg_fee }}" readonly
                                    class="readonly-input w-20" style="color:var(--accent)">
                            </td>
                            <td class="p-4">
                                <input type="datetime-local"
                                    value="{{ $event->end_date->format('Y-m-d\TH:i') }}" readonly
                                    class="readonly-input text-[10px]">
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <input type="number" value="{{ $event->min_members }}" readonly
                                        class="readonly-input w-10 text-center">
                                    <span style="color:var(--text-muted)">-</span>
                                    <input type="number" value="{{ $event->max_members }}" readonly
                                        class="readonly-input w-10 text-center">
                                </div>
                            </td>
                            <td class="p-4 rounded-r-xl">
                                <div class="flex items-center gap-3 justify-end">
                                    <a href="{{ route('events.edit', $event->id) }}"
                                        class="text-[10px] font-bold uppercase transition"
                                        style="color:var(--accent)">Full Edit</a>
                                    <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this event?');"
                                        class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="border px-3 py-1.5 rounded-lg text-[10px] font-black uppercase transition"
                                            style="background:rgba(239,68,68,0.1); border-color:rgba(239,68,68,0.5); color:rgb(248,113,113)"
                                            onmouseover="this.style.background='rgba(239,68,68,0.8)'; this.style.color='white'"
                                            onmouseout="this.style.background='rgba(239,68,68,0.1)'; this.style.color='rgb(248,113,113)'">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.1.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        ClassicEditor
            .create(document.querySelector('#editor_description'), {
                toolbar: ['heading','|','bold','italic','link','bulletedList','numberedList','blockQuote','undo','redo']
            })
            .catch(error => console.error(error));
    });
</script>
@endsection