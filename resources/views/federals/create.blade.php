@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add New Federal</h1>
    
    <form action="{{ route('federals.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">Federal Name</label>
            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
              @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Add Federal</button>
        <a href="{{ route('home') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
