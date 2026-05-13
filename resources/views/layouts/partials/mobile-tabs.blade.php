<div
    class="lg:hidden fixed bottom-0 left-0 z-50 w-full h-20 bg-white/95 backdrop-blur-lg border-t border-gray-100 px-2 pb-2 shadow-[0_-5px_15px_rgba(0,0,0,0.05)]">
    <div class="grid h-full grid-cols-5 items-center">
        <!-- Home -->
        <a href="/" class="flex flex-col items-center justify-center gap-1">
            <i class="fa-solid fa-house text-xl {{ request()->is('/') ? 'text-green-600' : 'text-slate-400' }}"></i>
            <span
                class="text-[10px] font-bold {{ request()->is('/') ? 'text-green-600' : 'text-slate-400' }}">Home</span>
        </a>

        <!-- Events -->
        <a href="#" class="flex flex-col items-center justify-center gap-1">
            <i class="fa-solid fa-code text-xl text-slate-400"></i>
            <span class="text-[10px] font-bold text-slate-400">Events</span>
        </a>

        <!-- Center Special Action -->
        <div class="flex justify-center -translate-y-6">
            <a href="#"
                class="w-14 h-14 bg-gradient-to-tr from-green-600 to-emerald-400 rounded-2xl rotate-45 flex items-center justify-center shadow-lg shadow-green-200 border-4 border-white">
                <i class="fa-solid fa-qrcode text-white text-xl -rotate-45"></i>
            </a>
        </div>

        <!-- Schedule -->
        <a href="#" class="flex flex-col items-center justify-center gap-1">
            <i class="fa-solid fa-calendar-days text-xl text-slate-400"></i>
            <span class="text-[10px] font-bold text-slate-400">Schedule</span>
        </a>

        <!-- Contact -->
        <a href="/contact" class="flex flex-col items-center justify-center gap-1">
            <i
                class="fa-solid fa-address-book text-xl {{ request()->is('contact') ? 'text-green-600' : 'text-slate-400' }}"></i>
            <span
                class="text-[10px] font-bold {{ request()->is('contact') ? 'text-green-600' : 'text-slate-400' }}">Contact</span>
        </a>
    </div>
</div>
