@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Cart Item</h1>

    <form action="{{ route('cart.update', $cartItem->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="product" class="form-label">Product</label>
            <input type="text" class="form-control" id="product" value="{{ $cartItem->product->name }}" readonly>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $cartItem->quantity }}" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Cart</button>
        <a href="{{ route('cart.index') }}" class="btn btn-secondary">Back to Cart</a>
    </form>
</div>
@endsection
