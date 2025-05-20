@extends('layouts.app')

@section('content')
<div class="container">
    <h4>Enter OTP Code</h4>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}">
        @csrf

        <div class="mb-3">
            <label for="otp_code">OTP Code</label>
            <input type="text" class="form-control @error('otp_code') is-invalid @enderror" name="otp_code" required>

            @error('otp_code')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Verify</button>
    </form>
</div>
@endsection
