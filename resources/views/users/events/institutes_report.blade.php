@extends('layouts.app')

@section('content')
    <div class="max-w-6xl mx-auto px-4 py-10">
        <div class="mb-8 flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-black text-white uppercase italic">
                    Participating <span class="text-purple-500">Institutions</span>
                </h2>
                <p class="text-slate-500">Event: {{ $event->name }}</p>
            </div>
            <div class="bg-slate-900 border border-slate-800 px-6 py-2 rounded-2xl">
                <span class="text-slate-400 text-sm">Total Unique Institutes:</span>
                <span class="text-purple-400 font-bold ml-2">{{ $institutes->count() }}</span>
            </div>
        </div>

        <div class="overflow-hidden rounded-[2rem] border border-slate-800 bg-slate-900/50 shadow-xl">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-800/50">
                        <th class="p-6 text-slate-400 font-bold uppercase text-xs tracking-widest">Institution Name</th>
                        <th class="p-6 text-slate-400 font-bold uppercase text-xs tracking-widest text-center">Total Teams
                        </th>
                        <th class="p-6 text-slate-400 font-bold uppercase text-xs tracking-widest text-center">Total
                            Participants</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800">
                    @forelse($institutes as $institute)
                        <tr class="hover:bg-slate-800/30 transition-colors group">
                            <td class="p-6">
                                <span class="text-slate-200 font-semibold group-hover:text-purple-400 transition-colors">
                                    {{ $institute->university_name }}
                                </span>
                            </td>
                            <td class="p-6 text-center">
                                <span
                                    class="bg-purple-500/10 text-purple-400 px-4 py-1 rounded-full text-sm font-bold border border-purple-500/20">
                                    {{ $institute->total_teams }}
                                </span>
                            </td>
                            <td class="p-6 text-center">
                                <span class="text-slate-300 font-mono italic">{{ $institute->total_registrations }}</span>
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
