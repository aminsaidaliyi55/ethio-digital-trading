@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Verify Your Email Address</h2>

 @if (session('message'))
    <div class="alert alert-success">
        {{ session('message') }}
    </div>
@endif

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button type="submit">Resend Verification Email</button>
</form>

</div>
@endsection
