@extends('layouts.app') <!-- Extending a base layout -->

@section('content')
<div class="container">
    <h1 class="mb-4">Edit Federal</h1>

    @if(session('success')) <!-- Show success message if available -->
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('federals.update', $federal->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Federal Name</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $federal->name) }}" required>
            
            @error('name') <!-- Display validation error for name -->
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Update Federal</button>
            <a href="{{ route('federals.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection
