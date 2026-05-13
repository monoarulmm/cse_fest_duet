 @extends('layouts.app')

 @section('content')
     <section class="py-20 container mx-auto px-6">
         <!-- Section Header -->
         <div class="mb-16 border-l-4 border-cyan-500 pl-6 flex justify-between items-end">
             <div>
                 <h2 class="heading-font text-4xl md:text-6xl font-black uppercase tracking-tighter">
                     Event <span class="text-cyan-500">Schedule</span>
                 </h2>
                 <p class="text-slate-500 font-mono text-sm mt-2 tracking-widest">>> MISSION_TIMELINE_2026</p>
             </div>

             <!-- Day Selector Buttons -->
             <div class="flex gap-2 mb-2">
                 <button
                     class="day-tab active px-6 py-2 border border-cyan-500/30 text-[10px] font-bold uppercase tracking-widest">Day
                     01</button>
                 <button
                     class="day-tab px-6 py-2 border border-cyan-500/30 text-slate-500 text-[10px] font-bold uppercase tracking-widest hover:text-cyan-400">Day
                     02</button>
             </div>
         </div>

         <!-- Timeline Grid -->
         <div class="timeline-container ml-4 md:ml-10">

             <!-- Slot 1: Inauguration -->
             <div class="timeline-item mb-12 group">
                 <div class="timeline-dot"></div>
                 <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                     <div class="md:col-span-1">
                         <span class="heading-font text-cyan-400 text-xl font-bold">08:00 AM</span>
                         <p class="text-[10px] text-slate-500 font-mono">09:00 AM (60 Min)</p>
                     </div>
                     <div class="md:col-span-3">
                         <div class="schedule-card p-6">
                             <div class="flex justify-between items-start mb-3">
                                 <h3 class="text-xl font-bold uppercase text-white tracking-wider">Inauguration Ceremony
                                 </h3>
                                 <span
                                     class="px-3 py-1 bg-cyan-500/10 text-cyan-400 text-[9px] font-bold uppercase tracking-widest border border-cyan-500/20">Main
                                     Stage</span>
                             </div>
                             <p class="text-slate-400 text-sm leading-relaxed">Welcome speech, Kit distribution, and
                                 official
                                 opening ceremony with faculty and guests.</p>
                             <div class="mt-4 flex items-center gap-3 opacity-50">
                                 <div class="h-[1px] w-8 bg-cyan-500"></div>
                                 <span class="text-[9px] text-cyan-300 uppercase tracking-[0.3em]">Status:
                                     Ready_to_Launch</span>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>

             <!-- Slot 2: Programming Contest -->
             <div class="timeline-item mb-12 group">
                 <div class="timeline-dot"></div>
                 <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                     <div class="md:col-span-1">
                         <span class="heading-font text-cyan-400 text-xl font-bold">10:00 AM</span>
                         <p class="text-[10px] text-slate-500 font-mono">01:00 PM (180 Min)</p>
                     </div>
                     <div class="md:col-span-3">
                         <div class="schedule-card p-6">
                             <div class="flex justify-between items-start mb-3">
                                 <h3 class="text-xl font-bold uppercase text-white tracking-wider">Inter-University
                                     Programming Contest</h3>
                                 <span
                                     class="px-3 py-1 bg-red-500/10 text-red-500 text-[9px] font-bold uppercase tracking-widest border border-red-500/20">Lab
                                     Arena</span>
                             </div>
                             <p class="text-slate-400 text-sm leading-relaxed">The ultimate brain battle. Teams will solve
                                 complex problems to secure their spot on the leaderboard.</p>
                             <div class="mt-4 flex items-center gap-3">
                                 <div class="h-[1px] w-8 bg-red-500"></div>
                                 <span class="text-[9px] text-red-400 uppercase tracking-[0.3em]">Critical Protocol:
                                     Competitive_CP</span>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>

             <!-- Slot 3: Lunch Break -->
             <div class="timeline-item mb-12 group">
                 <div class="timeline-dot"></div>
                 <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                     <div class="md:col-span-1 text-slate-500">
                         <span class="heading-font text-xl font-bold">01:00 PM</span>
                         <p class="text-[10px] font-mono">02:30 PM</p>
                     </div>
                     <div class="md:col-span-3">
                         <div class="border border-slate-800 p-4 rounded-lg flex items-center gap-4 bg-slate-900/30">
                             <div class="p-3 bg-slate-800 rounded text-cyan-500">
                                 <i class="fa-solid fa-utensils"></i>
                             </div>
                             <div>
                                 <h4 class="text-sm font-bold uppercase tracking-widest">Lunch & Prayer Break</h4>
                                 <p class="text-[10px] text-slate-500">System maintenance and refueling session.</p>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>

             <!-- Slot 4: Prize Giving -->
             <div class="timeline-item group">
                 <div class="timeline-dot"></div>
                 <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-start">
                     <div class="md:col-span-1">
                         <span class="heading-font text-cyan-400 text-xl font-bold">04:30 PM</span>
                         <p class="text-[10px] text-slate-500 font-mono">End of Day 01</p>
                     </div>
                     <div class="md:col-span-3">
                         <div class="schedule-card p-6">
                             <div class="flex justify-between items-start mb-3">
                                 <h3 class="text-xl font-bold uppercase text-white tracking-wider">Grand Prize Giving</h3>
                                 <span
                                     class="px-3 py-1 bg-yellow-500/10 text-yellow-500 text-[9px] font-bold uppercase tracking-widest border border-yellow-500/20">Auditorium</span>
                             </div>
                             <p class="text-slate-400 text-sm leading-relaxed">Rewarding the brilliant minds and closing the
                                 first day of our tech festival.</p>
                         </div>
                     </div>
                 </div>
             </div>

         </div>
     </section>
 @endsection
