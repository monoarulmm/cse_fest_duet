@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-3 sm:px-4 py-6 sm:py-10">
        <div class="max-w-6xl mx-auto">

            {{-- ইভেন্ট হেডার --}}
            <div class="mb-8 text-center">
                <h1 class="text-2xl sm:text-4xl font-black text-white uppercase tracking-wider">{{ $event->name }}</h1>
                <p class="text-purple-400 tracking-[0.2em] mt-1 text-xs sm:text-sm font-bold uppercase">Selection Phase</p>
            </div>

            {{-- জাভাস্ক্রিপ্ট ব্যাক বাটন --}}
            <div class="mb-6">
                <button onclick="window.history.back()"
                    class="flex items-center gap-2 px-4 py-2 bg-slate-900 border border-slate-700 rounded-xl hover:border-cyan-500 group transition-all">
                    <i
                        class="fa-solid fa-chevron-left text-cyan-500 group-hover:-translate-x-1 transition-transform text-xs"></i>
                    <span
                        class="text-xs font-bold text-slate-400 group-hover:text-white uppercase tracking-[0.15em]">Back</span>
                </button>
            </div>

            {{-- প্রফেশনাল সার্চ ফরম --}}
            <form action="{{ url()->current() }}" method="GET" class="mb-6 flex justify-center px-1">
                <div class="relative w-full max-w-xl group">
                    <input type="text" name="search" value="{{ request('search') }}" required
                        placeholder="Search Selected Team or Student ID..."
                        class="w-full bg-slate-900/80 border border-purple-500/30 rounded-full px-5 sm:px-8 py-3 sm:py-4 text-sm text-white focus:outline-none focus:border-purple-500 transition-all pr-28 sm:pr-36 shadow-xl">
                    <button type="submit"
                        class="absolute right-2 top-1.5 bottom-1.5 sm:top-2 sm:bottom-2 bg-purple-500 px-4 sm:px-6 rounded-full text-white font-black hover:scale-105 transition-transform flex items-center gap-1.5 text-sm">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        <span class="hidden sm:inline text-xs font-black uppercase tracking-wider">Search</span>
                    </button>
                </div>
            </form>

            {{-- ডেটা পার্ট: শুধুমাত্র সার্চ করা হলেই টেবিল বা ফিল্টার রেজাল্ট দেখাবে --}}
            @if (request('search'))
                <div class="mb-3 px-1 flex items-center justify-between text-slate-400 text-xs">
                    <div class="flex items-center gap-2">
                        <i class="fa-solid fa-filter text-purple-500"></i>
                        <span>Results for: <strong class="text-purple-400">"{{ request('search') }}"</strong></span>
                    </div>
                    <a href="{{ url()->current() }}"
                        class="text-red-400 hover:text-red-300 underline underline-offset-2 font-bold uppercase text-[10px] tracking-wider">Clear
                        Search</a>
                </div>

                {{-- টেবিল স্ট্রাকচার --}}
                <div
                    class="rounded-2xl sm:rounded-3xl border border-purple-500/20 bg-slate-900/40 backdrop-blur-md shadow-2xl">
                    <div class="overflow-x-auto w-full" style="-webkit-overflow-scrolling: touch;">
                        <table class="text-left border-collapse" style="width: 100%; min-width: 560px;">
                            <thead
                                class="bg-purple-500/10 text-purple-400 text-[10px] sm:text-xs uppercase tracking-widest">
                                <tr>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">Team / Participant</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">University / Institute</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold">Status</th>
                                    <th class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-slate-300 text-sm divide-y divide-purple-500/10">
                                @forelse ($teams as $team)
                                    <tr class="hover:bg-purple-500/5 transition duration-200">
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap font-bold text-white">
                                            {{ $team->team_name ?? 'Participant: ' . $team->m1_name }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap text-xs text-slate-300">
                                            {{ $team->university_name }}
                                        </td>
                                        <td class="px-4 sm:px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-2.5 py-1 rounded-full text-[9px] uppercase font-black tracking-wider bg-green-500/20 text-green-400 border border-green-500/30">
                                                {{ $team->status }}
                                            </span>
                                        </td>
                                        {{-- টেবিলের ভেতরের অ্যাকশন বাটন --}}
                                        <td class="px-4 sm:px-6 py-4 text-right whitespace-nowrap">
                                            @if ($event->slug == 'iupc')
                                                {{-- শুধুমাত্র IUPC এর জন্য মোডাল ওপেন হবে --}}
                                                <button
                                                    onclick="openCouponModal('{{ $team->team_name ?? $team->m1_name }}')"
                                                    class="bg-cyan-500 text-slate-900 px-3 sm:px-4 py-1.5 rounded-full text-[10px] font-black uppercase hover:scale-105 transition-all">
                                                    Verify Coupon
                                                </button>
                                            @else
                                                {{-- Project Showcase বা Hackathon এর জন্য সরাসরি লিঙ্ক --}}
                                                <a href="{{ route('event.final_reg_direct', [$event->slug, $team->id]) }}"
                                                    class="inline-flex items-center bg-green-500 text-slate-900 px-3 sm:px-4 py-1.5 rounded-full text-[10px] font-black uppercase hover:scale-105 transition-all">
                                                    Make Payment
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">
                                            <i class="fa-solid fa-magnifying-glass text-slate-600 text-2xl mb-3 block"></i>
                                            No selected teams found for <strong
                                                class="text-white">"{{ request('search') }}"</strong>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Scroll hint — only mobile --}}
                @if ($teams->count() > 0)
                    <p class="sm:hidden text-center text-slate-600 text-[10px] mt-2 italic">
                        <i class="fa-solid fa-arrows-left-right mr-1"></i> বাম/ডানে স্ক্রল করুন
                    </p>
                @endif

                {{-- প্যাজিনেশন --}}
                @if ($teams->hasPages())
                    <div class="mt-6">
                        {{ $teams->appends(request()->input())->links() }}
                    </div>
                @endif
            @else
                {{-- সার্চ করার আগের সুন্দর ফাঁকা স্টেট মেসেজ --}}
                <div class="text-center py-20 border-2 border-dashed border-slate-800 rounded-3xl bg-slate-900/10 px-4">
                    <i class="fa-solid fa-magnifying-glass text-5xl text-slate-700 mb-4 block"></i>
                    <h3 class="text-slate-500 text-xs sm:text-sm font-bold uppercase tracking-widest">Enter your Team Name
                        or Student ID to Check Selection Status</h3>
                    <p class="text-slate-600 text-[10px] sm:text-xs mt-2 italic">Search to check if your team is selected
                        and proceed with the next steps.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- কুপন ভেরিফিকেশন মোডাল --}}
    <div id="couponModal"
        class="fixed inset-0 z-[100] hidden flex items-center justify-center bg-slate-950/80 backdrop-blur-md px-4">
        <div class="relative w-full max-w-md transform transition-all">
            <div
                class="bg-slate-900 border border-cyan-500/30 rounded-[2.5rem] overflow-hidden shadow-[0_0_50px_rgba(34,211,238,0.15)]">
                <div class="bg-cyan-500 p-6 text-center">
                    <h3 class="text-slate-900 font-black uppercase tracking-tighter text-xl">Finalize Registration</h3>
                    <p class="text-slate-900/70 text-[10px] font-bold uppercase tracking-widest mt-1">Verify your team with
                        a coupon code</p>
                </div>

                <div class="p-8">
                    <div class="mb-6 text-center">
                        <p class="text-slate-400 text-sm">Team: <span id="modalTeamName"
                                class="text-white font-bold italic"></span></p>
                    </div>

                    <form id="couponForm" action="{{ url('event.verify_coupon', $event->slug) }}" method="POST">
                        @csrf
                        <input type="hidden" name="team_name" id="hiddenTeamName">

                        <div class="relative group">
                            <input type="text" name="coupon_code" required placeholder="ENTER COUPON CODE"
                                class="w-full bg-slate-800 border border-slate-700 rounded-2xl px-6 py-4 text-white text-center font-mono tracking-[0.3em] focus:outline-none focus:border-cyan-500 transition-all placeholder:text-slate-600 uppercase">
                            <div
                                class="absolute inset-0 rounded-2xl bg-cyan-500/5 blur-xl group-hover:bg-cyan-500/10 transition-all -z-10">
                            </div>
                        </div>

                        <div class="mt-8 flex flex-col gap-3">
                            <button type="submit"
                                class="w-full bg-cyan-500 text-slate-900 font-black py-4 rounded-2xl uppercase tracking-widest hover:scale-[1.02] transition-transform shadow-[0_10px_20px_rgba(34,211,238,0.3)]">
                                Verify & Confirm
                            </button>

                            <button type="button" onclick="closeCouponModal()"
                                class="w-full bg-transparent text-slate-500 font-bold py-3 rounded-2xl uppercase text-[10px] hover:text-white transition-colors">
                                Maybe Later
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openCouponModal(teamName) {
            document.getElementById('modalTeamName').innerText = teamName;
            document.getElementById('hiddenTeamName').value = teamName;
            const modal = document.getElementById('couponModal');
            modal.classList.remove('hidden');
            modal.querySelector('.transform').classList.add('scale-100', 'opacity-100');
        }

        function closeCouponModal() {
            const modal = document.getElementById('couponModal');
            modal.classList.add('hidden');
        }

        // ক্লোজ অন আউটসাইড ক্লিক
        window.onclick = function(event) {
            const modal = document.getElementById('couponModal');
            if (event.target == modal) {
                closeCouponModal();
            }
        }
    </script>
@endsection
