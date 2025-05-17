@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Cart Item Details</h1>

    <div class="card">
        <div class="card-header">
            Product: {{ $cartItem->product->name }}
        </div>
        <div class="card-body">
            <h5 class="card-title">Product Information</h5>
            <p class="card-text"><strong>Price:</strong> ${{ number_format($cartItem->product->price, 2) }}</p>
            <p class="card-text"><strong>Quantity:</strong> {{ $cartItem->quantity }}</p>
            <p class="card-text"><strong>Total Price:</strong> ${{ number_format($cartItem->quantity * $cartItem->product->price, 2) }}</p>
            <p class="card-text"><strong>Status:</strong> {{ ucfirst($cartItem->status) }}</p>
        </div>
        <div class="card-footer">
            <a href="{{ route('cart.index') }}" class="btn btn-secondary">Back to Cart</a>
            <a href="{{ route('cart.edit', $cartItem->id) }}" class="btn btn-primary">Edit Item</a>
        </div>
    </div>
</div>
@endsection
