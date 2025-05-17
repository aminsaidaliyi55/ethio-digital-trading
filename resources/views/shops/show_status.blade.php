@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Shop Status</h1>
    <p><strong>Shop Name:</strong> {{ $shop->name }}</p>
    <p><strong>Status:</strong> {{ $shop->status }}</p>
    <a href="{{ route('shops.index') }}" class="btn btn-secondary">Back to Shops</a>
</div>
@endsection
