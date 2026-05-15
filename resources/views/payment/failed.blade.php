{{-- resources/views/payment/failed.blade.php --}}

@extends('layouts.app')

@section('title', 'Payment Failed')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow border-0">
                    <div class="card-header text-center py-4"
                        style="background: linear-gradient(135deg, #b91c1c, #ef4444); color: #fff;">
                        <div style="font-size: 3rem;">❌</div>
                        <h3 class="mt-2 mb-0">পেমেন্ট ব্যর্থ হয়েছে</h3>
                        @if (!empty($message))
                            <small>{{ $message }}</small>
                        @endif
                    </div>

                    <div class="card-body p-4 text-center">

                        @if (!empty($sp_code))
                            <p class="text-muted">Error Code: <code>{{ $sp_code }}</code></p>
                        @endif

                        @if ($registration ?? null)
                            <p class="mb-1">Order ID: <strong>{{ $registration->order_id }}</strong></p>
                            <p class="mb-3">নাম: {{ $registration->m1_name }}</p>
                        @endif

                        <p class="text-muted">
                            আবার পেমেন্ট করতে Event Dashboard এ যান অথবা আমাদের সাথে যোগাযোগ করুন।
                        </p>

                        <div class="mt-4 d-flex justify-content-center gap-3">
                            <a href="{{ route('event.dashboard', $slug ?? 'event') }}" class="btn btn-danger px-4">
                                আবার চেষ্টা করুন
                            </a>
                            <a href="{{ url('/') }}" class="btn btn-outline-secondary px-4">
                                হোমে যান
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
