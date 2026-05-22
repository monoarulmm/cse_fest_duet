@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="mb-8 flex justify-between items-center">

            <button onclick="window.history.back()"
                class="flex items-center gap-3 px-6 py-3  border border-slate-700 rounded-xl hover:border-cyan-500 group transition-all">
                <i class="fa-solid fa-chevron-left text-cyan-500 group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-xs font-bold text-slate-400 group-hover:text-white uppercase tracking-[0.2em]">
                    Back to previous
                </span>
            </button>

            <div class=" border border-slate-800 px-6 py-2 rounded-2xl">
                <span class="text-slate-400 text-sm">Total Unique Institutes:</span>
                <span class="text-purple-400 font-bold ml-2">{{ $institutes->count() }}</span>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-slate-800  shadow-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="">
                        <th class="p-6 text-slate-400 font-bold uppercase text-xs tracking-widest">Institution Name</th>
                        <th class="p-6 text-slate-400 font-bold uppercase text-xs tracking-widest text-center">Total Teams
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($institutes as $institute)
                        <tr class="hover:bg-slate-800/30 transition-colors group">
                            <td class="p-6">
                                <span class=" font-semibold group-hover:text-purple-400 transition-colors">
                                    {{ $institute->university_name }}
                                </span>
                            </td>
                            <td class="p-6 text-center">
                                <span
                                    class="bg-purple-500/10 text-purple-400 px-4 py-1 rounded-full text-sm font-bold border border-purple-500/20">
                                    {{ $institute->total_teams }}
                                </span>
                            </td>
                            
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-20 text-center text-slate-500 italic">
                                No registrations found for this event.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Back Button --}}
        <div class="mt-16 text-center">
            <a href="{{ route('event.dashboard', $event->slug) }}"
                class="text-slate-500 hover:text-green-400 transition-colors uppercase text-sm font-bold tracking-widest">
                <i class="fa-solid fa-arrow-left mr-2"></i> Back to Event Details
            </a>
        </div>
    </div>
@endsection
