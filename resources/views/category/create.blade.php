@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Category</h1>
    
    <form action="{{ route('category.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Category Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="mb-3">
            <label for="tax" class="form-label">Tax (%)</label>
            <input type="number" class="form-control @error('tax') is-invalid @enderror" id="tax" name="tax" value="{{ old('tax') }}" required step="0.01" min="0">
            @error('tax')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        
        <button type="submit" class="btn btn-primary">Add Category</button>
        <a href="{{ route('category.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
