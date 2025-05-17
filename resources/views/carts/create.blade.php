@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Product to Cart</h1>

    <form action="{{ route('cart.add', $product->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="product" class="form-label">Product</label>
            <input type="text" class="form-control" id="product" value="{{ $product->name }}" readonly>
        </div>

        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Add to Cart</button>
        <a href="{{ route('cart.index') }}" class="btn btn-secondary">Back to Cart</a>
    </form>
</div>
@endsection
