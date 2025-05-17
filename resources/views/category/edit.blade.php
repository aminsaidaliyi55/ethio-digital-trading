@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">Edit Category</div>
    <div class="card-body">
        <form action="{{ route('category.update', $category->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Category Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="tax" class="form-label">Tax (%)</label>
                <input type="number" class="form-control @error('tax') is-invalid @enderror" id="tax" name="tax" value="{{ old('tax', $category->tax) }}" required step="0.01" min="0">
                @error('tax')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-primary">Update Category</button>
                <a href="{{ route('category.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
