@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Customer Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">{{ $customer->name }}</h5>
            <p><strong>Email:</strong> {{ $customer->email }}</p>
            <p><strong>Phone:</strong> {{ $customer->phone }}</p>
            <p><strong>Address:</strong> {{ $customer->address }}</p>
            <p><strong>City:</strong> {{ $customer->city }}</p>
            <p><strong>State:</strong> {{ $customer->state }}</p>
            <p><strong>Postal Code:</strong> {{ $customer->postal_code }}</p>
            <p><strong>Country:</strong> {{ $customer->country }}</p>
            <a href="{{ route('customer.index') }}" class="btn btn-secondary">Back to List</a>
            <a href="{{ route('customer.edit', $customer->id) }}" class="btn btn-warning">Edit</a>
        </div>
    </div>
</div>
@endsection
