@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4">Track Your Order</h2>

                        @if(session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <form action="{{ route('orders.track') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Order Number</label>
                                <input type="text" name="order_number" class="form-control form-control-lg"
                                    placeholder="e.g. ORD-123456" required>
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control form-control-lg"
                                    placeholder="Enter billing email" required>
                            </div>
                            <button type="submit" class="btn btn-dark w-100 btn-lg">Track Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection