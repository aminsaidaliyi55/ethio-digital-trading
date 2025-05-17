@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Supplier Details</h1>

    <div class="mb-3">
        <strong>ID:</strong> {{ $supplier->id }}
    </div>
    <div class="mb-3">
        <strong>Name:</strong> {{ $supplier->name }}
    </div>

    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning">Edit Supplier</a>
    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-danger">Delete Supplier</button>
    </form>
    
    <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">Back to Supplier List</a>
</div>
@endsection
