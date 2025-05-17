@extends('layouts.app') <!-- Extending a base layout -->

@section('content')
<div class="container" style="max-height: 1000px; overflow-y: auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
    <h1 class="mb-4">Create Region</h1>

    <!-- Display validation errors if any -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form to create a new region -->
    <form action="{{ route('regions.store') }}" method="POST">
    @csrf
    
    <div class="mb-3">
        <label for="name" class="form-label">Region Name</label>
        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
    </div>

    <div class="mb-3">
        <label for="federal_id" class="form-label">Select Federal</label>
        <select name="federal_id" id="federal_id" class="form-control" required >
            <option value="">-- Select Federal --</option>
            @foreach($federals as $federal)
                <option value="{{ $federal->id }}" {{ old('federal_id') == $federal->id ? 'selected' : '' }}>{{ $federal->name }}</option>
            @endforeach
        </select>
    </div>

    <button type="submit" class="btn btn-primary">Create Region</button>
    <a href="{{ route('regions.index') }}" class="btn btn-secondary">Back</a>
    </form>
</div>
@endsection
