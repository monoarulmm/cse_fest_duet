@extends('layouts.app')

@section('content')
    @php
        $settings = \App\Models\Setting::first();
    @endphp

    <section class="min-h-screen py-20 container mx-auto px-6 relative overflow-hidden">
        {{-- Background Decorative Elements --}}
        <div class="absolute top-0 right-0 -z-10 w-96 h-96 bg-cyan-500/10 blur-[120px] rounded-full"></div>
        <div class="absolute bottom-0 left-0 -z-10 w-96 h-96 bg-purple-500/10 blur-[120px] rounded-full"></div>

        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-6xl font-black uppercase tracking-tighter text-inherit mb-4">
                Get In <span class="text-cyan-500">Touch</span>
            </h2>
            <div class="h-1.5 w-24 bg-cyan-500 mx-auto rounded-full shadow-[0_0_15px_rgba(34,211,238,0.5)] mb-6"></div>
            <p class="text-slate-400 font-mono tracking-widest uppercase text-xs md:text-sm">
                System.Status: Online | Data Protocol: Secure
            </p>
        </div>

        <div class="max-w-5xl mx-auto">
            {{-- Contact Information Grid --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">

                {{-- Location --}}
                <div class="contact-info-card group">
                    <div class="info-icon"><i class="fas fa-map-marker-alt"></i></div>
                    <div>
                        <h4>Location</h4>
                        <p>{{ $settings->address ?? 'DUET, Gazipur-1707, Bangladesh' }}</p>
                    </div>
                </div>

                {{-- Official Mail --}}
                <div class="contact-info-card group">
                    <div class="info-icon"><i class="fas fa-envelope-open-text"></i></div>
                    <div>
                        <h4>Official Mail</h4>
                        <p>{{ $settings->email ?? 'support@csefest.com' }}</p>
                    </div>
                </div>

                {{-- Primary Helpline --}}
                <div class="contact-info-card group">
                    <div class="info-icon"><i class="fas fa-phone-volume"></i></div>
                    <div>
                        <h4>Primary Helpline</h4>
                        <p>{{ $settings->phone_primary ?? '+880 XXXX-XXXXXX' }}</p>
                    </div>
                </div>

                {{-- Secondary Helpline --}}
                <div class="contact-info-card group">
                    <div class="info-icon"><i class="fas fa-headset"></i></div>
                    <div>
                        <h4>Emergency Contact</h4>
                        <p>{{ $settings->phone_secondary ?? '+880 XXXX-XXXXXX' }}</p>
                    </div>
                </div>

            </div>

            {{-- Social Connectivity Portals --}}
            <div class="p-10 bg-slate-900/40 border border-slate-800 rounded-[3rem] backdrop-blur-xl text-center">
                <p class="text-cyan-500 font-black text-[10px] uppercase tracking-[0.4em] mb-8">Establish Connection Via
                    Social Portals</p>
                <div class="flex flex-wrap justify-center gap-6">

                    @if ($settings->fb_link)
                        <a href="{{ $settings->fb_link }}" target="_blank" class="social-link-wrapper group">
                            <div class="social-icon-box"><i class="fab fa-facebook-f"></i></div>
                            <span class="social-label">Facebook</span>
                        </a>
                    @endif

                    @if ($settings->youtube_link)
                        <a href="{{ $settings->youtube_link }}" target="_blank" class="social-link-wrapper group">
                            <div class="social-icon-box"><i class="fab fa-youtube"></i></div>
                            <span class="social-label">YouTube</span>
                        </a>
                    @endif

                    @if ($settings->whatsapp_link)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $settings->whatsapp_link) }}" target="_blank"
                            class="social-link-wrapper group">
                            <div class="social-icon-box"><i class="fab fa-whatsapp"></i></div>
                            <span class="social-label">WhatsApp</span>
                        </a>
                    @endif

                    {{-- Default Links if needed --}}
                    <a href="#" class="social-link-wrapper group">
                        <div class="social-icon-box"><i class="fab fa-linkedin-in"></i></div>
                        <span class="social-label">LinkedIn</span>
                    </a>
                </div>
            </div>

            {{-- Back to Home --}}
            <div class="mt-16 text-center">
                <a href="/"
                    class="text-slate-500 hover:text-cyan-400 transition-colors uppercase text-[10px] font-bold tracking-widest">
                    <i class="fa-solid fa-arrow-left mr-2"></i> Return to Main Terminal
                </a>
            </div>
        </div>
    </section>

    <style>
        /* Base Card Styling */
        .contact-info-card {
            @apply flex items-center p-8 bg-slate-900/40 border border-slate-800 rounded-[2.5rem] transition-all duration-500 hover:bg-slate-800/80 hover:border-cyan-500/30 hover:-translate-y-1;
        }

        .info-icon {
            @apply flex items-center justify-center min-w-[4rem] min-h-[4rem] bg-cyan-500/10 text-cyan-400 text-2xl rounded-2xl mr-6 transition-all group-hover:scale-110 group-hover:rotate-6 group-hover:bg-cyan-500 group-hover:text-slate-900;
        }

        .contact-info-card h4 {
            @apply text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] mb-1;
        }

        .contact-info-card p {
            @apply text-inherit font-bold text-base md:text-lg;
        }

        /* Social Link Styling */
        .social-link-wrapper {
            @apply flex flex-col items-center gap-3 transition-all hover:-translate-y-2;
        }

        .social-icon-box {
            @apply w-16 h-16 flex items-center justify-center rounded-2xl bg-slate-800 text-slate-400 border border-slate-700 transition-all group-hover:bg-cyan-500 group-hover:text-slate-900 group-hover:shadow-[0_10px_30px_rgba(34, 211, 238, 0.3)] group-hover:border-transparent text-2xl;
        }

        .social-label {
            @apply text-[9px] font-bold uppercase tracking-widest text-slate-500 group-hover:text-cyan-400 transition-colors;
        }
    </style>
@endsection
