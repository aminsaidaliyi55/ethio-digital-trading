@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Supplier</h1>

    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary mb-3">Back to Supplier List</a>

    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Supplier Name</label>
            <input type="text" class="form-control" name="name" id="name" required>
        </div>
        <button type="submit" class="btn btn-success">Create Supplier</button>
    </form>
</div>
@endsection
