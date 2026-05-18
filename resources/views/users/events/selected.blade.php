@extends('layouts.app')

@section('content')
    <div class="container mx-auto px-4 py-10">
        <div class="max-w-6xl mx-auto">

            {{-- ইভেন্ট হেডার --}}
            <div class="text-center mb-10">
                <h1 class="text-4xl font-black text-white uppercase tracking-tight">{{ $event->name }}</h1>
                <p class="text-purple-400 tracking-[0.2em] mt-1 text-sm font-bold uppercase">Selection Phase</p>
            </div>


            {{-- ২. জাভাস্ক্রিপ্ট ব্যাক বাটন (হিস্ট্রি অনুযায়ী কাজ করবে) --}}
            <button onclick="window.history.back()"
                class="flex items-center gap-3 px-6 py-3 bg-slate-900 border border-slate-700 rounded-xl hover:border-cyan-500 group transition-all">
                <i class="fa-solid fa-chevron-left text-cyan-500 group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-xs font-bold text-slate-400 group-hover:text-white uppercase tracking-[0.2em]">
                    Back to previous
                </span>
            </button>

            {{-- ডাইনামিক ট্যাব মেনু (কাউন্ট সহ) --}}
            {{-- <div class="flex flex-wrap justify-center gap-4 mb-10">
                <a href="{{ route('event.pre_registered', $event->slug) }}"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 bg-slate-900/50 text-cyan-400 border-cyan-500/20 hover:border-cyan-500/50">
                    Pre-Registered ({{ $counts['pre-registered'] ?? 0 }})
                </a>

                <a href="{{ route('event.select_registered', $event->slug) }}"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 bg-purple-500 text-white font-bold shadow-[0_0_20px_rgba(168,85,247,0.4)]">
                    Selected ({{ $counts['selected'] ?? 0 }})
                </a>

                <a href="{{ route('event.final_registered', $event->slug) }}"
                    class="px-6 py-3 rounded-2xl border transition-all duration-300 bg-slate-900/50 text-green-400 border-green-500/20 hover:border-green-500/50">
                    Final Verified ({{ $counts['verified'] ?? 0 }})
                </a>
            </div> --}}

            {{-- প্রফেশনাল সার্চ ফরম --}}
            <form action="{{ url()->current() }}" method="GET" class="mb-10 flex justify-center">
                <div class="relative w-full max-w-xl group">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search Selected Team or Student ID."
                        class="w-full bg-slate-900/80 border border-purple-500/30 rounded-full px-8 py-4 text-white focus:outline-none focus:border-purple-500 transition-all shadow-xl">
                    <button type="submit"
                        class="absolute right-3 top-2.5 bottom-2.5 bg-purple-500 px-6 rounded-full text-white font-black hover:scale-105 transition-transform flex items-center gap-2">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <span class="hidden md:inline text-xs">SEARCH</span>
                    </button>
                </div>
            </form>

            {{-- ডেটা টেবিল --}}
            <div
                class="form-glass rounded-3xl overflow-hidden shadow-2xl border border-purple-500/20 bg-slate-900/40 backdrop-blur-md">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-purple-500/10 text-purple-400 heading-font text-xs uppercase tracking-widest">
                        <tr>
                            <th class="p-6">Team / Participant</th>
                            <th class="p-6">University / Institute</th>
                            <th class="p-6">Status</th>
                            <th class="p-6 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-slate-300 text-sm">
                        @forelse ($teams as $team)
                            <tr class="border-b border-purple-500/10 hover:bg-purple-500/5 transition duration-300">
                                <td class="p-6 font-bold text-white">
                                    {{ $team->team_name ?? 'Participant: ' . $team->m1_name }}
                                </td>
                                <td class="p-6">{{ $team->university_name }}</td>
                                <td class="p-6">
                                    <span
                                        class="px-3 py-1 rounded-full text-[10px] uppercase font-black bg-green-500/20 text-green-400 border border-green-500/30">
                                        {{ $team->status }}
                                    </span>
                                </td>
                                {{-- টেবিলের ভেতরের অ্যাকশন বাটন --}}
                                <td class="p-6 text-right">
                                    @if ($event->slug == 'iupc')
                                        {{-- শুধুমাত্র IUPC এর জন্য মোডাল ওপেন হবে --}}
                                        <button onclick="openCouponModal('{{ $team->team_name ?? $team->m1_name }}')"
                                            class="bg-cyan-500 text-slate-900 px-5 py-2 rounded-full text-[10px] font-black uppercase hover:scale-110 transition-all">
                                            Verify Coupon
                                        </button>
                                    @else
                                        {{-- Project Showcase বা Hackathon এর জন্য সরাসরি লিঙ্ক --}}
                                        <a href="{{ route('event.final_reg_direct', [$event->slug, $team->id]) }}"
                                            class="bg-green-500 text-slate-900 px-5 py-2 rounded-full text-[10px] font-black uppercase hover:scale-110 transition-all">
                                            Make Payment
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-10 text-center text-slate-500 italic">
                                    No selected teams found for this event.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- প্যাজিনেশন --}}
            <div class="mt-8">
                {{ $teams->appends(request()->input())->links() }}
            </div>
        </div>
    </div>

    {{-- কুপন ভেরিফিকেশন মোডাল (যদি আলাদা ফাইলে না থাকে তবে এখানে যুক্ত করুন) --}}
    {{-- @include('users.events.partials.coupon_modal') --}}

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
            // Animation
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
