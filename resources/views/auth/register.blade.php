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
                <!-- Header Section -->
                <div class="text-center mb-10">
                    <h2
                        class="heading-font text-3xl md:text-4xl font-black tracking-tighter  text-white-900 dark:text-state-400 uppercase italic">
                        INITIALIZE <span class="text-cyan-500">IDENTITY</span>
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 text-[10px] mt-2 tracking-[0.4em] uppercase">
                        Create your unique profile for DUET CSE FEST
                    </p>
                </div>
                <!-- Registration Card -->
                <div class="glass-nav p-8 md:p-10 rounded-3xl border border-cyan-500/10 relative">
                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-6">
                            <!-- Full Name -->
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-cyan-400 uppercase tracking-widest mb-2 ml-1">Full
                                    Name</label>
                                <input type="text" name="name" required
                                    class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-cyan-500 transition-all">
                            </div>
                            <!-- Username -->
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-cyan-400 uppercase tracking-widest mb-2 ml-1">Handle
                                    (Active Phone)</label>
                                <input type="number" name="phone" required
                                    class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-cyan-500 transition-all">
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="mb-6">
                            <label
                                class="block text-[10px] font-bold text-cyan-400 uppercase tracking-widest mb-2 ml-1">Communication
                                Link (Email)</label>
                            <input type="email" name="email" required
                                class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-cyan-500 transition-all">
                        </div>


                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-8">
                            <!-- Password -->
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-cyan-400 uppercase tracking-widest mb-2 ml-1">Access
                                    Key</label>
                                <input type="password" name="password" required
                                    class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-cyan-500 transition-all">
                            </div>
                            <!-- Confirm Password -->
                            <div>
                                <label
                                    class="block text-[10px] font-bold text-cyan-400 uppercase tracking-widest mb-2 ml-1">Verify
                                    Key</label>
                                <input type="password" name="password_confirmation" required
                                    class="w-full bg-slate-950/50 border border-slate-800 rounded-xl py-3 px-4 text-white focus:outline-none focus:border-cyan-500 transition-all">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="cyber-btn w-full py-4 text-xs font-black uppercase tracking-[0.3em] flex items-center justify-center gap-3">
                            Join the Grid <i class="fa-solid fa-user-plus text-[10px]"></i>
                        </button>
                    </form>

                    <div class="mt-8 pt-6 border-t border-white/5 text-center">
                        <p class="text-slate-500 text-xs">Already have an identity? <a href="{{ url('login') }}"
                                class="text-cyan-400 font-bold ml-1">Access Terminal</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
