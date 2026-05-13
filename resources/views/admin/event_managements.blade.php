{{-- @extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">

        <div class="admin-card p-8 rounded-3xl border border-cyan-500/20 bg-slate-900/50 mb-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-8 w-1 bg-cyan-500 shadow-[0_0_15px_rgba(34,211,238,0.8)]"></div>
                <h2 class="heading-font text-xl text-white uppercase font-black">Create New <span class="text-cyan-400">Event
                        Segment</span></h2>
            </div>

            <form action="{{ route('admin.events.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Event
                            Name</label>
                        <input type="text" name="name" required placeholder="Ex: AI Hackathon"
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Slug (Unique
                            ID)</label>
                        <input type="text" name="slug" required placeholder="Ex: ai-hackathon"
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Reg Fee
                            (BDT)</label>
                        <input type="number" name="reg_fee" required placeholder="1000"
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Deadline</label>
                        <input type="datetime-local" name="end_date" required
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Min
                            Members</label>
                        <input type="number" name="min_members" value="1"
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Max
                            Members</label>
                        <input type="number" name="max_members" value="3"
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="flex items-center space-x-3 lg:pt-8">
                        <input type="checkbox" name="needs_coach" id="needs_coach" value="1"
                            class="w-5 h-5 accent-cyan-500">
                        <label for="needs_coach" class="text-xs text-white uppercase font-bold cursor-pointer">Requires
                            Coach?</label>
                    </div>

                    <div class="lg:pt-6">
                        <button type="submit"
                            class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-900 py-3 rounded-xl font-black uppercase text-xs transition transform hover:scale-[1.02]">
                            + Add Segment
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <div class="admin-card p-8 rounded-3xl border border-cyan-500/20 bg-slate-900/50">
            <div class="flex justify-between items-center mb-6">
                <h2 class="heading-font text-xl text-cyan-400 uppercase font-black">Existing <span
                        class="text-white">Segments</span></h2>
                <span class="text-[10px] text-slate-500 uppercase tracking-widest">Total: {{ count($events) }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="text-[10px] text-slate-400 uppercase border-b border-white/5">
                        <tr>
                            <th class="p-4">Event Name & Rule</th>
                            <th class="p-4">Reg Fee</th>
                            <th class="p-4">Deadline</th>
                            <th class="p-4">Member Limit</th>
                            <th class="p-4 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-white text-sm">
                        @foreach ($events as $event)
                            <tr class="border-b border-white/5 hover:bg-white/5 transition group">
                                <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
                                    @csrf
                                    <td class="p-4">
                                        <div class="font-bold">{{ $event->name }}</div>
                                        <div
                                            class="text-[9px] {{ $event->needs_coach ? 'text-cyan-500' : 'text-slate-500' }} uppercase">
                                            {{ $event->needs_coach ? 'Coach Required' : 'No Coach' }}
                                        </div>
                                    </td>
                                    <td class="p-4">
                                        <input type="number" name="reg_fee" value="{{ $event->reg_fee }}"
                                            class="bg-slate-800 border border-white/10 rounded px-3 py-1.5 w-24 text-cyan-400 focus:border-cyan-500 outline-none">
                                    </td>
                                    <td class="p-4">
                                        <input type="datetime-local" name="end_date"
                                            value="{{ $event->end_date->format('Y-m-d\TH:i') }}"
                                            class="bg-slate-800 border border-white/10 rounded px-3 py-1.5 text-xs focus:border-cyan-500 outline-none">
                                    </td>
                                    <td class="p-4">
                                        <div class="flex gap-2">
                                            <input type="number" name="min_members" value="{{ $event->min_members }}"
                                                title="Min"
                                                class="bg-slate-800 border border-white/10 rounded px-2 py-1 w-12 text-center text-xs">
                                            <span class="text-slate-600">-</span>
                                            <input type="number" name="max_members" value="{{ $event->max_members }}"
                                                title="Max"
                                                class="bg-slate-800 border border-white/10 rounded px-2 py-1 w-12 text-center text-xs">
                                        </div>
                                    </td>
                                    <td class="p-4 text-right">
                                        <button type="submit"
                                            class="bg-white/10 group-hover:bg-cyan-500 group-hover:text-slate-900 px-4 py-2 rounded-lg font-black uppercase text-[10px] transition-all">
                                            Save
                                        </button>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection --}}


@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">

        {{-- Create New Event Section --}}
        <div class="admin-card p-8 rounded-3xl border border-cyan-500/20 bg-slate-900/50 mb-10">
            <div class="flex items-center gap-3 mb-6">
                <div class="h-8 w-1 bg-cyan-500 shadow-[0_0_15px_rgba(34,211,238,0.8)]"></div>
                <h2 class="heading-font text-xl text-white uppercase font-black">Create New <span class="text-cyan-400">Event
                        Segment</span></h2>
            </div>

            <form action="{{ route('admin.events.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Event
                            Name</label>
                        <input type="text" name="name" required placeholder="Ex: AI Hackathon"
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Slug (Unique
                            ID)</label>
                        <input type="text" name="slug" required placeholder="Ex: ai-hackathon"
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Reg Fee
                            (BDT)</label>
                        <input type="number" name="reg_fee" required
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Deadline</label>
                        <input type="datetime-local" name="end_date" required
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Min
                            Members</label>
                        <input type="number" name="min_members" value="1"
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="space-y-1">
                        <label class="text-[10px] text-slate-400 uppercase font-bold tracking-widest ml-1">Max
                            Members</label>
                        <input type="number" name="max_members" value="3"
                            class="w-full bg-slate-800 border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:border-cyan-500 outline-none transition">
                    </div>

                    <div class="flex items-center space-x-3 lg:pt-8">
                        <input type="checkbox" name="needs_coach" id="needs_coach" value="1"
                            class="w-5 h-5 accent-cyan-500">
                        <label for="needs_coach" class="text-xs text-white uppercase font-bold cursor-pointer">Requires
                            Coach?</label>
                    </div>

                    <div class="lg:pt-6">
                        <button type="submit"
                            class="w-full bg-cyan-500 hover:bg-cyan-400 text-slate-900 py-3 rounded-xl font-black uppercase text-xs transition transform hover:scale-[1.02]">
                            + Add Segment
                        </button>
                    </div>
                </div>
            </form>
        </div>

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
                                <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
                                    @csrf
                                    <td class="p-4 rounded-l-xl">
                                        <input type="text" name="name" value="{{ $event->name }}"
                                            class="bg-transparent border-b border-white/10 focus:border-cyan-500 outline-none w-full font-bold text-white mb-1">
                                        <input type="text" name="slug" value="{{ $event->slug }}"
                                            class="bg-transparent border-b border-white/10 focus:border-cyan-500 outline-none w-full text-[10px] text-cyan-500 uppercase tracking-widest">
                                    </td>
                                    <td class="p-4">
                                        <input type="number" name="reg_fee" value="{{ $event->reg_fee }}"
                                            class="bg-slate-800 border border-white/10 rounded px-2 py-1.5 w-20 text-cyan-400 focus:border-cyan-500 outline-none">
                                    </td>
                                    <td class="p-4">
                                        <input type="datetime-local" name="end_date"
                                            value="{{ $event->end_date->format('Y-m-d\TH:i') }}"
                                            class="bg-slate-800 border border-white/10 rounded px-2 py-1.5 text-[10px] focus:border-cyan-500 outline-none">
                                    </td>
                                    <td class="p-4">
                                        <div class="flex items-center gap-2">
                                            <input type="number" name="min_members" value="{{ $event->min_members }}"
                                                class="bg-slate-800 border border-white/10 rounded px-1 py-1 w-10 text-center text-xs">
                                            <span class="text-slate-600">-</span>
                                            <input type="number" name="max_members" value="{{ $event->max_members }}"
                                                class="bg-slate-800 border border-white/10 rounded px-1 py-1 w-10 text-center text-xs">
                                        </div>
                                    </td>
                                    <td class="p-4 text-right rounded-r-xl">
                                        <div class="flex items-center justify-end gap-3">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" name="is_active" value="1"
                                                    {{ $event->is_active ? 'checked' : '' }} class="hidden peer">
                                                <div
                                                    class="w-8 h-4 bg-slate-700 peer-checked:bg-cyan-500 rounded-full relative transition-all after:content-[''] after:absolute after:top-1 after:left-1 after:bg-white after:w-2 after:h-2 after:rounded-full after:transition-all peer-checked:after:translate-x-4">
                                                </div>
                                            </label>
                                            <button type="submit"
                                                class="bg-cyan-500 text-slate-900 px-4 py-2 rounded-lg font-black uppercase text-[10px] hover:bg-cyan-400 transition-all shadow-[0_0_10px_rgba(34,211,238,0.2)]">
                                                Save
                                            </button>
                                        </div>
                                    </td>
                                </form>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
