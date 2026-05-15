<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice | CSE CARNIVAL</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

        * {
            font-family: 'Inter', sans-serif;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #invoice-section, #invoice-section * {
                visibility: visible;
            }
            #invoice-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 20px;
            }
            .no-print {
                display: none !important;
            }
            .print-only {
                display: block !important;
            }
            .invoice-card {
                box-shadow: none !important;
                border: 1px solid #ddd !important;
            }
        }

        .print-only {
            display: none;
        }

        .success-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .failed-gradient {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        }

        .pending-gradient {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }

        .animate-pulse-slow {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-900 via-purple-900 to-gray-900 min-h-screen">

<div id="invoice-section" class="container mx-auto px-4 py-8 max-w-5xl">

    {{-- Status Banner --}}
    @if($payment_status == 'success')
        <div class="mb-6 bg-green-500/20 border-l-4 border-green-500 rounded-r-xl p-4 no-print">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-2xl mr-3"></i>
                <div>
                    <h3 class="text-green-400 font-bold">Payment Successful!</h3>
                    <p class="text-green-300 text-sm">{{ $message }}</p>
                </div>
            </div>
        </div>
    @elseif($payment_status == 'failed')
        <div class="mb-6 bg-red-500/20 border-l-4 border-red-500 rounded-r-xl p-4 no-print">
            <div class="flex items-center">
                <i class="fas fa-times-circle text-red-500 text-2xl mr-3"></i>
                <div>
                    <h3 class="text-red-400 font-bold">Payment Failed!</h3>
                    <p class="text-red-300 text-sm">{{ $message }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="mb-6 bg-yellow-500/20 border-l-4 border-yellow-500 rounded-r-xl p-4 no-print">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-2xl mr-3"></i>
                <div>
                    <h3 class="text-yellow-400 font-bold">Verification Error!</h3>
                    <p class="text-yellow-300 text-sm">{{ $message }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Invoice Card --}}
    <div class="bg-white/10 backdrop-blur-lg rounded-3xl shadow-2xl overflow-hidden border border-white/20">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-purple-600/30 to-pink-600/30 p-6 md:p-8 border-b border-white/20">
            <div class="flex justify-between items-start flex-wrap gap-4">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-bold uppercase mb-4
                            {{ $payment_status == 'success' ? 'bg-green-500 text-white' : ($payment_status == 'failed' ? 'bg-red-500 text-white' : 'bg-yellow-500 text-white') }}">
                        <i class="fas {{ $payment_status == 'success' ? 'fa-check-circle' : ($payment_status == 'failed' ? 'fa-times-circle' : 'fa-exclamation-triangle') }}"></i>
                        {{ $payment_status == 'success' ? 'Payment Successful' : ($payment_status == 'failed' ? 'Payment Failed' : 'Verification Error') }}
                    </div>
                    <h1 class="text-3xl md:text-5xl font-black text-white mb-2">Payment Invoice</h1>
                    <p class="text-purple-200 text-sm">
                        {{ $payment_status == 'success' ? 'Thank you for your registration. Your payment has been confirmed.' : 'Payment verification status' }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-5xl mb-2">
                        <i class="fas fa-receipt text-purple-300"></i>
                    </div>
                    <p class="text-[10px] text-purple-300 uppercase tracking-wider">Invoice Number</p>
                    <p class="text-purple-300 font-bold text-sm">
                        INV-{{ date('Ymd') }}-{{ $registration->id ?? '000' }}
                    </p>
                </div>
            </div>
        </div>

        {{-- Body --}}
        <div class="p-6 md:p-8">

            {{-- Status Badge --}}
            <div class="text-center mb-8">
                <div class="inline-block">
                    @if($payment_status == 'success')
                        <div class="bg-green-500/20 rounded-full p-4 mb-2">
                            <i class="fas fa-check-circle text-green-400 text-6xl"></i>
                        </div>
                        <h2 class="text-green-400 text-2xl font-bold">Payment Confirmed</h2>
                    @elseif($payment_status == 'failed')
                        <div class="bg-red-500/20 rounded-full p-4 mb-2">
                            <i class="fas fa-times-circle text-red-400 text-6xl"></i>
                        </div>
                        <h2 class="text-red-400 text-2xl font-bold">Payment Failed</h2>
                    @else
                        <div class="bg-yellow-500/20 rounded-full p-4 mb-2">
                            <i class="fas fa-exclamation-triangle text-yellow-400 text-6xl"></i>
                        </div>
                        <h2 class="text-yellow-400 text-2xl font-bold">Verification Error</h2>
                    @endif
                </div>
            </div>

            {{-- Registration Information --}}
            @if($registration)
                <div class="mb-8">
                    <h3 class="text-purple-300 text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-user-check"></i> Registration Information
                    </h3>
                    <div class="bg-black/30 rounded-2xl p-5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-purple-300 text-[10px] uppercase mb-1">Registration ID</p>
                                <p class="text-white font-bold text-lg">#{{ $registration->id }}</p>
                            </div>
                            <div>
                                <p class="text-purple-300 text-[10px] uppercase mb-1">Registration Date</p>
                                <p class="text-white font-bold">{{ $registration->created_at->format('d M, Y h:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-purple-300 text-[10px] uppercase mb-1">Event Name</p>
                                <p class="text-white font-semibold">{{ $registration->event->name ?? 'CSE Carnival 2026' }}</p>
                            </div>
                            <div>
                                <p class="text-purple-300 text-[10px] uppercase mb-1">Event Category</p>
                                <p class="text-white font-semibold">{{ $registration->event->category ?? 'Programming Contest' }}</p>
                            </div>
                            <div>
                                <p class="text-purple-300 text-[10px] uppercase mb-1">Team Name</p>
                                <p class="text-white font-semibold">{{ $registration->team->team_name ?? 'Individual Participant' }}</p>
                            </div>
                            <div>
                                <p class="text-purple-300 text-[10px] uppercase mb-1">University</p>
                                <p class="text-white font-semibold">{{ $registration->team->university_name ?? $registration->student->university ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Student Information --}}
                @if($registration->student)
                    <div class="mb-8">
                        <h3 class="text-purple-300 text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2">
                            <i class="fas fa-user-graduate"></i> Student Information
                        </h3>
                        <div class="bg-black/30 rounded-2xl p-5">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-purple-300 text-[10px] uppercase mb-1">Full Name</p>
                                    <p class="text-white font-semibold">{{ $registration->student->name ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-300 text-[10px] uppercase mb-1">Student ID</p>
                                    <p class="text-white font-semibold">{{ $registration->student->student_id ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-300 text-[10px] uppercase mb-1">Email Address</p>
                                    <p class="text-white font-semibold">{{ $registration->student->email ?? 'N/A' }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-300 text-[10px] uppercase mb-1">Phone Number</p>
                                    <p class="text-white font-semibold">{{ $registration->student->phone ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Payment Details --}}
                <div class="mb-8">
                    <h3 class="text-purple-300 text-xs font-bold uppercase tracking-wider mb-4 flex items-center gap-2">
                        <i class="fas fa-credit-card"></i> Payment Details
                    </h3>
                    <div class="bg-black/30 rounded-2xl p-5">
                        @if($transaction && $payment_status == 'success')
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-purple-300 text-[10px] uppercase mb-1">Transaction ID</p>
                                    <p class="text-white font-mono text-sm">{{ $transaction->transaction_id }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-300 text-[10px] uppercase mb-1">Payment Method</p>
                                    <p class="text-white font-semibold">{{ ucfirst($transaction->payment_method) }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-300 text-[10px] uppercase mb-1">Amount Paid</p>
                                    <p class="text-green-400 font-bold text-2xl">{{ number_format($transaction->amount) }} {{ $transaction->currency }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-300 text-[10px] uppercase mb-1">Payment Status</p>
                                    <p class="text-green-400 font-semibold">{{ $transaction->status }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-300 text-[10px] uppercase mb-1">Payment Date</p>
                                    <p class="text-white">{{ $transaction->created_at->format('d M, Y h:i A') }}</p>
                                </div>
                                <div>
                                    <p class="text-purple-300 text-[10px] uppercase mb-1">Currency</p>
                                    <p class="text-white">{{ $transaction->currency }}</p>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-6">
                                <i class="fas fa-exclamation-circle text-red-400 text-4xl mb-3"></i>
                                <p class="text-red-400 font-semibold">Payment information not available</p>
                                <p class="text-gray-400 text-sm mt-2">Please contact support for assistance</p>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="text-center py-12">
                    <i class="fas fa-user-slash text-gray-500 text-6xl mb-4"></i>
                    <h3 class="text-gray-400 text-xl font-bold">Registration Not Found</h3>
                    <p class="text-gray-500 mt-2">Unable to find registration information</p>
                </div>
            @endif

            {{-- Amount Summary for Success --}}
            @if($payment_status == 'success' && $transaction)
                <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 rounded-2xl p-6 mb-8 border border-green-500/30">
                    <div class="text-center">
                        <p class="text-green-300 text-xs uppercase tracking-wider mb-2">Total Amount Paid</p>
                        <p class="text-green-400 text-5xl font-black">{{ number_format($transaction->amount) }} {{ $transaction->currency }}</p>
                        <p class="text-green-300/70 text-xs mt-3">* This is an electronically generated invoice</p>
                    </div>
                </div>
            @elseif($payment_status == 'failed')
                <div class="bg-red-500/10 rounded-2xl p-6 mb-8 border border-red-500/30">
                    <div class="text-center">
                        <i class="fas fa-credit-card text-red-400 text-4xl mb-3"></i>
                        <p class="text-red-400 font-bold mb-2">Payment Not Completed</p>
                        <p class="text-red-300/70 text-sm">Please try again or use a different payment method</p>
                        <a href="" class="inline-block mt-4 px-6 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-bold transition-all no-print">
                            <i class="fas fa-redo-alt mr-2"></i> Retry Payment
                        </a>
                    </div>
                </div>
            @elseif($payment_status == 'error')
                <div class="bg-yellow-500/10 rounded-2xl p-6 mb-8 border border-yellow-500/30">
                    <div class="text-center">
                        <i class="fas fa-headset text-yellow-400 text-4xl mb-3"></i>
                        <p class="text-yellow-400 font-bold mb-2">Technical Issue Detected</p>
                        <p class="text-yellow-300/70 text-sm">Please contact our support team for assistance</p>
                        <button onclick="location.href='tel:+8801234567890'" class="inline-block mt-4 px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-xl text-sm font-bold transition-all no-print">
                            <i class="fas fa-phone-alt mr-2"></i> Contact Support
                        </button>
                    </div>
                </div>
            @endif

            {{-- Footer --}}
            <div class="text-center border-t border-white/10 pt-6 mt-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-[10px] text-purple-300/70 mb-4">
                    <div>
                        <i class="fas fa-envelope mr-1"></i> support@csecarnival.com
                    </div>
                    <div>
                        <i class="fas fa-phone-alt mr-1"></i> +880 1234 567890
                    </div>
                    <div>
                        <i class="fas fa-globe mr-1"></i> www.csecarnival.com
                    </div>
                </div>
                <p class="text-[9px] text-purple-300/50">© {{ date('Y') }} CSE Carnival. All rights reserved.</p>
            </div>

            {{-- Action Buttons --}}
            <div class="no-print flex flex-col sm:flex-row gap-4 mt-8 pt-6 border-t border-white/20">
                <button onclick="window.print()" class="flex-1 bg-white/10 hover:bg-white/20 py-3 rounded-xl text-white font-bold uppercase text-xs tracking-wider transition-all">
                    <i class="fas fa-print mr-2"></i> Print / Save PDF
                </button>
                <a href="{{ route('event.dashboard', $registration->event->slug ?? '#') }}"
                   class="flex-1 bg-purple-500/20 hover:bg-purple-500/30 text-center py-3 rounded-xl text-purple-300 font-bold uppercase text-xs tracking-wider transition-all">
                    <i class="fas fa-arrow-left mr-2"></i> Dashboard
                </a>
                <a href=""
                   class="flex-1 bg-gradient-to-r from-purple-500 to-pink-500 text-center py-3 rounded-xl text-white font-bold uppercase text-xs tracking-wider transition-all hover:scale-105">
                    <i class="fas fa-home mr-2"></i> Home
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
