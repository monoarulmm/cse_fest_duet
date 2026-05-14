@extends('layouts.app')

@section('title', 'Gallery | CSE FEST 2026')

@section('custom_css')
    <style>
        /* --- Gallery Card Styles --- */
        .gallery-card {
            clip-path: polygon(10% 0, 100% 0, 100% 85%, 90% 100%, 0 100%, 0 15%);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            cursor: pointer;
            position: relative;
        }

        .gallery-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 0 30px rgba(34, 211, 238, 0.4);
        }

        .data-overlay {
            background: linear-gradient(0deg, rgba(2, 6, 23, 0.9) 0%, rgba(2, 6, 23, 0.1) 100%);
        }

        /* --- Fullscreen Modal Styles --- */
        #image-modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.98);
            z-index: 9999;
            backdrop-filter: blur(15px);
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        #modal-img {
            max-width: 95%;
            max-height: 75vh;
            border: 2px solid #22d3ee;
            box-shadow: 0 0 60px rgba(34, 211, 238, 0.3);
            clip-path: polygon(5% 0, 100% 0, 100% 95%, 95% 100%, 0 100%, 0 5%);
            animation: modalZoom 0.4s ease-out;
        }

        @keyframes modalZoom {
            from {
                opacity: 0;
                transform: scale(0.8);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .modal-close {
            position: absolute;
            top: 25px;
            right: 35px;
            color: #22d3ee;
            font-size: 45px;
            cursor: pointer;
            transition: transform 0.3s;
        }

        .modal-close:hover {
            transform: rotate(90deg);
            color: #fff;
        }

        .expand-icon-btn {
            background: rgba(34, 211, 238, 0.1);
            border: 1px solid #22d3ee;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #22d3ee;
            transition: all 0.3s;
        }

        .gallery-card:hover .expand-icon-btn {
            background: #22d3ee;
            color: #020617;
            box-shadow: 0 0 15px #22d3ee;
        }
    </style>
@endsection

@section('content')
    <div class="container mx-auto px-6 py-10">

        <!-- Page Header -->
        <div class="mb-16 border-l-4 border-cyan-500 pl-6">
            <h2 class="heading-font text-4xl md:text-6xl font-black uppercase tracking-tighter">
                Visual <span class="text-cyan-500">Archive</span>
            </h2>
            <p class="text-slate-500 font-mono text-sm mt-2 tracking-widest">>> ENCRYPTED_MEMORIES_2026.SYS</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @for ($i = 1; $i <= 15; $i++)
                <!-- Gallery Item {{ $i }} -->
                <div
                    class="group gallery-card relative bg-slate-900 border border-cyan-500/20 overflow-hidden h-80 rounded-lg">
                    <!-- Image Source from public/gallery -->
                    <img src="{{ asset('gallery/' . $i . '.jpg') }}"
                        class="w-full h-full object-cover clickable-img group-hover:scale-110 transition-transform duration-700"
                        alt="DUET CSE Carnival Image {{ $i }}">

                    <!-- Cyberpunk Overlay -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6">
                        <div class="flex justify-between items-end">
                            <div>
                                <h4 class="heading-font text-cyan-400 text-lg font-bold uppercase">
                                    @php
                                        $titles = [
                                            'IUPC Arena',
                                            'Cyber Hackathon',
                                            'Project Demo',
                                            'ICT Olympiad',
                                            'Deep Learning',
                                            'Tech Seminar',
                                        ];
                                        echo $titles[($i - 1) % count($titles)];
                                    @endphp
                                </h4>
                                <p class="text-[10px] text-slate-400 font-mono uppercase">Node:
                                    DUET_GALLERY_0{{ $i }}</p>
                            </div>

                            <!-- Expand Icon -->
                            <div class="expand-icon-btn bg-cyan-500/20 p-2 rounded border border-cyan-500/50">
                                <i class="fa-solid fa-expand text-cyan-400 text-sm"></i>
                            </div>
                        </div>

                        <!-- Animated Progress Bar -->
                        <div class="mt-4 h-[2px] w-full bg-cyan-900 overflow-hidden">
                            <div class="h-full bg-cyan-400 w-0 group-hover:w-full transition-all duration-700 ease-out">
                            </div>
                        </div>
                    </div>
                </div>
            @endfor
        </div>
    </div>

    <!-- MODAL STRUCTURE -->
    <div id="image-modal">
        <span class="modal-close">&times;</span>
        <div class="flex flex-col items-center w-full max-w-6xl">
            <img id="modal-img" src="" alt="Full View">
            <div class="mt-8 text-center">
                <h3 id="modal-caption" class="heading-font text-cyan-400 text-lg uppercase tracking-[0.3em] px-6"></h3>
                <div class="mt-3 h-1 w-32 bg-cyan-500 mx-auto opacity-40"></div>
            </div>
        </div>
    </div>

    <script>
        const modal = document.getElementById('image-modal');
        const modalImg = document.getElementById('modal-img');
        const modalCaption = document.getElementById('modal-caption');
        const closeBtn = document.querySelector('.modal-close');
        const cards = document.querySelectorAll('.gallery-card');

        // কার্ডে ক্লিক করলে মডাল ওপেন হবে
        cards.forEach(card => {
            card.addEventListener('click', () => {
                const img = card.querySelector('.clickable-img');
                modal.style.display = "flex";
                modalImg.src = img.src;
                modalCaption.innerText = img.alt;
                document.body.style.overflow = 'hidden';
            });
        });

        // ক্লোজ বাটন
        closeBtn.onclick = () => {
            modal.style.display = "none";
            document.body.style.overflow = 'auto';
        };

        // বাইরে ক্লিক করলে বন্ধ হবে
        modal.onclick = (e) => {
            if (e.target === modal) {
                modal.style.display = "none";
                document.body.style.overflow = 'auto';
            }
        };

        // Esc কী চাপলে বন্ধ হবে
        document.addEventListener('keydown', (e) => {
            if (e.key === "Escape") {
                modal.style.display = "none";
                document.body.style.overflow = 'auto';
            }
        });
    </script>
@endsection
