@extends('layouts.app')

@section('content')
    <section class="relative min-h-screen flex items-center justify-center py-24 overflow-hidden">
        <div class="container mx-auto px-6 relative z-10">
            <div class="max-w-xl mx-auto">
                @if (session('success'))
                    <script>
                        Swal.fire({
                            icon: 'success',
                            title: 'সফল!',
                            text: "{{ session('success') }}",
                            confirmButtonText: 'ঠিক আছে'
                        });
                    </script>
                @endif

                @if (session('error'))
                    <script>
                        Swal.fire({
                            icon: 'error',
                            title: 'দুঃখিত!',
                            text: "{{ session('error') }}",
                            confirmButtonText: 'আবার চেষ্টা করুন'
                        });
                    </script>
                @endif

                {{-- ভ্যালিডেশন এরর দেখানোর জন্য (যেমন: ইমেইল অলরেডি আছে) --}}
                @if ($errors->any())
                    <script>
                        Swal.fire({
                            icon: 'warning',
                            title: 'ভুল হয়েছে!',
                            html: '{!! implode('<br>', $errors->all()) !!}',
                            confirmButtonText: 'ঠিক আছে'
                        });
                    </script>
                @endif
                <!-- Header -->
                 <!-- Branding/Logo Area -->
                <div class="text-center mb-10">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 bg-slate-900 border border-cyan-500/50 rounded-2xl rotate-45 mb-6 shadow-[0_0_20px_rgba(34,211,238,0.2)]">
                        <i class="fa-solid fa-shield-halved text-cyan-400 text-2xl -rotate-45"></i>
                    </div>
                    <h2 class="heading-font text-3xl font-black tracking-tighter  uppercase italic">
                        SYSTEM <span class="text-cyan-400">ACCESS</span>
                    </h2>
                    <p class="text-slate-500 text-xs mt-2 tracking-widest uppercase">Authorized Personnel Only</p>
                </div>

                <!-- Login Card -->
                <div class="glass-nav p-8 md:p-10 rounded-3xl border border-white/10 relative overflow-hidden">
                    <!-- Inner Glow -->
                    <div class="absolute -top-24 -left-24 w-48 h-48 bg-cyan-500/10 rounded-full blur-3xl"></div>

                    <form action="{{ route('login') }}" method="POST" class="relative z-10">
                        @csrf

                        <!-- Email Field -->
                        <div class="mb-6">
                            <label class="block text-[10px] font-bold text-cyan-400 uppercase tracking-[0.2em] mb-2 ml-1">
                                Terminal ID (Email or Phone)
                            </label>
                            <div class="relative group">
                                <i
                                    class="fa-solid fa-user-shield absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-cyan-400 transition-colors"></i>

                                {{-- name="login" এবং type="text" করা হয়েছে --}}
                                <input type="text" name="login" value="{{ old('login') }}" required
                                    class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-4 pl-12 pr-4  focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-all placeholder:text-slate-700"
                                    placeholder="Email or Phone Number">
                            </div>
                            @error('login')
                                <p class="text-red-500 text-[10px] mt-2 ml-1 uppercase font-bold">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-2">
                            <label class="block text-[10px] font-bold text-cyan-400 uppercase tracking-[0.2em] mb-2 ml-1">
                                Access Key (Password)
                            </label>
                            <div class="relative group">
                                <i
                                    class="fa-solid fa-lock absolute left-4 top-1/2 -translate-y-1/2 text-slate-500 group-focus-within:text-cyan-400 transition-colors"></i>
                                <input type="password" name="password" required
                                    class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-4 pl-12 pr-4  focus:outline-none focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 transition-all placeholder:text-slate-700"
                                    placeholder="••••••••">
                            </div>
                        </div>



                        <!-- Remember & Forgot -->
                        <div class="flex items-center justify-between mb-8 px-1">
                            <label class="flex items-center gap-2 cursor-pointer group">
                                <input type="checkbox" class="hidden peer">
                                <div
                                    class="w-4 h-4 border border-slate-700 rounded bg-slate-900 peer-checked:bg-cyan-500 peer-checked:border-cyan-500 transition-all flex items-center justify-center">
                                    <i class="fa-solid fa-check text-[10px] text-slate-950"></i>
                                </div>
                                <span
                                    class="text-[11px] text-slate-500 group-hover:text-slate-300 transition-colors">Remember
                                    Me</span>
                            </label>
                            <!-- <a href="{{ route('password.request') }}"
                                class="text-[11px] text-cyan-400/70 hover:text-cyan-400 transition-colors">Recover Key?</a> -->
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="relative w-full group overflow-hidden rounded-xl h-14">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-cyan-500 to-blue-600 transition-transform group-hover:scale-105">
                            </div>
                            <span
                                class="relative z-10 heading-font font-black text-slate-950 uppercase tracking-[0.2em] flex items-center justify-center gap-3">
                                Execute Login <i class="fa-solid fa-bolt-lightning text-xs"></i>
                            </span>
                        </button>
                    </form>

                    <!-- Footer Links -->
                    <div class="mt-8 pt-6 border-t border-white/5 text-center">
                        <p class="text-slate-500 text-xs">
                            Don't have access?
                            <!-- <a href="{{ url('register') }}" class="text-cyan-400 font-bold hover:underline ml-1">Initialize
                                Account</a> -->
                        </p>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-8">
                    <a href="/"
                        class="text-slate-600 hover:text-cyan-400 transition-colors text-[10px] font-bold uppercase tracking-widest flex items-center justify-center gap-2">
                        <i class="fa-solid fa-arrow-left-long"></i> Return to Terminal
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection
