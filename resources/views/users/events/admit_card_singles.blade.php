<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admit Card - {{ $team->participant_id }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* প্রিন্ট করার সময় ব্যাকগ্রাউন্ড কালার এবং ডিজাইন ঠিক রাখার জন্য */
        @media print {
            body {
                background-color: #020617 !important;
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .no-print {
                display: none !important;
            }

            .print-container {
                padding: 0 !important;
                margin: 0 !important;
                display: flex !important;
                justify-content: center !important;
                align-items: center !important;
                height: 100vh;
            }

            #id-card-wrap {
                box-shadow: none !important;
                border: none !important;
            }
        }

        /* স্ক্রিন ভিউ এর জন্য কাস্টম ফন্ট বা স্টাইল */
        body {
            background-color: #020617;
        }
    </style>
</head>

<body class="antialiased">

    <div class="min-h-screen py-12 px-4 flex flex-col items-center justify-center print-container">
        <!-- লোডিং মেসেজ (শুধুমাত্র স্ক্রিনে দেখাবে) -->
        <div class="mb-6 text-center no-print">
            <p class="text-slate-500 font-bold animate-pulse text-xs uppercase tracking-widest">
                Generating Your Admit Card...
            </p>
        </div>

        <!-- মেইন অ্যাডমিট কার্ড -->
        <div id="id-card-wrap" class="max-w-[400px] w-full space-y-6">
            <div
                class="id-card-body relative bg-[#0f172a] border border-slate-700 rounded-[2rem] overflow-hidden shadow-2xl">

                <!-- হেডার অংশ -->
                <div class="pt-8 pb-4 text-center border-b border-white/5 bg-white/5">
                    <h1 class="text-white text-2xl font-black tracking-tighter uppercase leading-none">DUET CSE FEST
                    </h1>
                    <p class="text-slate-500 text-[10px] font-bold tracking-[0.3em] mt-2 uppercase">Official Entry Pass
                    </p>
                </div>

                <div class="p-8 text-center">
                    <!-- নাম এবং আইডি -->
                    <div class="mb-6">
                        <div class="inline-block bg-white px-4 py-1 mb-2">
                            <h2 class="text-black text-xl font-black uppercase">{{ $team->m1_name }}</h2>
                        </div>
                        <p class="text-cyan-500 text-xs font-black uppercase tracking-widest mt-1">
                            ID: {{ $team->participant_id }}
                        </p>
                    </div>

                    <!-- ইভেন্ট এবং প্রতিষ্ঠানের তথ্য -->
                    <div class="space-y-3 text-left bg-black/20 p-5 rounded-2xl border border-white/5">
                        <div class="flex justify-between items-center">
                            <span class="text-[9px] text-slate-500 uppercase font-black tracking-tighter">Event</span>
                            <span class="text-[10px] text-white font-bold uppercase text-right leading-tight">
                                {{ $event->name }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span
                                class="text-[9px] text-slate-500 uppercase font-black tracking-tighter">Institution</span>
                            <span class="text-[10px] text-white font-bold text-right leading-tight">
                                {{ \Illuminate\Support\Str::limit($team->university_name, 25) }}
                            </span>
                        </div>
                    </div>

                    <!-- কিউআর কোড -->
                    <div class="mt-8 flex flex-col items-center">
                        <div class="bg-white p-2 rounded-xl mb-3 shadow-lg">
                            {!! QrCode::size(100)->margin(1)->generate(url()->current()) !!}
                        </div>
                        <p class="text-[8px] text-slate-500 font-black uppercase">Scan to Verify Registration</p>
                    </div>
                </div>

                <!-- ফুটার -->
                <div class="bg-white/5 p-4 text-center border-t border-white/5">
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">
                        VENUE: DUET CAMPUS, GAZIPUR
                    </p>
                </div>
            </div>
        </div>

        <!-- প্রিন্ট করার পর ইউজারকে ব্যাক করানোর বাটন (শুধুমাত্র স্ক্রিনে দেখাবে) -->
        <div class="mt-8 no-print">
            <button onclick="window.history.back()"
                class="text-slate-500 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">
                ← Back to Dashboard
            </button>
        </div>
    </div>

    <!-- অটো প্রিন্ট স্ক্রিপ্ট -->
    <script>
        window.onload = function() {
            // ইমেজ এবং কিউআর কোড পুরোপুরি লোড হওয়ার জন্য ১ সেকেন্ড সময় দেওয়া হয়েছে
            setTimeout(function() {
                window.print();
            }, 1000);

            // প্রিন্ট ডায়ালগ বন্ধ করলে বা প্রিন্ট হয়ে গেলে অটোমেটিক আগের পেজে ফিরে যাবে
            window.onafterprint = function() {
                window.history.back();
            };
        };
    </script>

</body>

</html>
