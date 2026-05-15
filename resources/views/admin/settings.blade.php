@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-[#080a11] py-12 px-6 lg:px-16 text-white">

        {{-- Top Header Section --}}
        <div
            class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6 border-b border-slate-800 pb-8 mb-10">

            {{-- ১. বড় প্রফেশনাল টাইটেল --}}
            <div>
                <h2 class="text-4xl md:text-5xl font-black text-white uppercase tracking-tighter">
                    Settings <span class="text-cyan-400">Details</span>
                </h2>
                <div class="h-1 w-20 bg-cyan-500 mt-2 shadow-[0_0_10px_rgba(34,211,238,0.5)]"></div>
            </div>

            {{-- ২. জাভাস্ক্রিপ্ট ব্যাক বাটন (হিস্ট্রি অনুযায়ী কাজ করবে) --}}
            <button onclick="window.history.back()"
                class="flex items-center gap-3 px-6 py-3 bg-slate-900 border border-slate-700 rounded-xl hover:border-cyan-500 group transition-all">
                <i class="fa-solid fa-chevron-left text-cyan-500 group-hover:-translate-x-1 transition-transform"></i>
                <span class="text-xs font-bold text-slate-400 group-hover:text-white uppercase tracking-[0.2em]">
                    Back to previous
                </span>
            </button>

        </div>
        <div class="max-w-7xl mx-auto">
            {{-- Title & Success Alert --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-12">
                <div>
                    <h1 class="text-4xl font-black italic tracking-tighter uppercase">SYSTEM <span
                            class="text-yellow-500">CONTROL</span> PANEL</h1>
                    <p class="text-gray-500 text-xs font-bold tracking-[0.4em] mt-2 uppercase">Professional Global Config
                    </p>
                </div>

                @if (session('success'))
                    <div id="status-popup"
                        class="bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 px-8 py-4 rounded-3xl text-sm font-bold shadow-2xl animate-bounce">
                        ✨ {{ session('success') }}
                    </div>
                @endif
            </div>

            <form id="settings-form" action="{{ route('admin.settings.update') }}" method="POST"
                enctype="multipart/form-data" class="space-y-10">
                @csrf

                {{-- 01. Core Identity & Branding --}}
                <div class="bg-[#111421] border border-white/5 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 text-[12rem] font-black text-white/5 italic select-none">01</div>
                    <h2 class="text-xl font-black uppercase italic mb-10 flex items-center gap-4 relative z-10">
                        <span
                            class="w-10 h-10 bg-yellow-500 text-black rounded-2xl flex items-center justify-center not-italic">1</span>
                        Core Identity & Branding
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 relative z-10">
                        <div class="lg:col-span-3">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-3 block">Website
                                Official Title</label>
                            <input type="text" name="site_name" value="{{ old('site_name', $setting->site_name ?? '') }}"
                                class="w-full bg-[#080a11] border {{ $errors->has('site_name') ? 'border-red-500' : 'border-white/10' }} rounded-2xl px-6 py-5 focus:border-yellow-500 outline-none transition-all font-bold text-lg">
                        </div>

                        {{-- Logo Upload --}}
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Main
                                Logo</label>
                            <div
                                class="bg-[#080a11] p-8 rounded-[2rem] border border-white/5 flex flex-col items-center justify-center gap-6">
                                <div class="h-24 flex items-center justify-center overflow-hidden">
                                    @if (!empty($setting->logo))
                                        <img id="logo-preview" src="{{ asset('storage/' . $setting->logo) }}"
                                            class="max-h-full">
                                    @else
                                        <span id="logo-placeholder" class="text-gray-700 font-bold italic uppercase">No
                                            Logo</span>
                                        <img id="logo-preview" src="#" class="max-h-full hidden">
                                    @endif
                                </div>
                                <input type="file" name="logo" class="text-[10px] text-gray-500 cursor-pointer"
                                    onchange="previewImg(this, 'logo-preview', 'logo-placeholder')">
                            </div>
                        </div>

                        {{-- Favicon --}}
                        <div class="space-y-4">
                            <label
                                class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Favicon</label>
                            <div
                                class="bg-[#080a11] p-8 rounded-[2rem] border border-white/5 flex flex-col items-center justify-center gap-6">
                                <div class="h-24 flex items-center justify-center">
                                    @if (!empty($setting->favicon))
                                        <img id="favicon-preview" src="{{ asset('storage/' . $setting->favicon) }}"
                                            class="h-16 w-16 object-contain">
                                    @else
                                        <span id="favicon-placeholder" class="text-gray-700 font-bold italic uppercase">No
                                            Icon</span>
                                        <img id="favicon-preview" src="#" class="h-16 w-16 object-contain hidden">
                                    @endif
                                </div>
                                <input type="file" name="favicon" class="text-[10px] text-gray-500"
                                    onchange="previewImg(this, 'favicon-preview', 'favicon-placeholder')">
                            </div>
                        </div>

                        {{-- Main Banner 1 --}}
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Hero Banner
                                1</label>
                            <div
                                class="bg-[#080a11] p-8 rounded-[2rem] border border-white/5 flex flex-col items-center justify-center gap-6">
                                <div class="h-24 w-full overflow-hidden rounded-xl">
                                    @if (!empty($setting->main_banner1))
                                        <img id="banner1-preview" src="{{ asset('storage/' . $setting->main_banner1) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span id="banner1-placeholder"
                                            class="text-gray-700 font-bold italic uppercase">Empty</span>
                                        <img id="banner1-preview" src="#" class="w-full h-full object-cover hidden">
                                    @endif
                                </div>
                                <input type="file" name="main_banner1" class="text-[10px] text-gray-500"
                                    onchange="previewImg(this, 'banner1-preview', 'banner1-placeholder')">
                            </div>
                        </div>

                        {{-- Banner 2 --}}
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Hero Banner
                                2</label>
                            <div
                                class="bg-[#080a11] p-8 rounded-[2rem] border border-white/5 flex flex-col items-center justify-center gap-6">
                                <div class="h-24 w-full overflow-hidden rounded-xl">
                                    @if (!empty($setting->main_banner2))
                                        <img id="banner2-preview" src="{{ asset('storage/' . $setting->main_banner2) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span id="banner2-placeholder"
                                            class="text-gray-700 font-bold italic uppercase">Empty</span>
                                        <img id="banner2-preview" src="#" class="w-full h-full object-cover hidden">
                                    @endif
                                </div>
                                <input type="file" name="main_banner2" class="text-[10px] text-gray-500"
                                    onchange="previewImg(this, 'banner2-preview', 'banner2-placeholder')">
                            </div>
                        </div>

                        {{-- Banner 3 --}}
                        <div class="space-y-4">
                            <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest block">Hero Banner
                                3</label>
                            <div
                                class="bg-[#080a11] p-8 rounded-[2rem] border border-white/5 flex flex-col items-center justify-center gap-6">
                                <div class="h-24 w-full overflow-hidden rounded-xl">
                                    @if (!empty($setting->main_banner3))
                                        <img id="banner3-preview" src="{{ asset('storage/' . $setting->main_banner3) }}"
                                            class="w-full h-full object-cover">
                                    @else
                                        <span id="banner3-placeholder"
                                            class="text-gray-700 font-bold italic uppercase">Empty</span>
                                        <img id="banner3-preview" src="#"
                                            class="w-full h-full object-cover hidden">
                                    @endif
                                </div>
                                <input type="file" name="main_banner3" class="text-[10px] text-gray-500"
                                    onchange="previewImg(this, 'banner3-preview', 'banner3-placeholder')">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 02. Sponsor Management --}}
                <div class="bg-[#111421] border border-white/5 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 text-[12rem] font-black text-white/5 italic select-none">02
                    </div>
                    <h2 class="text-xl font-black uppercase italic mb-10 flex items-center gap-4 relative z-10">
                        <span
                            class="w-10 h-10 bg-indigo-600 text-white rounded-2xl flex items-center justify-center not-italic">2</span>
                        Sponsor Management
                    </h2>

                    <div class="relative group mb-12 z-10">
                        <input type="file" name="sponsor_banner[]" multiple
                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-20">
                        <div
                            class="bg-[#080a11] border-2 border-dashed border-white/10 rounded-[2.5rem] py-16 text-center group-hover:border-indigo-500 transition-all duration-500">
                            <div class="text-4xl mb-4">📤</div>
                            <p class="text-gray-500 font-bold uppercase text-xs tracking-widest">Click or Drag images to
                                upload sponsors</p>
                        </div>
                    </div>

                    @php
                        $sponsors = is_array($setting->sponsor_banner)
                            ? $setting->sponsor_banner
                            : json_decode($setting->sponsor_banner ?? '[]', true);
                    @endphp

                    @if (!empty($sponsors))
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 relative z-10">
                            @foreach ($sponsors as $index => $path)
                                <div
                                    class="group relative aspect-square rounded-[1.5rem] overflow-hidden border border-white/5 bg-[#080a11]">
                                    <img src="{{ asset('storage/' . $path) }}"
                                        class="w-full h-full object-cover opacity-70 group-hover:opacity-100 transition-all duration-700">
                                    <label class="absolute inset-0 flex items-center justify-center cursor-pointer">
                                        <input type="checkbox" name="remove_sponsors[]" value="{{ $index }}"
                                            class="peer hidden">
                                        <div
                                            class="absolute inset-0 bg-red-600/40 opacity-0 peer-checked:opacity-100 transition-all flex items-center justify-center backdrop-blur-sm">
                                            <span
                                                class="bg-white text-red-600 px-3 py-1 rounded-full text-[10px] font-black uppercase">To
                                                Delete</span>
                                        </div>
                                        <div
                                            class="absolute top-3 right-3 bg-red-500 text-white p-2 rounded-xl opacity-0 group-hover:opacity-100 peer-checked:bg-white peer-checked:text-red-600 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                </path>
                                            </svg>
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- 03. Global Connectivity --}}
                <div class="bg-[#111421] border border-white/5 rounded-[3rem] p-10 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 text-[12rem] font-black text-white/5 italic select-none">03
                    </div>
                    <h2 class="text-xl font-black uppercase italic mb-10 flex items-center gap-4 relative z-10">
                        <span
                            class="w-10 h-10 bg-emerald-600 text-white rounded-2xl flex items-center justify-center not-italic">3</span>
                        Global Connectivity
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Primary
                                Phone</label>
                            <input type="text" name="phone_primary"
                                value="{{ old('phone_primary', $setting->phone_primary ?? '') }}"
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-emerald-500 outline-none transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Secondary
                                Phone</label>
                            <input type="text" name="phone_secondary"
                                value="{{ old('phone_secondary', $setting->phone_secondary ?? '') }}"
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-white/20 outline-none transition-colors">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Official
                                Email</label>
                            <input type="email" name="email" value="{{ old('email', $setting->email ?? '') }}"
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-blue-500 outline-none transition-colors">
                        </div>
                        <div class="md:col-span-3 space-y-2">
                            <label class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Head Office
                                Address</label>
                            <textarea name="address" rows="3"
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-5 focus:border-yellow-500 outline-none transition-colors">{{ old('address', $setting->address ?? '') }}</textarea>
                        </div>

                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Facebook</label>
                            <input type="text" name="fb_link" value="{{ old('fb_link', $setting->fb_link ?? '') }}"
                                placeholder="https://..."
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-blue-500 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-red-500 uppercase tracking-widest">YouTube</label>
                            <input type="text" name="youtube_link"
                                value="{{ old('youtube_link', $setting->youtube_link ?? '') }}" placeholder="https://..."
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-red-500 outline-none">
                        </div>
                        <div class="space-y-2">
                            <label
                                class="text-[10px] font-black text-emerald-500 uppercase tracking-widest">WhatsApp</label>
                            <input type="text" name="whatsapp_link"
                                value="{{ old('whatsapp_link', $setting->whatsapp_link ?? '') }}" placeholder="+880..."
                                class="w-full bg-[#080a11] border border-white/10 rounded-2xl px-6 py-4 focus:border-emerald-500 outline-none">
                        </div>
                    </div>
                </div>

                {{-- Buttons --}}
                <div class="flex items-center justify-end gap-8 pt-10 pb-20">
                    <button type="reset"
                        class="text-[10px] font-black text-gray-600 uppercase tracking-[0.3em] hover:text-white transition-colors">Discard
                        Changes</button>
                    <button type="submit" id="submit-btn"
                        class="bg-yellow-500 text-black px-16 py-6 rounded-3xl font-black uppercase text-xs tracking-widest shadow-xl hover:scale-[1.05] active:scale-95 transition-all duration-300 flex items-center gap-3 group">
                        <span id="btn-text">Update Global Config</span>
                        <div id="loader"
                            class="hidden w-4 h-4 border-2 border-black border-t-transparent rounded-full animate-spin">
                        </div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ১. সাবমিট বাটন হ্যান্ডলিং
        const form = document.getElementById('settings-form');
        const btn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const loader = document.getElementById('loader');

        form.addEventListener('submit', () => {
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btnText.innerText = 'Updating...';
            loader.classList.remove('hidden');
        });

        // ২. স্ট্যাটাস পপআপ অটো হাইড
        const popup = document.getElementById('status-popup');
        if (popup) {
            setTimeout(() => {
                popup.style.transition = "all 0.8s ease";
                popup.style.opacity = "0";
                popup.style.transform = "translateY(-20px)";
                setTimeout(() => popup.remove(), 800);
            }, 4000);
        }

        // ৩. ইমেজ প্রিভিউ (অপ্টিমাইজড)
        function previewImg(input, previewId, placeholderId) {
            const preview = document.getElementById(previewId);
            const placeholder = document.getElementById(placeholderId);

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
