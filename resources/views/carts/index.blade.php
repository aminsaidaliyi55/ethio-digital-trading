@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Your Cart</h1>

    @if ($carts->isEmpty())
        <p>Your cart is empty.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($carts as $cart)
                    <tr>
                        <td>{{ $cart->product->name }}</td>
                        <td>{{ $cart->quantity }}</td>
                        <td>
                          <div class="card-footer">
    <a href="{{ route('cart.index') }}" class="btn btn-secondary">Back to Cart</a>
    <a href="{{ route('cart.edit', $cartItem->id) }}" class="btn btn-primary">Edit Item</a>

    <form action="{{ route('cart.destroy', $cartItem->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to remove this item from the cart?')">Remove Item</button>
    </form>
</div>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
