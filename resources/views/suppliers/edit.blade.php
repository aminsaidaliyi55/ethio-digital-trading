@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Supplier</h1>

    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mb-3">Back to Supplier List</a>

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">Supplier Name</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $supplier->name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Supplier</button>
    </form>
</div>
@endsection
