@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Order Details</h2>

    <div class="card">
        <div class="card-header">
            <strong>Order ID: {{ $order->id }}</strong>
        </div>
        <div class="card-body">
            <p><strong>Customer:</strong> {{ $order->user->name }}</p>
            <p><strong>Product:</strong> {{ $order->product->name }}</p>
            <p><strong>Shop Owner:</strong> {{ $order->product->shop->owner->name ?? 'N/A' }}</p>
            <p><strong>Quantity:</strong> {{ $order->quantity }}</p>
            <p><strong>Total Price:</strong> {{ number_format($order->total_price, 2) }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>

            <hr>

            <a href="{{ route('orders.index') }}" class="btn btn-secondary btn-sm">Back to Orders</a>
            @if(auth()->user()->hasRole('admin')) <!-- Only show edit and delete options for admin -->
                <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-warning btn-sm">Edit</a>

                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?')">Delete</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
