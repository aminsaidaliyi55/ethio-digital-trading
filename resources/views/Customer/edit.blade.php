@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Customer</h1>

    <form action="{{ route('customer.update', $customer->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $customer->name) }}" required>
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $customer->email) }}" required>
        </div>

        <!-- Additional fields as needed -->
        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $customer->phone) }}">
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <input type="text" name="address" class="form-control" value="{{ old('address', $customer->address) }}">
        </div>
        <div class="form-group">
            <label for="city">City</label>
            <input type="text" name="city" class="form-control" value="{{ old('city', $customer->city) }}" required>
        </div>
        <div class="form-group">
            <label for="state">State</label>
            <input type="text" name="state" class="form-control" value="{{ old('state', $customer->state) }}" required>
        </div>
        <div class="form-group">
            <label for="postal_code">Postal Code</label>
            <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code', $customer->postal_code) }}" required>
        </div>
        <div class="form-group">
            <label for="country">Country</label>
            <input type="text" name="country" class="form-control" value="{{ old('country', $customer->country) }}" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Customer</button>
    </form>
</div>
@endsection
