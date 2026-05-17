@extends('layouts.app')

@section('content')
    <div class="min-h-screen flex items-center justify-center bg-gray-50">
        <div class="bg-white rounded-2xl shadow-lg p-8 max-w-md w-full text-center border-t-4 border-red-500">

            {{-- Icon --}}
            <div class="flex justify-center mb-4">
                <div class="bg-red-100 rounded-full p-4">
                    <svg class="w-10 h-10 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
                    </svg>
                </div>
            </div>

            {{-- Title --}}
            <h2 class="text-2xl font-bold text-gray-800 mb-2 uppercase tracking-wide">
                @if ($error_type === 'gateway_error')
                    Payment Error
                @else
                    System Error
                @endif
            </h2>

            {{-- Message --}}
            <p class="text-gray-600 mb-6 text-base leading-relaxed">{{ $message }}</p>

            {{-- Buttons --}}
            <div class="space-y-3">
                @if ($registration)
                    <a href="{{ url()->previous() }}"
                        class="block w-full py-3 px-4 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition">
                        আবার চেষ্টা করুন
                    </a>
                    <a href="{{ route('event.dashboard', $slug) }}"
                        class="block w-full py-3 px-4 border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold rounded-xl transition">
                        Event Page এ ফিরে যান
                    </a>
                @else
                    <a href="{{ route('event.dashboard', $slug) }}"
                        class="block w-full py-3 px-4 bg-red-500 hover:bg-red-600 text-white font-semibold rounded-xl transition">
                        Go Back To Event Page
                    </a>
                @endif
            </div>

            {{-- Contact --}}
            <p class="mt-6 text-sm text-gray-400">
                কোনো সমস্যা হলে যোগাযোগ করুন:
                <a href="mailto:csefest2026@duet.ac.bd" class="text-red-500 font-medium hover:underline">
                    csefest2026@duet.ac.bd
                </a>
            </p>
        </div>
    </div>
@endsection
